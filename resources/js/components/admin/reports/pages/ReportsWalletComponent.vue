<template>
  <div class="reports">
    <div class="content-header">
      <div class="row header__container w-12">
        <h2 class="col-12 col-md-3 content-header-title float-left mb-0">
          Reportes cartera
        </h2>
        <div class="col-12 col-md-9 d-flex filters__container">
          <label for="trm" class="px-0 col-1"> TRM USD: </label>
          <input
            type="text"
            class="col-2 form-control"
            id="trm"
            placeholder="4100"
            v-model="trm"
          />
          <div class="col d-flex">
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
    <div class="row">
      <div class="col-6 text-center">
        <h2 class="title">Facturas vencidas</h2>
        <percentage-doughnut-chart
          class="col-6 m-auto"
          v-bind="configFacturasVencidas"
        ></percentage-doughnut-chart>
      </div>
      <div class="col-6 text-center">
        <h2 class="title">Importe de facturas vencidas</h2>
        <percentage-doughnut-chart
          class="col-6 m-auto"
          :description="description"
          :percentage="50"
        ></percentage-doughnut-chart>
      </div>
    </div>

    <!-- segundo -->
    <div class="row pt-3">
      <div class="col-6 m-0 row d-flex justify-content-around">
        <card-info class="card__item">
          <h2 class="card__title">Monto facturado en USD</h2>
          <p class="card__price">{{ montoFacturadoUSD }}</p>
        </card-info>
        <card-info class="card__item">
          <h2 class="card__title">Monto facturado en COP</h2>
          <p class="card__price">{{ montoFacturadoCOP }}</p>
        </card-info>
      </div>
      <div class="col-6 text-center">
        <card-info class="col-8 m-auto">
          <h2 class="card__title card__title--total">Total por cobrar</h2>
          <p class="card__price">$90.6M</p>
        </card-info>
      </div>
    </div>

    <!-- tercero -->
    <div class="row pt-3">
      <div class="col-6 text-center">
        <h2 class="title">Total cartera vencida</h2>
        <div class="row m-0">
          <div class="col-4 m-auto">
            <percentage-doughnut-chart
              v-bind="configTotalCarteraVencida30"
            ></percentage-doughnut-chart>
            <p>30 Dias</p>
          </div>
          <div class="col-4 m-auto">
            <percentage-doughnut-chart
              v-bind="configTotalCarteraVencida60"
            ></percentage-doughnut-chart>
            <p>60 Dias</p>
          </div>
          <div class="col-4 m-auto">
            <percentage-doughnut-chart
              v-bind="configTotalCarteraVencida90"
            ></percentage-doughnut-chart>
            <p>90 Dias</p>
          </div>
        </div>
      </div>
      <div class="col-6 text-center">
        <h2 class="title">Morosidad total</h2>
        <morosidad-total
          v-bind="configMorosidadTotal"
          style="max-height: 20rem"
        ></morosidad-total>
      </div>
    </div>

    <!-- cuarto -->
    <div class="row pt-3">
      <div class="col-6 text-center">
        <h2 class="title">Visualización Cartera</h2>
        <p>Explicación de deuda y días</p>
        <div class="cartera__container">
          <div
            class="cartera__item row"
            v-for="cartera in visualizacionCartera"
            :key="cartera.id"
          >
            <div class="col">
              <p class="cartera__value">
                {{ cartera.debe ? cartera.debe : cartera.value_total | price }}
              </p>
              <p class="cartera__title">Total a cobrar</p>
            </div>
            <div class="col">
              <p class="cartera__value">{{ cartera.customer.business_name }}</p>
              <p class="cartera__title">CLIENTE</p>
            </div>
            <div class="col">
              <p class="cartera__value">{{ cartera.totalDias }}</p>
              <p class="cartera__title">DIAS ATRASO</p>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 row m-0 text-center">
        <div class="col">
          <h2 class="title">Total deuda por cliente</h2>
          <total-deuda-by-cliente
            v-bind="configFacturasVencidasPorCliente"
            style="max-height: 20rem"
          ></total-deuda-by-cliente>
        </div>
        <div class="col pt-3">
          <h2 class="title">Ranking deudores</h2>
          <ranking-deudores v-bind="configRankingDeudores"></ranking-deudores>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CardInfo from "../components/CardInfo.vue";
