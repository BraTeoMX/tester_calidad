@extends('layouts.app', ['pageSlug' => 'HornoReporte', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')

<div class="content">
    <div class="card">
        <div class="card-header card-header-primary">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="card-title" style="text-align: center; font-weight: bold;">Reporte Screen</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                {{-- Cambiamos el id de fecha_reporte a fecha_inicio para mayor claridad --}}
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"
                    value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin"
                    value="{{ now()->format('Y-m-d') }}" required>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <div class="form-group w-100">
                <button type="button" class="btn btn-secondary w-100" id="btnMostrarDatos">Mostrar Datos</button>
            </div>
        </div>
    </div>

    <div class="card card-body">
        {{-- Contenedor donde se insertarán las tablas dinámicamente --}}
        <div id="contenedor-tablas-maquinas">
            <div class="card card-body">
                <p class="text-center">Seleccione una fecha y presione "Mostrar Datos" para ver los reportes por
                    máquina.</p>
            </div>
        </div>
        {{-- Contenedor para la tabla de Resumen General --}}
        <div id="contenedor-resumen-general" class="mt-4">
            {{-- Aquí se insertará la tabla de resumen general --}}
        </div>

        {{-- Contenedor para las gráficas de Top 3 Defectos por Máquina --}}
        <div id="contenedor-graficas-maquinas" class="mt-4">
            {{-- Aquí se insertarán las gráficas de top 3 defectos por máquina --}}
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

    /* Estilos adicionales para controles de gráficas */
    .btn-group-toggle .btn {
        font-size: 11px;
        padding: 4px 8px;
    }

    .btn-group-toggle .btn.active {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .card-chart .card-header {
        padding: 10px 15px;
        min-height: 50px;
    }

    .card-chart .card-title {
        margin: 0;
        font-size: 14px;
    }

    /* Responsive para controles en móviles */
    @media (max-width: 768px) {
        .btn-group-toggle .btn span.d-none {
            display: none !important;
        }

        .btn-group-toggle .btn {
            padding: 6px 10px;
            font-size: 10px;
        }
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

<!-- Highcharts para gráficas -->
<script src="{{ asset('js/highcharts/12/highcharts.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/exporting.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/offline-exporting.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/no-data-to-display.js') }}"></script>
<script src="{{ asset('js/highcharts/12/modules/accessibility.js') }}"></script>

<script>
    $(document).ready(function() {
            const COLUMNAS_REGISTROS = 16;
            let dataTableInstances = []; // Para almacenar instancias de DataTables y poder destruirlas
        
            // Función para destruir todas las instancias activas de DataTables
            function destruirDataTablesExistentes() {
                dataTableInstances.forEach(function(table) {
                    if ($.fn.DataTable.isDataTable(table.selector)) {
                        table.instance.destroy();
                    }
                });
                dataTableInstances = []; // Resetear el array
            }
        
            function cargarReportePorDia(fechaInicio, fechaFin) {
                if (!fechaInicio || !fechaFin) {
                    alert("Por favor, seleccione una fecha de inicio y de fin.");
                    destruirDataTablesExistentes();
                    $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center text-danger">Error: Fechas no proporcionadas.</p></div>`);
                    $("#contenedor-resumen-general").empty();
                    return;
                }
        
                // Destruir DataTables existentes antes de una nueva carga
                destruirDataTablesExistentes();
                $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center">Cargando datos... <i class="fas fa-spinner fa-spin"></i></p></div>`);
                $("#contenedor-resumen-general").empty();

                const rangoFechasTexto = fechaInicio === fechaFin ? fechaInicio : `${fechaInicio} al ${fechaFin}`;
        
                $.ajax({
                    url: '{{ route("reportesScreen.datosPorDia") }}',
                    method: 'GET',
                    data: { 
                        fecha_inicio: fechaInicio, 
                        fecha_fin: fechaFin, // <-- Nuevo parámetro
                        _token: '{{ csrf_token() }}' 
                    },
                    dataType: 'json',
                    success: function(response) {
                        var contenedorMaquinas = $("#contenedor-tablas-maquinas");
                        var contenedorResumenGeneral = $("#contenedor-resumen-general");
                        destruirDataTablesExistentes();
                        contenedorMaquinas.empty();
                        contenedorResumenGeneral.empty();
        
                        // --- Renderizar Tablas por Máquina ---
                        if (response.reportePorMaquina && Object.keys(response.reportePorMaquina).length > 0) {
                            let machineTableIndex = 0; // Para generar IDs únicos para cada tabla de máquina
                            $.each(response.reportePorMaquina, function(nombreMaquina, dataMaquina) {
                                const tableId = `tabla-maquina-${machineTableIndex}`;
                                let cardHtml = `
                                    <div class="card mt-3">
                                        <div class="card-header card-header-info">
                                            <h4 class="card-title mb-0">Máquina: ${$('<div>').text(nombreMaquina).html()}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table id="${tableId}" class="table table-striped table-hover table-sm display"> <thead class="thead-primary">
                                                        <tr>
                                                            <th>Auditor</th>
                                                            <th>Bulto</th>
                                                            <th>OP</th>
                                                            <th>Cliente</th>
                                                            <th>Estilo</th>
                                                            <th>Color</th>
                                                            <th>Cantidad</th>
                                                            <th>Panel</th>
                                                            <th>Gráfica</th>
                                                            <th>Técnicas</th>
                                                            <th>Fibras</th>
                                                            <th>Técnico Screen</th>
                                                            <th>Defectos Screen</th>
                                                            <th>Técnico Plancha</th>
                                                            <th>Defectos Plancha</th>
                                                            <th>Hora Registro</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>`;
        
                                if (dataMaquina.registros && dataMaquina.registros.length > 0) {
                                    $.each(dataMaquina.registros, function(index, registro) {
                                        cardHtml += `<tr>
                                            <td>${$('<div>').text(registro.auditor).html()}</td>
                                            <td>${$('<div>').text(registro.bulto).html()}</td>
                                            <td>${$('<div>').text(registro.op).html()}</td>
                                            <td>${$('<div>').text(registro.cliente).html()}</td>
                                            <td>${$('<div>').text(registro.estilo).html()}</td>
                                            <td>${$('<div>').text(registro.color).html()}</td>
                                            <td class="text-right">${Number(registro.cantidad).toLocaleString()}</td>
                                            <td>${$('<div>').text(registro.panel).html()}</td>
                                            <td>${$('<div>').text(registro.grafica).html()}</td>
                                            <td>${registro.tecnicasHtml}</td>
                                            <td>${registro.fibrasHtml}</td>
                                            <td>${$('<div>').text(registro.tecnico_screen).html()}</td>
                                            <td>${registro.screenDefectosHtml}</td>
                                            <td>${$('<div>').text(registro.tecnico_plancha).html()}</td>
                                            <td>${registro.planchaDefectosHtml}</td>
                                            <td>${$('<div>').text(registro.fecha).html()}</td>
                                        </tr>`;
                                    });
                                } else {
                                    // DataTables no maneja bien tbody vacíos si se inicializa,
                                    // pero para consistencia visual lo dejamos, aunque la tabla no se inicializará si no hay datos.
                                    cardHtml += `<tr><td colspan="${COLUMNAS_REGISTROS}" class="text-center">No hay registros de inspección para esta máquina.</td></tr>`;
                                }
                                cardHtml += `</tbody>`;
        
                                if (dataMaquina.resumen) {
                                    cardHtml += `<tfoot>
                                        <tr>
                                            <td colspan="6" class="text-right font-weight-bold">TOTALES MÁQUINA:</td>
                                            <td class="text-right font-weight-bold">${Number(dataMaquina.resumen.totalCantidadAuditada).toLocaleString()}</td>
                                            <td colspan="4"></td>
                                            <td class="text-right font-weight-bold" colspan="2">Def. Screen: ${Number(dataMaquina.resumen.totalScreenDefectos).toLocaleString()}</td>
                                            <td class="text-right font-weight-bold" colspan="2">Def. Plancha: ${Number(dataMaquina.resumen.totalPlanchaDefectos).toLocaleString()}</td>
                                            <td class="text-right font-weight-bold">
                                                Total Def: ${Number(dataMaquina.resumen.totalDefectosCombinados).toLocaleString()}<br>
                                                %: <span data-porcentaje="${Number(dataMaquina.resumen.porcentajeDefectos).toFixed(2)}">${Number(dataMaquina.resumen.porcentajeDefectos).toFixed(2)}%</span>
                                            </td>
                                        </tr>
                                    </tfoot>`;
                                }
                                cardHtml += `</table>
                                            </div>
                                        </div>
                                    </div>`;
                                contenedorMaquinas.append(cardHtml);
        
                                // Inicializar DataTables para esta tabla específica SI HAY DATOS
                                if (dataMaquina.registros && dataMaquina.registros.length > 0) {
                                    let dtInstance = $(`#${tableId}`).DataTable({
                                        dom: 'Bfrtip', // B para Buttons, f para filtering input, r para processing display, t para the table, i para table information, p para pagination control
                                        buttons: [
                                            {
                                                extend: 'excelHtml5',
                                                text: 'Exportar a Excel',
                                                title: `Reporte Máquina - ${nombreMaquina} - ${rangoFechasTexto}`,
                                                footer: true, // Esto asegura que el tfoot se incluya en la exportación
                                                // Opción más simple: usar customizeData para limpiar datos antes de generar Excel
                                                customizeData: function(data) {
                                                    // Limpiar datos del body
                                                    for (var i = 0; i < data.body.length; i++) {
                                                        for (var j = 0; j < data.body[i].length; j++) {
                                                            if (typeof data.body[i][j] === 'string' && data.body[i][j].indexOf('%') !== -1) {
                                                                data.body[i][j] = data.body[i][j].replace('%', '');
                                                            }
                                                        }
                                                    }
        
                                                    // Limpiar datos del footer
                                                    if (data.footer) {
                                                        for (var i = 0; i < data.footer.length; i++) {
                                                            for (var j = 0; j < data.footer[i].length; j++) {
                                                                if (typeof data.footer[i][j] === 'string' && data.footer[i][j].indexOf('%') !== -1) {
                                                                    data.footer[i][j] = data.footer[i][j].replace('%', '');
                                                                }
                                                            }
                                                        }
                                                    }
        
                                                    return data;
                                                },
                                                exportOptions: {
                                                    format: {
                                                        body: function(data, row, column, node) {
                                                            // Usar el atributo data-porcentaje si existe
                                                            if (node && node.querySelector && node.querySelector('[data-porcentaje]')) {
                                                                return node.querySelector('[data-porcentaje]').getAttribute('data-porcentaje');
                                                            }
                                                            // Función específica para formatear datos durante la exportación
                                                            if (data && data.includes && data.includes('%')) {
                                                                return data.replace('%', '');
                                                            }
                                                            return data;
                                                        },
                                                        footer: function(data, row, column, node) {
                                                            // Usar el atributo data-porcentaje si existe
                                                            if (node && node.querySelector && node.querySelector('[data-porcentaje]')) {
                                                                return node.querySelector('[data-porcentaje]').getAttribute('data-porcentaje');
                                                            }
                                                            // Función específica para formatear el footer durante la exportación
                                                            if (data && data.includes && data.includes('%')) {
                                                                return data.replace('%', '');
                                                            }
                                                            return data;
                                                        }
                                                    }
                                                }
                                            }
                                        ],
                                        // Puedes agregar más opciones de DataTables aquí:
                                        // paging: true,
                                        // searching: true,
                                        // ordering: true,
                                        // info: true,
                                        language: { // Traducciones opcionales
                                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                                        }
                                    });
                                    dataTableInstances.push({selector: `#${tableId}`, instance: dtInstance});
                                }
                                machineTableIndex++;
                            });
                        } else {
                            contenedorMaquinas.html(`<div class="card card-body"><p class="text-center">No se encontraron datos de inspección para la fecha seleccionada.</p></div>`);
                        }
        
                        // --- Renderizar Tabla de Resumen General ---
                        if (response.resumenGeneral && response.resumenGeneral.detallePorMaquina && response.resumenGeneral.detallePorMaquina.length > 0) {
                            const resumenTableId = 'tabla-resumen-general';
                            let resumenGeneralHtml = `
                                <div class="card">
                                    <div class="card-header card-header-success">
                                        <h4 class="card-title mb-0">Resumen General del Periodo (${rangoFechasTexto})</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="${resumenTableId}" class="table table-bordered table-hover table-sm display">
                                                <thead>
                                                    <tr>
                                                        <th>Máquina</th>
                                                        <th class="text-right">Cantidad Auditada</th>
                                                        <th class="text-right">Defectos Screen</th>
                                                        <th class="text-right">Defectos Plancha</th>
                                                        <th class="text-right">Total Defectos</th>
                                                        <th class="text-right">Porcentaje Def. (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>`;
                            $.each(response.resumenGeneral.detallePorMaquina, function(index, detalle) {
                                resumenGeneralHtml += `<tr>
                                    <td>${$('<div>').text(detalle.nombreMaquina).html()}</td>
                                    <td class="text-right">${Number(detalle.cantidadAuditada).toLocaleString()}</td>
                                    <td class="text-right">${Number(detalle.cantidadScreenDefectos).toLocaleString()}</td>
                                    <td class="text-right">${Number(detalle.cantidadPlanchaDefectos).toLocaleString()}</td>
                                    <td class="text-right">${Number(detalle.cantidadDefectosCombinados).toLocaleString()}</td>
                                    <td class="text-right" data-porcentaje="${Number(detalle.porcentajeDefectos).toFixed(2)}">${Number(detalle.porcentajeDefectos).toFixed(2)}%</td>
                                </tr>`;
                            });
                            resumenGeneralHtml += `</tbody>`;
                            resumenGeneralHtml += `<tfoot class="table-active font-weight-bold">
                                <tr>
                                    <td>TOTAL GENERAL:</td>
                                    <td class="text-right">${Number(response.resumenGeneral.totalCantidadAuditadaGlobal).toLocaleString()}</td>
                                    <td class="text-right">${Number(response.resumenGeneral.totalScreenDefectosGlobal).toLocaleString()}</td>
                                    <td class="text-right">${Number(response.resumenGeneral.totalPlanchaDefectosGlobal).toLocaleString()}</td>
                                    <td class="text-right">${Number(response.resumenGeneral.totalDefectosCombinadosGlobal).toLocaleString()}</td>
                                    <td class="text-right" data-porcentaje="${Number(response.resumenGeneral.porcentajeDefectosGlobal).toFixed(2)}">${Number(response.resumenGeneral.porcentajeDefectosGlobal).toFixed(2)}%</td>
                                </tr>
                            </tfoot>`;
                            resumenGeneralHtml += `</table>
                                        </div>
                                    </div>
                                </div>`;
                            contenedorResumenGeneral.html(resumenGeneralHtml);
        
                            // Inicializar DataTables para la tabla de resumen general
                            let dtResumenInstance = $(`#${resumenTableId}`).DataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'excelHtml5',
                                        text: 'Exportar Resumen a Excel',
                                        title: `Resumen General - ${rangoFechasTexto}`,
                                        footer: true, // Incluir tfoot
                                        // Opción más simple: usar customizeData para limpiar datos antes de generar Excel
                                        customizeData: function(data) {
                                            // Limpiar datos del body
                                            for (var i = 0; i < data.body.length; i++) {
                                                for (var j = 0; j < data.body[i].length; j++) {
                                                    if (typeof data.body[i][j] === 'string' && data.body[i][j].indexOf('%') !== -1) {
                                                        data.body[i][j] = data.body[i][j].replace('%', '');
                                                    }
                                                }
                                            }

                                            // Limpiar datos del footer
                                            if (data.footer) {
                                                for (var i = 0; i < data.footer.length; i++) {
                                                    for (var j = 0; j < data.footer[i].length; j++) {
                                                        if (typeof data.footer[i][j] === 'string' && data.footer[i][j].indexOf('%') !== -1) {
                                                            data.footer[i][j] = data.footer[i][j].replace('%', '');
                                                        }
                                                    }
                                                }
                                            }

                                            return data;
                                        },
                                        exportOptions: {
                                            format: {
                                                body: function(data, row, column, node) {
                                                    // Usar el atributo data-porcentaje si existe
                                                    if (node && node.querySelector && node.querySelector('[data-porcentaje]')) {
                                                        return node.querySelector('[data-porcentaje]').getAttribute('data-porcentaje');
                                                    }
                                                    // Función específica para formatear datos durante la exportación
                                                    if (data && data.includes && data.includes('%')) {
                                                        return data.replace('%', '');
                                                    }
                                                    return data;
                                                },
                                                footer: function(data, row, column, node) {
                                                    // Usar el atributo data-porcentaje si existe
                                                    if (node && node.querySelector && node.querySelector('[data-porcentaje]')) {
                                                        return node.querySelector('[data-porcentaje]').getAttribute('data-porcentaje');
                                                    }
                                                    // Función específica para formatear el footer durante la exportación
                                                    if (data && data.includes && data.includes('%')) {
                                                        return data.replace('%', '');
                                                    }
                                                    return data;
                                                }
                                            }
                                        }
                                    }
                                ],
                                paging: false, // Generalmente no se pagina la tabla de resumen
                                searching: false,
                                info: false,
                                ordering: true, // Permitir ordenar si se desea
                                language: {
                                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                                }
                            });
                            dataTableInstances.push({selector: `#${resumenTableId}`, instance: dtResumenInstance});
                        } else if (Object.keys(response.reportePorMaquina).length > 0) {
                             contenedorResumenGeneral.html(`<div class="card card-body"><p class="text-center">No se generó el resumen general.</p></div>`);
                        }
        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        destruirDataTablesExistentes(); // Destruir en caso de error también
                        $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center text-danger">Error al cargar los registros.</p></div>`);
                        $("#contenedor-resumen-general").empty();
                        console.error("Error AJAX:", textStatus, errorThrown, jqXHR.responseText);
                        alert("Error al cargar los registros del día. " + (jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : errorThrown));
                    }
                });
            }

            // Función para cargar y renderizar gráficas de top 3 defectos por máquina
            function cargarGraficasTopDefectos(fechaInicio, fechaFin) {
                if (!fechaInicio || !fechaFin) {
                    return;
                }

                const contenedorGraficas = $("#contenedor-graficas-maquinas");
                contenedorGraficas.empty();

                // Mostrar indicador de carga
                contenedorGraficas.html(`
                    <div class="card">
                        <div class="card-header card-header-warning">
                            <h4 class="card-title mb-0">Top 3 Defectos por Máquina</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <p>Cargando gráficas... <i class="fas fa-spinner fa-spin"></i></p>
                            </div>
                        </div>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("reportesScreen.topDefectosPorMaquina") }}',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaInicio,
                        fecha_fin: fechaFin,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(data) {
                        contenedorGraficas.empty();

                        if (Object.keys(data).length === 0) {
                            contenedorGraficas.html(`
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center">
                                            <p>No hay datos de defectos para mostrar gráficas.</p>
                                        </div>
                                    </div>
                                </div>
                            `);
                            return;
                        }

                        // Crear gráficas para cada máquina
                        Object.keys(data).forEach(function(nombreMaquina) {
                            const maquinaData = data[nombreMaquina];
                            const rangoFechasTexto = fechaInicio === fechaFin ? fechaInicio : `${fechaInicio} al ${fechaFin}`;

                            // Crear contenedor para gráficas de esta máquina
                            const maquinaContainerId = `graficas-maquina-${nombreMaquina.replace(/\s+/g, '-').toLowerCase()}`;
                            const graficaScreenId = `grafica-screen-${nombreMaquina.replace(/\s+/g, '-').toLowerCase()}`;
                            const graficaPlanchaId = `grafica-plancha-${nombreMaquina.replace(/\s+/g, '-').toLowerCase()}`;

                            let maquinaHtml = `
                                <div class="card mt-3">
                                    <div class="card-header card-header-warning">
                                        <h4 class="card-title mb-0">Máquina: ${$('<div>').text(nombreMaquina).html()}</h4>
                                        <p class="card-category">Período: ${rangoFechasTexto}</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card card-chart">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-sm-6 text-left">
                                                                <h5 class="card-title">Top 3 Defectos - SCREEN</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="${graficaScreenId}" style="width: 100%; height: 300px;">
                                                            <div class="text-center" style="padding-top: 100px;">
                                                                <p>Cargando gráfica...</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card card-chart">
                                                    <div class="card-header">
                                                        <div class="row">
                                                            <div class="col-sm-6 text-left">
                                                                <h5 class="card-title">Top 3 Defectos - PLANCHA</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div id="${graficaPlanchaId}" style="width: 100%; height: 300px;">
                                                            <div class="text-center" style="padding-top: 100px;">
                                                                <p>Cargando gráfica...</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            contenedorGraficas.append(maquinaHtml);

                            // Crear gráfica para defectos Screen
                            if (maquinaData.screen && maquinaData.screen.length > 0) {
                                crearGraficaDefectos(graficaScreenId, `Top 3 Defectos Screen - ${nombreMaquina}`, maquinaData.screen);
                            } else {
                                $(`#${graficaScreenId}`).html(`
                                    <div class="text-center" style="padding-top: 100px;">
                                        <p>No hay datos de defectos Screen para esta máquina.</p>
                                    </div>
                                `);
                            }

                            // Crear gráfica para defectos Plancha
                            if (maquinaData.plancha && maquinaData.plancha.length > 0) {
                                crearGraficaDefectos(graficaPlanchaId, `Top 3 Defectos Plancha - ${nombreMaquina}`, maquinaData.plancha);
                            } else {
                                $(`#${graficaPlanchaId}`).html(`
                                    <div class="text-center" style="padding-top: 100px;">
                                        <p>No hay datos de defectos Plancha para esta máquina.</p>
                                    </div>
                                `);
                            }

                            // Agregar funcionalidad a los controles de mostrar/ocultar
                            configurarControlesGraficas(nombreMaquina);
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        contenedorGraficas.html(`
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-center text-danger">
                                        <p>Error al cargar las gráficas de defectos.</p>
                                        <small>${errorThrown}</small>
                                    </div>
                                </div>
                            </div>
                        `);
                        console.error("Error al cargar gráficas:", textStatus, errorThrown);
                    }
                });
            }
        
            // Función para configurar controles de mostrar/ocultar gráficas
            function configurarControlesGraficas(nombreMaquina) {
                const nombreMaquinaFormateado = nombreMaquina.replace(/\s+/g, '-').toLowerCase();
        
                // Configurar controles para gráfica Screen
                $(`input[name="options-${nombreMaquinaFormateado}-screen"]`).each(function() {
                    $(this).off('change').on('change', function() {
                        const chartId = $(this).closest('label').data('chart');
                        const isShow = $(this).closest('label').text().trim() === 'Mostrar';
        
                        if (isShow) {
                            $(`#${chartId}`).show();
                        } else {
                            $(`#${chartId}`).hide();
                        }
                    });
                });
        
                // Configurar controles para gráfica Plancha
                $(`input[name="options-${nombreMaquinaFormateado}-plancha"]`).each(function() {
                    $(this).off('change').on('change', function() {
                        const chartId = $(this).closest('label').data('chart');
                        const isShow = $(this).closest('label').text().trim() === 'Mostrar';
        
                        if (isShow) {
                            $(`#${chartId}`).show();
                        } else {
                            $(`#${chartId}`).hide();
                        }
                    });
                });
            }
        
            // Función para crear gráfica de defectos usando Highcharts (estilo dashboard)
            function crearGraficaDefectos(containerId, titulo, datos) {
                // Obtener categorías y valores
                const categorias = datos.map(d => d.defecto);
                const valores = datos.map(d => parseInt(d.total));

                // Paleta de colores igual al dashboard
                const colores = ['#f44336', '#ff9800', '#ffc107', '#4caf50', '#00bcd4'];

                const chart = Highcharts.chart(containerId, {
                    chart: {
                        type: 'column',
                        height: 300,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: titulo,
                        style: {
                            color: '#ffffff',
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        labels: {
                            style: {
                                color: '#ffffff',
                                fontSize: '11px'
                            }
                        },
                        lineColor: '#ffffff',
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Cantidad de Defectos',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                color: '#ffffff',
                                fontSize: '10px'
                            }
                        },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)'
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        style: {
                            color: '#ffffff'
                        },
                        formatter: function() {
                            const pointData = datos[this.point.index];
                            return `<b>Defecto: ${pointData.defecto}</b><br/>` +
                                   `Cantidad total: <b>${pointData.total}</b>`;
                        }
                    },
                    plotOptions: {
                        series: {
                            colorByPoint: true,
                            colors: colores,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                color: '#FFFFFF',
                                style: {
                                    textOutline: 'none',
                                    fontSize: '10px'
                                },
                                format: '{point.y}'
                            }
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    credits: {
                        enabled: false
                    },
                    series: [{
                        name: 'Total de Defectos',
                        data: valores
                    }]
                });

                return chart;
            }

            $("#btnMostrarDatos").click(function() {
                var fechaInicio = $("#fecha_inicio").val();
                var fechaFin = $("#fecha_fin").val(); // <-- Obtener la fecha de fin
                cargarReportePorDia(fechaInicio, fechaFin); // <-- Pasar ambos valores
                cargarGraficasTopDefectos(fechaInicio, fechaFin); // <-- Cargar gráficas también
            });
        });
</script>
@endsection