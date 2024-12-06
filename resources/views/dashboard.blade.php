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
                                    <td>{{ $generalAQL }}%</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td>{{ $generalAQLPlanta1 }}%</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
                                    <td>{{ $generalAQLPlanta2 }}%</td>
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
                                    <td>{{ $generalProceso }}%</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td>{{ $generalProcesoPlanta1 }}%</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
                                    <td>{{ $generalProcesoPlanta2 }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaClientePorDia" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaSupervisorPorDia" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModuloPorDia" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> <i class="tim-icons icon-shape-star text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Dia actual</p>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaClientes" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Cliente</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($dataGeneral['dataCliente'] as $clienteData)
                              <tr class="{{ $clienteData['porcentajeErrorProceso'] > 9 && $clienteData['porcentajeErrorProceso'] <= 15 ? 'error-bajo' : ($clienteData['porcentajeErrorProceso'] > 15 ? 'error-alto' : '') }}">
                                <td>{{ $clienteData['cliente'] }}</td>
                                <td>{{ number_format($clienteData['porcentajeErrorAQL'], 2) }}%</td>
                                <td>{{ number_format($clienteData['porcentajeErrorProceso'], 2) }}%</td>
                              </tr>
                              @endforeach
                            </tbody>
                            <tr style="background: #1d1c1c;">
                              <td>GENERAL</td>
                              <td>{{ number_format($generalAQL, 2) }}%</td>
                              <td>{{ number_format($generalProceso, 2) }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Responsables AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaResponsables">
                            <thead class="text-primary">
                                <tr>
                                    <th>Supervisor</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataGerentesGeneral as $item)
                                    <tr>
                                        <td>{{ $item['team_leader'] }}</td>
                                        <td>{{ $item['porcentaje_error_aql'] !== null ? number_format($item['porcentaje_error_aql'], 2) . '%' : 'N/A' }}</td>
                                        <td>{{ $item['porcentaje_error_proceso'] !== null ? number_format($item['porcentaje_error_proceso'], 2) . '%' : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Modulos AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaModulos">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModulosGeneral as $item)
                                    <tr>
                                        <td>{{ $item['modulo'] }}</td>
                                        <td>{{ $item['porcentaje_error_aql'] !== null ? number_format($item['porcentaje_error_aql'], 2) . '%' : 'N/A' }}</td>
                                        <td>{{ $item['porcentaje_error_proceso'] !== null ? number_format($item['porcentaje_error_proceso'], 2) . '%' : 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaAQLGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (AQL)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>% AQL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloAQLGeneral as $item)
                                    <tr>
                                        <td>{{ $item['modulo'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td>
                                        <td>{{ $item['conteParoModular'] }}</td>
                                        <td>{{ number_format($item['porcentaje_error_aql'], 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaProcesoGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (Proceso)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloProcesoGeneral as $item)
                                    <tr>
                                        <td>{{ $item['modulo'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoUtility'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td>
                                        <td>{{ number_format($item['porcentaje_error_proceso'], 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaClientesSemanal" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaSupervisoresSemanal" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModulosSemanal" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> <i class="tim-icons icon-shape-star text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Semana actual</p>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaClientesSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Cliente</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($clientesSemana as $cliente)
                                <tr>
                                    <td>{{ $cliente['cliente'] }}</td>
                                    <td>{{ number_format($cliente['% AQL'], 2) }}%</td>
                                    <td>{{ number_format($cliente['% PROCESO'], 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Supervisores AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaResponsablesSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Supervisor</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($supervisoresSemana as $supervisor)
                                <tr>
                                    <td>{{ $supervisor['team_leader'] }}</td>
                                    <td>{{ number_format($supervisor['% AQL'], 2) }}%</td>
                                    <td>{{ number_format($supervisor['% PROCESO'], 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Modulos AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaModulosSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Módulo</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($modulosSemana as $modulo)
                                <tr>
                                    <td>{{ $modulo['modulo'] }}</td>
                                    <td>{{ number_format($modulo['% AQL'], 2) }}%</td>
                                    <td>{{ number_format($modulo['% PROCESO'], 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">
                                <a href="{{ route('dashboar.dashboarAProcesoAQL') }}">Intimark Mensual General</a>
                            </h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-success btn-simple active" id="btnAQL">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i> AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-info btn-simple" id="btnProcesos">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-vector text-primary"></i> Proceso</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;">
                        <div id="chartAQLContainer"></div>
                        <div id="chartProcesosContainer" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Graficas -->
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador Mensual por Cliente</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="cliente0">
                                    <input type="radio" name="clienteOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="cliente1">
                                    <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAll">
                                    <input type="checkbox" name="toggleAllOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Mostrar/Ocultar Todo</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-bullet-list-67"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;">
                        <div id="clienteChartAQL"></div>
                        <div id="clienteChartProcesos" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador Mensual por Módulo</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="modulo0">
                                    <input type="radio" name="moduloOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="modulo1">
                                    <input type="radio" class="d-none d-sm-none" name="moduloOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAllModulos">
                                    <input type="checkbox" name="toggleAllModulosOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Mostrar/Ocultar Todo</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-bullet-list-67"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;">
                        <div id="moduloChartAQL"></div>
                        <div id="moduloChartProcesos" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> Top 3 (Defectos)</h3>
                    <div class="col-sm-15">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="top3-1" onclick="mostrarGrafica('AQL')">
                                <input type="radio" name="clienteOptions" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-single-02"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="top3-2" onclick="mostrarGrafica('Procesos')">
                                <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-gift-2"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 400px;">
                    <div class="chart-area">
                        <div id="chartContainer"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-8">
            <div class="card card-chart">
                <div class="card-header">
                    <h2 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> Segundas/Terceras Acomulado Mensual</h2>
                </div>
                <div class="card-body">
                    <div id="SegundasTercerasChart"></div>
                    <div id="spinner" class="spinner"></div>
                </div>
            </div>
        </div>





    </div>

    <style>
  /* Estilo para el spinner */
.spinner {
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #3498db;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite;

  /* Centrar el spinner horizontal y verticalmente */
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Ocultar el spinner inicialmente */
#spinner {
  display: none;
}
      </style>


    <style>
        .chart-area {
          height: 500px; /* Ajusta esta altura según tus necesidades */
        }

        #chartAQLContainer, #chartProcesosContainer, #clienteChartAQL, #clienteChartProcesos, #moduloChartAQL, #moduloChartProcesos{
            width: 100%;
            height: 100%;
        }
      </style>
@endsection

@push('js')
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechas = @json($fechas);
            const porcentajesAQL = @json($porcentajesAQL);
            const porcentajesProceso = @json($porcentajesProceso);

            // Función para convertir los datos y manejar valores nulos o cero
            function prepareData(data) {
                return data.map(value => value === null ? null : parseFloat(value));
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'areaspline',
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: fechas,
                    tickmarkPlacement: 'on',
                    title: { enabled: false },
                    labels: { style: { color: '#ffffff' } }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5,
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartAQL = Highcharts.chart('chartAQLContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'AQL',
                    data: prepareData(porcentajesAQL),
                    color: '#00F0BA', // Color de la línea y el fondo de la línea
                    showInLegend: false // Ocultar nombre en la leyenda
                }]
            }));

            // Gráfica Procesos
            const chartProcesos = Highcharts.chart('chartProcesosContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'Procesos',
                    data: prepareData(porcentajesProceso),
                    color: '#E146A1', // Color de la línea y el fondo de la línea
                    showInLegend: false // Ocultar nombre en la leyenda
                }]
            }));

            // Funcionalidad de los botones
            document.getElementById('btnAQL').addEventListener('click', function() {
                document.getElementById('chartAQLContainer').style.display = 'block';
                document.getElementById('chartProcesosContainer').style.display = 'none';
                chartAQL.reflow();
            });

            document.getElementById('btnProcesos').addEventListener('click', function() {
                document.getElementById('chartAQLContainer').style.display = 'none';
                document.getElementById('chartProcesosContainer').style.display = 'block';
                chartProcesos.reflow();
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartAQL.reflow();
                chartProcesos.reflow();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechasGrafica = @json($fechasGrafica);
            const datasetsAQL = @json($datasetsAQL);
            const datasetsProceso = @json($datasetsProceso);

            // Lista de colores
            const colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#40AFFF'
            ];

            // Función para preparar datasets para Highcharts
            function prepareDatasets(datasets) {
                return datasets.map((dataset, index) => {
                    return {
                        name: dataset.label,
                        data: dataset.data.map((value, i) => [new Date(fechasGrafica[i]).getTime(), parseFloat(value)]),
                        color: colores[index % colores.length],
                        //showInLegend: false // Ocultar nombre en la leyenda
                    };
                });
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'spline', // Cambiado a 'spline' para curvas suaves
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                // Eliminar el título de la gráfica
                title: {
                    text: null
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        style: { color: '#ffffff' }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    spline: { // Opciones específicas para 'spline'
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartClienteAQL = Highcharts.chart('clienteChartAQL', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsAQL)
            }));

            // Gráfica Procesos
            const chartClienteProcesos = Highcharts.chart('clienteChartProcesos', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsProceso)
            }));

            // Funcionalidad de los botones
            document.getElementById('cliente0').addEventListener('click', function() {
                document.getElementById('clienteChartAQL').style.display = 'block';
                document.getElementById('clienteChartProcesos').style.display = 'none';
                chartClienteAQL.reflow();
            });

            document.getElementById('cliente1').addEventListener('click', function() {
                document.getElementById('clienteChartAQL').style.display = 'none';
                document.getElementById('clienteChartProcesos').style.display = 'block';
                chartClienteProcesos.reflow();
            });

            document.getElementById('toggleAll').addEventListener('click', function() {
                const showAll = document.getElementById('toggleAll').querySelector('input').checked;
                const toggleVisibility = function(chart) {
                    chart.series.forEach(function(series) {
                        series.setVisible(showAll, false);
                    });
                    chart.redraw();
                };

                toggleVisibility(chartClienteAQL);
                toggleVisibility(chartClienteProcesos);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartClienteAQL.reflow();
                chartClienteProcesos.reflow();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechasGraficaModulos = @json($fechasGraficaModulos);
            const datasetsAQLModulos = @json($datasetsAQLModulos);
            const datasetsProcesoModulos = @json($datasetsProcesoModulos);

            // Lista de colores
            const colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#40AFFF'
            ];

            // Función para preparar datasets para Highcharts
            function prepareDatasets(datasets) {
                return datasets.map((dataset, index) => {
                    return {
                        name: dataset.label,
                        data: dataset.data.map((value, i) => [new Date(fechasGraficaModulos[i]).getTime(), parseFloat(value)]),
                        color: colores[index % colores.length],
                        //showInLegend: false // Ocultar nombre en la leyenda
                    };
                });
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'spline', // Cambiado a 'spline' para curvas suaves
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                // Eliminar el título de la gráfica
                title: {
                    text: null
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        style: { color: '#ffffff' }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    spline: { // Opciones específicas para 'spline'
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartModuloAQL = Highcharts.chart('moduloChartAQL', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsAQLModulos)
            }));

            // Gráfica Procesos
            const chartModuloProcesos = Highcharts.chart('moduloChartProcesos', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsProcesoModulos)
            }));

            // Funcionalidad de los botones
            document.getElementById('modulo0').addEventListener('click', function() {
                document.getElementById('moduloChartAQL').style.display = 'block';
                document.getElementById('moduloChartProcesos').style.display = 'none';
                chartModuloAQL.reflow();
            });

            document.getElementById('modulo1').addEventListener('click', function() {
                document.getElementById('moduloChartAQL').style.display = 'none';
                document.getElementById('moduloChartProcesos').style.display = 'block';
                chartModuloProcesos.reflow();
            });

            document.getElementById('toggleAllModulos').addEventListener('click', function() {
                const showAll = document.getElementById('toggleAllModulos').querySelector('input').checked;
                const toggleVisibility = function(chart) {
                    chart.series.forEach(function(series) {
                        series.setVisible(showAll, false);
                    });
                    chart.redraw();
                };

                toggleVisibility(chartModuloAQL);
                toggleVisibility(chartModuloProcesos);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartModuloAQL.reflow();
                chartModuloProcesos.reflow();
            });
        });
    </script>
    <!-- nothing-->
    <!-- nothing-->
    <script>
        const topDefectosAQL = @json($topDefectosAQL);
        const topDefectosProceso = @json($topDefectosProceso);
        // Lista de colores
        const colores = [
            '#F03C3C', '#F0E23C', '#3C8EF0', '#36A2EB', '#FFCE56',
        ];

        function prepararDatos(datos) {
            const tp = datos.map(d => d.tp);
            const total = datos.map(d => d.total);

            return {
                tp,
                total,
            };
        }

        function crearGrafica(datos, titulo) {
            const { tp, total } = prepararDatos(datos);

            Highcharts.chart('chartContainer', {
                chart: {
                    type: 'column',
                    backgroundColor: '#27293D',
                },
                title: {
                    text: titulo,
                    style: {
                        color: '#FFFFFF'
                    }
                },
                xAxis: {
                    categories: ['Defectos'],
                    title: {
                        style: {
                            color: '#FFFFFF'
                        }
                    },
                    labels: {
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Número de defectos',
                        style: {
                            color: '#FFFFFF'
                        }
                    },
                    labels: {
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF'
                    }
                },
                series: [
                    {
                        name: tp[0],
                        data: [total[0]],
                        color: colores[0],
                    },
                    {
                        name: tp[1],
                        data: [total[1]],
                        color: colores[1],
                    },
                    {
                        name: tp[2],
                        data: [total[2]],
                        color: colores[2],
                    }
                ],
                plotOptions: {
                    column: {
                        colorByPoint: false, // Cambia a false ya que estamos asignando colores manualmente
                        borderColor: '#27293D'
                    }
                }
            });
        }

        function mostrarGrafica(tipo) {
            if (tipo === 'AQL') {
                crearGrafica(topDefectosAQL, 'Top 3 Defectos AQL');
            } else {
                crearGrafica(topDefectosProceso, 'Top 3 Defectos Procesos');
            }
        }

        // Mostrar la gráfica AQL por defecto
        mostrarGrafica('AQL');
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
      // Crear una bandera global para evitar múltiples cargas
      if (window.datosCargados) return; // Detener si ya se ha cargado
      window.datosCargados = true; // Marcar como cargado

      // Mostrar el spinner al iniciar la petición
      document.getElementById("spinner").style.display = "block";

      fetch("/SegundasTerceras", {
        method: "GET",
        headers: {
          "Content-Type": "application/json"
        }
      })
      .then(response => {
        if (!response.ok) {
          throw new Error("Error en la respuesta de la red");
        }
        return response.json();
      })
      .then(data => {
        let segundas = 0;
        let terceras = 0;
        let totalQty = 0; // Variable para almacenar el total de Total_QTY

        // Sumamos las cantidades de Total_QTY
        data.data.forEach(item => {
          let qty = parseFloat(item.Total_QTY); // Asegúrate de que el valor es numérico
          totalQty += qty; // Sumar al total de QTY
          // Sumar para segundas y terceras
          if (item.QUALITY === "1") {
            segundas += qty; // Suma para "Segundas"
          } else if (item.QUALITY === "2") {
            terceras += qty; // Suma para "Terceras"
          }
        });

        // Calcular el porcentaje para Segundas y Terceras
        let porcentajeSegundas = (segundas / totalQty) * 100;
        let porcentajeTerceras = (terceras / totalQty) * 100;

        // Mostrar en consola para verificar
        console.log("Total QTY: ", totalQty);
        console.log("Segundas: ", segundas, " | Porcentaje Segundas: ", porcentajeSegundas);
        console.log("Terceras: ", terceras, " | Porcentaje Terceras: ", porcentajeTerceras);

        // Generamos la gráfica con los datos
        Highcharts.chart("SegundasTercerasChart", {
          chart: {
            type: "column",
            backgroundColor: "transparent"
          },
          title: {
            text: "Segundas y Terceras"
          },
          xAxis: {
            categories: ["Segundas", "Terceras"]
          },
          yAxis: {
            min: 0,
            title: {
              text: "Cantidad"
            }
          },
          tooltip: {
            pointFormatter: function() {
              return `<b>${this.series.name}:</b> ${this.y} <br><b>Porcentaje:</b> ${(this.y / totalQty * 100).toFixed(2)}%`;
            }
          },
          series: [{
            name: "Segundas",
            id: "segundas",
            data: [{
              y: segundas,
            }],
            color: "#7cb5ec",
            dataLabels: {
              enabled: false,
            },
            events: {
              click: function(event) {
                if (this.options.id === "segundas") {
                  window.location.href = "/Segundas";
                }
              }
            }
          }, {
            name: "Terceras",
            id: "terceras",
            data: [{
              y: terceras,
            }],
            color: "#434348",
            dataLabels: {
              enabled: false,
            }
          }],
          legend: {
            enabled: true
          }
        });

        // Ocultar el spinner después de que se haya generado la gráfica
        document.getElementById("spinner").style.display = "none";
      })
      .catch(error => {
        console.error("Error al cargar los datos:", error);
        // Ocultar el spinner en caso de error
        document.getElementById("spinner").style.display = "none";
      });
    });
  </script>




    <script>
        // Configuración global de Highcharts para la fuente y estilo
        Highcharts.setOptions({
            chart: {
                style: {
                    fontFamily: 'Arial, sans-serif' // Establecer la tipografía global
                }
            }
        });
        //script para cliente por semana
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaClientesSemanal', {
                chart: {
                    type: 'column', // Cambiar a 'bar' si prefieres barras horizontales
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Clientes (Semana Actual)'
                },
                xAxis: {
                    categories: @json(array_column($clientesSemana, 'cliente')), // Lista de clientes
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($clientesSemana, '% AQL')), // Datos de AQL
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($clientesSemana, '% PROCESO')), // Datos de PROCESO
                    color: '#dd4dc7' // Color definido para PROCESO
                }]
            });
        });

        //Supervisores por semana
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaSupervisoresSemanal', {
                chart: {
                    type: 'column',
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Supervisores (Semana Actual)'
                },
                xAxis: {
                    categories: @json(array_column($supervisoresSemana, 'team_leader')),
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($supervisoresSemana, '% AQL')),
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($supervisoresSemana, '% PROCESO')),
                    color: '#dd4dc7' // Color definido para PROCESO

                }]
            });
        });

        //Modulo por semana
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaModulosSemanal', {
                chart: {
                    type: 'column',
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Módulos (Semana Actual)'
                },
                xAxis: {
                    categories: @json(array_column($modulosSemana, 'modulo')),
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($modulosSemana, '% AQL')),
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($modulosSemana, '% PROCESO')),
                    color: '#dd4dc7' // Color definido para PROCESO
                }]
            });
        });

    </script>

    <script>
        //misma grafica pero ahora por dia
        // Configuración global de Highcharts para la fuente y estilo
        Highcharts.setOptions({
            chart: {
                style: {
                    fontFamily: 'Arial, sans-serif' // Establecer la tipografía global
                }
            }
        });

        // Script para Clientes por día
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaClientePorDia', {
                chart: {
                    type: 'column',
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Clientes (Día Actual)'
                },
                xAxis: {
                    categories: @json(array_column($dataGeneral['dataCliente'], 'cliente')), // Lista de clientes
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($dataGeneral['dataCliente'], 'porcentajeErrorAQL')), // Datos de AQL
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($dataGeneral['dataCliente'], 'porcentajeErrorProceso')), // Datos de PROCESO
                    color: '#dd4dc7' // Color definido para PROCESO
                }]
            });
        });

        // Script para Supervisores por día
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaSupervisorPorDia', {
                chart: {
                    type: 'column',
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Supervisores (Día Actual)'
                },
                xAxis: {
                    categories: @json(array_column($dataGerentesGeneral, 'team_leader')), // Lista de supervisores
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($dataGerentesGeneral, 'porcentaje_error_aql')), // Datos de AQL
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($dataGerentesGeneral, 'porcentaje_error_proceso')), // Datos de PROCESO
                    color: '#dd4dc7' // Color definido para PROCESO
                }]
            });
        });

        // Script para Módulos por día
        document.addEventListener('DOMContentLoaded', function () {
            Highcharts.chart('graficaModuloPorDia', {
                chart: {
                    type: 'column',
                    backgroundColor: null // Fondo transparente
                },
                title: {
                    text: 'Comparativo AQL y PROCESO - Módulos (Día Actual)'
                },
                xAxis: {
                    categories: @json(array_column($dataModulosGeneral, 'modulo')), // Lista de módulos
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Porcentaje (%)'
                    }
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: '% AQL',
                    data: @json(array_column($dataModulosGeneral, 'porcentaje_error_aql')), // Datos de AQL
                    color: '#00f0c1' // Color definido para AQL
                }, {
                    name: '% PROCESO',
                    data: @json(array_column($dataModulosGeneral, 'porcentaje_error_proceso')), // Datos de PROCESO
                    color: '#dd4dc7' // Color definido para PROCESO
                }]
            });
        });

    </script>

