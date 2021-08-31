<template>
  <div>
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
          type="multiselect"
          :multiselect="{
            selectLabel: 'Seleccionar',
            selectedLabel: 'Seleccionado',
            deselectLabel: 'Desmarcar',
            placeholder: 'Seleccionar el estado',
            taggable: false,
            label: 'name',
            options: remainingState,
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
    <div class="input-group col-md-6 mb-3 p-0">
      <select
        class="form-control"
        v-model="newStatus"
        name="new_status"
      >
        <option :value="null">Seleccione un nuevo estado</option>
        <option :key="state.id" :value="state" v-for="state in remainingState">
          {{ state.name }}
        </option>
      </select>
      <button
        class="btn btn-outline-secondary"
        type="button"
        @click="addNewStatus"
      >
        Añadir Nuevo Estado
      </button>
    </div>
  </div>
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
  },
  data() {
    return {
      newStatus: null,
      state_orders: [],
    };
  },
  computed: {
    closeIcon() {
      return feather.icons["x"].toSvg();
    },
    remainingState() {
      return this.state_orders.filter(
        (state) =>
          !this.state_histories.find(
            (history) => history.state_order.id == state.id
          )
      );
    },
  },
  methods: {
    getData() {
      axios.get("/api/all-state-ordes").then((response) => {
        this.state_orders = response.data;
        if (this.state_histories.length < 1) {
          this.state_histories.push({
            description: "",
            estimated_date: "",
            state_order: this.state_orders[0],
          });
        }
      });
    },
    removeHistory(index) {
      this.state_histories.splice(index, 1);
      if (this.state_histories.length < 1) {
        this.state_histories.push({
          description: "",
          estimated_date: "",
          state_order: this.state_orders[0],
        });
      }
    },
    addNewStatus() {
      if (!this.newStatus) {
        return;
      }
      this.state_histories.push({
        description: "",
        estimated_date: "",
        state_order: this.newStatus,
      });
      this.newStatus = null;
    },
  },
  created() {
    this.getData();
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
