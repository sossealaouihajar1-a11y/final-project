import apiClient from '@/api/axios'

export default {
  // Mettre à jour le profil
  async updateProfile(data) {
    const response = await apiClient.put('/profile', data)
    return response.data
  },

  // Changer le mot de passe
  async updatePassword(data) {
    const response = await apiClient.put('/profile/password', data)
    return response.data
  }
}