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
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Orden: {{ $encabezadoAuditoriaCorte->orden_id }}</h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Evento: {{ $encabezadoAuditoriaCorte->evento }}</h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Estilo: {{ $encabezadoAuditoriaCorte->estilo_id }}</h4>
                        </div>
                    </div>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    @php
                        $options = ['-1/16', '-1/8', '-1/4', '-1/2', '0', '+1/2', '+1/4', '+1/8', '+1/16'];
                    @endphp
                    @if($encabezadoAuditoriaCorte->estatus_evaluacion_corte == '1')
                    -
                    @else
                    <form method="POST" action="{{ route('evaluacionCorte.formRegistro') }}">
                        @csrf
                        <input type="hidden" name="orden" value="{{ $encabezadoAuditoriaCorte->orden_id }}">
                        <input type="hidden" name="evento" value="{{ $encabezadoAuditoriaCorte->evento }}">
                        <input type="hidden" name="estilo" value="{{ $encabezadoAuditoriaCorte->estilo_id }}">
                        <input type="hidden" name="estilo" value="{{ $encabezadoAuditoriaCorte->estilo_id }}"> 
                        <input type="hidden" name="auditorDato" value="{{ $auditorDato }}">

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Descripción de partes</th>
                                        <th>Izquierda X</th>
                                        <th>Izquierda Y</th>
                                        <th>Derecha X</th>
                                        <th>Derecha Y</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="descripcion_parte" id="descripcion_parte" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                <option>OTRO</option>
                                                @foreach ($CategoriaParteCorte as $parteCorte)
                                                    <option value="{{ $parteCorte->nombre }}">{{ $parteCorte->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="izquierda_x" id="izquierda_x" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="izquierda_y" id="izquierda_y" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="derecha_x" id="derecha_x" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="derecha_y" id="derecha_y" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success">Añadir</button>
                        </div>
                    </form>
                    @endif
                    <hr>

                    <div class="table-responsive">
                        @if($encabezadoAuditoriaCorte->estatus_evaluacion_corte == '1')
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Descripción de partes</th>
                                    <th>Izquierda X</th>
                                    <th>Izquierda Y</th>
                                    <th>Derecha X</th>
                                    <th>Derecha Y</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registroEvaluacionCorte as $item)
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="descripcion_parte" id="descripcion_parte" value="{{ $item->descripcion_parte ?? '' }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="izquierda_x" id="izquierda_x" value="{{ $item->izquierda_x ?? '' }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="izquierda_y" id="izquierda_y" value="{{ $item->izquierda_y ?? '' }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="derecha_x" id="derecha_x" value="{{ $item->derecha_x ?? '' }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="derecha_y" id="derecha_y" value="{{ $item->derecha_y ?? '' }}" readonly>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Descripción de partes</th>
                                        <th>Izquierda X</th>
                                        <th>Izquierda Y</th>
                                        <th>Derecha X</th>
                                        <th>Derecha Y</th>
                                        <th>Editar</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registroEvaluacionCorte as $item)
                                        <form
                                            action="{{ route('evaluacionCorte.formActualizacionEliminacionEvaluacionCorte', ['id' => $item->id]) }}"
                                            method="POST">
                                            @csrf
                                            <tr>
                                                <td>
                                                    <select name="descripcion_parte" id="descripcion_parte" class="form-control" required>
                                                        <option value="">Seleccione una opción</option>
                                                        @foreach ($CategoriaParteCorte as $parteCorte)
                                                            <option value="{{ $parteCorte->nombre }}" {{ $item->descripcion_parte == $parteCorte->nombre ? 'selected' : '' }}>{{ $parteCorte->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="izquierda_x" id="izquierda_x" class="form-control" required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}"
                                                                @if ($item->izquierda_x == $option) selected @endif>
                                                                {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="izquierda_y" id="izquierda_y" class="form-control" required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}"
                                                                @if ($item->izquierda_y == $option) selected @endif>
                                                                {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="derecha_x" id="derecha_x" class="form-control" required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}"
                                                                @if ($item->derecha_x == $option) selected @endif>
                                                                {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="derecha_y" id="derecha_y" class="form-control" required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($options as $option)
                                                            <option value="{{ $option }}"
                                                                @if ($item->derecha_y == $option) selected @endif>
                                                                {{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" name="action" value="update" class="btn btn-success">Guardar</button>
                                                </td>
                                                <td>
                                                    <button type="submit" name="action" value="delete" class="btn btn-danger">Eliminar</button>
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                    <hr>
                    @if($encabezadoAuditoriaCorte->estatus_evaluacion_corte == '1')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="observacion" class="col-sm-6 col-form-label">Observaciones:</label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="observacion" id="observacion" rows="3" placeholder="comentarios" readonly></textarea>
                                </div>
                            </div>
                        </div>
                    @else
                        <form action="{{ route('evaluacionCorte.formFinalizarEventoCorte') }}" method="POST">
                            @csrf
                            <input type="hidden" name="orden" value="{{ $encabezadoAuditoriaCorte->orden_id }}">
                            <input type="hidden" name="evento" value="{{ $encabezadoAuditoriaCorte->evento }}">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="observacion" class="col-sm-6 col-form-label">Observaciones:</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control" name="observacion" id="observacion" rows="3" placeholder="comentarios" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button type="submit" name="action" class="btn btn-danger">Finalizar</button>
                                </div>
                            </div>
                        </form>
                    @endif

                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>
    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333; /* Color del texto */
        }

    </style>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

<script>

            $('#descripcion_parte').on('change', function() {
                var tecnicaSeleccionada = $(this).val();
                // Si el usuario selecciona 'OTRO', mostrar un prompt para ingresar una nueva opción
                if (tecnicaSeleccionada === 'OTRO') {
                    var nuevaTecnica = prompt('Por favor, ingresa la nueva técnica');
                    // Si el usuario ingresó una nueva técnica, enviarla al servidor
                    if (nuevaTecnica) {
                        $.ajax({
                            url: '/crearCategoriaParteCorte', // Ajusta la URL según tu ruta
                            type: 'POST',
                            data: {
                                nuevaTecnica: nuevaTecnica,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                // Agregar la nueva opción a la lista desplegable
                                $('#descripcion_parte').append($('<option>', {
                                    text: nuevaTecnica
                                }));
                                // Seleccionar la nueva opción
                                $('#descripcion_parte').val(nuevaTecnica);
                            },
                            error: function(error) {
                                console.error('Error al agregar nueva técnica: ', error);
                            }
                        });
                    }
                }
            });
            </script>




@endsection
