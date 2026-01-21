<template>
  <div class="min-h-screen flex bg-[#f8f5ef] text-[#2a2a28] font-serif">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#6b705c] text-[#f8f5ef] fixed inset-y-0 left-0 border-r border-[#5f6452]">
      <div class="px-6 py-6 border-b border-[#5f6452]">
        <h2 class="text-xl font-bold">Administration</h2>
        <p class="text-xs uppercase tracking-widest opacity-70 mt-1">Vintage Store</p>
      </div>

      <nav class="px-4 py-6 space-y-2 text-sm">
        <router-link to="/admin/dashboard" class="nav-link">Dashboard</router-link>
        <router-link to="/admin/orders" class="nav-link">Commandes</router-link>
        <router-link to="/admin/products" class="nav-link">Produits</router-link>
        <router-link to="/admin/vendors" class="nav-link">Vendeurs</router-link>
        <router-link to="/admin/users" class="nav-link">Utilisateurs</router-link>
        <router-link to="/admin/reviews" class="nav-link">Commentaires</router-link>
      </nav>
    </aside>

    <!-- CONTENU -->
    <div class="ml-64 flex-1 flex flex-col min-h-screen">
      
      <!-- HEADER -->
      <header class="bg-[#f6f3ee] border-b border-[#d6cdbf] px-10 py-6 flex justify-between items-center">
        <h1 class="text-2xl font-serif text-[#4a3728]">Dashboard Admin</h1>
        <button
          @click="logout"
          class="px-4 py-2 bg-[#8b5e3c] hover:bg-[#a06a47] text-white font-semibold rounded-lg transition"
        >
          Déconnecter
        </button>
      </header>

      <!-- MAIN CONTENT -->
      <main class="flex-1 p-10 bg-[#f8f5ef]">
        <router-view />
      </main>

    </div>

  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import apiClient from '@/api/axios'

const router = useRouter()

const logout = async () => {
  try {
    // Appel API de déconnexion
    await apiClient.post('/auth/logout')
  } catch (error) {
    console.error('Erreur lors de la déconnexion:', error)
  } finally {
    // Nettoyer le localStorage
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    
    // Dispatcher un événement pour notifier les autres composants (header, etc.)
    window.dispatchEvent(new Event('authChange'))
    
    // Rediriger vers la page d'accueil
    router.push('/')
  }
}
</script>

<style scoped>
.nav-link {
  display: block;
  padding: 10px 14px;
  border-radius: 6px;
  transition: background 0.2s;
}
.nav-link:hover {
  background: rgba(255,255,255,0.12);
}
.router-link-active {
  background: rgba(255,255,255,0.25);
  font-weight: 600;
}
</style>