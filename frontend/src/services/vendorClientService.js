import apiClient from '@/api/axios'

export default {
  // Get all vendor clients
  getAllClients(params = {}) {
    return apiClient.get('/vendor/clients', { params })
  },

  // Get client statistics
  getStatistics() {
    return apiClient.get('/vendor/clients/statistics')
  },

  // Get single client details
  getClient(clientId) {
    return apiClient.get(`/vendor/clients/${clientId}`)
  },

  // Get recent orders
  getRecentOrders(params = {}) {
    return apiClient.get('/vendor/clients/recent-orders', { params })
  },

  // Export clients to CSV
  exportClients(params = {}) {
    return apiClient.get('/vendor/clients/export', {
      params,
      responseType: 'blob'
    })
  }
}
