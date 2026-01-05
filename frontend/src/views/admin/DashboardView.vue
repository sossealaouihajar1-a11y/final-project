<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <span class="text-indigo-600 font-semibold text-lg">🔧 Dashboard Admin</span>
            <router-link
              to="/admin/vendors"
              class="text-gray-700 hover:text-indigo-600 transition text-sm"
            >
              Vendeurs
            </router-link>
            <router-link
              to="/admin/users"
              class="text-gray-700 hover:text-indigo-600 transition text-sm"
            >
              Utilisateurs
            </router-link>
            <router-link
              to="/admin/products"
              class="text-gray-700 hover:text-indigo-600 transition text-sm"
            >
              Produits
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-gray-700">{{ authStore.user?.name }}</span>
            <button
              @click="handleLogout"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
            >
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
          Bienvenue, {{ authStore.user?.name }} ! 👋
        </h2>
        
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
          <p class="text-sm text-green-700">
            ✅ Vous êtes connecté en tant qu'<strong>Administrateur</strong>
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Gestion Vendeurs -->
          <router-link
            to="/admin/vendors"
            class="group bg-gradient-to-br from-indigo-50 to-indigo-100 p-6 rounded-lg hover:shadow-lg transition-all border border-indigo-200"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="text-3xl">👥</div>
              <svg class="w-5 h-5 text-indigo-600 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-indigo-900 mb-2">Gestion Vendeurs</h3>
            <p class="text-sm text-indigo-700">
              Approuver, rejeter ou suspendre des vendeurs
            </p>
          </router-link>

          <!-- Gestion Utilisateurs -->
          <router-link
            to="/admin/users"
            class="group bg-gradient-to-br from-blue-50 to-blue-100 p-6 rounded-lg hover:shadow-lg transition-all border border-blue-200"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="text-3xl">👤</div>
              <svg class="w-5 h-5 text-blue-600 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Gestion Utilisateurs</h3>
            <p class="text-sm text-blue-700">
              Gérer tous les utilisateurs de la plateforme
            </p>
          </router-link>

          <!-- Gestion Produits -->
          <router-link
            to="/admin/products"
            class="group bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-lg hover:shadow-lg transition-all border border-purple-200"
          >
            <div class="flex items-center justify-between mb-3">
              <div class="text-3xl">📦</div>
              <svg class="w-5 h-5 text-purple-600 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-purple-900 mb-2">Gestion Produits</h3>
            <p class="text-sm text-purple-700">
              Créer, éditer et gérer tous les produits
            </p>
          </router-link>

          <!-- Stats (placeholder) -->
          <div class="bg-gradient-to-br from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
            <div class="text-3xl mb-3">📊</div>
            <h3 class="text-lg font-semibold text-green-900 mb-2">Statistiques</h3>
            <p class="text-sm text-green-700">
              Vue d'ensemble des ventes et activités
            </p>
          </div>

          <!-- Commandes (placeholder) -->
          <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 p-6 rounded-lg border border-yellow-200">
            <div class="text-3xl mb-3">🛒</div>
            <h3 class="text-lg font-semibold text-yellow-900 mb-2">Commandes</h3>
            <p class="text-sm text-yellow-700">
              Suivre toutes les commandes
            </p>
          </div>

          <!-- Paramètres (placeholder) -->
          <div class="bg-gradient-to-br from-gray-50 to-gray-100 p-6 rounded-lg border border-gray-200">
            <div class="text-3xl mb-3">⚙️</div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Paramètres</h3>
            <p class="text-sm text-gray-700">
              Configuration de la plateforme
            </p>
          </div>
        </div>

        <!-- Info compte -->
        <div class="mt-8 pt-6 border-t border-gray-200">
          <h3 class="text-lg font-medium text-gray-900 mb-3">Informations du compte</h3>
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
              <dd class="font-medium text-indigo-600">Administrateur</dd>
            </div>
          </dl>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>