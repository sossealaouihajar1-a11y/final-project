<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-100 to-emerald-100 py-12 px-4">
    <div class="max-w-3xl w-full bg-white p-8 rounded-xl shadow-2xl">
      <div class="text-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-900">
          🏪 Devenir vendeur
        </h2>
        <p class="mt-2 text-sm text-gray-600">
          Ou
          <router-link to="/login" class="font-medium text-green-600 hover:text-green-500">
            connectez-vous
          </router-link>
        </p>
      </div>

      <!-- Succès -->
      <div v-if="success" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-green-800">✅ Votre demande a été soumise ! Redirection...</p>
      </div>

      <!-- Erreur -->
      <div v-if="authStore.error" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-800">{{ authStore.error }}</p>
      </div>

      <form @submit.prevent="handleRegister" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
            <input
              v-model="form.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input v-model="form.email" type="email" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
            <input v-model="form.phone" type="tel" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
            <input v-model="form.city" type="text" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
            <input v-model="form.address" type="text" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type d'identité *</label>
            <select v-model="form.identity_type" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            >
              <option value="">Sélectionnez...</option>
              <option value="cin">CIN</option>
              <option value="passport">Passeport</option>
            </select>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Document d'identité * (PDF, JPG, PNG - Max 2MB)
            </label>
            <input
              type="file"
              required
              accept=".pdf,.jpg,.jpeg,.png"
              @change="handleFileChange"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg"
            />
            <p v-if="fileName" class="mt-1 text-sm text-green-600">
              ✓ {{ fileName }}
            </p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe *</label>
            <input v-model="form.password" type="password" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer *</label>
            <input v-model="form.password_confirmation" type="password" required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
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
          class="w-full py-2.5 px-4 border border-transparent rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50"
        >
          {{ authStore.loading ? 'Envoi...' : 'Soumettre ma candidature' }}
        </button>

        <div class="text-center">
          <router-link to="/login" class="text-sm text-green-600 hover:text-green-500">
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