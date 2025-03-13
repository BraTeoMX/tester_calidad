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

        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            /* Aumenta el tamaño del botón */
            font-size: 1.2rem;
            /* Aumenta el tamaño de la fuente */
            font-weight: bold;
            /* Texto en negritas */
            border-radius: 10px;
            /* Ajusta las esquinas redondeadas */
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
            /* Cambia el cursor a una mano */
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
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
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

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 50px auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            box-sizing: border-box;
            position: relative;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            /* Alinea título a la izquierda y botón a la derecha */
            background-color: #2e2e2e;
            padding: 15px;
            align-items: center;
        }

        .custom-modal-body {
            padding: 15px;
        }

        /* Estilo para el botón "CERRAR" en la esquina superior derecha */
        .custom-modal-footer {
            margin-right: 10px;
            /* Ajusta el margen derecho si deseas */
        }

        #closeModal {
            font-size: 14px;
            padding: 8px 16px;
        }

        .special-option {
            font-weight: bold;
            /* Negrita */
            font-style: italic;
            /* Cursiva */
            transform: skew(-10deg);
            /* Inclinación */
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
                            <h3 class="card-title">AUDITORIA EN PROCESO</h3>
                        </div>
                        <div class="col-auto">
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-link" id="openModal">
                                <h4>Fecha:
                                    {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                </h4>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal personalizado -->
                <div id="customModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="custom-modal-header">
                            <h5 class="modal-title texto-blanco">Detalles del Proceso</h5>
                            <!-- Botón "CERRAR" en la esquina superior derecha -->
                            <button id="closeModal" class="btn btn-danger">CERRAR</button>
                        </div>
                        <div class="custom-modal-body">
                            <!-- Aquí va el contenido de la tabla -->
                            <div class="table-responsive">
                                <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Acción</th>
                                            <th>Módulo</th>
                                            <th>Estilo</th>
                                            <th>Supervisor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaProcesos1">
                                        <!-- Aquí se insertarán los datos dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-200">
                            <thead class="thead-primary">
                                <tr>
                                    <th>MODULO</th>
                                    <th>ESTILO</th>
                                    <th>SUPERVISOR</th>
                                    <th>GERENTE PRODUCCION</th>
                                    <th>AUDITOR</th>
                                    <th>TURNO</th>
                                    <th>CLIENTE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="moduleid" id="modulo" value="{{ $data['modulo'] }}" readonly>
                                    </td>
                                    <td>
                                        <select class="form-control select2 texto-blanco" name="estilo" id="estilo_proceso">
                                            <option value="">Seleccione un estilo</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader" value="{{ $data['team_leader'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="gerente_produccion" value="{{ $data['gerente_produccion'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="auditor" id="auditor" value="{{ $data['auditor'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="turno" id="turno" value="{{ $data['turno'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="cliente" id="cliente" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table flex-container table932">
                            <thead class="thead-primary">
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>OPERACION</th>
                                    <th>PIEZAS AUDITADAS</th>
                                    <th>PIEZAS RECHAZADAS</th>
                                    <th id="tp-column-header" class="d-none">TIPO DE PROBLEMA</th>
                                    <th id="ac-column-header" class="d-none">ACCION CORRECTIVA</th>
                                    <th>P x P</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="nombre_final" id="lista_nombre" class="form-control select2" required>
                                            <option value="">Selecciona una opción</option>
                                        </select>
                                    </td>                                    
                                    
                                    <td><input type="text" class="form-control" name="pxp" id="pxp"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn-verde-xd">GUARDAR</button> 
                </div>
            </div>
        </div>
    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }

        .table1 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(6) {
            min-width: 250px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(7) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(8) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table1 th:nth-child(2) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .table932 th:nth-child(1) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(2) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(3) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(4) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(5) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(6) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(7) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .texto-blanco {
            color: white !important;
        }

        .table-200 th:nth-child(1) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(2) {
            min-width: 150px;
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
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(6) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .tp-column {
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection--multiple {
            width: 100% !important;
        }
    </style>

    <script>
        $(document).ready(function () {
            // Función para abrir el modal
            function abrirModal() {
                $('#customModal').fadeIn(); // Mostrar con efecto de desvanecimiento
            }

            // Función para cerrar el modal
            function cerrarModal() {
                $('#customModal').fadeOut(); // Ocultar con efecto de desvanecimiento
            }

            // Evento para abrir el modal al hacer clic en el botón
            $('#openModal').on('click', function () {
                abrirModal();

                // Petición AJAX para cargar los datos en la tabla del modal
                $.ajax({
                    url: "{{ route('obtenerListaProcesosV2') }}",
                    type: 'GET',
                    success: function (response) {
                        var tabla = $('#tablaProcesos1');
                        tabla.empty(); // Limpiar tabla antes de insertar nuevos datos

                        if (response.procesos.length === 0) {
                            tabla.append('<tr><td colspan="4" class="text-center">No hay datos disponibles</td></tr>');
                        } else {
                            $.each(response.procesos, function (index, proceso) {
                                var row = `
                                    <tr>
                                        <td>
                                            <form method="POST" action="{{ route('formAltaProcesoV2') }}">
                                                @csrf
                                                <input type="hidden" name="modulo" value="${proceso.modulo}">
                                                <input type="hidden" name="estilo" value="${proceso.estilo}">
                                                <input type="hidden" name="team_leader" value="${proceso.team_leader}">
                                                <input type="hidden" name="gerente_produccion" value="${proceso.gerente_produccion}">
                                                <input type="hidden" name="auditor" value="${proceso.auditor}">
                                                <input type="hidden" name="turno" value="${proceso.turno}">
                                                <button type="submit" class="btn btn-primary">Acceder</button>
                                            </form>
                                        </td>
                                        <td>${proceso.modulo}</td>
                                        <td>${proceso.estilo}</td>
                                        <td>${proceso.team_leader}</td>
                                    </tr>`;
                                tabla.append(row);
                            });
                        }
                    },
                    error: function () {
                        alert('Error al obtener los datos');
                    }
                });
            });

            // Evento para cerrar el modal al hacer clic en el botón de cerrar
            $('#closeModal').on('click', function () {
                cerrarModal();
            });

            // Evento para cerrar el modal al hacer clic fuera del contenido del modal
            $(window).on('click', function (event) {
                if (event.target === document.getElementById('customModal')) {
                    cerrarModal();
                }
            });

            // Evento para cerrar el modal al presionar la tecla "ESC"
            $(document).on('keydown', function (event) {
                if (event.key === "Escape") {
                    cerrarModal();
                }
            });

            // Filtro de búsqueda en la tabla
            $('#searchInput1').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos1 tr').filter(function () {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Inicializar select2
            $('#estilo_proceso').select2({
                placeholder: 'Seleccione un estilo',
                allowClear: true
            });

            function cargarEstilos() {
                var moduleid = $('#modulo').val(); // Asegurar que se está obteniendo el moduleid correctamente

                $.ajax({
                    url: "{{ route('obtenerEstilosV2') }}",
                    type: 'GET',
                    data: { moduleid: moduleid },
                    success: function (response) {
                        var selectEstilo = $('#estilo_proceso');
                        selectEstilo.empty();
                        selectEstilo.append('<option value="">Seleccione un estilo</option>');

                        $.each(response.estilos, function (index, estilo) {
                            var selected = (estilo.itemid == "{{ $data['estilo'] }}") ? "selected" : "";
                            selectEstilo.append('<option value="' + estilo.itemid + '" data-cliente="' + estilo.custname + '" ' + selected + '>' + estilo.itemid + '</option>');
                        });

                        // Disparar el evento de cambio manualmente para actualizar el cliente y la URL
                        selectEstilo.trigger('change');
                    }
                });
            }

            // Cargar estilos al iniciar la página
            cargarEstilos();

            // Cuando se seleccione un estilo, actualizar el cliente automáticamente y cambiar la URL
            $('#estilo_proceso').on('change', function () {
                var cliente = $(this).find(':selected').data('cliente');
                $('#cliente').val(cliente || '');

                var nuevoEstilo = $(this).val(); // Obtener el nuevo estilo seleccionado
                actualizarURL('estilo', nuevoEstilo);
            });

            function actualizarURL(parametro, valor) {
                var url = new URL(window.location.href);
                url.searchParams.set(parametro, valor); // Cambia el valor del parámetro en la URL
                window.history.pushState({}, '', url); // Actualiza la URL sin recargar la página
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#lista_nombre').select2({
                placeholder: 'Selecciona una opción',
                allowClear: true,
                minimumInputLength: 0, // Permite mostrar toda la lista sin escribir
                ajax: {
                    url: "{{ route('obtenerNombresGenerales') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250, // Evita hacer demasiadas peticiones rápidas
                    data: function (params) {
                        return {
                            search: params.term || '', // Si no hay búsqueda, devuelve toda la lista
                            modulo: $('#modulo').val() // Se usa para ordenar los resultados
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.nombres, function (item) {
                                return {
                                    id: item.name, // Se envía el 'name' como valor
                                    text: item.personnelnumber + " - " + item.name // Se muestra 'Número - Nombre'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        });

    </script>

@endsection
