<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "DoughnutChart",
  props: {
    labels: {
      type: Array,
      require: true,
    },
    datasets: {
      type: Array,
      require: true,
    },
  },
  methods: {
    created(chart) {
      chartItem = chart;
    },
    addNewItem() {
      this.dataset[0].data.push(3);
      this.dataset[0].backgroundColor.push("rgba(255, 159, 64, 0.2)");
      this.dataset[0].borderColor.push("rgba(255, 159, 64, 1)");
      chartItem.update();
    },
  },
  computed: {
    config() {
      return {
        type: "doughnut",
        data: {
          labels: this.labels,
          datasets: this.datasets,
        },
        options: {
          maintainAspectRatio: false,
          cutout: "80%",
          borderRadius: 0,
          borderWidth: 0,
          responsive: true,
          plugins: {
            legend: {
              position: "right",
            },
            title: {
              display: false,
            },
          },
        },
      };
    },
  },
};
</script>
