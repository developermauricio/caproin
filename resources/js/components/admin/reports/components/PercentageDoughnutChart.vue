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
    percentage: {
      type: Number,
      require: true,
    },
    title: {
      type: String,
      default: null,
    },
    description: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      data: {
        labels: ["porcentaje", "restante"],
        datasets: [
          {
            label: "full",
            data: [],
            backgroundColor: ["#d22730", "#ededed"],
          },
        ],
      },
    };
  },
  methods: {
    created(chart) {
      chartItem = chart;
    },
  },
  computed: {
    plugins() {
      const percentage = {
        id: "custom_canvas_background_color",
        beforeDraw: (chart) => {
          const ctx = chart.canvas.getContext("2d");
          ctx.save();
          ctx.textBaseline = "top";
          ctx.textAlign = "center";
          ctx.fillStyle = "#d22730";
          const fontSize = chart.width / 5;
          ctx.font = `${fontSize}px Arial`;
          const x = chart.width / 2;
          const y = chart.height / 2;
          ctx.fillText(
            `${this.percentage}%`,
            x,
            this.description ? y - fontSize / 1.3 : y - fontSize/2
          );
          if (this.description) {
            const sizeDescription =
              fontSize * (this.description.fontSize / 100);
            ctx.font = `${sizeDescription}px Arial`;
            ctx.fillText(this.description.text, x, y + sizeDescription/2);
          }
          ctx.restore();
        },
      };
      return [percentage];
    },
    getPorcentaje() {
      return [this.percentage, 100 - this.percentage];
    },
    config() {
      this.data.datasets[0].data = this.getPorcentaje;

      const config = {
        type: "doughnut",
        data: this.data,
        options: {
          cutout: "85%",
          borderRadius: 0,
          borderWidth: 0,
          responsive: true,
          plugins: {
            legend: {
              position: "top",
              display: false,
            },
          },
        },
        plugins: this.plugins,
      };

      if (this.title) {
        config.options.plugins.title = {
          display: true,
          position: "top",
          text: this.title,
        };
      }
      return config;
    },
  },
};
</script>
