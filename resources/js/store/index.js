// Lib imports
import Vue from 'vue'
import Vuex from 'vuex'

// Store functionality
import authModule from './auth'
import systemModule from './system'
import rolesModule from './roles'
import anketModule from './anket'

Vue.use(Vuex);

// Create a new store
const store = new Vuex.Store({
    modules: {
        auth: authModule,
        system: systemModule,
        roles: rolesModule,
        anket: anketModule
    }
});

export default store
