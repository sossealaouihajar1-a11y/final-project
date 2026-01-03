import apiClient from '@/api/axios'

export default {
  // Récupérer toutes les commandes
  async getOrders(page = 1) {
    const response = await apiClient.get('/orders', { params: { page } })
    return response.data
  },

  // Récupérer une commande
  async getOrder(id) {
    const response = await apiClient.get(`/orders/${id}`)
    return response.data
  },

  // Annuler une commande
  async cancelOrder(id) {
    const response = await apiClient.post(`/orders/${id}/cancel`)
    return response.data
  }
}