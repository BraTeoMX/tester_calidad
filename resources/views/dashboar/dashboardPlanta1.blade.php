@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard General Planta 1 - Ixtlahuaca </h2>
                </div>
            </div>
        </div>
    </div>

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
                                    <td>Planta I :</td>
                                    <td>{{ $generalAQLPlanta1 }}%</td>
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
                                    <td>Planta I :</td>
                                    <td>{{ $generalProcesoPlanta1 }}%</td>
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
                        <canvas id="chartLinePurple" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> Segundas / Terceras</h3>
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="CountryChart"></canvas>
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
                    <form action="{{ route('dashboar.detalleXModuloPlanta1') }}" method="GET">
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
      </style>
@endsection

@push('js')
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
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

    <script>
        $(document).ready(function() {
            let myChart;

            function actualizarGrafico(datos) {
                const ctx = document.getElementById('chartLinePurple').getContext('2d');

                if (myChart) {
                    myChart.destroy();
                }

                const backgroundColor = datos.map(() => {
                    const r = Math.floor(Math.random() * 256);
                    const g = Math.floor(Math.random() * 256);
                    const b = Math.floor(Math.random() * 256);
                    return `rgba(${r}, ${g}, ${b}, 0.5)`;
                });

                // Prepara los datos para el gráfico (un solo dataset)
                const labels = datos.map((item, index) => `${item.defecto} (${index + 1})`);
                const cantidades = datos.map(item => item.cantidad);

                myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{ // Un solo dataset con todos los datos
                            label: 'Cantidad', // Etiqueta general para el dataset
                            data: cantidades,
                            backgroundColor: backgroundColor,
                            borderColor: backgroundColor.map(color => color.replace('0.5', '1')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: (value, context) => {
                                    return `${labels[context.dataIndex]} (${value})`; // Mostrar etiqueta y valor
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            $('#top3-1, #top3-2').change(function() {
                let tipo = $(this).attr('id') === 'top3-1' ? 'TpAuditoriaAQL' : 'TpAseguramientoCalidad';

                $.ajax({
                    url: 'obtener_top_defectos',
                    method: 'GET',
                    data: {
                        tipo: tipo
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            actualizarGrafico(response.data);
                        } else {
                            console.error(response.error);
                        }
                    },
                    error: function() {
                        console.error('Error en la solicitud AJAX.');
                    }
                });
            });

            $('#top3-1').trigger('change');
        });
    </script>

@endpush
