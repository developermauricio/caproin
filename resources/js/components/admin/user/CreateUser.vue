<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Usuario</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateUser" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtNameUser"
              label="Nombre"
              pattern="all"
              errorMsg="Ingrese nombre válido"
              requiredMsg="El nombre es obligatorio"
              :modelo.sync="user.name"
              :required="true"
              :msgServer.sync="errors.name"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-user-name" class="text-danger">El nombre no puede contener menos de 5 caracteres</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtLastNameUser"
              label="Apellido"
              pattern="all"
              errorMsg="Ingrese un apellido válido"
              requiredMsg="El apellido es obligatorio"
              :modelo.sync="user.last_name"
              :required="true"
              :msgServer.sync="errors.last_name"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-user-lastName" class="text-danger">El apellido no puede contener menos de 5 caracteres</p>
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
            <input-form
              label="Sucursal"
              id="textSucursalUsuario"
              errorMsg
              requiredMsg="La sucursal es obligatoria"
              :required="true"
              :modelo.sync="typeBranchOffice"
              :msgServer.sync="errors.typeBranchOffice"
              type="multiselect"
              selectLabel="Sucursal"
              :multiselect="{ options: optionsTypeBranchOffice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Sucursal',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeBranchOfficeSelect=>typeBranchOfficeSelect.name
                                        }"
            ></input-form>
          </div>

          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Rol"
              id="textRolUsuario"
              errorMsg
              requiredMsg="El rol es obligatorio"
              :required="true"
              :modelo.sync="rol"
              :msgServer.sync="errors.rol"
              type="multiselect"
              selectLabel="Rol"
              :multiselect="{ options: optionsTypeRol,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Rol',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeRolSelect=>typeRolSelect.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Tipo Usuario"
              id="textTipoUsuario"
              errorMsg
              requiredMsg="El tipo de usuario es obligatorio"
              :required="true"
              :modelo.sync="typeUser"
              :msgServer.sync="errors.typeUser"
              type="multiselect"
              selectLabel="Rol"
              :multiselect="{ options: optionsTypeUser,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Usuario',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeUserSelect=>typeUserSelect.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <label class="form-control-label label-selects">Teléfono</label>
            <VuePhoneNumberInput
              v-model="user.phone"
              :required="true"
              requiredMsg="El teléfono es obligatorio"
              @update="changePhone($event)"
              :fetch-country="true"
              :translations="{
                                countrySelectorLabel: 'Código País',
                                countrySelectorError: 'Selecciona un País',
                                phoneNumberLabel: 'Número',
                                example: 'Ejemplo'
                            }"/>
            <p style="margin-top: 0.2rem;font-size: 0.9rem; display: none"
               id="text-verify-phone-user" class="text-danger">Ingrese un número de teléfono válido</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="createNewCustomer()" type="button" class="btn btn-primary">Crear Usuario</button>
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
  name: "CreateUser",
  components: {
    Multiselect,
    VuePhoneNumberInput,
  },
  data() {
    return {
      identification: '',

      identificationVerify: '',
      codeVerify: '',
      code: null,
      customer: {
        businessName: '',
        typeIdentification: null,
      },
      optionsTypeIdentification: [],


      typeBranchOffice: null,
      user: {
        name: '',
        last_name: '',
        phone: '',
        phoneI: '',
      },
      rol: null,
      typeUser: null,
      email: '',
      optionsTypeBranchOffice: [],
      optionsTypeRol: [],
      optionsTypeUser: [],
      errors: {},
    }
  },
  methods: {
    changePhone(e) {
      if (e.isValid){
        console.log(e.isValid);
      }
      if (e.formatInternational){
        this.user.phoneI = e.formatInternational;
      } else {
        this.user.phoneI = '';
      }
    },
    createNewCustomer() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateUser .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          if (user.phone.length < 11) {
            document.getElementById('text-verify-phone-user').disabled = true;
            $('#text-verify-phone-user').css("display", "block");
          }
          return;
        }
        const data = new FormData()
        data.append('name', this.user.name);
        data.append('last_name', this.user.last_name);
        data.append('branch_office', JSON.stringify(this.typeBranchOffice));
        data.append('type_user', JSON.stringify(this.typeUser));
        data.append('role', JSON.stringify(this.rol));
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);

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
              text: 'Registrando Usuario...'
            })
            axios.post('/api/register/store-user', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Usuario creado correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              this.$vs.loading.close()
              window.location = "/usuarios";
            }).catch(err => {
              console.log('mostrando el error', err)
              this.$toast.error({
                title: 'Algo salio mal',
                message: 'Comunícate con el administrador',
                showDuration: 1000,
                hideDuration: 8000,
              })
              this.$vs.loading.close()
            });
          }
        })

      }, 200)
    },

    getApiTypeBranchoffice() {
      axios.get('/api/get-branchofficetype').then(resp => {
        this.optionsTypeBranchOffice = resp.data.data
      })
    },

    getApiTypeRol() {
      axios.get('/api/get-rol').then(resp => {
        this.optionsTypeRol = resp.data.data
      })
    },
    getApiTypeUser() {
      axios.get('/api/get-type-user').then(resp => {
        this.optionsTypeUser = resp.data.data
      })
    }
  },
  watch: {
    identification: function (val) {
      console.log(val)
      let data = this
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
    'user.name'(val) {
      if (val.length <= 5) {
        setTimeout(() => {
          $("#txtNameUser").addClass("is-invalid");
          $("#text-verify-one-character-user-name").css("display", "block");
          document.getElementById('text-verify-one-character-user-name').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-user-name').disabled = false;
        $("#txtNameUser").removeClass("is-invalid");
        $("#text-verify-one-character-user-name").css("display", "none");

      }
    },
    'user.last_name'(val) {
      if (val.length <= 5) {
        setTimeout(() => {
          $("#txtLastNameUser").addClass("is-invalid");
          $("#text-verify-one-character-user-lastName").css("display", "block");
          document.getElementById('text-verify-one-character-user-lastName').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-user-lastName').disabled = false;
        $("#txtLastNameUser").removeClass("is-invalid");
        $("#text-verify-one-character-user-lastName").css("display", "none");

      }
    },
    'user.phone'(val) {
      if (val !== null) {
        if (val.length < 11) {
          setTimeout(() => {
            $("#MazPhoneNumberInput-18_phone_number").addClass("is-invalid");
            $(".input-tel__label").addClass("is-invalid");
          }, 200)
        } else {
          document.getElementById('text-verify-phone-user').disabled = false;
          $('#text-verify-phone-user').css("display", "none");
          $("#MazPhoneNumberInput-18_phone_number").removeClass("is-invalid");
          $(".input-tel__label").removeClass("is-invalid");

        }
      }
    }
  },
  mounted() {
    this.getApiTypeBranchoffice();
    this.getApiTypeRol();
    this.getApiTypeUser();
  }
}
</script>

<style scoped>
.multiselect__tag {
  background: #B33131 !important;
}

.multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
  background: #283046 !important;
}

.multiselect__option--highlight {
  background: #B33131 !important;
}
</style>

