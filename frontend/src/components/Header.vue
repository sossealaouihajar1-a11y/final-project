<template>
  <header class="bg-[#f7f6f3] border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-6 py-6">

      <!-- Ligne décorative -->
      <div class="flex items-center justify-center mb-4">
        <div class="flex-1 h-px bg-gray-400"></div>
        <span class="mx-6 text-gray-500 uppercase tracking-[0.3em] text-xs">
          Since 2025
        </span>
        <div class="flex-1 h-px bg-gray-400"></div>
      </div>

      <!-- Navigation principale -->
      <div class="flex items-center justify-between">

        <!-- Menu gauche -->
        <nav class="flex space-x-8 text-sm uppercase tracking-widest text-gray-700">
          <router-link to="/" class="hover:text-black transition">
            Home
          </router-link>
          <router-link to="/products" class="hover:text-black transition">
            Shop
          </router-link>
          <router-link to="/categories" class="hover:text-black transition">
            Categories
          </router-link>
        </nav>

        <!-- LOGO VINTAGE CENTRÉ -->
        <router-link
          to="/"
          class="flex flex-col items-center text-gray-900 group"
        >
          <div class="flex items-center font-serif">
            <span
              class="text-3xl uppercase tracking-[0.35em] transition-all duration-300 group-hover:tracking-[0.45em]"
            >
              Vintage
            </span>

            <span class="mx-4 text-gray-500 text-xl">✦</span>

            <span
              class="text-3xl uppercase tracking-[0.35em] transition-all duration-300 group-hover:tracking-[0.45em]"
            >
              Shop
            </span>
          </div>

          <span
            class="mt-1 text-[10px] uppercase tracking-[0.4em] text-gray-500"
          >
            Timeless goods
          </span>
        </router-link>

        <!-- Menu droit -->
        <nav class="flex space-x-8 text-sm uppercase tracking-widest text-gray-700">
          <router-link to="/about" class="hover:text-black transition">
            About
          </router-link>
          <router-link to="/contact" class="hover:text-black transition">
            Contact
          </router-link>
          <router-link to="/cart" class="hover:text-black transition">
            Cart
          </router-link>
        </nav>

      </div>
    </div>
  </header>
</template>

<script setup>
// Pas de logique JS nécessaire
</script>
