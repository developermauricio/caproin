<template>
  <div class="table-responsive">
    <h3>Estos datos no fueron importados</h3>
    <div class="py-2">
      <a
        id="link_download"
        :href="hrefDownload"
        :download="nameReportDownload"
      />
      <button class="btn btn-primary" @click="downloadReport">
        Descargar Reporte
      </button>
    </div>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>error</th>
          <th>Código</th>
          <th>Nombre</th>
          <th>Tipo de identificacion</th>
          <th>identificación</th>
          <th>Tipo de proveedor</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in lines" :key="item['código'] + index">
          <td>{{ item["error"] }}</td>
          <td>{{ item["código"] }}</td>
          <td>{{ item["nombre"] }}</td>
          <td>{{ item["tipo identificación"] }}</td>
          <td>{{ item["numero de identificación"] }}</td>
          <td>{{ item["tipo proveedor"] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "ImportErrorDataProvider",
  props: ["lines"],
  data() {
    return {
      hrefDownload: null,
      nameReportDownload: "errors-provider",
    };
  },
  methods: {
    downloadReport() {
      axios
        .post(
          "/api/download-excel",
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

<style scoped>
</style>
