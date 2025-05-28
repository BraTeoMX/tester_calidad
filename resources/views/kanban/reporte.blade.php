@extends('layouts.app', ['pageSlug' => 'reporte_kanban', 'titlePage' => __('reporte_kanban')])

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
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <!-- 1. FILTROS -->
            <form id="filtrosForm" class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="desde" class="form-label">Fecha Corte Desde</label>
                    <input type="date" id="desde" name="desde" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="hasta" class="form-label">Fecha Corte Hasta</label>
                    <input type="date" id="hasta" name="hasta" class="form-control">
                </div>
                {{-- <div class="col-md-2">
                    <label for="op" class="form-label">OP</label>
                    <input type="text" id="op" name="op" class="form-control" placeholder="OP">
                </div> --}}
                <div class="col-md-2">
                    <label for="estatus" class="form-label">Estatus</label>
                    <select id="estatus" name="estatus" class="form-control">
                        <option value="">Todos</option>
                        <option value="1">Aceptado</option>
                        <option value="2">Parcial</option>
                        <option value="3">Rechazado</option>
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-verde-xd">Filtrar</button>
                </div>
            </form>

            <!-- 2. KPI CARDS -->
            <div class="row mb-4 d-flex flex-wrap">
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h6>Total OP</h6>
                        <h3 id="kpi-total-op">0</h3>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h6>Total Piezas</h6>
                        <h3 id="kpi-total-piezas">0</h3>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h6>Aceptados</h6>
                        <h3 id="kpi-aceptados">0</h3>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h6>Parciales</h6>
                        <h3 id="kpi-parciales">0</h3>
                    </div>
                </div>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h6>Rechazados</h6>
                        <h3 id="kpi-rechazados">0</h3>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <!-- Gráfico de línea de tiempo por rango -->
                <h5 style="color:#fff;">Línea de Tiempo Promedio General por Rango</h5>
                <div id="controlesFasesRango" class="mb-3"></div>
                <div id="tiempoTotalRango" class="mb-2"></div>
                <div id="graficoLineaRango" style="height: 300px;"></div>
                <div id="cantidadRegistrosRango" class="mb-2"></div>
            </div>

            <!-- 3. GRÁFICO HIGHCHARTS -->
            <div class="row mb-4">
                <!-- Pie: 1/3 -->
                <div class="col-md-4">
                    <div class="card p-3">
                        <div id="estatusChart" style="height: 300px;"></div>
                    </div>
                </div>

                <!-- Timeline: 2/3 -->
                <div class="col-md-8">
                    <div class="card p-3">
                        <div id="timelineChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="card card-body">
                <div class="accordion mt-4" id="acordeonDetalleOP">
                    <div class="card">
                        <div class="card-header" id="headingOP">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                    data-target="#detalleOP" aria-expanded="false" aria-controls="detalleOP">
                                    Análisis de Tiempos por OP
                                </button>
                            </h5>
                        </div>

                        <div id="detalleOP" class="collapse" aria-labelledby="headingOP" data-parent="#acordeonDetalleOP">
                            <div class="card-body">
                                <!-- 1) Formulario de búsqueda solo con OP -->
                                <form id="formBusquedaOP" class="row align-items-end mb-3">
                                    <div class="col-md-4">
                                        <label for="opDetalle" class="form-label">Buscar OP</label>
                                        <input type="text" id="opDetalle" class="form-control"
                                            placeholder="Ej. OP12345">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">Buscar</button>
                                    </div>
                                </form>

                                <!-- 2) Aquí inyectaremos controles, tabla y gráfico -->
                                <div id="resultadosDetalleOP"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. TABLE DATA -->
            <div class="card card-body">
                <div class="table-responsive">
                    <table id="tabla-kanban" class="table table-striped" style="width:100%">
                        <thead class="thead-primary">
                            <tr>
                                <th>OP</th>
                                <th>Planta</th>
                                <th>Cliente</th>
                                <th>Estilo</th>
                                <th>Piezas</th>
                                <th>Estatus</th>
                                <th>Fecha Corte</th>
                                <th>Fecha Almacen</th>
                                <th>Fecha Aceptado</th>
                                <th>Fecha Parcial</th>
                                <th>Fecha Rechazo</th>
                                <th>Fecha Online</th>
                                <th>Fecha Offline</th>
                                <th>Fecha Approved</th>
                                <th>Tiempo Corte - Almacén</th>
                                <th>Tiempo Almacén - Calidad</th>
                                <th>Tiempo Calidad - Producción</th>
                                <th>Tiempo Corte - Producción</th>
                                <th>Tiempo Producción - Offline</th>
                                <th>Tiempo Offline - Approved</th>
                                <th>Tiempo Corte - Approved</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modalDetalleOP" class="mi-modal">
        <div class="mi-modal-contenido">
            <span class="cerrar-modal" onclick="cerrarModalDetalleOP()">&times;</span>
            <div id="contenidoModalDetalleOP">
                <!-- Aquí se inyectará todo lo que ya generas con JS -->
            </div>
        </div>
    </div>
    
    <style>
        /* Estilo para la gráfica en modo oscuro */
        #graficoLineaTiempo {
            background-color: #111;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 10px;
        }

        /* Estilo general para etiquetas y texto */
        #resultadosDetalleOP h5,
        #resultadosDetalleOP table,
        #resultadosDetalleOP label,
        #resultadosDetalleOP p,
        #resultadosDetalleOP th,
        #resultadosDetalleOP td {
            color: #fff;
        }

        /* Checkbox personalizado */
        .custom-checkbox {
            display: flex;
            align-items: center;
            margin-right: 12px;
        }

        .custom-checkbox input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #999;
            border-radius: 3px;
            margin-right: 6px;
            cursor: pointer;
            position: relative;
            background-color: #222;
        }

        .custom-checkbox input[type="checkbox"]:checked {
            background-color: #00c853;
            border-color: #00c853;
        }

        .custom-checkbox input[type="checkbox"]::after {
            content: '';
            position: absolute;
            width: 5px;
            height: 10px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            top: 2px;
            left: 6px;
            transform: rotate(45deg);
            display: none;
        }

        .custom-checkbox input[type="checkbox"]:checked::after {
            display: block;
        }

        .custom-checkbox label {
            user-select: none;
            font-size: 14px;
            color: #eee;
        }

        .mi-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .mi-modal-contenido {
            background-color: #1a1a1a;
            margin: 60px auto;
            padding: 20px;
            border: 1px solid #555;
            width: 90%;
            max-width: 900px;
            border-radius: 10px;
            color: #fff;
        }

        .cerrar-modal {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .cerrar-modal:hover {
            color: #fff;
        }

    </style>


    <!-- DataTables CSS desde carpeta local -->
    <link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dataTable/css/buttons.bootstrap5.min.css') }}">

    <!-- jQuery y DataTables desde local -->
    <script src="{{ asset('dataTable/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Botones para exportar -->
    <script src="{{ asset('dataTable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/buttons.html5.min.js') }}"></script>
    <!-- Moment.js para fechas -->
    <script src="{{ asset('min/moment.min.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/offline-exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/no-data-to-display.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/accessibility.js') }}"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>

    <script>
        $(function() {
            // 1) Pie
            const pie = Highcharts.chart('estatusChart', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Distribución de Estatus',
                    style: {
                        color: '#fff'
                    }
                },
                legend: {
                    itemStyle: {
                        color: '#fff'
                    }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            color: '#fff',
                            style: {
                                textOutline: 'none'
                            }
                        }
                    }
                },
                exporting: {
                    enabled: true
                },
                series: [{
                    name: 'Registros',
                    colorByPoint: false, // 👈 importante para usar los colores manuales
                    data: [{
                            name: 'Aceptados',
                            y: 0,
                            color: '#27ae60' // ✅ Verde
                        },
                        {
                            name: 'Parciales',
                            y: 0,
                            color: '#e67e22' // ✅ Naranja
                        },
                        {
                            name: 'Rechazados',
                            y: 0,
                            color: '#c0392b' // ✅ Rojo
                        }
                    ]
                }]
            });

            // 2) Timeline
            const flowChart = Highcharts.chart('timelineChart', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Flujo de OP por Etapa',
                    style: {
                        color: '#fff'
                    }
                },
                xAxis: {
                    categories: ['Corte', 'Almacén', 'Resultado', 'Producción'],
                    labels: {
                        style: {
                            color: '#fff'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cantidad de OP',
                        style: {
                            color: '#fff'
                        }
                    },
                    labels: {
                        style: {
                            color: '#fff'
                        }
                    }
                },
                legend: {
                    itemStyle: {
                        color: '#fff'
                    }
                },
                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true,
                            color: '#fff'
                        }
                    }
                },
                series: [{
                        name: 'OP',
                        data: [0, 0, null, null],
                        color: '#3498db' // Azul claro
                    },
                    {
                        name: 'Aceptado',
                        data: [null, null, 0, null],
                        color: '#27ae60' // ✅ Verde
                    },
                    {
                        name: 'Parcial',
                        data: [null, null, 0, null],
                        color: '#e67e22' // ✅ Naranja
                    },
                    {
                        name: 'Rechazo',
                        data: [null, null, 0, null],
                        color: '#c0392b' // ✅ Rojo
                    },
                    {
                        name: 'Producción',
                        data: [null, null, null, 0],
                        color: '#007bff' // Azul oscuro
                    }
                ]
            });

            // 3) DataTable
            const table = $('#tabla-kanban').DataTable({
                columns: [{
                        data: 'op',
                        render: function (data, type, row) {
                            return `<button class="btn btn-sm btn-outline-info" onclick="mostrarDetalleOPDesdeTabla('${data}')">${data}</button>`;
                        }
                    },
                    {
                        data: 'planta',
                        render: p => p == 1 ? 'Ixtlahuaca' : p == 2 ? 'San Bartolo' : ''
                    },
                    {
                        data: 'cliente'
                    },
                    {
                        data: 'estilo'
                    },
                    {
                        data: 'piezas'
                    },
                    {
                        data: 'estatus',
                        render: e => e == 1 ? 'Aceptado' : e == 2 ? 'Parcial' : 'Rechazado'
                    },
                    {
                        data: 'fecha_corte',
                        render: d => moment(d).format('DD/MM/YYYY HH:mm')
                    },
                    {
                        data: 'fecha_almacen',
                        render: d => moment(d).format('DD/MM/YYYY HH:mm')
                    },
                    {
                        data: 'fecha_liberacion',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_parcial',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_rechazo',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_online',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_offline',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_approved',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: null,
                        render: row => {
                            if (!row.fecha_corte || !row.fecha_almacen) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_almacen).diff(moment(row.fecha_corte)));
                            return `${diff.days()}d ${diff.hours()}h ${diff.minutes()}m ${diff.seconds()}s`;
                        }
                    },
                    {
                        data: null,
                        render: row => {
                            if (!row.fecha_almacen || !row.fecha_liberacion) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_liberacion).diff(moment(row.fecha_almacen)));
                            return `${diff.days()}d ${diff.hours()}h ${diff.minutes()}m ${diff.seconds()}s`;
                        }
                    },
                    {
                        data: null,
                        render: row => {
                            if (!row.fecha_liberacion || !row.fecha_online) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_online).diff(moment(row.fecha_liberacion)));
                            return `${diff.days()}d ${diff.hours()}h ${diff.minutes()}m ${diff.seconds()}s`;
                        }
                    },
                    {
                        data: null,
                        render: row => {
                            if (!row.fecha_corte || !row.fecha_online) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_online).diff(moment(row.fecha_corte)));
                            return `${diff.days()}d ${diff.hours()}h ${diff.minutes()}m ${diff.seconds()}s`;
                        }
                    },
                    {
                        data: null, // Tiempo Producción - Offline
                        title: "Tiempo Prod-Offline", // Título opcional para exportaciones si el HTML no lo tiene
                        render: function(data, type, row) {
                            if (!row.fecha_online || !row.fecha_offline) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_offline).diff(moment(row.fecha_online)));
                            if (diff.asMilliseconds() < 0) return 'Error'; // Fechas en orden incorrecto
                            return `${Math.floor(diff.asDays())}d ${diff.hours()}h ${diff.minutes()}m`;
                        }
                    },
                    {
                        data: null, // Tiempo Offline - Approved
                        title: "Tiempo Offline-Approved",
                        render: function(data, type, row) {
                            if (!row.fecha_offline || !row.fecha_approved) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_approved).diff(moment(row.fecha_offline)));
                            if (diff.asMilliseconds() < 0) return 'Error';
                            return `${Math.floor(diff.asDays())}d ${diff.hours()}h ${diff.minutes()}m`;
                        }
                    },
                    {
                        data: null, // Tiempo Corte - Approved
                        title: "Tiempo Corte-Approved",
                        render: function(data, type, row) {
                            if (!row.fecha_corte || !row.fecha_approved) return 'N/A';
                            const diff = moment.duration(moment(row.fecha_approved).diff(moment(row.fecha_corte)));
                            if (diff.asMilliseconds() < 0) return 'Error';
                            return `${Math.floor(diff.asDays())}d ${diff.hours()}h ${diff.minutes()}m`;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['csv', 'excel'],
                pageLength: 20
            });

            // 4) Fetch y actualización
            function fetchData() {
                const params = $('#filtrosForm').serialize();
                $.ajax({
                    url: "{{ route('kanban.reporte') }}",
                    data: params,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success(json) {
                        // 1. Actualizar KPIs
                        $('#kpi-total-op').text(json.kpis.total_op);
                        $('#kpi-total-piezas').text(json.kpis.total_piezas);
                        $('#kpi-aceptados').text(json.kpis.aceptados);
                        $('#kpi-parciales').text(json.kpis.parciales);
                        $('#kpi-rechazados').text(json.kpis.rechazados);

                        // 2. Actualizar pie chart
                        pie.series[0].setData([{
                                name: 'Aceptados',
                                y: json.kpis.aceptados
                            },
                            {
                                name: 'Parciales',
                                y: json.kpis.parciales
                            },
                            {
                                name: 'Rechazados',
                                y: json.kpis.rechazados
                            }
                        ]);

                        // 3. Calcular datos para el stacked column (“flowChart”)
                        const total = json.kpis.total_op; // Corte
                        const almacen = json.registros.filter(r => r.fecha_almacen).length;
                        const liberacion = json.kpis.aceptados; // estatus 1
                        const parciales = json.kpis.parciales; // estatus 2
                        const rechazados = json.kpis.rechazados; // estatus 3
                        const produccion = json.produccion || 0;

                        // 4. Actualizar stacked column chart
                        flowChart.series[0].setData([total, almacen, null]); // OP
                        flowChart.series[1].setData([null, null, liberacion]); // Aceptado
                        flowChart.series[2].setData([null, null, parciales]); // Parcial
                        flowChart.series[3].setData([null, null, rechazados]); // Rechazo
                        flowChart.series[4].setData([null, null, null, produccion]);

                        // 5. Refrescar DataTable
                        table.clear()
                            .rows.add(json.registros)
                            .draw();

                        dibujarGraficoLineaRango(json);
                    }
                });
            }

            // Bind y carga inicial
            $('#filtrosForm').on('submit', e => {
                e.preventDefault();
                fetchData();
            });
            fetchData();
        });
    </script>

    <script>
        function obtenerEtiqueta(campo) {
            const etiquetas = {
                'fecha_corte': 'Corte',
                'fecha_almacen': 'Almacén',
                'fecha_liberacion': 'Aceptado',
                'fecha_parcial': 'Parcial',
                'fecha_rechazo': 'Rechazo',
                'fecha_online': 'Producción',
                'fecha_offline': 'Offline',
                'fecha_approved': 'Approved'
            };
            return etiquetas[campo] || campo;
        }

        function construirTablaFechas(data) {
            const filas = [
                ['Corte', data.fecha_corte],
                ['Almacén', data.fecha_almacen],
                ['Aceptado', data.fecha_liberacion],
                ['Parcial', data.fecha_parcial],
                ['Rechazo', data.fecha_rechazo],
                ['Producción', data.fecha_online],
                ['Offline', data.fecha_offline],
                ['Approved', data.fecha_approved]
            ];

            let html = `
                    <div class="table-responsive">
                    <table class="table table-bordered table-sm bg-dark">
                        <thead>
                        <tr><th>Etapa</th><th>Fecha y Hora</th></tr>
                        </thead>
                        <tbody>
                    `;

            filas.forEach(([etapa, valor]) => {
                // Solo mostrar filas si la etapa tiene un valor o es una de las etapas principales (opcional)
                // Si quieres que siempre aparezcan aunque no tengan fecha, elimina la condición `|| valor`
                // o ajusta qué etapas siempre deben mostrarse. Para este ejemplo, se mostrarán si hay valor.
                if (valor || ['Corte', 'Almacén', 'Aceptado', 'Producción', 'Offline', 'Approved'].includes(etapa)) {
                    html += `
                    <tr>
                        <td>${etapa}</td>
                        <td>${valor ? moment(valor).format('DD/MM/YYYY HH:mm:ss') : 'N/A'}</td>
                    </tr>
                    `;
                }
            });

            html += `
                        </tbody>
                    </table>
                    </div>
                    `;
            return html;
        }

        function formatearTiempo(ms) {
            if (ms < 0) ms = 0; // Asegurar que no haya tiempos negativos si las fechas están en orden incorrecto
            const minutos = Math.floor(ms / 60000);
            if (minutos === 0 && ms > 0) return `${Math.floor(ms/1000)}s`; // Mostrar segundos si es menos de un minuto
            if (minutos < 1) return '0m'; // Si es 0ms o muy poco
            if (minutos < 60) return `${minutos}m`; // Corrección: 'min' a 'm' para consistencia
            const horas = Math.floor(minutos / 60);
            const mins = minutos % 60;
            if (horas < 24) return `${horas}h ${mins}m`;
            const dias = Math.floor(horas / 24);
            const horasRestantes = horas % 24;
            return `${dias}d ${horasRestantes}h ${mins}m`;
        }


        function dibujarGraficoLinea(data) {
            const fasesSeleccionadas = [];
            document.querySelectorAll('.fase-checkbox').forEach(chk => {
                if (chk.checked) fasesSeleccionadas.push(chk.value);
            });

            const puntos = fasesSeleccionadas
                .map(campo => {
                    const fecha = data[campo];
                    if (!fecha) return null;
                    return {
                        x: new Date(fecha).getTime(),
                        y: 0,
                        name: obtenerEtiqueta(campo),
                        campo // Mantener el campo original para ordenar
                    };
                })
                .filter(p => p !== null);

            // ✅ NUEVO: Incluir los nuevos indicadores en el orden deseado
            // Ajusta este orden según la secuencia lógica de tu proceso
            const orden = ['fecha_corte', 'fecha_almacen', 'fecha_liberacion', 'fecha_parcial', 'fecha_rechazo', 'fecha_online', 'fecha_offline', 'fecha_approved'];
            puntos.sort((a, b) => {
                // Si alguna fase no está en 'orden', se puede poner al final o manejar como error
                const indexA = orden.indexOf(a.campo);
                const indexB = orden.indexOf(b.campo);
                if (indexA === -1) return 1; // a después si no está en orden
                if (indexB === -1) return -1; // b después si no está en orden
                return indexA - indexB;
            });


            const seriesData = puntos.map(p => ({ x: p.x, y: p.y, name: p.name }));

            let tiempoTotal = '';
            if (puntos.length >= 2) {
                const diffMs = puntos[puntos.length - 1].x - puntos[0].x;

                // Reutilizando la lógica mejorada de formatearTiempo para el tiempo total
                // Si quieres el formato anterior detallado, puedes revertir esta parte.
                tiempoTotal = `
                    <p>
                        <strong>Tiempo total (primera a última fase seleccionada):</strong><br>
                        ${formatearTiempo(diffMs)}
                    </p>
                `;
            }

            const etiquetasIntermedias = [];
            for (let i = 0; i < puntos.length - 1; i++) {
                const p1 = puntos[i];
                const p2 = puntos[i + 1];
                const tiempoMs = p2.x - p1.x;
                if (tiempoMs >= 0) { // Solo mostrar si el tiempo es positivo o cero
                    etiquetasIntermedias.push({
                        x: (p1.x + p2.x) / 2,
                        y: 0,
                        dataLabels: [{
                            enabled: true,
                            useHTML: true,
                            formatter: function () {
                                return `<span style="color:#fff;background:#333;padding:2px 6px;border-radius:4px;font-size:11px;">
                                        ${formatearTiempo(tiempoMs)}</span>`;
                            },
                            style: { color: '#fff' },
                            align: 'center',
                            verticalAlign: 'bottom',
                            y: -10
                        }],
                        marker: { enabled: false }
                    });
                }
            }

            Highcharts.chart('graficoLineaTiempo', {
                chart: {
                    type: 'line',
                    backgroundColor: '#111'
                },
                title: {
                    text: 'Línea de Tiempo de OP',
                    style: { color: '#fff' }
                },
                xAxis: {
                    type: 'datetime',
                    title: { text: 'Fecha y Hora', style: { color: '#fff' } },
                    labels: { style: { color: '#fff' } }
                },
                yAxis: {
                    title: { text: '' },
                    labels: { enabled: false },
                    gridLineWidth: 0
                },
                tooltip: {
                    backgroundColor: '#222',
                    style: { color: '#fff' },
                    pointFormat: '{point.name}: <b>{point.x:%d/%m/%Y %H:%M}</b>'
                },
                legend: { enabled: false },
                series: [
                    {
                        name: 'Trazabilidad',
                        data: seriesData,
                        marker: {
                            enabled: true,
                            radius: 4,
                            fillColor: '#00e676'
                        },
                        lineWidth: 2,
                        color: '#00e676'
                    },
                    {
                        name: 'Intervalos',
                        type: 'scatter',
                        data: etiquetasIntermedias,
                        enableMouseTracking: false,
                        marker: { enabled: false }
                    }
                ]
            });

            document.getElementById('tiempoTotal').innerHTML = tiempoTotal;
        }

        document.getElementById('formBusquedaOP').addEventListener('submit', function(e) {
            e.preventDefault();
            const op = document.getElementById('opDetalle').value.trim();
            if (!op) return;

            $.ajax({
                url: "{{ route('kanban.buscar.op') }}", // Asegúrate que esta ruta es correcta
                method: 'GET',
                data: {
                    op
                },
                dataType: 'json',
                success: function(responseData) { // Renombrado 'data' a 'responseData' para claridad
                    if (!responseData || !responseData.op) { // Usar responseData aquí
                        document.getElementById('resultadosDetalleOP').innerHTML =
                            `<div class="alert alert-warning">No se encontró la OP.</div>`;
                        return;
                    }

                    // ✅ NUEVO: Incluir los nuevos indicadores en la lista para los checkboxes
                    const todasLasFases = ['corte', 'almacen', 'liberacion', 'parcial', 'rechazo', 'online', 'offline', 'approved'];

                    const controles = `
                        <div id="controlesFases" class="mb-3">
                        <label><strong style="color: #eee;">Mostrar fases:</strong></label>
                        <div class="d-flex flex-wrap mt-2">
                            ${todasLasFases.map(key => {
                                const campo = 'fecha_' + key;
                                const label = obtenerEtiqueta(campo);
                                // Verificar si el campo existe en los datos para marcarlo por defecto (opcional)
                                // Por ahora, todos se marcan por defecto si la fase existe en `todasLasFases`
                                const checked = responseData[campo] ? 'checked' : ''; // Marcar solo si hay fecha
                                // O simplemente 'checked' para todas por defecto: const checked = 'checked';
                                return `
                                    <div class="custom-checkbox mr-2 mb-1"> <input type="checkbox" class="fase-checkbox" id="chk_${key}" value="${campo}" ${checked}>
                                        <label for="chk_${key}" class="ml-1">${label}</label> </div>
                                    `;
                            }).join('')}
                        </div>
                        </div>
                        `;

                    const tabla = construirTablaFechas(responseData); // Usar responseData aquí
                    const indicadorTiempo = `<div id="tiempoTotal" class="mb-2"></div>`;
                    const grafico =
                        `<div id="graficoLineaTiempo" class="mt-3" style="height: 300px;"></div>`;

                    document.getElementById('resultadosDetalleOP').innerHTML =
                        `<h5 style="color:#fff;">Detalle de OP: ${responseData.op}</h5>` + // Usar responseData aquí
                        controles +
                        tabla +
                        indicadorTiempo +
                        grafico;

                    dibujarGraficoLinea(responseData); // Usar responseData aquí

                    document.querySelectorAll('.fase-checkbox').forEach(chk =>
                        chk.addEventListener('change', () => dibujarGraficoLinea(responseData)) // Usar responseData aquí
                    );
                },
                error: function(jqXHR, textStatus, errorThrown) { // Manejo básico de errores AJAX
                    console.error("Error en la búsqueda de OP:", textStatus, errorThrown);
                    document.getElementById('resultadosDetalleOP').innerHTML =
                        `<div class="alert alert-danger">Error al buscar la OP. Por favor, inténtelo más tarde.</div>`;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('keydown', function (event) {
            const modal = document.getElementById('modalDetalleOP');
            if (event.key === 'Escape' && modal.style.display === 'block') {
                cerrarModalDetalleOP();
            }
        });

        function abrirModalDetalleOP(contenidoHTML) {
            document.getElementById('contenidoModalDetalleOP').innerHTML = contenidoHTML;
            document.getElementById('modalDetalleOP').style.display = 'block';
        }

        function cerrarModalDetalleOP() {
            document.getElementById('modalDetalleOP').style.display = 'none';
        }

        function mostrarDetalleOPDesdeTabla(op) {
            $.ajax({
                url: "{{ route('kanban.buscar.op') }}",
                method: 'GET',
                data: { op },
                dataType: 'json',
                success: function(data) {
                    if (!data || !data.op) {
                        abrirModalDetalleOP(`<div class="alert alert-warning">No se encontró la OP.</div>`);
                        return;
                    }

                    const controles = `
                        <div id="controlesFases" class="mb-3">
                            <label><strong style="color: #eee;">Mostrar fases:</strong></label>
                            <div class="d-flex flex-wrap mt-2">
                                ${['corte', 'almacen', 'liberacion', 'parcial', 'rechazo', 'online', 'offline', 'approved'].map(key => {
                                    const campo = 'fecha_' + key;
                                    const label = obtenerEtiqueta(campo);
                                    return `
                                        <div class="custom-checkbox mr-3 mb-2">
                                            <input type="checkbox" class="fase-checkbox" id="chk_${key}" value="${campo}" checked>
                                            <label for="chk_${key}" class="ml-1">${label}</label>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;

                    const tabla = construirTablaFechas(data);
                    const indicadorTiempo = `<div id="tiempoTotal" class="mb-2"></div>`;
                    const grafico = `<div id="graficoLineaTiempo" class="mt-3" style="height: 300px;"></div>`;

                    abrirModalDetalleOP(
                        `<h5 style="color:#fff;">Detalle de OP: ${data.op}</h5>` +
                        controles + tabla + indicadorTiempo + grafico
                    );

                    dibujarGraficoLinea(data);

                    document.querySelectorAll('.fase-checkbox').forEach(chk =>
                        chk.addEventListener('change', () => dibujarGraficoLinea(data))
                    );
                }
            });
        }
    </script>

    <script>
        function dibujarGraficoLineaRango(json) {
            const registros = json.registros;
            const fases = ['fecha_corte', 'fecha_almacen', 'fecha_liberacion', 'fecha_parcial', 'fecha_rechazo', 'fecha_online', 'fecha_offline', 'fecha_approved'];
            const etiquetas = {
                'fecha_corte': 'Corte',
                'fecha_almacen': 'Almacén',
                'fecha_liberacion': 'Aceptado',
                'fecha_parcial': 'Parcial',
                'fecha_rechazo': 'Rechazo',
                'fecha_online': 'Producción',
                'fecha_offline': 'Offline',
                'fecha_approved': 'Approved'
            };

            function construirControles() {
                const html = `
                    <label><strong style="color: #eee;">Mostrar fases:</strong></label>
                    <div class="d-flex flex-wrap mt-2">
                        ${fases.map(f => `
                            <div class="custom-checkbox mr-3 mb-2">
                                <input type="checkbox" class="fase-checkbox-rango" id="chk_rango_${f}" value="${f}" checked>
                                <label for="chk_rango_${f}" class="ml-1">${etiquetas[f]}</label>
                            </div>
                        `).join('')}
                    </div>
                `;
                document.getElementById('controlesFasesRango').innerHTML = html;

                document.querySelectorAll('.fase-checkbox-rango').forEach(chk =>
                    chk.addEventListener('change', recalcularYdibujar)
                );
            }

            function recalcularYdibujar() {
                const fasesSeleccionadas = fases.filter(f => document.getElementById('chk_rango_' + f)?.checked);
                const tiemposAcumulados = [];
                let registrosValidos = 0;

                if (fasesSeleccionadas.length > 0) {
                    registrosValidos = registros.filter(r =>
                        fasesSeleccionadas.some(f => !!r[f])
                    ).length;
                }

                for (let i = 0; i < fasesSeleccionadas.length - 1; i++) {
                    const fase1 = fasesSeleccionadas[i];
                    const fase2 = fasesSeleccionadas[i + 1];
                    let sumaMs = 0;
                    let conteo = 0;

                    registros.forEach(r => {
                        if (r[fase1] && r[fase2]) {
                            const f1 = new Date(r[fase1]);
                            const f2 = new Date(r[fase2]);
                            const diff = f2 - f1;
                            if (diff > 0) {
                                sumaMs += diff;
                                conteo++;
                            }
                        }
                    });

                    tiemposAcumulados.push({
                        nombre: `${etiquetas[fase1]} → ${etiquetas[fase2]}`,
                        tiempoMs: sumaMs,
                        promedioMs: conteo ? Math.floor(sumaMs / conteo) : 0,
                        conteo
                    });
                }

                const seriesData = fasesSeleccionadas.map((f, i) => ({
                    x: i,
                    y: 0,
                    name: etiquetas[f]
                }));

                const etiquetasIntermedias = tiemposAcumulados.map((t, i) => ({
                    x: i + 0.5,
                    y: 0,
                    dataLabels: [{
                        enabled: true,
                        useHTML: true,
                        formatter: function () {
                            return `<span style="color:#fff;background:#333;padding:2px 6px;border-radius:4px;font-size:11px;">
                                        ${formatearTiempo(t.promedioMs)}</span>`;
                        },
                        style: { color: '#fff' },
                        align: 'center',
                        verticalAlign: 'bottom',
                        y: -10
                    }],
                    marker: { enabled: false }
                }));

                Highcharts.chart('graficoLineaRango', {
                    chart: {
                        type: 'line',
                        backgroundColor: '#111'
                    },
                    title: { text: '', style: { color: '#fff' } },
                    xAxis: {
                        categories: fasesSeleccionadas.map(f => etiquetas[f]),
                        labels: { style: { color: '#fff' } }
                    },
                    yAxis: {
                        title: { text: '' },
                        labels: { enabled: false },
                        gridLineWidth: 0
                    },
                    tooltip: {
                        backgroundColor: '#222',
                        style: { color: '#fff' },
                        pointFormat: '{point.name}'
                    },
                    legend: { enabled: false },
                    series: [
                        {
                            name: 'Fases',
                            data: seriesData,
                            marker: {
                                enabled: true,
                                radius: 4,
                                fillColor: '#00e676'
                            },
                            lineWidth: 2,
                            color: '#00e676'
                        },
                        {
                            name: 'Intervalos',
                            type: 'scatter',
                            data: etiquetasIntermedias,
                            enableMouseTracking: false,
                            marker: { enabled: false }
                        }
                    ]
                });

                const totalMs = tiemposAcumulados.reduce((acc, t) => acc + t.tiempoMs, 0);
                document.getElementById('tiempoTotalRango').innerHTML = `
                    <p><strong>Tiempo acumulado total:</strong> ${formatearTiempo(totalMs)}</p>
                `;

                document.getElementById('cantidadRegistrosRango').innerHTML = `
                    <p><strong>Registros considerados en el análisis:</strong> ${registrosValidos}</p>
                `;
            }

            // Asegúrate de que la función formatearTiempo exista y esté definida en tu script.
            // Ejemplo de función formatearTiempo (debes adaptarla a tus necesidades):
            function formatearTiempo(ms) {
                if (ms === 0) return '0s';
                const segundos = Math.floor((ms / 1000) % 60);
                const minutos = Math.floor((ms / (1000 * 60)) % 60);
                const horas = Math.floor((ms / (1000 * 60 * 60)) % 24);
                const dias = Math.floor(ms / (1000 * 60 * 60 * 24));

                let str = "";
                if (dias > 0) str += dias + "d ";
                if (horas > 0) str += horas + "h ";
                if (minutos > 0) str += minutos + "m ";
                if (segundos > 0 || str === "") str += segundos + "s";
                return str.trim();
            }


            construirControles();
            recalcularYdibujar();
        }
    </script>
@endsection
