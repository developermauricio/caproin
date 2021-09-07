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
        name="client_product_code"
        label="Código de producto del cliente"
        errorMsg="Ingrese código de producto del cliente valido"
        requiredMsg="El código de producto del cliente es obligatorio"
        :modelo.sync="order_detail.client_product_code"
        :msgServer.sync="errors.client_product_code"
        :required="true"
        pattern="all"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="customer_product_description"
        label="Descripción del producto del cliente"
        pattern="all"
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
        pattern="all"
        errorMsg="Ingrese una casa válido"
        requiredMsg="La casa es obligatoria"
        :modelo.sync="order_detail.manufacturer"
        :msgServer.sync="errors.manufacturer"
        :required="true"
      ></input-form>
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
        name="internal_quote_number"
        label="Número Cotización Interna"
        pattern="all"
        errorMsg="Ingrese un número cotización interna válido"
        requiredMsg="El número cotización interna es obligatorio"
        :modelo.sync="order_detail.internal_quote_number"
        :msgServer.sync="errors.internal_quote_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="house_quote_number"
        label="Número Cotización de Casa"
        pattern="all"
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
        pattern="all"
        errorMsg="Ingrese una aplicación valida"
        requiredMsg="La aplicación es obligatoria"
        :modelo.sync="order_detail.application"
        :msgServer.sync="errors.application"
        :required="true"
        type="textarea"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="blueprint_number"
        label="Número de Plano"
        pattern="all"
        errorMsg="Ingrese un número de plano válido"
        requiredMsg="El número de plano es obligatorio"
        :modelo.sync="order_detail.blueprint_number"
        :msgServer.sync="errors.blueprint_number"
        :required="true"
      ></input-form>
    </div>
  </div>
</template>

<script>
export default {
  name: "OrderDetail",
  data() {
    return {
      product_type: null,
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
    moneyConfig() {
      const id =
        this.order_detail && this.order_detail.currency
          ? this.order_detail.currency.id
          : this.currency
          ? this.currency.id
          : 1;
      switch (id) {
        case 1:
          return {
            decimal: ".",
            thousands: ",",
            prefix: "$ ",
            suffix: "USD",
            precision: 0,
          };
        case 2:
          return {
            decimal: ".",
            thousands: ",",
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
    }
  },
  methods: {
    changeTypeProduct(product_type) {
      console.log(product_type);
    },
  },
};
</script>
