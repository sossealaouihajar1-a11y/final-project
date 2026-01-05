import apiClient from '@/api/axios'

export default {
  // Récupérer l'adresse
  async getAddress() {
    const response = await apiClient.get('/shipping-address')
    return response.data
  },

  // Créer ou mettre à jour l'adresse
  async saveAddress(data) {
    const response = await apiClient.post('/shipping-address', data)
    return response.data
  },

  // Supprimer l'adresse
  async deleteAddress() {
    const response = await apiClient.delete('/shipping-address')
    return response.data
  }
}