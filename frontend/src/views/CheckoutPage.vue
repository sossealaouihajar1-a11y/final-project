<template>
  <div class="min-h-screen flex flex-col bg-[#f8f5ef] text-[#2a2a28] font-serif">
    <!-- Header -->
    <Header />

    <!-- ================= HERO ================= -->
    <section class="bg-[#fbfaf7] border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-5 text-center">
        <p class="uppercase tracking-widest text-xs text-[#6b7b4b] mb-2">
          Validation de commande
        </p>
        <h3 class="text-2xl md:text-3xl font-bold text-[#4a3728] mb-2">
          Valider votre commande
        </h3>
        <p class="text-[#5a564f] text-lg">
          Complétez votre achat en quelques étapes simples
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">
      <!-- Progress Steps -->
      <div class="mb-12">
        <div class="flex items-center justify-center gap-2 flex-wrap md:flex-nowrap">
          <!-- Step 1 -->
          <div class="flex flex-col items-center" :class="currentStep >= 1 ? 'text-[#8b5e3c]' : 'text-[#c1bdae]'">
            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center font-semibold text-lg" :class="currentStep >= 1 ? 'border-[#8b5e3c] bg-[#fbfaf7]' : 'border-[#d6cdbf] bg-[#f1eee6]'">
              1
            </div>
            <p class="text-xs uppercase tracking-widest mt-2 font-medium">Adresse</p>
          </div>
          
          <!-- Line 1 -->
          <div class="flex-1 h-1 mx-2 md:mx-4 mb-8" :class="currentStep >= 2 ? 'bg-[#8b5e3c]' : 'bg-[#d6cdbf]'"></div>
          
          <!-- Step 2 -->
          <div class="flex flex-col items-center" :class="currentStep >= 2 ? 'text-[#8b5e3c]' : 'text-[#c1bdae]'">
            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center font-semibold text-lg" :class="currentStep >= 2 ? 'border-[#8b5e3c] bg-[#fbfaf7]' : 'border-[#d6cdbf] bg-[#f1eee6]'">
              2
            </div>
            <p class="text-xs uppercase tracking-widest mt-2 font-medium">Paiement</p>
          </div>
          
          <!-- Line 2 -->
          <div class="flex-1 h-1 mx-2 md:mx-4 mb-8" :class="currentStep >= 3 ? 'bg-[#8b5e3c]' : 'bg-[#d6cdbf]'"></div>
          
          <!-- Step 3 -->
          <div class="flex flex-col items-center" :class="currentStep >= 3 ? 'text-[#8b5e3c]' : 'text-[#c1bdae]'">
            <div class="w-12 h-12 rounded-full border-2 flex items-center justify-center font-semibold text-lg" :class="currentStep >= 3 ? 'border-[#8b5e3c] bg-[#fbfaf7]' : 'border-[#d6cdbf] bg-[#f1eee6]'">
              3
            </div>
            <p class="text-xs uppercase tracking-widest mt-2 font-medium">Confirmation</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
          <!-- STEP 1: Shipping Address -->
          <div v-if="currentStep === 1" class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] overflow-hidden">
            <div class="p-8">
              <h2 class="text-3xl font-bold text-[#4a3728] mb-8">Adresse de livraison</h2>

              <!-- Option to use existing address -->
              <div class="mb-8">
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="useExistingAddress ? 'border-[#8b5e3c] bg-[#f8f5ef]' : 'border-[#d6cdbf] hover:border-[#c1bdae]'">
                  <input
                    type="radio"
                    v-model="useExistingAddress"
                    :value="true"
                    class="mt-1 h-4 w-4 text-[#8b5e3c]"
                  />
                  <div class="ml-3 flex-1">
                    <p class="font-semibold text-[#4a3728] uppercase tracking-wider text-sm">Utiliser mon adresse enregistrée</p>
                    <p class="text-sm text-[#5a564f] mt-2 normal-case" v-if="existingAddress">
                      {{ existingAddress.full_name }}, {{ existingAddress.address }}, {{ existingAddress.postal_code }} {{ existingAddress.city }}
                    </p>
                    <p class="text-sm text-red-600 mt-2" v-else>
                      Aucune adresse enregistrée
                    </p>
                  </div>
                </label>
              </div>

              <!-- Option to add new address -->
              <div v-if="existingAddress" class="mb-8">
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="!useExistingAddress ? 'border-[#8b5e3c] bg-[#f8f5ef]' : 'border-[#d6cdbf] hover:border-[#c1bdae]'">
                  <input
                    type="radio"
                    v-model="useExistingAddress"
                    :value="false"
                    class="mt-1 h-4 w-4 text-[#8b5e3c]"
                  />
                  <div class="ml-3 flex-1">
                    <p class="font-semibold text-[#4a3728] uppercase tracking-wider text-sm">Utiliser une autre adresse</p>
                  </div>
                </label>
              </div>

              <!-- Address Form -->
              <div v-if="!useExistingAddress || !existingAddress" class="space-y-4 mt-6 border-t border-[#d6cdbf] pt-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Nom complet</label>
                    <input
                      v-model="shippingForm.full_name"
                      type="text"
                      class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                      placeholder="Votre nom"
                    />
                    <p v-if="errors.full_name" class="text-red-600 text-xs mt-1">{{ errors.full_name }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Téléphone</label>
                    <input
                      v-model="shippingForm.phone"
                      type="tel"
                      class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                      placeholder="+212..."
                    />
                    <p v-if="errors.phone" class="text-red-600 text-xs mt-1">{{ errors.phone }}</p>
                  </div>
                </div>

                <div>
                  <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Adresse</label>
                  <input
                    v-model="shippingForm.address"
                    type="text"
                    class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                    placeholder="Rue, numéro"
                  />
                  <p v-if="errors.address" class="text-red-600 text-xs mt-1">{{ errors.address }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Code postal</label>
                    <input
                      v-model="shippingForm.postal_code"
                      type="text"
                      class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                      placeholder="30000"
                    />
                    <p v-if="errors.postal_code" class="text-red-600 text-xs mt-1">{{ errors.postal_code }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Ville</label>
                    <input
                      v-model="shippingForm.city"
                      type="text"
                      class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                      placeholder="Fès"
                    />
                    <p v-if="errors.city" class="text-red-600 text-xs mt-1">{{ errors.city }}</p>
                  </div>
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Pays</label>
                    <input
                      v-model="shippingForm.country"
                      type="text"
                      class="w-full border border-[#d6cdbf] bg-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#8b5e3c] focus:border-transparent transition"
                      placeholder="Maroc"
                    />
                    <p v-if="errors.country" class="text-red-600 text-xs mt-1">{{ errors.country }}</p>
                  </div>
                </div>
              </div>

              <!-- Navigation Buttons -->
              <div class="flex justify-between mt-8 border-t border-[#d6cdbf] pt-8">
                <router-link to="/cart" class="px-8 py-3 border-2 border-[#8b5e3c] text-[#8b5e3c] rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#8b5e3c] hover:text-white transition">
                  Retour au panier
                </router-link>
                <button
                  @click="validateAddressAndNext"
                  class="px-8 py-3 bg-[#8b5e3c] text-white rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#a06a47] transition shadow-lg hover:shadow-xl"
                >
                  Continuer vers le paiement →
                </button>
              </div>
            </div>
          </div>

          <!-- STEP 2: Payment -->
          <div v-if="currentStep === 2" class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] overflow-hidden">
            <div class="p-8">
              <h2 class="text-3xl font-bold text-[#4a3728] mb-8">Méthode de paiement</h2>

              <!-- Payment Method Selection -->
              <div class="space-y-4 mb-8">
                <!-- Cash on Delivery -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="paymentMethod === 'cash_on_delivery' ? 'border-[#6b7b4b] bg-[#f8faf7]' : 'border-[#d6cdbf] hover:border-[#c1bdae]'">
                  <input
                    type="radio"
                    v-model="paymentMethod"
                    value="cash_on_delivery"
                    class="mt-1 h-4 w-4 text-[#6b7b4b]"
                  />
                  <div class="ml-3 flex-1">
                    <div class="flex items-center space-x-2">
                      <svg class="w-5 h-5 text-[#6b7b4b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                      </svg>
                      <span class="font-semibold text-[#4a3728] uppercase tracking-wider text-sm">Paiement à la livraison</span>
                    </div>
                    <p class="text-sm text-[#5a564f] mt-2">Payez en espèces lors de la réception de votre commande</p>
                  </div>
                </label>

                <!-- Stripe -->
                <label class="flex items-start p-4 border-2 rounded-lg cursor-pointer transition" :class="paymentMethod === 'stripe' ? 'border-[#8b5e3c] bg-[#f8f5ef]' : 'border-[#d6cdbf] hover:border-[#c1bdae]'">
                  <input
                    type="radio"
                    v-model="paymentMethod"
                    value="stripe"
                    class="mt-1 h-4 w-4 text-[#8b5e3c]"
                  />
                  <div class="ml-3 flex-1">
                    <div class="flex items-center space-x-2">
                      <svg class="w-5 h-5 text-[#8b5e3c]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                      </svg>
                      <span class="font-semibold text-[#4a3728] uppercase tracking-wider text-sm">Carte bancaire (Stripe)</span>
                    </div>
                    <p class="text-sm text-[#5a564f] mt-2">Paiement en ligne sécurisé par Stripe</p>
                  </div>
                </label>
              </div>

              <!-- Stripe Payment Form -->
              <div v-if="paymentMethod === 'stripe'" class="mb-8 p-6 bg-[#f8f5ef] rounded-lg border border-[#d6cdbf]">
                <h3 class="font-semibold text-[#4a3728] mb-4 uppercase tracking-widest text-sm">Informations de paiement</h3>
                
                <form @submit.prevent="handleStripePayment" class="space-y-4">
                  <!-- Stripe Elements Container -->
                  <div>
                    <label class="block text-xs font-semibold text-[#5a564f] mb-2 uppercase tracking-widest">Détails de la carte</label>
                    <div id="card-element" class="w-full border border-[#d6cdbf] rounded-lg px-4 py-3 bg-white"></div>
                    <div id="card-errors" role="alert" class="text-red-600 text-sm mt-2"></div>
                  </div>

                  <!-- Collapsible Test Info (toggle) -->
                  <div class="mt-4">
                    <button 
                      type="button"
                      @click="showTestInfo = !showTestInfo"
                      class="text-sm text-[#8b5e3c] hover:text-[#a06a47] font-semibold uppercase tracking-widest flex items-center"
                    >
                      <span v-if="showTestInfo">Masquer les cartes de test</span>
                      <span v-else>Afficher les cartes de test (mode développement)</span>
                      <svg 
                        class="w-4 h-4 ml-1 transition-transform" 
                        :class="showTestInfo ? 'rotate-180' : ''" 
                        fill="none" 
                        stroke="currentColor" 
                        viewBox="0 0 24 24"
                      >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                      </svg>
                    </button>

                    <div v-if="showTestInfo" class="mt-3 p-4 bg-[#fbfaf7] border border-[#d6cdbf] rounded-lg text-sm text-[#5a564f]">
                      <p class="font-semibold mb-2 text-[#4a3728]">Cartes de test Stripe (mode test uniquement) :</p>
                      <ul class="list-disc pl-5 space-y-1">
                        <li><strong>Succès</strong>: 4242 4242 4242 4242 – Toute date future – CVC quelconque</li>
                        <li><strong>Refus</strong>: 4000 0000 0000 0002</li>
                        <li><strong>3D Secure requis</strong>: 4000 0025 0000 3155</li>
                        <li><strong>Fonds insuffisants</strong>: 4000 0000 0000 9995</li>
                      </ul>
                      <p class="mt-3 text-xs italic text-[#5a564f]">Ces cartes ne fonctionnent qu'en mode test. N'utilisez jamais de vraies cartes ici.</p>
                    </div>
                  </div>
                  
                  <div class="flex justify-end mt-6">
                    <button type="submit" @click.prevent="handleStripePayment" class="px-8 py-3 bg-[#8b5e3c] text-white rounded-lg hover:bg-[#a06a47] font-bold uppercase tracking-wider text-sm transition shadow-lg hover:shadow-xl">Payer maintenant</button>
                  </div>
                </form>
              </div>

              <!-- Navigation Buttons -->
              <div class="flex justify-between border-t border-[#d6cdbf] pt-8">
                <button
                  @click="currentStep = 1"
                  class="px-8 py-3 border-2 border-[#8b5e3c] text-[#8b5e3c] rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#8b5e3c] hover:text-white transition"
                >
                  ← Retour à l'adresse
                </button>
                <button
                  @click="proceedToConfirmation"
                  :disabled="processingPayment || (paymentMethod === 'stripe' && !stripeLoaded)"
                  class="px-8 py-3 bg-[#8b5e3c] text-white rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#a06a47] transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2"
                >
                  <span v-if="!processingPayment">Confirmer le paiement {{ totalPrice.toFixed(2) }} MAD →</span>
                  <span v-else class="flex items-center space-x-2">
                    <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Traitement...</span>
                  </span>
                </button>
              </div>
            </div>
          </div>

          <!-- STEP 3: Confirmation -->
          <div v-if="currentStep === 3" class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] overflow-hidden">
            <div class="p-8 text-center">
              <div class="mb-6">
                <svg class="w-20 h-20 text-[#6b7b4b] mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <h2 class="text-3xl font-bold text-[#4a3728] mb-2">Commande confirmée!</h2>
              <p class="text-[#5a564f] mb-4">Numéro de commande: <span class="font-semibold text-[#8b5e3c]">#{{ confirmedOrderId }}</span></p>
              <p class="text-[#5a564f] mb-8">Un email de confirmation vous a été envoyé.</p>
              
              <div class="flex flex-col sm:flex-row justify-center gap-4">
                <router-link to="/client/dashboard" class="px-8 py-3 bg-[#8b5e3c] text-white rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#a06a47] transition shadow-lg hover:shadow-xl">
                  Voir mes commandes
                </router-link>
                <router-link to="/products" class="px-8 py-3 border-2 border-[#8b5e3c] text-[#8b5e3c] rounded-lg font-bold uppercase tracking-wider text-sm hover:bg-[#8b5e3c] hover:text-white transition">
                  Continuer shopping
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
          <div class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-6 sticky top-24">
            <h3 class="text-lg font-bold text-[#4a3728] mb-6 uppercase tracking-widest">Récapitulatif</h3>
            
            <div class="space-y-3 mb-6 border-b border-[#d6cdbf] pb-4">
              <div v-for="item in cartItems" :key="item.id" class="flex justify-between text-sm">
                <span class="text-[#5a564f]">{{ item.title }} × {{ item.quantity }}</span>
                <span class="font-medium text-[#4a3728]">{{ (item.price * item.quantity).toFixed(2) }} MAD</span>
              </div>
            </div>

            <div class="space-y-3 pb-4 border-b border-[#d6cdbf]">
              <div class="flex justify-between text-sm text-[#5a564f]">
                <span>Sous-total</span>
                <span class="text-[#4a3728] font-medium">{{ subtotal.toFixed(2) }} MAD</span>
              </div>
              <div class="flex justify-between text-sm text-[#5a564f]">
                <span>Frais de port</span>
                <span v-if="subtotal >= 400" class="text-[#6b7b4b] font-medium">Gratuit</span>
                <span v-else class="text-[#4a3728] font-medium">5.00 MAD</span>
              </div>
            </div>

            <div class="flex justify-between text-xl font-bold text-[#4a3728] mb-6 mt-4">
              <span>Total</span>
              <span class="text-[#8b5e3c]">{{ totalPrice.toFixed(2) }} MAD</span>
            </div>

            <div class="p-4 bg-[#f8faf7] rounded-lg border border-[#d6cdbf]">
              <p class="text-xs text-[#5a564f]">
                <span class="font-semibold text-[#4a3728]">Info:</span> Livraison gratuite à partir de 400 MAD
              </p>
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
        :class="notification.type === 'error' ? 'border-red-500' : notification.type === 'info' ? 'border-blue-500' : 'border-[#6b7b4b]'"
      >
        <div class="flex items-center space-x-3">
          <svg v-if="notification.type === 'success'" class="w-6 h-6 text-[#6b7b4b]" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-else-if="notification.type === 'info'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <p class="text-[#4a3728] font-medium">{{ notification.message }}</p>
        </div>
      </div>
    </transition>

    <Footer />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, onBeforeUnmount } from 'vue'
import { useRouter } from 'vue-router'
import { loadStripe } from '@stripe/stripe-js'
import { useCartStore } from '@/stores/cartStore'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

import cartService from '@/services/cartService'
import paymentService from '@/services/paymentService'
import shippingAddressService from '@/services/shippingAddressService'

const router = useRouter()
const cartStore = useCartStore()

const currentStep = ref(1)
const useExistingAddress = ref(true)
const existingAddress = ref(null)
const paymentMethod = ref('cash_on_delivery')
const processingPayment = ref(false)
const confirmedOrderId = ref(null)
const stripe = ref(null)
const cardElement = ref(null)
const stripeLoaded = ref(false)
const currentOrderId = ref(null)
const currentPaymentIntentId = ref(null)
const showTestInfo = ref(false)

const shippingForm = ref({
  full_name: '',
  phone: '',
  address: '',
  postal_code: '',
  city: '',
  country: 'Maroc'
})

const errors = ref({})

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

const cartItems = computed(() => cartStore.items)

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
    if (result && typeof result === 'object' && Object.keys(result).length > 0) {
      existingAddress.value = result
      useExistingAddress.value = true
    } else {
      useExistingAddress.value = false
    }
  } catch (error) {
    console.error('Erreur lors du chargement de l\'adresse:', error)
    useExistingAddress.value = false
  }
}

