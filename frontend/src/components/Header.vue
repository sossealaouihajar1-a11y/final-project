<template>
  <header class="bg-[#f7f6f3] border-b border-gray-300 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-3 py-3 flex items-center justify-between">

      <!-- Menu gauche (desktop) -->
      <nav class="hidden md:flex space-x-4 text-sm uppercase tracking-widest text-gray-700">
        <router-link
          to="/"
          class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          Home
        </router-link>
        <router-link
          to="/products"
          class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          Shop
        </router-link>
        <router-link
          to="/categories"
          class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          Categories
        </router-link>
      </nav>

      <!-- LOGO CENTRÉ -->
      <router-link
        to="/"
        class="flex flex-col items-center text-[#6b4f3a] group"
      >
        <div class="flex items-center font-serif">
          <span
            class="text-2xl font-bold uppercase tracking-[0.3em] transition-all duration-300 group-hover:tracking-[0.4em] text-[#6b4f3a]"
          >
            Nostalgia
          </span>

          <span class="mx-2 text-[#a2b38b] text-lg">✦</span>

          <span
            class="text-2xl font-bold uppercase tracking-[0.3em] transition-all duration-300 group-hover:tracking-[0.4em] text-[#2a2a28]"
          >
            Collective
          </span>
        </div>

        <span
          class="mt-1 text-[9px] uppercase tracking-[0.35em] text-gray-500 italic"
        >
          Timeless Finds
        </span>
      </router-link>

      <!-- Menu droit (desktop) -->
      <nav class="hidden md:flex space-x-4 text-sm uppercase tracking-widest text-gray-700 items-center">
        <router-link
          to="/about"
          class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          About
        </router-link>
        <router-link
          to="/contact"
          class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          Contact
        </router-link>

        <!-- Si client authentifié -->
        <template v-if="isAuthenticated && userRole === 'client'">
          <span class="text-gray-500">|</span>
          <router-link
            to="/cart"
            class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
          >
            Cart
          </router-link>
          <span class="text-gray-500">|</span>
          
          <!-- Dropdown Menu -->
          <div 
            class="relative"
            @mouseenter="showDropdown = true"
            @mouseleave="showDropdown = false"
          >
            <button
              class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition flex items-center"
            >
              {{ userName }}
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
            </button>

            <!-- Dropdown Content -->
            <div 
              v-if="showDropdown"
              class="absolute right-0 mt-0 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-10"
            >
              <router-link
                :to="getDashboardLink()"
                class="block w-full text-left px-4 py-2 hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition first:rounded-t-md"
              >
                {{ getDashboardLabel() }}
              </router-link>
              <button
                @click="logout"
                class="block w-full text-left px-4 py-2 hover:bg-[#6b4f3a] hover:text-white transition last:rounded-b-md border-t border-gray-300"
              >
                Logout
              </button>
            </div>
          </div>
        </template>

        <!-- Si authentifié mais pas client (vendor/admin) -->
        <template v-else-if="isAuthenticated">
          <span class="text-gray-500">|</span>
          
          <!-- Dropdown Menu -->
          <div 
            class="relative"
            @mouseenter="showDropdown = true"
            @mouseleave="showDropdown = false"
          >
            <button
              class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition flex items-center"
            >
              {{ userName }}
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
            </button>

            <!-- Dropdown Content -->
            <div 
              v-if="showDropdown"
              class="absolute right-0 mt-0 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-10"
            >
              <router-link
                :to="getDashboardLink()"
                class="block w-full text-left px-4 py-2 hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition first:rounded-t-md"
              >
                {{ getDashboardLabel() }}
              </router-link>
              <button
                @click="logout"
                class="block w-full text-left px-4 py-2 hover:bg-[#6b4f3a] hover:text-white transition last:rounded-b-md border-t border-gray-300"
              >
                Logout
              </button>
            </div>
          </div>
        </template>

        <!-- Si pas connecté -->
        <template v-else>
          <span class="text-gray-500">|</span>
          <router-link
            to="/login"
            class="px-3 py-1 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
          >
            Login
          </router-link>
        </template>
      </nav>

      <!-- Hamburger mobile -->
      <button
        @click="isOpen = !isOpen"
        class="md:hidden text-gray-700 focus:outline-none"
      >
        <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
        <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Menu mobile -->
    <div v-if="isOpen" class="md:hidden px-3 pb-3 space-y-2 bg-[#f7f6f3]">
      <router-link
        to="/"
        class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
      >
        Home
      </router-link>
      <router-link
        to="/products"
        class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
      >
        Shop
      </router-link>
      <router-link
        to="/categories"
        class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
      >
        Categories
      </router-link>
      <router-link
        to="/about"
        class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
      >
        About
      </router-link>
      <router-link
        to="/contact"
        class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
      >
        Contact
      </router-link>

      <!-- Si client authentifié (mobile) -->
      <template v-if="isAuthenticated && userRole === 'client'">
        <router-link
          to="/cart"
          class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
        >
          Cart
        </router-link>
        <div class="border-t border-gray-300 pt-2 mt-2">
          <router-link
            to="/profile"
            class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
          >
            {{ userName }}
          </router-link>
          <button
            @click="logout"
            class="w-full text-left px-3 py-2 bg-[#6b4f3a] text-white rounded-md hover:bg-[#a2b38b] transition"
          >
            Logout
          </button>
        </div>
      </template>

      <!-- Si authentifié mais pas client (mobile) -->
      <template v-else-if="isAuthenticated">
        <div class="border-t border-gray-300 pt-2 mt-2">
          <router-link
            to="/profile"
            class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
          >
            {{ userName }}
          </router-link>
          <button
            @click="logout"
            class="w-full text-left px-3 py-2 bg-[#6b4f3a] text-white rounded-md hover:bg-[#a2b38b] transition"
          >
            Logout
          </button>
        </div>
      </template>

      <!-- Si pas connecté (mobile) -->
      <template v-else>
        <div class="border-t border-gray-300 pt-2 mt-2">
          <router-link
            to="/login"
            class="block px-3 py-2 rounded-md hover:bg-[#e4e1d8] hover:text-[#6b4f3a] transition"
          >
            Login
          </router-link>
        </div>
      </template>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue'
