<template>
  <canvas ref="graphic" width="400" height="400"></canvas>
</template>

<script>
const CHART_COLORS = {
  red: "rgb(255, 99, 132)",
  orange: "rgb(255, 159, 64)",
  yellow: "rgb(255, 205, 86)",
  green: "rgb(75, 192, 192)",
  blue: "rgb(54, 162, 235)",
  purple: "rgb(153, 102, 255)",
  grey: "rgb(201, 203, 207)",
};

export default {
  name: "StackedBarChart",
  data() {
    return {
      myChart: null,
    };
  },
  props: {
    config: {
      type: Object,
      default: () => {},
    },
    data: {
      type: Object,
      default: () => {},
    },
    actions: {
      type: Array,
      default: () => [],
    },
  },
  mounted() {
    const DATA_COUNT = 7;
    const NUMBER_CFG = { count: DATA_COUNT, min: -100, max: 100 };

    const labels = [
      "January",
      "February",
      "March",
      "April",
      "May",
      "June",
      "July",
    ];
    const data = {
      labels: labels,
      datasets: [
        {
          label: "Dataset 1",
          data: this.numbers(NUMBER_CFG),
          backgroundColor: CHART_COLORS.red,
        },
        {
          label: "Dataset 2",
          data: this.numbers(NUMBER_CFG),
          backgroundColor: CHART_COLORS.blue,
        },
        {
          label: "Dataset 3",
          data: this.numbers(NUMBER_CFG),
          backgroundColor: CHART_COLORS.green,
        },
      ],
    };

    const config = {
      type: "bar",
      data: data,
      options: {
        plugins: {
          title: {
            display: true,
            text: "Chart.js Bar Chart - Stacked",
          },
        },
        responsive: true,
        scales: {
          x: {
            stacked: true,
          },
          y: {
            stacked: true,
          },
        },
      },
    };
    this.myChart = new this.$chart(this.$refs.graphic, config);
  },
  methods: {
    valueOrDefault(v, d) {
      return v || d;
    },
    numbers(config) {
      var cfg = config || {};
      var min = this.valueOrDefault(cfg.min, 0);
      var max = this.valueOrDefault(cfg.max, 100);
      var from = this.valueOrDefault(cfg.from, []);
      var count = this.valueOrDefault(cfg.count, 8);
      var decimals = this.valueOrDefault(cfg.decimals, 8);
      var continuity = this.valueOrDefault(cfg.continuity, 1);
      var dfactor = Math.pow(10, decimals) || 0;
      var data = [];
      var i, value;

      for (i = 0; i < count; ++i) {
        value = (from[i] || 0) + this.rand(min, max);
        if (this.rand() <= continuity) {
          data.push(Math.round(dfactor * value) / dfactor);
        } else {
          data.push(null);
        }
      }

      return data;
    },
    rand(min, max) {
      min = this.valueOrDefault(min, 0);
      max = this.valueOrDefault(max, 0);
      let _seed = (2 * 9301 + 49297) % 233280;
      return min + (_seed / 233280) * (max - min);
    },
  },
};
</script>

<style>
</style>
