<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "MorosidadTotal",
  data() {
    return {
      labels: [
        "2021-1",
        "2021-2",
        "2021-3",
        "2021-4",
        "2021-5",
        "2021-6",
        "2021-7",
        "2021-8",
      ],
      dataset: [
        {
          label: "",
          data: [50, 20, 15, 30, 32, 45, 9, 12],
          backgroundColor: "#66c2a5",
          borderWidth: 1,
        },
      ],
    };
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
