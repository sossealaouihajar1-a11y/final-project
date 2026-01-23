<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <Header />

    <main class="max-w-7xl mx-auto px-4 py-12">
      <!-- Titre -->
      <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Mes Favoris</h1>
        <p class="text-gray-600">{{ favoritesList.length }} produit(s) sauvegardé(s)</p>
      </div>

      <!-- Empty State -->
      <div v-if="favoritesList.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun favori</h3>
        <p class="mt-1 text-sm text-gray-500">Vous n'avez pas encore sauvegardé de produits.</p>
        <RouterLink to="/products" class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
          Découvrir les produits
        </RouterLink>
      </div>

      <!-- Favorites Grid -->
      <div v-else>
        <!-- Action Buttons -->
        <div class="mb-6 flex gap-3">
          <button
            @click="clearAllFavorites"
            v-if="favoritesList.length > 0"
            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium text-sm"
          >
            Supprimer tous
          </button>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="favorite in favoritesList" :key="favorite.id" class="relative">
            <ProductCard
              :product="favorite.product"
              :isFavorite="true"
              @click="navigateToProduct"
              @toggle-favorite="removeFavorite"
            />
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
      </div>

      <!-- Error State -->
      <div v-if="error" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ error }}
      </div>
    </main>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import favoritesService from '@/services/favoritesService'
import ProductCard from '@/components/ProductCard.vue'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

const router = useRouter()
const { confirmDelete } = useConfirmDialog()
const favoritesList = ref([])
const loading = ref(true)
const error = ref(null)

const fetchFavorites = async () => {
  try {
    loading.value = true
    error.value = null
    const response = await favoritesService.getFavorites()
    favoritesList.value = response.data || []
  } catch (err) {
    error.value = 'Impossible de charger vos favoris'
    console.error('Error fetching favorites:', err)
  } finally {
    loading.value = false
  }
}

const removeFavorite = async (productId) => {
  try {
    const favorite = favoritesList.value.find(f => f.product_id === productId)
    if (favorite) {
      await favoritesService.removeFavorite(favorite.id)
      favoritesList.value = favoritesList.value.filter(f => f.product_id !== productId)
      // Emit event to update navbar count
      window.dispatchEvent(new CustomEvent('favorites-updated', { detail: { count: favoritesList.value.length } }))
    }
  } catch (err) {
    error.value = 'Impossible de supprimer le favori'
    console.error('Error removing favorite:', err)
  }
}

const clearAllFavorites = async () => {
  const confirmed = await confirmDelete('tous vos favoris')
  if (confirmed) {
    try {
      await favoritesService.clearAll()
      favoritesList.value = []
      // Emit event to update navbar count
      window.dispatchEvent(new CustomEvent('favorites-updated', { detail: { count: 0 } }))
    } catch (err) {
      error.value = 'Impossible de supprimer les favoris'
      console.error('Error clearing favorites:', err)
    }
  }
}

const navigateToProduct = (product) => {
  router.push(`/products/${product.id}`)
}

onMounted(() => {
  fetchFavorites()
})
</script>

<style scoped>
</style>
