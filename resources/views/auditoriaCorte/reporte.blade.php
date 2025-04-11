@extends('layouts.app', ['pageSlug' => 'consulta_corte_final', 'titlePage' => __('consulta_corte_final')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta Corte</h2>
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
                    <div id="spinnerAQL" class="text-center my-3" style="display: none;">
                        <div class="loading-container">
                            <span class="loading-text">Cargando...</span>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaAQLGeneralNuevo">
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
                        <div id="spinnerPROCESO" class="text-center my-3" style="display: none;">
                            <div class="loading-container">
                                <span class="loading-text">Cargando...</span>
                            </div>
                        </div>
                        <table class="table tablesorter" id="tablaProcesoGeneralNuevo">
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

@endsection
