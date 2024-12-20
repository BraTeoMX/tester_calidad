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
            background-color: rgba(0,0,0,0.9);
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            box-sizing: border-box;
            position: relative;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            background-color: #2e2e2e;
            padding: 15px;
            align-items: center;
        }

        .custom-modal-body {
            padding: 15px;
        }

        #closeModalAQL {
            font-size: 14px;
            padding: 8px 16px;
        }

    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!-- Encabezado del card -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA AQL</h3>
                        </div>
                        <div class="col-auto">
                            <h4>
                                Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <!-- Contenido del card -->
                <div class="card-body">
                    <!-- Tabla responsiva -->
                    <div class="table-responsive">
                        <table class="table" id="tabla-datos-principales">
                            <thead class="thead-primary table-100">
                                <tr>
                                    <th>MODULO</th>
                                    <th>OP</th>
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
                                        <input type="text" class="form-control texto-blanco" name="modulo" id="modulo" value="{{ $data['modulo'] }}" readonly>
                                    </td>
                                    <td>
                                        <select class="form-control texto-blanco" name="op_seleccion" id="op_seleccion" required title="Selecciona una OP">
                                            <option value="">Cargando opciones...</option>
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
                                        <input type="text" class="form-control texto-blanco" name="customername" id="customername_hidden" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table32" id="tabla-datos-secundarios">
                            <thead class="thead-primary">
                                <tr>
                                    <th># BULTO</th>
                                    <th>PIEZAS</th>
                                    <th>ESTILO</th>
                                    <th>COLOR</th>
                                    <th>TALLA</th>
                                    <th>PIEZAS INSPECCIONADAS</th>
                                    <th>PIEZAS RECHAZADAS</th>
                                    <th>TIPO DE DEFECTO</th>
                                    <th>ACCION CORRECTIVA</th>
                                    <th>NOMBRE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="bulto_seleccion" id="bulto_seleccion" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Cargando bultos...</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control texto-blanco" name="pieza" id="pieza-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="estilo" id="estilo-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="color" id="color-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="talla" id="talla-seleccion" readonly></td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                    <td> 
                                        <select id="tpSelectAQL" class="form-control w-100" title="Por favor, selecciona una opción"></select>
                                        <div id="selectedOptionsContainerAQL" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
                                    </td>
                                    <td><input type="text" class="form-control" name="accion_correctiva" id="accion_correctiva" required></td>
                                    <td>
                                        <select name="nombre-none" id="nombre_select" class="form-control"></select> 
                                        <div id="selectedOptionsContainerNombre" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn-verde-xd">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para crear un nuevo defecto -->
    <div class="modal fade" id="nuevoConceptoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoConceptoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 id="nuevoConceptoModalLabel">Introduce el nuevo defecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInput" placeholder="Nuevo defecto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarNuevoConcepto">Guardar</button>
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

        .table-100 th:nth-child(1) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(3) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(4) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(8) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(3) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(10) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .table55 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(1) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(2) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(6) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        /* Estilo general para el contenedor de la tabla */
        .contenedor-tabla {
            width: 30%;
            /* Ajusta el ancho según tus necesidades */

        }


        @media (max-width: 768px) {
            .table23 th:nth-child(3) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        #ac-column-header, .ac-column, #nombre-column-header, .nombre-column {
            display: none;
        }

        .texto-blanco {
            color: white !important;
        }
    </style>
    <style>
        .tiempo-extra {
            background-color: #1d0f2c; /* Color gris claro */
        }
        
        /* Asegúrate de que los textos permanezcan visibles */
        .tiempo-extra input, 
        .tiempo-extra .form-control, 
        .tiempo-extra button {
            color: #1d0f2c; 
        }
    </style>

    <script>
        $(document).ready(function () {
            // Configuración de Select2
            const select2Options = {
                placeholder: 'Selecciona una opción',
                allowClear: true,
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
                ajax: {
                    url: "{{ route('obtener.opciones.op') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '', // Enviar búsqueda o vacío para obtener todos
                        };
                    },
                    processResults: function (data) {
                        // Si no hay datos, Select2 mostrará automáticamente el mensaje "No se encontraron resultados"
                        return {
                            results: data.map(item => ({
                                id: item.prodid,
                                text: item.prodid,
                            })),
                        };
                    },
                    cache: true,
                },
            };

            const opSelect = $('#op_seleccion');

            // Inicializa Select2
            opSelect.select2(select2Options);

            // Función para obtener el parámetro de la URL
            function getParameterByName(name) {
                const url = new URL(window.location.href);
                return url.searchParams.get(name);
            }

            // Función para preseleccionar el valor basado en el parámetro de la URL
            function preselectValue() {
                const selectedValue = getParameterByName('op'); // Obtiene el valor del parámetro 'op'
                if (selectedValue) {
                    // Crea una opción temporal y selecciónala
                    const optionExists = opSelect.find(`option[value="${selectedValue}"]`).length > 0;
                    if (!optionExists) {
                        const newOption = new Option(selectedValue, selectedValue, true, true);
                        opSelect.append(newOption).trigger('change');
                    } else {
                        opSelect.val(selectedValue).trigger('change');
                    }
                }
            }

            // Llama a la función para preseleccionar el valor al cargar
            preselectValue();

            // Maneja el evento 'change' para no actualizar la URL
            opSelect.on('change', function () {
                const selectedValue = $(this).val();
                console.log('Valor seleccionado:', selectedValue); // Realiza acciones según sea necesario
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const opSelect = $('#op_seleccion');
            const bultoSelect = $('#bulto_seleccion');

            // Configuración de Select2 para "op_seleccion"
            opSelect.select2({
                placeholder: 'Selecciona una OP',
                allowClear: true,
                ajax: {
                    url: "{{ route('obtener.opciones.op') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '',
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.prodid,
                                text: item.prodid,
                            })),
                        };
                    },
                    cache: true,
                },
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
            });

            // Configuración de Select2 para "bulto_seleccion"
            bultoSelect.select2({
                placeholder: 'Selecciona un bulto',
                allowClear: true,
                ajax: {
                    url: "{{ route('obtener.opciones.bulto') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        const selectedOp = opSelect.val();
                        if (!selectedOp) {
                            console.error("Debes seleccionar una OP primero.");
                            return {};
                        }
                        return {
                            op: selectedOp,
                            search: params.term || '',
                        };
                    },
                    processResults: function (data) {
                        // Si no hay datos o se encuentra un error, muestra un mensaje vacío
                        if (!data || data.length === 0) {
                            return { results: [] };
                        }
                        return {
                            results: data.map(item => ({
                                id: item.prodpackticketid,
                                text: item.prodpackticketid,
                                extra: item, // Almacena datos adicionales
                            })),
                        };
                    },
                    cache: true,
                },
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
            });

            // Evento para recargar "bulto_seleccion" al cambiar "op_seleccion"
            opSelect.on('change', function () {
                bultoSelect.val(null).trigger('change');
            });

            // Evento para manejar selección de "bulto_seleccion"
            bultoSelect.on('select2:select', function (e) {
                const data = e.params.data.extra; // Obtén los datos adicionales del registro seleccionado

                if (data) {
                    $('#pieza-seleccion').val(data.qty || '');
                    $('#estilo-seleccion').val(data.itemid || '');
                    $('#color-seleccion').val(data.colorname || '');
                    $('#talla-seleccion').val(data.inventsizeid || '');
                    $('#customername_hidden').val(data.customername || '');
                    // Opcional: Almacena datos adicionales en inputs ocultos
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'inventcolorid',
                        value: data.inventcolorid || '',
                    }).appendTo('form');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const tpSelect = $('#tpSelectAQL');
            const selectedOptionsContainer = $('#selectedOptionsContainerAQL');

            // Configuración de Select2
            tpSelect.select2({
                placeholder: 'Selecciona una o más opciones',
                allowClear: true,
                ajax: {
                    url: "{{ route('obtener.defectos.aql') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { search: params.term || '' };
                    },
                    processResults: function (data) {
                        const options = data.map(item => ({
                            id: item.nombre,
                            text: item.nombre,
                        }));
                        options.unshift({ id: 'CREAR_DEFECTO', text: 'CREAR DEFECTO', action: true });
                        return { results: options };
                    },
                    cache: true,
                },
                templateResult: function (data) {
                    if (data.action) {
                        return $('<span style="color: #007bff; font-weight: bold;">' + data.text + '</span>');
                    }
                    return data.text;
                },
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
            });

            // Evento al seleccionar una opción
            tpSelect.on('select2:select', function (e) {
                const selected = e.params.data;

                if (selected.id === 'CREAR_DEFECTO') {
                    $('#nuevoConceptoModal').modal('show');
                    tpSelect.val(null).trigger('change'); // Resetea la selección
                    return;
                }

                // Agregar la selección al contenedor
                addOptionToContainer(selected.id, selected.text);
                tpSelect.val(null).trigger('change');
            });

            // Agregar la opción seleccionada al contenedor
            function addOptionToContainer(id, text) {
                // Crear un elemento de la lista
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1">
                        <button class="btn btn-primary btn-sm duplicate-option">+</button>
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option">Eliminar</button>
                    </div>
                `);

                // Añadir eventos para los botones
                optionElement.find('.duplicate-option').on('click', function () {
                    addOptionToContainer(id, text); // Duplicar la opción
                });

                optionElement.find('.remove-option').on('click', function () {
                    optionElement.remove(); // Eliminar la opción
                });

                // Agregar la opción al contenedor
                selectedOptionsContainer.append(optionElement);
            }

            // Evento para abrir el modal y crear un nuevo defecto
            $('#guardarNuevoConcepto').on('click', function () {
                const nuevoDefecto = $('#nuevoConceptoInput').val();

                if (!nuevoDefecto) {
                    alert('Por favor, ingresa un defecto válido.');
                    return;
                }

                $.ajax({
                    url: "{{ route('crear.defecto.aql') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        nombre: nuevoDefecto,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        const newOption = new Option(data.nombre, data.nombre, true, true);
                        tpSelect.append(newOption).trigger('change');
                        addOptionToContainer(data.nombre, data.nombre); // Agregar al contenedor
                        $('#nuevoConceptoModal').modal('hide');
                        $('#nuevoConceptoInput').val('');
                    },
                    error: function (xhr) {
                        alert('Ocurrió un error al guardar el defecto: ' + xhr.responseJSON.error);
                    },
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const nombreSelect = $('#nombre_select');
            const selectedOptionsContainerNombre = $('#selectedOptionsContainerNombre');
            const selectedIds = new Set(); // Usamos un Set para almacenar los IDs seleccionados

            // Configuración de Select2 con datos cargados desde el servidor
            nombreSelect.select2({
                placeholder: 'Selecciona una opción',
                allowClear: true,
                ajax: {
                    url: "{{ route('obtener.nombres.proceso') }}", // Ruta al controlador que devuelve los datos
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function () {
                        return {
                            modulo: $('#modulo').val(), // Obtén el valor del input con id "modulo"
                        };
                    },
                    processResults: function (data) {
                        // Mapeo de resultados
                        const options = data.map(item => ({
                            id: item.name, // Asume que los nombres vienen en la propiedad "name"
                            text: item.name,
                        }));

                        return { results: options };
                    },
                    cache: true,
                },
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
            });

            // Evento al seleccionar una opción
            nombreSelect.on('select2:select', function (e) {
                const selected = e.params.data;

                // Verifica si ya existe en el contenedor
                if (selectedIds.has(selected.id)) {
                    alert('Esta opción ya ha sido seleccionada.');
                    nombreSelect.val(null).trigger('change'); // Resetea el select
                    return;
                }

                // Agregar la selección al contenedor
                addOptionToContainer(selected.id, selected.text);
                nombreSelect.val(null).trigger('change');
            });

            // Agregar la opción seleccionada al contenedor
            function addOptionToContainer(id, text) {
                // Marcar el ID como seleccionado
                selectedIds.add(id);

                // Crear un elemento de la lista
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1" data-id="${id}">
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option">Eliminar</button>
                    </div>
                `);

                // Añadir evento para eliminar
                optionElement.find('.remove-option').on('click', function () {
                    // Eliminar del contenedor
                    optionElement.remove();
                    // Eliminar el ID de la lista de seleccionados
                    selectedIds.delete(id);
                });

                // Agregar la opción al contenedor
                selectedOptionsContainerNombre.append(optionElement);
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            // Identificadores de las tablas específicas
            const tablasObjetivo = ['#tabla-datos-principales', '#tabla-datos-secundarios'];

            // Inicializa las columnas ocultas
            const columnasPosteriores = $('th:contains("TIPO DE DEFECTO"), th:contains("ACCION CORRECTIVA"), th:contains("NOMBRE")')
                .add('td:nth-child(8), td:nth-child(9), td:nth-child(10)');
            columnasPosteriores.hide(); // Ocultar al inicio

            // Detectar cambios en el campo cantidad_rechazada
            $('#cantidad_rechazada').on('input', function () {
                const valor = $(this).val();

                if (valor > 0) {
                    columnasPosteriores.show(); // Mostrar columnas
                    columnasPosteriores.find('input, select').prop('required', true); // Hacer obligatorios
                } else {
                    columnasPosteriores.hide(); // Ocultar columnas
                    columnasPosteriores.find('input, select').prop('required', false); // Quitar obligatoriedad
                }
            });

            // Evento del botón "Guardar"
            $('.btn-verde-xd').on('click', function (e) {
                e.preventDefault(); // Prevenir el envío estándar

                let esValido = true;
                let formData = {};

                // Obtenemos el valor actual de cantidad_rechazada para saber si validamos ciertos campos
                const valorCantidadRechazada = $('#cantidad_rechazada').val();

                // Validar inputs y selects visibles (excepto los excluidos)
                $(`${tablasObjetivo.join(', ')} input:visible, ${tablasObjetivo.join(', ')} select:visible`).not('#tpSelectAQL, #nombre_select').each(function () {
                    const name = $(this).attr('name'); 
                    const value = $(this).val();

                    if ($(this).prop('required') && !value) {
                        esValido = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }

                    if (name) {
                        formData[name] = value;
                    }
                });

                // Si hay algún campo requerido vacío, mostrar alerta genérica
                if (!esValido) {
                    alert('Por favor, completa todos los campos requeridos.');
                    return; 
                }

                // Validaciones adicionales si cantidad_rechazada > 0
                if (valorCantidadRechazada > 0) {
                    if ($('#selectedOptionsContainerAQL').children().length === 0) {
                        alert('Por favor, selecciona al menos una opción en "Tipo de Defecto".');
                        return;
                    }

                    if ($('#selectedOptionsContainerNombre').children().length === 0) {
                        alert('Por favor, selecciona al menos una opción en "Nombre".');
                        return;
                    }

                    const defectCount = $('#selectedOptionsContainerAQL .selected-option').length;
                    const cantRechazadaNum = parseInt(valorCantidadRechazada, 10);
                    if (defectCount !== cantRechazadaNum) {
                        alert(`La cantidad de defectos seleccionados (${defectCount}) debe coincidir con las piezas rechazadas (${cantRechazadaNum}).`);
                        return;
                    }
                }

                // Serializar las opciones seleccionadas en caso de que cantidad_rechazada > 0
                if (valorCantidadRechazada > 0) {
                    // Procesar `selectedAQL` eliminando el texto de los botones (por ejemplo, "Eliminar")
                    const selectedAQL = [];
                    $('#selectedOptionsContainerAQL .selected-option').each(function () {
                        let text = $(this).text().trim();
                        // Remover palabras específicas como "Eliminar"
                        text = text.replace(/^\+/, '').replace(/\bEliminar\b/g, '').trim();
                        selectedAQL.push(text);
                    });
                    formData['selectedAQL'] = selectedAQL;

                    // Procesar `selectedNombre` eliminando el texto de los botones (por ejemplo, "Eliminar")
                    const selectedNombre = [];
                    $('#selectedOptionsContainerNombre .selected-option').each(function () {
                        let text = $(this).text().trim();
                        // Remover palabras específicas como "Eliminar"
                        text = text.replace(/\bEliminar\b/g, '').trim();
                        selectedNombre.push(text);
                    });
                    formData['selectedNombre'] = selectedNombre;
                } else {
                    // Si es 0, no agregamos estos datos
                    formData['selectedAQL'] = [];
                    formData['selectedNombre'] = [];
                }

                // ** Ajuste adicional **
                // Reasignamos siempre los valores de la primera tabla para asegurarnos 
                // de que se incluyan sin importar el valor de cantidad_rechazada.
                $('#tabla-datos-principales input, #tabla-datos-principales select').each(function () {
                    const name = $(this).attr('name'); 
                    const value = $(this).val();
                    if (name && typeof formData[name] === 'undefined') {
                        formData[name] = value;
                    }
                });

                // Enviar datos mediante AJAX
                $.ajax({
                    url: "{{ route('guardar.registro.aql') }}", // Reemplaza con la ruta correcta
                    type: 'POST',
                    data: {
                        ...formData,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        alert('Datos guardados correctamente.');

                        // Si cantidad_rechazada es mayor a 0, recargar la página
                        if ($('#cantidad_rechazada').val() > 0) {
                            location.reload(); // Recargar la página
                        } else {
                            // Limpiar los campos de la segunda tabla
                            $('#bulto_seleccion').val('').trigger('change');
                            $('#pieza-seleccion').val('');
                            $('#estilo-seleccion').val('');
                            $('#color-seleccion').val('');
                            $('#talla-seleccion').val('');
                            $('#cantidad_auditada').val('');
                            $('#cantidad_rechazada').val('');
                            $('#selectedOptionsContainerAQL').empty();
                            $('#accion_correctiva').val('');
                            $('#selectedOptionsContainerNombre').empty();

                        }
                    },
                    error: function (xhr) {
                        alert('Hubo un error al guardar los datos. Por favor, intenta nuevamente.');
                    }
                });
            });
        });
    </script>

@endsection