const initializeStripe = async () => {
  try {
    const stripePublicKey = import.meta.env.VITE_STRIPE_PUBLIC_KEY || process.env.VUE_APP_STRIPE_PUBLIC_KEY
    if (!stripePublicKey) {
      console.error('Stripe public key not configured')
      return
    }

    stripe.value = await loadStripe(stripePublicKey)
    if (!stripe.value) {
      throw new Error('Failed to load Stripe')
    }

    stripeLoaded.value = true
  } catch (error) {
    console.error('Error initializing Stripe:', error)
    showNotification('Erreur lors du chargement de Stripe', 'error')
  }
}

const setupCardElement = () => {
  if (!stripe.value || !stripeLoaded.value) return

  try {
    const elements = stripe.value.elements()
    cardElement.value = elements.create('card', {
      style: {
        base: {
          fontSize: '16px',
          color: '#424770',
          '::placeholder': {
            color: '#aab7c4',
          },
        },
        invalid: {
          color: '#fa755a',
          iconColor: '#fa755a',
        },
      },
    })

    const cardElementDiv = document.getElementById('card-element')
    if (cardElementDiv) {
      cardElement.value.mount('#card-element')

      // Handle card errors
      cardElement.value.addEventListener('change', (event) => {
        const displayError = document.getElementById('card-errors')
        if (displayError) {
          displayError.textContent = event.error ? event.error.message : ''
        }
      })
    }
  } catch (error) {
    console.error('Error setting up card element:', error)
  }
}

