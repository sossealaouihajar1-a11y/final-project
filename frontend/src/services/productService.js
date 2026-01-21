import apiClient from '@/api/axios'

export default {
  // Récupérer tous les produits avec filtres et pagination
  async getAllProducts(params = {}) {
    const response = await apiClient.get('/products', { params })
    return response.data
  },

  // Récupérer un produit par ID
  async getProduct(id) {
    const response = await apiClient.get(`/products/${id}`)
    return response.data
  },

  // Récupérer toutes les catégories
  async getCategories() {
    const response = await apiClient.get('/products/categories')
    return response.data
  },
  // Récupérer toutes les conditions
 async getConditions() {
    const response = await apiClient.get('/products/conditions')
    return response.data
  },
  // Récupérer les statistiques
  async getStats() {
    const response = await apiClient.get('/products/stats')
    return response.data
  },

  // Récupérer les produits en promotion
  async getPromotions() {
    const response = await apiClient.get('/products/promotions')
    return response.data
  },

  // Récupérer les produits par catégorie
  async getByCategory(category) {
    const response = await apiClient.get(`/products/category/${category}`)
    return response.data
  }
}