<template>
  <nav class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">

      <!-- LEFT MENU -->
      <div class="flex gap-4">
        <router-link
          v-for="item in navigation.visibleNavItems"
          :key="item.to"
          :to="item.to"
        >
          {{ item.name }}
        </router-link>
      </div>

      <!-- LOGOUT -->
      <button
        v-if="auth.isAuthenticated"
        @click="logout"
        class="bg-red-500 px-4 py-2 rounded"
      >
        Logout
      </button>

    </div>
  </nav>

  <router-view />
</template>

<script setup>
import { useAuthenticateStore } from '@/stores/authenticate'
import { useNavigationStore } from '@/stores/navigation'
import { useRouter } from 'vue-router'

const auth = useAuthenticateStore()
const navigation = useNavigationStore()
const router = useRouter()

const logout = () => {
  auth.logout()
  router.push('/login')
}
</script>
