/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import { Chart, registerables } from 'chart.js';
import Vue from 'vue';
const { formatDate } = require('./common');

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
Vue.component('reports-component', require('./components/admin/reports/pages/ReportsComponent.vue').default);
Vue.component('chart-js', require('./components/admin/reports/components/ChartJS.vue').default);


Chart.register(...registerables)
Vue.prototype.$chart = Chart;
Vue.prototype.$axios = require('axios');
Vue.prototype.$axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.prototype.$formatDate = formatDate;

window.eventBus = new Vue();
const app = new Vue({
  el: '#app',
});
