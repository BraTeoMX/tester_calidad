@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content') 
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center">Dashboard Detalle Planta 1 - Ixtlahuaca </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
            <form action="{{ route('dashboar.dashboardPlanta1Detalle') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </form>
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Obtener los parámetros de la URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const fechaInicio = urlParams.get('fecha_inicio');
                    const fechaFin = urlParams.get('fecha_fin');

                    // Establecer los valores de los campos de fecha
                    document.getElementById("fecha_inicio").value = fechaInicio || '';
                    document.getElementById("fecha_fin").value = fechaFin || '';

                    // Manejar el evento de envío del formulario
                    document.getElementById("filterForm").addEventListener("submit", function(event) {
                        // Agregar los valores de los campos de fecha a la URL del formulario
                        const fechaInicioValue = document.getElementById("fecha_inicio").value || '';
                        const fechaFinValue = document.getElementById("fecha_fin").value || '';
                        this.action = "{{ route('dashboar.dashboardPlanta1Detalle') }}?fecha_inicio=" + fechaInicioValue + "&fecha_fin=" + fechaFinValue;
                    });
                });

            </script>
            <hr>     
        </div>
    </div>

    <div class="row"> 
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Seleccion por rango General: </h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-app text-success"></i>&nbsp; AQL
                                    </span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos
                                    </span>
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
                        <div id="chartAQL" ></div>
                        <div id="chartProcesos" style="none;"></div>
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
                            <h2 class="card-title">Indicador por Cliente en selección de rango:</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="cliente0">
                                    <input type="radio" name="clienteOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-app text-success"></i>&nbsp; AQL
                                    </span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="cliente1">
                                    <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos
                                    </span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAllClientes">
                                    <input type="checkbox" name="toggleAllClientesOptions">
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
        <!-- Gráficos por Módulo -->
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador por Módulo en selección de rango:</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="modulo0">
                                    <input type="radio" name="moduloOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-app text-success"></i>&nbsp; AQL
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="modulo1">
                                    <input type="radio" name="moduloOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAllModulos">
                                    <input type="checkbox" name="toggleAllModulosOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Mostrar/Ocultar Todo</span>
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
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador por Supervisor en selección de rango:</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="supervisor0">
                                    <input type="radio" name="supervisorOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-app text-success"></i>&nbsp; AQL
                                    </span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="supervisor1">
                                    <input type="radio" class="d-none d-sm-none" name="supervisorOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">
                                        <i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos
                                    </span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAllSupervisores">
                                    <input type="checkbox" name="toggleAllSupervisoresOptions">
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
                        <div id="supervisorChartAQL"></div>
                        <div id="supervisorChartProcesos" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @php
            function formatWeekLabel($value) {
                list($year, $week) = explode('-', $value);
                return "Semana: $week, Año: $year";
            }
        @endphp
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Datos por Cliente en Selección de Rango en tabla</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="clientesDetalleTabla">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                @foreach ($semanasGrafica as $semana)
                                    <th colspan="2">{{ formatWeekLabel($semana) }}</th> <!-- Colspan 2 para AQL y Proceso -->
                                @endforeach
                            </tr>
                            <tr>
                                <th></th>
                                @foreach ($semanasGrafica as $semana)
                                    <th>AQL</th>
                                    <th>Proceso</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientesGrafica as $index => $cliente)
                                <tr>
                                    <td>{{ $cliente }}</td>
                                    @foreach ($semanasGrafica as $semana)
                                        <td>{{ number_format($datasetsAQL[$index]['data'][$loop->index], 2) }}%</td>
                                        <td>{{ number_format($datasetsProceso[$index]['data'][$loop->index], 2) }}%</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Datos por Modulo en Selección de Rango en tabla</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="clientesDetalleTabla">
                        <thead>
                            <tr>
                                <th>Modulo</th>
                                @foreach ($semanasGrafica as $semana)
                                    <th colspan="2">{{ formatWeekLabel($semana) }}</th> <!-- Colspan 2 para AQL y Proceso -->
                                @endforeach
                            </tr>
                            <tr>
                                <th></th>
                                @foreach ($semanasGrafica as $semana)
                                    <th>AQL</th>
                                    <th>Proceso</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modulosGrafica as $index => $modulo)
                                <tr>
                                    <td>{{ $modulo }}</td>
                                    @foreach ($semanasGrafica as $semana)
                                        <td>{{ number_format($datasetsAQLModulos[$index]['data'][$loop->index], 2) }}%</td>
                                        <td>{{ number_format($datasetsProcesoModulos[$index]['data'][$loop->index], 2) }}%</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Datos por Supervisor en Selección de Rango en tabla</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="clientesDetalleTabla">
                        <thead>
                            <tr>
                                <th>Supevisor</th>
                                @foreach ($semanasGrafica as $semana)
                                    <th colspan="2">{{ formatWeekLabel($semana) }}</th> <!-- Colspan 2 para AQL y Proceso -->
                                @endforeach
                            </tr>
                            <tr>
                                <th></th>
                                @foreach ($semanasGrafica as $semana)
                                    <th>AQL</th>
                                    <th>Proceso</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teamLeadersGrafica as $index => $team_leader)
                                <tr>
                                    <td>{{ $team_leader }}</td>
                                    @foreach ($semanasGrafica as $semana)
                                        <td>{{ number_format($datasetsAQLSupervisor[$index]['data'][$loop->index], 2) }}%</td>
                                        <td>{{ number_format($datasetsProcesoSupervisor[$index]['data'][$loop->index], 2) }}%</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    

    <div class="row">
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"><i class="tim-icons icon-spaceship text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Rango de Fechas: {{ $fechaInicioFormateada }} - {{ $fechaFinFormateada }}</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaClientes" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Cliente</th>
                                    <th>% Error Proceso</th>
                                    <th>% Error AQL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataGeneral['dataCliente'] as $clienteData)
                                <tr>
                                    <td>{{ $clienteData['cliente'] }}</td>
                                    <td>{{ isset($clienteData['porcentajeErrorProceso']) ? number_format($clienteData['porcentajeErrorProceso'], 2) : 'N/A' }}%</td>
                                    <td>{{ isset($clienteData['porcentajeErrorAQL']) ? number_format($clienteData['porcentajeErrorAQL'], 2) : 'N/A' }}%</td>
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
                    <h4 class="card-title">Responsables <i class="tim-icons icon-app text-success"></i>&nbsp; AQL  y &nbsp;<i class="tim-icons icon-vector text-primary"></i>&nbsp; PROCESO </h4>
                    <p class="card-category d-inline"> Rango de Fechas: {{ $fechaInicioFormateada }} - {{ $fechaFinFormateada }}</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaDinamico">
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
                    <h4 class="card-title">Modulos &nbsp;<i class="tim-icons icon-app text-success"></i>&nbsp;  AQL y  &nbsp;<i class="tim-icons icon-vector text-primary"></i> &nbsp;PROCESO</h4>
                    <p class="card-category d-inline"> Rango de Fechas: {{ $fechaInicioFormateada }} - {{ $fechaFinFormateada }}</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaDinamico2">
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
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i>&nbsp; Modulo AQL general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaDinamico3">
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
                <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i>&nbsp; Modulo Proceso general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaDinamico4">
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

    <style>
        .chart-area {
            height: 500px; /* Altura ajustable */
            position: relative;
            overflow: hidden;
        }
        
        #chartAQL, #chartProcesos {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            display: none; /* Por defecto no se muestra */
        }

        #clienteChartAQL, #clienteChartProcesos, #moduloChartProcesos, #moduloChartAQL, #supervisorChartAQL, #supervisorChartProcesos {
            width: 100%;
            height: 100%;
        }

        #chartAQL {
            display: block; /* Mostrar por defecto el primer gráfico */
        }
      </style>
