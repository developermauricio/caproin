<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "RankingDeudores",
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
