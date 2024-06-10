@extends('layouts.app', ['pageSlug' => 'dashboard'])

@section('content')

    <div class="row"> 
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-zoom-split text-success"></i> Seleccion de Cliente por Modulo</h3>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <h4>Cliente seleccionado: {{ $clienteBusqueda }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-12">
            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
            <form action="{{ route('dashboar.detalleXModulo') }}" method="GET" id="filterForm">
                <input type="hidden" name="clienteBusqueda" id="hiddenClienteBusqueda" value="{{ $clienteBusqueda }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </form>
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Obtener los parámetros de la URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const fechaInicio = urlParams.get('fecha_inicio');
                    const fechaFin = urlParams.get('fecha_fin');

                    // Establecer los valores de los campos de fecha
                    document.getElementById("fecha_inicio").value = fechaInicio || '';
                    document.getElementById("fecha_fin").value = fechaFin || '';

                    // Manejar el evento de envío del formulario
                    document.getElementById("filterForm").addEventListener("submit", function(event) {
                        // Agregar los valores de los campos de fecha a la URL del formulario
                        const fechaInicioValue = document.getElementById("fecha_inicio").value || '';
                        const fechaFinValue = document.getElementById("fecha_fin").value || '';
                        this.action = "{{ route('dashboar.detalleXModulo') }}?fecha_inicio=" + fechaInicioValue + "&fecha_fin=" + fechaFinValue;
                    });
                });

            </script>
            <hr>     
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Módulo AQL General</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    @foreach ($semanas as $semana)
                                        <th colspan="2">{{ $semana }}</th>
                                    @endforeach
                                    <th>Porcentaje General</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    @foreach ($semanas as $semana)
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datosAgrupadosAQL as $modulo => $datos)
                                    <tr>
                                        <td>{{ $modulo }}</td>
                                        @foreach ($semanas as $semana)
                                            <td>
                                                @if (isset($datos['semanas'][$semana]))
                                                    {{ $datos['semanas'][$semana]['cantidad'] }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($datos['semanas'][$semana]))
                                                    {{ number_format(($datos['semanas'][$semana]['cantidad_rechazada'] / $datos['semanas'][$semana]['cantidad_auditada']) * 100, 2) }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            @if ($datos['cantidad_total_auditada'] > 0)
                                                {{ number_format(($datos['cantidad_total_rechazada'] / $datos['cantidad_total_auditada']) * 100, 2) }}%
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Módulo Proceso General</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    @foreach ($semanas as $semana)
                                        <th colspan="2">{{ $semana }}</th>
                                    @endforeach
                                    <th>Porcentaje General</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    @foreach ($semanas as $semana)
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datosAgrupadosProceso as $modulo => $datos)
                                    <tr>
                                        <td>{{ $modulo }}</td>
                                        @foreach ($semanas as $semana)
                                            <td>
                                                @if (isset($datos['semanas'][$semana]))
                                                    {{ $datos['semanas'][$semana]['cantidad'] }}
                                                @else
                                                    0
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($datos['semanas'][$semana]))
                                                    {{ number_format(($datos['semanas'][$semana]['cantidad_rechazada'] / $datos['semanas'][$semana]['cantidad_auditada']) * 100, 2) }}%
                                                @else
                                                    0%
                                                @endif
                                            </td>
                                        @endforeach
                                        <td>
                                            @if ($datos['cantidad_total_auditada'] > 0)
                                                {{ number_format(($datos['cantidad_total_rechazada'] / $datos['cantidad_total_auditada']) * 100, 2) }}%
                                            @else
                                                N/A
                                            @endif
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


    <style>
        .chart-area {
          height: 500px; /* Ajusta esta altura según tus necesidades */
        }
      </style>
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>


@endpush
