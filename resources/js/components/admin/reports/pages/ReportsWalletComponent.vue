<template>
  <div class="reports">
    <div class="content-header row">
      <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row">
          <div class="col-12">
            <h2 class="content-header-title float-left mb-0">
              Reportes cartera
            </h2>
            <div class="filters__container">
              <div class="mb-3 row">
                <label for="trm" class="col-sm-2 pr-0 col-form-label">
                  TRM USD:
                </label>
                <div class="col-sm-3">
                  <input
                    type="text"
                    class="form-control"
                    id="trm"
                    placeholder="4100"
                  />
                </div>
              </div>
            </div>
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
          :description="description"
          :percentage="50"
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
          <p class="card__price">$90.6M</p>
        </card-info>
        <card-info class="card__item">
          <h2 class="card__title">Monto facturado en COP</h2>
          <p class="card__price">$10.4K</p>
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
        <vertical-bar-chart style="max-height: 20rem"></vertical-bar-chart>
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
            style="max-height: 20rem"
          ></total-deuda-by-cliente>
        </div>
        <div class="col pt-3">
          <h2 class="title">Ranking deudores</h2>
          <ranking-deudores></ranking-deudores>
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
  },
  data() {
    return {
      description: {
        text: `$12.000.000`,
        fontSize: 50,
      },
    };
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
</style>
