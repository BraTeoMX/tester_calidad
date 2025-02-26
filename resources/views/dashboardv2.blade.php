@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')])

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Auditoria AQL por día</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalAQL">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td id="generalAQLPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
                                    <td id="generalAQLPlanta2">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Proceso por dia</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalProceso">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td id="generalProcesoPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
                                    <td id="generalProcesoPlanta2">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('api.porcentajesPorDiaV2') }}",
                type: "GET",
                success: function(data) {
                    $("#generalAQL").text(data.generalAQL + "%");
                    $("#generalAQLPlanta1").text(data.generalAQLPlanta1 + "%");
                    $("#generalAQLPlanta2").text(data.generalAQLPlanta2 + "%");
                    $("#generalProceso").text(data.generalProceso + "%");
                    $("#generalProcesoPlanta1").text(data.generalProcesoPlanta1 + "%");
                    $("#generalProcesoPlanta2").text(data.generalProcesoPlanta2 + "%");
                }
            });
        });
    </script>
@endpush
