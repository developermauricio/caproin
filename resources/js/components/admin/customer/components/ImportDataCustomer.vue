<template>
  <div class="modal-content ">
    <div class="modal-header">
      <h5 class="modal-title" id="myModalLabel160">Importar Clientes</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="demo-spacing-0" v-if="errorMessage">
      <div class="alert alert-danger alert-dismissible m-1 fade show" role="alert">
        <div class="alert-body">
          <p><strong>Error al importar.</strong> Verifique que no hay algún correo electrónico o número de identificación duplicado.
            Tambien verifique que los nombres o titulos de las columnas esten tan cual el excel de ejemplo. A continuación puede ver el mensaje que arroja el servidor,
            le puede dar una guia para solucionar el problema:

          </p>
          <p>
            <code>{{ errorMessage }}</code>
          </p>
          <p class="mt-1"><strong>Al generar este error, ningún dato ha sido importado. Intentelo nuevamente despues de encontrar la solución.</strong></p>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
    </div>
    <form @submit="submitDataExcel" enctype="multipart/form-data">
      <div class="modal-body">
        <h6 class="text-center">Selecciona desde tu computadora el archivo Excel tipo
          <strong>xlsx</strong></h6>
        <input @change="previewFiles" type="file" class="form-control text-center" required
               accept=".xls,.xlsx">
        <div class="text-center pt-1">
          <a href="/import-excel-customers/caproin-import-customer.xlsx" target="_blank">Descarga el ejemplo</a>
        </div>
        <div class="collapse-default pt-1">
          <div class="card collapse-icon">
            <div id="headingCollapse1" class="card-header" data-toggle="collapse" role="button" data-target="#collapse1"
                 aria-expanded="false" aria-controls="collapse1">
              <span class="lead collapse-title">Instrucciones</span>
            </div>
            <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="collapse">
              <div class="card-body">
                <p class="card-text text-justify">
                  Para importar clientes debe cargar un archivo Excel en formato <code>xlsx</code>.
                  Tenga en cuenta que el <code>correo electrónico</code> y el <code>número de identificación</code> es
                  único, asi que verifique
                  que en su archivo de excel no existan correos electrónicos o números de identificación iguales.
                </p>
                <p class="card-text text-justify">
                  Para el <code>tipo de idenficación</code> debe ingresar un número como se muestra en el archivo excel de ejemplo. A
                  continuación
                  la tabla con el nombre del tipo de identificación y el número.
                </p>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                    <tr>
                      <th>Número</th>
                      <th>Nombre tipo identificación</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="tipoIdentification in optionsTypeIdentification" :key="tipoIdentification.id">
                      <td class="text-center">
                        <span class="font-weight-bold" v-text="tipoIdentification.id"></span>
                      </td>
                      <td class="text-center">
                        <span class="font-weight-bold" v-text="tipoIdentification.name"></span>
                      </td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">Importar</button>
      </div>
    </form>
  </div>
</template>

<script>
import Swal from "sweetalert2";

export default {
  name: "ImportDataCustomer",
  data() {
    return {
      archive: null,
      errorMessage:'',
      optionsTypeIdentification: [],
    }
  },
  methods: {
    submitDataExcel(e) {
      e.preventDefault();
      const config = {
        headers: {
          'content-type': 'multipart/form-data'
        }
      }
      this.errorMessage = ''
      const data = new FormData();
      data.append('archive', this.archive);
      Swal.fire({
        title: 'Confirmar',
        text: '¿Estás seguro de importar este archivo?',
        confirmButtonColor: "#0082FB",
        cancelButtonColor: "#F05E7D",
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cancelar',
        customClass: "swal-confirmation",
        showCancelButton: true,
        reverseButtons: true,
        allowOutsideClick: false,
      }).then((result) => {
          if (result.value) {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Importando datos, por favor espere...'
            })
            axios.post('/api/import-data-customers', data, config).then(resp => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'La importación se realizo correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
            }).catch(err => {
              console.log(err)
              this.archive = ''
              this.errorMessage = err.response.data.message
              this.$toast.error({
                title: 'Algo salio mal',
                message: 'Comunícate con el administrador',
                showDuration: 1000,
                hideDuration: 8000,
              })
            });
            setTimeout(() => {
              this.$vs.loading.close()
            }, 2000)
          }
      });
    },
    previewFiles(e) {
      this.archive = e.target.files[0];

    },

    getApiTypeIdentification() {
      axios.get('/api/get-identificationtype').then(resp => {
        this.optionsTypeIdentification = resp.data.data
      })
    }
  },
  mounted() {
    this.getApiTypeIdentification()
  }
}
</script>

<style scoped>

</style>
