<template>
  <div class="modal-content">
    <input type="hidden" @click="traerDatosInvoice" id="traerDatosBotonInvoice"/>
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160" v-show="showDetailInvoice">Información Factura</h5>
      <h5 class="modal-title" id="myModalLabel1600" v-show="showEditInvoice">Editar Factura</h5>
      <button @click="closeModalEditInvoice()" type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <!--=====================================
        INFORMACIÓN
      ======================================-->
      <div class="row" v-show="showDetailInvoice">
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Número de factura:</label>
            <p v-text="invoice_number"></p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Estado:</label>
            <p>{{ stateDetail.name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Tipo de Pago:</label>
            <p>{{ paymentTypeDetail.name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4" v-if="paymentTypeDetail.id === 2">
          <div class="form-group">
            <label class="font-weight-bold">Valor Pagado:</label>
            <p>${{ dollarUSLocale.format(valuePaymentDetail) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Número de factura electrónica:</label>
            <p v-text="electronic_invoice_number"></p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha de emisión de la factura:</label>
            <p>{{ moment(date_issue).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha de vencimiento de la factura:</label>
            <p>{{ moment(expiration_date).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Tipo Factura:</label>
            <p>{{ invoice_type_detail.name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Cliente:</label>
            <p>{{ customerDetail.business_name }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Valor:</label>
            <p>${{ dollarUSLocale.format(value) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha de recibo por parte del cliente:</label>
            <p>{{ moment(date_received_client).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha de pago por parte del cliente:</label>
            <p>{{ moment(date_payment_client).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha Factura Casa Representante:</label>
            <p>{{ moment(invoice_date_house_manufacturer).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Número de factura casa representante:</label>
            <p>{{ invoice_number_house_representative }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Valor de la comisión:</label>
            <p>${{ dollarUSLocale.format(commission_value) }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Fecha Recibo Comisión:</label>
            <p>{{ moment(commission_receipt_date).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">
          <div class="form-group">
            <label class="font-weight-bold">Nueva fecha concertada de pago:</label>
            <p>{{ moment(new_agreed_payment_date).locale('es').format("MM-DD-YYYY") }}</p>
          </div>
        </div>
      </div>

      <div class="row" v-show="showDetailInvoice">
        <div class="col-12" v-if="urlsArchiveInvoice">
          <div class="form-group">
            <label class="font-weight-bold">Archivos:</label>
          </div>
        </div>
        <div class="col-12 col-lg-4 col-md-4" v-if="urlsArchiveInvoice.length > 0"
             v-for="archives in urlsArchiveInvoice" :key="archives.uuid">
          <div class="card shadow-none bg-transparent border-secondary" style="cursor: pointer"
               @click="openArchiveInvoice(archives.archive)">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-center w-100">
                <img v-if="archives.type === 'csv'
                                                            || archives.type === 'pdf'
                                                            || archives.type === 'docx'
                                                            || archives.type === 'pptx'
                                                            || archives.type === 'xlsx'
                                                            || archives.type === 'jpg'
                                                            || archives.type === 'png'"
                     :src="'/images/archives-icons/'+archives.type+'.png'" alt="file-icon" height="35"/>
                <img v-else src="/images/archives-icons/archive.png" alt="" height="35">
              </div>
              <h6 class="card-title text-center pt-1" v-text="archives.nameArchive"></h6>
              <p class="card-text text-center">
                <small class="text-muted">Vista Previa</small>
              </p>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="form-group">
            <label class="font-weight-bold">Comentarios u Observaciones:</label>
            <p>{{ comments }}</p>
          </div>
        </div>
      </div>
      <!--=====================================
        DATOS PARA EDITAR
      ======================================-->
      <form class="" id="validateCreateInvoiceEdit" method="post">
        <div v-show="showEditInvoice" class="row" style="display: none">
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceNumberEdit"
              label="Número de factura"
              pattern="all"
              errorMsg="Ingrese número de factura válido"
              requiredMsg="El número de factura es obligatorio"
              :modelo.sync="invoice_number"
              :required="true"
              :msgServer.sync="errors.invoice_number"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Estado"
              id="txtInvoiceStateEdit"
              errorMsg
              requiredMsg="El estado es obligatorio"
              :required="true"
              :modelo.sync="state"
              :msgServer.sync="errors.state"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsStateInvoice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado de la factura',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateInvoice=>stateInvoice.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Tipo de Pago"
              id="txtInvoicePaymentTypeEdit"
              errorMsg
              requiredMsg="El tipo de pago es obligatorio"
              :required="true"
              :modelo.sync="paymentType"
              :msgServer.sync="errors.paymentType"
              type="multiselect"
              selectLabel="Tipo de Pago"
              :multiselect="{ options: optionsPaymentType,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Pago',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': paymentType=>paymentType.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4" v-if="paymentType.id === 2">
            <input-form
              type="money"
              label="Valor Pagado"
              id="txtValueInvoicePaymentParcialEdit"
              pattern="num"
              errorMsg="Ingrese un valor válido"
              requiredMsg="Agregar el valor pagado"
              :required="true"
              :modelo.sync="valuePaymentParcial"
              :msgServer.sync="errors.valuePaymentParcial"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceElectronicNumberEdit"
              label="Número de factura electrónica"
              pattern="all"
              errorMsg="Ingrese número de factura electrónica válido"
              requiredMsg="El número de la factura electrónica es obligatorio"
              :modelo.sync="electronic_invoice_number"
              :required="true"
              :msgServer.sync="errors.electronic_invoice_number"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de emisión de la factura"
              id="txtDateIssueInvoice"
              pattern="all"
              errorMsg="Ingrese una fecha de emisión válida"
              requiredMsg="La fecha de emisión es obligatorio"
              :required="true"
              :modelo.sync="date_issue"
              :msgServer.sync="errors.date_issue"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de vencimiento de la factura"
              id="txtExpirationDateInvoice"
              pattern="all"
              errorMsg="Ingrese una fecha de vencimiento válida"
              requiredMsg="La fecha de vencimiento es obligatorio"
              :required="true"
              :modelo.sync="expiration_date"
              :msgServer.sync="errors.expiration_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Tipo de Factura"
              id="txtInvoiceTypeEdit"
              errorMsg
              requiredMsg="El tipo de factura es requerida"
              :required="true"
              :modelo.sync="invoice_type"
              :msgServer.sync="errors.invoice_type"
              type="multiselect"
              selectLabel="Estado"
              :multiselect="{ options: optionsTypeInvoice,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Factura',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeInvoice=>typeInvoice.name
                                        }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Cliente"
              id="txtInvoiceCustomerEdit"
              errorMsg
              requiredMsg="El cliente es obligatorio"
              :required="true"
              :modelo.sync="customer"
              :msgServer.sync="errors.customer"
              type="multiselect"
              selectLabel="Selecciona un cliente"
              :multiselect="{ options: optionsCustomerInvoice,
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
              type="money"
              label="Valor"
              id="txtValueInvoice"
              pattern="all"
              errorMsg="Ingrese un valor válido"
              requiredMsg="El valor es obligatorio"
              :required="true"
              :modelo.sync="value"
              :msgServer.sync="errors.value"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de recibo por parte del cliente"
              id="txtDateReceivedClient"
              pattern="all"
              errorMsg="Ingrese una fecha de recibo válida"
              requiredMsg="La fecha de recibo es obligatorio"
              :required="true"
              :modelo.sync="date_received_client"
              :msgServer.sync="errors.date_received_client"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de pago por parte del cliente"
              id="txtDatePaymentClient"
              pattern="all"
              errorMsg="Ingrese una fecha de pago válida"
              requiredMsg="La fecha de pago es obligatorio"
              :required="true"
              :modelo.sync="date_payment_client"
              :msgServer.sync="errors.date_payment_client"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de factura casa representante"
              id="txtInvoiceDateHouseManufacturer"
              pattern="all"
              errorMsg="Ingrese una fecha casa representante válida"
              requiredMsg="La fecha casa representante es obligatorio"
              :required="true"
              :modelo.sync="invoice_date_house_manufacturer"
              :msgServer.sync="errors.invoice_date_house_manufacturer"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceNumberHouseRepresentativeEdit"
              label="Número de factura casa representate"
              pattern="all"
              errorMsg="Ingrese número de factura casa representante válido"
              requiredMsg="El número de la factura casa representante es obligatorio"
              :modelo.sync="invoice_number_house_representative"
              :required="true"
              :msgServer.sync="errors.invoice_number_house_representative"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              type="money"
              label="Valor de la comisión"
              id="txtValueCommisionInvoice"
              pattern="all"
              errorMsg="Ingrese un valor de comisión válido"
              requiredMsg="El valor de la comisión es obligatorio"
              :required="true"
              :modelo.sync="commission_value"
              :msgServer.sync="errors.commission_value"
              :money="money"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Fecha de recibo de comisión"
              id="txtCommissionReceiptDate"
              pattern="all"
              errorMsg="Ingrese una fecha de recibo de comisión válida"
              requiredMsg="La fecha de recibo de comisión es obligatorio"
              :required="true"
              :modelo.sync="commission_receipt_date"
              :msgServer.sync="errors.commission_receipt_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              label="Nueva fecha concertada de pago"
              id="txtNewAgreedPaymentDate"
              pattern="all"
              errorMsg="Ingrese una fecha válida"
              requiredMsg="La fecha concertada de pago es obligatorio"
              :required="true"
              :modelo.sync="new_agreed_payment_date"
              :msgServer.sync="errors.new_agreed_payment_date"
              type="date"
              :datepicker="{
                                                   'clear-button': false,
                                                  'bootstrap-styling':true,

                                                }"
            ></input-form>
          </div>
        </div>
        <div v-show="showEditInvoice" class="row">
          <div class="col-12" v-if="urlsArchiveInvoice">
            <div class="form-group">
              <label>Archivos:</label>
            </div>
          </div>
          <div class="col-12 col-lg-4 col-md-4" v-if="urlsArchiveInvoice.length > 0"
               v-for="archives in urlsArchiveInvoice" :key="archives.uuid">
            <div class="card card-employee-task shadow-none bg-transparent border-secondary">
              <div class="card-header">
                <vs-tooltip class="offset-11" :text="`Eliminar ${archives.nameArchive}`"><span @click="removedArchiveInvoice(archives)" style="cursor: pointer" class="material-icons">delete_outline</span></vs-tooltip>
              </div>
              <div class="card-body" @click="openArchiveInvoice(archives.archive)" style="cursor: pointer">
                <div class="d-flex align-items-center justify-content-center w-100">
                  <img v-if="archives.type === 'csv'
                                                            || archives.type === 'pdf'
                                                            || archives.type === 'docx'
                                                            || archives.type === 'pptx'
                                                            || archives.type === 'xlsx'
                                                            || archives.type === 'jpg'
                                                            || archives.type === 'png'"
                       :src="'/images/archives-icons/'+archives.type+'.png'" alt="file-icon" height="35"/>
                  <img v-else src="/images/archives-icons/archive.png" alt="" height="35">
                </div>
                <h6 class="card-title text-center pt-1" v-text="archives.nameArchive"></h6>
                <p class="card-text text-center">
                  <small class="text-muted">Vista Previa</small>
                </p>
              </div>
            </div>
          </div>
          <div class="col-12">
            <input-form
              type="textarea"
              label="Comentarios u observaciones"
              id="txtCommentsInvoiceEdit"
              pattern="all"
              errorMsg="Ingrese texto válido"
              requiredMsg="Los comentarios u observaciones son requeridos"
              :required="true"
              :modelo.sync="comments"
              :msgServer.sync="errors.comments"
              :options="{
                                                rows: 5
                                                }"
            >
            </input-form>
          </div>
        </div>
        <div class="row"  v-show="showEditInvoice">
          <div class="col-12">
            <label class="form-control-label" id="add-archive-dropzone-team">Agregar Archivos</label>
            <vue2Dropzone class="dropzone upload-logo dropzone-area dz-clickable"
                          ref="myVueDropzone"
                          @vdropzone-sending="sendingEvent"
                          @vdropzone-canceled="cancelUpload"
                          @vdropzone-max-files-exceeded="maxFiles"
                          @vdropzone-success="addArchiveTeam"
                          @vdropzone-removed-file="removedArchiveDropzoneTeam"
                          id="dpz-archives-customer-company"
                          :options="dropzoneOptionsTeamArchive">

            </vue2Dropzone>
          </div>
        </div>
      </form>
      <!--=====================================
        BOTONES PARA EDITAR
      ======================================-->
      <div class="row pl-1">
        <div class="demo-inline-spacing">
          <!-- Boton para agregar archivos -->
          <button v-if="showDetailInvoice === true && $gate.allow('createInvoice', 'invoice')" @click="btnEditInvoice"
                  type="button"
                  class="btn btn-primary waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Editar
          </button>
          <button v-if="showEditInvoice === true" @click="btnCancelEditInvoice"
                  type="button"
                  class="btn btn-gris waves-effect waves-float waves-light"
                  style="font-size: 0.92rem">
            Cancelar
          </button>
        </div>
      </div>

    </div>
    <div class="modal-footer">
      <button @click="closeModalEditInvoice()" v-if="showDetailInvoice === true" type="button" data-dismiss="modal"
              class="btn btn-gris">Cerrar
      </button>
      <button v-if="showEditInvoice === true" @click="btnCancelEditInvoice" class="btn btn-gris">Cancelar</button>
      <button v-if="showEditInvoice === true" @click="createNewInvoice()" type="button" class="btn btn-primary">
        Actualizar Factura
      </button>
    </div>
  </div>
</template>
<script src="/node_modules/feather-icons/dist/feather.js"></script>
<script>
import Multiselect from "vue-multiselect";
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'
import Swal from "sweetalert2";
import vue2Dropzone from 'vue2-dropzone'

export default {
  name: "EditInvoice",
  components: {
    Multiselect,
    Datepicker,
    vue2Dropzone,
    moment
  },
  data() {
    return {
      showEditInvoice: false,
      showDetailInvoice: true,
      moment: moment,
      en: en,
      es: es,
      urlsArchiveInvoice: null,
      urlsArchiveInvoiceUpdate: [],
      invoice_number: '',
      electronic_invoice_number: '',
      comments: '',
      valuePaymentParcial: null,
      valuePaymentDetail: null,
      state: null,
      paymentType: {id: null},
      stateDetail: {
        name: null,
      },
      paymentTypeDetail: {
        name: null,
        id: null,
      },
      dollarUSLocale: Intl.NumberFormat('es-US'),
      date_issue: null,
      expiration_date: null,
      invoice_type: null,
      invoice_type_detail: {
        name: null
      },
      customer: null,
      customerDetail: {
        name: null
      },
      date_received_client: null,
      date_payment_client: null,
      invoice_date_house_manufacturer: null,
      invoice_number_house_representative: null,
      commission_receipt_date: null,
      new_agreed_payment_date: null,
      value: '0',
      commission_value: '0',
      errors: {},
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 0
      },
      idInvoice: null,
      optionsStateInvoice: [],
      optionsTypeInvoice: [],
      optionsCustomerInvoice: [],
      optionsPaymentType: [],
      dropzoneOptionsTeamArchive: {
        url: '/api/upload-archive-invoice',
        // thumbnailWidth: 200,
        maxFilesize: 5,  //Tamaño en MB
        maxFiles: 1, // Catidad maxima que se puede subir
        paramName: 'archive',
        acceptedFiles: "application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
        addRemoveLinks: true,
        dictDefaultMessage: 'Clic aquí o arrastra tus documentos',
        dictMaxFilesExceeded: 'No es posible agregar más archivos. Limite maximo 2',
        dictFileTooBig: 'El archivo es demasiado grande, su peso es' + " ({{filesize}}MB). " + 'Tamaño máximo del archivo:' + " {{maxFilesize}}MB.",
        dictRemoveFile: 'Remover Archivo',
        dictInvalidFileType: 'No puede cargar archivos de este tipo.',
        headers: {
          'X-CSRF-TOKEN': this.csrf_token //Este token lo pasamos por los props
        },
        // params: {id: this.entity_get_data.id}  //Para enviar parametros
      },
    }
  },
  methods: {
    maxFiles(file) {
      this.$refs.myVueDropzone.removeFile(file);
      this.$toast.error({
        title: 'Atención',
        message: 'No es posible agregar más archivos. Limite maximo 1',
        showDuration: 1000,
        hideDuration: 8000,
        position: 'top right',
      })
    },
    sendingEvent(file, xhr, formData) {

      console.log('upload file', file);
      formData.append('nameInvoice', this.userName);
      formData.append('nameId', file.upload.uuid);
    },

    cancelUpload(file){
      this.$refs.myVueDropzone.removeFile(file);
    },

    addArchiveTeam(file, response) {

      if (this.urlsArchiveInvoice){
        this.$toast.error({
          title: '¡Atención!',
          message: 'Para agregar archivos, debes eliminar el existente',
          showDuration: 1000,
          hideDuration: 5000,
          position: 'top right',
        })
        this.cancelUpload(file)
      }else{
        this.urlsArchiveInvoiceUpdate.push({
          nameArchive: file.name,
          urlArchive: response.data,
          uuid: response.uuid,
          extension: response.extension
        })
        console.log(this.urlsArchiveInvoiceUpdate);
        setTimeout(() => {
          this.$toast.success({
            title: '¡Muy bien!',
            message: 'Archivo subido correctamente',
            showDuration: 1000,
            hideDuration: 5000,
            position: 'top right',
          })
        }, 1000);
      }

    },

    removedArchiveDropzoneTeam(file, error, xhr) {
      console.log('remove file ', file.upload.uuid);
      let uuid = file.upload.uuid

      for (let i = 0; i < this.urlsArchiveInvoiceUpdate.length; i++)
        if (this.urlsArchiveInvoiceUpdate[i].uuid === uuid) {
          const data = new FormData();
          data.append("archiveInvoice", this.urlsArchiveInvoiceUpdate[i].urlArchive);
          axios.post('/api/removed-archive-invoice', data)
            .then(resp => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Se quitó correctamente',
                showDuration: 1000,
                hideDuration: 5000,
                position: 'top right',
              })
              this.urlsArchiveInvoiceUpdate.splice(i, 1);
            }).catch(err => {
            this.$toast.error({
              title: 'Error',
              message: '¡Algo salió mal!',
              showDuration: 1000,
              hideDuration: 5000,
              position: 'top right',
            })
          });
          break;
        }
    },

    removedArchiveInvoice(archive){
      console.log(archive);

          const data = new FormData();
          data.append("archiveInvoiceUuid", archive.uuid);
          data.append("archiveInvoiceUrl", archive.archive);

      Swal.fire({
        title: 'Confirmar',
        text: '¿Estás seguro de eliminar el archivo?. Se eliminará inmediatamente del servidor.',
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
          axios.post('/api/removed-archive-invoice-db', data)
            .then(resp => {

              for (let i = 0; i < this.urlsArchiveInvoice.length; i++) {
                if (this.urlsArchiveInvoice[i].uuid === archive.uuid) {
                  this.urlsArchiveInvoice.splice(i, 1);
                }
              }

              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Se eliminó correctamente',
                showDuration: 1000,
                hideDuration: 5000,
                position: 'top right',
              })
            }).catch(err => {
            this.$toast.error({
              title: 'Error',
              message: '¡Algo salió mal!',
              showDuration: 1000,
              hideDuration: 5000,
              position: 'top right',
            })
          });
        }
      })
    },

    closeModalEditInvoice() {
      this.urlsArchiveInvoice = null
    },
    openArchiveInvoice(archive) {
      // e.preventDefault();
      window.open(archive);
    },
    btnEditInvoice() {
      this.showDetailInvoice = false;
      this.showEditInvoice = true;
    },

    btnCancelEditInvoice() {
      this.urlsArchiveInvoice = null
      this.showEditInvoice = false;
      this.showDetailInvoice = true;

    },

    createNewInvoice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateInvoiceEdit .is-invalid").length > 0) {
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
        data.append('invoice_number', this.invoice_number);
        data.append('state', JSON.stringify(this.state));
        data.append('electronic_invoice_number', this.electronic_invoice_number);
        data.append('date_issue', moment(this.date_issue).format("YYYY-MM-DD HH:mm:ss"));
        data.append('expiration_date', moment(this.expiration_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_type', JSON.stringify(this.invoice_type));
        data.append('customer', JSON.stringify(this.customer));
        data.append('value', this.value);
        data.append('paymentType', JSON.stringify(this.paymentType));
        data.append('valuePaymentParcial', this.valuePaymentParcial);
        data.append('date_received_client', moment(this.date_received_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('date_payment_client', moment(this.date_payment_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_date_house_manufacturer', moment(this.invoice_date_house_manufacturer).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_number_house_representative', this.invoice_number_house_representative);
        data.append('commission_value', this.commission_value);
        data.append('commission_receipt_date', moment(this.commission_receipt_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('new_agreed_payment_date', moment(this.new_agreed_payment_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('comments', this.comments);
        data.append('idInvoice', this.idInvoice);
        data.append('archives', JSON.stringify(this.urlsArchiveInvoiceUpdate));


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
        }).then((result) => {
          if (result.value) {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Actualizando Factura...'
            })
            axios.post('/api/register/update-invoice', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Factura actualizada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/facturas";
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
            }, 2000)
          }
        })
      }, 200);
    },

    traerDatosInvoice(e) {
      this.urlsArchiveInvoice = []
      e.preventDefault();
      window.feather.replace()
      this.idInvoice = +e.target.value;
      console.log('', this.idInvoice)
      this.$vs.loading({
        color: '#3f4f6e',
        text: 'Espere un momento por favor...'
      })

      setTimeout(() => {
        axios.get('/api/data-invoice/' + this.idInvoice).then(resp => {
          console.log('DATOS FACTURA', resp.data.data)
          this.invoice_number = resp.data.data.invoice_number
          this.state = resp.data.data.state
          this.stateDetail = resp.data.data.state
          this.paymentTypeDetail = resp.data.data.payment_type
          this.electronic_invoice_number = resp.data.data.electronic_invoice_number
          this.date_issue = resp.data.data.date_issue
          this.expiration_date = resp.data.data.expiration_date
          this.date_received_client = resp.data.data.date_received_client
          this.date_payment_client = resp.data.data.date_payment_client
          this.invoice_date_house_manufacturer = resp.data.data.invoice_date_house_manufacturer
          this.commission_receipt_date = resp.data.data.commission_receipt_date
          this.new_agreed_payment_date = resp.data.data.new_agreed_payment_date
          this.invoice_type = resp.data.data.type_invoice
          this.paymentType = resp.data.data.payment_type
          this.invoice_type_detail = resp.data.data.type_invoice
          this.value = resp.data.data.value_total
          this.valuePaymentParcial = resp.data.data.value_payment
          this.valuePaymentDetail = resp.data.data.value_payment
          this.invoice_number_house_representative = resp.data.data.invoice_number_house_representative
          this.commission_value = resp.data.data.commission_value
          this.comments = resp.data.data.comments
          if (resp.data.data.archive.length > 0) {
            this.urlsArchiveInvoice = resp.data.data.archive
          }else{
            this.urlsArchiveInvoice = null
          }
          this.customer = resp.data.data.customers
          this.customerDetail = resp.data.data.customers
        })

        setTimeout(() => {
          this.$vs.loading.close()
        }, 1000)
      }, 500)

    },

    getApiStateInvoice() {
      axios.get('/api/get-state-invoice').then(resp => {
        this.optionsStateInvoice = resp.data.data
      });
    },

    getApiTypeInvoice() {
      axios.get('/api/get-type-invoice').then(resp => {
        this.optionsTypeInvoice = resp.data.data
      });
    },

    getApiCustomer() {
      axios.get('/api/all-customers').then(resp => {
        this.optionsCustomerInvoice = resp.data.data
      });
    },

    getApiPaymentType() {
      axios.get('/api/get-payment-type').then(resp => {
        this.optionsPaymentType = resp.data.data
      });
    },

  },
  mounted() {
    this.getApiStateInvoice();
    this.getApiTypeInvoice();
    this.getApiCustomer();
    this.getApiPaymentType();
  }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
.multiselect__tag {
  background: #D9393D !important;
}

.multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
  background: #7D7E7E !important;
}
</style>
