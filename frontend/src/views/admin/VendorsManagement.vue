<template>
  <div class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center space-x-4">
            <router-link to="/admin/dashboard" class="text-gray-700 hover:text-gray-900 font-medium">
              Dashboard
            </router-link>
            <span class="text-indigo-600 font-semibold">Gestion Vendeurs</span>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-gray-700">{{ userName }}</span>
            <button @click="handleLogout" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
              Déconnexion
            </button>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Gestion des Vendeurs</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-600">Total</div>
            <div class="text-2xl font-bold text-gray-900">{{ stats.total }}</div>
          </div>
          <div class="bg-yellow-50 p-4 rounded-lg shadow border-l-4 border-yellow-500">
            <div class="text-sm text-yellow-800">En Attente</div>
            <div class="text-2xl font-bold text-yellow-900">{{ stats.pending }}</div>
          </div>
          <div class="bg-green-50 p-4 rounded-lg shadow border-l-4 border-green-500">
            <div class="text-sm text-green-800">Approuvés</div>
            <div class="text-2xl font-bold text-green-900">{{ stats.approved }}</div>
          </div>
          <div class="bg-red-50 p-4 rounded-lg shadow border-l-4 border-red-500">
            <div class="text-sm text-red-800">Rejetés</div>
            <div class="text-2xl font-bold text-red-900">{{ stats.rejected }}</div>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap gap-3">
          <button @click="filterStatus = null" :class="getButtonClass(null)" class="px-4 py-2 rounded-md text-sm font-medium">
            Tous
          </button>
          <button @click="filterStatus = 'pending'" :class="getButtonClass('pending')" class="px-4 py-2 rounded-md text-sm font-medium">
            En attente
          </button>
          <button @click="filterStatus = 'approved'" :class="getButtonClass('approved')" class="px-4 py-2 rounded-md text-sm font-medium">
            Approuvés
          </button>
          <button @click="filterStatus = 'rejected'" :class="getButtonClass('rejected')" class="px-4 py-2 rounded-md text-sm font-medium">
            Rejetés
          </button>
        </div>
      </div>

      <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
        <p class="text-sm text-green-800">{{ successMessage }}</p>
      </div>

      <div v-if="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <p class="text-sm text-red-800">{{ errorMessage }}</p>
      </div>

      <div v-if="loading" class="bg-white rounded-lg shadow p-8 text-center">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
        <p class="mt-2 text-gray-600">Chargement...</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="vendor in filteredVendors" :key="vendor.id" class="bg-white rounded-lg shadow-md overflow-hidden">
          <div class="p-6">
            <div class="flex items-start justify-between mb-4">
              <div>
                <h3 class="text-xl font-bold text-gray-900">{{ vendor.name }}</h3>
                <p class="text-sm text-gray-500">{{ vendor.email }}</p>
              </div>
              <span :class="getStatusClass(vendor.vendor_status)" class="px-4 py-2 rounded-full text-sm font-semibold">
                {{ getStatusLabel(vendor.vendor_status) }}
              </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
              <div class="space-y-2">
                <h4 class="font-semibold text-gray-700 border-b pb-1">Contact</h4>
                <div class="space-y-1 text-sm">
                  <p><span class="text-gray-600">Email:</span> <span class="font-medium">{{ vendor.email }}</span></p>
                  <p><span class="text-gray-600">Téléphone:</span> <span class="font-medium">{{ vendor.phone || 'N/A' }}</span></p>
                </div>
              </div>

              <div class="space-y-2">
                <h4 class="font-semibold text-gray-700 border-b pb-1">Localisation</h4>
                <div class="space-y-1 text-sm">
                  <p><span class="text-gray-600">Ville:</span> <span class="font-medium">{{ vendor.city || 'N/A' }}</span></p>
                  <p><span class="text-gray-600">Adresse:</span> <span class="font-medium">{{ vendor.address || 'N/A' }}</span></p>
                </div>
              </div>

              <div class="space-y-2">
                <h4 class="font-semibold text-gray-700 border-b pb-1">Identité</h4>
                <div class="space-y-2 text-sm">
                  <p><span class="text-gray-600">Type:</span> <span class="font-medium">{{ getIdentityType(vendor.identity_type) }}</span></p>
                  <a v-if="vendor.identity_document" :href="getDocumentUrl(vendor.identity_document)" target="_blank" class="inline-block px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700">
                    Voir le document
                  </a>
                  <p v-else class="text-gray-400 italic">Aucun document</p>
                </div>
              </div>
            </div>

            <div class="flex flex-wrap gap-2 pt-4 border-t">
              <button v-if="vendor.vendor_status === 'pending'" @click="approveVendor(vendor.id)" :disabled="actionLoading" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium disabled:opacity-50">
                Approuver
              </button>
              <button v-if="vendor.vendor_status === 'pending'" @click="rejectVendor(vendor.id)" :disabled="actionLoading" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium disabled:opacity-50">
                Rejeter
              </button>
              <button v-if="vendor.vendor_status === 'approved'" @click="suspendVendor(vendor.id)" :disabled="actionLoading" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 text-sm font-medium disabled:opacity-50">
                Suspendre
              </button>
              <button v-if="vendor.vendor_status === 'suspended'" @click="reactivateVendor(vendor.id)" :disabled="actionLoading" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium disabled:opacity-50">
                Réactiver
              </button>
              <button v-if="vendor.vendor_status === 'rejected'" @click="approveVendor(vendor.id)" :disabled="actionLoading" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm font-medium disabled:opacity-50">
                Approuver
              </button>
            </div>
          </div>
        </div>

        <div v-if="filteredVendors.length === 0" class="bg-white rounded-lg shadow p-12 text-center">
          <p class="text-gray-500">Aucun vendeur trouvé</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import authService from '@/services/authService'

