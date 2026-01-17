<template>
  <div class="bg-white min-h-screen flex flex-col">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Panier
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Mon Panier
        </h1>
        <p class="text-gray-600 max-w-2xl">
          {{ cartStore.itemCount }} article(s) dans votre panier
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">
    <!-- Titre -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center space-x-3">
          <svg class="w-8 h-8 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span>Votre panier</span>
        </h1>
      </div>

      <!-- Panier vide -->
      <div v-if="cartStore.isEmpty" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre panier est vide</h2>
        <p class="text-gray-600 mb-6">Découvrez nos produits vintage uniques</p>
        <router-link to="/products" class="inline-block border border-[#8b1c3d] px-10 py-3 text-[#8b1c3d] uppercase tracking-wider text-sm hover:bg-[#8b1c3d] hover:text-white transition">
          Parcourir la boutique
        </router-link>
      </div>

      <!-- Panier avec articles -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des articles -->
        <div class="lg:col-span-2 space-y-4">
          <div
            v-for="item in cartStore.items"
            :key="item.id"
            class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition"
          >
            <div class="flex flex-col sm:flex-row">
              <!-- Image -->
              <div class="sm:w-48 h-48 bg-gray-100 flex items-center justify-center flex-shrink-0">
                <img
                  v-if="item.image_url"
                  :src="item.image_url"
                  :alt="item.title"
                  class="w-full h-full object-cover"
                />
                <svg v-else class="w-16 h-16 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </div>

              <!-- Contenu -->
              <div class="flex-1 p-6">
                <div class="flex justify-between items-start mb-4">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ item.title }}</h3>
                    <p class="text-sm text-gray-600">{{ item.category }}</p>
                  </div>
                  <button
                    @click="removeItem(item.id)"
                    class="text-red-500 hover:text-red-700 transition"
                    title="Supprimer"
                  >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                  <!-- Prix -->
                  <div class="mb-4 sm:mb-0">
                    <div class="text-2xl font-bold text-[#8b1c3d]">
                      {{ (item.price * item.quantity).toFixed(2) }}€
                    </div>
                    <div class="text-sm text-gray-500">
                      {{ item.price }}€ × {{ item.quantity }}
                    </div>
                  </div>

                  <!-- Quantité -->
                  <div class="flex items-center space-x-3 bg-gray-50 rounded-lg p-2 border border-gray-200">
                    <button
                      @click="decrementQuantity(item.id)"
                      class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-gray-100 transition font-bold border border-gray-300"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                    <span class="font-semibold text-lg min-w-[2rem] text-center">{{ item.quantity }}</span>
                    <button
                      @click="incrementQuantity(item.id)"
                      :disabled="item.quantity >= item.stock"
                      class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-gray-100 transition font-bold disabled:opacity-50 disabled:cursor-not-allowed border border-gray-300"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Stock -->
                <div class="mt-3 text-sm flex items-center space-x-2" :class="item.quantity >= item.stock ? 'text-orange-600' : 'text-gray-600'">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                  </svg>
                  <span>Stock disponible: {{ item.stock }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

            <h2 class="text-xl font-bold text-gray-900 mb-6">Récapitulatif</h2>

            <!-- Price Details -->
            <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
              <div class="flex justify-between text-gray-600">
                <span>Sous-total</span>
                <span class="font-medium">{{ subtotal.toFixed(2) }}€</span>
              </div>
              <div class="flex justify-between text-gray-600">
                <span>Livraison</span>
                <span class="font-medium" :class="shippingCost === 0 ? 'text-[#8b1c3d]' : ''">
                  {{ shippingCost === 0 ? 'GRATUIT' : shippingCost.toFixed(2) + '€' }}
                </span>
              </div>

              <!-- Progress Bar -->
              <div v-if="subtotal < 400" class="pt-2">
                <div class="flex justify-between text-xs text-gray-600 mb-2">
                  <span>Livraison gratuite à partir de 400€</span>
                  <span class="font-medium">{{ (400 - subtotal).toFixed(2) }}€</span>
                </div>
                <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                  <div class="h-full bg-gradient-to-r from-[#8b1c3d] to-[#5a4a3a] transition-all duration-500" :style="{ width: `${(subtotal / 400) * 100}%` }"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Plus que {{ (400 - subtotal).toFixed(2) }}€ pour la livraison gratuite !</p>
              </div>
              <div v-else class="pt-2">
                <div class="flex items-center space-x-2 text-[#8b1c3d] text-sm font-medium">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                  <span>Vous bénéficiez de la livraison gratuite !</span>
                </div>
              </div>
            </div>

            <div class="flex justify-between text-xl font-bold text-gray-900 mb-6">
              <span>Total</span>
              <span class="text-[#8b1c3d]">{{ totalPrice.toFixed(2) }}€</span>
            </div>

            <!-- Adresse de livraison enregistrée -->
            <div v-if="hasShippingAddress" class="mb-6 p-4 bg-[#f2f1ed] rounded-lg border border-gray-200">
              <h3 class="font-semibold text-gray-900 mb-2 flex items-center space-x-2">
                <svg class="w-5 h-5 text-[#8b1c3d]" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                </svg>
                <span>Adresse de livraison</span>
              </h3>
              <p class="text-sm text-gray-700">
                {{ shippingAddress.full_name }}<br>
                {{ shippingAddress.address }}<br>
                {{ shippingAddress.postal_code }} {{ shippingAddress.city }}<br>
                {{ shippingAddress.country }}<br>
                Tél: {{ shippingAddress.phone }}
              </p>
              <router-link to="/client/dashboard" class="text-xs text-[#8b1c3d] hover:text-[#5a4a3a] mt-2 inline-block">
                Modifier l'adresse →
              </router-link>
            </div>

            <!-- Message si pas d'adresse -->
            <div v-else class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-lg">
              <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-orange-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                  <p class="text-sm font-semibold text-orange-800">Adresse de livraison manquante</p>
                  <p class="text-xs text-orange-700 mt-1">Vous devez enregistrer une adresse avant de commander.</p>
                  <router-link to="/client/dashboard" class="inline-block mt-2 text-xs text-orange-600 hover:text-orange-700 font-medium underline">
                    Ajouter une adresse →
                  </router-link>
                </div>
              </div>
            </div>

            <!-- ÉTAPE 1: Sélection de la méthode de paiement (AVANT confirmation) -->
            <div v-if="!orderConfirmed && hasShippingAddress" class="mb-6">
              <h3 class="text-sm font-semibold text-gray-900 mb-3">Méthode de paiement</h3>
              <div class="space-y-3">
                <!-- Cash on Delivery -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="paymentMethod === 'cash_on_delivery' ? 'border-[#8b1c3d] bg-[#f2f1ed]' : 'border-gray-200 hover:border-gray-300'">
                  <input
                    type="radio"
                    v-model="paymentMethod"
                    value="cash_on_delivery"
                    class="mt-1 h-4 w-4 text-[#8b1c3d] focus:ring-[#8b1c3d]"
                  />
                  <div class="ml-3 flex-1">
                    <div class="flex items-center space-x-2">
                      <svg class="w-5 h-5 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      <span class="font-medium text-gray-900">Paiement à la livraison</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Payez en espèces lors de la réception</p>
                  </div>
                </label>

                <!-- Stripe -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="paymentMethod === 'stripe' ? 'border-[#8b1c3d] bg-[#f2f1ed]' : 'border-gray-200 hover:border-gray-300'">
                  <input
                    type="radio"
                    v-model="paymentMethod"
                    value="stripe"
                    class="mt-1 h-4 w-4 text-[#8b1c3d] focus:ring-[#8b1c3d]"
                  />
                  <div class="ml-3 flex-1">
                    <div class="flex items-center space-x-2">
                      <svg class="w-5 h-5 text-[#8b1c3d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                      </svg>
                      <span class="font-medium text-gray-900">Carte bancaire (Stripe)</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Paiement en ligne sécurisé</p>
                  </div>
                </label>
              </div>
            </div>

            <!-- ÉTAPE 1: Bouton Confirmer (AVANT confirmation) -->
            <div v-if="!orderConfirmed">
              <button
                @click="proceedToCheckout"
                :disabled="!hasShippingAddress || processing || !paymentMethod"
                class="w-full px-6 py-4 bg-[#8b1c3d] text-white font-bold rounded-xl hover:bg-[#5a4a3a] transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
              >
                <svg v-if="!processing" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg v-else class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ processing ? 'Traitement...' : 'Confirmer la commande' }}</span>
              </button>
              <p class="text-xs text-gray-500 text-center mt-3">
                En confirmant, vous acceptez nos conditions générales de vente
              </p>
            </div>

            <!-- ÉTAPE 2: Après confirmation -->
            <div v-else>
              <!-- Si Cash on Delivery: Message de succès -->
              <div v-if="confirmedOrder?.payment_method === 'cash_on_delivery'" class="space-y-4">
                <div class="p-4 bg-green-50 border-2 border-green-500 rounded-xl">
                  <div class="flex items-center space-x-3 mb-3">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-bold text-green-800 text-lg">Commande confirmée !</p>
                      <p class="text-sm text-green-700">Paiement à la livraison</p>
                    </div>
                  </div>
                  <p class="text-sm text-green-700 mb-2">
                    Votre commande a été enregistrée avec succès.
                  </p>
                  <p class="text-sm text-green-700">
                    💰 Montant à régler à la livraison: <strong>{{ confirmedOrder.total_price.toFixed(2) }}€</strong>
                  </p>
                </div>
                <router-link to="/client/dashboard" class="block w-full px-6 py-4 bg-[#8b1c3d] text-white font-bold rounded-xl hover:bg-[#5a4a3a] transition text-center">
                  Voir mes commandes
                </router-link>
              </div>

              <!-- Si Stripe: Bouton Payer -->
              <div v-else-if="confirmedOrder?.payment_method === 'stripe'" class="space-y-4">
                <div class="p-4 bg-blue-50 border-2 border-blue-500 rounded-xl">
                  <div class="flex items-center space-x-3 mb-3">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                      <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                      </svg>
                    </div>
                    <div>
                      <p class="font-bold text-blue-800 text-lg">Commande confirmée !</p>
                      <p class="text-sm text-blue-700">En attente de paiement</p>
                    </div>
                  </div>
                  <p class="text-sm text-blue-700 mb-2">
                    Votre commande a été enregistrée. Veuillez procéder au paiement.
                  </p>
                  <p class="text-sm text-blue-700">
                    💳 Montant à payer: <strong>{{ confirmedOrder.total_price.toFixed(2) }}€</strong>
                  </p>
                </div>
                <button
                  @click="proceedToPayment"
                  :disabled="paymentProcessing"
                  class="w-full px-6 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition disabled:opacity-50 flex items-center justify-center space-x-2 shadow-lg hover:shadow-xl"
                >
                  <svg v-if="!paymentProcessing" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                  </svg>
                  <svg v-else class="animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <span>{{ paymentProcessing ? 'Traitement...' : 'Procéder au paiement' }}</span>
                </button>
              </div>
            </div>

            <!-- Informations complémentaires -->
            <div v-if="!orderConfirmed" class="mt-6 pt-6 border-t space-y-4">
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Paiement sécurisé</div>
                  <p>Vos données sont protégées</p>
                </div>
              </div>
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                  <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Livraison gratuite</div>
                  <p>À partir de 400€</p>
                </div>
              </div>
              <div class="flex items-start space-x-3 text-sm text-gray-600">
                <svg class="w-5 h-5 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                <div>
                  <div class="font-semibold text-gray-900 mb-1">Livraison rapide</div>
                  <p>Sous 3 à 5 jours ouvrés</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Toast Notification -->
    <transition name="slide-up">
      <div
        v-if="notification.show"
        class="fixed bottom-4 right-4 bg-white rounded-lg shadow-2xl p-4 max-w-sm z-50 border-l-4"
        :class="notification.type === 'error' ? 'border-red-500' : notification.type === 'info' ? 'border-blue-500' : 'border-green-500'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else-if="notification.type === 'info'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
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
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import cartService from '@/services/cartService'
import shippingAddressService from '@/services/shippingAddressService'
import Header from '@/components/Header.vue'

