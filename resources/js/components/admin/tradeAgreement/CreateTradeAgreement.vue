<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Acuerdo Comercial</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateTradeAgreement" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtConsecutiveOffer"
              label="Consecutivo de Oferta"
              pattern="all"
              errorMsg="Ingrese consecutivo de oferta válido"
              requiredMsg="El consecutivo de oferta es obligatorio"
              :modelo.sync="consecutiveOffer"
              :required="true"
              :msgServer.sync="errors.consecutiveOffer"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtVersion"
              label="Versión"
              pattern="all"
              errorMsg="Ingrese versión válida"
              requiredMsg="La versión es obligatoria"
              :modelo.sync="version"
              :required="true"
              :msgServer.sync="errors.version"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Cliente"
              id="txtTradeAgreementCustomer"
              errorMsg
              requiredMsg="El cliente es obligatorio"
              :required="true"
              :modelo.sync="customer"
              :msgServer.sync="errors.customer"
              type="multiselect"
              selectLabel="Selecciona un cliente"
              :multiselect="{ options: optionsCustomerTradeAgreement,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Selecciona un cliente',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': customer=>customer.business_name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Moneda"
              id="txtCurrencyCustomer"
              errorMsg
              requiredMsg="La moneda es obligatoria"
              :required="true"
              :modelo.sync="currency"
              :msgServer.sync="errors.currency"
              type="multiselect"
              selectLabel="Selecciona una moneda"
              :multiselect="{ options: optionsCurrencyTradeAgreement,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Selecciona una moneda',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': currency=>currency.code
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              type="money"
              label="Valor Unitario"
              id="txtUnitValue"
              pattern="num"
              errorMsg="Ingrese un valor válido"
              requiredMsg="El valor es obligatorio"
              :required="true"
              :modelo.sync="valueUnit"
              :msgServer.sync="errors.valueUnit"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              type="money"
              label="TRM"
              id="txtTRMValue"
              pattern="num"
              errorMsg="Ingrese un valor de TRM válido"
              requiredMsg="El TRM es obligatorio"
              :required="true"
              :modelo.sync="TRM"
              :msgServer.sync="errors.TRM"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInternalProductCodeTradeAgreement"
              label="Código de Producto Interno"
              pattern="all"
              errorMsg="Ingrese código de producto interno válido"
              requiredMsg="El código de producto interno es obligatorio"
              :modelo.sync="InternalProductCode"
              :required="true"
              :msgServer.sync="errors.InternalProductCode"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtClientProductCodeTradeAgreement"
              label="Código de Producto Cliente"
              pattern="all"
              errorMsg="Ingrese código de producto cliente válido"
              requiredMsg="El código de producto cliente es obligatorio"
              :modelo.sync="ClientProductCode"
              :required="true"
              :msgServer.sync="errors.ClientProductCode"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Estado"
              id="textStateTradeAgreement"
              errorMsg
              requiredMsg="El estado  es obligatorio"
              :required="true"
              :modelo.sync="stateTradeAgreement"
              :msgServer.sync="errors.stateTradeAgreement"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsStateTradeAgreement,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateTradeAgreement=>stateTradeAgreement.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de Creación"
              id="txtCreationDate"
              pattern="all"
              errorMsg="Ingrese una fecha de creación válida"
              requiredMsg="La fecha de cración es obligatoria"
              :required="true"
              :modelo.sync="dateCreate"
              :msgServer.sync="errors.dateCreate"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de Final"
              id="txtFinalDate"
              pattern="all"
              errorMsg="Ingrese una fecha final válida"
              requiredMsg="La fecha final es obligatoria"
              :required="true"
              :modelo.sync="dateFinal"
              :msgServer.sync="errors.dateFinal"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtDeliveryTimer"
              label="Tiempo de Entrega"
              pattern="all"
              errorMsg="Ingrese tiempo de entrega válido"
              requiredMsg="El tiempo de entrega es obligatorio"
              :modelo.sync="deliveryTime"
              :required="true"
              :msgServer.sync="errors.deliveryTime"
            ></input-form>
          </div>
        </div>
        <!--=====================================
		      PRODUCTOS ASIGNADOS
        ======================================-->
        <div class="row" v-if="productsTradeAgreement.length > 0">
          <div class="col-12">
            <label>Productos Asignados</label>
            <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                <tr>
                  <th>Código</th>
                  <th>Tipo</th>
                  <th>Nombre</th>
                  <th>Descripción</th>
                  <th>Cantidad Mínima</th>
                  <th>Acciones</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in productsTradeAgreement" :key="product.code">
                  <td>{{ product.code }}</td>
                  <td>{{ product.product_type.name }}</td>
                  <td>{{ product.name }}</td>
                  <td>{{ product.description }}</td>
                  <td>
                    <input-form
                      id="txtMiniumAmount"
                      label=""
                      pattern="num"
                      errorMsg="Ingrese una cantidad minima válido"
                      requiredMsg="La cantidad minima es obligatorio"
                      :modelo.sync="product.minimum"
                      :msgServer.sync="errors.product"
                      :required="true"
                    ></input-form>
                  </td>
                  <td> <button @click="removedProducts(product)" class="btn btn-primary">Quitar Producto o Servicio</button></td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <!--=====================================
		      COLAPSE PARA AGREGAR PRODUCTOS
        ======================================-->
        <div class="row">
          <div class="col-12">
            <div class="collapse-default">
              <div class="card">
                <div id="headingCollapse1" class="card-header" data-toggle="collapse" role="button"
                     data-target="#collapseProducts"
                     aria-expanded="false" aria-controls="collapse1">
                  <span
                    class="lead collapse-title">Asignar Productos <strong>(clic para agregar productos)</strong></span>
                </div>
                <p class="pl-1">Solo se muestran los productos activos</p>
                <div id="collapseProducts" role="tabpanel" aria-labelledby="headingCollapse1" class="collapse">
                  <div class="card-body">
                    <vue-good-table

                      :pagination-options="{
                        enabled: true,
                        mode: 'records',
                        perPage: 10,
                        position: 'top',
                        perPageDropdown: [3, 7, 9],
                        dropdownAllowAll: false,
                        setCurrentPage: 2,
                        nextLabel: 'Siguiente',
                        prevLabel: 'Anterior',
                        rowsPerPageLabel: 'Filas por página',
                        ofLabel: 'de',
                        pageLabel: 'page', // for 'pages' mode
                        allLabel: 'All',
                        // infoFn: (params) => `my own page ${params.firstRecordOnPage}`,
                      }"
                      :columns="columns"
                      :rows="optionsProductsTradeAgreement"
                      :search-options="{
                        enabled: true,
                        placeholder: 'Buscar Productos o Servicios',
                      }"
                    >
                      <template slot="table-row" slot-scope="props">
                        <button v-if="props.column.field == 'btn'" @click="props.row.add ? removedProducts(props.row) : addProducts(props.row)" type="button"
                                :class="props.row.add ? 'btn btn-primary' : 'btn btn-success'">
                          {{ props.row.add ? 'Quitar Producto' : 'Agregar Producto' }}
                        </button>
                      </template>

                    </vue-good-table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--=====================================
		      DESCRIPCIÓN CORTA
        ======================================-->
        <div class="row pt-2">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Descripción"
              id="txtDescriptionShortTrade"
              pattern="all"
              errorMsg="Ingrese una descripción válida"
              requiredMsg="La descripción es requerida"
              :required="true"
              :modelo.sync="descriptionShort"
              :msgServer.sync="errors.descriptionShort"
              :options="{
                                                rows: 5
                                                }"
            >
            </input-form>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button  type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="createTradeAgreement()" type="button" class="btn btn-primary">Crear Acuerdo Comercial</button>
      </div>
    </form>
  </div>
