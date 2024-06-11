@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard General Planta 2 - San Bartolo </h2>
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
                            <h2 class="card-title"><a href="{{ route('dashboar.dashboardPlanta2Detalle') }}">Intimark Mensual General</a></h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i> AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"> <i class="tim-icons icon-vector text-primary"></i> Procesos</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartAQL"></canvas>
                        <canvas id="chartProcesos" style="display: none;"></canvas>
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
                            <h2 class="card-title" >Indicador Mensual por Cliente</h2>
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
                    <form action="{{ route('dashboar.detalleXModuloPlanta2') }}" method="GET">
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
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializa las gráficas
        var ctxAQL = document.getElementById('chartAQL').getContext('2d');
        var chartAQL = new Chart(ctxAQL, {
            type: 'line',
            data: {
                labels: {!! json_encode($fechas) !!},
                datasets: [{
                    label: 'AQL',
                    data: {!! json_encode($porcentajesAQL) !!},
                    borderColor: '#f96332',
                    backgroundColor: 'rgba(249, 99, 50, 0.4)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false // Ocultar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'DD-MM-YYYY'
                            }
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 45
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

        var ctxProcesos = document.getElementById('chartProcesos').getContext('2d');
        var chartProcesos = new Chart(ctxProcesos, {
            type: 'line',
            data: {
                labels: {!! json_encode($fechas) !!},
                datasets: [{
                    label: 'Procesos',
                    data: {!! json_encode($porcentajesProceso) !!},
                    borderColor: '#1f8ef1',
                    backgroundColor: 'rgba(31, 142, 241, 0.4)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false // Ocultar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'DD-MM-YYYY'
                            }
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 45
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

        // Manejar el cambio de gráficos
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
