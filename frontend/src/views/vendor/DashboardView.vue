<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-gray-900">Dashboard Vendeur</h1>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-gray-700">{{ user?.name || 'Vendeur' }}</span>
            <button @click="handleLogout" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Bienvenue !</h2>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-4">
          <p class="text-sm text-green-700">Vous êtes connecté en tant que Vendeur</p>
        </div>
        <div v-if="user?.vendor_status" class="bg-white border border-gray-200 rounded-lg p-4">
          <h3 class="font-medium text-gray-900 mb-2">Statut</h3>
          <span v-if="user.vendor_status === 'approved'" class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
            Approuvé
          </span>
          <span v-else-if="user.vendor_status === 'pending'" class="px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
            En attente
          </span>
          <span v-else-if="user.vendor_status === 'rejected'" class="px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-800">
            Rejeté
          </span>
          <span v-else class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
            {{ user.vendor_status }}
          </span>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()
const user = computed(() => authStore.user)

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>