<template>
  <form-wizard
    color="#D9393D"
    error-color="#D9393D"
    subtitle
    title
    finishButtonText="Crear Entidad"
    nextButtonText="Siguiente"
    backButtonText="Atrás"
    shape="tab"
    @on-change="cambioPagina"
    @on-complete="onComplete"
    ref="wizard"
    id="wizard__order"
  >
    <tab-content title="Transportadora" :beforeChange="validarTab">
      <conveyor-order
        v-if="currentTab >= 0"
        :purchase_order.sync="purchase_order"
        :conveyors="conveyors"
      />
    </tab-content>

    <tab-content title="Estado del pedido" :beforeChange="validarTab">
      <status-purchase-order
        v-if="currentTab >= 1"
        :state_histories.sync="purchase_order.purchase_order_state_histories"
      />
    </tab-content>

    <tab-content title="Detalle" :beforeChange="validarTab">
      <order-preview v-if="currentTab >= 2" :purchase_order="purchase_order" />
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
          @click.native="props.nextTab()"
          class="wizard-footer-right finish-button"
          :style="props.fillButtonStyle"
        >
          {{ props.isLastStep ? actionLastButton : "Siguiente" }}
        </wizard-button>
      </div>
    </template>
  </form-wizard>
</template>

<script>
import ConveyorOrder from "./components/ConveyorOrder.vue";
import OrderPreview from "./components/OrderPreview.vue";
import StatusPurchaseOrder from "./components/StatusPurchaseOrder.vue";

export default {
  name: "CreatePurchaseOrder",
  components: {
    ConveyorOrder,
    StatusPurchaseOrder,
    OrderPreview,
  },
  props: {
    id: {
      type: Number,
      default: 0,
    },
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
        trm: 0,
        total_delivery: true,
        invoice_number: "",
        contact_number: "",
        provider_id: "",
        purchase_order_state_histories: [],
        order_details: [],
      },
      conveyors: [],
      state_histories: [],
    };
  },
  created() {
    this.getData().catch((err) => {
      console.log(err);
    });
  },
  computed: {
    actionLastButton() {
      return "Actualizar orden de compra";
    },
  },
  methods: {
    async getData() {
      if (this.id > 0) {
        this.purchase_order = (
          await axios.get("/api/get-purchase-order/" + this.id)
        ).data;
      }
      this.conveyors = (await axios.get("/api/all-conveyor-list")).data;
    },
    async validarTab() {
      return await this.$checkForm("#wizard__order");
    },
    cambioPagina(prevIndex, nextIndex) {
      this.currentTab = nextIndex;
    },
    updatePurchaseOrder() {
      this.$vs.loading({
        color: "#3f4f6e",
        text: "Actualizando orden de compra...",
      });
      axios
        .put(
          "/api/update-purchase-order/" + this.purchase_order.id,
          this.purchase_order
        )
        .then(() => {
          this.$toast.success({
            title: "¡Muy bien!",
            message: "Se ha actualizado la orden de compra",
            showDuration: 1000,
            hideDuration: 7000,
            position: "top right",
          });
          window.location = "/ordenes-compra";
        })
        .catch((err) => {
          console.error("mostrando el error", err, err.response.data.msg);
          this.$toast.error({
            title: "Algo salio mal",
            message: "Comunícate con el administrador",
            showDuration: 1000,
            hideDuration: 8000,
          });
        })
        .finally(() => {
          this.$vs.loading.close();
        });
    },
    onComplete() {
      this.updatePurchaseOrder();
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
