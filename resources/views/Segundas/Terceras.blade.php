@extends('layouts.app', ['pageSlug' => 'Terceras', 'titlePage' => __('Terceras')])

@section('content')
    <!--TialwindCSS-->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h1 class="card-title">{{ __('Terceras') }}</h1>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h3 class="card-title">{{ __('Detalle terceras.') }}</h3>
                                </div>
                                <br>
                                <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                                    <div class="card card-stats">
                                        <div class="card-header card-header-danger card-header-icon">
                                            <div class="card-icon">
                                                <span class="material-symbols-outlined">
                                                    calendar_month
                                                </span>
                                            </div>
                                            <h3 class="card-title">Fecha
                                                <br>
                                                <small id="Fecha"> </small>
                                            </h3>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
