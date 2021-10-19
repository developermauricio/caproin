<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Transportador</h5>
      <button type="button" @click="reset()" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateConveyor" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <input-form
              id="txtNombre"
              label="Nombre"
              pattern="^.{4,}$"
              errorMsg="Ingrese un nombre valido"
              requiredMsg="El nombre es obligatorio"
              :modelo.sync="conveyor.name"
              :required="true"
              :msgServer.sync="errors.name"
            ></input-form>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <input-form
              label="Descripción"
              id="txtDescription"
              errorMsg="La descripción es invalida"
              requiredMsg="la descripción es obligatoria"
              :required="false"
              :modelo.sync="conveyor.description"
              :msgServer.sync="errors.description"
              type="textarea"
            ></input-form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-gris" @click="reset()">Cancelar</button>
        <button @click="createNewConveyor()" type="button" class="btn btn-primary">Crear Transportador</button>
      </div>
    </form>
  </div>

</template>

<script>
import Swal from 'sweetalert2'

export default {
  name: "CreateConveyor",
  data() {
    return {
      conveyor: {
        name: '',
        description: null,
      },
      errors: {},
    }
  },
  methods: {

    createNewConveyor() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        if (document.querySelectorAll("#validateCreateConveyor .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          return;
        }
        eventBus.$emit("resetValidaciones");
        const data = new FormData()
        data.append('name', this.conveyor.name);
        if (this.conveyor.description){
          data.append('description', this.conveyor.description);
        }

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
              text: 'Registrando Transportador...'
            })
            axios.post('/api/register/store-conveyor', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Transportador creado correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/conveyors";
            }).catch(err => {
              console.log('mostrando el error', err)
              this.$toast.error({
                title: 'Algo salio mal',
                message: 'Comunícate con el administrador',
                showDuration: 1000,
                hideDuration: 8000,
              })
              this.$vs.loading.close();
            });
            setTimeout(() => {
              this.$vs.loading.close()
            }, 2000)
          }
        })

      }, 200)
    },

    getApiTypeConveyors() {
      axios.get('/api/all-type-conveyors').then(resp => {
        this.optionsTypeConveyor = resp.data.data
      })
    },
    getApiTypeIdentification() {
      axios.get('/api/get-identificationtype').then(resp => {
        this.optionsTypeIdentification = resp.data.data
      })
    },
    reset() {
      eventBus.$emit("resetValidaciones");
    }
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
