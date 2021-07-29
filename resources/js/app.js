/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import 'material-icons/iconfont/material-icons.css';
import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
Vue.use(VueFormWizard)


Vue.use(Vuesax);
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/*COMPONENTES EXTERNOS*/
Vue.component('input-form', require('./components/InputFormComponent.vue').default);

Vue.component('create-customer', require('./components/admin/customer/CreateCustomer.vue').default);
Vue.component('component-customer-type-legal', require('./components/admin/customer/components/CustomerTypeLegal').default);
Vue.component('component-customer-persona-natural', require('./components/admin/customer/components/CustomerTypePersonNatural').default);

window.eventBus = new Vue();
const app = new Vue({
  el: '#app',
});
