<template>
  <div class="min-h-screen flex flex-col bg-[#f8f5ef] text-[#2a2a28] font-serif">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#fbfaf7] border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-5">
     
        <h1 class="text-3xl md:text-4xl font-bold text-[#4a3728] mb-2">
          Mon Compte
        </h1>
        <p class="text-[#5a564f]">
          Bienvenue {{ authStore.user?.name }} ! Gérez votre profil et vos commandes.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow w-full">
      <!-- Navigation Tabs -->
      <div class="mb-12 border-b border-[#d6cdbf] overflow-x-auto">
        <nav class="flex space-x-8">
          <button
            @click="activeTab = 'profile'"
            :class="activeTab === 'profile' ? 'border-[#8b5e3c] text-[#8b5e3c]' : 'border-transparent text-[#5a564f] hover:text-[#4a3728]'"
            class="pb-4 px-1 border-b-2 font-medium text-sm transition whitespace-nowrap"
          >
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span>Profil</span>
            </div>
          </button>

          <button
            @click="activeTab = 'address'"
            :class="activeTab === 'address' ? 'border-[#8b5e3c] text-[#8b5e3c]' : 'border-transparent text-[#5a564f] hover:text-[#4a3728]'"
            class="pb-4 px-1 border-b-2 font-medium text-sm transition whitespace-nowrap"
          >
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Adresse</span>
            </div>
          </button>

          <button
            @click="activeTab = 'orders'"
            :class="activeTab === 'orders' ? 'border-[#8b5e3c] text-[#8b5e3c]' : 'border-transparent text-[#5a564f] hover:text-[#4a3728]'"
            class="pb-4 px-1 border-b-2 font-medium text-sm transition whitespace-nowrap"
          >
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
              </svg>
              <span>Commandes</span>
            </div>
          </button>
          
          <button
            @click="activeTab = 'favorites'"
            :class="activeTab === 'favorites' ? 'border-[#8b5e3c] text-[#8b5e3c]' : 'border-transparent text-[#5a564f] hover:text-[#4a3728]'"
            class="pb-4 px-1 border-b-2 font-medium text-sm transition whitespace-nowrap relative"
          >
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              <span>Favoris</span>
              <span v-if="favoriteCount > 0" class="ml-2 bg-[#8b5e3c] text-white text-xs font-bold rounded-full px-2 py-0.5">
                {{ favoriteCount }}
              </span>
            </div>
          </button>

          <button
            @click="activeTab = 'reviews'"
            :class="activeTab === 'reviews' ? 'border-[#8b5e3c] text-[#8b5e3c]' : 'border-transparent text-[#5a564f] hover:text-[#4a3728]'"
            class="pb-4 px-1 border-b-2 font-medium text-sm transition whitespace-nowrap"
          >
            <div class="flex items-center space-x-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
              <span>Avis</span>
            </div>
          </button>
        </nav>
      </div>

      <!-- Contenu Profil -->
      <div v-if="activeTab === 'profile'" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations Personnelles -->
        <div class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-8">
          <h2 class="text-2xl font-bold text-[#4a3728] mb-6 flex items-center space-x-2">
            <svg class="w-6 h-6 text-[#8b5e3c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span>Informations Personnelles</span>
          </h2>

          <form @submit.prevent="updateProfile" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Nom complet *</label>
              <input
                v-model="profileForm.name"
                type="text"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Email *</label>
              <input
                v-model="profileForm.email"
                type="email"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Téléphone</label>
              <input
                v-model="profileForm.phone"
                type="tel"
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                placeholder="+212 6 12 34 56 78"
              />
            </div>

            <div v-if="profileError" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
              <p class="text-sm text-red-700">{{ profileError }}</p>
            </div>

            <button
              type="submit"
              :disabled="profileLoading"
              class="w-full px-6 py-3 bg-[#8b5e3c] text-white font-bold rounded-lg hover:bg-[#a06a47] transition disabled:opacity-50 shadow-lg hover:shadow-xl uppercase tracking-wider text-sm"
            >
              {{ profileLoading ? 'Enregistrement...' : 'Enregistrer les modifications' }}
            </button>
          </form>
        </div>

        <!-- Changer le Mot de Passe -->
        <div class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-8">
          <h2 class="text-2xl font-bold text-[#4a3728] mb-6 flex items-center space-x-2">
            <svg class="w-6 h-6 text-[#8b5e3c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <span>Changer le Mot de Passe</span>
          </h2>

          <form @submit.prevent="updatePassword" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Mot de passe actuel *</label>
              <input
                v-model="passwordForm.current_password"
                type="password"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Nouveau mot de passe *</label>
              <input
                v-model="passwordForm.password"
                type="password"
                required
                minlength="8"
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
              />
              <p class="text-xs text-[#5a564f] mt-1">Minimum 8 caractères</p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Confirmer le mot de passe *</label>
              <input
                v-model="passwordForm.password_confirmation"
                type="password"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
              />
            </div>

            <div v-if="passwordError" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
              <p class="text-sm text-red-700">{{ passwordError }}</p>
            </div>

            <button
              type="submit"
              :disabled="passwordLoading"
              class="w-full px-6 py-3 bg-[#8b5e3c] text-white font-bold rounded-lg hover:bg-[#a06a47] transition disabled:opacity-50 shadow-lg hover:shadow-xl uppercase tracking-wider text-sm"
            >
              {{ passwordLoading ? 'Modification...' : 'Changer le mot de passe' }}
            </button>
          </form>
        </div>
      </div>

      <!-- Contenu Adresse de Livraison -->
      <div v-if="activeTab === 'address'">
        <div class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-8 max-w-3xl">
          <h2 class="text-2xl font-bold text-[#4a3728] mb-6 flex items-center space-x-2">
            <svg class="w-6 h-6 text-[#8b5e3c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Adresse de Livraison</span>
          </h2>

          <!-- Adresse Actuelle -->
          <div v-if="shippingAddress && !editingAddress" class="mb-6 p-6 bg-[#f8f5ef] rounded-lg border border-[#d6cdbf]">
            <div class="flex justify-between items-start mb-3">
              <h3 class="font-bold text-[#4a3728] uppercase tracking-wider text-sm">Adresse Enregistrée</h3>
              <button
                @click="startEditingAddress"
                class="text-[#8b5e3c] hover:text-[#a06a47] text-sm font-semibold uppercase tracking-widest"
              >
                Modifier
              </button>
            </div>
            <p class="text-sm text-[#5a564f] leading-relaxed">
              {{ shippingAddress.full_name }}<br>
              {{ shippingAddress.address }}<br>
              {{ shippingAddress.postal_code }} {{ shippingAddress.city }}<br>
              {{ shippingAddress.country }}<br>
              Tél: {{ shippingAddress.phone }}
            </p>
          </div>

          <!-- Message si pas d'adresse -->
          <div v-if="!shippingAddress && !editingAddress" class="mb-6 p-6 bg-orange-50 rounded-lg border-l-4 border-orange-500">
            <p class="text-sm text-orange-800 mb-2 font-bold">
              ⚠️ Aucune adresse enregistrée
            </p>
            <p class="text-xs text-orange-700 mb-3">
              Vous devez enregistrer une adresse de livraison pour passer des commandes.
            </p>
            <button
              @click="startEditingAddress"
              class="text-sm font-bold text-orange-600 hover:text-orange-700 uppercase tracking-widest"
            >
              Ajouter une adresse →
            </button>
          </div>

          <!-- Formulaire d'Adresse -->
          <form v-if="editingAddress" @submit.prevent="saveAddress" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Nom complet *</label>
              <input
                v-model="addressForm.full_name"
                type="text"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                placeholder="Ahmed Benali"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Téléphone *</label>
              <input
                v-model="addressForm.phone"
                type="tel"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                placeholder="+212 6 12 34 56 78"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Adresse *</label>
              <input
                v-model="addressForm.address"
                type="text"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                placeholder="123 Rue Hassan II"
              />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Code postal *</label>
                <input
                  v-model="addressForm.postal_code"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                  placeholder="30000"
                />
              </div>
              <div>
                <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Ville *</label>
                <input
                  v-model="addressForm.city"
                  type="text"
                  required
                  class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                  placeholder="Fès"
                />
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Pays *</label>
              <input
                v-model="addressForm.country"
                type="text"
                required
                class="w-full px-4 py-2 border border-[#d6cdbf] bg-white rounded-lg focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent"
                placeholder="Maroc"
              />
            </div>

            <div v-if="addressError" class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
              <p class="text-sm text-red-700">{{ addressError }}</p>
            </div>

            <div class="flex space-x-3">
              <button
                type="submit"
                :disabled="addressLoading"
                class="flex-1 px-6 py-3 bg-[#8b5e3c] text-white font-bold rounded-lg hover:bg-[#a06a47] transition disabled:opacity-50 shadow-lg hover:shadow-xl uppercase tracking-wider text-sm"
              >
                {{ addressLoading ? 'Enregistrement...' : 'Enregistrer l\'adresse' }}
              </button>
              <button
                type="button"
                @click="cancelEditingAddress"
                class="px-6 py-3 bg-[#f1eee6] text-[#5a564f] font-bold rounded-lg hover:bg-[#e5e1d8] transition border border-[#d6cdbf] uppercase tracking-wider text-sm"
              >
                Annuler
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Contenu Commandes -->
      <div v-if="activeTab === 'orders'">
        <OrdersHistory @cancel-order="handleCancelOrder" />
      </div>

      <!-- Contenu Favoris -->
      <div v-if="activeTab === 'favorites'" class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-3xl font-bold text-[#4a3728]">Mes Favoris</h2>
            <p class="text-[#5a564f] mt-1">{{ favoriteCount }} produit(s) sauvegardé(s)</p>
          </div>
          <button
            v-if="favoriteCount > 0"
            @click="clearAllFavorites"
            class="px-6 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-bold text-sm uppercase tracking-widest"
          >
            Supprimer tous
          </button>
        </div>

        <!-- Empty State -->
        <div v-if="favoritesList.length === 0" class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-16 text-center">
          <svg class="mx-auto h-16 w-16 text-[#c1bdae] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          <h3 class="text-xl font-bold text-[#4a3728] mb-2">Aucun favori</h3>
          <p class="text-[#5a564f] mb-6">Vous n'avez pas encore sauvegardé de produits.</p>
          <router-link to="/products" class="inline-block px-8 py-3 bg-[#8b5e3c] text-white rounded-lg font-bold hover:bg-[#a06a47] transition shadow-lg hover:shadow-xl uppercase tracking-wider text-sm">
            Découvrir les produits
          </router-link>
        </div>

        <!-- Loading State -->
        <div v-if="favoritesLoading" class="flex justify-center items-center py-12">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#8b5e3c]"></div>
        </div>

        <!-- Error State -->
        <div v-if="favoritesError" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
          {{ favoritesError }}
        </div>

        <!-- Favorites Grid -->
        <div v-if="!favoritesLoading && favoritesList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
          <div v-for="favorite in favoritesList" :key="favorite.id" class="relative">
            <div class="bg-[#fbfaf7] rounded-2xl shadow-md overflow-hidden cursor-pointer transform transition duration-300 hover:scale-105 hover:shadow-2xl group border border-[#d6cdbf]">
              <!-- Image -->
              <div class="relative h-56 bg-[#f1eee6] flex items-center justify-center overflow-hidden">
                <img
                  v-if="favorite.product?.image_url || favorite.product?.image"
                  :src="favorite.product.image_url || favorite.product.image"
                  :alt="favorite.product.title"
                  class="w-full h-full object-cover group-hover:scale-110 transition duration-300"
                />
                <div v-else class="text-[#c1bdae] text-sm">Aucune image</div>
                
                <!-- Stock Badge -->
                <div v-if="favorite.product?.stock > 0 && favorite.product?.stock < 5" class="absolute top-3 left-3 bg-orange-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                  Plus que {{ favorite.product.stock }}
                </div>
                <div v-else-if="favorite.product?.stock === 0" class="absolute top-3 left-3 bg-gray-500 text-white px-3 py-1.5 rounded-full text-xs font-bold shadow-lg">
                  Épuisé
                </div>

                <!-- Remove Button -->
                <button
                  @click="removeFavorite(favorite.id)"
                  class="absolute bottom-3 right-3 bg-white rounded-full p-2.5 shadow-lg hover:scale-110 transition transform z-10 text-red-500"
                >
                  <svg class="w-5 h-5 fill-red-500" viewBox="0 0 24 24">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                </button>
              </div>

              <!-- Content -->
              <div class="p-5">
                <h3 class="text-lg font-semibold text-[#4a3728] mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-[#8b5e3c] transition cursor-pointer" @click="goToProduct(favorite.product.id)">
                  {{ favorite.product?.title }}
                </h3>
                
                <p class="text-sm text-[#5a564f] mb-3">
                  {{ favorite.product?.category }}
                </p>
                
                <!-- Price -->
                <div class="flex items-center justify-between mb-3">
                  <div class="text-2xl font-bold text-[#8b5e3c]">
                    {{ favorite.product?.price }} MAD
                  </div>
                </div>

                <!-- Condition -->
                <div v-if="favorite.product?.condition" class="mb-4">
                  <span :class="getConditionClass(favorite.product.condition)" class="px-3 py-1.5 text-xs font-semibold rounded-full inline-block">
                    {{ getConditionLabel(favorite.product.condition) }}
                  </span>
                </div>

                <!-- View Button -->
                <button
                  @click="goToProduct(favorite.product.id)"
                  class="w-full px-4 py-2 bg-[#8b5e3c] text-white rounded-lg hover:bg-[#a06a47] transition font-bold text-sm uppercase tracking-wider shadow-lg hover:shadow-xl"
                >
                  Voir le produit
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Contenu Mes Avis -->
      <div v-if="activeTab === 'reviews'">
        <MyReviews />
      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : 'border-[#6b7b4b]'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-[#6b7b4b]" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-[#4a3728] font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>
  </div>
  <Footer />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { useFavorites } from '@/composables/useFavorites'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import profileService from '@/services/profileService'
import shippingAddressService from '@/services/shippingAddressService'
import orderService from '@/services/orderService'
import favoritesService from '@/services/favoritesService'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

import OrdersHistory from '@/components/OrdersHistory.vue'
import MyReviews from '@/components/MyReviews.vue'

const router = useRouter()
const authStore = useAuthStore()
const { loadFavorites, getFavoriteCount } = useFavorites()
const { confirmDelete } = useConfirmDialog()

const activeTab = ref('profile')

const profileForm = ref({
  name: '',
  email: '',
  phone: ''
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const addressForm = ref({
  full_name: '',
  phone: '',
  address: '',
  city: '',
  postal_code: '',
  country: 'Maroc'
})

const shippingAddress = ref(null)
const editingAddress = ref(false)

const profileLoading = ref(false)
const passwordLoading = ref(false)
const addressLoading = ref(false)

const profileError = ref('')
const passwordError = ref('')
const addressError = ref('')

// Favorites state
const favoritesList = ref([])
const favoritesLoading = ref(false)
const favoritesError = ref('')
const favoriteCount = ref(0)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadProfile = () => {
  profileForm.value = {
    name: authStore.user?.name || '',
    email: authStore.user?.email || '',
    phone: authStore.user?.phone || ''
  }
}

const loadShippingAddress = async () => {
  try {
    shippingAddress.value = await shippingAddressService.getAddress()
  } catch (error) {
    console.error('Erreur chargement adresse:', error)
  }
}

const updateProfile = async () => {
  profileLoading.value = true
  profileError.value = ''

  try {
    const response = await profileService.updateProfile(profileForm.value)
    
    // Mettre à jour le store
    authStore.user.name = profileForm.value.name
    authStore.user.email = profileForm.value.email
    authStore.user.phone = profileForm.value.phone
    localStorage.setItem('user', JSON.stringify(authStore.user))
    
    showNotification('Profil mis à jour avec succès!')
  } catch (error) {
    console.error('Erreur mise à jour profil:', error)
    profileError.value = error.response?.data?.message || 'Erreur lors de la mise à jour'
  } finally {
    profileLoading.value = false
  }
}

const updatePassword = async () => {
  passwordLoading.value = true
  passwordError.value = ''

  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    passwordError.value = 'Les mots de passe ne correspondent pas'
    passwordLoading.value = false
    return
  }

  try {
    await profileService.updatePassword(passwordForm.value)
    
    showNotification('Mot de passe mis à jour avec succès!')
    
    // Réinitialiser le formulaire
    passwordForm.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    }
  } catch (error) {
    console.error('Erreur mise à jour mot de passe:', error)
    passwordError.value = error.response?.data?.message || 'Erreur lors de la mise à jour'
  } finally {
    passwordLoading.value = false
  }
}

const startEditingAddress = () => {
  if (shippingAddress.value) {
    // Mode édition - charger l'adresse existante
    addressForm.value = {
      full_name: shippingAddress.value.full_name,
      phone: shippingAddress.value.phone,
      address: shippingAddress.value.address,
      city: shippingAddress.value.city,
      postal_code: shippingAddress.value.postal_code,
      country: shippingAddress.value.country
    }
  } else {
    // Mode création - formulaire vide
    addressForm.value = {
      full_name: authStore.user?.name || '',
      phone: '',
      address: '',
      city: '',
      postal_code: '',
      country: 'Maroc'
    }
  }
  editingAddress.value = true
}

const cancelEditingAddress = () => {
  editingAddress.value = false
  addressError.value = ''
}

const saveAddress = async () => {
  addressLoading.value = true
  addressError.value = ''

  try {
    const response = await shippingAddressService.saveAddress(addressForm.value)
    
    shippingAddress.value = response.address
    editingAddress.value = false
    
    showNotification(response.message || 'Adresse enregistrée avec succès!')
  } catch (error) {
    console.error('Erreur sauvegarde adresse:', error)
    addressError.value = error.response?.data?.message || 'Erreur lors de la sauvegarde'
  } finally {
    addressLoading.value = false
  }
}

const handleCancelOrder = async (orderId) => {
  const confirmed = await confirmDelete('cette commande')
  if (!confirmed) {
    return
  }

  try {
    await orderService.cancelOrder(orderId)
    showNotification('Commande annulée avec succès')
  } catch (error) {
    console.error('Erreur annulation:', error)
    const message = error.response?.data?.message || 'Erreur lors de l\'annulation'
    showNotification(message, 'error')
  }
}

const handleLogout = async () => {
  await authStore.logout()
  router.push('/login')
}

// Favorites methods
const fetchFavorites = async () => {
  try {
    favoritesLoading.value = true
    favoritesError.value = ''
    const response = await favoritesService.getFavorites()
    favoritesList.value = response.data || []
    favoriteCount.value = favoritesList.value.length
  } catch (err) {
    favoritesError.value = 'Impossible de charger vos favoris'
    console.error('Error fetching favorites:', err)
  } finally {
    favoritesLoading.value = false
  }
}

const removeFavorite = async (favoriteId) => {
  try {
    await favoritesService.removeFavorite(favoriteId)
    favoritesList.value = favoritesList.value.filter(f => f.id !== favoriteId)
    favoriteCount.value = favoritesList.value.length
    showNotification('Produit supprimé des favoris')
  } catch (err) {
    favoritesError.value = 'Impossible de supprimer le favori'
    console.error('Error removing favorite:', err)
  }
}

const clearAllFavorites = async () => {
  const confirmed = await confirmDelete('tous vos favoris')
  if (confirmed) {
    try {
      await favoritesService.clearAll()
      favoritesList.value = []
      favoriteCount.value = 0
      showNotification('Tous les favoris ont été supprimés')
    } catch (err) {
      favoritesError.value = 'Impossible de supprimer les favoris'
      console.error('Error clearing favorites:', err)
    }
  }
}

const goToProduct = (productId) => {
  router.push(`/products/${productId}`)
}

const getConditionLabel = (condition) => {
  const labels = {
    neuf: 'Neuf',
    excellent: 'Excellent',
    tres_bon: 'Très bon état',
    bon: 'Bon état',
    acceptable: 'État correct',
  }
  return labels[condition] || condition
}

const getConditionClass = (condition) => {
  const classes = {
    neuf: 'bg-green-100 text-green-800',
    excellent: 'bg-blue-100 text-blue-800',
    tres_bon: 'bg-indigo-100 text-indigo-800',
    bon: 'bg-yellow-100 text-yellow-800',
    acceptable: 'bg-orange-100 text-orange-800',
  }
  return classes[condition] || 'bg-gray-100 text-gray-800'
}

onMounted(() => {
  loadProfile()
  loadShippingAddress()
  fetchFavorites()
})
</script>

<style scoped>
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