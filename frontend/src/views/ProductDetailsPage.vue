<template>
  <div class="min-h-screen bg-white">
    <!-- Header -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <router-link to="/products" class="flex items-center space-x-2">
              <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-900 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">Vintage Shop</span>
            </router-link>
            <button @click="$router.back()" class="text-sm font-medium text-gray-700 hover:text-gray-900 flex items-center space-x-1 transition">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Back</span>
            </button>
          </div>
          <div class="flex items-center space-x-6">
            <router-link v-if="authStore.isClient" to="/cart" class="relative text-gray-600 hover:text-gray-900 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="cartStore.itemCount > 0" class="absolute -top-2 -right-2 bg-gray-900 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
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
        <svg class="animate-spin h-12 w-12 text-gray-900" fill="none" viewBox="0 0 24 24">
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
      <h2 class="text-2xl font-bold text-gray-900 mb-2">Product Not Found</h2>
      <p class="text-gray-600 mb-6">The product you're looking for doesn't exist.</p>
      <router-link to="/products" class="inline-block px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 font-medium transition">
        Back to Catalogue
      </router-link>
    </div>

    <!-- Product Details -->
    <div v-else class="max-w-7xl mx-auto px-4 py-8">
      <!-- Breadcrumb -->
      <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-8">
        <router-link to="/" class="hover:text-gray-900">Home</router-link>
        <span>/</span>
        <router-link to="/products" class="hover:text-gray-900">Home Décor</router-link>
        <span>/</span>
        <span class="hover:text-gray-900">{{ product.category }}</span>
        <span>/</span>
        <span class="text-gray-900">{{ product.title }}</span>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Images Gallery -->
        <div class="space-y-4">
          <!-- Main Image -->
          <div class="relative aspect-square bg-gray-50 overflow-hidden">
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

            <!-- Navigation Arrows -->
            <button class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition">
              <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-50 transition">
              <svg class="w-5 h-5 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>

          <!-- Thumbnail Images -->
          <div class="grid grid-cols-3 gap-4">
            <div class="aspect-square bg-gray-100 cursor-pointer hover:opacity-75 transition">
              <img v-if="product.image_url" :src="product.image_url" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-square bg-gray-100 cursor-pointer hover:opacity-75 transition">
              <img v-if="product.image_url" :src="product.image_url" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-square bg-gray-100 cursor-pointer hover:opacity-75 transition">
              <img v-if="product.image_url" :src="product.image_url" class="w-full h-full object-cover" />
            </div>
          </div>
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
          <!-- Vendor -->
          <div class="text-sm text-gray-600 uppercase tracking-widest">
            {{ product.vendeur?.name || 'ALEGRIACOLLECTION' }}
          </div>

          <!-- Title -->
          <h1 class="text-4xl font-serif text-gray-900 leading-tight">
            {{ product.title }}
          </h1>

          <!-- Price -->
          <div class="text-2xl font-medium text-gray-900">
            ${{ product.final_price }}
          </div>

          <!-- Description -->
          <div class="prose prose-sm text-gray-700 leading-relaxed border-t border-b border-gray-200 py-6">
            <p>{{ product.description }}</p>
          </div>

          <!-- Stock -->
          <div class="text-sm text-gray-700">
            <span class="font-medium">{{ product.stock }}</span> in stock
          </div>

          <!-- Add to Cart Button -->
          <div v-if="authStore.isClient">
            <button
              @click="addToCart"
              :disabled="product.stock === 0 || addingToCart"
              class="w-full px-8 py-4 bg-gray-800 text-white font-medium text-sm uppercase tracking-wider rounded-md hover:bg-gray-900 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {{ addingToCart ? 'Adding...' : product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
          </div>
          <div v-else>
            <router-link to="/login" class="block w-full px-8 py-4 bg-gray-800 text-white font-medium text-sm uppercase tracking-wider rounded-md hover:bg-gray-900 transition text-center">
              Login to Purchase
            </router-link>
          </div>

      
          <!-- Favorite Button -->
          <button
            v-if="authStore.isClient"
            @click="toggleFavorite"
            class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition"
          >
            <svg class="w-6 h-6" :class="isFavorite ? 'fill-current text-red-500' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Product Information Tabs -->
      <div class="border-t border-gray-200 mb-16">
        <button class="w-full text-left py-6 flex items-center justify-between text-gray-900 font-medium uppercase tracking-wide text-sm">
          <span>Product Information</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>

      <!-- Reviews Section -->
      <div class="border-t border-gray-200 py-16">
        <div class="text-center mb-12">
          <p class="text-sm text-gray-500 uppercase tracking-widest mb-4">Read Customer Reviews</p>
          <h2 class="text-3xl font-serif text-gray-900">What they're saying about {{ product.vendeur?.name || 'our shop' }}</h2>
        </div>

        <div class="max-w-4xl mx-auto">
          <ReviewSection :product-id="product.id" />
        </div>
      </div>

      <!-- More You'll Love -->
      <div class="border-t border-gray-200 py-16">
        <h2 class="text-3xl font-serif text-gray-900 text-center mb-12">More you'll love</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
          <div v-for="i in 4" :key="i" class="bg-gray-100 aspect-square"></div>
        </div>
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
    showNotification('Login to add to favorites', 'error')
    return
  }

  try {
    const response = await favoriteService.toggleFavorite(product.value.id)
    isFavorite.value = response.is_favorite
    showNotification(response.message, 'success')
  } catch (error) {
    console.error('Erreur toggle favori:', error)
    showNotification('Error adding to favorites', 'error')
  }
}

const addToCart = async () => {
  if (product.value.stock === 0) return

  addingToCart.value = true
  try {
    await cartStore.addItem(product.value)
    showNotification('Product added to cart!', 'success')
  } catch (error) {
    console.error('Erreur ajout panier:', error)
    showNotification('Error adding to cart', 'error')
  } finally {
    addingToCart.value = false
  }
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

.prose {
  max-width: none;
}

.prose p {
  margin: 0;
  line-height: 1.75;
}
</style>