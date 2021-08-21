<template>
  <div class="modal-content">
    <input type="hidden" @click="traerDatosInvoice" id="traerDatosBotonProductService"/>
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Producto/Servicio</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateUpdateProductService" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtNameProductEdit"
              label="Nombre Producto o Servicio"
              pattern="all"
              errorMsg="Ingrese nombre de producto o servicio válido"
              requiredMsg="El nombre es obligatorio"
              :modelo.sync="name"
              :required="true"
              :msgServer.sync="errors.name"
            ></input-form>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Tipo"
              id="textTypeProductServiceEdit"
              errorMsg
              requiredMsg="El tipo es obligatorio"
              :required="true"
              :modelo.sync="typeProductService"
              :msgServer.sync="errors.typeProductService"
              type="multiselect"
              selectLabel="Tipo"
              :multiselect="{ options: optionsTypeProductService,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeProductService=>typeProductService.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtCodeProductServiceEdit"
              label="Código"
              pattern="all"
              errorMsg="Ingrese un código válido"
              requiredMsg="El código es obligatorio"
              :modelo.sync="code"
              :required="true"
              :msgServer.sync="errors.code"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-code-product-service-edit" class="text-danger">El código ya
              ha sido registrado</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Estado"
              id="textStateZonaEdit"
              errorMsg
              requiredMsg="El estado es obligatorio"
              :required="true"
              :modelo.sync="state"
              :msgServer.sync="errors.state"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsStateProduct,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateProduct=>stateProduct.name
                                        }"
            ></input-form>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Descripción corta"
              id="txtDescriptionShortEdit"
              pattern="all"
              errorMsg="Ingrese texto válido"
              requiredMsg=""
              :required="false"
              :modelo.sync="descriptionShort"
              :msgServer.sync="errors.descriptionShort"
              :options="{
                                                rows: 4
                                                }"
            >
            </input-form>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Descripción"
              id="txtDescriptionEdit"
              pattern="all"
              errorMsg="Ingrese texto válido"
              requiredMsg="La descripción es obligatoria"
              :required="true"
              :modelo.sync="description"
              :msgServer.sync="errors.description"
              :options="{
                                                rows: 4
                                                }"
            >
            </input-form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="updateProductService()" type="button" class="btn btn-primary">Actualizar Producto/Servicio</button>
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
  name: "EditProductService",
  components: {
    Multiselect,
    VuePhoneNumberInput,
  },
  data() {
    return {
      name: '',
      typeProductService: null,
      code: null,
      optionsTypeProductService: [],
      descriptionShort: '',
      description: '',
      codeValidet: null,
      state:null,
      optionsStateProduct: [
        {
          name: 'Activo',
          id: 1
        },
        {
          name: 'Inactivo',
          id: 2
        }
      ],
      idProductService: null,
      errors: {},
    }
  },
  methods: {
    updateProductService() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateUpdateProductService .is-invalid").length > 0) {
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
        data.append('name', this.name);
        data.append('typeProductService', JSON.stringify(this.typeProductService));
        data.append('state', JSON.stringify(this.state));
        data.append('code', this.code);
        data.append('descriptionShort', this.descriptionShort);
        data.append('description', this.description);
        data.append('idProductService', this.idProductService);

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar el registro?',
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
              text: 'Registrando...'
            })
            axios.post('/api/register/update-product-service', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Actualizado Correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/productos-servicios";
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
            }, 200)
          }
        })

      }, 200)
    },

    traerDatosInvoice(e) {
      e.preventDefault();
      this.idProductService = +e.target.value;
      console.log('PRODUCTO ID', this.idProductService)
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })

      setTimeout(() => {
        axios.get('/api/data-product-service/' + this.idProductService).then(resp => {
          this.name = resp.data.data.name
          this.code = resp.data.data.code
          this.codeValidet = resp.data.data.code
          this.descriptionShort = resp.data.data.short_description
          this.description = resp.data.data.description
          this.typeProductService = resp.data.data.product_type
          if (resp.data.data.state === '1') {
            this.state = {name: "Activo", id: 1}
          } else {
            this.state = {name: "Inactivo", id: 2}
          }

        })

        setTimeout(() => {
          this.$vs.loading.close()
        }, 1000)
      }, 500)

    },

    getApiTypeProductService() {
      axios.get('/api/get-product-service').then(resp => {
        this.optionsTypeProductService = resp.data.data
      })
    },
  },
  watch: {
    code: function (val) {
      let data = this
      if (val) {
        console.log('codigo propio', this.codeValidet)
        console.log('value', val)
        if (this.codeValidet !== val) {
          setTimeout(() => {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Válidando código...'
            })
            axios.get('/api/verify-code-product/' + val)
              .then(resp => {
                if (resp.data) {
                  if (this.idValidateCode === 1) {
                    $("#txtCodeProductServiceEdit").addClass("is-invalid");
                    $("#text-verify-code-product-service-edit").css("display", "block");
                  } else {
                    $("#txtCodeProductServiceEdit").addClass("is-invalid");
                    $("#text-verify-code-product-service-edit").css("display", "block");
                  }
                } else {
                  data.emailverify = ''
                  $("#txtCodeProductServiceEdit").removeClass("is-invalid");
                  $("#text-verify-code-product-service-edit").css("display", "none");

                  $("#txtCodeProductServiceEdit").removeClass("is-invalid");
                  $("#text-verify-code-product-service-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtCodeProductServiceEdit").removeClass("is-invalid");
          $("#text-verify-code-product-service-edit").css("display", "none");
        }
      }
    }
  },
  mounted() {
    this.getApiTypeProductService();
  }
}
</script>

<style>
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



