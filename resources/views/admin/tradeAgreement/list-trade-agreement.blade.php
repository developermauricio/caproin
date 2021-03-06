@extends('layouts.app')
@push('css')
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/file-uploaders/dropzone.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugins/forms/form-file-uploader.css">
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/datetime/1.1.0/css/dataTables.dateTime.min.css">
@endpush
@section('title', 'Acuerdos Comerciales')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12">
            <div class="row">
                <div class="col-12">
                    <h2 class="pb-1 border-title float-left mb-0">Administrar Acuerdos Comerciales</h2>
{{--                    <div class="breadcrumb-wrapper">--}}
{{--                        <ol class="breadcrumb">--}}
{{--                            --}}{{-- <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
{{--                            --}}{{-- </li>--}}
{{--                            --}}{{-- <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
{{--                            --}}{{-- </li>--}}
{{--                            <li class="breadcrumb-item active">--}}
{{--                                Acuerdos Comerciales--}}
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
@if(session('lines'))
<div class="row">
    <div class="col-12">
        <div class="card p-2">
            <import-error-data-trade
            :lines="{{ session('lines') }}"
            ></import-error-data-trade>
        </div>
    </div>
</div>
@endif

<!--=====================================
    MODAL PARA IMPORTAR ACUERDOS COMERCIALES
======================================-->
<div class="modal fade text-left modal-primary" id="modal-import-trade" data-backdrop="static" tabindex="-1"
        role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel160">Importar Acuerdos Comerciales</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('import.data.trades') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
                    <h6 class="text-center">Selecciona desde tu computadora el archivo Excel tipo
                        <strong>xlsx</strong></h6>
                    <input type="file" name="archive" class="form-control text-center" required
                            accept=".xls,.xlsx">
                    <div class="text-center pt-1"><a href="/import-excel-trades/caproin-import-trade.xlsx"
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
                                        Para importar acuerdos comerciales debe cargar un archivo Excel en formato
                                        <code>xlsx</code>.
                                        En el campo de <code>Cliente</code> se puede ingresar el correo electronico de la persona
                                    </p>
                                    <p class="card-text text-justify">
                                        Para  <code>Moneda</code> o <code>Estado</code> debe ingresar un
                                        n??mero como se muestra en el archivo excel de ejemplo. A
                                        continuaci??n la tabla con el nombre y el n??mero a cual corresponde.
                                    </p>
                                    <div class="mb-2 table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>N??mero</th>
                                                <th>Moneda</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($currencies as $currency)
                                                <tr>
                                                    <td>{{ $currency->id }}</td>
                                                    <td>{{ $currency->code }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>N??mero</th>
                                                <th>Estado</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>VIGENTE</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>FINALIZADO</td>
                                                </tr>
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

    <section id="basic-datatable-trade-agreement">
        <div class="row">
            <div class="col-12">
                <div class="card p-2">
                    <table
                        class="datatables-all-tradeagreement hover datatablescreategica table-responsive datatables-basic table table-striped"
                        style="width:100%">
                        <thead>
                        <tr>
                            <th style="max-width: 30% !important;">Consecutivo de Oferta</th>
                            <th>Versi??n</th>
                            <th>Cliente</th>
                            <th>Descripci??n Corta</th>
                            <th>Estado</th>
                            <th>TRM</th>
                            <th>Moneda</th>
                            <th>Fecha de Creaci??n</th>
                            <th>Vigencia</th>
                            <th>Acciones</th>
                        </tr>
                        <tr class="tr-filter-dekstop-provider">
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th class="filter-4" style="max-width: 30% !important;"></th>
                            <th></th>
                            <th class="filter-6"></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!--=====================================
		    MODAL PARA CREAR UN NUEVO ACUERDO COMERCIAL
        ======================================-->
        <div class="modal fullscreen-modal fade text-left modal-primary" id="modal-new-tradeagreement"
             data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg " role="document">
                <create-trade-agreement></create-trade-agreement>
            </div>
        </div>

        <!--=====================================
		    MODAL PARA VER EL DETALLE DEL ACUERDO COMERCIAL
        ======================================-->
        <div class="modal fullscreen-modal fade text-left modal-primary" id="modal-show-trade-agreement"
             data-backdrop="static" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel160" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <show-edit-trade-agreement id="componet-show-trade-agreement"
                                           id-trade-agreement></show-edit-trade-agreement>
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
            let dollarUSLocale = Intl.NumberFormat('es-US');
            setTimeout(() => {
                table = $('.datatables-all-tradeagreement').DataTable({
                    "ordering": true,
                    "orderCellsTop": true,
                    "fixedHeader": true,
                    "initComplete": function () {
                        this.api().columns([4]).every(function () {
                            var column = this;
                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-tradeagreement .filter-' + column[0][0])
                                .on('change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val(),
                                    );
                                    column
                                        .search(val ? '^' + val + '$' : '', true, false)
                                        .draw();
                                });

                            column.data().unique().sort().each(function (d, j) {
                                const state = (d == "1" ? 'Vigente' : 'Finalizado');
                                select.append('<option value="' + state + '">' + state + '</option>')
                            });
                        });

                        this.api().columns([6]).every(function () {
                            var column = this;

                            var select = $('<select class="form-control"><option hidden selected>Filtrar</option><option value="">Mostrar todos los registros</option></select>')
                                .appendTo('.datatables-all-tradeagreement .filter-' + column[0][0])
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
                            var select = $(`<select class="form-control filter-select-movil-provider mb-1"><option hidden selected>Filtrar por tipo de identificaci??n</option><option value="">Mostrar todos los registros</option></select>`)
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
                        url: "{{route('api.all.trade.agreemente')}}",
                    },
                    "columns": [
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.consecutive_Offer === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.consecutive_Offer}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.version === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.version}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.customer === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.customer.business_name}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.short_description === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.short_description}</span>`;
                                }
                            },
                        },
                        {
                            data: "state",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.state === '1') {
                                    return '<div class="badge badge-pill badge-light-success mr-1">Vigente</div>'
                                } else {
                                    return `<div class="badge badge-pill badge-light-danger mr-1">Finalizado</div>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.TRM === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">$${dollarUSLocale.format(JsonResultRow.TRM)}</span>`;
                                }
                            },
                        },
                        {
                            data: "currency.code",
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.currency === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${JsonResultRow.currency.code}</span>`;
                                }
                            },
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.creation_date === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${moment(JsonResultRow.creation_date).locale('es').format("MM-DD-YYYY")}</span>`;
                                }
                            }
                            ,
                        },
                        {
                            render: function (data, type, JsonResultRow, meta) {
                                if (JsonResultRow.final_date === null) {
                                    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ning??n valor por defecto</span>'
                                } else {
                                    return `<span class="label text-center font-weight-bold">${moment(JsonResultRow.final_date).locale('es').format("MM-DD-YYYY")}</span>`;
                                }
                            }
                            ,
                        },

                        {
                            render: function (data, type, JsonResultRow, meta) {
                                return '<div class="demo-inline-spacing text-center"><button data-target="#modal-show-trade-agreement" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="M??s Informaci??n" type="button" class="btn btn-show-user btn-icon btn-primary"><i data-feather="eye"></i><span class="mt-2">Ver</span></button></div>'

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
                            "sLast": "??ltimo",
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
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6, 7]},
                                    "orientation": 'landscape',
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
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
                                },
                                {
                                    "extend": 'excel',
                                    "text": feather.icons['file'].toSvg({class: 'font-small-4 mr-50'}) + 'Excel',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6, 7]},

                                },
                                {
                                    "extend": 'pdf',
                                    "text": feather.icons['clipboard'].toSvg({class: 'font-small-4 mr-50'}) + 'Pdf',
                                    "className": 'dropdown-item',
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6, 7]},
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
                                    "exportOptions": {columns: [0, 1, 2, 3, 4, 5, 6, 7]}
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
                                'data-target': '#modal-import-trade',
                                'data-toggle': 'modal'
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        },
                        @endif
                        @if(auth()->user()->roles->first()->name === 'Administrador')
                        {
                            text: feather.icons['plus'].toSvg({class: 'mr-50 font-small-4'}) + 'Nuevo Acuerdo Comercial',
                            className: 'create-new btn btn-primary',
                            attr: {
                                'data-target': '#modal-new-tradeagreement',
                                'data-toggle': 'modal',
                            },
                            init: function (api, node, config) {
                                $(node).removeClass('btn-secondary');
                            }
                        }
                        @endif
                    ],

                })
                let text = 'Todos los acuerdos comerciales'
                $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
                // table.on('order.dt search.dt', function () {
                //     table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                //         cell.innerHTML = i + 1;
                //     });
                // }).draw();
            }, 5);


            $('.datatables-all-tradeagreement').on('click', '.btn-show-user', function (e) {
                var dataTableTrade = table.row($(this).parents('tr')).data();
                console.log(dataTableTrade.id);
                $('#traerDatosBotonTradeAgreement').val(dataTableTrade.id).click();
                $('#componet-show-trade-agreement').attr("id-trade-agreement", dataTableTrade.id)
            });
        });

    </script>
@endpush
