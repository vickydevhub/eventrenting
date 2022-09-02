import { createWebHistory, createRouter } from 'vue-router'
import store from '@/store'
import Auth from '../Auth';
/* Guest Component */
const Login = () => import('@/components/Login.vue')
const Register = () => import('@/components/Register.vue')
/* Guest Component */

/* Layouts */
const DahboardLayout = () => import('@/components/layouts/Default.vue')
/* Layouts */

/* Authenticated Component */
const Dashboard = () => import('@/components/Dashboard.vue')
/* Authenticated Component */
import Events from '../components/events/List.vue'
import createEvents from '../components/events/Create.vue'

const routes = [
    {
        name: "login",
        path: "/login",
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component: Register,
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: "/",
        component: DahboardLayout,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                name: "dashboard",
                path: '/',
                component: Dashboard,
                meta: {
                    title: `Dashboard`
                }
            }
        ]
    },
    {
        path: "/events",
        component: DahboardLayout,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                name: "events",
                path: '/events',
                component: Events,
                meta: {
                    title: `Events`
                }
            } 
        ]
    },
    {
        path: "/events/add",
        component: DahboardLayout,
        meta: {
            requiresAuth: true
        },
        children: [
             
            {
                name: "newEvent",
                path: '/events/add',
                component: createEvents,
                meta: {
                    title: `Add Events`
                }
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth) ) {
        if (Auth.check()) {
            next();
            return;
        } else {
            router.push('/login');
        }
    } else {
        next();
    }
});

export default router