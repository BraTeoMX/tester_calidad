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

    <script>
        $(document).ready(function() {
            // Define el número de columnas para los mensajes de colspan, ajusta si cambian los headers
            const COLUMNAS_REGISTROS = 16; // Auditor, Bulto, OP,..., Hora Registro (16 columnas en total para los datos de inspección)
        
            function cargarReportePorDia(fechaSeleccionada) {
                if (!fechaSeleccionada) {
                    alert("Por favor, seleccione una fecha.");
                    $("#contenedor-tablas-maquinas").html(`<div class="card card-body"><p class="text-center text-danger">Error: Fecha no proporcionada.</p></div>`);
                    $("#contenedor-resumen-general").empty(); // Limpiar también el resumen general
                    return;
                }
        
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
                        contenedorMaquinas.empty();
                        contenedorResumenGeneral.empty();
        
                        // --- Renderizar Tablas por Máquina ---
                        if (response.reportePorMaquina && Object.keys(response.reportePorMaquina).length > 0) {
                            $.each(response.reportePorMaquina, function(nombreMaquina, dataMaquina) {
                                let cardHtml = `
                                    <div class="card mt-3">
                                        <div class="card-header card-header-info">
                                            <h4 class="card-title mb-0">Máquina: ${$('<div>').text(nombreMaquina).html()}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover table-sm">
                                                    <thead class="thead-primary"> <tr>
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
                                    cardHtml += `<tr><td colspan="${COLUMNAS_REGISTROS}" class="text-center">No hay registros de inspección para esta máquina.</td></tr>`;
                                }
                                cardHtml += `</tbody>`;
        
                                // --- tfoot para la tabla de la máquina ---
                                if (dataMaquina.resumen) {
                                    cardHtml += `<tfoot> <tr>
                                            <td colspan="6" class="text-right font-weight-bold">TOTALES MÁQUINA:</td>
                                            <td class="text-right font-weight-bold">${Number(dataMaquina.resumen.totalCantidadAuditada).toLocaleString()}</td>
                                            <td colspan="4"></td> <td class="text-right font-weight-bold" colspan="2">Def. Screen: ${Number(dataMaquina.resumen.totalScreenDefectos).toLocaleString()}</td>
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
                            });
                        } else {
                            contenedorMaquinas.html(`<div class="card card-body"><p class="text-center">No se encontraron datos de inspección para la fecha seleccionada.</p></div>`);
                        }
        
                        // --- Renderizar Tabla de Resumen General ---
                        if (response.resumenGeneral && response.resumenGeneral.detallePorMaquina && response.resumenGeneral.detallePorMaquina.length > 0) {
                            let resumenGeneralHtml = `
                                <div class="card">
                                    <div class="card-header card-header-success">
                                        <h4 class="card-title mb-0">Resumen General del Día</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover table-sm">
                                                <thead class="thead-light">
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
        
                            // --- tfoot para la tabla de resumen general ---
                            resumenGeneralHtml += `<tfoot class="table-active font-weight-bold"> <tr>
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
                        } else if (Object.keys(response.reportePorMaquina).length > 0) { // Si hay datos por máquina pero no resumen general (debería haber)
                            contenedorResumenGeneral.html(`<div class="card card-body"><p class="text-center">No se generó el resumen general.</p></div>`);
                        }
                        // Si no hay datos por máquina, el mensaje ya se mostró y el resumen general no se muestra.
        
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
        
            // Opcional: Cargar datos para la fecha por defecto (hoy) al cargar la página
            // var fechaInicial = $("#fecha_reporte").val();
            // if(fechaInicial) {
            //     cargarReportePorDia(fechaInicial);
            // }
        });
    </script>
@endsection