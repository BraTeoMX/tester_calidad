@extends('layouts.app', ['pageSlug' => 'dashboardPorDia', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta por dia Planta 1
                        - Ixtlahuaca </h2>
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
                <h5><i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos</h5>
            </label>
        </div>
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
            </div>
        </div>
    </div>

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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("btnMostrar").addEventListener("click", function() {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }

                // Cargar datos SOLO para AQL al inicio
                cargarDatos("{{ route('dashboardPlanta1V2.buscarAQL') }}", "tablaAQLGeneralNuevoBody",
                    "datosModuloEstiloAQL");
                cargarDatos("{{ route('dashboardPlanta1V2.buscarAQLTE') }}", "tablaAQLGeneralTENuevoBody",
                    "datosModuloEstiloAQLTE");

                // Mostrar la tabla de AQL por defecto
                document.getElementById("tablaAQL").style.display = "block";
                document.getElementById("tablaProceso").style.display = "none";
            });

            document.getElementById("showAQL").addEventListener("click", function() {
                document.getElementById("tablaAQL").style.display = "block";
                document.getElementById("tablaProceso").style.display = "none";
            });
        });

        // Función para cargar datos en las tablas AQL
        function cargarDatos(url, tablaBodyId, dataKey) {
            let fechaInicio = document.getElementById("fecha_inicio").value;

            fetch(url + "?fecha_inicio=" + fechaInicio, {
                    method: "GET",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    let tablaBody = document.getElementById(tablaBodyId);
                    tablaBody.innerHTML = ""; // Limpiar contenido anterior

                    let registros = data[dataKey];

                    if (registros && registros.length > 0) {
                        registros.forEach(item => {
                            let row = `<tr>
                                <td>${item.auditoresUnicos}</td>
                                <td>
                                    <button type="button" class="custom-btn" 
                                        onclick="openCustomModal('customModalAQL${item.modulo}_${item.estilo}')">
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
                .catch(error => console.error("Error en AJAX:", error));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let procesoCargado = false; // Evita recargar Proceso si ya se cargó
            let procesoTECargado = false; // Evita recargar Proceso TE si ya se cargó

            // 1️⃣ Cargar SOLO AQL cuando se da clic en "Mostrar Datos"
            document.getElementById("btnMostrar").addEventListener("click", function() {
                let fechaInicio = document.getElementById("fecha_inicio").value;
                if (!fechaInicio) {
                    alert("Por favor selecciona una fecha.");
                    return;
                }

                // Cargar los datos de AQL y AQL TE
                cargarDatos("{{ route('dashboardPlanta1V2.buscarAQL') }}", "tablaAQLGeneralNuevoBody", "datosModuloEstiloAQL");
                cargarDatos("{{ route('dashboardPlanta1V2.buscarAQLTE') }}", "tablaAQLGeneralTENuevoBody", "datosModuloEstiloAQLTE");

                // Mostrar la tabla de AQL normal por defecto
                document.getElementById("tablaAQL").style.display = "block";
                document.getElementById("tablaProceso").style.display = "none";
                document.getElementById("tablaAQLTE").style.display = "none";
                document.getElementById("tablaProcesoTE").style.display = "none";
            });

            // 2️⃣ Mostrar u Ocultar AQL y Proceso (Turno Normal)
            document.getElementById("showAQL").addEventListener("click", function() {
                document.getElementById("tablaAQL").style.display = "block";
                document.getElementById("tablaProceso").style.display = "none";
                document.getElementById("tablaAQLTE").style.display = "none";
                document.getElementById("tablaProcesoTE").style.display = "none";
            });

            document.getElementById("showProceso").addEventListener("click", function() {
                document.getElementById("tablaAQL").style.display = "none";
                document.getElementById("tablaProceso").style.display = "block";
                document.getElementById("tablaAQLTE").style.display = "none";
                document.getElementById("tablaProcesoTE").style.display = "none";

                if (!procesoCargado) {
                    let fechaInicio = document.getElementById("fecha_inicio").value;
                    if (!fechaInicio) {
                        alert("Por favor selecciona una fecha.");
                        return;
                    }

                    // Cargar datos de Proceso por primera vez
                    cargarDatosProceso("{{ route('dashboardPlanta1V2.buscarProceso') }}", "tablaProcesoGeneralNuevoBody", "datosModuloEstiloProceso");
                    procesoCargado = true;
                }
            });

            // 3️⃣ Mostrar u Ocultar AQL TE y Proceso TE (Tiempo Extra)
            document.getElementById("showAQLTE").addEventListener("click", function() {
                document.getElementById("tablaAQL").style.display = "none";
                document.getElementById("tablaProceso").style.display = "none";
                document.getElementById("tablaAQLTE").style.display = "block";
                document.getElementById("tablaProcesoTE").style.display = "none";
            });

            document.getElementById("showProcesoTE").addEventListener("click", function() {
                document.getElementById("tablaAQL").style.display = "none";
                document.getElementById("tablaProceso").style.display = "none";
                document.getElementById("tablaAQLTE").style.display = "none";
                document.getElementById("tablaProcesoTE").style.display = "block";

                if (!procesoTECargado) {
                    let fechaInicio = document.getElementById("fecha_inicio").value;
                    if (!fechaInicio) {
                        alert("Por favor selecciona una fecha.");
                        return;
                    }

                    // Cargar datos de Proceso TE por primera vez
                    cargarDatosProceso("{{ route('dashboardPlanta1V2.buscarProcesoTE') }}", "tablaProcesoGeneralTENuevoBody", "datosModuloEstiloProcesoTE");
                    procesoTECargado = true;
                }
            });
        });

        // Función para cargar datos en las tablas de Proceso
        function cargarDatosProceso(url, tablaBodyId, dataKey) {
            let fechaInicio = document.getElementById("fecha_inicio").value;

            fetch(url + "?fecha_inicio=" + fechaInicio, {
                    method: "GET",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    let tablaBody = document.getElementById(tablaBodyId);
                    tablaBody.innerHTML = ""; // Limpiar contenido anterior

                    let registros = data[dataKey];

                    if (registros && registros.length > 0) {
                        registros.forEach(item => {
                            let row = `<tr>
                                <td>${item.auditoresUnicos}</td>
                                <td>
                                    <button type="button" class="custom-btn" 
                                        onclick="openCustomModal('customModalProceso${item.modulo}_${item.estilo}')">
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
                        tablaBody.innerHTML =
                        `<tr><td colspan='10'>No hay datos disponibles para ${dataKey}.</td></tr>`;
                    }
                })
                .catch(error => console.error("Error en AJAX:", error));
        }
    </script>
@endsection
