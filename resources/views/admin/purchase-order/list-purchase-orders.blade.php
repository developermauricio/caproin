@extends('layouts.app')
@push('css')
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css">
@endpush
@section('title', 'Lista de ordenes de compra')
@section('header_page')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Lista de las ordenes de compra</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">
                            Ordenes de compra
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
                <table class="datatables-all-purchase-ordes hover datatablescreategica datatables-basic table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Numero de pedido</th>
                            <th style="max-width: 30% !important;">Cliente</th>
                            <th>Fecha Requerida</th>
                            <th>Estado del pedido</th>
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
<script src="{{asset('js/purchase-orders-table.js')}}"></script>
<script>
$(function () {
  initTable(`{{route('api.all.purchase-orders')}}`, `{{route('admin.purchase_order.create')}}`);
});
</script>
@endpush
