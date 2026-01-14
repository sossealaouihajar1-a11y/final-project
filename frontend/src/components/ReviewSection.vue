<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-gray-900 flex items-center space-x-2">
        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <span>Avis clients ({{ total }})</span>
      </h3>
    </div>

    <!-- Formulaire de création (si non authentifié ou non client) -->
    <div v-if="!authStore.isAuthenticated" class="p-6 bg-blue-50 border-2 border-blue-200 rounded-xl">
      <div class="flex items-start space-x-3">
        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        <div>
          <p class="font-semibold text-blue-800 text-sm">Connectez-vous pour laisser un avis</p>
          <p class="text-xs text-blue-700 mt-1">Seuls les clients connectés peuvent laisser des avis.</p>
          <router-link to="/login" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition font-medium">
            Se connecter
          </router-link>
        </div>
      </div>
    </div>

    <div v-else-if="!authStore.isClient" class="p-6 bg-orange-50 border-2 border-orange-200 rounded-xl">
      <div class="flex items-start space-x-3">
        <svg class="w-6 h-6 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
        </svg>
        <div>
          <p class="font-semibold text-orange-800 text-sm">Seuls les clients peuvent laisser des avis</p>
          <p class="text-xs text-orange-700 mt-1">Cette fonctionnalité est réservée aux comptes clients.</p>
        </div>
      </div>
    </div>

    <!-- Avis de l'utilisateur connecté -->
    <div v-else-if="userReview" class="p-6 bg-green-50 border-2 border-green-500 rounded-xl">
      <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
          </div>
          <div>
            <p class="font-bold text-green-800">Votre avis</p>
            <p class="text-xs text-green-700">{{ formatDate(userReview.created_at) }}</p>
          </div>
        </div>
        <div class="flex items-center space-x-2">
          <button v-if="!editingReview" @click="startEdit" class="p-2 text-green-700 hover:bg-green-100 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </button>
          <button @click="showDeleteConfirm = true" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mode Vue -->
      <div v-if="!editingReview">
        <p class="text-gray-700 whitespace-pre-wrap">{{ userReview.comment }}</p>
      </div>

      <!-- Mode Édition -->
      <div v-else class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire</label>
          <textarea
            v-model="editForm.comment"
            rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"
            placeholder="Partagez votre expérience avec ce produit..."
          ></textarea>
          <div class="flex justify-between items-center mt-2">
            <span class="text-xs" :class="editForm.comment.length >= 10 ? 'text-gray-500' : 'text-red-500'">
              {{ editForm.comment.length }} / 1000 caractères (min. 10)
            </span>
          </div>
        </div>
        <div class="flex space-x-3">
          <button
            @click="updateReview"
            :disabled="editForm.comment.length < 10 || submitting"
            class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium"
          >
            {{ submitting ? 'Enregistrement...' : 'Enregistrer' }}
          </button>
          <button
            @click="cancelEdit"
            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Formulaire de création (si pas encore d'avis) -->
    <div v-else-if="authStore.isClient" class="p-6 bg-blue-50 border-2 border-blue-200 rounded-xl">
      <h4 class="font-bold text-blue-900 mb-4">Laissez votre avis</h4>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire *</label>
          <textarea
            v-model="reviewForm.comment"
            rows="4"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
            placeholder="Partagez votre expérience avec ce produit..."
          ></textarea>
          <div class="flex justify-between items-center mt-2">
            <span class="text-xs" :class="reviewForm.comment.length >= 10 ? 'text-gray-500' : 'text-red-500'">
              {{ reviewForm.comment.length }} / 1000 caractères (min. 10)
            </span>
          </div>
        </div>
        <button
          @click="submitReview"
          :disabled="reviewForm.comment.length < 10 || submitting"
          class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
        >
          {{ submitting ? 'Publication...' : 'Publier mon avis' }}
        </button>
      </div>
    </div>

    <!-- Liste des avis -->
    <div v-if="reviews.length > 0" class="space-y-4">
      <h4 class="font-bold text-gray-900 text-lg">Tous les avis ({{ reviews.length }})</h4>
      <div
        v-for="review in reviews"
        :key="review.id"
        class="p-5 bg-gray-50 border border-gray-200 rounded-xl hover:shadow-md transition"
      >
        <div class="flex items-start justify-between mb-3">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ review.user.name }}</p>
              <p class="text-xs text-gray-500">{{ formatDate(review.created_at) }}</p>
            </div>
          </div>
        </div>
        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ review.comment }}</p>
      </div>
    </div>

    <div v-else class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
      <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
      </svg>
      <p class="text-gray-600 font-medium">Aucun avis pour ce produit</p>
      <p class="text-sm text-gray-500 mt-1">Soyez le premier à laisser un avis !</p>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="showDeleteConfirm" @click="showDeleteConfirm = false" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
      <div @click.stop class="bg-white rounded-xl p-6 max-w-md w-full">
        <div class="flex items-center space-x-3 mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-bold text-gray-900">Supprimer votre avis ?</h3>
            <p class="text-sm text-gray-600">Cette action est irréversible.</p>
          </div>
        </div>
        <div class="flex space-x-3">
          <button
            @click="deleteReview"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium"
          >
            Supprimer
          </button>
          <button
            @click="showDeleteConfirm = false"
            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import reviewService from '@/services/reviewService'

