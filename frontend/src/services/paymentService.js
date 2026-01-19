import apiClient from '@/api/axios'

export default {
  /**
   * Create a payment intent
   */
  async createPaymentIntent(orderId) {
    const response = await apiClient.post('/payments/create-intent', {
      order_id: orderId
    })
    return response.data
  },

  /**
   * Confirm payment
   */
  async confirmPayment(paymentIntentId, orderId) {
    const response = await apiClient.post('/payments/confirm', {
      payment_intent_id: paymentIntentId,
      order_id: orderId
    })
    return response.data
  },

  /**
   * Get payment status
   */
  async getPaymentStatus(paymentIntentId) {
    const response = await apiClient.get(`/payments/${paymentIntentId}/status`)
    return response.data
  }
}
