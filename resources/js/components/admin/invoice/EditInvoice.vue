<template>
  <div class="modal-content">
    <input type="hidden" @click="traerDatosInvoice" id="traerDatosBotonInvoice"/>
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Factura</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <!--=====================================
        INFORMACIÓN
      ======================================-->
      <div class="row" v-show="showDetailInvoice">
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Número de factura:</label>
            <p v-text="invoice_number"></p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Estado:</label>
            <p>{{ stateDetail.name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Número de factura electrónica:</label>
            <p v-text="electronic_invoice_number"></p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de emisión de la factura:</label>
            <p>{{ moment(date_issue).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de vencimiento de la factura:</label>
            <p>{{ moment(expiration_date).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Tipo Factura:</label>
            <p>{{ invoice_type_detail.name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Cliente:</label>
            <p>{{ customerDetail.business_name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Valor:</label>
            <p>${{  dollarUSLocale.format(value) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de recibo por parte del cliente:</label>
            <p>{{ moment(date_received_client).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de pago por parte del cliente:</label>
            <p>{{ moment(date_payment_client).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Número de factura casa representante:</label>
            <p>{{ invoice_number_house_representative }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Valor de la comisión:</label>
            <p>${{ dollarUSLocale.format(commission_value) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de pago por parte del cliente:</label>
            <p>{{ moment(commission_receipt_date).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label>Fecha de pago por parte del cliente:</label>
            <p>{{ moment(new_agreed_payment_date).locale('es').format("dddd, MMMM Do YYYY") }}</p>
          </div>
        </div>
      </div>
      <div class="row" v-show="showDetailInvoice">
        <div class="col-12">
          <div class="form-group">
            <label>Comentarios u Observaciones:</label>
            <p>{{ comments }}</p>
          </div>
        </div>
      </div>
      <!--=====================================
        DATOS PARA EDITAR
      ======================================-->
      <form class="" id="validateCreateInvoiceEdit" method="post">
        <div v-show="showEditInvoice" class="row" style="display: none">
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceNumberEdit"
              label="Número de factura"
              pattern="all"
              errorMsg="Ingrese número de factura válido"
              requiredMsg="El número de factura es obligatorio"
              :modelo.sync="invoice_number"
              :required="true"
              :msgServer.sync="errors.invoice_number"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Estado"
              id="txtInvoiceStateEdit"
              errorMsg
              requiredMsg="El estado es obligatorio"
              :required="true"
              :modelo.sync="state"
              :msgServer.sync="errors.state"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsStateInvoice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado de la factura',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateInvoice=>stateInvoice.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceElectronicNumberEdit"
              label="Número de factura electrónica"
              pattern="all"
              errorMsg="Ingrese número de factura electrónica válido"
              requiredMsg="El número de la factura electrónica es obligatorio"
              :modelo.sync="electronic_invoice_number"
              :required="true"
              :msgServer.sync="errors.electronic_invoice_number"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de emisión de la factura"
              id="txtDateIssueInvoice"
              pattern="all"
              errorMsg="Ingrese una fecha de emisión válida"
              requiredMsg="La fecha de emisión es obligatorio"
              :required="true"
              :modelo.sync="date_issue"
              :msgServer.sync="errors.date_issue"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de vencimiento de la factura"
              id="txtExpirationDateInvoice"
              pattern="all"
              errorMsg="Ingrese una fecha de vencimiento válida"
              requiredMsg="La fecha de vencimiento es obligatorio"
              :required="true"
              :modelo.sync="expiration_date"
              :msgServer.sync="errors.expiration_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Tipo de Factura"
              id="txtInvoiceTypeEdit"
              errorMsg
              requiredMsg="El tipo de factura es requerida"
              :required="true"
              :modelo.sync="invoice_type"
              :msgServer.sync="errors.invoice_type"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsTypeInvoice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Factura',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeInvoice=>typeInvoice.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Cliente"
              id="txtInvoiceCustomerEdit"
              errorMsg
              requiredMsg="El cliente es obligatorio"
              :required="true"
              :modelo.sync="customer"
              :msgServer.sync="errors.customer"
              type="multiselect"
              selectLabel="Selecciona un cliente"
              :multiselect="{ options: optionsCustomerInvoice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Selecciona un cliente',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': customer=>customer.business_name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              type="money"
              label="Valor"
              id="txtValueInvoice"
              pattern="all"
              errorMsg="Ingrese un valor válido"
              requiredMsg="El valor es obligatorio"
              :required="true"
              :modelo.sync="value"
              :msgServer.sync="errors.value"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de recibo por parte del cliente"
              id="txtDateReceivedClient"
              pattern="all"
              errorMsg="Ingrese una fecha de recibo válida"
              requiredMsg="La fecha de recibo es obligatorio"
              :required="true"
              :modelo.sync="date_received_client"
              :msgServer.sync="errors.date_received_client"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de pago por parte del cliente"
              id="txtDatePaymentClient"
              pattern="all"
              errorMsg="Ingrese una fecha de pago válida"
              requiredMsg="La fecha de pago es obligatorio"
              :required="true"
              :modelo.sync="date_payment_client"
              :msgServer.sync="errors.date_payment_client"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de factura casa representante"
              id="txtInvoiceDateHouseManufacturer"
              pattern="all"
              errorMsg="Ingrese una fecha casa representante válida"
              requiredMsg="La fecha casa representante es obligatorio"
              :required="true"
              :modelo.sync="invoice_date_house_manufacturer"
              :msgServer.sync="errors.invoice_date_house_manufacturer"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceNumberHouseRepresentativeEdit"
              label="Número de factura casa representate"
              pattern="all"
              errorMsg="Ingrese número de factura casa representante válido"
              requiredMsg="El número de la factura casa representante es obligatorio"
              :modelo.sync="invoice_number_house_representative"
              :required="true"
              :msgServer.sync="errors.invoice_number_house_representative"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              type="money"
              label="Valor de la comisión"
              id="txtValueCommisionInvoice"
              pattern="all"
              errorMsg="Ingrese un valor de comisión válido"
              requiredMsg="El valor de la comisión es obligatorio"
              :required="true"
              :modelo.sync="commission_value"
              :msgServer.sync="errors.commission_value"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de recibo de comisión"
              id="txtCommissionReceiptDate"
              pattern="all"
              errorMsg="Ingrese una fecha de recibo de comisión válida"
              requiredMsg="La fecha de recibo de comisión es obligatorio"
              :required="true"
              :modelo.sync="commission_receipt_date"
              :msgServer.sync="errors.commission_receipt_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Nueva fecha concertada de pago"
              id="txtNewAgreedPaymentDate"
              pattern="all"
              errorMsg="Ingrese una fecha válida"
              requiredMsg="La fecha concertada de pago es obligatorio"
              :required="true"
              :modelo.sync="new_agreed_payment_date"
              :msgServer.sync="errors.new_agreed_payment_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
        </div>
        <div v-show="showEditInvoice" class="row">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Comentarios u observaciones"
              id="txtCommentsInvoiceEdit"
              pattern="all"
              errorMsg="Ingrese texto válido"
              requiredMsg="Los comentarios u observaciones son requeridos"
              :required="true"
              :modelo.sync="comments"
              :msgServer.sync="errors.comments"
              :options="{
                                                rows: 5
                                                }"
            >
            </input-form>
          </div>
        </div>
      </form>
      <!--=====================================
        BOTONES PARA EDITAR
      ======================================-->
      <div class="row pl-1">
        <div class="demo-inline-spacing">
          <!-- Boton para agregar archivos -->
          <button v-if="showDetailInvoice === true" @click="btnEditInvoice"
                  type="button"
                  class="btn btn-primary waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Editar
          </button>
          <button v-if="showEditInvoice === true" @click="btnCancelEditInvoice"
                  type="button"
                  class="btn btn-gris waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Cancelar
          </button>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button v-if="showDetailInvoice === true" type="button" data-dismiss="modal" class="btn btn-gris">Cerrar
      </button>
      <button v-if="showEditInvoice === true" @click="btnCancelEditInvoice" class="btn btn-gris">Cancelar</button>
      <button v-if="showEditInvoice === true" @click="createNewInvoice()" type="button" class="btn btn-primary">
        Actualizar Factura
      </button>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'
import Swal from "sweetalert2";

export default {
  name: "EditInvoice",
  components: {
    Multiselect,
    Datepicker,
    moment
  },
  data() {
    return {
      showEditInvoice: false,
      showDetailInvoice: true,
      moment: moment,
      en: en,
      es: es,

      invoice_number: '',
      electronic_invoice_number: '',
      comments: '',
      state: null,
      stateDetail: {
        name: null,
      },
      dollarUSLocale: Intl.NumberFormat('es-US'),
      date_issue: null,
      expiration_date: null,
      invoice_type: null,
      invoice_type_detail: {
        name: null
      },
      customer: null,
      customerDetail: {
        name: null
      },
      date_received_client: null,
      date_payment_client: null,
      invoice_date_house_manufacturer: null,
      invoice_number_house_representative: null,
      commission_receipt_date: null,
      new_agreed_payment_date: null,
      value: '0',
      commission_value: '0',
      errors: {},
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 0
      },
      idInvoice: null,
      optionsStateInvoice: [],
      optionsTypeInvoice: [],
      optionsCustomerInvoice: [],
    }
  },
  methods: {

    btnEditInvoice() {
      this.showDetailInvoice = false;
      this.showEditInvoice = true;
    },

    btnCancelEditInvoice() {
      this.showEditInvoice = false;
      this.showDetailInvoice = true;

    },

    createNewInvoice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateInvoiceEdit .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          return;
        }

        const data = new FormData()
        data.append('invoice_number', this.invoice_number);
        data.append('state', JSON.stringify(this.state));
        data.append('electronic_invoice_number', this.electronic_invoice_number);
        data.append('date_issue', moment(this.date_issue).format("YYYY-MM-DD HH:mm:ss"));
        data.append('expiration_date', moment(this.expiration_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_type', JSON.stringify(this.invoice_type));
        data.append('customer', JSON.stringify(this.customer));
        data.append('value', this.value);
        data.append('date_received_client', moment(this.date_received_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('date_payment_client', moment(this.date_payment_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_date_house_manufacturer', moment(this.invoice_date_house_manufacturer).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_number_house_representative', this.invoice_number_house_representative);
        data.append('commission_value', this.commission_value);
        data.append('commission_receipt_date', moment(this.commission_receipt_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('new_agreed_payment_date', moment(this.new_agreed_payment_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('comments', this.comments);
        data.append('idInvoice', this.idInvoice);

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar?',
          confirmButtonColor: "#D9393D",
          cancelButtonColor: "#7D7E7E",
          confirmButtonText: 'Aceptar',
          cancelButtonText: 'Cancelar',
          customClass: "swal-confirmation",
          showCancelButton: true,
          reverseButtons: true,
          allowOutsideClick: false,
        }).then((result) => {
          if (result.value) {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Registrando Factura...'
            })
            axios.post('/api/register/update-invoice', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Factura actualizada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/facturas";
            }).catch(err => {
              console.log('mostrando el error', err)
              this.$toast.error({
                title: 'Algo salio mal',
                message: 'Comunícate con el administrador',
                showDuration: 1000,
                hideDuration: 8000,
              })
            });
            setTimeout(() => {
              this.$vs.loading.close()
            }, 2000)
          }
        })
      }, 200);
    },

    traerDatosInvoice(e) {
      e.preventDefault();
      this.idInvoice = +e.target.value;
      console.log('FACTURA ID', this.idInvoice)
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })

      setTimeout(() => {
        axios.get('/api/data-invoice/' + this.idInvoice).then(resp => {

          this.invoice_number = resp.data.data.invoice_number
          this.state = resp.data.data.state
          this.stateDetail = resp.data.data.state
          this.electronic_invoice_number = resp.data.data.electronic_invoice_number
          this.date_issue = resp.data.data.date_issue
          this.expiration_date = resp.data.data.expiration_date
          this.date_received_client = resp.data.data.date_received_client
          this.date_payment_client = resp.data.data.date_payment_client
          this.invoice_date_house_manufacturer = resp.data.data.invoice_date_house_manufacturer
          this.commission_receipt_date = resp.data.data.commission_receipt_date
          this.new_agreed_payment_date = resp.data.data.new_agreed_payment_date
          this.invoice_type = resp.data.data.type_invoice
          this.invoice_type_detail = resp.data.data.type_invoice
          this.value = resp.data.data.value_total
          this.invoice_number_house_representative = resp.data.data.invoice_number_house_representative
          this.commission_value = resp.data.data.commission_value
          this.comments = resp.data.data.comments
          this.customer = resp.data.data.customers
          this.customerDetail = resp.data.data.customers
        })

        setTimeout(() => {
          this.$vs.loading.close()
        }, 1000)
      }, 500)

    },

    getApiStateInvoice() {
      axios.get('/api/get-state-invoice').then(resp => {
        this.optionsStateInvoice = resp.data.data
      });
    },

    getApiTypeInvoice() {
      axios.get('/api/get-type-invoice').then(resp => {
        this.optionsTypeInvoice = resp.data.data
      });
    },

    getApiCustomer() {
      axios.get('/api/all-customers').then(resp => {
        this.optionsCustomerInvoice = resp.data.data
      });
    }

  },
  mounted() {
    this.getApiStateInvoice();
    this.getApiTypeInvoice();
    this.getApiCustomer();
  }
}
</script>

<style>
.multiselect__tag {
  background: #0082FB !important;
}

.multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
  background: #283046 !important;
}

.multiselect__option--highlight {
  background: #0082FB !important;
}
</style>
