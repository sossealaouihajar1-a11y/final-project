<template>
  <div class="min-h-screen bg-[#f6f3ee] text-[#2a2a28] font-serif">

    <!-- Hero -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Administration
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Gestion des Produits
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Suivi, contrôle et gestion des produits de la plateforme
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div
          v-for="stat in [
            { label: 'Total produits', value: statistics.total_products },
            { label: 'Produits actifs', value: statistics.active_products },
            { label: 'Stock total', value: statistics.total_stock },
            { label: 'Catégories', value: (statistics.categories || []).length }
          ]"
          :key="stat.label"
          class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center"
        >
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">
            {{ stat.label }}
          </p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">
            {{ stat.value || 0 }}
          </p>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            placeholder="Recherche par titre ou description"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b] col-span-2"
          />
          <select v-model="filterCategory" @change="loadProducts"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Toutes les catégories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
          <select v-model="filterStatus" @change="loadProducts"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Tous les statuts</option>
            <option value="active">Actifs</option>
            <option value="inactive">Inactifs</option>
          </select>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-[#f1ede6]">
            <tr class="text-left text-xs uppercase tracking-wider text-[#6b7b4b]">
              <th class="px-6 py-4">Produit</th>
              <th class="px-6 py-4">Catégorie</th>
              <th class="px-6 py-4">Prix</th>
              <th class="px-6 py-4">Stock</th>
              <th class="px-6 py-4">État</th>
              <th class="px-6 py-4">Statut</th>
              <th class="px-6 py-4">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="product in products"
              :key="product.id"
              class="border-t border-[#e4dccf] hover:bg-[#f4f1eb]"
            >
              <td class="px-6 py-4 font-medium text-[#4a3728]">
                <div class="flex items-center space-x-3">
                  <img v-if="product.image_url" :src="product.image_url" alt="image" class="h-12 w-12 rounded-lg object-cover"/>
                  <span>{{ product.title }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-[#5a564f]">{{ product.category }}</td>
              <td class="px-6 py-4 font-semibold text-[#4a3728]">{{ product.price }} MAD</td>
              <td class="px-6 py-4 text-[#5a564f]">{{ product.stock }}</td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs bg-[#e9e4d7] text-[#4a3728]">
                  {{ getConditionLabel(product.condition) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs"
                  :class="product.status === 'active' ? 'bg-[#d4e4c8] text-[#4a3728]' : 'bg-[#f5e4e4] text-[#4a3728]'">
                  {{ product.status === 'active' ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4 space-x-2">
                <button @click="editProduct(product)" class="text-[#6b7b4b] hover:underline">Éditer</button>
                <button @click="toggleActive(product)" class="text-[#4a3728] hover:underline">
                  {{ product.status === 'active' ? 'Désactiver' : 'Activer' }}
                </button>
                <button @click="confirmDelete(product)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="products.length === 0" class="p-10 text-center text-gray-500">
          Aucun produit trouvé
        </div>
      </div>

    </div>
  </div>
</template>



<script setup>
import { ref, onMounted } from 'vue'
import Header from '@/components/Header.vue'
import { useNotification } from '@/composables/useNotification'
import productManagementService from '@/services/productManagementService'

const { showError, showSuccess } = useNotification()

const products = ref([])
const loading = ref(false)
const statistics = ref({})
const categories = ref([])
const searchQuery = ref('')
const filterCategory = ref('')
const filterStatus = ref('')
const editingProduct = ref(null)
const showNewProductModal = ref(false)
const confirmModal = ref(null)
const pagination = ref(null)
let searchTimeout = null

const productForm = ref({
  title: '',
  description: '',
  category: '',
  condition: 'neuf',
  price: 0,
  promotion: 0,
  stock: 0,
  image: null,
})

const imagePreview = ref(null)

const loadProducts = async (page = 1) => {
  loading.value = true
  try {
    const response = await productManagementService.getAllProducts({
      page,
      per_page: 15,
      category: filterCategory.value || undefined,
      status: filterStatus.value || undefined,
      search: searchQuery.value || undefined,
    })
    products.value = response.data.data || []
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
    }
  } catch (error) {
    console.error('Erreur chargement produits:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await productManagementService.getProductStatistics()
    statistics.value = response.data
  } catch (error) {
    console.error('Erreur chargement statistiques:', error)
  }
}

const loadCategories = async () => {
  try {
    const response = await productManagementService.getCategories()
    categories.value = response.data || []
  } catch (error) {
    console.error('Erreur chargement catégories:', error)
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadProducts(1)
  }, 500)
}

const editProduct = (product) => {
  editingProduct.value = product
  productForm.value = { ...product, image: null }
  imagePreview.value = null
}

const closeProductModal = () => {
  editingProduct.value = null
  showNewProductModal.value = false
  resetProductForm()
}

const resetProductForm = () => {
  productForm.value = {
    title: '',
    description: '',
    category: '',
    condition: 'neuf',
    price: 0,
    promotion: 0,
    stock: 0,
    image: null,
  }
  imagePreview.value = null
}

const saveProduct = async () => {
  try {
    if (editingProduct.value) {
      await productManagementService.updateProduct(editingProduct.value.id, productForm.value)
    } else {
      await productManagementService.createProduct(productForm.value)
    }
    closeProductModal()
    loadProducts()
    loadStatistics()
  } catch (error) {
    console.error('Erreur enregistrement produit:', error)
    console.error('Response data:', error.response?.data)
    console.error('Validation errors:', error.response?.data?.errors)
    
    if (error.response?.data?.errors) {
      const errorMessages = Object.entries(error.response.data.errors)
        .map(([field, messages]) => `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`)
        .join('\n')
      console.error('Full validation errors:', error.response.data.errors)
      showError('Erreurs de validation:\n' + errorMessages, 'Erreur de validation')
    } else {
      showError(error.response?.data?.message || error.message, 'Erreur')
    }
  }
}

const toggleActive = async (product) => {
  try {
    await productManagementService.toggleProductActive(product.id)
    loadProducts()
    loadStatistics()
  } catch (error) {
    console.error('Erreur changement statut:', error)
  }
}

const confirmDelete = (product) => {
  confirmModal.value = {
    title: 'Supprimer ce produit',
    message: `Êtes-vous sûr de vouloir supprimer "${product.title}"? Cette action est irréversible.`,
    action: () => deleteProduct(product.id),
  }
}

const deleteProduct = async (productId) => {
  try {
    await productManagementService.deleteProduct(productId)
    confirmModal.value = null
    loadProducts()
    loadStatistics()
  } catch (error) {
    console.error('Erreur suppression:', error)
  }
}

const getConditionLabel = (condition) => {
  const labels = {
    neuf: 'Neuf',
    excellent: 'Excellent',
    tres_bon: 'Très bon',
    bon: 'Bon',
    acceptable: 'Acceptable',
    a_restaurer: 'À restaurer',
  }
  return labels[condition] || condition
}

const handleImageSelect = (event) => {
  const file = event.target.files[0]
  if (file) {
    productForm.value.image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}
const formatPrice = (price) => {
  if (!price) return '0 MAD'
  return new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(price) + ' MAD'
}

onMounted(() => {
  loadProducts()
  loadStatistics()
  loadCategories()
})
</script>