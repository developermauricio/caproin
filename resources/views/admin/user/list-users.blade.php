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
                    <h2 class="content-header-title float-left mb-0">Lista de Usuarios</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            {{--                            <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
                            {{--                            </li>--}}
                            <li class="breadcrumb-item active">
                                Usuarios
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
@if (session('lines'))
<div class="row">
    <div class="col-12">
        <div class="card p-2">
            <import-error-data-user :lines="{{session('lines')}}"></import-error-data-user>
        </div>
    </div>
</div>
@endif
    <section id="basic-datatable">
        @if(session()->has('success-import-data-customer'))
            <div class="demo-spacing-0 pb-2">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-body">
                        {{session('success-import-data-customer')}}
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card p-2">
                    <table
                        class="datatables-all-users hover datatablescreategica table-responsive datatables-basic table table-striped"
                        style="width:100%">
                        <thead>
                        <tr>
                            <th style="max-width: 30% !important;">#</th>
                            <th>Nombres y Apellidos</th>
                            <th>Correo Empresarial</th>
                            <th>Teléfono</th>
                            <th>Sucursal</th>
                            <th>Rol</th>
                            <th>Tipo de Usuario</th>
                            <th>Acciones</th>
                        </tr>
                        <tr class="tr-filter-dekstop-provider">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="filter-4" style="max-width: 30% !important;"></th>
                            <th class="filter-5"></th>
                            <th class="filter-6"></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--=====================================
		    MODAL PARA CREAR UN NUEVO USUARIOS
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-new-provider" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <create-users></create-users>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA VER EL DETALLE DEL CLIENTE
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-show-customer" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <show-user id="componet-show-user" id-user></show-user>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA IMPORTAR CLIENTES
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-import-customer" data-backdrop="static" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel160">Importar Usuarios</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('import.data.users') }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <h6 class="text-center">Selecciona desde tu computadora el archivo Excel tipo
                                <strong>xlsx</strong></h6>
                            <input type="file" name="archive" class="form-control text-center" required accept=".xls,.xlsx">
                            <div class="text-center pt-1"><a href="/import-excel-users/caproin-import-users.xlsx" target="_blank">Descarga el ejemplo</a></div>
                            <div class="collapse-default pt-1">
                                <div class="card collapse-icon">
                                    <div id="headingCollapse1" class="card-header" data-toggle="collapse" role="button" data-target="#collapse1" aria-expanded="false" aria-controls="collapse1">
                                        <span class="lead collapse-title">Instrucciones</span>
                                    </div>
                                    <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1" class="collapse">
                                        <div class="card-body">
                                            <p class="card-text text-justify">
                                                Para importar usuarios debe cargar un archivo Excel en formato
                                                <code>xlsx</code>. Tenga en cuenta que el
                                                <code>correo electrónico</code> es único, asi que
                                                verifique que en su archivo de excel no existan correos
                                                electrónicos iguales.
                                            </p>
                                            <div class="roles">
                                                <p class="card-text text-justify">
                                                    Para el <code>rol</code> debe ingresar un
                                                    número como se muestra en el archivo excel de ejemplo. A
                                                    continuación la tabla con el nombre del rol y el número.
                                                </p>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Número</th>
                                                                <th>rol</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($roles as $rol)
                                                            <tr>
                                                                <td>{{$rol->id}}</td>
                                                                <td>{{$rol->name}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="sucursales">
                                                <p class="card-text text-justify">
                                                    Para la <code>sucursal</code> debe ingresar un
                                                    número como se muestra en el archivo excel de ejemplo. A
                                                    continuación la tabla con el nombre de la sucursal y el número.
                                                </p>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Número</th>
                                                                <th>sucursal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($branchOffices as $branchOffice)
                                                            <tr>
                                                                <td>{{$branchOffice->id}}</td>
                                                                <td>{{$branchOffice->name}}</td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="user_types">
                                                <p class="card-text text-justify">
                                                    Para el <code>tipo de usuario</code> debe ingresar un
                                                    número como se muestra en el archivo excel de ejemplo. A
                                                    continuación la tabla con el nombre de tipo de usuario y el número.
                                                </p>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Número</th>
                                                                <th>sucursal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($employeeTypes as $employeType)
                                                            <tr>
                                                                <td>{{$employeType->id}}</td>
                                                                <td>{{$employeType->name}}</td>
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
                        </div>
                        <div class="modal-footer">
                            <button id="btn_importar" type="submit" type="button" class="btn btn-primary">Importar</button>
                        </div>
                    </form>
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
            let table = null;
            setTimeout(() => {
                table = $('.datatables-all-users').DataTable({
                    "ordering": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "initComplete": function () {
                        this.api().columns([4]).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-users .filter-' + column[0][0])
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

                        this.api().columns([5]).every(function () {
                            var column = this;

                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-users .filter-' + column[0][0])
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });
                            select.append('<option value="Administrador">Administrador</option>')
                            select.append('<option value="Asistente Sucursal">Asistente Sucursal</option>')
                            select.append('<option value="Gerencia">Gerencia</option>')
                            select.append('<option value="Vendedor">Vendedor</option>')
                            select.append('<option value="Logistica">Logistica</option>')
                            select.append('<option value="Finanzas">Finanzas</option>')

                            // column.data().unique().sort().each(function (d, j) {
                            //     console.log('DATOS', j)
                            //     select.append('<option value="' + d + '">' + d + '</option>')
                            // });
                        });
                        this.api().columns([6]).every(function () {
                            var column = this;

                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-users .filter-' + column[0][0])
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
                        // this.api().columns([6]).every(function () {
                        //     var column = this;
                        //     var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                        //         .appendTo('.datatables-all-clients .filter-' + column[0][0])
                        //         .on('change', function () {
                        //             var val = $.fn.dataTable.util.escapeRegex(
                        //                 $(this).val(),
                        //             );
                        //             column
                        //                 .search(val ? '^' + val + '$' : '', true, false)
                        //                 .draw();
                        //         });
                        //
                        //     column.data().unique().sort().each(function (d, j) {
                        //         const state = (d == 1 ? 'Activo' : 'Inactivo');
                        //         select.append('<option value="' + state + '">' + state + '</option>')
                        //     });
                        // });

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
                        this.api().columns([5]).every(function () {
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
                                console.log(d)
                                select.append('<option value="' + d + '">' + d + '</option>')
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
                        url: "{{route('api.all.users')}}",
                    },
                    "columns": [
                        {"data": "id"},
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.name === null || JsonResultRow.last_name === null ) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.name} ${JsonResultRow.last_name}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.email === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.email}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.phone === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.phone}</span>`;
                                }
                            },
                        },
                        {
                            data: "employes.branch_offices.name",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.employes === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.employes.branch_offices.name}</span>`;
                                }
                            },
                        },
                        {
                            // data: "roles",
                            // render: {
                            //     _: '[, ].name',
                            //     sp: '[].name'
                            // },
                            // searchPanes: {
                            //     orthogonal: 'sp'
                            // },
                            // data: "employes.roles", "render": function (data) {
                            //     console.log('ESTA DATA', data)
                            //     return '<pre>' + data + '</pre>';
                            // },
                            data: "roles",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.roles === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return JsonResultRow.roles.map(function (obj){
                                        return `<span class="label text-center font-weight-bold" style="margin-right: 0.5rem">${obj.name}</span>`
                                    }).join("-")
                                }
                            },
                        },

                        {
                            data: "employes.type_employe.name",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.employes === null) {
                                    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.employes.type_employe.name}</span>`;
                                }
                            },
                        },

                        // {
                        //     data: "user.state",
                        //     render: function (data, type, JsonResultRow, meta) {
                        //         if (JsonResultRow.user.state === "1") {
                        //             return '<div class="badge badge-pill badge-light-success mr-1">Activo</div>'
                        //         } else {
                        //             return `<div class="badge badge-pill badge-light-danger mr-1">Inactivo</div>`;
                        //         }
                        //     },
                        // },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                return '<div class="demo-inline-spacing text-center"><button data-target="#modal-show-customer" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-user btn-icon btn-primary"><i data-feather="edit-2"></i></button></div>'

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
                            text: feather.icons['file-text'].toSvg({class: 'mr-50 font-small-4'}) + 'Importar',
                            className: 'create-new btn btn-primary',
                            attr: {
                                'data-target': '#modal-import-customer',
                                'data-toggle': 'modal',
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        {
                            text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nuevo Usuario',
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
                let text = 'Todos los usuarios registrados'
                $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                table.on('order.dt search.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }, 5);


            $('.datatables-all-users').on('click', '.btn-show-user', function (e) {
                var dataTableUser = table.row($(this).parents('tr')).data();
                console.log(dataTableUser.id);
                $('#traerDatosBotonUser').val(dataTableUser.id).click();
                $('#componet-show-user').attr("id-user", dataTableUser.id)
            });
        });

    </script>
@endpush

