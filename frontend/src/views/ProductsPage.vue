<template>
  <div class="bg-white min-h-screen flex flex-col">
    
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Shop
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Vintage Collection
        </h1>
        <p class="text-gray-600 max-w-2xl">
          Discover timeless vintage pieces curated with care.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">
      
      <div class="flex flex-col lg:flex-row gap-12">
        
        <!-- ================= SIDEBAR FILTRES ================= -->
        <aside class="lg:w-64 flex-shrink-0">
          <div class="sticky top-6 space-y-6">
            
            <!-- Titre filtres -->
            <div class="flex items-center justify-between">
              <h2 class="font-serif text-2xl text-[#5a4a3a]">Filters</h2>
              <button 
                v-if="hasActiveFilters"
                @click="resetFilters"
                class="text-xs text-[#8b7355] hover:text-[#5a4a3a] underline transition-colors"
              >
                Clear all
              </button>
            </div>

            <div class="h-px bg-gradient-to-r from-[#d4c5b0] via-[#d4c5b0] to-transparent"></div>

            <!-- Recherche -->
            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7355] mb-3">
                Search Products
              </label>
              <div class="relative">
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Type to search..."
                  class="w-full px-4 py-3 pl-11 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] placeholder-[#a39582] outline-none transition-all duration-200 focus:shadow-md text-sm"
                  @input="debouncedSearch"
                />
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </div>
            </div>

            <!-- Catégories -->
            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7355] mb-3">
                Category
              </label>
              <div class="relative">
                <select
                  v-model="filters.category"
                  @change="applyFilters"
                  class="w-full px-4 py-3 pr-10 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] outline-none transition-all duration-200 focus:shadow-md appearance-none cursor-pointer text-sm"
                >
                  <option value="">All categories</option>
                  <option
                    v-for="cat in categories"
                    :key="cat"
                    :value="cat"
                  >
                    {{ cat }}
                  </option>
                </select>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b7355] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Condition -->
            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7355] mb-3">
                Condition
              </label>
              <div class="relative">
                <select
                  v-model="filters.condition"
                  @change="applyFilters"
                  class="w-full px-4 py-3 pr-10 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] outline-none transition-all duration-200 focus:shadow-md appearance-none cursor-pointer text-sm"
                >
                  <option value="">All conditions</option>
                  <option
                    v-for="cond in conditions"
                    :key="cond"
                    :value="cond"
                  >
                    {{ cond }}
                  </option>
                </select>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b7355] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Prix -->
            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7355] mb-3">
                Price Range
              </label>
              <div class="space-y-3">
                <div class="relative">
                  <input
                    v-model.number="filters.min_price"
                    type="number"
                    placeholder="Min price"
                    class="w-full px-4 py-3 pl-11 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] placeholder-[#a39582] outline-none transition-all duration-200 focus:shadow-md text-sm"
                    @change="applyFilters"
                  />
                  <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <div class="relative">
                  <input
                    v-model.number="filters.max_price"
                    type="number"
                    placeholder="Max price"
                    class="w-full px-4 py-3 pl-11 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] placeholder-[#a39582] outline-none transition-all duration-200 focus:shadow-md text-sm"
                    @change="applyFilters"
                  />
                  <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
            </div>

            <div class="h-px bg-gradient-to-r from-[#d4c5b0] via-[#d4c5b0] to-transparent"></div>

            <!-- Promotion -->
            <div class="bg-gradient-to-br from-[#faf9f6] to-[#f5f3ed] p-5 rounded-sm">
              <label class="flex items-start cursor-pointer group">
                <div class="relative flex-shrink-0 mt-0.5">
                  <input
                    type="checkbox"
                    v-model="filters.with_promotion"
                    @change="applyFilters"
                    class="sr-only peer"
                  />
                  <div class="w-11 h-6 bg-[#d4c5b0] peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#8b1c3d] peer-focus:ring-offset-2 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-[#d4c5b0] after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#8b1c3d] shadow-inner"></div>
                </div>
                <div class="ml-4 flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5 text-[#8b1c3d]" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                      <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-semibold text-[#5a4a3a] group-hover:text-[#8b1c3d] transition-colors">
                      On Sale Only
                    </span>
                  </div>
                  <p class="text-xs text-[#8b7355]">
                    Show only discounted items
                  </p>
                </div>
              </label>
            </div>

            <div class="h-px bg-gradient-to-r from-[#d4c5b0] via-[#d4c5b0] to-transparent"></div>

            <!-- Tri -->
            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-[#8b7355] mb-3">
                Sort By
              </label>
              <div class="relative">
                <select
                  v-model="filters.sort_by"
                  @change="applyFilters"
                  class="w-full px-4 py-3 pr-10 bg-[#faf9f6] focus:bg-white text-[#5a4a3a] outline-none transition-all duration-200 focus:shadow-md appearance-none cursor-pointer text-sm"
                >
                  <option value="created_at">Newest First</option>
                  <option value="price_asc">Price: Low to High</option>
                  <option value="price_desc">Price: High to Low</option>
                  <option value="title">Name: A-Z</option>
                </select>
                <svg class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-[#8b7355] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

          </div>
        </aside>

        <!-- ================= PRODUITS ================= -->
        <section class="flex-1">

          <!-- Stats -->
          <div class="mb-8 pb-6 border-b border-[#d4c5b0] flex items-center justify-between">
            <p class="text-sm text-[#8b7355]">
              {{ totalProducts }} product{{ totalProducts !== 1 ? 's' : '' }} found
            </p>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="py-20 text-center">
            <svg class="animate-spin h-10 w-10 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"/>
            </svg>
            <p class="mt-4 text-gray-500">Loading products...</p>
          </div>

          <!-- Grille vintage -->
          <div
            v-else-if="products.length"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-16"
          >
            <article
              v-for="product in products"
              :key="product.id"
              class="group relative"
            >
              <!-- Badge promotion -->
              <div
                v-if="product.promotion > 0"
                class="absolute top-3 left-3 z-10 bg-[#8b1c3d] text-white text-xs font-semibold px-3 py-1.5 uppercase tracking-wide shadow-lg flex items-center gap-1.5 rounded-sm"
              >
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                  <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                -{{ product.promotion }}%
              </div>

              <!-- Image -->
              <div
                class="aspect-[3/4] bg-gray-100 overflow-hidden cursor-pointer relative"
                @click="goToProduct(product)"
              >
                <img
                  :src="product.image_url"
                  :alt="product.title"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                
                <!-- Overlay hover avec boutons -->
                <div 
                  class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-end justify-center pb-6 opacity-0 group-hover:opacity-100"
                >
                  <div v-if="authStore.isClient" class="flex gap-3 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    <!-- Bouton Add to Cart -->
                    <button
                      @click.stop="addToCart(product)"
                      class="bg-[#faf9f6] text-[#5a4a3a] px-6 py-2.5 text-sm font-medium hover:bg-[#5a4a3a] hover:text-[#faf9f6] transition-colors duration-300 flex items-center gap-2 border border-[#d4c5b0]"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                      </svg>
                      Add to Cart
                    </button>
                    
                    <!-- Bouton Favoris -->
                    <button
                      @click.stop="toggleFavorite(product)"
                      class="bg-[#faf9f6] text-[#5a4a3a] w-10 h-10 flex items-center justify-center hover:bg-[#5a4a3a] hover:text-[#faf9f6] transition-colors duration-300 border border-[#d4c5b0]"
                      :class="{ 'bg-[#5a4a3a] text-[#faf9f6]': isFavorite(product.id) }"
                    >
                      <svg class="w-5 h-5" :fill="isFavorite(product.id) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                      </svg>
                    </button>
                  </div>
                  <div v-else class="flex items-center justify-center">
                    <router-link
                      to="/login"
                      class="bg-[#faf9f6] text-[#5a4a3a] px-6 py-2.5 text-sm font-medium hover:bg-[#5a4a3a] hover:text-[#faf9f6] transition-colors duration-300 border border-[#d4c5b0]"
                    >
                      Login to Shop
                    </router-link>
                  </div>
                </div>
              </div>

              <!-- Infos -->
              <div class="mt-4">
                <h3
                  class="font-serif text-base text-gray-900 mb-1 cursor-pointer hover:underline"
                  @click="goToProduct(product)"
                >
                  {{ product.title }}
                </h3>

                <div class="text-sm text-gray-700 flex items-center gap-2">
                  <span
                    v-if="product.promotion > 0"
                    class="line-through text-gray-400"
                  >
                    {{ product.price }}€
                  </span>
                  <span class="font-medium" :class="{ 'text-[#8b1c3d] font-semibold': product.promotion > 0 }">
                    {{ product.final_price }}€
                  </span>
                </div>
              </div>
            </article>
          </div>

          <!-- Aucun produit -->
          <div v-else class="text-center py-20">
            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 text-lg">No products found</p>
            <p class="text-gray-400 text-sm mt-2">Try adjusting your filters</p>
          </div>

          <!-- Pagination -->
          <div v-if="pagination && pagination.last_page > 1" class="mt-16 flex justify-center">
            <nav class="flex gap-2">
              <button
                v-for="page in visiblePages"
                :key="page"
                @click="goToPage(page)"
                class="px-4 py-2 border text-sm transition-colors"
                :class="page === pagination.current_page 
                  ? 'bg-[#5a4a3a] text-[#faf9f6] border-[#5a4a3a]' 
                  : 'bg-[#faf9f6] text-[#5a4a3a] border-[#d4c5b0] hover:border-[#8b7355] hover:bg-[#f5f3ed]'"
              >
                {{ page }}
              </button>
            </nav>
          </div>

        </section>

      </div>

    </main>

    <!-- Footer -->
    <Footer />

  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import productService from '@/services/productService'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

const router = useRouter()
const authStore = useAuthStore()

// Data
const products = ref([])
const categories = ref([])
const loading = ref(false)
const pagination = ref(null)
const favorites = ref(JSON.parse(localStorage.getItem('favorites') || '[]'))

const conditions = ['Excellent', 'Very Good', 'Good', 'Fair']

// Filtres
const filters = ref({
  search: '',
  category: '',
  condition: '',
  min_price: null,
  max_price: null,
  with_promotion: false,
  sort_by: 'created_at',
  page: 1
})

// Computed
const totalProducts = computed(() => pagination.value?.total || 0)

const hasActiveFilters = computed(() => {
  return filters.value.search 
    || filters.value.category 
    || filters.value.condition 
    || filters.value.min_price 
    || filters.value.max_price 
    || filters.value.with_promotion
})

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

// Navigation produit
const encodeTitle = (title) =>
  encodeURIComponent(
    title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-')
  )

const goToProduct = (product) => {
  router.push(`/products/${encodeTitle(product.title)}`)
}

// Chargement produits
const loadProducts = async () => {
  loading.value = true
  try {
    const params = {
      per_page: 12
    }
    
    // Ajouter les filtres un par un s'ils sont valides
    if (filters.value.search && filters.value.search.trim() !== '') {
      params.search = filters.value.search.trim()
    }
    
    if (filters.value.category) {
      params.category = filters.value.category
    }
    
    if (filters.value.condition) {
      params.condition = filters.value.condition
    }
    
    // Prix min et max - envoyer indépendamment si présents
    if (filters.value.min_price !== null && filters.value.min_price !== '') {
      params.min_price = filters.value.min_price
    }
    
    if (filters.value.max_price !== null && filters.value.max_price !== '') {
      params.max_price = filters.value.max_price
    }
    
    if (filters.value.with_promotion) {
      params.with_promotion = true
    }
    
    if (filters.value.sort_by) {
      params.sort_by = filters.value.sort_by
    }
    
    if (filters.value.page) {
      params.page = filters.value.page
    }
    
    console.log('🔍 Params envoyés à l\'API:', params)
    
    const res = await productService.getAllProducts(params)
    
    // Le service retourne déjà response.data
    products.value = res.data || res || []
    
    // Gestion de la pagination
    if (res.current_page) {
      pagination.value = {
        current_page: res.current_page,
        last_page: res.last_page,
        total: res.total
      }
    }
  } catch (e) {
    console.error('Error loading products:', e)
  } finally {
    loading.value = false
  }
}

// Chargement catégories
const loadCategories = async () => {
  try {
    const res = await productService.getCategories()
    // Le service retourne déjà response.data
    categories.value = res || []
  } catch (e) {
    console.error('Error loading categories:', e)
  }
}

// Appliquer filtres
const applyFilters = () => {
  filters.value.page = 1
  loadProducts()
}

// Reset filtres
const resetFilters = () => {
  filters.value = {
    search: '',
    category: '',
    condition: '',
    min_price: null,
    max_price: null,
    with_promotion: false,
    sort_by: 'created_at',
    page: 1
  }
  loadProducts()
}

// Debounce search
let searchTimeout
const debouncedSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

// Pagination
const goToPage = (page) => {
  if (page === '...') return
  filters.value.page = page
  loadProducts()
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Favoris
const isFavorite = (productId) => {
  return favorites.value.includes(productId)
}

const toggleFavorite = (product) => {
  const index = favorites.value.indexOf(product.id)
  if (index > -1) {
    favorites.value.splice(index, 1)
  } else {
    favorites.value.push(product.id)
  }
  localStorage.setItem('favorites', JSON.stringify(favorites.value))
}

// Add to cart (à adapter selon votre logique)
const addToCart = (product) => {
  // Exemple basique - à remplacer par votre logique de panier
  const cart = JSON.parse(localStorage.getItem('cart') || '[]')
  const existingItem = cart.find(item => item.id === product.id)
  
  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cart.push({
      id: product.id,
      title: product.title,
      price: product.final_price,
      image_url: product.image_url,
      quantity: 1
    })
  }
  
  localStorage.setItem('cart', JSON.stringify(cart))
  
  // Notification (optionnel - vous pouvez utiliser une lib comme vue-toastification)
  alert(`${product.title} added to cart!`)
}

// Lifecycle
onMounted(() => {
  loadProducts()
  loadCategories()
})
</script>

<style scoped>
/* Animations personnalisées si nécessaire */
</style>