/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
Vue.use(VueFormWizard)

import CxltToastr from 'cxlt-vue2-toastr'
Vue.use(CxltToastr)
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css';

import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
import 'material-icons/iconfont/material-icons.css';
Vue.use(Vuesax);
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/*COMPONENTES EXTERNOS*/
Vue.component('input-form', require('./components/InputFormComponent.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS CLIENTES
=============================================*/
Vue.component('create-customer', require('./components/admin/customer/CreateCustomer.vue').default);
Vue.component('component-customer-type-legal', require('./components/admin/customer/components/CustomerTypeLegal').default);
Vue.component('component-customer-persona-natural', require('./components/admin/customer/components/CustomerTypePersonNatural').default);

/*=============================================
COMPONENTES PARA LOS MODULOS PROVEEDORES
=============================================*/
Vue.component('create-provider', require('./components/admin/provider/CreateProvider.vue').default);
Vue.component('show-provider', require('./components/admin/provider/ShowProvider.vue').default);
Vue.component('table-list-providers', require('./components/admin/provider/components/TableListProviders.vue').default);

window.eventBus = new Vue();
const app = new Vue({
    el: '#app',
});
