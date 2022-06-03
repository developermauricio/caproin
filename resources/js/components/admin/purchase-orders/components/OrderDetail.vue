<template>
  <div class="row">
    <div class="col-12 col-md-4 col-lg-4">
      <div class="form-group">
        <label for="txt_internal_product_code" class="form-control-label">
          <span>Código producto interno</span>
        </label>
        <input
          :placeholder="order_detail.internal_product_code"
          class="form-control"
          :value="order_detail.internal_product_code"
          readonly="true"
        />
      </div>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <div class="form-group">
        <label for="txt_product_description_interno" class="form-control-label">
          <span>Descripción del producto interno</span>
        </label>
        <textarea
          placeholder="Descripción del producto interno"
          class="form-control"
          :value="order_detail.product.description"
          readonly="true"
        ></textarea>
      </div>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Tipo de moneda',
          taggable: false,
          label: 'name',
          options: type_currencies,
          'custom-label': (currency) => currency.code,
        }"
        :modelo.sync="order_detail.currency"
        :msgServer.sync="errors.currency"
        name="currency"
        label="Tipo de Moneda"
        pattern="all"
        errorMsg="Tipo de moneda no seleccionada"
        requiredMsg="El tipo de moneda es obligatoria"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="money"
        label="Valor"
        pattern="all"
        errorMsg="Ingrese un valor válido"
        requiredMsg="El valor es obligatorio"
        :required="true"
        :modelo.sync="order_detail.value"
        :msgServer.sync="errors.value"
        :money="moneyConfig"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="number"
        label="Cantidad"
        pattern="all"
        errorMsg="Ingrese la cantidad"
        requiredMsg="La cantidad es obligatoria"
        :required="true"
        :modelo.sync="order_detail.quantity"
        :msgServer.sync="errors.quantity"
        :options="{ min: 1 }"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="money"
        label="Valor total"
        pattern="all"
        errorMsg="Ingrese un valor válido"
        requiredMsg="El valor es obligatorio"
        :required="true"
        :modelo.sync="order_detail.total_value"
        :msgServer.sync="errors.total_value"
        :money="moneyConfig"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="client_product_code"
        label="Código de producto del cliente"
        errorMsg="Ingrese código de producto del cliente valido"
        requiredMsg="El código de producto del cliente es obligatorio"
        :modelo.sync="order_detail.client_product_code"
        :msgServer.sync="errors.client_product_code"
        :required="true"
        pattern="^[\w -]{3,}$"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="customer_product_description"
        label="Descripción del producto del cliente"
        pattern="^.{10,}$"
        errorMsg="Ingrese una descripción valida"
        requiredMsg="La descripción es obligatoria"
        :modelo.sync="order_detail.customer_product_description"
        :msgServer.sync="errors.customer_product_description"
        :required="true"
        type="textarea"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="manufacturer"
        label="Casa"
        pattern="^.{3,}$"
        errorMsg="Ingrese una casa válido"
        requiredMsg="La casa es obligatoria"
        :modelo.sync="order_detail.manufacturer"
        :msgServer.sync="errors.manufacturer"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="house_quote_number"
        label="Número Cotización de Casa"
        pattern="^[\w -]{3,}$"
        errorMsg="Ingrese un número cotización de casa válido"
        requiredMsg="El número cotización de casa es obligatorio"
        :modelo.sync="order_detail.house_quote_number"
        :msgServer.sync="errors.house_quote_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="application"
        label="Aplicación"
        pattern="^.{10,}$"
        errorMsg="Ingrese una aplicación valida"
        requiredMsg="La aplicación es obligatoria"
        :modelo.sync="order_detail.application"
        :msgServer.sync="errors.application"
        :required="false"
        type="textarea"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
<!--      <input-form-->
<!--        name="blueprint_number"-->
<!--        label="Número de Plano (si no exite plano, dejar el campo en blanco"-->
<!--        pattern="^[\w -]{3,}$"-->
<!--        errorMsg="Ingrese un número de plano válido"-->
<!--        requiredMsg="El número de plano es obligatorio"-->
<!--        :modelo.sync="order_detail.blueprint_number"-->
<!--        :msgServer.sync="errors.blueprint_number"-->
<!--        :required="false"-->
<!--      ></input-form> -->
      <input-form
        name="blueprint_number"
        label="Número de Plano"
        pattern="all"
        errorMsg="Ingrese un número de plano válido"
        requiredMsg="El número de plano es obligatorio"
        :modelo.sync="order_detail.blueprint_number"
        :msgServer.sync="errors.blueprint_number"
        :required="false"
      ></input-form>
    </div>

    <div class="col-12" v-show="order_detail.blueprint_number">
      <a target="_blank" :href="order_detail.blueprint_file" v-if="order_detail.blueprint_file" class="btn btn-primary mb-1">
        Descargar plano
        <i v-html="iconDownload"></i>
      </a>
      <vue2Dropzone
        class="dropzone upload-logo dropzone-area dz-clickable"
        ref="myVueDropzone"
        :duplicateCheck="true"
        id="order_detail.product.id"
        :options="dropZoneOptions"
        @vdropzone-sending="sendingEvent"
        @vdropzone-max-files-exceeded="maxFiles"
        @vdropzone-success="addArchiveTeam"
        @vdropzone-removed-file="removedArchiveDropzoneTeam"
      ></vue2Dropzone>
    </div>
  </div>
