import './bootstrap';
import { createApp } from 'vue';
import axios from 'axios';
import router from './router';
import App from './App.vue';

// Настройка axios
axios.defaults.baseURL = 'http://yandex-maps-int';
axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

const app = createApp(App);
app.use(router);
app.mount('#app');
