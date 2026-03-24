<template>
  <div class="min-h-screen bg-gradient-to-br from-brand-dark via-brand-primary to-brand-light text-white">
    <Navbar />
    <main class="flex-1 flex items-center justify-center p-6">
      <div class="w-full max-w-5xl">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<script setup>
import Navbar from './components/layout/Navbar.vue'
</script>