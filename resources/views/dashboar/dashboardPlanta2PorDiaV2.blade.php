@extends('layouts.app', ['pageSlug' => 'dashboardPorDia', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta por dia Planta 2
                        - San Bartolo Morelos </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 d-flex align-items-center">
            <div class="form-group w-100">
                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-group">
                <label class="d-block">&nbsp;</label> <!-- Espacio para alinear con el input -->
                <button type="button" class="btn btn-secondary" id="btnMostrar">Mostrar datos</button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
            <label class="btn btn-sm btn-primary btn-simple active" id="showAQL">
                <input type="radio" name="options" checked>
                <h5><i class="tim-icons icon-app text-success"></i>&nbsp; AQL</h5>
            </label>
            <label class="btn btn-sm btn-primary btn-simple" id="showProceso">
                <input type="radio" name="options">
                <h5><i class="tim-icons icon-vector text-primary"></i>&nbsp; Proceso</h5>
            </label>
        </div>
        <div id="tablaAQL" class="table-container" style="display: block;">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Turno Normal
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Auditor</th>
                                    <th>Modulo (AQL)</th>
                                    <th>Supervisor</th>
                                    <th>Estilo</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Minutos Paro Modular</th>
                                    <th>Total piezas por Bulto</th>
                                    <th>Total Bulto</th>
                                    <th>Total Bulto Rechazados</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error AQL</th>
                                    <th>Defectos</th>
                                    <th>Accion Correctiva</th>
                                    <th>Operario Responsable</th>
                                    <th>Reparacion Piezas</th>
                                    <th>Piezas de Bulto Rechazado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaAQLGeneralNuevoBody">
                                <!-- Aquí se insertarán los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="spinnerAQL" class="text-center my-3" style="display: none;">
                        <div class="loading-container">
                            <span class="loading-text">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de Proceso General -->
        <div id="tablaProceso" class="table-container" style="display: none;">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Turno
                        Normal</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Auditor</th>
                                    <th>Modulo</th>
                                    <th>Supervisor</th>
                                    <th>Estilo</th>
                                    <th>Recorridos</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Minutos Paro Modular</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error Proceso</th>
                                    <th>DEFECTOS</th>
                                    <th>ACCION CORRECTIVA</th>
                                    <th>Operarios</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProcesoGeneralNuevoBody">
                                <!-- Aquí se insertarán los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="spinnerPROCESO" class="text-center my-3" style="display: none;">
                        <div class="loading-container">
                            <span class="loading-text">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
            <label class="btn btn-sm btn-primary btn-simple active" id="showAQLTE">
                <input type="radio" name="optionsTE" checked>
                <h5><i class="tim-icons icon-app text-success"></i>&nbsp; AQL TE</h5>
            </label>
            <label class="btn btn-sm btn-primary btn-simple" id="showProcesoTE">
                <input type="radio" name="optionsTE">
                <h5><i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos TE</h5>
            </label>
        </div>
        <!-- Tabla de AQL TE (visible por defecto) -->
        <div id="tablaAQLTE" class="table-container" style="display: block;">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Tiempo Extra</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Auditor</th>
                                    <th>Modulo (AQL)</th>
                                    <th>Supervisor</th>
                                    <th>Estilo</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Minutos Paro Modular</th>
                                    <th>Total piezas por Bulto</th>
                                    <th>Total Bulto</th>
                                    <th>Total Bulto Rechazados</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error AQL</th>
                                    <th>Defectos</th>
                                    <th>Accion Correctiva</th>
                                    <th>Operario Responsable</th>
                                    <th>Reparacion Piezas</th>
                                    <th>Piezas de Bulto Rechazado</th>
                                </tr>
                            </thead>
                            <tbody id="tablaAQLGeneralTENuevoBody">
                                <!-- Aquí se insertarán los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="spinnerAQLTE" class="text-center my-3" style="display: none;">
                        <div class="loading-container">
                            <span class="loading-text">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de Proceso Tiempo Extra -->
        <div id="tablaProcesoTE" class="table-container" style="display: none;">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Tiempo
                        Extra</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Auditor</th>
                                    <th>Modulo</th>
                                    <th>Supervisor</th>
                                    <th>Estilo</th>
                                    <th>Recorridos</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Minutos Paro Modular</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error Proceso</th>
                                    <th>DEFECTOS</th>
                                    <th>ACCION CORRECTIVA</th>
                                    <th>Operarios</th>
                                </tr>
                            </thead>
                            <tbody id="tablaProcesoGeneralTENuevoBody">
                                <!-- Aquí se insertarán los datos dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <div id="spinnerPROCESOTE" class="text-center my-3" style="display: none;">
                        <div class="loading-container">
                            <span class="loading-text">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal reutilizable para AQL y AQL TE -->
    <div id="modalAQL" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <span class="custom-close" onclick="cerrarModalAQL()">&times;</span>
                <h3 id="modalAQLTitulo">Detalles de AQL</h3>
            </div>
            <div class="custom-modal-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>PARO</th>
                            <th>CLIENTE</th>
                            <th># BULTO</th>
                            <th>PIEZAS</th>
                            <th>TALLA</th>
                            <th>COLOR</th>
                            <th>ESTILO</th>
                            <th>PIEZAS INSPECCIONADAS</th>
                            <th>PIEZAS RECHAZADAS</th>
                            <th>TIPO DE DEFECTO</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody id="modalAQLBody">
                        <!-- Aquí se cargarán los registros vía JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal reutilizable para Proceso Normal y Tiempo Extra -->
    <div id="modalProceso" class="custom-modal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <span class="custom-close" onclick="cerrarModalProceso()">&times;</span>
                <h3 id="modalProcesoTitulo">Detalles de Proceso</h3>
            </div>
            <div class="custom-modal-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>PARO</th>
                            <th>CLIENTE</th>
                            <th>Nombre</th>
                            <th>Operacion</th>
                            <th>Piezas Auditadas</th>
                            <th>Piezas Rechazadas</th>
                            <th>Tipo de Problema</th>
                            <th>Acción Correctiva</th>
                            <th>pxp</th>
                            <th>Hora</th>
                        </tr>
                    </thead>
                    <tbody id="modalProcesoBody">
                        <!-- Aquí se insertarán dinámicamente los registros vía JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        /* Contenedor para centrar el texto */
        .loading-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Texto animado */
        .loading-text {
            font-size: 18px;
            font-weight: bold;
            color: #d1d1d1; /* Color para tema oscuro */
            
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centrar exactamente */
            
            animation: fadeInOut 1.5s infinite;
        }

        /* Animación de parpadeo */
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

    </style>
    <style>
        .custom-body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .custom-card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .custom-card-header {
            background-color: #2e7d32;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .custom-card-body {
            padding: 15px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #333;
        }

        .custom-table th {
            background-color: #2e2e2e;
        }

        .custom-btn {
            background-color: transparent;
            border: none;
            color: #4caf50;
            cursor: pointer;
            text-decoration: underline;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }

        .custom-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: fixed;
            right: 25px;
            top: 15px;
        }

        .custom-close:hover,
        .custom-close:focus {
            color: #fff;
        }

        .custom-modal-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #2e2e2e;
            padding: 15px;
            z-index: 1001;
        }

        .custom-modal-body {
            margin-top: 70px;
            /* Ajusta este valor según la altura de tu encabezado */
            padding: 15px;
        }
    </style>

    <!-- JavaScript -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Variables para controlar si ya se cargaron las consultas para la fecha actual
            let lastFecha = null;
            let aqlLoaded = false;
            let aqlteLoaded = false;
            let procesoLoaded = false;
            let procesoteLoaded = false;
            
            // Función para resetear las banderas (se llama cuando se cambia la fecha)
            function resetFlags() {
                aqlLoaded = false;
                aqlteLoaded = false;
                procesoLoaded = false;
                procesoteLoaded = false;
            }
            
            // Función para mostrar el contenedor de tabla correspondiente y ocultar los demás
            function showTable(tableToShow) {
                const tables = ["tablaAQL", "tablaAQLTE", "tablaProceso", "tablaProcesoTE"];
                tables.forEach(function(tableId) {
                    let elemento = document.getElementById(tableId);
                    if (elemento) {
                        elemento.style.display = (tableId === tableToShow) ? "block" : "none";
                    }
                });
            }
            
            // Evento en el botón "Mostrar datos"
            document.getElementById("btnMostrar").addEventListener("click", function () {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }
                // Si la fecha cambió, resetea las banderas
                if (lastFecha !== fechaInicio) {
                    resetFlags();
                    lastFecha = fechaInicio;
                }
                // Al hacer clic en btnMostrar se carga SOLO la consulta AQL (si aún no se ha cargado)
                if (!aqlLoaded) {
                    cargarDatos("{{ route('dashboardPlanta2V2.buscarAQLP2') }}", "tablaAQLGeneralNuevoBody", "datosModuloEstiloAQL");
                    aqlLoaded = true;
                }
                // Mostrar la tabla AQL por defecto
                showTable("tablaAQL");
            });
            
            // Evento para el botón "AQL"
            document.getElementById("showAQL").addEventListener("click", function () {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }
                // Si no se ha cargado aún, se ejecuta la consulta AQL
                if (!aqlLoaded) {
                    cargarDatos("{{ route('dashboardPlanta2V2.buscarAQLP2') }}", "tablaAQLGeneralNuevoBody", "datosModuloEstiloAQL");
                    aqlLoaded = true;
                }
                showTable("tablaAQL");
            });
            
            // Evento para el botón "AQL TE"
            document.getElementById("showAQLTE").addEventListener("click", function () {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }
                if (!aqlteLoaded) {
                    cargarDatos("{{ route('dashboardPlanta2V2.buscarAQLTEP2') }}", "tablaAQLGeneralTENuevoBody", "datosModuloEstiloAQLTE");
                    aqlteLoaded = true;
                }
                showTable("tablaAQLTE");
            });
            
            // Evento para el botón "Proceso"
            document.getElementById("showProceso").addEventListener("click", function () {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }
                if (!procesoLoaded) {
                    cargarDatosProceso("{{ route('dashboardPlanta2V2.buscarProcesoP2') }}", "tablaProcesoGeneralNuevoBody", "datosModuloEstiloProceso");
                    procesoLoaded = true;
                }
                showTable("tablaProceso");
            });
            
            // Evento para el botón "Proceso TE"
            document.getElementById("showProcesoTE").addEventListener("click", function () {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }
                if (!procesoteLoaded) {
                    cargarDatosProceso("{{ route('dashboardPlanta2V2.buscarProcesoTEP2') }}", "tablaProcesoGeneralTENuevoBody", "datosModuloEstiloProcesoTE");
                    procesoteLoaded = true;
                }
                showTable("tablaProcesoTE");
            });
            
            // Cuando se cambia la fecha, se resetean las banderas para que las consultas se vuelvan a cargar si se hace clic
            document.getElementById("fecha_inicio").addEventListener("change", function () {
                let nuevaFecha = this.value;
                if (nuevaFecha !== lastFecha) {
                    resetFlags();
                    lastFecha = nuevaFecha;
                }
            });
            
            // Función para cargar datos en tablas AQL y AQL TE (la estructura de la fila puede variar según el endpoint)
            function cargarDatos(url, tablaBodyId, dataKey) {
                let fechaInicio = document.getElementById("fecha_inicio").value;

                // Mostrar el spinner solo para AQL
                if (tablaBodyId === "tablaAQLGeneralNuevoBody") {
                    document.getElementById("spinnerAQL").style.display = "block";
                }
                // Mostrar el spinner solo para AQL TE
                if (tablaBodyId === "tablaAQLGeneralTENuevoBody") {
                    document.getElementById("spinnerAQLTE").style.display = "block";
                }

                fetch(url + "?fecha_inicio=" + fechaInicio, {
                    method: "GET",
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    let tablaBody = document.getElementById(tablaBodyId);
                    tablaBody.innerHTML = ""; // Limpiar contenido previo
                    let registros = data[dataKey];
                    if (registros && registros.length > 0) {
                        registros.forEach(item => {
                            // Para AQL y AQL TE se asume una estructura similar;
                            // si fuera necesario, se puede condicionar según dataKey o URL
                            let row = `<tr>
                                <td>${item.auditoresUnicos}</td>
                                <td>
                                    <button type="button" class="custom-btn" 
                                        onclick="abrirModalAQL('${item.modulo}', '${item.estilo}', '${tablaBodyId}')">
                                        ${item.modulo}
                                    </button>
                                </td>
                                <td>${item.supervisoresUnicos}</td>
                                <td>${item.estilosUnicos}</td>
                                <td>${item.conteoOperario}</td>
                                <td>${item.conteoMinutos}</td>
                                <td>${item.sumaMinutos}</td>
                                <td>${item.promedioMinutosEntero}</td>
                                <td>${item.conteParoModular}</td>
                                <td>${item.sumaParoModular}</td>
                                <td>${item.sumaPiezasBulto}</td>
                                <td>${item.cantidadBultosEncontrados}</td>
                                <td>${item.cantidadBultosRechazados}</td>
                                <td>${item.sumaAuditadaAQL}</td>
                                <td>${item.sumaRechazadaAQL}</td>
                                <td>${Number(item.porcentajeErrorAQL).toFixed(2)}%</td>
                                <td>${item.defectosUnicos}</td>
                                <td>${item.accionesCorrectivasUnicos}</td>
                                <td>${item.operariosUnicos}</td>
                                <td>${item.sumaReparacionRechazo}</td>
                                <td>${item.piezasRechazadasUnicas}</td>
                            </tr>`;
                            tablaBody.innerHTML += row;
                        });
                    } else {
                        tablaBody.innerHTML = `<tr><td colspan='9'>No hay datos disponibles para ${dataKey}.</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error("Error en AJAX:", error);
                })
                .finally(() => {
                    // Ocultar el spinner si era AQL
                    if (tablaBodyId === "tablaAQLGeneralNuevoBody") {
                        document.getElementById("spinnerAQL").style.display = "none";
                    }
                    // Ocultar el spinner si era AQL TE
                    if (tablaBodyId === "tablaAQLGeneralTENuevoBody") {
                        document.getElementById("spinnerAQLTE").style.display = "none";
                    }
                });
            }
            
            // Función para cargar datos en tablas de Proceso y Proceso TE
            function cargarDatosProceso(url, tablaBodyId, dataKey) {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                // Mostrar el spinner solo para PROCESO
                if (tablaBodyId === "tablaProcesoGeneralNuevoBody") {
                    document.getElementById("spinnerPROCESO").style.display = "block";
                }
                // Mostrar el spinner solo para PROCESO TE
                if (tablaBodyId === "tablaProcesoGeneralTENuevoBody") {
                    document.getElementById("spinnerPROCESOTE").style.display = "block";
                }
                fetch(url + "?fecha_inicio=" + fechaInicio, {
                    method: "GET",
                    headers: { "X-Requested-With": "XMLHttpRequest" }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    let tablaBody = document.getElementById(tablaBodyId);
                    tablaBody.innerHTML = ""; // Limpiar contenido previo
                    let registros = data[dataKey];
                    if (registros && registros.length > 0) {
                        registros.forEach(item => {
                            let row = `<tr>
                                <td>${item.auditoresUnicos}</td>
                                <td>
                                    <button type="button" class="custom-btn" 
                                        onclick="abrirModalProceso('${item.modulo}', '${item.estilo}', '${tablaBodyId}')">
                                        ${item.modulo}
                                    </button>
                                </td>
                                <td>${item.supervisoresUnicos}</td>
                                <td>${item.estilo}</td>
                                <td>${item.cantidadRecorridos}</td>
                                <td>${item.conteoOperario}</td>
                                <td>${item.conteoUtility}</td>
                                <td>${item.conteoMinutos}</td>
                                <td>${item.sumaMinutos}</td>
                                <td>${item.promedioMinutosEntero}</td>
                                <td>${item.conteParoModular}</td>
                                <td>${item.sumaParoModular}</td>
                                <td>${item.sumaAuditadaProceso}</td>
                                <td>${item.sumaRechazadaProceso}</td>
                                <td>${Number(item.porcentajeErrorProceso).toFixed(2)}%</td>
                                <td>${item.defectosUnicos}</td>
                                <td>${item.accionesCorrectivasUnicos}</td>
                                <td>${item.operariosUnicos}</td>
                            </tr>`;
                            tablaBody.innerHTML += row;
                        });
                    } else {
                        tablaBody.innerHTML = `<tr><td colspan='10'>No hay datos disponibles para ${dataKey}.</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error("Error en AJAX:", error);
                })
                .finally(() => {
                    // Ocultar el spinner si era PROCESO
                    if (tablaBodyId === "tablaProcesoGeneralNuevoBody") {
                            document.getElementById("spinnerPROCESO").style.display = "none";
                        }
                    // Ocultar el spinner si era PROCESO TE
                    if (tablaBodyId === "tablaProcesoGeneralTENuevoBody") {
                        document.getElementById("spinnerPROCESOTE").style.display = "none";
                    }
                });
            }
        });
    </script>

    <script>
        let activeModalId = null;

        function abrirModalAQL(modulo, estilo, tablaOrigenId) {
            // Mostrar modal vacío
            const modal = document.getElementById("modalAQL");
            const tbody = document.getElementById("modalAQLBody");
            const titulo = document.getElementById("modalAQLTitulo");
            tbody.innerHTML = `<tr><td colspan="11">Cargando...</td></tr>`; // Spinner simple

            // Abrir modal
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
            activeModalId = "modalAQL";

            // Asignar título dinámico
            titulo.textContent = `Detalles de AQL para Módulo ${modulo}, Estilo: ${estilo}`;

            // Determinar si es tiempo extra o no
            const tiempo_extra = (tablaOrigenId === "tablaAQLGeneralTENuevoBody") ? 1 : null;
            const fecha = document.getElementById("fecha_inicio").value;

            // Hacer fetch a un endpoint que te pasaré después
            fetch(`dashboardPlanta1V2P2/buscarAQL/detalles?modulo=${modulo}&estilo=${estilo}&fecha=${fecha}&tiempo_extra=${tiempo_extra}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        tbody.innerHTML = `<tr><td colspan="11">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="11">No hay registros.</td></tr>`;
                        return;
                    }

                    let rows = "";
                    data.forEach(registro => {
                        rows += `<tr>
                            <td>${registro.minutos_paro ?? 'N/A'}</td>
                            <td>${registro.cliente ?? 'N/A'}</td>
                            <td>${registro.bulto ?? 'N/A'}</td>
                            <td>${registro.pieza ?? 'N/A'}</td>
                            <td>${registro.talla ?? 'N/A'}</td>
                            <td>${registro.color ?? 'N/A'}</td>
                            <td>${registro.estilo ?? 'N/A'}</td>
                            <td>${registro.cantidad_auditada ?? 'N/A'}</td>
                            <td>${registro.cantidad_rechazada ?? 'N/A'}</td>
                            <td>${registro.defectos ?? 'N/A'}</td>
                            <td>${registro.hora ?? 'N/A'}</td>
                        </tr>`;
                    });
                    tbody.innerHTML = rows;
                })
                .catch(err => {
                    tbody.innerHTML = `<tr><td colspan="11">Error al cargar detalles.</td></tr>`;
                    console.error(err);
                });
        }

        function cerrarModalAQL() {
            document.getElementById("modalAQL").style.display = "none";
            document.body.style.overflow = "auto";
            activeModalId = null;
        }

        // Cerrar al hacer clic fuera del modal
        window.onclick = function(event) {
            if (event.target.id === "modalAQL") {
                cerrarModalAQL();
            }
        };

        // Cerrar con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && activeModalId === "modalAQL") {
                cerrarModalAQL();
            }
        });
    </script>

    <script>
        let activeModalIdProceso = null;

        function abrirModalProceso(modulo, estilo, tablaOrigenId) {
            // Mostrar modal vacío con mensaje "Cargando..."
            const modal = document.getElementById("modalProceso");
            const tbody = document.getElementById("modalProcesoBody");
            const titulo = document.getElementById("modalProcesoTitulo");
            tbody.innerHTML = `<tr><td colspan="10">Cargando...</td></tr>`; // Spinner simple

            // Abrir modal
            modal.style.display = "block";
            document.body.style.overflow = "hidden";
            activeModalIdProceso = "modalProceso";

            // Asignar título dinámico
            titulo.textContent = `Detalles de Proceso para Módulo ${modulo}, Estilo: ${estilo}`;

            // Determinar si es tiempo extra
            const tiempo_extra = (tablaOrigenId.includes("TE")) ? 1 : null;
            const fecha = document.getElementById("fecha_inicio").value;

            // Fetch a endpoint (lo verás en el siguiente paso del backend)
            fetch(`dashboardPlanta1V2P2/buscarProceso/detalles?modulo=${modulo}&estilo=${estilo}&fecha=${fecha}&tiempo_extra=${tiempo_extra}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        tbody.innerHTML = `<tr><td colspan="10">${data.error}</td></tr>`;
                        return;
                    }

                    if (data.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="10">No hay registros.</td></tr>`;
                        return;
                    }

                    let rows = "";
                    data.forEach(registro => {
                        rows += `<tr>
                            <td>${registro.minutos_paro ?? 'N/A'}</td>
                            <td>${registro.cliente ?? 'N/A'}</td>
                            <td>${registro.nombre ?? 'N/A'}</td>
                            <td>${registro.operacion ?? 'N/A'}</td>
                            <td>${registro.cantidad_auditada ?? 'N/A'}</td>
                            <td>${registro.cantidad_rechazada ?? 'N/A'}</td>
                            <td>${registro.tipo_problema ?? 'N/A'}</td>
                            <td>${registro.ac ?? 'N/A'}</td>
                            <td>${registro.pxp ?? 'N/A'}</td>
                            <td>${registro.hora ?? 'N/A'}</td>
                        </tr>`;
                    });

                    tbody.innerHTML = rows;
                })
                .catch(err => {
                    tbody.innerHTML = `<tr><td colspan="10">Error al cargar detalles.</td></tr>`;
                    console.error(err);
                });
        }

        function cerrarModalProceso() {
            document.getElementById("modalProceso").style.display = "none";
            document.body.style.overflow = "auto";
            activeModalIdProceso = null;
        }

        // Cierre al dar clic fuera del modal
        window.onclick = function(event) {
            if (event.target.id === "modalProceso") {
                cerrarModalProceso();
            }
        };

        // Cierre con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && activeModalIdProceso === "modalProceso") {
                cerrarModalProceso();
            }
        });
    </script>

    
@endsection
