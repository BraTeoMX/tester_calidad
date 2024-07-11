@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

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
                    @if((($conteoParos == 2) && ($finParoModular1 == true)) || (($conteoParos == 4) && ($finParoModular2 == true)))
                        <div class="row">
                            <form method="POST" action="{{ route('auditoriaAQL.cambiarEstadoInicioParoAQL') }}">
                                @csrf
                                <input type="hidden" name="finalizar_paro_modular" value="1">
                                <input type="hidden" class="form-control" name="modulo" id="modulo" value="{{ $data['modulo'] }}">
                                <input type="hidden" class="form-control" name="op" id="op1" value="{{ $data['op'] }}"> 
                                <input type="hidden" class="form-control" name="area" id="area" value="{{ $data['area'] }}">
                                <input type="hidden" class="form-control" name="team_leader" id="team_leader" value="{{ $data['team_leader'] }}">


                                <button type="submit" class="btn btn-primary">Fin Paro Modular</button> 
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('auditoriaAQL.formRegistroAuditoriaProcesoAQL') }}">
                            @csrf
                            <input type="hidden" class="form-control" name="area" id="area"
                                value="{{ $data['area'] }}">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-primary table-100">
                                        <tr>
                                            <th>MODULO</th>
                                            <th>OP</th> 
                                            <th>CLIENTE</th>
                                            <th>TEAM LEADER</th>
                                            <th>AUDITOR</th>
                                            <th>TURNO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control texto-blanco" name="modulo" id="modulo"
                                                    value="{{ $data['modulo'] }}" readonly></td>
                                            <td>
                                                <select class="form-control texto-blanco" name="op" id="op" required title="Selecciona una OP">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($selectPivoteOP as $op)
                                                        <option value="{{ $op->prodid }}" {{ $op->prodid == $data['op'] ? 'selected' : '' }}>
                                                            {{ $op->prodid }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td><input type="text" class="form-control texto-blanco" name="cliente" id="cliente"
                                                    value="{{ $datoUnicoOP->customername }}" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader"
                                                    value="{{ $data['team_leader'] }}" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="auditor" id="auditor"
                                                    value="{{ $data['auditor'] }}" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="turno" id="turno"
                                                    value="{{ $data['turno'] }}" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            @if ($estatusFinalizar)
                            @else
                                <div class="table-responsive">
                                    <table class="table table32">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th># BULTO</th>
                                                <th>PIEZAS</th>
                                                <th>ESTILO</th>
                                                <th>COLOR</th>
                                                <th>TALLA</th>
                                                <th>PIEZAS INSPECCIONADAS</th>
                                                <th>PIEZAS RECHAZADAS</th>
                                                <th id="tp-column-header">TIPO DE DEFECTO</th>
                                                <th id="ac-column-header">ACCION CORRECTIVA</th>
                                                <th id="nombre-column-header">NOMBRE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="bulto" id="bulto" class="form-control" required title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control texto-blanco" name="pieza" id="pieza" readonly></td>
                                                <td><input type="text" class="form-control texto-blanco" name="estilo" id="estilo" readonly></td>
                                                <td><input type="text" class="form-control texto-blanco" name="color" id="color" readonly></td>
                                                <td><input type="text" class="form-control texto-blanco" name="talla" id="talla" readonly></td>
                                                <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                                <td><input type="text" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                                <td class="tp-column">
                                                    <select name="tp[]" id="tpSelectAQL" class="form-control" required multiple title="Por favor, selecciona una opción">
                                                        <option value="OTRO">OTRO</option>
                                                        @if ($data['area'] == 'AUDITORIA AQL')
                                                            @foreach ($categoriaTPProceso as $proceso)
                                                                <option value="{{ $proceso->nombre }}">{{ $proceso->nombre }}</option>
                                                            @endforeach
                                                        @elseif($data['area'] == 'AUDITORIA AQL PLAYERA')
                                                            @foreach ($categoriaTPPlayera as $playera)
                                                                <option value="{{ $playera->nombre }}">{{ $playera->nombre }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                                <td class="ac-column"><input type="text" class="form-control" name="ac" id="ac"></td>
                                                <td class="nombre-column">
                                                    <select name="nombre" id="nombre" class="form-control">
                                                        <option value="">Selecciona una opción</option>
                                                        @if($auditorPlanta == 'Planta1')
                                                            @foreach($nombreProcesoToAQLPlanta1 as $opcion)
                                                                <option value="{{ $opcion['nombre'] ?? $opcion['name'] }}">{{ $opcion['nombre'] ?? $opcion['name'] }}</option>
                                                            @endforeach
                                                        @elseif($auditorPlanta == 'Planta2')
                                                            @foreach($nombreProcesoToAQLPlanta2 as $opcion)
                                                                <option value="{{ $opcion['nombre'] ?? $opcion['name'] }}">{{ $opcion['nombre'] ?? $opcion['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" class="btn-verde-xd">Guardar</button>
                            @endif
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="nuevoConceptoModalAQL" tabindex="-1" role="dialog" aria-labelledby="nuevoConceptoModalLabelAQL" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header">
                                        <h5 id="nuevoConceptoModalLabelAQL">Introduce el nuevo concepto</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInputAQL" placeholder="Nuevo concepto">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="guardarNuevoConceptoAQL">Guardar</button>
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
                            <table class="table table566">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>PARO</th>
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
                                <tbody>
                                    @foreach ($mostrarRegistro as $registro)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="minutos_paro"
                                                value="&nbsp;{{ $registro->minutos_paro }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="bulto"
                                                value="{{ $registro->bulto }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="pieza"
                                                value="{{ $registro->pieza }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="talla"
                                                value="{{ $registro->talla }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="color" id="color"
                                                value="{{$registro->color}}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="estilo" id="estilo"
                                                value="{{$registro->estilo}}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada"
                                                value="{{$registro->cantidad_auditada}}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada"
                                                value="{{$registro->cantidad_rechazada}}" readonly>
                                            </td>

                                            <form action="{{ route('auditoriaAQL.formUpdateDeleteProceso') }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $registro->id }}">
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" readonly
                                                           value="{{ implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray()) }}">
                                                </td>
                                                <td>
                                                    {{ $registro->created_at->format('H:i:s') }}
                                                </td>
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

                                <table class="table table56">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>PARO</th>
                                            <th># BULTO</th>
                                            <th>PIEZAS</th>
                                            <th>TALLA</th>
                                            <th>COLOR</th>
                                            <th>ESTILO</th>
                                            <th>PIEZAS INSPECCIONADAS</th>
                                            <th>PIEZAS RECHAZADAS</th>
                                            <th>TIPO DE DEFECTO</th>
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
                                                        <form method="POST" action="{{ route('auditoriaAQL.cambiarEstadoInicioParoAQL') }}">
                                                            @csrf
                                                            <input type="hidden" name="idCambio" value="{{ $registro->id }}">
                                                            <button type="submit" class="btn btn-primary">Fin Paro AQL</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="bulto"
                                                    value="{{ $registro->bulto }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="pieza"
                                                    value="{{ $registro->pieza }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="talla"
                                                    value="{{ $registro->talla }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="color" id="color"
                                                    value="{{$registro->color}}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="estilo" id="estilo"
                                                    value="{{$registro->estilo}}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada"
                                                    value="{{$registro->cantidad_auditada}}" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada"
                                                    value="{{$registro->cantidad_rechazada}}" readonly>
                                                </td>

                                                <form action="{{ route('auditoriaAQL.formUpdateDeleteProceso') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $registro->id }}">
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" readonly
                                                               value="{{ implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray()) }}">
                                                    </td>
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
                                <form action="{{ route('auditoriaAQL.formFinalizarProceso') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="area" value="{{ $data['area'] }}">
                                    <input type="hidden" name="modulo" value="{{ $data['modulo'] }}">
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
                        <h2>Piezas auditadas por dia</h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Total de piezas Muestra Auditadas </th>
                                    <th>Total de piezas Muestra Rechazadas</th>
                                    <th>Porcentaje AQL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrosIndividual as $registro)
                                    <tr>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_auditada }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada != 0 ? number_format(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0 }}"
                                                readonly></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <table class="table contenedor-tabla">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de piezas en bultos Auditados</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrosIndividualPieza as $registro)
                                <tr>
                                    <td><input type="text" class="form-control texto-blanco"
                                        value="{{ $registro->total_pieza }}" readonly></td>
                                        {{--
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada }}" readonly></td>
                                        <td><input type="text" class="form-control texto-blanco"
                                                value="{{ $registro->total_rechazada != 0 ? number_format(($registro->total_rechazada / $registro->total_pieza) * 100, 2) : 0 }}"
                                                readonly></td>
                                        --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <div class="table-responsive">
                        <h2>Total por Bultos </h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>total de Bultos Auditados</th>
                                    <th>total de Bultos Rechazados</th>
                                    <th>Porcentaje Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control texto-blanco" name="conteo_bulto"
                                            id="conteo_bulto" value="{{ $conteoBultos }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_rechazada"
                                            id="total_rechazada" value="{{ $conteoPiezaConRechazo }}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="total_porcentaje"
                                            id="total_porcentaje" value="{{ number_format($porcentajeBulto, 2) }}"
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

        .table-100 th:nth-child(1) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(3) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(4) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-100 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(8) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(3) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(10) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .table55 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(1) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(2) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(6) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table56 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        /* Estilo general para el contenedor de la tabla */
        .contenedor-tabla {
            width: 30%;
            /* Ajusta el ancho según tus necesidades */

        }


        @media (max-width: 768px) {
            .table23 th:nth-child(3) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        #ac-column-header, .ac-column, #nombre-column-header, .nombre-column {
            display: none;
        }

        .texto-blanco {
            color: white !important;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#bulto').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
            });
            $('#op').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
            });
            $('#tpSelectAQL').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true
            });

            $('#tpSelectAQL').on('change', function() {
                let selectedOptions = $(this).val();
                if (selectedOptions.includes('OTRO')) {
                    $('#nuevoConceptoModalAQL').modal('show');
                }
            });

            $('#guardarNuevoConceptoAQL').on('click', function() {
                let nuevoConcepto = $('#nuevoConceptoInputAQL').val();
                if (nuevoConcepto) {
                    let area = '';
                    @if ($data['area'] == 'AUDITORIA AQL')
                        area = 'proceso';
                    @elseif($data['area'] == 'AUDITORIA AQL PLAYERA')
                        area = 'playera';
                    @endif

                    fetch('{{ route('categoria_tipo_problema_aql.store') }}', {
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
                            $('#tpSelectAQL').append(newOption).trigger('change');
                            $('#nuevoConceptoModalAQL').modal('hide');
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

            $('#nuevoConceptoModalAQL').on('hidden.bs.modal', function () {
                $('#nuevoConceptoInputAQL').val('');
                let selectedOptions = $('#tpSelectAQL').val();
                let index = selectedOptions.indexOf('OTRO');
                if (index > -1) {
                    selectedOptions.splice(index, 1);
                    $('#tpSelectAQL').val(selectedOptions).trigger('change');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Función para mostrar/ocultar columnas según el valor de cantidad_rechazada
            function updateColumnsVisibility() {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                if (isNaN(cantidadRechazada) || cantidadRechazada === 0) { // Ocultar si es 0 o NaN
                    $('#ac-column-header, #nombre-column-header, #tp-column-header').hide();
                    $('.ac-column, .nombre-column, .tp-column').hide();
                    $('#ac, #nombre, #tpSelectAQL').prop('required', false);
                } else {
                    $('#ac-column-header, #nombre-column-header, #tp-column-header').show();
                    $('.ac-column, .nombre-column, .tp-column').show();
                    $('#ac, #nombre, #tpSelectAQL').prop('required', true);
                }
            }

            // Inicializar la visibilidad de las columnas al cargar la página
            updateColumnsVisibility();

            // Actualizar la visibilidad de las columnas al cambiar el valor de cantidad_rechazada
            $('#cantidad_rechazada').on('input', function() {
                updateColumnsVisibility();
            });

            // Actualizar los valores de los campos según la opción seleccionada en el select "bulto"
            $('#bulto').change(function() {
                var selectedOption = $(this).find(':selected');
                $('#pieza').val(selectedOption.data('pieza'));
                $('#estilo').val(selectedOption.data('estilo'));
                $('#color').val(selectedOption.data('color'));
                $('#talla').val(selectedOption.data('talla'));
            });

            // Actualizar los valores de los campos al cargar la página si una opción está seleccionada por defecto
            var selectedOption = $('#bulto').find(':selected');
            $('#pieza').val(selectedOption.data('pieza'));
            $('#estilo').val(selectedOption.data('estilo'));
            $('#color').val(selectedOption.data('color'));
            $('#talla').val(selectedOption.data('talla'));
        });
    </script>

    <script>
        $(document).ready(function() {
            function cargarBultos(op, modulo) {
                if (op) {
                    $.ajax({
                        url: '{{ route("getBultosByOp") }}', // Ruta al controlador que manejará la solicitud
                        type: 'GET',
                        data: { op: op, modulo: modulo },
                        success: function(data) {
                            $('#bulto').empty(); // Vacía el select de bultos
                            $('#bulto').append('<option value="">Selecciona una opción</option>');
                            
                            $.each(data, function(key, value) {
                                $('#bulto').append('<option value="'+ value.prodpackticketid +'" data-estilo="'+ value.itemid +'" data-color="'+ value.colorname +'" data-talla="'+ value.inventsizeid +'" data-pieza="'+ value.qty +'">'+ value.prodpackticketid +'</option>');
                            });

                            // Si hay un valor seleccionado en el select de bulto, seleccionarlo
                            var selectedBulto = '{{ $data['bulto'] ?? '' }}';
                            if (selectedBulto) {
                                $('#bulto').val(selectedBulto);
                            }
                        }
                    });
                } else {
                    $('#bulto').empty();
                    $('#bulto').append('<option value="">Selecciona una opción</option>');
                }
            }

            // Función para limpiar los inputs
            function limpiarInputs() {
                $('#cantidad_auditada').val('');
                $('#cantidad_rechazada').val('');
            }

            // Ejecutar la función cuando se cambie el valor del select de op
            $('#op').change(function() {
                var selectedOp = $(this).val();
                var modulo = $('#modulo').val();
                cargarBultos(selectedOp, modulo);
                limpiarInputs();
            });

            // Ejecutar la función al cargar la página si hay una opción seleccionada en op
            var initialOp = $('#op').val();
            var modulo = $('#modulo').val();
            cargarBultos(initialOp, modulo);
        });
    </script>

@endsection
