<template>
  <div class="bg-white min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Partenariat
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Devenir Vendeur
        </h1>
        <p class="text-gray-600 max-w-2xl">
          Rejoignez notre plateforme et commercialisez vos produits vintage auprès de milliers de clients.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">
      <div class="w-full max-w-3xl mx-auto">
        <!-- Succès -->
        <div v-if="success" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
          <p class="text-sm text-green-800">✅ Votre demande a été soumise ! Redirection...</p>
        </div>

        <!-- Erreur -->
        <div v-if="authStore.error" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <p class="text-sm text-red-800">{{ authStore.error }}</p>
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
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
              <input
                v-model="form.phone"
                type="tel"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ville *</label>
              <input
                v-model="form.city"
                type="text"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Adresse *</label>
              <input
                v-model="form.address"
                type="text"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Type d'identité *</label>
              <select
                v-model="form.identity_type"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              >
                <option value="">Sélectionnez...</option>
                <option value="cin">CIN</option>
                <option value="passport">Passeport</option>
              </select>
            </div>

            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Document d'identité * (PDF, JPG, PNG - Max 2MB)
              </label>
              <input
                type="file"
                required
                accept=".pdf,.jpg,.jpeg,.png"
                @change="handleFileChange"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
              <p v-if="fileName" class="mt-2 text-sm text-green-600">
                ✓ {{ fileName }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer *</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>
          </div>

          <div class="rounded-md bg-blue-50 p-4 border border-blue-200">
            <p class="text-sm text-blue-700">
              📋 Votre compte sera validé par l'administrateur sous 48h
            </p>
          </div>

          <button
            type="submit"
            :disabled="authStore.loading || success"
            class="w-full py-3 px-4 border border-transparent rounded-lg text-white bg-[#8b1c3d] hover:bg-[#5a4a3a] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#8b1c3d] disabled:opacity-50 transition-all font-medium"
          >
            {{ authStore.loading ? 'Envoi...' : 'Soumettre ma candidature' }}
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
const fileName = ref('')

const form = reactive({
  name: '',
  email: '',
  phone: '',
  city: '',
  address: '',
  identity_type: '',
  identity_document: null,
  password: '',
  password_confirmation: ''
})

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    if (file.size > 2 * 1024 * 1024) {
      alert('Fichier trop volumineux (max 2MB)')
      event.target.value = ''
      return
    }
    form.identity_document = file
    fileName.value = file.name
  }
}

const handleRegister = async () => {
  if (!form.identity_document) {
    alert('Veuillez sélectionner votre document d\'identité')
    return
  }

  const formData = new FormData()
  Object.keys(form).forEach(key => {
    formData.append(key, form[key])
  })

  try {
    await authStore.registerVendor(formData)
    success.value = true

    setTimeout(() => {
      router.push('/login')
    }, 3000)
  } catch (error) {
    console.error('Erreur inscription:', error)
  }
}
</script>