const props = defineProps({
  productId: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['review-added', 'review-updated', 'review-deleted'])

const authStore = useAuthStore()

const reviews = ref([])
const total = ref(0)
const userReview = ref(null)
const submitting = ref(false)
const editingReview = ref(false)
const showDeleteConfirm = ref(false)

const reviewForm = ref({
  comment: ''
})

const editForm = ref({
  comment: ''
})

const loadReviews = async () => {
  try {
    const response = await reviewService.getProductReviews(props.productId)
    reviews.value = response.reviews
    total.value = response.total
  } catch (error) {
    console.error('Erreur chargement avis:', error)
  }
}

const checkUserReview = async () => {
  if (!authStore.isAuthenticated) return
  
  try {
    const response = await reviewService.checkUserReview(props.productId)
    if (response.has_reviewed) {
      userReview.value = response.review
    }
  } catch (error) {
    console.error('Erreur vérification avis:', error)
  }
}

const submitReview = async () => {
  if (reviewForm.value.comment.length < 10) return
  
  submitting.value = true
  try {
    const response = await reviewService.createReview({
      product_id: props.productId,
      comment: reviewForm.value.comment
    })
    
    userReview.value = response.review
    reviewForm.value.comment = ''
    
    await loadReviews()
    emit('review-added', response.review)
  } catch (error) {
    console.error('Erreur création avis:', error)
    alert(error.response?.data?.message || 'Erreur lors de la création de l\'avis')
  } finally {
    submitting.value = false
  }
}

const startEdit = () => {
  editForm.value.comment = userReview.value.comment
  editingReview.value = true
}

const cancelEdit = () => {
  editingReview.value = false
}

const updateReview = async () => {
  if (editForm.value.comment.length < 10) return
  
  submitting.value = true
  try {
    const response = await reviewService.updateReview(userReview.value.id, {
      comment: editForm.value.comment
    })
    
    userReview.value = response.review
    editingReview.value = false
    
    await loadReviews()
    emit('review-updated', response.review)
  } catch (error) {
    console.error('Erreur mise à jour avis:', error)
    alert('Erreur lors de la mise à jour de l\'avis')
  } finally {
    submitting.value = false
  }
}

const deleteReview = async () => {
  try {
    await reviewService.deleteReview(userReview.value.id)
    
    userReview.value = null
    showDeleteConfirm.value = false
    
    await loadReviews()
    emit('review-deleted')
  } catch (error) {
    console.error('Erreur suppression avis:', error)
    alert('Erreur lors de la suppression de l\'avis')
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

onMounted(async () => {
  await loadReviews()
  await checkUserReview()
})
</script>