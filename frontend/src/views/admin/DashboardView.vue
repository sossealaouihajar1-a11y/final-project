<script setup>
import { useAuthStore } from '@/stores/authStore'
import Header from '@/components/Header.vue'

const authStore = useAuthStore()
const admin = authStore.user
</script>

<template>
  <div class="max-w-4xl mx-auto py-12 px-6 space-y-12">

    <!-- Hero / Titre -->
    <section class="text-center">
      <p class="uppercase tracking-[0.2em] text-xs text-[#6b7b4b] mb-2">
        Administration
      </p>
      <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] font-bold">
        Bienvenue, {{ admin?.name }}
      </h1>
      <p class="mt-2 text-[#5a564f] max-w-md mx-auto">
        Votre espace personnel pour gérer la plateforme et consulter vos informations.
      </p>
    </section>

    <!-- Carte infos personnelles -->
    <section class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-2xl p-8 shadow-lg">
      <h2 class="text-2xl font-semibold text-[#556b2f] mb-6 border-b border-[#d6cdbf] pb-2">
        Informations personnelles
      </h2>

      <ul class="grid grid-cols-1 md:grid-cols-2 gap-4 text-[#2a2a28]">
        <li class="flex flex-col">
          <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Nom</span>
          <span class="mt-1 text-lg font-medium">{{ admin?.name }}</span>
        </li>
        <li class="flex flex-col">
          <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Email</span>
          <span class="mt-1 text-lg font-medium">{{ admin?.email }}</span>
        </li>
        <li class="flex flex-col">
          <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Rôle</span>
          <span class="mt-1 text-lg font-medium">Administrateur</span>
        </li>
        <li class="flex flex-col">
          <span class="text-sm text-[#6b7b4b] uppercase tracking-wider">Date d’inscription</span>
          <span class="mt-1 text-lg font-medium">
            {{ new Date(admin?.created_at).toLocaleDateString('fr-FR', { day:'2-digit', month:'long', year:'numeric' }) }}
          </span>
        </li>
      </ul>
    </section>

  </div>
</template>
