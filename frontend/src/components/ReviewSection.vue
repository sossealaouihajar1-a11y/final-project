<template>
  <div class="space-y-10">

    <!-- Header -->
    <div class="text-center">
      <p class="text-sm uppercase tracking-widest text-gray-500 mb-3">
        Avis clients
      </p>
      <!-- <h3 class="text-3xl font-serif text-gray-900">
        Ce que disent les clients ({{ total }})
      </h3> -->
    </div>

    <!-- Comment form - Only for authenticated clients -->
    <div v-if="authStore.isClient">
      <!-- User review -->
      <div
        v-if="userReview"
        class="border border-gray-300 bg-white p-6 rounded-lg shadow-sm"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
             <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">
               Votre avis
            </p> 
            <p class="text-sm text-gray-600">
              {{ formatDate(userReview.created_at) }}
            </p>
          </div>

          <div class="flex gap-4 text-sm">
            <button
              v-if="!editingReview"
              @click="startEdit"
              class="text-gray-700 hover:text-gray-900 transition"
            >
              Modifier
            </button>
            <button
              @click="showDeleteConfirm = true"
              class="text-red-600 hover:text-red-700 transition"
            >
              Supprimer
            </button>
          </div>
        </div>

        <!-- View -->
        <p
          v-if="!editingReview"
          class="text-gray-800 leading-relaxed"
        >
          {{ userReview.comment }}
        </p>

        <!-- Edit -->
        <div v-else class="space-y-4">
          <textarea
            v-model="editForm.comment"
            rows="4"
            class="w-full border border-gray-300 px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-900 rounded"
          />

          <div class="flex gap-3">
            <button
              @click="updateReview"
              :disabled="editForm.comment.length < 10 || submitting"
              class="px-6 py-2 bg-gray-900 text-white text-sm uppercase tracking-wider hover:bg-gray-800 disabled:opacity-50 transition rounded"
            >
              Enregistrer
            </button>
            <button
              @click="cancelEdit"
              class="px-6 py-2 border border-gray-300 text-gray-700 text-sm hover:bg-gray-50 transition rounded"
            >
              Annuler
            </button>
          </div>
        </div>
      </div>

      <!-- Create review -->
      <div
        v-else
        class="border border-gray-300 bg-white p-6 rounded-lg shadow-sm"
      >
        <h4 class="text-lg font-serif text-gray-900 mb-4">
          Écrire un avis
        </h4>

        <textarea
          v-model="reviewForm.comment"
          rows="4"
          class="w-full border border-gray-300 px-4 py-3 focus:outline-none focus:ring-1 focus:ring-gray-900 rounded"
          placeholder="Partagez votre expérience avec ce produit..."
        />

        <button
          @click="submitReview"
          :disabled="reviewForm.comment.length < 10 || submitting"
          class="mt-4 px-8 py-3 bg-gray-900 text-white text-sm uppercase tracking-wider hover:bg-gray-800 disabled:opacity-50 transition rounded"
        >
          {{ submitting ? 'Publication...' : 'Publier l\'avis' }}
        </button>
      </div>
    </div>

    <!-- Not authenticated message -->
    <!-- <div
      v-else-if="!authStore.isAuthenticated"
      class="border border-gray-200 bg-gray-50 p-6 rounded-lg text-center"
    >
      <p class="text-gray-800 font-medium mb-2">
        Connexion requise
      </p>
      <p class="text-sm text-gray-600 mb-4">
        Seuls les clients connectés peuvent laisser un avis.
      </p>
      <router-link
        to="/login"
        class="inline-block px-6 py-3 bg-gray-900 text-white text-sm uppercase tracking-wider hover:bg-gray-800 transition rounded"
      >
        Se connecter
      </router-link>
    </div> -->

    <!-- Not client message -->
    <!-- <div
      v-else-if="authStore.isVendor || authStore.isAdmin"
      class="border border-gray-200 bg-gray-50 p-6 rounded-lg text-center"
    >
      <p class="text-gray-800 font-medium">
        Seuls les clients peuvent laisser des avis.
      </p>
    </div> -->

    <!-- Reviews list - Always visible -->
    <div v-if="reviews.length" class="space-y-6">
      <!-- <h4 class="text-xl font-serif text-gray-900 border-b border-gray-200 pb-3">
        Tous les avis
      </h4> -->

      <div
        v-for="review in reviews"
        :key="review.id"
        class="border border-gray-200 bg-white p-6 rounded-lg shadow-sm"
      >
        <div class="flex justify-between items-start mb-3">
          <p class="font-medium text-gray-900">
            {{ review.user.name }}
          </p>
          <p class="text-xs text-gray-500">
            {{ formatDate(review.created_at) }}
          </p>
        </div>

        <p class="text-gray-700 leading-relaxed">
          {{ review.comment }}
        </p>
      </div>
    </div>

    <!-- Empty state -->
    <div
      v-else
      class="text-center py-12 border border-dashed border-gray-300 rounded-lg bg-gray-50"
    >
      <!-- <p class="text-gray-700 font-medium">
        Aucun avis pour le moment
      </p> -->
      <!-- <p class="text-sm text-gray-500 mt-1">
        Soyez le premier à partager votre expérience.
      </p> -->
    </div>

    <!-- Delete modal -->
    <div
      v-if="showDeleteConfirm"
      class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center"
      @click="showDeleteConfirm = false"
    >
      <div
        @click.stop
        class="bg-white p-8 max-w-md w-full rounded-lg shadow-xl"
      >
        <h3 class="text-lg font-serif text-gray-900 mb-2">
          Supprimer votre avis ?
        </h3>
        <p class="text-sm text-gray-600 mb-6">
          Cette action est irréversible.
        </p>

        <div class="flex gap-4">
          <button
            @click="deleteReview"
            class="flex-1 px-4 py-2 bg-red-600 text-white text-sm hover:bg-red-700 transition rounded"
          >
            Delete
          </button>
          <button
            @click="showDeleteConfirm = false"
            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 text-sm hover:bg-gray-50 transition rounded"
          >
            Cancel
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

const reviewForm = ref({ comment: '' })
const editForm = ref({ comment: '' })

const loadReviews = async () => {
  const res = await reviewService.getProductReviews(props.productId)
  reviews.value = res.reviews
  total.value = res.total
}

const checkUserReview = async () => {
  if (!authStore.isAuthenticated) return
  const res = await reviewService.checkUserReview(props.productId)
  if (res.has_reviewed) userReview.value = res.review
}

const submitReview = async () => {
  submitting.value = true
  const res = await reviewService.createReview({
    product_id: props.productId,
    comment: reviewForm.value.comment
  })
  userReview.value = res.review
  reviewForm.value.comment = ''
  await loadReviews()
  emit('review-added', res.review)
  submitting.value = false
}

const startEdit = () => {
  editForm.value.comment = userReview.value.comment
  editingReview.value = true
}

const cancelEdit = () => {
  editingReview.value = false
}

const updateReview = async () => {
  submitting.value = true
  const res = await reviewService.updateReview(userReview.value.id, {
    comment: editForm.value.comment
  })
  userReview.value = res.review
  editingReview.value = false
  await loadReviews()
  emit('review-updated', res.review)
  submitting.value = false
}

const deleteReview = async () => {
  await reviewService.deleteReview(userReview.value.id)
  userReview.value = null
  showDeleteConfirm.value = false
  await loadReviews()
  emit('review-deleted')
}

const formatDate = date =>
  new Date(date).toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })

onMounted(async () => {
  await loadReviews()
  await checkUserReview()
})
</script>
