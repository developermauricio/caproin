<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Crear Orden de Compra</h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="" id="validateCreatePurchaseOrder" method="post">
      <div class="modal-body">
        <h2>Encabezado</h2>
        <header-purchase-order :purchase_order.sync="purchase_order" ></header-purchase-order>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-danger">
          Cancelar
        </button>
        <button
          @click="createPurchaseOrder()"
          type="button"
          class="btn btn-primary"
        >
          Crear Factura
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import HeaderPurchaseOrder from './components/HeaderPurchaseOrder.vue';
export default {
  name: "CreatePurchaseOrder",
  components: { HeaderPurchaseOrder },
  data() {
    return {
      purchase_order: {
        customer_order_number: '',
        internal_order_number: '',
        customer: null,
        final_user: '',
        productType: null,
        zone_id: '',
        zone: null,
        seller_id: '',
        seller: null,
        house: '',
        description: '',
        has_blueprint: false,
        type_coin_id: '',
        type_coin: null,
        total_value: '',
        internal_quote_number: '',
        manufacturer_house_quotation_number: '',
        dispatch_guide_number: '',
        conveyor: null,
        conveyor_id: '',
        order_receipt_date: '',
        offer_delivery_date: '',
        delivery_date_required_customer: '',
        expected_dispatch_date: '',
        actual_dispatch_date: '',
        actual_delivery_date: '',
        way_to_pay: '',
        invoice_id: '',
        invoice_state: '',
        invoice_number: '',
        contact_number: '',
        provider_id: ''
      }
    };
  },
  methods: {
    createPurchaseOrder() {
      alert("create");
    }
  }
};
</script>

<style >
.multiselect__tag {
  background: #0082fb !important;
}

.multiselect__tag-icon:focus,
.multiselect__tag-icon:hover {
  background: #283046 !important;
}

.multiselect__option--highlight {
  background: #0082fb !important;
}
</style>
