<template>
  <div>
    <input type="hidden" @click="traerDatos" id="traerDatosBoton" />
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel160">
          Modificar Transportador
        </h5>
        <button
          @click="closeModalShowConveyor()"
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="validateEditConveyorForm" class="" method="post">
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
          <button
            @click="closeModalShowConveyor()"
            type="button"
            data-dismiss="modal"
            class="btn btn-gris"
          >
            Cancelar
          </button>
          <button @click="editConveyor()" type="button" class="btn btn-primary">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "ShowConveyor",
  data() {
    return {
      conveyor: {
        name: "",
        description: null,
      },
      errors: {},
    };
  },

  methods: {
    editConveyor() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (
          document.querySelectorAll("#validateEditConveyorForm .is-invalid")
            .length > 0
        ) {
          this.$toast.error({
            title: "Error",
            message:
              "Revisa que todos los campos que son obligatorios tengan datos",
            showDuration: 2000,
            hideDuration: 9000,
            position: "top right",
          });
          return;
        }
        eventBus.$emit("resetValidaciones");
        const data = new FormData();
        data.append("id", this.conveyor.id);
        data.append("name", this.conveyor.name);
        if (this.conveyor.description) {
          data.append("description", this.conveyor.description);
        }
        Swal.fire({
          title: "Confirmar",
          text: "¿Estás seguro de actualizar?",
          confirmButtonColor: "#D9393D",
          cancelButtonColor: "#7D7E7E",
          confirmButtonText: "Aceptar",
          cancelButtonText: "Cancelar",
          customClass: "swal-confirmation",
          showCancelButton: true,
          reverseButtons: true,
          allowOutsideClick: false,
        }).then((result) => {
          if (result.value) {
            this.$vs.loading({
              color: "#3f4f6e",
              text: "Actualizando Transportador...",
            });
            axios
              .post("/api/update-conveyor", data)
              .then((res) => {
                this.$toast.success({
                  title: "¡Muy bien!",
                  message: "Transportador actualizado correctamente",
                  showDuration: 1000,
                  hideDuration: 7000,
                  position: "top right",
                });
                setTimeout(() => {
                  window.location = "/conveyors";
                }, 200);
              })
              .catch((err) => {
                console.log("mostrando el error", err);
                this.$toast.error({
                  title: "Algo salioó mal",
                  message: "Comunícate con el administrador",
                  showDuration: 1000,
                  hideDuration: 8000,
                });
                this.$vs.loading.close();
              });
          }
        });
      }, 200);
    },
    traerDatos(e) {
      e.preventDefault();
      this.idConveyor = +e.target.value;
      this.$vs.loading({
        color: "#3f4f6e",
        text: "Espere un momento por favor...",
      });
      setTimeout(() => {
        axios.get("/api/data-conveyor/" + this.idConveyor).then((resp) => {
          this.conveyor = resp.data;
        });
        this.$vs.loading.close();
      }, 500);
    },
    closeModalShowConveyor() {
      Object.assign(this.conveyor, { name: "", description: "" });
      eventBus.$emit("resetValidaciones");
    },
  },
};
</script>

<style>
.multiselect__tag {
  background: #0082fb !important;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #283046 !important;
}

.multiselect__option--highlight {
  background: #0082fb !important;
}
</style>
