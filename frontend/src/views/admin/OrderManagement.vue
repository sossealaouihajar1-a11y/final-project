<template>
  <div class="min-h-screen bg-[#f6f3ee] text-[#2a2a28]">

    <!-- Order Details Modal -->
    <div v-if="selectedOrder" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-[#d6cdbf]">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl font-serif text-[#4a3728]">Détails commande</h2>
            <button @click="selectedOrder = null" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
        </div>
        
        <div class="p-6 space-y-6">
          <!-- Header Info -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-xs uppercase text-[#6b7b4b] mb-1">Numéro commande</p>
              <p class="font-semibold text-[#4a3728]">{{ selectedOrder.order_number }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-[#6b7b4b] mb-1">Statut</p>
              <span :class="getStatusClass(selectedOrder.status)" class="px-3 py-1 rounded-full text-xs">
                {{ getStatusLabel(selectedOrder.status) }}
              </span>
            </div>
            <div>
              <p class="text-xs uppercase text-[#6b7b4b] mb-1">Date</p>
              <p class="text-[#4a3728]">{{ formatDate(selectedOrder.created_at) }}</p>
            </div>
            <div>
              <p class="text-xs uppercase text-[#6b7b4b] mb-1">Total</p>
              <p class="font-semibold text-[#6b7b4b]">{{ formatPrice(selectedOrder.total_price) }}</p>
            </div>
          </div>

          <!-- Client Info -->
          <div class="border-t border-[#d6cdbf] pt-4">
            <h3 class="font-semibold text-[#4a3728] mb-3">Client</h3>
            <p>{{ selectedOrder.client_name }}</p>
            <p class="text-sm text-gray-600">{{ selectedOrder.client_email }}</p>
            <p class="text-sm text-gray-600">{{ selectedOrder.client_phone }}</p>
          </div>

          <!-- Vendor Info -->
          <div class="border-t border-[#d6cdbf] pt-4">
            <h3 class="font-semibold text-[#4a3728] mb-3">Vendeur</h3>
            <p>{{ selectedOrder.vendor_name }}</p>
            <p class="text-sm text-gray-600">{{ selectedOrder.vendor_email }}</p>
            <p class="text-sm text-gray-600">{{ selectedOrder.vendor_phone }}</p>
          </div>

          <!-- Items -->
          <div class="border-t border-[#d6cdbf] pt-4">
            <h3 class="font-semibold text-[#4a3728] mb-3">Articles</h3>
            <div class="space-y-2">
              <div v-for="item in selectedOrder.items" :key="item.id" class="flex justify-between items-center py-2 border-b border-[#e4dccf]">
                <div class="flex items-center space-x-3">
                  <img v-if="item.product_image" :src="item.product_image" :alt="item.product_name" class="w-12 h-12 object-cover rounded">
                  <div>
                    <p class="font-medium text-[#4a3728]">{{ item.product_name }}</p>
                    <p class="text-sm text-gray-600">Qty: {{ item.quantity }}</p>
                  </div>
                </div>
                <p class="font-semibold text-[#4a3728]">{{ formatPrice(item.price * item.quantity) }}</p>
              </div>
            </div>
          </div>

          <!-- Totals -->
          <div class="border-t border-[#d6cdbf] pt-4 space-y-2">
            <div class="flex justify-between">
              <span>Sous-total</span>
              <span>{{ formatPrice(selectedOrder.subtotal) }}</span>
            </div>
            <div class="flex justify-between font-semibold text-[#4a3728]">
              <span>Total</span>
              <span>{{ formatPrice(selectedOrder.total_price) }}</span>
            </div>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex justify-end">
          <button @click="selectedOrder = null" class="px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Fermer
          </button>
        </div>
      </div>
    </div>

    <!-- Status Update Modal -->
    <div v-if="statusUpdateOrder" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-[#d6cdbf]">
          <h2 class="text-xl font-serif text-[#4a3728]">Mettre à jour le statut</h2>
        </div>
        
        <div class="p-6 space-y-4">
          <p class="text-[#5a564f]">Commande: <span class="font-semibold">{{ statusUpdateOrder.order_number }}</span></p>
          
          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Nouveau statut</label>
            <select v-model="newStatus" class="w-full px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
              <option value="pending">En attente</option>
              <option value="paid">Payée</option>
              <option value="shipped">Expédiée</option>
              <option value="delivered">Livrée</option>
              <option value="canceled">Annulée</option>
            </select>
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="saveStatusUpdate" class="flex-1 px-4 py-2 bg-[#6b7b4b] text-white rounded-lg hover:bg-[#556b2f] font-medium">
            Confirmer
          </button>
          <button @click="statusUpdateOrder = null" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Delete Modal -->
    <div v-if="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="p-6 border-b border-[#d6cdbf]">
          <h2 class="text-xl font-serif text-[#4a3728]">{{ confirmModal.title }}</h2>
        </div>
        
        <div class="p-6">
          <p class="text-[#5a564f]">{{ confirmModal.message }}</p>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="confirmModal.action()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
            Supprimer
          </button>
          <button @click="confirmModal = null" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Hero -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Administration
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Gestion des commandes
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Suivi, contrôle et gestion des commandes de la plateforme
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div
          v-for="stat in [
            { label: 'Total commandes', value: statistics.total_orders },
            { label: 'En attente', value: statistics.pending_orders },
            { label: 'Payées', value: statistics.processing_orders },
            { label: 'Livrées', value: statistics.delivered_orders },
            { label: 'Commission admin', value: formatPrice(totalAdminCommission) }
          ]"
          :key="stat.label"
          class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6"
        >
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">
            {{ stat.label }}
          </p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">
            {{ stat.value || 0 }}
          </p>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            placeholder="Recherche commande, client, vendeur"
            class="col-span-2 px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b]"
          />

          <select v-model="filterStatus" @change="loadOrders"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Tous les statuts</option>
            <option value="pending">En attente</option>
            <option value="paid">Payée</option>
            <option value="shipped">Expédiée</option>
            <option value="delivered">Livrée</option>
            <option value="canceled">Annulée</option>
          </select>

          <input type="date" v-model="filterDateFrom" @change="loadOrders"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white" />

          <input type="date" v-model="filterDateTo" @change="loadOrders"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white" />
        </div>
      </div>

      <!-- Table -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-[#f1ede6]">
            <tr class="text-left text-xs uppercase tracking-wider text-[#6b7b4b]">
              <th class="px-6 py-4">Commande</th>
              <th class="px-6 py-4">Client</th>
              <th class="px-6 py-4">Vendeur</th>
              <th class="px-6 py-4">Articles</th>
              <th class="px-6 py-4">Total</th>
              <th class="px-6 py-4">Commission</th>
              <th class="px-6 py-4">Statut</th>
              <th class="px-6 py-4">Date</th>
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
                {{ order.order_number }}
              </td>
              <td class="px-6 py-4">
                {{ order.client_name }}<br>
                <span class="text-xs text-gray-500">{{ order.client_email }}</span>
              </td>
              <td class="px-6 py-4">
                {{ order.vendor_name }}<br>
                <span class="text-xs text-gray-500">{{ order.vendor_email }}</span>
              </td>
              <td class="px-6 py-4">
                {{ order.items_count }}
              </td>
              <td class="px-6 py-4 font-semibold">
                {{ formatPrice(order.total_price) }}
              </td>
              <td class="px-6 py-4 text-[#6b7b4b] font-semibold">
                {{ formatPrice(calculateCommission(order.total_price)) }}
              </td>
              <td class="px-6 py-4">
                <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-xs">
                  {{ getStatusLabel(order.status) }}
                </span>
              </td>
              <td class="px-6 py-4">
                {{ formatDate(order.created_at) }}
              </td>
              <td class="px-6 py-4 space-x-3">
                <button @click="viewOrderDetails(order)" class="text-[#6b7b4b] hover:underline">
                  Détails
                </button>
                <!-- <button @click="updateStatus(order)" class="text-[#4a3728] hover:underline">
                  Statut
                </button> -->
                <!-- <button @click="confirmDelete(order)" class="text-red-600 hover:underline">
                  Supprimer
                </button> -->
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="orders.length === 0" class="p-10 text-center text-gray-500">
          Aucune commande trouvée
        </div>
      </div>

    </div>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import Header from '@/components/Header.vue'
import orderManagementService from '@/services/orderManagementService'

const orders = ref([])
const loading = ref(false)
const statistics = ref({})
const searchQuery = ref('')
const filterStatus = ref('')
const filterDateFrom = ref('')
const filterDateTo = ref('')
const selectedOrder = ref(null)
const statusUpdateOrder = ref(null)
const newStatus = ref('')
const confirmModal = ref(null)
const pagination = ref(null)
let searchTimeout = null

// Fonction pour calculer la commission admin (10%)
const calculateCommission = (totalPrice) => {
  return totalPrice * 0.10
}

// Calculer la commission totale de toutes les commandes
const totalAdminCommission = computed(() => {
  return orders.value.reduce((total, order) => {
    return total + calculateCommission(order.total_price)
  }, 0)
})

const loadOrders = async (page = 1) => {
  loading.value = true
  try {
    const response = await orderManagementService.getAllOrders({
      page,
      per_page: 15,
      status: filterStatus.value || undefined,
      date_from: filterDateFrom.value || undefined,
      date_to: filterDateTo.value || undefined,
      search: searchQuery.value || undefined,
    })
    orders.value = response.data.data || []
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
    }
  } catch (error) {
    console.error('Erreur chargement commandes:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await orderManagementService.getOrderStatistics()
    statistics.value = response.data
  } catch (error) {
    console.error('Erreur chargement statistiques:', error)
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadOrders(1)
  }, 500)
}

const viewOrderDetails = async (order) => {
  try {
    const response = await orderManagementService.getOrderDetails(order.id)
    selectedOrder.value = response.data
  } catch (error) {
    console.error('Erreur chargement détails:', error)
    selectedOrder.value = order
  }
}

const updateStatus = (order) => {
  statusUpdateOrder.value = order
  newStatus.value = order.status
}

const saveStatusUpdate = async () => {
  try {
    await orderManagementService.updateOrderStatus(statusUpdateOrder.value.id, newStatus.value)
    statusUpdateOrder.value = null
    loadOrders()
    loadStatistics()
  } catch (error) {
    console.error('Erreur mise à jour statut:', error)
  }
}

const confirmDelete = (order) => {
  confirmModal.value = {
    title: 'Supprimer cette commande',
    message: `Êtes-vous sûr de vouloir supprimer la commande ${order.order_number}? Cette action est irréversible.`,
    action: () => deleteOrder(order.id),
  }
}

const deleteOrder = async (orderId) => {
  try {
    await orderManagementService.deleteOrder(orderId)
    confirmModal.value = null
    loadOrders()
    loadStatistics()
  } catch (error) {
    console.error('Erreur suppression:', error)
  }
}

const exportOrders = async () => {
  try {
    const response = await orderManagementService.exportOrders({
      status: filterStatus.value || undefined,
      date_from: filterDateFrom.value || undefined,
      date_to: filterDateTo.value || undefined,
    })
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `commandes_${new Date().toISOString().split('T')[0]}.csv`)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    console.error('Erreur export:', error)
  }
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    paid: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    canceled: 'bg-red-100 text-red-800',
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'En attente',
    paid: 'Payée',
    shipped: 'Expédiée',
    delivered: 'Livrée',
    canceled: 'Annulée',
  }
  return labels[status] || status
}

const formatPrice = (price) => {
  if (!price) return '0 MAD'

  return new Intl.NumberFormat('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price) + ' MAD'
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadOrders()
  loadStatistics()
})
</script>