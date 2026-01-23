<template>
  <div class="bg-[#f6f3ee] min-h-screen text-[#2a2a28]">

    <!-- Order Details Modal -->
    <div v-if="showDetailsModal && selectedOrder" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-[#d6cdbf]">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl font-serif text-[#4a3728]">Détails de la commande</h2>
            <button @click="showDetailsModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <p class="text-xs uppercase text-[#6b7b4b] mb-1">Numéro</p>
            <p class="font-semibold text-[#4a3728]">#{{ selectedOrder.id.substring(0, 8) }}</p>
          </div>

          <div>
            <p class="text-xs uppercase text-[#6b7b4b] mb-1">Client</p>
            <p class="text-[#4a3728]">{{ selectedOrder.user.name }}</p>
            <p class="text-sm text-gray-600">{{ selectedOrder.user.email }}</p>
          </div>

          <div class="border-t border-[#d6cdbf] pt-4">
            <p class="text-xs uppercase text-[#6b7b4b] mb-2">Articles</p>
            <div v-for="item in selectedOrder.order_items" :key="item.id" class="flex justify-between py-2">
              <span>{{ item.vintage_product.title }} (x{{ item.quantity }})</span>
              <span class="font-medium">{{ item.price * item.quantity }} MAD</span>
            </div>
          </div>

          <div class="border-t border-[#d6cdbf] pt-4">
            <div class="flex justify-between mb-2">
              <span>Sous-total</span>
              <span>{{ selectedOrder.subtotal }} MAD</span>
            </div>
            <div class="flex justify-between font-semibold text-[#4a3728]">
              <span>Total</span>
              <span>{{ selectedOrder.total_price }} MAD</span>
            </div>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex justify-end">
          <button @click="showDetailsModal = false" class="px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Fermer
          </button>
        </div>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div v-if="showStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-[#d6cdbf]">
          <h2 class="text-xl font-serif text-[#4a3728]">Mettre à jour le statut</h2>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Nouveau statut</label>
            <select v-model="newStatus" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
              <option value="pending">En attente</option>
              <option value="confirmed">Confirmée</option>
              <option value="shipped">Expédiée</option>
              <option value="delivered">Livrée</option>
              <option value="cancelled">Annulée</option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Notes (optionnel)</label>
            <textarea v-model="statusNotes" rows="3" placeholder="Ajoutez une note..." class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]"></textarea>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="submitStatusUpdate" class="flex-1 px-4 py-2 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium">
            Confirmer
          </button>
          <button @click="showStatusModal = false" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- ===== HERO ===== -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Gestion commandes
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Suivi des Commandes
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Gérez et suivez toutes les commandes de vos clients
        </p>
      </div>
    </section>

    <!-- ===== CONTENT ===== -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- ===== STATISTICS ===== -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
        <StatsCard title="Total" :value="orderStats.total_orders || 0" />
        <StatsCard title="En attente" :value="orderStats.pending_orders || 0" />
        <StatsCard title="Confirmées" :value="orderStats.confirmed_orders || 0" />
        <StatsCard title="Expédiées" :value="orderStats.shipped_orders || 0" />
        <StatsCard title="Livrées" :value="orderStats.delivered_orders || 0" />
      </div>

      <!-- ===== FILTERS ===== -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            v-model="filters.search"
            type="text"
            placeholder="Rechercher un client..."
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b]"
          />

          <select
            v-model="filters.status"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white"
          >
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
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white"
          />

          <div class="flex gap-3">
            <button
              @click="loadOrders"
              class="flex-1 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition"
            >
              Filtrer
            </button>
            <button
              @click="exportOrders"
              class="bg-[#bfa77a] text-white px-5 rounded-lg hover:bg-[#a88f64] font-medium transition"
            >
              Export
            </button>
          </div>
        </div>
      </div>

      <!-- ===== ORDERS TABLE ===== -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">

        <div v-if="loading" class="p-10 text-center text-[#7a7465]">
          Chargement...
        </div>

        <div v-else-if="orders.length === 0" class="p-10 text-center text-[#7a7465]">
          Aucune commande trouvée
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-[#f1ede6]">
              <tr class="text-left text-xs uppercase tracking-wider text-[#6b7b4b]">
                <th class="px-6 py-4">Commande</th>
                <th class="px-6 py-4">Client</th>
                <th class="px-6 py-4">Date</th>
                <th class="px-6 py-4">Articles</th>
                <th class="px-6 py-4">Total</th>
                <th class="px-6 py-4">Statut</th>
                <th class="px-6 py-4">Actions</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="order in orders"
                :key="order.id"
                class="border-t border-[#e4dccf] hover:bg-[#f4f1eb]"
              >
                <td class="px-6 py-4 font-medium text-[#4a3728]">
                  #{{ order.id.substring(0, 8) }}
                </td>

                <td class="px-6 py-4">
                  <p class="font-medium text-[#4a3728]">
                    {{ order.user.name }}
                  </p>
                  <p class="text-xs text-[#7a7465]">
                    {{ order.user.email }}
                  </p>
                </td>

                <td class="px-6 py-4 text-[#7a7465]">
                  {{ formatDate(order.created_at) }}
                </td>

                <td class="px-6 py-4 text-[#7a7465]">
                  {{ order.order_items.length }} article(s)
                </td>

                <td class="px-6 py-4 font-medium text-[#4a3728]">
                  {{ order.total_price }} MAD
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="getStatusClass(order.status)"
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                  >
                    {{ order.status }}
                  </span>
                </td>

                <td class="px-6 py-4 space-x-3">
                  <button
                    @click="viewOrder(order.id)"
                    class="text-[#6b8043] hover:underline font-medium"
                  >
                    Voir
                  </button>
                  <button
                    @click="updateOrderStatus(order.id, order.status)"
                    class="text-[#bfa77a] hover:underline font-medium"
                  >
                    Mettre à jour
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
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
