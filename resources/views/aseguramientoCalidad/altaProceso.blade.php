@extends('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')])

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
    @if (session('sobre-escribir'))
        <div class="alert sobre-escribir">
            {{ session('sobre-escribir') }}
        </div>
    @endif
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    @if (session('cambio-estatus'))
        <div class="alert cambio-estatus">
            {{ session('cambio-estatus') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #32CD32;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .sobre-escribir {
            background-color: #FF8C00;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .cambio-estatus {
            background-color: #800080;
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
                            <h3 class="card-title">AUDITORIA CONTROL DE CALIDAD</h3>
                        </div>
                        <div class="col-auto">
                            <h4>Fecha:
                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                        @csrf
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>ESTILO</th>
                                        <th>CLIENTE</th>
                                        <th>TEAM-LEADER</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="area" id="area" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                <option value="AUDITORIA EN PROCESO">AUDITORIA EN PROCESO</option>
                                                <option value="AUDITORIA EN PROCESO PLAYERA">AUDITORIA EN PROCESO PLAYERA</option>
                                                <option value="AUDITORIA EN EMPAQUE">AUDITORIA EN EMPAQUE</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                @if ($auditorPlanta == 'Planta1')
                                                    @foreach ($auditoriaProcesoIntimark1 as $moduloP1)
                                                        <option value="{{ $moduloP1->moduleid }}" data-itemid="{{ $moduloP1->itemid }}">
                                                            {{ $moduloP1->moduleid }}
                                                        </option>
                                                    @endforeach
                                                @elseif($auditorPlanta == 'Planta2')
                                                    @foreach ($auditoriaProcesoIntimark2 as $moduloP2)
                                                        <option value="{{ $moduloP2->moduleid }}" data-itemid="{{ $moduloP2->itemid }}">
                                                            {{ $moduloP2->moduleid }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="estilo" id="estilo" readonly required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="cliente" id="cliente" readonly required />
                                        </td>
                                        
                                        <td>
                                            <select name="team_leader" id="team_leader" class="form-control" required
                                                title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                <!-- Agrega el atributo selected aquí -->
                                                @if ($auditorPlanta == 'Planta1')
                                                    @foreach ($teamLeaderPlanta1 as $teamLeader)
                                                        <option value="{{ $teamLeader->nombre }}">
                                                            {{ $teamLeader->nombre }}
                                                        </option>
                                                    @endforeach
                                                @elseif($auditorPlanta == 'Planta2')
                                                    @foreach ($teamLeaderPlanta2 as $teamLeader)
                                                        <option value="{{ $teamLeader->nombre }}">
                                                            {{ $teamLeader->nombre }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                value="{{ $auditorDato }}" readonly required /></td>
                                        <td><input type="text" class="form-control me-2" name="turno" id="turno"
                                                value="1" readonly required /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">Iniciar</button>
                    </form>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->

                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h5 class="card-title">ESTATUS</h5>
                        </div>
                        <div class="col-auto">

                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        AUDITORIA EN PROCESO
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseOne5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                            <th>Gerente de Produccion</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($procesoActual as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                                <td>{{ $proceso->team_leader }}</td>

                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 1 -->
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseOne6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($procesoFinal as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 2 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Fin del apartado del primer acordeon externo--}}
                    {{--Inicio del Segundo acordeon externo--}}
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        AUDITORIA EN PROCESO PLAYERA
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseTwo5" aria-expanded="true" aria-controls="collapseTwo5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseTwo5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($playeraActual as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 1 -->
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseTwo6" aria-expanded="true" aria-controls="collapseTwo6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseTwo6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($playeraFinal as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 2 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Fin del Segundo Acordeon Externo--}}
                    {{--Inicio del Tercer Acordeon Externo--}}
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        AUDITORIA EN PROCESO EMPAQUE
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="collapseThree" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseThree5" aria-expanded="true" aria-controls="collapseThree5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($empaqueActual as $proceso)
                                                                            <tr>
                                                                                <td><button class="btn btn-primary">Acceder</button></td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 1 -->
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseThree6" aria-expanded="true" aria-controls="collapseThree6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($empaqueFinal as $proceso)
                                                                            <tr>
                                                                                <td><button class="btn btn-primary">Acceder</button></td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 2 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Fin del Tercer Acordeon Externo--}}
                    
                    
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>
    </div>
 
    <script>
        $('#modulo').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
            });

    </script>

    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }
    </style>
    <script>
        $(document).ready(function() {
            // Manejar la selección del módulo para actualizar el input de estilo
            $('#modulo').change(function() {
                var moduleid = $(this).val();
                $('#estilo').val(''); // Limpiar el input anterior
    
                $.ajax({
                    url: '{{ route("obtenerItemId") }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'moduleid': moduleid
                    },
                    success: function(response) {
                        console.log(response); // Verificar los datos recibidos
                        if (response.itemid) {
                            $('#estilo').val(response.itemid); // Establecer el itemid en el input
                        } else {
                            $('#estilo').val('No se encontró estilo'); // Mensaje si no hay itemid
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
    
            // Manejar la selección del módulo para actualizar el input de cliente
            $('#modulo').change(function() {
                var moduleid = $(this).val();
    
                // Realizar solicitud AJAX para obtener el cliente correspondiente al módulo seleccionado
                $.ajax({
                    type: 'POST',
                    url: '{{ route("obtenerCliente1") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        moduleid: moduleid
                    },
                    success: function(response) {
                        console.log(response); // Verificar los datos recibidos
                        // Actualizar el valor del campo "cliente" con el cliente obtenido de la respuesta AJAX
                        $('#cliente').val(response.cliente);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>    






@endsection
