@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

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
                    <form method="POST" action="{{ route('AQLV3.formAltaAQLV3') }}"> 
                        @csrf
                        <div class="table-responsive">
                            <table class="table table-200">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>OP</th>
                                        <th>SUPERVISOR</th>
                                        <th>GERENTE DE PRODUCCION</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control texto-blanco" name="area" value="AUDITORIA AQL" readonly>
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required
                                                title="Por favor, selecciona una opción" onchange="cargarOrdenesOP(); obtenerSupervisor();">
                                                <option value="" selected>Selecciona una opción</option>
                                                @foreach ($listaModulos as $modulo)
                                                    <option value="{{ $modulo->moduleid }}"
                                                        data-modulo="{{ $modulo->moduleid }}">
                                                        {{ $modulo->moduleid }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="op" id="op" class="form-control" required
                                                title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="team_leader" id="team_leader" class="form-control" required>
                                                <!-- Las opciones se llenarán dinámicamente con la llamada AJAX -->
                                            </select>                                            
                                        </td>
                                        <td>
                                            <select name="gerente_produccion" class="form-control" required title="Por favor, selecciona una opción">
                                                
                                                @foreach ($gerenteProduccion as $gerente)
                                                    <option value="{{ $gerente->nombre }}">
                                                        {{ $gerente->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control texto-blanco" name="auditor" id="auditor"
                                                value="{{ $auditorDato }}" readonly required /></td>
                                        <td><input type="text" class="form-control texto-blanco" name="turno" id="turno"
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
                                    AUDITORIA AQL
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
                                                            <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo u OP">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tablaProcesos1">
                                                                    @php
                                                                        $valoresMostrados = [];
                                                                    @endphp
                                                                    @foreach($procesoActualAQL as $proceso)
                                                                        @if (!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op]))
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('auditoriaAQL.formAltaProcesoAQL_v2') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="op" value="{{ $proceso->op }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="gerente_produccion" value="{{ $proceso->gerente_produccion }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->op }}</td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            @php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            @endphp
                                                                        @endif
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
                                                            <input type="text" id="searchInput2" class="form-control mb-3" placeholder="Buscar Módulo u OP">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tablaProcesos2">
                                                                    @php
                                                                        $valoresMostrados = [];
                                                                    @endphp
                                                                    @foreach($procesoFinalAQL as $proceso)
                                                                        @if (!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op]))
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('auditoriaAQL.formAltaProcesoAQL_v2') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="op" value="{{ $proceso->op }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="gerente_produccion" value="{{ $proceso->gerente_produccion }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->op }}</td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            @php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            @endphp
                                                                        @endif
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

                <!--Fin de la edicion del codigo para mostrar el contenido-->
            </div>
        </div>

    </div>

    <style>
        #searchInput1::placeholder, #searchInput2::placeholder, #searchInput3::placeholder, #searchInput4::placeholder {
            color: rgba(255, 255, 255, 0.85);
            font-weight: bold;
        }
      
        #searchInput1, #searchInput2, #searchInput3, #searchInput4 {
            background-color: #1a1f30;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }

        .texto-blanco {
            color: white !important;
        }

        .table-200 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(2) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(3) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(5) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#modulo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            $('#modulo').on('select2:select', function(e) {
                var itemid = e.params.data.element.dataset.itemid;
                $('#estilo').val(itemid);
            });
        });

        $(document).ready(function() {
            $('#op').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#modulo').change(function() {
                var itemid = $(this).find(':selected').data('itemid');
                $('#estilo').val(itemid);
            });
        });
    </script>


    <script>
        function cargarOrdenesOP() {
            var moduloSeleccionado = $('#modulo').val();

            $.ajax({
                url: '/cargarOrdenesOP',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    modulo: moduloSeleccionado
                },
                method: 'POST',
                success: function(data) {
                    $('#op').empty(); // Limpiar el select de ordenesOP
                    $('#op').append('<option value="">Selecciona una opción</option>');

                    data.forEach(function(orden) {
                        $('#op').append('<option value="' + orden.prodid + '">' + orden.prodid +
                            '</option>');
                    });
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            $('#searchInput1').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos1 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput2').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos2 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput3').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos3 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput4').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos4 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });
    </script>

    <script>
        function obtenerSupervisor() {
            var moduleid = $('#modulo').val();  // Obtén el valor del select "modulo"
            
            if (moduleid) {
                $.ajax({
                    url: '/obtener-supervisor',  // Ruta para obtener el supervisor
                    type: 'GET',
                    data: { moduleid: moduleid },
                    success: function(response) {
                        var $teamLeader = $('#team_leader');
                        $teamLeader.empty();  // Limpia el select antes de llenarlo

                        // Si hay un supervisor relacionado, lo agrega como seleccionado
                        if (response.supervisorRelacionado) {
                            $teamLeader.append('<option value="' + response.supervisorRelacionado.name + '" selected>' + response.supervisorRelacionado.name + '</option>');
                        }

                        // Agrega las otras opciones de supervisores
                        $.each(response.supervisores, function(index, supervisor) {
                            if (response.supervisorRelacionado && supervisor.name === response.supervisorRelacionado.name) {
                                // Ya se agregó el supervisor relacionado como opción seleccionada, por lo que se omite
                                return;
                            }
                            // Agrega el resto de los supervisores
                            $teamLeader.append('<option value="' + supervisor.name + '">' + supervisor.name + '</option>');
                        });

                        // Si no hay supervisores disponibles, muestra un mensaje opcional
                        if (!response.supervisores.length) {
                            $teamLeader.append('<option value="">No hay supervisores disponibles</option>');
                        }
                    },
                    error: function() {
                        alert('Error al obtener los supervisores');
                    }
                });
            }
        }
    </script>
@endsection
