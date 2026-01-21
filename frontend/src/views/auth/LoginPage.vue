<template>
  <div class="bg-[#f7f6f3] min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#e6dfd1] border-b border-gray-300">
      <div class="max-w-6xl mx-auto px-6 py-12 text-center"> <!-- padding réduit -->
        
        <h1 class="text-3xl md:text-4xl font-serif text-[#3b2f2f] mb-4"> <!-- taille titre réduite -->
          Authentification
        </h1>
        <p class="text-gray-600 max-w-xl mx-auto text-sm"> <!-- texte légèrement plus petit -->
         Chaque pièce a son histoire. Connectez-vous et faites partie de notre collection intemporelle
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="flex-grow flex items-center justify-center px-6 py-20">
      <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-[#fffaf5] border border-[#d1c4b1] rounded-2xl shadow-lg p-8">

          <!-- Error -->
          <div
            v-if="authStore.error"
            class="mb-6 p-4 bg-[#f8e6e0] border border-[#d29a85] rounded-lg text-sm text-[#a33c2c]"
          >
            {{ authStore.error }}
          </div>

          <form @submit.prevent="handleLogin" class="space-y-6">
            <!-- Email -->
            <div>
              <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-2">
                Email
              </label>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="votre@email.com"
                class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                       focus:outline-none focus:ring-2 focus:ring-[#3b2f2f]
                       focus:border-transparent transition"
              />
            </div>

            <!-- Password -->
            <div>
              <label class="block text-xs uppercase tracking-wider text-[#5a4a3a] mb-2">
                Mot de passe
              </label>
              <input
                v-model="form.password"
                type="password"
                required
                placeholder="••••••••"
                class="w-full px-4 py-3 rounded-lg border border-[#d1c4b1] bg-[#fffaf5]
                       focus:outline-none focus:ring-2 focus:ring-[#3b2f2f]
                       focus:border-transparent transition"
              />
            </div>

            <!-- Button -->
            <button
              type="submit"
              :disabled="authStore.loading"
              class="w-full py-3 rounded-lg bg-[#3b2f2f] text-[#fffaf5] font-medium
                     hover:bg-[#5a4a3a] transition-all
                     focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3b2f2f]
                     disabled:opacity-50"
            >
              {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
            </button>
          </form>

          <!-- Divider -->
          <div class="my-10 flex items-center gap-4">
            <span class="flex-1 h-px bg-[#d1c4b1]"></span>
            <span class="text-xs uppercase tracking-widest text-[#5a4a3a]">
              Nouveau ici ?
            </span>
            <span class="flex-1 h-px bg-[#d1c4b1]"></span>
          </div>

          <!-- Register links -->
          <div class="space-y-3">
            <router-link
              to="/register-client"
              class="block w-full py-3 rounded-lg border border-[#d1c4b1] text-center
                     text-[#3b2f2f] hover:border-[#3b2f2f] hover:text-[#3b2f2f]
                     transition font-medium"
            >
              Créer un compte client
            </router-link>

            <router-link
              to="/register-vendor"
              class="block w-full py-3 rounded-lg border border-[#3b2f2f]
                     text-center text-[#3b2f2f] hover:bg-[#fff1e0]
                     transition font-medium"
            >
              Devenir vendeur
            </router-link>
          </div>

        </div>
      </div>
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

const router = useRouter()
const authStore = useAuthStore()

const form = reactive({
  email: '',
  password: ''
})

const handleLogin = async () => {
  try {
    await authStore.login(form)
    await new Promise(resolve => setTimeout(resolve, 10))
    const user = authStore.user

    if (!user) throw new Error('Utilisateur non défini après login')

    window.dispatchEvent(new Event('authChange'))

    if (user.role === 'admin') {
      await router.push('/admin/dashboard')
    } else if (user.role === 'vendor' || user.role === 'vendeur') {
      await router.push('/vendor/dashboard')
    } else {
      await router.push('/client/dashboard')
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
  }
}
</script>
