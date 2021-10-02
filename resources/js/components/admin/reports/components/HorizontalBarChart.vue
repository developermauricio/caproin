<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "HorizontalBarChart",
  props: {
    labels: {
      type: Array,
      require: true,
    },
    dataset: {
      type: Array,
      require: true,
    },
  },
  methods: {
    created(chart) {
      chartItem = chart;
    },
  },
  computed: {
    config() {
      return {
        type: "bar",
        data: {
          labels: this.labels,
          datasets: this.dataset,
        },
        options: {
          indexAxis: "y",
          plugins: {
            legend: {
              display: false,
            },
          },
          maintainAspectRatio: false,
          scales: {
            x: {
              stacked: true,
              grid: {
                display: false,
              },
            },
            y: {
              stacked: true,
              max: 100,
              min: 0,
              ticks: {
                stepSize: 50,
              },
              grid: {
                display: false,
              },
            },
          },
        },
      };
    },
  },
};
</script>

<style>
</style>
