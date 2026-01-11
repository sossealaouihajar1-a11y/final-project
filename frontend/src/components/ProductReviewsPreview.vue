<template>
  <div v-if="reviews.length > 0" class="mt-4 pt-4 border-t border-gray-200">
    <div class="flex items-center justify-between mb-3">
      <h4 class="text-sm font-semibold text-gray-900 flex items-center space-x-2">
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
        <span>Avis ({{ reviews.length }})</span>
      </h4>
      <button
        v-if="reviews.length > 2"
        @click="$emit('view-all')"
        class="text-xs text-indigo-600 hover:text-indigo-700 font-medium"
      >
        Voir tous →
      </button>
    </div>

    <!-- Afficher les 2 premiers avis -->
    <div class="space-y-3">
      <div
        v-for="review in displayedReviews"
        :key="review.id"
        class="bg-gray-50 rounded-lg p-3"
      >
        <div class="flex items-start justify-between mb-2">
          <div class="flex items-center space-x-2">
            <div class="w-7 h-7 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
              <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-semibold text-gray-900">{{ review.user.name }}</p>
              <p class="text-xs text-gray-500">{{ formatDate(review.created_at) }}</p>
            </div>
          </div>
        </div>
        <p class="text-xs text-gray-700 line-clamp-2 leading-relaxed">{{ review.comment }}</p>
      </div>
    </div>

    <!-- Message si plus de 2 avis -->
    <button
      v-if="reviews.length > 2"
      @click="$emit('view-all')"
      class="mt-3 w-full text-center text-xs text-indigo-600 hover:text-indigo-700 font-medium py-2 hover:bg-indigo-50 rounded-lg transition"
    >
      + {{ reviews.length - 2 }} autre(s) avis
    </button>
  </div>

  <!-- Aucun avis -->
  <div v-else class="mt-4 pt-4 border-t border-gray-200">
    <p class="text-xs text-gray-500 text-center py-2">Aucun avis pour ce produit</p>
  </div>
</template>

<script setup>
import { computed, defineProps, defineEmits } from 'vue'

const props = defineProps({
  reviews: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['view-all'])

// Afficher seulement les 2 premiers avis
const displayedReviews = computed(() => {
  return props.reviews.slice(0, 2)
})

const formatDate = (dateString) => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInDays = Math.floor((now - date) / (1000 * 60 * 60 * 24))
  
  if (diffInDays === 0) return "Aujourd'hui"
  if (diffInDays === 1) return "Hier"
  if (diffInDays < 7) return `Il y a ${diffInDays} jours`
  if (diffInDays < 30) return `Il y a ${Math.floor(diffInDays / 7)} semaine(s)`
  
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>