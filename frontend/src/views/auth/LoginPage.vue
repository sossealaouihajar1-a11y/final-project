<template>
  <div class="bg-white min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Authentification
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Connexion
        </h1>
        <p class="text-gray-600 max-w-2xl">
          Accédez à votre compte pour continuer vos achats et gérer vos commandes.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow flex items-center">
      <div class="w-full max-w-md mx-auto">
        <!-- Erreur -->
        <div v-if="authStore.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-sm text-red-800">{{ authStore.error }}</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              placeholder="votre@email.com"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Mot de passe
            </label>
            <input
              v-model="form.password"
              type="password"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
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
            class="w-full py-3 px-4 border border-transparent rounded-lg text-white bg-[#8b1c3d] hover:bg-[#5a4a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8b1c3d] disabled:opacity-50 transition-all font-medium"
          >
            {{ authStore.loading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </form>

        <!-- Register links -->
        <div class="mt-8 pt-8 border-t border-gray-200">
          <p class="text-sm text-gray-600 text-center mb-4">
            Vous n'avez pas de compte ?
          </p>
          <div class="space-y-3">
            <router-link to="/register-client" class="block w-full py-3 px-4 border-2 border-gray-300 rounded-lg text-center text-gray-700 hover:border-[#8b1c3d] hover:text-[#8b1c3d] transition-all font-medium">
              Créer un compte client
            </router-link>
            <router-link to="/register-vendor" class="block w-full py-3 px-4 border-2 border-[#8b1c3d] rounded-lg text-center text-[#8b1c3d] hover:bg-[#f2f1ed] transition-all font-medium">
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

    // Attendre un peu pour être sûr que la session est établie
    await new Promise(resolve => setTimeout(resolve, 10))
    
    const user = authStore.user
    
    console.log('User après login:', user) // DEBUG
    console.log('User role:', user?.role) // DEBUG
    
    if (!user) {
      throw new Error('Utilisateur non défini après login')
    }
    
    // Dispatcher un événement pour notifier les autres composants (comme Header)
    window.dispatchEvent(new Event('authChange'))
    
    // Redirection selon le rôle
    if (user.role === 'admin') {
      console.log('Redirection vers admin dashboard')
      await router.push('/admin/dashboard')
    } else if (user.role === 'vendor' || user.role === 'vendeur') {
      console.log('Redirection vers vendor dashboard')
      await router.push('/vendor/dashboard')
    } else {
      console.log('Redirection vers client dashboard')
      await router.push('/client/dashboard')
    }
  } catch (error) {
    console.error('Erreur de connexion:', error)
  }
}
</script>