<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <router-link to="/products" class="flex items-center space-x-2">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">Vintage Shop</span>
            </router-link>
            <button @click="$router.back()" class="text-sm font-medium text-gray-700 hover:text-indigo-600 flex items-center space-x-1 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Retour</span>
            </button>
          </div>
          <div class="flex items-center space-x-6">
            <router-link v-if="authStore.isClient" to="/cart" class="relative text-gray-600 hover:text-indigo-600 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ cartStore.itemCount }}
              </span>
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="inline-block">
        <svg class="animate-spin h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
      </div>
    </div>

    <!-- Product Not Found -->
    <div v-else-if="!product" class="max-w-7xl mx-auto px-4 py-20 text-center">
      <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Produit introuvable</h2>
      <p class="text-gray-600 mb-6">Le produit que vous recherchez n'existe pas ou a été supprimé.</p>
      <router-link to="/products" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
        Retour au catalogue
      </router-link>
    </div>

    <!-- Product Details -->
    <div v-else class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm text-gray-600 mb-6">
        <router-link to="/products" class="hover:text-indigo-600">Catalogue</router-link>
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
        </svg>
        <span class="text-gray-900 font-medium">{{ product.title }}</span>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Images Section -->
        <div class="space-y-4">
          <!-- Main Image -->
          <div class="relative aspect-square bg-gray-100 rounded-2xl overflow-hidden shadow-lg">
            <img
              v-if="product.image_url"
              :src="product.image_url"
              :alt="product.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-32 h-32 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
              </svg>
            </div>
            
            <!-- Promotion Badge -->
            <div v-if="product.promotion > 0" class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg text-lg font-bold shadow-lg">
              -{{ product.promotion }}%
            </div>

            <!-- Favorite Button -->
            <button
              v-if="authStore.isClient"
              @click="toggleFavorite"
              class="absolute top-4 left-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center hover:scale-110 transition-transform"
            >
              <svg class="w-6 h-6" :class="isFavorite ? 'text-red-500 fill-current' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <!-- Additional Info Cards -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-600">Authenticité</p>
                  <p class="text-sm font-semibold text-gray-900">100% Garanti</p>
                </div>
              </div>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs text-gray-600">Livraison</p>
                  <p class="text-sm font-semibold text-gray-900">Rapide</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Product Info Section -->
        <div class="space-y-6">
          <!-- Title & Category -->
          <div>
            <div class="flex items-center space-x-3 mb-3">
              <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">
                {{ product.category }}
              </span>
              <span :class="getConditionClass(product.condition)" class="px-3 py-1 text-xs font-medium rounded-full">
                {{ getConditionLabel(product.condition) }}
              </span>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ product.title }}</h1>
            <p class="text-gray-600">Vendeur: {{ product.vendeur?.name || 'Vintage Shop' }}</p>
          </div>

          <!-- Price -->
          <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
            <div v-if="product.promotion > 0" class="flex items-center space-x-3 mb-2">
              <span class="text-2xl text-gray-400 line-through">{{ product.price }}€</span>
              <span class="px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-lg">
                -{{ product.promotion }}%
              </span>
            </div>
            <div class="flex items-baseline space-x-3">
              <span class="text-5xl font-bold text-indigo-600">{{ product.final_price }}€</span>
              <span v-if="product.promotion > 0" class="text-sm text-green-600 font-semibold">
                Économisez {{ (product.price - product.final_price).toFixed(2) }}€
              </span>
            </div>
          </div>

          <!-- Stock & Actions -->
          <div class="space-y-4">
            <div class="flex items-center space-x-3">
              <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z" />
              </svg>
              <span class="text-gray-700 font-medium">
                Stock: 
                <span :class="product.stock < 5 ? 'text-orange-600' : 'text-green-600'" class="font-bold">
                  {{ product.stock > 0 ? `${product.stock} disponible(s)` : 'Épuisé' }}
                </span>
              </span>
            </div>

            <!-- Add to Cart Button -->
            <div v-if="authStore.isClient">
              <button
                @click="addToCart"
                :disabled="product.stock === 0 || addingToCart"
                class="w-full px-8 py-4 bg-indigo-600 text-white font-bold text-lg rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-3 shadow-lg hover:shadow-xl"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>{{ addingToCart ? 'Ajout...' : product.stock === 0 ? 'Rupture de stock' : 'Ajouter au panier' }}</span>
              </button>
            </div>
            <div v-else class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
              <div class="flex items-start space-x-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                  <p class="font-semibold text-blue-800">Connexion requise</p>
                  <p class="text-sm text-blue-700 mt-1">Connectez-vous en tant que client pour acheter ce produit.</p>
                  <router-link to="/login" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Se connecter
                  </router-link>
                </div>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-3 text-lg flex items-center space-x-2">
              <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <span>Description</span>
            </h3>
            <p class="text-gray-700 leading-relaxed">{{ product.description }}</p>
          </div>

          <!-- Features -->
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
              <div class="flex items-center space-x-3 mb-2">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-semibold text-gray-900">Qualité</span>
              </div>
              <p class="text-sm text-gray-600">{{ getConditionLabel(product.condition) }}</p>
            </div>

            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
              <div class="flex items-center space-x-3 mb-2">
                <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                </svg>
                <span class="font-semibold text-gray-900">Catégorie</span>
              </div>
              <p class="text-sm text-gray-600">{{ product.category }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <ReviewSection :product-id="product.id" />
      </div>
    </div>

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
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useCartStore } from '@/stores/cartStore'
import productService from '@/services/productService'
import favoriteService from '@/services/favoritesService'
import ReviewSection from '@/components/ReviewSection.vue'

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

