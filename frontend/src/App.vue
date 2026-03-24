<template>
  <div class="min-h-screen bg-gradient-to-br from-brand-dark via-brand-primary to-brand-light text-white">

    <!-- HEADER -->
    <header class="w-full p-4 flex justify-between items-center backdrop-blur-md bg-black/20">
      <h1 class="text-xl font-bold">🌿 Dualab</h1>
      <span class="text-sm opacity-80">Panel de prácticas</span>
    </header>

    <!-- CONTENIDO -->
    <main class="flex-1 flex items-center justify-center p-6">
      
      <div class="w-full max-w-5xl">

        <!-- CARD GLASS -->
        <div class="backdrop-blur-md bg-white/20 border border-white/20 rounded-2xl shadow-xl p-6 text-black">

          <h2 class="text-2xl font-bold text-brand-dark mb-4">
            Calendario de eventos
          </h2>

          <!-- AQUÍ VA EL CALENDARIO -->
          <Calendar />

        </div>

      </div>

    </main>

  </div>
</template>

<script setup>
import Calendar from './components/Calendario.vue'
</script>