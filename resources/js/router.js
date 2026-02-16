import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from './components/LoginPage.vue';
import SettingsPage from './components/SettingsPage.vue';
import ReviewsPage from './components/ReviewsPage.vue';

const routes = [
    { path: '/', redirect: '/login' },
    { path: '/login', component: LoginPage },
    { path: '/settings', component: SettingsPage, meta: { requiresAuth: true } },
    { path: '/reviews', component: ReviewsPage, meta: { requiresAuth: true } }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Защита маршрутов (проверка авторизации)
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    
    if (to.meta.requiresAuth && !token) {
        // Если нужна авторизация, а токена нет — отправляем на логин
        next('/login');
    } else if (to.path === '/login' && token) {
        // Если уже авторизован и пытается зайти на логин — отправляем в настройки
        next('/settings');
    } else {
        // В остальных случаях пропускаем
        next();
    }
});

export default router;