<template>
  <div class="row">
    <div class="col-6">
      <doughnut-chart></doughnut-chart>
    </div>
    <vertical-bar-chart></vertical-bar-chart>
    <stacked-bar-chart></stacked-bar-chart>
  </div>
</template>

<script>
import DoughnutChart from "../components/DoughnutChart.vue";
import StackedBarChart from "../components/StackedBarChart.vue";
import VerticalBarChart from "../components/VerticalBarChart.vue";

export default {
  name: "ReportsComponent",
  components: {
    VerticalBarChart,
    StackedBarChart,
    DoughnutChart,
  },
};
</script>

<style></style>
