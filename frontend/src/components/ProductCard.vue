<template>
  <div
    @click="$emit('click', product)"
    class="bg-white rounded-xl shadow-md overflow-hidden cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-2xl group"
  >
    <!-- Image -->
    <div class="relative h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
      <img
        v-if="product.image_url"
        :src="product.image_url"
        :alt="product.title"
        class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
      />
      <div v-else class="text-gray-400 text-sm">Aucune image</div>
      
      <!-- Badge Promotion -->
      <div v-if="product.promotion > 0" class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1.5 rounded-full text-sm font-bold shadow-lg animate-pulse">
        -{{ product.promotion }}%
      </div>

      <!-- Badge Stock -->
      <div v-if="product.stock > 0 && product.stock < 5" class="absolute top-3 left-3 bg-orange-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
        Plus que {{ product.stock }}
      </div>
      <div v-else-if="product.stock === 0" class="absolute top-3 left-3 bg-gray-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
        Épuisé
      </div>

      <!-- Bouton Favoris -->
      <button
        @click.stop="handleToggleFavorite"
        class="absolute bottom-3 right-3 bg-white rounded-full p-2.5 shadow-lg hover:scale-110 transition transform z-10"
        :disabled="isLoading"
      >
        <svg 
          class="w-5 h-5 transition"
          :class="isFavorite(product.id) ? 'text-red-500 fill-red-500' : 'text-gray-400'"
          fill="none" 
          stroke="currentColor" 
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
      </button>
    </div>

    <!-- Contenu -->
    <div class="p-5">
      <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-indigo-600 transition">
        {{ product.title }}
      </h3>
      
      <p class="text-sm text-gray-600 mb-3">
        {{ product.category }}
      </p>
      
      <!-- Prix -->
      <div class="flex items-center justify-between mb-3">
        <div>
          <div v-if="product.promotion > 0" class="text-sm text-gray-400 line-through">
            {{ product.price }}€
          </div>
          <div class="text-2xl font-bold text-indigo-600">
            {{ product.final_price }}€
          </div>
        </div>
      </div>

      <!-- Condition -->
      <div class="mb-4 flex items-center justify-between">
        <span :class="getConditionClass(product.condition)" class="px-3 py-1.5 text-xs font-semibold rounded-full inline-block">
          {{ getConditionLabel(product.condition) }}
        </span>
      </div>

      <!-- Add to Favorites Button -->
      <button
        @click.stop="handleToggleFavorite"
        :class="isFavorite(product.id) ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700'"
        class="w-full px-4 py-2.5 rounded-lg transition font-semibold text-sm flex items-center justify-center space-x-2 disabled:opacity-50"
        :disabled="isLoading"
      >
        <svg class="w-5 h-5" :class="isFavorite(product.id) ? 'fill-white' : 'fill-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <span>{{ isFavorite(product.id) ? 'En favoris' : 'Ajouter aux favoris' }}</span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useFavorites } from '@/composables/useFavorites'

const props = defineProps({
  product: {
    type: Object,
    required: true
  },
  isFavorite: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click', 'toggle-favorite'])
const { isFavorite, isLoading, toggleFavorite } = useFavorites()

const handleToggleFavorite = async () => {
  try {
    const newStatus = await toggleFavorite(props.product.id)
    // Dispatch custom event to update navbar
    window.dispatchEvent(new CustomEvent('favorites-updated', { 
      detail: { count: newStatus } 
    }))
    emit('toggle-favorite', props.product.id)
  } catch (err) {
    console.error('Error toggling favorite:', err)
  }
}

const getConditionLabel = (condition) => {
  const labels = {
    neuf: 'Neuf',
    excellent: 'Excellent',
    tres_bon: 'Très bon état',
    bon: 'Bon état',
    acceptable: 'État correct',
    a_restaurer: 'À restaurer'
  }
  return labels[condition] || condition
}

const getConditionClass = (condition) => {
  const classes = {
    neuf: 'bg-green-100 text-green-800',
    excellent: 'bg-blue-100 text-blue-800',
    tres_bon: 'bg-cyan-100 text-cyan-800',
    bon: 'bg-yellow-100 text-yellow-800',
    acceptable: 'bg-orange-100 text-orange-800',
    a_restaurer: 'bg-red-100 text-red-800'
  }
  return classes[condition] || 'bg-gray-100 text-gray-800'
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

