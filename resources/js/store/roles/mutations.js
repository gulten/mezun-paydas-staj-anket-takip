// https://vuex.vuejs.org/en/mutations.html

export default {
  save_role (state, { role }) {
    state.role = role;
  },
  remove_role (state) {
    state.role = '';
  }
}
