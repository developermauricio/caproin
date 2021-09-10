<template>
  <div>
    <input
      type="hidden"
      name="idPurchaseOrder"
      id="idPurchaseOrder"
      ref="idPurchaseOrder"
      @click="updateData"
    />
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myModalLabel160">
          Detalle de la orden de compra
        </h5>
        <button
          @click="reset"
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" v-if="purchase_order.id">
        <order-preview :purchase_order="purchase_order" ></order-preview>
      </div>
      <div class="modal-footer">
        <button
          @click="reset"
          type="button"
          data-dismiss="modal"
          class="btn btn-gris"
        >
          Cancelar
        </button>
        <button @click="edit" type="button" class="btn btn-primary">
          Editar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import OrderPreview from './components/OrderPreview.vue';
export default {
  components: { OrderPreview },
  name: "ShowPurchaseOrder",
  data() {
    return {
      purchase_order: {
        customer_order_number: "",
        internal_order_number: "",
        customer: null,
        final_user: "",
        productType: null,
        zone_id: "",
        zone: null,
        seller_id: "",
        seller: null,
        house: "",
        description: "",
        currency_id: "",
        currency: null,
        total_value: "",
        internal_quote_number: "",
        manufacturer_house_quotation_number: "",
        dispatch_guide_number: "",
        conveyor: null,
        conveyor_id: "",
        order_receipt_date: "",
        offer_delivery_date: "",
        delivery_date_required_customer: "",
        expected_dispatch_date: "",
        actual_dispatch_date: "",
        actual_delivery_date: "",
        payment: null,
        payment_id: "",
        invoice_id: "",
        invoice_state: "",
        invoice_number: "",
        contact_number: "",
        provider_id: "",
        purchase_order_state_histories: [],
        order_details: [],
      },
    };
  },
  methods: {
    updateData() {
      this.$vs.loading({
        color: "#3f4f6e",
        text: "Cargando datos compra...",
      });
      axios
        .get("/api/get-purchase-order/" + this.$refs.idPurchaseOrder.value)
        .then((response) => {
          this.purchase_order = response.data;
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    reset() {
      this.purchase_order.id = null;
    },
    edit() {
      window.location = "/ordenes-compra/actualizar/" + this.purchase_order.id;
    },
  },
};
</script>