</template>
<script src="/node_modules/feather-icons/dist/feather.js"></script>
<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'
import Multiselect from "vue-multiselect";
import vue2Dropzone from "vue2-dropzone";
import Swal from "sweetalert2";

export default {
  name: "CreateTradeAgreement",
  components: {
    Multiselect,
    Datepicker,
    moment
  },
  data() {
    return {
      consecutiveOffer: null,
      version: null,
      customer: null,
      currency: null,
      dateFinal: null,
      valueUnit: null,
      dateCreate: null,
      deliveryTime: null,
      TRM: null,
      InternalProductCode: null,
      ClientProductCode: null,
      stateTradeAgreement: null,
      descriptionShort: null,

      optionsCustomerTradeAgreement: [],
      optionsCurrencyTradeAgreement: [],
      optionsProductsTradeAgreement: [],
      productsTradeAgreement: [],
      optionsStateTradeAgreement: [
        {
          name: 'Vigente',
          id: 1
        },
        {
          name: 'Finalizado',
          id: 2
        }
      ],

      columns: [
        {
          label: 'Código',
          field: 'code',
        },
        {
          label: 'Tipo',
          field: 'product_type.name',
        },
        {
          label: 'Nombre',
          field: 'name',
        },
        {
          label: 'Descripción',
          field: 'description',
          sortable: false,
        },
        {
          label: 'Acciones',
          field: 'btn',
          html: true,
        }

      ],
      errors: {},
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 0
      },
    }
  },
  methods: {
    createTradeAgreement(){
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateTradeAgreement .is-invalid").length > 0) {
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
        data.append('consecutive_Offer', this.consecutiveOffer);
        data.append('version', this.version);
        data.append('customer', JSON.stringify(this.customer));
        data.append('state', JSON.stringify(this.stateTradeAgreement));
        data.append('short_description', this.descriptionShort);
        data.append('internal_product_code', this.InternalProductCode);
        data.append('client_product_code', this.ClientProductCode);
        data.append('currency', JSON.stringify(this.currency));
        data.append('creation_date', moment(this.dateCreate).format("YYYY-MM-DD HH:mm:ss"));
        data.append('final_date', moment(this.dateFinal).format("YYYY-MM-DD HH:mm:ss"));
        data.append('delivery_time', this.deliveryTime);
        data.append('unit_value', this.valueUnit);
        data.append('TRM', this.TRM);
        data.append('products', JSON.stringify(this.productsTradeAgreement));

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
                text: 'Registrando Acuerdo Comercial...'
              })
              axios.post('/api/register/store-trade-agreement', data).then(resp =>{
                this.$toast.success({
                  title: '¡Muy bien!',
                  message: 'Acuerdo comercial creado correctamente',
                  showDuration: 1000,
                  hideDuration: 7000,
                  position: 'top right',
                })
                window.location = "/acuerdos-comerciales";
              }).catch(err => {
                console.log('mostrando el error', err)
                this.$toast.error({
                  title: 'Algo salio mal',
                  message: 'Comunícate con el administrador',
                  showDuration: 1000,
                  hideDuration: 8000,
                })
              })
              setTimeout(() => {
                this.$vs.loading.close()
              }, 2000)
            }
          });
      }, 200)

    },
    addProducts(product) {
      console.log(product)
      this.productsTradeAgreement.push(product)
      let code = product.code
      for (let i = 0; i < this.productsTradeAgreement.length; i++) {
        if (this.productsTradeAgreement[i].code === code) {
          Object.assign(this.productsTradeAgreement[i], {add: 1});
        }
      }
    },
    removedProducts(product){
      let code = product.code
      for (let i = 0; i < this.productsTradeAgreement.length; i++) {
        if (this.productsTradeAgreement[i].code === code) {
          delete this.productsTradeAgreement[i].add;
          this.productsTradeAgreement.splice(i, 1)
        }
      }
    },
    getApiCustomer() {
      axios.get('/api/all-customers').then(resp => {
        this.optionsCustomerTradeAgreement = resp.data.data
      });
    },
    getApiCurrency() {
      axios.get('/api/all-currency').then(resp => {
        this.optionsCurrencyTradeAgreement = resp.data.data
      });
    },
    getApiProducts() {
      axios.get('/api/all-products').then(resp => {
        this.optionsProductsTradeAgreement = resp.data.data
      });
    }
  },

  mounted() {
    window.feather.replace()
    this.getApiCustomer();
    this.getApiCurrency();
    this.getApiProducts();
  }
}

</script>

<style scoped>

</style>
