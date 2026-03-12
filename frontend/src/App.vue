<template>
  <div class="min-h-screen bg-slate-100 flex flex-col items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md text-center">
      <h1 class="text-2xl font-bold text-indigo-600 mb-4">Vue 3 Frontend</h1>

      <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
        <p class="text-sm text-gray-500 mb-2">Respuesta del Backend:</p>
        <p v-if="loading" class="text-amber-500 font-semibold animate-pulse">Conectando con Laravel...</p>
        <p v-else-if="error" class="text-red-500 font-semibold">{{ error }}</p>
        <p v-else class="text-green-600 font-bold">{{ apiData }}</p>
      </div>

      <button @click="fetchData" class="mt-6 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
        Recargar Datos
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const apiData = ref('')
const loading = ref(true)
const error = ref(null)

const fetchData = async () => {
  loading.value = true
  error.value = null
  try {
    // Reemplaza fetch por axios
    const { data } = await axios.get('http://127.0.0.1:8000/api/datos')
    apiData.value = data.message
  } catch (err) {
    error.value = 'No se pudo conectar con la API.'
    console.error(err)
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchData()
})
</script>