<template>
  <div class="table-responsive">
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
          <th>nombres</th>
          <th>apellidos</th>
          <th>correo</th>
          <th>teléfono</th>
          <th>roles</th>
          <th>tipo usuario</th>
          <th>oficina</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in lines" :key="item.identificacion + index">
          <td>{{ item["error"] }}</td>
          <td>{{ item["nombres"] }}</td>
          <td>{{ item["apellidos"] }}</td>
          <td>{{ item["correo"] }}</td>
          <td>{{ item["teléfono"] }}</td>
          <td>{{ item["roles"] }}</td>
          <td>{{ item["tipo usuario"] }}</td>
          <td>{{ item["oficina"] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "ImportErrorDataUser",
  props: ["lines"],
  data() {
    return {
      nameReportDownload: "errors-users",
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
