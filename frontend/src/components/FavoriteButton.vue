<template>
  <button
    @click="handleToggleFavorite"
    :disabled="isLoading"
    :title="isFavorite(productId) ? 'Retirer des favoris' : 'Ajouter aux favoris'"
    class="transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full"
    :class="[
      isLoading ? 'opacity-50 cursor-not-allowed' : 'hover:scale-110',
      sizeClass,
      styleClass
    ]"
  >
    <svg 
      class="transition-all"
      :class="[
        isFavorite(productId) 
          ? 'text-red-500 fill-red-500' 
          : 'text-gray-400 hover:text-red-500',
        sizeIconClass
      ]"
      fill="none" 
      stroke="currentColor" 
      viewBox="0 0 24 24"
    >
      <path 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        stroke-width="2" 
        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" 
      />
    </svg>
  </button>
</template>

<script setup>
import { useFavorites } from '@/composables/useFavorites'

const props = defineProps({
  productId: {
    type: String,
    required: true
  },
  size: {
    type: String,
    default: 'md', // sm, md, lg
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  variant: {
    type: String,
    default: 'icon', // icon, button
    validator: (value) => ['icon', 'button'].includes(value)
  }
})

const { isFavorite, isLoading, toggleFavorite } = useFavorites()

const sizeClass = {
  sm: 'p-1.5',
  md: 'p-2.5',
  lg: 'p-3'
}[props.size]

const sizeIconClass = {
  sm: 'w-4 h-4',
  md: 'w-5 h-5',
  lg: 'w-6 h-6'
}[props.size]

const styleClass = props.variant === 'button' 
  ? 'bg-white rounded-full shadow-lg hover:shadow-xl'
  : ''

const handleToggleFavorite = async () => {
  try {
    const newStatus = await toggleFavorite(props.productId)
    // Dispatch custom event to update navbar and other components
    window.dispatchEvent(new CustomEvent('favorites-updated', { 
      detail: { count: newStatus } 
    }))
  } catch (err) {
    console.error('Error toggling favorite:', err)
  }
}
</script>

<style scoped>
</style>
