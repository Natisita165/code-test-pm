<template>
  <div class="container mt-4">
    <h2>Listado de Registros</h2>

    <b-button variant="primary" @click="showModal()">Nuevo</b-button>

    <b-form-group label="Filtrar por estado" class="mt-3">
      <b-form-select v-model="filterStatus" :options="statusOptions" @change="fetchRecords" />
    </b-form-group>

    <b-table :items="records" :fields="fields" striped hover class="mt-3">
      <template #cell(status)="data">
        <b-badge :variant="data.item.status ? 'success' : 'danger'">
          {{ data.item.status ? 'Activo' : 'Inactivo' }}
        </b-badge>
      </template>

      <template #cell(actions)="row">
        <b-button size="sm" variant="warning" @click="showModal(row.item)">Editar</b-button>
        <b-button size="sm" variant="danger" @click="deleteRecord(row.item.uuid)">Eliminar</b-button>
      </template>
    </b-table>

    <b-modal v-model="modalVisible" title="Registro" @hide="resetForm">
      <b-form @submit.prevent="saveRecord">
        <b-form-group label="Nombre">
          <b-form-input v-model="form.name" required />
        </b-form-group>

        <b-form-group label="Descripción">
          <b-form-textarea v-model="form.description" />
        </b-form-group>

        <b-form-group label="Código">
          <b-form-input v-model="form.code" required />
        </b-form-group>

        <b-form-group label="Estado">
          <b-form-checkbox v-model="form.status" :value="true" :unchecked-value="false">
            Activo
          </b-form-checkbox>
        </b-form-group>

        <b-button type="submit" variant="success">Guardar</b-button>
        <b-button variant="secondary" @click="modalVisible = false">Cancelar</b-button>
      </b-form>
    </b-modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const records = ref([])
const modalVisible = ref(false)
const filterStatus = ref('')
const form = ref({
  uuid: null,
  name: '',
  description: '',
  code: '',
  status: true,
})

const statusOptions = [
  { value: '', text: 'Todos' },
  { value: 'active', text: 'Activos' },
  { value: 'inactive', text: 'Inactivos' },
]

const fields = [
  'name',
  'description',
  'code',
  'status',
  { key: 'actions', label: 'Acciones' },
]

const fetchRecords = () => {
  let url = '/records'
  if (filterStatus.value) {
    url += `?status=${filterStatus.value}`
  }

  axios.get(url).then(response => {
    records.value = response.data
  })
}

const showModal = (record = null) => {
  modalVisible.value = true
  if (record) {
    form.value = { ...record }
  } else {
    resetForm()
  }
}

const resetForm = () => {
  form.value = {
    uuid: null,
    name: '',
    description: '',
    code: '',
    status: true,
  }
}

const saveRecord = () => {
  let data = {
    name: form.value.name,
    description: form.value.description,
    code: form.value.code,
    status: form.value.status ? 1 : 0,
  }

  if (form.value.uuid) {
    axios.put(`/records/${form.value.uuid}`, data).then(() => {
      fetchRecords()
      modalVisible.value = false
    })
  } else {
    axios.post('/records', data).then(() => {
      fetchRecords()
      modalVisible.value = false
    }).catch(error => {
      console.error('Error al crear el registro:', error.response?.data || error)
    })
  }
}

const deleteRecord = (uuid) => {
  if (confirm('¿Estás segura que deseas eliminar este registro?')) {
    axios.delete(`/records/${uuid}`).then(() => fetchRecords())
  }
}
onMounted(fetchRecords)
</script>