// https://vuex.vuejs.org/en/actions.html

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
  saving_system ({ commit }, data) {
      commit('save_system', data.system);
      localStorage.setItem('system', data.system );
  },

  removing_system ({ commit }) {
      commit('remove_system');
      localStorage.removeItem('system');
  },

}
