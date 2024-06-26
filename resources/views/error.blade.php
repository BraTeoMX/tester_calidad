@extends('layouts.app', ['pageSlug' => 'error', 'titlePage' => __('Error')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="card-title">{{ __('Error') }}</h1>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p style="font-size: 1.5em; font-weight: bold;">{{ __('Lo sentimos, ha ocurrido un error.') }}</p>
                        <p style="font-size: 1.5em; font-weight: bold;">{{ __('Revisa tus permisos con el administrador.') }}</p>
                        <div class="iframe-container d-none d-lg-block">
                            <!-- Ajustes de estilo para la imagen -->
                            <img src="{{ asset('/material/img/error.jpg') }}" alt="{{ __('Imagen de error') }}" style="box-shadow: 15px 14px 18px rgba(0,0,0,0.5); margin: auto; display: block; border-radius: 10px; width: 90%;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
