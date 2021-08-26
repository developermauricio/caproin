<template>
  <div>
    <order-detail
      v-for="(order_detail, key) in order_details"
      :key="key"
      :errors="errors"
      :order_detail="order_detail"
      :currency="currency"
      :type_currencies="type_currencies"
      :type_products="type_products"
    />
  </div>
</template>

<script>
import OrderDetail from "./OrderDetail.vue";
export default {
  components: { OrderDetail },
  name: "OrderDetails",
  props: {
    errors: {
      type: Object,
      default: function () {
        return {};
      },
    },
    order_details: {
      type: Array,
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
};
</script>
