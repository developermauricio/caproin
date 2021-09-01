<template>
  <div>
    <input type="hidden" @click="traerDatos" id="traerDatosBotonCustomer"/>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel1600" v-show="showEditCustomer">Modificar Cliente</h5>
        <h5 class="modal-title" id="myModalLabel16000" v-show="showDetailCustomer">Información</h5>
        <button @click="closeModalShowCustomer()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="validateEditCustomerForm" class="" method="post">
        <div class="modal-body">
          <!--=====================================
            INFORMACIÓN
          ======================================-->
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#informacion" aria-controls="home"
                 role="tab"
                 aria-selected="true" v-if="showEditCustomer.length > 0">Actualizar Cliente</a>
              <a class="nav-link active" data-toggle="tab" href="#informacion" aria-controls="home"
                 role="tab"
                 aria-selected="true" v-else>Información Cliente</a>
            </li>
            <li class="nav-item" v-if="invoices.length > 0">
              <a class="nav-link" id="profile-tab" data-toggle="tab" href="#facturas" aria-controls="profile" role="tab"
                 aria-selected="false">Facturas</a>
            </li>
            <li class="nav-item" v-if="historySendEmailCustomer.length > 0">
              <a class="nav-link" id="about-tab" data-toggle="tab" href="#sendPaymentEmailCustomer"
                 aria-controls="about" role="tab"
                 aria-selected="false">Historial Envio Notificaciones</a>
            </li>
          </ul>

          <div class="tab-content">
            <!--=====================================
                  TAB INFORMACIÓN
              ======================================-->
            <div class="tab-pane active" id="informacion" aria-labelledby="home-tab" role="tabpanel">
              <div class="row" v-show="showDetailCustomer">
                <div class="col-12">
                  <div class="form-group">
                    <label class="font-weight-bold">Nombre y Apellido o Razón Social:</label>
                    <p v-text="customer.businessName"></p>
                  </div>
                </div>
              </div>
              <div class="row" v-show="showDetailCustomer">
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Tipo de Identificación:</label>
                  <p v-text="typeIdentificationDetail"></p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Número de Identificación:</label>
                  <p v-text="identification"></p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Correo Electrónico:</label>
                  <p v-text="email"></p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Teléfono:</label>
                  <p v-text="phone"></p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Estado:</label>
                  <p v-text="stateDetail"></p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Número de días para enviar cobro por correo electrónico, después de
                    generar la factura:</label>
                  <p>{{ numberDaysAfterInvoice }} {{ numberDaysAfterInvoice === 1 ? 'Dia' : 'Días' }}</p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="font-weight-bold">Número de días para enviar correo de cobro después de vencida la factura:</label>
                  <p>{{ numberDaysOverdueInvoice }} {{ numberDaysOverdueInvoice === 1 ? 'Dia' : 'Días' }}</p>
                </div>
              </div>

              <div class="row" v-show="showEditCustomer">
                <div class="col-12">
                  <input-form
                    id="txtNameUserCustomerEdit"
                    label="Nombre y Apellido o Razón Social"
                    pattern="all"
                    errorMsg="Ingrese nombre o razón social válido"
                    requiredMsg="El nombre o razón social es obligatorio"
                    :modelo.sync="customer.businessName"
                    :required="true"
                    :msgServer.sync="errors.businessName"
                  ></input-form>
                </div>
              </div>
              <div class="row" v-show="showEditCustomer">
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    label="Tipo de Identificación"
                    id="textTypeIdentificationCustomerEdit"
                    errorMsg
                    requiredMsg="El tipo de identificación es obligatorio"
                    :required="true"
                    :modelo.sync="customer.typeIdentification"
                    :msgServer.sync="errors.typeIdentification"
                    type="multiselect"
                    selectLabel="Tipo de Identificación"
                    :multiselect="{ options: optionsTypeIdentification,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Identificación',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeIdentification=>typeIdentification.name
                                        }"
                  ></input-form>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    id="txtIdentifacationCustomerEdit"
                    label="Número de Identificación"
                    pattern="all"
                    errorMsg="Ingrese un número de identificación válido"
                    requiredMsg="El número identificación es obligatorio"
                    :modelo.sync="identification"
                    :required="true"
                    :msgServer.sync="errors.identification"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-identification-customer-edit" class="text-danger">El número de identificación ya
                    ha sido registrado</p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    id="txtUserEmailEdit"
                    label="Correo electrónico"
                    pattern="email"
                    errorMsg="Ingrese un correo electrónico válido"
                    requiredMsg="El correo el electrónico es obligatorio"
                    :modelo.sync="email"
                    :required="true"
                    :msgServer.sync="errors.email"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-email-edit-customer" class="text-danger">Este correo electrónico ya ha sido
                    registrado</p>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <label class="form-control-label label-selects">Teléfono</label>
                  <VuePhoneNumberInput
                    v-model="phone"
                    :required="true"
                    requiredMsg="El teléfono es obligatorio"
                    @update="user.phoneI=$event.formatInternational"
                    :fetch-country="true"
                    :translations="{
                                countrySelectorLabel: 'Código País',
                                countrySelectorError: 'Selecciona un País',
                                phoneNumberLabel: 'Número',
                                example: 'Ejemplo'
                            }"/>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    label="Estado del Cliente"
                    id="textStateCustomerEdit"
                    errorMsg
                    requiredMsg="El estado del cliente es obligatorio"
                    :required="true"
                    :modelo.sync="customer.state"
                    :msgServer.sync="errors.state"
                    type="multiselect"
                    selectLabel="Estado del Cliente"
                    :multiselect="{ options: optionsStateCustomer,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado del Cliente',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateCustomer=>stateCustomer.name
                                        }"
                  ></input-form>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    id="txtSendPaymentEmailCustomerEdit"
                    label="Número de días para enviar cobro por correo electrónico, después de generar la factura"
                    pattern="all"
                    errorMsg="Ingrese un número de días válido"
                    requiredMsg="El número de días es obligatorio"
                    :modelo.sync="numberDaysAfterInvoice"
                    :required="true"
                    :msgServer.sync="errors.numberDaysAfterInvoice"
                  ></input-form>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                  <input-form
                    id="txtSendOverdueEmailCustomerEdit"
                    label="Número de días para enviar correo de cobro después de vencida la factura"
                    pattern="all"
                    errorMsg="Ingrese un número de días válido"
                    requiredMsg="El número de días es obligatorio"
                    :modelo.sync="numberDaysOverdueInvoice"
                    :required="true"
                    :msgServer.sync="errors.numberDaysOverdueInvoice"
                  ></input-form>
                </div>
              </div>
              <!--=====================================
                  BOTONES PARA EDITAR
              ======================================-->
              <div class="row pl-1">
                <div class="demo-inline-spacing">
                  <button v-if="showDetailCustomer === true && $gate.allow('editCustomer', 'customer')"
                          @click="btnEditCustomer"
                          type="button"
                          class="btn btn-primary waves-effect waves-float waves-light"
                          style="font-size: 0.92rem">
                    Editar
                  </button>
                  <button v-if="showEditCustomer === true" @click="btnCancelEditInvoice"
                          type="button"
                          class="btn btn-gris waves-effect waves-float waves-light"
                          style="font-size: 0.92rem">
                    Cancelar
                  </button>
                </div>
              </div>
            </div>
            <!--=====================================
                  TAB FACTURAS
              ======================================-->
            <div class="tab-pane" id="facturas" aria-labelledby="home-tab" role="tabpanel">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                      <tr>
                        <th>Número de Factura</th>
                        <th>Fecha de Emisión</th>
                        <th>Fecha de Pago Cliente</th>
                        <th>Recordatorio Fecha de Pago</th>
                        <th>Recordatorio Factura Vencida</th>
                        <th>Estado</th>
                        <th>Tipo de Factura</th>
                        <th>Tipo de Pago</th>
                        <th>Valor Total</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="invoice in invoices" :key="invoice.id">
                        <td>#{{ invoice.invoice_number }}</td>
                        <td>{{ moment(invoice.date_issue).format('MM-DD-YYYY') }}</td>
                        <td>{{ moment(invoice.date_payment_client).format('MM-DD-YYYY') }}</td>
                        <td><div :class="invoice.send_payment_cuertomer_state === 1 ? 'badge badge-pill badge-light-success': 'badge badge-pill badge-light-danger'">{{ invoice.send_payment_cuertomer_state === 1 ? 'Enviado' : 'No Enviado' }}</div></td>
                        <td><div :class="invoice.send_overdue_cuertomer_state === 1 ? 'badge badge-pill badge-light-success': 'badge badge-pill badge-light-danger'">{{ invoice.send_overdue_cuertomer_state === 1 ? 'Enviado' : 'No Enviado' }}</div></td>
                        <td>{{ invoice.state.name }}</td>
                        <td>{{ invoice.type_invoice.name }}</td>
                        <td>{{ invoice.payment_type.name }}</td>
                        <td>${{ dollarUSLocale.format(invoice.value_total) }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!--=====================================
                  TAB HISTORIAL DE ENVIOS DE CORREO ELECTRÓNICOS
              ======================================-->
            <div class="tab-pane" id="sendPaymentEmailCustomer" aria-labelledby="home-tab" role="tabpanel">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table">
                      <thead>
                      <tr>
                        <th>Número de Factura</th>
                        <th>Tipo de Envio</th>
                        <th>Detalle</th>
                        <th>Fecha de Envío</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="historySendEmail in historySendEmailCustomer" :key="historySendEmail.invoice_id">
                        <td>#{{ historySendEmail.invoice_number }}</td>
                        <td>{{ historySendEmail.type_send === 1 ? 'Envío Automático' : 'Manual' }}</td>
                        <td>{{ historySendEmail.detail === 1 ? 'Recordatorio Pago' : 'Factura Vencida' }}</td>
                        <td>{{  moment(historySendEmail.date_register).format('MM-DD-YYYY') }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeModalShowCustomer()" type="button" data-dismiss="modal" class="btn btn-gris">Cerrar
          </button>
          <button v-if="showEditCustomer" @click="editCustomer()" type="button" class="btn btn-primary">Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
import Swal from 'sweetalert2'
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'

export default {
  name: "ShowCustomer",
  components: {
    Multiselect,
    VuePhoneNumberInput,
    moment
  },
  data() {
    return {
      moment: moment,
      en: en,
      es: es,

      showDetailCustomer: true,
      showEditCustomer: false,
      numberDaysAfterInvoice: null,
      numberDaysOverdueInvoice: null,
      invoices: [],
      historySendEmailCustomer: [],

      email: '',
      emailValidate: '',
      documentValidate: '',
      emailverify: '',
      idCustomer: null,
      idUser: null,
      dataCustomer: null,
      identification: '',
      identificationVerify: '',
      stateDetail: null,
      phone: '',
      dollarUSLocale: Intl.NumberFormat('es-US'),
      customer: {
        businessName: '',
        typeIdentification: null,
        state: null,

      },
      typeIdentificationDetail: null,
      user: {
        phoneI: null
      },
      optionsTypeIdentification: [],
      optionsStateCustomer: [
        {
          name: 'Activo',
          id: 1
        },
        {
          name: 'Inactivo',
          id: 2
        }
      ],
      errors: {},
    }
  },

  methods: {

    btnEditCustomer() {
      this.showDetailCustomer = false;
      this.showEditCustomer = true;
    },

    btnCancelEditInvoice() {
      this.showEditCustomer = false;
      this.showDetailCustomer = true;

    },

    editCustomer() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateEditCustomerForm .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa los campos.',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          return;
        }
        this.resetValidations()


        const data = new FormData()
        data.append('businessName', this.customer.businessName);
        data.append('typeIdentification', JSON.stringify(this.customer.typeIdentification));
        data.append('identification', this.identification);
        data.append('state', JSON.stringify(this.customer.state));
        data.append('idCustomer', this.idCustomer);
        data.append('idUser', this.idUser);
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);
        data.append('numberDaysAfterInvoice', this.numberDaysAfterInvoice);
        data.append('numberDaysOverdueInvoice', this.numberDaysOverdueInvoice);

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
        }).then(result => {
          if (result.value) {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Actualizando Cliente...'
            })
            axios.post('/api/update-customer', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Cliente actualizado correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              setTimeout(() => {
                window.location = "/customers";
              }, 200)
            }).catch(err => {
              console.log('mostrando el error', err)
              this.$toast.error({
                title: 'Algo salioó mal',
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

      }, 200)
    },
    traerDatos(e) {
      e.preventDefault();
      this.idCustomer = +e.target.value;
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })
      setTimeout(() => {
        axios.get('/api/data-customer/' + this.idCustomer).then(resp => {


          this.historySendEmailCustomer = resp.data.historySendPaymentClient
          this.dataCustomer = resp.data.data
          this.idUser = this.dataCustomer.user.id
          this.customer.businessName = this.dataCustomer.business_name
          this.identification = this.dataCustomer.user.identification
          this.documentValidate = this.dataCustomer.user.identification
          this.phone = this.dataCustomer.user.phone
          this.email = this.dataCustomer.user.email
          this.emailValidate = this.dataCustomer.user.email
          this.numberDaysAfterInvoice = this.dataCustomer.number_of_days_after_generating_invoice
          this.numberDaysOverdueInvoice = this.dataCustomer.number_of_days_after_invoice_overdue
          this.customer.typeIdentification = this.dataCustomer.user.identification_type
          this.typeIdentificationDetail = this.dataCustomer.user.identification_type.name
          this.invoices = this.dataCustomer.invoices
          if (this.dataCustomer.user.state === '1') {
            this.customer.state = {name: "Activo", id: 1}
            this.stateDetail = 'Activo'
          } else {
            this.customer.state = {name: "Inactivo", id: 2}
            this.stateDetail = 'Inactivo'
          }
        })
        this.$vs.loading.close()
      }, 500)
    },
    getApiTypeIdentification() {
      axios.get('/api/get-identificationtype').then(resp => {
        this.optionsTypeIdentification = resp.data.data
      })
    },
    closeModalShowCustomer() {
      this.customer.businessName = ''
      this.identification = ''
      this.customer.typeIdentification = null
      this.resetValidations()
    },
    resetValidations() {
      eventBus.$emit('resetValidaciones')
    }
  },
  watch: {
    identification: function (val) {
      console.log(val)
      let data = this
      if (val) {
        console.log('val', val)
        console.log('identificación', this.documentValidate)
        if (this.documentValidate !== val) {
          setTimeout(() => {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Válidando número de identificación...'
            })
            axios.get('/api/verify-identification-user/' + val)
              .then(resp => {
                if (resp.data) {
                  $("#txtIdentifacationCustomerEdit").addClass("is-invalid");
                  $("#text-verify-identification-customer-edit").css("display", "block");
                  this.$toast.error({
                    title: '¡Atención!',
                    message: 'El número de identificación ya ha sido registrado, por favor ingrese otro',
                    showDuration: 1000,
                    hideDuration: 8000,
                  })
                } else {
                  data.identificationVerify = ''
                  $("#txtIdentifacationCustomerEdit").removeClass("is-invalid");
                  $("#text-verify-identification-customer-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtIdentifacationCustomerEdit").removeClass("is-invalid");
          $("#text-verify-identification-customer-edit").css("display", "none");
        }
      }
    },
    email: function (val) {
      let data = this
      if (val) {

        if (this.emailValidate !== val) {
          setTimeout(() => {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Válidando correo electrónico...'
            })
            axios.get('/api/verify-email-user/' + val)
              .then(resp => {
                if (resp.data) {
                  $("#txtUserEmailEdit").addClass("is-invalid");
                  $("#text-verify-email-edit-customer").css("display", "block");
                } else {
                  data.emailverify = ''
                  $("#txtUserEmailEdit").removeClass("is-invalid");
                  $("#text-verify-email-edit-customer").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });

          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtUserEmailEdit").removeClass("is-invalid");
          $("#text-verify-email-edit-customer").css("display", "none");
        }

      }
    }
    ,
  },
  mounted() {
    this.getApiTypeIdentification();
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
