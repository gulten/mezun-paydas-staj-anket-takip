// https://vuex.vuejs.org/en/actions.html

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
    setBasicComponentList: ({ commit, state }, newValue) => {
        commit('SET_BASIC_COMPONENT_LIST', newValue);
        return state.basicComponentList
    }
}
