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
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Procesos</h3>
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
                                <label class="btn btn-sm btn-primary btn-simple active" id="btnAQL">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i> AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="btnProcesos">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-vector text-primary"></i> Procesos</span>
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
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
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
                    <div class="chart-area" style="height: 500px;"> <!-- Ajusta esta altura según tus necesidades -->
                        <canvas id="clienteChartAQL"></canvas>
                        <canvas id="clienteChartProcesos" style="display: none;"></canvas>
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
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
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
                    <div class="chart-area" style="height: 500px;"> <!-- Ajusta esta altura según tus necesidades -->
                        <canvas id="moduloChartAQL"></canvas>
                        <canvas id="moduloChartProcesos" style="display: none;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> Top 3 Defectos</h3>
                    <div class="col-sm-15">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="top3-1">
                                <input type="radio" name="clienteOptions" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-single-02"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="top3-2">
                                <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-gift-2"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="newChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h2 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> Segundas / Terceras</h2>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="SegundasTerceras"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-send text-info"></i> Incidencias</h3>
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
                    </div>
                </div>
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
                                    <th>% Error Proceso</th>
                                    <th>% Error AQL</th>
                                    <!-- Aquí puedes agregar más encabezados si es necesario -->
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($dataGeneral['dataCliente'] as $clienteData)
                              <tr class="{{ $clienteData['porcentajeErrorProceso'] > 9 && $clienteData['porcentajeErrorProceso'] <= 15 ? 'error-bajo' : ($clienteData['porcentajeErrorProceso'] > 15 ? 'error-alto' : '') }}">
                                <td>{{ $clienteData['cliente'] }}</td>
                                <td>{{ number_format($clienteData['porcentajeErrorProceso'], 2) }}%</td>
                                <td>{{ number_format($clienteData['porcentajeErrorAQL'], 2) }}%</td>
                              </tr>
                              @endforeach
                            </tbody>
                            <tr style="background: #1d1c1c;">
                              <td>GENERAL</td>
                              <td>{{ number_format($totalGeneral['totalPorcentajeErrorProceso'], 2) }}%</td>
                              <td>{{ number_format($totalGeneral['totalPorcentajeErrorAQL'], 2) }}%</td>
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
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Gerentes Produccion</th>
                                    <th>% Error AQL</th>
                                    <th>% Error Proceso</th>
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
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    <th>% Error AQL</th>
                                    <th>% Error Proceso</th>
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
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (AQL)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>% Error AQL</th>
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
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (Proceso)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>% Error Proceso</th>
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
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-zoom-split text-success"></i> Seleccion de Cliente por Modulo</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboar.detalleXModulo') }}" method="GET">
                        <div class="form-group">
                            <label for="clienteBusqueda">Seleccione un cliente:</label>
                            <select class="form-control" name="clienteBusqueda" id="clienteBusqueda" required>
                                <option value="">Seleccione un cliente</option>
                                @foreach ($clientesUnicosArrayBusqueda as $cliente)
                                    <option value="{{ $cliente }}">{{ $cliente }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"></h3>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>


    <style>
        .chart-area {
          height: 500px; /* Ajusta esta altura según tus necesidades */
        }
        
        #chartAQLContainer, #chartProcesosContainer {
            width: 100%;
            height: 100%;
        }
      </style>
@endsection

