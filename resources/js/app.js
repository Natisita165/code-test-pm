import { createApp } from 'vue';
import BootstrapVue3 from 'bootstrap-vue-3';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';
import axios from 'axios';

import RecordCrud from './components/RecordCrud.vue';

axios.defaults.baseURL = 'http://localhost:8000/api';  // O la URL correcta si usas Docker o algo diferente

// Hacer la solicitud
axios.get('/records')
    .then(response => console.log(response.data))
    .catch(error => console.error('Error al obtener los registros:', error));


const app = createApp(RecordCrud); 
app.use(BootstrapVue3);
app.mount('#app');