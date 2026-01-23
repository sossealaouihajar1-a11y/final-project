<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <router-link to="/" class="flex items-center space-x-2">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">Vintage Shop</span>
            </router-link>
            <router-link to="/products" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
              Catalogue
            </router-link>
          </div>
          <div class="flex items-center space-x-6">
            <router-link to="/favorites" class="relative text-gray-600 hover:text-red-500 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </router-link>
            <router-link to="/cart" class="relative text-gray-600 hover:text-indigo-600 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </router-link>
            <router-link to="/client/dashboard" class="text-sm font-medium text-indigo-600">
              Mon Compte
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Titre et Stats -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3 mb-6">
          <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <span>Mes Commandes</span>
        </h1>

        <!-- Statistiques -->
        <div v-if="stats" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total commandes</p>
                <p class="text-2xl font-bold text-gray-900">{{ stats.total_orders }}</p>
              </div>
              <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">En attente</p>
                <p class="text-2xl font-bold text-orange-600">{{ stats.pending_orders }}</p>
              </div>
              <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Livrées</p>
                <p class="text-2xl font-bold text-green-600">{{ stats.delivered_orders }}</p>
              </div>
              <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-gray-600 mb-1">Total dépensé</p>
                <p class="text-2xl font-bold text-indigo-600">{{ formatPrice(stats.total_spent) }} MAD</p>
              </div>
              <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-20">
        <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">Chargement...</p>
      </div>

      <!-- Aucune commande -->
      <div v-else-if="orders.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Aucune commande</h2>
        <p class="text-gray-600 mb-6">Vous n'avez pas encore passé de commande</p>
        <router-link to="/products" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
          Parcourir la boutique
        </router-link>
      </div>

      <!-- Liste des commandes -->
      <div v-else class="space-y-4">
        <div
          v-for="order in orders"
          :key="order.id"
          class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition"
        >
          <div class="p-6">
            <!-- En-tête commande -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 pb-4 border-b">
              <div>
                <p class="text-sm text-gray-600">Commande</p>
                <p class="text-lg font-bold text-gray-900">#{{ order.id.substring(0, 8) }}</p>
                <p class="text-sm text-gray-500">{{ formatDate(order.created_at) }}</p>
              </div>
              <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                <span :class="getStatusClass(order.status)" class="px-4 py-2 rounded-lg text-sm font-semibold">
                  {{ getStatusLabel(order.status) }}
                </span>
                <button
                  v-if="order.status === 'pending'"
                  @click="cancelOrder(order.id)"
                  class="px-4 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-semibold hover:bg-red-100 transition"
                >
                  Annuler
                </button>
              </div>
            </div>

            <!-- Articles -->
            <div class="space-y-3 mb-4">
              <div
                v-for="item in order.order_items"
                :key="item.id"
                class="flex items-center space-x-4"
              >
                <div class="w-16 h-16 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden">
                  <img
                    v-if="item.vintage_product?.image_url"
                    :src="item.vintage_product.image_url"
                    :alt="item.vintage_product.title"
                    class="w-full h-full object-cover"
                  />
                  <svg v-else class="w-full h-full text-gray-300 p-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-semibold text-gray-900 truncate">{{ item.vintage_product?.title }}</p>
                  <p class="text-sm text-gray-500">{{ item.vintage_product?.category }}</p>
                </div>
                <div class="text-right">
                  <p class="text-sm text-gray-600">{{ item.quantity }} × {{ formatPrice(item.price) }} MAD</p>
                  <p class="font-semibold text-gray-900">{{ formatPrice(item.price * item.quantity) }} MAD</p>
                </div>
              </div>
            </div>

            <!-- Adresse et Total -->
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between pt-4 border-t">
              <div v-if="order.shipping_address" class="mb-3 sm:mb-0">
                <p class="text-sm font-semibold text-gray-700 mb-1">📍 Adresse de livraison</p>
                <p class="text-sm text-gray-600">
                  {{ order.shipping_address.full_name }}<br>
                  {{ order.shipping_address.address }}<br>
                  {{ order.shipping_address.postal_code }} {{ order.shipping_address.city }}
                </p>
              </div>
              <div class="text-left sm:text-right">
                <p class="text-sm text-gray-600 mb-1">Total</p>
                <p class="text-2xl font-bold text-indigo-600">{{ formatPrice(order.total_price) }} MAD</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination && pagination.last_page > 1" class="flex justify-center mt-8">
          <nav class="flex items-center space-x-2">
            <button
              @click="loadOrders(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition text-sm font-medium"
            >
              Précédent
            </button>
            <span class="px-4 py-2 text-sm text-gray-700">
              Page {{ pagination.current_page }} sur {{ pagination.last_page }}
            </span>
            <button
              @click="loadOrders(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-4 py-2 bg-white border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition text-sm font-medium"
            >
              Suivant
            </button>
          </nav>
        </div>
      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-gray-800 font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import orderService from '@/services/orderService'

const { confirmDelete } = useConfirmDialog()

const orders = ref([])
const stats = ref(null)
const loading = ref(false)
const pagination = ref(null)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadOrders = async (page = 1) => {
  loading.value = true
  try {
    const response = await orderService.getOrders(page)
    orders.value = response.data
    pagination.value = {
      current_page: response.current_page,
      last_page: response.last_page,
      total: response.total
    }
  } catch (error) {
    console.error('Erreur chargement commandes:', error)
    showNotification('Erreur lors du chargement des commandes', 'error')
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    stats.value = await orderService.getStats()
  } catch (error) {
    console.error('Erreur chargement stats:', error)
  }
}

const cancelOrder = async (orderId) => {
  const confirmed = await confirmDelete('cette commande')
  if (!confirmed) {
    return
  }

  try {
    await orderService.cancelOrder(orderId)
    showNotification('Commande annulée avec succès')
    loadOrders()
    loadStats()
  } catch (error) {
    console.error('Erreur annulation:', error)
    const message = error.response?.data?.message || 'Erreur lors de l\'annulation'
    showNotification(message, 'error')
  }
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'En attente',
    paid: 'Payée',
    shipped: 'Expédiée',
    delivered: 'Livrée',
    canceled: 'Annulée'
  }
  return labels[status] || status
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-orange-100 text-orange-800',
    paid: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    canceled: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadOrders()
  loadStats()
})
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  transform: translateY(100px);
  opacity: 0;
}

.slide-up-leave-to {
  transform: translateY(100px);
  opacity: 0;
}
</style>