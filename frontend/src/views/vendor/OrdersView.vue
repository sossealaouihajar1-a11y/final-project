<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h3 class="text-lg font-semibold text-gray-900">Suivi des Commandes</h3>
      <p class="text-sm text-gray-600 mt-1">Gérez et suivez vos commandes</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
      <StatsCard title="Total" :value="orderStats.total_orders || 0" color="blue" icon="📋" />
      <StatsCard title="En attente" :value="orderStats.pending_orders || 0" color="yellow" icon="⏳" />
      <StatsCard title="Confirmées" :value="orderStats.confirmed_orders || 0" color="purple" icon="✓" />
      <StatsCard title="Expédiées" :value="orderStats.shipped_orders || 0" color="blue" icon="🚚" />
      <StatsCard title="Livrées" :value="orderStats.delivered_orders || 0" color="green" icon="✅" />
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input 
          v-model="filters.search" 
          type="text" 
          placeholder="Rechercher client..." 
          class="border rounded-lg px-3 py-2"
        />
        <select v-model="filters.status" class="border rounded-lg px-3 py-2">
          <option value="">Tous les statuts</option>
          <option value="pending">En attente</option>
          <option value="confirmed">Confirmée</option>
          <option value="shipped">Expédiée</option>
          <option value="delivered">Livrée</option>
          <option value="cancelled">Annulée</option>
        </select>
        <input 
          v-model="filters.date_from" 
          type="date" 
          class="border rounded-lg px-3 py-2"
        />
        <div class="flex gap-2">
          <button @click="loadOrders" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Filtrer
          </button>
          <button @click="exportOrders" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
            📥
          </button>
        </div>
      </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-500">
        Chargement...
      </div>
      <div v-else-if="orders.length === 0" class="p-8 text-center text-gray-500">
        Aucune commande trouvée
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID Commande</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produits</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              #{{ order.id.substring(0, 8) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div>
                <p class="text-sm font-medium text-gray-900">{{ order.user.name }}</p>
                <p class="text-xs text-gray-600">{{ order.user.email }}</p>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ formatDate(order.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ order.items.length }} article(s)
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
              {{ order.total_price }}€
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ order.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
              <button @click="viewOrder(order.id)" class="text-blue-600 hover:text-blue-800">
                👁️ Voir
              </button>
              <button @click="updateOrderStatus(order.id, order.status)" class="text-green-600 hover:text-green-800">
                ⬆️ Mettre à jour
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Order Details Modal -->
    <div v-if="showDetailsModal && selectedOrder" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Commande #{{ selectedOrder.id.substring(0, 8) }}</h3>
            <p class="text-sm text-gray-600">{{ formatDate(selectedOrder.created_at) }}</p>
          </div>
          <button @click="showDetailsModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="mb-4">
          <h4 class="font-semibold text-gray-900 mb-2">Client</h4>
          <p class="text-sm text-gray-600">{{ selectedOrder.user.name }}</p>
          <p class="text-sm text-gray-600">{{ selectedOrder.user.email }}</p>
          <p class="text-sm text-gray-600">{{ selectedOrder.user.phone }}</p>
        </div>

        <div class="mb-4">
          <h4 class="font-semibold text-gray-900 mb-2">Articles</h4>
          <div class="space-y-2">
            <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between p-2 bg-gray-50 rounded">
              <span class="text-sm text-gray-600">{{ item.product.title }}</span>
              <span class="text-sm font-medium text-gray-900">{{ item.quantity }}x {{ item.total_price }}€</span>
            </div>
          </div>
        </div>

        <div class="border-t pt-4">
          <div class="flex justify-between mb-2">
            <span class="text-sm text-gray-600">Statut</span>
            <span :class="getStatusClass(selectedOrder.status)" class="px-2 py-1 rounded text-xs font-semibold">
              {{ selectedOrder.status }}
            </span>
          </div>
          <div class="flex justify-between font-semibold text-gray-900">
            <span>Total</span>
            <span>{{ selectedOrder.total_price }}€</span>
          </div>
        </div>

        <button @click="showDetailsModal = false" class="mt-6 w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          Fermer
        </button>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div v-if="showStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mettre à jour le statut</h3>
        <select v-model="newStatus" class="w-full border rounded-lg px-3 py-2 mb-4">
          <option value="pending">En attente</option>
          <option value="confirmed">Confirmée</option>
          <option value="shipped">Expédiée</option>
          <option value="delivered">Livrée</option>
          <option value="cancelled">Annulée</option>
        </select>
        <textarea v-model="statusNotes" placeholder="Notes (optionnel)" class="w-full border rounded-lg px-3 py-2 h-20 mb-4"></textarea>
        <div class="flex gap-3">
          <button @click="showStatusModal = false" class="flex-1 px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-50">
            Annuler
          </button>
          <button @click="submitStatusUpdate" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Mettre à jour
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import vendorOrderService from '@/services/vendorOrderService'
import StatsCard from '@/components/vendor/StatsCard.vue'

const authStore = useAuthStore()

const orders = ref([])
const loading = ref(false)
const orderStats = ref({})
const showDetailsModal = ref(false)
const showStatusModal = ref(false)
const selectedOrder = ref(null)
const selectedOrderId = ref(null)
const newStatus = ref('')
const statusNotes = ref('')

const filters = ref({
  search: '',
  status: '',
  date_from: '',
  date_to: ''
})

onMounted(() => {
  loadOrders()
  loadStatistics()
})

const loadOrders = async () => {
  loading.value = true
  try {
    const response = await vendorOrderService.getAllOrders({
      search: filters.value.search,
      status: filters.value.status,
      date_from: filters.value.date_from,
      date_to: filters.value.date_to
    })
    orders.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading orders:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await vendorOrderService.getStatistics()
    orderStats.value = response.data
  } catch (error) {
    console.error('Error loading statistics:', error)
  }
}

const viewOrder = async (orderId) => {
  try {
    const response = await vendorOrderService.getOrder(orderId)
    selectedOrder.value = response.data
    showDetailsModal.value = true
  } catch (error) {
    console.error('Error loading order details:', error)
  }
}

const updateOrderStatus = (orderId, currentStatus) => {
  selectedOrderId.value = orderId
  newStatus.value = currentStatus
  statusNotes.value = ''
  showStatusModal.value = true
}

const submitStatusUpdate = async () => {
  try {
    await vendorOrderService.updateOrderStatus(selectedOrderId.value, {
      status: newStatus.value,
      notes: statusNotes.value
    })
    
    showStatusModal.value = false
    await loadOrders()
    await loadStatistics()
  } catch (error) {
    console.error('Error updating status:', error)
  }
}

const exportOrders = async () => {
  try {
    const response = await vendorOrderService.exportOrders({
      status: filters.value.status
    })
    const blob = response.data
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'commandes.csv'
    a.click()
  } catch (error) {
    console.error('Error exporting orders:', error)
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'confirmed': 'bg-blue-100 text-blue-800',
    'shipped': 'bg-purple-100 text-purple-800',
    'delivered': 'bg-green-100 text-green-800',
    'cancelled': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}
</script>
