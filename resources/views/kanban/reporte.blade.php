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
                        <option value="1">Planta 1</option>
                        <option value="2">Planta 2</option>
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
            <div class="card mb-4 p-3">
                <div id="estatusChart" style="height: 300px;"></div>
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

    <script>
        $(function() {
            // Inicializar Highcharts
            const chart = Highcharts.chart('estatusChart', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'transparent' // ← hace el fondo del gráfico invisible
                },
                title: {
                    text: 'Distribución de Estatus',
                    style: { color: '#ffffff' } // ← texto blanco
                },
                legend: {
                    itemStyle: {
                        color: '#ffffff' // ← leyenda en blanco
                    }
                },
                exporting: {
                    enabled: true
                },
                series: [{
                    name: 'Registros',
                    colorByPoint: true,
                    data: [{
                            name: 'Aceptados',
                            y: 0
                        },
                        {
                            name: 'Parciales',
                            y: 0
                        },
                        {
                            name: 'Rechazados',
                            y: 0
                        }
                    ]
                }]
            });

            // Inicializar DataTable con Buttons
            const table = $('#tabla-kanban').DataTable({
                columns: [{
                        data: 'op'
                    },
                    {
                        data: 'planta',
                        render: planta => planta == 1 ? 'Ixtlahuaca' : planta == 2 ? 'San Bartolo' : ''
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
                        render: est => est == 1 ? 'Aceptado' : est == 2 ? 'Parcial' : 'Rechazado'
                    },
                    {
                        data: 'fecha_corte',
                        render: d => moment(d).format('DD/MM/YYYY HH:mm')
                    },
                    {
                        data: 'fecha_liberacion',
                        render: d => d ? moment(d).format('DD/MM/YYYY HH:mm') : 'N/A'
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['csv', 'excel'],
                pageLength: 20 
            });

            // Función para recargar datos
            function fetchData() {
                const params = $('#filtrosForm').serialize();
                $.ajax({
                    url: '{{ route('kanban.reporte') }}',
                    data: params,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success(json) {
                        // 2. KPIs
                        $('#kpi-total-op').text(json.kpis.total_op);
                        $('#kpi-total-piezas').text(json.kpis.total_piezas);
                        $('#kpi-aceptados').text(json.kpis.aceptados);
                        $('#kpi-rechazados').text(json.kpis.rechazados);

                        // 3. Actualizar Highcharts
                        chart.series[0].setData([{
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

                        // 4. Recargar DataTable
                        table.clear().rows.add(json.registros).draw();
                    }
                });
            }

            // Captura submit
            $('#filtrosForm').on('submit', e => {
                e.preventDefault();
                fetchData();
            });

            // Carga inicial
            fetchData();
        });
    </script>
@endsection
