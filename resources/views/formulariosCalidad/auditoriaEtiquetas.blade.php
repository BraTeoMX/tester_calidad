@extends('layouts.app', ['pageSlug' => 'Etiquetas', 'titlePage' => __('Etiquetas')])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">{{ __('Auditoria Etiquetas.') }}</h3>
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
                            <br>
                        </div>
                        <div class="col-md-3">
                            <label for="tipoBusqueda">Tipo de búsqueda:</label>
                            <select class="form-control" id="tipoBusqueda" name="tipoBusqueda">
                                <option selected>Selecciona un tipo de busqueda</option>
                                <option value="OC">OC</option>
                                <option value="OP">OP</option>
                                <option value="PO">PO</option>
                                <option value="OV">OV</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ordenInput">Escribe tu Orden:</label>
                            <input type="text" class="form-control" id="ordenInput" name="ordenInput" required>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success" id="Buscar" name ="Buscar">
                                Buscar
                            </button>
                        </div>
                        <div class="cl-toggle-switch">
                            <label class="cl-switch">
                                <input type="checkbox" id="consultarDataBtn">
                                <span>Consultar Data extra</span>
                            </label>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="card col-md-20" style="width: auto;">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="card-title" style="font-size: 24px;">{{ __('Auditoría.') }}</h3>
                        </div>
                    </div>
                </div>
                <div id="accordion" name="accordion" style="margin-top: 1px;">
                    <!-- Los acordeones se generarán dinámicamente aquí -->
                </div>
                <div id="accordionnew" name="accordionnew" style="margin-top: 1px;">
                    <!-- Los acordeones se generarán dinámicamente aquí -->
                </div>
            </div>
        </div>
        <div class="loader" id="loader" style="display:none;">
            <span class="loader-text">Cargando</span>
            <span class="load"></span>
        </div>
    </div>
    <style>
        .loader {
            width: 80px;
            height: 50px;
            position: relative;
        }

        .loader-text {
            position: absolute;
            top: 0;
            padding: 0;
            margin: 0;
            color: #C8B6FF;
            animation: text_713 3.5s ease both infinite;
            font-size: .8rem;
            letter-spacing: 1px;
        }

        .load {
            background-color: #9A79FF;
            border-radius: 50px;
            display: block;
            height: 16px;
            width: 16px;
            bottom: 0;
            position: absolute;
            transform: translateX(64px);
            animation: loading_713 3.5s ease both infinite;
        }

        .load::before {
            position: absolute;
            content: "";
            width: 100%;
            height: 100%;
            background-color: #D1C2FF;
            border-radius: inherit;
            animation: loading2_713 3.5s ease both infinite;
        }

        @keyframes text_713 {
            0% {
                letter-spacing: 1px;
                transform: translateX(0px);
            }

            40% {
                letter-spacing: 2px;
                transform: translateX(26px);
            }

            80% {
                letter-spacing: 1px;
                transform: translateX(32px);
            }

            90% {
                letter-spacing: 2px;
                transform: translateX(0px);
            }

            100% {
                letter-spacing: 1px;
                transform: translateX(0px);
            }
        }

        @keyframes loading_713 {
            0% {
                width: 16px;
                transform: translateX(0px);
            }

            40% {
                width: 100%;
                transform: translateX(0px);
            }

            80% {
                width: 16px;
                transform: translateX(64px);
            }

            90% {
                width: 100%;
                transform: translateX(0px);
            }

            100% {
                width: 16px;
                transform: translateX(0px);
            }
        }

        @keyframes loading2_713 {
            0% {
                transform: translateX(0px);
                width: 16px;
            }

            40% {
                transform: translateX(0%);
                width: 80%;
            }

            80% {
                width: 100%;
                transform: translateX(0px);
            }

            90% {
                width: 80%;
                transform: translateX(15px);
            }

            100% {
                transform: translateX(0px);
                width: 16px;
            }
        }
    </style>
    <script>
        $(document).ready(function() {
            // Cachear selectores
            var $ordenInput = $('#ordenInput');
            var $tipoBusqueda = $('#tipoBusqueda');
            var $accordion = $('#accordion');
            var $loader = $('#loader'); // Loader

            // Manejar el clic en el botón de buscar
            $('#Buscar').click(function() {
                var orden = $ordenInput.val();
                var tipoBusqueda = $tipoBusqueda.val();

                if (!orden || !tipoBusqueda) {
                    alert('Por favor, seleccione una orden y un tipo de búsqueda.');
                    return;
                }
            // Limpiar el contenido del acordeón antes de mostrar el loader
            $accordion.empty();
                // Mostrar el loader antes de iniciar la solicitud AJAX
                $loader.show();
                $('#loader').css({
            "display": "block",
            "position": "fixed",
            "top": "50%",
            "left": "50%",
            "transform": "translate(-50%, -50%)",
            "z-index": "9999"
        });
                $.ajax({
                    url: '/buscarEstilos', // Cambia esto a la ruta correcta en tu aplicación
                    type: 'GET',
                    data: {
                        orden: orden,
                        tipoBusqueda: tipoBusqueda,
                        _token: '{{ csrf_token() }}' // Token CSRF para la seguridad
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Limpiar el contenido del acordeón
                        $accordion.empty();

                        // Generar dinámicamente los acordeones
                        $.each(response.estilos, function(index, estilo) {
                            var estado = response.status[index];

                            // Crear el HTML para el acordeón
                            var acordeonHtml = `
                            <div class="card">
                                <div class="card-header" id="heading${index}" style="background-color: rgb(170, 42, 176) !important; text-align: center;">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" style="background-color: rgb(170, 42, 176) !important; color: white; font-size: 17px; !important;" data-toggle="collapse" data-target="#collapse${index}" aria-expanded="true" aria-controls="collapse${index}">
                                            Estilo: ${estilo.Estilos}
                                            <br>
                                            Estado: ${estado}
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse${index}" class="collapse" aria-labelledby="heading${index}" data-parent="#accordion">
                                    <div class="card-body">
                                        <!-- Contenido de la auditoría para el estilo -->
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="miTabla">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 0.5%;"></th>
                                                        <th style="text-align: center; width: 1%">Color</th>
                                                        <th style="text-align: center; width: 1%">Talla</th>
                                                        <th style="text-align: center; width: 1%;">Cantidad</th>
                                                        <th style="text-align: center; width: 1%;">Tamaño<br>Muestra</th>
                                                        <th style="text-align: center; width: 25%;">Tipos<br>Defectos</th>
                                                        <th style="text-align: center; width: 10%;"># Defectos</th>
                                                        <th style="text-align: center; width: auto;">Acciones<br>Correctivas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Las filas del cuerpo (tbody) se generarán dinámicamente aquí -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                            // Agregar el acordeón al contenedor
                            $accordion.append(acordeonHtml);
                        });
                    },
                    error: function(error) {
                        console.error('Error al buscar estilos: ', error);
                    },
                    complete: function() {
                        // Ocultar el loader después de que los datos se hayan cargado
                        $loader.hide();
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Función para generar un ID único
            function generateUniqueId(prefix) {
                return prefix + '_' + Math.random().toString(36).substr(2, 9);
            }

            $('#accordion').on('shown.bs.collapse', function(event) { // Cambiado a 'shown.bs.collapse'
                let target = $(event.target);
                let estiloText = $(event.target).closest('.card').find('.btn-link').text();
                let estilo = estiloText.match(/Estilo:\s*([^\s]+)/)[1].trim();
                let orden = $('#ordenInput').val();
                let tipoBusqueda = $('#tipoBusqueda').val();

                $.ajax({
                    url: '/buscarDatosAuditoriaPorEstilo',
                    type: 'GET',
                    data: {
                        estilo: estilo,
                        orden: orden,
                        tipoBusqueda: tipoBusqueda
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#miTabla tbody').empty();

                        $.each(data, function(index, item) {
                            var campos = {
                                OC: {
                                    Orden: 'OrdenCompra',
                                    Color: 'Color',
                                    Talla: 'Talla',
                                    Cantidad: 'Cantidad'
                                },
                                OP: {
                                    Orden: 'op',
                                    Color: 'inventcolorid',
                                    Talla: 'sizename',
                                    Cantidad: 'qty'
                                },
                                PO: {
                                    Orden: 'cpo',
                                    Color: 'inventcolorid',
                                    Talla: 'sizename',
                                    Cantidad: 'qty'
                                },
                                OV: {
                                    Orden: 'salesid',
                                    Color: 'inventcolorid',
                                    Talla: 'sizename',
                                    Cantidad: 'qty'
                                },
                            } [tipoBusqueda];

                            var uniqueId = generateUniqueId('input');

                            // Formatear la cantidad
                            var cantidadFormateada = item[campos.Cantidad];
                            if (typeof cantidadFormateada === 'string') {
                                var puntoIndex = cantidadFormateada.indexOf('.');
                                if (puntoIndex !== -1) {
                                    var parteDecimal = cantidadFormateada.substring(
                                        puntoIndex + 1);
                                    if (parteDecimal.length > 2) {
                                        parteDecimal = parteDecimal.substring(0, 2);
                                    }
                                    cantidadFormateada = cantidadFormateada.substring(0,
                                        puntoIndex + 1) + parteDecimal;
                                }
                            }

                            // Agregar fila a la tabla con IDs únicos
                            var fila = `<tr>
                                        <td style="text-align: center;">${(index + 1)} </td>
                                        <td style="text-align: center;">${item[campos.Color] || 'N/A'}</td>
                                        <td style="text-align: center;">${item[campos.Talla] || 'N/A'}</td>
                                        <td style="text-align: center;">${cantidadFormateada}</td>
                                        <td style="text-align: center;"><span class="tamañoMuestra">${item.tamaño_muestra ? item.tamaño_muestra : 'N/A'}</span></td>
                                        <td style="text-align: center;">
                                            <select class="form-control tipoProblemasSelect" id="tipoProblemas_${uniqueId}" multiple="multiple"></select>
                                        </td>
                                        <td class="cantidadContainer" style="text-align: center;">
                                            <!-- Los inputs de cantidad se generarán dinámicamente aquí -->
                                        </td>
                                        <td class="select-container" style="text-align: center;">
                                            <div class="btn-group dropleft">
                                                <button id="dropdownToggle_${uniqueId}" class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                    Opciones
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownToggle_${uniqueId}">
                                                    <li><a class="dropdown-item text-success" value="Aprobado" data-row-id="${uniqueId}">Aprobado</a></li>
                                                    <li><a class="dropdown-item text-warning" value="Aprobado Condicionalmente" data-row-id="${uniqueId}">Aprobado Condicionalmente</a></li>
                                                    <li><a class="dropdown-item text-danger" value="Rechazado" data-row-id="${uniqueId}">Rechazado</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>`;

                            $('#miTabla tbody').append(fila);
                            Numid = index + 1;
                            // Inicializar select2 para los select dinámicos dentro del acordeón
                            target.find('select.tipoProblemasSelect').each(function() {
                                $(this).select2({
                                    placeholder: 'Seleccione tipo de problema',
                                    ajax: {
                                        url: '/obtenerTiposDefectos',
                                        dataType: 'json',
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data,
                                                    function(
                                                        item) {
                                                        return {
                                                            id: item
                                                                .Defectos,
                                                            text: item
                                                                .Defectos
                                                        };
                                                    })
                                            };
                                        }
                                    }
                                });
                            });
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al buscar datos de auditoría:', error);
                    }
                });
            });

            // Evento delegado para manejar la selección de problemas
            $('#accordion').on('select2:select select2:unselect', '.tipoProblemasSelect', function(e) {
                var $select = $(this);
                var selectedOptions = $select.val(); // Obtener las opciones seleccionadas

                var $row = $select.closest('tr'); // Obtener la fila correspondiente
                var $cantidadContainer = $row.find(
                    '.cantidadContainer'); // Div contenedor de los inputs de cantidad

                // Limpiar los inputs de cantidad anteriores
                $cantidadContainer.empty();

                if (selectedOptions && selectedOptions.length > 0) {
                    // Generar inputs de cantidad dinámicamente
                    $.each(selectedOptions, function(index, value) {
                        var inputId = generateUniqueId('cantidadInput');
                        var inputHtml = `
                        <div class="cantidad-group" style="margin-bottom: 5px;">
                            <label for="${inputId}" style="margin-right: 10px;">${value}:</label>
                            <input type="number" class="form-control cantidadInput" id="${inputId}" value="0">
                        </div>`;
                        $cantidadContainer.append(inputHtml);
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Manejar clic en cualquier opción del dropdown
            $('#accordion').on('click', '.dropdown-item', function() {
                var $dropdownItem = $(this);
                var selectedOption = $dropdownItem.attr('value');
                var status = $dropdownItem.text().trim();
                var tipoBusqueda = $('#tipoBusqueda').val();
                // Obtener los datos de la fila modificada
                var fila = $dropdownItem.closest('tr'); // Obtener la fila actual
                var tipoBusqueda = tipoBusqueda;
                var orden = $('#ordenInput').val();
                var estiloText = $(event.target).closest('.card').find('.btn-link').text();
                var estilo = estiloText.match(/Estilo:\s*([^\s]+)/)[1].trim();
                var color = fila.find('td:nth-child(2)').text().trim();
                var talla = fila.find('td:nth-child(3)').text().trim();
                var cantidad = fila.find('td:nth-child(4)').text().trim();
                var muestreo = fila.find('.tamañoMuestra').text().trim();
                var tipoDefecto = fila.find('.tipoProblemasSelect').val();
                var defectos = [];
                // Obtener todos los valores de los inputs de defectos
                fila.find('.cantidadContainer .cantidadInput').each(function() {
                    defectos.push({
                        cantidad: $(this).val()
                    });
                });

                var datosFila = {
                    tipoBusqueda: tipoBusqueda,
                    orden: orden,
                    estilo: estilo,
                    color: color,
                    talla: talla,
                    cantidad: cantidad,
                    muestreo: muestreo,
                    tipoDefecto: tipoDefecto,
                    defectos: defectos,
                    status: status
                };
                // Armar los datos a enviar al servidor
                var datosAEnviar = {
                    _token: $('meta[name="csrf-token"]').attr(
                        'content'), // Obtener el token CSRF del meta tag
                    datos: [datosFila],
                    status: status,
                    tipoBusqueda: tipoBusqueda
                };

                // Realizar la solicitud AJAX para enviar los datos al servidor
                $.ajax({
                    url: '/actualizarStatus',
                    type: 'PUT',
                    data: JSON.stringify(datosAEnviar), // Enviar como JSON
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        alert(response.mensaje);
                        // Actualizar visualmente el estado en la interfaz
                        $dropdownItem.closest('.btn-group').find('.dropdown-toggle').text(
                            status);
                    },
                    error: function(xhr, status, error) {
                        // Manejar errores
                        alert('Error al actualizar el status. Por favor, inténtalo de nuevo.');
                        console.error('Error al actualizar el status: ', error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var $toggleSwitch = $('#consultarDataBtn');
            var nuevosEstilos = '';
            var $accordionew = $('#accordionnew');

            // Evento para activar/desactivar la búsqueda de datos extra
            $toggleSwitch.change(function() {
                if ($(this).is(':checked')) {
                    // Solicitar al usuario los nuevos estilos a buscar
                    nuevosEstilos = prompt(
                        'Ingrese los nuevos estilos separados por comas (ej: Estilo1, Estilo2, Estilo3):'
                    );

                    // Verificar que el usuario haya ingresado algo
                    if (!nuevosEstilos) {
                        alert('Debe ingresar al menos un estilo.');
                        $toggleSwitch.prop('checked', false); // Desactivar el switch si no se ingresa nada
                        return;
                    }

                    // Limpiar espacios en blanco alrededor de los estilos
                    nuevosEstilos = nuevosEstilos.split(',').map(function(estilo) {
                        return estilo.trim();
                    }).join(',');

                    // Mostrar un mensaje de carga si el toggle está activado
                    $('#accordionnew').html('<p>Cargando datos adicionales...</p>');
                } else {
                    // Si el toggle se desactiva, limpiar el contenedor de resultados
                    $('#accordionnew').empty();
                }
            });

            // Evento para manejar la expansión del acordeón
            $('#accordion').on('shown.bs.collapse', function(event) {
                console.log('Acordeón expandido');

                if ($toggleSwitch.is(':checked')) {
                    let target = $(event.target);
                    let estiloText = target.closest('.card').find('.btn-link').text();
                    let estiloMatch = estiloText.match(/Estilo:\s*([^\s]+)/);

                    if (!estiloMatch) {
                        alert('No se pudo obtener el estilo correctamente.');
                        return;
                    }

                    let estilo = estiloMatch[1].trim();
                    let orden = $('#ordenInput').val();

                    // Asegurarnos que la orden y el estilo sean válidos antes de proceder
                    if (!orden || !estilo) {
                        alert('Por favor, seleccione una orden y un estilo válidos.');
                        $('#accordionnew').html('');
                        return;
                    }

                    alert('Orden: ' + orden + '\nEstilo: ' + estilo + '\nNuevos Estilos: ' + nuevosEstilos);

                    // Desactivar el botón mientras se realiza la solicitud
                    $toggleSwitch.prop('disabled', true);

                    // Realizar la solicitud AJAX
                    $.ajax({
                        url: '/datosinventario',
                        type: 'GET',
                        data: {
                            orden: orden,
                            estilo: estilo,
                            nuevosEstilos: nuevosEstilos
                        },
                        success: function(response) {
                            // Limpiar el contenido del acordeón
                            $accordionew.empty();

                            // Agrupar los datos por ITEMID
                            var groupedData = {};
                            $.each(response, function(index, item) {
                                if (!groupedData[item.ITEMID]) {
                                    groupedData[item.ITEMID] = [];
                                }
                                groupedData[item.ITEMID].push(item);
                            });

                            // Generar dinámicamente los acordeones
                            $.each(groupedData, function(itemID, items) {
                                var estado = items[0].status || 'N/A';

                                // Crear el HTML para el acordeón
                                var acordeonNewHtml = `
                            <div class="card">
                                <div class="card-header" id="headingNew${itemID}" style="background-color: rgb(170, 42, 176) !important; text-align: center;">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" style="background-color: rgb(170, 42, 176) !important; color: white; font-size: 17px;" data-toggle="collapse" data-target="#collapsenew${itemID}" aria-expanded="true" aria-controls="collapsenew${itemID}">
                                            Estilo: ${itemID}
                                            <br>
                                            Estado: ${estado}
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapsenew${itemID}" class="collapse" aria-labelledby="headingNew${itemID}" data-parent="#accordionnew">
                                    <div class="card-body">
                                        <!-- Contenido de la auditoría para el estilo -->
                                        <div class="table-responsive">
                                            <table class="table table-sm" id="miTablaNew">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center;">#</th>
                                                        <th style="text-align: center;">Color</th>
                                                        <th style="text-align: center;">Talla</th>
                                                        <th style="text-align: center;">Cantidad</th>
                                                        <th style="text-align: center;">Tamaño Muestra</th>
                                                        <th style="text-align: center;">Tipos Defectos</th>
                                                        <th style="text-align: center;"># Defectos</th>
                                                        <th style="text-align: center;">Acciones Correctivas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    ${items.map((item, index) => {
                                                        var cantidadFormateada = parseFloat(item.REQUESTQTY).toFixed(2);
                                                        return `
                                                                    <tr>
                                                                        <td style="text-align: center;">${index + 1}</td>
                                                                        <td style="text-align: center;">${item.INVENTCOLORID}</td>
                                                                        <td style="text-align: center;">${item.INVENTSIZEID}</td>
                                                                        <td style="text-align: center;">${cantidadFormateada}</td>
                                                                        <td style="text-align: center;"><span class="tamañoMuestra">${item.tamaño_muestra ? item.tamaño_muestra : 'N/A'}</span></td>
                                                                        <td style="text-align: center;">
                                                                            <select class="form-control tipoProblemasSelectNew" id="tipoProblemasNew${itemID}" multiple="multiple"></select>
                                                                        </td>
                                                                        <td class="cantidadContainerNew" style="text-align: center;">
                                                                            <!-- Los inputs de cantidad se generarán dinámicamente aquí -->
                                                                        </td>
                                                                        <td style="text-align: center;">${item.acciones_correctivas}</td>
                                                                    </tr>
                                                                `;
                                                    }).join('')}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                                // Agregar el acordeón al contenedor
                                $accordionew.append(acordeonNewHtml);
                                itemID = itemID;
                                // Inicializar select2 para los select dinámicos dentro del acordeón
                                $accordionew.find('select.tipoProblemasSelectNew').each(
                                    function() {
                                        $(this).select2({
                                            placeholder: 'Seleccione tipo de problema',
                                            ajax: {
                                                url: '/obtenerTiposDefectos',
                                                dataType: 'json',
                                                processResults: function(
                                                    data) {
                                                    return {
                                                        results: $.map(
                                                            data,
                                                            function(
                                                                item
                                                            ) {
                                                                return {
                                                                    id: item
                                                                        .Defectos,
                                                                    text: item
                                                                        .Defectos
                                                                };
                                                            })
                                                    };
                                                }
                                            }
                                        });
                                    });
                            });
                        },
                        error: function(xhr, status, error) {
                            $('#accordionnew').html(
                                '<p>Ocurrió un error al consultar los datos.</p>');
                            console.error(error);
                        },
                        complete: function() {
                            // Reactivar el botón después de que la solicitud se complete
                            $toggleSwitch.prop('disabled', false);
                        }
                    });
                }
            });
            // Evento delegado para manejar la selección de problemas
            $('#accordionnew').on('select2:select select2:unselect', '.tipoProblemasSelectNew', function(e) {
                var $select = $(this);
                var selectedOptions = $select.val(); // Obtener las opciones seleccionadas

                var $row = $select.closest('tr'); // Obtener la fila correspondiente
                var $cantidadContainerNew = $row.find(
                    '.cantidadContainerNew'); // Div contenedor de los inputs de cantidad
                var $itemID;
                // Limpiar los inputs de cantidad anteriores
                $cantidadContainerNew.empty();

                if (selectedOptions && selectedOptions.length > 0) {
                    // Generar inputs de cantidad dinámicamente
                    $.each(selectedOptions, function(index, value) {

                        var inputHtmlNews = `
                        <div class="cantidad-group" style="margin-bottom: 5px;">
                            <label for="${itemID}" style="margin-right: 10px;">${value}:</label>
                            <input type="number" class="form-control cantidadInputNew" id="${itemID}" value="0">
                        </div>`;
                        $cantidadContainerNew.append(inputHtmlNews);
                    });
                }
            });
        });
    </script>
@endsection
