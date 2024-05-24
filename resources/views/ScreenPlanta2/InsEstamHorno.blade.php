@extends('layouts.app', ['pageSlug' => 'InspeccionEstampado', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')
    <style>
        .negative-image {
            filter: invert(100%);
        }
    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">{{ __('Inspeccion Estampado Despues del Horno.') }}</h3>
                        </div>
                        <div class="col-md-6 text-right">
                            Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header card-header-primary text-center">
                                <h4 class="card-title mb-3">{{ __('Informacion General.') }}</h4>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="ordenSelect">Seleccion de op:</label>
                            <select class="form-control" id="ordenSelect" name="ordenSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="clienteSelect">Seleccion de cliente:</label>
                            <select class="form-control" id="clienteSelect" name="clienteSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>

                        </div>
                        <div class="col-md-2">
                            <label for="estiloSelect">Seleccion de estilo:</label>
                            <select class="form-control" id="estiloSelect" name="estiloSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tecnicosSelect">Seleccion del tecnico:</label>
                            <select class="form-control" id="tecnicosSelect" name="tecnicosSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputColor">Ingresa color:</label>
                            <input class="form-control" id="inputColor" name="inputColor" required>

                        </div>
                        <div class="col-md-2">
                            <label for="inputGrafico">Ingresa # de Grafico:</label>
                            <input class="form-control" id="inputGrafico" name="inputGrafico" required>
                        </div>
                        <div class="col-md-2">
                            <form class="form-inline">
                                <label class="my-1 mr-2" for="inputTipoMaquina" name="inputTipoMaquina">Tipo de
                                    Maquina</label>
                                <select class="custom-select my-1 mr-sm-2" id="inputTipoMaquina" name="inputTipoMaquina">
                                    <option selected>Seleccion tipo de maquina</option>
                                    <option value="Oval1">Oval1</option>
                                    <option value="Oval2">Oval2</option>
                                    <option value="Eco">Eco</option>
                                    <option value="You">You</option>
                                    <option value="Challenger">Challenger</option>
                                    <option value="Otra">Otra</option>
                                </select>
                                <input type="text" class="form-control my-1 mr-sm-2" id="otraTipoMaquina"
                                    name="otraTipoMaquina" placeholder="Especificar otra máquina" style="display: none;">
                            </form>
                        </div>
                        <div class="col-md-2">
                            <label for="inputLeyendaSprint">Leyenda Sprint:</label>
                            <input class="form-control" id="inputLeyendaSprint" name="inputLeyendaSprint" required>
                        </div>
                        <div class="col-md-2">
                            <label for="tecnicaSelect">Seleccion de tipo de tecnica:</label>
                            <select class="form-control" id="tecnicaSelect" name="tecnicaSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputpiezasxbulto">Ingresa piezas a auditar:</label>
                            <input type="number" class="form-control" id="inputpiezasxbulto" name="inputpiezasxbulto"
                                required>
                        </div>
                        <div class="col-md-2">
                            <label for="fibraSelect">Seleccion de tipo de fibra:</label>
                            <select class="form-control" id="fibraSelect" name="fibraSelect[]" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="button" id="insertarFila">
                            <span class="button__text">Añadir</span>
                            <span class="button__icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke-linejoin="round" stroke-linecap="round" stroke="currentColor" height="24"
                                    fill="none" class="svg">
                                    <line y2="19" y1="5" x2="12" x1="12"></line>
                                    <line y2="12" y1="12" x2="19" x1="5"></line>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card" style="width: auto;">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title">{{ __('Inspección en  Bultos.') }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Tabla de resultados -->
                    <br>
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                            <div class="card card-stats">
                                <div class="card-header card-header-success card-header-icon">
                                    <div class="card-icon">
                                        <i class="material-icons">fact_check</i>
                                    </div>
                                    <h3 class="card-title">Gran total revisado.
                                        <br>
                                        <small id="granTotalRevisando"> </small>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                            <div class="card card-stats">
                                <div class="card-header card-header-danger card-header-icon">
                                    <div class="card-icon">
                                        <i><img style="width:50px" class="negative-image"
                                                src="{{ asset('material') }}/img/breaking_news_alt_1.svg"></i>
                                    </div>
                                    <h3 class="card-title">Gran total defectos.
                                        <br>
                                        <small id="granTotalDefectos"> </small>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 mx-auto">
                            <div class="card card-stats">
                                <div class="card-header card-header-warning card-header-icon">
                                    <div class="card-icon">
                                        <i><img style="width:50px" class="negative-image"
                                                src="{{ asset('material') }}/img/swap_driving_apps_wheel.svg"></i>
                                    </div>
                                    <h3 class="card-title">% Defectos totales.
                                        <br>
                                        <small id="porcentajeDefectos"> </small>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="messages">
                        <div class="card-body table-responsive">
                            <table class="table-cebra" id="miTabla">
                                <thead class="text-primary">
                                    <tr>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 1.5%;">
                                            ID</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 7.1%;">
                                            Auditor</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 5%;">
                                            Cliente</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 5%;">
                                            Estilo</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 5%;">
                                            OP</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 3%">
                                            Tecnico</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 3.5%;">
                                            Color</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 3.5%;">
                                            # Grafico</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 3.5%;">
                                            Tipo de Maquina</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 4.5%;">
                                            Leyenda Sprint</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 3.5%;">
                                            Tecnica</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 2.5%;">
                                            Piezas a auditar</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 6.5%;">
                                            Fibras</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 6.6%;">
                                            % de Fibras</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 2.9%;">
                                            Hora</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 2.5%;">
                                            Bulto</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 2.5%;">
                                            Talla</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 6.5%;">
                                            Tipo Defectos</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 2.5%;">
                                            # Defectos</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 8.7%;">
                                            Acciones Correctivas</th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 7.5%;">
                                        </th>
                                        <th
                                            style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 8.5%;">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-danger" id="Finalizar">
                                                <span>Finalizar</span>
                                            </button>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para el cliente
            $('#clienteSelect').select2({
                placeholder: 'Seleccione un cliente',
                allowClear: true
            });
            $('#estiloSelect').select2({
                placeholder: 'Seleccione un estilo',
                allowClear: true
            });
            // Inicializar Select2 para la orden
            $('#ordenSelect').select2({
                placeholder: 'Seleccione una orden',
                allowClear: true
            });
            // Inicializar Select2 para la orden
            $('#tecnicosSelect').select2({
                placeholder: 'Seleccione un tecnico',
                allowClear: true
            });
            $('#tecnicaSelect').select2({
                placeholder: 'Seleccione una tecnica',
                allowClear: true
            });
            // Contenedor para los inputs
            var contenedorInputs = $('<div>', {
                class: 'col-md-2 style: text-align: right;'
            });

            // Agregar el contenedor después del Select2
            $('.select2-container').parent().append(contenedorInputs);

            $('#fibraSelect').select2({
                placeholder: 'Seleccione una o varias fibras',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });

            function crearInputs(selectedOptions) {
                // Limpiar contenedor anterior
                contenedorInputs.empty();
                // Crear un input para cada opción seleccionada
                if (selectedOptions && selectedOptions.length > 0) {
                    // Contenedor para los inputs en línea
                    var contenedorInputsEnLinea = $('<div>', {
                        class: 'col-md-12 d-flex'
                    });
                    // Variable para almacenar la suma de los porcentajes
                    var sumaPorcentajes = 0;
                    var inputs = [];
                    $.each(selectedOptions, function(index, fibra) {
                        var porcentajeInput = $('<input>', {
                            type: 'number',
                            class: 'porcentajeInput form-control mr-2',
                            name: 'porcentajeInput[]',
                            placeholder: '% para ' + fibra,
                            min: 0,
                            max: 100,
                            required: true,
                            style: 'width: 150px;' // Ajusta el ancho según tus preferencias
                        });
                        // Etiqueta para el nombre de la fibra
                        var etiquetaFibra = $('<label>', {
                            text: fibra + ': ',
                            class: 'mr-2'
                        });
                        // Contenedor individual para cada fibra
                        var contenedorFibra = $('<div>', {
                            class: 'd-flex align-items-center'
                        });
                        contenedorFibra.append(etiquetaFibra);
                        contenedorFibra.append(porcentajeInput);
                        // Agregar el contenedor de fibra al contenedor general
                        contenedorInputsEnLinea.append(contenedorFibra);
                        // Guardar referencia al input
                        inputs.push(porcentajeInput);
                    });
                    // Agregar el contenedor de inputs en línea al contenedor general
                    contenedorInputs.append(contenedorInputsEnLinea);

                    // Evento de cambio en el input de porcentaje
                    inputs.forEach(function(input) {
                        input.on('input', function() {
                            // No hacer nada aquí
                        });
                    });
                    // Evento al salir del último input
                    inputs[inputs.length - 1].on('blur', function() {
                        // Actualizar la suma de los porcentajes al salir del último input
                        sumaPorcentajes = calcularSumaPorcentajes();
                        validarSumaPorcentajes();
                    });
                    // Función para calcular la suma de los porcentajes
                    function calcularSumaPorcentajes() {
                        var suma = 0;
                        inputs.forEach(function(input) {
                            suma += parseFloat(input.val()) || 0;
                        });
                        return suma;
                    }
                    // Función para validar y ajustar la suma de los porcentajes
                    function validarSumaPorcentajes() {
                        if (sumaPorcentajes < 100) {
                            alert(
                                'La suma de los porcentajes no puede ser menor al 100%. Se ajustará automáticamente.'
                            );
                            // Ajustar automáticamente el último porcentaje ingresado
                            var ultimoInput = inputs[inputs.length - 1];
                            var nuevoValor = 100 - (sumaPorcentajes - parseFloat(ultimoInput.val()));
                            ultimoInput.val(nuevoValor);
                        } else if (sumaPorcentajes > 100) {
                            alert(
                                'La suma de los porcentajes no puede superar el 100%. Se ajustará automáticamente.'
                            );
                            // Ajustar automáticamente el último porcentaje ingresado
                            var ultimoInput = inputs[inputs.length - 1];
                            var nuevoValor = 100 - (sumaPorcentajes - parseFloat(ultimoInput.val()));
                            ultimoInput.val(nuevoValor);
                        }
                    }
                }
            }
            // Inicializar los inputs al cargar la página
            crearInputs($('#fibraSelect').val());
            $('#fibraSelect').on('select2:select select2:unselect', function(e) {
                var selectedOptions = $(this).val();
                // Crear o actualizar los inputs al seleccionar o quitar opciones
                crearInputs(selectedOptions);
            });
            // Agregar el contenedor de inputs después del último div dentro de card-body
            $('.card-body .row').append(contenedorInputs);
            // Obtener las órdenes al cargar la página
            $.ajax({
                url: '/Ordenes',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar las opciones existentes
                    $('#ordenSelect').empty();
                    // Agregar la opción predeterminada
                    $('#ordenSelect').append($('<option>', {
                        disabled: true,
                        selected: true,

                    }));
                    // Agregar las nuevas opciones desde la respuesta del servidor
                    $.each(data, function(key, value) {
                        $('#ordenSelect').append($('<option>', {
                            text: value.op
                        }));
                    });
                },
                error: function(error) {
                    console.error('Error al cargar opciones de ordenes: ', error);
                }
            });

            // Evento de cambio en el select de órdenes
            $('#ordenSelect').on('change', function() {
                var ordenselect = $(this).val();

                // Realizar la solicitud para obtener los clientes asociados a la orden seleccionada
                $.ajax({
                    url: '/Clientes/' + ordenselect,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#clienteSelect').empty();
                        // Agregar la opción predeterminada
                        $('#clienteSelect').append($('<option>', {
                            disabled: true,
                            selected: true,

                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#clienteSelect').append($('<option>', {
                                text: value.custorname
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones de clientes: ', error);
                    }
                });
            });
            // Evento de cambio en el select de clientes
            $('#ordenSelect').on('change', function() {
                var ordenselect = $(this).val();

                // Realizar la solicitud para obtener los estilos asociados al cliente seleccionado
                $.ajax({
                    url: '/Estilo/' + ordenselect,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#estiloSelect').empty();
                        // Agregar la opción predeterminada
                        $('#estiloSelect').append($('<option>', {
                            disabled: true,
                            selected: true,

                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#estiloSelect').append($('<option>', {
                                text: value.estilo
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones de estilos: ', error);
                    }
                });
            });


            $.ajax({
                url: '/Tecnicos', // Ajusta la URL según tu ruta
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar las opciones existentes
                    $('#tecnicosSelect').empty();
                    // Agregar la opción predeterminada
                    $('#tecnicosSelect').append($('<option>', {
                        disabled: true,
                        selected: true
                    }));
                    // Agregar las nuevas opciones desde la respuesta del servidor
                    $.each(data, function(key, value) {
                        $('#tecnicosSelect').append($('<option>', {
                            text: value.Nom_Tecnico
                        }));
                    });
                },
                error: function(error) {
                    console.error('Error al cargar opciones de Tecnicos: ', error);
                }

            });
            $.ajax({
                url: '/TipoTecnica', // Ajusta la URL según tu ruta
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar las opciones existentes
                    $('#tecnicaSelect').empty();
                    // Agregar la opción predeterminada
                    $('#tecnicaSelect').append($('<option>', {
                        disabled: true,
                        selected: true
                    }));
                    // Agregar las nuevas opciones desde la respuesta del servidor
                    $.each(data, function(key, value) {
                        $('#tecnicaSelect').append($('<option>', {
                            text: value.Tipo_tecnica
                        }));
                    });
                },
                error: function(error) {
                    console.error('Error al cargar opciones de clientes: ', error);
                }
            });
            $.ajax({
                url: '/TipoFibra', // Ajusta la URL según tu ruta
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Limpiar las opciones existentes
                    $('#fibraSelect').empty();
                    // Agregar las nuevas opciones desde la respuesta del servidor
                    $.each(data, function(key, value) {
                        $('#fibraSelect').append($('<option>', {
                            text: value.Tipo_Fibra
                        }));
                    });
                },
                error: function(error) {
                    console.error('Error al cargar opciones de clientes: ', error);
                }
            });
            $('#tecnicaSelect').on('change', function() {
                var tecnicaSeleccionada = $(this).val();
                // Si el usuario selecciona 'Otra', mostrar un prompt para ingresar una nueva opción
                if (tecnicaSeleccionada === 'Otra') {
                    var nuevaTecnica = prompt('Por favor, ingresa la nueva técnica');
                    // Si el usuario ingresó una nueva técnica, enviarla al servidor
                    if (nuevaTecnica) {
                        $.ajax({
                            url: '/AgregarTecnica', // Ajusta la URL según tu ruta
                            type: 'POST',
                            data: {
                                nuevaTecnica: nuevaTecnica,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                // Agregar la nueva opción a la lista desplegable
                                $('#tecnicaSelect').append($('<option>', {
                                    text: nuevaTecnica
                                }));
                                // Seleccionar la nueva opción
                                $('#tecnicaSelect').val(nuevaTecnica);
                            },
                            error: function(error) {
                                console.error('Error al agregar nueva técnica: ', error);
                            }
                        });
                    }
                }
            });
            $('#fibraSelect').on('change', function() {
                var fibrasSeleccionadas = $(this).val();
                // Verificar si 'Otra' está entre las opciones seleccionadas
                if (Array.isArray(fibrasSeleccionadas) && fibrasSeleccionadas.includes('Otra')) {
                    var nuevafibra = prompt('Por favor, ingresa la nueva fibra');

                    // Si el usuario ingresó una nueva fibra, enviarla al servidor
                    if (nuevafibra) {
                        $.ajax({
                            url: '/AgregarFibra',
                            type: 'POST',
                            data: {
                                nuevafibra: nuevafibra,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                // Agregar la nueva opción a la lista desplegable
                                $('#fibraSelect').append($('<option>', {
                                    text: nuevafibra,
                                    value: nuevafibra
                                }));
                                // Seleccionar la nueva opción
                                fibrasSeleccionadas.push(nuevafibra);
                                $('#fibraSelect').val(fibrasSeleccionadas);
                            },
                            error: function(error) {
                                console.error('Error al agregar nueva fibra: ', error);
                            }
                        });
                    }
                }
            });
        });
    </script>
    <script>
        var lastRegisteredId = 0;
        var addRowClicked = false;
        $(document).ready(function() {
            // Hacer la llamada Ajax al servidor para obtener datos
            $.ajax({
                url: '/viewTableIns', // Ruta de tu servidor Laravel
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    try {
                        // Iterar sobre los datos recibidos y agregar filas a la tabla
                        $.each(data, function(index, item) {
                            // Verificar si es una fila guardada o nueva
                            var isGuardado = item.Status === 'Nuevo' || item.Status ===
                                'Update';
                            var isFinalizado = item.Status === 'Finalizado';
                            var readonlyAttribute = isGuardado ? '' : 'readonly';
                            var disabledAttribute = isGuardado ? '' : 'disabled';
                            var hiddenAttribute = isFinalizado ? 'style="visibility: hidden;"' :
                                '';
                            // Crear celdas para Tipo_Problema y Ac_Correctiva como select2
                            var tipoProblemaCell = isFinalizado ? '' :
                                '<td style="white-space: nowrap;"><select class="form-control tipoProblemaSelect" name="tipoProblemaSelect[]" multiple ' +
                                readonlyAttribute + '"></select></td>';
                            var acCorrectivaCell = isFinalizado ? '' :
                                '<td style="white-space: nowrap;"><select class="form-control acCorrectivaSelect" name="acCorrectivaSelect[]" multiple ' +
                                readonlyAttribute + '"></select></td>';
                            // Crear la fila con las celdas modificadas
                            var row = '<tr>' +
                                '<td><input type="text" name="id" class="form-control" value="' +
                                item.id + '" readonly style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Auditor" class="form-control" value="' +
                                item.Auditor + '" readonly style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Cliente" class="form-control" value="' +
                                item.Cliente + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Estilo" class="form-control" value="' +
                                item.Estilo + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="OP_Defec" class="form-control" value="' +
                                item.OP_Defec + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tecnico" class="form-control" value="' +
                                item.Tecnico + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Color" class="form-control" value="' +
                                item.Color + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Num_Grafico" class="form-control" value="' +
                                item.Num_Grafico + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tipo_Maquina" class="form-control" value="' +
                                item.Tipo_Maquina + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="LeyendaSprint" class="form-control" value="' +
                                item.LeyendaSprint + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tecnica" class="form-control" value="' +
                                item.Tecnica + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Piezas_Auditar" class="form-control" value="' +
                                item.Piezas_Auditar + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Fibras" class="form-control" value="' +
                                item.Fibras + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Porcen_Fibra" class="form-control" value="' +
                                item.Porcen_Fibra + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Hora" class="form-control" value="' +
                                item.Hora + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Bulto" class="form-control" value="' +
                                item.Bulto + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Talla" class="form-control" value="' +
                                item.Talla + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tipo_ProblemF" class="form-control" value="' +
                                item.Tipo_Problema + '" ' +
                                'readonly style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Num_Problemas" class="form-control" value="' +
                                item.Num_Problemas + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Ac_CorrectivaF" class="form-control" value="' +
                                item.Ac_Correctiva + '" ' +
                                'readonly style="white-space: nowrap;"></td>' +
                                '<td><button type="button" class="btn btn-success guardarFila updateFile" ' +
                                disabledAttribute + ' ' + hiddenAttribute +
                                '>Guardar</button></td>' +
                                '</tr>';

                            // Agregar la fila a la tabla
                            $('#miTabla tbody').append(row);
                            lastRegisteredId = item.id;
                            afterRowsRendered();
                            if (!isFinalizado) {
                                $('.tipoProblemaSelect').select2({
                                    placeholder: 'Seleccione Tipo de Problema',
                                    allowClear: true,
                                });

                                $('.acCorrectivaSelect').select2({
                                    placeholder: 'Seleccione Acción Correctiva',
                                    allowClear: true,
                                });
                            }
                        });
                        // Cargar opciones para los nuevos select2
                        OpcionesTipoProblema('Seleccione Tipo de Problema');
                        OpcionesACCorrectiva('Seleccione Acción Correctiva');

                    } catch (error) {
                        console.error('Error al procesar los datos:', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la llamada Ajax:', status, error);
                }
            });
        });

        function OpcionesTipoProblema(placeholder) {
            $.ajax({
                url: '/OpcionesTipoProblema',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Agregar una opción vacía al principio con el placeholder como texto
                    data.unshift('Seleccione Tipo de Problema');
                    llenarSelect('tipoProblemaSelect', data);
                    // Establecer el valor en nulo después de cargar los datos
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener opciones de tipo_problemaR[]:', status, error);
                }
            });
        }
        // Función para cargar opciones de Acción Correctiva en un select2 específico
        function OpcionesACCorrectiva(placeholder) {
            $.ajax({
                url: '/OpcionesACCorrectiva',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Agregar una opción vacía al principio con el placeholder como texto
                    data.unshift('Seleccione Acción Correctiva');
                    llenarSelect('acCorrectivaSelect', data);
                    // Establecer el valor en nulo después de cargar los datos
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener opciones de ac_correctivaR[]:', status, error);
                }
            });
        }
        // Función para llenar un select2 con opciones
        function llenarSelect(nombreSelect, opciones, placeholder) {
            var select = $('.form-control[name="' + nombreSelect + '"]');
            select.empty();

            // Agregar una opción vacía al principio con el placeholder como texto
            select.append('<option value="" selected disabled>' + placeholder + '</option>');

            select.select2({
                placeholder: placeholder,
                allowClear: true,
                multiple: true,
            });
            opciones.forEach(function(opcion) {
                select.append('<option value="' + opcion + '">' + opcion + '</option>');
            });
            select.val(null).trigger('change'); // Esto asegura que la opción vacía esté seleccionada
        }
    </script>
    <script>
        var addRowClicked = false;

        function cargarOpcionesACCorrectiva() {
            $.ajax({
                url: '/obtenerOpcionesACCorrectiva', // Ajusta la ruta según tu configuración
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    llenarSelect('ac_correctivaR[]', data, 'Seleccione Acción Correctiva');
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener opciones de ac_correctivaR[]:', status, error);
                }
            });
        }

        function cargarOpcionesTipoProblema() {
            $.ajax({
                url: '/obtenerOpcionesTipoProblema', // Ajusta la ruta según tu configuración
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    llenarSelect('tipo_problemaR[]', data, 'Seleccione Tipo de Problema');
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener opciones de tipo_problemaR[]:', status, error);
                }
            });
        }

        function llenarSelect(nombreSelect, opciones) {
            var select = $('.form-control[name="' + nombreSelect + '"]');
            select.empty();
            select.select2({
                placeholder: 'Seleccione una opcion',
                allowClear: true,
                multiple: true,
            });
            opciones.forEach(function(opcion) {
                select.append('<option value="' + opcion + '">' + opcion + '</option>');
            });
            select.select2();
        }
        $('#insertarFila').on('click', function() {
            console.log('Se hizo clic en el botón "Añadir"');
            addRowClicked = true;

            // Verificar si todos los campos están llenos
            var camposVacios = false;
            $('select, input').each(function() {
                if ($(this).val() === "" && !(($(this).attr('id') === 'otraTipoMaquina') && ($(
                        '#inputTipoMaquina').val() !== 'Otra'))) {
                    camposVacios = true;
                    return false; // Salir del bucle si se encuentra un campo vacío
                }
            });

            if (camposVacios) {
                alert('Por favor, complete todos los campos antes de añadir una nueva fila.');
                return; // Detener la ejecución si hay campos vacíos
            }

            lastRegisteredId++;

            var auditor = '{{ Auth::user()->name }}';
            var cliente = $('#clienteSelect').val();
            var estilo = $('#estiloSelect').val();
            var op = $('#ordenSelect').val();
            var tecnico = $('#tecnicosSelect').val();
            var color = $('#inputColor').val();
            var numGrafico = $('#inputGrafico').val();
            var tipoMaquinaValue = $('#inputTipoMaquina').val();
            var tipoMaquinaText = $('#inputTipoMaquina option:selected').text();
            var leyendasprint = $('#inputLeyendaSprint').val();
            var tecnica = $('#tecnicaSelect').val();
            var piezasAuditar = $('#inputpiezasxbulto').val();
            var fibras = $('#fibraSelect').val();
            var tipoProblema = $('#tipoProblemaSelect').val();
            var acCorrectiva = $('#acCorrectivaSelect').val();

            // Verificar si el valor de Tipo de Maquina es "Otra"
            if (tipoMaquinaValue === 'Otra') {
                tipoMaquinaValue = $('#otraTipoMaquina').val(); // Obtener el valor del campo de entrada "Otra"
            }

            var porcentajes = [];
            $('.porcentajeInput').each(function() {
                porcentajes.push($(this).val());
            });

            var newRow = '<tr>' +
                '<td><input type="hidden" name="idR[]" value="' + lastRegisteredId + '"></td>' +
                '<td><input type="text" name="auditorR[]" class="form-control" value="' + auditor +
                '" readonly style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="clienteR[]" class="form-control" value="' + cliente +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="estiloR[]" class="form-control" value="' + estilo +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="op_defecR[]" class="form-control" value="' + op +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tecnicoR[]" class="form-control" value="' + tecnico +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="colorR[]" class="form-control" value="' + color +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="num_graficoR[]" class="form-control" value="' +
                numGrafico + '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tipo_maquinaR[]" class="form-control" value="' +
                tipoMaquinaValue + '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="leyendasprintR[]" class="form-control" value="' +
                leyendasprint + '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tecnicaR[]" class="form-control" value="' + tecnica +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="piezas_auditarR[]" class="form-control" value="' + piezasAuditar +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="fibrasR[]" class="form-control" value="' + fibras.join(', ') +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="porcentaje_fibraR[]" class="form-control" value="' +
                porcentajes.join(', ') + '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="horaR[]" class="form-control" value="' +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="bultoR[]" class="form-control" value="' +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tallaR[]" class="form-control" value="' +
                '" style="white-space: nowrap;"></td>' +
                '<td><select class="form-control" name="tipo_problemaR[]" style="white-space: nowrap;"></select></td>' +
                '<td id="problemasContainer' + lastRegisteredId + '"></td>' + // Contenedor para los inputs
                '<td><select class="form-control" name="ac_correctivaR[]" style="white-space: nowrap;"></select></td>' +
                '<td><button type="button" class="btn btn-success guardarFila updateFile" style="white-space: nowrap;">Guardar</button></td>' +
                '<td><button type="button" class="btn btn-danger descartar" style="white-space: nowrap;" onclick="descartarClicked()">Descartar <i class="material-icons">delete</i></button></td>' +
                '</tr>';

            $('#miTabla tbody').append(newRow);
            afterRowAdded();
            // Cargar opciones de los nuevos select
            cargarOpcionesACCorrectiva();
            cargarOpcionesTipoProblema();
            $('select[name="tipo_problemaR[]"]').last().on('change', function() {
                generarInputsProblemas(this, lastRegisteredId);
            });
        });

        $(document).ready(function() {
            cargarOpcionesACCorrectiva();
            cargarOpcionesTipoProblema();
        });
        function generarInputsProblemas(selectElement, rowId) {
            var selectedOptions = $(selectElement).val();
            var container = $('#problemasContainer' + rowId);
            container.empty();

            selectedOptions.forEach(function(option) {
                var input = $('<input>', {
                    type: 'number',
                    class: 'form-control',
                    name: '#_problemasR[]',
                    placeholder: '# problemas de ' + option,
                    style: 'white-space: nowrap; width: 150px;'
                });

                // Establecer valor por defecto y readonly si la opción es "N/A"
                if (option === 'N/A') {
                    input.val(0);
                    input.prop('readonly', true);
                }

                container.append(input);
            });
        }
        // Evento de clic en el botón "Guardar"
        $(document).on('click', '.guardarFila', function() {
            // Obtener el token CSRF
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Verificar si se hizo clic en el botón "AddRow"
            if (addRowClicked) {
                // Obtener los valores de la fila desde los campos de entrada
                var auditorValue = $(this).closest('tr').find('[name="auditorR[]"]').val();
                var clienteValue = $(this).closest('tr').find('[name="clienteR[]"]').val();
                var estiloValue = $(this).closest('tr').find('[name="estiloR[]"]').val();
                var opDefecValue = $(this).closest('tr').find('[name="op_defecR[]"]').val();
                var tecnicoValue = $(this).closest('tr').find('[name="tecnicoR[]"]').val();
                var colorValue = $(this).closest('tr').find('[name="colorR[]"]').val();
                var numGraficoValue = $(this).closest('tr').find('[name="num_graficoR[]"]').val();
                var tipoMaquinaValue = $(this).closest('tr').find('[name="tipo_maquinaR[]"]').val();
                var leyendaSprintValue = $(this).closest('tr').find('[name="leyendasprintR[]"]').val();
                var tecnicaValue = $(this).closest('tr').find('[name="tecnicaR[]"]').val();
                var piezasAuditarValue = $(this).closest('tr').find('[name="piezas_auditarR[]"]').val();
                var fibrasValue = $(this).closest('tr').find('[name="fibrasR[]"]').val();
                var porcentajeFibraValue = $(this).closest('tr').find('[name="porcentaje_fibraR[]"]').val();
                var horaValue = $(this).closest('tr').find('[name="horaR[]"]').val();
                var bultoValue = $(this).closest('tr').find('[name="bultoR[]"]').val();
                var tallaValue = $(this).closest('tr').find('[name="tallaR[]"]').val();
                var tipoProblemaValue = $(this).closest('tr').find('[name="tipo_problemaR[]"]').val();
                var numProblemas = [];
                $(this).closest('tr').find('input[name="num_problemasR[]"]').each(function() {
                    var valor = $(this).val();
                    numProblemas.push(valor === "" ? 0 :
                    valor); // Envía 0 si está vacío, si no, envía el valor
                });
                var acCorrectivaValue = $(this).closest('tr').find('[name="ac_correctivaR[]"]').val();
                $.ajax({
                    url: '/SendInspeccionEstampadoHornot',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        addRowClicked: addRowClicked,
                        Auditor: auditorValue,
                        Cliente: clienteValue,
                        Estilo: estiloValue,
                        OP_Defec: opDefecValue,
                        Tecnico: tecnicoValue,
                        Color: colorValue,
                        Num_Grafico: numGraficoValue,
                        Tipo_Maquina: tipoMaquinaValue,
                        LeyendaSPrint: leyendaSprintValue,
                        Tecnica: tecnicaValue,
                        Piezas_Auditar: piezasAuditarValue,
                        Fibras: fibrasValue,
                        Porcen_Fibra: porcentajeFibraValue,
                        Hora: horaValue,
                        Bulto: bultoValue,
                        Talla: tallaValue,
                        Tipo_Problema: tipoProblemaValue,
                        Num_Problemas: numProblemas,
                        Ac_Correctiva: acCorrectivaValue
                    },
                    success: function(response) {
                        // Realizar acciones adicionales si es necesario después de la respuesta exitosa
                        console.log(response);
                    },
                    error: function(error) {
                        // Manejar errores si es necesario
                        console.log('Error en la solicitud POST:', error);
                    }

                });
            }
        });
    </script>
    <script>
        var lastRegisteredId = 0;
        // Evento de clic en el botón "Guardar"
        $(document).on('click', '.updateFile', function() {
            // Obtener el token CSRF
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Obtener los valores de la fila desde los campos de entrada
            var row = $(this).closest('tr');
            var idValue = row.find('input[name="id"]')
                .val(); // Asegúrate de obtener el valor correcto del campo "id"
            var addRowClicked = row.find('input[name="id"]').length === 0; // Verifica si es una fila agregada
            // Obtener otros valores de la fila
            var auditorValue = row.find('input[name="Auditor"]').val();
            var clienteValue = row.find('input[name="Cliente"]').val();
            var estiloValue = row.find('input[name="Estilo"]').val();
            var opDefecValue = row.find('input[name="OP_Defec"]').val();
            var tecnicoValue = row.find('input[name="Tecnico"]').val();
            var colorValue = row.find('input[name="Color"]').val();
            var numGraficoValue = row.find('input[name="Num_Grafico"]').val();
            var tipo_maquina = row.find('input[name="Tipo_Maquina"]').val();
            var leyendasprint = row.find('input[name="LeyendaSprint"]').val();
            var tecnicaValue = row.find('input[name="Tecnica"]').val();
            var fibrasValue = row.find('input[name="Fibras"]').val();
            var porcentajeFibraValue = row.find('input[name="Porcen_Fibra"]').val();
            var hora = row.find('input[name="Hora"]').val();
            var bulto = row.find('input[name="Bulto"]').val();
            var talla = row.find('input[name="Talla"]').val();
            // Obtener valores de los elementos select
            var piezasAuditarValue = $(this).closest('tr').find('[name="Piezas_Auditar"]').val();
            // Obtener valores de los elementos select
            var tipoProblemaValue = $(this).closest('tr').find('[name="Tipo_Problemas"]').val();
            var numProblemasValue = $(this).closest('tr').find('[name="Num_Problemas"]').val();
            var acCorrectivaValue = row.find('input[name="Ac_Correctiva"]').val();

            // Continuar con la solicitud AJAX
            $.ajax({
                url: '/UpdateIsnpec/' + idValue,
                method: 'PUT',
                data: {
                    _token: csrfToken,
                    addRowClicked: false,
                    id: idValue,
                    Auditor: auditorValue,
                    Cliente: clienteValue,
                    Estilo: estiloValue,
                    OP_Defec: opDefecValue,
                    Tecnico: tecnicoValue,
                    Color: colorValue,
                    Num_Grafico: numGraficoValue,
                    Tipo_Maquina: tipo_maquina,
                    LeyendaSprint: leyendasprint,
                    Tecnica: tecnicaValue,
                    Piezas_Auditar: piezasAuditarValue,
                    Fibras: fibrasValue,
                    Porcen_Fibra: porcentajeFibraValue,
                    Hora: hora,
                    Bulto: bulto,
                    Talla: talla,
                    Tipo_Problema: tipoProblemaValue,
                    Num_Problemas: numProblemasValue,
                    Ac_Correctiva: acCorrectivaValue
                },
                success: function(response) {
                    // Realizar acciones adicionales si es necesario después de la respuesta exitosa
                    console.log(response);
                },
                error: function(error) {
                    // Manejar errores si es necesario
                    console.log(addRowClicked ? 'Error en la solicitud POST:' :
                        'Error en la solicitud PUT:', error);
                },
                complete: function() {
                    // Recargar la página después de completar la solicitud
                    location.reload();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#Finalizar').click(function() {
                // Iterar sobre cada fila de la tabla
                $('#miTabla tbody tr').each(function() {
                    var id = $(this).find('input[name="id"]').val();
                    // Hacer una solicitud POST para cada fila para actualizar el estado a "Finalizado"
                    $.ajax({
                        url: '/actualizarEstado/' + id, // Ruta de tu servidor Laravel
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}', // Añade el token CSRF aquí
                            status: 'Finalizado'
                        },
                        success: function(response) {
                            console.log(
                                'Estado actualizado con éxito para la fila con id ' +
                                id + ':', response);
                        },
                        error: function(xhr, status, error) {
                            console.error(
                                'Error al actualizar el estado para la fila con id ' +
                                id + ':', status, error);
                        },
                        complete: function() {
                            // Recargar la página después de completar la solicitud
                            location.reload();
                        }
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dtHorizontalVerticalExample').DataTable({
                "scrollX": true,
                "scrollY": 200,
            });
            $('.dataTables_length').addClass('bs-select');
        });
    </script>
    <script>
        $(document).ready(function() {
            // Hacer la llamada Ajax al servidor para obtener datos
            $.ajax({
                url: '/PorcenTotalDefec', // Ruta de tu servidor Laravel
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    try {
                        $('#granTotalRevisando').text(response.totalRegistros);
                        $('#granTotalDefectos').text(response.totalDefectos);
                        $('#porcentajeDefectos').text(response.porcentaje.toFixed(2) + '%');
                    } catch (error) {
                        console.error('Error al procesar los datos:', error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la llamada Ajax:', status, error);
                }
            });
        });
    </script>
    <script>
        function initializeFlatpickr() {
            flatpickr('input[name="horaR[]"]', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true,
                defaultDate: "12:00",
            });
        }

        // Llama a esta función después de añadir una nueva fila
        function afterRowAdded() {
            initializeFlatpickr();
        }

        $(document).ready(function() {
            initializeFlatpickr();
        });
    </script>
    <script>
        function initializeFlatpickrForHour() {
            var horaInputs = $('input[name="Hora"]');
            horaInputs.each(function() {
                if (!$(this).prop('readonly')) {
                    flatpickr(this, {
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true,
                    });
                }
            });
        }

        function afterRowsRendered() {
            initializeFlatpickrForHour();
        }

        $(document).ready(function() {
            afterRowsRendered();
        });
    </script>
    <script>
        $(document).ready(function() {
            // Evento de cambio en el selector
            $('#inputTipoMaquina').change(function() {
                // Obtener el valor seleccionado
                var selectedValue = $(this).val();

                // Mostrar u ocultar el campo de entrada "Otra" según la selección
                if (selectedValue === 'Otra') {
                    $('#otraTipoMaquina').show();
                } else {
                    $('#otraTipoMaquina').hide();
                }
            });
        });
    </script>
    <script>
        function descartarClicked() {
            location.reload();
        }
    </script>
@endsection
