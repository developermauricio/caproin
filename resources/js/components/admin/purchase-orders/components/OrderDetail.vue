<template>
  <div class="row">
    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="customer_order_number"
        id="txt_customer_order_number"
        label="Número de pedido del cliente"
        pattern="all"
        errorMsg="Ingrese un número de pedido válido"
        requiredMsg="El número pedido del cliente es obligatorio"
        :modelo.sync="order_detail.customer_order_number"
        :msgServer.sync="errors.customer_order_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="internal_order_number"
        id="txt_internal_order_number"
        label="Número de pedido interno"
        pattern="all"
        errorMsg="Ingrese un número de pedido válido"
        requiredMsg="El número de pedido interno es obligatorio"
        :modelo.sync="order_detail.internal_order_number"
        :msgServer.sync="errors.internal_order_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="manufacturer"
        id="txt_manufacturer"
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
        name="internal_product_code"
        id="txt_internal_product_code"
        label="Código de producto interno"
        errorMsg="Ingrese código de producto interno valido"
        requiredMsg="El código de producto es obligatorio"
        :modelo.sync="order_detail.internal_product_code"
        :msgServer.sync="errors.internal_product_code"
        :required="true"
        pattern="all"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="client_product_code"
        id="txt_client_product_code"
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
        id="txt_type_product"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Tipo de producto',
          taggable: false,
          label: 'name',
          options: type_products,
          'custom-label': (type_product) => type_product.name,
        }"
        :modelo.sync="order_detail.type_product"
        name="type_product"
        label="Tipo de producto"
        pattern="all"
        errorMsg="Tipo de producto no seleccionado"
        requiredMsg="El tipo de producto es obligatorio"
        :required="true"
        @updatedValue="changeTypeProduct"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4" v-if="products[0]">
      <input-form
        id="txt_product"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Producto',
          taggable: false,
          label: 'name',
          options: products,
          'custom-label': (product) => product.name,
        }"
        :modelo.sync="order_detail.product"
        :msgServer.sync="errors.product"
        name="product"
        label="producto"
        pattern="all"
        errorMsg="Producto no seleccionado"
        requiredMsg="El producto es obligatorio"
        :required="true"
      ></input-form>
    </div>
    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_currency"
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
        label="Tipo de moneda"
        pattern="all"
        errorMsg="Tipo de moneda no seleccionada"
        requiredMsg="El tipo de moneda es obligatoria"
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
      products: [],
    }
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
    type_products: {
      type: Array,
      default: function () {
        return [];
      },
    },
  },
  methods: {
    changeTypeProduct(product_type) {
      if (!product_type) {
        return;
      }
      this.products = [];
      this.order_detail.product = null;
      axios.get("/api/all-products-by-type?type=" + product_type.id)
        .then((products) => {
          this.products = products.data;
        });
    },
  },
};
</script>
