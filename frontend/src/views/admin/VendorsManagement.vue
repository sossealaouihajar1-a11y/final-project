<template>
  <div class="min-h-screen bg-[#f6f3ee] text-[#2a2a28] font-serif">
    
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Administration
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Gestion des Vendeurs
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Approuvez, rejetez ou suspendez les vendeurs
        </p>
      </div>
    </section>

    <!-- Content -->
    <main class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Total</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ stats.total }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">En attente</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ stats.pending }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Approuvés</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ stats.approved }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Rejetés</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ stats.rejected }}</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="flex flex-wrap gap-3">
          <button
            @click="filterStatus = null"
            :class="filterStatus === null ? 'bg-[#4a3728] text-white' : 'bg-[#fbfaf7] text-[#4a3728] hover:bg-[#f4f1eb]'"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold transition"
          >
            Tous
          </button>
          <button
            @click="filterStatus = 'pending'"
            :class="filterStatus === 'pending' ? 'bg-[#6b7b4b] text-white' : 'bg-[#fbfaf7] text-[#4a3728] hover:bg-[#f4f1eb]'"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold transition"
          >
            En attente
          </button>
          <button
            @click="filterStatus = 'approved'"
            :class="filterStatus === 'approved' ? 'bg-[#4a3728] text-white' : 'bg-[#fbfaf7] text-[#4a3728] hover:bg-[#f4f1eb]'"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold transition"
          >
            Approuvés
          </button>
          <button
            @click="filterStatus = 'rejected'"
            :class="filterStatus === 'rejected' ? 'bg-[#8b1c3d] text-white' : 'bg-[#fbfaf7] text-[#4a3728] hover:bg-[#f4f1eb]'"
            class="px-5 py-2.5 rounded-lg text-sm font-semibold transition"
          >
            Rejetés
          </button>
        </div>
      </div>

      <!-- Vendors List -->
      <div class="space-y-6">
        <div
          v-for="vendor in filteredVendors"
          :key="vendor.id"
          class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden hover:bg-[#f4f1eb] transition"
        >
          <div class="p-6">

            <!-- Header -->
            <div class="flex items-start justify-between mb-6">
              <div>
                <h3 class="text-2xl font-serif font-bold text-[#4a3728] mb-1">{{ vendor.name }}</h3>
                <p class="text-sm text-[#5a564f]">{{ vendor.email }}</p>
              </div>
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800': vendor.vendor_status === 'pending',
                  'bg-green-100 text-green-800': vendor.vendor_status === 'approved',
                  'bg-red-100 text-red-800': vendor.vendor_status === 'rejected',
                  'bg-gray-100 text-gray-800': vendor.vendor_status === 'suspended'
                }"
                class="px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider"
              >
                {{ getStatusLabel(vendor.vendor_status) }}
              </span>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 pb-6 border-b border-[#d6cdbf]">
              <div class="space-y-3">
                <h4 class="text-xs font-semibold text-[#6b7b4b] uppercase tracking-wider">Contact</h4>
                <p class="text-sm text-[#5a564f]">Email: {{ vendor.email }}</p>
                <p class="text-sm text-[#5a564f]">Téléphone: {{ vendor.phone || 'Non renseigné' }}</p>
              </div>
              <div class="space-y-3">
                <h4 class="text-xs font-semibold text-[#6b7b4b] uppercase tracking-wider">Localisation</h4>
                <p class="text-sm text-[#5a564f]">Ville: {{ vendor.city || 'Non renseignée' }}</p>
                <p class="text-sm text-[#5a564f]">Adresse: {{ vendor.address || 'Non renseignée' }}</p>
              </div>
              <div class="space-y-3">
                <h4 class="text-xs font-semibold text-[#6b7b4b] uppercase tracking-wider">Identité</h4>
                <p class="text-sm text-[#5a564f]">Type: {{ getIdentityType(vendor.identity_type) }}</p>
                <a
                  v-if="vendor.identity_document"
                  :href="getDocumentUrl(vendor.identity_document)"
                  target="_blank"
                  class="inline-flex items-center px-4 py-2 bg-[#4a3728] text-white text-xs font-semibold rounded-lg hover:bg-[#6b7b4b] transition"
                >
                  Voir le document
                </a>
                <p v-else class="text-gray-400 italic">Aucun document</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3">
              <button
                v-if="vendor.vendor_status === 'pending'"
                @click="approveVendor(vendor.id)"
                :disabled="actionLoading"
                class="px-5 py-2.5 bg-[#6b7b4b] text-white rounded-lg hover:bg-[#4a3728] text-sm font-semibold disabled:opacity-50 transition"
              >
                Approuver
              </button>
              <button
                v-if="vendor.vendor_status === 'pending'"
                @click="rejectVendor(vendor.id)"
                :disabled="actionLoading"
                class="px-5 py-2.5 bg-[#8b1c3d] text-white rounded-lg hover:bg-[#6d1530] text-sm font-semibold disabled:opacity-50 transition"
              >
                Rejeter
              </button>
              <button
                v-if="vendor.vendor_status === 'approved'"
                @click="suspendVendor(vendor.id)"
                :disabled="actionLoading"
                class="px-5 py-2.5 bg-[#f4a261] text-white rounded-lg hover:bg-[#e07b3c] text-sm font-semibold disabled:opacity-50 transition"
              >
                Suspendre
              </button>
              <button
                v-if="vendor.vendor_status === 'suspended'"
                @click="reactivateVendor(vendor.id)"
                :disabled="actionLoading"
                class="px-5 py-2.5 bg-[#4a3728] text-white rounded-lg hover:bg-[#6b7b4b] text-sm font-semibold disabled:opacity-50 transition"
              >
                Réactiver
              </button>
              <button
                v-if="vendor.vendor_status === 'rejected'"
                @click="approveVendor(vendor.id)"
                :disabled="actionLoading"
                class="px-5 py-2.5 bg-[#6b7b4b] text-white rounded-lg hover:bg-[#4a3728] text-sm font-semibold disabled:opacity-50 transition"
              >
                Approuver
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredVendors.length === 0" class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-12 text-center text-[#5a564f]">
          Aucun vendeur trouvé
        </div>
      </div>
    </main>
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import Header from '@/components/Header.vue'
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
  return 'Non renseigné'
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
    errorMessage.value = 'Erreur lors du chargement des vendeurs'
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
    successMessage.value = 'Vendeur approuvé avec succès'
    setTimeout(() => successMessage.value = '', 3000)
    await loadVendors()
  } catch (error) {
    errorMessage.value = 'Erreur lors de l\'approbation'
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
    errorMessage.value = 'Erreur lors du rejet'
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
    errorMessage.value = 'Erreur lors de la suspension'
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
    errorMessage.value = 'Erreur lors de la réactivation'
    setTimeout(() => errorMessage.value = '', 3000)
  } finally {
    actionLoading.value = false
  }
}

onMounted(() => {
  loadVendors()
})
</script>