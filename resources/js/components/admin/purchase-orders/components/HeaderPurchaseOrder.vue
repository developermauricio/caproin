<template>
  <div class="row">
    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        name="internal_order_number"
        id="txt_internal_order_number"
        label="Número de Pedido Interno"
        pattern="^[a-zA-Z0-9-]{3,20}$"
        errorMsg="Ingrese un número de pedido interno válido"
        requiredMsg="El número pedido interno es obligatorio"
        :modelo.sync="purchase_order.internal_order_number"
        :msgServer.sync="errors.internal_order_number"
        :required="true"
        @keyup="changeInternalNumber"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txtCustomerOrderNumber"
        name="customer_order_number"
        label="Número de Pedido Cliente"
        pattern="^[\w -]{3,}$"
        errorMsg="Ingrese un número de pedido cliente válido"
        requiredMsg="El número pedido cliente es obligatorio"
        :modelo.sync="purchase_order.customer_order_number"
        :msgServer.sync="errors.customer_order_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txtInvoiceNumber"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar cliente',
          taggable: false,
          label: 'name',
          options: customers,
          'custom-label': (customer) => customer.business_name,
        }"
        :modelo.sync="purchase_order.customer"
        :msgServer.sync="errors.customer"
        name="cliente"
        label="Cliente"
        pattern="all"
        errorMsg="Cliente no seleccionado"
        requiredMsg="El cliente es obligatorio"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_invoice"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar factura',
          taggable: false,
          label: 'name',
          options: invoices,
          'custom-label': (invoice) => invoice.invoice_number,
        }"
        :modelo.sync="purchase_order.invoice"
        :msgServer.sync="errors.invoice"
        name="factura"
        label="Factura"
        pattern="all"
        errorMsg="Factura no seleccionada"
        requiredMsg="La factura es obligatoria"
        :required="false"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txtFinalUser"
        name="final_user"
        label="Usuario Final"
        pattern="all"
        errorMsg="Ingrese un usuario final válido"
        requiredMsg="El usuario final es obligatorio"
        :modelo.sync="purchase_order.final_user"
        :msgServer.sync="errors.final_user"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_order_type"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar tipo',
          taggable: false,
          label: 'name',
          'track-by': 'id',
          options: order_types,
          'custom-label': (order_type) => order_type.name,
        }"
        :modelo.sync="purchase_order.order_type"
        :msgServer.sync="errors.order_type"
        name="order_type"
        label="Tipo"
        pattern="all"
        errorMsg="Tipo no seleccionado"
        requiredMsg="El tipo es obligatorio"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_zone"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar zona',
          taggable: false,
          label: 'name',
          options: zones,
          'custom-label': (zone) => zone.name,
        }"
        :modelo.sync="purchase_order.zone"
        :msgServer.sync="errors.zone"
        name="zone"
        label="Zona"
        pattern="all"
        errorMsg="Zona no seleccionada"
        requiredMsg="La zona es obligatoria"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_seller"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar vendedor',
          taggable: false,
          label: 'name',
          options: sellers,
          'track-by': 'id',
          'custom-label': (seller) => seller.user.name,
        }"
        :modelo.sync="purchase_order.seller"
        :msgServer.sync="errors.seller"
        name="seller"
        label="Vendedor"
        pattern="all"
        errorMsg="Vendedor no seleccionado"
        requiredMsg="El vendedor es obligatorio"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_house"
        name="house"
        label="Casa"
        pattern="^.{3,}$"
        errorMsg="Ingrese una casa válida"
        requiredMsg="La casa es obligatoria"
        :modelo.sync="purchase_order.house"
        :msgServer.sync="errors.house"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_order_receipt_date"
        name="order_receipt_date"
        label="Fecha de recibo de la orden"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha de recibo es obligatoria"
        :modelo.sync="purchase_order.order_receipt_date"
        :msgServer.sync="errors.order_receipt_date"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_offer_delivery_date"
        name="offer_delivery_date"
        label="Fecha de entrega ofertada"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha ofertada es obligatoria"
        :modelo.sync="purchase_order.offer_delivery_date"
        :msgServer.sync="errors.offer_delivery_date"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_delivery_date_required_customer"
        name="delivery_date_required_customer"
        label="Fecha de entrega requerida por el cliente"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha requerida por el cliente es obligatoria"
        :modelo.sync="purchase_order.delivery_date_required_customer"
        :msgServer.sync="errors.delivery_date_required_customer"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="textarea"
        id="txt_description"
        name="description"
        label="Aplicación"
        pattern="^.{3,}$"
        errorMsg="Ingrese una aplicación válida"
        requiredMsg="La aplicación es obligatoria"
        :modelo.sync="purchase_order.description"
        :msgServer.sync="errors.description"
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
          placeholder: 'Seleccionar tipo de moneda',
          taggable: false,
          label: 'type_moneda',
          options: type_currencies,
          'custom-label': (currency) => currency.code,
        }"
        :modelo.sync="purchase_order.currency"
        :msgServer.sync="errors.currency"
        name="currency"
        label="Tipo de Moneda"
        pattern="all"
        errorMsg="Tipo de moneda no seleccionado"
        requiredMsg="El tipo de moneda es obligatorio"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_expected_dispatch_date"
        name="expected_dispatch_date"
        label="Fecha esperada de despacho de la orden"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha de despacho esperada es obligatoria"
        :modelo.sync="purchase_order.expected_dispatch_date"
        :msgServer.sync="errors.expected_dispatch_date"
        :required="false"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="money"
        label="TRM"
        id="txt_trm"
        pattern="all"
        errorMsg="Ingrese un trm válido"
        requiredMsg="El trm es obligatorio"
        :required="true"
        :modelo.sync="purchase_order.trm"
        :msgServer.sync="errors.trm"
        :money="{
          decimal: '.',
          thousands: ',',
          prefix: '$ ',
          suffix: 'COP',
          precision: 0,
        }"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="money"
        label="Valor Total"
        id="txt_total_value"
        pattern="all"
        errorMsg="Ingrese un valor válido"
        requiredMsg="El valor total es obligatorio"
        :required="true"
        :modelo.sync="purchase_order.total_value"
        :msgServer.sync="errors.total_value"
        :money="moneyConfig"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_internal_quote_number"
        name="internal_quote_number"
        label="Número de cotización interna"
        pattern="^[a-zA-Z0-9-]{3,20}$"
        errorMsg="Ingrese un número de cotización válido"
        requiredMsg="El número de cotización interno es obligatorio"
        :modelo.sync="purchase_order.internal_quote_number"
        :msgServer.sync="errors.internal_quote_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_manufacturer_house_quotation_number"
        name="manufacturer_house_quotation_number"
        label="Numero de cotización casa fabricante"
        pattern="^[\w -]{3,}$"
        errorMsg="Ingrese un número de cotización válido"
        requiredMsg="El número de cotización del fabricante es obligatorio"
        :modelo.sync="purchase_order.manufacturer_house_quotation_number"
        :msgServer.sync="errors.manufacturer_house_quotation_number"
        :required="false"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_payment"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar forma de pago',
          taggable: false,
          label: 'name',
          options: payments,
          'custom-label': (payment) => payment.name,
        }"
        :modelo.sync="purchase_order.payment"
        :msgServer.sync="errors.payment"
        name="payment"
        label="Forma de Pago"
        pattern="all"
        errorMsg="Forma de pago no seleccionado"
        requiredMsg="La forma de pago es obligatoria"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <div class="form-group txt_contact_number">
        <label for="txt_contact_number" class="form-control-label"
          ><span>Número de Contacto</span>
          <span class="text-danger asterisco">*</span>
        </label>
        <celular-component
          v-model="purchase_order.contact_number"
          :required="true"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HeaderPurchaseOrder",
  props: {
    purchase_order: {
      type: Object,
      require: true,
    },
    customers: {
      type: Array,
      default: function () {
        return [];
      },
    },
    order_types: {
      type: Array,
      default: function () {
        return [];
      },
    },
    zones: {
      type: Array,
      default: function () {
        return [];
      },
    },
    sellers: {
      type: Array,
      default: function () {
        return [];
      },
    },
    type_currencies: {
      type: Array,
      default: function () {
        return [];
      },
    },
    payments: {
      type: Array,
      default: function () {
        return [];
      },
    },
  },
  data() {
    return {
      invoices: [],
      errors: {},
    };
  },
  computed: {
    moneyConfig() {
      const id = this.purchase_order.currency
        ? this.purchase_order.currency.id
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
    },
  },
  created() {
    axios.get("/api/all-invoices-list").then((response) => {
      this.invoices = response.data;
    });
    this.timeoutCheck = null;
  },
  methods: {
    changeInternalNumber() {
      if (!this.purchase_order || !this.purchase_order.internal_order_number) {
        return;
      }
      clearTimeout(this.timeoutCheck);
      this.timeoutCheck = setTimeout(() => {
        const code = this.purchase_order.internal_order_number;
        axios
          .post("/api/check-purchase-internal-order", {
            internal_order_number: code,
          })
          .then((response) => {
            // console.log(response);
            this.errors.internal_order_number = null;
            if (response.data && !response.data.length) {
              this.errors = Object.assign(response.data);
            }
          });
      }, 300);
    },
  },
};
</script>
