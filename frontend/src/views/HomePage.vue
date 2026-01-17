<template>
  <div class="bg-white">

    <!-- ================= NAVIGATION CATEGORIES (sous header) ================= -->
    <nav class="border-b border-gray-200 bg-white sticky top-0 z-40">
      <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-center gap-8 py-4 overflow-x-auto">
          <router-link
            v-for="category in categories"
            :key="category"
            :to="`/products?category=${encodeURIComponent(category)}`"
            class="text-sm uppercase tracking-wider text-gray-700 hover:text-[#8b1c3d] transition-colors whitespace-nowrap font-medium"
          >
            {{ formatCategoryName(category) }}
          </router-link>
        </div>
      </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="relative h-[75vh]">
      <img
        src="/images/hero-vintage.png"
        alt="Vintage Collection"
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-black/30"></div>

      <div class="relative z-10 h-full flex items-center justify-center text-center px-6">
        <div>
          <p class="uppercase tracking-[0.3em] text-sm text-white/80 mb-6">
            Timeless Vintage Pieces
          </p>
          <h1 class="text-4xl md:text-6xl font-serif text-white mb-8">
            The Latest Arrivals
          </h1>

          <router-link
            to="/products"
            class="inline-block border border-white px-10 py-3 text-white uppercase tracking-wider text-sm hover:bg-white hover:text-black transition"
          >
            Shop Collection
          </router-link>
        </div>
      </div>
    </section>

    <!-- ================= SHOP BY STYLE / VINTAGE FINDS ================= -->
    <section class="py-32 bg-[#eef0ef]">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-3 gap-16 items-center">

        <!-- Image gauche -->
        <div class="overflow-hidden">
          <img
            src="/images/style-left.png"
            alt="Vintage Interior"
            class="w-full h-[500px] object-cover"
          />
        </div>

        <!-- Contenu central -->
        <div class="text-center px-4">
          <p class="uppercase tracking-[0.35em] text-xs text-gray-600 mb-6">
            Shop by style
          </p>

          <h2 class="font-serif text-4xl md:text-5xl text-gray-900 leading-tight mb-6">
            Vintage Finds<br />
            <span class="italic font-normal">that find you</span>
          </h2>

          <p class="text-gray-600 mb-10 max-w-md mx-auto leading-relaxed">
            Find your style destination with our curated collections.
          </p>

          <router-link
            to="/products"
            class="inline-block border border-black px-10 py-3 uppercase tracking-widest text-sm hover:bg-black hover:text-white transition"
          >
            Visit the style shop
          </router-link>
        </div>

        <!-- Image droite -->
        <div>
          <div class="overflow-hidden">
            <img
              src="/images/style-right.png"
              alt="Mid Century Style"
              class="w-full h-[360px] object-cover"
            />
          </div>

          <div class="mt-6">
            <p class="uppercase tracking-widest text-sm font-medium">
              Mid-century
            </p>
            <router-link
              to="/products?style=mid-century"
              class="text-xs uppercase tracking-[0.3em] text-gray-700 hover:underline"
            >
              Shop now
            </router-link>
          </div>
        </div>

      </div>
    </section>

    <!-- ================= SHOP BY CATEGORY (Style Vintage Keepers - Horizontal) ================= -->
    <section class="py-12 bg-[#faf9f5]">
      <div class="max-w-[1400px] mx-auto px-6">
        <!-- Titre centré -->
        <div class="text-center mb-10">
          <p class="uppercase tracking-[0.35em] text-[10px] text-gray-500 mb-3">
            INTRODUCING THE ONLINE VINTAGE DESTINATION
          </p>
          <h2 class="text-xl md:text-2xl lg:text-3xl font-serif text-gray-800 leading-tight max-w-3xl mx-auto mb-4" style="font-weight: 300;">
            Connecting vintage lovers around the world with a 
            <span class="italic">fresh, fun,</span> and 
            <span class="italic">artfully curated</span> online marketplace.
          </h2>
        </div>

        <!-- Scroll horizontal -->
        <div class="relative -mx-6 px-6">
          <!-- Gradient edges -->
          <div class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-[#faf9f5] to-transparent z-10 pointer-events-none"></div>
          <div class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-[#faf9f5] to-transparent z-10 pointer-events-none"></div>
          
          <!-- Container horizontal -->
          <div class="flex gap-3 overflow-x-auto scroll-smooth scrollbar-hide pb-2">
            <router-link
              v-for="cat in categoryImages"
              :key="cat.name"
              :to="`/products?category=${encodeURIComponent(cat.name)}`"
              class="group relative overflow-hidden flex-shrink-0 w-48 h-60 bg-[#e8e6df]"
            >
              <!-- Image -->
              <img
                :src="cat.image"
                :alt="formatCategoryName(cat.name)"
                class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
              />
              
              <!-- Overlay avec dégradé subtil -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-70 group-hover:opacity-80 transition-opacity duration-300"></div>
              
              <!-- Texte en bas -->
              <div class="absolute inset-x-0 bottom-0 p-3 text-white">
                <h3 class="text-xs font-sans uppercase tracking-[0.2em] text-center" style="font-weight: 400; letter-spacing: 0.15em;">
                  {{ formatCategoryName(cat.name) }}
                </h3>
              </div>

              <!-- Hover indicator subtil -->
              <div class="absolute top-2 right-2 w-5 h-5 border border-white/60 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
              </div>
            </router-link>
          </div>
        </div>

        <!-- Bouton "Our Story" -->
        <div class="text-center mt-8">
          <router-link
            to="/about"
            class="inline-block border-b border-gray-800 pb-1 uppercase tracking-[0.25em] text-[11px] font-medium text-gray-800 hover:text-gray-600 transition-colors"
          >
            Our Story
          </router-link>
        </div>
      </div>
    </section>

    <!-- ================= DISCOVER OUR COLLECTION (Carousel Scrollable) ================= -->
    <section class="py-20 bg-[#f7f6f3] overflow-hidden">
      <div class="max-w-7xl mx-auto px-6">
        <div class="mb-10">
          <!-- Titre à gauche -->
          <h2 class="text-3xl md:text-4xl font-serif text-gray-900">
            Discover Our Collection
          </h2>
        </div>

        <!-- Loading -->
        <div v-if="loadingProducts" class="text-center py-20">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-gray-300 border-t-[#8b1c3d]"></div>
        </div>

        <!-- Carousel Container -->
        <div v-else class="relative -mx-6 px-6">
          <!-- Gradient Edges -->
          <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-[#f7f6f3] to-transparent z-10 pointer-events-none"></div>
          <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-[#f7f6f3] to-transparent z-10 pointer-events-none"></div>
          
          <!-- Scrollable Container -->
          <div
            ref="carouselContainer"
            @scroll="updateScrollPosition"
            @mouseenter="handleMouseEnter"
            @mouseleave="handleMouseLeave"
            class="flex gap-6 overflow-x-auto scroll-smooth scrollbar-hide pb-4"
            style="scroll-snap-type: x mandatory;"
          >
            <div
              v-for="(product, index) in allProducts"
              :key="product.id"
              class="flex-shrink-0 w-[240px] group relative"
              style="scroll-snap-align: start;"
              :style="{ animationDelay: `${index * 0.08}s` }"
              :class="'animate-fadeInUp'"
            >
              <!-- Container image -->
              <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
                <!-- Image -->
                <img
                  :src="product.image_url"
                  :alt="product.title"
                  class="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
                />

                <!-- Overlay hover -->
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-500"></div>

                <!-- Quick view button -->
                <div class="absolute inset-x-0 bottom-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                  <router-link
                    :to="`/products/${encodeTitle(product.title)}`"
                    class="block w-full bg-white/95 backdrop-blur-sm text-center py-2.5 text-xs uppercase tracking-wider font-medium hover:bg-[#8b1c3d] hover:text-white transition-colors"
                  >
                    Quick View
                  </router-link>
                </div>
              </div>

              <!-- Infos produit (uniquement le titre) -->
              <div class="mt-3 text-center">
                <router-link
                  :to="`/products/${encodeTitle(product.title)}`"
                  class="block"
                >
                  <h3 class="font-serif text-sm text-gray-900 hover:text-[#8b1c3d] transition-colors line-clamp-2">
                    {{ product.title }}
                  </h3>
                </router-link>
              </div>
            </div>
          </div>

          <!-- Scroll Indicator (optional) -->
          <div class="flex justify-center gap-1.5 mt-8">
            <div
              v-for="i in Math.ceil(allProducts.length / 5)"
              :key="i"
              class="h-1 rounded-full transition-all duration-300"
              :class="currentPage === i - 1 ? 'w-6 bg-[#8b1c3d]' : 'w-1 bg-gray-300'"
            ></div>
          </div>
        </div>

        <!-- Bouton voir plus -->
        <div class="text-center mt-10">
          <router-link
            to="/products"
            class="inline-block border border-black px-8 py-2.5 uppercase tracking-wider text-xs hover:bg-black hover:text-white transition-all duration-300"
          >
            View All Products
          </router-link>
        </div>
      </div>
    </section>

    <!-- ================= INSPIRATION ================= -->
    <section class="py-24 bg-[#e6e7e4]">
      <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        <div>
          <p class="uppercase tracking-[0.3em] text-sm text-gray-600 mb-4">
            Inspiration
          </p>
          <h3 class="text-4xl font-serif mb-6">
            #VintageFinds
          </h3>
          <p class="text-gray-700 mb-8 leading-relaxed">
            Une sélection de pièces uniques et intemporelles, pensées pour les amateurs
            de vintage authentique et d'élégance durable.
          </p>

          <a
            href="#"
            class="inline-block border border-black px-8 py-3 uppercase tracking-wider text-sm hover:bg-black hover:text-white transition"
          >
            Follow Us
          </a>
        </div>

        <div class="grid grid-cols-3 gap-4">
          <img src="/images/inspo1.png" class="h-40 object-cover w-full" />
          <img src="/images/inspo2.jpeg" class="h-40 object-cover w-full" />
          <img src="/images/inspo3.jpg" class="h-40 object-cover w-full" />
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import productService from '@/services/productService'

const categories = ref([])
const allProducts = ref([])
const loadingProducts = ref(false)

// Carousel refs
const carouselContainer = ref(null)
const scrollPosition = ref(0)
const autoScrollInterval = ref(null)
const isHovering = ref(false)

// Images pour les catégories (à adapter selon vos vraies catégories)
const categoryImages = ref([
  { name: 'mode', image: '/images/category-mode.jpg' },
  { name: 'mobilier', image: '/images/category-mobilier.jpg' },
  { name: 'accessoires', image: '/images/category-accessoires.jpg' },
  { name: 'electronique_vintage', image: '/images/category-electronique-vintage.jpg' },
  { name: 'art', image: '/images/category-art.jpg' },
  { name: 'autre', image: '/images/category-autre.jpg' },
])

// Traduction des noms de catégories pour affichage
const formatCategoryName = (category) => {
  const translations = {
    'mode': 'Mode',
    'mobilier': 'Mobilier',
    'accessoires': 'Accessoires',
    'electronique_vintage': 'Électronique Vintage',
    'art': 'Art',
    'autre': 'Autre'
  }
  return translations[category] || category
}

// Encoder le titre pour l'URL
const encodeTitle = (title) =>
  encodeURIComponent(
    title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-')
  )

// Computed pour le scroll max
const maxScroll = computed(() => {
  if (!carouselContainer.value) return 0
  return carouselContainer.value.scrollWidth - carouselContainer.value.clientWidth
})

// Computed pour la page actuelle (pour l'indicateur)
const currentPage = computed(() => {
  if (!carouselContainer.value) return 0
  const itemWidth = 240 + 24 // largeur + gap
  return Math.round(scrollPosition.value / (itemWidth * 5))
})

// Méthodes de scroll
const scrollLeft = () => {
  if (carouselContainer.value) {
    const scrollAmount = carouselContainer.value.clientWidth * 0.8
    carouselContainer.value.scrollBy({
      left: -scrollAmount,
      behavior: 'smooth'
    })
  }
}

const scrollRight = () => {
  if (carouselContainer.value) {
    const scrollAmount = carouselContainer.value.clientWidth * 0.8
    carouselContainer.value.scrollBy({
      left: scrollAmount,
      behavior: 'smooth'
    })
  }
}

const updateScrollPosition = () => {
  if (carouselContainer.value) {
    scrollPosition.value = carouselContainer.value.scrollLeft
  }
}

// Auto-scroll automatique
const startAutoScroll = () => {
  stopAutoScroll() // Clear any existing interval
  
  autoScrollInterval.value = setInterval(() => {
    if (!isHovering.value && carouselContainer.value) {
      // Si on est à la fin, revenir au début
      if (scrollPosition.value >= maxScroll.value - 10) {
        carouselContainer.value.scrollTo({
          left: 0,
          behavior: 'smooth'
        })
      } else {
        // Sinon, scroller vers la droite
        scrollRight()
      }
    }
  }, 5000) // 5 secondes
}

const stopAutoScroll = () => {
  if (autoScrollInterval.value) {
    clearInterval(autoScrollInterval.value)
    autoScrollInterval.value = null
  }
}

const handleMouseEnter = () => {
  isHovering.value = true
}

const handleMouseLeave = () => {
  isHovering.value = false
}

// Charger les catégories
const loadCategories = async () => {
  try {
    const data = await productService.getCategories()
    console.log('📦 Catégories reçues de l\'API:', data)
    
    // FALLBACK : Si vide ou null, utiliser les catégories par défaut
    if (!data || data.length === 0) {
      console.log('⚠️ API retourne vide, utilisation des catégories par défaut')
      categories.value = [
        'mode',
        'mobilier',
        'accessoires',
        'electronique_vintage',
        'art',
        'autre'
      ]
    } else {
      categories.value = data
    }
    
    // Mettre à jour categoryImages avec les vraies catégories
    categoryImages.value = categories.value.map(cat => ({
      name: cat,
      image: `/images/category-${cat.replace(/_/g, '-')}.jpg`
    }))
    
    console.log('✅ Categories finales:', categories.value)
    console.log('✅ CategoryImages:', categoryImages.value)
  } catch (error) {
    console.error('❌ Error loading categories:', error)
    
    // FALLBACK en cas d'erreur
    categories.value = [
      'mode',
      'mobilier',
      'accessoires',
      'electronique_vintage',
      'art',
      'autre'
    ]
    
    categoryImages.value = categories.value.map(cat => ({
      name: cat,
      image: `/images/category-${cat.replace(/_/g, '-')}.jpg`
    }))
  }
}

// Charger tous les produits
const loadProducts = async () => {
  loadingProducts.value = true
  try {
    const data = await productService.getAllProducts({ per_page: 12 })
    allProducts.value = data.data || data || []
  } catch (error) {
    console.error('Error loading products:', error)
  } finally {
    loadingProducts.value = false
    
    // Démarrer l'auto-scroll une fois les produits chargés
    setTimeout(() => {
      startAutoScroll()
    }, 1000)
  }
}

onMounted(() => {
  loadCategories()
  loadProducts()
})

onUnmounted(() => {
  stopAutoScroll()
})
</script>

<style scoped>
/* Animation fade in up pour les produits */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out forwards;
  opacity: 0;
}

/* Masquer la scrollbar mais garder le scroll */
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;  /* Chrome, Safari and Opera */
}

/* Line clamp pour limiter le texte à 2 lignes */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}
</style>