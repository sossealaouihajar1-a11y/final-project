import apiClient from '@/api/axios'

export default {
  // Récupérer tous les avis d'un produit
  async getProductReviews(productId) {
    const response = await apiClient.get(`/products/${productId}/reviews`)
    return response.data
  },

  // Vérifier si l'utilisateur a déjà laissé un avis
  async checkUserReview(productId) {
    const response = await apiClient.get(`/reviews/check/${productId}`)
    return response.data
  },

  // Créer un avis
  async createReview(data) {
    const response = await apiClient.post('/reviews', data)
    return response.data
  },

  // Mettre à jour un avis
  async updateReview(reviewId, data) {
    const response = await apiClient.put(`/reviews/${reviewId}`, data)
    return response.data
  },

  // Supprimer un avis
  async deleteReview(reviewId) {
    const response = await apiClient.delete(`/reviews/${reviewId}`)
    return response.data
  }
}