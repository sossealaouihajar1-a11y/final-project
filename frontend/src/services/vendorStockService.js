import apiClient from '@/api/axios'

export default {
  // Get stock overview
  getStock(params = {}) {
    return apiClient.get('/vendor/stock', { params })
  },

  // Get stock statistics
  getStatistics() {
    return apiClient.get('/vendor/stock/statistics')
  },

  // Get low stock products
  getLowStock(params = {}) {
    return apiClient.get('/vendor/stock/low-stock', { params })
  },

  // Get out of stock products
  getOutOfStock(params = {}) {
    return apiClient.get('/vendor/stock/out-of-stock', { params })
  },

  // Get stock alerts
  getAlerts() {
    return apiClient.get('/vendor/stock/alerts')
  },

  // Get stock movement history
  getHistory(params = {}) {
    return apiClient.get('/vendor/stock/history', { params })
  },

  // Update product stock
  updateStock(productId, data) {
    return apiClient.put(`/vendor/stock/${productId}`, data)
  },

  // Adjust stock
  adjustStock(productId, data) {
    return apiClient.post(`/vendor/stock/${productId}/adjust`, data)
  },

  // Bulk update stock
  bulkUpdate(data) {
    return apiClient.post('/vendor/stock/bulk-update', data)
  },

  // Export stock report
  exportStock(params = {}) {
    return apiClient.get('/vendor/stock/export', {
      params,
      responseType: 'blob'
    })
  }
}
