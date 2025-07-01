<template>
  <div class="container mt-4">
    <h2>Listado de Registros</h2>

    <b-button variant="primary" @click="showModal()">Nuevo</b-button>
    <b-alert
      v-if="showAlert"
      variant="success"
      dismissible
      class="mt-3"
      @dismissed="showAlert = false"
    >
      {{ successMessage }}
    </b-alert>

    <b-alert
      v-if="showError"
      variant="danger"
      dismissible
      class="mt-3"
      @dismissed="showError = false"
    >
      {{ errorMessage }}
    </b-alert>
    <b-form-group label="Filtrar por estado" class="mt-3">
      <b-form-select v-model="filterStatus" :options="statusOptions" />
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

    <b-modal v-model="modalVisible" :title="modalTitle" @hide="resetForm">
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
          <b-form-checkbox v-model="form.status" :value="1" :unchecked-value="0">
            Activo
          </b-form-checkbox>
        </b-form-group>

        <b-button type="submit" variant="success">{{ submitLabel }}</b-button>
        <b-button variant="secondary" @click="modalVisible = false">Cancelar</b-button>
      </b-form>
    </b-modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { watch } from 'vue'

const errorMessage = ref('')
const showError = ref(false)
const successMessage = ref('')
const showAlert = ref(false)
const submitLabel = ref('Crear')
const modalTitle = ref('Nuevo Registro')
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
  { value: '1', text: 'Activos' },
  { value: '0', text: 'Inactivos' },
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
  if (filterStatus.value !== '') {
    url += `?status=${filterStatus.value}`
  }

  axios.get(url).then(response => {
    records.value = response.data
  })
}

const showModal = (record = null) => {
  modalVisible.value = true
  if (record) {
    modalTitle.value = 'Editar Registro'
    submitLabel.value = 'Actualizar'
    form.value = {
      ...record,
      status: Boolean(Number(record.status))
    }
  } else {
    modalTitle.value = 'Nuevo Registro'
    submitLabel.value = 'Crear'
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
  const data = {
    name: form.value.name,
    description: form.value.description,
    code: form.value.code,
    status: form.value.status ? 1 : 0,
  }

  const handleSuccess = (msg) => {
    successMessage.value = msg
    showAlert.value = false
    showError.value = false
    modalVisible.value = false
    fetchRecords()

    setTimeout(() => {
    showAlert.value = false
  }, 3000)
  }

  const handleError = (error) => {
    showError.value = false

    if (error.response?.data?.message?.includes('code') && error.response?.status === 422) {
      errorMessage.value = 'El código ingresado ya está en uso.'
    } else {
      errorMessage.value = 'Ocurrió un error al guardar el registro.'
    }

    setTimeout(() => {
    showError.value = false
  }, 5000)
  }

  if (form.value.uuid) {
    axios.put(`/records/${form.value.uuid}`, data)
      .then(() => handleSuccess('Registro actualizado con éxito.'))
      .catch(handleError)
  } else {
    axios.post('/records', data)
      .then(() => handleSuccess('Registro creado con éxito.'))
      .catch(handleError)
  }
}

const deleteRecord = (uuid) => {
  if (confirm('¿Estás segura que deseas eliminar este registro?')) {
    axios.delete(`/records/${uuid}`).then(() => fetchRecords())
  }
}
onMounted(fetchRecords)

watch(filterStatus, () => {
  fetchRecords()
})
</script>