@endpush

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.type.order['custom-num-pre'] = function(a) {
                // Si es "N/A", devolver un valor que lo coloque al final
                if (a === "N/A") return -Infinity;

                // Convertir a número flotante
                var x = parseFloat(a);

                // Si no es un número válido, devolver -Infinity
                return isNaN(x) ? -Infinity : x;
            };

            $.fn.dataTable.ext.type.order['custom-num-desc'] = function(a, b) {
                return b - a;
            };
            const tableIds = ['#tablaAQLGeneral', '#tablaProcesoGeneral', '#tablaResponsables', '#tablaModulos', '#tablaResponsable', '#tablaClientes',
                            '#tablaModulosSemanal', '#tablaResponsablesSemanal', '#tablaClientesSemanal',
            ];

            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 5,
                        autoWidth: false,
                        responsive: true,
                        columnDefs: [
                            {
                                searchable: false,
                                orderable: false,
                            },
                            {
                                targets: 0, // La primera columna (índice 0)
                                type: "string", // Tratar como texto
                                render: function(data, type, row) {
                                    // Asegúrate de manejar correctamente el texto
                                    return type === 'sort' ? data : data;
                                }
                            },
                            {
                                targets: "_all", // Todas las demás columnas numéricas
                                type: "custom-num",  // Usar tipo personalizado
                                render: function(data, type, row) {
                                    // Esto ayuda a manejar la presentación de "N/A"
                                    return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                }
                            }
                        ],
                        language: {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        }
                    });
                }
            });
        });
    </script>




@endpush
