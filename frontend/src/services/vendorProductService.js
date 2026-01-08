import apiClient from '@/api/axios'

export default {
  // Get all vendor products
  getAllProducts(params = {}) {
    return apiClient.get('/vendor/products', { params })
  },

  // Get product statistics
  getProductStatistics() {
    return apiClient.get('/vendor/products/statistics')
  },

  // Get categories
  getCategories() {
    return apiClient.get('/vendor/products/categories-list')
  },

  // Get single product
  getProduct(productId) {
    return apiClient.get(`/vendor/products/${productId}`)
  },

  // Create product
  createProduct(data) {
    // If data is FormData (for file uploads), send as-is
    if (data instanceof FormData) {
      return apiClient.post('/vendor/products', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    }
    return apiClient.post('/vendor/products', data)
  },

  // Update product
  updateProduct(productId, data) {
    // If data is FormData (for file uploads), send as-is
    if (data instanceof FormData) {
      return apiClient.post(`/vendor/products/${productId}?_method=PUT`, data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    }
    return apiClient.put(`/vendor/products/${productId}`, data)
  },

  // Delete product
  deleteProduct(productId) {
    return apiClient.delete(`/vendor/products/${productId}`)
  },

  // Bulk update status
  bulkUpdateStatus(productIds, status) {
    return apiClient.post('/vendor/products/bulk-status', {
      product_ids: productIds,
      status: status
    })
  },

  // Update product stock
  updateStock(productId, stock) {
    return apiClient.post(`/vendor/products/${productId}/stock`, {
      stock: stock
    })
  }
}
