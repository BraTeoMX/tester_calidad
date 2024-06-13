@extends('layouts.app', ['pageSlug' => 'Maquila', 'titlePage' => __('Maquila')])

@section('content')
<link rel="stylesheet" href="black/css/styleScreenPrint.css">
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
                            <h3 class="card-title">{{ __('Maquila.') }}</h3>
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
                            <label for="inputDescripcion">Ingresa Descripcion:</label>
                            <input class="form-control" id="inputDescripcion" name="inputDescripcion" required>
                        </div>
                        <div class="col-md-2">
                            <label for="ordenSelect">Ingresa la OP:</label>
                            <input class="form-control" id="ordenSelect" name="ordenSelect" required>
                        </div>
                        <div class="col-md-2">
                            <label for="clienteSelect">Ingresa el Cliente:</label>
                            <input class="form-control" id="clienteSelect" name="clienteSelect" required>
                        </div>
                        <div class="col-md-2">
                            <label for="estiloSelect">Ingresa el Estilo:</label>
                            <input class="form-control" id="estiloSelect" name="estiloSelect" required>
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
                            <label for="tecnicosSelect">Seleccion del tecnico:</label>
                            <select class="form-control" id="tecnicosSelect" name="tecnicosSelect" required>
                                <!-- Las opciones se cargarán dinámicamente aquí -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="inputCorte">Ingresa Corte:</label>
                            <input class="form-control" id="inputCorte" name="inputCorte" required>
                        </div>
                        <div class="col-md-2">
                            <label for="inputColor">Ingresa color:</label>
                            <input class="form-control" id="inputColor" name="inputColor" required>
                        </div>
                        <div class="col-md-2">
                            <form class="form-inline">
                                <label class="my-1 mr-2" for="inputTalla" name="inputTalla">Tipo de Talla</label>
                                <select class="custom-select my-1 mr-sm-2" id="inputTalla" name="inputTalla">
                                    <option selected>Seleccion de talla</option>
                                    <option value="S">S</option>
                                    <option value="M">M</option>
                                    <option value="L">L</option>
                                    <option value="XL">XL</option>
                                    <option value="XXL">XXL</option>
                                </select>
                            </form>
                        </div>
                        <div class="col-md-2">
                            <label for="inputpiezasxbulto">Ingresa piezas a auditar:</label>
                            <input type="number" class="form-control" id="inputpiezasxbulto" name="inputpiezasxbulto"
                                required>
                        </div>

                    </div>
                    <br>
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
                                <h3 class="card-title">{{ __('Inspección Maquila.') }}</h3>
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
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="tablaDinamicaMaquila">
                                <thead class="text-primary">
                                    <tr>
                                        <th>
                                            ID</th>
                                        <th>
                                            Auditor</th>
                                        <th>
                                            Descripción</th>
                                        <th>
                                            Cliente</th>
                                        <th>
                                            Estilo</th>
                                        <th>
                                            OP</th>
                                        <th>
                                            Maquina</th>
                                        <th>
                                            Tecnico</th>
                                        <th>
                                            Corte</th>
                                        <th>
                                            Color</th>
                                        <th>
                                            Talla</th>
                                        <th>
                                            Piezas a auditar</th>
                                        <th>
                                            Tipo Defectos</th>
                                        <th>
                                            # Defectos</th>
                                        <th>
                                            Acciones Correctivas</th>
                                        <th>
                                        </th>
                                        <th>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-danger" id="Finalizar">
                                <span>Finalizar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Inicializar Select2 para la orden
            $('#tecnicosSelect').select2({
                placeholder: 'Seleccione un tecnico',
                allowClear: true
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
        });
    </script>
    <script>
        var lastRegisteredId = 0;
        var addRowClicked = false;
        $(document).ready(function() {
            // Hacer la llamada Ajax al servidor para obtener datos
            $.ajax({
                url: '/viewTableMaquila', // Ruta de tu servidor Laravel
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
                                '<td><input type="text" name="Descripcion" class="form-control" value="' +
                                item.Descripcion + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Cliente" class="form-control" value="' +
                                item.Cliente + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Estilo" class="form-control" value="' +
                                item.Estilo + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="OP_Defec" class="form-control" value="' +
                                item.OP_Defec + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Maquina" class="form-control" value="' +
                                item.Maquina + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tecnico" class="form-control" value="' +
                                item.Tecnico + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Corte" class="form-control" value="' +
                                item.Corte + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Color" class="form-control" value="' +
                                item.Color + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Talla" class="form-control" value="' +
                                item.Talla + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Piezas_Auditar" class="form-control" value="' +
                                item.Piezas_Auditar + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Tipo_Problemas" class="form-control" value="' +
                                item.Tipo_Problema + '" ' +
                                'readonly style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Num_Problemas" class="form-control" value="' +
                                item.Num_Problemas + '" ' + readonlyAttribute +
                                ' style="white-space: nowrap;"></td>' +
                                '<td><input type="text" name="Ac_Correctiva" class="form-control" value="' +
                                item.Ac_Correctiva + '" ' +
                                'readonly style="white-space: nowrap;"></td>' +
                                '<td><button type="button" class="btn btn-success guardarFila updateFile" ' +
                                disabledAttribute + ' ' + hiddenAttribute +
                                '>Guardar</button></td>' +
                                '</tr>';

                            // Agregar la fila a la tabla
                            $('#tablaDinamicaMaquila tbody').append(row);
                            lastRegisteredId = item.id;

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
                multiple: true
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
                multiple: true
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
                        '#inputMaquina').val() !== 'Otra'))) {
                    camposVacios = true;
                    return false; // Salir del bucle si se encuentra un campo vacío
                }
            });
            lastRegisteredId++;

            var auditor = '{{ Auth::user()->name }}';
            var descripcion = $('#inputDescripcion').val();
            var cliente = $('#clienteSelect').val();
            var estilo = $('#estiloSelect').val();
            var op = $('#ordenSelect').val();
            var maquina = $('#inputTipoMaquina').val();
            var tecnico = $('#tecnicosSelect').val();
            var corte = $('#inputCorte').val();
            var color = $('#inputColor').val();
            var talla = $('#inputTalla').val();
            var piezasAuditar = $('#inputpiezasxbulto').val();
            var tipoProblema = $('#tipoProblemaSelect').val();
            var acCorrectiva = $('#acCorrectivaSelect').val();

            var newRow = '<tr>' +
                '<td><input type="hidden" name="idR[]" value="' + lastRegisteredId + '"></td>' +
                '<td><input type="text" name="auditorR[]" class="form-control" value="' + auditor +
                '" readonly style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="descripcionR[]" class="form-control" value="' + descripcion +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="clienteR[]" class="form-control" value="' + cliente +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="estiloR[]" class="form-control" value="' + estilo +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="op_defecR[]" class="form-control" value="' + op +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="maquinaR[]" class="form-control" value="' + maquina +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tecnicoR[]" class="form-control" value="' + tecnico +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="corteR[]" class="form-control" value="' + corte +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="colorR[]" class="form-control" value="' + color +
                '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="tallaR[]" class="form-control" value="' +
                talla + '" style="white-space: nowrap;"></td>' +
                '<td><input type="text" name="piezas_auditarR[]" class="form-control" value="' + piezasAuditar +
                '" style="white-space: nowrap;"></td>' +
                '<td><select class="form-control" name="tipo_problemaR[]" style="white-space: nowrap;"></select></td>' +
                '<td id="problemasContainer' + lastRegisteredId + '"></td>' + // Contenedor para los inputs
                '<td><select class="form-control" name="ac_correctivaR[]" style="white-space: nowrap;"></select></td>' +
                '<td><button type="button" class="btn btn-success guardarFila updateFile" style="white-space: nowrap;">Guardar</button></td>' +
                '<td><button type="button" class="btn btn-danger descartar" style="white-space: nowrap;" onclick="descartarClicked()">Descartar <i class="material-icons">delete</i></button></td>' +
                '</tr>';

            $('#tablaDinamicaMaquila tbody').append(newRow);

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
                    name: 'num_problemasR[]',
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
                var descripcionValue = $(this).closest('tr').find('[name="descripcionR[]"]').val();
                var clienteValue = $(this).closest('tr').find('[name="clienteR[]"]').val();
                var estiloValue = $(this).closest('tr').find('[name="estiloR[]"]').val();
                var opDefecValue = $(this).closest('tr').find('[name="op_defecR[]"]').val();
                var maquinaValue = $(this).closest('tr').find('[name="maquinaR[]"]').val();
                var tecnicoValue = $(this).closest('tr').find('[name="tecnicoR[]"]').val();
                var corteValue = $(this).closest('tr').find('[name="corteR[]"]').val();
                var colorValue = $(this).closest('tr').find('[name="colorR[]"]').val();
                var tallaValue = $(this).closest('tr').find('[name="tallaR[]"]').val();
                var piezasAuditarValue = $(this).closest('tr').find('[name="piezas_auditarR[]"]').val();
                var tipoProblemaValue = $(this).closest('tr').find('[name="tipo_problemaR[]"]').val();
                var acCorrectivaValue = $(this).closest('tr').find('[name="ac_correctivaR[]"]').val();
                var numProblemas = [];
                $(this).closest('tr').find('input[name="num_problemasR[]"]').each(function() {
                    var valor = $(this).val();
                    numProblemas.push(valor === "" ? 0 :
                    valor); // Envía 0 si está vacío, si no, envía el valor
                });
                $.ajax({
                    url: '/SendMaquila',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        addRowClicked: addRowClicked,
                        Auditor: auditorValue,
                        Descripcion: descripcionValue,
                        Cliente: clienteValue,
                        Estilo: estiloValue,
                        OP_Defec: opDefecValue,
                        Maquina: maquinaValue,
                        Tecnico: tecnicoValue,
                        Corte: corteValue,
                        Color: colorValue,
                        Talla: tallaValue,
                        Piezas_Auditar: piezasAuditarValue,
                        Tipo_Problema: tipoProblemaValue,
                        Num_Problemas: numProblemas,
                        Ac_Correctiva: acCorrectivaValue,
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
            var descripcionValue = row.find('input[name="Descripcion"]').val();
            var clienteValue = row.find('input[name="Cliente"]').val();
            var estiloValue = row.find('input[name="Estilo"]').val();
            var opDefecValue = row.find('input[name="OP_Defec"]').val();
            var maquinaValue = row.find('select[name="Maquina"]').val();
            var tecnicoValue = row.find('input[name="Tecnico"]').val();
            var corteValue = row.find('input[name="Corte"]').val();
            var colorValue = row.find('input[name="Color"]').val();
            var tallaValue = row.find('input[name="Talla"]').val();
            var piezasAuditarValue = $(this).closest('tr').find('[name="Piezas_Auditar"]').val();
            // Obtener valores de los elementos select
            var tipoProblemaValue = $(this).closest('tr').find('[name="Tipo_Problemas"]').val();
            var numProblemasValue = $(this).closest('tr').find('[name="Num_Problemas"]').val();
            var acCorrectivaValue = row.find('input[name="Ac_Correctiva"]').val();
            // Continuar con la solicitud AJAX
            $.ajax({
                url: '/UpdateMaquila/' + idValue,
                method: 'PUT',
                data: {
                    _token: csrfToken,
                    addRowClicked: false,
                    id: idValue,
                    Auditor: auditorValue,
                    Descripcion: descripcionValue,
                    Cliente: clienteValue,
                    Estilo: estiloValue,
                    OP_Defec: opDefecValue,
                    Maquina: maquinaValue,
                    Tecnico: tecnicoValue,
                    Corte: corteValue,
                    Color: colorValue,
                    Talla: tallaValue,
                    Piezas_Auditar: piezasAuditarValue,
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
                $('#tablaDinamicaMaquila tbody tr').each(function() {
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
                url: '/PorcenTotalDefecMaquila', // Ruta de tu servidor Laravel
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
@endsection
