<template>
  <FullCalendar :options="calendarOptions" />
</template>

<script setup>
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',

  events: 'http://localhost:8000/api/eventos',

  dateClick: async (info) => {
    const titulo = prompt('Título del evento')

    if (titulo) {
      await fetch('http://localhost:8000/api/eventos', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          titulo: titulo,
          inicio: info.dateStr
        })
      })

      location.reload() // simple refresh (luego lo mejoramos)
    }
  }
})
</script>