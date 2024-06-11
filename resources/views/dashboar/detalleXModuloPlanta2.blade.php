@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')

    <div class="row"> 
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-zoom-split text-success"></i> Seleccion de Cliente por Modulo Planta 2 - San Bartolo</h3>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <h4>Cliente seleccionado: {{ $clienteBusqueda }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12">
            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
            <form action="{{ route('dashboar.detalleXModuloPlanta2') }}" method="GET" id="filterForm">
                <input type="hidden" name="clienteBusqueda" id="hiddenClienteBusqueda" value="{{ $clienteBusqueda }}">
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
                        this.action = "{{ route('dashboar.detalleXModuloPlanta2') }}?fecha_inicio=" + fechaInicioValue + "&fecha_fin=" + fechaFinValue;
                    });
                });

            </script>
            <hr>     
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Promedio General Semanal</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;"> <!-- Ajusta esta altura según tus necesidades -->
                        <canvas id="promedioGeneralChart"></canvas>
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
                            <h2 class="card-title">Indicador Semanal por Módulo</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="moduloAQLButton">
                                    <input type="radio" name="moduloOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="moduloProcesoButton">
                                    <input type="radio" class="d-none d-sm-none" name="moduloOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
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
                        <canvas id="moduloChartAQL"></canvas>
                        <canvas id="moduloChartProcesos" style="display: none;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title">Módulo por: <i class="tim-icons icon-app text-success"></i> AQL y <i class="tim-icons icon-vector text-primary"></i> PROCESO</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-fixed-header" id="tablaDinamico">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    @foreach ($semanas as $semana)
                                        @php
                                            list($year, $week) = explode('-', $semana);
                                        @endphp
                                        <th colspan="2" style="text-align: center">Semana {{ $week }}, Año {{ $year }}</th>
                                    @endforeach
                                    <th>% General AQL</th>
                                    <th>% General Proceso</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    @foreach ($semanas as $semana)
                                        <th>Porcentaje AQL</th>
                                        <th>Porcentaje Proceso</th>
                                    @endforeach
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datosCombinados as $modulo => $datos)
                                    <tr>
                                        <td>{{ $modulo }}</td>
                                        @foreach ($semanas as $semana)
                                            <td>
                                                @if (isset($datos['semanas'][$semana]['cantidad_auditada_AQL']) && $datos['semanas'][$semana]['cantidad_auditada_AQL'] > 0)
                                                    {{ number_format(($datos['semanas'][$semana]['cantidad_rechazada_AQL'] / $datos['semanas'][$semana]['cantidad_auditada_AQL']) * 100, 2) }}%
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($datos['semanas'][$semana]['cantidad_auditada_Proceso']) && $datos['semanas'][$semana]['cantidad_auditada_Proceso'] > 0)
                                                    {{ number_format(($datos['semanas'][$semana]['cantidad_rechazada_Proceso'] / $datos['semanas'][$semana]['cantidad_auditada_Proceso']) * 100, 2) }}%
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            @if ($datos['cantidad_total_auditada_AQL'] > 0)
                                                {{ number_format(($datos['cantidad_total_rechazada_AQL'] / $datos['cantidad_total_auditada_AQL']) * 100, 2) }}%
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($datos['cantidad_total_auditada_Proceso'] > 0)
                                                {{ number_format(($datos['cantidad_total_rechazada_Proceso'] / $datos['cantidad_total_auditada_Proceso']) * 100, 2) }}%
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <td style="background-color: #000; color: #fff;">Promedio General</td>
                                @foreach ($semanas as $semana)
                                    <td style="background-color: #000; color: #fff;">
                                        @if ($promediosGenerales[$semana]['total_auditada_AQL'] > 0)
                                            {{ number_format(($promediosGenerales[$semana]['total_rechazada_AQL'] / $promediosGenerales[$semana]['total_auditada_AQL']) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td style="background-color: #000; color: #fff;">
                                        @if ($promediosGenerales[$semana]['total_auditada_Proceso'] > 0)
                                            {{ number_format(($promediosGenerales[$semana]['total_rechazada_Proceso'] / $promediosGenerales[$semana]['total_auditada_Proceso']) * 100, 2) }}%
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                @endforeach
                                <td style="background-color: #000; color: #fff;"></td>
                                <td style="background-color: #000; color: #fff;"></td>
                            </tr>
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
    <style>
        /* Contenedor de la tabla para permitir el desplazamiento horizontal */
        .table-responsive {
            position: relative;
            overflow-x: auto;
        }
    
        /* Estilos para la tabla */
        .table-fixed-header {
            display: table;
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1b2f; /* Color de fondo del tema */
        }
    
        /* Estilo para las columnas de la tabla */
        .table-fixed-header th, .table-fixed-header td {
            padding: 8px 16px;
            white-space: nowrap;
            border: 1px solid #ddd;
            background-color: #1a1b2f; /* Color de fondo del tema */
        }
    
        /* Estilo para fijar la primera columna y el encabezado de la primera columna */
        .table-fixed-header thead th:first-child,
        .table-fixed-header tbody td:first-child,
        .table-fixed-header tfoot td:first-child {
            position: sticky;
            left: 0;
            z-index: 3;
            background-color: #1a1b2f; /* Color de fondo del tema */
        }
    
        /* Estilo para fijar la primera fila */
        .table-fixed-header thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background-color: #1a1b2f; /* Color de fondo del tema */
        }
    
        /* Estilo específico para la última fila fuera del tbody */
        .table-fixed-header tfoot tr td {
            background-color: #000; /* Color de fondo específico para esta fila */
            color: #fff; /* Color de texto blanco para mejor legibilidad */
        }
    </style>
    
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <!-- Script para la gráfica de Indicador Semanal por Módulo -->
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
    
            // Datos para gráfico AQL
            var ctxModuloAQL = document.getElementById('moduloChartAQL').getContext('2d');
            var datasetsAQL = Object.keys(@json($datosCombinados)).map((modulo, index) => {
                var aqlData = @json($semanas).map((semana) => {
                    if (@json($datosCombinados)[modulo].semanas[semana] && @json($datosCombinados)[modulo].semanas[semana]['cantidad_auditada_AQL'] > 0) {
                        return ((@json($datosCombinados)[modulo].semanas[semana]['cantidad_rechazada_AQL'] / @json($datosCombinados)[modulo].semanas[semana]['cantidad_auditada_AQL']) * 100).toFixed(2);
                    } else {
                        return NaN;
                    }
                });
                return {
                    label: `${modulo}`,
                    data: aqlData,
                    borderColor: colores[index % colores.length],
                    backgroundColor: colores[index % colores.length],
                    fill: false,
                    spanGaps: true,
                    lineTension: 0.4
                };
            });
    
            var chartModuloAQL = new Chart(ctxModuloAQL, {
                type: 'line',
                data: {
                    labels: @json($semanas).map((semana) => {
                        var parts = semana.split('-');
                        return 'Semana ' + parts[1] + ', Año ' + parts[0];
                    }),
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
                            labels: @json($semanas).map((semana) => {
                                var parts = semana.split('-');
                                return 'Semana ' + parts[1] + ', Año ' + parts[0];
                            })
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
    
            // Datos para gráfico Proceso
            var ctxModuloProcesos = document.getElementById('moduloChartProcesos').getContext('2d');
            var datasetsProceso = Object.keys(@json($datosCombinados)).map((modulo, index) => {
                var procesoData = @json($semanas).map((semana) => {
                    if (@json($datosCombinados)[modulo].semanas[semana] && @json($datosCombinados)[modulo].semanas[semana]['cantidad_auditada_Proceso'] > 0) {
                        return ((@json($datosCombinados)[modulo].semanas[semana]['cantidad_rechazada_Proceso'] / @json($datosCombinados)[modulo].semanas[semana]['cantidad_auditada_Proceso']) * 100).toFixed(2);
                    } else {
                        return NaN;
                    }
                });
                return {
                    label: `${modulo}`,
                    data: procesoData,
                    borderColor: colores[index % colores.length],
                    backgroundColor: colores[index % colores.length],
                    fill: false,
                    spanGaps: true,
                    lineTension: 0.4
                };
            });
    
            var chartModuloProcesos = new Chart(ctxModuloProcesos, {
                type: 'line',
                data: {
                    labels: @json($semanas).map((semana) => {
                        var parts = semana.split('-');
                        return 'Semana ' + parts[1] + ', Año ' + parts[0];
                    }),
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
                            labels: @json($semanas).map((semana) => {
                                var parts = semana.split('-');
                                return 'Semana ' + parts[1] + ', Año ' + parts[0];
                            })
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
    
            // Alternar entre gráficos
            $('#moduloAQLButton').on('click', function() {
                $('#moduloChartAQL').show();
                $('#moduloChartProcesos').hide();
                chartModuloAQL.update();
            });
    
            $('#moduloProcesoButton').on('click', function() {
                $('#moduloChartAQL').hide();
                $('#moduloChartProcesos').show();
                chartModuloProcesos.update();
            });
        });
    </script>

<!-- Script para la gráfica de Promedio General Semanal -->
<script>
    $(document).ready(function() {
        var colores = [
            'rgba(51, 226, 223, 1)', // Color del icono de Auditoria AQL
            'rgba(226, 51, 218, 1)' // Color del icono de Auditoria de Procesos
        ];

        var ctxPromedioGeneral = document.getElementById('promedioGeneralChart').getContext('2d');
        var promedioAQL = @json($semanas).map((semana) => {
            if (@json($promediosGenerales)[semana]['total_auditada_AQL'] > 0) {
                return ((@json($promediosGenerales)[semana]['total_rechazada_AQL'] / @json($promediosGenerales)[semana]['total_auditada_AQL']) * 100).toFixed(2);
            } else {
                return NaN;
            }
        });
        var promedioProceso = @json($semanas).map((semana) => {
            if (@json($promediosGenerales)[semana]['total_auditada_Proceso'] > 0) {
                return ((@json($promediosGenerales)[semana]['total_rechazada_Proceso'] / @json($promediosGenerales)[semana]['total_auditada_Proceso']) * 100).toFixed(2);
            } else {
                return NaN;
            }
        });

        var chartPromedioGeneral = new Chart(ctxPromedioGeneral, {
            type: 'line',
            data: {
                labels: @json($semanas).map((semana) => {
                    var parts = semana.split('-');
                    return 'Semana ' + parts[1] + ', Año ' + parts[0];
                }),
                datasets: [
                    {
                        label: 'Promedio AQL',
                        data: promedioAQL,
                        borderColor: colores[0],
                        backgroundColor: colores[0],
                        fill: false,
                        spanGaps: true,
                        lineTension: 0.4 // Añadir curvatura a la línea
                    },
                    {
                        label: 'Promedio Proceso',
                        data: promedioProceso,
                        borderColor: colores[1],
                        backgroundColor: colores[1],
                        fill: false,
                        spanGaps: true,
                        lineTension: 0.4 // Añadir curvatura a la línea
                    }
                ]
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
                        labels: @json($semanas).map((semana) => {
                            var parts = semana.split('-');
                            return 'Semana ' + parts[1] + ', Año ' + parts[0];
                        })
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
    });
</script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $(document).ready(function() {
            // Verifica si la tabla ya está inicializada antes de inicializarla nuevamente

            if (!$.fn.dataTable.isDataTable('#tablaDinamico')) {
                $('#tablaDinamico').DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 10,
                    autoWidth: false,
                    responsive: true,
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico2')) {
                $('#tablaDinamico2').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico3')) {
                $('#tablaDinamico3').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico4')) {
                $('#tablaDinamico4').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        });
    </script>
@endpush
