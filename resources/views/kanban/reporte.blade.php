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
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }

        .texto-blanco {
            color: white !important;
        }

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
        // Mapea campo → etiqueta legible
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

        // Construye la tabla de trazabilidad
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
          <table class="table table-bordered table-sm">
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

        // Dibuja la línea de tiempo como gráfico de línea conectada
        function dibujarGraficoLinea(data) {
            // 1) ¿Qué fases están seleccionadas?
            const fasesSeleccionadas = [];
            document.querySelectorAll('.fase-checkbox').forEach(chk => {
                if (chk.checked) fasesSeleccionadas.push(chk.value);
            });

            // 2) Preparamos los puntos con tiempo (x=timestamp, y=0)
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

            // 3) Ordenamos según el flujo deseado
            const orden = ['fecha_corte', 'fecha_almacen', 'fecha_liberacion', 'fecha_parcial', 'fecha_rechazo',
                'fecha_online'
            ];
            puntos.sort((a, b) => orden.indexOf(a.campo) - orden.indexOf(b.campo));

            // 4) Extraemos solo los datos para Highcharts
            const seriesData = puntos.map(p => ({
                x: p.x,
                y: p.y,
                name: p.name
            }));

            // 5) Calculamos tiempo total (primer → último)
            let tiempoTotal = '';
            if (puntos.length >= 2) {
                const mins = Math.floor((puntos[puntos.length - 1].x - puntos[0].x) / 60000);
                tiempoTotal = `<p><strong>Tiempo total:</strong> ${Math.floor(mins/60)}h ${mins%60}m</p>`;
            }

            // 6) Renderizamos Highcharts
            Highcharts.chart('graficoLineaTiempo', {
                chart: {
                    type: 'line',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Línea de Tiempo de OP',
                    style: {
                        color: '#333'
                    }
                },
                xAxis: {
                    type: 'datetime',
                    title: {
                        text: 'Fecha y Hora'
                    }
                },
                yAxis: {
                    title: {
                        text: ''
                    },
                    labels: {
                        enabled: false
                    },
                    gridLineWidth: 0
                },
                tooltip: {
                    pointFormat: '{point.name}: <b>{point.x:%d/%m/%Y %H:%M}</b>'
                },
                legend: {
                    enabled: false
                },
                series: [{
                    data: seriesData,
                    marker: {
                        enabled: true,
                        radius: 4
                    },
                    lineWidth: 2
                }]
            });

            // 7) Actualizamos indicador de tiempo total
            document.getElementById('tiempoTotal').innerHTML = tiempoTotal;
        }

        // Cuando el usuario envíe el formulario...
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

                    // 1) Controles de checkboxes para mostrar/ocultar fases
                    const controles = `
            <div id="controlesFases" class="mb-3">
              <label class="mr-2"><strong>Mostrar fases:</strong></label>
              ${['corte','almacen','liberacion','parcial','rechazo','online'].map(key => {
                const campo = 'fecha_' + key;
                const label = obtenerEtiqueta(campo);
                return `
                      <div class="form-check form-check-inline">
                        <input class="form-check-input fase-checkbox" type="checkbox"
                               id="chk_${key}" value="${campo}" checked>
                        <label class="form-check-label" for="chk_${key}">${label}</label>
                      </div>
                    `;
              }).join('')}
            </div>
          `;

                    // 2) Tabla de trazabilidad
                    const tabla = construirTablaFechas(data);

                    // 3) Contenedor para tiempo total
                    const indicadorTiempo = `<div id="tiempoTotal" class="mb-2"></div>`;

                    // 4) Contenedor de gráfico
                    const grafico = `<div id="graficoLineaTiempo" style="height: 300px;"></div>`;

                    // Inyectamos todo junto
                    document.getElementById('resultadosDetalleOP').innerHTML =
                        `<h5>Detalle de OP: ${data.op}</h5>` +
                        controles +
                        tabla +
                        indicadorTiempo +
                        grafico;

                    // 5) Dibujamos por primera vez
                    dibujarGraficoLinea(data);

                    // 6) Cada vez que cambie un checkbox, redraw
                    document.querySelectorAll('.fase-checkbox').forEach(chk =>
                        chk.addEventListener('change', () => dibujarGraficoLinea(data))
                    );
                }
            });
        });
    </script>

@endsection