const validateAddress = () => {
  errors.value = {}
  
  if (useExistingAddress.value && existingAddress.value) {
    return true
  }

  if (!shippingForm.value.full_name) {
    errors.value.full_name = 'Nom requis'
  }
  if (!shippingForm.value.phone) {
    errors.value.phone = 'Téléphone requis'
  }
  if (!shippingForm.value.address) {
    errors.value.address = 'Adresse requise'
  }
  if (!shippingForm.value.postal_code) {
    errors.value.postal_code = 'Code postal requis'
  }
  if (!shippingForm.value.city) {
    errors.value.city = 'Ville requise'
  }
  if (!shippingForm.value.country) {
    errors.value.country = 'Pays requis'
  }

  return Object.keys(errors.value).length === 0
}

const validateAddressAndNext = () => {
  if (validateAddress()) {
    currentStep.value = 2
    if (paymentMethod.value === 'stripe' && !cardElement.value) {
      setTimeout(() => {
        setupCardElement()
      }, 0)
    }
  } else {
    showNotification('Veuillez remplir tous les champs requis', 'error')
  }
}

const createOrder = async () => {
  try {
    const items = cartItems.value.map(item => ({
      product_id: item.id,
      quantity: item.quantity
    }))

    const response = await cartService.checkout(items, 'stripe')
    return response.order.id
  } catch (error) {
    console.error('Erreur lors de la création de la commande:', error)
    throw error
  }
}