@push('js')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/themes/dark-unica.js"></script>
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
                    text: 'Intimark Mensual General',
                    style: { color: '#ffffff' }
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
                colors: ['#7cb5ec'],
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };
        
            // Gráfica AQL
            const chartAQL = Highcharts.chart('chartAQLContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'AQL',
                    data: prepareData(porcentajesAQL)
                }]
            }));
        
            // Gráfica Procesos
            const chartProcesos = Highcharts.chart('chartProcesosContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'Procesos',
                    data: prepareData(porcentajesProceso)
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
        $(document).ready(function() {
        // Lista de colores
        var colores = [
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(199, 199, 199, 1)',
            'rgba(255, 99, 255, 1)',
            'rgba(99, 255, 132, 1)',
            'rgba(99, 132, 255, 1)',
            'rgba(132, 99, 255, 1)',
            'rgba(192, 75, 192, 1)',
            'rgba(235, 162, 54, 1)',
            'rgba(86, 255, 206, 1)',
            'rgba(64, 159, 255, 1)'
        ];

        // Inicializa las gráficas
        var ctxClienteAQL = document.getElementById('clienteChartAQL').getContext('2d');
        var datasetsAQL = @json($datasetsAQL).map((dataset, index) => {
            return {
                ...dataset,
                borderColor: colores[index % colores.length],
                backgroundColor: colores[index % colores.length]
            };
        });
        var chartClienteAQL = new Chart(ctxClienteAQL, {
            type: 'line',
            data: {
                labels: @json($fechasGrafica),
                datasets: datasetsAQL
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true // Mostrar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'YYYY-MM-DD'
                            }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value + '%'; // Añadir el símbolo de porcentaje
                            }
                        }
                    }]
                }
            }
        });

        var ctxClienteProcesos = document.getElementById('clienteChartProcesos').getContext('2d');
        var datasetsProceso = @json($datasetsProceso).map((dataset, index) => {
            return {
                ...dataset,
                borderColor: colores[index % colores.length],
                backgroundColor: colores[index % colores.length]
            };
        });
        var chartClienteProcesos = new Chart(ctxClienteProcesos, {
            type: 'line',
            data: {
                labels: @json($fechasGrafica),
                datasets: datasetsProceso
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true // Mostrar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'YYYY-MM-DD'
                            }
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value + '%'; // Añadir el símbolo de porcentaje
                            }
                        }
                    }]
                }
            }
        });

        $('#cliente0').on('click', function() {
            $('#clienteChartAQL').show();
            $('#clienteChartProcesos').hide();
            chartClienteAQL.update(); // Asegurarse de que la gráfica se actualice
        });

        $('#cliente1').on('click', function() {
            $('#clienteChartAQL').hide();
            $('#clienteChartProcesos').show();
            chartClienteProcesos.update(); // Asegurarse de que la gráfica se actualice
        });

        $('#toggleAll').on('click', function() {
            var showAll = $('#toggleAll input').prop('checked');
            var toggleVisibility = function(chart) {
                chart.data.datasets.forEach(function(dataset) {
                    dataset.hidden = !showAll;
                });
                chart.update();
            };

            toggleVisibility(chartClienteAQL);
            toggleVisibility(chartClienteProcesos);
        });
        });
    </script>

    <script>
        $(document).ready(function() {
            var colores = [
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(199, 199, 199, 1)',
                'rgba(255, 99, 255, 1)',
                'rgba(99, 255, 132, 1)',
                'rgba(99, 132, 255, 1)',
                'rgba(132, 99, 255, 1)',
                'rgba(192, 75, 192, 1)',
                'rgba(235, 162, 54, 1)',
                'rgba(86, 255, 206, 1)',
                'rgba(64, 159, 255, 1)'
            ];

            // Inicializa las gráficas de módulos
            var ctxModuloAQL = document.getElementById('moduloChartAQL').getContext('2d');
            var datasetsAQLModulos = @json($datasetsAQLModulos).map((dataset, index) => {
                return {
                    ...dataset,
                    borderColor: colores[index % colores.length],
                    backgroundColor: colores[index % colores.length]
                };
            });
            var chartModuloAQL = new Chart(ctxModuloAQL, {
                type: 'line',
                data: {
                    labels: @json($fechasGraficaModulos),
                    datasets: datasetsAQLModulos
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'll',
                                displayFormats: {
                                    day: 'YYYY-MM-DD'
                                }
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, values) {
                                    return value + '%';
                                }
                            }
                        }]
                    }
                }
            });

            var ctxModuloProcesos = document.getElementById('moduloChartProcesos').getContext('2d');
            var datasetsProcesoModulos = @json($datasetsProcesoModulos).map((dataset, index) => {
                return {
                    ...dataset,
                    borderColor: colores[index % colores.length],
                    backgroundColor: colores[index % colores.length]
                };
            });
            var chartModuloProcesos = new Chart(ctxModuloProcesos, {
                type: 'line',
                data: {
                    labels: @json($fechasGraficaModulos),
                    datasets: datasetsProcesoModulos
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'll',
                                displayFormats: {
                                    day: 'YYYY-MM-DD'
                                }
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value, index, values) {
                                    return value + '%';
                                }
                            }
                        }]
                    }
                }
            });

            $('#modulo0').on('click', function() {
                $('#moduloChartAQL').show();
                $('#moduloChartProcesos').hide();
                chartModuloAQL.update();
            });

            $('#modulo1').on('click', function() {
                $('#moduloChartAQL').hide();
                $('#moduloChartProcesos').show();
                chartModuloProcesos.update();
            });

            $('#toggleAllModulos').on('click', function() {
                var showAll = $('#toggleAllModulos input').prop('checked');
                var toggleVisibility = function(chart) {
                    chart.data.datasets.forEach(function(dataset) {
                        dataset.hidden = !showAll;
                    });
                    chart.update();
                };

                toggleVisibility(chartModuloAQL);
                toggleVisibility(chartModuloProcesos);
            });
        });
    </script>
    <!-- nothing-->
    <!-- nothing-->
    <script>
        $(document).ready(function() {
            let myNewChart;
            let chartInitialized = false;

            const colorsAQL = [
                {
                    backgroundColor: 'rgba(128, 0, 0, 0.8)', // Color fuerte y sólido (Rojo oscuro)
                    borderColor: 'rgba(128, 0, 0, 1)'
                },
                {
                    backgroundColor: 'rgba(255, 165, 0, 0.6)', // Color intermedio (Naranja)
                    borderColor: 'rgba(255, 165, 0, 1)'
                },
                {
                    backgroundColor: 'rgba(255, 255, 0, 0.4)', // Color claro (Amarillo claro)
                    borderColor: 'rgba(255, 255, 0, 1)'
                }
            ];

            const colorsProceso = [
                {
                    backgroundColor: 'rgba(0, 0, 139, 0.8)', // Color fuerte y sólido (Azul oscuro)
                    borderColor: 'rgba(0, 0, 139, 1)'
                },
                {
                    backgroundColor: 'rgba(34, 139, 34, 0.6)', // Color intermedio (Verde)
                    borderColor: 'rgba(34, 139, 34, 1)'
                },
                {
                    backgroundColor: 'rgba(173, 216, 230, 0.4)', // Color claro (Celeste)
                    borderColor: 'rgba(173, 216, 230, 1)'
                }
            ];

            function createChart(data, tipo) {
                const canvas = document.getElementById('newChart');
                const ctx = canvas.getContext('2d');

                if (myNewChart) {
                    myNewChart.destroy();
                }

                const colors = tipo === 'TpAuditoriaAQL' ? colorsAQL : colorsProceso;

                const datasets = data.map((item, index) => {
                    const color = colors[index % colors.length]; // Ciclar a través de los colores si hay más de 3 defectos

                    return {
                        label: item.defecto,
                        data: [item.cantidad],
                        backgroundColor: color.backgroundColor,
                        borderColor: color.borderColor,
                        borderWidth: 1
                    };
                });

                myNewChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Defectos'],
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                        },
                        legend: {
                            display: true
                        }
                    }
                });
            }

            function fetchChartData(tipo) {
                $.ajax({
                    url: 'obtener_top_defectos',
                    method: 'GET',
                    data: {
                        tipo: tipo
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            createChart(response.data, tipo);
                        } else {
                            console.error(response.error);
                        }
                    },
                    error: function() {
                        console.error('Error en la solicitud AJAX.');
                    }
                });
            }

            // Asegurar que los eventos solo se enlacen una vez
            if (!chartInitialized) {
                $('#top3-1, #top3-2').change(function() {
                    let tipo = $(this).attr('id') === 'top3-1' ? 'TpAuditoriaAQL' : 'TpAseguramientoCalidad';
                    fetchChartData(tipo);
                });

                // Llamar una vez al cargar la página
                fetchChartData('TpAuditoriaAQL');
                chartInitialized = true;
            }
        });
    </script>

@endpush
