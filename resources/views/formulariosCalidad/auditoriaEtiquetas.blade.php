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
                            <label for="ordenSelect">Selecciona No/Orden:</label>
                            <select class="form-control" id="ordenSelect" name="ordenSelect" required>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success" id="Buscar" name ="Buscar">
                                Buscar
                            </button>
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
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Cachear los selectores para reutilizarlos
            var $ordenSelect = $('#ordenSelect');
            var $tipoBusqueda = $('#tipoBusqueda');
            var ajaxRequest; // Variable para almacenar la solicitud AJAX actual

            // Inicializar Select2 para la orden
            $ordenSelect.select2({
                placeholder: 'Seleccione una orden',
                allowClear: true
            });

            // Mapeo de rutas y campos a mostrar
            var rutas = {
                OC: '/NoOrdenes',
                OP: '/NoOP',
                PO: '/NoPO',
                OV: '/NoOV'
            };
            var campos = {
                OC: 'OrdenCompra',
                OP: 'op',
                PO: 'cpo',
                OV: 'salesid'
            };

            // Manejar el cambio de tipo de búsqueda
            $tipoBusqueda.change(function() {
                var tipoBusqueda = $(this).val();
                var url = rutas[tipoBusqueda] || rutas.OC;
                var campoMostrar = campos[tipoBusqueda];

                // Limpiar y restablecer el select de orden antes de cargar nuevas opciones
                $ordenSelect.html('<option disabled selected>Seleccione una orden</option>').val(null).trigger('change');

                // Cancelar cualquier solicitud AJAX anterior si está en progreso
                if (ajaxRequest && ajaxRequest.readyState != 4) {
                    ajaxRequest.abort();
                }

                // Realizar nueva solicitud AJAX
                ajaxRequest = $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Crear nuevas opciones usando los datos recibidos
                        var opciones = $('<option disabled selected>Seleccione una orden</option>');

                        $.each(data, function(key, value) {
                            opciones = opciones.add($('<option>', {
                                value: value[campoMostrar],
                                text: value[campoMostrar]
                            }));
                        });

                        // Reemplazar las opciones del select
                        $ordenSelect.html(opciones);
                    },
                    error: function(error) {
                        if (error.statusText !== 'abort') { // Ignorar errores de solicitud abortada
                            console.error('Error al cargar opciones de ordenes: ', error);
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Cachear selectores
            var $ordenSelect = $('#ordenSelect');
            var $tipoBusqueda = $('#tipoBusqueda');
            var $accordion = $('#accordion');

            // Manejar el clic en el botón de buscar
            $('#Buscar').click(function() {
                var orden = $ordenSelect.val();
                var tipoBusqueda = $tipoBusqueda.val();

                if (!orden || !tipoBusqueda) {
                    alert('Por favor, seleccione una orden y un tipo de búsqueda.');
                    return;
                }

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
                                    <th style="width: 0.5%;""></th>
                                    <th style="text-align: center; width: 1%">Color</th>
                                    <th style="text-align: center; width: 1%">Talla</th>
                                    <th style="text-align: center; width: 1%;">Cantidad</th>
                                    <th style="text-align: center; width: 1%;">Tamaño
                                        <br>Muestra</th>
                                    <th style="text-align: center; width: 25%;">Tipos
                                        <br>Defectos</th>
                                    <th style="text-align: center; width: 10%;"># Defectos</th>
                                    <th style="text-align: center;  width: auto;">Acciones
                                        <br>Correctivas</th>
                             </tr>
                        </thead>
                            <tbody>
                                <!-- Las filas del cuerpo (tbody) se generarán dinámicamente aquí -->
                            </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                <button type="button" class="button insertarFila" data-index="${index}">
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
                            </div>`;
                            // Agregar el acordeón al contenedor
                            $accordion.append(acordeonHtml);
                        });
                    },
                    error: function(error) {
                        console.error('Error al buscar estilos: ', error);
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
            let orden = $('#ordenSelect').val();
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
                        }[tipoBusqueda];

                        var uniqueId = generateUniqueId('input');

                        // Formatear la cantidad
                        var cantidadFormateada = item[campos.Cantidad];
                        if (typeof cantidadFormateada === 'string') {
                            var puntoIndex = cantidadFormateada.indexOf('.');
                            if (puntoIndex !== -1) {
                                var parteDecimal = cantidadFormateada.substring(puntoIndex + 1);
                                if (parteDecimal.length > 2) {
                                    parteDecimal = parteDecimal.substring(0, 2);
                                }
                                cantidadFormateada = cantidadFormateada.substring(0, puntoIndex + 1) + parteDecimal;
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
                                                    <li><a class="dropdown-item text-success" value="Aprobado" data-row-id="${item.id}">Aprobado</a></li>
                                                    <li><a class="dropdown-item text-warning" value="Aprobado Condicionalmente" data-row-id="${item.id}">Aprobado Condicionalmente</a></li>
                                                    <li><a class="dropdown-item text-danger" value="Rechazado" data-row-id="${item.id}">Rechazado</a></li>
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
                                            results: $.map(data, function(item) {
                                                return {
                                                    id: item.Defectos,
                                                    text: item.Defectos
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
            var $cantidadContainer = $row.find('.cantidadContainer'); // Div contenedor de los inputs de cantidad

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
            var rowId = $dropdownItem.data('row-id');
            var tipoBusqueda = $('#tipoBusqueda').val();
            // Obtener los datos de la fila modificada
            var fila = $dropdownItem.closest('tr'); // Obtener la fila actual
            var  tipoBusqueda = tipoBusqueda;
            var orden = $('#ordenSelect').val();
            var estiloText = $(event.target).closest('.card').find('.btn-link').text();
            var estilo = estiloText.match(/Estilo:\s*([^\s]+)/)[1].trim();
            var color = fila.find('td:nth-child(2)').text().trim();
            var  talla = fila.find('td:nth-child(3)').text().trim();
            var  cantidad = fila.find('td:nth-child(4)').text().trim();
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
                id: rowId,
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
                _token: $('meta[name="csrf-token"]').attr('content'), // Obtener el token CSRF del meta tag
                datos: [datosFila],
                status: status,
                rowId: rowId,
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
                    $dropdownItem.closest('.btn-group').find('.dropdown-toggle').text(status);
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
        // Función para generar un ID único
        function generateUniqueId(prefix) {
            return prefix + '_' + Math.random().toString(36).substr(2, 9);
        }

        // Evento delegado para el botón de añadir filas dinámicamente dentro del acordeón
        $(document).on('click', '.insertarFila', function() {
            var uniqueId = generateUniqueId('input');
            Numid++;
            var nuevaFila = `<tr>
                                <td style="text-align: center;">${Numid}</td>
                                <td style="text-align: center;">
                                    <input type="text" class="form-control" id="color_${uniqueId}" value="">
                                </td>
                                <td style="text-align: center;">
                                    <input type="text" class="form-control" id="talla_${uniqueId}" value="">
                                </td>
                                <td style="text-align: center;">
                                    <input type="text" class="form-control" id="cantidad_${uniqueId}" value="">
                                </td>
                                <td style="text-align: center;">
                                    <input type="text" class="form-control" id="muestra_${uniqueId}" value="">
                                </td>
                                <td style="text-align: center;">
                                    <select class="form-control tipoProblemasSelect" id="tipoProblemas_${uniqueId}" multiple="multiple"></select>
                                </td>
                                <td class="cantidadContainer" style="text-align: center;">
                                    <!-- Inputs de cantidad generados dinámicamente aquí -->
                                </td>
                                <td style="text-align: center;">
                                    <div class="btn-group dropleft">
                                        <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenu_${uniqueId}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Opciones
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenu_${uniqueId}">
                                            <li><a class="dropdown-item2 text-success" value="Aprobado" data-row-id="${uniqueId}">Aprobado</a></li>
                                            <li><a class="dropdown-item2 text-warning" value="Aprobado Condicionalmente" data-row-id="${uniqueId}">Aprobado Condicionalmente</a></li>
                                            <li><a class="dropdown-item2 text-danger" value="Rechazado" data-row-id="${uniqueId}">Rechazado</a></li>
                                        </div>
                                    </div>
                                </td>
                            </tr>`;

            // Añadir la fila a la tabla del acordeón correspondiente
            $('#miTabla tbody').append(nuevaFila);

            // Inicializar select2 en la nueva fila
            $('#tipoProblemas_' + uniqueId).select2({
                placeholder: 'Seleccione tipo de problema',
                ajax: {
                    url: '/obtenerTiposDefectos',
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: $.map(data, function(item) {
                                return {
                                    id: item.Defectos,
                                    text: item.Defectos
                                };
                            })
                        };
                    }
                }
            });
        });

        // Nuevo script para manejar clics en el dropdown de opciones generado dinámicamente
        $('#accordion').on('click', '.dropdown-item2', function(event) {
            var $dropdownItem = $(this);
            var selectedOption = $dropdownItem.attr('value');
            var status = $dropdownItem.text().trim();
            var uniqueId = $dropdownItem.data('row-id');

            // Obtener los datos de la fila específica
            var fila = $dropdownItem.closest('tr');
            var orden = $('#ordenSelect').val(); // Valor del select de orden
            var estiloText = $(event.target).closest('.card').find('.btn-link').text();
            var estilo = estiloText.match(/Estilo:\s*([^\s]+)/)[1].trim();
            var color = fila.find(`#color_${uniqueId}`).val().trim();
            var talla = fila.find(`#talla_${uniqueId}`).val().trim();
            var cantidad = fila.find(`#cantidad_${uniqueId}`).val().trim();
            var muestreo = fila.find(`#muestra_${uniqueId}`).val().trim();
            var tipoDefecto = fila.find(`#tipoProblemas_${uniqueId}`).val(); // Valor del select múltiple
            var defectos = [];

            // Obtener todos los valores de los inputs de defectos generados dinámicamente
            fila.find('.cantidadContainer .cantidadInput').each(function() {
                defectos.push({
                    cantidad: $(this).val()
                });
            });

            // Preparar los datos para enviar al servidor
            var datosFila = {
                orden: orden,
                estilo: estilo,
                color: color,
                talla: talla,
                cantidad: cantidad,
                muestreo: muestreo,
                tipoDefecto: tipoDefecto,
                defectos: defectos
            };

            var datosAEnviar = {
                _token: $('meta[name="csrf-token"]').attr('content'), // Obtener el token CSRF del meta tag
                datos: [datosFila],
                status: status
            };

            // Realizar la solicitud AJAX para guardar los datos en el servidor
            $.ajax({
                url: '/saveFila',
                type: 'POST',
                data: JSON.stringify(datosAEnviar), // Enviar los datos como JSON
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    // Manejar la respuesta del servidor
                    alert(response.mensaje);
                    // Actualizar visualmente el estado en la interfaz
                    $dropdownItem.closest('.btn-group').find('.dropdown-toggle').text(status);
                },
                error: function(xhr, status, error) {
                    // Manejar errores
                    alert('Error al guardar los datos. Por favor, inténtalo de nuevo.');
                    console.error('Error al guardar los datos: ', error);
                }
            });
        });
    });
    </script>



@endsection
