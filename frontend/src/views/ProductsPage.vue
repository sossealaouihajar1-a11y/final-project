<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-6">
            <router-link to="/" class="text-2xl font-bold text-indigo-600">
              Vintage Shop
            </router-link>
            <router-link to="/products" class="text-gray-700 hover:text-indigo-600 font-medium">
              Produits
            </router-link>
          </div>
          <div class="flex items-center space-x-4">
            <button @click="toggleFavoritesPanel" class="relative text-gray-700 hover:text-red-500 transition">
              <div class="w-6 h-6 rounded-full border-2"
                   :class="favoritesCount > 0 ? 'border-red-500 bg-red-500' : 'border-gray-400'">
              </div>
              <span v-if="favoritesCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                {{ favoritesCount }}
              </span>
            </button>

            <router-link v-if="!authStore.isAuthenticated" to="/login" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 transition">
              Connexion
            </router-link>
            <router-link v-else-if="authStore.isAdmin" to="/admin/dashboard" class="text-gray-700 hover:text-indigo-600 font-medium">
              Dashboard Admin
            </router-link>
            <router-link v-else-if="authStore.isVendor" to="/vendor/dashboard" class="text-gray-700 hover:text-indigo-600 font-medium">
              Mon Espace Vendeur
            </router-link>
            <router-link v-else to="/client/dashboard" class="text-gray-700 hover:text-indigo-600 font-medium">
              Mon Compte
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Bannière -->
      <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl p-8 mb-8 text-white shadow-xl">
        <h1 class="text-4xl md:text-5xl font-bold mb-2">Découvrez Notre Collection Vintage</h1>
        <p class="text-lg md:text-xl opacity-90">Des pièces uniques et authentiques sélectionnées avec soin.</p>
      </div>

      <!-- Filtres et Recherche -->
      <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <!-- Recherche -->
          <div class="lg:col-span-2">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Rechercher un produit..."
              class="w-full pl-4 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
              @input="debouncedSearch"
            />
          </div>

          <!-- Catégorie -->
          <div>
            <select
              v-model="filters.category"
              @change="applyFilters"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
            >
              <option value="">Toutes catégories</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>

          <!-- Condition -->
          <div>
            <select
              v-model="filters.condition"
              @change="applyFilters"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
            >
              <option value="">Toutes conditions</option>
              <option value="neuf">Neuf</option>
              <option value="excellent">Excellent</option>
              <option value="tres_bon">Très bon état</option>
              <option value="bon">Bon état</option>
              <option value="acceptable">État correct</option>
              <option value="a_restaurer">À restaurer</option>
            </select>
          </div>

          <!-- Tri -->
          <div>
            <select
              v-model="filters.sort_by"
              @change="applyFilters"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
            >
              <option value="created_at">Plus récents</option>
              <option value="price_asc">Prix croissant</option>
              <option value="price_desc">Prix décroissant</option>
              <option value="title">Nom A-Z</option>
            </select>
          </div>
        </div>

        <!-- Filtres supplémentaires -->
        <div class="mt-4 flex flex-wrap gap-3">
          <button
            @click="togglePromotionFilter"
            :class="filters.with_promotion ? 'bg-red-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-full text-sm font-medium transition"
          >
            En promotion
          </button>
          <button
            @click="clearFilters"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full text-sm font-medium hover:bg-gray-200 transition"
          >
            Réinitialiser
          </button>
        </div>
      </div>

      <!-- Stats -->
      <div v-if="stats" class="mb-6 flex flex-wrap gap-4 text-sm text-gray-600 bg-white rounded-lg p-4 shadow">
        <div class="flex items-center space-x-2">
          <span class="font-medium">{{ stats.total }}</span>
          <span class="text-gray-500">produits</span>
        </div>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
          <span class="font-medium">{{ stats.categories }}</span>
          <span class="text-gray-500">catégories</span>
        </div>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
          <span class="font-medium">{{ stats.avg_price }}€</span>
          <span class="text-gray-500">prix moyen</span>
        </div>
        <span v-if="stats.with_promotion" class="text-gray-300">|</span>
        <div v-if="stats.with_promotion" class="flex items-center space-x-2">
          <span class="font-medium text-red-600">{{ stats.with_promotion }}</span>
          <span class="text-gray-500">en promotion</span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-indigo-600"></div>
        <p class="mt-4 text-gray-600 text-lg">Chargement des produits...</p>
      </div>

      <!-- Grille de Produits -->
      <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          @click="openProductModal(product)"
          class="bg-white rounded-xl shadow-md overflow-hidden cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-2xl group"
        >
          <!-- Image -->
          <div class="relative h-56 bg-gray-100 flex items-center justify-center overflow-hidden">
            <img
              v-if="product.image_url"
              :src="product.image_url"
              :alt="product.title"
              class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
            />
            <div v-else class="text-gray-400 text-sm">Aucune image</div>
            
            <!-- Badge Promotion -->
            <div v-if="product.promotion > 0" class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1.5 rounded-full text-sm font-bold shadow-lg animate-pulse">
              -{{ product.promotion }}%
            </div>

            <!-- Badge Stock -->
            <div v-if="product.stock > 0 && product.stock < 5" class="absolute top-3 left-3 bg-orange-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
              Plus que {{ product.stock }}
            </div>
            <div v-else-if="product.stock === 0" class="absolute top-3 left-3 bg-gray-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
              Épuisé
            </div>

            <!-- Bouton Favoris -->
            <button
              @click.stop="toggleFavorite(product.id)"
              class="absolute bottom-3 right-3 bg-white rounded-full p-2.5 shadow-lg hover:scale-110 transition transform"
            >
              <div class="w-5 h-5 rounded-full border-2"
                   :class="isFavorite(product.id) ? 'border-red-500 bg-red-500' : 'border-gray-400'">
              </div>
            </button>
          </div>

          <!-- Contenu -->
          <div class="p-5">
            <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-indigo-600 transition">
              {{ product.title }}
            </h3>
            
            <p class="text-sm text-gray-600 mb-3">
              {{ product.category }}
            </p>
            
            <!-- Prix -->
            <div class="flex items-center justify-between mb-3">
              <div>
                <div v-if="product.promotion > 0" class="text-sm text-gray-400 line-through">
                  {{ product.price }}€
                </div>
                <div class="text-2xl font-bold text-indigo-600">
                  {{ product.final_price }}€
                </div>
              </div>
            </div>

            <!-- Condition -->
            <div>
              <span :class="getConditionClass(product.condition)" class="px-3 py-1.5 text-xs font-semibold rounded-full inline-block">
                {{ getConditionLabel(product.condition) }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Aucun produit -->
      <div v-else class="bg-white rounded-xl shadow-lg p-16 text-center">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Aucun produit trouvé</h3>
        <p class="text-gray-600 mb-6">Essayez de modifier vos filtres ou votre recherche</p>
        <button @click="clearFilters" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition">
          Réinitialiser les filtres
        </button>
      </div>

      <!-- Pagination -->
      <div v-if="pagination && pagination.last_page > 1" class="mt-10 flex justify-center">
        <nav class="flex items-center space-x-2">
          <button
            @click="goToPage(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition font-medium"
          >
            ← Précédent
          </button>
          <div class="flex items-center space-x-1">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              :class="page === pagination.current_page ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50'"
              class="px-4 py-2 border-2 border-gray-300 rounded-lg transition font-medium"
            >
              {{ page }}
            </button>
          </div>
          <button
            @click="goToPage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 bg-white border-2 border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition font-medium"
          >
            Suivant →
          </button>
        </nav>
      </div>
    </main>

    <!-- Modal Détails Produit -->
    <transition name="modal">
      <div v-if="selectedProduct" @click="closeModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm">
        <div @click.stop class="bg-white rounded-2xl max-w-5xl w-full my-8 max-h-[90vh] overflow-y-auto shadow-2xl">
          <div class="relative">
            <button @click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl z-10 bg-white rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:shadow-xl transition">
              ✕
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2">
              <!-- Image -->
              <div class="relative h-96 md:h-full bg-gray-100 flex items-center justify-center">
                <img
                  v-if="selectedProduct.image_url"
                  :src="selectedProduct.image_url"
                  :alt="selectedProduct.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="text-gray-400 text-sm">Aucune image</div>
                
                <div v-if="selectedProduct.promotion > 0" class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-full text-base font-bold shadow-lg">
                  -{{ selectedProduct.promotion }}%
                </div>
              </div>

              <!-- Détails -->
              <div class="p-8 md:p-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ selectedProduct.title }}</h2>
                
                <div class="space-y-4 mb-6">
                  <div class="flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">Catégorie:</span>
                    <span class="font-semibold text-indigo-600">{{ selectedProduct.category }}</span>
                  </div>

                  <div class="flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">État:</span>
                    <span :class="getConditionClass(selectedProduct.condition)" class="px-3 py-1.5 text-sm font-semibold rounded-full">
                      {{ getConditionLabel(selectedProduct.condition) }}
                    </span>
                  </div>

                  <div class="flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">Stock:</span>
                    <span class="font-semibold" :class="selectedProduct.stock < 5 ? 'text-orange-600' : 'text-green-600'">
                      {{ selectedProduct.stock > 0 ? `${selectedProduct.stock} disponible(s)` : 'Épuisé' }}
                    </span>
                  </div>

                  <div class="flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">Vendeur:</span>
                    <span class="font-semibold">{{ selectedProduct.vendeur?.name || 'Vintage Shop' }}</span>
                  </div>
                </div>

                <!-- Prix -->
                <div class="mb-6 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
                  <div v-if="selectedProduct.promotion > 0" class="flex items-center space-x-3 mb-2">
                    <span class="text-2xl text-gray-400 line-through">{{ selectedProduct.price }}€</span>
                    <span class="px-3 py-1.5 bg-red-500 text-white text-sm font-bold rounded-full">
                      -{{ selectedProduct.promotion }}%
                    </span>
                  </div>
                  <div class="text-5xl font-bold text-indigo-600 mb-2">
                    {{ selectedProduct.final_price }}€
                  </div>
                  <div v-if="selectedProduct.promotion > 0" class="text-sm text-green-600 font-semibold">
                    Vous économisez {{ (selectedProduct.price - selectedProduct.final_price).toFixed(2) }}€
                  </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                  <h3 class="font-bold text-gray-900 mb-3 text-lg">Description du produit</h3>
                  <p class="text-gray-700 leading-relaxed">{{ selectedProduct.description }}</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                  <!-- <button
                    @click="contactVendor(selectedProduct)"
                    :disabled="selectedProduct.stock === 0"
                    class="flex-1 px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                  >
                    {{ selectedProduct.stock === 0 ? 'Rupture de stock' : 'Contacter le vendeur' }}
                  </button> -->
                  <button
                    @click="toggleFavorite(selectedProduct.id)"
                    :class="isFavorite(selectedProduct.id) ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-6 py-4 font-bold rounded-xl transition flex items-center justify-center space-x-2 shadow-lg"
                  >
                    {{ isFavorite(selectedProduct.id) ? 'Retiré' : 'Ajouter aux favoris' }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import productService from '@/services/productService'

const authStore = useAuthStore()
const products = ref([])
const categories = ref([])
const stats = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const selectedProduct = ref(null)
const favorites = ref(JSON.parse(localStorage.getItem('favorites') || '[]'))
const pagination = ref(null)

const filters = ref({
  category: '',
  condition: '',
  sort_by: 'created_at',
  with_promotion: false,
  page: 1
})

let searchTimeout = null

const favoritesCount = computed(() => favorites.value.length)

const visiblePages = computed(() => {
  if (!pagination.value) return []
  const current = pagination.value.current_page
  const last = pagination.value.last_page
  const delta = 2
  const range = []
  
  for (let i = Math.max(2, current - delta); i <= Math.min(last - 1, current + delta); i++) {
    range.push(i)
  }
  
  if (current - delta > 2) range.unshift('...')
  if (current + delta < last - 1) range.push('...')
  
  range.unshift(1)
  if (last > 1) range.push(last)
  
  return range.filter((v, i, a) => a.indexOf(v) === i)
})

const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    filters.value.page = 1
    loadProducts()
  }, 500)
}

