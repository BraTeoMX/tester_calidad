@extends('layouts.app', ['pageSlug' => 'dashboardComparativoClientes', 'titlePage' => __('Dashboard Comparativo Clientes')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            @php
                use Carbon\Carbon;
                $fechaFinCarbon = Carbon::now();
                $fechaInicioCarbon = Carbon::now()->subWeeks(3);
                $fechaFinInput = $fechaFinCarbon->format('Y-\WW');
                $fechaInicioInput = $fechaInicioCarbon->format('Y-\WW');
            @endphp

            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title text-center font-weight-bold">COMPARATIVO CLIENTES</h2>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Semana inicio</label>
                                            <input type="week" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicioInput }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_fin">Semana fin</label>
                                            <input type="week" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $fechaFinInput }}" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
                            </form>
                            <div class="accordion" id="accordionExport">
                                <div class="card">
                                    <div class="card-header p-0" id="headingExport">
                                        <button class="btn btn-secondary text-left py-2" type="button" data-toggle="collapse" data-target="#collapseExport" aria-expanded="false" aria-controls="collapseExport">
                                            Exportar
                                        </button>
                                    </div>
                                    <div id="collapseExport" class="collapse" aria-labelledby="headingExport" data-parent="#accordionExport">
                                        <div class="card-body p-2">
                                            <button type="button" id="exportExcelGeneral" class="btn btn-info mb-2">General</button>
                                            <button type="button" id="exportExcelPlanta1" class="btn btn-info mb-2">Planta 1 - Ixtlahuaca</button>
                                            <button type="button" id="exportExcelPlanta2" class="btn btn-info ">Planta 2 - San Bartolo</button>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aquí irán las tabs de clientes y sus contenidos, pero inicialmente vacíos. 
                Serán llenados dinámicamente vía JavaScript tras la llamada AJAX. -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <!-- Contenedor para las tabs de clientes (se llenará con JS) -->
                    <ul class="nav nav-tabs" id="clienteTabs" role="tablist">
                        <!-- Se generará dinámicamente con JavaScript -->
                    </ul>
                </div>
                <div class="tab-content" id="clienteTabContent">
                    <!-- Contenido de las pestañas de clientes (y las subpestañas General, Planta 1, Planta 2) se generará dinámicamente con JavaScript -->
                </div>
            </div>
        </div>
    </div>
    <style>
        .bg-rojo-oscuro {
            background-color: #8B0000; /* Rojo oscuro */
            color: white; /* Texto blanco para contraste */
        }

        .bg-amarillo-oscuro {
            background-color: #918305; /* Rojo oscuro */
            color: white; /* Texto blanco para contraste */
        }

    </style>
        
@endsection


