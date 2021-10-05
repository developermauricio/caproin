<template>
  <div class="modal-content">
    <input type="hidden" @click="traerDatosTradeAgreement" id="traerDatosBotonTradeAgreement"/>
    <div class="modal-header">
      <!--      <h5 class="modal-title" id="myModalLabel160" v-show="showDetailInvoice">Información Factura</h5>-->
      <h5 class="modal-title" id="myModalLabel1600">Información Acuerdo Comercial</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <!--=====================================
        INFORMACIÓN ACUERDO COMERCIAL
      ======================================-->
      <div class="row" v-show="showDetailTradeAgreement">
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Consecutivo Oferta:</label>
            <p v-text="consecutiveOfferDetail"></p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Versión:</label>
            <p>{{ versionDetail }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Cliente:</label>
            <p>{{ customerDetail }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Moneda:</label>
            <p>{{ currencyDetail }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4" v-if="currencyDetail === 'USD'">
          <div class="form-group">
            <label class="font-weight-bold">TRM:</label>
            <p>${{ dollarUSLocale.format(TRMDetail) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Estado:</label>
            <p>{{ stateFormTrade.id === 1 ? 'Vigente' : 'Finalizado' }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha de Creación:</label>
            <p>{{ moment(dateCreateDetail).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha Final:</label>
            <p>{{ moment(dateFinalDetail).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Tiempo de Entrega:</label>
            <p>{{ deliveryTimeDetail }}</p>
          </div>
        </div>
      </div>
      <div class="row" v-show="showDetailTradeAgreement">
        <div class="col-12">
          <div class="form-group">
            <label class="font-weight-bold">Descripción:</label>
            <p>{{ descriptionShortDetail }}</p>
          </div>
        </div>
      </div>
      <!--=====================================
		      PRODUCTOS ASIGNADOS
        ======================================-->
      <div class="row" v-show="showDetailTradeAgreement" v-if="productsTradeAgreement.length > 0">
        <div class="col-12">
          <label class="font-weight-bold">Productos Asignados:</label>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead>
              <tr>
                <th>Código Prod. Interno</th>
                <th>Tipo</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Cantidad Mínima</th>
                <th>Valor Unitario</th>
                <th>Código Producto Cliente</th>
                <th>Descripción</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="product in productsTradeAgreement" :key="product.code">
                <td>{{ product.code }}</td>
                <td>{{ product.product_type.name }}</td>
                <td>{{ product.name }}</td>
                <td>
                  <div class="centerx">
                    <vs-tooltip
                      title="Descripción"
                      :text="product.description ">
                      <vs-button color="warning" type="flat">Ver descripción</vs-button>
                    </vs-tooltip>
                  </div>
                </td>
                <td>{{ product.pivot.minimum_amount }}</td>
                <td>${{ dollarUSLocale.format(product.pivot.unit_value) }}</td>
                <td>{{ product.pivot.client_product_code }}</td>
                <td>{{ product.pivot.description }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!--=====================================
        SECCIÓN PARA EDITAR
      ======================================-->
      <div v-show="showEditTradeAgreement">
        <form class="" id="validateEditTradeAgreement" method="post">
          <div class="row">
            <div class="col-12 col-md-4 col-lg-4">
              <input-form
                id="txtConsecutiveOfferEditV"
                label="Consecutivo de Oferta"
                pattern="all"
                errorMsg="Ingrese consecutivo de oferta válido"
                requiredMsg="El consecutivo de oferta es obligatorio"
                :modelo.sync="consecutiveOfferDetail"
                :required="true"
                :msgServer.sync="errors.consecutiveOfferDetail"
              ></input-form>
              <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                 id="text-verify-consecutivo-oferta-edit" class="text-danger">El consecutivo de oferta ya ha sido registrado</p>
              <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                 id="text-verify-one-character-consecutive-offer-edit" class="text-danger">El consecutivo oferta no puede contener un caracter</p>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
              <input-form
                id="txtVersionEdit"
                label="Versión"
                pattern="all"
                errorMsg="Ingrese versión válida"
                requiredMsg="La versión es obligatoria"
                :modelo.sync="versionDetail"
                :required="true"
                :msgServer.sync="errors.versionDetail"
              ></input-form>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
              <input-form
                label="Cliente"
                id="txtTradeAgreementCustomerEdit"
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
                id="txtCurrencyTradeEdit"
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
            <div class="col-12 col-md-4 col-lg-4" v-if="currency.id === 1">
              <input-form
                type="money"
                label="TRM"
                id="txtTRMValueEdit"
                pattern="num"
                errorMsg="Ingrese un valor de TRM válido"
                requiredMsg="El TRM es obligatorio"
                :required="true"
                :modelo.sync="TRMDetail"
                :msgServer.sync="errors.TRMDetail"
                :money="money"
              ></input-form>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
              <input-form
                label="Estado"
                id="textStateTradeAgreementEdit"
                errorMsg
                requiredMsg="El estado  es obligatorio"
                :required="true"
                :modelo.sync="stateFormTrade"
                :msgServer.sync="errors.stateFormTrade"
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
                :modelo.sync="dateCreateDetail"
                :msgServer.sync="errors.dateCreateDetail"
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
                :modelo.sync="dateFinalDetail"
                :msgServer.sync="errors.dateFinalDetail"
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
                :modelo.sync="deliveryTimeDetail"
                :required="true"
                :msgServer.sync="errors.deliveryTimeDetail"
              ></input-form>
            </div>

          </div>
          <!--=====================================
		          DESCRIPCIÓN
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
                :modelo.sync="descriptionShortDetail"
                :msgServer.sync="errors.descriptionShortDetail"
                :options="{
                                                rows: 5
                                                }"
              >
              </input-form>
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
                    <th>Código Prod. Interno</th>
                    <th>Tipo</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad Mínima</th>
                    <th>Valor Unitario</th>
                    <th>Código Producto Cliente</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="product in productsTradeAgreement" :key="product.code">
                    <td>{{ product.code }}</td>
                    <td>{{ product.product_type.name }}</td>
                    <td>{{ product.name }}</td>
                    <td>
                      <div class="centerx">
                        <vs-tooltip
                          title="Descripción"
                          :text="product.description ">
                          <vs-button color="warning" type="flat">Ver descripción</vs-button>
                        </vs-tooltip>
                      </div>
                    </td>
                    <td>
                      <input-form
                        id="txtMiniumAmount"
                        label=""
                        pattern="num"
                        errorMsg="Ingrese una cantidad minima válido"
                        requiredMsg="La cantidad minima es obligatorio"
                        :modelo.sync="product.pivot.minimum_amount"
                        :msgServer.sync="errors.product"
                        :required="true"
                      ></input-form>
                    </td>
                    <td>
                      <input-form
                        style="width: 15vh;"
                        type="money"
                        label=""
                        id="txtValueUnitEdit"
                        pattern="num"
                        errorMsg="Ingrese un valor unitario válido"
                        requiredMsg="El valor unitario es obligatorio"
                        :required="true"
                        :modelo.sync="product.pivot.unit_value"
                        :msgServer.sync="errors.product"
                        :money="money"
                      ></input-form>
                    </td>
                    <td>
                      <input-form
                        id="txtCodeInterClientEdit"
                        label=""
                        pattern="num"
                        errorMsg="Ingrese un código interno del cliente válido"
                        requiredMsg="La código interno del cliente es obligatorio"
                        :modelo.sync="product.pivot.client_product_code"
                        :msgServer.sync="errors.product"
                        :required="true"
                      ></input-form>
                    </td>
                    <td>
                      <input-form
                        style="width: 44vh;"
                        type="textarea"
                        label=""
                        id="txtDescriptionProducEdit"
                        pattern="all"
                        errorMsg="Ingrese una descripción válida"
                        requiredMsg="La descripción es requerida"
                        :required="true"
                        :modelo.sync="product.pivot.description"
                        :msgServer.sync="errors.product"
                        :options="{
                                                rows: 5
                                                }"
                      >
                      </input-form>
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
          <div class="row" v-show="showEditTradeAgreement">
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
                        :rows="listSinRepetidos"
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
        </form>
      </div>
      <!--=====================================
        BOTONES PARA EDITAR
      ======================================-->
      <div class="row pl-1">
        <div class="demo-inline-spacing">
          <!-- Boton para agregar archivos -->
          <button v-if="showDetailTradeAgreement === true && $gate.allow('editTradeAgreement', 'tradeAgreement')" @click="btnEditTradeAgreement"
                  type="button"
                  class="btn btn-primary waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Editar
          </button>
          <button v-if="showEditTradeAgreement === true" @click="btnCancelEditTradeAgreement"
                  type="button"
                  class="btn btn-gris waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Cancelar
          </button>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button @click="closeModalEditTradeAgreement()" v-if="showDetailTradeAgreement === true" type="button" data-dismiss="modal"
              class="btn btn-gris">Cerrar
      </button>
      <button v-if="showEditTradeAgreement === true" @click="btnCancelEditTradeAgreement()" class="btn btn-gris">Cancelar</button>
      <button v-if="showEditTradeAgreement === true" @click="updateTradeAgreement()" type="button" class="btn btn-primary">
        Actualizar Factura
      </button>
    </div>
  </div>
</template>

<script>
import moment from 'moment';
import Multiselect from "vue-multiselect";
import Datepicker from "vuejs-datepicker";
import vue2Dropzone from "vue2-dropzone";
import {en, es} from "vuejs-datepicker/dist/locale";
import Swal from "sweetalert2";

export default {
  name: "ShowEditTradeAgreement",
  components: {
    Multiselect,
    Datepicker,
    vue2Dropzone,
    moment
  },
  data() {
    return {
      idTradeAgreement: null,
      moment: moment,
      en: en,
      es: es,
      showDetailTradeAgreement: true,
      showEditTradeAgreement: false,

      consecutiveOfferDetail: null,
      consecutiveOfferPropio: null,
      versionDetail: null,
      customerDetail: null,
      customer: null,
      currencyDetail: null,
      currency: {id:null},
      dateFinalDetail: null,
      dateCreateDetail: null,
      deliveryTimeDetail: null,
      TRMDetail: null,
      stateTradeAgreementDetail: null,
      stateFormTrade: {
        id: null,
      },

      optionsCustomerTradeAgreement: [],
      optionsCurrencyTradeAgreement: [],
      optionsProductsTradeAgreement: [],
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

      productsTradeAgreement: {},
      descriptionShortDetail: null,
      dollarUSLocale: Intl.NumberFormat('es-US'),
      errors:{},
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
    btnEditTradeAgreement() {
      this.showDetailTradeAgreement = false;
      this.showEditTradeAgreement = true;
    },

    btnCancelEditTradeAgreement() {
      this.showEditTradeAgreement = false;
      this.showDetailTradeAgreement = true;

    },
    closeModalEditTradeAgreement(){

    },
    traerDatosTradeAgreement(e) {
      e.preventDefault();
      // this.getApiProducts();
      this.idTradeAgreement = +e.target.value;
      window.feather.replace()
      console.log('ID ACUERDO', this.idTradeAgreement)
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })
      setTimeout(() => {
        axios.get('/api/data-trade-agreement/' + this.idTradeAgreement).then(resp => {
          console.log('DATOS', resp.data.data)
          this.consecutiveOfferDetail = resp.data.data.consecutive_Offer
          this.consecutiveOfferPropio = resp.data.data.consecutive_Offer
          this.versionDetail = resp.data.data.version
          this.customerDetail = resp.data.data.customer.business_name
          this.customer = resp.data.data.customer
          this.currencyDetail = resp.data.data.currency.code
          this.currency = resp.data.data.currency
          this.dateFinalDetail = resp.data.data.final_date
          this.dateCreateDetail = resp.data.data.creation_date
          this.deliveryTimeDetail = resp.data.data.delivery_time
          this.TRMDetail = resp.data.data.TRM
          this.stateTradeAgreementDetail = resp.data.data.state
          this.descriptionShortDetail = resp.data.data.short_description
          this.productsTradeAgreement = resp.data.data.products

          if (this.stateTradeAgreementDetail === '1') {
            this.stateFormTrade = {name: "Vigente", id: 1}
          } else {
            this.stateFormTrade = {name: "Finalizado", id: 2}
          }
        })
        this.$vs.loading.close()
      }, 2000)
    },
    updateTradeAgreement(){
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateEditTradeAgreement .is-invalid").length > 0) {
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

        data.append('consecutive_Offer', this.consecutiveOfferDetail);
        data.append('version', this.versionDetail);
        data.append('idTradeAgreement', this.idTradeAgreement);
        data.append('customer', JSON.stringify(this.customer));
        data.append('state', JSON.stringify(this.stateFormTrade));
        data.append('short_description', this.descriptionShortDetail);
        data.append('currency', JSON.stringify(this.currency));
        data.append('creation_date', moment(this.dateCreateDetail).format("YYYY-MM-DD HH:mm:ss"));
        data.append('final_date', moment(this.dateFinalDetail).format("YYYY-MM-DD HH:mm:ss"));
        data.append('delivery_time', this.deliveryTimeDetail);
        data.append('TRM', this.TRMDetail);
        data.append('products', JSON.stringify(this.productsTradeAgreement));

          Swal.fire({
            title: 'Confirmar',
            text: '¿Estás seguro de actualizar el acuerdo comercial?',
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
                text: 'Actualizando Acuerdo Comercial...'
              })
              axios.post('/api/register/update-trade-agreement', data).then(resp =>{
                this.$toast.success({
                  title: '¡Muy bien!',
                  message: 'Acuerdo comercial actualizado correctamente',
                  showDuration: 1000,
                  hideDuration: 7000,
                  position: 'top right',
                })
                // window.location = "/acuerdos-comerciales";
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
          })

      },200)
    },

    btnCancelEditTrade(){

    },

    addProducts(product) {
      product.pivot = {
        minimum_amount:'',
        client_product_code:'',
        unit_value: '',
        description: '',
      }

      this.productsTradeAgreement.push(Object.assign({},product))
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
  computed:{
    listSinRepetidos(){
      return this.optionsProductsTradeAgreement.filter((option) =>{
        return !this.productsTradeAgreement.find((product)=>{
          return product.id == option.id
        })
      })
    },
  },

  watch: {
    consecutiveOfferDetail: function (val) {
      let data = this
      if (val) {
        console.log('codigo propio', this.consecutiveOfferPropio)
        console.log('value', val)
        if (this.consecutiveOfferPropio !== val) {
          setTimeout(() => {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Válidando Consecutivo...'
            })
            axios.get('/api/verify-consecutivo-oferta/' + val)
              .then(resp => {
                if (resp.data) {
                  if (this.idValidateCode === 1) {
                    $("#txtConsecutiveOfferEdit").addClass("is-invalid");
                    $("#text-verify-consecutivo-oferta-edit").css("display", "block");
                  } else {
                    $("#txtConsecutiveOfferEdit").addClass("is-invalid");
                    $("#text-verify-consecutivo-oferta-edit").css("display", "block");
                  }
                } else {
                  data.emailverify = ''
                  $("#txtConsecutiveOfferEdit").removeClass("is-invalid");
                  $("#text-verify-consecutivo-oferta-edit").css("display", "none");

                  $("#txtConsecutiveOfferEdit").removeClass("is-invalid");
                  $("#text-verify-consecutivo-oferta-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtConsecutiveOfferEdit").removeClass("is-invalid");
          $("#text-verify-consecutivo-oferta-edit").css("display", "none");
        }
      }
      if (val.length === 1) {
        setTimeout(() => {
          $("#txtConsecutiveOfferEditV").addClass("is-invalid");
          $("#text-verify-one-character-consecutive-offer-edit").css("display", "block");
          document.getElementById('text-verify-one-character-consecutive-offer-edit').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-consecutive-offer-edit').disabled = false;
        $("#txtConsecutiveOfferEditV").removeClass("is-invalid");
        $("#text-verify-one-character-consecutive-offer-edit").css("display", "none");
      }
    }
  },

  mounted() {
    window.feather.replace()
    this.getApiCustomer()
    this.getApiProducts()
    this.getApiCurrency()

  }
}
</script>

<style>
.multiselect__tag {
  background: #D9393D !important;
}

.multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
  background: #7D7E7E !important;
}

.multiselect__option--highlight {
  background: #D9393D !important;
}
</style>
