import { useAuthenticateStore } from '@/stores/authenticate'
import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import Login from '../components/Login.vue'
import Register from '../components/Register.vue'
import AddPerson from '../components/auth/AddPerson.vue'
import EditPerson from '../components/auth/EditPerson.vue'
import Person from '../components/auth/Person.vue'

const routes = [
  {
    path: '/',
    component: Home,
    meta: { requiresAuth: true }
  },
  // {
  //   path: '/profile',
  //   component: Profile,
  //   meta: { requiresAuth: true }
  // },
   {
    path: '/person',
    component: Person,
    meta: { requiresAuth: true },
    name:'person'
  },
  {
    path: '/create',
    component: AddPerson,
    name: 'add-person',
    meta: { requiresAuth: true }
  },
  {
    path: '/edit/:id',
    component: EditPerson,
    name: 'edit-person',
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/register',
    component: Register
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const auth = useAuthenticateStore()

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next('/login')
  } else {
    next()
  }
})

export default router
