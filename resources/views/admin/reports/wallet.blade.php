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
<reports-wallet-component></reports-wallet-component>
@endsection
