<template>
  <div class="bg-white">

    <!-- ================= HERO ================= -->
    <section class="relative h-[85vh]">
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

    <!-- ================= LATEST PRODUCTS ================= -->
    <section class="py-24 bg-[#f7f6f3]">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-serif text-center mb-16">
          Latest Arrivals
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
          <div
            v-for="product in featuredProducts"
            :key="product.id"
            class="group"
          >
            <div class="overflow-hidden">
              <img
                :src="product.image"
                alt=""
                class="w-full h-80 object-cover transition-transform duration-500 group-hover:scale-105"
              />
            </div>

            <div class="mt-4 text-center">
              <h3 class="font-serif text-lg mb-1">
                {{ product.title }}
              </h3>
              <p class="text-sm text-gray-600">
                {{ product.price }} €
              </p>
            </div>
          </div>
        </div>

        <div class="text-center mt-16">
          <router-link
            to="/products"
            class="inline-block border border-black px-8 py-3 uppercase tracking-wider text-sm hover:bg-black hover:text-white transition"
          >
            View All Products
          </router-link>
        </div>
      </div>
    </section>

    <!-- ================= SHOP BY CATEGORY ================= -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-serif text-center mb-16">
          Shop by Style
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <router-link
  v-for="category in categories"
  :key="category"
  :to="`/products?category=${category}`"
  class="border border-gray-300 py-10 text-center font-serif text-lg hover:bg-gray-50 transition"
>
  {{ category }}
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
            de vintage authentique et d’élégance durable.
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

import { ref, onMounted } from 'vue'
import axios from 'axios'

const categories = ref([])

onMounted(async () => {
  const { data } = await axios.get('http://localhost:8000/api/categories')
  categories.value = data
})


const featuredProducts = [
  {
    id: 1,
    title: 'Vintage Leather Jacket',
    price: 120,
    image: '/images/product1.jpg',
  },
  {
    id: 2,
    title: 'Classic Denim Jacket',
    price: 95,
    image: '/images/product2.jpg',
  },
  {
    id: 3,
    title: 'Retro Wool Coat',
    price: 140,
    image: '/images/product3.jpg',
  },
  {
    id: 4,
    title: 'Vintage Handbag',
    price: 80,
    image: '/images/product4.png',
  },
]


</script>
