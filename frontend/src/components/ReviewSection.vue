<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
      <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
      </svg>
      <span>Avis Clients</span>
      <span class="text-sm font-normal text-gray-500">({{ total }})</span>
    </h2>

    <!-- Formulaire d'avis (client connecté) -->
    <div v-if="authStore.isClient && !userReview" class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
      <h3 class="font-bold text-gray-900 mb-4 flex items-center space-x-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        <span>Laisser un avis</span>
      </h3>
      <form @submit.prevent="submitReview">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Votre commentaire *</label>
          <textarea
            v-model="reviewForm.comment"
            rows="4"
            required
            minlength="10"
            maxlength="1000"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
            placeholder="Partagez votre expérience avec ce produit... (minimum 10 caractères)"
          ></textarea>
          <p class="text-xs text-gray-500 mt-1">{{ reviewForm.comment?.length || 0 }}/1000 caractères (minimum 10)</p>
        </div>

        <button
          type="submit"
          :disabled="!reviewForm.comment || reviewForm.comment.length < 10 || submitting"
          class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {{ submitting ? 'Envoi...' : 'Publier l\'avis' }}
        </button>
      </form>
    </div>

    <!-- Avis de l'utilisateur (s'il en a déjà laissé un) -->
    <div v-if="userReview" class="mb-8 p-6 bg-green-50 rounded-xl border border-green-200">
      <div class="flex justify-between items-start mb-3">
        <h3 class="font-bold text-gray-900 flex items-center space-x-2">
          <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span>Votre avis</span>
        </h3>
        <button
          v-if="!editingReview"
          @click="startEditingReview"
          class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center space-x-1"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
          </svg>
          <span>Modifier</span>
        </button>
      </div>

      <div v-if="!editingReview">
        <p class="text-gray-700 whitespace-pre-wrap">{{ userReview.comment }}</p>
        <p class="text-xs text-gray-500 mt-3">Publié le {{ formatDate(userReview.created_at) }}</p>
      </div>

      <form v-else @submit.prevent="updateReview">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Modifier votre commentaire *</label>
          <textarea
            v-model="editForm.comment"
            rows="4"
            required
            minlength="10"
            maxlength="1000"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
          ></textarea>
          <p class="text-xs text-gray-500 mt-1">{{ editForm.comment?.length || 0 }}/1000 caractères</p>
        </div>

        <div class="flex space-x-3">
          <button
            type="submit"
            :disabled="submitting || editForm.comment.length < 10"
            class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
          >
            {{ submitting ? 'Mise à jour...' : 'Mettre à jour' }}
          </button>
          <button
            type="button"
            @click="cancelEditing"
            class="px-6 py-3 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition"
          >
            Annuler
          </button>
          <button
            type="button"
            @click="deleteReview"
            class="px-6 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition ml-auto flex items-center space-x-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Supprimer</span>
          </button>
        </div>
      </form>
    </div>

    <!-- Message pour non-clients -->
    <div v-if="!authStore.isClient && authStore.isAuthenticated" class="mb-8 p-4 bg-orange-50 rounded-lg border border-orange-200">
      <p class="text-sm text-orange-800">
        ⚠️ Seuls les clients peuvent laisser des avis sur les produits.
      </p>
    </div>

    <!-- Message pour non-authentifiés -->
    <div v-if="!authStore.isAuthenticated" class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
      <p class="text-sm text-gray-700">
        <router-link to="/login" class="text-indigo-600 hover:text-indigo-700 font-semibold">Connectez-vous</router-link> pour laisser un avis sur ce produit.
      </p>
    </div>

    <!-- Liste des avis -->
    <div class="space-y-6">
      <h3 class="font-bold text-gray-900 text-lg flex items-center space-x-2">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        <span>Tous les avis ({{ reviews.length }})</span>
      </h3>

      <div v-if="reviews.length === 0" class="text-center py-12 text-gray-500">
        <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <p class="text-lg font-medium mb-1">Aucun avis pour le moment</p>
        <p class="text-sm">Soyez le premier à partager votre expérience avec ce produit !</p>
      </div>

      <div v-for="review in reviews" :key="review.id" class="p-6 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-md transition">
        <div class="flex justify-between items-start mb-3">
          <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ review.user.name }}</p>
              <p class="text-xs text-gray-500">{{ formatDate(review.created_at) }}</p>
            </div>
          </div>
        </div>
        <p class="text-gray-700 whitespace-pre-wrap leading-relaxed">{{ review.comment }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from 'vue'
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
  if (!authStore.isClient) return
  
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
  if (!reviewForm.value.comment || reviewForm.value.comment.length < 10) return

  submitting.value = true

  try {
    const response = await reviewService.createReview({
      product_id: props.productId,
      comment: reviewForm.value.comment
    })

    userReview.value = response.review
    reviewForm.value = { comment: '' }
    
    await loadReviews()
    emit('review-added', response.review)
  } catch (error) {
    console.error('Erreur création avis:', error)
    alert(error.response?.data?.message || 'Erreur lors de la création de l\'avis')
  } finally {
    submitting.value = false
  }
}

const startEditingReview = () => {
  editForm.value = {
    comment: userReview.value.comment || ''
  }
  editingReview.value = true
}

const cancelEditing = () => {
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
  if (!confirm('Êtes-vous sûr de vouloir supprimer votre avis ?')) return

  try {
    await reviewService.deleteReview(userReview.value.id)
    
    userReview.value = null
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

onMounted(() => {
  loadReviews()
  checkUserReview()
})
</script>