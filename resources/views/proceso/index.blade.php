@extends('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')])

@section('content')
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
                        <h4>Fecha: {{ $fechaActualParaVista }}</h4>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <form method="POST" action="{{ route('procesoV3.formAltaProceso') }}">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table10">
                            <thead class="thead-primary">
                                <tr>
                                    <th>AREA</th>
                                    <th>MODULO</th>
                                    <th>ESTILO</th>
                                    <th>CLIENTE</th>
                                    <th>SUPERVISOR</th>
                                    <th>GERENTE DE PRODUCCION</th>
                                    <th>AUDITOR</th>
                                    <th>TURNO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control me-2 texto-blanco" name="area" id="area"
                                            value="AUDITORIA EN PROCESO" readonly />
                                    </td>
                                    <td>
                                        <select name="modulo" id="modulo_proceso" class="form-control" required>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control texto-blanco" name="estilo" id="estilo_proceso" required>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="cliente" id="cliente" readonly required />
                                    </td>

                                    <td>
                                        <select name="team_leader" id="supervisor_proceso" class="form-control" required>
                                            <!-- Las opciones se llenarán dinámicamente con la llamada AJAX -->
                                        </select>
                                    </td>
                                    <td>
                                        <select name="gerente_produccion" id="select_gerente_produccion_id" class="form-control" required title="Por favor, selecciona una opción">
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                            value="{{ $auditorDato }}" readonly /></td>
                                    <td>
                                        <select name="turno" id="turno" class="form-control" required style="background-color: #27293d !important; color: white !important;">
                                            @foreach($turnos as $turno)
                                            <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
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
                                                            En Proceso <span id="loaderTablaProcesos1" style="display:none;">(Cargando...)</span>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseOne5" class="collapse show" aria-labelledby="headingOne5"
                                                    data-parent="#accordionExample5">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>Estilo</th>
                                                                        <th>Supervisor</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tablaProcesos1">
                                                                    {{-- Las filas se cargarán aquí por AJAX --}}
                                                                    <tr>
                                                                        <td colspan="4" class="text-center">Cargando datos...</td>
                                                                    </tr>
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
                                                            Finalizado <span id="loaderTablaProcesos2" style="display:none;">(Cargando...)</span>
                                                        </button>
                                                    </h2>
                                                </div>
                                                <div id="collapseOne6" class="collapse show" aria-labelledby="headingOne6"
                                                    data-parent="#accordionExample6">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <input type="text" id="searchInput2" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>Estilo</th>
                                                                        {{-- Supervisor no está aquí según tu HTML original para esta tabla --}}
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tablaProcesos2">
                                                                    {{-- Las filas se cargarán aquí por AJAX --}}
                                                                    <tr>
                                                                        <td colspan="3" class="text-center">Cargando datos...</td>
                                                                    </tr>
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
</div>