import { useRouter } from 'vue-router'
import apiClient from '@/api/axios'

const router = useRouter()
const isOpen = ref(false)
const showDropdown = ref(false)

// État réactif pour l'authentification
const token = ref(localStorage.getItem('token'))
const user = ref(localStorage.getItem('user'))

// Computed pour vérifier l'authentification
const isAuthenticated = computed(() => !!token.value)

// Données utilisateur parsées
const userData = computed(() => {
  try {
    return user.value ? JSON.parse(user.value) : null
  } catch {
    return null
  }
})

const userName = computed(() => userData.value?.name || userData.value?.email || 'Mon Espace')
const userRole = computed(() => userData.value?.role || 'client')

// Initialiser au montage
onMounted(() => {
  // Écouter les changements de localStorage
  window.addEventListener('storage', updateAuth)
  
  // Listener pour les changements dans la même tab
  window.addEventListener('authChange', updateAuth)
})

// Mettre à jour l'authentification
const updateAuth = () => {
  token.value = localStorage.getItem('token')
  user.value = localStorage.getItem('user')
  console.log('🔄 Header updated - isAuth:', isAuthenticated.value, 'role:', userRole.value)
}

const getDashboardLink = () => {
  const role = userRole.value?.toLowerCase() || 'client'
  
  switch(role) {
    case 'admin':
      return '/admin/dashboard'
    case 'vendor':
      return '/vendor/dashboard'
    case 'client':
    default:
      return '/client/dashboard'
  }
}

const getDashboardLabel = () => {
  const role = userRole.value?.toLowerCase() || 'client'
  
  switch(role) {
    case 'admin':
      return 'Admin Dashboard'
    case 'vendor':
      return 'Vendor Dashboard'
    case 'client':
    default:
      return 'Mon Espace'
  }
}

const logout = async () => {
  try {
    await apiClient.post('/auth/logout')
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error)
  } finally {
    // Nettoyer le localStorage
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    
    // Mettre à jour l'état
    updateAuth()
    showDropdown.value = false
    
    // Dispatcher un événement pour notifier les autres composants
    window.dispatchEvent(new Event('authChange'))
    
    // Rediriger vers la page d'accueil
    router.push('/')
  }
}
</script>
