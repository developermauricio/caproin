@php
$customScript = 1
@endphp
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
<reports-logistic-component></reports-logistic-component>
@endsection
@push('js')
<script src="{{ asset('js/reportes.js') }}"></script>
@endpush