const applyFilters = () => {
  filters.value.page = 1
  loadProducts()
}

const loadProducts = async () => {
  loading.value = true
  try {
    const params = {
      ...filters.value,
      search: searchQuery.value || undefined
    }
    
    const response = await productService.getAllProducts(params)
    products.value = response.data
    pagination.value = {
      current_page: response.current_page,
      last_page: response.last_page,
      total: response.total
    }
  } catch (error) {
    console.error('Erreur chargement produits:', error)
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    categories.value = await productService.getCategories()
  } catch (error) {
    console.error('Erreur chargement catégories:', error)
  }
}

const loadStats = async () => {
  try {
    stats.value = await productService.getStats()
  } catch (error) {
    console.error('Erreur chargement stats:', error)
  }
}

const goToPage = (page) => {
  if (page === '...' || page < 1 || page > pagination.value.last_page) return
  filters.value.page = page
  loadProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const togglePromotionFilter = () => {
  filters.value.with_promotion = !filters.value.with_promotion
  applyFilters()
}

const clearFilters = () => {
  searchQuery.value = ''
  filters.value = {
    category: '',
    condition: '',
    sort_by: 'created_at',
    with_promotion: false,
    page: 1
  }
  loadProducts()
}

const getConditionLabel = (condition) => {
  const labels = {
    neuf: 'Neuf',
    excellent: 'Excellent',
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

const openProductModal = (product) => {
  selectedProduct.value = product
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  selectedProduct.value = null
  document.body.style.overflow = 'auto'
}

const isFavorite = (productId) => favorites.value.includes(productId)

const toggleFavorite = (productId) => {
  if (isFavorite(productId)) favorites.value = favorites.value.filter(id => id !== productId)
  else favorites.value.push(productId)
  localStorage.setItem('favorites', JSON.stringify(favorites.value))
}

const toggleFavoritesPanel = () => {
  alert(`Vous avez ${favoritesCount.value} produit(s) en favoris`)
}

const contactVendor = (product) => {
  if (product.stock === 0) {
    alert('Ce produit est en rupture de stock')
    return
  }
  alert(`Contactez le vendeur pour acheter : ${product.title}`)
}

onMounted(() => {
  loadProducts()
  loadCategories()
  loadStats()
})
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}
</style>
