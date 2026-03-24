<template>
    <div class="backdrop-blur-md bg-white/20 border border-white/20 rounded-2xl shadow-xl p-6 text-black">
      <h2 class="text-2xl font-bold text-brand-dark mb-4">Calendario de eventos</h2>
      <CalendarioComponent />
    </div>
  </template>
  
  <script setup>
  import CalendarioComponent from '../components/Calendario.vue'
  </script>