<template>
  <Layout>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white overflow-hidden">
      <div class="absolute inset-0 bg-black opacity-10"></div>
      <svg class="absolute inset-0 w-full h-full" preserveAspectRatio="none" viewBox="0 0 1200 1200" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <pattern id="dots" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
            <circle cx="30" cy="30" r="1" fill="white" opacity="0.05" />
          </pattern>
        </defs>
        <rect width="1200" height="1200" fill="url(#dots)" />
      </svg>
      
      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
        <div class="text-center">
          <h1 class="text-4xl md:text-6xl font-bold mb-6 animate-fade-in">
            Bienvenue dans l'Univers Vintage
          </h1>
          <p class="text-xl md:text-2xl mb-8 text-indigo-100 max-w-3xl mx-auto">
            Découvrez une collection unique de pièces authentiques, soigneusement sélectionnées pour leur caractère et leur histoire.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <router-link
              to="/products"
              class="px-8 py-4 bg-white text-indigo-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-xl"
            >
              Explorer la Collection
            </router-link>
            <router-link
              to="/categories"
              class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition transform hover:scale-105"
            >
              Parcourir les Catégories
            </router-link>
          </div>
        </div>
      </div>

      <!-- Wave Separator -->
      <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
      </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="text-center p-6 rounded-xl hover:shadow-lg transition">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Authenticité Garantie</h3>
            <p class="text-gray-600">Tous nos produits sont vérifiés et authentifiés par nos experts.</p>
          </div>

          <div class="text-center p-6 rounded-xl hover:shadow-lg transition">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Prix Compétitifs</h3>
            <p class="text-gray-600">Des prix justes et transparents pour tous les budgets.</p>
          </div>

          <div class="text-center p-6 rounded-xl hover:shadow-lg transition">
            <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Livraison Rapide</h3>
            <p class="text-gray-600">Expédition sécurisée et suivi en temps réel de votre commande.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Produits Populaires -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Produits Populaires
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Découvrez les pièces les plus appréciées par notre communauté
          </p>
        </div>

        <div v-if="loading" class="text-center py-16">
          <div class="inline-block animate-spin rounded-full h-16 w-16 border-b-4 border-indigo-600"></div>
          <p class="mt-4 text-gray-600 text-lg">Chargement des produits...</p>
        </div>

        <div v-else-if="featuredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard
            v-for="product in featuredProducts"
            :key="product.id"
            :product="product"
            :is-favorite="isFavorite(product.id)"
            @click="openProductModal(product)"
            @toggle-favorite="toggleFavorite"
          />
        </div>

        <div v-else class="text-center py-12">
          <p class="text-gray-600">Aucun produit disponible pour le moment.</p>
        </div>

        <div class="text-center mt-12">
          <router-link
            to="/products"
            class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-bold hover:from-indigo-700 hover:to-purple-700 transition shadow-lg hover:shadow-xl transform hover:scale-105"
          >
            Voir Tous les Produits
          </router-link>
        </div>
      </div>
    </section>

    <!-- Catégories -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
            Explorez par Catégorie
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Trouvez exactement ce que vous cherchez
          </p>
        </div>

        <div v-if="categories.length > 0" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
          <router-link
            v-for="category in categories"
            :key="category"
            :to="`/products?category=${category}`"
            class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 p-6 text-center transform hover:scale-105 transition shadow-lg hover:shadow-2xl"
          >
            <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-10 transition"></div>
            <div class="relative z-10">
              <div class="text-3xl mb-2">🏺</div>
              <h3 class="text-white font-bold text-sm md:text-base">{{ category }}</h3>
            </div>
          </router-link>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">
          Prêt à Commencer Votre Collection ?
        </h2>
        <p class="text-xl text-indigo-100 mb-8">
          Rejoignez notre communauté de passionnés de vintage et découvrez des trésors uniques.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link
            to="/register-client"
            class="px-8 py-4 bg-white text-indigo-600 rounded-lg font-bold text-lg hover:bg-gray-100 transition transform hover:scale-105 shadow-xl"
          >
            Créer un Compte
          </router-link>
          <router-link
            to="/products"
            class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-indigo-600 transition transform hover:scale-105"
          >
            Explorer Maintenant
          </router-link>
        </div>
      </div>
    </section>

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
                    <span class="px-3 py-1.5 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                      {{ getConditionLabel(selectedProduct.condition) }}
                    </span>
                  </div>

                  <div class="flex items-center space-x-3">
                    <span class="text-gray-600 text-sm">Stock:</span>
                    <span class="font-semibold" :class="selectedProduct.stock < 5 ? 'text-orange-600' : 'text-green-600'">
                      {{ selectedProduct.stock > 0 ? `${selectedProduct.stock} disponible(s)` : 'Épuisé' }}
                    </span>
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
                </div>

                <!-- Description -->
                <div class="mb-8">
                  <h3 class="font-bold text-gray-900 mb-3 text-lg">Description</h3>
                  <p class="text-gray-700 leading-relaxed">{{ selectedProduct.description }}</p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                  <router-link
                    :to="`/products`"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                  >
                    Voir Détails
                  </router-link>
                  <button
                    @click="toggleFavorite(selectedProduct.id)"
                    :class="isFavorite(selectedProduct.id) ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                    class="px-6 py-4 font-bold rounded-xl transition flex items-center justify-center space-x-2 shadow-lg"
                  >
                    <svg class="w-5 h-5" :class="isFavorite(selectedProduct.id) ? 'fill-current' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span>{{ isFavorite(selectedProduct.id) ? 'Retiré' : 'Ajouter aux favoris' }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Layout from '@/components/Layout.vue'
import ProductCard from '@/components/ProductCard.vue'
import productService from '@/services/productService'

const router = useRouter()
const featuredProducts = ref([])
const categories = ref([])
const loading = ref(false)
const selectedProduct = ref(null)
const favorites = ref(JSON.parse(localStorage.getItem('favorites') || '[]'))

const loadFeaturedProducts = async () => {
  loading.value = true
  try {
    const response = await productService.getAllProducts({ limit: 8, sort_by: 'created_at' })
    featuredProducts.value = response.data || []
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

const isFavorite = (productId) => favorites.value.includes(productId)

const toggleFavorite = (productId) => {
  if (isFavorite(productId)) {
    favorites.value = favorites.value.filter(id => id !== productId)
  } else {
    favorites.value.push(productId)
  }
  localStorage.setItem('favorites', JSON.stringify(favorites.value))
}

const openProductModal = (product) => {
  selectedProduct.value = product
  document.body.style.overflow = 'hidden'
}

const closeModal = () => {
  selectedProduct.value = null
  document.body.style.overflow = 'auto'
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

onMounted(() => {
  loadFeaturedProducts()
  loadCategories()
})
</script>

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fade-in 1s ease-out;
}
</style>
