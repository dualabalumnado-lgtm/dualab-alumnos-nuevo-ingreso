<template>
  <div class="calendar-wrapper">
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
  </div>

  <!-- MODAL -->
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
  <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">

    <!-- título -->
    <input
      ref="inputTitulo"
      v-model="titulo"
      placeholder="Añadir título"
      class="w-full text-lg font-semibold border-b mb-4 outline-none text-center"
      @keyup.enter="guardarEvento"
    />

    <!-- fecha -->
    <input
      v-model="fechaSeleccionada"
      type="date"
      class="w-full border rounded-lg p-2 mb-3"
    />

    <!-- hora -->
    <input
      v-model="hora"
      type="time"
      class="w-full border rounded-lg p-2 mb-3"
    />

    <!-- descripción -->
    <textarea
      v-model="descripcion"
      placeholder="Añadir descripción"
      class="w-full border rounded-lg p-2 mb-4"
    ></textarea>

    <!-- colores -->
    <div class="flex gap-2 mb-4 justify-center">
      <div
        v-for="c in colores"
        :key="c"
        :style="{ background: c }"
        class="w-6 h-6 rounded-full cursor-pointer border-2"
        :class="color === c ? 'border-black' : 'border-transparent'"
        @click="color = c"
      ></div>
    </div>

    <!-- botones -->
    <div class="flex justify-between items-center">

      <button
        v-if="modoEdicion"
        @click="eliminarEvento"
        class="px-3 py-1 bg-red-500 text-white rounded"
      >
        Eliminar
      </button>

      <div class="flex gap-2 ml-auto">
        <button @click="cerrarModal" class="px-3 py-1 bg-gray-200 rounded">
          Cancelar
        </button>

        <button @click="guardarEvento" class="px-3 py-1 bg-green-500 text-white rounded">
          Guardar
        </button>
      </div>

    </div>

  </div>
</div>
</template>

<script setup>
import { ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

const calendarRef = ref(null)

// 🔥 estado
const showModal = ref(false)
const titulo = ref('')
const color = ref('#59BF38')
const fechaSeleccionada = ref(null)
const modoEdicion = ref(false)
const eventoId = ref(null)
const descripcion = ref('')
const hora = ref('')

const colores = [
  '#59BF38',
  '#1F6935',
  '#AEE565',
  '#75947F',
  '#C6D8C6'
]

//  helpers
const cerrarModal = () => {
  showModal.value = false
  modoEdicion.value = false
  titulo.value = ''
}

//  guardar (crear + editar)
const guardarEvento = async () => {
  if (!titulo.value) return

  const fechaFinal = hora.value
  ? `${fechaSeleccionada.value}T${hora.value}`
  : fechaSeleccionada.value

  const url = modoEdicion.value
    ? `http://localhost:8000/api/eventos/${eventoId.value}`
    : 'http://localhost:8000/api/eventos'

  const method = modoEdicion.value ? 'PUT' : 'POST'

  await fetch(url, {
    method,
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      titulo: titulo.value,
      inicio: fechaSeleccionada.value,
      color: color.value
    })
  })

  cerrarModal()
  calendarRef.value.getApi().refetchEvents()
}

//  eliminar
const eliminarEvento = async () => {
  await fetch(`http://localhost:8000/api/eventos/${eventoId.value}`, {
    method: 'DELETE'
  })

  cerrarModal()
  calendarRef.value.getApi().refetchEvents()
}

//  calendario
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: 'http://localhost:8000/api/eventos',
  editable: true,

  eventDrop: async (info) => {
    await fetch(`http://localhost:8000/api/eventos/${info.event.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        inicio: info.event.startStr
      })
    })
  },

  eventClick: (info) => {
    modoEdicion.value = true
    showModal.value = true

    titulo.value = info.event.title
    color.value = info.event.backgroundColor
    fechaSeleccionada.value = info.event.startStr
    eventoId.value = info.event.id
  },

  dateClick: (info) => {
    modoEdicion.value = false
    showModal.value = true

    titulo.value = ''
    color.value = '#59BF38'
    fechaSeleccionada.value = info.dateStr
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