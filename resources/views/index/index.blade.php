@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div id="app">
                <div class="row">
                @foreach($countries as $val)
                <div class="col-4">
                    <div class="card" >

                        <div class="card-body">
                            <h5 class="card-title">{{ $val }}</h5>
                            <p class="card-text"></p>
                            <dashboard-order-index country="{{ $val }}"></dashboard-order-index>
                        </div>
                    </div>
                </div>
                @endforeach
                </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="{{ mix('js/app.js') }}"></script>
@stop
