<template>
  <div class="reports">
    <!-- primero -->
    <div class="row pt-3">
      <div class="col-6 m-0 row d-flex justify-content-around">
        <card-info class="card__item">
          <p class="card__price">906000</p>
          <h2 class="card__title">Ordenes totales</h2>
        </card-info>
        <card-info class="card__item">
          <p class="card__price">800</p>
          <h2 class="card__title">Ordenes en procesamiento</h2>
        </card-info>
      </div>
      <div class="col-6 m-0 row d-flex justify-content-around">
        <card-info class="card__item">
          <p class="card__price">860</p>
          <h2 class="card__title">Ordenes despachadas</h2>
        </card-info>
        <card-info class="card__item">
          <p class="card__price">820</p>
          <h2 class="card__title">Ordenes entregadas</h2>
        </card-info>
      </div>
    </div>

    <!-- segundo -->
    <div class="row pt-3">
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos hechos por (periodo)</h2>
        <vertical-bar-chart style="max-height: 20rem"></vertical-bar-chart>
      </div>
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos entregados por (periodo)</h2>
        <vertical-bar-chart style="max-height: 20rem"></vertical-bar-chart>
      </div>
      <div class="col-4 text-center">
        <h2 class="title">Total pedidos retrasados por periodo</h2>
        <vertical-bar-chart style="max-height: 20rem"></vertical-bar-chart>
      </div>
    </div>

    <!-- tercero -->
    <div class="row pt-3">
      <div class="col text-center">
        <h2 class="title">Clientes Vs Estados de pedido</h2>
        <horizontal-bar-chart style="max-height: 20rem"></horizontal-bar-chart>
      </div>
    </div>

    <!-- cuarto -->
    <div class="row pt-3">
      <div class="col-6 text-center">
        <h2 class="title">Pedidos por estatus</h2>
        <doughnut-chart style="max-height: 20rem"></doughnut-chart>
      </div>
      <div class="col-6 text-center">
        <h2 class="title">Promedio de entrega transportadoras</h2>
        <vertical-bar-chart style="max-height: 20rem"></vertical-bar-chart>
      </div>
    </div>

    <!-- <vertical-bar-chart></vertical-bar-chart>
    <stacked-bar-chart></stacked-bar-chart> -->
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
