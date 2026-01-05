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

        <!-- Résumé -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Résumé de la commande</h2>

            <div class="space-y-4 mb-6">
              <div class="flex justify-between text-gray-600">
                <span>Sous-total</span>
                <span class="font-medium">{{ cartStore.totalPrice }}€</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Livraison</span>
                <span class="font-medium text-green-600">Gratuite</span>
              </div>
              <div class="border-t pt-4 flex justify-between text-lg font-bold text-gray-900">
                <span>Total</span>
                <span class="text-indigo-600">{{ cartStore.totalPrice }}€</span>
              </div>
            </div>

            <!-- Bouton Passer la commande -->
            <div v-if="!showShippingForm" class="mb-4">
              <button
                @click="showShippingForm = true"
                class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-xl"
              >
                Passer la commande
              </button>
            </div>

            <!-- Formulaire Adresse de Livraison -->
            <div v-else class="space-y-4 mb-4">
              <h3 class="font-bold text-gray-900 mb-4 flex items-center space-x-2">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                <span>Adresse de livraison</span>
              </h3>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet *</label>
                <input
                  v-model="shippingAddress.full_name"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                  placeholder="Jean Dupont"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                <input
                  v-model="shippingAddress.phone"
                  type="tel"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                  placeholder="+33 6 12 34 56 78"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
                <input
                  v-model="shippingAddress.address"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                  placeholder="123 Rue de la Paix"
                />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Code postal *</label>
                  <input
                    v-model="shippingAddress.postal_code"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                    placeholder="75001"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Ville *</label>
                  <input
                    v-model="shippingAddress.city"
                    type="text"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                    placeholder="Paris"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pays *</label>
                <input
                  v-model="shippingAddress.country"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                  placeholder="France"
                />
              </div>

              <button
                @click="proceedToCheckout"
                :disabled="processing || !isShippingValid"
                class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ processing ? 'Traitement...' : 'Confirmer la commande' }}
              </button>

              <button
                @click="showShippingForm = false"
                class="w-full px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition"
              >
                Annuler
              </button>
            </div>

            <router-link
              v-if="!showShippingForm"
              to="/products"
              class="block w-full px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition text-center"
            >
              Continuer mes achats
            </router-link>

            <div class="mt-6 pt-6 border-t space-y-4">
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Paiement sécurisé</div>
                  <p>Vos données sont protégées</p>
                </div>
              </div>
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                  <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Livraison gratuite</div>
                  <p>Sur toutes les commandes</p>
                </div>
              </div>
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Livraison rapide</div>
                  <p>Sous 3 à 5 jours ouvrés</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import cartService from '@/services/cartService'

const router = useRouter()
const cartStore = useCartStore()

const processing = ref(false)
const showShippingForm = ref(false)

const shippingAddress = ref({
  full_name: '',
  phone: '',
  address: '',
  city: '',
  postal_code: '',
  country: 'France'
})

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const isShippingValid = computed(() => {
  return shippingAddress.value.full_name &&
         shippingAddress.value.phone &&
         shippingAddress.value.address &&
         shippingAddress.value.city &&
         shippingAddress.value.postal_code &&
         shippingAddress.value.country
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
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
  if (!isShippingValid.value) {
    showNotification('Veuillez remplir tous les champs requis', 'error')
    return
  }

  processing.value = true

  try {
    const response = await cartService.checkout(cartStore.items, shippingAddress.value)
    
    showNotification('Commande créée avec succès! Vérifiez votre email pour la facture.', 'success')
    
    // Vider le panier
    cartStore.clearCart()
    
    // Rediriger vers le dashboard
    setTimeout(() => {
      router.push('/orders')
    }, 2000)
    
  } catch (error) {
    console.error('Erreur checkout:', error)
    const message = error.response?.data?.message || 'Erreur lors de la création de la commande'
    showNotification(message, 'error')
  } finally {
    processing.value = false
  }
}
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