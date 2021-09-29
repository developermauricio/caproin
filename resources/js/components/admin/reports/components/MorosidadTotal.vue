<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
export default {
  name: "MorosidadTotal",
  props: {
    labels: {
      require: true,
    },
    dataset: {
      require: true,
    },
  },
  methods: {
    created(chart) {
      this.chartItem = chart;
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
          plugins: {
            legend: {
              display: false,
            },
          },
          maintainAspectRatio: false,
          scales: {
            x: {
              grid: {
                display: false,
              },
            },
            y: {
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
  created() {
    this.chartItem = null;
  },
};
</script>

<style>
</style>
