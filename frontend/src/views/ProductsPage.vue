<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <router-link to="/" class="flex items-center space-x-2">
              <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <span class="text-xl font-bold text-gray-900">Vintage Shop</span>
            </router-link>
            <router-link to="/products" class="text-sm font-medium text-indigo-600 border-b-2 border-indigo-600 pb-1">
              Catalogue
            </router-link>
          </div>
          <div class="flex items-center space-x-6">
            <!-- Favoris - Seulement pour clients -->
            <router-link v-if="authStore.isClient" to="/favorites" class="relative text-gray-600 hover:text-red-500 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span v-if="favoritesCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ favoritesCount }}
              </span>
            </router-link>
            <!-- Panier - Seulement pour clients -->
            <router-link v-if="authStore.isClient" to="/cart" class="relative text-gray-600 hover:text-indigo-600 transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span v-if="cartCount > 0" class="absolute -top-2 -right-2 bg-indigo-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ cartCount }}
              </span>
            </router-link>

            <!-- Navigation selon le rôle -->
            <router-link v-if="!authStore.isAuthenticated" to="/login" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
              Connexion
            </router-link>
            <router-link v-else-if="authStore.isAdmin" to="/admin/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
              Dashboard Admin
            </router-link>
            <router-link v-else-if="authStore.isVendor" to="/vendor/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
              Espace Vendeur
            </router-link>
       <router-link v-else-if="authStore.isClient" to="/client/dashboard" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">
          Mon Compte
        </router-link>
          </div>
        </div>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
      <!-- Hero Section -->
      <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-12 mb-10 text-white">
        <div class="max-w-3xl">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Collection Vintage</h1>
          <p class="text-xl text-indigo-100 mb-6">Découvrez des pièces uniques et authentiques, soigneusement sélectionnées pour vous</p>
          <div class="flex items-center space-x-6 text-sm">
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
              </svg>
              <span>Authentique</span>
            </div>
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <span>Qualité garantie</span>
            </div>
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
              </svg>
              <span>Livraison gratuite</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Message si non client -->
      <div v-if="!authStore.isClient" class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-center">
          <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="font-semibold text-blue-800">Connectez-vous pour acheter</p>
            <p class="text-sm text-blue-700">Vous devez être connecté en tant que client pour ajouter des produits au panier.</p>
          </div>
        </div>
      </div>

      <!-- Filtres -->
      <div class="mb-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
          <!-- Recherche -->
          <div class="lg:col-span-2">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Rechercher un produit..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
                @input="debouncedSearch"
              />
              <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Catégorie -->
          <div>
            <select
              v-model="filters.category"
              @change="applyFilters"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
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
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
            >
              <option value="">Toutes conditions</option>
              <option value="neuf">Neuf</option>
              <option value="excellent">Excellent</option>
              <option value="tres_bon">Très bon</option>
              <option value="bon">Bon</option>
              <option value="acceptable">Acceptable</option>
              <option value="a_restaurer">À restaurer</option>
            </select>
          </div>

          <!-- Tri -->
          <div>
            <select
              v-model="filters.sort_by"
              @change="applyFilters"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition text-sm"
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
            :class="filters.with_promotion ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
            class="px-4 py-2 rounded-lg text-sm font-medium transition inline-flex items-center space-x-2"
          >
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
            </svg>
            <span>En promotion</span>
          </button>
          <button
            @click="clearFilters"
            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition inline-flex items-center space-x-2"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span>Réinitialiser</span>
          </button>
        </div>
      </div>

      <!-- Stats -->
      <div v-if="stats" class="mb-6 flex flex-wrap items-center gap-6 text-sm text-gray-600 bg-white rounded-lg p-4 shadow-sm border border-gray-200">
        <div class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
          </svg>
          <span class="font-semibold text-gray-900">{{ stats.total }}</span>
          <span>produits</span>
        </div>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
          </svg>
          <span class="font-semibold text-gray-900">{{ stats.categories }}</span>
          <span>catégories</span>
        </div>
        <span class="text-gray-300">|</span>
        <div class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
          </svg>
          <span>Prix moyen:</span>
          <span class="font-semibold text-gray-900">{{ stats.avg_price }}€</span>
        </div>
        <span v-if="stats.with_promotion" class="text-gray-300">|</span>
        <div v-if="stats.with_promotion" class="flex items-center space-x-2">
          <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
          </svg>
          <span class="font-semibold text-red-600">{{ stats.with_promotion }}</span>
          <span>en promotion</span>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="text-center py-20">
        <div class="inline-block">
          <svg class="animate-spin h-12 w-12 text-indigo-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
        <p class="mt-4 text-gray-600 font-medium">Chargement des produits...</p>
      </div>

      <!-- Grille de Produits -->
      <div v-else-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transform transition duration-300 hover:shadow-xl hover:-translate-y-1"
        >
          <!-- Image -->
          <div class="relative h-64 bg-gray-100 overflow-hidden cursor-pointer" @click="openProductModal(product)">
            <img
              v-if="product.image_url"
              :src="product.image_url"
              :alt="product.title"
              class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
            />
            <div v-else class="w-full h-full flex items-center justify-center">
              <svg class="w-20 h-20 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
              </svg>
            </div>
            
            <!-- Badge Promotion -->
            <div v-if="product.promotion > 0" class="absolute top-3 right-3 bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
              -{{ product.promotion }}%
            </div>

            <!-- Badge Stock -->
            <div v-if="product.stock > 0 && product.stock < 5" class="absolute top-3 left-3 bg-orange-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow-lg">
              Plus que {{ product.stock }}
            </div>

            <!-- Favorite Button - Only for clients -->
            <button
              v-if="authStore.isClient"
              @click.stop="toggleFavoriteFromGrid(product)"
              class="absolute bottom-3 right-3 bg-white rounded-full p-2.5 shadow-lg hover:scale-110 transition transform z-10 hover:shadow-xl"
              :disabled="isTogglingFavorite"
              :title="isFavorite(product.id) ? 'Retirer des favoris' : 'Ajouter aux favoris'"
            >
              <svg 
                class="w-5 h-5 transition"
                :class="isFavorite(product.id) ? 'text-red-500 fill-red-500' : 'text-gray-400 hover:text-red-500'"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>
          </div>

          <!-- Contenu -->
          <div class="p-5">
            <div class="mb-3 cursor-pointer" @click="openProductModal(product)">
              <h3 class="text-base font-semibold text-gray-900 mb-2 line-clamp-2 min-h-[3rem] hover:text-indigo-600 transition">
                {{ product.title }}
              </h3>
              <p class="text-sm text-gray-500">{{ product.category }}</p>
            </div>
            
            <!-- Prix -->
            <div class="flex items-center justify-between mb-4">
              <div>
                <div v-if="product.promotion > 0" class="text-sm text-gray-400 line-through">
                  {{ product.price }}€
                </div>
                <div class="text-xl font-bold text-indigo-600">
                  {{ product.final_price }}€
                </div>
              </div>
              <span :class="getConditionClass(product.condition)" class="px-3 py-1.5 text-xs font-medium rounded-lg inline-block">
                {{ getConditionLabel(product.condition) }}
              </span>
            </div>

            <!-- Bouton Ajouter au panier - Seulement pour clients -->
            <button
              v-if="authStore.isClient"
              @click="addToCart(product)"
              :disabled="product.stock === 0"
              class="w-full px-4 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span>{{ product.stock === 0 ? 'Épuisé' : 'Ajouter au panier' }}</span>
            </button>

            <!-- Bouton Voir les détails - Pour non-clients -->
            <button
              v-else
              @click="openProductModal(product)"
              class="w-full px-4 py-2.5 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 transition flex items-center justify-center space-x-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <span>Voir les détails</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Aucun produit -->
      <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
        <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun produit trouvé</h3>
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
            class="px-4 py-2 bg-white border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition font-medium text-sm"
          >
            Précédent
          </button>
          <div class="flex items-center space-x-1">
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="goToPage(page)"
              :class="page === pagination.current_page ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
              class="px-4 py-2 border rounded-lg transition font-medium text-sm min-w-[40px]"
            >
              {{ page }}
            </button>
          </div>
          <button
            @click="goToPage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="px-4 py-2 bg-white border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition font-medium text-sm"
          >
            Suivant
          </button>
        </nav>
      </div>
    </main>

