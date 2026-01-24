import { defineStore } from 'pinia'
import { computed } from 'vue'
import { useAuthenticateStore } from './authenticate'

export const useNavigationStore = defineStore('navigation', () => {
  const auth = useAuthenticateStore()

  const navItems = [
    { name: 'Home', to: '/', auth: true },
    { name: 'Profile', to: '/profile', auth: true },
    { name: 'Dashboard', to: '/dashboard', auth: true },
    { name: 'Login', to: '/login', auth: false },
    { name: 'Register', to: '/register', auth: false }
  ]

  const visibleNavItems = computed(() => {
    return navItems.filter(item => {
      return item.auth === auth.isAuthenticated
    })
  })

  return {
    visibleNavItems
  }
})
