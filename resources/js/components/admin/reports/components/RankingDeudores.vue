<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "RankingDeudores",
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
          label: "Deuda total",
          data: [50, 20, 15, 30, 32, 45, 9, 12],
          backgroundColor: "#a6cee3",
          borderWidth: 1,
        },
        {
          label: "Venta mensual",
          data: [50 * 2, 20 * 2, 15 * 2, 30 * 2, 32 * 2, 45 * 2, 9 * 2, 12 * 2],
          borderColor: "#b41f42",
          backgroundColor: "#b41f42",
          type: "line",
          order: 0,
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
              display: true,
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
              suggestedMin: 50,
              ticks: {
                maxTicksLimit: 6,
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
