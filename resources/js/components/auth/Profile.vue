<template>
  <section class="chat-wrapper">
    <div v-if="currentUser">
      <h3>Welcome, {{ currentUser.name }}</h3>
      <p>Email: {{ currentUser.email }}</p>
    </div>

    <div v-else>
      <p>Loading user...</p>
    </div>
  </section>
</template>

<script setup>
import { useAuthenticateStore } from '@/stores/authenticate';
import { ref } from 'vue';

const authenticate = useAuthenticateStore()
const currentUser = ref(authenticate.user)

console.log(currentUser.value)

</script>

<style scoped>

</style> 