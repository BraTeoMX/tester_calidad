@extends('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')])

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
            font-size: 1rem;
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
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                    {{--<h3 id="estatusValue2">Estatus: {{ $datoAX->estatus }}</h3>--}}
                    @isset($encabezadoAuditoriaCorte->estatus)
                        <h3 id="estatusValue">Estatus: {{ $encabezadoAuditoriaCorte->estatus }}</h3>
                    @endisset
                    @isset($encabezadoAuditoriaCorte->evento)
                        <h4>Evento: {{$encabezadoAuditoriaCorte->evento}} / {{ $encabezadoAuditoriaCorte->total_evento }} </h4>
                    @endisset
                </div>
                <hr> 
                @if ($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == "proceso")
                <form method="POST" action="{{ route('auditoriaCorte.formEncabezadoAuditoriaCorteV2') }}">
                    @csrf
                    <input type="hidden" name="idEncabezadoAuditoriaCorte" value="{{ $encabezadoAuditoriaCorte->id }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Orden: {{ $encabezadoAuditoriaCorte->orden_id }}</h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Estilo: {{ $encabezadoAuditoriaCorte->estilo_id }}</h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Cliente: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->cliente_id : '' }}</h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Material: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->material : '' }}</h4>
                        </div>
                        @if($encabezadoAuditoriaCorte->temporada_id)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <h4>Color: {{ $encabezadoAuditoriaCorte->temporada_id }}</h4>
                            </div>
                        @else
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <label for="temporada_id" class="col-sm-6 col-form-label">Temporada</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control texto-blanco" name="temporada_id" id="temporada_id" placeholder="..." required/>
                                </div>
                            </div>
                        @endif
                        @if($encabezadoAuditoriaCorte->color_id)
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <h4>Color: {{ $encabezadoAuditoriaCorte->color_id }}</h4>
                            </div>
                        @else
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <label for="color_id" class="col-sm-6 col-form-label">Color</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control texto-blanco" name="color_id" id="color_id" placeholder="..." required/>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="pieza" class="col-sm-6 col-form-label">PIEZAS</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="pieza" id="pieza"
                                    placeholder="..." required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="lienzo" class="col-sm-6 col-form-label">LIENZOS</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control texto-blanco" name="lienzo" id="lienzo"
                                    placeholder="..." required/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn-verde-xd">Guardar</button>
                    </div>
                </form>
                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Orden: {{ $encabezadoAuditoriaCorte->orden_id }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Estilo: {{ $encabezadoAuditoriaCorte->estilo_id }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Temporada: {{ $encabezadoAuditoriaCorte->temporada_id }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Cliente: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->cliente_id : '' }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Material: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->material : '' }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Color: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->color_id : '' }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Lienzo: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->lienzo : '' }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Piezas: {{ isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->pieza : '' }}</h4>
                    </div>
                </div>
                @endif
                <div id="accordion">
                    <!--Inicio acordeon 1 -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button id="btnOne" class="btn btn-info btn-block" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    - - AUDITORIA DE MARCADA - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                {{-- Inicio cuerpo acordeon --}}
                                @if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'proceso')
                                    <p> - </p>
                                @elseif ($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada') 
                                <form method="POST" action="{{ route('auditoriaCorte.formAuditoriaMarcadaV2') }}"> 
                                    @csrf
                                    <input type="hidden" name="idAuditoriaMarcada" value="{{ $auditoriaMarcada->encabezado_id }}">
                                    {{-- Campo oculto para el boton Finalizar --}}
                                    <input type="hidden" name="accion" value="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_orden" class="col-sm-6 col-form-label">Yardas en la orden</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_orden" id="yarda_orden" placeholder="..."
                                                        value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->yarda_orden : '' }}"
                                                        required />
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="yarda_orden_estatus"
                                                        id="yarda_orden_estatus1" value="1"
                                                        {{ isset($auditoriaMarcada) && $auditoriaMarcada->yarda_orden_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="yarda_orden_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="yarda_orden_estatus"
                                                        id="yarda_orden_estatus2" value="0"
                                                        {{ isset($auditoriaMarcada) && $auditoriaMarcada->yarda_orden_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="yarda_orden_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                        <!-- Aquí parseamos los datos almacenados -->
                                        @php
                                            // Convertir las cadenas separadas por comas en arreglos
                                            $tallas = isset($auditoriaMarcada->tallas) ? explode(',', $auditoriaMarcada->tallas) : [];
                                            $tallasParciales = isset($auditoriaMarcada->tallas_parciales) ? explode(',', $auditoriaMarcada->tallas_parciales) : [];
                                            $bultos = isset($auditoriaMarcada->bultos) ? explode(',', $auditoriaMarcada->bultos) : [];
                                            $bultosParciales = isset($auditoriaMarcada->bultos_parciales) ? explode(',', $auditoriaMarcada->bultos_parciales) : [];
                                            $totalPiezas = isset($auditoriaMarcada->total_piezas) ? explode(',', $auditoriaMarcada->total_piezas) : [];
                                            $totalPiezasParciales = isset($auditoriaMarcada->total_piezas_parciales) ? explode(',', $auditoriaMarcada->total_piezas_parciales) : [];

                                            // Determinar el número de columnas
                                            $numColumns = max(count($tallas), count($bultos), count($totalPiezas), 1);
                                            $numColumnsParciales = max(count($tallasParciales), count($bultosParciales), count($totalPiezasParciales), 1);

                                            // Asegurar que todos los arreglos tengan la misma longitud
                                            $tallas = array_pad($tallas, $numColumns, '');
                                            $bultos = array_pad($bultos, $numColumns, '');
                                            $totalPiezas = array_pad($totalPiezas, $numColumns, '');

                                            $tallasParciales = array_pad($tallasParciales, $numColumnsParciales, '');
                                            $bultosParciales = array_pad($bultosParciales, $numColumnsParciales, '');
                                            $totalPiezasParciales = array_pad($totalPiezasParciales, $numColumnsParciales, '');
                                        @endphp
                                        <!-- Tabla CANTIDADES ABSOLUTAS --> 
                                        <div class="table-responsive">
                                            <p>CANTIDADES ABSOLUTAS</p> 
                                            <button class="btn btn-link" id="agregarColumnaCantidadAbsoluta">Agregar Columna</button> 
                                            <table id="tablaDinamica" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tallas</th>
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <th>
                                                                <select name="tallas[{{ $i }}]" class="form-control">
                                                                    <option value="">Selecciona una talla</option>
                                                                    @foreach ($auditoriaMarcadaTalla as $sizename)
                                                                        <option value="{{ $sizename }}" {{ $tallas[$i] == $sizename ? 'selected' : '' }}>{{ $sizename }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td># Bultos</td> 
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <td><input type="number" class="form-control" name="bultos[{{ $i }}]" value="{{ $bultos[$i] }}" /></td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <td><input type="number" class="form-control" name="total_piezas[{{ $i }}]" value="{{ $totalPiezas[$i] }}" /></td>
                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Tabla CANTIDADES PARCIALES -->
                                        <div class="table-responsive">
                                            <p>CANTIDADES PARCIALES</p> 
                                            <button class="btn btn-link" id="agregarColumnaCantidadParcial">Agregar Columna</button> 
                                            <table id="tablaParcialesDinamica" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tallas</th>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <th>
                                                                <select name="tallas_parciales[{{ $i }}]" class="form-control">
                                                                    <option value="">Selecciona una talla</option>
                                                                    @foreach ($auditoriaMarcadaTalla as $sizename)
                                                                        <option value="{{ $sizename }}" {{ $tallasParciales[$i] == $sizename ? 'selected' : '' }}>{{ $sizename }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <td>
                                                                <input type="number" class="form-control" name="bultos_parciales[{{ $i }}]" value="1" readonly />
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <td><input type="number" class="form-control" name="total_piezas_parciales[{{ $i }}]" value="{{ $totalPiezasParciales[$i] }}" /></td>
                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <hr>
                                        <div class="col-md-6 mb-3">
                                            <label for="largo_trazo" class="col-sm-3 col-form-label">Largo Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="largo_trazo" id="largo_trazo" placeholder="..."
                                                    value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->largo_trazo : '' }}"
                                                    required />
                                                <input type="text" class="form-control texto-blanco" id="largo_trazo_mt" readonly placeholder="En metros" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_trazo" class="col-sm-3 col-form-label">Ancho Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="ancho_trazo" id="ancho_trazo" placeholder="..."
                                                    value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->ancho_trazo : '' }}"
                                                    required />
                                                <input type="text" class="form-control texto-blanco" id="ancho_trazo_mt" readonly placeholder="En metros" />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="accion" class="btn-verde-xd">Guardar</button>
                                        @if($mostrarFinalizarMarcada == True)
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        @else
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        @endif
                                    </div>
                                    <script>
                                        $(document).ready(function() {
                                            let columnCount = {{ $numColumns }};
                                            let columnParcialCount = {{ $numColumnsParciales }};
                                            const tallas = @json($auditoriaMarcadaTalla);
                                            
                                            // Agregar columna para CANTIDADES ABSOLUTAS
                                            $('#agregarColumnaCantidadAbsoluta').click(function(event) {
                                                event.preventDefault(); // Evitar envío del formulario
                                                columnCount++;
                                    
                                                // Agregar nueva celda a cada fila
                                                $('#tablaDinamica tr').each(function(index) {
                                                    if (index === 0) {
                                                        // Primera fila (encabezado)
                                                        let selectHtml = `
                                                            <th>
                                                                <select name="tallas[${columnCount - 1}]" class="form-control">
                                                                    <option value="">Selecciona una talla</option>`;
                                                        
                                                        tallas.forEach(function(talla) {
                                                            selectHtml += `<option value="${talla}">${talla}</option>`;
                                                        });
                                                        
                                                        selectHtml += `
                                                                </select>
                                                            </th>`;
                                                        
                                                        $(this).append(selectHtml);
                                                    } else if (index === 1) {
                                                        // Segunda fila (# Bultos)
                                                        $(this).append(`<td><input type="number" class="form-control" name="bultos[${columnCount - 1}]" value="" /></td>`);
                                                    } else if (index === 2) {
                                                        // Tercera fila (Total Piezas)
                                                        $(this).append(`<td><input type="number" class="form-control" name="total_piezas[${columnCount - 1}]" value="" /></td>`);
                                                    }
                                                });
                                            });
                                    
                                            // Agregar columna para CANTIDADES PARCIALES
                                            $('#agregarColumnaCantidadParcial').click(function(event) {
                                                event.preventDefault(); // Evitar envío del formulario
                                                columnParcialCount++;
                                    
                                                // Agregar nueva celda a cada fila
                                                $('#tablaParcialesDinamica tr').each(function(index) {
                                                    if (index === 0) {
                                                        // Primera fila (encabezado)
                                                        let selectHtml = `
                                                            <th>
                                                                <select name="tallas_parciales[${columnParcialCount - 1}]" class="form-control">
                                                                    <option value="">Selecciona una talla</option>`;
                                                        
                                                        tallas.forEach(function(talla) {
                                                            selectHtml += `<option value="${talla}">${talla}</option>`;
                                                        });
                                                        
                                                        selectHtml += `
                                                                </select>
                                                            </th>`;
                                                        
                                                        $(this).append(selectHtml);
                                                    } else if (index === 1) {
                                                        // Segunda fila (# Bultos)
                                                        $(this).append(`<td><input type="number" class="form-control" name="bultos_parciales[${columnParcialCount - 1}]" value="1" readonly /></td>`);
                                                    } else if (index === 2) {
                                                        // Tercera fila (Total Piezas)
                                                        $(this).append(`<td><input type="number" class="form-control" name="total_piezas_parciales[${columnParcialCount - 1}]" value="" /></td>`);
                                                    }
                                                });
                                            });
                                        });
                                    </script>                                    
                                </form>
                                {{-- Fin cuerpo acordeon --}}
                                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))  
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_orden" class="col-sm-6 col-form-label">Yardas en la orden</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2 texto-blanco"
                                                        name="yarda_orden" id="yarda_orden" placeholder="..."
                                                        value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->yarda_orden : '' }}"
                                                        {{ isset($auditoriaMarcada) ? 'readonly' : '' }}
                                                        required />
                                                </div>
                                                @if (isset($auditoriaMarcada))
                                                    <div class="form-check form-check-inline">
                                                        @if ($auditoriaMarcada->yarda_orden_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                        @php
                                            // Convertir las cadenas separadas por comas en arreglos para CANTIDADES ABSOLUTAS
                                            $tallas = isset($auditoriaMarcada->tallas) ? explode(',', $auditoriaMarcada->tallas) : [];
                                            $bultos = isset($auditoriaMarcada->bultos) ? explode(',', $auditoriaMarcada->bultos) : [];
                                            $totalPiezas = isset($auditoriaMarcada->total_piezas) ? explode(',', $auditoriaMarcada->total_piezas) : [];

                                            // Convertir las cadenas separadas por comas en arreglos para CANTIDADES PARCIALES
                                            $tallasParciales = isset($auditoriaMarcada->tallas_parciales) ? explode(',', $auditoriaMarcada->tallas_parciales) : [];
                                            $bultosParciales = isset($auditoriaMarcada->bultos_parciales) ? explode(',', $auditoriaMarcada->bultos_parciales) : [];
                                            $totalPiezasParciales = isset($auditoriaMarcada->total_piezas_parciales) ? explode(',', $auditoriaMarcada->total_piezas_parciales) : [];

                                            // Determinar el número de columnas para cada tabla
                                            $numColumns = max(count($tallas), count($bultos), count($totalPiezas), 1);
                                            $numColumnsParciales = max(count($tallasParciales), count($bultosParciales), count($totalPiezasParciales), 1);
                                        @endphp
                                        <div class="table-responsive">
                                            <p>CANTIDADES ABSOLUTAS</p>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tallas</th>
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <th>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $tallas[$i] ?? '' }}" readonly />
                                                            </th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <td>
                                                                <input type="number" class="form-control texto-blanco" value="{{ $bultos[$i] ?? '' }}" readonly />
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        @for ($i = 0; $i < $numColumns; $i++)
                                                            <td>
                                                                <input type="number" class="form-control texto-blanco" value="{{ $totalPiezas[$i] ?? '' }}" readonly />
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <p>CANTIDADES PARCIALES</p>
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Tallas</th>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <th>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $tallasParciales[$i] ?? '' }}" readonly />
                                                            </th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <td>
                                                                <input type="number" class="form-control texto-blanco" value="{{ $bultosParciales[$i] ?? '' }}" readonly />
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        @for ($i = 0; $i < $numColumnsParciales; $i++)
                                                            <td>
                                                                <input type="number" class="form-control texto-blanco" value="{{ $totalPiezasParciales[$i] ?? '' }}" readonly />
                                                            </td>
                                                        @endfor
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="largo_trazo" class="col-sm-3 col-form-label texto-blanco">Largo Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2 texto-blanco"
                                                    name="largo_trazo" id="largo_trazo" placeholder="..."
                                                    value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->largo_trazo : '' }}"
                                                    {{ isset($auditoriaMarcada) ? 'readonly' : '' }}
                                                    required />
                                                <input type="text" class="form-control texto-blanco" id="largo_trazo_mt" readonly placeholder="En metros" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_trazo" class="col-sm-3 col-form-label texto-blanco">Ancho Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2 texto-blanco"
                                                    name="ancho_trazo" id="ancho_trazo" placeholder="..."
                                                    value="{{ isset($auditoriaMarcada) ? $auditoriaMarcada->ancho_trazo : '' }}"
                                                    {{ isset($auditoriaMarcada) ? 'readonly' : '' }}
                                                    required />
                                                <input type="text" class="form-control texto-blanco" id="ancho_trazo_mt" readonly placeholder="En metros" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 1 -->
                    <!--Inicio acordeon 2 -->
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button id="btnTwo" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    - - AUDITORIA DE TENDIDO - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                {{-- Inicio cuerpo acordeon --}}
                                @if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada')
                                    <p>-</p>
                                @elseif ($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido')
                                <form method="POST"
                                    action="{{ route('auditoriaCorte.formAuditoriaTendidoV2') }}"> 
                                    @csrf
                                    <input type="hidden" name="idAuditoriaTendido" value="{{ $auditoriaTendido->encabezado_id }}">
                                    {{-- Campo oculto para el boton Finalizar --}}
                                    <input type="hidden" name="accion" value=""> 
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="row"> 
                                                <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) TENDEDOR(ES)</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control" name="nombre[]" id="nombre" multiple required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($operarioCorte as $nombre)
                                                            <option value="{{ $nombre->nombre }}" {{ isset($auditoriaTendido) && in_array(trim($nombre->nombre), explode(',', trim($auditoriaTendido->nombre))) ? 'selected' : '' }}>
                                                                {{ $nombre->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción" required>
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="1 : Mesa" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '1 : Mesa' ? 'selected' : '' }}>1 : Manual</option>
                                                    <option value="2 : Brio" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '2 : Brio' ? 'selected' : '' }}>2 : Brio</option>
                                                    <option value="3 : Brio" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '3 : Brio' ? 'selected' : '' }}>3 : Brio</option>
                                                    <option value="4 : Brio" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '4 : Brio' ? 'selected' : '' }}>4 : Brio</option>
                                                    <option value="5 : Brio" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '5 : Brio' ? 'selected' : '' }}>5 : Brio</option>
                                                    <option value="6 : Brio" {{ isset($auditoriaTendido) && $auditoriaTendido->mesa == '6 : Brio' ? 'selected' : '' }}>6 : Brio</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                                    value="{{ $auditorDato }}" readonly required />
                                                <input type="hidden" name="auditor" value="{{ $auditorDato }}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_material" class="col-sm-6 col-form-label">1. Codigo de
                                                material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="codigo_material_estatus" id="codigo_material_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->codigo_material_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="codigo_material_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="codigo_material_estatus" id="codigo_material_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->codigo_material_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="codigo_material_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco"
                                                        name="codigo_material" id="codigo_material" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->codigo_material : '' }}"
                                                        required />
                                                </div>                                              
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_color" class="col-sm-6 col-form-label">2. Codigo de
                                                color</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center"
                                                    style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_color_estatus" id="codigo_color_estatus1"
                                                            value="1"
                                                            {{ isset($auditoriaTendido) && $auditoriaTendido->codigo_color_estatus == 1 ? 'checked' : '' }}
                                                            required />
                                                        <label class="label-paloma" for="codigo_color_estatus1">✔ </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_color_estatus" id="codigo_color_estatus2"
                                                            value="0"
                                                            {{ isset($auditoriaTendido) && $auditoriaTendido->codigo_color_estatus == 0 ? 'checked' : '' }}
                                                            required />
                                                        <label class="label-tache" for="codigo_color_estatus2">✖ </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="text" class="form-control me-2 texto-blanco" name="codigo_color" id="codigo_color"
                                                               placeholder="..." value="{{ $encabezadoAuditoriaCorte->color_id }}" readonly required />
                                                        <input type="hidden" name="codigo_color" value="{{ $encabezadoAuditoriaCorte->color_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="informacion_trazo" class="col-sm-6 col-form-label">3. Informacion
                                                de trazo</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="informacion_trazo_estatus" id="informacion_trazo_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="informacion_trazo_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="informacion_trazo_estatus" id="informacion_trazo_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="informacion_trazo_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <select name="informacion_trazo" id="informacion_trazo" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Si" {{ isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo == 'Si' ? 'selected' : '' }}>Si</option>
                                                        <option value="No" {{ isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo == 'No' ? 'selected' : '' }}>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_lienzo" class="col-sm-6 col-form-label">4. Cantidad de
                                                lienzos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->cantidad_lienzo_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="cantidad_lienzo_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->cantidad_lienzo_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="cantidad_lienzo_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="cantidad_lienzo" id="cantidad_lienzo"
                                                           value="{{ $encabezadoAuditoriaCorte->lienzo }}" readonly required />
                                                    <input type="hidden" name="cantidad_lienzo" value="{{ $encabezadoAuditoriaCorte->lienzo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="longitud_tendido" class="col-sm-6 col-form-label">5. Longitud de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="longitud_tendido_estatus" id="longitud_tendido_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->longitud_tendido_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="longitud_tendido_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="longitud_tendido_estatus" id="longitud_tendido_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->longitud_tendido_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="longitud_tendido_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="longitud_tendido" id="longitud_tendido"
                                                           value="{{ $auditoriaMarcada->largo_trazo / 100, 2 }}" readonly required />
                                                    <input type="hidden" name="longitud_tendido" value="{{ $auditoriaMarcada->largo_trazo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_tendido" class="col-sm-6 col-form-label">6. Ancho de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ancho_tendido_estatus" id="ancho_tendido_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->ancho_tendido_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="ancho_tendido_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ancho_tendido_estatus" id="ancho_tendido_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->ancho_tendido_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="ancho_tendido_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="ancho_tendido" id="ancho_tendido"
                                                           value="{{ $auditoriaMarcada->ancho_trazo / 100, 2}}" readonly required />
                                                    <input type="hidden" name="ancho_tendido" value="{{ $auditoriaMarcada->ancho_trazo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="material_relajado" class="col-sm-6 col-form-label">7. Material relajado</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="material_relajado_estatus" id="material_relajado_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->material_relajado_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="material_relajado_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="material_relajado_estatus" id="material_relajado_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->material_relajado_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="material_relajado_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <select name="material_relajado" id="material_relajado" class="form-control"
                                                        title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($CategoriaMaterialRelajado as $materialRelajado) 
                                                            <option value="{{ $materialRelajado->nombre }}"
                                                                {{ isset($auditoriaTendido) && trim($auditoriaTendido->material_relajado) == trim($materialRelajado->nombre) ? 'selected' : '' }}>
                                                                {{ $materialRelajado->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="empalme" class="col-sm-6 col-form-label">8. Empalmes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                        id="empalme_estatus1" value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->empalme_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="empalme_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                        id="empalme_estatus2" value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->empalme_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="empalme_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cara_material" class="col-sm-6 col-form-label">9. Cara de
                                                material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cara_material_estatus" id="cara_material_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->cara_material_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="cara_material_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cara_material_estatus" id="cara_material_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->cara_material_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="cara_material_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tono" class="col-sm-6 col-form-label">10. Tonos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                        id="tono_estatus1" value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->tono_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="tono_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                        id="tono_estatus2" value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->tono_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="tono_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco"
                                                        name="tono" id="tono" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->tono : '' }}"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="alineacion_tendido" class="col-sm-6 col-form-label">11. Alineacion de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="alineacion_tendido" id="alineacion_tendido" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Regular" {{ isset($auditoriaTendido) && $auditoriaTendido->alineacion_tendido == 'Regular' ? 'selected' : '' }}>Regular </option>
                                                        <option value="Mal" {{ isset($auditoriaTendido) && $auditoriaTendido->alineacion_tendido == 'Mal' ? 'selected' : '' }}>Mal </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="arruga_tendido" class="col-sm-6 col-form-label">12. Arrugas de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="arruga_tendido" id="arruga_tendido" class="form-control" title="Por favor, selecciona una opción"> 
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Algunas" {{ isset($auditoriaTendido) && $auditoriaTendido->arruga_tendido == 'Algunas' ? 'selected' : '' }}>Algunas </option>
                                                        <option value="Pocas" {{ isset($auditoriaTendido) && $auditoriaTendido->arruga_tendido == 'Pocas' ? 'selected' : '' }}>Pocas </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto_material" class="col-sm-6 col-form-label">13. defecto de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="defecto_material[]" id="defecto_material" class="form-control" multiple>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="OTRO">OTRO</option>
                                                        @foreach ($CategoriaDefectoCorte as $defectoMaterial)
                                                            <option value="{{ $defectoMaterial->nombre }}"
                                                                {{ isset($auditoriaTendido) && in_array(trim($defectoMaterial->nombre), explode(',', trim($auditoriaTendido->defecto_material))) ? 'selected' : '' }}>
                                                                {{ $defectoMaterial->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal -->
                                        <div class="modal fade" id="otroDefectoModal" tabindex="-1" role="dialog" aria-labelledby="otroDefectoModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content bg-dark text-white">
                                                    <div class="modal-header">
                                                    <h5 class="modal-title" id="otroDefectoModalLabel">Agregar Nuevo Defecto de Material</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    </div>
                                                    <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="nuevoDefecto">Nuevo Defecto</label>
                                                        <input type="text" class="form-control" id="nuevoDefecto">
                                                    </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                    <button type="button" class="btn btn-primary" id="guardarNuevoDefecto">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>         
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_marcada" class="col-sm-6 col-form-label">14. Yardas en la
                                                marcada</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_marcada" id="yarda_marcada" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->yarda_marcada : '' }}"
                                                        required />
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="yarda_marcada_estatus" id="yarda_marcada_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->yarda_marcada_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="yarda_marcada_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="yarda_marcada_estatus" id="yarda_marcada_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaTendido) && $auditoriaTendido->yarda_marcada_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="yarda_marcada_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bio_tension" class="col-sm-6 col-form-label">
                                                15. Parametro de Brio Tension:
                                            </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select class="form-control me-2" name="bio_tension" id="bio_tension" required>
                                                    <option value="">Selecciona una opcion</option>
                                                    <option value="automatico" {{ isset($auditoriaTendido) && $auditoriaTendido->bio_tension === 'automatico' ? 'selected' : '' }}>Automático</option>
                                                    <option value="manual" {{ isset($auditoriaTendido) && $auditoriaTendido->bio_tension === 'manual' ? 'selected' : '' }}>Manual</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="velocidad" class="col-sm-6 col-form-label">
                                                16. Velocidad:
                                            </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco"
                                                    name="velocidad" id="velocidad" placeholder="..."
                                                    value="{{ isset($auditoriaTendido) ? $auditoriaTendido->velocidad : '' }}"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="accion_correctiva" class="col-sm-6 col-form-label">Accion
                                                correctiva </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="accion_correctiva" id="accion_correctiva" class="form-control me-2" required>
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($CategoriaAccionCorrectiva as $categoria)
                                                        <option value="{{ $categoria->accion_correctiva }}" {{ isset($auditoriaTendido) && $auditoriaTendido->accion_correctiva == $categoria->accion_correctiva ? 'selected' : '' }}>
                                                            {{ $categoria->accion_correctiva }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="comentario_tendido" class="col-sm-6 col-form-label">Comentario: </label>
                                            <div class="form-check">
                                                <input type="text" class="form-control texto-blanco"
                                                    name="comentario_tendido" id="comentario_tendido" placeholder="..."
                                                    value="{{ isset($auditoriaTendido) ? $auditoriaTendido->comentario_tendido : '' }}"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        @if($mostrarFinalizarTendido)
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        @else
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        @endif
                                    </div>
                                </form> 
                                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="row"> 
                                                <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) TENDEDOR(ES)</label>
                                                <div class="col-sm-6">
                                                    @if (isset($auditoriaTendido))
                                                        @foreach (explode(',', trim($auditoriaTendido->nombre)) as $nombre)
                                                            <input type="text" class="form-control mb-2  texto-blanco" value="{{ $nombre }}" readonly />
                                                        @endforeach
                                                    @else
                                                        <select class="form-control" name="nombre[]" id="nombre" multiple>
                                                            <option value="">Selecciona una opción</option>
                                                            @foreach ($operarioCorte as $nombre)
                                                                <option value="{{ $nombre->nombre }}">{{ $nombre->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ isset($auditoriaTendido) ? $auditoriaTendido->created_at->format('d ') . $mesesEnEspanol[$auditoriaTendido->created_at->format('n') - 1] . $auditoriaTendido->created_at->format(' Y - H:i:s') : '' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($auditoriaTendido))
                                                    <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->mesa }}" readonly />
                                                @else
                                                    <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="1 : Mesa">1 : Manual</option>
                                                        <option value="2 : Brio">2 : Brio</option>
                                                        <option value="3 : Brio">3 : Brio</option>
                                                        <option value="4 : Brio">4 : Brio</option>
                                                        <option value="5 : Brio">5 : Brio</option>
                                                        <option value="6 : Brio">6 : Brio</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                                    value="{{ isset($auditoriaTendido) ? $auditoriaTendido->auditor : '' }}" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_material" class="col-sm-6 col-form-label">1. Codigo de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->codigo_material_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_material_estatus" id="codigo_material_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="codigo_material_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_material_estatus" id="codigo_material_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="codigo_material_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco"
                                                        name="codigo_material" id="codigo_material" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->codigo_material : '' }}"
                                                        {{ isset($auditoriaTendido) ? 'readonly' : '' }}
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_color" class="col-sm-6 col-form-label">2. Codigo de color</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        @if (isset($auditoriaTendido))
                                                            @if ($auditoriaTendido->codigo_color_estatus == 1)
                                                                <label class="label-paloma">✔</label>
                                                            @else
                                                                <label class="label-tache">✖</label>
                                                            @endif
                                                        @else
                                                            <input class="quitar-espacio" type="radio"
                                                                name="codigo_color_estatus" id="codigo_color_estatus1"
                                                                value="1" required />
                                                            <label class="label-paloma" for="codigo_color_estatus1">✔</label>
                                                            <input class="quitar-espacio" type="radio"
                                                                name="codigo_color_estatus" id="codigo_color_estatus2"
                                                                value="0" required />
                                                            <label class="label-tache" for="codigo_color_estatus2">✖</label>
                                                        @endif
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="text" class="form-control me-2 texto-blanco" name="codigo_color" id="codigo_color"
                                                            placeholder="..." value="{{ $encabezadoAuditoriaCorte->color_id }}" readonly required />
                                                        <input type="hidden" name="codigo_color" value="{{ $encabezadoAuditoriaCorte->color_id }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="informacion_trazo" class="col-sm-6 col-form-label">3. Informacion de trazo</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->informacion_trazo_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="informacion_trazo_estatus" id="informacion_trazo_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="informacion_trazo_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="informacion_trazo_estatus" id="informacion_trazo_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="informacion_trazo_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->informacion_trazo }}" readonly />
                                                    @else
                                                        <select name="informacion_trazo" id="informacion_trazo" class="form-control" title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Si">Si</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_lienzo" class="col-sm-6 col-form-label">4. Cantidad de lienzos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->cantidad_lienzo_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="cantidad_lienzo_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="cantidad_lienzo_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="cantidad_lienzo" id="cantidad_lienzo"
                                                        value="{{ $encabezadoAuditoriaCorte->lienzo }}" readonly required />
                                                    <input type="hidden" name="cantidad_lienzo" value="{{ $encabezadoAuditoriaCorte->lienzo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="longitud_tendido" class="col-sm-6 col-form-label">5. Longitud de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->longitud_tendido_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="longitud_tendido_estatus" id="longitud_tendido_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="longitud_tendido_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="longitud_tendido_estatus" id="longitud_tendido_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="longitud_tendido_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="longitud_tendido" id="longitud_tendido"
                                                        value="{{ $auditoriaMarcada->largo_trazo / 100, 2 }}" readonly required />
                                                    <input type="hidden" name="longitud_tendido" value="{{ $auditoriaMarcada->largo_trazo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_tendido" class="col-sm-6 col-form-label">6. Ancho de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->ancho_tendido_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="ancho_tendido_estatus" id="ancho_tendido_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="ancho_tendido_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="ancho_tendido_estatus" id="ancho_tendido_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="ancho_tendido_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="ancho_tendido" id="ancho_tendido"
                                                        value="{{ $auditoriaMarcada->ancho_trazo }}" readonly required />
                                                    <input type="hidden" name="ancho_tendido" value="{{ $auditoriaMarcada->ancho_trazo }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="material_relajado" class="col-sm-6 col-form-label">7. Material relajado</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->material_relajado_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="material_relajado_estatus" id="material_relajado_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="material_relajado_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="material_relajado_estatus" id="material_relajado_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="material_relajado_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->material_relajado }}" readonly />
                                                    @else
                                                        <select name="material_relajado" id="material_relajado" class="form-control"
                                                            title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            @foreach ($CategoriaMaterialRelajado as $materialRelajado) 
                                                                <option value="{{ $materialRelajado->nombre }}">{{ $materialRelajado->nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="empalme" class="col-sm-6 col-form-label">8. Empalmes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->empalme_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                            id="empalme_estatus1" value="1" required />
                                                        <label class="label-paloma" for="empalme_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                            id="empalme_estatus2" value="0" required />
                                                        <label class="label-tache" for="empalme_estatus2">✖</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cara_material" class="col-sm-6 col-form-label">9. Cara de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->cara_material_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cara_material_estatus" id="cara_material_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="cara_material_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cara_material_estatus" id="cara_material_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="cara_material_estatus2">✖</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tono" class="col-sm-6 col-form-label">10. Tonos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        @if ($auditoriaTendido->tono_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    @else
                                                        <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                            id="tono_estatus1" value="1" required />
                                                        <label class="label-paloma" for="tono_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                            id="tono_estatus2" value="0" required />
                                                        <label class="label-tache" for="tono_estatus2">✖</label>
                                                    @endif
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco"
                                                        name="tono" id="tono" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->tono : '' }}"
                                                        {{ isset($auditoriaTendido) ? 'readonly' : '' }}
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="alineacion_tendido" class="col-sm-6 col-form-label">11. Alineacion de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->alineacion_tendido }}" readonly />
                                                    @else
                                                        <select name="alineacion_tendido" id="alineacion_tendido" class="form-control" title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Regular">Regular</option>
                                                            <option value="Mal">Mal</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="arruga_tendido" class="col-sm-6 col-form-label">12. Arrugas de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if (isset($auditoriaTendido))
                                                        <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->arruga_tendido }}" readonly />
                                                    @else
                                                        <select name="arruga_tendido" id="arruga_tendido" class="form-control" title="Por favor, selecciona una opción"> 
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Algunas">Algunas</option>
                                                            <option value="Pocas">Pocas</option>
                                                        </select>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto_material" class="col-sm-6 col-form-label">13. Defecto de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                @if (isset($auditoriaTendido))
                                                    @foreach (explode(',', trim($auditoriaTendido->defecto_material)) as $defecto)
                                                        <input type="text" class="form-control mb-2  texto-blanco" value="{{ $defecto }}" readonly />
                                                    @endforeach
                                                @else
                                                    <select name="defecto_material[]" id="defecto_material" class="form-control" multiple>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($CategoriaDefectoCorte as $defectoMaterial)
                                                            <option value="{{ $defectoMaterial->nombre }}">{{ $defectoMaterial->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_marcada" class="col-sm-6 col-form-label">14. Yardas en la marcada</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_marcada" id="yarda_marcada" placeholder="..."
                                                        value="{{ isset($auditoriaTendido) ? $auditoriaTendido->yarda_marcada : '' }}"
                                                        {{ isset($auditoriaTendido) ? 'readonly' : '' }}
                                                        required />
                                                </div>
                                                @if (isset($auditoriaTendido))
                                                    <div class="form-check form-check-inline">
                                                        @if ($auditoriaTendido->yarda_marcada_estatus == 1)
                                                            <label class="label-paloma">✔</label>
                                                        @else
                                                            <label class="label-tache">✖</label>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bio_tension" class="col-sm-6 col-form-label">15. Parametro de Brio Tension:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($auditoriaTendido))
                                                    <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->bio_tension }}" readonly />
                                                @else
                                                    <select class="form-control me-2" name="bio_tension" id="bio_tension" required>
                                                        <option value="">Selecciona una opcion</option>
                                                        <option value="automatico">Automático</option>
                                                        <option value="manual">Manual</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="velocidad" class="col-sm-6 col-form-label">16. Velocidad:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco"
                                                    name="velocidad" id="velocidad" placeholder="..."
                                                    value="{{ isset($auditoriaTendido) ? $auditoriaTendido->velocidad : '' }}"
                                                    {{ isset($auditoriaTendido) ? 'readonly' : '' }}
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="accion_correctiva" class="col-sm-6 col-form-label">Accion correctiva</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($auditoriaTendido))
                                                    <input type="text" class="form-control texto-blanco" value="{{ $auditoriaTendido->accion_correctiva }}" readonly />
                                                @else
                                                    <select name="accion_correctiva" id="accion_correctiva" class="form-control me-2" required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($CategoriaAccionCorrectiva as $categoria)
                                                            <option value="{{ $categoria->accion_correctiva }}">{{ $categoria->accion_correctiva }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="comentario_tendido" class="col-sm-6 col-form-label">Comentario: </label>
                                            <div class="form-check">
                                                <input type="text" class="form-control texto-blanco"
                                                    name="comentario_tendido" id="comentario_tendido" placeholder="..."
                                                    value="{{ isset($auditoriaTendido) ? $auditoriaTendido->comentario_tendido : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin cuerpo acordeon --}}
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 2 -->
                    <!--Inicio acordeon 3 -->
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button id="btnThree" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    - - LECTRA - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                {{-- Inicio cuerpo acordeon --}}
                                @if($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido'))
                                    <p>-</p>
                                @elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusLectra') 
                                <form method="POST"
                                    action="{{ route('auditoriaCorte.formLectraV2') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $encabezadoAuditoriaCorte->id }}">
                                    <input type="hidden" name="idLectra" value="{{ $Lectra->encabezado_id }}">
                                    {{-- Campo oculto para el boton Finalizar --}}
                                    <input type="hidden" name="accion" value="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) CORTADOR(ES)</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="nombre[]" id="nombrel" class="form-control" multiple required>
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($operarioCorte as $nombre)
                                                        <option value="{{ $nombre->nombre }}"
                                                            {{ isset($Lectra) && in_array(trim($nombre->nombre), explode(',', trim($Lectra->nombre))) ? 'selected' : '' }}>
                                                            {{ $nombre->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">Maquina Lectra: </label>
                                            <div class="col-sm-12 d-flex align-items-center"> 
                                                <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción"> 
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="IH8" {{ isset($Lectra) && $Lectra->mesa == 'IH8' ? 'selected' : '' }}>IH8</option>
                                                    <option value="IX6" {{ isset($Lectra) && $Lectra->mesa == 'IX6' ? 'selected' : '' }}>IX6</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                                    value="{{ $auditorDato }}" readonly required />
                                                <input type="hidden" name="auditor" value="{{ $auditorDato }}">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        @php
                                            $options = ['-1', '-15/16', '-7/8', '-13/16', '-3/4', '-11/16', '-5/8', '-9/16', '-1/2', '-7/16', '-3/8', '-5/16', '-1/4', '-3/16', '-1/8', '-1/16', 
                                            '0', '+1/16', '+1/8', '+3/16', '+1/4', '+5/16', '+3/8', '+7/16', '+1/2', '+9/16', '+5/8', '+11/16', '+3/4', '+13/16', '+7/8', '+15/16', '+1'];
                                            // Datos almacenados (simulados, aquí se deben cargar desde la variable $Lectra o similar)
                                            $simetria_piezas = isset($Lectra) ? explode(',', $Lectra->simetria_piezas) : [];
                                            $panel_x1 = isset($Lectra) ? explode(',', $Lectra->panel_x1) : [];
                                            $panel_x2 = isset($Lectra) ? explode(',', $Lectra->panel_x2) : [];
                                            $panel_y1 = isset($Lectra) ? explode(',', $Lectra->panel_y1) : [];
                                            $panel_y2 = isset($Lectra) ? explode(',', $Lectra->panel_y2) : [];

                                            // Número de filas existentes
                                            $numFilas = max(count($simetria_piezas), count($panel_x1), count($panel_x2), count($panel_y1), count($panel_y2), 1);
                                        @endphp
                                        <div class="table-responsive">
                                            <button id="agregarFila" class="btn btn-link mb-2">Agregar Fila</button>
                                            <table class="table" id="tablaPaneles">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Panel</th>
                                                        <th scope="col">Simetría de piezas</th>
                                                        <th scope="col" colspan="2">X° ANCHO</th>
                                                        <th scope="col" colspan="2">Y° LARGO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Generar filas existentes -->
                                                    @for ($i = 0; $i < $numFilas; $i++)
                                                        <tr>
                                                            <th scope="row">Panel {{ $i + 1 }}</th>
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" name="simetria_piezas[]" placeholder="Panel {{ $i + 1 }}" value="{{ $simetria_piezas[$i] ?? '' }}" />
                                                            </td>
                                                            <!-- Panel X -->
                                                            <td>
                                                                <select name="panel_x1[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial</option>
                                                                    @foreach($options as $option)
                                                                        <option value="{{ $option }}" {{ (isset($panel_x1[$i]) && $panel_x1[$i] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel_x2[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final</option>
                                                                    @foreach($options as $option)
                                                                        <option value="{{ $option }}" {{ (isset($panel_x2[$i]) && $panel_x2[$i] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <!-- Panel Y -->
                                                            <td>
                                                                <select name="panel_y1[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial</option>
                                                                    @foreach($options as $option)
                                                                        <option value="{{ $option }}" {{ (isset($panel_y1[$i]) && $panel_y1[$i] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel_y2[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final</option>
                                                                    @foreach($options as $option)
                                                                        <option value="{{ $option }}" {{ (isset($panel_y2[$i]) && $panel_y2[$i] == $option) ? 'selected' : '' }}>{{ $option }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    const options = @json($options);
                                                    const tablaPaneles = document.getElementById('tablaPaneles').querySelector('tbody');
                                                    let panelCount = {{ $numFilas }};
                                            
                                                    document.getElementById('agregarFila').addEventListener('click', function(event) {
                                                        event.preventDefault();
                                                        panelCount++;
                                            
                                                        // Crear nueva fila
                                                        const nuevaFila = document.createElement('tr');
                                                        nuevaFila.innerHTML = `
                                                            <th scope="row">Panel ${panelCount}</th>
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" name="simetria_piezas[]" placeholder="Panel ${panelCount}" />
                                                            </td>
                                                            <!-- Panel X -->
                                                            <td>
                                                                <select name="panel_x1[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial</option>
                                                                    ${options.map(option => `<option value="${option}">${option}</option>`).join('')}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel_x2[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final</option>
                                                                    ${options.map(option => `<option value="${option}">${option}</option>`).join('')}
                                                                </select>
                                                            </td>
                                                            <!-- Panel Y -->
                                                            <td>
                                                                <select name="panel_y1[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial</option>
                                                                    ${options.map(option => `<option value="${option}">${option}</option>`).join('')}
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel_y2[]" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final</option>
                                                                    ${options.map(option => `<option value="${option}">${option}</option>`).join('')}
                                                                </select>
                                                            </td>
                                                        `;
                                            
                                                        // Añadir la nueva fila a la tabla
                                                        tablaPaneles.appendChild(nuevaFila);
                                                    });
                                                });
                                            </script>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3"> 
                                            <label for="pieza_contrapatron" class="col-sm-6 col-form-label">1. Piezas contra patron</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="pieza_contrapatron_estatus" id="pieza_contrapatron_estatus1"
                                                        value="1"
                                                        {{ isset($Lectra) && $Lectra->pieza_contrapatron_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="pieza_contrapatron_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="pieza_contrapatron_estatus" id="pieza_contrapatron_estatus2"
                                                        value="0"
                                                        {{ isset($Lectra) && $Lectra->pieza_contrapatron_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="pieza_contrapatron_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_inspeccionada" class="col-sm-6 col-form-label">Piezas inspeccionadas</label> 
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if($encabezadoAuditoriaCorte->pieza >= 2 && $encabezadoAuditoriaCorte->pieza <= 8)
                                                    <input type="text" class="form-control texto-blanco" readonly value="2" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 9 && $encabezadoAuditoriaCorte->pieza <= 15)
                                                    <input type="text" class="form-control texto-blanco" readonly value="3" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 16 && $encabezadoAuditoriaCorte->pieza <= 25)
                                                    <input type="text" class="form-control texto-blanco" readonly value="5" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 26 && $encabezadoAuditoriaCorte->pieza <= 50)
                                                    <input type="text" class="form-control texto-blanco" readonly value="8" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 51 && $encabezadoAuditoriaCorte->pieza <= 90)
                                                    <input type="text" class="form-control texto-blanco" readonly value="13" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 91 && $encabezadoAuditoriaCorte->pieza <= 150)
                                                    <input type="text" class="form-control texto-blanco" readonly value="20" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 151 && $encabezadoAuditoriaCorte->pieza <= 280)
                                                    <input type="text" class="form-control texto-blanco" readonly value="32" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 281 && $encabezadoAuditoriaCorte->pieza <= 500)
                                                    <input type="text" class="form-control texto-blanco" readonly value="50" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 501 && $encabezadoAuditoriaCorte->pieza <= 1200)
                                                    <input type="text" class="form-control texto-blanco" readonly value="80" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 1201 && $encabezadoAuditoriaCorte->pieza <= 3200)
                                                    <input type="text" class="form-control texto-blanco" readonly value="125" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 3201 && $encabezadoAuditoriaCorte->pieza <= 10000)
                                                    <input type="text" class="form-control texto-blanco" readonly value="200" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 10001 && $encabezadoAuditoriaCorte->pieza <= 35000)
                                                    <input type="text" class="form-control texto-blanco" readonly value="315" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @endif
                                            </div> 
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nivel_aql" class="col-sm-6 col-form-label">Nivel AQL</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" name="nivel_aql" id="nivel_aql">
                                                    <option value="1.0" {{ isset($Lectra) && $Lectra->nivel_aql == '1.0' ? 'selected' : '' }}>1.0</option>
                                                    <option value="1.5" {{ isset($Lectra) && $Lectra->nivel_aql == '1.5' ? 'selected' : '' }}>1.5</option>
                                                    <option value="2.5" {{ isset($Lectra) && $Lectra->nivel_aql == '2.5' ? 'selected' : '' }}>2.5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="cantidad_defecto"
                                                       id="cantidad_defecto" placeholder="..."
                                                       value="{{ isset($Lectra) ? $Lectra->cantidad_defecto : '' }}"
                                                       required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="porcentaje" id="porcentaje" placeholder="..."
                                                    value="{{ isset($calculoPorcentaje) ? $calculoPorcentaje : '' }}" readonly step="0.01"/>
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12">
                                                <!-- Campo oculto para enviar el mensaje de validación al formulario -->
                                                <input type="hidden" name="estado_validacion" id="estado_validacion" />
                                                <input type="text" class="form-control texto-blanco" name="mensaje_validacion" readonly id="mensaje_validacion" 
                                                value="{{ isset($Lectra) ? $Lectra->estado_validacion : '' }}" readonly />
                                            </div>
                                        </div>
                                        
                                        <script>
                                            // Obtener referencia al input de cantidad de defectos y al input de porcentaje
                                            const cantidadDefectosInput = document.getElementById('cantidad_defecto');
                                            const porcentajeInput = document.getElementById('porcentaje');
                                            const piezasInspeccionadasInput = document.getElementById('pieza_inspeccionada_input');
                                        
                                            // Función para calcular el porcentaje y actualizar el input de porcentaje
                                            function actualizarPorcentaje() {
                                                const cantidadDefectos = parseFloat(cantidadDefectosInput.value);
                                                const piezasInspeccionadas = parseFloat(piezasInspeccionadasInput.value);
                                        
                                                // Si piezasInspeccionadas es 0, asignarle el valor del select inicial
                                                const piezasIniciales = {
                                                    2: 2,
                                                    3: 3,
                                                    5: 5,
                                                    8: 8,
                                                    13: 13,
                                                    20: 20,
                                                    32: 32,
                                                    50: 50,
                                                    80: 80,
                                                    125: 125,
                                                    200: 200,
                                                    315: 315
                                                };
                                        
                                                const valorInicial = piezasIniciales[piezasInspeccionadas] || 0;
                                                const porcentaje = piezasInspeccionadas !== 0 ? (cantidadDefectos / valorInicial) * 100 : 0; // Evitar división por cero
                                                porcentajeInput.value = porcentaje.toFixed(2);
                                            }
                                        
                                            // Escuchar el evento input en el input de cantidad de defectos para actualizar el porcentaje
                                            cantidadDefectosInput.addEventListener('input', actualizarPorcentaje);
                                            // Escuchar el evento change en el select de piezas inspeccionadas para actualizar el porcentaje
                                            piezasInspeccionadasInput.addEventListener('change', actualizarPorcentaje);
                                        
                                            // Calcular el porcentaje inicial al cargar la página
                                            actualizarPorcentaje();

                                            const selectOpcion = document.getElementById('nivel_aql');
                                            const mensajeValidacionInput = document.getElementById('mensaje_validacion');

                                            function validarCantidadDefectos() {
                                                const piezasInspeccionadas = parseFloat(piezasInspeccionadasInput.value);
                                                const cantidadDefectos = parseFloat(cantidadDefectosInput.value);
                                                const opcion = selectOpcion.value;

                                                let minCantidadDefectos;
                                                let maxCantidadDefectos;

                                                switch (opcion) {
                                                    case '1.0':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 800:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 1250:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    case '1.5':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 800:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    case '2.5':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    default:
                                                        minCantidadDefectos = 0;
                                                        maxCantidadDefectos = 0;
                                                        break;
                                                }

                                                if (cantidadDefectos >= minCantidadDefectos && cantidadDefectos <= maxCantidadDefectos) {
                                                    mensajeValidacionInput.value = 'Aceptable';
                                                    document.getElementById('estado_validacion').value = 'Aceptable';
                                                } else {
                                                    mensajeValidacionInput.value = 'Rechazado';
                                                    document.getElementById('estado_validacion').value = 'Rechazado';
                                                }
                                            }

                                            selectOpcion.addEventListener('change', validarCantidadDefectos);
                                            cantidadDefectosInput.addEventListener('input', validarCantidadDefectos);

                                        </script>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="defecto[]" id="defecto" class="form-control" multiple title="Por favor, selecciona una opción" required>
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="OTRO">OTRO</option>
                                                    @foreach ($CategoriaDefectoCorteTendido as $corteTendido)
                                                        <option value="{{ $corteTendido->nombre }}"
                                                            {{ isset($Lectra) && in_array(trim($corteTendido->nombre), explode(',', trim($Lectra->defecto))) ? 'selected' : '' }}>
                                                            {{ $corteTendido->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="comentario_lectra" class="col-sm-6 col-form-label">Comentario: </label>
                                            <div class="form-check">
                                                <input type="text" class="form-control texto-blanco"
                                                    name="comentario_lectra" id="comentario_lectra" placeholder="..."
                                                    value="{{ isset($Lectra) ? $Lectra->comentario_lectra : '' }}"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Modal para el nuevo select -->
                                    <div class="modal fade" id="otroDefectoCorteModal" tabindex="-1" role="dialog" aria-labelledby="otroDefectoCorteModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content bg-dark text-white">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="otroDefectoCorteModalLabel">Agregar Nuevo Defecto de Corte</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                            <div class="form-group">
                                              <label for="nuevoDefectoCorte">Nuevo Defecto</label>
                                              <input type="text" class="form-control" id="nuevoDefectoCorte">
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="button" class="btn btn-primary" id="guardarNuevoDefectoCorte">Guardar</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>                                    
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        @if($mostrarFinalizarLectra)
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        @else
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        @endif
                                    </div>
                                </form>
                                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) CORTADOR(ES)</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($Lectra))
                                                    @foreach (explode(',', trim($Lectra->nombre)) as $nombre)
                                                        <input type="text" class="form-control mb-2  texto-blanco" value="{{ $nombre }}" readonly />
                                                    @endforeach
                                                @else
                                                    <select name="nombre[]" id="nombrel" class="form-control" multiple required>
                                                        <option value="">Selecciona una opción</option>
                                                        @foreach ($operarioCorte as $nombre)
                                                            <option value="{{ $nombre->nombre }}">{{ $nombre->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                                {{ isset($Lectra) ? $Lectra->created_at->format('d ') . $mesesEnEspanol[$Lectra->created_at->format('n') - 1] . $Lectra->created_at->format(' Y - H:i:s') : '' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">Maquina Lectra:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($Lectra))
                                                    <input type="text" class="form-control texto-blanco" value="{{ $Lectra->mesa }}" readonly />
                                                @else
                                                    <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="IH8">IH8</option>
                                                        <option value="IX6">IX6</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor" 
                                                    value="{{ isset($Lectra) ? $Lectra->auditor : '' }}" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        @php
                                            // Obtener datos desde la variable $Lectra
                                            $simetria_piezas = isset($Lectra) ? explode(',', $Lectra->simetria_piezas) : [];
                                            $panel_x1 = isset($Lectra) ? explode(',', $Lectra->panel_x1) : [];
                                            $panel_x2 = isset($Lectra) ? explode(',', $Lectra->panel_x2) : [];
                                            $panel_y1 = isset($Lectra) ? explode(',', $Lectra->panel_y1) : [];
                                            $panel_y2 = isset($Lectra) ? explode(',', $Lectra->panel_y2) : [];

                                            // Determinar el número de filas con base en los datos obtenidos
                                            $numFilas = max(count($simetria_piezas), count($panel_x1), count($panel_x2), count($panel_y1), count($panel_y2), 1);
                                        @endphp
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Panel</th>
                                                        <th scope="col">Simetría de piezas</th>
                                                        <th scope="col" colspan="2">X° ANCHO</th>
                                                        <th scope="col" colspan="2">Y° LARGO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Generar filas con datos de solo lectura -->
                                                    @for ($i = 0; $i < $numFilas; $i++)
                                                        <tr>
                                                            <th scope="row">Panel {{ $i + 1 }}</th>
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $simetria_piezas[$i] ?? '' }}" readonly />
                                                            </td>
                                                            <!-- Panel X -->
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $panel_x1[$i] ?? '' }}" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $panel_x2[$i] ?? '' }}" readonly />
                                                            </td>
                                                            <!-- Panel Y -->
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $panel_y1[$i] ?? '' }}" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control texto-blanco" value="{{ $panel_y2[$i] ?? '' }}" readonly />
                                                            </td>
                                                        </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>
                                    
                                        <div class="col-md-6 mb-3"> 
                                            <label for="pieza_contrapatron" class="col-sm-6 col-form-label">1. Piezas contra patron</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                @if (isset($Lectra))
                                                    @if ($Lectra->pieza_contrapatron_estatus == 1)
                                                        <label class="label-paloma">✔</label>
                                                    @else
                                                        <label class="label-tache">✖</label>
                                                    @endif
                                                @endif
                                            </div>
                                        </div> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_inspeccionada" class="col-sm-6 col-form-label">Piezas inspeccionadas</label> 
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if($encabezadoAuditoriaCorte->pieza >= 2 && $encabezadoAuditoriaCorte->pieza <= 8)
                                                    <input type="text" class="form-control texto-blanco" readonly value="2" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 9 && $encabezadoAuditoriaCorte->pieza <= 15)
                                                    <input type="text" class="form-control texto-blanco" readonly value="3" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 16 && $encabezadoAuditoriaCorte->pieza <= 25)
                                                    <input type="text" class="form-control texto-blanco" readonly value="5" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 26 && $encabezadoAuditoriaCorte->pieza <= 50)
                                                    <input type="text" class="form-control texto-blanco" readonly value="8" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 51 && $encabezadoAuditoriaCorte->pieza <= 90)
                                                    <input type="text" class="form-control texto-blanco" readonly value="13" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 91 && $encabezadoAuditoriaCorte->pieza <= 150)
                                                    <input type="text" class="form-control texto-blanco" readonly value="20" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 151 && $encabezadoAuditoriaCorte->pieza <= 280)
                                                    <input type="text" class="form-control texto-blanco" readonly value="32" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 281 && $encabezadoAuditoriaCorte->pieza <= 500)
                                                    <input type="text" class="form-control texto-blanco" readonly value="50" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 501 && $encabezadoAuditoriaCorte->pieza <= 1200)
                                                    <input type="text" class="form-control texto-blanco" readonly value="80" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 1201 && $encabezadoAuditoriaCorte->pieza <= 3200)
                                                    <input type="text" class="form-control texto-blanco" readonly value="125" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 3201 && $encabezadoAuditoriaCorte->pieza <= 10000)
                                                    <input type="text" class="form-control texto-blanco" readonly value="200" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @elseif($encabezadoAuditoriaCorte->pieza >= 10001 && $encabezadoAuditoriaCorte->pieza <= 35000)
                                                    <input type="text" class="form-control texto-blanco" readonly value="315" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                @endif
                                            </div> 
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nivel_aql" class="col-sm-6 col-form-label">Nivel AQL</label>
                                            <div class="col-sm-12">
                                                @if (isset($Lectra))
                                                    <input type="text" class="form-control texto-blanco" value="{{ $Lectra->nivel_aql }}" readonly />
                                                @else
                                                    <select class="form-control" name="nivel_aql" id="nivel_aql">
                                                        <option value="1.0">1.0</option>
                                                        <option value="1.5">1.5</option>
                                                        <option value="2.5">2.5</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="cantidad_defecto"
                                                    id="cantidad_defecto" placeholder="..."
                                                    value="{{ isset($Lectra) ? $Lectra->cantidad_defecto : '' }}"
                                                    readonly required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="porcentaje" id="porcentaje" placeholder="..."
                                                    value="{{ isset($Lectra) ? $Lectra->porcentaje : '' }}" readonly step="0.01"/>
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12">
                                                <!-- Campo oculto para enviar el mensaje de validación al formulario -->
                                                <input type="hidden" name="estado_validacion" id="estado_validacion" />
                                                <input type="text" class="form-control texto-blanco" name="mensaje_validacion" readonly id="mensaje_validacion" 
                                                value="{{ isset($Lectra) ? $Lectra->estado_validacion : '' }}" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if (isset($Lectra))
                                                    @foreach (explode(',', trim($Lectra->defecto)) as $defecto)
                                                        <input type="text" class="form-control mb-2  texto-blanco" value="{{ $defecto }}" readonly />
                                                    @endforeach
                                                @else
                                                    <select name="defecto[]" id="defecto" class="form-control" multiple title="Por favor, selecciona una opción" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="ninguno">Ninguno</option>
                                                        @foreach ($CategoriaDefectoCorteTendido as $corteTendido)
                                                            <option value="{{ $corteTendido->nombre }}">{{ $corteTendido->nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="comentario_lectra" class="col-sm-6 col-form-label">Comentario: </label>
                                            <div class="form-check">
                                                <input type="text" class="form-control texto-blanco"
                                                    name="comentario_lectra" id="comentario_lectra" placeholder="..."
                                                    value="{{ isset($Lectra) ? $Lectra->comentario_lectra : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin cuerpo acordeon --}}
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 3 -->
                    <!--Inicio acordeon 4 -->
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <button id="btnFour" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    - - AUDITORIA EN BULTOS - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                {{-- Inicio cuerpo acordeon --}}
                                @if($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra'))
                                    <p>-</p>
                                @elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto')
                                <form method="POST"
                                    action="{{ route('auditoriaCorte.formAuditoriaBultoV2') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $encabezadoAuditoriaCorte->id }}">
                                    <input type="hidden" name="idBulto" value="{{ $auditoriaBulto->encabezado_id }}"> 
                                    {{-- Campo oculto para el boton Finalizar --}}
                                    <input type="hidden" name="accion" value="">
                                    <div class="row"> 
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE DEL SELLADOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="nombre[]" id="nombreb" class="form-control" multiple required>
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($operarioCorte as $nombre)
                                                        <option value="{{ $nombre->nombre }}"
                                                            {{ isset($auditoriaBulto) && in_array(trim($nombre->nombre), explode(',', trim($auditoriaBulto->nombre))) ? 'selected' : '' }}>
                                                            {{ $nombre->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ isset($auditoriaBulto) ? $auditoriaBulto->created_at->format('d ') . $mesesEnEspanol[$auditoriaBulto->created_at->format('n') - 1] . $auditoriaBulto->created_at->format(' Y - H:i:s') : '' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            @php
                                                $nombreMesa = "SELLADO";
                                            @endphp
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="mesa" id="mesa" placeholder="..."
                                                    value="{{ isset($nombreMesa) ? $nombreMesa : '' }}" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                                        value="{{ $auditorDato }}" readonly required />
                                                    <input type="hidden" name="auditor" value="{{ $auditorDato }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_paquete" class="col-sm-6 col-form-label">1. Piezas por paquete</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input type="number" class="form-control me-2"
                                                               name="pieza_paquete" id="pieza_paquete" placeholder="..."
                                                               value="{{ isset($auditoriaBulto) ? $auditoriaBulto->pieza_paquete : '' }}"
                                                               required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_bulto" class="col-sm-6 col-form-label">2. Cantidad de Bultos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="cantidad_bulto" id="cantidad_bulto" placeholder="..."
                                                           value="{{ isset($calculoPorcentajeBulto) ? $calculoPorcentajeBulto : '' }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            // Obtener referencia al input de piezas por paquete y al input de cantidad de bultos
                                            const piezasPorPaqueteInput = document.getElementById('pieza_paquete');
                                            const cantidadBultoInput = document.getElementById('cantidad_bulto');
                                        
                                            // Función para calcular la cantidad de bultos y actualizar el input de cantidad de bultos
                                            function actualizarCantidadBulto() {
                                                const piezasPorPaquete = parseFloat(piezasPorPaqueteInput.value);
                                                const cantidadBulto = piezasPorPaquete !== 0 ? Math.ceil({{ $encabezadoAuditoriaCorte->pieza }} / piezasPorPaquete) : 0; // Evitar división por cero
                                                cantidadBultoInput.value = cantidadBulto;
                                            }
                                        
                                            // Escuchar el evento input en el input de piezas por paquete para actualizar la cantidad de bultos
                                            piezasPorPaqueteInput.addEventListener('input', actualizarCantidadBulto);
                                        
                                            // Calcular la cantidad de bultos inicial al cargar la página
                                            actualizarCantidadBulto();
                                        </script>
                                        <div class="col-md-6 mb-3">
                                            <label for="ingreso_ticket" class="col-sm-6 col-form-label">3. Ingreso de Tickets</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ingreso_ticket_estatus" id="ingreso_ticket_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="ingreso_ticket_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ingreso_ticket_estatus" id="ingreso_ticket_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="ingreso_ticket_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sellado_paquete" class="col-sm-6 col-form-label">4. Sellado de Paquetes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="sellado_paquete_estatus" id="sellado_paquete_estatus1"
                                                        value="1"
                                                        {{ isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 1 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-paloma" for="sellado_paquete_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="sellado_paquete_estatus" id="sellado_paquete_estatus2"
                                                        value="0"
                                                        {{ isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 0 ? 'checked' : '' }}
                                                        required />
                                                    <label class="label-tache" for="sellado_paquete_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="cantidad_defecto"
                                                    id="cantidad_defecto_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->cantidad_defecto : '' }}"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="defecto"
                                                    id="defecto_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->defecto : '' }}"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="porcentaje"
                                                    id="porcentaje_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->porcentaje : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Obtener referencias a los elementos del DOM
                                                const piezaPaqueteInput = document.getElementById('pieza_paquete');
                                                const cantidadDefectoInput = document.getElementById('cantidad_defecto_bulto');
                                                const porcentajeInput = document.getElementById('porcentaje_bulto');
                                        
                                                // Función para actualizar el porcentaje
                                                function actualizarPorcentaje() {
                                                    const piezaPaquete = parseFloat(piezaPaqueteInput.value) || 0;
                                                    const cantidadDefecto = parseFloat(cantidadDefectoInput.value) || 0;
                                                    console.log('pieza_paquete:', piezaPaquete);
                                                    console.log('cantidad_defecto:', cantidadDefecto);
                                        
                                                    if (piezaPaquete !== 0) {
                                                        const porcentaje = (cantidadDefecto / piezaPaquete) * 100;
                                                        console.log('porcentaje:', porcentaje);
                                                        if (!isNaN(porcentaje)) {
                                                            porcentajeInput.value = porcentaje.toFixed(2);
                                                            console.log('porcentajeInput.value:', porcentajeInput.value);
                                                        } else {
                                                            porcentajeInput.value = '';
                                                        }
                                                    } else {
                                                        porcentajeInput.value = '';
                                                    }
                                                }
                                        
                                                // Agregar eventos de escucha a los inputs
                                                piezaPaqueteInput.addEventListener('input', actualizarPorcentaje);
                                                cantidadDefectoInput.addEventListener('input', actualizarPorcentaje);
                                        
                                                // Calcular el porcentaje inicial al cargar la página
                                                actualizarPorcentaje();
                                            });
                                        </script>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        @if($mostrarFinalizarBulto)
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        @else
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        @endif
                                    </div>
                                </form>
                                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))
                                    <<div class="row"> 
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE DEL SELLADOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                @if(isset($auditoriaBulto))
                                                    @foreach (explode(',', $auditoriaBulto->nombre) as $nombre)
                                                        <input type="text" class="form-control me-2 mb-2 texto-blanco" value="{{ trim($nombre) }}" readonly />
                                                    @endforeach
                                                @else
                                                    <input type="text" class="form-control me-2 texto-blanco" value="No hay datos" readonly />
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                {{ isset($auditoriaBulto) ? $auditoriaBulto->created_at->format('d ') . $mesesEnEspanol[$auditoriaBulto->created_at->format('n') - 1] . $auditoriaBulto->created_at->format(' Y - H:i:s') : '' }}
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            @php
                                                $nombreMesa = "SELLADO";
                                            @endphp
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="mesa" id="mesa" placeholder="..."
                                                    value="{{ isset($nombreMesa) ? $nombreMesa : '' }}" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->auditor : '' }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_paquete" class="col-sm-6 col-form-label">1. Piezas por paquete</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input type="number" class="form-control me-2"
                                                               name="pieza_paquete" id="pieza_paquete" placeholder="..."
                                                               value="{{ isset($auditoriaBulto) ? $auditoriaBulto->pieza_paquete : '' }}"
                                                               readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_bulto" class="col-sm-6 col-form-label">2. Cantidad de Bultos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="cantidad_bulto" id="cantidad_bulto" placeholder="..."
                                                           value="{{ isset($auditoriaBulto) ? $auditoriaBulto->cantidad_bulto : '' }}" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ingreso_ticket" class="col-sm-6 col-form-label">3. Ingreso de Tickets</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if(isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 1)
                                                        <label class="label-paloma">✔</label>
                                                    @else
                                                        <label class="label-tache">✖</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sellado_paquete" class="col-sm-6 col-form-label">4. Sellado de Paquetes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    @if(isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 1)
                                                        <label class="label-paloma">✔</label>
                                                    @else
                                                        <label class="label-tache">✖</label>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="cantidad_defecto"
                                                    id="cantidad_defecto_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->cantidad_defecto : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="defecto"
                                                    id="defecto_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->defecto : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="porcentaje"
                                                    id="porcentaje_bulto" placeholder="..."
                                                    value="{{ isset($auditoriaBulto) ? $auditoriaBulto->porcentaje : '' }}"
                                                    readonly />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin cuerpo acordeon --}}
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 4 -->
                    <!--Inicio acordeon 5 -->
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                                <button id="btnFive" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    - - AUDITORIA FINAL - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                                {{-- Inicio cuerpo acordeon --}}
                                @if ($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal')
                                    <form method="POST"
                                        action="{{ route('auditoriaCorte.formAuditoriaFinalV2') }}">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $encabezadoAuditoriaCorte->id }}">
                                        <input type="hidden" name="idAuditoriaFinal" value="{{ $auditoriaFinal->encabezado_id }}">
                                        {{-- Campo oculto para el boton Finalizar --}}
                                        <input type="hidden" name="accion" value="">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="supervisor_corte" class="col-sm-6 col-form-label">Supervisor de Corte</label>
                                                @php
                                                    $supervisorCorteFinal = "DAVID"
                                                @endphp
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2 texto-blanco" name="supervisor_corte" id="supervisor_corte"
                                                        value="{{ $supervisorCorteFinal }}" readonly />
                                                    <input type="hidden" name="supervisor_corte" value="{{ $supervisorCorteFinal }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="aceptado_condicion" class="col-sm-6 col-form-label">Aceptado con condiciones :</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" name="aceptado_condicion" id="aceptado_condicion" rows="3"
                                                        placeholder="comentarios" required>{{ isset($auditoriaFinal) ? $auditoriaFinal->aceptado_condicion : '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Aceptado - Rechazado</label>
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="aceptado_rechazado" id="estatus1"
                                                            value="1"
                                                            {{ isset($auditoriaFinal) && $auditoriaFinal->aceptado_rechazado == 1 ? 'checked' : '' }}
                                                            required />
                                                        <label class="label-paloma" for="estatus1">✔
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="aceptado_rechazado" id="estatus2"
                                                            value="0"
                                                            {{ isset($auditoriaFinal) && $auditoriaFinal->aceptado_rechazado == 0 ? 'checked' : '' }}
                                                            required />
                                                        <label class="label-tache" for="estatus2">✖ </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn-verde-xd">Guardar</button>
                                            @if($mostrarFinalizarFinal)
                                                <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                            @else
                                                <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                            @endif
                                        </div>
                                    </form>
                                @elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin'))
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="supervisor_corte" class="col-sm-6 col-form-label">Supervisor de Corte</label>
                                            @php
                                                $supervisorCorteFinal = "DAVID";
                                            @endphp
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2 texto-blanco" name="supervisor_corte" id="supervisor_corte"
                                                    value="{{ $supervisorCorteFinal }}" readonly />
                                                <input type="hidden" name="supervisor_corte" value="{{ $supervisorCorteFinal }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="aceptado_condicion" class="col-sm-6 col-form-label">Aceptado con condiciones :</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="aceptado_condicion" id="aceptado_condicion" rows="3" placeholder="comentarios" readonly>{{ isset($auditoriaFinal) ? $auditoriaFinal->aceptado_condicion : '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                @if(isset($auditoriaFinal))
                                                    @if($auditoriaFinal->aceptado_rechazado == 1)
                                                        <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Aceptado</label>
                                                        <label class="label-paloma">✔</label>
                                                    @else
                                                        <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Rechazado</label>
                                                        <label class="label-tache">✖</label>
                                                    @endif
                                                @else
                                                    <label class="label-tache">✖</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                {{-- Fin cuerpo acordeon --}}
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 5 -->
                </div>
                <!--Fin div de acordeon -->
            </div>
        </div>
        <style>
            /* Estilos personalizados para los elementos de tipo "radio" */
            input[type="radio"] {
                width: 20px;
                /* Ancho personalizado */
                height: 20px;
                /* Altura personalizada */
                /* Otros estilos personalizados según tus necesidades */
            }

            .label-paloma {
                font-size: 20px;
                /* Tamaño de fuente personalizado */
                color: #33a533;
                /* Color de texto personalizado */
                font-weight: bold;
                /* Texto en negritas (bold) */
                /* Otros estilos personalizados según tus necesidades */
            }

            .label-tache {
                font-size: 20px;
                /* Tamaño de fuente personalizado */
                color: #b61711;
                /* Color de texto personalizado */
                font-weight: bold;
                /* Texto en negritas (bold) */
                /* Otros estilos personalizados según tus necesidades */
            }

            .form-check-inline {
                margin-right: 25px;
            }

            .form-control.me-2 {
                margin-right: 25px;
                /* Ajusta la cantidad de margen según tus necesidades */
            }

            .quitar-espacio {
                margin-right: 10px;
            }

            .texto-blanco {
                color: white !important;
            }
        </style>
        <!-- Script para abrir el acordeón correspondiente -->
        <script>
            // Obtenemos el valor del estatus desde el HTML generado por PHP en Laravel
            var estatus = @json(optional($encabezadoAuditoriaCorte)->estatus);
            const estatusTextos = {
                'estatusAuditoriaMarcada': 'Auditoria de Marcada',
                'estatusAuditoriaTendido': 'Auditoria de Tendido',
                'estatusLectra': 'Lectra',
                'estatusAuditoriaBulto': 'Auditoria en Bultos',
                'estatusAuditoriaFinal': 'Auditoria Final',
                'fin': 'Terminado'
                // Agrega otros valores para los demás estados
            };
            const estatusTexto = estatusTextos[estatus];

            // Verificamos si el valor de estatus se estableció correctamente
            if (estatus) {
                // Mostramos el valor en la página
                document.getElementById("estatusValue").innerText = "Estatus: " + estatusTexto;

                // Dependiendo del valor de estatus, abrimos el acordeón correspondiente
                switch (estatus) {
                    case "estatusAuditoriaMarcada":
                        // Abre el acordeón 1
                        document.getElementById("collapseOne").classList.add("show");
                        document.getElementById("btnOne").classList.remove("btn-info");
                        document.getElementById("btnOne").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaTendido":
                        // Abre el acordeón 2
                        document.getElementById("collapseTwo").classList.add("show");
                        document.getElementById("btnTwo").classList.remove("btn-info");
                        document.getElementById("btnTwo").classList.add("btn-primary");
                        break;
                    case "estatusLectra":
                        // Abre el acordeón 3
                        document.getElementById("collapseThree").classList.add("show");
                        document.getElementById("btnThree").classList.remove("btn-info");
                        document.getElementById("btnThree").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaBulto":
                        // Abre el acordeón 4
                        document.getElementById("collapseFour").classList.add("show");
                        document.getElementById("btnFour").classList.remove("btn-info");
                        document.getElementById("btnFour").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaFinal":
                        // Abre el acordeón 5
                        document.getElementById("collapseFive").classList.add("show");
                        document.getElementById("btnFive").classList.remove("btn-info");
                        document.getElementById("btnFive").classList.add("btn-primary");
                        break;
                    default:
                        console.log("El valor de estatus no coincide con ninguna opción válida para abrir un acordeón.");
                }
            } else {
                console.log("ERROR: No se pudo obtener el valor de estatus.");
            }
        </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="text"]');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });
        });

    </script>

    <script>
        $('#defecto_material').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombre').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombrel').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombreb').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#defecto').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
    </script>

    <script>
        $(document).ready(function() {
            // Función para el primer select
            $('#defecto_material').change(function() {
                if ($(this).val().includes('OTRO')) {
                    $('#otroDefectoModal').modal('show');
                }
            });
        
            $('#guardarNuevoDefecto').click(function() {
                var nuevoDefecto = $('#nuevoDefecto').val();
                if (nuevoDefecto) {
                    $.ajax({
                        url: '{{ route("auditoriaCorte.agregarDefecto") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: nuevoDefecto,
                            area: 'tendido',
                            estado: 1
                        },
                        success: function(response) {
                            if (response.success) {
                                // Agregar el nuevo defecto al select
                                $('#defecto_material').append(new Option(nuevoDefecto, nuevoDefecto));
                                $('#defecto_material').val($('#defecto_material').val().filter(item => item !== 'OTRO')); // Quitar la opción "OTRO"
                                $('#defecto_material').val([...$('#defecto_material').val(), nuevoDefecto]); // Seleccionar el nuevo defecto
                                $('#defecto_material').trigger('change'); // Disparar el evento de cambio para que se actualice la selección
                                $('#otroDefectoModal').modal('hide');
                                $('#nuevoDefecto').val(''); // Limpiar el input del modal
                            } else {
                                alert('Error al agregar el defecto');
                            }
                        },
                        error: function() {
                            alert('Error en la solicitud AJAX');
                        }
                    });
                }
            });
        
            // Función para el segundo select
            $('#defecto').change(function() {
                if ($(this).val().includes('OTRO')) {
                    $('#otroDefectoCorteModal').modal('show');
                }
            });
        
            $('#guardarNuevoDefectoCorte').click(function() {
                var nuevoDefectoCorte = $('#nuevoDefectoCorte').val();
                if (nuevoDefectoCorte) {
                    $.ajax({
                        url: '{{ route("auditoriaCorte.agregarDefecto") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            nombre: nuevoDefectoCorte,
                            area: 'tendido',
                            estado: 1
                        },
                        success: function(response) {
                            if (response.success) {
                                // Agregar el nuevo defecto al select
                                $('#defecto').append(new Option(nuevoDefectoCorte, nuevoDefectoCorte));
                                $('#defecto').val($('#defecto').val().filter(item => item !== 'OTRO')); // Quitar la opción "OTRO"
                                $('#defecto').val([...$('#defecto').val(), nuevoDefectoCorte]); // Seleccionar el nuevo defecto
                                $('#defecto').trigger('change'); // Disparar el evento de cambio para que se actualice la selección
                                $('#otroDefectoCorteModal').modal('hide');
                                $('#nuevoDefectoCorte').val(''); // Limpiar el input del modal
                            } else {
                                alert('Error al agregar el defecto');
                            }
                        },
                        error: function() {
                            alert('Error en la solicitud AJAX');
                        }
                    });
                }
            });
        });
        </script>
        
        <script>
            document.getElementById('largo_trazo').addEventListener('input', function() {
                var cmValue = this.value;
                var mtValue = cmValue / 100;
                document.getElementById('largo_trazo_mt').value = mtValue.toFixed(2) + ' mt';
            });
        
            document.getElementById('ancho_trazo').addEventListener('input', function() {
                var cmValue = this.value;
                var mtValue = cmValue / 100;
                document.getElementById('ancho_trazo_mt').value = mtValue.toFixed(2) + ' mt';
            });
        
            // Si hay valores iniciales, convertir al cargar la página
            window.onload = function() {
                var largoTrazo = document.getElementById('largo_trazo').value;
                if (largoTrazo) {
                    var mtValue = largoTrazo / 100;
                    document.getElementById('largo_trazo_mt').value = mtValue.toFixed(2) + ' mt';
                }
        
                var anchoTrazo = document.getElementById('ancho_trazo').value;
                if (anchoTrazo) {
                    var mtValue = anchoTrazo / 100;
                    document.getElementById('ancho_trazo_mt').value = mtValue.toFixed(2) + ' mt';
                }
            };
        </script>
    @endsection
