<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <router-link to="/" class="flex items-center space-x-2">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">Vintage Shop</span>
            </router-link>
            <router-link to="/products" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
              Catalogue
            </router-link>
          </div>
          <div class="flex items-center space-x-6">
            <router-link to="/favorites" class="relative text-gray-600 hover:text-red-500 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </router-link>
            <router-link to="/cart" class="relative text-indigo-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ cartStore.itemCount }}
              </span>
            </router-link>
            <router-link to="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600">
              Mon Compte
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Titre -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3">
          <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>Mon Panier</span>
        </h1>
        <p class="text-gray-600 mt-2">{{ cartStore.itemCount }} article(s) dans votre panier</p>
      </div>

      <!-- Panier vide -->
      <div v-if="cartStore.isEmpty" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre panier est vide</h2>
        <p class="text-gray-600 mb-6">Découvrez nos produits vintage uniques</p>
        <router-link to="/products" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
          Parcourir la boutique
        </router-link>
      </div>

      <!-- Panier avec articles -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des articles -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition"
          >
            <div class="flex flex-col sm:flex-row">
              <!-- Image -->
              <div class="sm:w-48 h-48 bg-gray-100 flex items-center justify-center flex-shrink-0">
                <img
                  v-if="item.image_url"
                  :src="item.image_url"
                  :alt="item.title"
                  class="w-full h-full object-cover"
                />
                <svg v-else class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </div>

              <!-- Contenu -->
              <div class="flex-1 p-6">
                <div class="flex justify-between items-start mb-4">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ item.title }}</h3>
                    <p class="text-sm text-gray-600">{{ item.category }}</p>
                  </div>
                  <button
                    @click="removeItem(item.id)"
                    class="text-red-500 hover:text-red-700 transition"
                    title="Supprimer"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                  <!-- Prix -->
                  <div class="mb-4 sm:mb-0">
                    <div class="text-2xl font-bold text-indigo-600">
                      {{ (item.price * item.quantity).toFixed(2) }}€
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ item.price }}€ × {{ item.quantity }}
                    </div>
                  </div>

                  <!-- Quantité -->
                  <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-2 border border-gray-200">
                    <button
                      @click="decrementQuantity(item.id)"
                      class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-gray-100 transition font-bold border border-gray-300"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                    <span class="font-semibold text-lg min-w-[2rem] text-center">{{ item.quantity }}</span>
                    <button
                      @click="incrementQuantity(item.id)"
                      :disabled="item.quantity >= item.stock"
                      class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-gray-100 transition font-bold disabled:opacity-50 disabled:cursor-not-allowed border border-gray-300"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Stock -->
                <div class="mt-3 text-sm flex items-center space-x-2" :class="item.quantity >= item.stock ? 'text-orange-600' : 'text-gray-600'">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                  </svg>
                  <span>Stock disponible: {{ item.stock }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Récapitulatif</h2>

            <!-- Price Details -->
            <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
              <div class="flex justify-between text-gray-600">
                <span>Sous-total</span>
                <span class="font-medium">{{ subtotal.toFixed(2) }}€</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Livraison</span>
                <span class="font-medium" :class="shippingCost === 0 ? 'text-green-600' : ''">
                  {{ shippingCost === 0 ? 'GRATUIT' : shippingCost.toFixed(2) + '€' }}
                </span>
              </div>

              <!-- Progress Bar -->
              <div v-if="subtotal < 400" class="pt-2">
                <div class="flex justify-between text-xs text-gray-600 mb-2">
                  <span>Livraison gratuite à partir de 400€</span>
                  <span class="font-medium">{{ (400 - subtotal).toFixed(2) }}€</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-indigo-500 to-green-500 transition-all duration-500" :style="{ width: `${(subtotal / 400) * 100}%` }"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Plus que {{ (400 - subtotal).toFixed(2) }}€ pour la livraison gratuite !</p>
              </div>
              <div v-else class="pt-2">
                <div class="flex items-center space-x-2 text-green-600 text-sm font-medium">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Vous bénéficiez de la livraison gratuite !</span>
                </div>
              </div>
            </div>

            <div class="flex justify-between text-xl font-bold text-gray-900 mb-6">
              <span>Total</span>
              <span class="text-indigo-600">{{ totalPrice.toFixed(2) }}€</span>
            </div>

            <!-- Checkout Button -->
            <button
              @click="proceedToCheckout"
              :disabled="cartStore.isEmpty || processing"
              class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
            >
              <svg v-if="!processing" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <svg v-else class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span>{{ processing ? 'Redirection...' : 'Procéder au checkout' }}</span>
            </button>
            <p class="text-xs text-gray-500 text-center mt-3">
              Vous pourrez enregistrer votre adresse et choisir votre méthode de paiement sur la page suivante
            </p>
          </div>
        </div>
      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : notification.type === 'info' ? 'border-blue-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else-if="notification.type === 'info'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-gray-800 font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'

const router = useRouter()
const cartStore = useCartStore()

const processing = ref(false)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

// Removed: hasShippingAddress - no longer needed in cart

// Calculs
const subtotal = computed(() => {
  return parseFloat(cartStore.totalPrice) || 0
})

const shippingCost = computed(() => {
  return subtotal.value >= 400 ? 0 : 5
})

const totalPrice = computed(() => {
  return subtotal.value + shippingCost.value
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadShippingAddress = async () => {
  try {
    const result = await shippingAddressService.getAddress()
    console.log('📍 Résultat getAddress:', result)
    
    // Vérifier si le résultat est valide
    if (result && typeof result === 'object' && Object.keys(result).length > 0) {
      shippingAddress.value = result
      console.log('✅ Adresse chargée avec succès:', shippingAddress.value)
    } else {
      shippingAddress.value = null
      console.log('⚠️ Aucune adresse trouvée')
    }
  } catch (error) {
    console.error('❌ Erreur lors du chargement de l\'adresse:', error)
    shippingAddress.value = null
  }
}

const removeItem = (productId) => {
  if (confirm('Voulez-vous retirer cet article du panier ?')) {
    cartStore.removeItem(productId)
    showNotification('Article retiré du panier')
  }
}

const incrementQuantity = (productId) => {
  const result = cartStore.incrementQuantity(productId)
  if (!result.success) {
    showNotification(result.message || 'Stock maximum atteint', 'error')
  }
}

const decrementQuantity = (productId) => {
  cartStore.decrementQuantity(productId)
}

const proceedToCheckout = async () => {
  if (cartStore.isEmpty) {
    showNotification('Votre panier est vide', 'error')
    return
  }

  processing.value = true
  
  try {
    // Rediriger vers la page de checkout
    router.push('/checkout')
  } catch (error) {
    console.error('Erreur:', error)
    showNotification('Erreur lors de la redirection', 'error')
  } finally {
    processing.value = false
  }
}

const proceedToPayment = async () => {
  paymentProcessing.value = true
  
  try {
    // TODO: Intégrer Stripe ici plus tard
    showNotification('Paiement Stripe à venir...', 'info')
    
    // Pour l'instant, rediriger vers le dashboard après 2 secondes
    setTimeout(() => {
      router.push('/client/dashboard')
    }, 2000)
    
  } catch (error) {
    console.error('Erreur paiement:', error)
    showNotification('Erreur lors du paiement', 'error')
  } finally {
    paymentProcessing.value = false
  }
}

onMounted(() => {
  // Component ready
})
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  transform: translateY(100px);
  opacity: 0;
}

.slide-up-leave-to {
  transform: translateY(100px);
  opacity: 0;
}
</style>