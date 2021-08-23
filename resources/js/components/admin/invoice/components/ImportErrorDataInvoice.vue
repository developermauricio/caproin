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
          <th>Número de Factura</th>
          <th>Número Factura electrónica</th>
          <th>Estado</th>
          <th>Fecha de emisión</th>
          <th>Fecha de expiración</th>
          <th>Tipo de Factura</th>
          <th>Cliente</th>
          <th>Tipo de pago</th>
          <th>Valor Pagado</th>
          <th>Valor</th>
          <th>Fecha de recibo por parte del cliente</th>
          <th>Fecha de pago por parte del cliente</th>
          <th>Fecha de factura casa representante</th>
          <th>Número de factura casa representante</th>
          <th>Valor de la comisión</th>
          <th>Fecha de recibo de comisión</th>
          <th>Nueva fecha concertada de pago</th>
          <th>Comentarios u observaciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in lines" :key="item.identificacion + index">
          <td>{{ item["error"] }}</td>
          <td>{{ item["Número de Factura"] }}</td>
          <td>{{ item["Número Factura electrónica"] }}</td>
          <td>{{ item["Estado"] }}</td>
          <td>{{ getDate(item["Fecha de emisión"]) }}</td>
          <td>{{ getDate(item["Fecha de expiración"]) }}</td>
          <td>{{ item["Tipo de Factura"] }}</td>
          <td>{{ item["Cliente"] }}</td>
          <td>{{ item["Tipo de pago"] }}</td>
          <td>{{ item["Valor Pagado"] }}</td>
          <td>{{ item["Valor"] }}</td>
          <td>{{ getDate(item["Fecha de recibo por parte del cliente"]) }}</td>
          <td>{{ getDate(item["Fecha de pago por parte del cliente"]) }}</td>
          <td>{{ getDate(item["Fecha de factura casa representante"]) }}</td>
          <td>{{ item["Número de factura casa representante"] }}</td>
          <td>{{ item["Valor de la comisión"] }}</td>
          <td>{{ getDate(item["Fecha de recibo de comisión"]) }}</td>
          <td>{{ getDate(item["Nueva fecha concertada de pago"]) }}</td>
          <td>{{ item["Comentarios u observaciones"] }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "ImportErrorDataInvoice",
  props: ["lines"],
  data() {
    return {
      nameReportDownload: "errors-customer",
      hrefDownload: null,
    };
  },
  methods: {
    getDate(data) {
      if (data.date) {
        return data.date
      }
      return data;
    },
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
