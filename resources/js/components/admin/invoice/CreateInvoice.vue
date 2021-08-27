<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Factura</h5>
      <button @click="clearInputsInvoice()" type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreateInvoice" method="post">
      <div class="modal-body">
        <div class="row">
          <div class="col-12 col-md-4 col-lg-4">
            <input-form
              id="txtInvoiceNumber"
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
              id="txtInvoiceState"
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
              id="txtInvoicePaymentType"
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
              id="txtValueInvoicePaymentParcial"
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
              id="txtInvoiceElectronicNumber"
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
              id="txtInvoiceType"
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
              id="txtInvoiceCustomer"
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
              pattern="num"
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
              id="txtInvoiceNumberHouseRepresentative"
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
              pattern="num"
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
        <div class="row">
          <div class="col-12">
            <input-form
              type="textarea"
              label="Comentarios u observaciones"
              id="txtCommentsInvoice"
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
        <div class="row">
          <div class="col-12">
            <label class="form-control-label" id="add-archive-dropzone-team">Agregar Archivos</label>
            <vue2Dropzone class="dropzone upload-logo dropzone-area dz-clickable"
                          ref="myVueDropzone"
                          @vdropzone-sending="sendingEvent"
                          @vdropzone-max-files-exceeded="maxFiles"
                          @vdropzone-success="addArchiveTeam"
                          @vdropzone-removed-file="removedArchiveDropzoneTeam"
                          id="dpz-archives-customer-company"
                          :options="dropzoneOptionsTeamArchive">

            </vue2Dropzone>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button @click="clearInputsInvoice()" type="button" data-dismiss="modal" class="btn btn-gris">Cancelar</button>
        <button @click="createNewInvoice()" type="button" class="btn btn-primary">Crear Factura</button>
      </div>
    </form>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
import {en, es} from 'vuejs-datepicker/dist/locale'
import Swal from "sweetalert2";
import vue2Dropzone from 'vue2-dropzone'

export default {
  name: "CreateInvoice",
  components: {
    Multiselect,
    Datepicker,
    vue2Dropzone,
    moment
  },
  data() {
    return {
      invoice_number: '',
      electronic_invoice_number: '',
      comments: '',
      state: null,
      date_issue: null,
      expiration_date: null,
      invoice_type: null,
      customer: null,
      valuePaymentParcial: '',
      urlsArchiveInvoice: [],
      paymentType: {id: null},
      date_received_client: null,
      date_payment_client: null,
      invoice_date_house_manufacturer: null,
      invoice_number_house_representative: null,
      commission_receipt_date: null,
      new_agreed_payment_date: null,
      value: '',
      commission_value: '',
      errors: {},
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "$ ",
        suffix: "",
        precision: 0
      },
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
        dictFileTooBig: 'El archivo es demasiado grande, su peso es' + " ({{filesize}}MiB). " + 'Tamaño máximo del archivo:' + " {{maxFilesize}}MiB.",
        dictRemoveFile: 'Remover Archivo',
        dictInvalidFileType: 'No puede cargar archivos de este tipo.',
        headers: {
          'X-CSRF-TOKEN': this.csrf_token //Este token lo pasamos por los props
        },
        // params: {id: this.entity_get_data.id}  //Para enviar parametros
      },
      optionsStateInvoice: [],
      optionsTypeInvoice: [],
      optionsPaymentType: [],
      optionsCustomerInvoice: [],
    }
  },
  methods: {
    sendingEvent(file, xhr, formData) {

      console.log('upload file', file);
      formData.append('nameInvoice', this.userName);
      formData.append('nameId', file.upload.uuid);
    },

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

    addArchiveTeam(file, response) {

      this.urlsArchiveInvoice.push({
        nameArchive: file.name,
        urlArchive: response.data,
        uuid: response.uuid,
        extension: response.extension
      })
      console.log(this.urlsArchiveInvoice);
      setTimeout(() => {
        this.$toast.success({
          title: '¡Muy bien!',
          message: 'Archivo subido correctamente',
          showDuration: 1000,
          hideDuration: 5000,
          position: 'top right',
        })
      }, 1000);
    },

    removedArchiveDropzoneTeam(file, error, xhr) {
      console.log('remove file ', file.upload.uuid);
      let uuid = file.upload.uuid

      for (let i = 0; i < this.urlsArchiveInvoice.length; i++)
        if (this.urlsArchiveInvoice[i].uuid === uuid) {
          const data = new FormData();
          data.append("archiveInvoice", this.urlsArchiveInvoice[i].urlArchive);
          axios.post('/api/removed-archive-invoice', data)
            .then(resp => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Se quitó correctamente',
                showDuration: 1000,
                hideDuration: 5000,
                position: 'top right',
              })
              this.urlsArchiveInvoice.splice(i, 1);
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

    clearInputsInvoice() {
      eventBus.$emit("resetValidaciones");
    },
    createNewInvoice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateInvoice .is-invalid").length > 0) {
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
        data.append('paymentType', JSON.stringify(this.paymentType));
        data.append('customer', JSON.stringify(this.customer));
        data.append('value', this.value);
        data.append('valuePaymentParcial', this.valuePaymentParcial);
        data.append('date_received_client', moment(this.date_received_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('date_payment_client', moment(this.date_payment_client).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_date_house_manufacturer', moment(this.invoice_date_house_manufacturer).format("YYYY-MM-DD HH:mm:ss"));
        data.append('invoice_number_house_representative', this.invoice_number_house_representative);
        data.append('commission_value', this.commission_value);
        data.append('commission_receipt_date', moment(this.commission_receipt_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('new_agreed_payment_date', moment(this.new_agreed_payment_date).format("YYYY-MM-DD HH:mm:ss"));
        data.append('comments', this.comments);
        data.append('archives', JSON.stringify(this.urlsArchiveInvoice));

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
              text: 'Registrando Factura...'
            })
            axios.post('/api/register/store-invoice', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Factura creada correctamente',
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

    getApiPaymentType() {
      axios.get('/api/get-payment-type').then(resp => {
        this.optionsPaymentType = resp.data.data
      });
    },

    getApiCustomer() {
      axios.get('/api/all-customers').then(resp => {
        this.optionsCustomerInvoice = resp.data.data
      });
    }
  }
  ,
  mounted() {
    this.getApiStateInvoice();
    this.getApiTypeInvoice();
    this.getApiCustomer();
    this.getApiPaymentType();
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
