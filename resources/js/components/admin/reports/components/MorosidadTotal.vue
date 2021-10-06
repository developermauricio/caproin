<template>
  <div>
    <canvas ref="graphic" width="400" height="400"></canvas>
  </div>
</template>

<script>
export default {
  name: "MorosidadTotal",
  props: {
    labels: {
      require: true,
    },
    dataset: {
      require: true,
    },
  },
  computed: {
    config() {
      const self = this;
      return {
        type: "bar",
        data: {
          labels: this.labels,
          datasets: this.dataset,
        },
        options: {
          animations: {
            onAnimationComplete(a){
              console.log(self);
              // console.log(a,b,c,d,e,f);
            }
          },
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
  mounted() {
    this.drawChart();
  },
  watch: {
    config: function () {
      this.drawChart();
    },
  },
  created() {
    this.chart = null;
  },
};
</script>

<style>
</style>
