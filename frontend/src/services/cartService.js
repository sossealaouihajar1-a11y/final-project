import apiClient from '@/api/axios'

export default {
  // Créer une commande
 async checkout(items, paymentMethod) {
  const response = await apiClient.post('/cart/checkout', {
    items: items,
    payment_method: paymentMethod
  })
  return response.data
},

  // Calculer les frais de livraison
  async calculateShipping(subtotal) {
    const response = await apiClient.post('/cart/calculate-shipping', { subtotal })
    return response.data
  }
}