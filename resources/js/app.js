import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import List from './components/Clients/List.vue';
import FormCreate from './components/Clients/FormCreate.vue';

const routes = [
  {
    path: '/',
    component: List
  },
  {
    path: '/create',
    component: FormCreate
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

const app = createApp({});
app.use(router);
app.mount('#app');