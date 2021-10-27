@extends('layouts.app')
@section('title', 'Zonas')
@section('header_page')
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12">
            <div class="row">
                <div class="col-12">
                    <h2 class="pb-1 border-title float-left mb-0">Administrar Zonas</h2>
{{--                    <div class="breadcrumb-wrapper">--}}
{{--                        <ol class="breadcrumb">--}}
{{--                                                        <li class="breadcrumb-item"><a href="index.html">Home</a>--}}
{{--                                                        </li>--}}
{{--                                                        <li class="breadcrumb-item"><a href="#">Layouts</a>--}}
{{--                                                        </li>--}}
{{--                            <li class="breadcrumb-item active">--}}
{{--                                Zonas--}}
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
    <component-zones></component-zones>
@endsection
