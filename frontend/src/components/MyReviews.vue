<template>
  <div>
    <!-- Loading -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block">
        <svg class="animate-spin h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
      <p class="mt-4 text-gray-600 font-medium">Chargement de vos avis...</p>
    </div>

    <!-- No Reviews -->
    <div v-else-if="reviews.length === 0" class="text-center py-16 bg-white rounded-xl shadow-sm border border-gray-200">
      <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
      </svg>
      <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun avis</h3>
      <p class="text-gray-600 mb-6">Vous n'avez pas encore laissé d'avis sur les produits</p>
      <router-link to="/products" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
        Découvrir nos produits
      </router-link>
    </div>

    <!-- Reviews List -->
    <div v-else class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between">
        <h3 class="text-2xl font-bold text-gray-900 flex items-center space-x-2">
          <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
          </svg>
          <span>Mes avis ({{ reviews.length }})</span>
        </h3>
      </div>

      <!-- Reviews Cards -->
      <div
        v-for="review in reviews"
        :key="review.id"
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition"
      >
        <!-- Review Card -->
        <div class="p-6">
          <!-- Product Info -->
          <div class="flex items-start space-x-4 mb-4 pb-4 border-b border-gray-200">
            <!-- Image -->
            <div class="w-20 h-20 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
              <img
                v-if="review.vintage_product?.image_url"
                :src="review.vintage_product.image_url"
                :alt="review.vintage_product.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>

            <!-- Product Details -->
            <div class="flex-1 min-w-0">
              <h4 class="text-lg font-bold text-gray-900 truncate">
                {{ review.vintage_product?.title || 'Produit supprimé' }}
              </h4>
              <div class="flex items-center space-x-3 mt-1">
                <span class="text-sm text-gray-500">{{ review.vintage_product?.category }}</span>
                <span class="text-sm font-semibold text-indigo-600">{{ review.vintage_product?.price }}€</span>
              </div>
              <p class="text-xs text-gray-500 mt-2">
                Publié le {{ formatDate(review.created_at) }}
              </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2">
              <button
                v-if="!editingReview[review.id]"
                @click="startEdit(review)"
                class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition"
                title="Modifier"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click="confirmDelete(review)"
                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                title="Supprimer"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Mode Vue -->
          <div v-if="!editingReview[review.id]" class="bg-gray-50 rounded-lg p-4">
            <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ review.comment }}</p>
          </div>

          <!-- Mode Édition -->
          <div v-else class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Modifier votre commentaire</label>
              <textarea
                v-model="editForm[review.id]"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                placeholder="Partagez votre expérience avec ce produit..."
              ></textarea>
              <div class="flex justify-between items-center mt-2">
                <span class="text-xs" :class="editForm[review.id]?.length >= 10 ? 'text-gray-500' : 'text-red-500'">
                  {{ editForm[review.id]?.length || 0 }} / 1000 caractères (min. 10)
                </span>
              </div>
            </div>
            <div class="flex space-x-3">
              <button
                @click="updateReview(review)"
                :disabled="!editForm[review.id] || editForm[review.id].length < 10 || submitting"
                class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium"
              >
                {{ submitting ? 'Enregistrement...' : 'Enregistrer' }}
              </button>
              <button
                @click="cancelEdit(review.id)"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
              >
                Annuler
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="reviewToDelete" @click="reviewToDelete = null" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div @click.stop class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">Supprimer cet avis ?</h3>
            <p class="text-sm text-gray-600">Cette action est irréversible.</p>
          </div>
        </div>
        <div class="bg-gray-50 rounded-lg p-3 mb-4">
          <p class="text-sm font-semibold text-gray-900 mb-1">{{ reviewToDelete.vintage_product?.title }}</p>
          <p class="text-xs text-gray-600 line-clamp-2">{{ reviewToDelete.comment }}</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="deleteReview"
            :disabled="submitting"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium disabled:opacity-50"
          >
            {{ submitting ? 'Suppression...' : 'Supprimer' }}
          </button>
          <button
            @click="reviewToDelete = null"
            :disabled="submitting"
            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-gray-800 font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue'
import reviewService from '@/services/reviewService'

const reviews = ref([])
const loading = ref(true)
const submitting = ref(false)
const editingReview = reactive({})
const editForm = reactive({})
const reviewToDelete = ref(null)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadReviews = async () => {
  loading.value = true
  try {
    const response = await reviewService.getMyReviews()
    reviews.value = response.reviews
  } catch (error) {
    console.error('Erreur chargement avis:', error)
    showNotification('Erreur lors du chargement de vos avis', 'error')
  } finally {
    loading.value = false
  }
}

const startEdit = (review) => {
  editingReview[review.id] = true
  editForm[review.id] = review.comment
}

const cancelEdit = (reviewId) => {
  editingReview[reviewId] = false
  delete editForm[reviewId]
}

const updateReview = async (review) => {
  if (!editForm[review.id] || editForm[review.id].length < 10) return

  submitting.value = true
  try {
    await reviewService.updateReview(review.id, {
      comment: editForm[review.id]
    })

    // Mettre à jour localement
    const index = reviews.value.findIndex(r => r.id === review.id)
    if (index !== -1) {
      reviews.value[index].comment = editForm[review.id]
    }

    editingReview[review.id] = false
    delete editForm[review.id]

    showNotification('Avis modifié avec succès', 'success')
  } catch (error) {
    console.error('Erreur modification avis:', error)
    showNotification('Erreur lors de la modification', 'error')
  } finally {
    submitting.value = false
  }
}

const confirmDelete = (review) => {
  reviewToDelete.value = review
}

const deleteReview = async () => {
  if (!reviewToDelete.value) return

  submitting.value = true
  try {
    await reviewService.deleteReview(reviewToDelete.value.id)

    // Retirer de la liste
    reviews.value = reviews.value.filter(r => r.id !== reviewToDelete.value.id)

    reviewToDelete.value = null
    showNotification('Avis supprimé avec succès', 'success')
  } catch (error) {
    console.error('Erreur suppression avis:', error)
    showNotification('Erreur lors de la suppression', 'error')
  } finally {
    submitting.value = false
  }
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

onMounted(() => {
  loadReviews()
})
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  transform: translateY(100px);
  opacity: 0;
}

.slide-up-leave-to {
  transform: translateY(100px);
  opacity: 0;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>