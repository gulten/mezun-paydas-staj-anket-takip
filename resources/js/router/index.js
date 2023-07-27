import Vue from 'vue';
import VueRouter from 'vue-router';
import store  from '../store';
// Routes
import bbys from './bbys';
import mezun from './mezun';
import anket from './anket';
import isyeriegitimi from './isyeriegitimi';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        bbys,
        mezun,
        anket,
        isyeriegitimi,
        {
            path: '/',
            component: () => import(`../container/General`),
            redirect: '/select_system',
            children: [
                {
                    path: '/',
                    meta: {
                        name: '',
                        requiresAuth: false
                    },
                    component: () =>
                        import(/* webpackChunkName: "routes" */ `../views/sessions/Login`),
                    // redirect if already signed in
                    beforeEnter: (to, from, next) => {
                        if (store.getters.authorized) {
                            next('/select_system')
                        } else {
                            next()
                        }
                    },
                },
                {
                    path: '/login',
                    component: () => import(`../views/sessions/Login`),
                    name: 'Login'
                },
                {
                    path: '/password/forgot',
                    component: () => import(`../views/sessions/Forgot`),
                    name: 'Forgot'
                },
                {
                    path: '/sended/mail',
                    component: () => import(`../views/sessions/SendedMail`),
                    name: 'Sended Mail'
                },
                {
                    path: '/reset-password/:token',
                    component: () => import(`../views/sessions/Reset`),
                    name: 'Reset Mail'
                },
                {
                    path: '/select_system',
                    meta: {
                        name: 'Select System',
                        requiresAuth: true
                    },
                    component: () => import(`../views/select/SelectSystem`),
                    name: 'Select System'
                },
                {
                    path: '/select_role',
                    meta: {
                        name: 'Select Role',
                        requiresAuth: true
                    },
                    component: () => import(`../views/select/SelectRole`),
                    name: 'Select Role'
                },
                {
                    path: '*',
                    name: '404',
                    component: () =>
                        import('../views/error/404.vue')
                }
            ]
        }
    ]
});



export default router;
