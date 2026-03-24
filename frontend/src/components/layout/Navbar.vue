<template>
    <header class="w-full p-4 flex justify-between items-center backdrop-blur-md bg-black/20">
        <img src="../../assets/logo_dualab.png" alt="Dualab" class="h-10" />
      <nav class="flex gap-4 text-sm font-medium">
        <RouterLink
          to="/"
          class="hover:text-green-300 transition"
          active-class="text-green-300 font-bold"
        >
          Inicio
        </RouterLink>
        <RouterLink
          to="/calendario"
          class="hover:text-green-300 transition"
          active-class="text-green-300 font-bold"
        >
          Calendario
        </RouterLink>
      </nav>
    </header>
  </template>