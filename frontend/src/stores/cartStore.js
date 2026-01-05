import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore = defineStore('cart', () => {
  // State
  const items = ref([])

  // Load from localStorage
  const loadCart = () => {
    const savedCart = localStorage.getItem('cart')
    if (savedCart) {
      items.value = JSON.parse(savedCart)
    }
  }

  // Save to localStorage
  const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(items.value))
  }

  // Getters
  const itemCount = computed(() => {
    return items.value.reduce((total, item) => total + item.quantity, 0)
  })

  const totalPrice = computed(() => {
    return items.value.reduce((total, item) => {
      return total + (item.price * item.quantity)
    }, 0).toFixed(2)
  })

  const isEmpty = computed(() => items.value.length === 0)

  // Actions
  const addItem = (product) => {
    const existingItem = items.value.find(item => item.id === product.id)

    if (existingItem) {
      if (existingItem.quantity < product.stock) {
        existingItem.quantity++
        saveCart()
        return { success: true, message: 'Quantité mise à jour' }
      } else {
        return { success: false, message: 'Stock insuffisant' }
      }
    } else {
      items.value.push({
        id: product.id,
        title: product.title,
        price: product.final_price || product.price,
        image_url: product.image_url,
        quantity: 1,
        stock: product.stock,
        category: product.category
      })
      saveCart()
      return { success: true, message: 'Produit ajouté au panier' }
    }
  }

  const removeItem = (productId) => {
    items.value = items.value.filter(item => item.id !== productId)
    saveCart()
  }

  const incrementQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item && item.quantity < item.stock) {
      item.quantity++
      saveCart()
      return { success: true }
    }
    return { success: false, message: 'Stock maximum atteint' }
  }

  const decrementQuantity = (productId) => {
    const item = items.value.find(item => item.id === productId)
    if (item) {
      if (item.quantity > 1) {
        item.quantity--
        saveCart()
      } else {
        removeItem(productId)
      }
      return { success: true }
    }
    return { success: false }
  }

  const clearCart = () => {
    items.value = []
    saveCart()
  }

  // Initialize
  loadCart()

  return {
    items,
    itemCount,
    totalPrice,
    isEmpty,
    addItem,
    removeItem,
    incrementQuantity,
    decrementQuantity,
    clearCart,
    loadCart
  }
})