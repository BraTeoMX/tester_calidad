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
                            <h2 class="card-title">Errores por Cliente en seleccion de rango: </h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="cliente0">
                                    <input type="radio" name="clienteOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i>&nbsp; AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="cliente1">
                                    <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"> <i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos</span>
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

        #chartAQL {
            display: block; /* Mostrar por defecto el primer gráfico */
        }
      </style>
@endsection

@push('js') 
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/themes/dark-unica.js"></script>
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
                                maxRotation: 0,
                                minRotation: 0,
                                maxTicksLimit: 10
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2,
                                callback: function(value) {
                                    return value % 1 === 0 ? Number(value.toFixed(2)) + '%' : '';
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ': ' + Number(tooltipItem.yLabel.toFixed(2)) + '%';
                            }
                        }
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
                                maxRotation: 0,
                                minRotation: 0,
                                maxTicksLimit: 10
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 0.2,
                                callback: function(value) {
                                    return value % 1 === 0 ? Number(value.toFixed(2)) + '%' : '';
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return data.datasets[tooltipItem.datasetIndex].label + ': ' + Number(tooltipItem.yLabel.toFixed(2)) + '%';
                            }
                        }
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

            $('#toggleAllClientes').on('click', function() {
                var showAll = $('#toggleAllClientes input').prop('checked');
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
            const tableIds = ['#tablaDinamico', '#tablaDinamico2', '#tablaDinamico3', '#tablaDinamico4', '#tablaClientes'];
            
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
