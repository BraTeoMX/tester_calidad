@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')])

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
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #28a745;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
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
                            <h3 class="card-title">Modulo {{$nombreModulo}}</h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    @if($rangoInicial == $rangoFinal)
                        <h3 class="card-title">Detalle por modulo seleccionado por el dia {{$rangoInicial}}</h3>
                    @else
                        <h3 class="card-title">Detalle por modulo seleccionado de {{$rangoInicial}} al {{$rangoFinal}} </h3>
                    @endif
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <h3 style="font-weight: bold;">Piezas auditadas</h3>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="table-responsive">
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
                                                <td><input type="text" class="form-control" value="{{ $registro->total_auditada }}" readonly></td>
                                                <td><input type="text" class="form-control" value="{{ $registro->total_rechazada }}" readonly></td>
                                                <td><input type="text" class="form-control" value="{{ $registro->total_rechazada != 0 ? number_format(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0 }}" readonly></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <table class="table contenedor-tabla">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Total de piezas en bultos Auditados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registrosIndividualPieza as $registro)
                                        <tr>
                                            <td><input type="text" class="form-control" value="{{ $registro->total_pieza }}" readonly></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h3 style="font-weight: bold;">Total por Bultos </h3>
                    <div class="table-responsive">
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
                                    <td><input type="text" class="form-control" name="conteo_bulto"
                                            id="conteo_bulto" value="{{ $conteoBultos }}" readonly></td>
                                    <td><input type="text" class="form-control" name="total_rechazada"
                                            id="total_rechazada" value="{{ $conteoPiezaConRechazo }}" readonly></td>
                                    <td><input type="text" class="form-control" name="total_porcentaje"
                                            id="total_porcentaje" value="{{ number_format($porcentajeBulto, 2) }}"
                                            readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <table class="table table55"> 
                        <thead class="thead-primary">
                            <tr>
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
                                        <input type="text" class="form-control" name="bulto"
                                        value="{{ $registro->bulto }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="pieza"
                                        value="{{ $registro->pieza }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="talla"
                                        value="{{ $registro->talla }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="color" id="color"
                                        value="{{$registro->color}}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="estilo" id="estilo"
                                        value="{{$registro->estilo}}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cantidad_auditada" id="cantidad_auditada"
                                        value="{{$registro->cantidad_auditada}}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cantidad_rechazada" id="cantidad_rechazada"
                                        value="{{$registro->cantidad_rechazada}}" readonly>
                                    </td>
                                    
                                    <form action="{{ route('auditoriaAQL.formUpdateDeleteProceso') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $registro->id }}">
                                        <td>
                                            <input type="text" class="form-control" readonly
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
                    <hr>


                </div>
            </div>
        </div>
    </div>

    <style>
        .table1 {
            max-width: 400px; /* Ajusta el valor según tus necesidades */
        }

        /* Personalizar estilo del thead */
        .thead-custom1 {
            background-color: #0c6666; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }
    </style>


@endsection
