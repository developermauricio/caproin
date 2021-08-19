<template>
  <div>
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel1">Perfil</h4>
        <button @click="clearInputsProfile()" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
              <label class="font-weight-bold">Nombre:</label>
              <p>{{ user.name }}</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
              <label class="font-weight-bold">Apellido:</label>
              <p>{{ user.last_name }}</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
              <label class="font-weight-bold">Teléfono:</label>
              <p>{{ user.phone }}</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
              <label class="font-weight-bold">Correo Electrónico:</label>
              <p>{{ user.email }}</p>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <div class="form-group">
              <label class="font-weight-bold">Rol:</label>
              <p>{{ user.roles[0].name }}</p>
            </div>
          </div>
        </div>
        <hr>
        <div class="row pb-1 ">
          <div class="col-12">
            <h3>Cambiar contraseña</h3>
          </div>
        </div>
        <form action="" id="validateChangedPassword" method="post">
          <div class="row">
            <div class="col-12 col-lg-6 col-md-6">
              <input-form
                id="txtPassword"
                label="Contraseña"
                pattern="all"
                type="password"
                requiredMsg="La contraseña es obligatoria"
                :modelo.sync="password"
                v-on:keyup="clearPasswordValidate"
                :required="true"
                :msgServer.sync="errors.password"
              ></input-form>
              <div style="margin-top: -1rem;" v-if="errorsPassword.length > 0"
                   v-for="error in errorsPassword" :key="error">
                <span class="text-danger" v-text="error"></span><br><br>
              </div>
            </div>
            <div class="col-12 col-lg-6 col-md-6">
              <input-form
                id="txtConfirmPassword"
                label="Confirmar Contraseña"
                requiredMsg="La confirmación de contraseña es obligatoria"
                pattern="all"
                type="password"
                :modelo.sync="password_confirmation"
                :required="true"
                :msgServer.sync="errors.password_confirmation"
              ></input-form>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="button" @click="updateChangePassword()" class="btn btn-primary float-right">
                Actualizar Contraseña
              </button>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button @click="clearInputsProfile()" type="button" class="btn btn-gris" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
  name: "ModalProfile",
  data() {
    return {
      password: '',
      password_confirmation: '',
      errorsPassword: [],
      errors: {},
    }
  },
  methods: {
    clearInputsProfile(){
      eventBus.$emit("resetValidaciones");
    },
    updateChangePassword() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateChangedPassword .is-invalid").length > 0) {
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
        data.append('password', this.password);
        data.append('password_confirmation', this.password_confirmation);
        data.append('user_id', this.user.id);


        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar la contraseña?',
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
              text: 'Actualizando Contraseña...'
            })
            axios.post('/api/register/update-password', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Contraseña actualizada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              this.errorsPassword = [];
            }).catch(err => {
              console.log('mostrando el error', err)
              if (err.response.status === 422) {
                this.errorsPassword = [];
                this.password = ''
                this.password_confirmation = ''
                err.response.data.errors.password.map(function (value, key) {
                  this.errorsPassword.push(value);
                  return this.$toast.error({
                    title: 'Atención',
                    message: value,
                    showDuration: 1000,
                    hideDuration: 7000,
                    position: 'top right',
                  })
                })
              }
            });
            setTimeout(() => {
              this.$vs.loading.close()
            }, 2000)
          }
        })

      }, 200)
    },
    clearPasswordValidate() {
      this.errorsPassword = []
      this.user.password_confirmation = ''
    },
  },
  props: ['user']
}
</script>

<style scoped>

</style>
