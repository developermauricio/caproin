/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { Chart, registerables } from 'chart.js';
import Vue from 'vue';
const { formatDate } = require('./common');

const parsePrice = function (value, locale = "es-CO", config = { currency: 'COP', minimumFractionDigits: 0 }) {
  value = Number(value);
  if (!value || isNaN(value)) {
    value = 0;
  }

  var formatter = new Intl.NumberFormat(locale, Object.assign({ style: 'currency' }, config));

  /*
  configuración dolar
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 0
    });
  */
  return formatter.format(value);
};

const shortNumber = function (value) {
  if (value < 1000) {
    return 100
  } else if (value < 1000000) {
    return (value / 1000).toFixed(1) + 'K'
  }
  return (value / 1000000).toFixed(1) + 'M'
}

Vue.filter('price', parsePrice)

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/*COMPONENTES EXTERNOS*/
Vue.component('input-form', require('./components/InputFormComponent.vue').default);


/*=============================================
COMPONENTES PARA EL PERFIL
=============================================*/
Vue.component('component-modal-profile', require('./components/admin/profile/ModalProfile.vue').default);


/*=============================================
COMPONENTES PARA REPORTES
=============================================*/
Vue.component('reports-wallet-component', require('./components/admin/reports/pages/ReportsWalletComponent.vue').default);
Vue.component('reports-logistic-component', require('./components/admin/reports/pages/ReportsLogisticComponent.vue').default);
Vue.component('chart-js', require('./components/admin/reports/components/ChartJS.vue').default);


Chart.register(...registerables)

Vue.prototype.$chart = Chart;
Vue.prototype.$price = parsePrice;
Vue.prototype.$shortNumber = shortNumber;
Vue.prototype.$axios = require('axios');
Vue.prototype.$axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.prototype.$formatDate = formatDate;

window.eventBus = new Vue();
const app = new Vue({
  el: '#app',
});
