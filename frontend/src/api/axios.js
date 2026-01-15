import axios from 'axios'

const apiClient = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Access-Control-Allow-Origin':'*',
    'Accept': 'application/json'
  }
})

// Interceptor pour ajouter le token automatiquement
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    console.log('Request:', config.method.toUpperCase(), config.url, token ? 'avec token' : 'sans token')
    return config
  },
  (error) => Promise.reject(error)
)

// Interceptor pour gérer les erreurs
apiClient.interceptors.response.use(
  (response) => {
    console.log('Response:', response.config.url, response.status)
    return response
  },
  (error) => {
    console.error('Error:', error.config?.url, error.response?.status)
    
    if (error.response?.status === 401) {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
      if (!window.location.pathname.includes('/login')) {
        window.location.href = '/login'
      }
    }
    
    return Promise.reject(error)
  }
)

export default apiClient