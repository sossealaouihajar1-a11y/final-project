<template>
  <div class="bg-[#f6f3ee] min-h-screen text-[#2a2a28]">

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-[#d6cdbf]">
          <div class="flex justify-between items-center">
            <h2 class="text-2xl font-serif text-[#4a3728]">{{ editingProduct ? 'Modifier' : 'Ajouter' }} un produit</h2>
            <button @click="closeModal" class="text-gray-500 hover:text-gray-700">✕</button>
          </div>
        </div>
        
        <div class="p-6 space-y-4">
          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Titre</label>
            <input v-model="form.title" type="text" placeholder="Titre du produit" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Description</label>
            <textarea v-model="form.description" rows="4" placeholder="Description du produit" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]"></textarea>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-[#4a3728] mb-2">Catégorie</label>
              <select v-model="form.category" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
                <option value="">Sélectionnez une catégorie</option>
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-[#4a3728] mb-2">Prix (MAD)</label>
              <input v-model.number="form.price" type="number" placeholder="0" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
            </div>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-[#4a3728] mb-2">Stock</label>
              <input v-model.number="form.stock" type="number" placeholder="0" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
            </div>

            <div>
              <label class="block text-sm font-medium text-[#4a3728] mb-2">Condition</label>
              <select v-model="form.condition" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
                <option value="bon">Bon</option>
                <option value="parfait">Parfait</option>
                <option value="utilise">Utilisé</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Promotion (%)</label>
            <input v-model.number="form.promotion" type="number" placeholder="0" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#6b7b4b]">
          </div>

          <div>
            <label class="block text-sm font-medium text-[#4a3728] mb-2">Image</label>
            <div v-if="form.image_preview" class="mb-4">
              <img :src="form.image_preview" :alt="form.title" class="w-32 h-32 object-cover rounded-lg">
              <button @click="clearImage" type="button" class="mt-2 text-red-600 hover:text-red-800">Retirer</button>
            </div>
            <input @change="onImageSelected" type="file" accept="image/*" class="w-full px-4 py-2 border border-[#d6cdbf] rounded-lg">
          </div>
        </div>

        <div class="p-6 border-t border-[#d6cdbf] flex space-x-3">
          <button @click="saveProduct" class="flex-1 px-4 py-2 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium">
            {{ editingProduct ? 'Mettre à jour' : 'Ajouter' }}
          </button>
          <button @click="closeModal" class="flex-1 px-4 py-2 bg-[#d6cdbf] text-[#4a3728] rounded-lg hover:bg-[#cfc3aa]">
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- ===== HERO ===== -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Gestion produits
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Mes Produits
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Gérez votre catalogue vintage et vos stocks
        </p>
      </div>
    </section>

    <!-- ===== CONTENT ===== -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- ===== ACTION BAR ===== -->
      <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
          <h3 class="text-lg font-serif text-[#4a3728]">
            Catalogue
          </h3>
          <p class="text-sm text-[#7a7465]">
            Tous vos produits vintage
          </p>
        </div>

        <button
          @click="openAddModal"
          class="px-6 py-3 bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition"
        >
          + Ajouter un produit
        </button>
      </div>

      <!-- ===== FILTERS ===== -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input
            v-model="filters.search"
            type="text"
            placeholder="Rechercher un produit..."
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b]"
          />

          <select v-model="filters.category" class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Toutes les catégories</option>
            <option v-for="cat in categories" :key="cat" :value="cat">
              {{ cat.charAt(0).toUpperCase() + cat.slice(1).replace(/_/g, ' ') }}
            </option>
          </select>

          <select v-model="filters.status" class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Tous les statuts</option>
            <option value="active">Actif</option>
            <option value="inactive">Inactif</option>
            <option value="sold_out">Épuisé</option>
          </select>

          <button
            @click="applyFilters"
            class="bg-[#6b8043] text-white rounded-lg hover:bg-[#556b2f] font-medium transition"
          >
            Filtrer
          </button>
        </div>
      </div>

      <!-- ===== TABLE ===== -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">

        <div v-if="loading" class="p-10 text-center text-[#7a7465]">
          Chargement...
        </div>

        <div v-else-if="products.length === 0" class="p-10 text-center text-[#7a7465]">
          Aucun produit trouvé
        </div>

        <div v-else class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="bg-[#f1ede6]">
              <tr class="text-left text-xs uppercase tracking-wider text-[#6b7b4b]">
                <th class="px-6 py-4">Produit</th>
                <th class="px-6 py-4">Catégorie</th>
                <th class="px-6 py-4">Prix</th>
                <th class="px-6 py-4">Stock</th>
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
                <td class="px-6 py-4">
                  <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-lg bg-[#d6cdbf] overflow-hidden">
                      <img
                        v-if="product.image_url"
                        :src="product.image_url"
                        :alt="product.title"
                        class="w-full h-full object-cover"
                      />
                    </div>
                    <div>
                      <p class="font-medium text-[#4a3728]">
                        {{ product.title }}
                      </p>
                      <p class="text-xs text-[#7a7465]">
                        {{ product.description.substring(0, 35) }}...
                      </p>
                    </div>
                  </div>
                </td>

                <td class="px-6 py-4 text-[#7a7465]">
                  {{ product.category }}
                </td>

                <td class="px-6 py-4 font-medium text-[#4a3728]">
                  {{ product.price }} MAD
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="getStockClass(product.stock)"
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                  >
                    {{ product.stock === 0 ? 'Rupture' : product.stock }}
                  </span>
                </td>

                <td class="px-6 py-4">
                  <span
                    :class="getStatusClass(product.status)"
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                  >
                    {{ product.status }}
                  </span>
                </td>

                <td class="px-6 py-4 space-x-3">
                  <button
                    @click="editProduct(product)"
                    class="text-[#6b8043] hover:underline font-medium"
                  >
                    Modifier
                  </button>
                  <button
                    @click="deleteProduct(product.id)"
                    class="text-[#8b1c3d] hover:underline font-medium"
                  >
                    Supprimer
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</template>



<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useNotification } from '@/composables/useNotification'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import vendorProductService from '@/services/vendorProductService'

const authStore = useAuthStore()
const { showError, showSuccess } = useNotification()
const { confirmDelete } = useConfirmDialog()

const products = ref([])
const loading = ref(false)
const showAddModal = ref(false)
const editingProduct = ref(null)
const categories = ref([])

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
  loadCategories()
})

