@extends('layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@section('title', 'Lista de Clientes')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Lista de Clientes</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            {{--                            <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
                            {{--                            </li>--}}
                            <li class="breadcrumb-item active">
                                Clientes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card p-2">
                    <table
                        class="datatables-all-clients hover datatablescreategica table-responsive datatables-basic table table-striped"
                        style="width:100%">
                        <thead>
                        <tr>
                            <th style="max-width: 30% !important;">Código</th>
                            <th>Nombre o razón social</th>
                            <th>Tipo Identificación</th>
                            <th>Identificación</th>
                            <th>Correo electrónico</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                        <tr class="tr-filter-dekstop-provider">
                            <th></th>
                            <th></th>
                            <th class="filter-2" style="max-width: 30% !important;"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="filter-6"></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--=====================================
		    MODAL PARA CREAR UN NUEVO CLIENTE
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-new-provider" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <create-customer></create-customer>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA VER EL DETALLE DEL CLIENTE
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-show-customer" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <show-customer id="componet-show-customer" id-customer></show-customer>
            </div>
        </div>
    </section>
@endsection
@push('js')
    <script src="/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
    {{--    <script src="/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>--}}
    <script src="/app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/jszip.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="/app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js"></script>
    <script src="/app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.2/js/dataTables.fixedColumns.min.js"></script>
@endpush
@push('js')
    <script>
        $(function () {
            let table = null;
            setTimeout(() => {
                table = $('.datatables-all-clients').DataTable({
                    "ordering": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "initComplete": function () {
                        this.api().columns([2]).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-clients .filter-'+column[0][0])
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    console.log('que fue', val)
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });


                        this.api().columns([6]).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-clients .filter-'+column[0][0])
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                const state = (d == 1 ? 'Activo' : 'Inactivo');
                                select.append('<option value="' + state + '">' + state + '</option>')
                            });
                        });

                        this.api().columns([2]).every(function () {
                            var column = this;
                            var select = $(`<select class="form-control filter-select-movil-provider mb-1"><option hidden selected>Filtrar por tipo de identificación</option><option value="">Mostrar todos los registros</option></select>`)
                                .appendTo('.card-header')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>')
                            });
                        });
                        this.api().columns([6]).every(function () {
                            var column = this;
                            console.log(column)
                            var select = $('<select class="form-control filter-select-movil-provider mb-1"><option hidden selected>Filtrar por Estado</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.card-header')
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    console.log('que fue', val)
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                console.log('ESTAD0', d)
                                const state = (d == 1 ? 'Activo' : 'Inactivo');
                                select.append('<option value="' + state + '">' + state + '</option>')
                            });
                        });
                    },

                    "processing": true,
                    "lengthMenu": [7, 10, 25, 50, 75, 100],
                    // "scrollY": 800,
                    // "scrollX": true,
                    // "responsive": true,
                    // "scrollCollapse": true,
                    // "paging": false,
                    // "fixedColumns": {
                    //     "leftColumns": 2,
                    // },
                    "pageLength": 10,
                    "autoWidth": false,
                    "columnDefs": [{
                        "defaultContent": "-",
                        "targets": "_all"
                    }],
                    "drawCallback": function (settings) {
                        feather.replace();
                    },
                    // "columnDefs": [
                    //     {"width": '10%', targets: 0},
                    // ],
                    // "order": [[1, 'asc']],

                    "ajax": {
                        url: "{{route('api.all.customers')}}",
                    },
                    "columns": [
                        // {"data": "id"},
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.id === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.id}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.business_name === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.business_name}</span>`;
                                }
                            },
                        },
                        {
                            data: "user.identification_type.name",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.user.identification_type === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.user.identification_type.name}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.user.identification === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.user.identification}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.user.email === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.user.email}</span>`;
                                }
                            },
                        },

                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.user.phone === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.user.phone}</span>`;
                                }
                            },
                        },

                        {
                            data: "user.state",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.user.state === "1") {
                                    return '<div class="badge badge-pill badge-light-success mr-1">Activo</div>'
                                } else {
                                    return `<div class="badge badge-pill badge-light-danger mr-1">Inactivo</div>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                return '<div class="demo-inline-spacing text-center"><button data-target="#modal-show-customer" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-customer btn-icon btn-primary"><i data-feather="edit-2"></i></button></div>'

                            },
                        },
                    ],
                    "language": {
                        "sProcessing": "Procesando",
                        "sLengthMenu": "Mostrar _MENU_ Registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "No hay datos",
                        "sInfo": "Mostrando  _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "{{ __('mostrando_registros_del_cero') }}",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    },
                    "dom":
                        '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    // "displayLength": 7,
                    "buttons": [
                        {
                            "extend": 'collection',
                            "className": 'btn btn-outline-secondary theme-light dropdown-toggle mr-2',
                            "text": feather.icons['share'].toSvg({class: 'font-small-4 mr-50'}) + 'Exportar',
                            "buttons": [
                                {
                                    "extend": 'print',
                                    "text": feather.icons['printer'].toSvg({class: 'font-small-4 mr-50'}) + 'Imprimir',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6]},
                                    "customize": function (win) {
                                        $(win.document.body)
                                            .css('font-size', '10pt')
                                        // .prepend(
                                        //     enter' style="text-align: center;top:50%;"><img width="550" src="${window.url}${window.logo_ligth}" style=" opacity: 12%;" /></div>``<div align='c
                                        // );

                                        $(win.document.body).find('table')
                                            .addClass('compact')
                                            .css('font-size', 'inherit');
                                    }
                                },
                                {
                                    "extend": 'csv',
                                    "text": feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6]}
                                },
                                {
                                    "extend": 'excel',
                                    "text": feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6]}
                                },
                                {
                                    "extend": 'pdf',
                                    "text": feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6]},
                                    "orientation": 'landscape',
                                    // "customize": function (doc) {
                                    //     doc.content.splice(1, 0, {
                                    //         margin: [0, 0, 0, 12],
                                    //         alignment: 'center',
                                    //         image: window.logo_base_64
                                    //     });
                                    // }
                                },
                                {
                                    "extend": 'copy',
                                    "text": feather.icons['copy'].toSvg({class: 'font-small-4 mr-50'}) + 'Copiar',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6]}
                                }
                            ],
                            // init: function (api, node, config) {
                            //     $(node).removeClass('btn-secondary');
                            //     $(node).parent().removeClass('btn-group');
                            //     setTimeout(function () {
                            //         $(node).closest('.dt-buttons').removeClass('btn-group').addClass('d-inline-flex');
                            //     }, 50);
                            // }
                        },
                        {
                            text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nuevo Cliente',
                            className: 'create-new btn btn-primary',
                            attr: {
                                'data-target': '#modal-new-provider',
                                'data-toggle': 'modal',
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                    ],

                })
                let text = 'Todos los clientes registrados'
                $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                // table.on('order.dt search.dt', function () {
                //     table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                //         cell.innerHTML = i + 1;
                //     });
                // }).draw();
            }, 5);


            $('.datatables-all-clients').on('click', '.btn-show-customer', function (e) {
                var dataTableCustomer = table.row($(this).parents('tr')).data();
                console.log(dataTableCustomer.id);
                $('#traerDatosBotonCustomer').val(dataTableCustomer.id).click();
                $('#componet-show-customer').attr("id-customer", dataTableCustomer.id)
            });
        });

    </script>
@endpush

