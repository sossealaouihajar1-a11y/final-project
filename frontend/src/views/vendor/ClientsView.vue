<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <h3 class="text-lg font-semibold text-gray-900">Suivi des Clients</h3>
      <p class="text-sm text-gray-600 mt-1">Gérez vos clients et leurs historiques d'achat</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <StatsCard title="Clients Totaux" :value="clientStats.total_clients || 0" color="blue" icon="👥" />
      <StatsCard title="Clients Fidèles" :value="clientStats.repeat_clients || 0" color="green" icon="⭐" />
      <StatsCard title="Commandes" :value="clientStats.total_orders || 0" color="purple" icon="📋" />
      <StatsCard title="Revenu Total" :value="'$' + (clientStats.total_revenue || 0)" color="orange" icon="💰" />
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow p-4">
      <div class="flex gap-4">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Rechercher par nom, email ou téléphone..." 
          class="flex-1 border rounded-lg px-3 py-2"
          @keyup.enter="loadClients"
        />
        <button @click="loadClients" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Rechercher
        </button>
        <button @click="exportClients" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          📥 Exporter
        </button>
      </div>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-500">
        Chargement...
      </div>
      <div v-else-if="clients.length === 0" class="p-8 text-center text-gray-500">
        Aucun client trouvé
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Téléphone</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'inscription</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="client in clients" :key="client.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3 text-sm font-medium">
                  {{ client.name.charAt(0) }}
                </div>
                <p class="text-sm font-medium text-gray-900">{{ client.name }}</p>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ client.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ client.phone || '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ client.city || '-' }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ formatDate(client.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
              <button @click="viewClientDetails(client.id)" class="text-blue-600 hover:text-blue-800">
                👁️ Voir
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Client Details Modal -->
    <div v-if="showDetailsModal && selectedClient" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl max-h-96 overflow-y-auto">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h3 class="text-lg font-semibold text-gray-900">{{ selectedClient.name }}</h3>
            <p class="text-sm text-gray-600">{{ selectedClient.email }}</p>
          </div>
          <button @click="showDetailsModal = false" class="text-gray-500 hover:text-gray-700">✕</button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="p-3 bg-gray-50 rounded">
            <p class="text-xs text-gray-600">Total d'achats</p>
            <p class="text-lg font-semibold text-gray-900">{{ clientDetails.statistics.total_purchases }}</p>
          </div>
          <div class="p-3 bg-gray-50 rounded">
            <p class="text-xs text-gray-600">Dépense totale</p>
            <p class="text-lg font-semibold text-gray-900">${{ clientDetails.statistics.total_spent }}</p>
          </div>
          <div class="p-3 bg-gray-50 rounded">
            <p class="text-xs text-gray-600">Valeur moyenne panier</p>
            <p class="text-lg font-semibold text-gray-900">${{ clientDetails.statistics.average_order_value }}</p>
          </div>
          <div class="p-3 bg-gray-50 rounded">
            <p class="text-xs text-gray-600">Dernier achat</p>
            <p class="text-sm font-semibold text-gray-900">{{ formatDate(clientDetails.statistics.last_purchase) }}</p>
          </div>
        </div>

        <h4 class="font-semibold text-gray-900 mb-3">Historique des commandes</h4>
        <div class="space-y-2 max-h-48 overflow-y-auto">
          <div v-for="order in clientDetails.orders" :key="order.id" class="p-3 border rounded bg-gray-50">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm font-medium text-gray-900">Commande #{{ order.id.substring(0, 8) }}</p>
                <p class="text-xs text-gray-600">{{ formatDate(order.created_at) }}</p>
              </div>
              <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded text-xs font-semibold">
                {{ order.status }}
              </span>
            </div>
            <p class="text-sm text-gray-600 mt-2">{{ order.items.length }} article(s) - {{ order.total_price }}€</p>
          </div>
        </div>

        <button @click="showDetailsModal = false" class="mt-6 w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          Fermer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import vendorClientService from '@/services/vendorClientService'
import StatsCard from '@/components/vendor/StatsCard.vue'

const authStore = useAuthStore()

const clients = ref([])
const loading = ref(false)
const searchQuery = ref('')
const clientStats = ref({})
const showDetailsModal = ref(false)
const selectedClient = ref(null)
const clientDetails = ref({ orders: [], statistics: {} })

onMounted(() => {
  loadClients()
  loadStatistics()
})

const loadClients = async () => {
  loading.value = true
  try {
    const response = await vendorClientService.getAllClients({
      search: searchQuery.value
    })
    clients.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading clients:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await vendorClientService.getStatistics()
    clientStats.value = response.data
  } catch (error) {
    console.error('Error loading statistics:', error)
  }
}

const viewClientDetails = async (clientId) => {
  try {
    const response = await vendorClientService.getClient(clientId)
    const data = response.data
    selectedClient.value = data.client
    clientDetails.value = {
      orders: data.orders,
      statistics: data.statistics
    }
    showDetailsModal.value = true
  } catch (error) {
    console.error('Error loading client details:', error)
  }
}

const exportClients = async () => {
  try {
    const response = await vendorClientService.exportClients()
    const blob = response.data
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'clients.csv'
    a.click()
  } catch (error) {
    console.error('Error exporting clients:', error)
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
