<template>
  <dir>
    <div
      v-for="(history, index) in state_histories"
      :key="history.id"
      class="row history"
    >
      <i
        class="icon--close"
        @click="removeHistory(index)"
        v-html="closeIcon"
      ></i>
      <div class="col-12 col-md-4 col-lg-4">
        <input-form
          type="date"
          id="txt_estimated_date"
          name="estimated_date"
          label="Fecha estimada"
          pattern="all"
          errorMsg="Ingrese una fecha válida"
          requiredMsg="La fecha estimada es obligatoria"
          :modelo.sync="history.estimated_date"
          :msgServer.sync="errors.estimated_date"
          :required="true"
        ></input-form>
      </div>

      <div class="col-12 col-md-4 col-lg-4">
        <input-form
          id="txt_state_order"
          type="multiselect"
          :multiselect="{
            selectLabel: 'Seleccionar',
            selectedLabel: 'Seleccionado',
            deselectLabel: 'Desmarcar',
            placeholder: 'Seleccionar el estado',
            taggable: false,
            label: 'name',
            options: state_orders,
            'custom-label': (state_order) => state_order.name,
          }"
          :modelo.sync="history.state_order"
          :msgServer.sync="errors.state_order"
          name="state_order"
          label="Estado"
          pattern="all"
          errorMsg="Estado no seleccionado"
          requiredMsg="El estado es obligatorio"
          :required="true"
        ></input-form>
      </div>

      <div class="col-12 col-md-4 col-lg-4">
        <input-form
          type="textarea"
          id="txt_description"
          name="description"
          label="Descripción"
          pattern="all"
          errorMsg="Ingrese una descripción válida"
          requiredMsg="La descripción es obligatoria"
          :modelo.sync="history.description"
          :msgServer.sync="errors.description"
          :required="true"
        ></input-form>
      </div>
    </div>
  </dir>
</template>

<script>
export default {
  name: "StatusPurchaseOrder",
  props: {
    errors: {
      type: Object,
      default: function () {
        return {};
      },
    },
    state_histories: {
      type: Array,
      require: true,
    },
    state_orders: {
      type: Array,
      require: require,
    },
  },
  computed: {
    closeIcon() {
      return feather.toSvg("x");
    },
  },
  methods: {
    removeHistory(index) {
      this.state_histories.splice(index, 1);
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
