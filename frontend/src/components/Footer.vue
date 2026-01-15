<template>
  <footer class="bg-[#1f1f1f] text-gray-300">

    <!-- Ligne décorative -->
    <div class="border-t border-gray-600"></div>

    <div class="max-w-7xl mx-auto px-6 py-20">

      <div class="grid grid-cols-1 md:grid-cols-4 gap-12">

        <!-- LOGO / BRAND -->
        <div>
          <h3 class="font-serif text-2xl tracking-widest text-white mb-6">
            Vintage Shop
          </h3>
          <p class="text-sm leading-relaxed text-gray-400">
            Curated vintage pieces for timeless interiors and
            authentic style lovers.
          </p>
        </div>

        <!-- SHOP -->
        <div>
          <h4 class="uppercase tracking-widest text-xs text-gray-400 mb-6">
            Shop
          </h4>
          <ul class="space-y-4 text-sm">
            <li><router-link to="/products" class="hover:text-white">All Products</router-link></li>
            <li><router-link to="/categories" class="hover:text-white">Categories</router-link></li>
            <li><router-link to="/products?new=true" class="hover:text-white">New Arrivals</router-link></li>
            <li><router-link to="/products?sale=true" class="hover:text-white">Sale</router-link></li>
          </ul>
        </div>

        <!-- ABOUT -->
        <div>
          <h4 class="uppercase tracking-widest text-xs text-gray-400 mb-6">
            About
          </h4>
          <ul class="space-y-4 text-sm">
            <li><router-link to="/about" class="hover:text-white">Our Story</router-link></li>
            <li><router-link to="/contact" class="hover:text-white">Contact</router-link></li>
            <li><router-link to="/faq" class="hover:text-white">FAQ</router-link></li>
            <li><router-link to="/shipping" class="hover:text-white">Shipping & Returns</router-link></li>
          </ul>
        </div>

        <!-- NEWSLETTER -->
        <div>
          <h4 class="uppercase tracking-widest text-xs text-gray-400 mb-6">
            Newsletter
          </h4>

          <p class="text-sm text-gray-400 mb-6">
            Join our world of timeless vintage finds.
          </p>

          <form class="flex">
            <input
              type="email"
              placeholder="Your email"
              class="w-full bg-transparent border border-gray-500 px-4 py-3 text-sm focus:outline-none focus:border-white"
            />
            <button
              class="ml-2 border border-white px-6 py-3 uppercase tracking-widest text-xs text-white hover:bg-white hover:text-black transition"
            >
              Join
            </button>
          </form>
        </div>

      </div>

      <!-- Bas de footer -->
      <div class="mt-20 pt-8 border-t border-gray-600 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500">

        <p>
          © 2025 Vintage Shop. All rights reserved.
        </p>

       
      </div>

    </div>
  </footer>
</template>

<script setup>
// Pas de JS nécessaire
</script>
