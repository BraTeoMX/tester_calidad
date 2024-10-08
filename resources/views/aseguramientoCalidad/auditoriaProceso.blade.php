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
                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalProcesos">
                                <h4>Fecha:
                                  {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                </h4>
                            </button>                              
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modalProcesos" tabindex="-1" role="dialog" aria-labelledby="modalProcesosLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content bg-dark">
                            <div class="modal-header">
                            <h5 class="modal-title texto-blanco" id="modalProcesosLabel">Detalles del Proceso</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <!-- Aquí va tu contenido de la tabla -->
                                <div class="table-responsive">
                                    <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Acción</th>
                                                <th>Módulo</th>
                                                <th>Estilo</th>
                                                <th>Supervisor</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaProcesos1">
                                            @foreach($procesoActual as $proceso)
                                            <tr>
                                                <td>
                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                        @csrf
                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                        <input type="hidden" name="gerente_produccion" value="{{ $proceso->gerente_produccion }}">
                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                    </form>
                                                </td>
                                                <td>{{ $proceso->modulo }}</td>
                                                <td>{{ $proceso->estilo }}</td>
                                                <td>{{ $proceso->team_leader }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <script>
                                    $(document).ready(function() {
                                        $('#searchInput1').on('keyup', function() {
                                            var value = $(this).val().toLowerCase();
                                            $('#tablaProcesos1 tr').filter(function() {
                                                var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                                                var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                                                $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    @if((($conteoParos == 3) && ($finParoModular1 == true)) || (($conteoParos == 6) && ($finParoModular2 == true))) 
                        <div class="row">
                            <form method="POST" action="{{ route('aseguramientoCalidad.cambiarEstadoInicioParo') }}">
                                @csrf
                                <input type="hidden" name="finalizar_paro_modular" value="1">
                                <input type="hidden" class="form-control" name="modulo" id="modulo" value="{{ $data['modulo'] }}">
                                <input type="hidden" class="form-control" name="estilo" id="estilo1" value="{{ $data['estilo'] }}"> 
                                <input type="hidden" class="form-control" name="area" id="area" value="{{ $data['area'] }}">
                                <input type="hidden" class="form-control" name="team_leader" id="team_leader" value="{{ $data['team_leader'] }}">
                                <input type="hidden" class="form-control" name="gerente_produccion" value="{{ $data['gerente_produccion'] }}">
        
        
                                <button type="submit" class="btn btn-primary">Fin Paro Modular Proceso</button> 
                            </form>
                        </div>
                    @else
                        <form id="miFormulario" method="POST" action="{{ route('aseguramientoCalidad.formRegistroAuditoriaProceso') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="area" id="area"
                                value="{{ $data['area'] }}">
                            <div class="table-responsive">
                                <table class="table table-200">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>MODULO</th>
                                            <th>ESTILO</th>
                                            <th>SUPERVISOR</th>
                                            <th>GERENTE PRODUCCION</th>
                                            <th>AUDITOR</th>
                                            <th>TURNO</th>
                                            <th>CLIENTE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control texto-blanco" name="modulo" id="modulo"
                                                    value="{{ $data['modulo'] }}" readonly></td>
                                            @if($data['modulo'] == "830A" || $data['modulo'] == "831A")
                                            <td>
                                                <select class="form-control texto-blanco" name="estilo" id="estilo" required onchange="actualizarEstilo(this.value)">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach($estilosEmpaque as $estilo)
                                                        <option value="{{ $estilo }}" {{ $estilo == $data['estilo'] ? 'selected' : '' }}>{{ $estilo }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @else
                                            <td>
                                                <select class="form-control texto-blanco" name="estilo" id="estilo" required onchange="actualizarEstilo(this.value)">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach($estilos as $estilo)
                                                        <option value="{{ $estilo }}" {{ $estilo == $data['estilo'] ? 'selected' : '' }}>{{ $estilo }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            @endif
                                            <td><input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader"
                                                    value="{{ $data['team_leader'] }}" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="gerente_produccion" 
                                                value="{{ $data['gerente_produccion'] }}" readonly></td>
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
                                    <table class="table flex-container table932">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>NOMBRE</th>
                                                @if($data['modulo'] == "830A" || $data['modulo'] == "831A")

                                                @else
                                                    <th>OPERACION</th>
                                                @endif
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
                                                        @foreach ($nombresPlanta as $nombre)
                                                            <option value="{{ $nombre->name }}">{{ $nombre->name }}</option>
                                                        @endforeach
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
                                                @if($data['modulo'] == "830A" || $data['modulo'] == "831A")

                                                @else
                                                <td>
                                                    <select name="operacion" id="operacion" class="form-control" required title="Por favor, selecciona una opción" onchange="cambiarAInput(this)">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="otra"> [OTRA OPERACION]</option>
                                                        @foreach ($operacionNombre as $nombre)
                                                            <option value="{{ $nombre->oprname }}">{{ $nombre->oprname }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                @endif
                                                <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                                <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                                <td class="tp-column d-none w-100">
                                                    <select id="tpSelect" class="form-control w-100" multiple title="Por favor, selecciona una opción"> 
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
                                                    <div id="selectedOptionsContainer" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div> 
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
                    @endif
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    @if ($mostrarRegistro)
                        @if ($estatusFinalizar)
                            <h2>Registro</h2>
                            <table class="table table1">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        @if($data['modulo'] == "830A" || $data['modulo'] == "831A")
                                        @else
                                        <th>Operacion </th>
                                        @endif
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
                                                @if($data['modulo'] == "830A" || $data['modulo'] == "831A")

                                                @else
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="{{ $registro->operacion }}" readonly>
                                                </td>
                                                @endif
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
                                            @if($data['modulo'] == "830A" || $data['modulo'] == "831A")

                                            @else
                                            <th>Operacion </th>
                                            @endif
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
                                                @if($data['modulo'] == "830A" || $data['modulo'] == "831A")

                                                @else
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="{{ $registro->operacion }}" readonly>
                                                </td>
                                                @endif
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
                                                                value="{{ $registro->pxp }}" readonly>
                                                        </td> 
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
                        <h2>Total General - Turno Normal</h2>
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
                    <div class="table-responsive">
                        <h2>Total General - Tiempo Extra </h2>
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
                                            id="total_auditada" value="{{ $total_auditadaTE }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_rechazada"
                                            id="total_rechazada" value="{{ $total_rechazadaTE }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_porcentaje"
                                            id="total_porcentaje" value="{{ number_format($total_porcentajeTE, 2) }}"
                                            readonly></td>
                                </tr>
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
        .table1 th:nth-child(8) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table1 th:nth-child(2) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .table932 th:nth-child(1) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(2) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(3) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(4) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(5) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(6) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(7) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .texto-blanco {
            color: white !important;
        }

        .table-200 th:nth-child(1) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(2) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(3) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(5) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(6) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .tp-column {
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection--multiple {
            width: 100% !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Inicializar el select2
            $('#operacion').select2({
                placeholder: 'Seleccione una opcion',
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>
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
            // Inicializar el select2
            $('#tpSelect').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true,
                width: 'resolve'
            });
    
            // Manejador de cambio del select
            $('#tpSelect').on('change', function() {
                let selectedOptions = $(this).val();
                if (selectedOptions.length > 0) {
                    let lastSelectedOption = selectedOptions[selectedOptions.length - 1];
                    if (lastSelectedOption === 'OTRO') {
                        $('#nuevoConceptoModal').modal('show');
                    } else {
                        addSelectedOption(lastSelectedOption);
                        $(this).val(null).trigger('change'); // Reiniciar el select
                    }
                }
            });
    
            // Manejador del botón de guardar del modal
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
                            addSelectedOption(nuevoConcepto.toUpperCase());
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
    
            // Ocultar el modal y reiniciar el input del nuevo concepto
            $('#nuevoConceptoModal').on('hidden.bs.modal', function () {
                $('#nuevoConceptoInput').val('');
            });
    
            // Función para agregar una opción seleccionada a la lista de seleccionados
            function addSelectedOption(optionText) {
                let container = $('#selectedOptionsContainer');

                // Crear el div para la nueva opción
                let newOption = $('<div class="selected-option">').text(optionText);

                // Crear el input oculto
                let hiddenInput = $('<input type="hidden" name="tp[]" />').val(optionText);
                newOption.append(hiddenInput);

                // Crear botón para eliminar
                let removeButton = $('<button type="button" class="btn btn-danger btn-sm ml-2">').text('Eliminar');
                removeButton.on('click', function() {
                    newOption.remove();
                    checkContainerValidity();
                });
                newOption.append(removeButton);

                // Crear botón para duplicar
                let duplicateButton = $('<button type="button" class="btn btn-info btn-sm ml-2">').text('+');
                duplicateButton.on('click', function() {
                    // Llamar a la misma función para duplicar la opción
                    addSelectedOption(optionText);
                });
                newOption.prepend(duplicateButton);  // Prepend para que el botón "+" aparezca al inicio

                // Añadir la nueva opción al contenedor
                container.append(newOption);

                checkContainerValidity();
            }
    
            // Verifica si el contenedor tiene opciones seleccionadas y ajusta la validez
            function checkContainerValidity() {
                let container = $('#selectedOptionsContainer');
                if (container.children('.selected-option').length === 0) {
                    container.addClass('is-invalid');
                } else {
                    container.removeClass('is-invalid');
                }
            }
    
            function updateColumnsVisibility() {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                
                if (isNaN(cantidadRechazada) || cantidadRechazada === 0) {
                    // Ocultar las columnas y quitar el "required"
                    $('#tp-column-header, #ac-column-header').addClass('d-none');
                    $('.tp-column, .ac-column').addClass('d-none');
                    $('#selectedOptionsContainer, #ac').prop('required', false);
    
                    // Quitar cualquier validación pendiente del contenedor
                    $('#selectedOptionsContainer').removeClass('is-invalid');
                    $('#selectedOptionsContainer').prop('required', false); // Asegurarse de que no sea obligatorio
    
                } else {
                    // Mostrar las columnas y volver a poner el "required"
                    $('#tp-column-header, #ac-column-header').removeClass('d-none');
                    $('.tp-column, .ac-column').removeClass('d-none');
                    $('#selectedOptionsContainer, #ac').prop('required', true);
    
                    // Validar si hay opciones seleccionadas
                    checkContainerValidity();
                }
            }
    
            // Llamar a la función en cuanto se cargue la página para inicializar
            updateColumnsVisibility();
    
            // Actualizar la visibilidad de las columnas al cambiar el valor de cantidad_rechazada
            $('#cantidad_rechazada').on('input', function() {
                updateColumnsVisibility();
            });
    
            // Modificar el comportamiento del submit
            $('#miFormulario').on('submit', function(event) {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                const selectedOptionsCount = $('#selectedOptionsContainer').children('.selected-option').length;
                
                if (cantidadRechazada > 0) {
                    if (selectedOptionsCount === 0) {
                        alert('Por favor, selecciona al menos un defecto antes de enviar el formulario.');
                        event.preventDefault(); // Detener el envío del formulario
                        $('#tpSelect').select2('open'); // Abrir el select2 para que el usuario vea dónde seleccionar
                    } else if (selectedOptionsCount !== cantidadRechazada) {
                        alert(`El número de defectos seleccionados debe ser exactamente ${cantidadRechazada}.`);
                        event.preventDefault(); // Detener el envío del formulario
                    }
                } else {
                    // No validar el contenedor cuando la cantidad rechazada es 0
                    $('#selectedOptionsContainer').prop('required', false);
                }
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
        
            // Realizar la solicitud AJAX para obtener el cliente correspondiente al estilo seleccionado
            $.ajax({
                type: 'POST',
                url: '{{ route("obtenerCliente1") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    itemid: nuevoEstilo
                },
                success: function(response) {
                    console.log(response); // Verificar los datos recibidos
                    // Actualizar el valor del campo "cliente" con el cliente obtenido de la respuesta AJAX
                    $('#cliente').val(response.cliente);
    
                    // Actualizar la URL para reflejar el cambio en el estilo y cliente
                    window.history.pushState({}, '', url.toString());
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
    
    <script>
        function cambiarAInput(selectElement) {
            // Verifica si se seleccionó "Otra operación"
            if (selectElement.value === "otra") {
                // Destruye el select2 para permitir la manipulación directa del select
                $(selectElement).select2('destroy');

                // Crear un nuevo input con los mismos atributos
                const input = document.createElement("input");
                input.type = "text";
                input.name = selectElement.name;
                input.id = selectElement.id;
                input.className = selectElement.className;
                input.required = true;
                input.placeholder = "Ingresa la operación";

                // Transformar el texto a mayúsculas mientras se escribe
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Reemplazar el select por el input
                selectElement.parentNode.replaceChild(input, selectElement);
            }
        }
    </script>
@endsection