@push('js') 
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <!-- Highcharts -->
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>

    <script>
        $(document).ready(function () {
            // Función genérica para exportar
            function exportExcel(planta) {
                const fechaInicio = $('#fecha_inicio').val();
                const fechaFin = $('#fecha_fin').val();

                // Crear un formulario para enviar los datos al backend
                const form = $('<form>', {
                    method: 'POST',
                    action: '{{ route("export.semana") }}' // Ruta genérica
                });

                // Agregar el token CSRF
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: '{{ csrf_token() }}'
                }));

                // Agregar fechas al formulario
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'fecha_inicio',
                    value: fechaInicio
                }));
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'fecha_fin',
                    value: fechaFin
                }));

                // Agregar planta al formulario
                form.append($('<input>', {
                    type: 'hidden',
                    name: 'planta',
                    value: planta
                }));

                // Agregar el formulario al cuerpo y enviarlo
                $('body').append(form);
                form.submit();
            }

            // Vincular los botones con la exportación
            $('#exportExcelGeneral').on('click', function () {
                exportExcel('general');
            });

            $('#exportExcelPlanta1').on('click', function () {
                exportExcel('planta1');
            });

            $('#exportExcelPlanta2').on('click', function () {
                exportExcel('planta2');
            });
        });


    // Definir el tipo de ordenamiento personalizado para manejar "N/A"
    $.fn.dataTable.ext.type.order['custom-num-pre'] = function (a) {
        if (a === "N/A") return -Infinity; 
        var x = parseFloat(a);
        return isNaN(x) ? -Infinity : x;
    };
    $.fn.dataTable.ext.type.order['custom-num-desc'] = function (a, b) {
        return b - a; 
    };

    $(document).ready(function() {
        // Cargar datos al inicio con las semanas por defecto
        cargarDatos();

        // Cuando se cambie el rango de semanas y se presione "Mostrar datos"
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            cargarDatos();
        });
    });

    function cargarDatos() {
        var fecha_inicio = $('#fecha_inicio').val();
        var fecha_fin = $('#fecha_fin').val();

        $.ajax({
            url: '{{ route("dashboarComparativaModulo.semanaComparativaGeneralData") }}',
            data: {
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
            },
            success: function(json) {
                $('#clienteTabs').empty();
                $('#clienteTabContent').empty();

                var modulosPorCliente = json.modulosPorClienteYEstilo;
                var modulosPlanta1 = json.modulosPorClienteYEstiloPlanta1;
                var modulosPlanta2 = json.modulosPorClienteYEstiloPlanta2;
                var semanas = json.semanas;

                var clientes = Object.keys(modulosPorCliente);
                if (clientes.length === 0) {
                    $('#clienteTabContent').html('<p>No hay datos para las semanas seleccionadas.</p>');
                    return;
                }

                $.each(clientes, function(index, cliente) {
                    var activeClass = index === 0 ? 'active' : '';
                    var showActiveClass = index === 0 ? 'show active' : '';

                    // Tabs de clientes
                    $('#clienteTabs').append(
                        '<li class="nav-item">' +
                            '<a class="nav-link ' + activeClass + '" id="tab-' + index + '" data-toggle="tab" href="#cliente-' + index + '" role="tab" aria-controls="cliente-' + index + '" aria-selected="' + (index === 0) + '">' +
                                cliente +
                            '</a>' +
                        '</li>'
                    );

                    var contenidoCliente = '<div class="tab-pane fade ' + showActiveClass + '" id="cliente-' + index + '" role="tabpanel" aria-labelledby="tab-' + index + '">' +
                        '<hr>' +
                        '<ul class="nav nav-pills mb-3" id="pills-tab-planta-' + index + '" role="tablist">' +
                            '<li class="nav-item">' +
                                '<a class="nav-link btn btn-secondary active" id="pills-general-tab-' + index + '" data-toggle="pill" href="#pills-general-' + index + '" role="tab" aria-controls="pills-general-' + index + '" aria-selected="true">General</a>' +
                            '</li>' +
                            '<li class="nav-item">' +
                                '<a class="nav-link btn btn-secondary" id="pills-planta1-tab-' + index + '" data-toggle="pill" href="#pills-planta1-' + index + '" role="tab" aria-controls="pills-planta1-' + index + '" aria-selected="false">Planta 1 - Ixtlahuaca</a>' +
                            '</li>' +
                            '<li class="nav-item">' +
                                '<a class="nav-link btn btn-secondary" id="pills-planta2-tab-' + index + '" data-toggle="pill" href="#pills-planta2-' + index + '" role="tab" aria-controls="pills-planta2-' + index + '" aria-selected="false">Planta 2 - San Bartolo</a>' +
                            '</li>' +
                        '</ul>' +
                        '<div class="tab-content" id="pills-tabContent-planta-' + index + '">' +
                            '<div class="tab-pane fade show active" id="pills-general-' + index + '" role="tabpanel" aria-labelledby="pills-general-tab-' + index + '"></div>' +
                            '<div class="tab-pane fade" id="pills-planta1-' + index + '" role="tabpanel" aria-labelledby="pills-planta1-tab-' + index + '"></div>' +
                            '<div class="tab-pane fade" id="pills-planta2-' + index + '" role="tabpanel" aria-labelledby="pills-planta2-tab-' + index + '"></div>' +
                        '</div>' +
                    '</div>';

                    $('#clienteTabContent').append(contenidoCliente);

                    // General
                    generarContenidoGeneral('#pills-general-' + index, cliente, modulosPorCliente[cliente], semanas);

                    // Planta 1
                    generarContenidoPlanta('#pills-planta1-' + index, cliente, modulosPlanta1[cliente] || {}, semanas, 'Planta 1');

                    // Planta 2
                    generarContenidoPlanta('#pills-planta2-' + index, cliente, modulosPlanta2[cliente] || {}, semanas, 'Planta 2');

                    // Delegar evento para resaltar el botón activo
                    $(document).on('click', '.nav-pills .nav-link', function () {
                        $(this).closest('.nav-pills').find('.nav-link').removeClass('btn-secondary active').addClass('btn-link');
                        $(this).removeClass('btn-link').addClass('btn-secondary active');
                    });

                    // Resaltar por defecto el primer botón "General"
                    $('.nav-pills .nav-link.active').removeClass('btn-link').addClass('btn-secondary');
                });
            }
        });
    }

    function generarContenidoGeneral(selector, cliente, estilosData, semanas) {
        if (!estilosData || Object.keys(estilosData).length === 0) {
            $(selector).html('<p>No hay datos para General.</p>');
            return;
        }

        // Reordenar estilos: "General" primero
        const estilosOrdenados = Object.keys(estilosData).sort((a, b) => {
            if (a.toLowerCase() === 'general') return -1; // General al inicio
            if (b.toLowerCase() === 'general') return 1;
            return a.localeCompare(b); // Orden lexicográfico para el resto
        });

        var html = '<div class="card mt-3">' +
            '<div class="card-header"><h4>Información del Cliente: ' + cliente + '</h4></div>' +
            '<div class="card-body">';

        estilosOrdenados.forEach(function(estilo) {
            html += generarSeccionEstilo(estilo, estilosData[estilo], semanas, '', 'General');
        });

        html += '</div></div>';

        $(selector).html(html);

        // Inicializar DataTables
        inicializarTablas($(selector));

        // Generar gráficas
        generarTodasLasGraficas($(selector), semanas);
    }

    function generarContenidoPlanta(selector, cliente, estilosData, semanas, plantaNombre) {
        if (!estilosData || Object.keys(estilosData).length === 0) {
            $(selector).html('<p>No hay datos para ' + plantaNombre + '.</p>');
            return;
        }

        var html = '<div class="card mt-3">' +
            '<div class="card-header"><h4>Información del Cliente: ' + cliente + '</h4></div>' +
            '<div class="card-body">';

        $.each(estilosData, function(estilo, datosEstilo) {
            var sufijo = '';
            if (plantaNombre.toLowerCase().includes('1')) {
                sufijo = 'planta1';
            } else if (plantaNombre.toLowerCase().includes('2')) {
                sufijo = 'planta2';
            }
            html += generarSeccionEstilo(estilo, datosEstilo, semanas, sufijo, plantaNombre);
        });

        html += '</div></div>';

        $(selector).html(html);

        inicializarTablas($(selector));

        // Generar gráficas
        generarTodasLasGraficas($(selector), semanas);
    }

    // Esta función genera la sección de un estilo, incluyendo las tablas y el div del gráfico.
    // Devuelve el HTML y además agrega atributos data-* con la info necesaria para las gráficas.
    function generarSeccionEstilo(estilo, datosEstilo, semanas, sufijo, titulo) {
        var indexEstilo = Math.floor(Math.random() * 1000000); // ID único
        var resumenTableId = 'tabla-resumen' + (sufijo ? '-' + sufijo : '') + '-' + indexEstilo;
        var detallesTableId = 'tabla-detalles' + (sufijo ? '-' + sufijo : '') + '-' + indexEstilo;
        var chartId = 'chart' + (sufijo ? '-' + sufijo : '') + '-' + indexEstilo;

        // Separador visual antes de cada estilo
        var html = '<hr style="border: 1px solid #ddd; margin: 30px 0;">'; // Línea horizontal
        // Preparar datos para las gráficas:
        var categories = semanas.map(s => "Semana " + s.semana + " (" + s.anio + ")");
        // Convertir "N/A" en null para las gráficas, números en su valor numérico
        function parseValue(v) {
            return (v === "N/A" || v === null || v === undefined) ? null : parseFloat(v);
        }

        var aqlData = datosEstilo.totales_aql.map(parseValue);
        var procesoData = datosEstilo.totales_proceso.map(parseValue);

        // Calcular maxY
        var allData = aqlData.concat(procesoData).filter(v => v !== null);
        var maxY = allData.length > 0 ? Math.ceil(Math.max(...allData)) + 5 : 10;

        var html = '<div class="row mt-4">' +
            '<div class="col-lg-3">' +
                '<div class="card">' +
                    '<div class="card-header"><h5>Estilo: ' + estilo + '</h5></div>' +
                    '<div class="card-header"><h5>resumen por Semana</h5></div>' +
                    '<div class="table-responsive" style="background-color: #2c2c2c; box-shadow:0px 4px 6px rgba(0,0,0,0.2); padding:15px; border-radius:8px;">' +
                        '<table class="table tablesorter" id="' + resumenTableId + '">' +
                            '<thead><tr><th>Semana</th><th>% AQL</th><th>% Proceso</th></tr></thead><tbody>';

        for (var i = 0; i < semanas.length; i++) {
            var semanaText = categories[i];
            var aql = datosEstilo.totales_aql[i];
            var proceso = datosEstilo.totales_proceso[i];
            var aqlColorClass = datosEstilo.totales_aql_colores[i] ? 'bg-rojo-oscuro' : '';
            var procesoColorClass = datosEstilo.totales_proceso_colores[i] ? 'bg-amarillo-oscuro' : '';

            html += '<tr>' +
                '<td>' + semanaText + '</td>' +
                '<td class="' + aqlColorClass + '">' + aql + '</td>' +
                '<td class="' + procesoColorClass + '">' + proceso + '</td>' +
                '</tr>';
        }

        html += '</tbody></table></div></div></div>';

        // Tarjeta del gráfico
        html += '<div class="col-lg-9">' +
            '<div class="card">' +
                '<div id="' + chartId + '" ' +
                'data-categories=\'' + JSON.stringify(categories) + '\' ' +
                'data-aql=\'' + JSON.stringify(aqlData) + '\' ' +
                'data-proceso=\'' + JSON.stringify(procesoData) + '\' ' +
                'data-maxy="' + maxY + '"' +
                'data-estilo=' + estilo + ' ' + // Nuevo atributo
                ' style="width:100%; height:500px;"></div>' +
            '</div>' +
            '</div>' +
        '</div>';

        // Tabla de detalles
        html += '<div class="card mt-3">' +
            '<div class="card-header"><h5>Estilo: ' + estilo + '</h5></div>' +
            '<div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow:0px 4px 6px rgba(0,0,0,0.2); padding:15px; border-radius:8px;">' +
                '<table class="table tablesorter" id="' + detallesTableId + '">' +
                    '<thead>' +
                        '<tr><th rowspan="2">Módulo</th>';

        for (var j = 0; j < semanas.length; j++) {
            html += '<th colspan="2" class="text-center">Semana ' + semanas[j].semana + ' <br> (' + semanas[j].anio + ')</th>';
        }
        html += '</tr><tr>';
        for (var k = 0; k < semanas.length; k++) {
            html += '<th>% AQL</th><th>% Proceso</th>';
        }
        html += '</tr></thead><tbody>';

        datosEstilo.modulos.forEach(function(moduloObj) {
            html += '<tr><td>' + moduloObj.modulo + '</td>';
            moduloObj.semanalPorcentajes.forEach(function(semanaData) {
                var aqlColor = semanaData.aql_color ? 'bg-rojo-oscuro' : '';
                var procesoColor = semanaData.proceso_color ? 'bg-amarillo-oscuro' : '';
                html += '<td class="' + aqlColor + '">' + semanaData.aql + '</td>' +
                        '<td class="' + procesoColor + '">' + semanaData.proceso + '</td>';
            });
            html += '</tr>';
        });

        html += '</tbody><tfoot><tr><th>Total</th>';
        for (var m = 0; m < semanas.length; m++) {
            var totAql = datosEstilo.totales_aql[m];
            var totProc = datosEstilo.totales_proceso[m];
            var totAqlColor = datosEstilo.totales_aql_colores[m] ? 'bg-rojo-oscuro' : '';
            var totProcColor = datosEstilo.totales_proceso_colores[m] ? 'bg-amarillo-oscuro' : '';
            html += '<td class="' + totAqlColor + '">' + totAql + '</td>' +
                    '<td class="' + totProcColor + '">' + totProc + '</td>';
        }
        html += '</tr></tfoot></table></div></div>';

        // Añadir separador al final del contenido del estilo
        html += '<hr style="border: 1px solid #ddd; margin: 30px 0;">';

        return html;
    }

    // Inicializa DataTables en las tablas recién creadas
    function inicializarTablas($container) {
        $container.find('table.tablesorter').each(function () {
            // Verificar si la tabla es la de resumen
            const isResumenTable = $(this).attr('id')?.startsWith('tabla-resumen');
            
            // Configuración personalizada para la tabla de resumen 
            $(this).DataTable({
                paging: true,
                searching: true,
                ordering: true,
                responsive: true,
                pageLength: isResumenTable ? 5 : 10, // Paginación de 5 para la tabla de resumen, 10 para las demás
                dom: 'FRtip',
                columnDefs: [
                    {
                        targets: 0,
                        type: 'string'
                    },
                    {
                        targets: '_all',
                        type: 'custom-num',
                        render: function (data, type, row) {
                            return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                        }
                    }
                ]
            });
        });
    }

    // Esta función busca todos los contenedores de gráficas en el $container y genera las gráficas progresivamente
    function generarTodasLasGraficas($container, semanas, batchSize = 3, delay = 1000) {
        const chartDivs = $container.find('div[id^="chart"]'); // Encuentra todos los contenedores de gráficas
        let currentIndex = 0;

        function renderNextBatch() {
            const batch = chartDivs.slice(currentIndex, currentIndex + batchSize); // Obtiene un lote de gráficos
            batch.each(function () {
                const $chartDiv = $(this);

                // Obtén los datos para la gráfica desde los atributos data-*
                const categories = JSON.parse($chartDiv.attr('data-categories'));
                const aqlData = JSON.parse($chartDiv.attr('data-aql'));
                const procesoData = JSON.parse($chartDiv.attr('data-proceso'));
                const maxY = parseInt($chartDiv.attr('data-maxy'), 10);
                const estilo = $chartDiv.attr('data-estilo'); // Obtener el estilo

                // Llama a la función para generar la gráfica con el estilo
                generarGrafico($chartDiv.attr('id'), categories, aqlData, procesoData, maxY, estilo);
            });

            currentIndex += batchSize;

            // Si quedan gráficas por generar, programa el siguiente lote
            if (currentIndex < chartDivs.length) {
                setTimeout(renderNextBatch, delay); // Espera un tiempo antes de procesar el siguiente lote
            }
        }

        renderNextBatch(); // Comienza a generar las gráficas
    }


    // Función para generar un gráfico Highcharts dado un contenedor e información
    function generarGrafico(containerId, categories, aqlData, procesoData, maxY, estilo) {
        Highcharts.chart(containerId, {
            chart: {
                backgroundColor: 'transparent',
                style: { fontFamily: 'Arial' }
            },
            title: {
                text: "Estilo: '" + estilo + "', Porcentaje Semanal", // Actualización del título
                style: { fontFamily: 'Arial' }
            },
            xAxis: {
                categories: categories,
                title: { text: "Semanas", style: { fontFamily: 'Arial' } },
                labels: { style: { fontFamily: 'Arial' } }
            },
            yAxis: {
                title: { text: "Porcentaje (%)", style: { fontFamily: 'Arial' } },
                min: 0,
                max: maxY,
                labels: { style: { fontFamily: 'Arial' } }
            },
            series: [
                {
                    name: "% AQL",
                    type: 'line',
                    data: aqlData,
                    color: "#28a745",
                    zIndex: 2,
                    marker: { enabled: true, radius: 4 }
                },
                {
                    name: "% Proceso",
                    type: 'column',
                    data: procesoData,
                    color: "#007bff",
                    zIndex: 1
                }
            ],
            tooltip: {
                shared: true,
                valueSuffix: "%",
                style: { fontFamily: 'Arial' }
            },
            credits: { enabled: false }
        });
    }


    </script>

@endpush
