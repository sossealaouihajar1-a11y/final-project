<template>
  <div class="min-h-screen bg-[#f6f3ee] text-[#2a2a28] font-serif">

    <!-- Hero -->
    <section class="border-b border-[#d6cdbf]">
      <div class="max-w-7xl mx-auto px-6 py-12 text-center">
        <p class="uppercase tracking-[0.3em] text-xs text-[#6b7b4b] mb-3">
          Administration
        </p>
        <h1 class="text-4xl md:text-5xl font-serif text-[#4a3728] mb-4">
          Gestion des Utilisateurs
        </h1>
        <p class="text-[#5a564f] max-w-xl mx-auto">
          Suivi et gestion des utilisateurs de la plateforme
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-10 space-y-10">

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Total utilisateurs</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ totalUsers }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Administrateurs</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ statistics.total_admins }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Vendeurs</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ statistics.total_vendors }}</p>
        </div>
        <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6 text-center">
          <p class="text-xs uppercase tracking-wider text-[#6b7b4b]">Clients</p>
          <p class="mt-3 text-3xl font-serif text-[#4a3728]">{{ statistics.total_clients }}</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <input
            v-model="searchQuery"
            @input="handleSearch"
            placeholder="Recherche nom ou email"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-[#6b7b4b] col-span-2"
          />
          <select v-model="filterRole" @change="loadUsers"
            class="px-4 py-3 border border-[#d6cdbf] rounded-lg bg-white">
            <option value="">Tous les rôles</option>
            <option value="admin">Administrateur</option>
            <option value="vendeur">Vendeur</option>
            <option value="client">Client</option>
          </select>
          <button @click="loadUsers" class="px-4 py-3 bg-[#6b7b4b] text-white rounded-lg hover:bg-[#53643f] transition">
            Charger
          </button>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-[#fbfaf7] border border-[#d6cdbf] rounded-xl overflow-hidden">
        <table class="min-w-full text-sm">
          <thead class="bg-[#f1ede6] text-[#6b7b4b] uppercase text-xs tracking-wider">
            <tr>
              <th class="px-6 py-4 text-left">Nom</th>
              <th class="px-6 py-4 text-left">Email</th>
              <th class="px-6 py-4 text-left">Rôle</th>
              <th class="px-6 py-4 text-left">Statut</th>
              <th class="px-6 py-4 text-left">Date création</th>
              <th class="px-6 py-4 text-left">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-t border-[#e4dccf] hover:bg-[#f4f1eb]">
              <td class="px-6 py-4 font-medium text-[#4a3728]">{{ user.name }}</td>
              <td class="px-6 py-4">{{ user.email }}</td>
              <td class="px-6 py-4">
                <span :class="getRoleClass(user.role)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ user.role === 'vendeur' ? 'Vendeur' : user.role === 'client' ? 'Client' : 'Admin' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span :class="getStatusClass(user.is_active)" class="px-3 py-1 rounded-full text-xs font-medium">
                  {{ user.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4">{{ formatDate(user.created_at) }}</td>
              <td class="px-6 py-4 space-x-3">
                <button @click="editUser(user)" class="text-[#6b7b4b] hover:underline">Éditer</button>
                <button v-if="user.is_active" @click="confirmSuspend(user)" class="text-[#b35e2c] hover:underline">Suspendre</button>
                <button v-else @click="reactivateUser(user.id)" class="text-[#53643f] hover:underline">Réactiver</button>
                <button @click="confirmDelete(user)" class="text-red-600 hover:underline">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="users.length === 0" class="p-10 text-center text-gray-500">
          Aucun utilisateur trouvé
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import userManagementService from '@/services/userManagementService'

const users = ref([])
const searchQuery = ref('')
const filterRole = ref('')
const editingUser = ref(null)
const confirmModal = ref(null)

const statistics = computed(() => {
  let total_admins = 0, total_vendors = 0, total_clients = 0
  users.value.forEach(user => {
    if (user.role === 'admin') total_admins++
    else if (user.role === 'vendeur') total_vendors++
    else if (user.role === 'client') total_clients++
  })
  return { total_admins, total_vendors, total_clients }
})

const totalUsers = computed(() => statistics.value.total_admins + statistics.value.total_vendors + statistics.value.total_clients)

const loadUsers = async () => {
  try {
    const response = await userManagementService.getAllUsers({
      role: filterRole.value || undefined,
      search: searchQuery.value || undefined,
    })
    users.value = response.data.data || []
  } catch (error) {
    console.error(error)
  }
}

const handleSearch = () => {
  loadUsers()
}

const editUser = (user) => { editingUser.value = { ...user } }
const confirmSuspend = (user) => { console.log('Suspendre', user) }
const reactivateUser = (id) => { console.log('Réactiver', id) }
const confirmDelete = (user) => { console.log('Supprimer', user) }
const formatDate = (date) => new Date(date).toLocaleDateString('fr-FR')

const getRoleClass = (role) => {
  const classes = {
    admin: 'bg-[#f4cccc] text-[#4a3728]',
    vendeur: 'bg-[#c9daf8] text-[#4a3728]',
    client: 'bg-[#fff2cc] text-[#4a3728]',
  }
  return classes[role] || 'bg-gray-100 text-gray-800'
}

const getStatusClass = (isActive) => {
  return isActive ? 'bg-[#d9ead3] text-[#4a3728]' : 'bg-[#e0e0e0] text-[#4a3728]'
}

onMounted(() => loadUsers())
</script>
