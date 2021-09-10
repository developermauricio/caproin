<template>
  <div class="row">
    <p class="col col-md-6">
      <strong>Numero de pedido interno:</strong>
      {{ internal_order_number }}
    </p>

    <p class="col col-md-6">
      <strong>Numero de pedido cliente:</strong>
      {{ customer_order_number }}
    </p>

    <p class="col col-md-6">
      <strong>Cliente:</strong>
      {{ customer.business_name }}
    </p>

    <p class="col col-md-6">
      <strong>Usuario final:</strong>
      {{ final_user }}
    </p>

    <p class="col col-md-6">
      <strong>Tipo de orden:</strong>
      {{ order_type }}
    </p>

    <p class="col col-md-6">
      <strong>Zona:</strong>
      {{ zone }}
    </p>

    <p class="col col-md-6">
      <strong>Vendedor:</strong>
      {{ seller }}
    </p>

    <p class="col col-md-6">
      <strong>Casa:</strong>
      {{ house }}
    </p>

    <p class="col col-md-6">
      <strong>Tipo de moneda:</strong>
      {{ currency }}
    </p>

    <p class="col col-md-6">
      <strong>Valor total:</strong>
      {{ total_value }}
    </p>

    <p class="col col-md-6">
      <strong>Numero de cotización interna:</strong>
      {{ internal_quote_number }}
    </p>

    <p class="col col-md-6">
      <strong>Numero cotización Casa Fabricante:</strong>
      {{ manufacturer_house_quotation_number }}
    </p>

    <p class="col col-md-6">
      <strong>Transportadora:</strong>
      {{ conveyor }}
    </p>

    <p class="col col-md-6">
      <strong>Numero de Guía de despacho:</strong>
      {{ dispatch_guide_number }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha de Recibo de la orden:</strong>
      {{ order_receipt_date }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha de entrega ofertada:</strong>
      {{ offer_delivery_date }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha de entrega requerida por el cliente:</strong>
      {{ delivery_date_required_customer }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha esperada de despacho:</strong>
      {{ expected_dispatch_date }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha de despacho Real:</strong>
      {{ actual_dispatch_date }}
    </p>

    <p class="col col-md-6">
      <strong>Fecha entrega real:</strong>
      {{ actual_delivery_date }}
    </p>

    <p class="col col-md-6">
      <strong>Forma de Pago:</strong>
      {{ payment }}
    </p>

    <p class="col col-md-6">
      <strong>Numero de Factura:</strong>
      {{ invoice_number }}
    </p>

    <p class="col col-md-6">
      <strong>Estado de la factura:</strong>
      {{ invoice_state }}
    </p>

    <p class="col col-md-6">
      <strong>Numero de contacto:</strong>
      {{ contact_number }}
    </p>

    <p class="col col-md-12">
      {{ description }}
    </p>
  </div>
</template>

<script>
export default {
  name: "OrderPreviewHeader",
  props: {
    purchase_order: {
      type: Object,
      require: true,
    },
  },
  computed: {
    internal_order_number() {
      return this.purchase_order.internal_order_number;
    },
    customer_order_number() {
      return this.purchase_order.customer_order_number;
    },
    customer() {
      if (!this.purchase_order.customer) {
        return "Sin valor";
      }
      return this.purchase_order.customer.business_name;
    },
    final_user() {
      return this.purchase_order.final_user;
    },
    order_type() {
      if (!this.purchase_order.order_type) {
        return "Sin valor";
      }
      return this.purchase_order.order_type.name;
    },
    zone() {
      if (!this.purchase_order.zone) {
        return "Sin valor";
      }
      return this.purchase_order.zone.name;
    },
    seller() {
      if (!this.purchase_order.seller || !this.purchase_order.seller.user) {
        return "Sin valor";
      }
      return this.purchase_order.seller.user.name;
    },
    house() {
      return this.purchase_order.house;
    },
    currency() {
      if (!this.purchase_order.currency) {
        return "Sin valor";
      }
      return this.purchase_order.currency.code;
    },
    total_value() {
      return this.purchase_order.total_value;
    },
    internal_quote_number() {
      return this.purchase_order.internal_quote_number;
    },
    manufacturer_house_quotation_number() {
      return this.purchase_order.manufacturer_house_quotation_number;
    },
    conveyor() {
      if (!this.purchase_order.conveyor) {
        return "Sin valor";
      }
      return this.purchase_order.conveyor.name;
    },
    dispatch_guide_number() {
      return this.purchase_order.dispatch_guide_number;
    },
    order_receipt_date() {
      return this.purchase_order.order_receipt_date;
    },
    offer_delivery_date() {
      return this.purchase_order.offer_delivery_date;
    },
    delivery_date_required_customer() {
      return this.purchase_order.delivery_date_required_customer;
    },
    expected_dispatch_date() {
      return this.purchase_order.expected_dispatch_date;
    },
    actual_dispatch_date() {
      if (this.purchase_order.actual_dispatch_date) {
        return this.purchase_order.actual_dispatch_date;
      }
      return "Sin asignar";
    },
    actual_delivery_date() {
      if (this.purchase_order.actual_delivery_date) {
        return this.purchase_order.actual_delivery_date;
      }
      return "Sin asignar";
    },
    payment() {
      if (!this.purchase_order.payment) {
        return "Sin valor";
      }
      return this.purchase_order.payment.name;
    },
    invoice_number() {
      if (!this.purchase_order.invoice) {
        return "Sin valor";
      }
      return this.purchase_order.invoice.invoice_number;
    },
    invoice_state() {
      return this.purchase_order.invoice_state;
    },
    contact_number() {
      return this.purchase_order.contact_number;
    },
    description() {
      return this.purchase_order.description;
    },
  },
};
</script>
