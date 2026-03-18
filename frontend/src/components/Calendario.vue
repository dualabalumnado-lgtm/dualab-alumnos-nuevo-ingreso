<template>
  <div class="calendar-wrapper">
    <FullCalendar :options="calendarOptions" />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const calendarRef = ref(null)

const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',

  events: 'http://localhost:8000/api/eventos',

  editable: true,

  eventDrop: async (info) => {
    await fetch(`http://localhost:8000/api/eventos/${info.event.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        inicio: info.event.startStr
      })
    })
  },

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

      // recargar eventos SIN recargar página
      info.view.calendar.refetchEvents()
    }
  }
})
</script>

<style scoped>
.calendar-wrapper {
  background: rgba(255, 255, 255, 0.85);
  border-radius: 16px;
  padding: 10px;
}
</style>