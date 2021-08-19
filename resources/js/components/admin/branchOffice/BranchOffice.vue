<template>
  <div>
    <div class="row pb-1">
      <div class="col-12 demo-inline-spacing">
        <div class="icon-search-wrapper mx-auto" style="margin-left: 0px !important;">
          <div class="input-group input-group-merge" style="width: 22rem !important;">
            <div class="input-group-prepend">
              <span class="input-group-text"><i data-feather="search"></i></span>
            </div>
            <input type="text" class="form-control" id="icons-search" v-model="searchQuery"
                   placeholder="Buscar por nombre o código"/>
          </div>
        </div>
        <button data-target="#modal-new-branch-office" data-toggle="modal" class="btn btn-primary float-right"><i
          data-feather="plus" class="mr-50"></i>Nueva Sucursal
        </button>
      </div>
    </div>
    <div class="row pt-2">
      <div v-if="resultQuery" v-for="branchsOffices in resultQuery" :key="branchsOffices.id"
           class="col-md-6 col-lg-4 col-12">
        <div class="card card-employee-task">
          <div class="card-header">
            <h4 class="card-title">{{ branchsOffices.name }}</h4>
            <div class="dropdown">
              <i data-feather="more-vertical" class="font-medium-3  dropdown-toggle cursor-pointer"
                 data-toggle="dropdown"></i>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-target="#modal-edit-branch-office" data-toggle="modal"
                   @click="getBranchOfficeFirst(branchsOffices.id)">
                  <i data-feather="edit-2" class="mr-50"></i>
                  <span>Editar</span>
                </a>
<!--                <a class="dropdown-item" href="">-->
<!--                  <i data-feather="trash" class="mr-50"></i>-->
<!--                  <span>Eliminar</span>-->
<!--                </a>-->
              </div>
            </div>
          </div>
          <div class="card-body">
            <p>Código de la Sucursal: <strong>{{ branchsOffices.code }}</strong></p>
          </div>
        </div>
      </div>
    </div>
    <!--=====================================
		    MODAL PARA CREAR UN NUEVA SUCURSAL
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-new-branch-office" data-backdrop="static" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1600">Crear Sucursal</h5>
            <button @click="clearInputBranchOffice()" type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="" id="validateCreateBranchOffices" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <input-form
                    id="txtNameBranchOffices"
                    label="Nombre de la sucursal"
                    pattern="all"
                    errorMsg="Ingrese un nombre válido"
                    requiredMsg="El nombre es obligatorio"
                    :modelo.sync="name"
                    :required="true"
                    :msgServer.sync="errors.name"
                  ></input-form>
                  <input-form
                    id="txtCodeBranchOffices"
                    label="Código"
                    pattern="all"
                    errorMsg="Ingrese un código válido"
                    requiredMsg="El código es obligatorio"
                    :modelo.sync="code"
                    :required="true"
                    :msgServer.sync="errors.code"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-code-branch-office" class="text-danger">El coódigo ya
                    ha sido registrado</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button @click="clearInputBranchOffice()" type="button" data-dismiss="modal" class="btn btn-gris">
                Cancelar
              </button>
              <button @click="createNewBranchOffice()" type="button" class="btn btn-primary">Crear Sucursal</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--=====================================
		    MODAL PARA ELIMINAR SUCURSAL
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-edit-branch-office" data-backdrop="static" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel160">Editar Sucursal</h5>
            <button @click="clearInputBranchOffice()" type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="" id="validateCreateBranchOfficesEdit" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <input-form
                    id="txtNameBranchOfficesEdit"
                    label="Nombre de la sucursal"
                    pattern="all"
                    errorMsg="Ingrese un nombre válido"
                    requiredMsg="El nombre es obligatorio"
                    :modelo.sync="name"
                    :required="true"
                    :msgServer.sync="errors.name"
                  ></input-form>
                  <input-form
                    id="txtCodeBranchOfficesEdit"
                    label="Código"
                    pattern="all"
                    errorMsg="Ingrese un código válido"
                    requiredMsg="El código es obligatorio"
                    :modelo.sync="code"
                    :required="true"
                    :msgServer.sync="errors.code"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-code-branch-office-edit" class="text-danger">El coódigo ya
                    ha sido registrado</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button @click="clearInputBranchOffice()" type="button" data-dismiss="modal" class="btn btn-gris">
                Cancelar
              </button>
              <button @click="udateBranchOffice()" type="button" class="btn btn-primary">Actualizar Sucursal</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script src="/node_modules/feather-icons/dist/feather.js"></script>
