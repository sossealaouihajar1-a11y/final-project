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
            <span class="text-gray-700">{{ user?.name || 'Admin' }}</span>
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
          <button @click="filterStatus = null" :class="filterStatus === null ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-md text-sm font-medium">
            Tous
          </button>
          <button @click="filterStatus = 'pending'" :class="filterStatus === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-md text-sm font-medium">
            En attente
          </button>
          <button @click="filterStatus = 'approved'" :class="filterStatus === 'approved' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-md text-sm font-medium">
            Approuvés
          </button>
          <button @click="filterStatus = 'rejected'" :class="filterStatus === 'rejected' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-md text-sm font-medium">
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

      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendeur</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ville</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="vendor in filteredVendors" :key="vendor.id" class="hover:bg-gray-50">
                <td class="px-6 py-4">
                  <div class="text-sm font-medium text-gray-900">{{ vendor.name }}</div>
                  <div class="text-xs text-gray-500">{{ vendor.email }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ vendor.phone || 'N/A' }}</div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900">{{ vendor.city || 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span v-if="vendor.vendor_status === 'pending'" class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                    En attente
                  </span>
                  <span v-else-if="vendor.vendor_status === 'approved'" class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                    Approuvé
                  </span>
                  <span v-else-if="vendor.vendor_status === 'rejected'" class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                    Rejeté
                  </span>
                  <span v-else class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                    {{ vendor.vendor_status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex flex-col space-y-2">
                    <div v-if="vendor.vendor_status === 'pending'" class="flex space-x-2">
                      <button @click="approveVendor(vendor.id)" :disabled="actionLoading" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-medium disabled:opacity-50">
                        Approuver
                      </button>
                      <button @click="rejectVendor(vendor.id)" :disabled="actionLoading" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-medium disabled:opacity-50">
                        Rejeter
                      </button>
                    </div>
                    <div v-if="vendor.vendor_status === 'approved'" class="flex space-x-2">
                      <button @click="suspendVendor(vendor.id)" :disabled="actionLoading" class="px-3 py-1 bg-orange-600 text-white rounded hover:bg-orange-700 text-xs font-medium disabled:opacity-50">
                        Suspendre
                      </button>
                    </div>
                    <div v-if="vendor.vendor_status === 'suspended'" class="flex space-x-2">
                      <button @click="reactivateVendor(vendor.id)" :disabled="actionLoading" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium disabled:opacity-50">
                        Réactiver
                      </button>
                    </div>
                    <div v-if="vendor.vendor_status === 'rejected'" class="flex space-x-2">
                      <button @click="approveVendor(vendor.id)" :disabled="actionLoading" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-medium disabled:opacity-50">
                        Approuver
                      </button>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-if="filteredVendors.length === 0" class="text-center py-12">
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
const user = computed(() => authStore.user)

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