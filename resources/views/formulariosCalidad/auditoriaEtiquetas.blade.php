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
                        <div class="col-md-6">
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
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Crear una plantilla para la opción predeterminada
                        var opciones = $(
                            '<option disabled selected>Seleccione una orden</option>');

                        // Crear las nuevas opciones usando la plantilla
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
                        console.error('Error al cargar opciones de ordenes: ', error);
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
                                        Aquí va el contenido de la auditoría para el estilo ${estilo.Estilos}.
                    <table class="table table-sm" id="miTabla">
                        <thead>
                              <tr>
                                    <th></th>
                                    <th style="text-align: center;">Color</th>
                                    <th style="text-align: center;">Talla</th>
                                    <th style="text-align: center;">Cantidad</th>
                                    <th style="text-align: center;">Tamaño
                                        <br>Muestra</th>
                                    <th style="text-align: center;"Tipos
                                        <br>Defectos</th>
                                    <th style="text-align: center;"># Defectos</th>
                                    <th style="text-align: center;">Acciones
                                        <br>Correctivas</th>
                             </tr>
                        </thead>
                            <tbody>
                                <!-- Las filas del cuerpo (tbody) se generarán dinámicamente aquí -->
                            </tbody>
                    </table>
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
    // Evento para cuando se despliega el accordion
    $('#accordion').on('show.bs.collapse', function(event) {
        let estiloText = $(event.target).closest('.card').find('.btn-link').text();
        let estilo = estiloText.match(/Estilo:\s*([^\s-]+)/)[1].trim(); // Obtener solo el estilo
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
                    }[tipoBusqueda];

                    // Formatear la cantidad (si es necesario)
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

                    // Verificar si el tamaño de muestra está en el rango específico
                    var tamañoMuestra = parseInt(item.tamaño_muestra);
                    var inputHTML = '<input type="number" class="form-control cantidadInput" id="cantidadInput_' +
                                    index + '_acordeon_' + estilo + '" value="0">';

                    if (tamañoMuestra == 32 || tamañoMuestra == 50 || tamañoMuestra == 80 ||
                        tamañoMuestra == 125 || tamañoMuestra == 200 || tamañoMuestra == 315 ||
                        tamañoMuestra == 500 || tamañoMuestra == 800 || tamañoMuestra == 2000) {
                        inputHTML = '<td style="text-align: center;"><input type="number" class="form-control cantidadInput" id="cantidadInput_' +
                                    index + '_acordeon_' + estilo + '" value="0"></td>';
                    }

                    // Agregar fila a la tabla
                    var fila = '<tr>' +
                                '<td style="text-align: center;">' + (index + 1) + '</td>' +
                                '<td style="text-align: center;">' + (item[campos.Color] || 'N/A') + '</td>' +
                                '<td style="text-align: center;">' + (item[campos.Talla] || 'N/A') + '</td>' +
                                '<td style="text-align: center;">' + cantidadFormateada + '</td>' +
                                '<td style="text-align: center;"><span class="tamañoMuestra">' + (item.tamaño_muestra ? item.tamaño_muestra : 'N/A') + '</span></td>' +
                                '<td style="text-align: center; position: relative;">' + inputHTML + '</td>' +
                                '<td class="select-container" style="text-align: center;">' +
                                '<div class="btn-group dropleft">' +
                                '<button id="dropdownToggle" class="btn btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">' +
                                'Opciones' +
                                '</button>' +
                                '<ul class="dropdown-menu" aria-labelledby="dropdownToggle">' +
                                '<li><a class="dropdown-item text-success" value="Aprobado" data-row-id="' +
                                item.id + '">Aprobado</a></li>' +
                                '<li><a class="dropdown-item text-warning" value="Aprobado Condicionalmente" data-row-id="' +
                                item.id + '">Aprobado Condicionalmente</a></li>' +
                                '<li><a class="dropdown-item text-danger" value="Rechazado" data-row-id="' +
                                item.id + '">Rechazado</a></li>' +
                                '</ul>' +
                                '</div>' +
                                '</td>' +
                                '</tr>';

                    $('#miTabla tbody').append(fila);
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al buscar datos de auditoría:', error);
            }
        });
    });

    // Evento delegado para el clic en elementos .dropdown-item dentro de #accordion
    $('#accordion').on('click', '.dropdown-item', function() {
        var selectedOption = $(this).text();
        // Encontrar el botón de alternancia más cercano (dropdown-toggle) dentro del mismo grupo (btn-group)
        var dropdownToggle = $(this).closest('.btn-group').find('.dropdown-toggle');
        dropdownToggle.text(selectedOption);
    });
});
    </script>
@endsection
