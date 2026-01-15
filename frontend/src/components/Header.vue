<template>
  <header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Logo -->
        <router-link to="/" class="flex items-center space-x-3 group">
          <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center transform group-hover:scale-110 transition duration-300 shadow-lg">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
          </div>
          <div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
              Vintage Shop
            </h1>
            <p class="text-xs text-gray-500">Collection Authentique</p>
          </div>
        </router-link>

        <!-- Navigation Desktop -->
        <nav class="hidden md:flex items-center space-x-8">
          <router-link 
            to="/" 
            class="text-gray-700 hover:text-indigo-600 font-medium transition relative group"
            active-class="text-indigo-600"
          >
            Accueil
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
          </router-link>
          <router-link 
            to="/products" 
            class="text-gray-700 hover:text-indigo-600 font-medium transition relative group"
            active-class="text-indigo-600"
          >
            Produits
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
          </router-link>
          <router-link 
            to="/categories" 
            class="text-gray-700 hover:text-indigo-600 font-medium transition relative group"
            active-class="text-indigo-600"
          >
            Catégories
            <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-indigo-600 group-hover:w-full transition-all duration-300"></span>
          </router-link>
        </nav>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
          <!-- Recherche Mobile -->
          <button 
            @click="toggleSearch"
            class="md:hidden p-2 text-gray-600 hover:text-indigo-600 transition"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>

          <!-- Favoris -->
          <button 
            @click="goToFavorites"
            class="relative p-2 text-gray-600 hover:text-red-500 transition group"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span 
              v-if="favoritesCount > 0" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold animate-pulse"
            >
              {{ favoritesCount }}
            </span>
            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
              Favoris
            </span>
          </button>

          <!-- Panier -->
          <button 
            @click="goToCart"
            class="relative p-2 text-gray-600 hover:text-indigo-600 transition group"
          >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span 
              v-if="cartCount > 0" 
              class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"
            >
              {{ cartCount }}
            </span>
            <span class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap">
              Panier
            </span>
          </button>

          <!-- Menu Utilisateur -->
          <div v-if="authStore.isAuthenticated" class="relative group">
            <button 
              @click="toggleUserMenu"
              class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition"
            >
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg">
                {{ userInitials }}
              </div>
              <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-if="showUserMenu"
              @click.stop
              class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden"
            >
              <div class="p-4 border-b border-gray-100">
                <p class="font-semibold text-gray-900">{{ authStore.user?.name }}</p>
                <p class="text-sm text-gray-500">{{ authStore.user?.email }}</p>
              </div>
              <div class="py-2">
                <router-link 
                  v-if="authStore.isAdmin"
                  to="/admin/dashboard"
                  @click="showUserMenu = false"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                >
                  Dashboard Admin
                </router-link>
                <router-link 
                  v-if="authStore.isVendor"
                  to="/vendor/dashboard"
                  @click="showUserMenu = false"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                >
                  Mon Espace Vendeur
                </router-link>
                <router-link 
                  v-if="authStore.isClient"
                  to="/client/dashboard"
                  @click="showUserMenu = false"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                >
                  Mon Compte
                </router-link>
                <button 
                  @click="handleLogout"
                  class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition"
                >
                  Déconnexion
                </button>
              </div>
            </div>
          </div>

          <!-- Bouton Connexion -->
          <router-link 
            v-else
            to="/login"
            class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            Connexion
          </router-link>

          <!-- Menu Mobile -->
          <button 
            @click="toggleMobileMenu"
            class="md:hidden p-2 text-gray-600 hover:text-indigo-600 transition"
          >
            <svg v-if="!showMobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Menu Mobile -->
      <transition name="slide">
        <div v-if="showMobileMenu" class="md:hidden border-t border-gray-200 py-4">
          <nav class="flex flex-col space-y-3">
            <router-link 
              to="/" 
              @click="showMobileMenu = false"
              class="px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition"
              active-class="bg-indigo-50 text-indigo-600"
            >
              Accueil
            </router-link>
            <router-link 
              to="/products" 
              @click="showMobileMenu = false"
              class="px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition"
              active-class="bg-indigo-50 text-indigo-600"
            >
              Produits
            </router-link>
            <router-link 
              to="/categories" 
              @click="showMobileMenu = false"
              class="px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition"
              active-class="bg-indigo-50 text-indigo-600"
            >
              Catégories
            </router-link>
          </nav>
        </div>
      </transition>
    </div>

    <!-- Overlay pour fermer le menu -->
    <div 
      v-if="showUserMenu || showMobileMenu"
      @click="closeMenus"
      class="fixed inset-0 z-40 md:hidden"
    ></div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useFavorites } from '@/composables/useFavorites'

const router = useRouter()
const authStore = useAuthStore()
const { loadFavorites, getFavoriteCount } = useFavorites()

const showUserMenu = ref(false)
const showMobileMenu = ref(false)
const cartCount = ref(0)

const favoritesCount = computed(() => getFavoriteCount.value)

const userInitials = computed(() => {
  if (!authStore.user?.name) return 'U'
  const names = authStore.user.name.split(' ')
  return names.map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
  showMobileMenu.value = false
}

const toggleMobileMenu = () => {
  showMobileMenu.value = !showMobileMenu.value
  showUserMenu.value = false
}

const toggleSearch = () => {
  router.push('/products')
}

const closeMenus = () => {
  showUserMenu.value = false
  showMobileMenu.value = false
}

const goToFavorites = () => {
  router.push('/favorites')
}

const goToCart = () => {
  // TODO: Implémenter la page panier
  alert('Fonctionnalité panier à venir')
}

const handleLogout = async () => {
  await authStore.logout()
  showUserMenu.value = false
  router.push('/')
}

const loadFavoritesCount = async () => {
  try {
    if (authStore.isAuthenticated) {
      await loadFavorites()
      // Favorites count is now computed automatically from getFavoriteCount
    }
  } catch (error) {
    console.error('Error loading favorites:', error)
  }
}

const handleClickOutside = (event) => {
  if (!event.target.closest('.relative')) {
    showUserMenu.value = false
  }
}

const updateFavoritesCount = async (event) => {
  // Reload favorites count from the server/composable
  await loadFavoritesCount()
}

onMounted(() => {
  loadFavoritesCount()
  window.addEventListener('click', handleClickOutside)
  // Listen for favorites updates
  window.addEventListener('favorites-updated', updateFavoritesCount)
  // Reload favorites when page becomes visible
  document.addEventListener('visibilitychange', () => {
    if (!document.hidden) {
      loadFavoritesCount()
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside)
  window.removeEventListener('favorites-updated', updateFavoritesCount)
})
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
  max-height: 500px;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  max-height: 0;
}
</style>

