@php
    $rol = auth()->user()->roles->first()->name;
    if (
        $rol === 'Administrador' ||
        $rol === 'Asistente Sucursal' ||
        $rol === 'Gerencia'
    ){
        $title = 'Pedido';
        $titleL = 'pedido';
    } else {
        $title = "Compra";
        $titleL = "compra";
    }
@endphp

@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@section('title', 'Ordenes de '.$titleL)
@section('header_page')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12">
        <div class="row">
            <div class="col-12">
                <h2 class="float-left mb-0 pb-1 border-title">Administrar Ordenes de {{$title}}</h2>
{{--                <div class="breadcrumb-wrapper">--}}
{{--                    <ol class="breadcrumb">--}}
{{--                        <li class="breadcrumb-item active">--}}
{{--                            Orden de {{$title}}--}}
{{--                        </li>--}}
{{--                    </ol>--}}
{{--                </div>--}}
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
            <import-error-data-purchase-order
            :lines="{{session('lines')}}"
            ></import-error-data-purchase-order>
        </div>
    </div>
</div>
@endif

<section id="basic-datatable">
    <div class="row">
        <div class="col-12">
            <div class="card p-2">
                <table class="datatables-all-purchase-ordes hover datatablescreategica datatables-basic table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Numero de {{$title}}</th>
                            <th style="max-width: 30% !important;">Cliente</th>
                            <th>Fecha Requerida</th>
                            <th>Estado de {{$title}}</th>
                            <th>Descripcion</th>
                            <th>Valor total</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!--=====================================
		    MODAL PARA CREAR UN ORDENES DE COMPRA
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-new-purchase-order" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <create-purchase-order></create-purchase-order>
        </div>
    </div>

    <!--=====================================
		    MODAL PARA VER EL DETALLE DE LA ORDEN DE COMPRA
        ======================================-->
    <div class="modal fade text-left modal-primary" id="modal-show-purchase-order" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <show-purchase-order ></show-purchase-order>
        </div>
    </div>

    <div class="modal fade text-left modal-primary" id="modal-trace-purchase-order" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <modal-tracer-purchase-order ></modal-tracer-purchase-order>
        </div>
    </div>


    <!--=====================================
                MODAL PARA IMPORTAR ORDENES
            ======================================-->
        <div class="modal fade text-left modal-primary" id="modal-import-purchase-order" data-backdrop="static" tabindex="-1"
             role="dialog" aria-labelledby="myModalLabel161" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel161">Importar Ordenes</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('import.data.purchase_ordes') }}" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <h6 class="text-center">Selecciona desde tu computadora el archivo Excel tipo
                                <strong>xlsx</strong></h6>
                            <input type="file" name="archive" class="form-control text-center" required
                                   accept=".xls,.xlsx">
                            <div class="text-center pt-1">
                            <a
                                href="/import-excel/import-purchase-orders.xlsx"
                                target="_blank"
                            >Descarga el ejemplo</a>
                            </div>
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
                                                Para importar ordenes de compra se debe cargar un archivo Excel en formato
                                                <code>xlsx</code>.
                                                <br><br>
                                                Tenga en cuenta que el
                                                <code>Tipo de orden</code>,
                                                <code>Zona</code>,
                                                <code>Transportista</code>,
                                                <code>Tipo Pago</code>, y el
                                                <code>Tipo de Moneda</code> debe ingresarse un numero que se encuentra en cada tabla.
                                            </p>
                                        </div>
                                    </div>
                                </div>


                                <div class="card collapse-icon">
                                    <div id="headingCollapse2" class="card-header" data-toggle="collapse" role="button"
                                         data-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                                        <span class="lead collapse-title">Tipos de ordenes</span>
                                    </div>
                                    <div id="collapse2" role="tabpanel" aria-labelledby="headingCollapse2"
                                         class="collapse">
                                        <div class="card-body">
                                            <div class="pb-1 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Nombre tipo orden</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($order_types as $order_type)
                                                        <tr>
                                                            <td>{{ $order_type->id }}</td>
                                                            <td>{{ $order_type->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card collapse-icon">
                                    <div id="headingCollapse3" class="card-header" data-toggle="collapse" role="button"
                                         data-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                                        <span class="lead collapse-title">Zonas</span>
                                    </div>
                                    <div id="collapse3" role="tabpanel" aria-labelledby="headingCollapse3"
                                         class="collapse">
                                        <div class="card-body">
                                            <div class="pb-1 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Nombre de las Zonas</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($zones as $zone)
                                                        <tr>
                                                            <td>{{ $zone->id }}</td>
                                                            <td>{{ $zone->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card collapse-icon">
                                    <div id="headingCollapse4" class="card-header" data-toggle="collapse" role="button"
                                         data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                        <span class="lead collapse-title">Transportistas</span>
                                    </div>
                                    <div id="collapse4" role="tabpanel" aria-labelledby="headingCollapse4"
                                         class="collapse">
                                        <div class="card-body">
                                            <div class="pb-1 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Nombre Transportista</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($conveyors as $conveyor)
                                                        <tr>
                                                            <td>{{ $conveyor->id }}</td>
                                                            <td>{{ $conveyor->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card collapse-icon">
                                    <div id="headingCollapse5" class="card-header" data-toggle="collapse" role="button"
                                         data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                        <span class="lead collapse-title">Tipos de pago</span>
                                    </div>
                                    <div id="collapse5" role="tabpanel" aria-labelledby="headingCollapse5"
                                         class="collapse">
                                        <div class="card-body">
                                            <div class="pb-1 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Nombre tipo pago</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($payment_types as $payment_type)
                                                        <tr>
                                                            <td>{{ $payment_type->id }}</td>
                                                            <td>{{ $payment_type->name }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card collapse-icon">
                                    <div id="headingCollapse6" class="card-header" data-toggle="collapse" role="button"
                                         data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                        <span class="lead collapse-title">Tipos de moneda</span>
                                    </div>
                                    <div id="collapse6" role="tabpanel" aria-labelledby="headingCollapse6"
                                         class="collapse">
                                        <div class="card-body">
                                            <div class="pb-1 table-responsive">
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>Número</th>
                                                        <th>Nombre Moneda</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($currencies as $currency)
                                                        <tr>
                                                            <td>{{ $currency->id }}</td>
                                                            <td>{{ $currency->code }}</td>
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

</section>
@endsection
@push('js')
<script src="/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js"></script>
<script src="/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
<script src="/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js"></script>
{{-- <script src="/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js"></script>--}}
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
const getRow = function (json, attribute) {
  const data = json[attribute];
  if (!data) {
    return '<span class="label label-danger text-center" style="color:#D9393D !important">Ningún valor por defecto</span>'
  } else {
    return `<span class="label text-center font-weight-bold">${data}</span>`;
  }
}

const initTable = function (urlListPurchaseOrders, urlCreate) {
  const table = $('.datatables-all-purchase-ordes').DataTable({
    "ordering": true,
    "orderCellsTop": true,
    "fixedHeader": true,
    "initComplete": function () { },
    "processing": true,
    "lengthMenu": [7, 10, 25, 50, 75, 100],
    "responsive": true,
    "pageLength": 10,
    "serverSide": true,
    "autoWidth": false,
    "columnDefs": [{
      "defaultContent": "-",
      "targets": "_all"
    }],
    "drawCallback": function (settings) {
      feather.replace();
    },
    "ajax": {
      url: urlListPurchaseOrders,
    },
    "columns": [
      {
        "data": "id"
      },
      {
        "data": "customer_order_number",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'customer_order_number');
        },
      },
      {
        data: "customer_id",
        render: function (data, type, JsonResultRow, meta) {
          let value = "Sin valor";
          if (JsonResultRow.customer) {
            value = JsonResultRow.customer.business_name
          }
          return value;
        },
      },
      {
        data: "delivery_date_required_customer",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'delivery_date_required_customer');
        },
      },
      {
        render: (data, type, JsonResultRow, meta) => {
          const statuses = JsonResultRow.purchase_order_state_histories;
          const status = statuses[statuses.length - 1];
          if (status) {
            return status.state_order.name;
          }
          return 'Sin valor'
        }
      },
      {
        data: "description",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'description');
        },
      },
      {
        data: "total_value",
        render: function (data, type, JsonResultRow, meta) {
          const value = JsonResultRow.total_value || 0;
          return `$${value.toLocaleString()}`;
        }
      },
      {
        render: function (data, type, JsonResultRow, meta) {
          return `
          <div class="d-flex" data-id="${JsonResultRow.id}">
            <button data-target="#modal-show-purchase-order" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-purchase btn-icon btn-primary">
              <i data-feather="eye"></i>
            </button>

            <button data-target="#modal-trace-purchase-order" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="ml-1 btn btn-trace-purchase btn-icon btn-primary">
              <i data-feather="activity"></i><span class="mt-2">Ver</span>
            </button>
          </div>`
        },
      }
    ],
    "language": languageDataTable,
    "dom": `<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>`,
    "buttons": [{
      "extend": 'collection',
      "className": 'btn btn-outline-secondary theme-light dropdown-toggle mr-2',
      "text": feather.icons['share'].toSvg({
        class: 'font-small-4 mr-50'
      }) + 'Exportar',
      "buttons": [
        {
          "extend": 'print',
          "text": feather.icons['printer'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Imprimir',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          },
          "customize": function (win) {
            $(win.document.body)
              .css('font-size', '10pt')
            $(win.document.body).find('table')
              .addClass('compact')
              .css('font-size', 'inherit');
          }
        },
        {
          "extend": 'csv',
          "text": feather.icons['file-text'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Csv',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          "extend": 'excel',
          "text": feather.icons['file'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Excel',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          "extend": 'pdf',
          "text": feather.icons['clipboard'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Pdf',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          },
          "orientation": 'vertical',
        },
        {
          "extend": 'copy',
          "text": feather.icons['copy'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Copiar',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ],
    },
    @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Asistente Sucursal')
    {
        text: feather.icons['file-text'].toSvg({
            class: 'mr-50 font-small-4'
        }) + 'Importar'
        , className: 'btn btn-primary'
        , attr: {
            'data-target': '#modal-import-purchase-order'
            , 'data-toggle': 'modal'
            ,
        }
        , init: function (api, node, config) {
            $(node).removeClass('btn-secondary');
        }
    },
    @endif
    @if(auth()->user()->roles->first()->name === 'Administrador' || auth()->user()->roles->first()->name === 'Asistente Sucursal')
    {
      text: feather.icons['plus'].toSvg({
        class: 'mr-50 font-small-4'
      }) + 'Nueva orden de {{$titleL}}',
      className: 'create-new btn btn-primary',
      attr: {
        'id': "create-new-purchase-order",
      },
      init: function (api, node, config) {
        $(node).click(() => {
          window.location = urlCreate
        });
      }
    }
    @endif
    ],
  });

  let text = 'Ordenes de {{$titleL}} registrados'
  $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);

  table.on('order.dt search.dt', function () {
    table.column(0, {
      search: 'applied',
      order: 'applied'
    }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();

  const getAttribute = (element, nameAttribute) => {
    const id = element.attr(nameAttribute);
    if (id) {
      return id;
    }
    return getAttribute(element.parent(), nameAttribute);
  }

  $('.datatables-all-purchase-ordes').on('click', '.btn-show-purchase', function (e) {
    const id = getAttribute($(this), 'data-id');
    $('#idPurchaseOrder').val(id).click();
  });

  $('.datatables-all-purchase-ordes').on('click', '.btn-trace-purchase', function (e) {
    const id = getAttribute($(this), 'data-id');
    $('#idPurchaseOrderTrace').val(id).click();
  });
}
</script>
<script>
$(function () {
  initTable(`{{route('api.all.purchase-orders')}}`, `{{route('admin.purchase_order.create')}}`);
});
</script>
@endpush