import DoughnutChart from "../components/DoughnutChart.vue";
import MorosidadTotal from "../components/MorosidadTotal.vue";
import PercentageDoughnutChart from "../components/PercentageDoughnutChart.vue";
import RankingDeudores from "../components/RankingDeudores.vue";
import StackedBarChart from "../components/StackedBarChart.vue";
import TotalDeudaByCliente from "../components/TotalDeudaByCliente.vue";
import VerticalBarChart from "../components/VerticalBarChart.vue";

export default {
  name: "ReportsWalletComponent",
  components: {
    VerticalBarChart,
    StackedBarChart,
    DoughnutChart,
    PercentageDoughnutChart,
    CardInfo,
    TotalDeudaByCliente,
    RankingDeudores,
    MorosidadTotal,
  },
  data() {
    return {
      trm: 4100,
      description: {
        text: `$12.000.000`,
        fontSize: 50,
      },
      fechaInicio: null,
      fechaFin: null,
      facturas_vencidas: {
        total_vencidas: 0,
        total: 0,
      },
      morosidad_total: {
        total_vencidas: [],
        total: [],
      },
      facturasVencidasPorCliente: [],
      rankingDeudores: {
        total_invoices: {},
        vencidas_invoices: {},
      },
      totalCarteraVencida: {
        total_30: 0,
        total_60: 0,
        total_90: 0,
        expired_total_30: 0,
        expired_total_60: 0,
        expired_total_90: 0,
      },
      visualizacionCartera: [],
    };
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      this.getTotalFacturasVencidas();
      this.getMorosidadTotal();
      this.getFacturasVencidasPorCliente();
      this.getRankingDeudores();
      this.getTotalCarteraVencida();
      this.getVisualizacionCartera();
    },
    getDataApi(url) {
      return this.$axios.get(url + this.paramsApi);
    },
    getVisualizacionCartera() {
      this.getDataApi("/api/report-wallet/visualizacion-cartera").then(
        (response) => {
          this.visualizacionCartera = response.data;
        }
      );
    },
    getTotalFacturasVencidas() {
      this.getDataApi("/api/report-wallet/total-facturas-vencidas").then(
        (response) => {
          this.facturas_vencidas = response.data;
        }
      );
    },
    getMorosidadTotal() {
      this.getDataApi("/api/report-wallet/morosidad-total").then((response) => {
        this.morosidad_total = response.data;
      });
    },
    getFacturasVencidasPorCliente() {
      this.getDataApi("/api/report-wallet/facturas-vencidas-por-cliente").then(
        (response) => {
          this.facturasVencidasPorCliente = response.data;
        }
      );
    },
    getRankingDeudores() {
      this.getDataApi("/api/report-wallet/ranking-deudores").then(
        (response) => {
          this.rankingDeudores = response.data;
        }
      );
    },
    getTotalCarteraVencida() {
      this.getDataApi("/api/report-wallet/total-cartera-vencida").then(
        (response) => {
          this.totalCarteraVencida = response.data;

          Object.keys(this.totalCarteraVencida).forEach((key) => {
            if (!this.totalCarteraVencida[key]) {
              this.totalCarteraVencida[key] = 0;
            }
          });
        }
      );
    },
    getPercentage(valor, total) {
      if (!total) {
        return 0;
      }
      if (!valor) {
        valor = 0;
      }
      return (valor / total) * 100;
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
    montoFacturadoUSD() {
      return this.$shortNumber(this.facturas_vencidas.total / this.trm);
    },
    montoFacturadoCOP() {
      return this.$shortNumber(this.facturas_vencidas.total);
    },
    configTotalCarteraVencida30() {
      return {
        description: {
          text: this.$price(this.totalCarteraVencida.expired_total_30),
          fontSize: 50,
        },
        percentage: Number(
          this.getPercentage(
            this.totalCarteraVencida.expired_total_30,
            this.totalCarteraVencida.total_30
          ).toFixed(1)
        ),
      };
    },
    configTotalCarteraVencida60() {
      return {
        description: {
          text: this.$price(this.totalCarteraVencida.expired_total_60),
          fontSize: 50,
        },
        percentage: Number(
          this.getPercentage(
            this.totalCarteraVencida.expired_total_60,
            this.totalCarteraVencida.total_60
          ).toFixed(1)
        ),
      };
    },
    configTotalCarteraVencida90() {
      return {
        description: {
          text: this.$price(this.totalCarteraVencida.expired_total_90),
          fontSize: 50,
        },
        percentage: Number(
          this.getPercentage(
            this.totalCarteraVencida.expired_total_90,
            this.totalCarteraVencida.total_90
          ).toFixed(1)
        ),
      };
    },
    configFacturasVencidas() {
      return {
        description: {
          text: this.$price(this.facturas_vencidas.total_vencidas),
          fontSize: 50,
        },
        percentage: Number(
          this.getPercentage(
            this.facturas_vencidas.total_vencidas,
            this.facturas_vencidas.total
          ).toFixed(1)
        ),
      };
    },
    configMorosidadTotal() {
      const keys = Object.keys(this.morosidad_total.total);
      const data = [];
      keys.forEach((key) => {
        let totalDeuda = 0;
        if (this.morosidad_total.total_vencidas[key]) {
          totalDeuda = this.morosidad_total.total_vencidas[key].valor_total;
        }

        data.push(
          Number(
            this.getPercentage(
              totalDeuda,
              this.morosidad_total.total[key].valor_total
            ).toFixed(1)
          )
        );
      });

      return {
        labels: keys,
        dataset: [
          {
            label: "",
            data: data,
            backgroundColor: "#66c2a5",
            borderWidth: 1,
          },
        ],
      };
    },
    configFacturasVencidasPorCliente() {
      const labels = [];
      const data = [];
      this.facturasVencidasPorCliente.forEach((factura) => {
        labels.push(factura.label);
        data.push(factura.value);
      });
      return {
        labels: labels,
        dataset: [
          {
            label: "",
            data: data,
            backgroundColor: "#a6cee3",
            borderWidth: 1,
          },
        ],
      };
    },
    configRankingDeudores() {
      const keys = Object.keys(this.rankingDeudores.total_invoices);
      const labels = [];
      const deuda = [];
      const total = [];

      keys.forEach((key) => {
        total.push(this.rankingDeudores.total_invoices[key].valor_total);
        labels.push(this.rankingDeudores.total_invoices[key].label);
        if (this.rankingDeudores.vencidas_invoices[key]) {
          deuda.push(this.rankingDeudores.vencidas_invoices[key].value);
        } else {
          deuda.push(0);
        }
      });

      return {
        labels: labels,
        dataset: [
          {
            label: "Venta",
            data: total,
            borderColor: "#b41f42",
            backgroundColor: "#b41f42",
            type: "line",
            order: 0,
          },
          {
            label: "Deuda total",
            data: deuda,
            backgroundColor: "#a6cee3",
            borderWidth: 1,
          },
        ],
      };
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

.filters__container {
  justify-content: space-between;
  display: flex;
  align-items: center;
}

.datepicker {
  max-width: 13rem;
}

.datepicker--right >>> .vdp-datepicker__calendar {
  right: 0;
}

.header__container {
  border-bottom: 0.2rem solid var(--blue-light);
  padding-bottom: 1rem;
  margin-bottom: 1rem;
}

.cartera__container {
  background-color: white;
  padding: 0.5rem;
  border-radius: 0.4rem;
  overflow: auto;
  max-height: 58rem;
}

.cartera__item {
  margin: 0;
  margin-bottom: 1rem;
  box-shadow: 0 0.2rem 0 #f2f1f0;
  position: relative;
}

.cartera__item::before {
  content: " ";
  background-color: #b4b0ad;
  width: 0.2rem;
  height: 90%;
  position: absolute;
  z-index: 20;
  top: 0;
}

.cartera__title,
.cartera__value {
  color: #676767;
  text-align: left;
  margin: 0;
}

.cartera__value {
  font-weight: 600;
}

.cartera__title {
  font-weight: 400;
}
</style>
