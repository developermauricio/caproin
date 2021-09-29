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
        "Cliente 1",
        "Cliente 2",
        "Cliente 3",
        "Cliente 4",
        "Cliente 5",
        "Cliente 6",
        "Cliente 7",
        "Cliente 8",
      ],
      dataset: [
        {
          label: "",
          data: [50, 20, 15, 30, 32, 45, 9, 12],
          backgroundColor: "#a6cee3",
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
              display: false,
            },
          },
        },
      };
    },
  },
};
</script>