<script>
import Swal from 'sweetalert2'

export default {
  name: "BranchOffice",
  data() {
    return {
      dataBranchOffices: [],
      name: '',
      code: '',
      idValidateCode: null,
      codeValidet: '',
      id: null,
      searchQuery: null,
      errors: {},
    }
  },
  methods: {
    udateBranchOffice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateBranchOfficesEdit .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          return;
        }
        const data = new FormData()
        data.append('name', this.name);
        data.append('code', this.code);
        data.append('id', this.id);

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar esta sucursal?',
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
              text: 'Actualizando Sucursal...'
            })
            axios.post('/api/update-branch-office', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Sucursal actualizada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/sucursales";
            }).catch(err => {
              console.log('mostrando el error', err)
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
        })
      }, 200);
    },
    createNewBranchOffice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateBranchOffices .is-invalid").length > 0) {
          this.$toast.error({
            title: 'Error',
            message: 'Revisa que todos los campos que son obligatorios tengan datos',
            showDuration: 2000,
            hideDuration: 9000,
            position: 'top right',
          })
          return;
        }
        const data = new FormData()
        data.append('name', this.name);
        data.append('code', this.code);

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de realizar el registro?',
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
              text: 'Registrando Sucursal...'
            })
            axios.post('/api/register/store-branch-office', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Sucursal creada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/sucursales";
            }).catch(err => {
              console.log('mostrando el error', err)
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
        })

      }, 200)
    },
    getBranchOfficeFirst(id) {
      axios.get('/api/get-branch-office/' + id).then(resp => {
        this.$vs.loading({
          color: '#3f4f6e',
          text: 'Cargando datos...'
        })
        this.name = resp.data.data.name
        this.code = resp.data.data.code
        this.id = resp.data.data.id
        this.codeValidet = resp.data.data.code
        this.idValidateCode = 1

        setTimeout(() => {
          this.$vs.loading.close()
        }, 1000)
      }).catch(err => {

      })
    },
    getBranchOffices() {
      axios.get('/api/all-branch-offices').then(resp => {
        this.dataBranchOffices = resp.data.data
        setTimeout(()=>{
          window.feather.replace()
        }, 200)

      }).catch(err => {
        console.log('mostrando el error', err)
        this.$toast.error({
          title: 'Algo salio mal',
          message: 'Comunícate con el administrador',
          showDuration: 1000,
          hideDuration: 8000,
        })
      })
    },
    clearInputBranchOffice() {
      this.name = '';
      this.code = '';
      this.codeValidet = '';
      this.idValidateCode = '';
    }
  },
  mounted() {
    this.getBranchOffices()
  },
  computed: {
    resultQuery() {
      if (this.searchQuery) {

        return this.dataBranchOffices.filter((item) => {
          console.log(item)
          return this.searchQuery.toLowerCase().split(' ').every(v =>
            item.code.toLowerCase().includes(v) || item.name.toLowerCase().includes(v),
          )
        })
      } else {
        return this.dataBranchOffices;
      }
    }
  },

  watch: {
    code: function (val) {
      let data = this
      if (val) {
        console.log('codigo propio', this.codeValidet)
        console.log('value', val)
        if (this.codeValidet !== val) {
          setTimeout(() => {
            this.$vs.loading({
              color: '#3f4f6e',
              text: 'Válidando código...'
            })
            axios.get('/api/verify-code-branch-office/' + val)
              .then(resp => {
                if (resp.data) {
                  if (this.idValidateCode === 1) {
                    $("#txtCodeBranchOfficesEdit").addClass("is-invalid");
                    $("#text-verify-code-branch-office-edit").css("display", "block");
                  } else {
                    $("#txtCodeBranchOffices").addClass("is-invalid");
                    $("#text-verify-code-branch-office").css("display", "block");
                  }
                } else {
                  data.emailverify = ''
                  $("#txtCodeBranchOffices").removeClass("is-invalid");
                  $("#text-verify-code-branch-office").css("display", "none");

                  $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
                  $("#text-verify-code-branch-office-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
          $("#text-verify-code-branch-office-edit").css("display", "none");
        }
      }
    }
  }
}
</script>

<style scoped>

</style>
