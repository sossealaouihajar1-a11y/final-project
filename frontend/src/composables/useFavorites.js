import { ref, computed } from 'vue'
import favoritesService from '@/services/favoritesService'

const favoriteIds = ref(new Set())
const isLoading = ref(false)
const error = ref(null)

export const useFavorites = () => {
  const isFavorite = computed(() => (productId) => {
    return favoriteIds.value.has(productId)
  })

  const toggleFavorite = async (productId) => {
    try {
      isLoading.value = true
      error.value = null
      
      const response = await favoritesService.toggleFavorite(productId)
      
      if (response.is_favorite) {
        favoriteIds.value.add(productId)
      } else {
        favoriteIds.value.delete(productId)
      }
      
      return response.is_favorite
    } catch (err) {
      error.value = 'Erreur lors de la mise à jour du favori'
      console.error('Error toggling favorite:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const addFavorite = async (productId) => {
    try {
      isLoading.value = true
      error.value = null
      
      await favoritesService.addFavorite(productId)
      favoriteIds.value.add(productId)
    } catch (err) {
      error.value = 'Erreur lors de l\'ajout au favori'
      console.error('Error adding favorite:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const removeFavorite = async (productId) => {
    try {
      isLoading.value = true
      error.value = null
      
      const favorite = favoriteIds.value.has(productId)
      if (favorite) {
        // We need the favorite ID, so fetch from service if needed
        await favoritesService.toggleFavorite(productId)
        favoriteIds.value.delete(productId)
      }
    } catch (err) {
      error.value = 'Erreur lors de la suppression du favori'
      console.error('Error removing favorite:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const loadFavorites = async () => {
    try {
      isLoading.value = true
      error.value = null
      
      const response = await favoritesService.getFavorites()
      favoriteIds.value.clear()
      
      if (Array.isArray(response.data)) {
        response.data.forEach(fav => {
          favoriteIds.value.add(fav.product_id)
        })
      }
    } catch (err) {
      error.value = 'Erreur lors du chargement des favoris'
      console.error('Error loading favorites:', err)
    } finally {
      isLoading.value = false
    }
  }

  const getFavoriteCount = computed(() => {
    return favoriteIds.value.size
  })

  const clearAll = async () => {
    try {
      isLoading.value = true
      error.value = null
      
      await favoritesService.clearAll()
      favoriteIds.value.clear()
    } catch (err) {
      error.value = 'Erreur lors de la suppression de tous les favoris'
      console.error('Error clearing all favorites:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    favoriteIds,
    isLoading,
    error,
    isFavorite,
    toggleFavorite,
    addFavorite,
    removeFavorite,
    loadFavorites,
    getFavoriteCount,
    clearAll
  }
}
