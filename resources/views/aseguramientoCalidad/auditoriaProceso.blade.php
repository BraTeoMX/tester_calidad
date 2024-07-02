@extends('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')])

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
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">{{ $data['area'] }}</h3>
                        </div>
                        <div class="col-auto">
                            <h4>Fecha:
                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <form method="POST" action="{{ route('aseguramientoCalidad.formRegistroAuditoriaProceso') }}">
                        @csrf
                        <input type="hidden" class="form-control" name="area" id="area"
                            value="{{ $data['area'] }}">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>MODULO</th>
                                        <th>ESTILO</th>
                                        <th>TEAM LEADER</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                        <th>CLIENTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control texto-blanco" name="modulo" id="modulo"
                                                value="{{ $data['modulo'] }}" readonly></td>
                                        <td>
                                            <select class="form-control texto-blanco" name="estilo" id="estilo" required onchange="actualizarEstilo(this.value)">
                                                <option value="">Selecciona una opción</option>
                                                @foreach($estilos as $estilo)
                                                    <option value="{{ $estilo }}" {{ $estilo == $data['estilo'] ? 'selected' : '' }}>{{ $estilo }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader"
                                                value="{{ $data['team_leader'] }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco" name="auditor" id="auditor"
                                                value="{{ $data['auditor'] }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco" name="turno" id="turno"
                                                value="{{ $data['turno'] }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco" name="cliente" id="cliente"
                                                value="{{ $data['cliente'] }}" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        @if ($estatusFinalizar)
                        @else
                            <div class="table-responsive">
                                <table class="table flex-container table32">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>OPERACION</th>
                                            <th>PIEZAS AUDITADAS</th>
                                            <th>PIEZAS RECHAZADAS</th>
                                            <th id="tp-column-header" class="d-none">TIPO DE PROBLEMA</th>
                                            <th id="ac-column-header" class="d-none">ACCION CORRECTIVA</th>
                                            @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                            @else
                                                <th>P x P</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="nombre_hidden" id="nombre_hidden" value="">
                                            <td>
                                                <button class="btn btn-secondary btn-block" type="button" onclick="resetForm()">Restablecer</button>
                                                <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción" onchange="showOtherOptions()">
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="OTRO">OTRO</option>
                                                    <option value="UTILITY">UTILITY</option>
                                                    @if ($auditorPlanta == 'Planta1')
                                                        @foreach ($nombresPlanta1 as $nombre)
                                                            <option value="{{ $nombre->name }}">{{ $nombre->name }}</option>
                                                        @endforeach
                                                    @elseif($auditorPlanta == 'Planta2')
                                                        @foreach ($nombresPlanta2 as $nombre)
                                                            <option value="{{ $nombre->name }}">{{ $nombre->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <div id="otroOptions" style="display: none;">
                                                    <select name="modulo_adicional" id="module" class="form-control" onchange="loadNames()">
                                                        <option value="">Selecciona un módulo</option>
                                                    </select>
                                                    <select name="nombre_otro" id="name" class="form-control">
                                                        <option value="">Selecciona un nombre</option>
                                                    </select>
                                                </div>
                                                <div id="utilityOptions" style="display: none;">
                                                    <select name="nombre_utility" id="utility" class="form-control">
                                                        <option value="">Selecciona un Utility</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td><input type="text" class="form-control texto-blanco" name="operacion" id="operacion" required></td>
                                            <td><input type="text" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                            <td><input type="text" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                            <td class="tp-column d-none">
                                                <select name="tp[]" id="tpSelect" class="form-control" multiple title="Por favor, selecciona una opción"> 
                                                    <option value="OTRO">OTRO</option>
                                                    @if ($data['area'] == 'AUDITORIA EN PROCESO')
                                                        @foreach ($categoriaTPProceso as $proceso)
                                                            <option value="{{ $proceso->nombre }}">{{ $proceso->nombre }}</option>
                                                        @endforeach
                                                    @elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA')
                                                        @foreach ($categoriaTPPlayera as $playera)
                                                            <option value="{{ $playera->nombre }}">{{ $playera->nombre }}</option>
                                                        @endforeach
                                                    @elseif($data['area'] == 'AUDITORIA EN EMPAQUE')
                                                        @foreach ($categoriaTPEmpaque as $empque)
                                                            <option value="{{ $empque->nombre }}">{{ $empque->nombre }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td class="ac-column d-none">
                                                <select name="ac" id="ac" class="form-control" title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    @if ($data['area'] == 'AUDITORIA EN PROCESO')
                                                        @foreach ($categoriaACProceso as $proceso)
                                                            <option value="{{ $proceso->accion_correctiva }}">{{ $proceso->accion_correctiva }}</option>
                                                        @endforeach
                                                    @elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA')
                                                        @foreach ($categoriaACPlayera as $playera)
                                                            <option value="{{ $playera->accion_correctiva }}">{{ $playera->accion_correctiva }}</option>
                                                        @endforeach
                                                    @elseif($data['area'] == 'AUDITORIA EN EMPAQUE')
                                                        @foreach ($categoriaACEmpaque as $empque)
                                                            <option value="{{ $empque->accion_correctiva }}">{{ $empque->accion_correctiva }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                            @else
                                                <td><input type="text" class="form-control" name="pxp" id="pxp"></td>
                                            @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn-verde-xd">GUARDAR</button>
                        @endif
                    </form>
                    <!-- Modal -->
                    <div class="modal fade" id="nuevoConceptoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoConceptoModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content bg-dark text-white">
                                <div class="modal-header">
                                    <h5 id="nuevoConceptoModalLabel">Introduce el nuevo concepto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" class="text-white">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInput" placeholder="Nuevo concepto">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary" id="guardarNuevoConcepto">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    @if ($mostrarRegistro)
                        @if ($estatusFinalizar)
                            <h2>Registro</h2>
                            <table class="table table1">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Operacion </th>
                                        <th>Piezas Auditadas</th>
                                        <th>Piezas Rechazadas</th>
                                        <th>Tipo de Problema </th>
                                        <th>Accion Correctiva </th>
                                        @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                        @else
                                            <th>pxp </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mostrarRegistro as $registro)
                                        <tr>
                                            <form action="{{ route('aseguramientoCalidad.formUpdateDeleteProceso') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $registro->id }}">
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="nombre"
                                                        value="{{ $registro->nombre }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="{{ $registro->operacion }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_auditada"
                                                        value="{{ $registro->cantidad_auditada }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_rechazada"
                                                        value="{{ $registro->cantidad_rechazada }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="tp"
                                                        value="{{ $registro->tp }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="ac"
                                                        value="{{ $registro->ac }}" readonly>
                                                </td>
                                                @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                                @else
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" name="pxp"
                                                            value="{{ $registro->pxp }}" readonly>

                                                    </td>
                                                @endif
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="observacion" class="col-sm-6 col-form-label">Observaciones:</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control texto-blanco" name="observacion" id="observacion" rows="3" readonly>{{ $registro->observacion }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <h2>Registro</h2>

                                <table class="table table1">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Paro</th>
                                            <th>Nombre</th>
                                            <th>Operacion </th>
                                            <th>Piezas Auditadas</th>
                                            <th>Piezas Rechazadas</th>
                                            <th>Tipo de Problema </th>
                                            <th>Accion Correctiva </th>
                                            @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                            @else
                                                <th>PxP </th>
                                            @endif
                                            <th>Eliminar </th>
                                            <th>Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mostrarRegistro as $registro)
                                            <tr>
                                                <td>
                                                    @if($registro->inicio_paro == NULL)
                                                        -
                                                    @elseif($registro->fin_paro != NULL)
                                                        {{$registro->minutos_paro}}
                                                    @elseif($registro->fin_paro == NULL)
                                                        <form method="POST" action="{{ route('aseguramientoCalidad.cambiarEstadoInicioParo') }}">
                                                            @csrf
                                                            <input type="hidden" name="idCambio" value="{{ $registro->id }}">
                                                            <button type="submit" class="btn btn-primary">Fin Paro Proceso</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="nombre"
                                                        value="{{ $registro->nombre }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="{{ $registro->operacion }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_auditada"
                                                        value="{{ $registro->cantidad_auditada }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_rechazada"
                                                        value="{{ $registro->cantidad_rechazada }}" readonly>
                                                </td>
                                                <form action="{{ route('aseguramientoCalidad.formUpdateDeleteProceso') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $registro->id }}">
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" readonly
                                                               value="{{ implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray()) }}">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" name="ac"
                                                        value="{{ $registro->ac }}" readonly>
                                                    </td>
                                                    @if ($data['area'] == 'AUDITORIA EN EMPAQUE')
                                                    @else
                                                        <td>
                                                            <input type="text" class="form-control texto-blanco" name="pxp_text"
                                                                id="pxp_text_{{ $registro->id }}"
                                                                value="{{ $registro->pxp }}" readonly>
                                                            <input type="hidden" name="pxp"
                                                                id="pxp_hidden_{{ $registro->id }}"
                                                                value="{{ $registro->pxp }}">
                                                        </td>
                                                        <script>
                                                            document.getElementById('pxp_text_{{ $registro->id }}').addEventListener('input', function() {
                                                                document.getElementById('pxp_hidden_{{ $registro->id }}').value = this.value;
                                                            });
                                                        </script>
                                                    @endif
                                                    <td>
                                                        <button type="submit" name="action" value="delete"
                                                            class="btn btn-danger">Eliminar</button>
                                                    </td>
                                                    <td>
                                                        {{ $registro->created_at->format('H:i:s') }}
                                                    </td>
                                                </form>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <form action="{{ route('aseguramientoCalidad.formFinalizarProceso') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="area" value="{{ $data['area'] }}">
                                    <input type="hidden" name="modulo" value="{{ $data['modulo'] }}">
                                    <input type="hidden" name="estilo" value="{{ $data['estilo'] }}">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="observacion"
                                                class="col-sm-6 col-form-label">Observaciones:</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control texto-blanco" name="observacion" id="observacion" rows="3" placeholder="comentarios"
                                                    required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <button type="submit" name="action"
                                                class="btn btn-danger">Finalizar</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        @endif
                    @else
                        <div>
                            <h2> sin registros el dia de hoy</h2>
                        </div>
                    @endif
                    <hr>
                    <div class="table-responsive">
                        <h2>Total Individual</h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Nombre </th>
                                    <th>No. Recorridos </th>
                                    <th>Total Piezas Auditada</th>
                                    <th>Total Piezas Rechazada</th>
                                    <th>Porcentaje Rechazado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrosIndividual as $registro)
                                    <tr>
                                        <td><input type="text" class="form-control texto-blanco" value="{{ $registro->nombre }}"
                                                readonly></td>
                                        <td><input type="text" class="form-control texto-blanco" 
                                            value="{{ $registro->cantidad_registros }}" readonly></td> 
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_auditada }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada != 0 ? round(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0 }}"
                                                readonly></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <h2>Total General </h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Total de Piezas Auditadas</th>
                                    <th>Total de Piezas Rechazados</th>
                                    <th>Porcentaje Rechazo Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control texto-blanco" name="total_auditada"
                                            id="total_auditada" value="{{ $total_auditada }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_rechazada"
                                            id="total_rechazada" value="{{ $total_rechazada }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_porcentaje"
                                            id="total_porcentaje" value="{{ number_format($total_porcentaje, 2) }}"
                                            readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
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

        .table1 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(6) {
            min-width: 250px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(7) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table1 th:nth-child(2) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .table32 th:nth-child(5) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .texto-blanco {
            color: white !important;
        }
    </style>

    <script>
        function showOtherOptions() {
            var select = document.getElementById("nombre");
            var otroOptions = document.getElementById("otroOptions");
            var utilityOptions = document.getElementById("utilityOptions");
            var nombreHidden = document.getElementById("nombre_hidden");

            if (select.value !== "OTRO" && select.value !== "UTILITY") {
                select.style.display = "block";
                select.disabled = false;
                otroOptions.style.display = "none";
                utilityOptions.style.display = "none";
                nombreHidden.value = select.value; // Actualiza el campo oculto con el valor seleccionado del primer select
            } else if (select.value === "UTILITY") {
                select.style.display = "none";
                select.disabled = true;
                otroOptions.style.display = "none";
                utilityOptions.style.display = "block";
                loadUtilities(); // Cargar los utilities disponibles
            } else {
                select.style.display = "none";
                select.disabled = true;
                otroOptions.style.display = "block";
                utilityOptions.style.display = "none";
                loadModules(); // Cargar los módulos disponibles
            }
        }

        function loadModules() {
            fetch("{{ route('modules.getModules') }}")
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById("module");
                    select.innerHTML = "";
                    data.forEach(module => {
                        var option = document.createElement("option");
                        option.text = module.moduleid;
                        option.value = module.moduleid;
                        select.appendChild(option);
                    });
                });
        }

        function loadNames() {
            var moduleid = document.getElementById("module").value;
            fetch("{{ route('modules.getNamesByModule') }}?moduleid=" + moduleid)
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById("name");
                    select.innerHTML = "";
                    data.forEach(name => {
                        var option = document.createElement("option");
                        option.text = name.name;
                        option.value = name.name;
                        select.appendChild(option);
                    });
                });
        }

        // Cargar los módulos iniciales
        loadModules();
        function loadUtilities() {
            fetch("{{ route('utilities.getUtilities') }}")
                .then(response => response.json())
                .then(data => {
                    var select = document.getElementById("utility");
                    select.innerHTML = "";
                    data.forEach(utility => {
                        var option = document.createElement("option");
                        option.text = utility.nombre; // Usa 'nombre' en lugar de 'name'
                        option.value = utility.nombre; // Usa 'nombre' en lugar de 'name'
                        select.appendChild(option);
                    });
                });
        }
        
    </script>
    <script>
        function resetForm() {
            var select = document.getElementById("nombre");
            var otroOptions = document.getElementById("otroOptions");
            var utilityOptions = document.getElementById("utilityOptions");
            var nombreHidden = document.getElementById("nombre_hidden");

            select.style.display = "block";
            select.disabled = false;
            otroOptions.style.display = "none";
            utilityOptions.style.display = "none";
            nombreHidden.value = ""; // Restablecer el valor del campo oculto

            // Limpiar select de módulos y nombres si fuera necesario
            var moduleSelect = document.getElementById("module");
            moduleSelect.innerHTML = "<option value=''>Selecciona un módulo</option>";

            var nameSelect = document.getElementById("name");
            nameSelect.innerHTML = "<option value=''>Selecciona un nombre</option>";

            // Cargar los módulos iniciales
            loadModules();
        }

    </script>
    <!-- Nuevo script para manejar la visibilidad de las columnas y select2 -->
    <script>
        $(document).ready(function() {
            $('#tpSelect').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true
            });

            $('#tpSelect').on('change', function() {
                let selectedOptions = $(this).val();
                if (selectedOptions.includes('OTRO')) {
                    $('#nuevoConceptoModal').modal('show');
                }
            });

            $('#guardarNuevoConcepto').on('click', function() {
                let nuevoConcepto = $('#nuevoConceptoInput').val();
                if (nuevoConcepto) {
                    let area = '';
                    @if ($data['area'] == 'AUDITORIA EN PROCESO')
                        area = 'proceso';
                    @elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA')
                        area = 'playera';
                    @elseif($data['area'] == 'AUDITORIA EN EMPAQUE')
                        area = 'empaque';
                    @endif

                    fetch('{{ route('categoria_tipo_problema.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            nombre: nuevoConcepto.toUpperCase(),
                            area: area
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let newOption = new Option(nuevoConcepto.toUpperCase(), nuevoConcepto.toUpperCase(), true, true);
                            $('#tpSelect').append(newOption).trigger('change');
                            $('#nuevoConceptoModal').modal('hide');
                        } else {
                            alert('Error al guardar el nuevo concepto');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        alert('Error al guardar el nuevo concepto');
                    });
                } else {
                    alert('Por favor, introduce un concepto válido');
                }
            });

            $('#nuevoConceptoModal').on('hidden.bs.modal', function () {
                $('#nuevoConceptoInput').val('');
                let selectedOptions = $('#tpSelect').val();
                let index = selectedOptions.indexOf('OTRO');
                if (index > -1) {
                    selectedOptions.splice(index, 1);
                    $('#tpSelect').val(selectedOptions).trigger('change');
                }
            });

            function updateColumnsVisibility() {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                if (isNaN(cantidadRechazada) || cantidadRechazada === 0) {
                    $('#tp-column-header, #ac-column-header').addClass('d-none');
                    $('.tp-column, .ac-column').addClass('d-none');
                    $('#tpSelect, #ac').prop('required', false);
                } else {
                    $('#tp-column-header, #ac-column-header').removeClass('d-none');
                    $('.tp-column, .ac-column').removeClass('d-none');
                    $('#tpSelect, #ac').prop('required', true);
                }
            }

            // Inicializar la visibilidad de las columnas al cargar la página
            updateColumnsVisibility();

            // Actualizar la visibilidad de las columnas al cambiar el valor de cantidad_rechazada
            $('#cantidad_rechazada').on('input', function() {
                updateColumnsVisibility();
            });
        });
    </script>

    <script>
        function actualizarEstilo(nuevoEstilo) {
            // Obtener la URL actual
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
        
            // Actualizar el parámetro 'estilo'
            params.set('estilo', nuevoEstilo);
        
            // Construir la nueva URL
            url.search = params.toString();
        
            // Redirigir a la nueva URL
            window.location.href = url.toString();
        }
    </script>
@endsection
