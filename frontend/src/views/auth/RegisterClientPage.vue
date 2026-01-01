<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-cyan-100 py-12 px-4">
    <div class="max-w-2xl w-full bg-white p-8 rounded-xl shadow-2xl">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">
          👤 Créer un compte client
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Ou
          <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
            connectez-vous
          </router-link>
        </p>
      </div>

      <!-- Succès -->
      <div v-if="success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-green-800">✅ Inscription réussie ! Redirection...</p>
      </div>

      <!-- Erreur -->
      <div v-if="authStore.error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-800">{{ authStore.error }}</p>
        <div v-if="errors" class="mt-2 text-sm text-red-700">
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="(error, field) in errors" :key="field">
              {{ error[0] }}
            </li>
          </ul>
        </div>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="Ahmed Ben Ali"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="ahmed@example.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input
              v-model="form.phone"
              type="tel"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="0612345678"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
            <input
              v-model="form.city"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="Casablanca"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
            <input
              v-model="form.address"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="123 Rue Mohammed V"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="••••••••"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer *</label>
            <input
              v-model="form.password_confirmation"
              type="password"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="••••••••"
            />
          </div>
        </div>

        <button
          type="submit"
          :disabled="authStore.loading || success"
          class="w-full py-2.5 px-4 border border-transparent rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 transition-all"
        >
          {{ authStore.loading ? 'Inscription...' : "S'inscrire" }}
        </button>

        <div class="text-center">
          <router-link to="/login" class="text-sm text-indigo-600 hover:text-indigo-500">
            ← Retour à la connexion
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()
const success = ref(false)
const errors = ref(null)

const form = reactive({
  name: '',
  email: '',
  phone: '',
  city: '',
  address: '',
  password: '',
  password_confirmation: ''
})

const handleRegister = async () => {
  errors.value = null
  try {
    await authStore.registerClient(form)
    success.value = true
    
    setTimeout(() => {
      router.push('/client/dashboard')
    }, 1500)
  } catch (error) {
    errors.value = error.response?.data?.errors
    console.error('Erreur inscription:', error)
  }
}
</script>