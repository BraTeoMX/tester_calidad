@extends('layouts.app', ['pageSlug' => 'reporte_auditoria_corte', 'titlePage' => __('Reporte Auditoría de Corte')])

@section('content')
{{-- Mensajes de sesión --}}
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<style>
    .alert-success {
        background-color: #32CD32;
        color: white;
        padding: 20px;
        border-radius: 15px;
        font-size: 16px;
    }

    .alert-danger {
        background-color: #dc3545;
        color: white;
        padding: 20px;
        border-radius: 15px;
        font-size: 16px;
    }

    .is-invalid {
        border-color: #dc3545;
        background-color: #ffe6e6;
    }
</style>

<div class="content">
    <div class="container-fluid">
        <!-- 1. FILTROS -->
        <form id="filtrosForm" class="row g-3 mb-4">
            <div class="col-md-3">
                <label for="desde" class="form-label">Fecha Desde</label>
                <input type="date" id="desde" name="desde" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="hasta" class="form-label">Fecha Hasta</label>
                <input type="date" id="hasta" name="hasta" class="form-control">
            </div>
            <div class="col-md-3">
                <label for="op" class="form-label">OP</label>
                <input type="text" id="op" name="op" class="form-control" placeholder="Buscar por OP">
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
                <button type="submit" class="btn btn-primary">Filtrar</button>
                {{--<button type="button" class="btn btn-warning" onclick="cargarDatosPrueba()">Datos de Prueba</button>
                <button type="button" class="btn btn-success" onclick="exportarExcel()">Exportar Excel</button>--}}
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
                    <h6>Concentración Promedio</h6>
                    <h3 id="kpi-concentracion">0%</h3>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                <div class="card p-3 text-center h-100">
                    <h6>Defectos Críticos</h6>
                    <h3 id="kpi-defectos-criticos">0</h3>
                </div>
            </div>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-3">
                <div class="card p-3 text-center h-100">
                    <h6>Eficiencia General</h6>
                    <h3 id="kpi-eficiencia">0%</h3>
                </div>
            </div>
        </div>

        <!-- 3. GRÁFICOS HIGHCHARTS -->
        <div class="row mb-4">
            <!-- Gráfico de Concentración por OP -->
            <div class="col-md-6">
                <div class="card p-3">
                    <div id="concentracionChart" style="height: 300px;"></div>
                </div>
            </div>

            <!-- Gráfico de Distribución de Estatus -->
            <div class="col-md-6">
                <div class="card p-3">
                    <div id="estatusChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>

        <!-- 4. TABLA DE DATOS -->
        <div class="card card-body">
            <div class="table-responsive">
                <table id="tabla-reporte" class="table table-striped" style="width:100%">
                    <thead class="thead-primary">
                        <tr>
                            <th>OP</th>
                            <th>Evento</th>
                            <th>Estilo</th>
                            <th>Cliente</th>
                            <th>Color</th>
                            <th>Estatus Actual</th>
                            {{--<th>Estatus Avanzado</th>--}}
                            <th>Concentración</th>
                            <th>Defectos</th>
                            <th>Piezas</th>
                            <th>Yarda Orden</th>
                            <th>Material</th>
                            <th>Bultos</th>
                            <th>Fecha Creación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <!-- 5. MODAL PARA DETALLES DE OP -->
        <div id="modalDetalleOP" class="modal-custom" style="display: none;">
            <div class="modal-content-custom">
                <div class="modal-header-custom">
                    <span class="close-modal" onclick="cerrarModal()">&times;</span>
                    <h3 id="modalTitulo">Detalles de OP</h3>
                </div>
                <div class="modal-body-custom">
                    <div id="modalContenido">
                        <!-- Aquí se cargarán los detalles -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-custom {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        overflow-y: auto;
    }

    .modal-content-custom {
        background-color: #1a1a1a;
        margin: 60px auto;
        padding: 20px;
        width: 90%;
        max-width: 800px;
        border-radius: 10px;
        color: #fff;
    }

    .modal-header-custom {
        position: relative;
        padding-bottom: 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #444;
    }

    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-modal:hover {
        color: #fff;
    }

    .modal-body-custom {
        max-height: 70vh;
        overflow-y: auto;
    }
