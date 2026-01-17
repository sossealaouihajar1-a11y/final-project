<template>
  <div class="min-h-screen bg-white">
    <Header />

    <!-- Hero Section -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <p class="text-sm font-semibold uppercase tracking-wider text-[#8b1c3d] mb-2">Gestion</p>
          <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">Tableau de Bord Admin</h1>
          <p class="text-lg text-gray-600">Bienvenue sur votre espace d'administration</p>
        </div>
      </div>
    </section>

    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Gestion Vendeurs -->
        <router-link
          to="/admin/vendors"
          class="group bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg hover:shadow-lg transition-all border border-gray-300 hover:border-[#8b1c3d]"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="text-3xl">👥</div>
            <svg class="w-5 h-5 text-[#8b1c3d] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-[#8b1c3d] mb-2">Gestion Vendeurs</h3>
          <p class="text-sm text-gray-600">
            Approuver, rejeter ou suspendre des vendeurs
          </p>
        </router-link>

        <!-- Gestion Utilisateurs -->
        <router-link
          to="/admin/users"
          class="group bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg hover:shadow-lg transition-all border border-gray-300 hover:border-[#8b1c3d]"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="text-3xl">👤</div>
            <svg class="w-5 h-5 text-[#8b1c3d] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-[#8b1c3d] mb-2">Gestion Utilisateurs</h3>
          <p class="text-sm text-gray-600">
            Gérer tous les utilisateurs de la plateforme
          </p>
        </router-link>

        <!-- Gestion Produits -->
        <router-link
          to="/admin/products"
          class="group bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg hover:shadow-lg transition-all border border-gray-300 hover:border-[#8b1c3d]"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="text-3xl">📦</div>
            <svg class="w-5 h-5 text-[#8b1c3d] group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-[#8b1c3d] mb-2">Gestion Produits</h3>
          <p class="text-sm text-gray-600">
            Créer, éditer et gérer tous les produits
          </p>
        </router-link>

        <!-- Stats (placeholder) -->
        <div class="bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg border border-gray-300">
          <div class="text-3xl mb-3">📊</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Statistiques</h3>
          <p class="text-sm text-gray-600">
            Vue d'ensemble des ventes et activités
          </p>
        </div>

        <!-- Commandes (placeholder) -->
        <div class="bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg border border-gray-300">
          <div class="text-3xl mb-3">🛒</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Commandes</h3>
          <p class="text-sm text-gray-600">
            Suivre toutes les commandes
          </p>
        </div>

        <!-- Paramètres (placeholder) -->
        <div class="bg-gradient-to-br from-[#f2f1ed] to-gray-100 p-6 rounded-lg border border-gray-300">
          <div class="text-3xl mb-3">⚙️</div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Paramètres</h3>
          <p class="text-sm text-gray-600">
            Configuration de la plateforme
          </p>
        </div>
      </div>

      <!-- Info compte -->
      <div class="bg-[#f2f1ed] border border-gray-300 rounded-lg p-6">
        <h3 class="text-lg font-medium text-[#8b1c3d] mb-4">Informations du compte</h3>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
          <div>
            <dt class="text-gray-600">Email:</dt>
            <dd class="font-medium text-gray-900">{{ authStore.user?.email }}</dd>
          </div>
          <div>
            <dt class="text-gray-600">Téléphone:</dt>
            <dd class="font-medium text-gray-900">{{ authStore.user?.phone }}</dd>
          </div>
          <div>
            <dt class="text-gray-600">Ville:</dt>
            <dd class="font-medium text-gray-900">{{ authStore.user?.city }}</dd>
          </div>
          <div>
            <dt class="text-gray-600">Rôle:</dt>
            <dd class="font-medium text-[#8b1c3d]">Administrateur</dd>
          </div>
        </dl>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import Header from '@/components/Header.vue'

const router = useRouter()
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>