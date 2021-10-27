@extends('layouts.app')
@section('title', 'Sucursales')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">Administrar Sucursales</h2>
                    <div class="breadcrumb-wrapper">
                        <ol class="breadcrumb">
                            {{--                            <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
                            {{--                            </li>--}}
                            {{--                            <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
                            {{--                            </li>--}}
                            <li class="breadcrumb-item active">
                                Sucursales
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('content')
        <branch-office></branch-office>
@endsection
