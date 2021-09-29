<template>
  <canvas ref="graphic" width="400" height="400"></canvas>
</template>

<script>
let chart = null;
export default {
  name: "CharJS",
  props: {
    config: {
      type: Object,
      default: () => {},
    },
  },
  mounted() {
    chart = new this.$chart(this.$refs.graphic, this.config);
    this.$emit("init", chart);
  },
  watch: {
    config: function () {
      console.log("update", this.config);
      chart.update();
    },
  },
};
</script>

<style>
</style>
