@extends('layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
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
                        class="datatables-all-clients hover datatablescreategica datatables-basic table table-striped"
                        style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th style="max-width: 30% !important;">Cliente</th>
{{--                            <th>Tipo de Cliente</th>--}}
{{--                            <th>Categoría</th>--}}
                            <th>Correo Electrónico</th>
                            <th>Teléfono</th>
                            <th>País</th>
                            <th>Ciudad</th>
                            <th>Estado</th>
                            {{--                            <th>Acciones</th>--}}
                        </tr>
                        </thead>
                    </table>
                </div>
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
            setTimeout(() => {
                    var table = $('.datatables-all-clients').DataTable({

                        "processing": true,
                        "lengthMenu": [7, 10, 25, 50, 75, 100],
                        // "scrollY": 800,
                        // "scrollX": true,
                        "responsive": true,
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
                        // "columnDefs": [
                        //     {"width": '10%', targets: 0},
                        // ],
                        // "order": [[1, 'asc']],

                        "ajax": {
                            url: "{{route('api.all.customers')}}",
                        },
                        "columns": [
                            {"data": "id"},
                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.name === null) {
                                        return '<span class="label label-danger text-center" style="color:#F05E7D !important">Ningún valor por defecto</span>'
                                    } else {
                                        return `<span class="label text-center font-weight-bold"><a href="/customer/profile/${JsonResultRow.user.slug}">${JsonResultRow.user.name}</a></span>`;
                                    }
                                },
                            },
                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.email === null) {
                                        return '<span class="label label-danger text-center" style="color:#F05E7D !important">Ningún valor por defecto</span>'
                                    } else {
                                        return `<span class="label text-center font-weight-bold">${JsonResultRow.user.email}</span>`;
                                    }
                                },
                            },
                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.phone === null) {
                                        return '<span class="label label-danger text-center" style="color:#F05E7D !important">Ningún valor por defecto</span>'
                                    } else {
                                        return `<span class="label text-center font-weight-bold">${JsonResultRow.user.phone}</span>`;
                                    }
                                },
                            },



                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.country === null) {
                                        return '<span class="label label-danger text-center" style="color:#F05E7D !important">Ningún valor por defecto</span>'
                                    } else {
                                        return `<span class="label text-center font-weight-bold">${JsonResultRow.user.country.name} <img class="brand-logo"  width="20" src="${JsonResultRow.user.country.flag}" alt=""></span>`;
                                    }
                                },
                            },
                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.city === null) {
                                        return '<span class="label label-danger text-center" style="color:#F05E7D !important">Ningún valor por defecto</span>'
                                    } else {
                                        return `<span class="label text-center font-weight-bold">${JsonResultRow.user.city.name}</span>`;
                                    }
                                },
                            },

                            {
                                render: function (data, type, JsonResultRow, meta) {
                                    if (JsonResultRow.user.state === "1") {
                                        return '<div class="badge badge-pill badge-glow badge-success">Activo</div>'
                                    } else {
                                        return `<div class="badge badge-pill badge-glow badge-danger">Inactivo</div>`;
                                    }
                                },
                            },
                        ],


                        "language": {
                            "sProcessing": "Procesando",
                            "sLengthMenu": "Mostrar _MENU_ Registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "{{__('nigun_dato_tabla')}}",
                            "sInfo": "Mostrando  _END_ de _TOTAL_ registros",
                            "sInfoEmpty": "{{ __('mostrando_registros_del_cero') }}",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix": "",
                            "sSearch": "Buscar:",
                            "sUrl": "",
                            "sInfoThousands": ",",
                            "sLoadingRecords": "{{__('cargando')}}",
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
                                        "exportOptions": {columns: [0, 1, 3, 4, 5, 6]},
                                        "customize": function ( win ) {
                                            $(win.document.body)
                                                .css( 'font-size', '10pt' )
                                                // .prepend(
                                                //     enter' style="text-align: center;top:50%;"><img width="550" src="${window.url}${window.logo_ligth}" style=" opacity: 12%;" /></div>``<div align='c
                                                // );

                                            $(win.document.body).find( 'table' )
                                                .addClass( 'compact' )
                                                .css( 'font-size', 'inherit' );
                                        }
                                    },
                                    {
                                        "extend": 'csv',
                                        "text": feather.icons['file-text'].toSvg({class: 'font-small-4 mr-50'}) + 'Csv',
                                        "className": 'dropdown-item',
                                        "exportOptions": {columns: [0, 1, 3, 4, 5, 6]}
                                    },
                                    {
                                        "extend": 'excel',
                                        "text": feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                        "className": 'dropdown-item',
                                        "exportOptions": {columns: [0, 1, 3, 4, 5, 6]}
                                    },
                                    {
                                        "extend": 'pdf',
                                        "text": feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                                        "className": 'dropdown-item',
                                        "exportOptions": {columns: [0, 1, 3, 4, 5, 6]},
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
                                        "exportOptions": {columns: [0, 1, 3, 4, 5, 6]}
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
                                text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nueva Empresa',
                                className: 'create-new btn btn-primary',
                                attr: {
                                    'onclick': `window.location.href='/create-customer'`
                                },
                                init: function (api, node, config) {
                                    $(node).removeClass('btn-secondary');
                                }
                            }
                        ],

                    })
                    let text = 'Todos los clientes registrados'
                    $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                    table.on('order.dt search.dt', function () {
                        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    }).draw();
                }
                , 1);
        });

    </script>
@endpush
