<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Cliente</h5>
      <button type="button" @click="closeModal()" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateCustomer" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <input-form
              id="txtNameUserCustomer"
              label="Nombre y Apellido o Razón Social"
              pattern="all"
              errorMsg="Ingrese nombre o razón social válido"
              requiredMsg="El nombre o razón social es obligatorio"
              :modelo.sync="businessName"
              :required="true"
              :msgServer.sync="errors.businessName"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character" class="text-danger">El nombre no puede ser de un caracter</p>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <label>Selecciona el tipo de empresa:</label>
          </div>
        </div>
        <div class="row pb-2 pt-1">
          <div class="col-2">
            <vs-radio color="#D9393D" v-model="principal" vs-value="0">
              <vs-tooltip text="Es una sede principal">Principal</vs-tooltip>
            </vs-radio>
          </div>
          <div class="col-2">
            <vs-radio color="#D9393D" v-model="principal" vs-value="1">
              <vs-tooltip text="Pertenece o esta ligada a una empresa">Sede</vs-tooltip>
            </vs-radio>
          </div>
        </div>
        <div class="row" v-if="principal === '1'">
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Seleccionar empresa a la que pertenece"
              id="textTypeCustomer"
              errorMsg
              requiredMsg="El cliente es obligatorio"
              :required="true"
              :modelo.sync="customerType"
              :msgServer.sync="errors.customerType"
              @updatedValue="getCustomer"
              type="multiselect"
              selectLabel="Tipo de Identificación"
              :multiselect="{ options: optionsTypeCustomer,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Empresa o Cliente',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeCustomer=>typeCustomer.business_name
                                        }"
            ></input-form>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6" v-if="principal === '0'">
            <input-form
              label="Tipo de Identificación"
              id="textTypeIdentificationCustomer"
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
          <div class="col-12 col-md-6 col-lg-6" v-else>
            <input-form
              id="txtTypePrincipalIdentifacationCustomer"
              label="Tipo de Identificación"
              pattern="all"
              errorMsg="Ingrese un número de identificación válido"
              requiredMsg="El número identificación es obligatorio"
              :modelo.sync="identificationTypePrincipal"
              :required="true"
              :msgServer.sync="errors.identificationTypePrincipal"
            ></input-form>

          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtIdentifacationCustomer"
              label="Número de Identificación"
              pattern="all"
              errorMsg="Ingrese un número de identificación válido"
              requiredMsg="El número identificación es obligatorio"
              :modelo.sync="identification"
              :required="true"
              :msgServer.sync="errors.identification"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-identification-customer" class="text-danger">El número de identificación ya
              ha sido registrado</p>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-indentification-character" class="text-danger">El número de identificación no puede
              ser de un caracter</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtUserEmail"
              label="Correo electrónico"
              pattern="email"
              errorMsg="Ingrese un correo electrónico válido"
              requiredMsg="El correo el electrónico es obligatorio"
              :modelo.sync="email"
              :required="true"
              :msgServer.sync="errors.email"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-email" class="text-danger">Este correo electrónico ya ha sido registrado</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <label class="form-control-label label-selects">Teléfono</label>
            <VuePhoneNumberInput
              v-model="user.phone"
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
            <p style="margin-top: 0.2rem;font-size: 0.9rem; display: none"
               id="text-verify-phone-customer" class="text-danger">Ingrese un número de teléfono válido</p>

          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtSendPaymentEmailCustomer"
              label="Número de días para enviar cobro por correo electrónico, después de generar la factura"
              pattern="num"
              errorMsg="Ingrese un número de días válido"
              requiredMsg="El número de días es obligatorio"
              :modelo.sync="numberDaysAfterInvoice"
              :required="true"
              :msgServer.sync="errors.numberDaysAfterInvoice"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-days-after-invoice" class="text-danger">'0' no es un número válido</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtSendOverdueEmailCustomer"
              label="Número de días para enviar correo de cobro después de vencida la factura"
              pattern="num"
              errorMsg="Ingrese un número de días válido"
              requiredMsg="El número de días es obligatorio"
              :modelo.sync="numberDaysOverdueInvoice"
              :required="true"
              :msgServer.sync="errors.numberDaysOverdueInvoice"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-days-overdue-invoice" class="text-danger">'0' no es un número válido</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button @click="closeModal()" type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="createNewCustomer()" type="button" class="btn btn-primary">Crear Cliente</button>
      </div>
    </form>
  </div>

