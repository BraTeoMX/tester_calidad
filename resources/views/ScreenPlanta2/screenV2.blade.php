@extends('layouts.app', ['pageSlug' => 'Screen', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos todos los elementos de alerta
            const alerts = document.querySelectorAll('.alert');

            // Iteramos por cada alerta para aplicar el desvanecido
            alerts.forEach(alert => {
                // Esperamos 6 segundos antes de iniciar el desvanecido
                setTimeout(() => {
                    // Cambiamos la opacidad para el efecto de desvanecido
                    alert.style.transition = 'opacity 1s ease';
                    alert.style.opacity = '0';

                    // Eliminamos el elemento del DOM después de 1 segundo (duración del desvanecido)
                    setTimeout(() => alert.remove(), 1000);
                }, 5000); // Tiempo de espera antes de desvanecer (6 segundos)
            });
        });
    </script>
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

    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Screen </h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-body table-responsive">
                    <table class="table table-striped" id="tabla-screen-strart">
                        <thead class="thead-primary">
                            <tr>
                                <th>Gran total revisado</th>
                                <th>Gran total de defectos</th>
                                <th>Porcentaje de defectos</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertarán los datos dinámicos con AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body table-responsive">
                    <h4>Control de horno</h4>
                    <form id="formInspeccion" method="POST" action="{{ route('formControlHorno') }}">
                        @csrf
                        <table class="table table-striped" id="control-horno">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Temperatura horno</th>
                                    <th>Velocidad de banda</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- Columna de Temperatura con Select -->
                                    <td>
                                        <select class="form-control temperatura-horno" name="grados">
                                            <option value="160">160 °</option>
                                            <option value="180">180 °</option>
                                        </select>
                                    </td>
                                    <!-- Columna de Velocidad de Banda con Selects para Minutos y Segundos -->
                                    <td>
                                        <div class="d-flex">
                                            <select class="form-control velocidad-min"
                                                style="width: 60px; margin-right: 5px;" name="minuto">
                                                <!-- Se generarán dinámicamente las opciones -->
                                            </select>
                                            <span>:</span>
                                            <select class="form-control velocidad-sec"
                                                style="width: 60px; margin-left: 5px;" name="segundo">
                                                <!-- Se generarán dinámicamente las opciones -->
                                            </select>
                                        </div>
                                    </td>
                                    <!-- Botón de Acción -->
                                    <td>
                                        <button class="btn-verde-xd btn-accion">Guardar</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <h3>Registros por dia</h3>
                    <table class="table table-striped" id="tabla-screen">
                        <thead class="thead-primary">
                            <tr>
                                <th>OP</th>
                                <th>Panel</th>
                                <th>Máquina</th>
                                <th>Técnicas</th> <!-- Nueva columna -->
                                <th>Fibras</th> <!-- Nueva columna -->
                                <th>Gráfica</th>
                                <th>Cliente</th>
                                <th>Estilo</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                                <th>Técnico Screen</th>
                                <th>Defectos</th>
                                <th>Acción Correctiva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertarán los datos dinámicos con AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-body table-responsive">
                    <h4>Control de horno</h4>
                    <table class="table table-striped">
                        <thead class="thead-primary">
                            <tr>
                                <th>Temperatura horno</th>
                                <th>Velocidad de banda</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registroHornoDia as $horno)
                                <tr>
                                    <td>{{ $horno->temperatura_horno }}</td>
                                    <td>{{ $horno->velocidad_banda }}</td>
                                    <td>{{ $horno->hora }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 10px;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
        }

        .btn-verde-xd:hover {
            color: #fff !important;
            background-color: #218838 !important;
            border-color: #1e7e34 !important;
        }

        .btn-verde-xd:focus,
        .btn-verde-xd.focus {
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        .btn-verde-xd:disabled,
        .btn-verde-xd.disabled {
            color: #ffffff !important;
            background-color: #4bce67 !important;
            /* Verde más claro */
            border-color: #4bce67 !important;
            cursor: not-allowed !important;
            /* Cursor de "prohibido" */
            opacity: 0.6;
            /* Reduce opacidad */
            box-shadow: none !important;
            /* Elimina sombra */
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active,
        .btn-verde-xd:not(:disabled):not(.disabled):active,
        .show>.btn-verde-xd.dropdown-toggle {
            color: #fff !important;
            background-color: #1e7e34 !important;
            border-color: #1c7430 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .show>.btn-verde-xd.dropdown-toggle:focus {
            box-shadow: none, 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }

        .texto-blanco {
            color: white !important;
        }

        /* Ajusta Select2 dentro de las celdas de la tabla */
        td .select2-container {
            width: 100% !important;
        }

        /* Corrige el padding para que el Select2 no sobresalga */
        td .select2-selection {
            height: 100% !important;
            padding: 4px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Asegurar que las celdas no se agranden demasiado */
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Si usas un tema oscuro, cambia los colores del Select2 */
        .select2-container--default .select2-selection--single {
            background-color: #1e1e1e;
            /* Color de fondo oscuro */
            color: #ffffff;
            /* Texto blanco */
            border: 1px solid #444;
            /* Borde más discreto */
        }

        /* Estilos base para el contenedor del checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        /* Ocultar el checkbox original */
        .form-check input[type="checkbox"] {
            display: none;
        }

        /* Crear el checkbox personalizado */
        .form-check label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-check label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #333;
            border-radius: 4px;
            background-color: #fff;
            transition: all 0.3s;
        }

        /* Icono de la palomita cuando está marcado */
        .form-check input[type="checkbox"]:checked+label::before {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .form-check input[type="checkbox"]:checked+label::after {
            content: "✔";
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: white;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function() {
            cargarRegistros();
        });

        function cargarRegistros() {
            $.ajax({
                url: "{{ route('screenV2.data') }}",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let tabla = "";
                    data.forEach(registro => {
                        tabla += `<tr>
                            <td>${registro.op}</td>
                            <td>${registro.panel}</td>
                            <td>${registro.maquina}</td>
                            <td>${registro.tecnicas}</td>
                            <td>${registro.fibras}</td>
                            <td>${registro.grafica}</td>
                            <td>${registro.cliente}</td>
                            <td>${registro.estilo}</td>
                            <td>${registro.color}</td>
                            <td>${registro.cantidad}</td>
                            <td>${registro.tecnico_screen}</td>
                            <td>${registro.defectos}</td>
                            <td>${registro.accion_correctiva}</td>
                        </tr>`;
                    });
                    $("#tabla-screen tbody").html(tabla);
                },
                error: function(error) {
                    console.error("Error al obtener datos:", error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            cargarDatosEstadisticos();
        });

        function cargarDatosEstadisticos() {
            $.ajax({
                url: "{{ route('screenV2.strart') }}", // Llamamos a la ruta de la función getScreenStats
                method: "GET",
                dataType: "json",
                success: function(data) {
                    let fila = `
                        <tr>
                            <td>${data.cantidad_total_revisada}</td>
                            <td>${data.cantidad_defectos}</td>
                            <td>${data.porcentaje_defectos} %</td>
                        </tr>
                    `;

                    $("#tabla-screen-strart tbody").html(fila);
                },
                error: function(error) {
                    console.error("Error al obtener datos estadísticos:", error);
                }
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            // Generar opciones para los minutos (01 al 05)
            let minutosSelect = $(".velocidad-min");
            for (let i = 1; i <= 5; i++) {
                let valorFormateado = i.toString().padStart(2, '0'); // Asegura "01", "02", etc.
                minutosSelect.append(new Option(valorFormateado, valorFormateado));
            }

            // Generar opciones para los segundos (00, 10, 20, ..., 50)
            let segundosSelect = $(".velocidad-sec");
            for (let i = 0; i <= 50; i += 10) {
                let valorFormateado = i.toString().padStart(2, '0'); // Asegura "00", "10", etc.
                segundosSelect.append(new Option(valorFormateado, valorFormateado));
            }
        });
    </script>

@endsection
