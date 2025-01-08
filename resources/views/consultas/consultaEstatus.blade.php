@extends('layouts.app', ['pageSlug' => 'consultaEstatus'])

@section('content') 
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center">Corte - Etiqueta - Screen</h2>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-4">
            <div class="card card-body">
                <h2>Corte</h2>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <h2>Etiqueta</h2>
            </div>
        </div>
        <div class="col-4">
            <div class="card card-body">
                <h2>Screen</h2>
            </div>
        </div>
    </div>
@endsection


@push('js')

@endpush