<template>
  <div class="bg-[#f6f3ee] min-h-screen text-[#2a2a28]">
    <!-- Hero -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Gestion clients
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Mes Clients
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Gérez vos clients et leurs historiques d'achat
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
          v-for="stat in [
            { label: 'Clients totaux', value: clientStats.total_clients || 0 },
            { label: 'Clients fidèles', value: clientStats.repeat_clients || 0 },
            { label: 'Commandes', value: clientStats.total_orders || 0 },
            { label: 'Revenu total', value: formatPrice(clientStats.total_revenue || 0) }
          ]"
          :key="stat.label"
          class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6"
        >
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">
            {{ stat.label }}
          </p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">
            {{ stat.value }}
          </p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="flex gap-4">
          <input 
            v-model="searchQuery" 
            type="text" 
            placeholder="Rechercher par nom, email ou téléphone..." 
            class="flex-1 px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b]"
            @keyup.enter="loadClients"
          />
          <button @click="loadClients" class="px-6 py-3 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition">
            Rechercher
          </button>
        </div>
      </div>

      <!-- Clients Table -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">
        <div v-if="loading" class="p-10 text-center text-[#7a7465]">
          Chargement...
        </div>
        <table v-else class="min-w-full text-sm">
          <thead class="bg-[#f1ede6]">
            <tr class="text-left text-xs uppercase tracking-wider text-[#6b7b4b]">
              <th class="px-6 py-4">Nom</th>
              <th class="px-6 py-4">Email</th>
              <th class="px-6 py-4">Téléphone</th>
              <th class="px-6 py-4">Ville</th>
              <th class="px-6 py-4">Date d'inscription</th>
              <th class="px-6 py-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="client in clients" :key="client.id" class="border-t border-[#e4dccf] hover:bg-[#f4f1eb]">
              <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                  <div class="w-10 h-10 rounded-full bg-[#d6cdbf] flex items-center justify-center text-sm font-semibold text-[#4a3728]">
                    {{ client.name.charAt(0) }}
                  </div>
                  <p class="font-medium text-[#4a3728]">{{ client.name }}</p>
                </div>
              </td>
              <td class="px-6 py-4 text-[#7a7465]">
                {{ client.email }}
              </td>
              <td class="px-6 py-4 text-[#7a7465]">
                {{ client.phone || '-' }}
              </td>
              <td class="px-6 py-4 text-[#7a7465]">
                {{ client.city || '-' }}
              </td>
              <td class="px-6 py-4 text-[#7a7465]">
                {{ formatDate(client.created_at) }}
              </td>
              <td class="px-6 py-4">
                <button @click="viewClientDetails(client.id)" class="text-[#6b8043] hover:underline text-sm font-medium">
                  Détails
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="!loading && clients.length === 0" class="p-10 text-center text-[#7a7465]">
          Aucun client trouvé
        </div>
      </div>
    </div>

    <!-- Client Details Modal -->
    <div v-if="showDetailsModal && selectedClient" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-[#fbfaf7] rounded-xl shadow-xl p-8 w-full max-w-2xl max-h-96 overflow-y-auto border border-[#d6cdbf]">
        <div class="flex justify-between items-start mb-6 pb-4 border-b border-[#d6cdbf]">
          <div>
            <h3 class="text-lg font-serif text-[#4a3728]">{{ selectedClient.name }}</h3>
            <p class="text-sm text-[#7a7465]">{{ selectedClient.email }}</p>
          </div>
          <button @click="showDetailsModal = false" class="text-[#7a7465] hover:text-[#4a3728] text-lg font-bold">✕</button>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="p-4 bg-[#f1ede6] rounded-lg border border-[#e4dccf]">
            <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Total d'achats</p>
            <p class="text-xl font-serif text-[#4a3728] mt-2">{{ clientDetails.statistics.total_purchases }}</p>
          </div>
          <div class="p-4 bg-[#f1ede6] rounded-lg border border-[#e4dccf]">
            <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Dépense totale</p>
            <p class="text-xl font-serif text-[#4a3728] mt-2">{{ formatPrice(clientDetails.statistics.total_spent) }}</p>
          </div>
          <div class="p-4 bg-[#f1ede6] rounded-lg border border-[#e4dccf]">
            <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Valeur moyenne</p>
            <p class="text-xl font-serif text-[#4a3728] mt-2">{{ formatPrice(clientDetails.statistics.average_order_value) }}</p>
          </div>
          <div class="p-4 bg-[#f1ede6] rounded-lg border border-[#e4dccf]">
            <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Dernier achat</p>
            <p class="text-sm font-medium text-[#4a3728] mt-2">{{ formatDate(clientDetails.statistics.last_purchase) }}</p>
          </div>
        </div>

        <h4 class="font-serif text-[#4a3728] mb-3 uppercase tracking-wider text-sm">Historique des commandes</h4>
        <div class="space-y-2 max-h-48 overflow-y-auto">
          <div v-for="order in clientDetails.orders" :key="order.id" class="p-3 border border-[#d6cdbf] rounded-lg bg-white">
            <div class="flex justify-between items-start">
              <div>
                <p class="text-sm font-medium text-[#4a3728]">Commande #{{ order.id.substring(0, 8) }}</p>
                <p class="text-xs text-[#7a7465]">{{ formatDate(order.created_at) }}</p>
              </div>
              <span :class="getStatusClass(order.status)" class="px-2 py-1 rounded text-xs font-semibold">
                {{ order.status }}
              </span>
            </div>
            <p class="text-sm text-[#7a7465] mt-2">{{ order.order_items.length }} article(s) - {{ formatPrice(order.total_price) }}</p>
          </div>
        </div>

        <button @click="showDetailsModal = false" class="mt-6 w-full px-6 py-3 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition">
          Fermer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import vendorClientService from '@/services/vendorClientService'

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

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

const formatPrice = (price) => {
  if (!price) return '0 MAD'
  return new Intl.NumberFormat('fr-FR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(price) + ' MAD'
}

const getStatusClass = (status) => {
  const classes = {
    'pending': 'bg-[#fef3c7] text-[#92400e]',
    'confirmed': 'bg-[#c7d2fe] text-[#312e81]',
    'shipped': 'bg-[#ddd6fe] text-[#5b21b6]',
    'delivered': 'bg-[#bbf7d0] text-[#065f46]',
    'cancelled': 'bg-[#fecaca] text-[#7f1d1d]'
  }
  return classes[status] || 'bg-[#e5e7eb] text-[#374151]'
}
</script>