</style>

<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" href="{{ asset('dataTable/css/buttons.bootstrap5.min.css') }}">

<!-- jQuery y DataTables -->
<script src="{{ asset('dataTable/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('dataTable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('dataTable/js/dataTables.bootstrap5.min.js') }}"></script>

<!-- Botones para exportar -->
<script src="{{ asset('dataTable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('dataTable/js/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('dataTable/js/jszip.min.js') }}"></script>
<script src="{{ asset('dataTable/js/buttons.html5.min.js') }}"></script>

<!-- Highcharts -->
<script src="{{ asset('js/highcharts/12/highcharts.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/exporting.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/offline-exporting.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/no-data-to-display.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/accessibility.js') }}"></script>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Definir variable table en scope global
    let table;

    $(document).ready(function() {
            // Inicializar DataTable
            table = $('#tabla-reporte').DataTable({
                columns: [
                    { data: 'orden_id' },
                    { data: 'evento' },
                    { data: 'estilo_id' },
                    { data: 'cliente_id' },
                    { data: 'color_id' },
                    { data: 'estatus_actual' },
                    //{ data: 'estatus_avanzado' },
                    {
                        data: 'concentracion',
                        render: function(data) {
                            return data !== 'N/A' ? data + '%' : 'N/A';
                        }
                    },
                    { data: 'defectos' },
                    { data: 'total_piezas' },
                    { data: 'yarda_orden' },
                    { data: 'codigo_material' },
                    { data: 'bultos' },
                    { data: 'fecha_creacion' },
                    {
                        data: 'acciones',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'Bfrtip',
                buttons: ['csv', 'excel'],
                pageLength: 20,
                language: {
                    "sProcessing": "Procesando...",
                    "sLengthMenu": "Mostrar _MENU_ registros",
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 a 0 de 0 registros",
                    "sInfoFiltered": "(filtrado de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                }
            });

            // Inicializar gráficos
            inicializarGraficos();

            // Cargar datos iniciales
            cargarDatos();
        // Función para obtener el rango de la semana actual
        function obtenerRangoSemanaActual() {
            const hoy = new Date();
            const diaSemana = hoy.getDay(); // 0 = Domingo, 1 = Lunes, ..., 6 = Sábado

            // Calcular el inicio de la semana (lunes)
            const inicioSemana = new Date(hoy);
            const diffInicio = hoy.getDate() - diaSemana + (diaSemana === 0 ? -6 : 1); // Ajuste para que lunes sea el inicio
            inicioSemana.setDate(diffInicio);

            // El fin de la semana es 6 días después del inicio
            const finSemana = new Date(inicioSemana);
            finSemana.setDate(inicioSemana.getDate() + 6);

            return {
                desde: formatearFecha(inicioSemana),
                hasta: formatearFecha(finSemana)
            };
        }

        // Función para formatear fecha a YYYY-MM-DD
        function formatearFecha(fecha) {
            const year = fecha.getFullYear();
            const month = String(fecha.getMonth() + 1).padStart(2, '0');
            const day = String(fecha.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Función para obtener la fecha actual en formato YYYY-MM-DD
        function obtenerFechaActual() {
            return formatearFecha(new Date());
        }

        // Función para validar fechas
        function validarFechas() {
            const fechaDesde = document.getElementById('desde').value;
            const fechaHasta = document.getElementById('hasta').value;
            const op = document.getElementById('op').value;
            const estatus = document.getElementById('estatus').value;
            const fechaActual = obtenerFechaActual();

            // Remover clases de error previas
            document.getElementById('desde').classList.remove('is-invalid');
            document.getElementById('hasta').classList.remove('is-invalid');

            let esValido = true;
            let mensajeError = '';

            // Solo validar fechas si se están utilizando para filtrar
            // Si solo se está filtrando por OP o estatus, permitir la búsqueda
            if (fechaDesde || fechaHasta) {
                // Si alguna fecha está llena, ambas deben estar llenas
                if ((fechaDesde && !fechaHasta) || (!fechaDesde && fechaHasta)) {
                    esValido = false;
                    mensajeError = 'Ambas fechas deben estar completas o ambas vacías';
                }

                // Si ambas fechas están completas, validar lógica
                if (fechaDesde && fechaHasta) {
                    // Validar que fecha inicial no sea mayor a fecha final
                    if (fechaDesde > fechaHasta) {
                        esValido = false;
                        mensajeError = 'La fecha inicial no puede ser mayor a la fecha final';
                        document.getElementById('desde').classList.add('is-invalid');
                        document.getElementById('hasta').classList.add('is-invalid');
                    }

                    // Validar que no haya fechas futuras
                    if (fechaDesde > fechaActual) {
                        esValido = false;
                        mensajeError = 'La fecha inicial no puede ser futura';
                        document.getElementById('desde').classList.add('is-invalid');
                    }

                    if (fechaHasta > fechaActual) {
                        esValido = false;
                        mensajeError = 'La fecha final no puede ser futura';
                        document.getElementById('hasta').classList.add('is-invalid');
                    }
                }
            }

            // Mostrar mensaje de error si existe
            if (!esValido) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validación de Fechas',
                    text: mensajeError,
                    timer: 3000,
                    showConfirmButton: false
                });
            }

            return esValido;
        }

        // Función para establecer valores por defecto
        function establecerValoresPorDefecto() {
            const rangoSemana = obtenerRangoSemanaActual();
            const fechaDesde = document.getElementById('desde');
            const fechaHasta = document.getElementById('hasta');

            // Solo establecer valores por defecto si los campos están vacíos
            if (!fechaDesde.value) {
                fechaDesde.value = rangoSemana.desde;
            }
            if (!fechaHasta.value) {
                fechaHasta.value = rangoSemana.hasta;
            }
        }

        // Event listeners para validación en tiempo real (solo visual, sin alertas)
        document.getElementById('desde').addEventListener('change', function() {
            validarFechasVisual();
        });

        document.getElementById('hasta').addEventListener('change', function() {
            validarFechasVisual();
        });

        // Función para validación visual (sin alertas)
        function validarFechasVisual() {
            const fechaDesde = document.getElementById('desde').value;
            const fechaHasta = document.getElementById('hasta').value;
            const fechaActual = obtenerFechaActual();

            // Remover clases de error previas
            document.getElementById('desde').classList.remove('is-invalid');
            document.getElementById('hasta').classList.remove('is-invalid');

            // Solo validar visualmente si ambas fechas están completas
            if (fechaDesde && fechaHasta) {
                // Validar que fecha inicial no sea mayor a fecha final
                if (fechaDesde > fechaHasta) {
                    document.getElementById('desde').classList.add('is-invalid');
                    document.getElementById('hasta').classList.add('is-invalid');
                }

                // Validar que no haya fechas futuras
                if (fechaDesde > fechaActual) {
                    document.getElementById('desde').classList.add('is-invalid');
                }

                if (fechaHasta > fechaActual) {
                    document.getElementById('hasta').classList.add('is-invalid');
                }
            }
        }

        // Establecer valores por defecto cuando la página cargue
        establecerValoresPorDefecto();

        // Función para cargar datos
        function cargarDatos() {
            const params = $('#filtrosForm').serialize();

            $.ajax({
                url: "{{ route('reporte.auditoria.corte.datos') }}",
                data: params,
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Respuesta del servidor:', response);

                    if (response.success) {
                        console.log('Total registros recibidos:', response.registros ? response.registros.length : 0);
                        console.log('Primer registro:', response.registros ? response.registros[0] : null);

                        // Actualizar KPIs
                        actualizarKPIs(response.kpis);

                        // Actualizar gráficos
                        actualizarGraficos(response.graficos);

                        // Actualizar tabla con verificación
                        if (typeof table !== 'undefined' && table) {
                            if (response.registros && response.registros.length > 0) {
                                table.clear().rows.add(response.registros).draw();
                                console.log('Tabla actualizada con', response.registros.length, 'registros');
                            } else {
                                table.clear().draw();
                                console.log('No hay registros para mostrar en la tabla');
                            }
                        } else {
                            console.error('La variable table no está definida');
                            // Reintentar después de un pequeño delay
                            setTimeout(function() {
                                if (typeof table !== 'undefined' && table) {
                                    table.clear().rows.add(response.registros || []).draw();
                                }
                            }, 100);
                        }

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos cargados',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        console.error('Error en respuesta:', response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr, status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudieron cargar los datos. Intente nuevamente.'
                    });
                }
            });
        }

        // Función para cargar datos de prueba
        function cargarDatosPrueba() {
            $.ajax({
                url: "{{ route('reporte.auditoria.corte.datos.prueba') }}",
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Respuesta de datos de prueba:', response);

                    if (response.success) {
                        console.log('Total registros de prueba:', response.registros ? response.registros.length : 0);
                        console.log('Primer registro de prueba:', response.registros ? response.registros[0] : null);

                        // Actualizar KPIs
                        actualizarKPIs(response.kpis);

                        // Actualizar gráficos
                        actualizarGraficos(response.graficos);

                        // Actualizar tabla con verificación
                        if (typeof table !== 'undefined' && table) {
                            if (response.registros && response.registros.length > 0) {
                                table.clear().rows.add(response.registros).draw();
                                console.log('Tabla de prueba actualizada con', response.registros.length, 'registros');
                            } else {
                                table.clear().draw();
                                console.log('No hay registros de prueba para mostrar en la tabla');
                            }
                        } else {
                            console.error('La variable table no está definida para datos de prueba');
                        }

                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Datos de prueba cargados',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        console.error('Error en respuesta de prueba:', response.message);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX en datos de prueba:', xhr, status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudieron cargar los datos de prueba. Intente nuevamente.'
                    });
                }
            });
        }

        // Función para actualizar KPIs
        function actualizarKPIs(kpis) {
            $('#kpi-total-op').text(kpis.total_op);
            $('#kpi-total-piezas').text(kpis.total_piezas);
            $('#kpi-concentracion').text(kpis.concentracion_promedio + '%');
            $('#kpi-defectos-criticos').text(kpis.defectos_criticos);
            $('#kpi-eficiencia').text(kpis.eficiencia_general + '%');
        }

        // Función para inicializar gráficos
        function inicializarGraficos() {
            // Gráfico de Concentración por OP
            Highcharts.chart('concentracionChart', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Concentración por OP',
                    style: { color: '#fff' }
                },
                xAxis: {
                    categories: [],
                    labels: { style: { color: '#fff' } }
                },
                yAxis: {
                    title: {
                        text: 'Concentración (%)',
                        style: { color: '#fff' }
                    },
                    labels: { style: { color: '#fff' } }
                },
                legend: {
                    itemStyle: { color: '#fff' }
                },
                series: [{
                    name: 'Concentración',
                    data: [],
                    color: '#3498db'
                }]
            });

            // Gráfico de Distribución de Estatus
            Highcharts.chart('estatusChart', {
                chart: {
                    type: 'pie',
                    backgroundColor: 'transparent'
                },
                title: {
                    text: 'Distribución de Estatus',
                    style: { color: '#fff' }
                },
                legend: {
                    itemStyle: { color: '#fff' }
                },
                plotOptions: {
                    pie: {
                        dataLabels: {
                            color: '#fff',
                            style: { textOutline: 'none' }
                        }
                    }
                },
                series: [{
                    name: 'Cantidad',
                    colorByPoint: true,
                    data: []
                }]
            });
        }

        // Función para actualizar gráficos
        function actualizarGraficos(graficos) {
            // Actualizar gráfico de concentración
            const concentracionChart = Highcharts.charts.find(chart =>
                chart && chart.renderTo.id === 'concentracionChart'
            );

            if (concentracionChart) {
                concentracionChart.xAxis[0].setCategories(
                    graficos.concentracion_por_op.map(item => item.op)
                );
                concentracionChart.series[0].setData(
                    graficos.concentracion_por_op.map(item => item.concentracion)
                );
            }

            // Actualizar gráfico de estatus
            const estatusChart = Highcharts.charts.find(chart =>
                chart && chart.renderTo.id === 'estatusChart'
            );

            if (estatusChart) {
                estatusChart.series[0].setData(
                    graficos.distribucion_estatus.map(item => ({
                        name: item.nombre,
                        y: item.cantidad
                    }))
                );
            }
        }

        // Función para exportar Excel
        function exportarExcel() {
            const params = $('#filtrosForm').serialize();
            window.location.href = "{{ route('reporte.auditoria.corte.exportar') }}?" + params;
        }

        // Función para ver detalles de OP
        function verDetallesOP(op) {
            $.ajax({
                url: "{{ route('reporte.auditoria.corte.buscar.op') }}",
                data: { op: op },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        mostrarModalDetalles(response);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudieron cargar los detalles de la OP'
                    });
                }
            });
        }

        // Función para mostrar modal de detalles
        function mostrarModalDetalles(data) {
            const modal = document.getElementById('modalDetalleOP');
            const titulo = document.getElementById('modalTitulo');
            const contenido = document.getElementById('modalContenido');

            titulo.textContent = `Detalles de OP: ${data.op} (${data.total_eventos} eventos)`;
            contenido.innerHTML = generarContenidoDetalles(data);

            modal.style.display = 'block';
        }

        // Función para generar contenido de detalles
        function generarContenidoDetalles(detalles) {
            let html = '<div class="row">';

            // Mostrar estadísticas generales
            if (detalles.estadisticas) {
                html += '<div class="col-12 mb-3">';
                html += '<h4>Estadísticas Generales</h4>';
                html += '<div class="row">';
                html += `<div class="col-md-3"><strong>Total Eventos:</strong> ${detalles.estadisticas.total_eventos}</div>`;
                html += `<div class="col-md-3"><strong>Completados:</strong> ${detalles.estadisticas.eventos_completados}</div>`;
                html += `<div class="col-md-3"><strong>Progreso:</strong> ${detalles.estadisticas.progreso}%</div>`;
                html += `<div class="col-md-3"><strong>Concentración Promedio:</strong> ${detalles.estadisticas.concentracion_promedio}%</div>`;
                html += '</div></div>';
            }

            // Mostrar detalles de cada evento
            detalles.detalles.forEach((detalle, index) => {
                html += '<div class="col-12 mb-4">';
                html += `<h5>Evento ${detalle.evento} de ${detalle.total_eventos}</h5>`;

                // Información general
                html += '<div class="row">';
                html += '<div class="col-md-6">';
                html += '<h6>Información General</h6>';
                html += '<table class="table table-sm table-dark">';
                html += '<tr><td><strong>Estilo:</strong></td><td>' + detalle.estilo_id + '</td></tr>';
                html += '<tr><td><strong>Cliente:</strong></td><td>' + detalle.cliente_id + '</td></tr>';
                html += '<tr><td><strong>Color:</strong></td><td>' + detalle.color_id + '</td></tr>';
                html += '<tr><td><strong>Material:</strong></td><td>' + detalle.material + '</td></tr>';
                html += '<tr><td><strong>Pieza:</strong></td><td>' + detalle.pieza + '</td></tr>';
                html += '<tr><td><strong>Progreso Etapa:</strong></td><td>' + detalle.progreso_etapa + '%</td></tr>';
                html += '</table></div>';

                // Auditoría Marcada
                html += '<div class="col-md-6">';
                html += '<h6>Auditoría Marcada</h6>';
                html += '<table class="table table-sm table-dark">';
                html += '<tr><td><strong>Yarda Orden:</strong></td><td>' + detalle.yarda_orden + '</td></tr>';
                html += '<tr><td><strong>Tallas:</strong></td><td>' + detalle.tallas + '</td></tr>';
                html += '<tr><td><strong>Total Piezas:</strong></td><td>' + detalle.total_piezas + '</td></tr>';
                html += '<tr><td><strong>Bultos:</strong></td><td>' + detalle.bultos + '</td></tr>';
                html += '</table></div></div>';

                // Auditoría Tendido
                html += '<div class="row mt-2">';
                html += '<div class="col-md-6">';
                html += '<h6>Auditoría Tendido</h6>';
                html += '<table class="table table-sm table-dark">';
                html += '<tr><td><strong>Código Material:</strong></td><td>' + detalle.codigo_material + '</td></tr>';
                html += '<tr><td><strong>Código Color:</strong></td><td>' + detalle.codigo_color + '</td></tr>';
                html += '<tr><td><strong>Material Relajado:</strong></td><td>' + detalle.material_relajado + '</td></tr>';
                html += '<tr><td><strong>Empalme:</strong></td><td>' + detalle.empalme + '</td></tr>';
                html += '<tr><td><strong>Cara Material:</strong></td><td>' + detalle.cara_material + '</td></tr>';
                html += '<tr><td><strong>Tono:</strong></td><td>' + detalle.tono + '</td></tr>';
                html += '<tr><td><strong>Yarda Marcada:</strong></td><td>' + detalle.yarda_marcada + '</td></tr>';
                html += '</table></div>';

                // Concentración y Bulto
                html += '<div class="col-md-6">';
                html += '<h6>Concentración y Bulto</h6>';
                html += '<table class="table table-sm table-dark">';
                html += '<tr><td><strong>Concentración:</strong></td><td>' + detalle.concentracion + '</td></tr>';
                html += '<tr><td><strong>Defectos:</strong></td><td>' + detalle.defectos + '</td></tr>';
                html += '<tr><td><strong>Pieza Inspeccionada:</strong></td><td>' + detalle.pieza_inspeccionada + '</td></tr>';
                html += '<tr><td><strong>Defecto:</strong></td><td>' + detalle.defecto + '</td></tr>';
                html += '<tr><td><strong>Cantidad Bulto:</strong></td><td>' + detalle.cantidad_bulto + '</td></tr>';
                html += '<tr><td><strong>Ingreso Ticket:</strong></td><td>' + detalle.ingreso_ticket_estatus + '</td></tr>';
                html += '<tr><td><strong>Sellado Paquete:</strong></td><td>' + detalle.sellado_paquete_estatus + '</td></tr>';
                html += '</table></div></div>';

                // Estado final
                html += '<div class="row mt-2">';
                html += '<div class="col-12">';
                html += '<h6>Estado Final</h6>';
                html += '<table class="table table-sm table-dark">';
                html += '<tr><td><strong>Estatus Actual:</strong></td><td>' + detalle.estatus_actual + '</td></tr>';
                //html += '<tr><td><strong>Estatus Avanzado:</strong></td><td>' + detalle.estatus_avanzado + '</td></tr>';
                html += '<tr><td><strong>Aceptado/Rechazado:</strong></td><td>' + detalle.aceptado_rechazado + '</td></tr>';
                html += '<tr><td><strong>Condición Aceptado:</strong></td><td>' + detalle.aceptado_condicion + '</td></tr>';
                html += '<tr><td><strong>Fecha Creación:</strong></td><td>' + detalle.fecha_creacion + '</td></tr>';
                html += '<tr><td><strong>Fecha Actualización:</strong></td><td>' + detalle.fecha_actualizacion + '</td></tr>';
                html += '</table></div></div>';

                html += '</div>';
            });

            html += '</div>';
            return html;
        }

        // Función para cerrar modal
        function cerrarModal() {
            document.getElementById('modalDetalleOP').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalDetalleOP');
            if (event.target === modal) {
                cerrarModal();
            }
        };

        // Cerrar modal con tecla Escape
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                cerrarModal();
            }
        });

        // Evento para el formulario de filtros
        $('#filtrosForm').on('submit', function(e) {
            e.preventDefault();

            const fechaDesde = document.getElementById('desde').value;
            const fechaHasta = document.getElementById('hasta').value;
            const op = document.getElementById('op').value;
            const estatus = document.getElementById('estatus').value;

            // Si se están usando fechas para filtrar, validarlas
            if (fechaDesde || fechaHasta) {
                if (validarFechas()) {
                    cargarDatos();
                }
            } else {
                // Si no se están usando fechas, permitir la búsqueda directamente
                cargarDatos();
            }
        });
    });
</script>
@endsection