<template>
  <div class="calendar-wrapper">
    <FullCalendar ref="calendarRef" :options="calendarOptions" />
  </div>

  <!-- MODAL -->
  <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black/40 z-50">
    <div class="bg-white rounded-2xl p-6 w-96 shadow-xl">

      <!-- título -->
      <input
        v-model="titulo"
        placeholder="Título del evento *"
        class="w-full text-xl font-bold border-b-2 mb-2 outline-none text-center border-gray-300 focus:border-green-500"
      />

      <!-- fecha -->
      <input
        v-model="fechaSeleccionada"
        type="date"
        class="w-full border rounded-lg p-2 mb-3"
      />

      <!-- descripción -->
      <textarea
        v-model="descripcion"
        placeholder="Descripción (opcional)"
        class="w-full border rounded-lg p-2 mb-4 text-sm text-gray-600"
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

// 🧠 ESTADO
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

// 🔹 Reset modal
const cerrarModal = () => {
  showModal.value = false
  modoEdicion.value = false
  titulo.value = ''
  descripcion.value = ''
}

//  GUARDAR EVENTO
const guardarEvento = async () => {
  console.log('titulo:', titulo.value)
  console.log('fecha:', fechaSeleccionada.value)
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
        inicio: fechaSeleccionada.value, // 👈 SOLO FECHA
        color: color.value,
        descripcion: descripcion.value
      })
    })

    if (!res.ok) {
      const error = await res.json()
      console.log('ERROR:', error)
      alert('Error al guardar')
      return
    }

    cerrarModal()
    calendarRef.value.getApi().refetchEvents()

  } catch (error) {
    console.error('Error fetch:', error)
  }
}

// ELIMINAR
const eliminarEvento = async () => {
  await fetch(`http://localhost:8000/api/eventos/${eventoId.value}`, {
    method: 'DELETE'
  })

  cerrarModal()
  calendarRef.value.getApi().refetchEvents()
}

// CALENDARIO
const calendarOptions = ref({
  plugins: [dayGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  events: 'http://localhost:8000/api/eventos',
  editable: true,
  displayEventTime: false, 

  // mover evento
  eventDrop: async (info) => {
    await fetch(`http://localhost:8000/api/eventos/${info.event.id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        inicio: info.event.startStr.split('T')[0]
      })
    })
  },

  // editar evento
  eventClick: (info) => {
  modoEdicion.value = true
  showModal.value = true

  titulo.value = info.event.title
  descripcion.value = info.event.extendedProps.descripcion || ''
  color.value = info.event.backgroundColor

  //  FIX SEGURO
  const fecha = info.event.start
    ? info.event.start.toISOString().split('T')[0]
    : ''

  fechaSeleccionada.value = fecha

  eventoId.value = info.event.id
},

  // nuevo evento
  dateClick: (info) => {
  modoEdicion.value = false
  showModal.value = true

  titulo.value = ''
  descripcion.value = ''
  color.value = '#59BF38'

  // 👇 FIX CLAVE
  const fecha = new Date(info.date)
    .toISOString()
    .split('T')[0]

  fechaSeleccionada.value = fecha
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