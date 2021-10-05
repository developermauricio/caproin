<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Producto/Servicio</h5>
      <button @click="clearInputProduct()" type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateProductService" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txtNameProduct"
              label="Nombre Producto o Servicio"
              pattern="all"
              errorMsg="Ingrese nombre de producto o servicio válido"
              requiredMsg="El nombre es obligatorio"
              :modelo.sync="name"
              :required="true"
              :msgServer.sync="errors.name"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-name-product" class="text-danger">El nombre del producto no puede contener menos de 5 caracteres</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              label="Tipo"
              id="textTypeProductService"
              errorMsg
              requiredMsg="El tipo es obligatorio"
              :required="true"
              :modelo.sync="typeProductService"
              :msgServer.sync="errors.typeProductService"
              type="multiselect"
              selectLabel="Tipo"
              :multiselect="{ options: optionsTypeBranchOffice,
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
              id="txtCodeProductService"
              label="Código"
              pattern="all"
              errorMsg="Ingrese un código válido"
              requiredMsg="El código es obligatorio"
              :modelo.sync="code"
              :required="true"
              :msgServer.sync="errors.code"
            ></input-form>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-code-product-service" class="text-danger">El código ya
              ha sido registrado</p>
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-code-product" class="text-danger">El código del producto no puede contener menos de 5 caracteres</p>
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
              id="txtDescriptionShort"
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
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-description-short-product" class="text-danger">La descripción corta del producto no puede contener menos de 5 caracteres</p>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Descripción"
              id="txtDescription"
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
            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
               id="text-verify-one-character-description-product" class="text-danger">La descripción del producto no puede contener menos de 5 caracteres</p>
          </div>
        </div>
        <product-price :product_prices="product_prices"></product-price>
      </div>
      <div class="modal-footer">
        <button @click="clearInputProduct()" type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="createNewProductService()" type="button" class="btn btn-primary">Crear Producto/Servicio
        </button>
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
import ProductPrice from './ProductPrice.vue';

export default {
  name: "CreateProductService",
  components: {
    Multiselect,
    VuePhoneNumberInput,
    ProductPrice
  },
  data() {
    return {
      name: '',
      typeProductService: null,
      code: null,
      optionsTypeProductService: [],
      descriptionShort: '',
      description: '',

      product_prices: [],

      identification: '',
      state: {
        name: 'Activo',
        id: 1
      },
      identificationVerify: '',
      codeVerify: '',
      customer: {
        businessName: '',
        typeIdentification: null,
      },
      optionsTypeIdentification: [],

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
      typeBranchOffice: null,
      user: {
        name: '',
        last_name: '',
        phone: '',
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
    clearInputProduct() {
      eventBus.$emit("resetValidaciones");
    },
    createNewProductService() {
      eventBus.$emit("resetValidaciones");
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateProductService .is-invalid").length > 0) {
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
        data.append('product_prices', JSON.stringify(this.product_prices));

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
              text: 'Registrando...'
            })
            axios.post('/api/register/store-product-service', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Creado Correctamente',
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

    getApiTypeProductService() {
      axios.get('/api/get-product-service').then(resp => {
        this.optionsTypeBranchOffice = resp.data.data
      })
    },
  },
  watch: {
    code: function (val) {
      let data = this
      if (val) {
        axios.get('/api/verify-code-product/' + val)
          .then(resp => {
            if (resp.data) {
              $("#txtCodeProductService").addClass("is-invalid");
              $("#text-verify-code-product-service").css("display", "block");
            } else {
              data.emailverify = ''
              $("#txtCodeProductService").removeClass("is-invalid");
              $("#text-verify-code-product-service").css("display", "none");
            }
          }).catch(err => {
        });
      }
      if (val.length <= 5) {
        setTimeout(() => {
          $("#txtCodeProductService").addClass("is-invalid");
          $("#text-verify-one-character-code-product").css("display", "block");
          document.getElementById('text-verify-one-character-code-product').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-code-product').disabled = false;
        $("#txtCodeProductService").removeClass("is-invalid");
        $("#text-verify-one-character-code-product").css("display", "none");
      }
    },
    name: function (val) {
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtNameProduct").addClass("is-invalid");
          $("#text-verify-one-character-name-product").css("display", "block");
          document.getElementById('text-verify-one-character-name-product').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-name-product').disabled = false;
        $("#txtNameProduct").removeClass("is-invalid");
        $("#text-verify-one-character-name-product").css("display", "none");
      }
    },
    descriptionShort: function (val) {
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtDescriptionShort").addClass("is-invalid");
          $("#text-verify-one-character-description-short-product").css("display", "block");
          document.getElementById('text-verify-one-character-description-short-product').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-description-short-product').disabled = false;
        $("#txtDescriptionShort").removeClass("is-invalid");
        $("#text-verify-one-character-description-short-product").css("display", "none");
      }
    },
    description: function (val) {
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtDescription").addClass("is-invalid");
          $("#text-verify-one-character-description-product").css("display", "block");
          document.getElementById('text-verify-one-character-description-product').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-description-product').disabled = false;
        $("#txtDescription").removeClass("is-invalid");
        $("#text-verify-one-character-description-product").css("display", "none");
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


