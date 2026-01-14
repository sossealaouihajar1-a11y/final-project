<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useCartStore } from '@/stores/cartStore'
import { useFavorites } from '@/composables/useFavorites'
import productService from '@/services/productService'

const router = useRouter()
const authStore = useAuthStore()
const cartStore = useCartStore()
const { isFavorite, isLoading: isTogglingFavorite, toggleFavorite, loadFavorites, getFavoriteCount } = useFavorites()

const products = ref([])
const categories = ref([])
const stats = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const pagination = ref(null)

const favoritesCount = computed(() => getFavoriteCount.value)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const filters = ref({
  category: '',
  condition: '',
  sort_by: 'created_at',
  with_promotion: false,
  page: 1
})

let searchTimeout = null

const cartCount = computed(() => cartStore.itemCount)

// Fonction pour encoder le titre pour l'URL
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

// Fonction pour naviguer vers le détail du produit
const goToProduct = (product) => {
  router.push(`/products/${encodeTitle(product.title)}`)
}

// ... reste du code identique ...

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

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
    tres_bon: 'Très bon',
    bon: 'Bon',
    acceptable: 'Acceptable',
    a_restaurer: 'À restaurer'
  }
  return labels[condition] || condition
}

const getConditionClass = (condition) => {
  const classes = {
    neuf: 'bg-green-100 text-green-800 border border-green-200',
    excellent: 'bg-blue-100 text-blue-800 border border-blue-200',
    tres_bon: 'bg-cyan-100 text-cyan-800 border border-cyan-200',
    bon: 'bg-yellow-100 text-yellow-800 border border-yellow-200',
    acceptable: 'bg-orange-100 text-orange-800 border border-orange-200',
    a_restaurer: 'bg-red-100 text-red-800 border border-red-200'
  }
  return classes[condition] || 'bg-gray-100 text-gray-800 border border-gray-200'
}

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

const toggleFavoriteFromGrid = async (product) => {
  try {
    const newStatus = await toggleFavorite(product.id)
    window.dispatchEvent(new CustomEvent('favorites-updated', { 
      detail: { count: getFavoriteCount.value } 
    }))
    showNotification(newStatus ? 'Ajouté aux favoris!' : 'Supprimé des favoris!')
  } catch (err) {
    console.error('Error toggling favorite:', err)
    showNotification('Erreur lors de la mise à jour du favori', 'error')
  }
}

const addToCart = (product) => {
  if (product.stock === 0) {
    showNotification('Ce produit est en rupture de stock', 'error')
    return
  }

  const result = cartStore.addItem(product)
  
  if (result.success) {
    showNotification('Produit ajouté au panier avec succès!')
  } else {
    showNotification(result.message, 'error')
  }
}

onMounted(async () => {
  loadProducts()
  loadCategories()
  loadStats()
  if (authStore.isClient) {
    await loadFavorites()
  }
})
</script>