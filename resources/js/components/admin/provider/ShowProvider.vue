<template>
    <div>
        <input type="hidden" @click="traerDatos" id="traerDatosBoton"/>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel160">Modificar Proveedor</h5>
                <button @click="closeModalShowProvider()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="validateEditProviderForm" class="" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <input-form
                                id="txtNameUserProviderEdit"
                                label="Nombre y Apellido o Razón Social"
                                pattern="all"
                                errorMsg="Ingrese nombre o razón social válido"
                                requiredMsg="El nombre o razón social es obligatorio"
                                :modelo.sync="provider.businessName"
                                :required="true"
                                :msgServer.sync="errors.businessName"
                            ></input-form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6">
                            <input-form
                                label="Tipo de Identificación"
                                id="textTypeIdentificationProviderEdit"
                                errorMsg
                                requiredMsg="El tipo de identificación es obligatorio"
                                :required="true"
                                :modelo.sync="provider.typeIdentification"
                                :msgServer.sync="errors.typeIdentification"
                                type="multiselect"
                                selectLabel="Tipo de Identificación"
                                :multiselect="{ options: optionsTypeIdentification,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Identificación',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeIdentification=>typeIdentification.name
                                        }"
                            ></input-form>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <input-form
                                id="txtIdentifacationProviderEdit"
                                label="Número de Identificación"
                                pattern="all"
                                errorMsg="Ingrese un número de identificación válido"
                                requiredMsg="El número identificación es obligatorio"
                                :modelo.sync="identification"
                                :required="true"
                                :msgServer.sync="errors.identification"
                            ></input-form>
                            <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                               id="text-verify-identification-provider" class="text-danger">El número de identificación ya
                                ha sido registrado</p>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <input-form
                                label="Tipo de Proveedor"
                                id="textTypeProviderEdit"
                                errorMsg
                                requiredMsg="El tipo de proveedor es obligatorio"
                                :required="true"
                                :modelo.sync="provider.typeProvider"
                                :msgServer.sync="errors.typeProvider"
                                type="multiselect"
                                selectLabel="Tipo de Proveedor"
                                :multiselect="{ options: optionsTypeProvider,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Tipo de Proveedor',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': typeProvider=>typeProvider.name
                                        }"
                            ></input-form>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <input-form
                                id="txtCodeProviderEdit"
                                label="Código"
                                pattern="all"
                                errorMsg="Ingrese un código válido"
                                requiredMsg="El código es obligatorio"
                                :modelo.sync="provider.code"
                                :required="true"
                                :msgServer.sync="errors.code"
                            ></input-form>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6">
                            <input-form
                                label="Estado del Proveedor"
                                id="textStateProviderEdit"
                                errorMsg
                                requiredMsg="El estado del proveedor es obligatorio"
                                :required="true"
                                :modelo.sync="provider.state"
                                :msgServer.sync="errors.state"
                                type="multiselect"
                                selectLabel="Estado del Proveedor"
                                :multiselect="{ options: optionsStateProvider,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                           placeholder:'Estado del Proveedor',
                                          taggable : false,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': stateProvider=>stateProvider.name
                                        }"
                            ></input-form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  @click="closeModalShowProvider()" type="button" data-dismiss="modal" class="btn btn-danger">Cancelar</button>
                    <button @click="editProvider()" type="button" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
    name: "ShowProvider",
  components: {
    Multiselect,
  },
    data() {
        return {
            idProvider: null,
            idUser: null,
            dataProvider: null,
            identification: '',
            identificationVerify: '',
            provider: {
                businessName: '',
                typeIdentification: null,
                typeProvider: null,
                code: null,
                state:null,

            },
            optionsTypeProvider: [],
            optionsTypeIdentification: [],
            optionsStateProvider: [
                {
                    name: 'Activo',
                    id: 1
                },
                {
                    name: 'Inactivo',
                    id: 2
                }
            ],
            errors: {},
        }
    },

    methods: {
        editProvider() {
            eventBus.$emit("validarFormulario");
            setTimeout(() => {
                let resp = this;
                /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
                if (document.querySelectorAll("#validateEditProviderForm .is-invalid").length > 0) {
                    this.$toast.error({
                        title: 'Error',
                        message: 'Revisa que todos los campos que son obligatorios tengan datos',
                        showDuration: 2000,
                        hideDuration: 9000,
                        position: 'top right',
                    })
                    return;
                }
                // if (this.identification){
                //     axios.get('/api/verify-identification-user/' + this.identification)
                //         .then(resp => {
                //             if (resp.data) {
                //                 $("#txtIdentifacationProvider").addClass("is-invalid");
                //                 $("#text-verify-identification-provider").css("display", "block");
                //                 this.$toast.error({
                //                     title: '¡Atención!',
                //                     message: 'El número de identificación ya ha sido registrado, por favor ingrese otro',
                //                     showDuration: 1000,
                //                     hideDuration: 8000,
                //                 })
                //                 return
                //             } else {
                //                 data.identificationVerify = ''
                //                 $("#txtIdentifacationProvider").removeClass("is-invalid");
                //                 $("#text-verify-identification-provider").css("display", "none");
                //             }
                //         }).catch(err => {
                //     });
                // }
                const data = new FormData()
                data.append('businessName', this.provider.businessName);
                data.append('typeIdentification', JSON.stringify(this.provider.typeIdentification));
                data.append('identification', this.identification);
                data.append('typeProvider', JSON.stringify(this.provider.typeProvider));
                data.append('code', this.provider.code);
                data.append('state', JSON.stringify(this.provider.state));
                data.append('idProvider', this.idProvider);
                data.append('idUser', this.idUser);

                Swal.fire({
                    title: 'Confirmar',
                    text: '¿Estás seguro de actualizar?',
                    confirmButtonColor: "#0082FB",
                    cancelButtonColor: "#F05E7D",
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    customClass: "swal-confirmation",
                    showCancelButton: true,
                    reverseButtons: true,
                    allowOutsideClick: false,
                }).then(result => {
                    if (result.value) {
                        this.$vs.loading({
                            color: '#3f4f6e',
                            text: 'Actualizando Proveedor...'
                        })
                        axios.post('/api/update-provider', data).then(res => {
                            this.$toast.success({
                                title: '¡Muy bien!',
                                message: 'Proveedor actualizado correctamente',
                                showDuration: 1000,
                                hideDuration: 7000,
                                position: 'top right',
                            })
                            setTimeout(() => {
                                window.location = "/providers";
                            }, 200)
                        }).catch(err => {
                            console.log('mostrando el error', err)
                            this.$toast.error({
                                title: 'Algo salioó mal',
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
        traerDatos(e) {
            e.preventDefault();
            this.idProvider = +e.target.value;
            this.$vs.loading({
                color: '#3f4f6e',
                text: 'Espere un momento por favor...'
            })
            setTimeout(() => {
                axios.get('/api/data-provider/' + this.idProvider).then(resp => {
                    this.dataProvider = resp.data.data
                    this.idUser = this.dataProvider.users.id
                    this.provider.businessName = this.dataProvider.business_name
                    this.identification = this.dataProvider.users.identification
                    this.provider.code = this.dataProvider.code
                    this.provider.typeProvider = this.dataProvider.type_providers
                    this.provider.typeIdentification = this.dataProvider.users.identification_type
                    if (this.dataProvider.state === '1'){
                        this.provider.state = {name: "Activo", id: 1}
                    }else{
                        this.provider.state = {name: "Inactivo", id: 2}
                    }
                })
                this.$vs.loading.close()
            }, 500)
        },
        getApiTypeProviders() {
            axios.get('/api/all-type-providers').then(resp => {
                this.optionsTypeProvider = resp.data.data
            })
        },
        getApiTypeIdentification() {
            axios.get('/api/get-identificationtype').then(resp => {
                this.optionsTypeIdentification = resp.data.data
            })
        },
        closeModalShowProvider(){
            this.provider.businessName = ''
            this.identification = ''
            this.provider.code = ''
            this.provider.typeProvider = null
            this.provider.typeIdentification = null
        }
    },
    // watch:{
    //     idProvider
    // },
    mounted() {
        this.getApiTypeProviders();
        this.getApiTypeIdentification();
    }
}
</script>

<style>
.multiselect__tag {
  background: #0082FB !important;
}

.multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
  background: #283046 !important;
}

.multiselect__option--highlight {
  background: #0082FB !important;
}
</style>
