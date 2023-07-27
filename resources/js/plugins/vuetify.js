import '@mdi/font/css/materialdesignicons.css'
import Vue from "vue";
import Vuetify from "vuetify";
import theme from './theme';
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vuetify);

export default new Vuetify({
    icons: {
        iconfont: 'mdi',  // 'mdi' || 'mdiSvg' || 'md' || 'fa' || 'fa4'
        theme
    }
})
