import apiClient from '@/api/axios'

export default {
  // Récupérer tous les commentaires
  getAllReviews(params = {}) {
    return apiClient.get('/admin/reviews', { params })
  },

  // Supprimer un commentaire
  deleteReview(reviewId) {
    return apiClient.delete(`/admin/reviews/${reviewId}`)
  },

  // Récupérer les statistiques des commentaires
  getReviewsStatistics() {
    return apiClient.get('/admin/reviews/statistics')
  },

  // Récupérer un commentaire spécifique
  getReviewDetails(reviewId) {
    return apiClient.get(`/admin/reviews/${reviewId}`)
  }
}