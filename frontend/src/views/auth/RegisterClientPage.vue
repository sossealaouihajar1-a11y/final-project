<template>
  <div class="bg-white min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Inscription
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Créer un compte client
        </h1>
        <p class="text-gray-600 max-w-2xl">
          Rejoignez notre communauté et accédez à tous nos services.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">
      <div class="w-full max-w-2xl mx-auto">
        <!-- Succès -->
        <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-sm text-green-800">✅ Inscription réussie ! Redirection...</p>
        </div>

        <!-- Erreur -->
        <div v-if="authStore.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-sm text-red-800">{{ authStore.error }}</p>
          <div v-if="errors" class="mt-2 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
              <li v-for="(error, field) in errors" :key="field">
                {{ error[0] }}
              </li>
            </ul>
          </div>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="Ahmed Ben Ali"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="ahmed@example.com"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
              <input
                v-model="form.phone"
                type="tel"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="0612345678"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
              <input
                v-model="form.city"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="Casablanca"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
              <input
                v-model="form.address"
                type="text"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="123 Rue Mohammed V"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="••••••••"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer *</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
                placeholder="••••••••"
              />
            </div>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading || success"
            class="w-full py-3 px-4 border border-transparent rounded-lg text-white bg-[#8b1c3d] hover:bg-[#5a4a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8b1c3d] disabled:opacity-50 transition-all font-medium"
          >
            {{ authStore.loading ? 'Inscription...' : "S'inscrire" }}
          </button>

          <div class="text-center">
            <router-link to="/login" class="text-sm text-[#8b1c3d] hover:text-[#5a4a3a]">
              ← Retour à la connexion
            </router-link>
          </div>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

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