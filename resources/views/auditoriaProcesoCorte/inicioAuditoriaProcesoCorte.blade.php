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
