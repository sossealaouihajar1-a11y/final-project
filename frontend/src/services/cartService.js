import apiClient from '@/api/axios'

export default {
  // Créer une commande
  async checkout(items) {
    const cartItems = items.map(item => ({
      product_id: item.id,
      quantity: item.quantity
    }))
    
    const response = await apiClient.post('/cart/checkout', { items: cartItems })
    return response.data
  }
}