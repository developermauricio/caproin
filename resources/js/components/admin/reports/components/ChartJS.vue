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
  mounted() {
    const chart = new this.$chart(this.$refs.graphic, this.config);
    this.$emit("init", chart);
  },
  watch: {
    config() {
      console.log("change");
    },
  },
};
</script>

<style>
</style>
