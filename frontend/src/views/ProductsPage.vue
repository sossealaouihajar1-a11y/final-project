<template>
  <div class="bg-white">

    <!-- ================= HERO ================= -->
    <section class="bg-[#f2f1ed] border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-6 py-14">
        <p class="uppercase tracking-[0.35em] text-xs text-gray-500 mb-4">
          Shop
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-gray-900 mb-4">
          Vintage Collection
        </h1>
        <p class="text-gray-600 max-w-2xl">
          Discover timeless vintage pieces curated with care.
        </p>
      </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <main class="max-w-7xl mx-auto px-6 py-16">

      <!-- 🔵 NE PAS SUPPRIMER : TON FILTRAGE EXISTANT -->
      <!-- ⬇️ COLLE ICI EXACTEMENT TON CODE DE FILTRAGE -->
      <section class="mb-16">
        <!-- EXEMPLE :
        <ProductFilter
          :categories="categories"
          @filter="applyFilter"
        />
        -->
      </section>

      <!-- ================= PRODUITS ================= -->
      <section>

        <!-- Loading -->
        <div v-if="loading" class="py-20 text-center">
          <svg class="animate-spin h-10 w-10 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.37 0 0 5.37 0 12h4z"/>
          </svg>
          <p class="mt-4 text-gray-500">Loading products...</p>
        </div>

        <!-- Grille vintage -->
        <div
          v-else-if="products.length"
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-12 gap-y-20"
        >
          <article
            v-for="product in products"
            :key="product.id"
            class="group"
          >
            <!-- Image -->
            <div
              class="aspect-[3/4] bg-gray-100 overflow-hidden cursor-pointer"
              @click="goToProduct(product)"
            >
              <img
                :src="product.image_url"
                :alt="product.title"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              />
            </div>

            <!-- Infos -->
            <div class="mt-4">
              <h3
                class="font-serif text-base text-gray-900 mb-1 cursor-pointer hover:underline"
                @click="goToProduct(product)"
              >
                {{ product.title }}
              </h3>

              <div class="text-sm text-gray-700">
                <span
                  v-if="product.promotion > 0"
                  class="line-through text-gray-400 mr-2"
                >
                  {{ product.price }}€
                </span>
                <span class="font-medium">
                  {{ product.final_price }}€
                </span>
              </div>
            </div>
          </article>
        </div>

        <!-- Aucun produit -->
        <div v-else class="text-center py-20 text-gray-500">
          No products found
        </div>

      </section>
    </main>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import productService from '@/services/productService'

const router = useRouter()

const products = ref([])
const loading = ref(false)

/* Navigation produit */
const encodeTitle = (title) =>
  encodeURIComponent(
    title.toLowerCase().replace(/[^\w\s-]/g, '').replace(/\s+/g, '-')
  )

const goToProduct = (product) => {
  router.push(`/products/${encodeTitle(product.title)}`)
}

/* Chargement produits (lié à ton filtrage existant) */
const loadProducts = async () => {
  loading.value = true
  try {
    const res = await productService.getAllProducts({})
    products.value = res.data || []
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(loadProducts)
</script>
