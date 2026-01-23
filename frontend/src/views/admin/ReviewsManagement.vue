<template>
  <div class="min-h-screen bg-[#f6f3ee] text-[#2a2a28]">

    <!-- Hero Section -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Administration
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Gestion des Commentaires
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Gérez tous les commentaires de la plateforme
        </p>
      </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Filters -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            placeholder="Rechercher par utilisateur, produit..."
            class="col-span-2 px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b]"
          />
          <button
            @click="loadReviews"
            class="px-4 py-3 bg-[#6b7b4b] text-white rounded-lg hover:bg-[#556b2f] font-medium"
          >
            Actualiser
          </button>
        </div>
      </div>

      <!-- Reviews List -->
      <div class="space-y-6">
        <div v-if="loading" class="p-12 text-center text-[#2a2a28]">
          Chargement des commentaires...
        </div>

        <div v-else-if="reviews.length === 0" class="p-12 text-center text-gray-500">
          Aucun commentaire trouvé
        </div>

        <div v-else>
          <div
            v-for="review in reviews"
            :key="review.id"
            class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 hover:bg-[#f4f1eb] transition"
          >
            <!-- Header: User Info -->
            <div class="flex justify-between items-start mb-4">
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-[#6b7b4b] rounded-full flex items-center justify-center text-white font-semibold">
                  {{ getInitials(review.user_name) }}
                </div>
                <div>
                  <p class="font-bold text-[#4a3728]">{{ review.user_name }}</p>
                  <p class="text-xs text-[#5a564f]">{{ review.user_email }}</p>
                  <p class="text-xs text-gray-400 mt-1">{{ formatDate(review.created_at) }}</p>
                </div>
              </div>
              <button
                @click="handleConfirmDelete(review)"
                class="text-red-600 hover:underline text-sm"
              >
                Supprimer
              </button>
            </div>

            <!-- Comment Text -->
            <div class="text-[#2a2a28] mb-2">
              {{ review.comment || 'Aucun commentaire écrit' }}
            </div>

            <!-- Product Info -->
            <div v-if="review.product_name" class="text-xs text-[#6b7b4b]">
              Produit: {{ review.product_name }}
            </div>
          </div>
        </div>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && reviews.length" class="flex justify-between items-center text-sm text-[#2a2a28]">
        <button
          v-if="pagination.current_page > 1"
          @click="loadReviews(pagination.current_page - 1)"
          class="px-4 py-2 border border-[#d6cdbf] rounded-lg hover:bg-[#f4f1eb]"
        >
          ← Précédent
        </button>
        <div>
          Page {{ pagination.current_page }} / {{ pagination.last_page }} ({{ pagination.total || 0 }} commentaires)
        </div>
        <button
          v-if="pagination.current_page < pagination.last_page"
          @click="loadReviews(pagination.current_page + 1)"
          class="px-4 py-2 border border-[#d6cdbf] rounded-lg hover:bg-[#f4f1eb]"
        >
          Suivant →
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useNotification } from '@/composables/useNotification'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import reviewManagementService from '@/services/reviewManagementService'

const reviews = ref([])
const loading = ref(false)
const searchQuery = ref('')
const pagination = ref({ current_page: 1, last_page: 1, total: 0 })
const { showError } = useNotification()
const { confirmDelete } = useConfirmDialog()
let searchTimeout = null

const loadReviews = async (page = 1) => {
  loading.value = true
  try {
    const response = await reviewManagementService.getAllReviews({
      page,
      per_page: 20,
      search: searchQuery.value || undefined,
    })
    reviews.value = response.data.data || []
    pagination.value = {
      current_page: response.data.current_page || 1,
      last_page: response.data.last_page || 1,
      total: response.data.total || 0,
    }
  } catch (error) {
    reviews.value = []
    pagination.value = { current_page: 1, last_page: 1, total: 0 }
    showError('Erreur lors du chargement des commentaires', 'Erreur')
  } finally {
    loading.value = false
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => loadReviews(1), 500)
}

const handleConfirmDelete = async (review) => {
  const confirmed = await confirmDelete(`le commentaire de ${review.user_name}`)
  if (confirmed) {
    deleteReview(review.id)
  }
}

const deleteReview = async (reviewId) => {
  try {
    await reviewManagementService.deleteReview(reviewId)
    loadReviews()
  } catch (error) {
    showError('Erreur lors de la suppression', 'Erreur')
  }
}

const getInitials = (name) => {
  if (!name) return '?'
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => loadReviews())
</script>
