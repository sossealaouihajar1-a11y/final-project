<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar Navigation -->
    <div class="flex h-screen">
      <div class="w-64 bg-white shadow-lg">
        <div class="p-6 border-b">
          <h1 class="text-xl font-bold text-gray-900">Espace Vendeur</h1>
          <p class="text-sm text-gray-600 mt-2">{{ user?.name }}</p>
        </div>
        <nav class="mt-6 space-y-1">
          <RouterLink to="/vendor/dashboard" class="nav-link" :class="{ 'active': isActive('dashboard') }">
            <span class="icon">📊</span> Tableau de Bord
          </RouterLink>
          <RouterLink to="/vendor/products" class="nav-link" :class="{ 'active': isActive('products') }">
            <span class="icon">📦</span> Produits
          </RouterLink>
          <RouterLink to="/vendor/clients" class="nav-link" :class="{ 'active': isActive('clients') }">
            <span class="icon">👥</span> Clients
          </RouterLink>
          <RouterLink to="/vendor/orders" class="nav-link" :class="{ 'active': isActive('orders') }">
            <span class="icon">📋</span> Commandes
          </RouterLink>
          <RouterLink to="/vendor/stock" class="nav-link" :class="{ 'active': isActive('stock') }">
            <span class="icon">📈</span> Stock
          </RouterLink>
        </nav>
        <div class="absolute bottom-0 w-64 p-4 border-t">
          <button @click="handleLogout" class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
            Déconnexion
          </button>
        </div>
      </div>

      <!-- Main Content -->
      <div class="flex-1 flex flex-col">
        <header class="bg-white shadow-sm">
          <div class="max-w-7xl mx-auto px-6 py-4">
            <h2 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h2>
          </div>
        </header>

        <main class="flex-1 overflow-auto">
          <div class="max-w-7xl mx-auto px-6 py-8">
            <!-- Dashboard Overview - Only on dashboard page -->
            <div v-if="isActive('dashboard')" class="space-y-6">
              <!-- Stats Cards -->
              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <StatsCard title="Produits Actifs" :value="stats.active_products || 0" color="blue" icon="📦" />
                <StatsCard title="Stock Faible" :value="stats.low_stock || 0" color="yellow" icon="⚠️" />
                <StatsCard title="Commandes Totales" :value="stats.total_orders || 0" color="green" icon="📋" />
                <StatsCard title="Revenu Total" :value="'$' + (stats.total_revenue || 0)" color="purple" icon="💰" />
              </div>

              <!-- Charts Section -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Commandes par Statut</h3>
                  <div class="space-y-2">
                    <StatusBar label="En attente" value="12" total="50" />
                    <StatusBar label="Confirmées" value="23" total="50" />
                    <StatusBar label="Expédiées" value="10" total="50" />
                    <StatusBar label="Livrées" value="5" total="50" />
                  </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                  <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Produits</h3>
                  <div class="space-y-3">
                    <div class="flex justify-between items-center" v-for="product in topProducts" :key="product.id">
                      <span class="text-sm text-gray-600">{{ product.title }}</span>
                      <span class="text-sm font-semibold text-gray-900">{{ product.sales }} ventes</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Router View for other pages -->
            <RouterView v-else />
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import vendorProductService from '@/services/vendorProductService'
import vendorOrderService from '@/services/vendorOrderService'
import StatsCard from '@/components/vendor/StatsCard.vue'
import StatusBar from '@/components/vendor/StatusBar.vue'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const user = computed(() => authStore.user)

const stats = ref({})
const topProducts = ref([])

const pageTitle = computed(() => {
  const titles = {
    'vendor-dashboard': 'Tableau de Bord',
    'vendor-products': 'Gestion des Produits',
    'vendor-clients': 'Suivi des Clients',
    'vendor-orders': 'Suivi des Commandes',
    'vendor-stock': 'Gestion du Stock'
  }
  return titles[route.name] || 'Tableau de Bord'
})

const isActive = (name) => {
  return route.name === `vendor-${name}`
}

onMounted(async () => {
  await loadStats()
})

const loadStats = async () => {
  try {
    // Load products stats
    const productsRes = await vendorProductService.getProductStatistics()
    stats.value = { ...stats.value, ...productsRes.data }

    // Load orders stats
    const ordersRes = await vendorOrderService.getStatistics()
    stats.value = { ...stats.value, ...ordersRes.data }
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.nav-link {
  @apply flex items-center px-6 py-3 text-gray-600 hover:bg-gray-50 transition;
}

.nav-link.active {
  @apply bg-blue-50 text-blue-600 border-r-4 border-blue-600;
}

.icon {
  @apply mr-3 text-lg;
}
</style>