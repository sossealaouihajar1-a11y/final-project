<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestion des Utilisateurs</h1>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Total Utilisateurs</div>
          <div class="text-3xl font-bold text-gray-900 mt-2">{{ statistics.total_users }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Administrateurs</div>
          <div class="text-3xl font-bold text-indigo-600 mt-2">{{ statistics.total_admins }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Vendeurs</div>
          <div class="text-3xl font-bold text-purple-600 mt-2">{{ statistics.total_vendors }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
          <div class="text-gray-500 text-sm font-medium">Clients</div>
          <div class="text-3xl font-bold text-blue-600 mt-2">{{ statistics.total_clients }}</div>
        </div>
      </div>

      <!-- Filters and Search -->
      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Nom ou email..."
              @input="handleSearch"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
            <select
              v-model="filterRole"
              @change="loadUsers"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="">Tous les rôles</option>
              <option value="admin">Administrateur</option>
              <option value="vendeur">Vendeur</option>
              <option value="client">Client</option>
            </select>
          </div>
          <div class="flex items-end">
            <button
              @click="loadUsers"
              class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
            >
              Charger
            </button>
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow overflow-hidden">
        <div v-if="loading" class="p-8 text-center">
          <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
          <p class="mt-2 text-gray-600">Chargement...</p>
        </div>

        <table v-else class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Création</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-red-100 text-red-800': user.role === 'admin',
                    'bg-purple-100 text-purple-800': user.role === 'vendeur',
                    'bg-blue-100 text-blue-800': user.role === 'client',
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ user.role === 'vendeur' ? 'Vendeur' : user.role === 'client' ? 'Client' : 'Admin' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  :class="{
                    'bg-green-100 text-green-800': user.is_active,
                    'bg-gray-100 text-gray-800': !user.is_active,
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ user.is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(user.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                <button
                  @click="editUser(user)"
                  class="text-indigo-600 hover:text-indigo-900 font-medium"
                >
                  Éditer
                </button>
                <button
                  v-if="user.is_active"
                  @click="confirmSuspend(user)"
                  class="text-orange-600 hover:text-orange-900 font-medium"
                >
                  Suspendre
                </button>
                <button
                  v-else
                  @click="reactivateUser(user.id)"
                  class="text-green-600 hover:text-green-900 font-medium"
                >
                  Réactiver
                </button>
                <button
                  @click="confirmDelete(user)"
                  class="text-red-600 hover:text-red-900 font-medium"
                >
                  Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="!loading && pagination" class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-600">
          Page {{ pagination.current_page }} de {{ pagination.last_page }} ({{ pagination.total }} utilisateurs)
        </div>
        <div class="space-x-2">
          <button
            v-if="pagination.current_page > 1"
            @click="loadUsers(pagination.current_page - 1)"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Précédent
          </button>
          <button
            v-if="pagination.current_page < pagination.last_page"
            @click="loadUsers(pagination.current_page + 1)"
            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            Suivant
          </button>
        </div>
      </div>
    </div>

    <!-- Edit Modal -->
    <div
      v-if="editingUser"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg max-w-md w-full p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Éditer l'utilisateur</h2>
        <div class="space-y-4 mb-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
            <input
              v-model="editingUser.name"
              type="text"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input
              v-model="editingUser.email"
              type="email"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
            <select
              v-model="editingUser.role"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
            >
              <option value="admin">Administrateur</option>
              <option value="vendeur">Vendeur</option>
              <option value="client">Client</option>
            </select>
          </div>
        </div>
        <div class="flex gap-4">
          <button
            @click="saveUser"
            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
          >
            Enregistrer
          </button>
          <button
            @click="editingUser = null"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Confirmation Modal -->
    <div
      v-if="confirmModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-lg max-w-sm w-full p-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">{{ confirmModal.title }}</h2>
        <p class="text-gray-600 mb-6">{{ confirmModal.message }}</p>
        <div class="flex gap-4">
          <button
            @click="confirmModal.action"
            :class="confirmModal.danger ? 'bg-red-600 hover:bg-red-700' : 'bg-indigo-600 hover:bg-indigo-700'"
            class="flex-1 px-4 py-2 text-white rounded-lg transition"
          >
            Confirmer
          </button>
          <button
            @click="confirmModal = null"
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          >
            Annuler
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import userManagementService from '@/services/userManagementService'

const users = ref([])
const loading = ref(false)
const statistics = ref({})
const searchQuery = ref('')
const filterRole = ref('')
const editingUser = ref(null)
const confirmModal = ref(null)
const pagination = ref(null)
let searchTimeout = null

const loadUsers = async (page = 1) => {
  loading.value = true
  try {
    const response = await userManagementService.getAllUsers({
      page,
      per_page: 15,
      role: filterRole.value || undefined,
      search: searchQuery.value || undefined,
    })
    users.value = response.data.data || []
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      total: response.data.total,
    }
  } catch (error) {
    console.error('Erreur chargement utilisateurs:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await userManagementService.getUserStatistics()
    statistics.value = response.data
  } catch (error) {
    console.error('Erreur chargement statistiques:', error)
  }
}

const handleSearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    loadUsers(1)
  }, 500)
}

const editUser = (user) => {
  editingUser.value = { ...user }
}

const saveUser = async () => {
  try {
    await userManagementService.updateUser(editingUser.value.id, {
      name: editingUser.value.name,
      email: editingUser.value.email,
      role: editingUser.value.role,
    })
    editingUser.value = null
    loadUsers()
    loadStatistics()
  } catch (error) {
    console.error('Erreur mise à jour utilisateur:', error)
  }
}

const confirmSuspend = (user) => {
  confirmModal.value = {
    title: 'Suspendre cet utilisateur',
    message: `Êtes-vous sûr de vouloir suspendre ${user.name}?`,
    action: () => suspendUser(user.id),
    danger: true,
  }
}

const suspendUser = async (userId) => {
  try {
    await userManagementService.suspendUser(userId)
    confirmModal.value = null
    loadUsers()
    loadStatistics()
  } catch (error) {
    console.error('Erreur suspension:', error)
  }
}

const reactivateUser = async (userId) => {
  try {
    await userManagementService.reactivateUser(userId)
    loadUsers()
    loadStatistics()
  } catch (error) {
    console.error('Erreur réactivation:', error)
  }
}

const confirmDelete = (user) => {
  confirmModal.value = {
    title: 'Supprimer cet utilisateur',
    message: `Êtes-vous sûr de vouloir supprimer ${user.name}? Cette action est irréversible.`,
    action: () => deleteUser(user.id),
    danger: true,
  }
}

const deleteUser = async (userId) => {
  try {
    await userManagementService.deleteUser(userId)
    confirmModal.value = null
    loadUsers()
    loadStatistics()
  } catch (error) {
    console.error('Erreur suppression:', error)
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('fr-FR')
}

onMounted(() => {
  loadUsers()
  loadStatistics()
})
</script>
