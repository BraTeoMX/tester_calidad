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
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA CONTROL DE CALIDAD</h3>
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
                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}"> 
                        @csrf
                        <div class="table-responsive">
                            <table class="table table10">
                                <thead class="thead-primary"> 
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>ESTILO</th> 
                                        <th>CLIENTE</th>
                                        <th>SUPERVISOR</th>
                                        <th>GERENTE DE PRODUCCION</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="area" id="area"
                                            value="AUDITORIA EN PROCESO" readonly  />
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required title="Por favor, selecciona una opción" onchange="obtenerSupervisor()">
                                                <option value="" selected>Selecciona una opción</option>
                                                @if ($auditorPlanta == 'Planta1')
                                                    @foreach ($auditoriaProcesoIntimark1 as $moduloP1)
                                                        <option value="{{ $moduloP1->moduleid }}" data-itemid="{{ $moduloP1->itemid }}">
                                                            {{ $moduloP1->moduleid }}
                                                        </option>
                                                    @endforeach
                                                @elseif($auditorPlanta == 'Planta2')
                                                    @foreach ($auditoriaProcesoIntimark2 as $moduloP2)
                                                        <option value="{{ $moduloP2->moduleid }}" data-itemid="{{ $moduloP2->itemid }}">
                                                            {{ $moduloP2->moduleid }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control texto-blanco" name="estilo" id="estilo" required>
                                                <option value="">Selecciona una opción</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control texto-blanco" name="cliente" id="cliente" readonly required />
                                        </td>
                                        
                                        <td>
                                            <select name="team_leader" id="team_leader" class="form-control" required>
                                                <!-- Las opciones se llenarán dinámicamente con la llamada AJAX -->
                                            </select>                                            
                                        </td>
                                        <td>
                                            <select name="gerente_produccion" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                @foreach ($gerenteProduccion as $gerente)
                                                    <option value="{{ $gerente->nombre }}">
                                                        {{ $gerente->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                            value="{{ $auditorDato }}" readonly  /></td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="turno" id="turno"
                                                    value="1" readonly  /></td>                             
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">Iniciar</button>
                    </form> 
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->

                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h5 class="card-title">ESTATUS</h5>
                        </div>
                        <div class="col-auto">

                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        AUDITORIA EN PROCESO
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseOne5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
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
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin del acordeón 1 -->
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseOne6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <input type="text" id="searchInput2" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos2">
                                                                        @foreach($procesoFinal as $proceso)
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
                                        <!-- Fin del acordeón 2 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Fin del apartado del primer acordeon externo--}}
                    {{--Inicio del Segundo acordeon externo--}}
                    {{--<div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                        AUDITORIA EN PROCESO PLAYERA
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                   
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseTwo5" aria-expanded="true" aria-controls="collapseTwo5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseTwo5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($playeraActual as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
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

                                        <div class="col-md-6">

                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseTwo6" aria-expanded="true" aria-controls="collapseTwo6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseTwo6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($playeraFinal as $proceso)
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                        @csrf
                                                                                        <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                        <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                        <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                        <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                        <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                        <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                        <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
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

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>--}}
                    {{--Fin del Segundo Acordeon Externo--}}
                    {{--Inicio del Tercer Acordeon Externo--}}
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                        data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        AUDITORIA EN PROCESO EMPAQUE
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="collapseThree" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseThree5" aria-expanded="true" aria-controls="collapseThree5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body"> 
                                                            <div class="table-responsive">
                                                                <input type="text" id="searchInput3" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos3">
                                                                        @foreach($empaqueActual as $proceso)
                                                                            <tr>
                                                                                <form method="POST" action="{{ route('aseguramientoCalidad.formAltaProceso') }}">
                                                                                    @csrf
                                                                                    <input type="hidden" name="area" value="{{ $proceso->area }}">
                                                                                    <input type="hidden" name="modulo" value="{{ $proceso->modulo }}">
                                                                                    <input type="hidden" name="cliente" value="{{ $proceso->cliente }}">
                                                                                    <input type="hidden" name="estilo" value="{{ $proceso->estilo }}">
                                                                                    <input type="hidden" name="team_leader" value="{{ $proceso->team_leader }}">
                                                                                    <input type="hidden" name="auditor" value="{{ $proceso->auditor }}">
                                                                                    <input type="hidden" name="turno" value="{{ $proceso->turno }}">
                                                                                    <td><button class="btn btn-primary">Acceder</button></td>
                                                                                </form>
                                                                                
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
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
                                        <!-- Fin del acordeón 1 -->
                                        <div class="col-md-6">
                                            {{-- Inicio de Acordeon --}}
                                            <div class="accordion" id="accordionExample6">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne6">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseThree6" aria-expanded="true" aria-controls="collapseThree6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <input type="text" id="searchInput4" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos4">
                                                                        @foreach($empaqueFinal as $proceso)
                                                                            <tr>
                                                                                <td><button class="btn btn-primary">Acceder</button></td>
                                                                                <td>{{ $proceso->modulo }}</td>
                                                                                <td>{{ $proceso->estilo }}</td>
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
                                        <!-- Fin del acordeón 2 -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--Fin del Tercer Acordeon Externo--}}
                    
                    
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>
    </div>

    <style>
        #searchInput1::placeholder, #searchInput2::placeholder, #searchInput3::placeholder, #searchInput4::placeholder {
            color: rgba(255, 255, 255, 0.85);
            font-weight: bold;
        }
      
        #searchInput1, #searchInput2, #searchInput3, #searchInput4 {
            background-color: #1a1f30;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#modulo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
            $('#estilo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });

    </script>

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

        .table10 th:nth-child(1) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(2) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(4) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(6) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(7) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>
    <script>
        function obtenerSupervisor() {
            var moduleid = $('#modulo').val();  // Obtén el valor del select "modulo"
            
            if (moduleid) {
                $.ajax({
                    url: '/obtener-supervisor',  // Ruta para obtener el supervisor
                    type: 'GET',
                    data: { moduleid: moduleid },
                    success: function(response) {
                        var $teamLeader = $('#team_leader');
                        $teamLeader.empty();  // Limpia el select antes de llenarlo

                        // Si hay un supervisor relacionado, lo agrega como seleccionado
                        if (response.supervisorRelacionado) {
                            $teamLeader.append('<option value="' + response.supervisorRelacionado.name + '" selected>' + response.supervisorRelacionado.name + '</option>');
                        }

                        // Agrega las otras opciones de supervisores
                        $.each(response.supervisores, function(index, supervisor) {
                            if (response.supervisorRelacionado && supervisor.name === response.supervisorRelacionado.name) {
                                // Ya se agregó el supervisor relacionado como opción seleccionada, por lo que se omite
                                return;
                            }
                            // Agrega el resto de los supervisores
                            $teamLeader.append('<option value="' + supervisor.name + '">' + supervisor.name + '</option>');
                        });

                        // Si no hay supervisores disponibles, muestra un mensaje opcional
                        if (!response.supervisores.length) {
                            $teamLeader.append('<option value="">No hay supervisores disponibles</option>');
                        }
                    },
                    error: function() {
                        alert('Error al obtener los supervisores');
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#modulo').change(function() {
                var moduleid = $(this).val();
                $('#estilo').html(''); // Limpiar el select anterior
    
                if (moduleid === '830A' || moduleid === '831A') {
                    // Realizar solicitud AJAX para obtener todos los estilos únicos
                    $.ajax({
                        url: '{{ route("obtenerTodosLosEstilosUnicos") }}',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            console.log(response); // Verificar los datos recibidos
                            if (response.itemids && response.itemids.length > 0) {
                                var options = '<option value="">Selecciona una opción</option>';
                                $.each(response.itemids, function(index, itemid) {
                                    options += '<option value="' + itemid + '">' + itemid + '</option>';
                                });
                                $('#estilo').html(options);
                            } else {
                                $('#estilo').html('<option value="">No se encontraron estilos</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    // Realizar solicitud AJAX para obtener los estilos según el módulo seleccionado
                    $.ajax({
                        url: '{{ route("obtenerItemId") }}',
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}',
                            'moduleid': moduleid
                        },
                        success: function(response) {
                            console.log(response); // Verificar los datos recibidos
                            if (response.itemids && response.itemids.length > 0) {
                                var options = '<option value="">Selecciona una opción</option>';
                                $.each(response.itemids, function(index, itemid) {
                                    options += '<option value="' + itemid + '">' + itemid + '</option>';
                                });
                                $('#estilo').html(options);
                            } else {
                                $('#estilo').html('<option value="">No se encontraron estilos</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });
    
            // Manejar la selección del estilo para actualizar el input de cliente
            $('#estilo').change(function() {
                var itemid = $(this).val();
    
                // Realizar solicitud AJAX para obtener el cliente correspondiente al estilo seleccionado
                $.ajax({ 
                    type: 'POST',
                    url: '{{ route("obtenerCliente1") }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        itemid: itemid
                    },
                    success: function(response) {
                        console.log(response); // Verificar los datos recibidos
                        // Actualizar el valor del campo "cliente" con el cliente obtenido de la respuesta AJAX
                        $('#cliente').val(response.cliente);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>


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

            $('#searchInput2').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos2 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput3').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos3 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput4').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos4 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });
    </script>



@endsection