const handleStripePayment = async () => {
  if (!stripe.value || !cardElement.value) {
    showNotification('Stripe n\'est pas prêt', 'error')
    return
  }

  processingPayment.value = true

  try {
    const orderId = await createOrder()
    currentOrderId.value = orderId

    const intentData = await paymentService.createPaymentIntent(orderId)
    currentPaymentIntentId.value = intentData.payment_intent_id

    const { paymentIntent, error } = await stripe.value.confirmCardPayment(
      intentData.client_secret,
      {
        payment_method: {
          card: cardElement.value,
          billing_details: {
            name: useExistingAddress.value && existingAddress.value 
              ? existingAddress.value.full_name 
              : shippingForm.value.full_name,
          },
        },
      }
    )

    if (error) {
      showNotification(`Erreur de paiement: ${error.message}`, 'error')
      processingPayment.value = false
      return
    }

    if (paymentIntent.status === 'succeeded') {
      await paymentService.confirmPayment(paymentIntent.id, orderId)
      
      confirmedOrderId.value = orderId
      cartStore.clearCart()
      currentStep.value = 3
      showNotification('Paiement réussi! Commande confirmée!', 'success')
    } else if (paymentIntent.status === 'requires_action') {
      showNotification('Authentification 3D Secure requise', 'info')
    }
  } catch (error) {
    console.error('Erreur lors du paiement:', error)
    showNotification(
      error.response?.data?.message || 'Erreur lors du paiement',
      'error'
    )
  } finally {
    processingPayment.value = false
  }
}