<!-- Modal Détails Produit -->
<transition name="modal">
  <div v-if="selectedProduct" @click="closeModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm">
    <div @click.stop class="bg-white rounded-2xl max-w-6xl w-full my-8 shadow-2xl">
      <!-- Header Modal avec Fermeture -->
      <div class="relative border-b border-gray-200">
        <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10 bg-white rounded-full w-10 h-10 flex items-center justify-center shadow-lg hover:shadow-xl transition">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Contenu Scrollable -->
      <div class="max-h-[85vh] overflow-y-auto">
        <!-- Section Produit -->
        <div class="grid grid-cols-1 md:grid-cols-2">
          <!-- Image -->
          <div class="relative h-96 md:h-[600px] bg-gray-100 flex items-center justify-center">
            <img
              v-if="selectedProduct.image_url"
              :src="selectedProduct.image_url"
              :alt="selectedProduct.title"
              class="w-full h-full object-cover"
            />
            <svg v-else class="w-32 h-32 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
            </svg>
            
            <div v-if="selectedProduct.promotion > 0" class="absolute top-4 right-4 bg-red-600 text-white px-4 py-2 rounded-lg text-base font-bold shadow-lg">
              -{{ selectedProduct.promotion }}%
            </div>

                <!-- Favorite Button -->
                <button
                  v-if="authStore.isClient"
                  @click.stop="toggleFavoriteProduct"
                  class="absolute bottom-4 right-4 bg-white rounded-full p-3 shadow-lg hover:scale-110 transition transform z-10"
                  :disabled="isTogglingFavorite"
                >
                  <svg 
                    class="w-6 h-6 transition"
                    :class="isProductFavorited ? 'text-red-500 fill-red-500' : 'text-gray-400'"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
          </div>

          <!-- Détails -->
          <div class="p-8 md:p-10">
                <div class="flex items-start justify-between mb-4">
              <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ selectedProduct.title }}</h2>
                  <div v-if="authStore.isClient && isProductFavorited" class="bg-red-50 border border-red-200 px-3 py-1.5 rounded-lg">
                    <span class="text-red-700 font-semibold text-sm flex items-center space-x-1">
                      <svg class="w-4 h-4 fill-red-500" viewBox="0 0 24 24">
                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                      </svg>
                      <span>En favoris</span>
                    </span>
                  </div>
                </div>
            
            <div class="space-y-4 mb-6">
              <div class="flex items-center space-x-3 text-sm">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                </svg>
                <span class="text-gray-600">Catégorie:</span>
                <span class="font-semibold text-indigo-600">{{ selectedProduct.category }}</span>
              </div>

              <div class="flex items-center space-x-3 text-sm">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-600">État:</span>
                <span :class="getConditionClass(selectedProduct.condition)" class="px-3 py-1.5 text-xs font-medium rounded-lg">
                  {{ getConditionLabel(selectedProduct.condition) }}
                </span>
              </div>

              <div class="flex items-center space-x-3 text-sm">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                </svg>
                <span class="text-gray-600">Stock:</span>
                <span class="font-semibold" :class="selectedProduct.stock < 5 ? 'text-orange-600' : 'text-green-600'">
                  {{ selectedProduct.stock > 0 ? `${selectedProduct.stock} disponible(s)` : 'Épuisé' }}
                </span>
              </div>

              <div class="flex items-center space-x-3 text-sm">
                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                <span class="text-gray-600">Vendeur:</span>
                <span class="font-semibold text-gray-900">{{ selectedProduct.vendeur?.name || 'Vintage Shop' }}</span>
              </div>
            </div>

            <!-- Prix -->
            <div class="mb-6 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-100">
              <div v-if="selectedProduct.promotion > 0" class="flex items-center space-x-3 mb-2">
                <span class="text-2xl text-gray-400 line-through">{{ selectedProduct.price }}€</span>
                <span class="px-3 py-1.5 bg-red-500 text-white text-sm font-bold rounded-lg">
                  -{{ selectedProduct.promotion }}%
                </span>
              </div>
              <div class="text-5xl font-bold text-indigo-600 mb-2">
                {{ selectedProduct.final_price }}€
              </div>
              <div v-if="selectedProduct.promotion > 0" class="text-sm text-green-600 font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Vous économisez {{ (selectedProduct.price - selectedProduct.final_price).toFixed(2) }}€</span>
              </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
              <h3 class="font-bold text-gray-900 mb-3 text-lg flex items-center space-x-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>Description</span>
              </h3>
              <p class="text-gray-700 leading-relaxed">{{ selectedProduct.description }}</p>
            </div>

            <!-- Actions -->
            <div v-if="authStore.isClient">
              <button
                @click="addToCartFromModal(selectedProduct)"
                :disabled="selectedProduct.stock === 0"
                class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>{{ selectedProduct.stock === 0 ? 'Rupture de stock' : 'Ajouter au panier' }}</span>
              </button>
            </div>
            <div v-else class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
              <div class="flex">
                <svg class="w-6 h-6 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <div>
                  <p class="font-semibold text-blue-800">Connexion requise</p>
                  <p class="text-sm text-blue-700 mt-1">Connectez-vous en tant que client pour acheter ce produit.</p>
                  <router-link to="/login" class="mt-3 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Se connecter
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- SECTION REVIEWS - ICI C'EST IMPORTANT -->
        <div class="border-t border-gray-200 bg-gray-50 p-8">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">📝 Section Avis (Test)</h2>
          <ReviewSection 
            v-if="selectedProduct && selectedProduct.id"
            :product-id="selectedProduct.id" 
          />
        </div>
      </div>
    </div>
  </div>
