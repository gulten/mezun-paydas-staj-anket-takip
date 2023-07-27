// https://vuex.vuejs.org/en/actions.html
import axios from 'axios'

// The login action passes vuex commit helper that we will use to trigger mutations.
export default {
  getTableList ({ commit }, tableName) {
    this.$http.get(`/${tableName}`)
      .then(response => {
        console.log(response);
        let tableList = response.data.Keys;
        commit('setTableList', { tableList })
      })
      .catch(error => console.log(error))
  },
  updateTableItem ({ commit }, tableData) {
    return new Promise((resolve, reject) => {
      let httpmethod = tableData.method;
      axios({ url: `/${tableData.endpoint}`, method: httpmethod, data: tableData.tableItem })
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          console.log(error);
          reject(error)
        })
    })
  }
}