const proceedToConfirmation = async () => {
  if (cartStore.isEmpty) {
    showNotification('Votre panier est vide', 'error')
    return
  }

  if (paymentMethod.value === 'stripe') {
    await handleStripePayment()
  } else {
    processingPayment.value = true

    try {
      const items = cartItems.value.map(item => ({
        product_id: item.id,
        quantity: item.quantity
      }))

      const response = await cartService.checkout(items, paymentMethod.value)
      
      confirmedOrderId.value = response.order.id
      cartStore.clearCart()
      currentStep.value = 3
      showNotification('Commande créée avec succès!', 'success')
    } catch (error) {
      console.error('Erreur lors de la commande:', error)
      showNotification(
        error.response?.data?.message || 'Erreur lors de la création de la commande',
        'error'
      )
    } finally {
      processingPayment.value = false
    }
  }
}

onMounted(async () => {
  if (cartStore.isEmpty) {
    showNotification('Votre panier est vide. Redirection...', 'info')
    setTimeout(() => {
      router.push('/cart')
    }, 2000)
  }
  loadShippingAddress()
  await initializeStripe()
})

watch(
  [currentStep, paymentMethod, stripeLoaded],
  ([step, method, loaded]) => {
    if (step === 2 && method === 'stripe' && loaded) {
      if (!cardElement.value) {
        setTimeout(() => {
          setupCardElement()
        }, 0)
      }
    } else {
      if (cardElement.value) {
        try {
          cardElement.value.unmount()
        } catch (e) {
          // ignore
        }
        cardElement.value = null
      }
    }
  }
)

onBeforeUnmount(() => {
  if (cardElement.value) {
    try {
      cardElement.value.unmount()
    } catch (e) {
      // ignore
    }
    cardElement.value = null
  }
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

#card-element {
  padding: 12px 0;
}
</style>