const router = useRouter()
const cartStore = useCartStore()

const processing = ref(false)
const shippingAddress = ref(null)
const paymentMethod = ref('cash_on_delivery')
const orderConfirmed = ref(false)
const confirmedOrder = ref(null)
const paymentProcessing = ref(false)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

// Computed property pour vérifier si une adresse existe
const hasShippingAddress = computed(() => {
  return shippingAddress.value !== null && 
         shippingAddress.value !== undefined && 
         typeof shippingAddress.value === 'object' &&
         Object.keys(shippingAddress.value).length > 0
})

// Calculs
const subtotal = computed(() => {
  return parseFloat(cartStore.totalPrice) || 0
})

const shippingCost = computed(() => {
  return subtotal.value >= 400 ? 0 : 5
})

const totalPrice = computed(() => {
  return subtotal.value + shippingCost.value
})

const showNotification = (message, type = 'success') => {
  notification.value = { show: true, message, type }
  setTimeout(() => {
    notification.value.show = false
  }, 3000)
}

const loadShippingAddress = async () => {
  try {
    const result = await shippingAddressService.getAddress()
    console.log('📍 Résultat getAddress:', result)
    
    // Vérifier si le résultat est valide
    if (result && typeof result === 'object' && Object.keys(result).length > 0) {
      shippingAddress.value = result
      console.log('✅ Adresse chargée avec succès:', shippingAddress.value)
    } else {
      shippingAddress.value = null
      console.log('⚠️ Aucune adresse trouvée')
    }
  } catch (error) {
    console.error('❌ Erreur lors du chargement de l\'adresse:', error)
    shippingAddress.value = null
  }
}

