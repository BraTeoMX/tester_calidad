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
                            <h3>CLIENTE {{$clienteSeleccionado}} </h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    @if($rangoInicial == $rangoFinal)
                        <h3 class="card-title">Seleccionado por el dia {{$rangoInicial}}</h3>
                    @else
                        <h3 class="card-title">Seleccionado de {{$rangoInicial}} al {{$rangoFinal}} </h3>
                    @endif
                    <hr>
                    <div class="row">
                        <h3>AQL</h3>
                        <div class="col-md-12">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Auditor</th>
                                        <th>Estilo</th>
                                        <th>Responsable</th>
                                        <th>Motivo de defecto</th>
                                        <th>Accion correctiva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datosAQLPlanta1TurnoNormal as $registro)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->modulo }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->auditor }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->estilo }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->team_leader }}" readonly>
                                            </td>
                                            <td>
                                                @foreach ($registro->tpAuditoriaAQL as $tp)
                                                    {{ $tp->tp }},&nbsp;
                                                @endforeach
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->ac }}" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="row">
                        <h3>PROCESO</h3>
                        <div class="col-md-12">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Operador</th>
                                        <th>Modulo</th>
                                        <th>Auditor</th>
                                        <th>Estilo</th>
                                        <th>Responsable</th>
                                        <th>Numero de paros </th>
                                        <th>Motivo de defecto</th>
                                        <th>Accion correctiva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datosProcesoPlanta1TurnoNormal as $registro)
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->nombre }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->modulo }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->auditor }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->estilo }}" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->team_leader }}" readonly>
                                            </td>
                                            <td>
                                                
                                                ({{ $registro->cantidad_rechazada > 0 ? $conteoRechazos : 0 }}) <!-- Mostrar el conteo si es mayor a 0 -->
                                            </td>
                                            <td>
                                                @foreach ($registro->tpAseguramientoCalidad as $tp)
                                                    {{ $tp->tp }},&nbsp;
                                                @endforeach
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="{{ $registro->ac }}" readonly>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table> 
                        </div>
                    </div>
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
