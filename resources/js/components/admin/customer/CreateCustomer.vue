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
              :modelo.sync="customer.businessName"
              :required="true"
              :msgServer.sync="errors.businessName"
            ></input-form>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
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
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtSendPaymentEmailCustomer"
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
              id="txtSendOverdueEmailCustomer"
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
      identificationVerify: '',
      codeVerify: '',
      code: null,
      user:{
        phone: '',
      },
      customer: {
        businessName: '',
        typeIdentification: null,
      },
      optionsTypeIdentification: [],
      errors: {},
    }
  },
  methods: {
    clearInputsCustomer(){
      eventBus.$emit("resetValidaciones");
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
          return;
        }
        const data = new FormData()
        data.append('businessName', this.customer.businessName);
        data.append('typeIdentification', JSON.stringify(this.customer.typeIdentification));
        data.append('identification', this.identification);
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);
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

    closeModal() {
      eventBus.$emit('resetValidaciones')
    }
  },
  watch: {
    identification: function (val) {
      console.log(val)
      let data = this
      if (val) {
        if (val === ''){
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
  },
  mounted() {
    this.getApiTypeIdentification();
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
