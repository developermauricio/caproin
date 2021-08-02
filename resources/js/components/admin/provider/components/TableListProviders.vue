<template>
    <vue-bootstrap4-table :rows="rows" :columns="columns" :config="config" :actions="actions"
                          @on-download="onDownload">
    </vue-bootstrap4-table>
</template>

<script>
import VueBootstrap4Table from 'vue-bootstrap4-table'

export default {
    name: "TableListProviders",
    components: {
        VueBootstrap4Table
    },
    data: function () {
        return {
            rows: [{
                "id": 1,
                "name": {
                    "first_name": "Vladimir",
                    "last_name": "Nitzsche"
                },
                "address": {
                    "country": "Mayotte"
                },
                "email": "franecki.anastasia@gmail.com",
            },
                {
                    "id": 2,
                    "name": {
                        "first_name": "Irwin",
                        "last_name": "Bayer"
                    },
                    "age": 23,
                    "address": {
                        "country": "Guernsey"
                    },
                    "email": "rlittle@macejkovic.biz",
                },
                {
                    "id": 3,
                    "name": {
                        "first_name": "Don",
                        "last_name": "Herman"
                    },
                    "address": {
                        "country": "Papua New Guinea"
                    },
                    "email": "delia.becker@cormier.com",
                }],
            columns: [{
                label: "id",
                name: "id",
                filter: {
                    type: "select",
                    mode: "single",
                    closeDropdownOnSelection: true,
                    placeholder: "Select options",
                    options: [
                        {
                            "name": "1",
                            "value": "1"
                        },
                        {
                            "name": "2",
                            "value": "2"
                        },
                        {
                            "name": "3",
                            "value": "3"
                        }
                    ],
                },
                sort: true,
            },
                {
                    label: "First Name",
                    name: "name.first_name",
                    filter: {
                        type: "simple",
                        placeholder: "Enter first name"
                    },
                    sort: true,
                },
                {
                    label: "Email",
                    name: "email",
                    sort: true,
                },
                {
                    label: "Country",
                    name: "address.country",
                    filter: {
                        type: "simple",
                        placeholder: "Enter country"
                    },
                }],
            actions: [
                {
                    btn_text: "Download",
                    event_name: "on-download",
                    class: "btn btn-primary my-custom-class",
                    event_payload: {
                        msg: "my custom msg"
                    }
                }
            ],
            config: {
                checkbox_rows: false,
                rows_selectable: false,
                card_title: "Vue Bootsrap 4 advanced table"
            }
        }
    },
    methods: {
        onDownload(payload) {
            console.log(payload);
        }
    },
}
</script>

<style scoped>

</style>
