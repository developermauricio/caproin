@php
$customScript = 1
@endphp
@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
<reports-logistic-component></reports-logistic-component>
@endsection
@push('js')
<script src="{{ asset('js/reportes.js') }}"></script>
@endpush
