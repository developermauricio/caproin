<template>
  <div>
    <chart-js @init="created" :config="config"></chart-js>
  </div>
</template>

<script>
let chartItem;
export default {
  name: "DoughnutChart",
  data() {
    return {
      data: {
        labels: ["Red", "Orange", "Yellow", "Green", "Blue"],
        datasets: [
          {
            label: "Dataset 1",
            data: [80, 20, 40, 50, 60],
            backgroundColor: ["#1f78b4", "#a6cee3", "#ff0000", "#00ff00", "#0000ff"],
          },
        ],
      },
    };
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
        data: this.data,
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
