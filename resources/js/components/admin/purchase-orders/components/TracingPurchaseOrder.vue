<template>
  <div class="col-12 m-auto">
    <div class="card card-user-timeline">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between w-100">
          <h4 class="card-title">Seguimiento</h4>
          <button
            class="btn btn-primary"
            v-if="!showForm && editable && $gate.allow('addSeguimiento', 'purchaseOrder')"
            @click="showForm = true"
          >
            Añadir Nuevo Seguimiento
          </button>
        </div>
      </div>
      <p v-if="seguimientos.length < 1" class="text-center">
        Sin Seguimientos
      </p>
      <div class="card-body">
        <form
          class="row pb-2"
          v-if="showForm"
          @submit.prevent="addNewSeguimiento"
        >
          <div class="col-12 col-md-6 col-lg-6">
            <input-form
              id="txt_status"
              type="multiselect"
              :multiselect="{
                selectLabel: 'Seleccionar',
                selectedLabel: 'Seleccionado',
                deselectLabel: 'Desmarcar',
                placeholder: 'Estado',
                taggable: false,
                label: 'name',
                options: state_histories,
                'custom-label': (history) => history.state_order.name,
              }"
              :modelo.sync="newSeguimiento.commentable"
              :msgServer.sync="errors.state"
              name="estado"
              label="Estado"
              pattern="all"
              errorMsg="Estado no seleccionado"
              requiredMsg="El estado es obligatorio"
              :required="true"
            ></input-form>
          </div>

          <div class="col-12 col-md-6">
            <input-form
              name="title"
              id="txt_title"
              label="Titulo"
              pattern="all"
              errorMsg="Ingrese un titulo válido"
              requiredMsg="El titulo es obligatorio"
              :modelo.sync="newSeguimiento.title"
              :msgServer.sync="errors.title"
              :required="true"
            ></input-form>
          </div>

          <div class="col-12">
            <input-form
              name="body"
              id="txt_body"
              label="Descripción"
              type="textarea"
              pattern="all"
              errorMsg="Ingrese una descripcion válida"
              requiredMsg="La descripción es obligatoria"
              :modelo.sync="newSeguimiento.body"
              :msgServer.sync="errors.body"
              :required="true"
            ></input-form>
          </div>

          <div class="col-12">
            <button class="btn btn-danger" @click.prevent="reset">
              Cancelar
            </button>
            <button class="btn btn-success">Guardar</button>
          </div>
        </form>
        <ul class="timeline ml-50">
          <li
            class="timeline-item"
            v-for="seguimiento in seguimientos"
            :key="seguimiento.created_at"
          >
            <span class="timeline-point timeline-point-indicator"></span>
            <div class="timeline-event">
              <div
                class="
                  d-flex
                  justify-content-between
                  flex-sm-row flex-column
                  mb-sm-0 mb-1
                "
              >
                <h6>
                  {{ seguimiento.title }}
                  <strong class="timeline-event-time">
                    {{ seguimiento.commentable.state_order.name }}
                  </strong>
                </h6>
                <span class="timeline-event-time mr-1">
                  {{ parseDate(seguimiento.created_at) }}
                </span>
              </div>
              <p>{{ seguimiento.body }}</p>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import moment from 'moment';

export default {
  name: "TracingPurchaseOrder",
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
    editable: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    closeIcon() {
      return feather.icons["x"].toSvg();
    },
  },
  data() {
    return {
      showForm: false,
      newSeguimiento: {
        title: "",
        body: "",
        created_at: new Date().toISOString(),
        commentable: {
          state_order: {},
        },
      },
      seguimientos: [],
    };
  },
  methods: {
    parseDate(date) {
      return moment(date).format('YYYY-MM-DD HH:mm:ss');
    },
    addNewSeguimiento() {
      axios
        .post("/api/save-purchase-order-seguimiento", this.newSeguimiento)
        .then((response) => {
          this.seguimientos.unshift(Object.assign(this.newSeguimiento, response.data));
          this.reset();
        })
        .catch((err) => {
          console.log(err);
          this.seguimientos.pop();
        });
    },
    reset() {
      this.showForm = false;
      this.newSeguimiento = Object.assign(
        {},
        {
          title: "",
          body: "",
          created_at: new Date().toISOString(),
          commentable: {
            state_order: {},
          },
        }
      );
    },
    getData() {
      axios
        .post("/api/all-purchase-order-seguimiento", {
          ids: this.state_histories.map((history) => history.id),
        })
        .then((response) => {
          this.seguimientos = response.data;
        });
    },
  },
  created() {
    this.getData();
  },
};
</script>
