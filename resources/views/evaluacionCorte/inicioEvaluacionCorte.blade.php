@extends('layouts.app', ['pageSlug' => 'Evaluacion Corte', 'titlePage' => __('Evaluacion Corte')])

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
                            <h3 class="card-title">EVALUACION DE CORTE CONTRA PATRON</h3>
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
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <form method="POST" action="{{ route('evaluacionCorte.formAltaEvaluacionCortes') }}">
                        @csrf
                        {{--
                        <input type="hidden" name="orden" value="{{ $datoAX->op }}">
                        <input type="hidden" name="estilo" value="{{ $datoAX->estilo }}">
                        --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="orden" class="col-sm-6 col-form-label">ORDEN</label>
                                <div class="col-sm-12">
                                    <select name="orden" id="orden" class="form-control select2" required
                                        title="Por favor, selecciona una opción" onchange="mostrarEstilo()">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($EncabezadoAuditoriaCorte as $dato)
                                            <option value="{{ $dato->orden_id }}" data-evento="{{ $dato->evento }}">{{ $dato->orden_id }} - Evento: {{ $dato->evento }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="evento" id="evento" value="">
                                </div>
                            </div>
                            &nbsp;
                            <div class="col-md-4 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">ESTILO</label>
                                <div class="col-sm-12">
                                    <input type="text" name="estilo" id="estilo" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <!--Este apartado debe ser modificado despues -->
                                <button type="submit" class="btn btn-primary">iniciar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
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
                                        EVALUACION DE CORTE CONTRA PATRON
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
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($procesoActualAQL as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
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
                                                                        @foreach($procesoFinalAQL as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
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

                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

    <script>
        function mostrarEstilo() {
            var ordenSeleccionado = document.getElementById('orden').value;

            // Obtener el token CSRF de la etiqueta meta
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Obtener el evento seleccionado del atributo data-evento de la opción seleccionada
            var eventoSeleccionado = document.getElementById('orden').selectedOptions[0].getAttribute('data-evento');

            $.ajax({
                url: "{{ route('evaluacionCorte.obtenerEstilo') }}",
                type: 'POST',
                data: {
                    orden_id: ordenSeleccionado,
                    _token: csrfToken // Incluir el token CSRF en los datos de la solicitud
                },
                success: function(response) {
                    console.log(response); // Verifica la respuesta en la consola
                    document.getElementById('estilo').value = response.estilo;
                    document.getElementById('evento').value = eventoSeleccionado; // Asignar el valor del evento obtenido
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // Muestra el mensaje de error en la consola
                }
            });
        }
    </script>



@endsection
