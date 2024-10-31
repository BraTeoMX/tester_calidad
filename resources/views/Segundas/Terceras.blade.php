@extends('layouts.app', ['pageSlug' => 'Terceras', 'titlePage' => __('Terceras')])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <h1 class="card-title">{{ __('Terceras') }}</h1>
            <div class="row">
                <div class="col-lg-auto col-md-auto col-sm-auto mx-auto">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">
                                    calendar_month
                                </span>
                            </div>
                            <h3 class="card-title">Fecha</h3>
                            <br>
                            <input id="datepicker" class="col-lg-auto col-md-auto col-sm-auto mx-auto" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                    <div class="card card-stats">
                        <div class="card-header card-header-success card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">
                                    location_away
                                </span>
                            </div>
                            <h3 class="card-title">Clientes
                                <br>
                                <small id="Clientes"> </small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                    <div class="card card-stats">
                        <div class="card-header card-header-warning card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">
                                    view_module
                                </span>
                            </div>
                            <h3 class="card-title">Modulo
                                <br>
                                <small id="porcentajeDefectos"> </small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                    <div class="card card-stats">
                        <div class="card-header card-header-danger card-header-icon">
                            <div class="card-icon">
                                <span class="material-symbols-outlined">
                                    source_environment
                                </span>
                            </div>
                            <h3 class="card-title">Planta
                                <br>
                                <small id="Planta"> </small>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---DatePicker--->
    <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <script>
        $('#datepicker').datepicker({
            uiLibrary: 'bootstrap5',
            modal: true,
            footer: true
        });
    </script>
@endsection
