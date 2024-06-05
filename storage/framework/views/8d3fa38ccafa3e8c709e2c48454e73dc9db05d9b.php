
<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title"><?php echo e(__('Auditoria Etiquetas.')); ?></h3>
                        </div>
                        <div class="col-md-6 text-right">
                            Fecha: <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                        </div>
                    </div>
                </div>
                <br>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header card-header-primary text-center">
                                <h4 class="card-title mb-3"><?php echo e(__('Informacion General.')); ?></h4>
                            </div>
                            <br>
                        </div>
                        <div class="col-md-1">
                            <label for="tipoBusqueda">Tipo de búsqueda:</label>
                            <select class="form-control" id="tipoBusqueda" name="tipoBusqueda">
                                <option selected>Selecciona un tipo de busqueda</option>
                                <option value="OC">OC</option>
                                <option value="OP">OP</option>
                                <option value="PO">PO</option>
                                <option value="OV">OV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ordenSelect">Selecciona No/Orden:</label>
                            <select class="form-control" id="ordenSelect" name="ordenSelect" required>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success" id="Buscar">
                                Buscar
                            </button>
                        </div>
                    </div>
                    <br>
                </div>

            </div>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div class="card col-md-12" style="width: auto;">
                    <div class="card-header card-header-primary">
                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="card-title" style="font-size: 24px;"><?php echo e(__('Auditoría.')); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div id="accordion" style="margin-top: 1px;">
                        <!-- Los acordeones se generarán dinámicamente aquí -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card.border-primary {
            border-color: rgb(170, 42, 176) !important;
        }

        .card-header.bg-primary {
            background-color: rgb(170, 42, 176) !important;
        }

        .rechazado {
            background-color: #d91f1f;
            /* Fondo rojo */
            color: #fff;
            /* Texto blanco */
        }

        .rechazado td {
            border: 1px solid #fff;
            /* Borde blanco */
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inicializar Select2 para la orden
            $('#ordenSelect').select2({
                placeholder: 'Seleccione una orden',
                allowClear: true
            });
            $('#tipoBusqueda').change(function() {
                var tipoBusqueda = $(this).val();
                var rutas = {
                    OC: '/NoOrdenes',
                    OP: '/NoOP',
                    PO: '/NoPO',
                    OV: '/NoOV'
                };

                var url = rutas[tipoBusqueda] || '/NoOrdenes';

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#ordenSelect').empty();

                        // Agregar la opción predeterminada
                        $('#ordenSelect').append($('<option>', {
                            disabled: true,
                            selected: true,
                            text: 'Seleccione una orden'
                        }));

                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            // Determinar el campo a mostrar según el tipo de búsqueda
                            var campoMostrar = {
                                OC: 'OrdenCompra',
                                OP: 'op',
                                PO: 'cpo',
                                OV: 'salesid'
                            } [tipoBusqueda]; // Usar un objeto para mapear los campos

                            $('#ordenSelect').append($('<option>', {
                                value: value[
                                    campoMostrar
                                ], // Usar el valor del campo correcto
                                text: value[campoMostrar]
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones de ordenes: ', error);
                    }
                });
                // Manejar clic en el botón de búsqueda
                $('#Buscar').click(function() {
                    var orden = $('#ordenSelect').val();
                    var tipoBusqueda = $('#tipoBusqueda').val();
                    if (orden) {
                        // Realizar la solicitud AJAX para buscar estilos
                        $.ajax({
                            url: '/buscarEstilos',
                            type: 'GET',
                            data: {
                                orden: orden,
                                tipoBusqueda: tipoBusqueda
                            },
                            dataType: 'json',
                            success: function(data) {
                                // Limpiar los acordeones existentes
                                $('#accordion').empty();
                                // Generar acordeones para cada estilo encontrado
                                $.each(data.estilos, function(key, value) {
                                    var accordion =
                                        '<div class="card border-primary mb-3">';
                                    accordion +=
                                        '<div class="card-header bg-primary" id="heading' +
                                        key + '">';
                                    accordion += '<h2 class="mb-0">';
                                    accordion +=
                                        '<button class="btn btn-link btn-block text-white" data-toggle="collapse" data-target="#collapse' +
                                        key +
                                        '" aria-expanded="true" aria-controls="collapse' +
                                        key + '">';
                                    accordion +=
                                        '<span style="font-size: 20px;">' +
                                        'Estilo: ' + value.Estilos + '</span>';
                                    accordion +=
                                        '<span style="font-size: 18px;" id="status_' +
                                        key +
                                        '">' +
                                        'Status: ' +
                                        data.status[key] +
                                        // Mostrar el estado de la auditoría
                                        '</span>';
                                    accordion += '</button>';
                                    accordion += '</h2>';
                                    accordion += '</div>';
                                    accordion += '<div id="collapse' + key +
                                        '" class="collapse" aria-labelledby="heading' +
                                        key + '" data-parent="#accordion">';
                                    accordion += '<div class="card-body">';
                                    // Contenido del acordeon
                                    accordion +=
                                        '<div class="tab-pane" id="messages">';
                                    accordion +=
                                        '<div class="card-body table-responsive">';
                                    accordion +=
                                        '<table class="table" id="miTabla">';
                                    accordion += '<thead class="text-primary">';
                                    accordion += '<tr>';
                                    accordion +=
                                        '<th style="text-align: left; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">#</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">No/Orden</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Estilos</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Color</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Talla</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Cantidad</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Tamaño Muestra</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Defectos</th>';
                                    accordion +=
                                        '<th style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: 1%;">Tipo Defectos</th>';
                                    accordion +=
                                        '<th  style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%;">Acciones</th>';
                                    accordion +=
                                        '<th  style="text-align: center; word-wrap: break-word; overflow-wrap: break-word; white-space: normal; width: .1%; display: none;">id</th>';

                                    accordion += '</tr>';
                                    accordion += '</thead>';
                                    accordion += '<tbody>';
                                    accordion += '</tbody>';
                                    accordion += '<tfoot>';
                                    accordion += '<tr>';
                                    accordion += '<td>';
                                    accordion +=
                                        '<button type="button" class="btn btn-success" id="Saved">';
                                    accordion += '<span>Guardar</span>';
                                    accordion += '</button>';
                                    accordion += '</td>';
                                    accordion += '</tr>';
                                    accordion += '</tfoot>';
                                    accordion += '</table>';
                                    accordion += '</div>';
                                    accordion += '</div>';
                                    accordion += '</div>';
                                    accordion += '</div>';
                                    accordion += '</div>';
                                    $('#accordion').append(accordion);
                                });

                            },
                            error: function(error) {
                                console.error('Error al buscar estilos: ', error);
                            }
                        });
                    } else {
                        alert('Por favor, seleccione una orden.');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Evento delegado para el clic en elementos .dropdown-item dentro de #accordion
            $('#accordion').on('click', '.dropdown-item', function() {
                var selectedOption = $(this).text();
                // Encontrar el botón de alternancia más cercano (dropdown-toggle) dentro del mismo grupo (dropdown)
                var dropdownToggle = $(this).closest('.dropup').find('.dropdown-toggle');
                dropdownToggle.text(selectedOption);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Controlador para el evento de clic en cualquier acordeón
            $('#accordion').on('click', '.card-header', function() {
                var ordenSeleccionada = $('#ordenSelect').val();
                var tipoBusqueda = $('#tipoBusqueda').val();
                var estilo = $(this).find('span:first').text().split(':')[1].trim();
                $.ajax({
                    url: '/buscarDatosAuditoriaPorEstilo',
                    type: 'GET',
                    data: {
                        estilo: estilo,
                        orden: ordenSeleccionada,
                        tipoBusqueda: tipoBusqueda
                    },
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar tabla antes de agregar resultados
                        $('#miTabla tbody').empty();
                        // Mostrar resultados en la tabla
                        $.each(data, function(index, item) {
                            var ordenSeleccionada = $('#ordenSelect').val();
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

                            // Formatear la cantidad (si es necesario)
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

                            // Verificar si el tamaño de muestra está en el rango de 2 a 20
                            var tamañoMuestra = parseInt(item.tamaño_muestra);
                            var inputHTML =
                                '<input type="number" class="form-control cantidadInput" id="cantidadInput_' +
                                index + '_acordeon_' + estilo +
                                '" value="0">'; // Modificado: agregado '_acordeon_' + estilo
                            // Verificar si el tamaño de muestra está en el rango específico
                            if (tamañoMuestra == 32 || tamañoMuestra == 50 ||
                                tamañoMuestra == 80 || tamañoMuestra == 125 ||
                                tamañoMuestra == 200 || tamañoMuestra == 315 ||
                                tamañoMuestra == 500 || tamañoMuestra == 800 ||
                                tamañoMuestra == 2000) {
                                inputHTML =
                                    '<td style="text-align: center;"><input type="number" class="form-control cantidadInput" id="cantidadInput_' +
                                    index + '_acordeon_' + estilo +
                                    '" value="0"></td>'; // Modificado: agregado '_acordeon_' + estilo
                            }
                            // Agregar fila a la tabla
                            var fila = '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td style="text-align: center;">' + ordenSeleccionada +
                                '</td>' +
                                '<td style="text-align: center;">' + (item.Estilos ||
                                    item.Estilo) + '</td>' +
                                '<td style="text-align: center;">' + (item[campos
                                    .Color] || 'N/A') + '</td>' +
                                '<td style="text-align: center;">' + (item[campos
                                    .Talla] || 'N/A') + '</td>' +
                                '<td style="text-align: center;">' +
                                cantidadFormateada + '</td>' +
                                '<td style="text-align: center;"><span class="tamañoMuestra">' +
                                (item.tamaño_muestra ? item.tamaño_muestra : 'N/A') +
                                '</span></td>' +
                                '<td style="text-align: center; position: relative;">' +
                                '<input type="number" class="form-control cantidadInput" id="cantidadInput_' +
                                index + '_acordeon_' + estilo + '" value="0">' +
                                '</td>' +
                                '<td class="select-container" style="text-align: center;">' +
                                '<select class="form-control select2-multiple" id="tipoDefectos_' +
                                item.id + '" multiple="multiple">' +
                                // ID único basado en item.id
                                '</select>' +
                                '</td>' +
                                '<td>' +
                                '<div class="dropup-center dropup">' +
                                '<button id="dropdownToggle_' + index + '_acordeon_' +
                                estilo +
                                '" class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">' +
                                'Opciones' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownToggle_' +
                                index + '_acordeon_' + estilo + '">' +
                                '<li><a class="dropdown-item text-success" value="Aprobado" data-row-id="' +
                                item.id + '">Aprobado</a></li>' +
                                '<li><a class="dropdown-item text-warning" value="Aprobado Condicionalmente" data-row-id="' +
                                item.id + '">Aprobado Condicionalmente</a></li>' +
                                '<li><a class="dropdown-item text-danger" value="Rechazado" data-row-id="' +
                                item.id + '">Rechazado</a></li>' +
                                '</ul>' +
                                '</div>' +
                                '</td>' +
                                '<td style="display: none;">' + (item.id ? item.id :
                                    'N/A') + '</td>' +
                                '</tr>';

                            $('#miTabla tbody').append(fila);
                            cargarOpcionesSelect(item, index);
                        });

                        function cargarOpcionesSelect(item,
                        index) { // Nueva función para cargar opciones
                            $.ajax({
                                url: '/obtenerTiposDefectos',
                                type: 'GET',
                                dataType: 'json',
                                success: function(options) {
                                    var $select = $('#tipoDefectos_' + item
                                    .id); // Seleccionar el select correcto usando el ID único

                                    // Agregar opciones al select
                                    $.each(options, function(key, value) {
                                        var $option = $('<option>', {
                                            value: value.Defectos,
                                            text: value.Defectos
                                        });

                                        // Preseleccionar la opción si ya existe en item.Tipo_Defectos
                                        if (item.Tipo_Defectos && item
                                            .Tipo_Defectos.includes(value
                                                .Defectos)) {
                                            $option.prop('selected', true);
                                        }

                                        $select.append($option);
                                    });

                                    // Inicializar Select2 después de cargar las opciones
                                    $select.select2({
                                        placeholder: 'Selecciona tipos de defecto',
                                        allowClear: true
                                    });
                                },
                                error: function(error) {
                                    console.error(
                                        'Error al cargar opciones del select: ',
                                        error);
                                }
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Error al buscar datos de auditoría por estilo: ', error);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('click', '#Saved', function() {
            var ordenSeleccionada = $('#ordenSelect').val();
            var tipoBusqueda = $('#tipoBusqueda').val();
            // Obtener los datos del acordeón
            var datosAEnviar = [];
            var acordeon = $(this).closest('.card');
            acordeon.find('tbody tr').each(function(index, fila) {
                var orden = $(fila).find('td:nth-child(2)').text().trim();
                var estilo = $(fila).find('td:nth-child(3)').text().trim();
                var color = $(fila).find('td:nth-child(4)').text().trim();
                var talla = $(fila).find('td:nth-child(5)').text().trim();
                var cantidad = $(fila).find('td:nth-child(6)').text().trim();
                var tipoDefecto = $(fila).find('.select-container select').val();
                var muestreo = $(fila).find('.tamañoMuestra').text().trim();
                var defectos = $(fila).find('.cantidadInput').val(); // Agregar el campo defectos
                var id = $(fila).find('td:nth-child(11)').text().trim();
                // Agregar los datos de la fila al arreglo datosAEnviar
                datosAEnviar.push({
                    id: id,
                    orden: orden,
                    estilo: estilo,
                    color: color,
                    talla: talla,
                    cantidad: cantidad,
                    muestreo: muestreo,
                    defectos: defectos, // Agregar el campo defectos
                    tipoDefecto: tipoDefecto,
                    tipoBusqueda: tipoBusqueda // Agregar el campo tipoDefecto

                });
            });
            // Realizar la solicitud AJAX para guardar los datos
            $.ajax({
                url: '/guardarInformacion',
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    orden: ordenSeleccionada,
                    datos: datosAEnviar
                },
                dataType: 'json',
                success: function(response) {
                    // Mostrar mensaje de éxito al usuario
                    alert(response.mensaje);
                },
                error: function(error) {
                    // Mostrar mensaje de error al usuario
                    alert('Error al guardar los datos. Por favor, inténtalo de nuevo.');
                    console.error('Error al guardar los datos: ', error);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Manejar clic en cualquier opción del dropdown
            $('#accordion').on('click', '.dropdown-item', function() {
                // Obtener el valor de la opción seleccionada
                var selectedOption = $(this).attr('value');
                // Obtener el texto del botón de alternancia más cercano (dropdown-toggle)
                var status = $(this).text().trim();
                // Obtener el ID de la fila correspondiente
                var rowId = $(this).data('row-id');
                var tipoBusqueda = $('#tipoBusqueda').val();
                // Obtener los datos de las filas de la tabla dentro del acordeón actual
                var datosAEnviar = [];
                var acordeon = $(this).closest('.card');
                acordeon.find('tbody tr').each(function(index, fila) {
                    var orden = $(fila).find('td:nth-child(2)').text().trim();
                    var estilo = $(fila).find('td:nth-child(3)').text().trim();
                    var color = $(fila).find('td:nth-child(4)').text().trim();
                    var talla = $(fila).find('td:nth-child(5)').text().trim();
                    var cantidad = $(fila).find('td:nth-child(6)').text().trim();
                    var tipoDefecto = $(fila).find('.select-container select').val();
                    var muestreo = $(fila).find('.tamañoMuestra').text().trim();
                    var defectos = $(fila).find('.cantidadInput')
                        .val(); // Agregar el campo defectos
                    var id = $(fila).find('td:nth-child(11)').text().trim();
                    // Agregar los datos de la fila al arreglo datosAEnviar
                    datosAEnviar.push({
                        id: id,
                        orden: orden,
                        estilo: estilo,
                        color: color,
                        talla: talla,
                        cantidad: cantidad,
                        muestreo: muestreo,
                        defectos: defectos, // Agregar el campo defectos
                        tipoDefecto: tipoDefecto, // Agregar el campo tipoDefecto
                        tipoBusqueda: tipoBusqueda
                    });
                });
                // Obtener la orden seleccionada
                var ordenSeleccionada = $('#ordenSelect').val();

                // Armar los datos a enviar al servidor
                var datosAEnviar = {
                    _token: '<?php echo e(csrf_token()); ?>',
                    orden: ordenSeleccionada,
                    datos: datosAEnviar, // Datos de las filas de la tabla
                    status: status, // Status seleccionado del dropdown
                    rowId: rowId, // ID de la fila seleccionada
                    tipoBusqueda: tipoBusqueda
                };

                // Realizar la solicitud AJAX para enviar los datos al servidor
                $.ajax({
                    url: '/actualizarStatus',
                    type: 'PUT', // Cambiado a PUT
                    data: datosAEnviar,
                    tipoBusqueda,
                    dataType: 'json',
                    success: function(response) {
                        // Manejar la respuesta del servidor
                        alert(response.mensaje); // Mostrar mensaje de éxito al usuario
                    },
                    error: function(error) {
                        // Manejar errores
                        alert('Error al actualizar el status. Por favor, inténtalo de nuevo.');
                        console.error('Error al actualizar el status: ', error);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Etiquetas', 'titlePage' => __('Etiquetas')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\formulariosCalidad\auditoriaEtiquetas.blade.php ENDPATH**/ ?>