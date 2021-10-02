<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "VerticalBarChart",
  props: {
    labels: {
      type: Array,
      require: true,
    },
    dataset: {
      type: Array,
      require: true,
    },
    max: {
      type: Number,
      default: null
    }
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
              max: this.max,
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
