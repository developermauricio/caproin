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
              :percentage="25"
            ></percentage-doughnut-chart>
            <p>30 Dias</p>
          </div>
          <div class="col-4 m-auto">
            <percentage-doughnut-chart
              :percentage="10"
            ></percentage-doughnut-chart>
            <p>60 Dias</p>
          </div>
          <div class="col-4 m-auto">
            <percentage-doughnut-chart
              :percentage="5"
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

    <!-- <vertical-bar-chart></vertical-bar-chart>
    <stacked-bar-chart></stacked-bar-chart> -->
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
    },
    getDataApi(url) {
      return this.$axios.get(url + this.paramsApi);
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
    getPercentage(valor, total) {
      if (total === 0) {
        return 0;
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

      console.log(total, deuda, labels);
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
</style>