<style>
    #searchInput1::placeholder,
    #searchInput2::placeholder,
    #searchInput3::placeholder,
    #searchInput4::placeholder {
        color: rgba(255, 255, 255, 0.85);
        font-weight: bold;
    }

    #searchInput1,
    #searchInput2,
    #searchInput3,
    #searchInput4 {
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

    .table10 th:nth-child(1) {
        min-width: 180px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(2) {
        min-width: 150px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(3) {
        min-width: 200px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(4) {
        min-width: 220px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(5) {
        min-width: 200px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(6) {
        min-width: 150px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }

    .table10 th:nth-child(7) {
        min-width: 50px;
        /* Ajusta el ancho mínimo según tu necesidad */
    }
</style>
<script>
    $(document).ready(function() {

        // --- FUNCIÓN PARA CARGAR PROCESOS (ACTUAL Y FINAL) ---
        function cargarProcesos(tipo, tablaId, loaderId, colspan) {
            var $loader = $('#' + loaderId);
            var $tbody = $('#' + tablaId);

            $loader.show();
            $tbody.html('<tr><td colspan="' + colspan + '" class="text-center">Cargando datos...</td></tr>');

            $.ajax({
                url: "{{ route('procesoV3.ajax.procesos') }}",
                type: "GET",
                data: {
                    tipo: tipo
                }, // 'actual' o 'final'
                dataType: "json",
                success: function(data) {
                    $tbody.empty(); // Limpiar el "Cargando datos..."
                    if (data && data.length > 0) {
                        $.each(data, function(index, proceso) {
                            // Sanitizar valores para atributos HTML (evitar que comillas rompan el value)
                            var moduloSafe = proceso.modulo ? proceso.modulo.toString().replace(/"/g, '&quot;') : '';
                            var estiloSafe = proceso.estilo ? proceso.estilo.toString().replace(/"/g, '&quot;') : '';
                            var teamLeaderSafe = proceso.team_leader ? proceso.team_leader.toString().replace(/"/g, '&quot;') : '';
                            var gerenteProdSafe = proceso.gerente_produccion ? proceso.gerente_produccion.toString().replace(/"/g, '&quot;') : '';
                            var auditorSafe = proceso.auditor ? proceso.auditor.toString().replace(/"/g, '&quot;') : '';
                            var turnoSafe = proceso.turno ? proceso.turno.toString().replace(/"/g, '&quot;') : '';
                            var clienteSafe = proceso.cliente ? proceso.cliente.toString().replace(/"/g, '&quot;') : ''; // Añadido por si acaso

                            var supervisorColHtml = '';
                            if (tipo === 'actual') {
                                supervisorColHtml = '<td>' + (proceso.team_leader || 'N/A') + '</td>';
                            }

                            var fila = '<tr>' +
                                '<td>' +
                                '<form method="POST" action="{{ route("procesoV3.formAltaProceso") }}">' +
                                '@csrf' + // Laravel manejará esto correctamente al renderizar la vista
                                '<input type="hidden" name="modulo" value="' + moduloSafe + '">' +
                                '<input type="hidden" name="estilo" value="' + estiloSafe + '">' +
                                '<input type="hidden" name="team_leader" value="' + teamLeaderSafe + '">' +
                                '<input type="hidden" name="gerente_produccion" value="' + gerenteProdSafe + '">' +
                                '<input type="hidden" name="auditor" value="' + auditorSafe + '">' +
                                '<input type="hidden" name="cliente" value="' + clienteSafe + '">' + // Asegúrate que cliente viene en el select del controller
                                '<input type="hidden" name="turno" value="' + turnoSafe + '">' +
                                '<button type="submit" class="btn btn-primary btn-sm">Acceder</button>' +
                                '</form>' +
                                '</td>' +
                                '<td>' + (proceso.modulo || 'N/A') + '</td>' +
                                '<td>' + (proceso.estilo || 'N/A') + '</td>' +
                                supervisorColHtml + // Se añade la columna de supervisor solo si es 'actual'
                                '</tr>';
                            $tbody.append(fila);
                        });
                    } else {
                        $tbody.append('<tr><td colspan="' + colspan + '" class="text-center">No hay procesos para mostrar.</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar procesos (" + tipo + "):", xhr.responseText);
                    $tbody.html('<tr><td colspan="' + colspan + '" class="text-center text-danger">Error al cargar datos. Por favor, intente de nuevo.</td></tr>');
                },
                complete: function() {
                    $loader.hide();
                }
            });
        }

        // --- LLAMADAS INICIALES PARA CARGAR LAS TABLAS ---
        cargarProcesos('actual', 'tablaProcesos1', 'loaderTablaProcesos1', 4); // 4 columnas para "En Proceso"
        cargarProcesos('final', 'tablaProcesos2', 'loaderTablaProcesos2', 3); // 3 columnas para "Finalizado"


        // --- TU LÓGICA EXISTENTE PARA LOS SEARCH INPUTS ---
        // Esta lógica debería seguir funcionando una vez que las tablas estén pobladas por AJAX.
        $('#searchInput1').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablaProcesos1 tr').filter(function() {
                var modulo = $(this).find('td:eq(1)').text().toLowerCase(); // Columna Módulo
                var estilo = $(this).find('td:eq(2)').text().toLowerCase(); // Columna Estilo
                // Si la tabla 'actual' tiene la columna supervisor en td:eq(3) y quieres buscar por ella también:
                // var supervisor = $(this).find('td:eq(3)').text().toLowerCase();
                // $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1 || supervisor.indexOf(value) > -1);
                $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
            });
        });

        $('#searchInput2').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablaProcesos2 tr').filter(function() {
                var modulo = $(this).find('td:eq(1)').text().toLowerCase(); // Columna Módulo
                var estilo = $(this).find('td:eq(2)').text().toLowerCase(); // Columna Estilo
                $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
            });
        });

        // Si tienes searchInput3 y searchInput4 para otras tablas, asegúrate que esas tablas se carguen
        // de manera similar si también son dinámicas, o que la lógica de búsqueda sea la correcta.
        // Por ahora, las mantengo como las tenías:
        $('#searchInput3').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablaProcesos3 tr').filter(function() { // Asume que tablaProcesos3 existe
                var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
            });
        });

        $('#searchInput4').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#tablaProcesos4 tr').filter(function() { // Asume que tablaProcesos4 existe
                var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        // Inicializar Select2 para ambos selects
        $('#modulo_proceso, #estilo_proceso').select2();

        // Hacer la petición AJAX para obtener los módulos (esto ya lo tienes)
        $.ajax({
            url: "{{ route('procesoV3.obtenerModulos') }}",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.length > 0) {
                    $("#modulo_proceso").empty().append('<option value="" selected>Selecciona una opción</option>');
                    $.each(response, function(index, item) {
                        $("#modulo_proceso").append('<option value="' + item.moduleid + '">' + item.moduleid + '</option>');
                    });
                } else {
                    console.warn("No se encontraron módulos.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener los módulos:", error);
            }
        });

        // Evento cuando cambia el módulo para obtener los estilos relacionados
        $('#modulo_proceso').on('change', function() {
            var moduloSeleccionado = $(this).val(); // Obtener el módulo seleccionado

            if (moduloSeleccionado) {
                $.ajax({
                    url: "{{ route('procesoV3.obtenerEstilos') }}",
                    type: "GET",
                    data: {
                        moduleid: moduloSeleccionado
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#estilo_proceso").empty().append('<option value="">Selecciona una opción</option>');
                        $("#cliente").val(""); // Limpiar cliente al cambiar de módulo

                        if (response.estilos.length > 0) {
                            $.each(response.estilos, function(index, item) {
                                $("#estilo_proceso").append('<option value="' + item.itemid + '" data-cliente="' + item.custname + '">' + item.itemid + '</option>');
                            });
                        } else {
                            console.warn("No se encontraron estilos para este módulo.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener los estilos:", error);
                    }
                });

                // Obtener supervisores
                $.ajax({
                    url: "{{ route('procesoV3.obtenerSupervisores') }}",
                    type: "GET",
                    data: {
                        moduleid: moduloSeleccionado
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#supervisor_proceso").empty().append('<option value="">Selecciona una opción</option>');

                        if (response.supervisores.length > 0) {
                            $.each(response.supervisores, function(index, item) {
                                let selected = (item.name === response.supervisorRelacionado) ? 'selected' : '';
                                $("#supervisor_proceso").append('<option value="' + item.name + '" ' + selected + '>' + item.name + '</option>');
                            });
                        } else {
                            console.warn("No se encontraron supervisores.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al obtener los supervisores:", error);
                    }
                });
            } else {
                $("#estilo_proceso").empty().append('<option value="">Selecciona una opción</option>');
                $("#cliente").val(""); // Limpiar cliente si no hay módulo seleccionado
                $("#supervisor_proceso").empty().append('<option value="">Selecciona una opción</option>');
            }
        });
        // Obtener cliente cuando se seleccione un estilo
        $('#estilo_proceso').on('change', function() {
            var clienteSeleccionado = $(this).find(':selected').data('cliente');
            $("#cliente").val(clienteSeleccionado || ""); // Si no hay cliente, dejar vacío
        });

        // --- NUEVA LÓGICA AJAX PARA CARGAR GERENTES DE PRODUCCIÓN ---
        function cargarGerentesProduccion() {
            var $selectGerentes = $('#select_gerente_produccion_id'); // Usamos el ID definido
            // Mantenemos "Cargando..." mientras se hace la petición
            $selectGerentes.empty().append('<option value="">Cargando gerentes...</option>').trigger('change');

            $.ajax({
                url: "{{ route('procesoV3.ajax.gerentesProduccion') }}", // Ruta definida para gerentes
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $selectGerentes.empty(); // Limpiar el "Cargando..." o cualquier opción previa

                    if (data && data.length > 0) {
                        $.each(data, function(index, gerente) {
                            var $option = $('<option>', {
                                value: gerente.nombre,
                                text: gerente.nombre
                            });

                            if (index === 0) { // Si es el primer elemento de la lista
                                $option.prop('selected', true); // Establecer como seleccionado
                            }
                            $selectGerentes.append($option);
                        });
                    } else {
                        // Si no hay gerentes, mostrar un mensaje y que esa opción quede seleccionada
                        console.warn("No se encontraron gerentes de producción.");
                        $selectGerentes.append('<option value="" selected>No hay gerentes disponibles</option>');
                    }
                    $selectGerentes.trigger('change'); // Notificar a Select2 de los cambios
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar gerentes de producción:", xhr.responseText);
                    $selectGerentes.empty().append('<option value="">Error al cargar gerentes</option>').trigger('change');
                }
            });
        }

        // Llamar a la función para cargar los gerentes cuando el DOM esté listo
        cargarGerentesProduccion();
    });
</script>

@endsection