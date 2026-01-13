import apiClient from '@/api/axios'

export default {
  // Get all favorites for the current user
  async getFavorites() {
    const response = await apiClient.get('/favorites')
    return response.data
  },

  // Add a product to favorites
  async addFavorite(productId) {
    const response = await apiClient.post('/favorites', { product_id: productId })
    return response.data
  },

  // Remove a product from favorites
  async removeFavorite(favoriteId) {
    const response = await apiClient.delete(`/favorites/${favoriteId}`)
    return response.data
  },

  // Toggle favorite status for a product
  async toggleFavorite(productId) {
    const response = await apiClient.post('/favorites/toggle', { product_id: productId })
    return response.data
  },

  // Check if a product is favorited
  async isFavorite(productId) {
    const response = await apiClient.get(`/favorites/${productId}/check`)
    return response.data
  },

  // Get count of favorites
  async getFavoriteCount() {
    const response = await apiClient.get('/favorites/count')
    return response.data
  },

  // Clear all favorites
  async clearAll() {
    const response = await apiClient.delete('/favorites')
    return response.data
  }
}
