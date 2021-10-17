import Vue from 'vue';
import App from './App.vue';
import store from './store/index';
import VueInsProgressBar from 'vue-ins-progress-bar';

const options = {
  position: 'fixed',
  show: true,
  height: '3px',
};
Vue.use(VueInsProgressBar, options);

Vue.config.productionTip = false;

new Vue({
  render: h => h(App),
  store,
}).$mount('#app');
