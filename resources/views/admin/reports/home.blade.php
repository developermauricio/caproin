@php
$customScript = 1
@endphp
@extends('layouts.app')
@section('title', 'Reportes')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Reportes</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">
                                Reportes
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <reports-component></reports-component>
@endsection
@push('js')
    <script src="{{ asset('js/reportes.js') }}"></script>
@endpush
