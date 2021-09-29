<template>
  <canvas ref="graphic" width="400" height="400"></canvas>
</template>

<script>
export default {
  name: "CharJS",
  props: {
    config: {
      type: Object,
      default: () => {},
    },
  },
  created() {
    this.chart = null;
  },
  mounted() {
    this.drawChart();
  },
  watch: {
    config: function () {
      this.drawChart();
    },
  },
  methods: {
    drawChart() {
      if (this.chart) {
        this.chart.destroy();
      }
      const chart = new this.$chart(this.$refs.graphic, this.config);
      this.$emit("init", chart);
      this.chart = chart;
    },
  },
};
</script>

<style>
</style>
