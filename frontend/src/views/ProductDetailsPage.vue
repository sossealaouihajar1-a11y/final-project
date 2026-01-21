<template>
  <div class="min-h-screen bg-white flex flex-col">

    <!-- Header -->
    <Header />

    <!-- Back button -->
    <div class="max-w-7xl mx-auto w-full px-4 mt-4">
      <button
        @click="$router.back()"
        class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back
      </button>
    </div>

    <!-- Main -->
    <main class="flex-1">

      <!-- Loading -->
      <div v-if="loading" class="flex items-center justify-center py-24">
        <svg class="animate-spin h-12 w-12 text-gray-900" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10"
            stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
      </div>

      <!-- Product not found -->
      <div v-else-if="!product" class="max-w-7xl mx-auto px-4 py-20 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">
          Product Not Found
        </h2>
        <p class="text-gray-600 mb-6">
          The product you're looking for doesn't exist.
        </p>
        <router-link
          to="/products"
          class="inline-block px-6 py-3 bg-gray-900 text-white rounded-md hover:bg-gray-800 transition"
        >
          Back to catalogue
        </router-link>
      </div>

      <!-- Product details -->
      <div v-else class="max-w-7xl mx-auto px-4 py-10">

        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-8 flex items-center gap-2">
          <router-link to="/" class="hover:text-gray-900">Home</router-link>
          <span>/</span>
          <router-link to="/products" class="hover:text-gray-900">Products</router-link>
          <span>/</span>
          <span class="text-gray-900">{{ product.title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 mb-20">

          <!-- Images -->
          <div class="space-y-4">
            <div class="aspect-square bg-gray-50 overflow-hidden">
              <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.title"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full flex items-center justify-center">
                <svg class="w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- Info -->
          <div class="space-y-6">
            <p class="text-sm uppercase tracking-widest text-gray-500">
              {{ product.vendeur?.name || 'NOSTALGIA COLLECTIVE' }}
            </p>

            <h1 class="text-4xl font-serif text-gray-900">
              {{ product.title }}
            </h1>

            <p class="text-2xl text-gray-900 font-medium">
              MAD {{ product.final_price }}
            </p>

            <div class="border-t border-b border-gray-200 py-6 text-gray-700 leading-relaxed">
              {{ product.description }}
            </div>

            <p class="text-sm text-gray-600" :class="product.stock === 0 ? 'text-red-600 font-semibold' : ''">
              <strong v-if="product.stock === 0">Rupture de stock</strong>
              <strong v-else>{{ product.stock }} in stock</strong>
            </p>

            <!-- Add to cart -->
            <button
              v-if="authStore.isClient"
              @click="addToCart"
              :disabled="product.stock === 0 || addingToCart"
              class="w-full px-8 py-4 bg-gray-900 text-white uppercase text-sm tracking-wider rounded-md hover:bg-gray-800 transition disabled:opacity-50"
            >
              {{ addingToCart ? 'Adding...' : 'Add to cart' }}
            </button>

            <router-link
              v-else
              to="/login"
              class="block w-full text-center px-8 py-4 bg-gray-900 text-white uppercase text-sm tracking-wider rounded-md hover:bg-gray-800 transition"
            >
              Login to purchase
            </router-link>

            <!-- Favorite -->
            <button
              v-if="authStore.isClient"
              @click="toggleFavorite"
              class="flex items-center gap-2 text-gray-700 hover:text-gray-900"
            >
              <svg
                class="w-6 h-6"
                :class="isFavorite ? 'fill-current text-red-500' : ''"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span>Favorite</span>
            </button>

          </div>
        </div>

        <!-- Reviews -->
        <div v-if="authStore.isClient" class="border-t border-gray-200 pt-16">
          <ReviewSection :product-id="product.id" />
        </div>

      </div>
    </main>

    <!-- Footer -->
    <Footer />

    <!-- Toast -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white shadow-xl p-4 rounded-lg border-l-4 z-50"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-green-500'"
      >
        <p class="font-medium text-gray-800">{{ notification.message }}</p>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
import ReviewSection from '@/components/ReviewSection.vue'

import { useAuthStore } from '@/stores/authStore'
import { useCartStore } from '@/stores/cartStore'
import productService from '@/services/productService'
import favoriteService from '@/services/favoritesService'

const route = useRoute()
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
  setTimeout(() => (notification.value.show = false), 3000)
}

const loadProduct = async () => {
  loading.value = true
  try {
    const decodedTitle = decodeURIComponent(route.params.id)
    const response = await productService.getAllProducts({})
    const products = response.data || response

    const normalize = t =>
      t.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-')

    product.value =
      products.find(p => normalize(p.title) === normalize(decodedTitle)) || null

    if (authStore.isClient && product.value) checkFavorite()
  } catch (e) {
    product.value = null
  } finally {
    loading.value = false
  }
}

const checkFavorite = async () => {
  const res = await favoriteService.checkFavorite(product.value.id)
  isFavorite.value = res.is_favorite
}

const toggleFavorite = async () => {
  const res = await favoriteService.toggleFavorite(product.value.id)
  isFavorite.value = res.is_favorite
  showNotification(res.message)
}

const addToCart = async () => {
  addingToCart.value = true
  await cartStore.addItem(product.value)
  showNotification('Product added to cart')
  addingToCart.value = false
}

onMounted(loadProduct)
</script>

<style scoped>
.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}
.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100px);
  opacity: 0;
}
</style>