const loadCategories = async () => {
  try {
    const response = await vendorProductService.getCategories()
    // Response can be either array directly or wrapped in data
    categories.value = Array.isArray(response.data) ? response.data : (response.data?.data || [])
    console.log('📂 Categories loaded:', categories.value)
  } catch (error) {
    console.error('Error loading categories:', error)
    // Fallback si erreur
    categories.value = ['mode', 'mobilier', 'accessoires', 'electronique_vintage', 'art', 'autre']
  }
}

const loadProducts = async () => {
  loading.value = true
  try {
    // Build params - only include non-empty values
    const params = {}
    if (filters.value.search && filters.value.search.trim()) {
      params.search = filters.value.search
    }
    if (filters.value.category) {
      params.category = filters.value.category
    }
    if (filters.value.status) {
      params.status = filters.value.status
    }
    
    console.log('🔍 Loading products with filters:', params)
    
    const response = await vendorProductService.getAllProducts(params)
    console.log('📦 Response received:', response)
    
    // Handle pagination response
    products.value = response.data.data || response.data || []
    console.log('✅ Products loaded:', products.value.length, 'products')
  } catch (error) {
    console.error('❌ Error loading products:', error)
    products.value = []
  } finally {
    loading.value = false
  }
}

const applyFilters = () => {
  console.log('🔄 Applying filters:', filters.value)
  loadProducts()
}

const openAddModal = () => {
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
  showAddModal.value = true
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

const closeModal = () => {
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
}

const onImageSelected = (event) => {
  const file = event.target.files?.[0]
  if (file) {
    // Validate file size (5MB)
    if (file.size > 5 * 1024 * 1024) {
      showError('Le fichier est trop volumineux. Max 5MB.', 'Fichier trop volumineux')
      return
    }
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
      showError('Veuillez sélectionner une image valide.', 'Image invalide')
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
    
    // Validation basique
    if (!form.value.title || !form.value.description || !form.value.category) {
      showError('Veuillez remplir tous les champs obligatoires', 'Champs manquants')
      return
    }
    
    // Create FormData for file upload
    const formData = new FormData()
    formData.append('title', form.value.title)
    formData.append('description', form.value.description)
    formData.append('category', form.value.category)
    formData.append('price', form.value.price.toString())
    formData.append('stock', form.value.stock.toString())
    formData.append('condition', form.value.condition)
    formData.append('promotion', (form.value.promotion || 0).toString())
    
    if (form.value.image instanceof File) {
      formData.append('image', form.value.image)
    }
    
    console.log('Saving product...', {
      method,
      title: form.value.title,
      hasImage: form.value.image instanceof File
    })
    
    let response
    if (method === 'create') {
      response = await vendorProductService.createProduct(formData)
    } else {
      response = await vendorProductService.updateProduct(editingProduct.value.id, formData)
    }
    
    console.log('Product saved successfully:', response.data)
    
    // Success message
    showSuccess(method === 'create' ? 'Produit ajouté avec succès!' : 'Produit mis à jour avec succès!', 'Succès')
    
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
    
    // Afficher les erreurs de validation ou autres erreurs
    if (error.response?.status === 422) {
      const errors = error.response.data.errors || {}
      const errorMessages = Object.values(errors).flat().join('\n')
      showError('Erreur de validation:\n' + errorMessages, 'Erreur de validation')
    } else if (error.response?.data?.message) {
      showError(error.response.data.message, 'Erreur')
    } else {
      showError('Erreur lors de l\'enregistrement du produit. Veuillez réessayer.', 'Erreur')
    }
  }
}

const deleteProduct = async (id) => {
  const confirmed = await confirmDelete('ce produit')
  if (confirmed) {
    try {
      await vendorProductService.deleteProduct(id)
      showSuccess('Produit supprimé avec succès!', 'Suppression réussie')
      await loadProducts()
    } catch (error) {
      console.error('Error deleting product:', error)
      showError('Erreur lors de la suppression du produit', 'Erreur')
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
