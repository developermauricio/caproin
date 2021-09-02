<template>
  <div class="row">
    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_conveyor"
        type="multiselect"
        :multiselect="{
          selectLabel: 'Seleccionar',
          selectedLabel: 'Seleccionado',
          deselectLabel: 'Desmarcar',
          placeholder: 'Seleccionar cliente',
          taggable: false,
          label: 'name',
          options: conveyors,
          'custom-label': (conveyor) => conveyor.name,
        }"
        :modelo.sync="purchase_order.conveyor"
        :msgServer.sync="errors.conveyor"
        name="conveyor"
        label="Transportadora"
        pattern="all"
        errorMsg="Transportadora no seleccionada"
        requiredMsg="La transportadora es obligatoria"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_dispatch_guide_number"
        name="dispatch_guide_number"
        label="Número de guía de despacho despacho"
        pattern="all"
        errorMsg="Ingrese un número de cotización válido"
        requiredMsg="El número de cotización del fabricante es obligatorio"
        :modelo.sync="purchase_order.dispatch_guide_number"
        :msgServer.sync="errors.dispatch_guide_number"
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
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_actual_dispatch_date"
        name="actual_dispatch_date"
        label="Fecha de despacho de la orden"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha de despacho es obligatoria"
        :modelo.sync="purchase_order.actual_dispatch_date"
        :msgServer.sync="errors.actual_dispatch_date"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        type="date"
        id="txt_actual_delivery_date"
        name="actual_delivery_date"
        label="Fecha de entrega real"
        pattern="all"
        errorMsg="Ingrese una fecha válida"
        requiredMsg="La fecha de recibo es obligatoria"
        :modelo.sync="purchase_order.actual_delivery_date"
        :msgServer.sync="errors.actual_delivery_date"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <input-form
        id="txt_contact_number"
        name="contact_number"
        label="Número de Contacto"
        pattern="all"
        errorMsg="Ingrese un número de contacto válido"
        requiredMsg="El número de contacto es obligatorio"
        :modelo.sync="purchase_order.contact_number"
        :msgServer.sync="errors.contact_number"
        :required="true"
      ></input-form>
    </div>
  </div>
</template>

<script>
export default {
  name: "ConveyorOrder",
  props: {
    errors: {
      type: Object,
      default: function () {
        return {};
      },
    },
    purchase_order: {
      type: Object,
      require: true,
    },
    conveyors: {
      type: Array,
      default: function () {
        return []
      }
    }
  }
};
</script>
