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

            <div class="card">
                <div class="card-header card-header-primary">
                    <!-- Tabs para los clientes -->
                    <ul class="nav nav-tabs" id="clienteTabs" role="tablist">
                        @foreach($modulosPorCliente as $cliente => $modulos)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $loop->index }}" data-toggle="tab" href="#cliente-{{ $loop->index }}" role="tab" aria-controls="cliente-{{ $loop->index }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $cliente }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="clienteTabContent">
                @foreach($modulosPorCliente as $cliente => $modulos)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="cliente-{{ $loop->index }}" role="tabpanel" aria-labelledby="tab-{{ $loop->index }}">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4>Cliente: {{ $cliente }}</h4>
                        </div>
                
                        <!-- Subpestañas para las secciones General, Planta 1 y Planta 2 -->
                        <ul class="nav nav-pills mb-3" id="pills-tab-{{ $loop->index }}" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab-{{ $loop->index }}" data-toggle="pill" href="#general-{{ $loop->index }}" role="tab" aria-controls="general-{{ $loop->index }}" aria-selected="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="planta1-tab-{{ $loop->index }}" data-toggle="pill" href="#planta1-{{ $loop->index }}" role="tab" aria-controls="planta1-{{ $loop->index }}" aria-selected="false">Planta 1 - Ixtlahuaca</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="planta2-tab-{{ $loop->index }}" data-toggle="pill" href="#planta2-{{ $loop->index }}" role="tab" aria-controls="planta2-{{ $loop->index }}" aria-selected="false">Planta 2 - San Bartolo</a>
                            </li>
                        </ul>
                
                        <div class="tab-content" id="pills-tabContent-{{ $loop->index }}">
                            <!-- Sección General -->
                            <div class="tab-pane fade show active" id="general-{{ $loop->index }}" role="tabpanel" aria-labelledby="general-tab-{{ $loop->index }}">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana General -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table id="tablaResumenCliente{{ $loop->index }}" class="table tablesorter">
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
                                                            <td class="{{ $totalesPorCliente[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorCliente[$cliente][$key]['aql'] }}
                                                            </td>
                                                            <td class="{{ $totalesPorCliente[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorCliente[$cliente][$key]['proceso'] }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoCliente_{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla General -->
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulos as $modulo)
                                            <tr>
                                                <td>{{ $modulo['modulo'] }}</td>
                                                @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['aql'] }}
                                                </td>
                                                <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                @foreach($totalesPorCliente[$cliente] as $totales)
                                                <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['aql'] }}
                                                </td>
                                                <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos -->
                                @isset($modulosPorClienteYEstilo[$cliente])
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    @foreach($modulosPorClienteYEstilo[$cliente] as $estilo => $modulosEstilo)
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: {{ $estilo }}</h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($modulosEstilo as $modulo)
                                                        <tr>
                                                            <td>{{ $modulo['modulo'] }}</td>
                                                            @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                            <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['aql'] }}
                                                            </td>
                                                            <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            @foreach($totalesPorClienteYEstilo[$cliente][$estilo] as $totales)
                                                            <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['aql'] }}
                                                            </td>
                                                            <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endisset
                                
                            </div>
                
                            <!-- Sección Planta 1 -->
                            <div class="tab-pane fade" id="planta1-{{ $loop->index }}" role="tabpanel" aria-labelledby="planta1-tab-{{ $loop->index }}">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana Planta 1 -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table id="tablaResumenClientePlanta1{{ $loop->index }}" class="table tablesorter">
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
                                                            <td class="{{ $totalesPorClientePlanta1[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorClientePlanta1[$cliente][$key]['aql'] }}
                                                            </td>
                                                            <td class="{{ $totalesPorClientePlanta1[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorClientePlanta1[$cliente][$key]['proceso'] }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoClientePlanta1_{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla Planta 1 -->
                                    <table id="tablaClienteModuloPlanta1{{ $loop->index }}" class="table tablesorter">
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulosPorClientePlanta1[$cliente] as $modulo)
                                            <tr>
                                                <td>{{ $modulo['modulo'] }}</td>
                                                @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['aql'] }}
                                                </td>
                                                <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                @foreach($totalesPorClientePlanta1[$cliente] as $totales)
                                                <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['aql'] }}
                                                </td>
                                                <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos Planta 1 -->
                                @isset($modulosPorClienteYEstiloPlanta1[$cliente])
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    @foreach($modulosPorClienteYEstiloPlanta1[$cliente] as $estilo => $modulosEstilo)
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: {{ $estilo }}</h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($modulosEstilo as $modulo)
                                                        <tr>
                                                            <td>{{ $modulo['modulo'] }}</td>
                                                            @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                            <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['aql'] }}
                                                            </td>
                                                            <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            @foreach($totalesPorClienteYEstiloPlanta1[$cliente][$estilo] as $totales)
                                                            <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['aql'] }}
                                                            </td>
                                                            <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </tfoot>
                                                </table>                                                
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endisset
                                
                            </div>
                
                            <!-- Sección Planta 2 -->
                            <div class="tab-pane fade" id="planta2-{{ $loop->index }}" role="tabpanel" aria-labelledby="planta2-tab-{{ $loop->index }}">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana Planta 2 -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table id="tablaResumenClientePlanta2{{ $loop->index }}" class="table tablesorter">
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
                                                            <td class="{{ $totalesPorClientePlanta2[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorClientePlanta2[$cliente][$key]['aql'] }}
                                                            </td>
                                                            <td class="{{ $totalesPorClientePlanta2[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totalesPorClientePlanta2[$cliente][$key]['proceso'] }}
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoClientePlanta2_{{ $loop->index }}" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla Planta 2 -->
                                    <table id="tablaClienteModuloPlanta2{{ $loop->index }}" class="table tablesorter">
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($modulosPorClientePlanta2[$cliente] as $modulo)
                                            <tr>
                                                <td>{{ $modulo['modulo'] }}</td>
                                                @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['aql'] }}
                                                </td>
                                                <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $porcentajes['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                @foreach($totalesPorClientePlanta2[$cliente] as $totales)
                                                <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['aql'] }}
                                                </td>
                                                <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                    {{ $totales['proceso'] }}
                                                </td>
                                                @endforeach
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos Planta 1 -->
                                @isset($modulosPorClienteYEstiloPlanta2[$cliente])
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    @foreach($modulosPorClienteYEstiloPlanta2[$cliente] as $estilo => $modulosEstilo)
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: {{ $estilo }}</h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($modulosEstilo as $modulo)
                                                        <tr>
                                                            <td>{{ $modulo['modulo'] }}</td>
                                                            @foreach($modulo['semanalPorcentajes'] as $porcentajes)
                                                            <td class="{{ $porcentajes['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['aql'] }}
                                                            </td>
                                                            <td class="{{ $porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $porcentajes['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            @foreach($totalesPorClienteYEstiloPlanta2[$cliente][$estilo] as $totales)
                                                            <td class="{{ $totales['aql_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['aql'] }}
                                                            </td>
                                                            <td class="{{ $totales['proceso_color'] ? 'bg-rojo-oscuro' : '' }}">
                                                                {{ $totales['proceso'] }}
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </tfoot>
                                                </table>                                                
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endisset
                            </div>
                        </div>
                    </div>
                </div>                
                @endforeach
            </div>            
        </div>
    </div>
    <style>
        .bg-rojo-oscuro {
            background-color: #8B0000; /* Rojo oscuro */
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

    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function () {

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

            // Inicializa DataTables en cada tabla de defectos por cliente-Modulo
            @foreach($modulosPorCliente as $index => $data)
                $('#tablaClienteModulo{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaClienteModuloPlanta1{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaClienteModuloPlanta2{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });
            @endforeach
        });
    </script>
    <script>
        $(document).ready(function () {
            // Inicializa DataTables en cada tabla resumen por cliente
            @foreach($modulosPorCliente as $index => $data)
                $('#tablaResumenCliente{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaResumenClientePlanta1{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaResumenClientePlanta2{{ $loop->index }}').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
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
                        "Semana {{ $semana['inicio']->format('W') }} - {{ $semana['inicio']->format('Y') }}",
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
    
                // Calcular rango dinámico para el eje Y
                const allData_{{ $loop->index }} = aql_{{ $loop->index }}.concat(proceso_{{ $loop->index }}).filter(v => v !== null);
                const maxY_{{ $loop->index }} = Math.ceil(Math.max(...allData_{{ $loop->index }})) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoCliente_{{ $loop->index }}", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: {{ $cliente }}",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_{{ $loop->index }},
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_{{ $loop->index }}, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_{{ $loop->index }},
                            color: "#28a745", // Color verde
                            zIndex: 2, // Mayor zIndex para sobreponerse a las barras
                            marker: {
                                enabled: true, // Mostrar puntos en la línea
                                radius: 4
                            }
                        },
                        {
                            name: "% Proceso",
                            type: 'column', // Barras para Proceso
                            data: proceso_{{ $loop->index }},
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            @endforeach
        });
    </script> 
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($modulosPorClientePlanta1 as $cliente => $modulos)
                // Crear datos para las series
                const semanas_{{ $loop->index }} = [
                    @foreach($semanas as $semana)
                        "Semana {{ $semana['inicio']->format('W') }} - {{ $semana['inicio']->format('Y') }}",
                    @endforeach
                ];
    
                const aql_{{ $loop->index }} = [
                    @foreach($totalesPorClientePlanta1[$cliente] as $totales)
                        {{ $totales['aql'] === 'N/A' ? 'null' : $totales['aql'] }},
                    @endforeach
                ];
    
                const proceso_{{ $loop->index }} = [
                    @foreach($totalesPorClientePlanta1[$cliente] as $totales)
                        {{ $totales['proceso'] === 'N/A' ? 'null' : $totales['proceso'] }},
                    @endforeach
                ];
    
                // Calcular rango dinámico para el eje Y
                const allData_{{ $loop->index }} = aql_{{ $loop->index }}.concat(proceso_{{ $loop->index }}).filter(v => v !== null);
                const maxY_{{ $loop->index }} = Math.ceil(Math.max(...allData_{{ $loop->index }})) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoClientePlanta1_{{ $loop->index }}", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: {{ $cliente }}",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_{{ $loop->index }},
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_{{ $loop->index }}, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_{{ $loop->index }},
                            color: "#28a745", // Color verde
                            zIndex: 2, // Mayor zIndex para sobreponerse a las barras
                            marker: {
                                enabled: true, // Mostrar puntos en la línea
                                radius: 4
                            }
                        },
                        {
                            name: "% Proceso",
                            type: 'column', // Barras para Proceso
                            data: proceso_{{ $loop->index }},
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            @endforeach
        });
    </script> 
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach($modulosPorClientePlanta2 as $cliente => $modulos)
                // Crear datos para las series
                const semanas_{{ $loop->index }} = [
                    @foreach($semanas as $semana)
                        "Semana {{ $semana['inicio']->format('W') }} - {{ $semana['inicio']->format('Y') }}",
                    @endforeach
                ];
    
                const aql_{{ $loop->index }} = [
                    @foreach($totalesPorClientePlanta2[$cliente] as $totales)
                        {{ $totales['aql'] === 'N/A' ? 'null' : $totales['aql'] }},
                    @endforeach
                ];
    
                const proceso_{{ $loop->index }} = [
                    @foreach($totalesPorClientePlanta2[$cliente] as $totales)
                        {{ $totales['proceso'] === 'N/A' ? 'null' : $totales['proceso'] }},
                    @endforeach
                ];
    
                // Calcular rango dinámico para el eje Y
                const allData_{{ $loop->index }} = aql_{{ $loop->index }}.concat(proceso_{{ $loop->index }}).filter(v => v !== null);
                const maxY_{{ $loop->index }} = Math.ceil(Math.max(...allData_{{ $loop->index }})) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoClientePlanta2_{{ $loop->index }}", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: {{ $cliente }}",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_{{ $loop->index }},
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_{{ $loop->index }}, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_{{ $loop->index }},
                            color: "#28a745", // Color verde
                            zIndex: 2, // Mayor zIndex para sobreponerse a las barras
                            marker: {
                                enabled: true, // Mostrar puntos en la línea
                                radius: 4
                            }
                        },
                        {
                            name: "% Proceso",
                            type: 'column', // Barras para Proceso
                            data: proceso_{{ $loop->index }},
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            @endforeach
        });
    </script>  

@endpush