</template>

<script>
import vue2Dropzone from "vue2-dropzone";

export default {
  name: "OrderDetail",
  components: {
    vue2Dropzone,
  },
  data() {
    return {
      product_type: null,
      dropZoneOptions: {
        url: "/api/upload-blueprint-file",
        maxFilesize: 5, //Tamaño en MB
        maxFiles: 1, // Catidad maxima que se puede subir
        paramName: "archive",
        acceptedFiles:
          "application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
        addRemoveLinks: true,
        dictDefaultMessage: "Clic aquí o arrastra tus documentos",
        dictMaxFilesExceeded:
          "No es posible agregar más archivos. Limite maximo 2",
        dictFileTooBig:
          "El archivo es demasiado grande, su peso es" +
          " ({{filesize}}MiB). " +
          "Tamaño máximo del archivo:" +
          " {{maxFilesize}}MiB.",
        dictRemoveFile: "Remover Archivo",
        dictInvalidFileType: "No puede cargar archivos de este tipo.",
        headers: {
          "X-CSRF-TOKEN": this.csrf_token, //Este token lo pasamos por los props
        },
        // params: {id: this.entity_get_data.id}  //Para enviar parametros
      },
    };
  },
  props: {
    errors: {
      type: Object,
      default: function () {
        return {};
      },
    },
    order_detail: {
      type: Object,
      require: true,
    },
    currency: {
      type: Object,
      require: true,
    },
    type_currencies: {
      type: Array,
      default: function () {
        return [];
      },
    },
  },
  computed: {
    iconDownload() {
      if (feather){
        return feather.icons['download-cloud'].toSvg();
      }
      return ''
    },
    currencyId() {
      return this.order_detail && this.order_detail.currency
        ? this.order_detail.currency.id
        : this.currency
        ? this.currency.id
        : 1;
    },
    moneyConfig() {
      switch (this.currencyId) {
        case 1:
          return {
            decimal: ",",
            thousands: ".",
            prefix: "$ ",
            suffix: "USD",
            precision: 0,
          };
        case 2:
          return {
            decimal: ",",
            thousands: ".",
            prefix: "$ ",
            suffix: "COP",
            precision: 0,
          };
        case 3:
          return {
            decimal: ",",
            thousands: ".",
            prefix: "$ ",
            suffix: "",
            precision: 0,
          };
      }
    },
    totalValue: function () {
      return this.order_detail.value * this.order_detail.quantity;
    },
  },
  watch: {
    totalValue: function () {
      this.order_detail.total_value = this.totalValue > 0 ? this.totalValue : 0;
    },
    currencyId: function () {
      this.updatePriceByCurrency();
    },
  },
  mounted() {
    if (this.currency && !this.order_detail.currency) {
      this.order_detail.currency = this.currency;
      this.updatePriceByCurrency();
    }
  },
  methods: {
    updatePriceByCurrency() {
      const productPrice = this.order_detail.product.product_prices.find(
        (productPrice) => {
          return productPrice.currency_id === this.currencyId;
        }
      );
      this.order_detail.value = productPrice.price;
    },
    sendingEvent(file, xhr, formData) {
      formData.append("blueprint_number", this.order_detail.blueprint_number);
      formData.append("id", file.upload.uuid);
    },
    maxFiles(file) {
      this.$refs.myVueDropzone.removeFile(file);
      this.$toast.error({
        title: "Atención",
        message: "No es posible agregar más archivos. Limite maximo 1",
        showDuration: 1000,
        hideDuration: 8000,
        position: "top right",
      });
    },
    addArchiveTeam(file, response) {
      if (this.order_detail.blueprint_file){
        this.removeFile(this.order_detail.blueprint_file).then(res => {

        });
      }

      this.order_detail.blueprint_file = response.data;
      setTimeout(() => {
        this.$toast.success({
          title: "¡Muy bien!",
          message: "Archivo subido correctamente",
          showDuration: 1000,
          hideDuration: 5000,
          position: "top right",
        });
      }, 1000);
    },
    async removedArchiveDropzoneTeam(file, error, xhr) {
      const isRemove = await this.removeFile(this.order_detail.blueprint_file);

      if (isRemove) {
        this.$toast.success({
          title: "¡Muy bien!",
          message: "Archivo subido correctamente",
          showDuration: 1000,
          hideDuration: 5000,
          position: "top right",
        });
        this.order_detail.blueprint_file = "";
      } else {
        this.$toast.error({
          title: "¡Fallo!",
          message: "El archivo no se pudo eliminar",
          showDuration: 1000,
          hideDuration: 5000,
          position: "top right",
        });
      }
    },
    async removeFile(fileUrl) {
      const data = new FormData();
      data.append("archive", fileUrl);
      const final = await axios.post("/api/remove-blueprint-file", data);
      return final.status === 204;
    },
  },
};
</script>
