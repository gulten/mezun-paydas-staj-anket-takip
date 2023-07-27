// https://vuex.vuejs.org/en/mutations.html

export default {
  auth_request (state) {
    state.authStatus = 'loading'
  },
  auth_success (state, [login, user]) {
    localStorage.setItem('isLoggedIn', login);
    localStorage.setItem('user', user);
    state.authStatus = 'success';
    state.user = user;
    state.isLoggedIn = login;
  },
  auth_error (state) {
    state.authStatus = 'error'
  },
  logout (state) {
    localStorage.removeItem('isLoggedIn')
    localStorage.removeItem('system');
    localStorage.removeItem('role');
    localStorage.removeItem('user');
    state.authStatus = '';
    state.isLoggedIn = ''
    state.role = ''
    state.user = ''
  }
}
