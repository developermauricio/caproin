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
@section('title', 'Transportadores')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Administrar Transportadores</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            {{--                            <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
                            {{--                            </li>--}}
                            <li class="breadcrumb-item active">
                                Transportadores
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
                            <th style="max-width: 30% !important;">Nombre</th>
                            <th>Descripción</th>
                            @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Gerencia')
                                <th>Acciones</th>
                            @endif
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--=====================================
		    MODAL PARA CREAR UN NUEVO TRANSPORTADOR
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-new-conveyor" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <create-conveyor></create-conveyor>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA VER EL DETALLE DEL TRANSPORTADOR
        ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-show-conveyor" data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <show-conveyor id="componet-show-conveyor" id-conveyor></show-conveyor>
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
                        url: "{{route('api.all.conveyors')}}",
                    },
                    "columns": [
                        {"data": "id"},
                        {
                            data: "name",
                            render: function (data, type, JsonResultRow, meta) {
                                return JsonResultRow.name || "Sin nombre"
                            },
                        },
                        {
                            data: "description",
                            render: function (data, type, JsonResultRow, meta) {
                                return JsonResultRow.description || "Sin descripción"
                            },
                        },
                            @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Gerencia')

                        {
                            render: function (data, type, JsonResultRow, meta) {
                                return `<div data-id="${JsonResultRow.id}" class="demo-inline-spacing text-center">
                                    <button data-id="${JsonResultRow.id}" data-target="#modal-show-conveyor" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-conveyor btn-icon btn-primary">
                                    <i data-id="${JsonResultRow.id}" data-feather="edit-2"></i><span class="mt-2 pl-1">Ver</span>
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
                            @if(auth()->user()->roles->first()->name === 'Administrador')

                        {
                            text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nuevo Transportador',
                            className: 'create-new btn btn-primary',
                            attr: {
                                'data-target': '#modal-new-conveyor',
                                'data-toggle': 'modal',
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                        @endif
                    ],

                })
                let text = 'Todos los transportadores registrados'
                $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                table.on('order.dt search.dt', function () {
                    table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            }, 5);


            $('.datatables-all-clients').on('click', '.btn-show-conveyor', function (e) {
                const id = e.target.getAttribute('data-id');
                //console.log(e.target);
                //console.log(id);
                //var dataTableConveyor = table.row($(this).parents('tr')).data();
                //console.log(dataTableConveyor.id);
                $('#traerDatosBoton').val(id).click();
                // window.idConveyor = dataTableConveyor.id
                // localStorage.setItem('idConveyor', dataTableConveyor.id)
            });
        });

    </script>
@endpush

