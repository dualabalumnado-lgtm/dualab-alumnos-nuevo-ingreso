<template>
  <div class="calendar-wrapper">
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
  </div>

  
  <!-- MODAL -->
<div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50"
     @keydown.enter.prevent="guardarEvento">  
  <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">

    <input
      v-model="titulo"
      placeholder="Título del evento *"
      class="w-full text-xl font-bold border-b-2 mb-2 outline-none text-center border-gray-300 focus:border-green-500"
    />

    <input
      v-model="fechaSeleccionada"
      type="date"
      class="w-full border rounded-lg p-2 mb-3"
    />

    <textarea>
      v-model="descripcion"
      placeholder="Descripción (opcional)"
      class="w-full border rounded-lg p-2 mb-4 text-sm text-gray-600"
      @keydown.enter.stop  
    </textarea>

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
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { ref } from 'vue'

const calendarRef = ref(null)

// ESTADO
const showModal = ref(false)
const titulo = ref('')
const color = ref('#59BF38')
const fechaSeleccionada = ref('')
const modoEdicion = ref(false)
const eventoId = ref(null)
const descripcion = ref('')

const colores = [
  '#59BF38',
  '#1F6935',
  '#AEE565',
  '#75947F',
  '#C6D8C6'
]

// RESET MODAL
const cerrarModal = () => {
  showModal.value = false
  modoEdicion.value = false
  titulo.value = ''
  descripcion.value = ''
}

// GUARDAR EVENTO
const guardarEvento = async () => {
  if (!titulo.value || !fechaSeleccionada.value) {
    alert('Faltan datos')
    return
  }

  const url = modoEdicion.value
    ? `http://localhost:8000/api/eventos/${eventoId.value}`
    : 'http://localhost:8000/api/eventos'
  const method = modoEdicion.value ? 'PUT' : 'POST'

  try {
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        titulo: titulo.value,
        inicio: fechaSeleccionada.value,
        color: color.value,
        descripcion: descripcion.value
      })
    })

    if (!res.ok) {
      const text = await res.text()
      console.log('ERROR:', text)
      alert('Error al guardar')
      return
    }

    const data = await res.json() // 👈 guarda la respuesta del servidor
    const calendarApi = calendarRef.value.getApi()

    if (modoEdicion.value) {
      // Busca el evento existente y actualízalo directamente
      const eventoExistente = calendarApi.getEventById(String(eventoId.value))
      if (eventoExistente) {
        eventoExistente.setProp('title', titulo.value)
        eventoExistente.setProp('color', color.value)
        eventoExistente.setStart(fechaSeleccionada.value)
      }
    } else {
      // Añade el nuevo evento directamente con el ID que devuelve el servidor
      calendarApi.addEvent({
        id: String(data.id),   // 👈 asegúrate que tu API devuelve el id
        title: titulo.value,
        start: fechaSeleccionada.value,
        color: color.value,
        extendedProps: { descripcion: descripcion.value }
      })
    }

    cerrarModal()

  } catch (error) {
    console.error('FETCH ERROR:', error)
  }
}
// ELIMINAR
const eliminarEvento = async () => {
  await fetch(`http://localhost:8000/api/eventos/${eventoId.value}`, {
    method: 'DELETE'
  })

  cerrarModal()

  const calendarApi = calendarRef.value.getApi()
  calendarApi.refetchEvents()
}

// CALENDARIO
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',

  //  FUENTE DE EVENTOS (ESTABLE)
  events: async (info, successCallback) => {
    const res = await fetch('http://localhost:8000/api/eventos')
    const data = await res.json()
    successCallback(data)
  },

  editable: true,
  displayEventTime: false,

  // MOVER EVENTO
  eventDrop: async (info) => {
    await fetch(`http://localhost:8000/api/eventos/${info.event.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        inicio: info.event.startStr.split('T')[0]
      })
    })

    const calendarApi = calendarRef.value.getApi()
    calendarApi.refetchEvents()
  },

  // EDITAR EVENTO
  eventClick: (info) => {
    modoEdicion.value = true
    showModal.value = true

    titulo.value = info.event.title
    descripcion.value = info.event.extendedProps.descripcion || ''
    color.value = info.event.backgroundColor

    // ✅ FIX FECHA (IMPORTANTÍSIMO)
    fechaSeleccionada.value = info.event.startStr

    eventoId.value = info.event.id
  },

  // NUEVO EVENTO
  dateClick: (info) => {
    modoEdicion.value = false
    showModal.value = true

    titulo.value = ''
    descripcion.value = ''
    color.value = '#59BF38'

    // ✅ FECHA CORRECTA
    fechaSeleccionada.value = info.dateStr
  }
})
</script>

<style scoped>
.calendar-wrapper {
  background: rgba(255, 255, 255, 0.9);
  border-radius: 16px;
  padding: 10px;
}
</style>