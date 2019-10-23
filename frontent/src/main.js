import Vue from 'vue'
import App from './App.vue'
import store from './store'
import VueEcho from 'vue-echo'
import Pusher from 'pusher-js'

import BootstrapVue from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

// Enable pusher logging - don't include this in production
Pusher.logToConsole = true;

Vue.config.productionTip = false;

Vue.use(VueEcho, {
  broadcaster: 'pusher',
  //authEndpoint : 'http://localhost:8080/broadcasting/auth',
  //authEndpoint : 'http://api.local',
  //authEndpoint : 'http://psapi.sergeysafarov.com/broadcasting/auth',
  key: '52a1c6b844f8a106b1a7',
  cluster: 'us2',
  forceTLS: false
});

new Vue({
  store,
  BootstrapVue,
  Pusher,
  VueEcho,
  render: h => h(App)
}).$mount('#app');
