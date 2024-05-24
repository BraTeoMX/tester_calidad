@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')])

@section('content')
    {{-- ... dentro de tu vista ... --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alerta-exito">
            {{ session('success') }}
            @if (session('sorteo'))
                <br>{{ session('sorteo') }}
            @endif
        </div>
    @endif
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #28a745;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">Dashboard Auditoria AQL General</h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
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
                        <button type="submit" class="btn btn-primary">Mostrar datos</button>
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
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom1 text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesError as $cliente => $porcentajeError)
                                        <tr class="{{ ($porcentajeError > 9 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $cliente }}</td>
                                            <td>{{ number_format($porcentajeError, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom3 text-center">
                                    <tr>
                                        <th>Gerentes de Produccion</th> 
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorJefeProduccion as $jefeProduccion => $porcentajeError)
                                        <tr class="{{ ($porcentajeError > 10 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $jefeProduccion }}</td>
                                            <td>{{ number_format($porcentajeError, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom3 text-center">
                                    <tr>
                                        <th>Team Leader</th> 
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorTeamLeader as $teamLeader => $porcentajeError)
                                        <tr class="{{ ($porcentajeError > 10 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $teamLeader }}</td>
                                            <td>{{ number_format($porcentajeError, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>                    
                    <hr>
                    <table id="tablaDetallesPorModulo" class="table table-bordered ">
                        <thead class="thead-custom2 text-center">
                            <tr>
                                <th>Detalles</th>
                                <th>Modulo</th>
                                <th>OP</th>
                                <th>Gerentes de Produccion / Team Leader</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porcentajesErrorNombre as $nombre => $porcentajeErrorNombre)
                                <tr class="{{ ($porcentajeErrorNombre > 9 && $porcentajeErrorNombre <= 15) ? 'error-bajo' : ($porcentajeErrorNombre > 15 ? 'error-alto' : '') }}">
                                    <td>
                                        <a href="{{ route('dashboar.detalleXModuloAQL', ['modulo' => $moduloPorNombre[$nombre], 'op' => $operacionesPorNombre[$nombre], 'team_leader' => $teamLeaderPorNombre[$nombre], 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-secondary">Ver detalles</a>
                                    </td>
                                    <td>{{ $moduloPorNombre[$nombre] }}</td>
                                    <td>{{ $operacionesPorNombre[$nombre] }}</td>
                                    <td>{{ $teamLeaderPorNombre[$nombre] }}</td>
                                    <td>{{ number_format($porcentajeErrorNombre, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>

            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">Planta 1 - Ixtlahuaca</h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="row">
                        <div class="col-md-5">
                            <table id="tablaDetallesPorCliente" class="table  table-bordered">
                                <thead class="thead-custom1 text-center">
                                    <tr>
                                        <th>Detalle</th>
                                        <th>Cliente</th>
                                        <th>% Error Proceso</th>
                                        <th>% Error AQL</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dataClientePlanta1 as $clienteData)
                                        <tr class="{{ ($clienteData['porcentajeErrorProceso'] > 9 && $clienteData['porcentajeErrorProceso'] <= 15) ? 'error-bajo' : ($clienteData['porcentajeErrorProceso'] > 15 ? 'error-alto' : '') }}">
                                            <td>
                                                <a href="{{ route('dashboar.detallePorCliente', ['planta' => 'Intimark1', 'cliente' => $clienteData['cliente'], 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-secondary" style="margin-right: 0;">Ver detalles</a>
                                            </td>
                                            <td>{{ $clienteData['cliente'] }}</td>
                                            <td>{{ number_format($clienteData['porcentajeErrorProceso'], 2) }}%</td>
                                            <td>{{ number_format($clienteData['porcentajeErrorAQL'], 2) }}%</td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                            <td></td>
                                            <td>GENERAL</td>
                                            <td>{{ number_format($totalPorcentajeErrorProceso, 2) }}%</td>
                                            <td>{{ number_format($totalPorcentajeErrorAQL, 2) }}%</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom3 text-center">
                                    <tr>
                                        <th>Gerentes de Produccion</th> 
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorJefeProduccionPlanta1 as $jefeProduccion => $porcentajeErrorPlanta1)
                                        <tr class="{{ ($porcentajeErrorPlanta1 > 10 && $porcentajeErrorPlanta1 <= 15) ? 'error-bajo' : ($porcentajeErrorPlanta1 > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $jefeProduccion }}</td>
                                            <td>{{ number_format($porcentajeErrorPlanta1, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-3">
                            {{-- <table class="table  table-bordered table1">
                                <thead class="thead-custom1 text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorProcesoPlanta1 as $cliente => $porcentajeError)
                                        <tr class="{{ ($porcentajeError > 9 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $cliente }}</td>
                                            <td>{{ number_format($porcentajeError, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> --}}
                        </div>
                    </div>
                    <hr>
                    <h3>Gerentes de Produccion</h3>
                    <table id="tablaProcesoAQL" class="table table-bordered">
                        <thead class="thead-custom2 text-center">
                            <tr>
                                <th>Detalles</th>
                                <th>Gerentes Produccion</th>
                                <th>Cantidad de Módulos</th>
                                <th>Numero de Operarios</th>
                                <th>Numero de Utility</th> 
                                <th>Cantidad Paro</th>
                                <th>Minutos Paro</th>
                                <th>Promedio Minutos Paro</th>
                                <th>% Error AQL</th>
                                <th>% Error Proceso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataGerentes as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('dashboar.detallePorGerente', ['planta' => 'Intimark1', 'team_leader' => $item['team_leader'], 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-secondary" style="margin-right: 0;">Ver detalles</a>
                                    </td>
                                    <td>{{ $item['team_leader'] }}</td>
                                    <td>{{ $item['modulos_unicos'] }}</td>
                                    <td>{{ $item['conteoOperario'] }}</td> 
                                    <td>{{ $item['conteoUtility'] }}</td>  
                                    <td>{{ $item['conteoMinutos'] }}</td> 
                                    <td>{{ $item['sumaMinutos'] }}</td> 
                                    <td>{{ $item['promedioMinutosEntero'] }}</td>
                                    <td>{{ number_format($item['porcentaje_error_aql'], 2) }}%</td>
                                    <td>{{ number_format($item['porcentaje_error_proceso'], 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr style="background: #ddd">
                            <td style="background: white"></td>
                            <td>Total</td>
                            <td>{{ $dataGerentesTotales->sum('modulos_unicos') }}</td>
                            <td>{{ $dataGerentesTotales->sum('conteoOperario') }}</td>
                            <td>{{ $dataGerentesTotales->sum('conteoUtility') }}</td>
                            <td>{{ $dataGerentesTotales->sum('conteoMinutos') }}</td>
                            <td>{{ $dataGerentesTotales->sum('sumaMinutos') }}</td>
                            <td>{{ $dataGerentesTotales->sum('promedioMinutosEntero') }}</td>
                            <td>- -</td>
                            <td>- -</td>
                        </tr>
                    </table>
                    <hr>
                    <h3>Modulos</h3>
                    <table id="tablaDetallesPorModulo" class="table table-bordered ">
                        <thead class="thead-custom2 text-center">
                            <tr>
                                <th>Detalles</th>
                                <th>Modulo</th>
                                <th>OP</th>
                                <th>Gerentes de Produccion</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porcentajesErrorModuloPlanta1 as $modulo => $porcentajeErrorModuloPlanta1)
                                <tr class="{{ ($porcentajeErrorModuloPlanta1 > 9 && $porcentajeErrorModuloPlanta1 <= 15) ? 'error-bajo' : ($porcentajeErrorModuloPlanta1 > 15 ? 'error-alto' : '') }}">
                                    <td>
                                        <a href="{{ route('dashboar.detalleXModuloAQL', ['modulo' => $moduloPorModuloPlanta1[$modulo], 'op' => $operacionesPorModuloPlanta1[$modulo], 'team_leader' => $teamLeaderPorModuloPlanta1[$modulo], 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-secondary">Ver detalles</a>
                                    </td>
                                    <td>{{ $moduloPorModuloPlanta1[$modulo] }}</td>
                                    <td>{{ $operacionesPorModuloPlanta1[$modulo] }}</td>
                                    <td>{{ $teamLeaderPorModuloPlanta1[$modulo] }}</td>
                                    <td>{{ number_format($porcentajeErrorModuloPlanta1, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">Planta 2 - San Bartolo</h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table  table-bordered table1">
                                <thead class="thead-custom1 text-center">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorPlanta2 as $cliente => $porcentajeError)
                                        <tr class="{{ ($porcentajeError > 9 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $cliente }}</td>
                                            <td>{{ number_format($porcentajeError, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom3 text-center">
                                    <tr>
                                        <th>Gerentes de Produccion</th> 
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorJefeProduccionPlanta2 as $jefeProduccion => $porcentajeErrorPlanta2)
                                        <tr class="{{ ($porcentajeErrorPlanta2 > 10 && $porcentajeErrorPlanta2 <= 15) ? 'error-bajo' : ($porcentajeErrorPlanta2 > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $jefeProduccion }}</td>
                                            <td>{{ number_format($porcentajeErrorPlanta2, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4">
                            <table class="table table-bordered table1">
                                <thead class="thead-custom3 text-center">
                                    <tr>
                                        <th>Team Leader</th> 
                                        <th>% Error</th>
                                        <!-- Aquí puedes agregar más encabezados si es necesario -->
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($porcentajesErrorTeamLeaderPlanta2 as $teamLeader => $porcentajeErrorPlanta2)
                                        <tr class="{{ ($porcentajeErrorPlanta2 > 10 && $porcentajeErrorPlanta2 <= 15) ? 'error-bajo' : ($porcentajeErrorPlanta2 > 15 ? 'error-alto' : '') }}">
                                            <td>{{ $teamLeader }}</td>
                                            <td>{{ number_format($porcentajeErrorPlanta2, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <table class="table table-bordered">
                        <thead class="thead-custom2 text-center">
                            <tr>
                                <th>Detalles</th>
                                <th>Modulo</th>
                                <th>OP</th>
                                <th>Gerentes de Produccion / Team Leader</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($porcentajesErrorModuloPlanta2 as $modulo => $porcentajeErrorModuloPlanta2)
                                <tr class="{{ ($porcentajeErrorModuloPlanta2 > 9 && $porcentajeErrorModuloPlanta2 <= 15) ? 'error-bajo' : ($porcentajeErrorModuloPlanta2 > 15 ? 'error-alto' : '') }}">
                                    <td>
                                        <a href="{{ route('dashboar.detalleXModuloAQL', ['modulo' => $moduloPorModuloPlanta2[$modulo], 'op' => $operacionesPorModuloPlanta2[$modulo], 'team_leader' => $teamLeaderPorModuloPlanta2[$modulo], 'fecha_inicio' => $fechaInicio, 'fecha_fin' => $fechaFin]) }}" class="btn btn-secondary" style="margin-right: 0;">Ver detalles</a> 
                                    </td>
                                    <td>{{ $moduloPorModuloPlanta2[$modulo] }}</td>
                                    <td>{{ $operacionesPorModuloPlanta2[$modulo] }}</td>
                                    <td>{{ $teamLeaderPorModuloPlanta2[$modulo] }}</td>
                                    <td>{{ number_format($porcentajeErrorModuloPlanta2, 2) }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>

        </div>
    </div>

    <style>
        .table1 {
            max-width: 400px; /* Ajusta el valor según tus necesidades */
        }

        /* Personalizar estilo del thead */
        .thead-custom1 {
            background-color: #0c6666; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }

        /* Personalizar estilo del thead */
        .thead-custom2 {
            background-color: #0891ec; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }

        /* Personalizar estilo del thead */
        .thead-custom3 {
            background-color: #f77b07; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }


        .error-bajo {
            background-color: #f8d7da; /* Rojo claro */
            color: #721c24; /* Texto oscuro */
        }

        .error-alto {
            background-color: #dc3545; /* Rojo */
            color: #ffffff; /* Texto blanco */
        }

        .table32 th:nth-child(1) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>


    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">


    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $(document).ready( function () {
            $('#tablaProcesoAQL').DataTable({
                lengthChange: false,
                searching: false,
                paging: true,
                pageLength: 10,
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [0] } // Aquí deshabilitas la ordenación para las columnas 2, 3 y 4 (índices 1, 2, 3)
                ]
            });
        });
    </script> 

    <script>
        $(document).ready( function () {
            $('#tablaDetallesPorModulo').DataTable({
                lengthChange: false,
                searching: false,
                paging: true,
                pageLength: 10,
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [0] } // Aquí deshabilitas la ordenación para las columnas 2, 3 y 4 (índices 1, 2, 3)
                ]
            });
        });
    </script> 
    <script>
        $(document).ready( function () {
            $('#tablaDetallesPorCliente').DataTable({
                lengthChange: false,
                searching: false,
                paging: true,
                pageLength: 10,
                autoWidth: false,
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [0] } // Aquí deshabilitas la ordenación para las columnas 2, 3 y 4 (índices 1, 2, 3)
                ]
            });
        });
    </script> 

@endsection
