import { createRouter, createWebHistory } from 'vue-router'
import Bienvenida from '../pages/Bienvenida.vue'
import Calendario from '../pages/Calendario.vue'

const routes = [
  { path: '/',           component: Bienvenida },
  { path: '/calendario', component: Calendario },
]

export default createRouter({
  history: createWebHistory(),
  routes
})