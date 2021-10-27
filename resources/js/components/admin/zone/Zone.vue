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
        <button v-if="$gate.allow('createZone', 'zone')" data-target="#modal-new-branch-office" data-toggle="modal"
                class="btn btn-primary create-new float-right"><i
          data-feather="plus" class="mr-50"></i>Nueva Zona
        </button>
      </div>
    </div>
    <div class="row pt-2">
      <div v-if="resultQuery" v-for="zones in resultQuery" :key="zones.id"
           class="col-md-6 col-lg-4 col-12">
        <div class="card card-employee-task">
          <div class="card-header">
            <h4 class="card-title">{{ zones.name }}</h4>
            <div class="dropdown">
              <i data-feather="more-vertical" class="font-medium-3  dropdown-toggle cursor-pointer"
                 data-toggle="dropdown"></i>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-target="#modal-edit-branch-office" data-toggle="modal"
                   @click="getBranchOfficeFirst(zones.id)">
                  <i data-feather="edit-2" class="mr-50"></i>
                  <span>Editar</span>
                </a>
                <a v-if="$gate.allow('deleteZone', 'zone')" class="dropdown-item" @click="deleteZone(zones.id)">
                  <i data-feather="trash" class="mr-50"></i>
                  <span>Eliminar</span>
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <p>Código de la Sucursal: <strong>{{ zones.code }}</strong></p>
            <p>Estado: <strong>{{ zones.state == 1 ? 'Activo' : 'Inactivo' }}</strong></p>

          </div>
        </div>
      </div>
    </div>
    <!--=====================================
		    MODAL PARA CREAR UN NUEVA ZONA
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-new-branch-office" data-backdrop="static" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel1600">Crear Zona</h5>
            <button @click="clearInputZone()" type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="" id="validateCreateZone" method="post">
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
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-one-character-branchoffices" class="text-danger">El nombre no puede contener menos de 5 caracteres</p>
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
                     id="text-verify-code-zone" class="text-danger">El código ya
                    ha sido registrado</p>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-one-character-code-zone" class="text-danger">El código no puede contener menos de 5 caracteres</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button @click="clearInputZone()" type="button" data-dismiss="modal" class="btn btn-gris">
                Cancelar
              </button>
              <button @click="createNewZone()" type="button" class="btn btn-primary">Crear Zona</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!--=====================================
		    MODAL PARA EDITAR ZONA
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-edit-branch-office" data-backdrop="static" tabindex="-1"
         role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel160">Editar Sucursal</h5>
            <button @click="clearInputZone()" type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form class="" id="validateCreateZoneEdit" method="post">
            <div class="modal-body">
              <div class="row">
                <div class="col-12">
                  <input-form
                    id="txtNameBranchOfficesEdit"
                    label="Nombre de la sucursal"
                    pattern="all"
                    errorMsg="Ingrese un nombre válido"
                    requiredMsg="El nombre es obligatorio"
                    :modelo.sync="nameEdit"
                    :required="true"
                    :msgServer.sync="errors.nameEdit"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-one-character-branchoffices-edit" class="text-danger">El nombre no puede contener menos de 5 caracteres</p>
                  <input-form
                    id="txtCodeBranchOfficesEdit"
                    label="Código"
                    pattern="all"
                    errorMsg="Ingrese un código válido"
                    requiredMsg="El código es obligatorio"
                    :modelo.sync="codeEdit"
                    :required="true"
                    :msgServer.sync="errors.codeEdit"
                  ></input-form>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-code-zone-edit" class="text-danger">El coódigo ya
                    ha sido registrado</p>
                  <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                     id="text-verify-one-character-code-zone-edit" class="text-danger">El código no puede contener menos de 5 caracteres</p>
                  <input-form
                    label="Estado"
                    id="textStateZonaEdit"
                    errorMsg
                    requiredMsg="El estado es obligatorio"
                    :required="true"
                    :modelo.sync="state"
                    :msgServer.sync="errors.state"
                    type="multiselect"
                    selectLabel="Estado"
                    :multiselect="{ options: optionsStateZone,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateZone=>stateZone.name
                                        }"
                  ></input-form>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button @click="clearInputZone()" type="button" data-dismiss="modal" class="btn btn-gris">
                Cancelar
              </button>
              <button @click="udateBranchOffice()" type="button" class="btn btn-primary">Actualizar Zona</button>
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
  name: "Zone",
  data() {
    return {
      dataZones: [],
      name: '',
      nameEdit: '',
      code: '',
      codeEdit: '',
      idValidateCode: null,
      codeValidet: '',
      state: null,
      id: null,
      searchQuery: null,
      errors: {},
      optionsStateZone: [
        {
          name: 'Activo',
          id: 1
        },
        {
          name: 'Inactivo',
          id: 2
        }
      ],
    }
  },
  methods: {
    deleteZone(id) {
      console.log('eliminar', id)
      const data = new FormData()
      data.append('id', id);

      Swal.fire({
        title: 'Confirmar',
        text: '¿Estás seguro de eliminar esta zona?',
        confirmButtonColor: "#D9393D",
        cancelButtonColor: "#7D7E7E",
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
            text: 'Eliminando Zona...'
          })
          axios.post('/api/delete-zone', data).then(resp => {
            this.$toast.success({
              title: '¡Muy bien!',
              message: 'La Zona se eliminó correctamente',
              showDuration: 1000,
              hideDuration: 7000,
              position: 'top right',
            })
            window.location = "/zonas";
          }).catch(err => {
            if (err.response.status === 301) {
              console.log('mensaje', err.response.data)
              return this.$toast.error({
                title: 'Atención',
                message: err.response.data,
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
            } else {
              this.$toast.error({
                title: 'Algo salio mal',
                message: 'Comunícate con el administrador',
                showDuration: 1000,
                hideDuration: 8000,
              })
            }
          })
          setTimeout(() => {
            this.$vs.loading.close()
          }, 2000)
        }
      })
    },
    udateBranchOffice() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateZoneEdit .is-invalid").length > 0) {
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
        data.append('name', this.nameEdit);
        data.append('code', this.codeEdit);
        data.append('id', this.id);
        data.append('state', JSON.stringify(this.state));

        Swal.fire({
          title: 'Confirmar',
          text: '¿Estás seguro de actualizar esta zona?',
          confirmButtonColor: "#D9393D",
          cancelButtonColor: "#7D7E7E",
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
              text: 'Actualizando Zona...'
            })
            axios.post('/api/update-zone', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'Zona actualizada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/zonas";
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
    createNewZone() {
      eventBus.$emit("validarFormulario");
      setTimeout(() => {
        let resp = this;
        /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
        if (document.querySelectorAll("#validateCreateZone .is-invalid").length > 0) {
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
          confirmButtonColor: "#D9393D",
          cancelButtonColor: "#7D7E7E",
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
              text: 'Registrando Zona...'
            })
            axios.post('/api/register/store-zone', data).then(res => {
              this.$toast.success({
                title: '¡Muy bien!',
                message: 'La zona ha sido creada correctamente',
                showDuration: 1000,
                hideDuration: 7000,
                position: 'top right',
              })
              window.location = "/zonas";
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
      axios.get('/api/get-zone/' + id).then(resp => {
        this.$vs.loading({
          color: '#3f4f6e',
          text: 'Cargando datos...'
        })
        this.nameEdit = resp.data.data.name
        this.codeEdit = resp.data.data.code
        this.id = resp.data.data.id
        this.codeValidet = resp.data.data.code
        this.idValidateCode = 1
        if (resp.data.data.state === '1') {
          this.state = {name: "Activo", id: 1}
        } else {
          this.state = {name: "Inactivo", id: 2}
        }

        setTimeout(() => {
          this.$vs.loading.close()
        }, 1000)
      }).catch(err => {

      })
    },
    getBranchOffices() {
      axios.get('/api/all-zones').then(resp => {
        this.dataZones = resp.data.data
        setTimeout(() => {
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
    clearInputZone() {
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
        window.feather.replace()
        return this.dataZones.filter((item) => {
          return this.searchQuery.toLowerCase().split(' ').every(v =>
            item.code.toLowerCase().includes(v) || item.name.toLowerCase().includes(v),
          )
        })
      } else {
        window.feather.replace()
        return this.dataZones;
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
            axios.get('/api/verify-code-zone/' + val)
              .then(resp => {
                if (resp.data) {
                  if (this.idValidateCode === 1) {
                    $("#txtCodeBranchOfficesEdit").addClass("is-invalid");
                    $("#text-verify-code-zone-edit").css("display", "block");
                  } else {
                    $("#txtCodeBranchOffices").addClass("is-invalid");
                    $("#text-verify-code-zone").css("display", "block");
                  }
                } else {
                  data.emailverify = ''
                  $("#txtCodeBranchOffices").removeClass("is-invalid");
                  $("#text-verify-code-zone").css("display", "none");

                  $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
                  $("#text-verify-code-zone-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
          $("#text-verify-code-zone-edit").css("display", "none");
        }
      }
      if (val.length <= 5) {

        setTimeout(() => {
          $("#txtCodeBranchOffices").addClass("is-invalid");
          $("#text-verify-one-character-code-zone").css("display", "block");
          document.getElementById('text-verify-one-character-code-zone').disabled = true;
        }, 200)
      } else {
        document.getElementById('text-verify-one-character-code-zone').disabled = false;
        $("#txtCodeBranchOffices").removeClass("is-invalid");
        $("#text-verify-one-character-code-zone").css("display", "none");
      }
    },
    codeEdit: function (val) {
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
            axios.get('/api/verify-code-zone/' + val)
              .then(resp => {
                if (resp.data) {
                  if (this.idValidateCode === 1) {
                    $("#txtCodeBranchOfficesEdit").addClass("is-invalid");
                    $("#text-verify-code-zone-edit").css("display", "block");
                  } else {
                    $("#txtCodeBranchOffices").addClass("is-invalid");
                    $("#text-verify-code-zone").css("display", "block");
                  }
                } else {
                  data.emailverify = ''
                  $("#txtCodeBranchOffices").removeClass("is-invalid");
                  $("#text-verify-code-zone").css("display", "none");

                  $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
                  $("#text-verify-code-zone-edit").css("display", "none");
                }
                this.$vs.loading.close()
              }).catch(err => {
            });
          }, 200)
          this.$vs.loading.close()
        } else {
          $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
          $("#text-verify-code-zone-edit").css("display", "none");
        }
      }
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtCodeBranchOfficesEdit").addClass("is-invalid");
          $("#text-verify-one-character-code-zone-edit").css("display", "block");
          document.getElementById('text-verify-one-character-code-zone-edit').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-code-zone-edit').disabled = false;
        $("#txtCodeBranchOfficesEdit").removeClass("is-invalid");
        $("#text-verify-one-character-code-zone-edit").css("display", "none");
      }
    },
    name: function (val) {
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtNameBranchOffices").addClass("is-invalid");
          $("#text-verify-one-character-branchoffices").css("display", "block");
          document.getElementById('text-verify-one-character-branchoffices').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-branchoffices').disabled = false;
        $("#txtNameBranchOffices").removeClass("is-invalid");
        $("#text-verify-one-character-branchoffices").css("display", "none");
      }
    },
    nameEdit: function (val) {
      if (val.length <= 5){
        setTimeout(() => {
          $("#txtNameBranchOfficesEdit").addClass("is-invalid");
          $("#text-verify-one-character-branchoffices-edit").css("display", "block");
          document.getElementById('text-verify-one-character-branchoffices-edit').disabled = true;
        }, 200)
      }else{
        document.getElementById('text-verify-one-character-branchoffices-edit').disabled = false;
        $("#txtNameBranchOfficesEdit").removeClass("is-invalid");
        $("#text-verify-one-character-branchoffices-edit").css("display", "none");
      }
    },
  }
}
</script>

<style scoped>

</style>
