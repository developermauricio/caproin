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
@section('title', 'Proveedores')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12">
            <div class="row">
                <div class="col-12">
                    <h2 class="float-left mb-0 pb-1 border-title">Administrar Proveedores</h2>
{{--                    <div class="breadcrumb-wrapper">--}}
{{--                        <ol class="breadcrumb">--}}
{{--                                                        <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
{{--                                                        </li>--}}
{{--                            <li class="breadcrumb-item active">--}}
{{--                                Proveedores--}}
{{--                            </li>--}}
{{--                        </ol>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="mb-2" style="border-bottom: 3px solid #d7d4d4; top:-2rem"></div>
@endsection
@section('content')

@if (session('lines'))
<div class="row">
    <div class="col-12">
        <div class="card p-2">
            <import-error-data-provider
            :lines="{{session('lines')}}"
            ></import-error-data-provider>
        </div>
    </div>
</div>
@endif

<!--=====================================
    MODAL PARA IMPORTAR PROVEEDOR
======================================-->
<div class="modal fade text-left modal-primary" id="modal-import-provider" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel160">Importar Proveedores</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('import.data.providers') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <h6 class="text-center">Selecciona desde tu computadora el archivo Excel tipo
                        <strong>xlsx</strong></h6>
                    <input type="file" name="archive" class="form-control text-center" required
                            accept=".xls,.xlsx">
                    <div class="text-center pt-1"><a href="/import-excel-provider/caproin-import-provider.xlsx"
                                                        target="_blank">Descarga el ejemplo</a></div>
                    <div class="collapse-default pt-1">
                        <div class="card collapse-icon">
                            <div id="headingCollapse1" class="card-header" data-toggle="collapse" role="button"
                                    data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                <span class="lead collapse-title">Instrucciones</span>
                            </div>
                            <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1"
                                    class="collapse">
                                <div class="card-body">
                                    <p class="card-text text-justify">
                                        Para importar proveedores debe cargar un archivo Excel en formato
                                        <code>xlsx</code>. Tenga en cuenta que el
                                        <code>correo electrónico</code> y el
                                        <code>número de identificación</code> es único, asi que
                                        verifique que en su archivo de excel no existan correos
                                        electrónicos o números de identificación iguales.
                                    </p>
                                    <p class="card-text text-justify">
                                        Para el <code>tipo de idenficación</code> o <code>tipo de proveedor</code> debe ingresar un
                                        número como se muestra en el archivo excel de ejemplo. A
                                        continuación la tabla con el nombre del tipo de identificación
                                        y el número.
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
                                                @foreach ($identificationTypes as $identificationType)
                                                <tr>
                                                    <td>{{ $identificationType->id }}</td>
                                                    <td>{{ $identificationType->name }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Número</th>
                                                <th>Tipo de proveedor</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($providerTypes as $providerType)
                                                <tr>
                                                    <td>{{ $providerType->id }}</td>
                                                    <td>{{ $providerType->name }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btn_importar" type="submit" type="button" class="btn btn-primary">Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                            <th style="max-width: 30% !important;">Código</th>
                            <th>Tipo</th>
                            <th>Tipo Identificación</th>
                            <th>Identificación</th>
                            <th>Nombre o razon social</th>
                            <th>Estado</th>
                            @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Gerencia')
                                <th>Acciones</th>
                            @endif
                        </tr>
                        <tr class="tr-filter-dekstop-provider">
                            <th></th>
                            <th style="max-width: 30% !important;"></th>
                            <th class="filter-2"></th>
                            <th class="filter-3"></th>
                            <th></th>
                            <th></th>
                            <th class="filter-6"></th>
                            @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Gerencia')
                                <th></th>
                            @endif
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--=====================================
		    MODAL PARA CREAR UN NUEVO PROVEEDOR
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-new-provider" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <create-provider></create-provider>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA VER EL DETALLE DEL PROVEEDOR
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-show-provider" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <show-provider id="componet-show-provider" id-provider></show-provider>
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
                        this.api().columns([2, 3]).every(function () {
                            var column = this;
                            console.log('columna', column[0][0])
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-clients .filter-' + column[0][0])
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
                            console.log(column)
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-clients .filter-' + column[0][0])
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
                                console.log('IMPRESION', d, j)
                                const state = (d == 1 ? 'Activo' : 'Inactivo');
                                select.append('<option value="' + state + '">' + state + '</option>')
                            });
                        });

                        this.api().columns([2, 3]).every(function () {
                            var column = this;
                            console.log('columna', column[0][0])
                            var select = $(`<select class="form-control filter-select-movil-provider mb-1"><option hidden selected>Filtrar por ${column[0][0] == 2 ? 'Tipo' : 'Tipo de Identificación'}</option><option value="">Mostrar todos los registros</option></select>`)
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
                                console.log('IMPRESION', d, j)
                                const state = (d == 1 ? 'Activo' : 'Inactivo');
                                select.append('<option value="' + state + '">' + state + '</option>')
                            });
                        });
                    },

                    "processing": true,
                    // "serverSide": true,
                    // "deferLoading": 57,
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
                    "drawCallback": function (settings) {
                        feather.replace();
                    },
                    // "columnDefs": [
                    //     {"width": '10%', targets: 0},
                    // ],
                    // "order": [[1, 'asc']],

                    "ajax": {
                        url: "{{route('api.all.providers')}}",
                    },
                    "columns": [
                        {"data": "id"},
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.code === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.code}</span>`;
                                }
                            },
                        },

                        {
                            data: "type_providers.name",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.type_providers.name === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.type_providers.name}</span>`;
                                }
                            },
                        },

                        {
                            data: "users.identification_type.name",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.users.identification_type === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.users.identification_type.name}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.users.identification === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.users.identification}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.business_name === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.business_name}</span>`;
                                }
                            },
                        },

                        {
                            data: "state",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.state === "1") {
                                    return '<div class="badge badge-pill badge-light-success mr-1">Activo</div>'
                                } else {
                                    return `<div class="badge badge-pill badge-light-danger mr-1">Inactivo</div>`;
                                }
                            },
                        },
                            @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Gerencia')

                        {
                            render: function (data, type, JsonResultRow, meta) {
                                return `<div data-id="${JsonResultRow.id}" class="demo-inline-spacing text-center">
                                    <button data-id="${JsonResultRow.id}" data-target="#modal-show-provider" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-provider btn-icon btn-primary">
                                    <i data-id="${JsonResultRow.id}" data-feather="edit-2"></i><span class="mt-2">Ver</span>
                                    </button>
                                </div>`

                            },
                        },
                        @endif
                    ],
                    "language": {
                        "processing": '<div class="spinner"></div>',
                        "sLengthMenu": "Mostrar _MENU_ Registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": 'No hay datos',
                        "sInfo": "Mostrando  _END_ de _TOTAL_ registros",
                        "sInfoEmpty": '<div class="spinner"></div><p style="margin-left: 1rem; padding-bottom: 1.5rem">Cargando datos...</p>',
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
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
                                    "exportOptions": {columns: [1, 2, 3, 4, 5, 6]},
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
                                    "exportOptions": {columns: [1, 2, 3, 4, 5, 6]}
                                },
                                {
                                    "extend": 'excel',
                                    "text": feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [1, 2, 3, 4, 5, 6]}
                                },
                                {
                                    "extend": 'pdf',
                                    "text": feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [1, 2, 3, 4, 5, 6]},
                                    "orientation": 'vertical',
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
                                    "exportOptions": {columns: [1, 2, 3, 4, 5, 6]}
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
                        @if(auth()->user()->roles->first()->name === 'Administrador')
                        {
                            text: feather.icons['file-text'].toSvg({
                                class: 'mr-50 font-small-4'
                            }) + 'Importar',
                            className: 'btn btn-primary',
                            attr: {
                                'data-target': '#modal-import-provider',
                                'data-toggle': 'modal'
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        @endif
                            @if(auth()->user()->roles->first()->name === 'Administrador')

                        {
                            text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nuevo Proveedor',
                            className: 'create-new btn btn-primary',
                            attr: {
                                'data-target': '#modal-new-provider',
                                'data-toggle': 'modal',
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                        @endif
                    ],

                })
                let text = 'Todos los proveedores registrados'
                $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                table.on('order.dt search.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }, 5);


            $('.datatables-all-clients').on('click', '.btn-show-provider', function (e) {
                const id = e.target.getAttribute('data-id');
                //console.log(e.target);
                //console.log(id);
                //var dataTableProvider = table.row($(this).parents('tr')).data();
                //console.log(dataTableProvider.id);
                $('#traerDatosBoton').val(id).click();
                // window.idProvider = dataTableProvider.id
                // localStorage.setItem('idProvider', dataTableProvider.id)
            });
        });

    </script>
@endpush

