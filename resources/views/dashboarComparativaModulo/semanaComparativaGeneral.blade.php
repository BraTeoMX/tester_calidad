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
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4>Información del Cliente: {{ $cliente }}</h4>
                            </div>
                            <div class="card-body">
                                @foreach($estilos as $estilo => $modulosEstilo)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5>Estilo: {{ $estilo }}</h5>
                                    </div>
                                    <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                        <table class="table tablesorter">
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
                                                @foreach($modulosEstilo as $modulo)
                                                <tr>
                                                    <td>{{ $modulo->modulo }}</td>
                                                    @foreach($modulo->semanalPorcentajes as $porcentajes)
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
                                            
                                        </table>
                                    </div>
                                </div>                                
                                @endforeach
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

    
    <!-- Highcharts JavaScript -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>


@endpush
