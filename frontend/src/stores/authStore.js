import { defineStore } from 'pinia'
import authService from '@/services/authService'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: authService.getCurrentUser(),
    token: authService.getToken(),
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isAdmin: (state) => state.user?.role === 'admin',
    isClient: (state) => state.user?.role === 'client',
    isVendor: (state) => state.user?.role === 'vendeur',
    isApprovedVendor: (state) => state.user?.role === 'vendeur' && state.user?.vendor_status === 'approved'
  },

  actions: {
    async registerClient(data) {
      this.loading = true
      this.error = null
      try {
        const response = await authService.registerClient(data)
        this.user = response.user
        this.token = response.token
        return response
      } catch (error) {
        this.error = this.extractError(error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async registerVendor(formData) {
      this.loading = true
      this.error = null
      try {
        const response = await authService.registerVendor(formData)
        return response
      } catch (error) {
        this.error = this.extractError(error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async login(credentials) {
      this.loading = true
      this.error = null
      try {
        const response = await authService.login(credentials)
        this.user = response.user
        this.token = response.token
        return response
      } catch (error) {
        this.error = this.extractError(error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async logout() {
      this.loading = true
      try {
        await authService.logout()
      } catch (error) {
        console.error('Erreur logout:', error)
      } finally {
        this.user = null
        this.token = null
        this.loading = false
      }
    },

    async fetchUser() {
      try {
        const response = await authService.me()
        this.user = response.user
        localStorage.setItem('user', JSON.stringify(response.user))
      } catch (error) {
        this.user = null
        this.token = null
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        throw error
      }
    },

    extractError(error) {
      return error.response?.data?.errors?.email?.[0] || 
             error.response?.data?.message || 
             'Une erreur est survenue'
    }
  }
})