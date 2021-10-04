<template>
  <div class="reports">
    <div class="content-header">
      <div class="row header__container w-12">
        <h2 class="col-12 col-md-3 content-header-title float-left mb-0">
          Reportes cartera
        </h2>
        <div class="col-12 col-md-9 d-flex filters__container">
          <div class="col d-flex justify-content-end">
            <label for="trm" class="px-1 col-form-label">
              Seleccionar fechas:
            </label>
            <input-form
              type="date"
              id="fechaInicio"
              name="fechaInicio"
              pattern="all"
              class="m-0 datepicker"
              errorMsg="Ingrese una fecha válida"
              requiredMsg="La fecha requerida por el cliente es obligatoria"
              :modelo.sync="fechaInicio"
              :showLabel="false"
            ></input-form>
            <label class="px-1 col-form-label"> - </label>
            <input-form
              type="date"
              id="fechaInicio"
              name="fechaInicio"
              pattern="all"
              class="m-0 datepicker datepicker--right"
              errorMsg="Ingrese una fecha válida"
              requiredMsg="La fecha requerida por el cliente es obligatoria"
              :modelo.sync="fechaFin"
              :showLabel="false"
            ></input-form>
          </div>
        </div>
      </div>
    </div>

    <!-- primero -->
    <div class="row pt-3">
      <div class="col-6 m-0 row d-flex justify-content-around">
        <card-info class="card__item">
          <p class="card__price">
            {{ actividadLogisticaEnvios.purchaseOrdersTotal }}
          </p>
          <h2 class="card__title">Ordenes totales</h2>
        </card-info>
        <card-info class="card__item">
          <p class="card__price">
            {{ actividadLogisticaEnvios.purchaseOrdersProcessing }}
          </p>
          <h2 class="card__title">Ordenes en procesamiento</h2>
        </card-info>
      </div>
      <div class="col-6 m-0 row d-flex justify-content-around">
        <card-info class="card__item">
          <p class="card__price">
            {{ actividadLogisticaEnvios.purchaseOrdersDispatched }}
          </p>
          <h2 class="card__title">
            Ordenes <br />
            despachadas
          </h2>
        </card-info>
        <card-info class="card__item">
          <p class="card__price">
            {{ actividadLogisticaEnvios.purchaseOrdersDelivered }}
          </p>
          <h2 class="card__title">
            Ordenes <br />
            entregadas
          </h2>
        </card-info>
      </div>
    </div>

    <!-- segundo -->
    <div class="row pt-3">
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos hechos por (periodo)</h2>
        <vertical-bar-chart
          v-bind="configActividadLogisticaEnviosPeriodo.hechos"
          style="max-height: 20rem"
        ></vertical-bar-chart>
      </div>
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos entregados por (periodo)</h2>
        <vertical-bar-chart
          v-bind="configActividadLogisticaEnviosPeriodo.entregados"
          style="max-height: 20rem"
        ></vertical-bar-chart>
      </div>
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos retrasados por periodo</h2>
        <vertical-bar-chart
          v-bind="configActividadLogisticaEnviosPeriodo.retrasados"
          style="max-height: 20rem"
        ></vertical-bar-chart>
      </div>
    </div>

    <!-- tercero -->
    <div class="row pt-3">
      <div class="col text-center">
        <h2 class="title">Clientes Vs Estados de pedido</h2>
        <horizontal-bar-chart
          v-bind="configClienteEstadosPedido"
          style="max-height: 20rem"
        ></horizontal-bar-chart>
      </div>
    </div>

    <!-- cuarto -->
    <div class="row pt-3">
      <div class="col-6 text-center">
        <h2 class="title">Pedidos por estatus</h2>
        <doughnut-chart
          v-bind="configEstadosPedido"
          style="max-height: 20rem"
        ></doughnut-chart>
      </div>
      <div class="col-6 text-center">
        <h2 class="title">Promedio de entrega transportadoras</h2>
        <vertical-bar-chart
          v-bind="configPromediosEntregaTransportadoras"
          style="max-height: 20rem"
        ></vertical-bar-chart>
      </div>
    </div>

    <!-- cinco -->
    <div class="row pt-3">
      <div class="col text-center">
        <h2 class="title">Visualización de estados de pedido</h2>
        <table class="table--promedio table table-bordered">
          <thead>
            <tr>
              <th>Cliente</th>
              <th>Numero de pedido</th>
              <th>Estado del pedido</th>
              <th>Fecha comprometida de entrega</th>
              <th>Dias de retraso</th>
              <th>Detalles</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in dataVisualizacionEstadosPedido" :key="row.id">
              <td>
                {{ row.customer.business_name }}
              </td>
              <td>
                {{ row.internal_order_number }}
              </td>
              <td>
                {{ getStatus(row) }}
              </td>
              <td>
                {{ row.offer_delivery_date }}
              </td>
              <td>
                {{ row.dias_retraso }}
              </td>
              <td>
                <button
                  data-target="#modal-show-purchase-order"
                  data-toggle="modal"
                  data-placement="top"
                  title="Más Información"
                  type="button"
                  class="btn btn-show-purchase btn-icon btn-primary"
                  @click="showPurchaseOrder(row.id)"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="feather feather-eye"
                  >
                    <path
                      d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                    ></path>
                    <circle cx="12" cy="12" r="3"></circle>
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- seis -->
    <div class="row pt-3">
      <div class="col text-center">
        <h2 class="title">
          Tiempos promedio de entrega (transportadora vs cliente)
        </h2>
        <table class="table--promedio table table-bordered">
          <thead>
            <tr>
              <th>
                <p class="title__table--diagonal px-2">
                  <span class="transportadora">Transportadora</span>
                  <span class="cliente">Cliente</span>
                </p>
              </th>
              <th v-for="header in headerConveyors" :key="header.key">
                {{ header.value }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="row in dataTiemposPromedioEntrega" :key="row.key">
              <td v-for="col in row.value" :key="col.key">
                {{ col.value }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import CardInfo from "../components/CardInfo.vue";
import DoughnutChart from "../components/DoughnutChart.vue";
import HorizontalBarChart from "../components/HorizontalBarChart.vue";
import PercentageDoughnutChart from "../components/PercentageDoughnutChart.vue";
import RankingDeudores from "../components/RankingDeudores.vue";
import StackedBarChart from "../components/StackedBarChart.vue";
import TotalDeudaByCliente from "../components/TotalDeudaByCliente.vue";
import VerticalBarChart from "../components/VerticalBarChart.vue";

const colorStatus = [
  "#f44336",
  "#e91e63",
  "#9c27b0",
  "#673ab7",
  "#3f51b5",
  "#2196f3",
  "#03a9f4",
  "#00bcd4",
  "#009688",
  "#4caf50",
  "#8bc34a",
  "#cddc39",
  "#ffeb3b",
  "#ffc107",
  "#ff9800",
  "#ff5722",
];

export default {
  name: "ReportsLogisticComponent",
  components: {
    VerticalBarChart,
    StackedBarChart,
    DoughnutChart,
    PercentageDoughnutChart,
    CardInfo,
    TotalDeudaByCliente,
    RankingDeudores,
    HorizontalBarChart,
  },
  data() {
    return {
      description: {
        text: `$12.000.000`,
        fontSize: 50,
      },
      fechaInicio: null,
      fechaFin: null,
      actividadLogisticaEnvios: {
        purchaseOrdersProcessing: 0,
        purchaseOrdersDispatched: 0,
        purchaseOrdersDelivered: 0,
        purchaseOrdersTotal: 0,
      },
      actividadLogisticaEnviosPeriodo: {
        purchaseOrdersTotal: [],
        purchaseOrdersDelayed: [],
        purchaseOrdersDelivered: [],
      },
      estadosPedido: {},
      clienteEstadosPedido: {},
      allStatusOrder: {},
      allCustomers: {},
      allConveyors: {},
      promediosEntregaTransportadoras: [],
      tiemposPromedioEntrega: {},
      visualizacionEstadosPedido: [],
    };
  },
  created() {
    this.getAllStatusOrder();
    this.getAllCustomers();
    this.getAllConveyors();
    this.getData();
  },
  methods: {
    showPurchaseOrder(id) {
      console.log(id);
      $("#idPurchaseOrder").val(id).click();
    },
    getDataApi(url) {
      return this.$axios.get(url + this.paramsApi);
    },
    getData() {
      this.getActividadLogisticaEnvios();
      this.getActividadLogisticaEnviosPeriodo();
      this.getEstadosPedido();
      this.getClienteEstadosPedido();
      this.getPromediosEntregaTransportadoras();
      this.getTiemposPromedioEntrega();
      this.getVisualizacionEstadosPedido();
    },
    getEstadosPedido() {
      this.getDataApi("/api/report-logistic/estados-pedido").then(
        (response) => {
          this.estadosPedido = response.data;
        }
      );
    },
    getActividadLogisticaEnvios() {
      this.getDataApi("/api/report-logistic/actividad-logistica-envios").then(
        (response) => {
          this.actividadLogisticaEnvios = response.data;
        }
      );
    },
    getActividadLogisticaEnviosPeriodo() {
      this.getDataApi(
        "/api/report-logistic/actividad-logistica-envios-periodo"
      ).then((response) => {
        this.actividadLogisticaEnviosPeriodo = response.data;
      });
    },
    getClienteEstadosPedido() {
      this.getDataApi("/api/report-logistic/clientes-estados-pedido").then(
        (response) => {
          this.clienteEstadosPedido = response.data;
        }
      );
    },
    getPromediosEntregaTransportadoras() {
      this.getDataApi(
        "/api/report-logistic/promedios-entrega-transportadoras"
      ).then((response) => {
        this.promediosEntregaTransportadoras = response.data;
      });
    },
    getTiemposPromedioEntrega() {
      this.getDataApi("/api/report-logistic/tiempos-promedio-entrega").then(
        (response) => {
          this.tiemposPromedioEntrega = response.data;
        }
      );
    },
    getVisualizacionEstadosPedido() {
      this.getDataApi("/api/report-logistic/visualizacion-estados-pedido").then(
        (response) => {
          this.visualizacionEstadosPedido = response.data;
        }
      );
    },
    getAllStatusOrder() {
      this.$axios
        .get("/api/report-logistic/get-all-status-order")
        .then((response) => {
          this.allStatusOrder = response.data;
        });
    },
    getAllCustomers() {
      this.$axios
        .get("/api/report-logistic/get-all-customers")
        .then((response) => {
          this.allCustomers = response.data;
        });
    },
    getAllConveyors() {
      this.$axios
        .get("/api/report-logistic/get-all-conveyors")
        .then((response) => {
          this.allConveyors = response.data;
        });
    },
    getStatus(item) {
      return item.dias_retraso < 1 ? "A tiempo" : "Retrasado";
    },
  },
  computed: {
    paramsApi() {
      let params = [];
      // if (this.trm) {
      //   params.push(`trm=${this.trm}`);
      // }
      if (this.fechaInicio) {
        const dateSplit = this.fechaInicio.toLocaleDateString().split("/");
        params.push(
          `from=${dateSplit[2]}-${"0" + dateSplit[0].slice(-2)}-${
            "0" + dateSplit[1].slice(-2)
          }`
        );
      }
      if (this.fechaFin) {
        const dateSplit = this.fechaFin.toLocaleDateString().split("/");
        params.push(
          `to=${dateSplit[2]}-${"0" + dateSplit[0].slice(-2)}-${
            "0" + dateSplit[1].slice(-2)
          }`
        );
      }
      return "?" + params.join("&");
    },
    configActividadLogisticaEnviosPeriodo() {
      const datos = {
        hechos: {
          labels: [],
          dataset: [
            {
              label: "",
              data: [],
              backgroundColor: "#66c2a5",
              borderWidth: 1,
            },
          ],
        },
        entregados: {
          labels: [],
          dataset: [
            {
              label: "",
              data: [],
              backgroundColor: "#66c2a5",
              borderWidth: 1,
            },
          ],
        },
        retrasados: {
          labels: [],
          dataset: [
            {
              label: "",
              data: [],
              backgroundColor: "#66c2a5",
              borderWidth: 1,
            },
          ],
        },
      };

      let max = 0;
      this.actividadLogisticaEnviosPeriodo.purchaseOrdersTotal.forEach(
        (item) => {
          datos.hechos.labels.push(item.label);
          datos.hechos.dataset[0].data.push(item.total);
          if (max < item.total) {
            max = item.total;
          }
        }
      );
      this.actividadLogisticaEnviosPeriodo.purchaseOrdersDelivered.forEach(
        (item) => {
          datos.entregados.labels.push(item.label);
          datos.entregados.dataset[0].data.push(item.total);
        }
      );
      this.actividadLogisticaEnviosPeriodo.purchaseOrdersDelayed.forEach(
        (item) => {
          datos.retrasados.labels.push(item.label);
          datos.retrasados.dataset[0].data.push(item.total);
        }
      );

      datos.hechos.max = max;
      datos.entregados.max = max;
      datos.retrasados.max = max;
      return datos;
    },
    configEstadosPedido() {
      const keys = Object.keys(this.estadosPedido);
      const labels = [];
      const data = [];

      keys.forEach((key) => {
        if (this.allStatusOrder[key] && this.estadosPedido[key]) {
          labels.push(this.allStatusOrder[key].name);
          data.push(this.estadosPedido[key].total);
        }
      });
      return {
        labels: labels,
        datasets: [
          {
            label: "Estados por pedido",
            data: data,
            backgroundColor: colorStatus,
          },
        ],
      };
    },
    configClienteEstadosPedido() {
      const labels = [];
      const keysCustomer = Object.keys(this.clienteEstadosPedido);

      const dataStates = {};

      const keyStatus = Object.keys(this.allStatusOrder);
      keyStatus.forEach((key) => {
        dataStates[key] = {
          label: this.allStatusOrder[key].name,
          data: [],
          backgroundColor: colorStatus[+key - 1],
          borderWidth: 1,
        };
      });

      keysCustomer.forEach((keyCustomer) => {
        labels.push(this.allCustomers[keyCustomer].business_name);
        const cliente = this.clienteEstadosPedido[keyCustomer];
        keyStatus.forEach((keyState) => {
          const state = cliente[keyState];
          if (state) {
            dataStates[keyState].data.push(state.total);
          } else {
            dataStates[keyState].data.push(0);
          }
          dataStates[keyState];
        });
      });

      const dataset = [];

      keyStatus.forEach((key) => {
        dataset.push(dataStates[key]);
      });

      return {
        labels,
        dataset,
      };
    },
    configPromediosEntregaTransportadoras() {
      const labels = [];
      const data = [];
      this.promediosEntregaTransportadoras.forEach((item) => {
        labels.push(item.conveyor.name);
        data.push(item.promedio);
      });
      return {
        labels: labels,
        dataset: [
          {
            label: ["promedio dias"],
            data: data,
            backgroundColor: "#1f78b4",
            borderWidth: 1,
          },
        ],
        max: null,
      };
    },
    dataVisualizacionEstadosPedido() {
      return this.visualizacionEstadosPedido.filter(item => item.customer.business_name);
    },
    dataTiemposPromedioEntrega() {
      const keyCustomers = Object.keys(this.tiemposPromedioEntrega);

      const body = [];

      keyCustomers.forEach((keyCustomer) => {
        const customer = this.allCustomers[keyCustomer];
        const customerData = this.tiemposPromedioEntrega[keyCustomer];
        if (customer) {
          const row = [
            { key: keyCustomer + "_0", value: customer.business_name },
          ];

          const keysConveyors = Object.keys(this.allConveyors);

          keysConveyors.forEach((keyConveyor) => {
            const conveyor = customerData[keyConveyor];
            if (conveyor) {
              row.push({
                key: keyCustomer + "_" + keyConveyor,
                value: (+conveyor.promedio).toFixed(0) + " Días",
              });
            } else {
              row.push({
                key: keyCustomer + "_" + keyConveyor,
                value: "N/A",
              });
            }
          });

          body.push({ key: keyCustomer, value: row });
        }
      });
      return body;
    },
    headerConveyors() {
      const keysConveyors = Object.keys(this.allConveyors);

      const headers = [];
      keysConveyors.forEach((keyConveyor) => {
        const conveyor = this.allConveyors[keyConveyor];
        if (conveyor) {
          headers.push({
            key: keyConveyor,
            value: conveyor.name,
          });
        }
      });

      return headers;
    },
  },
  watch: {
    paramsApi() {
      this.getData();
    },
  },
};
</script>

<style scoped>
.reports {
  --blue: #1f78b4;
  --blue-light: #a6cee3;
  --gray: #707070;
}

.title {
  font-size: 1.8rem;
  font-weight: bold;
  padding-bottom: 0.3rem;
  color: var(--blue);
}

.card__title {
  font-size: 1.3rem;
  text-align: center;
}

.card__title--total {
  font-size: 2.1rem;
  font-weight: 500;
  padding-bottom: 0.3rem;
}

.card__item {
  width: 45%;
}

.card__price {
  text-align: center;
  font-size: 2.3rem;
  font-weight: 500;
}

.table--promedio.table th {
  padding: 0.5rem;
}

.table--promedio .title__table--diagonal {
  position: relative;
  flex-grow: 1;
  height: 100%;
  margin: 0;
  overflow: hidden;
}
/*
.table--promedio .title__table--diagonal::after {
  content: " ";
  border-bottom: 1px solid red;
  position: absolute;
  width: 100%;
  left: 0;
  transform: rotateY(0deg) rotate(45deg);
  transform: rotate(15deg);
} */

.table--promedio.table .transportadora {
  display: block;
  text-align: right;
}

.table--promedio.table .cliente {
  display: block;
  text-align: left;
}

.datepicker--right >>> .vdp-datepicker__calendar {
  right: 0;
}
</style>
