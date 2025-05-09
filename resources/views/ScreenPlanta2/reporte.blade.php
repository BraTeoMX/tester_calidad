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
            <div class="col-md-6 d-flex align-items-center"> {{-- Ajustado para dar más espacio al input y menos al botón --}}
                <div class="form-group w-100">
                    <label for="fecha_reporte" class="form-label">Seleccionar Fecha del Reporte</label>
                    <input type="date" class="form-control" id="fecha_reporte" name="fecha_reporte" value="{{ now()->format('Y-m-d') }}" required> {{-- Valor por defecto: hoy --}}
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-end"> {{-- Alineado al final para el botón --}}
                <div class="form-group">
                    <label class="d-block">&nbsp;</label> <button type="button" class="btn btn-secondary w-100" id="btnMostrarDatos">Mostrar Datos</button>
                </div>
            </div>
        </div>

        <div class="card card-body">
            {{-- Contenedor donde se insertarán las tablas dinámicamente --}}
            <div id="contenedor-tablas-maquinas">
                <div class="card card-body">
                    <p class="text-center">Seleccione una fecha y presione "Mostrar Datos" para ver los reportes por máquina.</p>
                </div>
            </div>
            {{-- Contenedor para la tabla de Resumen General --}}
            <div id="contenedor-resumen-general" class="mt-4">
                {{-- Aquí se insertará la tabla de resumen general --}}
            </div>
        </div>
    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333;                 /* Color del texto */
        }
        .texto-blanco {
            color: white !important;
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
        
            function cargarReportePorDia(fechaSeleccionada) {
                if (!fechaSeleccionada) {
                    alert("Por favor, seleccione una fecha.");
                    destruirDataTablesExistentes(); // Destruir tablas antes de mostrar error
                    $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center text-danger">Error: Fecha no proporcionada.</p></div>`);
                    $("#contenedor-resumen-general").empty();
                    return;
                }
        
                // Destruir DataTables existentes antes de una nueva carga
                destruirDataTablesExistentes();
        
                $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center">Cargando datos... <i class="fas fa-spinner fa-spin"></i></p></div>`);
                $("#contenedor-resumen-general").empty();
        
                $.ajax({
                    url: '{{ route("reportesScreen.datosPorDia") }}',
                    method: 'GET',
                    data: { fecha_inicio: fechaSeleccionada, _token: '{{ csrf_token() }}' },
                    dataType: 'json',
                    success: function(response) {
                        var contenedorMaquinas = $("#contenedor-tablas-maquinas");
                        var contenedorResumenGeneral = $("#contenedor-resumen-general");
                        
                        // Destruir DataTables ANTES de vaciar los contenedores por si acaso
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
                                                %: ${Number(dataMaquina.resumen.porcentajeDefectos).toFixed(2)}%
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
                                                title: `Reporte Máquina - ${nombreMaquina} - ${fechaSeleccionada}`,
                                                footer: true, // Esto asegura que el tfoot se incluya en la exportación
                                                exportOptions: {
                                                    // Aquí podrías especificar columnas si fuera necesario, pero con footer:true es usualmente suficiente.
                                                    // columns: ':visible' // Exporta solo columnas visibles
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
                                        <h4 class="card-title mb-0">Resumen General del Día (${fechaSeleccionada})</h4>
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
                                    <td class="text-right">${Number(detalle.porcentajeDefectos).toFixed(2)}%</td>
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
                                    <td class="text-right">${Number(response.resumenGeneral.porcentajeDefectosGlobal).toFixed(2)}%</td>
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
                                        title: `Resumen General - ${fechaSeleccionada}`,
                                        footer: true // Incluir tfoot
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
        
            $("#btnMostrarDatos").click(function() {
                var fechaSeleccionada = $("#fecha_reporte").val();
                cargarReportePorDia(fechaSeleccionada);
            });
        });
        </script>
@endsection