@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

@section('content')
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
                            <h4>Fecha: <span id="fecha-display">Cargando...</span></h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <form method="POST" action="{{ route('auditoriaAQL.formAltaProcesoAQL_v2') }}"> 
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
                                                <option value="" selected>Cargando módulos...</option>
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
                                            <select name="gerente_produccion" id="gerente_produccion" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="" selected>Cargando gerentes...</option>
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
        // =========================================================================
        // FUNCIONES GLOBALES (llamadas desde onchange en el HTML)
        // =========================================================================

        function cargarOrdenesOP() {
            var moduloSeleccionado = $('#modulo').val();

            if (moduloSeleccionado) { // Solo ejecutar si hay un módulo seleccionado
                $.ajax({
                    url: '/auditoriaAQLV3/ordenes-op', // O "{{ route('AQLV3.cargarOrdenesOP') }}" si está nombrada
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        modulo: moduloSeleccionado
                    },
                    method: 'POST',
                    success: function(data) {
                        var $opSelect = $('#op');
                        $opSelect.empty();
                        $opSelect.append('<option value="" selected>Selecciona una opción</option>');
                        data.forEach(function(orden) {
                            $opSelect.append('<option value="' + orden.prodid + '">' + orden.prodid + '</option>');
                        });
                        // Si Select2 está inicializado para #op, puede que necesite un trigger para actualizarse
                        // $opSelect.trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar órdenes OP: ", error);
                        $('#op').empty().append('<option value="">Error al cargar</option>');
                    }
                });
            } else {
                // Limpiar select OP si no hay módulo seleccionado
                var $opSelect = $('#op');
                $opSelect.empty().append('<option value="" selected>Selecciona un módulo primero</option>');
                // $opSelect.trigger('change'); // Si usas Select2
            }
        }

        function obtenerSupervisor() {
            var moduleid = $('#modulo').val(); // Obtén el valor del select "modulo"

            if (moduleid) {
                $.ajax({
                    url: '/auditoriaAQLV3/obtener-supervisor', // O "{{ route('AQLV3.obtenerSupervisor') }}" si está nombrada
                    type: 'GET',
                    data: { moduleid: moduleid }, // Laravel tomará esto del query string
                    success: function(response) {
                        var $teamLeader = $('#team_leader');
                        $teamLeader.empty();

                        if (response.supervisorRelacionado && response.supervisorRelacionado.name) {
                            $teamLeader.append('<option value="' + response.supervisorRelacionado.name + '" selected>' + response.supervisorRelacionado.name + '</option>');
                        } else {
                            // Añadir una opción por defecto si no hay supervisor relacionado o para permitir selección
                            $teamLeader.append('<option value="">Selecciona un supervisor</option>');
                        }

                        if (response.supervisores && response.supervisores.length > 0) {
                            $.each(response.supervisores, function(index, supervisor) {
                                // Evitar duplicar el supervisor relacionado si ya fue agregado
                                if (!(response.supervisorRelacionado && supervisor.name === response.supervisorRelacionado.name)) {
                                    $teamLeader.append('<option value="' + supervisor.name + '">' + supervisor.name + '</option>');
                                }
                            });
                        }
                        
                        if (!response.supervisorRelacionado && (!response.supervisores || response.supervisores.length === 0)) {
                            // Si no hubo relacionado y la lista de supervisores está vacía (o no vino)
                            if ($teamLeader.children().length === 0) { // Solo si realmente está vacío
                            $teamLeader.append('<option value="">No hay supervisores disponibles</option>');
                            }
                        }
                        // Si Select2 está inicializado para #team_leader, puede que necesite un trigger
                        // $teamLeader.trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al obtener los supervisores: ', error);
                        alert('Error al obtener los supervisores');
                        $('#team_leader').empty().append('<option value="">Error al cargar supervisores</option>');
                    }
                });
            } else {
                // Limpiar select de supervisor si no hay módulo seleccionado
                var $teamLeader = $('#team_leader');
                $teamLeader.empty().append('<option value="">Selecciona un módulo primero</option>');
                // $teamLeader.trigger('change'); // Si usas Select2
            }
        }

        // =========================================================================
        // CÓDIGO EJECUTADO CUANDO EL DOCUMENTO ESTÁ LISTO
        // =========================================================================
        $(document).ready(function() {

            // 1. Cargar datos iniciales (auditor y fecha)
            $.ajax({
                url: "{{ route('AQLV3.initialData') }}",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#auditor').val(data.auditorDato);
                    $('#fecha-display').text(data.fechaFormateada);
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar datos iniciales: ", error);
                    $('#auditor').val('Error');
                    $('#fecha-display').text('Error');
                }
            });

            // 2. Cargar lista de módulos y luego inicializar Select2 para #modulo
            $.ajax({
                url: "{{ route('AQLV3.listaModulos') }}",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var $moduloSelect = $('#modulo');
                    $moduloSelect.empty();
                    $moduloSelect.append('<option value="" selected>Selecciona una opción</option>');
                    $.each(data, function(index, modulo) {
                        // Usamos modulo.moduleid para el valor y para data-modulo
                        $moduloSelect.append('<option value="' + modulo.moduleid + '" data-modulo="' + modulo.moduleid + '">' + modulo.moduleid + '</option>');
                    });

                    // Inicializar Select2 para #modulo DESPUÉS de poblar las opciones
                    $moduloSelect.select2({
                        placeholder: 'Seleccione una opción',
                        allowClear: true
                    });

                    // Adjuntar el evento DESPUÉS de inicializar Select2
                    $moduloSelect.on('select2:select', function(e) {
                        // 'this' dentro del handler es el elemento select DOM
                        // e.params.data.element es el <option> original
                        // e.params.data.id es el valor del <option> seleccionado
                        var valorModuloSeleccionado = $(this).val(); // o e.params.data.id
                        var dataModulo = $(e.params.data.element).data('modulo'); 

                        // Si #estilo debe tomar el valor del módulo o un atributo específico:
                        // Si 'itemid' era el 'moduleid', entonces puedes usar valorModuloSeleccionado
                        // Si 'itemid' era un dato diferente que venía con el módulo,
                        // asegúrate que la API /auditoriaAQLV3/lista-modulos lo devuelva y lo añadas como data-attribute.
                        // Por ahora, asumimos que quieres el valor del 'data-modulo' o el valor seleccionado.
                        $('#estilo').val(dataModulo); // O $('#estilo').val(valorModuloSeleccionado);

                        // Las funciones cargarOrdenesOP() y obtenerSupervisor() ya están en el onchange del HTML,
                        // así que se ejecutarán automáticamente. Si quisieras llamarlas desde aquí:
                        // cargarOrdenesOP();
                        // obtenerSupervisor();
                    });

                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar módulos: ", error);
                    $('#modulo').empty().append('<option value="">Error al cargar</option>');
                    // Considera deshabilitar Select2 si falla la carga
                }
            });

            // 3. Cargar gerentes de producción
            $.ajax({
                url: "{{ route('AQLV3.gerentesProduccion') }}",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var $gerenteSelect = $('#gerente_produccion');
                    $gerenteSelect.empty();
                    $gerenteSelect.append('<option value="" selected>Selecciona una opción</option>');
                    $.each(data, function(index, gerente) {
                        $gerenteSelect.append('<option value="' + gerente.nombre + '">' + gerente.nombre + '</option>');
                    });

                    // Si #gerente_produccion también usa Select2, inicialízalo aquí:
                    // $('#gerente_produccion').select2({
                    //  placeholder: 'Seleccione una opción',
                    //  allowClear: true
                    // });
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar gerentes de producción: ", error);
                    $('#gerente_produccion').empty().append('<option value="">Error al cargar</option>');
                }
            });

            // 4. Inicializar Select2 para #op (se llena dinámicamente, pero el select base puede inicializarse)
            $('#op').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
            
            // 5. Inicializar Select2 para #team_leader (opcional, si lo deseas)
            // Se llena dinámicamente por obtenerSupervisor(). Si quieres Select2:
            // $('#team_leader').select2({
            //     placeholder: 'Seleccione un supervisor',
            //     allowClear: true
            // });
            // Si lo haces, asegúrate de que se actualice correctamente después de que obtenerSupervisor() lo llene.
            // Esto puede requerir $teamLeader.trigger('change'); dentro del success de obtenerSupervisor()
            // o reinicializarlo. Por simplicidad, y como no lo tenías antes, lo dejo comentado.

        }); // Fin de $(document).ready()
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

@endsection
