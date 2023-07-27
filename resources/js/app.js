import 'babel-polyfill';

import Vue from 'vue';

// Sync router with store
import { sync } from 'vuex-router-sync';

import axios from 'axios';
import VueAxios from 'vue-axios';
import Vuex from 'vuex';
import vuetify from './plugins/vuetify';
import Notifications from 'vue-notification';
import velocity from 'velocity-animate';
import store from './store';
import router  from './router';
import VueMask from 'v-mask';
import VueHtmlToPaper from 'vue-html-to-paper';
//import Notifications from 'vue-notification';

// Application imports
import App from './App.vue';

Vue.config.productionTip = true;

Vue.use(VueAxios, axios, Vuex);
Vue.use(Notifications, { velocity });
Vue.use(VueMask);


const printOptions = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://fonts.googleapis.com/css?family=Nunito:400,500,500i,600,700,800&subset=latin,latin-ext',
        'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css',
        'https://fonts.googleapis.com/icon?family=Material+Icons',
        'https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css',
        'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css',
        '/css/app.css'
    ]
}

Vue.use(VueHtmlToPaper, printOptions);

//Vue.prototype.$http = axios;
// Sets the default url used by all of this axios instance's requests
const api = axios.create({
    withCredentials : true,
    baseURL: '/api',
    headers: {
        "Accept" : "application/json",
        "Content-Type": "application/json;charset=utf-8",
    }

});

let role = localStorage.getItem('role');

function isLoggedIn() {
    return localStorage.getItem('isLoggedIn')
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (!isLoggedIn()) {
            next({
                name: 'Login',
            })
        } else {
            next()
        }
    } else if (to.matched.some(record => record.meta.requiresVisitor)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (isLoggedIn()) {
            next({
                name: 'Select System',
            })
        } else {
            next()
        }
    } else {
        next() // make sure to always call next()!
    }
});

api.interceptors.request.use(config => {
        if (!isLoggedIn()) {
            router.push('/login');
        }

        return config;
    },
    error => Promise.reject(error)
);

api.interceptors.response.use(function (response) {
    // Do something with response data
    return response;
}, function (error) {
        if ((error.response.status === 401) || (error.response.data.message === 'Unauthenticated.')){
        router.push('/login');
    }
    return Promise.reject(error);

});





window.api = api;
// Sync store with router
sync(store, router);

Vue.config.productionTip = false;

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App),
    components: { App }
}).$mount('#app');
