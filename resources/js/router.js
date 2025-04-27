import { createRouter, createWebHistory } from 'vue-router'
import useAuth from '@/composables/useAuth'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/login',
            name: 'login',
            component: () => import('./views/auth/Login.vue'),
        },
        {
            path: '/',
            name: 'dashboard',
            component: () => import('./views/Dashboard.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/tasks/:id',
            name: 'tasks.show',
            component: () => import('./views/Task/Show.vue'),
            meta: { requiresAuth: true }
        },
        {
            path: '/tasks/:id/edit',
            name: 'tasks.edit',
            component: () => import('./views/Task/Edit.vue'),
            meta: { requiresAuth: true }
        }
    ]
})


router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('token');

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'login' }); 
    } else if(isAuthenticated && to.name === 'login') {

        next({ name: 'dashboard' })
    } else {
        next(); 
    }
});

export default router