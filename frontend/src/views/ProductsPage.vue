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
            <router-link to="/products" class="text-sm font-medium text-indigo-600 border-b-2 border-indigo-600 pb-1">
              Catalogue
            </router-link>
          </div>
          <div class="flex items-center space-x-6">
            <!-- Favoris -->
            <router-link v-if="authStore.isClient" to="/favorites" class="relative text-gray-600 hover:text-red-500 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </router-link>
            <!-- Panier -->
            <router-link v-if="authStore.isClient" to="/cart" class="relative text-gray-600 hover:text-indigo-600 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ cartCount }}
              </span>
            </router-link>

            <!-- Navigation -->
            <router-link v-if="!authStore.isAuthenticated" to="/login" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
              Connexion
            </router-link>
            <router-link v-else-if="authStore.isAdmin" to="/admin/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
              Dashboard Admin
            </router-link>
            <router-link v-else-if="authStore.isVendor" to="/vendor/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
              Espace Vendeur
            </router-link>
            <router-link v-else-if="authStore.isClient" to="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
              Mon Compte
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-12 mb-10 text-white">
        <div class="max-w-3xl">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Collection Vintage</h1>
          <p class="text-xl text-indigo-100 mb-6">Découvrez des pièces uniques et authentiques</p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block">
          <svg class="animate-spin h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="mt-4 text-gray-600 font-medium">Chargement des produits...</p>
      </div>

      <!-- Grille de Produits -->
      <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition"
        >
          <!-- Image -->
          <div 
            class="relative h-64 bg-gray-100 cursor-pointer" 
            @click="goToProduct(product)"
          >
            <img
              v-if="product.image_url"
              :src="product.image_url"
              :alt="product.title"
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
              </svg>
            </div>
            
            <!-- Badge Promotion -->
            <div v-if="product.promotion > 0" class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold">
              -{{ product.promotion }}%
            </div>
          </div>

          <!-- Contenu -->
          <div class="p-5">
            <div class="mb-3 cursor-pointer" @click="goToProduct(product)">
              <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 hover:text-indigo-600">
                {{ product.title }}
              </h3>
              <p class="text-sm text-gray-500">{{ product.category }}</p>
            </div>
            
            <!-- Prix -->
            <div class="flex items-center justify-between mb-4">
              <div>
                <div v-if="product.promotion > 0" class="text-sm text-gray-400 line-through">
                  {{ product.price }}€
                </div>
                <div class="text-xl font-bold text-indigo-600">
                  {{ product.final_price }}€
                </div>
              </div>
            </div>

            <!-- Bouton -->
            <button
              v-if="authStore.isClient"
              @click="addToCart(product)"
              :disabled="product.stock === 0"
              class="w-full px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-50"
            >
              {{ product.stock === 0 ? 'Épuisé' : 'Ajouter au panier' }}
            </button>
            <button
              v-else
              @click="goToProduct(product)"
              class="w-full px-4 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition"
            >
              Voir les détails
            </button>
          </div>
        </div>
      </div>

      <!-- Aucun produit -->
      <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun produit trouvé</h3>
        <p class="text-gray-600 mb-6">Aucun produit disponible pour le moment</p>
      </div>
    </main>

    <!-- Toast -->
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
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useCartStore } from '@/stores/cartStore'
import productService from '@/services/productService'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()

const products = ref([])
const loading = ref(false)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const cartCount = computed(() => cartStore.itemCount)

// Fonction pour encoder le titre
const encodeTitle = (title) => {
  return encodeURIComponent(
    title
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/\s+/g, '-')
      .replace(/-+/g, '-')
  )
}

// Navigation vers produit
const goToProduct = (product) => {
  const encodedTitle = encodeTitle(product.title)
  console.log('Navigation vers:', encodedTitle)
  router.push(`/products/${encodedTitle}`)
}

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadProducts = async () => {
  loading.value = true
  console.log('🔄 Chargement des produits...')
  
  try {
    const response = await productService.getAllProducts({})
    console.log('✅ Produits reçus:', response)
    products.value = response.data || []
  } catch (error) {
    console.error('❌ Erreur chargement produits:', error)
    showNotification('Erreur lors du chargement des produits', 'error')
  } finally {
    loading.value = false
  }
}

const addToCart = (product) => {
  if (product.stock === 0) {
    showNotification('Ce produit est en rupture de stock', 'error')
    return
  }

  const result = cartStore.addItem(product)
  
  if (result.success) {
    showNotification('Produit ajouté au panier!')
  } else {
    showNotification(result.message, 'error')
  }
}

onMounted(() => {
  console.log('🚀 ProductsPage monté')
  loadProducts()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

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
