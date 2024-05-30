@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content') 
    <div class="row">
        <div class="col-md-12">
            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
            <form action="{{ route('dashboar.dashboarAProcesoAQL') }}" method="GET" id="filterForm">
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
                        this.action = "{{ route('dashboar.dashboarAProcesoAQL') }}?fecha_inicio=" + fechaInicioValue + "&fecha_fin=" + fechaFinValue;
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

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Errores por Cliente en seleccion de rango: </h2>
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
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> <i class="tim-icons icon-shape-star text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Dia actual</p>

                </div>
                <div class="card-body">
                    
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
            function formatWeekLabel(value) {
                const [year, week] = value.split('-');
                return `Semana: ${week}, Año: ${year}`;
            }

            var ctxAQL = document.getElementById('chartAQL').getContext('2d');
            var chartAQL = new Chart(ctxAQL, {
                type: 'line',
                data: {
                    labels: {!! json_encode($semanas) !!},
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
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            labels: {!! json_encode($semanas) !!},
                            ticks: {
                                callback: function(value, index, values) {
                                    return formatWeekLabel(value);
                                },
                                autoSkip: false,
                                maxRotation: 0, // Para que las etiquetas sean horizontales
                                minRotation: 0
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2, // Define el intervalo de los ticks
                                callback: function(value) {
                                    return Number(value.toFixed(2)) + '%'; // Formatea los valores a dos decimales
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
                    labels: {!! json_encode($semanas) !!},
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
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            labels: {!! json_encode($semanas) !!},
                            ticks: {
                                callback: function(value, index, values) {
                                    return formatWeekLabel(value);
                                },
                                autoSkip: false,
                                maxRotation: 0, // Para que las etiquetas sean horizontales
                                minRotation: 0
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2, // Define el intervalo de los ticks
                                callback: function(value) {
                                    return Number(value.toFixed(2)) + '%'; // Formatea los valores a dos decimales
                                }
                            }
                        }]
                    }
                }
            });

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
                    labels: @json($semanasGrafica),
                    datasets: datasetsAQL
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            labels: @json($semanasGrafica),
                            ticks: {
                                callback: function(value, index, values) {
                                    return formatWeekLabel(value);
                                },
                                autoSkip: false,
                                maxRotation: 0, // Para que las etiquetas sean horizontales
                                minRotation: 0
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2, // Define el intervalo de los ticks
                                callback: function(value) {
                                    return Number(value.toFixed(2)) + '%'; // Formatea los valores a dos decimales
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
                    labels: @json($semanasGrafica),
                    datasets: datasetsProceso
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            type: 'category',
                            labels: @json($semanasGrafica),
                            ticks: {
                                callback: function(value, index, values) {
                                    return formatWeekLabel(value);
                                },
                                autoSkip: false,
                                maxRotation: 0, // Para que las etiquetas sean horizontales
                                minRotation: 0
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2, // Define el intervalo de los ticks
                                callback: function(value) {
                                    return Number(value.toFixed(2)) + '%'; // Formatea los valores a dos decimales
                                }
                            }
                        }]
                    }
                }
            });

            $('#cliente0').on('click', function() {
                $('#clienteChartAQL').show();
                $('#clienteChartProcesos').hide();
                chartClienteAQL.update();
            });

            $('#cliente1').on('click', function() {
                $('#clienteChartAQL').hide();
                $('#clienteChartProcesos').show();
                chartClienteProcesos.update();
            });
        });
    </script>
@endpush