const product = ref(null)
const loading = ref(true)
const addingToCart = ref(false)
const isFavorite = ref(false)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadProduct = async () => {
  loading.value = true
  try {
    const productId = route.params.id
    product.value = await productService.getProduct(productId)
    
    // Vérifier si favori
    if (authStore.isClient) {
      checkFavorite()
    }
  } catch (error) {
    console.error('Erreur chargement produit:', error)
    product.value = null
  } finally {
    loading.value = false
  }
}

const checkFavorite = async () => {
  if (!authStore.isClient) return
  
  try {
    const response = await favoriteService.checkFavorite(product.value.id)
    isFavorite.value = response.is_favorite
  } catch (error) {
    console.error('Erreur vérification favori:', error)
  }
}

const toggleFavorite = async () => {
  if (!authStore.isClient) {
    showNotification('Connectez-vous pour ajouter aux favoris', 'error')
    return
  }

  try {
    const response = await favoriteService.toggleFavorite(product.value.id)
    isFavorite.value = response.is_favorite
    showNotification(response.message, 'success')
  } catch (error) {
    console.error('Erreur toggle favori:', error)
    showNotification('Erreur lors de l\'ajout aux favoris', 'error')
  }
}

const addToCart = async () => {
  if (product.value.stock === 0) return

  addingToCart.value = true
  try {
    await cartStore.addItem(product.value)
    showNotification('Produit ajouté au panier !', 'success')
  } catch (error) {
    console.error('Erreur ajout panier:', error)
    showNotification('Erreur lors de l\'ajout au panier', 'error')
  } finally {
    addingToCart.value = false
  }
}

const getConditionLabel = (condition) => {
  const labels = {
    neuf: 'Neuf',
    excellent: 'Excellent état',
    tres_bon: 'Très bon état',
    bon: 'Bon état',
    acceptable: 'État correct',
    a_restaurer: 'À restaurer'
  }
  return labels[condition] || condition
}

const getConditionClass = (condition) => {
  const classes = {
    neuf: 'bg-green-100 text-green-800',
    excellent: 'bg-blue-100 text-blue-800',
    tres_bon: 'bg-cyan-100 text-cyan-800',
    bon: 'bg-yellow-100 text-yellow-800',
    acceptable: 'bg-orange-100 text-orange-800',
    a_restaurer: 'bg-red-100 text-red-800'
  }
  return classes[condition] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  loadProduct()
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