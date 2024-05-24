@extends('layouts.app')

    @section('content')
    {{-- ... dentro de tu vista ... --}}
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="alert alerta-exito">
        {{ session('success') }}
        @if(session('sorteo'))
            <br>{{ session('sorteo') }}
        @endif
    </div>
    @endif
    @if(session('status')) {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    <style>
    .alerta-exito {
        background-color: #28a745; /* Color de fondo verde */
        color: white; /* Color de texto blanco */
        padding: 20px;
        border-radius: 15px;
        font-size: 20px;
    }
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <!--Aqui se edita el encabezado que es el que se muestra -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2>AUDITORIA DE ETIQUETAS</h2>
                            </div>
                            <div>
                            </div>
                        </div>
                            <div class="card-body">
                                <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                                <div>
                                    <form action="{{ route('formulariosCalidad.filtrarDatosEtiquetas') }}" method="GET">
                                        <div class="row mb-3">
                                            <label for="cliente" class="col-sm-3 col-form-label">CLIENTE</label>
                                            <div class="col-sm-9">
                                                <select name="cliente" id="cliente" class="form-control" required title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($CategoriaCliente as $cliente)
                                                        <option value="{{ $cliente->id }}" @if(request('cliente') == $cliente->id) selected @endif>{{ $cliente->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="estilo" class="col-sm-3 col-form-label">ESTILO</label>
                                            <div class="col-sm-9">
                                                <select name="estilo" id="estilo" class="form-control" title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($CategoriaEstilo as $estilo)
                                                        <option value="{{ $estilo->id }}" @if(request('estilo') == $estilo->id) selected @endif>{{ $estilo->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="no_recibo" class="col-sm-3 col-form-label">NO/RECIBO</label>
                                            <div class="col-sm-9">
                                                <select name="no_recibo" id="no_recibo" class="form-control" title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($CategoriaNoRecibo as $no_recibo)
                                                        <option value="{{ $no_recibo->id }}" @if(request('no_recibo') == $no_recibo->id) selected @endif>{{ $no_recibo->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fecha" class="col-sm-3 col-form-label">Fecha</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="fecha" id="fecha" class="form-control" value="{{ request('fecha') }}">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Filtrar Datos</button>
                                        <button type="submit" formaction="{{ route('exportar-excel') }}" class="btn btn-success">Exportar a Excel</button>
                                    </form>                                    
                                </div>
                                <hr>
                                @if($mostrarAuditoriaEtiquetas->isEmpty())
                                    <div class="alert alert-info">No hay datos para mostrar.</div>
                                @else
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estilo</th>
                                            <th>NO/RECIBIDO</th>
                                            <th>TALLA/CANTIDAD</th>
                                            <th>TAMAÑO MUESTRA</th>
                                            <th>DEFECTOS</th>
                                            <th>TIPO DE DEFECTO</th>
                                            <th>ESTADO</th>
                                            {{-- Agrega más columnas según tus datos --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($mostrarAuditoriaEtiquetas as $auditoria)
                                            <tr>
                                                <td>{{ optional($auditoria->categoriaEstilo)->nombre ?? 'NINGUNO' }}</td>
                                                <td>{{ optional($auditoria->categoriaNoRecibo)->nombre ?? 'NINGUNO' }}</td>
                                                <td>{{ $auditoria->talla_cantidad_id ?: 'NINGUNO' }}</td>
                                                <td>{{ $auditoria->tamaño_muestra_id ?: 'NINGUNO' }}</td>
                                                <td>{{ $auditoria->defecto_id ?: 'NINGUNO' }}</td>
                                                <td>{{ optional($auditoria->categoriaTipoDefecto)->nombre ?? 'NINGUNO' }}</td>
                                                <td>
                                                    @if ($auditoria->estado == 1)
                                                        APROBADO
                                                    @elseif ($auditoria->estado == 0)
                                                        RECHAZADO
                                                    @else
                                                        NINGUNO
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                                {{-- ... tu código existente ... --}}
                                {{-- Elementos canvas para los gráficos --}}
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="graficoPorEstilo"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="graficoPorNoRecibo"></canvas>
                                    </div>
                                    {{-- Puedes agregar más gráficos si lo necesitas --}}
                                </div>
                                <!--Fin de la edicion del codigo para mostrar el contenido-->
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Incluir Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico por estilo
            let datosPorEstilo = @json($datosPorEstilo);
            let etiquetasEstilo = Object.keys(datosPorEstilo);
            let valoresEstilo = Object.values(datosPorEstilo);

            // Datos para el gráfico por tipo de No. Recibo
            let datosPorNoRecibo = @json($datosPorNoRecibo);
            let etiquetasNoRecibo = Object.keys(datosPorNoRecibo);
            let valoresNoRecibo = Object.values(datosPorNoRecibo);

            // Crear gráfico por estilo
            new Chart(document.getElementById('graficoPorEstilo'), {
                type: 'bar', // Puedes cambiar el tipo de gráfico aquí
                data: {
                    labels: etiquetasEstilo,
                    datasets: [{
                        label: 'Auditorías por Estilo',
                        data: valoresEstilo,
                        // Configuraciones adicionales...
                    }]
                },
                options: {
                    // Opciones del gráfico...
                }
            });

            // Crear gráfico por tipo de defecto
            new Chart(document.getElementById('graficoPorNoRecibo'), {
                type: 'pie', // Puedes cambiar el tipo de gráfico aquí
                data: {
                    labels: etiquetasNoRecibo,
                    datasets: [{
                        label: 'Auditorías por Numero de Recibo',
                        data: valoresNoRecibo,
                        // Configuraciones adicionales...
                    }]
                },
                options: {
                    // Opciones del gráfico...
                }
            });

            // Puedes agregar más gráficos aquí...
        });
    </script>


    @endsection
