import apiClient from '@/api/axios'

export default {
  // Get all users
  getAllUsers(params = {}) {
    return apiClient.get('/admin/users', { params })
  },

  // Get user statistics
  getUserStatistics() {
    return apiClient.get('/admin/users/statistics')
  },

  // Get single user
  getUser(userId) {
    return apiClient.get(`/admin/users/${userId}`)
  },

  // Update user
  updateUser(userId, data) {
    return apiClient.put(`/admin/users/${userId}`, data)
  },

  // Delete user
  deleteUser(userId) {
    return apiClient.delete(`/admin/users/${userId}`)
  },

  // Suspend user
  suspendUser(userId) {
    return apiClient.post(`/admin/users/${userId}/suspend`)
  },

  // Reactivate user
  reactivateUser(userId) {
    return apiClient.post(`/admin/users/${userId}/reactivate`)
  }
}
