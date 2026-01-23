<template>
  <div class="min-h-screen flex bg-[#f8f5ef] text-[#2a2a28] font-serif">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#6b705c] text-[#f8f5ef] fixed inset-y-0 left-0 border-r border-[#5f6452]">
      <div class="px-6 py-6 border-b border-[#5f6452]">
        <h2 class="text-xl font-bold">Espace Vendeur</h2>
        <p class="text-xs uppercase tracking-widest opacity-70 mt-1">Vintage Store</p>
      </div>

      <nav class="px-4 py-6 space-y-2 text-sm">
        <router-link 
          to="/vendor/dashboard" 
          class="nav-link"
          :class="{ 'bg-[#8b1c3d] text-white font-semibold': isActive('dashboard') }"
        >Tableau de Bord</router-link>
        <router-link 
          to="/vendor/products" 
          class="nav-link"
          :class="{ 'bg-[#8b1c3d] text-white font-semibold': isActive('products') }"
        >Produits</router-link>
        <router-link 
          to="/vendor/clients" 
          class="nav-link"
          :class="{ 'bg-[#8b1c3d] text-white font-semibold': isActive('clients') }"
        >Clients</router-link>
        <router-link 
          to="/vendor/orders" 
          class="nav-link"
          :class="{ 'bg-[#8b1c3d] text-white font-semibold': isActive('orders') }"
        >Commandes</router-link>
        <router-link 
          to="/vendor/stock" 
          class="nav-link"
          :class="{ 'bg-[#8b1c3d] text-white font-semibold': isActive('stock') }"
        >Stock</router-link>
      </nav>

      <div class="mt-6 pt-6 border-t border-[#5f6452] px-4">
      <button 
        @click="handleLogout" 
        class="w-full px-4 py-2 text-sm font-medium text-white bg-[#8b5e3c] rounded-lg hover:bg-[#a06a47] transition"
      >
        Déconnexion
      </button>

      </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">

      <!-- HEADER -->
      <header class="bg-[#f6f3ee] border-b border-[#d6cdbf] px-10 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-serif text-[#4a3728]">
          {{ pageTitle }}
        </h1>
        <p class="text-sm text-[#6b7b4b]">Bienvenue, {{ user?.name }}</p>
      </header>

      <!-- MAIN SECTION -->
      <main class="flex-1 p-10 bg-[#f8f5ef]">

        <!-- Affiche infos vendeur uniquement sur dashboard -->
        <section v-if="isActive('dashboard')" class="max-w-4xl mx-auto bg-[#fbfaf7] border border-[#d6cdbf] rounded-2xl p-8 shadow-lg space-y-6">
          <h2 class="text-2xl font-semibold text-[#556b2f] mb-6 border-b border-[#d6cdbf] pb-2">
            Informations personnelles
          </h2>

          <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[#2a2a28]">
            <li class="flex flex-col">
              <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Nom</span>
              <span class="mt-1 text-lg font-medium">{{ user?.name }}</span>
            </li>
            <li class="flex flex-col">
              <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Email</span>
              <span class="mt-1 text-lg font-medium">{{ user?.email }}</span>
            </li>
            <li class="flex flex-col">
              <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Rôle</span>
              <span class="mt-1 text-lg font-medium">Vendeur</span>
            </li>
            <li class="flex flex-col">
              <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Date d’inscription</span>
              <span class="mt-1 text-lg font-medium">
                {{ new Date(user?.created_at).toLocaleDateString('fr-FR', { day:'2-digit', month:'long', year:'numeric' }) }}
              </span>
            </li>
          </ul>
        </section>

        <!-- Affiche les autres pages (Produits, Commandes, Clients, Stock) -->
        <RouterView v-else />

      </main>
    </div>

  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const user = computed(() => authStore.user)

const isActive = (name) => route.name === `vendor-${name}`

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

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.nav-link {
  display: block;
  padding: 10px 14px;
  border-radius: 6px;
  transition: background 0.2s;
}
.nav-link:hover {
  background: rgba(255,255,255,0.12);
}
.router-link-active {
  background: rgba(255,255,255,0.25);
  font-weight: 600;
}
</style>
