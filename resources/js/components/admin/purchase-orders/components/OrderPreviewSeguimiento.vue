<template>
  <tracing-purchase-order
   :state_histories="purchase_order.purchase_order_state_histories"
  ></tracing-purchase-order>
</template>

<script>
import TracingPurchaseOrder from './TracingPurchaseOrder.vue';
export default {
  components: { TracingPurchaseOrder },
  name: "OrderPreviewSeguimiento",
  props: {
    purchase_order: {
      type: Object,
      require: true,
    },
  },
};
</script>
