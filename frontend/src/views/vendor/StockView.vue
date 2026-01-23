<template>
  <div class="min-h-screen bg-[#f6f3ee] p-4 md:p-6 font-serif text-[#3f3f36] space-y-8">

    <!-- Edit Stock Modal -->
    <div v-if="showEditModal && selectedProduct" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-[#d6cdbf]">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-serif text-[#4a3728]">Modifier le stock</h2>
            <button @click="showEditModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <p class="text-sm font-medium text-[#4a3728] mb-2">Produit</p>
            <p class="text-[#7a7465]">{{ selectedProduct.title }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Nouveau stock</label>
            <input v-model.number="editForm.stock" type="number" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Raison</label>
            <select v-model="editForm.reason" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
              <option value="">Sélectionnez une raison</option>
              <option value="restock">Réapprovisionnement</option>
              <option value="correction">Correction d'inventaire</option>
              <option value="damage">Produit endommagé</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Notes (optionnel)</label>
            <textarea v-model="editForm.notes" rows="2" placeholder="Ajoutez une note..." class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]"></textarea>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="submitEditStock" class="flex-1 px-4 py-2 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium">
            Confirmer
          </button>
          <button @click="showEditModal = false" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div v-if="showAdjustModal && selectedProduct" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-[#d6cdbf]">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-serif text-[#4a3728]">Ajuster le stock</h2>
            <button @click="showAdjustModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <p class="text-sm font-medium text-[#4a3728] mb-2">Produit</p>
            <p class="text-[#7a7465]">{{ selectedProduct.title }}</p>
            <p class="text-xs text-[#7a7465]">Stock actuel: {{ selectedProduct.stock }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Ajustement (+ ou -)</label>
            <input v-model.number="adjustForm.adjustment" type="number" placeholder="Ex: +5 ou -2" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Raison</label>
            <select v-model="adjustForm.reason" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
              <option value="">Sélectionnez une raison</option>
              <option value="restock">Réapprovisionnement</option>
              <option value="correction">Correction d'inventaire</option>
              <option value="damage">Produit endommagé</option>
              <option value="return">Retour client</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Notes (optionnel)</label>
            <textarea v-model="adjustForm.notes" rows="2" placeholder="Ajoutez une note..." class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]"></textarea>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="submitAdjustStock" class="flex-1 px-4 py-2 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium">
            Confirmer
          </button>
          <button @click="showAdjustModal = false" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Header -->
    <section class="border-b border-[#d6cdbf] pb-5">
      <h1 class="text-3xl font-bold text-[#4a3728]">Gestion du Stock</h1>
      <p class="text-sm uppercase tracking-wider text-[#6b7b4b] mt-1">
        Suivi et contrôle de l’inventaire
      </p>
    </section>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <StatsCard title="Total Articles" :value="stockStats.total_items || 0" />
      <StatsCard title="Stock Faible" :value="stockStats.low_stock_products || 0" />
      <StatsCard title="Rupture" :value="stockStats.out_of_stock_products || 0" />
      <StatsCard title="Valeur du Stock" :value="`${stockStats.inventory_value || 0} MAD`" />
    </div>

    <!-- Alerts -->
    <section
      v-if="alerts.low_stock > 0 || alerts.out_of_stock > 0"
      class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-4"
    >
      <p class="text-sm text-[#6b7b4b]">
        {{ alerts.low_stock }} produit(s) à stock faible —
        {{ alerts.out_of_stock }} en rupture
      </p>
    </section>

    <!-- Filters -->
    <section class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input
          v-model="filters.search"
          type="text"
          placeholder="Rechercher un produit"
          class="border border-[#d6cdbf] rounded-lg px-3 py-2 bg-white focus:ring-1 focus:ring-[#6b7b4b] focus:border-[#6b7b4b]"
        />

        <select
          v-model="filters.stock_level"
          class="border border-[#d6cdbf] rounded-lg px-3 py-2 bg-white"
        >
          <option value="">Tous les niveaux</option>
          <option value="low">Stock faible</option>
          <option value="out">Rupture</option>
          <option value="sufficient">Suffisant</option>
        </select>

        <button
          @click="loadStock"
          class="bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition"
        >
          Appliquer
        </button>
      </div>
    </section>

    <!-- Table -->
    <section class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-[#7a7465]">
        Chargement…
      </div>

      <div v-else-if="products.length === 0" class="p-8 text-center text-[#7a7465]">
        Aucun produit trouvé
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-[#f1ede6] border-b border-[#d6cdbf]">
            <tr>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Produit</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Catégorie</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Prix</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Stock</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Valeur</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Statut</th>
              <th class="px-5 py-3 text-left uppercase tracking-wider text-[#6b7b4b]">Action</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-[#e4dccf]">
            <tr v-for="product in products" :key="product.id" class="hover:bg-[#f4f1eb]">
              <td class="px-5 py-4 font-medium text-[#4a3728]">
                {{ product.title }}
                <p class="text-xs text-[#7a7465]">#{{ product.id.substring(0, 8) }}</p>
              </td>

              <td class="px-5 py-4 text-[#7a7465]">{{ product.category }}</td>
              <td class="px-5 py-4 font-semibold text-[#4a3728]">{{ product.price }} MAD</td>

              <td class="px-5 py-4">
                <span :class="getStockClass(product.stock)" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ product.stock }}
                </span>
              </td>

              <td class="px-5 py-4 font-semibold text-[#4a3728]">{{ (product.stock * product.price).toFixed(2) }} MAD</td>

              <td class="px-5 py-4">
                <span :class="getStatusClass(product.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                  {{ product.status }}
                </span>
              </td>

              <td class="px-5 py-4">
                <button @click="editStock(product)" class="text-[#6b8043] hover:underline font-medium mr-3">Modifier</button>
                <button @click="adjustStock(product)" class="text-[#bfa77a] hover:underline font-medium">Ajuster</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

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