const removeItem = (productId) => {
  if (confirm('Voulez-vous retirer cet article du panier ?')) {
    cartStore.removeItem(productId)
    showNotification('Article retiré du panier')
  }
}

const incrementQuantity = (productId) => {
  const result = cartStore.incrementQuantity(productId)
  if (!result.success) {
    showNotification(result.message || 'Stock maximum atteint', 'error')
  }
}

const decrementQuantity = (productId) => {
  cartStore.decrementQuantity(productId)
}

const proceedToCheckout = async () => {
  console.log('🔍 === DÉBUT VÉRIFICATION CHECKOUT ===')
  console.log('Panier vide?', cartStore.isEmpty)
  console.log('Méthode de paiement:', paymentMethod.value)
  console.log('Adresse existe?', hasShippingAddress.value)
  console.log('Adresse complète:', shippingAddress.value)
  
  // Vérification 1: Panier
  if (cartStore.isEmpty) {
    showNotification('Votre panier est vide', 'error')
    return
  }

  // Vérification 2: Méthode de paiement
  if (!paymentMethod.value) {
    showNotification('Veuillez sélectionner une méthode de paiement', 'error')
    return
  }

  // Vérification 3: Adresse de livraison
  if (!hasShippingAddress.value) {
    console.log('❌ Pas d\'adresse - redirection vers dashboard')
    showNotification('Veuillez enregistrer une adresse de livraison dans votre compte', 'error')
    setTimeout(() => {
      router.push('/client/dashboard')
    }, 2000)
    return
  }

  console.log('✅ Toutes les vérifications passées - Création de la commande')
  processing.value = true

  try {
    const items = cartStore.items.map(item => ({
      product_id: item.id,
      quantity: item.quantity
    }))

    console.log('📦 Items envoyés:', items)
    console.log('💳 Méthode de paiement:', paymentMethod.value)

    const response = await cartService.checkout(items, paymentMethod.value)

    console.log('✅ Réponse du serveur:', response)

    // Sauvegarder la commande confirmée
    confirmedOrder.value = response.order

    // Marquer comme confirmé
    orderConfirmed.value = true

    // Vider le panier local
    cartStore.clearCart()

    showNotification('Commande créée avec succès !', 'success')

  } catch (error) {
    console.error('❌ Erreur lors de la commande:', error)
    console.error('Détails de l\'erreur:', error.response?.data)
    showNotification(
      error.response?.data?.message || 'Erreur lors de la création de la commande',
      'error'
    )
  } finally {
    processing.value = false
  }
}

const proceedToPayment = async () => {
  paymentProcessing.value = true
  
  try {
    // TODO: Intégrer Stripe ici plus tard
    showNotification('Paiement Stripe à venir...', 'info')
    
    // Pour l'instant, rediriger vers le dashboard après 2 secondes
    setTimeout(() => {
      router.push('/client/dashboard')
    }, 2000)
    
  } catch (error) {
    console.error('Erreur paiement:', error)
    showNotification('Erreur lors du paiement', 'error')
  } finally {
    paymentProcessing.value = false
  }
}

onMounted(async () => {
  console.log('🚀 Component monté - Chargement de l\'adresse')
  await loadShippingAddress()
  console.log('📊 État final après chargement:')
  console.log('  - hasShippingAddress:', hasShippingAddress.value)
  console.log('  - shippingAddress:', shippingAddress.value)
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