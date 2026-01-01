<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-md p-4 flex justify-end space-x-4">
      <button
        @click="goToLogin"
        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
      >
        Login
      </button>
      <button
        @click="goToProducts"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition"
      >
        Products
      </button>
    </header>

    <!-- Contenu principal -->
    <main class="flex flex-col items-center justify-center mt-20">
      <h1 class="text-3xl font-bold text-gray-800 mb-4">
        Salut tout le monde
      </h1>
      <p class="text-gray-600">Bienvenue sur notre application !</p>
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goToLogin = () => {
  router.push('/login')
}

const goToProducts = () => {
  router.push('/products')
}
</script>

<style scoped>
/* Optionnel : tu peux personnaliser plus */
</style>
