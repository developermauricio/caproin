/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import CxltToastr from 'cxlt-vue2-toastr';
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css';
import 'material-icons/iconfont/material-icons.css';
import money from 'v-money';
import Vue from 'vue';
import VueFormWizard from 'vue-form-wizard';
import 'vue-form-wizard/dist/vue-form-wizard.min.css';
import VueGoodTablePlugin from 'vue-good-table';
import 'vue-good-table/dist/vue-good-table.css';
import Vuesax from 'vuesax';
import 'vuesax/dist/vuesax.css';
import { checkForm, formatDate } from './bootstrap';
import Gate from './permissions/Gate';

Vue.prototype.$gate = new Gate(window.user, roles);

Vue.filter('price', function (value, locale = "es-CO", config = { currency: 'COP', minimumFractionDigits: 0 }) {
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
})

Vue.use(VueFormWizard)
Vue.use(CxltToastr)
Vue.use(Vuesax);
Vue.use(money, { precision: 3 })

Vue.use(VueGoodTablePlugin);

// import moment from 'moment-timezone'
// moment.tz.setDefault('America/Bogota')
// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
/*COMPONENTES EXTERNOS*/
Vue.component('input-form', require('./components/InputFormComponent.vue').default);
Vue.component('accordion-component', require('./components/AccordionComponent.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS CLIENTES
=============================================*/
Vue.component('create-customer', require('./components/admin/customer/CreateCustomer.vue').default);
Vue.component('show-customer', require('./components/admin/customer/ShowCustomer').default);
Vue.component('import-error-data-customer', require('./components/admin/customer/components/ImportErrorDataCustomer.vue').default);

// Vue.component('component-customer-type-legal', require('./components/admin/customer/components/CustomerTypeLegal').default);
// Vue.component('component-customer-persona-natural', require('./components/admin/customer/components/CustomerTypePersonNatural').default);

/*=============================================
COMPONENTES PARA LOS MODULOS PROVEEDORES
=============================================*/
Vue.component('create-provider', require('./components/admin/provider/CreateProvider.vue').default);
Vue.component('show-provider', require('./components/admin/provider/ShowProvider.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS DE SUCURSALES
=============================================*/
Vue.component('branch-office', require('./components/admin/branchOffice/BranchOffice.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS DE USUARIOS
=============================================*/
Vue.component('create-users', require('./components/admin/user/CreateUser.vue').default);
Vue.component('show-user', require('./components/admin/user/ShowUser.vue').default);
Vue.component('import-error-data-user', require('./components/admin/user/components/ImportErrorDataUser.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS ZONES
=============================================*/
Vue.component('component-zones', require('./components/admin/zone/Zone.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS FACTURAS
=============================================*/
Vue.component('create-invoice', require('./components/admin/invoice/CreateInvoice.vue').default);
Vue.component('edit-invoice', require('./components/admin/invoice/EditInvoice.vue').default);
Vue.component('import-error-data-invoice', require('./components/admin/invoice/components/ImportErrorDataInvoice.vue').default);

/*=============================================
COMPONENTES PARA LOS MODULOS PRODUCTOS Y SERVICIOS
=============================================*/
Vue.component('component-create-product-service', require('./components/admin/product/CreateProductService.vue').default);
Vue.component('edit-product-service', require('./components/admin/product/EditProductService.vue').default);

/*=============================================
COMPONENTES PARA EL PERFIL
=============================================*/
Vue.component('component-modal-profile', require('./components/admin/profile/ModalProfile.vue').default);

/*=============================================
COMPONENTES PARA EL ACUERDO COMERCIAL
=============================================*/
Vue.component('create-trade-agreement', require('./components/admin/tradeAgreement/CreateTradeAgreement.vue').default);
Vue.component('show-edit-trade-agreement', require('./components/admin/tradeAgreement/ShowEditTradeAgreement.vue').default);

/*=============================================
COMPONENTES PARA MODULOS ORDENES DE COMPRA
=============================================*/
Vue.component('create-purchase-order', require('./components/admin/purchase-orders/CreatePurchaseOrder.vue').default);
Vue.component('show-purchase-order', require('./components/admin/purchase-orders/ShowPurchaseOrder.vue').default);
Vue.component('modal-tracer-purchase-order', require('./components/admin/purchase-orders/ModalTracerPurchaseOrder.vue').default);


Vue.prototype.$checkForm = checkForm;
Vue.prototype.$formatDate = formatDate;

window.eventBus = new Vue();
const app = new Vue({
  el: '#app',
});
