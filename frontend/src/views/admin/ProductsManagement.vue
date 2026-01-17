<template>
  <div class="min-h-screen bg-white">
    <Header />

    <!-- Hero Section -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center">
          <p class="text-sm font-semibold uppercase tracking-wider text-[#8b1c3d] mb-2">Administration</p>
          <h1 class="text-4xl md:text-5xl font-serif font-bold text-gray-900 mb-4">Gestion des Produits</h1>
          <p class="text-lg text-gray-600">Gérez tous les produits de la plateforme</p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Total Produits</div>
          <div class="text-3xl font-bold text-gray-900 mt-2">{{ statistics.total_products }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Produits Actifs</div>
          <div class="text-3xl font-bold text-green-600 mt-2">{{ statistics.active_products }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Stock Total</div>
          <div class="text-3xl font-bold text-blue-600 mt-2">{{ statistics.total_stock }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Catégories</div>
          <div class="text-3xl font-bold text-purple-600 mt-2">{{ (statistics.categories || []).length }}</div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Titre ou description..."
              @input="handleSearch"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
            <select
              v-model="filterCategory"
              @change="loadProducts"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            >
              <option value="">Toutes les catégories</option>
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
            <select
              v-model="filterStatus"
              @change="loadProducts"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            >
              <option value="">Tous</option>
              <option value="active">Actifs</option>
              <option value="inactive">Inactifs</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadProducts"
              class="w-full px-4 py-2 bg-[#8b1c3d] text-white rounded-lg hover:bg-[#5a4a3a] transition"
            >
              Charger
            </button>
          </div>
        </div>
      </div>

      <!-- Products Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div v-if="loading" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#8b1c3d]"></div>
          <p class="mt-2 text-gray-600">Chargement...</p>
        </div>

        <table v-else class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">État</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <img
                    v-if="product.image_url"
                    :src="product.image_url"
                    :alt="product.title"
                    class="h-10 w-10 rounded object-cover mr-3"
                  />
                  <div class="text-sm font-medium text-gray-900">{{ product.title }}</div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.category }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ product.price }}€</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ product.stock }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                  {{ getConditionLabel(product.condition) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-green-100 text-green-800': product.status === 'active',
                    'bg-gray-100 text-gray-800': product.status !== 'active',
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ product.status === 'active' ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button
                  @click="editProduct(product)"
                  class="text-[#8b1c3d] hover:text-[#5a4a3a] font-medium"
                >
                  Éditer
                </button>
                <button
                  @click="toggleActive(product)"
                  :class="product.status === 'active' ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900'"
                  class="font-medium"
                >
                  {{ product.status === 'active' ? 'Désactiver' : 'Activer' }}
                </button>
                <button
                  @click="confirmDelete(product)"
                  class="text-red-600 hover:text-red-900 font-medium"
                >
                  Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && pagination" class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
          Page {{ pagination.current_page }} de {{ pagination.last_page }} ({{ pagination.total }} produits)
        </div>
        <div class="space-x-2">
          <button
            v-if="pagination.current_page > 1"
            @click="loadProducts(pagination.current_page - 1)"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Précédent
          </button>
          <button
            v-if="pagination.current_page < pagination.last_page"
            @click="loadProducts(pagination.current_page + 1)"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Suivant
          </button>
        </div>
      </div>
    </div>

    <!-- Edit/Create Modal -->
    <div
      v-if="editingProduct || showNewProductModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4 overflow-y-auto"
    >
      <div class="bg-white rounded-lg max-w-lg w-full p-8 my-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">
          {{ editingProduct ? 'Éditer le produit' : 'Nouveau produit' }}
        </h2>
        <div class="space-y-4 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
            <input
              v-model="productForm.title"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea
              v-model="productForm.description"
              rows="3"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            ></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Catégorie</label>
              <input
                v-model="productForm.category"
                type="text"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">État</label>
              <select
                v-model="productForm.condition"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              >
                <option value="neuf">Neuf</option>
                <option value="excellent">Excellent</option>
                <option value="tres_bon">Très bon</option>
                <option value="bon">Bon</option>
                <option value="acceptable">Acceptable</option>
                <option value="a_restaurer">À restaurer</option>
              </select>
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prix (€)</label>
              <input
                v-model.number="productForm.price"
                type="number"
                step="0.01"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Promotion (%)</label>
              <input
                v-model.number="productForm.promotion"
                type="number"
                min="0"
                max="100"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
              />
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Stock</label>
            <input
              v-model.number="productForm.stock"
              type="number"
              min="0"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#8b1c3d] focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Image du Produit</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-[#8b1c3d] transition cursor-pointer" @click="$refs.imageInput.click()">
              <div v-if="imagePreview" class="mb-4">
                <img :src="imagePreview" alt="Preview" class="h-40 w-40 object-cover mx-auto rounded" />
              </div>
              <div v-else-if="editingProduct && editingProduct.image_url" class="mb-4">
                <img :src="editingProduct.image_url" alt="Current" class="h-40 w-40 object-cover mx-auto rounded" />
              </div>
              <svg v-else class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-18-8l-4 4m0 0l4 4m-4-4h20" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
              <p class="mt-2 text-sm text-gray-600">Cliquez pour importer une image</p>
              <p class="text-xs text-gray-500">PNG, JPG, GIF jusqu'à 5MB</p>
            </div>
            <input
              ref="imageInput"
              type="file"
              accept="image/jpeg,image/png,image/jpg,image/gif"
              @change="handleImageSelect"
              class="hidden"
            />
          </div>
        </div>
        <div class="flex gap-4">
          <button
            @click="saveProduct"
            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >
            {{ editingProduct ? 'Mettre à jour' : 'Créer' }}
          </button>
          <button
            @click="closeProductModal"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div
      v-if="confirmModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg max-w-sm w-full p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ confirmModal.title }}</h2>
        <p class="text-gray-600 mb-6">{{ confirmModal.message }}</p>
        <div class="flex gap-4">
          <button
            @click="confirmModal.action"
            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
          >
            Confirmer
          </button>
          <button
            @click="confirmModal = null"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import Header from '@/components/Header.vue'
import productManagementService from '@/services/productManagementService'

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
    
    // Show detailed error message
    if (error.response?.data?.errors) {
      const errorMessages = Object.entries(error.response.data.errors)
        .map(([field, messages]) => `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`)
        .join('\n')
      console.error('Full validation errors:', error.response.data.errors)
      alert('Erreurs de validation:\n' + errorMessages)
    } else {
      alert('Erreur: ' + (error.response?.data?.message || error.message))
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

onMounted(() => {
  loadProducts()
  loadStatistics()
  loadCategories()
})
</script>