@endsection

@push('js') 
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>
    <script>
        $(document).ready(function() {
            function formatWeekLabel(value) {
                const [year, week] = value.split('-');
                return `Semana: ${week}, Año: ${year}`;
            }
    
            Highcharts.setOptions({
                lang: {
                    thousandsSep: ',',
                    decimalPoint: '.'
                }
            });
    
            // Encuentra el valor máximo de los datos
            var maxAQL = Math.max(...{!! json_encode($porcentajesAQL->map(function($value) { return (float)$value; })) !!});
            var maxProcesos = Math.max(...{!! json_encode($porcentajesProceso->map(function($value) { return (float)$value; })) !!});
    
            // Añade un margen al máximo
            var margen = 2;
            var maxYValue = Math.max(maxAQL, maxProcesos) + margen;
    
            var chartOptions = {
                chart: {
                    type: 'spline',  // Cambiado de 'line' a 'spline'
                    backgroundColor: 'transparent'
                },
                title: {
                    text: ''  // Título se establecerá individualmente para cada gráfica
                },
                xAxis: {
                    categories: {!! json_encode($semanas) !!},
                    labels: {
                        formatter: function() {
                            return formatWeekLabel(this.value);
                        },
                        rotation: 0 // Ajuste aquí para que los labels se muestren horizontalmente
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje'
                    },
                    min: 0,
                    max: maxYValue, // Ajuste dinámico del valor máximo
                    tickInterval: 2,
                    labels: {
                        formatter: function() {
                            return this.value + '%';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.2f}%</b>'
                },
                plotOptions: {
                    spline: {
                        marker: {
                            enabled: true
                        },
                        lineWidth: 2,
                        states: {
                            hover: {
                                lineWidth: 3
                            }
                        }
                    }
                }
            };
    
            var chartAQL = Highcharts.chart('chartAQL', Highcharts.merge(chartOptions, {
                title: {
                    text: 'AQL'
                },
                series: [{
                    name: 'AQL',
                    data: {!! json_encode($porcentajesAQL->map(function($value) { return (float)$value; })) !!},
                    color: '#f96332',
                    fillOpacity: 0.4
                }]
            }));
    
            var chartProcesos = Highcharts.chart('chartProcesos', Highcharts.merge(chartOptions, {
                title: {
                    text: 'Procesos'
                },
                series: [{
                    name: 'Procesos',
                    data: {!! json_encode($porcentajesProceso->map(function($value) { return (float)$value; })) !!},
                    color: '#1f8ef1',
                    fillOpacity: 0.4
                }]
            }));
    
            $('#0').on('click', function() {
                $('#chartAQL').show();
                $('#chartProcesos').hide();
            });
    
            $('#1').on('click', function() {
                $('#chartAQL').hide();
                $('#chartProcesos').show();
            });
        });
    </script>    

    <script>
        $(document).ready(function() {
            function formatWeekLabel(value) {
                const [year, week] = value.split('-');
                return `Semana: ${week}, Año: ${year}`;
            }

            var colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#409FFF'
            ];

            var chartOptionsBase = {
                chart: {
                    type: 'spline',
                    backgroundColor: 'transparent'
                },
                xAxis: {
                    categories: {!! json_encode($semanasGrafica) !!},
                    labels: {
                        formatter: function() {
                            return formatWeekLabel(this.value);
                        },
                        rotation: 0
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje'
                    },
                    min: 0,
                    tickInterval: 0.2,
                    labels: {
                        formatter: function() {
                            return this.value % 1 === 0 ? this.value.toFixed(2) + '%' : '';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.2f}%</b>'
                },
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true
                        }
                    }
                }
            };
 
            var datasetsAQL = @json($datasetsAQL).map((dataset, index) => {
                return {
                    name: dataset.label,
                    data: dataset.data,
                    color: colores[index % colores.length]
                };
            });

            var chartClienteAQL = Highcharts.chart('clienteChartAQL', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Cliente - AQL'
                },
                series: datasetsAQL
            }));

            var datasetsProceso = @json($datasetsProceso).map((dataset, index) => {
                return {
                    name: dataset.label,
                    data: dataset.data,
                    color: colores[index % colores.length]
                };
            });

            var chartClienteProcesos = Highcharts.chart('clienteChartProcesos', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Cliente - Procesos'
                },
                series: datasetsProceso
            }));

            $('#cliente0').on('click', function() {
                $('#clienteChartAQL').show();
                $('#clienteChartProcesos').hide();
                chartClienteAQL.reflow();
            });

            $('#cliente1').on('click', function() {
                $('#clienteChartAQL').hide();
                $('#clienteChartProcesos').show();
                chartClienteProcesos.reflow();
            });

            function toggleVisibility(charts, showAll) {
                charts.forEach(function(chart) {
                    if (chart && chart.series) {
                        chart.series.forEach(function(series) {
                            series.setVisible(showAll, false);
                        });
                        chart.redraw();
                    }
                });
            }

            $('#toggleAllClientes').on('click', function() {
                var showAll = $(this).find('input').prop('checked');
                var activeChart = $('#clienteChartAQL').is(':visible') ? chartClienteAQL : chartClienteProcesos;
                toggleVisibility([activeChart], showAll);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartClienteAQL.reflow();
                chartClienteProcesos.reflow();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Función para formatear el label de la semana
            function formatWeekLabel(value) {
                const [year, week] = value.split('-');
                return `Semana: ${week}, Año: ${year}`;
            }

            var colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#409FFF'
            ];

            var chartOptionsBase = {
                chart: {
                    type: 'spline',
                    backgroundColor: 'transparent'
                },
                xAxis: {
                    categories: {!! json_encode($semanasGrafica) !!},
                    labels: {
                        formatter: function() {
                            return formatWeekLabel(this.value);
                        },
                        rotation: 0
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje'
                    },
                    min: 0,
                    tickInterval: 0.2,
                    labels: {
                        formatter: function() {
                            return this.value % 1 === 0 ? this.value.toFixed(2) + '%' : '';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.2f}%</b>'
                },
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true
                        }
                    }
                }
            };

            // Gráficos para Módulo
            var datasetsModuloAQL = @json($datasetsAQLModulos).map((dataset, index) => ({
                name: dataset.label,
                data: dataset.data,
                color: colores[index % colores.length]
            }));

            var chartModuloAQL = Highcharts.chart('moduloChartAQL', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Módulo - AQL'
                },
                series: datasetsModuloAQL
            }));

            var datasetsModuloProceso = @json($datasetsProcesoModulos).map((dataset, index) => ({
                name: dataset.label,
                data: dataset.data,
                color: colores[index % colores.length]
            }));

            var chartModuloProcesos = Highcharts.chart('moduloChartProcesos', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Módulo - Procesos'
                },
                series: datasetsModuloProceso
            }));

            $('#modulo0').on('click', function() {
                $('#moduloChartAQL').show();
                $('#moduloChartProcesos').hide();
                chartModuloAQL.reflow();
            });

            $('#modulo1').on('click', function() {
                $('#moduloChartAQL').hide();
                $('#moduloChartProcesos').show();
                chartModuloProcesos.reflow();
            });

            $('#toggleAllModulos').on('click', function() {
                var showAll = $(this).find('input').prop('checked');
                var activeChart = $('#moduloChartAQL').is(':visible') ? chartModuloAQL : chartModuloProcesos;
                toggleVisibility([activeChart], showAll);
            });

            // Ajuste responsivo para los gráficos de módulo
            window.addEventListener('resize', function() {
                chartModuloAQL.reflow();
                chartModuloProcesos.reflow();
            });

            function toggleVisibility(charts, showAll) {
                charts.forEach(function(chart) {
                    if (chart && chart.series) {
                        chart.series.forEach(function(series) {
                            series.setVisible(showAll, false);
                        });
                        chart.redraw();
                    }
                });
            }
        });

    </script>

    <script>
        $(document).ready(function() {
            function formatWeekLabel(value) {
                const [year, week] = value.split('-');
                return `Semana: ${week}, Año: ${year}`;
            }

            var colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#409FFF'
            ];

            var chartOptionsBase = {
                chart: {
                    type: 'spline',
                    backgroundColor: 'transparent'
                },
                xAxis: {
                    categories: {!! json_encode($semanasGrafica) !!},
                    labels: {
                        formatter: function() {
                            return formatWeekLabel(this.value);
                        },
                        rotation: 0
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje'
                    },
                    min: 0,
                    tickInterval: 0.2,
                    labels: {
                        formatter: function() {
                            return this.value % 1 === 0 ? this.value.toFixed(2) + '%' : '';
                        }
                    }
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.y:.2f}%</b>'
                },
                plotOptions: {
                    line: {
                        marker: {
                            enabled: true
                        }
                    }
                }
            };

            var datasetsAQLSupervisor = @json($datasetsAQLSupervisor).map((dataset, index) => {
                return {
                    name: dataset.label,
                    data: dataset.data,
                    color: colores[index % colores.length]
                };
            });

            var chartSupervisorAQL = Highcharts.chart('supervisorChartAQL', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Supervisor - AQL'
                },
                series: datasetsAQLSupervisor
            }));

            var datasetsProcesoSupervisor = @json($datasetsProcesoSupervisor).map((dataset, index) => {
                return {
                    name: dataset.label,
                    data: dataset.data,
                    color: colores[index % colores.length]
                };
            });

            var chartSupervisorProcesos = Highcharts.chart('supervisorChartProcesos', Highcharts.merge(chartOptionsBase, {
                title: {
                    text: 'Indicador por Supervisor - Procesos'
                },
                series: datasetsProcesoSupervisor
            }));

            $('#supervisor0').on('click', function() {
                $('#supervisorChartAQL').show();
                $('#supervisorChartProcesos').hide();
                chartSupervisorAQL.reflow();
            });

            $('#supervisor1').on('click', function() {
                $('#supervisorChartAQL').hide();
                $('#supervisorChartProcesos').show();
                chartSupervisorProcesos.reflow();
            });

            function toggleVisibility(charts, showAll) {
                charts.forEach(function(chart) {
                    if (chart && chart.series) {
                        chart.series.forEach(function(series) {
                            series.setVisible(showAll, false);
                        });
                        chart.redraw();
                    }
                });
            }

            $('#toggleAllSupervisores').on('click', function() {
                var showAll = $(this).find('input').prop('checked');
                var activeChart = $('#supervisorChartAQL').is(':visible') ? chartSupervisorAQL : chartSupervisorProcesos;
                toggleVisibility([activeChart], showAll);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartSupervisorAQL.reflow();
                chartSupervisorProcesos.reflow();
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
            const tableIds = ['#tablaDinamico', '#tablaDinamico2', '#tablaDinamico3', '#tablaDinamico4', '#tablaClientes', '#clientesDetalleTabla'];
            
            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 10,
                        autoWidth: false,
                        responsive: true,
                        columnDefs: [
                            {
                                searchable: false,
                                orderable: false,
                            },
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
