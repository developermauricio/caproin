<template>
  <form-wizard
    color="#F05E7D"
    subtitle
    title
    finishButtonText="Crear Entidad"
    nextButtonText="Siguiente"
    backButtonText="Atrás"
    shape="tab"
    error-color="#ff4949"
    @on-change="cambioPagina"
    @on-complete="createPurchaseOrder"
    ref="wizard"
    id="wizard__order"
  >
    <tab-content title="Datos iniciales" :beforeChange="validarTab">
      <header-purchase-order
        v-if="currentTab == 0"
        :purchase_order.sync="purchase_order"
        :customers="customers"
        :order_types="orderTypes"
        :zones="zones"
        :sellers="sellers"
        :type_currencies="type_currencies"
        :payments="payments"
      />
    </tab-content>
    <tab-content title="Transportadora" :beforeChange="validarTab">
      <conveyor-order
        v-if="currentTab == 1"
        :purchase_order.sync="purchase_order"
        :conveyors="conveyors"
      />
    </tab-content>

    <tab-content title="Estado del pedido" :beforeChange="validarTab">
      <status-purchase-order
        v-if="currentTab == 2"
        :state_histories.sync="state_histories"
      />
    </tab-content>

    <tab-content title="Detalle" :beforeChange="validarTab">
      <order-details
        v-if="currentTab == 3"
        :currency="purchase_order.currency"
        :order_details.sync="order_details"
        :type_currencies="type_currencies"
        :type_products="type_products"
      />
    </tab-content>

    <template slot="footer" slot-scope="props">
      <div class="wizard-footer-left">
        <wizard-button
          v-if="props.activeTabIndex > 0"
          @click.native="props.prevTab()"
          :style="props.fillButtonStyle"
        >
          Volver
        </wizard-button>
      </div>
      <div class="wizard-footer-right">
        <wizard-button
          v-if="!props.isLastStep"
          @click.native="props.nextTab()"
          class="wizard-footer-right"
          :style="props.fillButtonStyle"
        >
          Siguiente
        </wizard-button>

        <wizard-button
          v-else
          @click.native="createPurchaseOrder"
          class="wizard-footer-right finish-button"
          :style="props.fillButtonStyle"
        >
          {{ props.isLastStep ? "Crear orden de compra" : "Siguiente" }}
        </wizard-button>
      </div>
    </template>
  </form-wizard>
</template>

<script>
import ConveyorOrder from "./components/ConveyorOrder.vue";
import HeaderPurchaseOrder from "./components/HeaderPurchaseOrder.vue";
import OrderDetails from "./components/OrderDetails.vue";
import StatusPurchaseOrder from "./components/StatusPurchaseOrder.vue";

export default {
  name: "CreatePurchaseOrder",
  components: {
    ConveyorOrder,
    HeaderPurchaseOrder,
    OrderDetails,
    StatusPurchaseOrder,
  },
  data() {
    return {
      currentTab: 0,
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
        has_blueprint: false,
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
      },
      customers: [],
      orderTypes: [],
      zones: [],
      sellers: [],
      type_currencies: [],
      payments: [],
      conveyors: [],
      state_histories: [],
      state_orders: [],
      order_details: [
        {
          purchase_order_id: null,
          customer_order_number: null,
          internal_order_number: null,
          manufacturer: null,
          internal_product_code: null,
          client_product_code: null,
          product_id: null,
          customer_product_description: null,
          application: null,
          blueprint_number: null,
          blueprint_file: null,
          currency_id: null,
          value: null,
          internal_quote_number: null,
          house_listing_number: null,
          created_at: null,
          updated_at: null,
          product: null,
          currency: null,
        },
      ],
      type_products: [],
    };
  },
  created() {
    this.getData().catch((err) => {
      console.log(err);
    });
  },
  methods: {
    async getData() {
      this.customers = (await axios.get("/api/all-customer-list")).data;
      this.orderTypes = (await axios.get("/api/all-order-type-list")).data;
      this.zones = (await axios.get("/api/all-zone-list")).data;
      this.sellers = (await axios.get("/api/all-seller-list")).data;
      this.type_currencies = (await axios.get("/api/all-coin-type-list")).data;
      this.conveyors = (await axios.get("/api/all-conveyor-list")).data;
      this.payments = (await axios.get("/api/get-payment-type")).data.data;
      this.type_products = (
        await axios.get("/api/all-product-types-list")
      ).data;
      // this.order_details = (
      //   await axios.get("/api/purchase-order-state-history")
      // ).data;
    },
    validarTab() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        const validated =
          document.querySelectorAll("#wizard__order .is-invalid").length < 1;
        if (validated) {
          console.log(this.currentTab);
          this.$refs.wizard.tabs[this.currentTab].validationError = null;
          this.$refs.wizard.changeTab(this.currentTab, this.currentTab + 1);
        }
      }, 200);
      return false;
    },
    cambioPagina(prevIndex, nextIndex) {
      this.currentTab = nextIndex;
    },
    createPurchaseOrder() {
      alert("create");
    },
  },
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