</template>

<script>
import Multiselect from "vue-multiselect";
import VuePhoneNumberInput from "vue-phone-number-input";
import "vue-phone-number-input/dist/vue-phone-number-input.css";
import vue2Dropzone from "vue2-dropzone";
import Swal from 'sweetalert2'

export default {
  name: "CreateCustomer",
  components: {
    Multiselect,
    VuePhoneNumberInput,
  },
  data() {
    return {
      numberDaysAfterInvoice: null,
      numberDaysOverdueInvoice: null,
      identification: '',
      email: '',
      customerType: null,
      identificationVerify: '',
      identificationTypePrincipal: null,
      identificationTypePrincipalId: null,
      codeVerify: '',
      code: null,
      principal: '0',

      user: {
        phone: '',
      },
      businessName: '',
      customer: {
        typeIdentification: null,
      },
      optionsTypeIdentification: [],
      optionsTypeCustomer: [],
      errors: {},
    }
  },
  methods: {
    clearInputsCustomer() {
      eventBus.$emit("resetValidaciones");
    },

    getCustomer(customer) {
      if (customer) {
        this.identification = customer.user.identification
        this.identificationTypePrincipal = customer.user.identification_type.name
        this.identificationTypePrincipalId = customer.user.identification_type.id

        document.getElementById('txtIdentifacationCustomer').disabled = true;

      } else {
        document.getElementById('txtIdentifacationCustomer').disabled = false;
        this.identification = ''
        this.identificationTypePrincipal = null
        this.identificationTypePrincipalId = null
      }
    },

    createNewCustomer() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateCustomer .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          if (user.phone.length < 11) {
            document.getElementById('text-verify-phone-customer').disabled = true;
            $('#text-verify-phone-customer').css("display", "block");
          }
          return;
        }
        const data = new FormData()
        data.append('businessName', this.businessName);
        data.append('typeIdentification', JSON.stringify(this.customer.typeIdentification));
        data.append('typeCustomer', JSON.stringify(this.customerType));
        data.append('identification', this.identification);
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);
        data.append('principal', this.principal);
        data.append('idTypeIndentification', this.identificationTypePrincipalId);
        data.append('numberDaysAfterInvoice', this.numberDaysAfterInvoice);
        data.append('numberDaysOverdueInvoice', this.numberDaysOverdueInvoice);

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de realizar el registro?',
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
              text: 'Registrando Cliente...'
            })
            axios.post('/api/register/store-customer', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Cliente creado correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/customers";
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

      }, 200)
    },

    getApiTypeIdentification() {
      axios.get('/api/get-identificationtype').then(resp => {
        this.optionsTypeIdentification = resp.data.data
      })
    },

    getApiCustomer() {
      axios.get('/api/all-customers').then(resp => {
        this.optionsTypeCustomer = resp.data.data
      });
    },

    closeModal() {
      eventBus.$emit('resetValidaciones')
    }
  },
  watch: {
    identification: function (val) {
      let data = this
      if (this.customerType === null) {
        if (val) {
          if (val === '') {
            data.identificationVerify = ''
            $("#txtIdentifacationCustomer").removeClass("is-invalid");
            $("#text-verify-identification-customer").css("display", "none");
          }
          axios.get('/api/verify-identification-user/' + val)
            .then(resp => {
              if (resp.data) {
                $("#txtIdentifacationCustomer").addClass("is-invalid");
                $("#text-verify-identification-customer").css("display", "block");
                this.$toast.error({
                  title: '¡Atención!',
                  message: 'El número de identificación ya ha sido registrado, por favor ingrese otro',
                  showDuration: 1000,
                  hideDuration: 8000,
                })
              } else {
                data.identificationVerify = ''
                $("#txtIdentifacationCustomer").removeClass("is-invalid");
                $("#text-verify-identification-customer").css("display", "none");
              }
            }).catch(err => {
          });
        }
      }
      if (val.length === 1) {
        setTimeout(() => {
          $("#txtIdentifacationCustomer").addClass("is-invalid");
          $("#text-verify-one-indentification-character").css("display", "block");
          document.getElementById('text-verify-one-indentification-character').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-indentification-character').disabled = false;
        $("#txtIdentifacationCustomer").removeClass("is-invalid");
        $("#text-verify-one-indentification-character").css("display", "none");
      }
    },
    email: function (val) {
      let data = this
      if (val) {
        axios.get('/api/verify-email-user/' + val)
          .then(resp => {
            if (resp.data) {
              $("#txtUserEmail").addClass("is-invalid");
              $("#text-verify-email").css("display", "block");
            } else {
              data.emailverify = ''
              $("#txtUserEmail").removeClass("is-invalid");
              $("#text-verify-email").css("display", "none");
            }
          }).catch(err => {
        });
      }
    },
    principal: function (val) {
      if (val === '1') {
        this.identification = ''
        document.getElementById('txtIdentifacationCustomer').disabled = true;
        setTimeout(() => {
          document.getElementById('txtTypePrincipalIdentifacationCustomer').disabled = true;
        }, 200)

        $("#txtIdentifacationCustomer").removeClass("is-invalid");
        $("#text-verify-identification-customer").css("display", "none");
      } else {
        this.identification = ''
        this.customerType = null
        this.identificationTypePrincipalId = null
        this.identificationTypePrincipal = null
        document.getElementById('txtIdentifacationCustomer').disabled = false;
        document.getElementById('txtTypePrincipalIdentifacationCustomer').disabled = false;
      }
    },
    businessName: function (val) {
      let data = this
      let character = val;
      if (character.length === 1) {
        setTimeout(() => {
          $("#txtNameUserCustomer").addClass("is-invalid");
          $("#text-verify-one-character").css("display", "block");
          document.getElementById('text-verify-one-character').disabled = true;
        }, 200)

      } else {
        document.getElementById('text-verify-one-character').disabled = false;
        $("#txtNameUserCustomer").removeClass("is-invalid");
        $("#text-verify-one-character").css("display", "none");
      }
    },
    numberDaysAfterInvoice: function (val) {
      if (val === '0' || val === 0) {
        setTimeout(() => {
          $("#txtSendPaymentEmailCustomer").addClass("is-invalid");
          $("#text-verify-one-character-days-after-invoice").css("display", "block");
          document.getElementById('text-verify-one-character-days-after-invoice').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-days-after-invoice').disabled = false;
        $("#txtSendPaymentEmailCustomer").removeClass("is-invalid");
        $("#text-verify-one-character-days-after-invoice").css("display", "none");
      }
    },
    numberDaysOverdueInvoice: function (val) {
      if (val === '0' || val === 0) {
        setTimeout(() => {
          $("#txtSendOverdueEmailCustomer").addClass("is-invalid");
          $("#text-verify-one-character-days-overdue-invoice").css("display", "block");
          document.getElementById('text-verify-one-character-days-overdue-invoice').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-days-after-invoice').disabled = false;
        $("#txtSendOverdueEmailCustomer").removeClass("is-invalid");
        $("#text-verify-one-character-days-overdue-invoice").css("display", "none");
      }
    },
    'user.phone'(val) {
      if (val !== null) {
        if (val.length < 11) {
          setTimeout(() => {
            $("#MazPhoneNumberInput-16_phone_number").addClass("is-invalid");
            $(".input-tel__label").addClass("is-invalid");
          }, 200)
        } else {
          document.getElementById('text-verify-phone-customer').disabled = false;
          $('#text-verify-phone-customer').css("display", "none");
          $("#MazPhoneNumberInput-16_phone_number").removeClass("is-invalid");
          $(".input-tel__label").removeClass("is-invalid");

        }
      }
    }
  },
  mounted() {
    this.getApiTypeIdentification();
    this.getApiCustomer();
  }
}
</script>

<style scoped>
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
