<template>
  <div class="bg-[#f7f6f3] min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#e6dfd1] border-b border-gray-300">
      <div class="max-w-6xl mx-auto px-6 py-12 text-center">
        <h1 class="text-3xl md:text-4xl font-serif text-[#3b2f2f] mb-4">
          Créer un compte client
        </h1>
        <p class="text-gray-600 max-w-xl mx-auto text-sm">
          Rejoignez notre communauté et accédez à tous nos services. Chaque inscription fait partie de notre collection intemporelle.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="flex-grow flex items-center justify-center px-6 py-20">
      <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-[#fffaf5] border border-[#d1c4b1] rounded-2xl shadow-lg p-8 space-y-6">

          <!-- Success Alert -->
          <div v-if="success" class="flex items-center gap-3 p-4 rounded-lg border border-[#a6d5ba] bg-[#e0f4e9] text-[#556b2f] text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-7l3-3-1.414-1.414L9 8.586 7.414 7 6 8.414l3 3z" clip-rule="evenodd" />
            </svg>
            <span>✅ Inscription réussie ! Redirection...</span>
          </div>

          <!-- Error Alert -->
          <div v-if="authStore.error" class="flex items-start gap-3 p-4 rounded-lg border border-[#a6d5ba] bg-[#e0f4e9] text-[#556b2f] text-sm">
            <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9V5h2v4h-2zm0 4h2v-2h-2v2z" clip-rule="evenodd" />
            </svg>
            <div>
              {{ authStore.error }}
              <div v-if="errors" class="mt-1 text-[#556b2f]">
                <ul class="list-disc pl-5 space-y-1">
                  <li v-for="(error, field) in errors" :key="field">
                    {{ error[0] }}
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Form -->
          <form @submit.prevent="handleRegister" class="space-y-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="md:col-span-2">
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Nom complet *</label>
                <input v-model="form.name" type="text" required placeholder="Ahmed Ben Ali"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div>
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Email *</label>
                <input v-model="form.email" type="email" required placeholder="ahmed@example.com"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div>
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Téléphone</label>
                <input v-model="form.phone" type="tel" placeholder="0612345678"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div>
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Ville</label>
                <input v-model="form.city" type="text" placeholder="Casablanca"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div class="md:col-span-2">
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Adresse</label>
                <input v-model="form.address" type="text" placeholder="123 Rue Mohammed V"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div>
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Mot de passe *</label>
                <input v-model="form.password" type="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>

              <div>
                <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-1">Confirmer *</label>
                <input v-model="form.password_confirmation" type="password" required placeholder="••••••••"
                       class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                              focus:outline-none focus:ring-2 focus:ring-[#556b2f] focus:border-transparent transition"/>
              </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="authStore.loading || success"
                    class="w-full py-3 rounded-lg bg-[#3b2f2f] text-[#fffaf5] font-medium
                           hover:bg-[#5a4a3a] transition-all
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3b2f2f]
                           disabled:opacity-50">
              {{ authStore.loading ? 'Inscription...' : "S'inscrire" }}
            </button>

            <div class="text-center mt-4 text-sm">
              <router-link to="/login" class="text-[#3b2f2f] hover:text-[#5a4a3a] transition">
                ← Retour à la connexion
              </router-link>
            </div>

          </form>
        </div>
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
