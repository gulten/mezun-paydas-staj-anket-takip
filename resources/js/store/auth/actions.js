// https://vuex.vuejs.org/en/actions.html
import Vue from 'vue'
import axios from 'axios'

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
    login ({ commit }, userData) {
        return new Promise((resolve, reject) => {
            const { user } = userData;
            commit('auth_request');
            axios.get('/sanctum/csrf-cookie'
                , {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    withCredentials: true,
                }).then(response => {
                axios.post('/api/login', JSON.stringify(user), { headers: {'Content-Type':'application/json'}})
                .then(response => {
                    if(response.data.success) {

                        // mutation to change state properties to the values passed along
                        commit('auth_success', ['true', response.data.data.username]);
                        Vue.notify({
                            group: 'loggedIn',
                            type: 'success',
                            title: 'Hoşgeldiniz, ' + response.data.data.username,
                            text: 'Sisteme Giriş Yaptınız',
                        })
                        resolve(response)
                    }
                })
                .catch(err => {
                    console.log('login error');
                    commit('auth_error');
                    localStorage.removeItem('isLoggedIn');
                    Vue.notify({
                        group: 'loggedIn',
                        type: 'error',
                        title: 'Üzgünüz, Bir Sorun Var!',
                        text: 'Lütfen Bilgilerinizi Kontrol Ediniz, Problem Devam Ederse Sistem Yöneticiniz İle İletişime Geçiniz.',
                    })
                    reject(err)
                })
            })
        })
    },
    logout ({ commit }) {
        return new Promise((resolve, reject) => {
            api.get('/logout').then(response => {
                commit('logout');

                Vue.notify({
                    group: 'loggedIn',
                    type: 'success',
                    title: 'İşlem Yapıldı',
                    text: 'Sistemden Çıkış Yaptınız.',
                })
            });
            resolve()
        })
    },
    forgot({ commit }, userData) {
        return new Promise((resolve, reject) => {
            const { user } = userData;
            commit('auth_request');
            axios.post('/api/forgot', JSON.stringify(user), { headers: { 'Content-Type': 'application/json' } })
                    .then(response => {
                        if (response.data.success) {
                            Vue.notify({
                                group: 'loggedIn',
                                type: 'success',
                                title: 'Bilgilendirme',
                                text: 'İşlem Başarılı',
                            });
                            resolve(response)
                        }
                    })
                    .catch(err => {
                        Vue.notify({
                            group: 'loggedIn',
                            type: 'error',
                            title: 'Üzgünüz, Bir Sorun Var!',
                            text: 'Parola Sıfırlama Maili Gönderilemedi, Lütfen Mail Adresinizi Kontrol Ediniz. Eğer Problem Devam Ederse Sistem Sorumlusu İle İletişim Kurunuz.',
                        })
                        //commit('auth_error');
                        reject(err)
                    })
        })
    },
    reset({ commit }, userData) {
        return new Promise((resolve, reject) => {
            const { user } = userData;
            commit('auth_request');
            axios.post('/api/reset/password', JSON.stringify(user), { headers: { 'Content-Type': 'application/json' } })
                .then(response => {
                    if (response.data.success) {
                        Vue.notify({
                            group: 'loggedIn',
                            type: 'success',
                            title: 'Bilgilendirme',
                            text: 'İşlem Başarılı',
                        })
                        resolve(response)
                    }
                })
                .catch(err => {
                    Vue.notify({
                        group: 'loggedIn',
                        type: 'error',
                        title: 'Üzgünüz, Bir Sorun Var!',
                        text: err.response.data.message,
                    })
                    //commit('auth_error');
                    reject(err)
                })
        })
    },
  /*refreshtoken ({ commit }) {
    axios.get('/refresh')
      .then(response => {
        const token = response.data.access_token;
        localStorage.setItem('token', token);
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
        commit('auth_success', { token })
      })
      .catch(error => {
        console.log('refresh token error');
        commit('logout');
        localStorage.removeItem('token');
        console.log(error)
      })
  },*/

  // autoRefreshToken ({ commit }) {
  //   return new Promise((resolve, reject) => {
  //     setInterval(function () {
  //       // code goes here that will be run every 20 minutes.
  //       axios.get('/refresh')
  //         .then(response => {
  //           const token = response.data.access_token
  //           localStorage.setItem('token', token)
  //           axios.defaults.headers.common['Authorization'] = 'Bearer ' + token
  //           commit('auth_success', { token })
  //           resolve(response)
  //         })
  //         .catch(error => {
  //           console.log('refresh token error')
  //           console.log(error)
  //           reject(error)
  //         })
  //     }, 1200000)
  //   }
  //   )
  // },

}
