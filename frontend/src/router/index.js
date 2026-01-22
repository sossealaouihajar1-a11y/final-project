import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import HomePage from '@/views/HomePage.vue'
import LoginPage from '@/views/auth/LoginPage.vue'
import RegisterClientPage from '@/views/auth/RegisterClientPage.vue'
import RegisterVendorPage from '@/views/auth/RegisterVendorPage.vue'
import AdminDashboard from '@/views/admin/DashboardView.vue'
import VendorsManagement from '@/views/admin/VendorsManagement.vue'
import UsersManagement from '@/views/admin/UsersManagement.vue'
import ProductsManagement from '@/views/admin/ProductsManagement.vue'
import ProductDetailsPage from '@/views/ProductDetailsPage.vue'
import About from '@/views/About.vue'
import Contact from '@/views/Contact.vue'
import Layout from '@/components/Layout.vue'   
import AdminLayout from '@/components/AdminLayout.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Layout,
      children: [
        {
          path: '',
          name: 'home',
          component: HomePage,
        },
      ],
    },
    {
      path: '/login',
      name: 'login',
      component: LoginPage,
      meta: { guest: true }
    },
    {
      path: '/about',
      name: 'About',
      component: About,
    },
    {
      path: '/contact',
      name: 'Contact',
      component: Contact,
    },
    {
      path: '/register-client',
      name: 'register-client',
      component: RegisterClientPage,
      meta: { guest: true }
    },
    {
      path: '/register-vendor',
      name: 'register-vendor',
      component: RegisterVendorPage,
      meta: { guest: true }
    },
    // ============================================
    // ROUTES ADMIN
    // ============================================
   {
  path: '/admin',
  component: AdminLayout,
  meta: { requiresAuth: true, role: 'admin' },
  children: [
    {
      path: 'dashboard',
      name: 'admin-dashboard',
      component: AdminDashboard
    },
    {
      path: 'vendors',
      name: 'admin-vendors',
      component: VendorsManagement
    },
    {
      path: 'users',
      name: 'admin-users',
      component: UsersManagement
    },
    {
      path: 'products',
      name: 'admin-products',
      component: ProductsManagement
    },
    {
      path: 'orders',
      name: 'admin-orders',
      component: () => import('@/views/admin/OrderManagement.vue')
    },
    {
      path: 'reviews',
      name: 'admin-reviews',
      component: () => import('@/views/admin/ReviewsManagement.vue')
    }
  ]
},
    // ============================================
    // ROUTES CLIENT
    // ============================================
    {
      path: '/client/dashboard',
      name: 'clientDashboard',
      component: () => import('@/views/ClientDashboard.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    // ============================================
    // ROUTES VENDOR
    // ============================================
    {
      path: '/vendor',
      name: 'vendor-layout',
      component: () => import('@/views/vendor/DashboardView.vue'),
      meta: { requiresAuth: true, role: 'vendeur' },
      children: [
        {
          path: 'dashboard',
          name: 'vendor-dashboard',
          component: () => import('@/views/vendor/DashboardView.vue'),
          meta: { requiresAuth: true, role: 'vendeur' }
        },
        {
          path: 'products',
          name: 'vendor-products',
          component: () => import('@/views/vendor/ProductsView.vue'),
          meta: { requiresAuth: true, role: 'vendeur' }
        },
        {
          path: 'clients',
          name: 'vendor-clients',
          component: () => import('@/views/vendor/ClientsView.vue'),
          meta: { requiresAuth: true, role: 'vendeur' }
        },
        {
          path: 'orders',
          name: 'vendor-orders',
          component: () => import('@/views/vendor/OrdersView.vue'),
          meta: { requiresAuth: true, role: 'vendeur' }
        },
        {
          path: 'stock',
          name: 'vendor-stock',
          component: () => import('@/views/vendor/StockView.vue'),
          meta: { requiresAuth: true, role: 'vendeur' }
        }
      ]
    },
    // ============================================
    // ROUTES PRODUITS ET PANIER
    // ============================================
    {
      path: '/products',
      name: 'products',
      component: () => import('@/views/ProductsPage.vue')
    },
    {
      path: '/products/:id',
      name: 'ProductDetails',
      component: () => import('@/views/ProductDetailsPage.vue'),
      meta: { requiresAuth: false }
    },
    {
      path: '/categories',
      name: 'categories',
      component: () => import('@/views/ProductsPage.vue')
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('@/views/CartPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    {
      path: '/checkout',
      name: 'checkout',
      component: () => import('@/views/CheckoutPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    {
      path: '/orders',
      name: 'orders',
      component: () => import('../views/OrdersPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: () => import('../views/FavoritesPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    }
  ]
})

// Navigation Guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

  // Routes pour invités
  if (to.meta.guest) {
    if (authStore.isAuthenticated && authStore.user) {
      const role = authStore.user.role
      if (role === 'admin') return next('/admin/dashboard')
      if (role === 'vendeur') return next('/vendor/dashboard')
      if (role === 'client') return next('/client/dashboard')
    }
    return next()
  }

  // Routes protégées
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated || !authStore.user) {
      try {
        await authStore.fetchUser()
      } catch (error) {
        return next('/login')
      }
    }

    if (!authStore.isAuthenticated || !authStore.user) {
      return next('/login')
    }

    if (to.meta.role && authStore.user.role !== to.meta.role) {
      const role = authStore.user.role
      if (role === 'admin') return next('/admin/dashboard')
      if (role === 'vendeur') return next('/vendor/dashboard')
      if (role === 'client') return next('/client/dashboard')
      return next('/login')
    }

    return next()
  }

  // Autres routes
  next()
})

export default router