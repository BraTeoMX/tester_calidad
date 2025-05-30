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
                                            value="AUDITORIA EN PROCESO" readonly  />
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
                                            <select name="gerente_produccion" class="form-control" required title="Por favor, selecciona una opción">
                                               <!-- <option value="" selected>Selecciona una opción</option> -->
                                                @foreach ($gerenteProduccion as $gerente)
                                                    <option value="{{ $gerente->nombre }}">
                                                        {{ $gerente->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                            value="{{ $auditorDato }}" readonly  /></td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="turno" id="turno"
                                                    value="1" readonly  /></td>                             
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
                                                                        @foreach($procesoActual as $proceso)
                                                                            <tr>
                                                                                <td> 
                                                                                    <form method="POST" action="{{ route('formAltaProcesoV2') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="gerente_produccion" value="{{ $proceso->gerente_produccion }}">
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
                                                                <input type="text" id="searchInput2" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos2">
                                                                        @foreach($procesoFinal as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('formAltaProcesoV2') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="gerente_produccion" value="{{ $proceso->gerente_produccion }}">
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
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
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
        $(document).ready(function() {
            // Inicializar Select2 para ambos selects
            $('#modulo_proceso, #estilo_proceso').select2();
        
            // Hacer la petición AJAX para obtener los módulos (esto ya lo tienes)
            $.ajax({
                url: "{{ route('obtenerModulosV2') }}",
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
                        url: "{{ route('obtenerEstilosV2') }}",
                        type: "GET",
                        data: { moduleid: moduloSeleccionado },
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
                        url: "{{ route('obtenerSupervisoresV2') }}",
                        type: "GET",
                        data: { moduleid: moduloSeleccionado },
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
        });
    </script>
    
@endsection
