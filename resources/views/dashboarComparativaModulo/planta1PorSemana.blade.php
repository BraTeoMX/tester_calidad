@extends('layouts.app', ['pageSlug' => 'dashboardCostosNoCalidad', 'titlePage' => __('Dashboard Comparativo clientes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard: COMPARATIVO CLIENTES </h2>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form action="{{ route('dashboarComparativaModulo.planta1PorSemana') }}" method="GET" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Semana inicio</label>
                                            <input type="week" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicio->format('Y-\WW') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_fin">Semana fin</label>
                                            <input type="week" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $fechaFin->format('Y-\WW') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-secondary">Mostrar datos</button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    @foreach($modulosPorCliente as $cliente => $modulos)
                        <div class="col-lg-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: {{ $cliente }}</h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="tablaClienteModulo{{ $loop->index }}" class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Módulo</th>
                                                @foreach($semanas as $semana)
                                                    <th colspan="2" class="text-center">
                                                        Semana {{ $semana['inicio']->format('W') }} <br> ({{ $semana['inicio']->format('Y') }})
                                                    </th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach($semanas as $semana)
                                                    <th>% Proceso</th>
                                                    <th>% AQL</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulos as $modulo)
                                                <tr>
                                                    <td>{{ $modulo['modulo'] }}</td>
                                                    @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                        <td>{{ $porcentajes['aql'] }}</td>
                                                        <td>{{ $porcentajes['proceso'] }}</td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                @foreach($totalesPorCliente[$cliente] as $totales)
                                                    <td>{{ $totales['aql'] }}</td>
                                                    <td>{{ $totales['proceso'] }}</td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="row">
                                <!-- Contenedor para la tabla reducida -->
                                <div class="col-lg-3">
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5>Resumen por Semana</h5>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table class="table tablesorter">
                                                <thead>
                                                    <tr>
                                                        <th>Semana</th>
                                                        <th>% AQL</th>
                                                        <th>% Proceso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($semanas as $key => $semana)
                                                        <tr>
                                                            <td>Semana {{ $semana['inicio']->format('W') }} <br> ({{ $semana['inicio']->format('Y') }})</td>
                                                            <td>{{ $totalesPorCliente[$cliente][$key]['aql'] }}</td>
                                                            <td>{{ $totalesPorCliente[$cliente][$key]['proceso'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                
                                <!-- Contenedor para la gráfica -->
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div id="graficoCliente_{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    @endforeach
                </div>                
            </div>
        </div>
    </div>

    <style>
        .costo-rojo {
            color: red;
        }
        .amarillo-indicador {
            background-color: #887404 !important; /* Color amarillo oscuro */
        }
    </style>
    
@endsection

@push('js') 
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function () {

            // Inicializa DataTables en cada tabla de defectos por cliente-Modulo
            @foreach($modulosPorCliente as $index => $data)
                $('#tablaClienteModulo{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10          // Número de registros por página
                });
            @endforeach
        });
    </script>

    <!-- Highcharts JavaScript -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($modulosPorCliente as $cliente => $modulos)
                // Crear datos para las series
                const semanas_{{ $loop->index }} = [
                    @foreach($semanas as $semana)
                        "Semana {{ $semana['inicio']->format('W') }}",
                    @endforeach
                ];
    
                const aql_{{ $loop->index }} = [
                    @foreach($totalesPorCliente[$cliente] as $totales)
                        {{ $totales['aql'] === 'N/A' ? 'null' : $totales['aql'] }},
                    @endforeach
                ];
    
                const proceso_{{ $loop->index }} = [
                    @foreach($totalesPorCliente[$cliente] as $totales)
                        {{ $totales['proceso'] === 'N/A' ? 'null' : $totales['proceso'] }},
                    @endforeach
                ];
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoCliente_{{ $loop->index }}", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica (línea)
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: {{ $cliente }}"
                    },
                    xAxis: {
                        categories: semanas_{{ $loop->index }},
                        title: {
                            text: "Semanas"
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)"
                        },
                        min: 0,
                        max: 100
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_{{ $loop->index }},
                            color: "#007bff" // Color azul
                        },
                        {
                            name: "% Proceso",
                            type: 'column', // Barras para Proceso
                            data: proceso_{{ $loop->index }},
                            color: "#28a745" // Color verde
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%"
                    },
                    credits: {
                        enabled: false
                    }
                });
            @endforeach
        });
    </script>
@endpush
