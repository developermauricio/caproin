<template>
    <div>
        <div class="row">
            <div class="col-12 col-md-5 col-lg-4 pt-2">
                <input-form
                    label="Selecciona el tipo de cliente"
                    id="txtTypeClient"
                    errorMsg
                    requiredMsg=""
                    :modelo.sync="modelTypeClient"
                    :required="false"
                    type="multiselect"
                    @updatedValue="changeTypeClient"
                    selectLabel="Selecciona tipo de cliente"
                    :multiselect="{
                                           options: optionsCustomerType,
                                           selectLabel:'Seleccionar',
                                           selectedLabel:'Seleccionado',
                                           deselectLabel:'Desmarcar',
                                          placeholder: 'Selecciona el tipo de cliente',
                                          taggable : true,
                                          'track-by':'id',
                                          label: 'name',
                                          'custom-label': customerType=>customerType.name
                                        }"
                ></input-form>
            </div>
        </div>
        <component-customer-type-legal :type_client ="modelTypeClient" v-if="typeEntity"></component-customer-type-legal>
        <component-customer-persona-natural :type_client ="modelTypeClient" v-if="typeClient"></component-customer-persona-natural>
    </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
    name: "CreateNewCustomer",
    components: {
        Multiselect,
    },
    data(){
        return {
            language: window.lang,
            typeClient: false,
            typeEntity: false,
            optionsCustomerType: [],
            modelTypeClient: [],
        }
    },
    methods: {
        changeTypeClient(typeClientSelect){
            if (typeClientSelect !== null){
                if (typeClientSelect.id === 2){
                    this.typeClient = false
                    this.typeEntity = true
                }else if(typeClientSelect.id === 1){
                    this.typeEntity = false
                    this.typeClient = true
                }
            }else{
                this.typeClient = false
                this.typeEntity = false
            }

        },
        getTypeEntity(){
            axios.get('/api/get-customer-type')
                .then(resp => {
                    this.optionsCustomerType = resp.data.data;
                })
        }
    },
    mounted() {
        this.getTypeEntity();
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
