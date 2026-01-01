<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100 py-12 px-4">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-2xl">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">
          🛍️ Connexion
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Ou
          <router-link to="/register-client" class="font-medium text-indigo-600 hover:text-indigo-500">
            créez un compte client
          </router-link>
          /
          <router-link to="/register-vendor" class="font-medium text-indigo-600 hover:text-indigo-500">
            devenez vendeur
          </router-link>
        </p>
      </div>

      <!-- Erreur -->
      <div v-if="authStore.error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-800">{{ authStore.error }}</p>
      </div>

      <form @submit.prevent="handleLogin" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Email
          </label>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="votre@email.com"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Mot de passe
          </label>
          <input
            v-model="form.password"
            type="password"
            required
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="••••••••"
          />
        </div>

        <div class="rounded-md bg-yellow-50 p-3 border border-yellow-200">
          <p class="text-xs text-yellow-800">
            ⚠️ Vous serez déconnecté si vous fermez cet onglet
          </p>
        </div>

        <button
          type="submit"
          :disabled="authStore.loading"
          class="w-full py-2.5 px-4 border border-transparent rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all"
        >
          {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
        </button>
      </form>

     
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    await authStore.login(form)
    
    // Attendre un peu pour être sûr que la session est établie
    await new Promise(resolve => setTimeout(resolve, 100))
    
    const user = authStore.user
    
    console.log('User après login:', user) // DEBUG
    
    if (!user) {
      throw new Error('Utilisateur non défini après login')
    }
    
    if (user.role === 'admin') {
      router.push('/admin/dashboard')
    } else if (user.role === 'vendeur') {
      router.push('/vendor/dashboard')
    } else {
      router.push('/client/dashboard')
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
  }
}
</script>