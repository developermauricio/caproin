<template>
  <div class="py-2">
    <a id="link_download" :href="hrefDownload" :download="nameReportDownload" />
    <button class="btn btn-primary" @click="downloadReport">
      Descargar Reporte
    </button>
  </div>
</template>

<script>
export default {
  name: "ImportErrorDataTrade",
  props: ["lines"],
  data() {
    return {
      hrefDownload: null,
      nameReportDownload: "errors-trade",
    };
  },
  methods: {
    downloadReport() {
      axios
        .post(
          "/api/download-excel-sheets",
          { lines: this.lines, name: this.nameReportDownload },
          { responseType: "blob" }
        )
        .then((resp) => {
          const blob = new Blob([resp.data], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          });
          this.hrefDownload = URL.createObjectURL(blob);
          this.downloadLink(this.hrefDownload);
        });
    },
    downloadLink(url) {
      const link = document.getElementById("link_download");
      link.href = url;
      link.click();
    },
  },
};
</script>
