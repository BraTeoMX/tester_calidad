@extends('layouts.app', ['pageSlug' => 'dashboardComparativoClientes', 'titlePage' => __('Dashboard Comparativo Clientes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title text-center font-weight-bold">Dashboard: COMPARATIVO CLIENTES</h2>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form action="{{ route('dashboarComparativaModulo.semanaComparativaGeneral') }}" method="GET" id="filterForm">
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

            <div class="card">
                <div class="card-header card-header-primary">
                    <!-- Tabs para los clientes -->
                    <ul class="nav nav-tabs" id="clienteTabs" role="tablist">
                        @foreach($modulosPorClienteYEstilo as $cliente => $estilos)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $loop->index }}" data-toggle="tab" href="#cliente-{{ $loop->index }}" role="tab" aria-controls="cliente-{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $cliente }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="tab-content" id="clienteTabContent">
                    @foreach($modulosPorClienteYEstilo as $cliente => $estilos)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="cliente-{{ $loop->index }}" role="tabpanel" aria-labelledby="tab-{{ $loop->index }}">
                        <!-- Subpestañas para General, Planta 1 y Planta 2 -->
                        <hr>
                        <ul class="nav nav-pills mb-3" id="pills-tab-planta-{{ $loop->index }}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-general-tab-{{ $loop->index }}" data-toggle="pill" href="#pills-general-{{ $loop->index }}" role="tab" aria-controls="pills-general-{{ $loop->index }}" aria-selected="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-planta1-tab-{{ $loop->index }}" data-toggle="pill" href="#pills-planta1-{{ $loop->index }}" role="tab" aria-controls="pills-planta1-{{ $loop->index }}" aria-selected="false">Planta 1 - Ixtlahuaca</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-planta2-tab-{{ $loop->index }}" data-toggle="pill" href="#pills-planta2-{{ $loop->index }}" role="tab" aria-controls="pills-planta2-{{ $loop->index }}" aria-selected="false">Planta 2 - San Bartolo</a>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="pills-tabContent-planta-{{ $loop->index }}">
                            <!-- CONTENIDO GENERAL -->
                            <div class="tab-pane fade show active" id="pills-general-{{ $loop->index }}" role="tabpanel" aria-labelledby="pills-general-tab-{{ $loop->index }}">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4>Información del Cliente: {{ $cliente }}</h4>
                                    </div>
                                    <div class="card-body">
                                        @foreach($estilos as $estilo => $modulosEstilo)
                                        <div class="row mt-4">
                                            <!-- Tarjeta Resumen por Semana -->
                                            <div class="col-lg-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Resumen por Semana</h5>
                                                    </div>
                                                    <div class="table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                        <table class="table tablesorter" id="tabla-resumen-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                            <thead>
                                                                <tr>
                                                                    <th>Semana</th>
                                                                    <th>% AQL</th>
                                                                    <th>% Proceso</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($semanas as $i => $semana)
                                                                <tr>
                                                                    <td>Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})</td>
                                                                    <td class="{{ $modulosEstilo['totales_aql_colores'][$i] ? 'bg-rojo-oscuro' : '' }}">
                                                                        {{ $modulosEstilo['totales_aql'][$i] }}
                                                                    </td>
                                                                    <td class="{{ $modulosEstilo['totales_proceso_colores'][$i] ? 'bg-amarillo-oscuro' : '' }}">
                                                                        {{ $modulosEstilo['totales_proceso'][$i] }}
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Tarjeta Gráfico -->
                                            <div class="col-lg-9">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Gráfico</h5>
                                                    </div>
                                                    <!-- Div para el gráfico con ID único -->
                                                    <div id="chart-{{ $loop->parent->index }}-{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h5>Estilo: {{ $estilo }}</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter" id="tabla-detalles-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Módulo</th>
                                                            @foreach($semanas as $semana)
                                                                <th colspan="2" class="text-center">
                                                                    Semana {{ $semana['semana'] }} <br> ({{ $semana['anio'] }})
                                                                </th>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            @foreach($semanas as $semana)
                                                                <th>% AQL</th>
                                                                <th>% Proceso</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($modulosEstilo['modulos'] as $modulo)
                                                        <tr>
                                                            <td>{{ $modulo['modulo'] }}</td>
                                                            @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                                <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                    {{ $porcentajes['aql'] }}
                                                                </td>
                                                                <td class="{{ $porcentajes['proceso_color'] ? 'bg-amarillo-oscuro' : '' }}">
                                                                    {{ $porcentajes['proceso'] }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th>Total</th>
                                                            @foreach($modulosEstilo['totales_aql'] as $index => $totalAql)
                                                                <td class="{{ $modulosEstilo['totales_aql_colores'][$index] ? 'bg-rojo-oscuro' : '' }}">
                                                                    {{ $totalAql }}
                                                                </td>
                                                                <td class="{{ $modulosEstilo['totales_proceso_colores'][$index] ? 'bg-amarillo-oscuro' : '' }}">
                                                                    {{ $modulosEstilo['totales_proceso'][$index] }}
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                            
                                        </div>                                
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- CONTENIDO PLANTA 1 -->
                            <div class="tab-pane fade" id="pills-planta1-{{ $loop->index }}" role="tabpanel" aria-labelledby="pills-planta1-tab-{{ $loop->index }}">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4>Información del Cliente: {{ $cliente }}</h4>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($modulosPorClienteYEstiloPlanta1[$cliente]))
                                            @foreach($modulosPorClienteYEstiloPlanta1[$cliente] as $estilo => $modulosEstilo)
                                            <div class="row mt-4">
                                                <!-- Tarjeta Resumen por Semana -->
                                                <div class="col-lg-3">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Resumen por Semana</h5>
                                                        </div>
                                                        <div class="table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                            <table class="table tablesorter" id="tabla-resumen-planta1-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Semana</th>
                                                                        <th>% AQL</th>
                                                                        <th>% Proceso</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($semanas as $i => $semana)
                                                                    <tr>
                                                                        <td>Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})</td>
                                                                        <td class="{{ $modulosEstilo['totales_aql_colores'][$i] ? 'bg-rojo-oscuro' : '' }}">
                                                                            {{ $modulosEstilo['totales_aql'][$i] }}
                                                                        </td>
                                                                        <td class="{{ $modulosEstilo['totales_proceso_colores'][$i] ? 'bg-amarillo-oscuro' : '' }}">
                                                                            {{ $modulosEstilo['totales_proceso'][$i] }}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Tarjeta Gráfico -->
                                                <div class="col-lg-9">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Gráfico</h5>
                                                        </div>
                                                        <!-- Div para el gráfico con ID único -->
                                                        <div id="chart-planta1-{{ $loop->parent->index }}-{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <h5>Estilo: {{ $estilo }}</h5>
                                                </div>
                                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                    <table class="table tablesorter" id="tabla-detalles-planta1-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2">Módulo</th>
                                                                @foreach($semanas as $semana)
                                                                    <th colspan="2" class="text-center">
                                                                        Semana {{ $semana['semana'] }} <br> ({{ $semana['anio'] }})
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                            <tr>
                                                                @foreach($semanas as $semana)
                                                                    <th>% AQL</th>
                                                                    <th>% Proceso</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($modulosEstilo['modulos'] as $modulo)
                                                            <tr>
                                                                <td>{{ $modulo['modulo'] }}</td>
                                                                @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                                    <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                        {{ $porcentajes['aql'] }}
                                                                    </td>
                                                                    <td class="{{ $porcentajes['proceso_color'] ? 'bg-amarillo-oscuro' : '' }}">
                                                                        {{ $porcentajes['proceso'] }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Total</th>
                                                                @foreach($modulosEstilo['totales_aql'] as $index => $totalAql)
                                                                    <td class="{{ $modulosEstilo['totales_aql_colores'][$index] ? 'bg-rojo-oscuro' : '' }}">
                                                                        {{ $totalAql }}
                                                                    </td>
                                                                    <td class="{{ $modulosEstilo['totales_proceso_colores'][$index] ? 'bg-amarillo-oscuro' : '' }}">
                                                                        {{ $modulosEstilo['totales_proceso'][$index] }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>                                
                                            @endforeach
                                        @else
                                            <p>No hay datos para la Planta 1.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- CONTENIDO PLANTA 2 -->
                            <div class="tab-pane fade" id="pills-planta2-{{ $loop->index }}" role="tabpanel" aria-labelledby="pills-planta2-tab-{{ $loop->index }}">
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h4>Información del Cliente: {{ $cliente }}</h4>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($modulosPorClienteYEstiloPlanta2[$cliente]))
                                            @foreach($modulosPorClienteYEstiloPlanta2[$cliente] as $estilo => $modulosEstilo)
                                            <div class="row mt-4">
                                                <!-- Tarjeta Resumen por Semana -->
                                                <div class="col-lg-3">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Resumen por Semana</h5>
                                                        </div>
                                                        <div class="table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                            <table class="table tablesorter" id="tabla-resumen-planta2-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Semana</th>
                                                                        <th>% AQL</th>
                                                                        <th>% Proceso</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($semanas as $i => $semana)
                                                                    <tr>
                                                                        <td>Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})</td>
                                                                        <td class="{{ $modulosEstilo['totales_aql_colores'][$i] ? 'bg-rojo-oscuro' : '' }}">
                                                                            {{ $modulosEstilo['totales_aql'][$i] }}
                                                                        </td>
                                                                        <td class="{{ $modulosEstilo['totales_proceso_colores'][$i] ? 'bg-amarillo-oscuro' : '' }}">
                                                                            {{ $modulosEstilo['totales_proceso'][$i] }}
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Tarjeta Gráfico -->
                                                <div class="col-lg-9">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h5>Gráfico</h5>
                                                        </div>
                                                        <!-- Div para el gráfico con ID único -->
                                                        <div id="chart-planta2-{{ $loop->parent->index }}-{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card mt-3">
                                                <div class="card-header">
                                                    <h5>Estilo: {{ $estilo }}</h5>
                                                </div>
                                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                    <table class="table tablesorter" id="tabla-detalles-planta2-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                        <thead>
                                                            <tr>
                                                                <th rowspan="2">Módulo</th>
                                                                @foreach($semanas as $semana)
                                                                    <th colspan="2" class="text-center">
                                                                        Semana {{ $semana['semana'] }} <br> ({{ $semana['anio'] }})
                                                                    </th>
                                                                @endforeach
                                                            </tr>
                                                            <tr>
                                                                @foreach($semanas as $semana)
                                                                    <th>% AQL</th>
                                                                    <th>% Proceso</th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($modulosEstilo['modulos'] as $modulo)
                                                            <tr>
                                                                <td>{{ $modulo['modulo'] }}</td>
                                                                @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                                    <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                        {{ $porcentajes['aql'] }}
                                                                    </td>
                                                                    <td class="{{ $porcentajes['proceso_color'] ? 'bg-amarillo-oscuro' : '' }}">
                                                                        {{ $porcentajes['proceso'] }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Total</th>
                                                                @foreach($modulosEstilo['totales_aql'] as $index => $totalAql)
                                                                    <td class="{{ $modulosEstilo['totales_aql_colores'][$index] ? 'bg-rojo-oscuro' : '' }}">
                                                                        {{ $totalAql }}
                                                                    </td>
                                                                    <td class="{{ $modulosEstilo['totales_proceso_colores'][$index] ? 'bg-amarillo-oscuro' : '' }}">
                                                                        {{ $modulosEstilo['totales_proceso'][$index] }}
                                                                    </td>
                                                                @endforeach
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                
                                            </div>                                
                                            @endforeach
                                        @else
                                            <p>No hay datos para la Planta 2.</p>
                                        @endif
                                    </div>
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
        .bg-rojo-oscuro {
            background-color: #8B0000; /* Rojo oscuro */
            color: white; /* Texto blanco para contraste */
        }

        .bg-amarillo-oscuro {
            background-color: #918305; /* Rojo oscuro */
            color: white; /* Texto blanco para contraste */
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

    <script>
        // Definir el tipo de ordenamiento personalizado para manejar "N/A"
        $.fn.dataTable.ext.type.order['custom-num-pre'] = function (a) {
            if (a === "N/A") return -Infinity; 
            var x = parseFloat(a);
            return isNaN(x) ? -Infinity : x;
        };
        $.fn.dataTable.ext.type.order['custom-num-desc'] = function (a, b) {
            return b - a; 
        };

        document.addEventListener("DOMContentLoaded", function () {
            // INICIALIZAR PARA GENERAL
            @foreach($modulosPorClienteYEstilo as $cliente => $estilos)
                @foreach($estilos as $estilo => $modulosEstilo)
                    (function() {
                        let resumenTableId = '#tabla-resumen-{{ $loop->parent->index }}-{{ $loop->index }}';
                        if (!$.fn.DataTable.isDataTable(resumenTableId)) {
                            $(resumenTableId).DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: true,
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        text: 'Exportar a Excel',
                                        className: 'btn btn-success' // Clase personalizada para el botón
                                    },
                                    {
                                        extend: 'copy',
                                        text: 'Copiar Tabla' // Texto personalizado
                                    }
                                ],
                                columnDefs: [
                                    {
                                        targets: 0, // La primera columna
                                        type: "string" // Ordenar como texto
                                    },
                                    {
                                        targets: "_all", // Todas las demás columnas
                                        type: "custom-num", // Usar el tipo de orden personalizado
                                        render: function (data, type, row) {
                                            // Renderizar "N/A" correctamente
                                            return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                        }
                                    }
                                ]
                            });
                        }
                    })();

                    (function() {
                        let detallesTableId = '#tabla-detalles-{{ $loop->parent->index }}-{{ $loop->index }}';
                        if (!$.fn.DataTable.isDataTable(detallesTableId)) {
                            $(detallesTableId).DataTable({
                                paging: true,
                                searching: true,
                                ordering: true,
                                responsive: false,
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        text: 'Exportar a Excel',
                                        className: 'btn btn-success' // Clase personalizada para el botón
                                    },
                                    {
                                        extend: 'copy',
                                        text: 'Copiar Tabla' // Texto personalizado
                                    }
                                ],
                                columnDefs: [
                                    {
                                        targets: 0, // La primera columna
                                        type: "string" // Ordenar como texto
                                    },
                                    {
                                        targets: "_all", // Todas las demás columnas
                                        type: "custom-num", // Usar el tipo de orden personalizado
                                        render: function (data, type, row) {
                                            // Renderizar "N/A" correctamente
                                            return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                        }
                                    }
                                ]
                            });
                        }
                    })();
                @endforeach
            @endforeach

            // INICIALIZAR PARA PLANTA 1 (si existe)
            @if(isset($modulosPorClienteYEstiloPlanta1))
                @foreach($modulosPorClienteYEstiloPlanta1 as $cliente => $estilos)
                    @foreach($estilos as $estilo => $modulosEstilo)
                        (function() {
                            let resumenTableId = '#tabla-resumen-planta1-{{ $loop->parent->index }}-{{ $loop->index }}';
                            if (!$.fn.DataTable.isDataTable(resumenTableId)) {
                                $(resumenTableId).DataTable({ 
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    responsive: true,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            text: 'Exportar a Excel',
                                            className: 'btn btn-success' // Clase personalizada para el botón
                                        },
                                        {
                                            extend: 'copy',
                                            text: 'Copiar Tabla' // Texto personalizado
                                        }
                                    ],
                                    columnDefs: [
                                        {
                                            targets: 0, // La primera columna
                                            type: "string" // Ordenar como texto
                                        },
                                        {
                                            targets: "_all", // Todas las demás columnas
                                            type: "custom-num", // Usar el tipo de orden personalizado
                                            render: function (data, type, row) {
                                                // Renderizar "N/A" correctamente
                                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                            }
                                        }
                                    ]
                                });
                            }
                        })();

                        (function() {
                            let detallesTableId = '#tabla-detalles-planta1-{{ $loop->parent->index }}-{{ $loop->index }}';
                            if (!$.fn.DataTable.isDataTable(detallesTableId)) {
                                $(detallesTableId).DataTable({ 
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    responsive: false,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            text: 'Exportar a Excel',
                                            className: 'btn btn-success' // Clase personalizada para el botón
                                        },
                                        {
                                            extend: 'copy',
                                            text: 'Copiar Tabla' // Texto personalizado
                                        }
                                    ],
                                    columnDefs: [
                                        {
                                            targets: 0, // La primera columna
                                            type: "string" // Ordenar como texto
                                        },
                                        {
                                            targets: "_all", // Todas las demás columnas
                                            type: "custom-num", // Usar el tipo de orden personalizado
                                            render: function (data, type, row) {
                                                // Renderizar "N/A" correctamente
                                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                            }
                                        }
                                    ]
                                });
                            }
                        })();
                    @endforeach
                @endforeach
            @endif

            // INICIALIZAR PARA PLANTA 2 (si existe)
            @if(isset($modulosPorClienteYEstiloPlanta2))
                @foreach($modulosPorClienteYEstiloPlanta2 as $cliente => $estilos)
                    @foreach($estilos as $estilo => $modulosEstilo)
                        (function() {
                            let resumenTableId = '#tabla-resumen-planta2-{{ $loop->parent->index }}-{{ $loop->index }}';
                            if (!$.fn.DataTable.isDataTable(resumenTableId)) {
                                $(resumenTableId).DataTable({ 
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    responsive: true,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            text: 'Exportar a Excel',
                                            className: 'btn btn-success' // Clase personalizada para el botón
                                        },
                                        {
                                            extend: 'copy',
                                            text: 'Copiar Tabla' // Texto personalizado
                                        }
                                    ],
                                    columnDefs: [
                                        {
                                            targets: 0, // La primera columna
                                            type: "string" // Ordenar como texto
                                        },
                                        {
                                            targets: "_all", // Todas las demás columnas
                                            type: "custom-num", // Usar el tipo de orden personalizado
                                            render: function (data, type, row) {
                                                // Renderizar "N/A" correctamente
                                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                            }
                                        }
                                    ]
                                });
                            }
                        })();

                        (function() {
                            let detallesTableId = '#tabla-detalles-planta2-{{ $loop->parent->index }}-{{ $loop->index }}';
                            if (!$.fn.DataTable.isDataTable(detallesTableId)) {
                                $(detallesTableId).DataTable({ 
                                    paging: true,
                                    searching: true,
                                    ordering: true,
                                    responsive: false,
                                    dom: 'Bfrtip',
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            text: 'Exportar a Excel',
                                            className: 'btn btn-success' // Clase personalizada para el botón
                                        },
                                        {
                                            extend: 'copy',
                                            text: 'Copiar Tabla' // Texto personalizado
                                        }
                                    ],
                                    columnDefs: [
                                        {
                                            targets: 0, // La primera columna
                                            type: "string" // Ordenar como texto
                                        },
                                        {
                                            targets: "_all", // Todas las demás columnas
                                            type: "custom-num", // Usar el tipo de orden personalizado
                                            render: function (data, type, row) {
                                                // Renderizar "N/A" correctamente
                                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                            }
                                        }
                                    ]
                                });
                            }
                        })();
                    @endforeach
                @endforeach
            @endif
        });
    </script>
    
    <!-- Highcharts JavaScript -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // GRAFICOS PARA GENERAL
            @foreach($modulosPorClienteYEstilo as $cliente => $estilos)
                @foreach($estilos as $estilo => $modulosEstilo)
                    // categories para general
                    const categories_{{ $loop->parent->index }}_{{ $loop->index }} = [
                        @foreach($semanas as $i => $semana)
                            "Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})",
                        @endforeach
                    ];
    
                    // aql para general
                    const aql_{{ $loop->parent->index }}_{{ $loop->index }} = [
                        @foreach($modulosEstilo['totales_aql'] as $totalAql)
                            {{ is_numeric($totalAql) ? $totalAql : 'null' }},
                        @endforeach
                    ];
    
                    // proceso para general
                    const proceso_{{ $loop->parent->index }}_{{ $loop->index }} = [
                        @foreach($modulosEstilo['totales_proceso'] as $totalProceso)
                            {{ is_numeric($totalProceso) ? $totalProceso : 'null' }},
                        @endforeach
                    ];
    
                    // Cálculo maxY para general
                    const allData_{{ $loop->parent->index }}_{{ $loop->index }} = aql_{{ $loop->parent->index }}_{{ $loop->index }}.concat(proceso_{{ $loop->parent->index }}_{{ $loop->index }}).filter(v => v !== null);
                    const maxY_{{ $loop->parent->index }}_{{ $loop->index }} = allData_{{ $loop->parent->index }}_{{ $loop->index }}.length > 0 ? Math.ceil(Math.max(...allData_{{ $loop->parent->index }}_{{ $loop->index }})) + 5 : 10;
    
                    Highcharts.chart("chart-{{ $loop->parent->index }}-{{ $loop->index }}", {
                        chart: {
                            backgroundColor: 'transparent',
                            style: { fontFamily: 'Arial' }
                        },
                        title: {
                            text: "Porcentajes Semanales",
                            style: { fontFamily: 'Arial' }
                        },
                        xAxis: {
                            categories: categories_{{ $loop->parent->index }}_{{ $loop->index }},
                            title: { text: "Semanas", style: { fontFamily: 'Arial' } },
                            labels: { style: { fontFamily: 'Arial' } }
                        },
                        yAxis: {
                            title: { text: "Porcentaje (%)", style: { fontFamily: 'Arial' } },
                            min: 0,
                            max: maxY_{{ $loop->parent->index }}_{{ $loop->index }},
                            labels: { style: { fontFamily: 'Arial' } }
                        },
                        series: [
                            {
                                name: "% AQL",
                                type: 'line',
                                data: aql_{{ $loop->parent->index }}_{{ $loop->index }},
                                color: "#28a745",
                                zIndex: 2,
                                marker: { enabled: true, radius: 4 }
                            },
                            {
                                name: "% Proceso",
                                type: 'column',
                                data: proceso_{{ $loop->parent->index }}_{{ $loop->index }},
                                color: "#007bff",
                                zIndex: 1
                            }
                        ],
                        tooltip: {
                            shared: true,
                            valueSuffix: "%",
                            style: { fontFamily: 'Arial' }
                        },
                        credits: { enabled: false }
                    });
                @endforeach
            @endforeach
    
            // GRAFICOS PARA PLANTA 1
            @if(isset($modulosPorClienteYEstiloPlanta1))
                @foreach($modulosPorClienteYEstiloPlanta1 as $cliente => $estilos)
                    @foreach($estilos as $estilo => $modulosEstilo)
                        // categories para planta 1
                        const categories_planta1_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($semanas as $i => $semana)
                                "Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})",
                            @endforeach
                        ];
    
                        // aql para planta 1
                        const aql_planta1_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($modulosEstilo['totales_aql'] as $totalAql)
                                {{ is_numeric($totalAql) ? $totalAql : 'null' }},
                            @endforeach
                        ];
    
                        // proceso para planta 1
                        const proceso_planta1_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($modulosEstilo['totales_proceso'] as $totalProceso)
                                {{ is_numeric($totalProceso) ? $totalProceso : 'null' }},
                            @endforeach
                        ];
    
                        // Cálculo maxY para planta 1
                        const allData_planta1_{{ $loop->parent->index }}_{{ $loop->index }} = aql_planta1_{{ $loop->parent->index }}_{{ $loop->index }}.concat(proceso_planta1_{{ $loop->parent->index }}_{{ $loop->index }}).filter(v => v !== null);
                        const maxY_planta1_{{ $loop->parent->index }}_{{ $loop->index }} = allData_planta1_{{ $loop->parent->index }}_{{ $loop->index }}.length > 0 ? Math.ceil(Math.max(...allData_planta1_{{ $loop->parent->index }}_{{ $loop->index }})) + 5 : 10;
    
                        Highcharts.chart("chart-planta1-{{ $loop->parent->index }}-{{ $loop->index }}", {
                            chart: {
                                backgroundColor: 'transparent',
                                style: { fontFamily: 'Arial' }
                            },
                            title: {
                                text: "Porcentajes Semanales",
                                style: { fontFamily: 'Arial' }
                            },
                            xAxis: {
                                categories: categories_planta1_{{ $loop->parent->index }}_{{ $loop->index }},
                                title: { text: "Semanas", style: { fontFamily: 'Arial' } },
                                labels: { style: { fontFamily: 'Arial' } }
                            },
                            yAxis: {
                                title: { text: "Porcentaje (%)", style: { fontFamily: 'Arial' } },
                                min: 0,
                                max: maxY_planta1_{{ $loop->parent->index }}_{{ $loop->index }},
                                labels: { style: { fontFamily: 'Arial' } }
                            },
                            series: [
                                {
                                    name: "% AQL",
                                    type: 'line',
                                    data: aql_planta1_{{ $loop->parent->index }}_{{ $loop->index }},
                                    color: "#28a745",
                                    zIndex: 2,
                                    marker: { enabled: true, radius: 4 }
                                },
                                {
                                    name: "% Proceso",
                                    type: 'column',
                                    data: proceso_planta1_{{ $loop->parent->index }}_{{ $loop->index }},
                                    color: "#007bff",
                                    zIndex: 1
                                }
                            ],
                            tooltip: {
                                shared: true,
                                valueSuffix: "%",
                                style: { fontFamily: 'Arial' }
                            },
                            credits: { enabled: false }
                        });
                    @endforeach
                @endforeach
            @endif
    
            // GRAFICOS PARA PLANTA 2
            @if(isset($modulosPorClienteYEstiloPlanta2))
                @foreach($modulosPorClienteYEstiloPlanta2 as $cliente => $estilos)
                    @foreach($estilos as $estilo => $modulosEstilo)
                        // categories para planta 2
                        const categories_planta2_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($semanas as $i => $semana)
                                "Semana {{ $semana['semana'] }} ({{ $semana['anio'] }})",
                            @endforeach
                        ];
    
                        // aql para planta 2
                        const aql_planta2_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($modulosEstilo['totales_aql'] as $totalAql)
                                {{ is_numeric($totalAql) ? $totalAql : 'null' }},
                            @endforeach
                        ];
    
                        // proceso para planta 2
                        const proceso_planta2_{{ $loop->parent->index }}_{{ $loop->index }} = [
                            @foreach($modulosEstilo['totales_proceso'] as $totalProceso)
                                {{ is_numeric($totalProceso) ? $totalProceso : 'null' }},
                            @endforeach
                        ];
    
                        // Cálculo maxY para planta 2
                        const allData_planta2_{{ $loop->parent->index }}_{{ $loop->index }} = aql_planta2_{{ $loop->parent->index }}_{{ $loop->index }}.concat(proceso_planta2_{{ $loop->parent->index }}_{{ $loop->index }}).filter(v => v !== null);
                        const maxY_planta2_{{ $loop->parent->index }}_{{ $loop->index }} = allData_planta2_{{ $loop->parent->index }}_{{ $loop->index }}.length > 0 ? Math.ceil(Math.max(...allData_planta2_{{ $loop->parent->index }}_{{ $loop->index }})) + 5 : 10;
    
                        Highcharts.chart("chart-planta2-{{ $loop->parent->index }}-{{ $loop->index }}", {
                            chart: {
                                backgroundColor: 'transparent',
                                style: { fontFamily: 'Arial' }
                            },
                            title: {
                                text: "Porcentajes Semanales",
                                style: { fontFamily: 'Arial' }
                            },
                            xAxis: {
                                categories: categories_planta2_{{ $loop->parent->index }}_{{ $loop->index }},
                                title: { text: "Semanas", style: { fontFamily: 'Arial' } },
                                labels: { style: { fontFamily: 'Arial' } }
                            },
                            yAxis: {
                                title: { text: "Porcentaje (%)", style: { fontFamily: 'Arial' } },
                                min: 0,
                                max: maxY_planta2_{{ $loop->parent->index }}_{{ $loop->index }},
                                labels: { style: { fontFamily: 'Arial' } }
                            },
                            series: [
                                {
                                    name: "% AQL",
                                    type: 'line',
                                    data: aql_planta2_{{ $loop->parent->index }}_{{ $loop->index }},
                                    color: "#28a745",
                                    zIndex: 2,
                                    marker: { enabled: true, radius: 4 }
                                },
                                {
                                    name: "% Proceso",
                                    type: 'column',
                                    data: proceso_planta2_{{ $loop->parent->index }}_{{ $loop->index }},
                                    color: "#007bff",
                                    zIndex: 1
                                }
                            ],
                            tooltip: {
                                shared: true,
                                valueSuffix: "%",
                                style: { fontFamily: 'Arial' }
                            },
                            credits: { enabled: false }
                        });
                    @endforeach
                @endforeach
            @endif
        });
    </script>

@endpush
