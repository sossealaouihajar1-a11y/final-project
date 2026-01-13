import apiClient from '@/api/axios'

export default {
  // ... méthodes existantes ...

  /**
   * Récupérer tous les avis de l'utilisateur connecté
   */
  async getMyReviews() {
    const response = await apiClient.get('/reviews/my-reviews')
    return response.data
  },

  // Reste des méthodes...
  async getProductReviews(productId) {
    const response = await apiClient.get(`/products/${productId}/reviews`)
    return response.data
  },

  async checkUserReview(productId) {
    const response = await apiClient.get(`/reviews/check/${productId}`)
    return response.data
  },

  async createReview(data) {
    const response = await apiClient.post('/reviews', data)
    return response.data
  },

  async updateReview(reviewId, data) {
    const response = await apiClient.put(`/reviews/${reviewId}`, data)
    return response.data
  },

  async deleteReview(reviewId) {
    const response = await apiClient.delete(`/reviews/${reviewId}`)
    return response.data
  }
}