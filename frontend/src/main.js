import Vue from 'vue'
import App from './App.vue'
import store from './store'


import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.config.productionTip = false;

Vue.use(require('vue-pusher'), {
  api_key: '52a1c6b844f8a106b1a7',
  options: {
    cluster: 'us2',
    encrypted: false,
  }
});

new Vue({
  store,
  BootstrapVue,
  render: h => h(App)
}).$mount('#app');
