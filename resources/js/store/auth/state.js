// https://vuex.vuejs.org/en/state.html

export default {
    authStatus: '',
    isLoggedIn: localStorage.getItem('isLoggedIn') || '',
    user: localStorage.getItem('user') || '',
}
