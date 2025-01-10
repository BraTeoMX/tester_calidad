@extends('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')])

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    <div class="row">
        <div class="card card-chart">
            <div class="card-header">
                <h3>Etiquetas</h3>
            </div>
            <div class="card-body">
                <h2>Etiquetas</h2>
            </div>
        </div>
    </div>

@endsection

@push('js')
    
    
@endpush
