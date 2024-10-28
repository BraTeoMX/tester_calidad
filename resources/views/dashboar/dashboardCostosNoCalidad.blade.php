@extends('layouts.app', ['pageSlug' => 'dashboardCostosNoCalidad', 'titlePage' => __('Dashboard Costos No Calidad')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title">Dashboard: COSTO DE LA NO CALIDAD</h3>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form action="{{ route('dashboardCostosNoCalidad') }}" method="GET" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Semana inicio</label>
                                            <input type="week" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaInicio->format('Y-\WW') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_fin">Semana fin</label>
                                            <input type="week" class="form-control" id="fecha_fin" name="fecha_fin" value="{{ $fechaFin->format('Y-\WW') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card table-responsive">
                        <table class="table tablesorter">
                            <thead>
                                <tr>
                                    <th># Semana</th>
                                    <th>Paros Proceso</th>
                                    <th>Min Paro Proceso</th>
                                    <th>Costo (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costoPorSemana as $dato)
                                    <tr>
                                        <td>SEMANA {{ $dato->semana }}</td>
                                        <td>{{ $dato->paros_proceso }}</td>
                                        <td>{{ $dato->min_paro_proc }}</td>
                                        <td>${{ number_format($dato->costo_usd, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron datos para el rango seleccionado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $totalParoSemana }}</th>
                                    <th>{{ $totalMinParoSemana }}</th>
                                    <th class="costo-rojo">${{ number_format($totalCostoSemana, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <!-- Gráfica para $costoPorSemana -->
                        <div id="graficoSemana" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card table-responsive">
                        <table class="table tablesorter">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Paros Proceso</th>
                                    <th>Min Paro Proceso</th>
                                    <th>Costo (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($costoPorMes as $dato)
                                    <tr>
                                        <td>{{ $dato->mes_nombre }}</td>
                                        <td>{{ $dato->paros_proceso }}</td>
                                        <td>{{ $dato->min_paro_proc }}</td>
                                        <td class="costo-rojo">${{ number_format($dato->costo_usd, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron datos para el rango mensual.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th>{{ $totalParoMes }}</th>
                                    <th>{{ $totalMinParoMes }}</th>
                                    <th class="costo-rojo">${{ number_format($totalCostoMes, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card">
                        <!-- Gráfica para $costoPorMes -->
                        <div id="graficoMes" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .costo-rojo {
            color: red;
        }
    </style>
@endsection

@push('js') 
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>
    <script>
        // Convertir los datos PHP a JSON para JavaScript
        const datosSemana = @json($costoPorSemana);
        const datosMes = @json($costoPorMes);

        // Procesar los datos para el gráfico de líneas de semanas
        const semanas = datosSemana.map(d => `SEMANA ${d.semana}`);
        const minutosParoSemana = datosSemana.map(d => d.min_paro_proc);
        const costoSemana = datosSemana.map(d => d.costo_usd);

        Highcharts.chart('graficoSemana', {
            chart: {
                type: 'line',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Costo y Minutos de Paro por Semana'
            },
            xAxis: {
                categories: semanas,
                title: {
                    text: 'Semana'
                }
            },
            yAxis: {
                title: {
                    text: 'Valor'
                }
            },
            series: [{
                name: 'Minutos Paro Proceso (MPP)',
                data: minutosParoSemana,
                color: '#228B22',  // Verde oscuro
                lineWidth: 3       // Grosor de línea aumentado
            }, {
                name: 'Costo (USD)',
                data: costoSemana,
                color: '#8B0000',  // Rojo oscuro
                lineWidth: 6       // Grosor de línea aumentado
            }]
        });

        // Procesar los datos para el gráfico de líneas de meses
        const meses = datosMes.map(d => d.mes_nombre);
        const minutosParoMes = datosMes.map(d => d.min_paro_proc);
        const costoMes = datosMes.map(d => d.costo_usd);

        Highcharts.chart('graficoMes', {
            chart: {
                type: 'line',
                backgroundColor: 'transparent'
            },
            title: {
                text: 'Costo y Minutos de Paro por Mes'
            },
            xAxis: {
                categories: meses,
                title: {
                    text: 'Mes'
                }
            },
            yAxis: {
                title: {
                    text: 'Valor'
                }
            },
            series: [{
                name: 'Minutos Paro Proceso (MPP)',
                data: minutosParoMes,
                color: '#228B22',  // Verde oscuro
                lineWidth: 3       // Grosor de línea aumentado
            }, {
                name: 'Costo (USD)',
                data: costoMes,
                color: '#8B0000',  // Rojo oscuro
                lineWidth: 6       // Grosor de línea aumentado
            }]
        });
    </script>
@endpush