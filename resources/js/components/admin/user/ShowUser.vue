<template>
  <div>
    <input type="hidden" @click="traerDatosUser" id="traerDatosBotonUser"/>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel160">Modificar Usuario</h5>
        <button @click="closeModalShowUser()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="validateEditUserForm" class="" method="post">
        <div class="modal-body">
          <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
              <input-form
                id="txtNameUserEdit"
                label="Nombre y Apellido o Razón Social"
                pattern="all"
                errorMsg="Ingrese nombre válido"
                requiredMsg="El nombre es obligatorio"
                :modelo.sync="user.name"
                :required="true"
                :msgServer.sync="errors.name"
              ></input-form>
            </div>
            <div class="col-12 col-md-6 col-lg-6">
              <input-form
                id="txtLastNameUserEdit"
                label="Apellido"
                pattern="all"
                errorMsg="Ingrese un apellido válido"
                requiredMsg="El apellido es obligatorio"
                :modelo.sync="user.last_name"
                :required="true"
                :msgServer.sync="errors.last_name"
              ></input-form>
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
              <input-form
                label="Sucursal"
                id="textSucursalUsuarioEdit"
                errorMsg
                requiredMsg="La sucursal es obligatoria obligatorio"
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
                id="textRolUsuarioEdit"
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
                id="textTipoUsuarioEdit"
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
                label="Estado del Usuario"
                id="textStateUserEdit"
                errorMsg
                requiredMsg="El estado del usuario es obligatorio"
                :required="true"
                :modelo.sync="user.state"
                :msgServer.sync="errors.state"
                type="multiselect"
                selectLabel="Estado del Usuario"
                :multiselect="{ options: optionsStateCustomer,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado del Usuario',
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
          <button @click="closeModalShowUser()" type="button" data-dismiss="modal" class="btn btn-danger">Cancelar
          </button>
          <button @click="editUser()" type="button" class="btn btn-primary">Actualizar</button>
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
  name: "ShowUser",
  components: {
    Multiselect,
    VuePhoneNumberInput,
  },
  data() {
    return {
      emailValidate: '',
      documentValidate: '',
      emailverify: '',
      idUser: null,
      // idUser: null,
      dataCustomer: null,
      identification: '',
      identificationVerify: '',
      phone: '',
      customer: {
        businessName: '',
        typeIdentification: null,
        state: null,

      },


      typeBranchOffice: null,
      user: {
        name: '',
        last_name: '',
        phone: '',
        state: null,
      },
      rol: null,
      typeUser: null,
      email: '',
      optionsTypeBranchOffice: [],
      optionsTypeRol: [],
      optionsTypeUser: [],


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
    editUser() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateEditUserForm .is-invalid").length > 0) {
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
        data.append('id_user', this.idUser);
        data.append('name', this.user.name);
        data.append('last_name', this.user.last_name);
        data.append('branch_office', JSON.stringify(this.typeBranchOffice));
        data.append('type_user', JSON.stringify(this.typeUser));
        data.append('role', JSON.stringify(this.rol));
        data.append('email', this.email);
        data.append('phone', this.user.phoneI);
        data.append('state', JSON.stringify(this.user.state));

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar?',
          confirmButtonColor: "#0082FB",
          cancelButtonColor: "#F05E7D",
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
              text: 'Actualizando Usuario...'
            })
            axios.post('/api/update-user', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Usuario actualizado correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              setTimeout(() => {
                window.location = "/usuarios";
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
    traerDatosUser(e) {
      e.preventDefault();
      console.log('ID DEL USUARIO', +e.target.value)
      this.idUser = +e.target.value;
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })
      setTimeout(() => {
        axios.get('/api/data-user/' + this.idUser).then(resp => {
          this.dataUser = resp.data.data
          this.idUser = this.dataUser.id
          this.user.name = this.dataUser.name
          this.user.last_name = this.dataUser.last_name
          this.user.phone = this.dataUser.phone
          this.email = this.dataUser.email
          this.emailValidate = this.dataUser.email
          this.rol = this.dataUser.roles[0]
          this.typeBranchOffice = this.dataUser.employes.branch_offices
          this.typeUser = this.dataUser.employes.type_employe

          if (this.dataUser.state === '1') {
            this.user.state = {name: "Activo", id: 1}
          } else {
            this.user.state = {name: "Inactivo", id: 2}
          }
        })
        this.$vs.loading.close()
      }, 500)
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
    },

    closeModalShowUser() {
      this.user.name = ''
      this.user.last_name = ''
      this.user.phone = null
      this.rol = null
      this.typeUser = null
      this.typeBranchOffice = null
    }
  },
  watch: {
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
    this.getApiTypeBranchoffice();
    this.getApiTypeRol();
    this.getApiTypeUser();
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

