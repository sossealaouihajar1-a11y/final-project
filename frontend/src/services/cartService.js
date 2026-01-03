import apiClient from '@/api/axios'

export default {
  // Créer une commande avec adresse de livraison
  async checkout(items, shippingAddress) {
    const cartItems = items.map(item => ({
      product_id: item.id,
      quantity: item.quantity
    }))
    
    const response = await apiClient.post('/cart/checkout', { 
      items: cartItems,
      shipping_address: shippingAddress
    })
    return response.data
  }
}