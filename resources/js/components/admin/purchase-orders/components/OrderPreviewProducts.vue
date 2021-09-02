<template>
  <div>
    <div
      class="row product--item"
      v-for="order_detail in order_details"
      :key="order_detail.id"
    >
      <h2 class="col-12">{{ order_detail.product.name }}</h2>
      <p class="col col-md-6">
        <strong>Código del Producto Interno:</strong>
        {{ order_detail.internal_product_code }}
      </p>
      <p class="col col-md-6">
        <strong>Código del producto cliente:</strong>
        {{ order_detail.client_product_code }}
      </p>
      <p class="col col-md-6">
        <strong>Descripción del producto interna:</strong>
        {{ order_detail.product.description }}
      </p>
      <p class="col col-md-6">
        <strong>Descripción del producto del cliente:</strong>
        {{ order_detail.customer_product_description }}
      </p>
      <p class="col col-md-6">
        <strong>Aplicación:</strong>
        {{ order_detail.application }}
      </p>
      <p class="col col-md-6">
        <strong>Numero de plano:</strong>
        {{ order_detail.blueprint_number }}
      </p>
      <p class="col col-md-6">
        <strong>Tipo de moneda:</strong>
        {{ order_detail.currency.code }}
      </p>
      <p class="col col-md-6">
        <strong>Valor:</strong>
        {{ order_detail.value }}
      </p>
      <p class="col col-md-6">
        <strong>Numero cotización interna:</strong>
        {{ order_detail.internal_quote_number }}
      </p>
      <p class="col col-md-6">
        <strong>Numero cotización Casa:</strong>
        {{ order_detail.house_quote_number }}
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: "OrderPreviewProducts",
  props: {
    order_details: {
      type: Array,
      require: true,
    },
  },
};
</script>

<style scoped>
.product--item:not(:last-child){
  border-bottom: 1px solid;
  margin-bottom: 1rem;
}
</style>
