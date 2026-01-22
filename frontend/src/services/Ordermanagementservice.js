import axios from 'axios'

const API_URL = 'http://localhost:8000/api'

// Créer une instance axios avec le token
const getAuthHeaders = () => {
  const token = localStorage.getItem('token')
  return {
    headers: {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json',
    }
  }
}

const orderManagementService = {
  // Récupérer toutes les commandes avec filtres
  getAllOrders(params = {}) {
    return axios.get(`${API_URL}/admin/orders`, {
      ...getAuthHeaders(),
      params
    })
  },

  // Récupérer les statistiques des commandes
  getOrderStatistics() {
    return axios.get(`${API_URL}/admin/orders/statistics`, getAuthHeaders())
  },

  // Récupérer les détails d'une commande
  getOrderDetails(orderId) {
    return axios.get(`${API_URL}/admin/orders/${orderId}`, getAuthHeaders())
  },

  // Mettre à jour le statut d'une commande
  updateOrderStatus(orderId, status) {
    return axios.put(`${API_URL}/admin/orders/${orderId}/status`, { status }, getAuthHeaders())
  },

  // Supprimer une commande
  deleteOrder(orderId) {
    return axios.delete(`${API_URL}/admin/orders/${orderId}`, getAuthHeaders())
  },

  // Exporter les commandes en CSV
  exportOrders(params = {}) {
    return axios.get(`${API_URL}/admin/orders/export`, {
      ...getAuthHeaders(),
      params,
      responseType: 'blob'
    })
  }
}

export default orderManagementService