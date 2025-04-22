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
                <div class="col-md-2">
                    <label for="op" class="form-label">OP</label>
                    <input type="text" id="op" name="op" class="form-control" placeholder="OP">
                </div>
                <div class="col-md-2">
                    <label for="planta" class="form-label">Planta</label>
                    <select id="planta" name="planta" class="form-control">
                        <option value="">Todas</option>
                        <option value="1">Planta 1</option>
                        <option value="2">Planta 2</option>
                    </select>
                </div>
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
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h6>Total OP</h6>
                        <h3 id="kpi-total-op">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h6>Total Piezas</h6>
                        <h3 id="kpi-total-piezas">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h6>Aceptados</h6>
                        <h3 id="kpi-aceptados">0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3 text-center">
                        <h6>Rechazados</h6>
                        <h3 id="kpi-rechazados">0</h3>
                    </div>
                </div>
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
                                <th>Fecha Liberación</th>
                                <th>Fecha Online</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
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
                        name: 'Liberación',
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
                        data: 'op'
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
                        data: 'fecha_liberacion',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    },
                    {
                        data: 'fecha_online',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
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
                        flowChart.series[1].setData([null, null, liberacion]); // Liberación
                        flowChart.series[2].setData([null, null, parciales]); // Parcial
                        flowChart.series[3].setData([null, null, rechazados]); // Rechazo
                        flowChart.series[4].setData([null, null, null, produccion]);

                        // 5. Refrescar DataTable
                        table.clear()
                            .rows.add(json.registros)
                            .draw();
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
                'fecha_liberacion': 'Liberación',
                'fecha_parcial': 'Parcial',
                'fecha_rechazo': 'Rechazo',
                'fecha_online': 'Producción'
            };
            return etiquetas[campo] || campo;
        }

        function construirTablaFechas(data) {
            const filas = [
                ['Corte', data.fecha_corte],
                ['Almacén', data.fecha_almacen],
                ['Liberación', data.fecha_liberacion],
                ['Parcial', data.fecha_parcial],
                ['Rechazo', data.fecha_rechazo],
                ['Producción', data.fecha_online]
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
                            html += `
                    <tr>
                        <td>${etapa}</td>
                        <td>${valor ? moment(valor).format('DD/MM/YYYY HH:mm:ss') : 'N/A'}</td>
                    </tr>
                    `;
                        });

                        html += `
                        </tbody>
                    </table>
                    </div>
                `;
            return html;
        }

        function formatearTiempo(ms) {
            const minutos = Math.floor(ms / 60000);
            if (minutos < 60) return `${minutos} min`;
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
                        campo
                    };
                })
                .filter(p => p !== null);

            const orden = ['fecha_corte', 'fecha_almacen', 'fecha_liberacion', 'fecha_parcial', 'fecha_rechazo', 'fecha_online'];
            puntos.sort((a, b) => orden.indexOf(a.campo) - orden.indexOf(b.campo));

            const seriesData = puntos.map(p => ({ x: p.x, y: p.y, name: p.name }));

            let tiempoTotal = '';
            if (puntos.length >= 2) {
                const mins = Math.floor((puntos[puntos.length - 1].x - puntos[0].x) / 60000);
                tiempoTotal = `<p><strong>Tiempo total:</strong> ${Math.floor(mins / 60)}h ${mins % 60}m</p>`;
            }

            // Nueva serie para mostrar los tiempos entre puntos
            const etiquetasIntermedias = [];
            for (let i = 0; i < puntos.length - 1; i++) {
                const p1 = puntos[i];
                const p2 = puntos[i + 1];
                const tiempoMs = p2.x - p1.x;
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
                url: "{{ route('kanban.buscar.op') }}",
                method: 'GET',
                data: {
                    op
                },
                dataType: 'json',
                success: function(data) {
                    if (!data || !data.op) {
                        document.getElementById('resultadosDetalleOP').innerHTML =
                            `<div class="alert alert-warning">No se encontró la OP.</div>`;
                        return;
                    }

                    const controles = `
                        <div id="controlesFases" class="mb-3">
                        <label><strong style="color: #eee;">Mostrar fases:</strong></label>
                        <div class="d-flex flex-wrap mt-2">
                            ${['corte', 'almacen', 'liberacion', 'parcial', 'rechazo', 'online'].map(key => {
                            const campo = 'fecha_' + key;
                            const label = obtenerEtiqueta(campo);
                            return `
                                    <div class="custom-checkbox">
                                    <input type="checkbox" class="fase-checkbox" id="chk_${key}" value="${campo}" checked>
                                    <label for="chk_${key}">${label}</label>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        </div>
                    `;

                    const tabla = construirTablaFechas(data);
                    const indicadorTiempo = `<div id="tiempoTotal" class="mb-2"></div>`;
                    const grafico =
                        `<div id="graficoLineaTiempo" class="mt-3" style="height: 300px;"></div>`;

                    document.getElementById('resultadosDetalleOP').innerHTML =
                        `<h5 style="color:#fff;">Detalle de OP: ${data.op}</h5>` +
                        controles +
                        tabla +
                        indicadorTiempo +
                        grafico;

                    dibujarGraficoLinea(data);

                    document.querySelectorAll('.fase-checkbox').forEach(chk =>
                        chk.addEventListener('change', () => dibujarGraficoLinea(data))
                    );
                }
            });
        });
    </script>

@endsection
