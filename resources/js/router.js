import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMeta from 'vue-meta'

Vue.use(VueRouter)
Vue.use(VueMeta)

import * as auth from './services/auth'

/* Dashboard */
import Index from './components/pages/home/Index'
import Home from './components/pages/home/Home'
import Detail from './components/pages/home/Detail'

import Suspicious from './components/pages/suspicious/Index'

/* Reports */
import Reports from './components/pages/reports/Index'
import Authorized from './components/pages/reports/Authorized'
import Records from './components/pages/reports/Records' 

/* Settings */
import Settings from './components/pages/settings/Index'
import Notification from './components/pages/settings/Notification'
import Verified from './components/pages/settings/Verified' 

import Scenario from './components/pages/settings/Scenario'

import Login from './components/pages/auth/Login'

import Control from './components/pages/admin/Index'

import NotFound from './components/pages/errors/404'

const routes = [
    {
        path: '/',
        component: Index,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'home',
                component: Home,
                meta: { requiresAuth: true }
            },
            {
                path: 'detail',
                name: 'detail',
                component: Detail,
                meta: { requiresAuth: true }
            },
        ]
    },
    {
        path: '/suspicious',
        name: 'suspicious',
        component: Suspicious,
        meta: { requiresAuth: true },
    },
    {
        path: '/reports',
        component: Reports,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'authorized',
                name: 'reports.authorized',
                component: Authorized,
                meta: { requiresAuth: true },
            },
            {
                path: 'verified',
                name: 'reports.records',
                component: Records,
                meta: { requiresAuth: true }
            },
        ]
    },
    {
        path: '/settings',
        component: Settings,
        meta: { requiresAuth: true },
        children: [
            {
                path: 'notification',
                name: 'settings.notification',
                component: Notification,
                meta: { requiresAuth: true },
            },
            {
                path: 'verified',
                name: 'settings.verified',
                component: Verified,
                meta: { requiresAuth: true }
            },
            {
                path: 'scenario',
                name: 'settings.scenario',
                component: Scenario,
                meta: { requiresAuth: true }
            }
        ]
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        beforeEnter(to, from, next) {
            if(!auth.isLoggedIn()) {
                return next()
            }
            return next({name:'home'})
        }
    },
    {
        path: '/control',
        name: 'control',
        component: Control,
        meta: { requiresAuth: true },
        beforeEnter(to, from, next) {
            if(auth.getUserRole() === 'admin') {
                next();
            } else {
                next('/404')
            }
        }
    },
    {
        path: '*',
        name: '404',
        component: NotFound
    }
]

const router = new VueRouter({
    mode: 'history',
    //linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    routes
})

router.beforeEach((to, from, next) => {
    if(to.meta.requiresAuth && !auth.isLoggedIn()) {
        return next({name:'login'})
    }
    return next()
})

const originalPush = VueRouter.prototype.push;
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
};

export default router