import apiClient from '@/api/axios'

export default {
  // Get all vendor orders
  getAllOrders(params = {}) {
    return apiClient.get('/vendor/orders', { params })
  },

  // Get order statistics
  getStatistics() {
    return apiClient.get('/vendor/orders/statistics')
  },

  // Get single order details
  getOrder(orderId) {
    return apiClient.get(`/vendor/orders/${orderId}`)
  },

  // Get orders by status
  getOrdersByStatus(status, params = {}) {
    return apiClient.get(`/vendor/orders/status/${status}`, { params })
  },

  // Update order status
  updateOrderStatus(orderId, data) {
    return apiClient.put(`/vendor/orders/${orderId}/status`, data)
  },

  // Export orders to CSV
  exportOrders(params = {}) {
    return apiClient.get('/vendor/orders/export', {
      params,
      responseType: 'blob'
    })
  }
}
