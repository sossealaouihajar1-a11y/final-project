// import { createRouter, createWebHistory } from 'vue-router'
// import { useAuthStore } from '@/stores/authStore'
// import LoginPage from '@/views/auth/LoginPage.vue'
// import RegisterClientPage from '@/views/auth/RegisterClientPage.vue'
// import RegisterVendorPage from '@/views/auth/RegisterVendorPage.vue'
// import AdminDashboard from '@/views/admin/DashboardView.vue'
// import VendorsManagement from '@/views/admin/VendorsManagement.vue'

// const router = createRouter({
//   history: createWebHistory(import.meta.env.BASE_URL),
//   routes: [
//     {
//       path: '/',
//       redirect: '/login'
//     },
//     {
//       path: '/login',
//       name: 'login',
//       component: LoginPage,
//       meta: { guest: true }
//     },
//     {
//       path: '/register-client',
//       name: 'register-client',
//       component: RegisterClientPage,
//       meta: { guest: true }
//     },
//     {
//       path: '/register-vendor',
//       name: 'register-vendor',
//       component: RegisterVendorPage,
//       meta: { guest: true }
//     },
//     {
//       path: '/admin/dashboard',
//       name: 'admin-dashboard',
//       component: AdminDashboard,
//       meta: { requiresAuth: true, role: 'admin' }
//     },
//     {
//       path: '/admin/vendors',
//       name: 'admin-vendors',
//       component: VendorsManagement,
//       meta: { requiresAuth: true, role: 'admin' }
//     },
//     {
//       path: '/client/dashboard',
//       name: 'client-dashboard',
//       component: () => import('@/views/client/DashboardView.vue'),
//       meta: { requiresAuth: true, role: 'client' }
//     },
//     {
//       path: '/vendor/dashboard',
//       name: 'vendor-dashboard',
//       component: () => import('@/views/vendor/DashboardView.vue'),
//       meta: { requiresAuth: true, role: 'vendeur' }
//     },

//     {
//   path: '/products',
//   name: 'products',
//   component: () => import('@/views/ProductsPage.vue')
// },
//   ]
// })

// // Navigation Guard CORRIGÉ
// router.beforeEach(async (to, from, next) => {
//   const authStore = useAuthStore()

//   // Routes pour invités (login, register)
//   if (to.meta.guest) {
//     // Si déjà connecté, rediriger vers le dashboard approprié
//     if (authStore.isAuthenticated && authStore.user) {
//       const role = authStore.user.role
//       if (role === 'admin') return next('/admin/dashboard')
//       if (role === 'vendeur') return next('/vendor/dashboard')
//       if (role === 'client') return next('/client/dashboard')
//     }
//     // Sinon, laisser accéder à la page guest
//     return next()
//   }

//   // Routes protégées
//   if (to.meta.requiresAuth) {
//     // Si pas authentifié, vérifier d'abord avec le serveur
//     if (!authStore.isAuthenticated || !authStore.user) {
//       try {
//         await authStore.fetchUser()
//       } catch (error) {
//         // Échec de fetchUser = pas connecté
//         return next('/login')
//       }
//     }

//     // Vérifier que l'utilisateur est toujours authentifié après fetchUser
//     if (!authStore.isAuthenticated || !authStore.user) {
//       return next('/login')
//     }

//     // Vérifier le rôle
//     if (to.meta.role && authStore.user.role !== to.meta.role) {
//       const role = authStore.user.role
//       if (role === 'admin') return next('/admin/dashboard')
//       if (role === 'vendeur') return next('/vendor/dashboard')
//       if (role === 'client') return next('/client/dashboard')
//       return next('/login')
//     }

//     // Tout est OK, autoriser l'accès
//     return next()
//   }

//   // Autres routes (pas de meta)
//   next()
// })

// export default router

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
    {
      path: '/admin/dashboard',
      name: 'admin-dashboard',
      component: AdminDashboard,
      meta: { requiresAuth: true, role: 'admin' }
    },
    {
      path: '/admin/vendors',
      name: 'admin-vendors',
      component: VendorsManagement,
      meta: { requiresAuth: true, role: 'admin' }
    },
    {
      path: '/admin/users',
      name: 'admin-users',
      component: UsersManagement,
      meta: { requiresAuth: true, role: 'admin' }
    },
    {
      path: '/admin/products',
      name: 'admin-products',
      component: ProductsManagement,
      meta: { requiresAuth: true, role: 'admin' }
    },
   {
      path: '/client/dashboard',
      name: 'clientDashboard',
      component: () => import('@/views/ClientDashboard.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
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
    {
      path: '/products',
      name: 'products',
      component: () => import('@/views/ProductsPage.vue')
    },
    {
      path: '/categories',
      name: 'categories',
      component: () => import('@/views/ProductsPage.vue')
    },
        {
      path: '/cart',
      name: 'cart',
      component: () => import('@/views/CartPage.vue')
    },
    {
      path: '/orders',
      name: 'orders',
      component: () => import('@/views/OrdersPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    {
      path: '/favorites',
      name: 'favorites',
      component: () => import('@/views/FavoritesPage.vue'),
      meta: { requiresAuth: true, role: 'client' }
    },
    {
    path: '/products/:id',
    name: 'ProductDetails',
    component: () => import('../views/ProductDetailsPage.vue'),
    meta: { requiresAuth: false }
},
{
    path: '/products/:id',
    name: 'ProductDetails',
    component: () => import('../views/ProductDetailsPage.vue'),
    meta: { requiresAuth: false }
    }
  ]
})

// Navigation Guard
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()

   if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.role && authStore.user?.role !== to.meta.role) {
    next('/products')
  } else {
    next()
  }
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
