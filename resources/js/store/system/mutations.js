// https://vuex.vuejs.org/en/mutations.html

export default {
  save_system (state, { system }) {
    state.system = system;
  },
  remove_system (state) {
    state.system = '';
  }
}
