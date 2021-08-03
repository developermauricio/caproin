<template>
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="myModalLabel160">Crear Proveedor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form class="" id="validateCreateProvider" method="post">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <input-form
                            id="txtNameUserProvider"
                            label="Nombre y Apellido o Razón Social"
                            pattern="alf"
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
                            id="textTypeIdentificationProvider"
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
                            id="txtIdentifacationProvider"
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
                            id="textTypeProvider"
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
                            id="txtCodeProvider"
                            label="Código"
                            pattern="all"
                            errorMsg="Ingrese un código válido"
                            requiredMsg="El código es obligatorio"
                            :modelo.sync="code"
                            :required="true"
                            :msgServer.sync="errors.code"
                        ></input-form>
                        <p style="margin-top: -1rem;font-size: 0.9rem; display: none"
                           id="text-verify-code-provider" class="text-danger">El código ya
                            ha sido registrado</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" data-dismiss="modal" class="btn btn-danger">Cancelar</button>
              <button @click="createNewProvider()" type="button" class="btn btn-primary">Crear Proveedor</button>
            </div>
        </form>
    </div>

</template>

<script>
import Multiselect from "vue-multiselect";
import VuePhoneNumberInput from "vue-phone-number-input";
import vue2Dropzone from "vue2-dropzone";
import Swal from 'sweetalert2'
export default {
    name: "CreateProvider",
    components: {
        Multiselect,
        VuePhoneNumberInput,
    },
    data() {
        return {
            identification: '',
            identificationVerify: '',
            codeVerify: '',
            code: null,
            provider: {
                businessName: '',
                typeIdentification: null,
                typeProvider: null,


            },
            optionsTypeProvider: [],
            optionsTypeIdentification: [],
            errors: {},
        }
    },
    methods: {

        createNewProvider() {
            eventBus.$emit("validarFormulario");
            setTimeout(() => {
                let resp = this;
                /***  VALIDANDO LOS ERRORES Y MOSTRANDO UNA ALERTA  ***/
                if (document.querySelectorAll("#validateCreateProvider .is-invalid").length > 0) {
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
                data.append('businessName', this.provider.businessName);
                data.append('typeIdentification', JSON.stringify(this.provider.typeIdentification));
                data.append('identification', this.identification);
                data.append('typeProvider', JSON.stringify(this.provider.typeProvider));
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
                            text: 'Registrando Proveedor...'
                        })
                        axios.post('/api/register/store-provider', data).then(res => {
                            this.$toast.success({
                                title: '¡Muy bien!',
                                message: 'Proveedor creado correctamente',
                                showDuration: 1000,
                                hideDuration: 7000,
                                position: 'top right',
                            })
                            window.location = "/providers";
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

        getApiTypeProviders() {
            axios.get('/api/all-type-providers').then(resp => {
                this.optionsTypeProvider = resp.data.data
            })
        },
        getApiTypeIdentification() {
            axios.get('/api/get-identificationtype').then(resp => {
                this.optionsTypeIdentification = resp.data.data
            })
        }
    },
    watch: {
        identification: function (val) {
            console.log(val)
            let data = this
            if (val) {
                if (val === ''){
                    data.identificationVerify = ''
                    $("#txtIdentifacationProvider").removeClass("is-invalid");
                    $("#text-verify-identification-provider").css("display", "none");
                }
                axios.get('/api/verify-identification-user/' + val)
                    .then(resp => {
                        if (resp.data) {
                            $("#txtIdentifacationProvider").addClass("is-invalid");
                            $("#text-verify-identification-provider").css("display", "block");
                            this.$toast.error({
                                title: '¡Atención!',
                                message: 'El número de identificación ya ha sido registrado, por favor ingrese otro',
                                showDuration: 1000,
                                hideDuration: 8000,
                            })
                        } else {
                            data.identificationVerify = ''
                            $("#txtIdentifacationProvider").removeClass("is-invalid");
                            $("#text-verify-identification-provider").css("display", "none");
                        }
                    }).catch(err => {
                });
            }
        },
        code: function (val) {
            console.log(val)
            let data = this
            if (val) {
                if (val === ''){
                    data.codeVerify = ''
                    $("#txtCodeProvider").removeClass("is-invalid");
                    $("#text-verify-code-provider").css("display", "none");
                }
                axios.get('/api/verify-code-provider/' + val)
                    .then(resp => {
                        if (resp.data) {
                            $("#txtCodeProvider").addClass("is-invalid");
                            $("#text-verify-code-provider").css("display", "block");
                            this.$toast.error({
                                title: '¡Atención!',
                                message: 'El código ya ha sido registrado, por favor ingrese otro',
                                showDuration: 1000,
                                hideDuration: 8000,
                            })
                        } else {
                            data.codeVerify = ''
                            $("#txtCodeProvider").removeClass("is-invalid");
                            $("#text-verify-code-provider").css("display", "none");
                        }
                    }).catch(err => {
                });
            }
        },
    },
    mounted() {
        this.getApiTypeProviders();
        this.getApiTypeIdentification();
    }
}
</script>

<style scoped>
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
