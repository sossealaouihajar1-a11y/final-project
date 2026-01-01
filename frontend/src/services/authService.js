import apiClient from '@/api/axios'

export default {
  async registerClient(data) {
    const response = await apiClient.post('/auth/register/client', data)
    if (response.data.token) {
      localStorage.setItem('token', response.data.token)
      localStorage.setItem('user', JSON.stringify(response.data.user))
    }
    return response.data
  },

  async registerVendor(formData) {
    const response = await apiClient.post('/auth/register/vendor', formData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })
    return response.data
  },

  async login(credentials) {
    const response = await apiClient.post('/auth/login', credentials)
    
    // Sauvegarder le token et l'utilisateur
    localStorage.setItem('token', response.data.token)
    localStorage.setItem('user', JSON.stringify(response.data.user))
    
    return response.data
  },

  async logout() {
    try {
      await apiClient.post('/auth/logout')
    } catch (error) {
      console.error('Erreur logout:', error)
    } finally {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  },

  async me() {
    const response = await apiClient.get('/auth/me')
    return response.data
  },

  getCurrentUser() {
    const userStr = localStorage.getItem('user')
    return userStr ? JSON.parse(userStr) : null
  },

  getToken() {
    return localStorage.getItem('token')
  },

  // Méthodes admin
  async getAllVendors(status = null) {
    const params = status ? { status } : {}
    const response = await apiClient.get('/admin/vendors', { params })
    return response.data
  },

  async approveVendor(vendorId) {
    const response = await apiClient.post(`/admin/vendors/${vendorId}/approve`)
    return response.data
  },

  async rejectVendor(vendorId) {
    const response = await apiClient.post(`/admin/vendors/${vendorId}/reject`)
    return response.data
  },

  async suspendVendor(vendorId) {
    const response = await apiClient.post(`/admin/vendors/${vendorId}/suspend`)
    return response.data
  },

  async reactivateVendor(vendorId) {
    const response = await apiClient.post(`/admin/vendors/${vendorId}/reactivate`)
    return response.data
  }
}