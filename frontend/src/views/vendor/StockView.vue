<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h3 class="text-lg font-semibold text-gray-900">Gestion du Stock</h3>
      <p class="text-sm text-gray-600 mt-1">Suivez et gérez votre inventaire</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <StatsCard title="Total Articles" :value="stockStats.total_items || 0" color="blue" icon="📦" />
      <StatsCard title="Stock Insuffisant" :value="stockStats.low_stock_products || 0" color="yellow" icon="⚠️" />
      <StatsCard title="Rupture" :value="stockStats.out_of_stock_products || 0" color="red" icon="❌" />
      <StatsCard title="Valeur Stock" :value="'$' + (stockStats.inventory_value || 0)" color="green" icon="💰" />
    </div>

    <!-- Alerts -->
    <div v-if="alerts.low_stock > 0 || alerts.out_of_stock > 0" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
      <div class="flex">
        <div class="flex-shrink-0">
          <span class="text-2xl">⚠️</span>
        </div>
        <div class="ml-3">
          <p class="text-sm text-yellow-700">
            <strong>{{ alerts.low_stock }}</strong> produit(s) avec stock faible et
            <strong>{{ alerts.out_of_stock }}</strong> produit(s) en rupture de stock
          </p>
        </div>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input 
          v-model="filters.search" 
          type="text" 
          placeholder="Rechercher un produit..." 
          class="border rounded-lg px-3 py-2"
        />
        <select v-model="filters.stock_level" class="border rounded-lg px-3 py-2">
          <option value="">Tous les niveaux</option>
          <option value="low">Stock faible (< 5)</option>
          <option value="out">Rupture (0)</option>
          <option value="sufficient">Suffisant (>= 5)</option>
        </select>
        <button @click="loadStock" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Filtrer
        </button>
        <button @click="exportStock" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          📥 Exporter
        </button>
      </div>
    </div>

    <!-- Stock Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-500">
        Chargement...
      </div>
      <div v-else-if="products.length === 0" class="p-8 text-center text-gray-500">
        Aucun produit trouvé
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix Unitaire</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Valeur Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center mr-3">
                  📦
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ product.title }}</p>
                  <p class="text-xs text-gray-500">{{ product.id.substring(0, 8) }}</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ product.category }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              ${{ product.price }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center space-x-2">
                <span :class="getStockClass(product.stock)" class="px-3 py-1 rounded-full text-sm font-semibold">
                  {{ product.stock }}
                </span>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              ${{ (product.stock * product.price).toFixed(2) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(product.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ product.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
              <button @click="editStock(product)" class="text-blue-600 hover:text-blue-800">
                ✏️ Modifier
              </button>
              <button @click="adjustStock(product)" class="text-green-600 hover:text-green-800">
                ⚖️ Ajuster
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Edit Stock Modal -->
    <div v-if="showEditModal && selectedProduct" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
          Modifier le stock - {{ selectedProduct.title }}
        </h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actuel</label>
            <p class="text-3xl font-bold text-gray-900">{{ selectedProduct.stock }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nouveau stock</label>
            <input v-model.number="editForm.stock" type="number" class="w-full border rounded-lg px-3 py-2" min="0" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Raison (optionnel)</label>
            <select v-model="editForm.reason" class="w-full border rounded-lg px-3 py-2">
              <option value="">Sélectionnez une raison</option>
              <option value="restock">Réapprovisionnement</option>
              <option value="correction">Correction d'inventaire</option>
              <option value="damage">Dommage</option>
              <option value="return">Retour</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea v-model="editForm.notes" class="w-full border rounded-lg px-3 py-2 h-20"></textarea>
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button @click="showEditModal = false" class="flex-1 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-50">
            Annuler
          </button>
          <button @click="submitEditStock" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Mettre à jour
          </button>
        </div>
      </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div v-if="showAdjustModal && selectedProduct" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
          Ajuster le stock - {{ selectedProduct.title }}
        </h3>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock actuel</label>
            <p class="text-3xl font-bold text-gray-900">{{ selectedProduct.stock }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ajustement (+ ou -)</label>
            <input v-model.number="adjustForm.adjustment" type="number" class="w-full border rounded-lg px-3 py-2" placeholder="Ex: +5 ou -3" />
          </div>
          <div>
            <p class="text-sm text-gray-600">Nouveau stock: <strong>{{ selectedProduct.stock + (adjustForm.adjustment || 0) }}</strong></p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Raison</label>
            <select v-model="adjustForm.reason" class="w-full border rounded-lg px-3 py-2" required>
              <option value="">Sélectionnez une raison</option>
              <option value="restock">Réapprovisionnement</option>
              <option value="damage">Dommage</option>
              <option value="correction">Correction</option>
              <option value="return">Retour</option>
              <option value="other">Autre</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <textarea v-model="adjustForm.notes" class="w-full border rounded-lg px-3 py-2 h-20"></textarea>
          </div>
        </div>
        <div class="flex gap-3 mt-6">
          <button @click="showAdjustModal = false" class="flex-1 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-50">
            Annuler
          </button>
          <button @click="submitAdjustStock" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            Ajuster
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import vendorStockService from '@/services/vendorStockService'
import StatsCard from '@/components/vendor/StatsCard.vue'

const authStore = useAuthStore()

const products = ref([])
const loading = ref(false)
const stockStats = ref({})
const alerts = ref({ low_stock: 0, out_of_stock: 0 })
const showEditModal = ref(false)
const showAdjustModal = ref(false)
const selectedProduct = ref(null)

const filters = ref({
  search: '',
  stock_level: ''
})

const editForm = ref({
  stock: 0,
  reason: '',
  notes: ''
})

const adjustForm = ref({
  adjustment: 0,
  reason: '',
  notes: ''
})

onMounted(() => {
  loadStock()
  loadStatistics()
  loadAlerts()
})

const loadStock = async () => {
  loading.value = true
  try {
    const response = await vendorStockService.getStock({
      search: filters.value.search,
      stock_level: filters.value.stock_level
    })
    products.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading stock:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await vendorStockService.getStatistics()
    stockStats.value = response.data
  } catch (error) {
    console.error('Error loading statistics:', error)
  }
}

const loadAlerts = async () => {
  try {
    const response = await vendorStockService.getAlerts()
    alerts.value = response.data
  } catch (error) {
    console.error('Error loading alerts:', error)
  }
}

const editStock = (product) => {
  selectedProduct.value = product
  editForm.value = {
    stock: product.stock,
    reason: '',
    notes: ''
  }
  showEditModal.value = true
}

const submitEditStock = async () => {
  try {
    await vendorStockService.updateStock(selectedProduct.value.id, editForm.value)
    
    showEditModal.value = false
    await loadStock()
    await loadStatistics()
  } catch (error) {
    console.error('Error updating stock:', error)
  }
}

const adjustStock = (product) => {
  selectedProduct.value = product
  adjustForm.value = {
    adjustment: 0,
    reason: '',
    notes: ''
  }
  showAdjustModal.value = true
}

const submitAdjustStock = async () => {
  try {
    await vendorStockService.adjustStock(selectedProduct.value.id, adjustForm.value)
    
    showAdjustModal.value = false
    await loadStock()
    await loadStatistics()
    await loadAlerts()
  } catch (error) {
    console.error('Error adjusting stock:', error)
  }
}

const exportStock = async () => {
  try {
    const response = await vendorStockService.exportStock()
    const blob = response.data
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `stock-${new Date().toISOString().split('T')[0]}.csv`
    a.click()
  } catch (error) {
    console.error('Error exporting stock:', error)
  }
}

const getStockClass = (stock) => {
  if (stock === 0) return 'bg-red-100 text-red-800'
  if (stock < 5) return 'bg-yellow-100 text-yellow-800'
  return 'bg-green-100 text-green-800'
}

const getStatusClass = (status) => {
  if (status === 'active') return 'bg-green-100 text-green-800'
  if (status === 'inactive') return 'bg-gray-100 text-gray-800'
  return 'bg-red-100 text-red-800'
}
</script>
