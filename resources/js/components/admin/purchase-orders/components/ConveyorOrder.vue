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
          placeholder: 'Seleccionar transportador',
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
        label="Número de guía de despacho"
        pattern="^.{4,}$"
        errorMsg="Ingrese un número de cotización válido"
        requiredMsg="El número de cotización del fabricante es obligatorio"
        :modelo.sync="purchase_order.dispatch_guide_number"
        :msgServer.sync="errors.dispatch_guide_number"
        :required="true"
      ></input-form>
    </div>

    <div class="col-12 col-md-4 col-lg-4">
      <div class="form-group">
        <label for="total_delivery" class="form-control-label">
          Tipo Entrega:
        </label>
        <switch-component
          class="d-block"
          id="total_delivery"
          v-model="purchase_order.total_delivery"
          :size="80"
          msgTrue="Total"
          msgFalse="Parcial"
        ></switch-component>
      </div>
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
        :required="false"
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
        :required="false"
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
import SwitchComponent from "../../../SwitchComponent.vue";
export default {
  components: {
    SwitchComponent,
  },
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
        return [];
      },
    },
  },
};
</script>