const router = useRouter()
const authStore = useAuthStore()

const vendors = ref([])
const loading = ref(false)
const actionLoading = ref(false)
const filterStatus = ref(null)
const successMessage = ref('')
const errorMessage = ref('')

const userName = computed(() => authStore.user?.name || 'Admin')

const stats = computed(() => {
  return {
    total: vendors.value.length,
    pending: vendors.value.filter(v => v.vendor_status === 'pending').length,
    approved: vendors.value.filter(v => v.vendor_status === 'approved').length,
    rejected: vendors.value.filter(v => v.vendor_status === 'rejected').length
  }
})

const filteredVendors = computed(() => {
  if (!filterStatus.value) return vendors.value
  return vendors.value.filter(v => v.vendor_status === filterStatus.value)
})

const getButtonClass = (status) => {
  if (filterStatus.value === status) {
    if (status === 'pending') return 'bg-yellow-600 text-white'
    if (status === 'approved') return 'bg-green-600 text-white'
    if (status === 'rejected') return 'bg-red-600 text-white'
    return 'bg-indigo-600 text-white'
  }
  return 'bg-gray-200 text-gray-700'
}

const getStatusClass = (status) => {
  if (status === 'pending') return 'bg-yellow-100 text-yellow-800'
  if (status === 'approved') return 'bg-green-100 text-green-800'
  if (status === 'rejected') return 'bg-red-100 text-red-800'
  if (status === 'suspended') return 'bg-gray-100 text-gray-800'
  return 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'En attente',
    approved: 'Approuvé',
    rejected: 'Rejeté',
    suspended: 'Suspendu'
  }
  return labels[status] || status
}

const getIdentityType = (type) => {
  if (type === 'cin') return 'CIN'
  if (type === 'passport') return 'Passeport'
  return 'N/A'
}

const getDocumentUrl = (path) => {
  return `http://localhost:8000/storage/${path}`
}

const loadVendors = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await authService.getAllVendors()
    vendors.value = response || []
  } catch (error) {
    errorMessage.value = 'Erreur lors du chargement'
    vendors.value = []
  } finally {
    loading.value = false
  }
}

const approveVendor = async (vendorId) => {
  if (!confirm('Approuver ce vendeur ?')) return
  actionLoading.value = true
  try {
    await authService.approveVendor(vendorId)
    successMessage.value = 'Vendeur approuvé'
    setTimeout(() => successMessage.value = '', 3000)
    await loadVendors()
  } catch (error) {
    errorMessage.value = 'Erreur'
    setTimeout(() => errorMessage.value = '', 3000)
  } finally {
    actionLoading.value = false
  }
}

const rejectVendor = async (vendorId) => {
  if (!confirm('Rejeter ce vendeur ?')) return
  actionLoading.value = true
  try {
    await authService.rejectVendor(vendorId)
    successMessage.value = 'Vendeur rejeté'
    setTimeout(() => successMessage.value = '', 3000)
    await loadVendors()
  } catch (error) {
    errorMessage.value = 'Erreur'
    setTimeout(() => errorMessage.value = '', 3000)
  } finally {
    actionLoading.value = false
  }
}

const suspendVendor = async (vendorId) => {
  if (!confirm('Suspendre ce vendeur ?')) return
  actionLoading.value = true
  try {
    await authService.suspendVendor(vendorId)
    successMessage.value = 'Vendeur suspendu'
    setTimeout(() => successMessage.value = '', 3000)
    await loadVendors()
  } catch (error) {
    errorMessage.value = 'Erreur'
    setTimeout(() => errorMessage.value = '', 3000)
  } finally {
    actionLoading.value = false
  }
}

const reactivateVendor = async (vendorId) => {
  if (!confirm('Réactiver ce vendeur ?')) return
  actionLoading.value = true
  try {
    await authService.reactivateVendor(vendorId)
    successMessage.value = 'Vendeur réactivé'
    setTimeout(() => successMessage.value = '', 3000)
    await loadVendors()
  } catch (error) {
    errorMessage.value = 'Erreur'
    setTimeout(() => errorMessage.value = '', 3000)
  } finally {
    actionLoading.value = false
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

onMounted(() => {
  loadVendors()
})
</script>