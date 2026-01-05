import apiClient from '@/api/axios'

export default {
  // Get all products
  getAllProducts(params = {}) {
    return apiClient.get('/admin/products', { params })
  },

  // Get product statistics
  getProductStatistics() {
    return apiClient.get('/admin/products/statistics')
  },

  // Get categories
  getCategories() {
    return apiClient.get('/admin/products/categories-list')
  },

  // Get single product
  getProduct(productId) {
    return apiClient.get(`/admin/products/${productId}`)
  },

  // Create product with file upload
  createProduct(data) {
    const formData = new FormData()
    
    console.log('Product data received:', data)
    
    // Add form fields
    Object.keys(data).forEach(key => {
      if (key === 'image' && data[key] instanceof File) {
        console.log(`Adding image file: ${data[key].name}, type: ${data[key].type}, size: ${data[key].size}`)
        formData.append('image', data[key])
      } else if (data[key] !== null && data[key] !== undefined && key !== 'image_url') {
        console.log(`Adding field: ${key} = ${data[key]}`)
        formData.append(key, data[key])
      }
    })

    // Log what we're sending for debugging
    console.log('Creating product with FormData fields:')
    for (let pair of formData.entries()) {
      if (pair[1] instanceof File) {
        console.log(`${pair[0]}: [File] ${pair[1].name} (${pair[1].type}, ${pair[1].size} bytes)`)
      } else {
        console.log(`${pair[0]}: ${pair[1]}`)
      }
    }

    return apiClient.post('/admin/products', formData)
  },

  // Update product with optional file upload
  updateProduct(productId, data) {
    const formData = new FormData()
    
    // Add form fields
    Object.keys(data).forEach(key => {
      if (key === 'image' && data[key] instanceof File) {
        formData.append('image', data[key])
      } else if (data[key] !== null && data[key] !== undefined && key !== 'image_url') {
        formData.append(key, data[key])
      }
    })

    return apiClient.post(`/admin/products/${productId}?_method=PUT`, formData)
  },

  // Delete product
  deleteProduct(productId) {
    return apiClient.delete(`/admin/products/${productId}`)
  },

  // Toggle product active status
  toggleProductActive(productId) {
    return apiClient.post(`/admin/products/${productId}/toggle-active`)
  }
}
