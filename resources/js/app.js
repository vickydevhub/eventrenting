import './bootstrap';
import '../sass/app.scss'
import Router from '@/router'
import store from '@/store'

import Auth from './Auth.js';

import { createApp } from 'vue';

axios.defaults.baseURL = 'http://localhost:8000/api/';
//axios.defaults.headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

const app = createApp({})
app.use(Router)
app.use(Auth)
app.mount('#app')