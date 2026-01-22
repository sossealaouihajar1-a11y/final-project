<template>
  <div class="min-h-screen bg-[#f7f6f3]">
    <Header />
    
    <!-- Hero Section -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <p class="text-sm font-semibold uppercase tracking-wider text-[#8b1c3d] mb-2">Espace Vendeur</p>
          <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">Tableau de Bord</h1>
          <p class="text-lg text-gray-600">Bienvenue, {{ user?.name }}</p>
        </div>
      </div>
    </section>

    <!-- Sidebar Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <aside class="lg:w-64 flex-shrink-0">
          <div class="bg-white border border-gray-200 rounded-lg p-6 sticky top-6">
            <nav class="space-y-2">
              <RouterLink 
                to="/vendor/dashboard" 
                class="nav-link block px-4 py-3 text-gray-700 hover:bg-[#f2f1ed] hover:text-[#8b1c3d] transition rounded"
                :class="{ 'bg-[#f2f1ed] text-[#8b1c3d] border-l-4 border-[#8b1c3d]': isActive('dashboard') }"
              >
                Tableau de Bord
              </RouterLink>
              <RouterLink 
                to="/vendor/products" 
                class="nav-link block px-4 py-3 text-gray-700 hover:bg-[#f2f1ed] hover:text-[#8b1c3d] transition rounded"
                :class="{ 'bg-[#f2f1ed] text-[#8b1c3d] border-l-4 border-[#8b1c3d]': isActive('products') }"
              >
                Produits
              </RouterLink>
              <RouterLink 
                to="/vendor/clients" 
                class="nav-link block px-4 py-3 text-gray-700 hover:bg-[#f2f1ed] hover:text-[#8b1c3d] transition rounded"
                :class="{ 'bg-[#f2f1ed] text-[#8b1c3d] border-l-4 border-[#8b1c3d]': isActive('clients') }"
              >
                Clients
              </RouterLink>
              <RouterLink 
                to="/vendor/orders" 
                class="nav-link block px-4 py-3 text-gray-700 hover:bg-[#f2f1ed] hover:text-[#8b1c3d] transition rounded"
                :class="{ 'bg-[#f2f1ed] text-[#8b1c3d] border-l-4 border-[#8b1c3d]': isActive('orders') }"
              >
                Commandes
              </RouterLink>
              <RouterLink 
                to="/vendor/stock" 
                class="nav-link block px-4 py-3 text-gray-700 hover:bg-[#f2f1ed] hover:text-[#8b1c3d] transition rounded"
                :class="{ 'bg-[#f2f1ed] text-[#8b1c3d] border-l-4 border-[#8b1c3d]': isActive('stock') }"
              >
                Stock
              </RouterLink>
            </nav>
            <div class="mt-6 pt-6 border-t border-gray-200">
              <button 
                @click="handleLogout" 
                class="w-full px-4 py-2 text-sm font-medium text-white bg-[#8b1c3d] rounded-lg hover:bg-[#5a4a3a] transition"
              >
                Déconnexion
              </button>
            </div>
          </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1">
          <!-- Dashboard Overview - Only on dashboard page -->
          <div v-if="isActive('dashboard')" class="space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <StatsCard title="Produits Actifs" :value="stats.active_products || 0" color="blue" />
              <StatsCard title="Stock Faible" :value="stats.low_stock || 0" color="yellow" />
              <StatsCard title="Commandes Totales" :value="stats.total_orders || 0" color="green" />
              <StatsCard title="Revenu Total" :value="'$' + (stats.total_revenue || 0)" color="purple" />
            </div>

              <!-- Charts Section -->
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h3 class="text-lg font-serif font-semibold text-gray-900 mb-4">Commandes par Statut</h3>
                  <div class="space-y-2">
                    <StatusBar label="En attente" value="12" total="50" />
                    <StatusBar label="Confirmées" value="23" total="50" />
                    <StatusBar label="Expédiées" value="10" total="50" />
                    <StatusBar label="Livrées" value="5" total="50" />
                  </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg p-6">
                  <h3 class="text-lg font-serif font-semibold text-gray-900 mb-4">Top Produits</h3>
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
import Header from '@/components/Header.vue'

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
</style>