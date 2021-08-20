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
          <th>nombre o razón social</th>
          <th>email</th>
          <th>teléfono</th>
          <th>identificación</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in lines" :key="item.identificacion + index">
          <td>{{ item["error"] }}</td>
          <td>{{ item["nombre o razon social"] }}</td>
          <td>{{ item["email"] }}</td>
          <td>{{ item["telefono"] }}</td>
          <td>{{ item["identificacion"] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "ImportErrorDataCustomer",
  props: ["lines"],
  data() {
    return {
      nameReportDownload: "errors-customer",
      hrefDownload: null,
    };
  },
  methods: {
    downloadReport() {
      if (this.hrefDownload !== null) {
        this.downloadLink(this.hrefDownload);
        return;
      }
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
