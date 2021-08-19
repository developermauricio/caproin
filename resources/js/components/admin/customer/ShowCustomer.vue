<template>
  <div>
    <input type="hidden" @click="traerDatos" id="traerDatosBotonCustomer"/>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel160">Modificar Cliente</h5>
        <button @click="closeModalShowCustomer()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="validateEditCustomerForm" class="" method="post">
        <div class="modal-body">
          <div class="row">
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
          <div class="row">
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
          </div>
        </div>
        <div class="modal-footer">
          <button @click="closeModalShowCustomer()" type="button" data-dismiss="modal" class="btn btn-gris">Cancelar
          </button>
          <button @click="editCustomer()" type="button" class="btn btn-primary">Actualizar</button>
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

export default {
  name: "ShowCustomer",
  components: {
    Multiselect,
    VuePhoneNumberInput,
  },
  data() {
    return {
      email: '',
      emailValidate: '',
      documentValidate: '',
      emailverify: '',
      idCustomer: null,
      idUser: null,
      dataCustomer: null,
      identification: '',
      identificationVerify: '',
      phone: '',
      customer: {
        businessName: '',
        typeIdentification: null,
        state: null,

      },
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

        const data = new FormData()
        data.append('businessName', this.customer.businessName);
        data.append('typeIdentification', JSON.stringify(this.customer.typeIdentification));
        data.append('identification', this.identification);
        data.append('state', JSON.stringify(this.customer.state));
        data.append('idCustomer', this.idCustomer);
        data.append('idUser', this.idUser);
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);

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
          this.dataCustomer = resp.data.data
          this.idUser = this.dataCustomer.user.id
          this.customer.businessName = this.dataCustomer.business_name
          this.identification = this.dataCustomer.user.identification
          this.documentValidate = this.dataCustomer.user.identification
          this.phone = this.dataCustomer.user.phone
          this.email = this.dataCustomer.user.email
          this.emailValidate = this.dataCustomer.user.email
          this.customer.typeIdentification = this.dataCustomer.user.identification_type
          if (this.dataCustomer.user.state === '1') {
            this.customer.state = {name: "Activo", id: 1}
          } else {
            this.customer.state = {name: "Inactivo", id: 2}
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
      eventBus.$emit("resetValidaciones");
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
