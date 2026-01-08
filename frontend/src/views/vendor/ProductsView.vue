<template>
  <div class="space-y-6">
    <!-- Header with Add Button -->
    <div class="flex justify-between items-center">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">Mes Produits</h3>
        <p class="text-sm text-gray-600 mt-1">Gérez vos produits vintage</p>
      </div>
      <button @click="showAddModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        + Ajouter un produit
      </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input v-model="filters.search" type="text" placeholder="Rechercher..." class="border rounded-lg px-3 py-2" />
        <select v-model="filters.category" class="border rounded-lg px-3 py-2">
          <option value="">Toutes les catégories</option>
          <option value="furniture">Mobilier</option>
          <option value="clothing">Vêtements</option>
          <option value="jewelry">Bijoux</option>
        </select>
        <select v-model="filters.status" class="border rounded-lg px-3 py-2">
          <option value="">Tous les statuts</option>
          <option value="active">Actif</option>
          <option value="inactive">Inactif</option>
          <option value="sold_out">Épuisé</option>
        </select>
        <button @click="applyFilters" class="bg-gray-600 text-white rounded-lg hover:bg-gray-700">
          Filtrer
        </button>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div v-if="loading" class="p-8 text-center text-gray-500">
        Chargement...
      </div>
      <div v-else-if="products.length === 0" class="p-8 text-center text-gray-500">
        Aucun produit trouvé
      </div>
      <table v-else class="w-full">
        <thead class="bg-gray-50 border-b">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center mr-3 overflow-hidden flex-shrink-0">
                  <img 
                    v-if="product.image_url" 
                    :src="product.image_url" 
                    :alt="product.title"
                    class="w-full h-full object-cover"
                  />
                  <span v-else class="text-lg">📦</span>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-900">{{ product.title }}</p>
                  <p class="text-xs text-gray-500">{{ product.description.substring(0, 30) }}...</p>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
              {{ product.category }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              ${{ product.price }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStockClass(product.stock)" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ product.stock }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="getStatusClass(product.status)" class="px-3 py-1 rounded-full text-xs font-semibold">
                {{ product.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
              <button @click="editProduct(product)" class="text-blue-600 hover:text-blue-800">
                ✏️ Modifier
              </button>
              <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-800">
                🗑️ Supprimer
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">
          {{ editingProduct ? 'Modifier le produit' : 'Ajouter un produit' }}
        </h3>
        <form @submit.prevent="saveProduct" class="space-y-4">
          <input v-model="form.title" type="text" placeholder="Titre" class="w-full border rounded-lg px-3 py-2" required />
          <textarea v-model="form.description" placeholder="Description" class="w-full border rounded-lg px-3 py-2 h-20" required></textarea>
          <select v-model="form.category" class="w-full border rounded-lg px-3 py-2" required>
            <option value="">Sélectionnez une catégorie</option>
            <option value="furniture">Mobilier</option>
            <option value="clothing">Vêtements</option>
            <option value="jewelry">Bijoux</option>
          </select>
          <input v-model.number="form.price" type="number" placeholder="Prix" class="w-full border rounded-lg px-3 py-2" required />
          <input v-model.number="form.stock" type="number" placeholder="Stock" class="w-full border rounded-lg px-3 py-2" required />
          <select v-model="form.condition" class="w-full border rounded-lg px-3 py-2" required>
            <option value="">État du produit</option>
            <option value="neuf">Neuf</option>
            <option value="excellent">Excellent</option>
            <option value="tres_bon">Très bon</option>
            <option value="bon">Bon</option>
            <option value="acceptable">Acceptable</option>
            <option value="a_restaurer">À restaurer</option>
          </select>
          <input v-model.number="form.promotion" type="number" placeholder="Promotion %" class="w-full border rounded-lg px-3 py-2" />
          
          <!-- Image Upload Section -->
          <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Image du produit</label>
            <div v-if="form.image_preview" class="mb-3">
              <img :src="form.image_preview" alt="Preview" class="w-full h-40 object-cover rounded-lg" />
              <button type="button" @click="clearImage" class="mt-2 text-sm text-red-600 hover:text-red-800">
                Supprimer l'image
              </button>
            </div>
            <input 
              type="file" 
              accept="image/*" 
              @change="onImageSelected"
              class="w-full border rounded-lg px-3 py-2"
            />
            <p class="text-xs text-gray-500">Formats acceptés: JPG, PNG, GIF. Max 5MB</p>
          </div>

          <div class="flex justify-end gap-3 mt-6">
            <button type="button" @click="showAddModal = false" class="px-4 py-2 text-gray-700 border rounded-lg hover:bg-gray-50">
              Annuler
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
              {{ editingProduct ? 'Mettre à jour' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import vendorProductService from '@/services/vendorProductService'

const authStore = useAuthStore()

const products = ref([])
const loading = ref(false)
const showAddModal = ref(false)
const editingProduct = ref(null)

const filters = ref({
  search: '',
  category: '',
  status: ''
})

const form = ref({
  title: '',
  description: '',
  category: '',
  price: 0,
  stock: 0,
  condition: 'bon',
  promotion: 0,
  image: null,
  image_preview: ''
})

onMounted(() => {
  loadProducts()
})

const loadProducts = async () => {
  loading.value = true
  try {
    const response = await vendorProductService.getAllProducts({
      search: filters.value.search,
      category: filters.value.category,
      status: filters.value.status
    })
    products.value = response.data.data || response.data
  } catch (error) {
    console.error('Error loading products:', error)
  } finally {
    loading.value = false
  }
}

const applyFilters = () => {
  loadProducts()
}

const editProduct = (product) => {
  editingProduct.value = product
  form.value = { 
    ...product,
    image: null,
    image_preview: product.image_url || ''
  }
  showAddModal.value = true
}

const onImageSelected = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
      alert('Le fichier est trop volumineux. Max 5MB.')
      return
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
      alert('Veuillez sélectionner une image valide.')
      return
    }
    
    form.value.image = file
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      form.value.image_preview = e.target?.result || ''
    }
    reader.readAsDataURL(file)
  }
}

const clearImage = () => {
  form.value.image = null
  form.value.image_preview = ''
}

const saveProduct = async () => {
  try {
    const method = editingProduct.value ? 'update' : 'create'
    
    // Create FormData for file upload
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('description', form.value.description)
    formData.append('category', form.value.category)
    formData.append('price', form.value.price)
    formData.append('stock', form.value.stock)
    formData.append('condition', form.value.condition)
    formData.append('promotion', form.value.promotion)
    
    if (form.value.image instanceof File) {
      formData.append('image', form.value.image)
    }
    
    if (method === 'create') {
      await vendorProductService.createProduct(formData)
    } else {
      await vendorProductService.updateProduct(editingProduct.value.id, formData)
    }
    
    showAddModal.value = false
    editingProduct.value = null
    form.value = {
      title: '',
      description: '',
      category: '',
      price: 0,
      stock: 0,
      condition: 'bon',
      promotion: 0,
      image: null,
      image_preview: ''
    }
    await loadProducts()
  } catch (error) {
    console.error('Error saving product:', error)
  }
}

const deleteProduct = async (id) => {
  if (confirm('Êtes-vous sûr de vouloir supprimer ce produit?')) {
    try {
      await vendorProductService.deleteProduct(id)
      await loadProducts()
    } catch (error) {
      console.error('Error deleting product:', error)
    }
  }
}

const getStockClass = (stock) => {
  if (stock === 0) return 'bg-red-100 text-red-800'
  if (stock < 5) return 'bg-yellow-100 text-yellow-800'
  return 'bg-green-100 text-green-800'
}

const getStatusClass = (status) => {
  if (status === 'active') return 'bg-green-100 text-green-800'
  if (status === 'inactive') return 'bg-gray-100 text-gray-800'
  return 'bg-red-100 text-red-800'
}
</script>
