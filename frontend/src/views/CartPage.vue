<template>
  <div class="min-h-screen flex flex-col bg-[#f8f5ef] text-[#2a2a28] font-serif">

    <!-- Header -->
    <Header />

    <!-- HERO -->
    <section class="bg-[#fbfaf7] border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-5 text-center">
        <p class="uppercase tracking-widest text-xs text-[#6b7b4b] mb-2">
          Panier
        </p>
    
        <p class="text-[#5a564f] text-lg">
          {{ cartStore.itemCount }} article(s) dans votre panier
        </p>
      </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-6 py-16 flex-grow">

      <!-- Panier vide -->
      <div v-if="cartStore.isEmpty" class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-16 text-center">
        <svg class="w-24 h-24 mx-auto text-[#c1bdae] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h2 class="text-2xl font-bold text-[#4a3728] mb-2">Votre panier est vide</h2>
        <p class="text-[#5a564f] mb-6">Découvrez nos produits vintage uniques</p>
        <router-link to="/products" class="inline-block border border-[#8b5e3c] px-10 py-3 text-[#8b5e3c] uppercase tracking-wider text-sm hover:bg-[#8b5e3c] hover:text-white transition rounded-lg">
          Parcourir la boutique
        </router-link>
      </div>

      <!-- Panier avec articles -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Liste des articles -->
        <div class="lg:col-span-2 space-y-4">
          <div v-for="item in cartStore.items" :key="item.id" class="bg-[#fbfaf7] rounded-2xl shadow-md border border-[#d6cdbf] overflow-hidden hover:shadow-lg transition">
            
            <div class="flex flex-col sm:flex-row">
              <!-- Image -->
              <div class="sm:w-48 h-48 bg-[#f1eee6] flex items-center justify-center flex-shrink-0">
                <img v-if="item.image_url" :src="item.image_url" :alt="item.title" class="w-full h-full object-cover rounded-lg" />
                <svg v-else class="w-16 h-16 text-[#c1bdae]" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                </svg>
              </div>

              <!-- Contenu -->
              <div class="flex-1 p-6">
                <div class="flex justify-between items-start mb-4">
                  <div>
                    <h3 class="text-lg font-semibold text-[#4a3728] mb-1">{{ item.title }}</h3>
                    <p class="text-sm text-[#5a564f]">{{ item.category }}</p>
                  </div>
                  <button @click="removeItem(item.id)" class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                  <!-- Prix -->
                  <div class="mb-4 sm:mb-0">
                    <div class="text-2xl font-bold text-[#8b5e3c]">
                      {{ (item.price * item.quantity).toFixed(2) }}MAD
                    </div>
                    <div class="text-sm text-[#5a564f]">
                      {{ item.price }}MAD× {{ item.quantity }}
                    </div>
                  </div>

                  <!-- Quantité -->
                  <div class="flex items-center space-x-3 bg-[#f1eee6] rounded-lg p-2 border border-[#d6cdbf]">
                    <button @click="decrementQuantity(item.id)" class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-[#f5f3ed] transition font-bold border border-[#d6cdbf]">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                      </svg>
                    </button>
                    <span class="font-semibold text-lg min-w-[2rem] text-center">{{ item.quantity }}</span>
                    <button @click="incrementQuantity(item.id)" :disabled="item.quantity >= item.stock" class="w-8 h-8 flex items-center justify-center bg-white rounded-md hover:bg-[#f5f3ed] transition font-bold disabled:opacity-50 disabled:cursor-not-allowed border border-[#d6cdbf]">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Stock -->
                <div class="mt-3 text-sm flex items-center space-x-2" :class="item.quantity >= item.stock ? 'text-orange-600' : 'text-[#5a564f]'">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                  </svg>
                  <span>Stock disponible: {{ item.stock }}</span>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Récapitulatif -->
        <div class="bg-[#fbfaf7] rounded-2xl shadow-lg border border-[#d6cdbf] p-6 sticky top-8">
          <h2 class="text-xl font-bold text-[#4a3728] mb-6">Récapitulatif</h2>
          <div class="space-y-3 mb-6 pb-6 border-b border-[#d6cdbf]">
            <div class="flex justify-between text-[#5a564f]">
              <span>Sous-total</span>
              <span class="font-medium">{{ subtotal.toFixed(2) }} MAD</span>
            </div>
            <div class="flex justify-between text-[#5a564f]">
              <span>Livraison</span>
              <span class="font-medium" :class="shippingCost === 0 ? 'text-[#8b5e3c]' : ''">
                {{ shippingCost === 0 ? 'GRATUIT' : shippingCost.toFixed(2) + ' MAD' }}
              </span>
            </div>
          </div>

          <div class="flex justify-between text-xl font-bold text-[#4a3728] mb-6">
            <span>Total</span>
            <span class="text-[#8b5e3c]">{{ totalPrice.toFixed(2) }} MAD</span>
          </div>

          <button @click="proceedToCheckout" class="w-full px-6 py-4 bg-[#8b5e3c] text-white font-bold rounded-xl hover:bg-[#a06a47] transition shadow-lg hover:shadow-xl">
            Procéder au paiement
          </button>

        </div>
      </div>
    </main>

    <Footer />
  </div>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import { useConfirmDialog } from '@/composables/useConfirmDialog'
import cartService from '@/services/cartService'
import shippingAddressService from '@/services/shippingAddressService'
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'

const router = useRouter()
const cartStore = useCartStore()
const { confirmDelete } = useConfirmDialog()

const processing = ref(false)

const notification = ref({
  show: false,
  message: '',
  type: 'success'
})

// Removed: hasShippingAddress - no longer needed in cart

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

const removeItem = async (productId) => {
  const confirmed = await confirmDelete('cet article')
  if (confirmed) {
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
  if (cartStore.isEmpty) {
    showNotification('Votre panier est vide', 'error')
    return
  }

  // Redirect to checkout page
  router.push('/checkout')
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

onMounted(() => {
  // Component ready
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