</transition>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-gray-800 font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useCartStore } from '@/stores/cartStore'
import { useFavorites } from '@/composables/useFavorites'
import productService from '@/services/productService'


import reviewService from '@/services/reviewService'
import ReviewSection from '@/components/ReviewSection.vue'  
import ProductReviewsPreview from '@/components/ProductReviewsPreview.vue'

const authStore = useAuthStore()
const cartStore = useCartStore()
const { isFavorite, isLoading: isTogglingFavorite, toggleFavorite, loadFavorites, getFavoriteCount } = useFavorites()

const products = ref([])
const categories = ref([])
const stats = ref(null)
const loading = ref(false)
const searchQuery = ref('')
const selectedProduct = ref(null)
const pagination = ref(null)
const isProductFavorited = ref(false)

// Computed property for real favorites count
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

const openProductModal = (product) => {
  selectedProduct.value = product
  document.body.style.overflow = 'hidden'
  // Check if product is favorited
  if (authStore.isClient) {
    isProductFavorited.value = isFavorite(product.id)
  }
}

const closeModal = () => {
  selectedProduct.value = null
  document.body.style.overflow = 'auto'
}

const toggleFavoriteProduct = async () => {
  if (!selectedProduct.value) return
  try {
    const newStatus = await toggleFavorite(selectedProduct.value.id)
    isProductFavorited.value = newStatus
    // Favorites count is now computed automatically from getFavoriteCount
    // Dispatch custom event to update navbar
    window.dispatchEvent(new CustomEvent('favorites-updated', { 
      detail: { count: getFavoriteCount.value } 
    }))
    showNotification(newStatus ? 'Ajouté aux favoris!' : 'Supprimé des favoris!')
  } catch (err) {
    console.error('Error toggling favorite:', err)
    showNotification('Erreur lors de la mise à jour du favori', 'error')
  }
}

const toggleFavoriteFromGrid = async (product) => {
  try {
    const newStatus = await toggleFavorite(product.id)
    // Favorites count is now computed automatically from getFavoriteCount
    // Dispatch custom event to update navbar
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

const addToCartFromModal = (product) => {
  addToCart(product)
}

onMounted(async () => {
  loadProducts()
  loadCategories()
  loadStats()
  // Load favorites if client is authenticated
  if (authStore.isClient) {
    await loadFavorites()
    // Favorites count is now computed automatically from getFavoriteCount
  }
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

.slide-up-enter-active,
.slide-up-leave-active {
  transition: all 0.3s ease;
}

.slide-up-enter-from {
  transform: translateY(100px);
  opacity: 0;
}

.slide-up-leave-to {
  transform: translateY(100px);
  opacity: 0;
}
</style>