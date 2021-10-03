@extends('layouts.app')
@section('title', 'Reportes')
@push('css')
<style>
    .app-content.content {
        background-color: #fbfbfb;
    }
</style>
@endpush
@section('content')
<!--=====================================
		    MODAL PARA VER EL DETALLE DE LA ORDEN DE COMPRA
======================================-->
<div class="modal fade text-left modal-primary" id="modal-show-purchase-order" data-backdrop="static" tabindex="-1"
  role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <show-purchase-order></show-purchase-order>
    </div>
</div>

<reports-logistic-component></reports-logistic-component>
@endsection
