<template>
  <div>
    <accordion-component :items="accordion_list">
      <template v-slot:default="{ item }">
        <order-preview-header
          v-if="isHeader(item)"
          :purchase_order="purchase_order"
        ></order-preview-header>
        <order-preview-status
          v-if="isStatus(item)"
          :purchase_order_state_histories="
            purchase_order.purchase_order_state_histories
          "
        ></order-preview-status>
        <order-preview-products
          v-if="isProducts(item)"
          :order_details="purchase_order.order_details"
        ></order-preview-products>
        <order-preview-seguimiento
          v-if="isSeguimiento(item)"
          :purchase_order="purchase_order"
        ></order-preview-seguimiento>
      </template>
    </accordion-component>
  </div>
</template>

<script>
import OrderPreviewHeader from "./OrderPreviewHeader.vue";
import OrderPreviewProducts from "./OrderPreviewProducts.vue";
import OrderPreviewSeguimiento from "./OrderPreviewSeguimiento.vue";
import OrderPreviewStatus from "./OrderPreviewStatus.vue";
export default {
  components: {
    OrderPreviewHeader,
    OrderPreviewStatus,
    OrderPreviewProducts,
    OrderPreviewSeguimiento,
  },
  name: "OrderPreview",
  data() {
    return {
      accordion_list: [
        { id: "header", title: "header" },
        { id: "status", title: "status" },
        { id: "products", title: "products" },
        { id: "seguimiento", title: "seguimiento" },
      ],
    };
  },
  props: {
    purchase_order: {
      type: Object,
      require: true,
    },
  },
  methods: {
    isHeader(item) {
      return item.id == "header";
    },
    isStatus(item) {
      return item.id == "status";
    },
    isProducts(item) {
      return item.id == "products";
    },
    isSeguimiento(item) {
      return item.id == "seguimiento";
    },
  },
};
</script>

<style scope>
.history {
  position: relative;
  padding-top: 1rem;
}

.history:not(:nth-child(1)) {
  border-top: 2px solid #f3f2ee;
}

.icon--close {
  position: absolute;
  top: 0;
  left: 0;
  color: rgb(240, 94, 125);
  z-index: 30;
  cursor: pointer;
}
</style>
