// https://vuex.vuejs.org/en/actions.html

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
  saving_role ({ commit }, data) {
      commit('save_role', data.role);
      localStorage.setItem('role', data.role );
  },

  removing_role ({ commit }) {
      commit('remove_role');
      localStorage.removeItem('role');
  },

}
