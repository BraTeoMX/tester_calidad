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
                        <div id="graficoMes" style="width:100%; height:500px;"></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Defectos Únicos por Cliente</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($costoPorSemanaClientes as $cliente => $data)
                        <div class="col-lg-6 col-md-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: {{ $cliente }}</h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th>Defecto Único</th>
                                                <th>Conteo</th>
                                                <th>Porcentaje (%)</th>
                                                <th>Porcentaje Acumulado (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['defectos'] as $index => $defecto)
                                                <tr class="{{ count($data['defectos']) > 7 && $index < 4 ? 'amarillo-indicador' : '' }}">
                                                    <td>{{ $defecto['defecto_unico'] }}</td>
                                                    <td>{{ $defecto['conteo'] }}</td>
                                                    <td>{{ number_format($defecto['porcentaje'], 2) }}%</td>
                                                    <td>{{ number_format($defecto['porcentaje_acumulado'], 2) }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th>{{ $data['total_conteo'] }}</th>
                                                <th>100%</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card">
                                <!-- Contenedor para la gráfica de cada cliente -->
                                <div id="graficoCliente_{{ $loop->index }}" style="width:100%; height:500px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modulos unicos por cliente</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Mostrar el gran total fuera del foreach -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5>Gran Total Minutos Paro Proceso: {{ $granTotalMinutosParo }}</h5>
                    </div>
                </div>
            
                <div class="row">
                    @foreach($modulosPorCliente as $cliente => $data)
                        <div class="col-lg-6 col-md-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: {{ $cliente }}</h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th>Módulo Único</th>
                                                <th>Minutos Paro Proceso</th>
                                                <th>Porcentaje (%)</th>
                                                <th>Estilos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['modulos'] as $modulo)
                                                <tr>
                                                    <td>{{ $modulo['modulo'] }}</td>
                                                    <td>{{ $modulo['minutos_paro_proceso'] }}</td>
                                                    <td>{{ $modulo['porcentaje'] }}%</td>
                                                    <td>{{ $modulo['estilos'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total Módulos</th>
                                                <th>{{ $data['total_modulos'] }}</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Total Minutos Paro Proceso</th>
                                                <th>{{ $data['total_minutos_paro'] }}</th>
                                                <th>100%</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Porcentaje respecto Gran Total</th>
                                                <th>{{ $data['porcentaje_entre_gran_total_cliente'] }}%</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <style>
        .costo-rojo {
            color: red;
        }
        .amarillo-indicador {
            background-color: #887404; /* Color amarillo oscuro */
        }
    </style>
@endsection

@push('js') 
    <script src="{{ asset('js/highcharts/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/highcharts-3d.js') }}"></script>
    <script src="{{ asset('js/highcharts/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/dark-unica.js') }}"></script>
    <script>
        // Configuración global de Highcharts para la fuente
        Highcharts.setOptions({
            chart: {
                style: {
                    fontFamily: 'Inter, sans-serif'  // Solo se aplica a los gráficos
                }
            }
        });
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
            yAxis: [{ // Primer eje Y para "Minutos Paro Proceso"
                title: {
                    text: 'Minutos Paro Proceso (MPP)',
                    style: {
                        color: '#4aa5d6' // Mismo color que la línea de MPP
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#4aa5d6'
                    }
                }
            }, { // Segundo eje Y para "Costo (USD)"
                title: {
                    text: 'Costo (USD)',
                    style: {
                        color: '#8B0000' // Mismo color que la línea de Costo
                    }
                },
                labels: {
                    format: '${value}',
                    style: {
                        color: '#8B0000'
                    }
                },
                opposite: true // Coloca el eje en el lado derecho
            }],
            series: [{
                name: 'Minutos Paro Proceso (MPP)',
                data: minutosParoSemana,
                color: '#4aa5d6',  // Azul
                lineWidth: 3,       // Grosor de línea aumentado
                yAxis: 0           // Asociado al primer eje Y
            }, {
                name: 'Costo (USD)',
                data: costoSemana,
                color: '#8B0000',  // Rojo oscuro
                lineWidth: 6,       // Grosor de línea aumentado
                yAxis: 1           // Asociado al segundo eje Y
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
            yAxis: [{ // Primer eje Y para "Minutos Paro Proceso"
                title: {
                    text: 'Minutos Paro Proceso (MPP)',
                    style: {
                        color: '#4aa5d6' // Mismo color que la línea de MPP
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: '#4aa5d6'
                    }
                }
            }, { // Segundo eje Y para "Costo (USD)"
                title: {
                    text: 'Costo (USD)',
                    style: {
                        color: '#8B0000' // Mismo color que la línea de Costo
                    }
                },
                labels: {
                    format: '${value}',
                    style: {
                        color: '#8B0000'
                    }
                },
                opposite: true // Coloca el eje en el lado derecho
            }],
            series: [{
                name: 'Minutos Paro Proceso (MPP)',
                data: minutosParoMes,
                color: '#4aa5d6',  // Azul
                lineWidth: 3,       // Grosor de línea aumentado
                yAxis: 0           // Asociado al primer eje Y
            }, {
                name: 'Costo (USD)',
                data: costoMes,
                color: '#8B0000',  // Rojo oscuro
                lineWidth: 6,       // Grosor de línea aumentado
                yAxis: 1           // Asociado al segundo eje Y
            }]
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @foreach($costoPorSemanaClientes as $index => $data)
                Highcharts.chart('graficoCliente_{{ $loop->index }}', {
                    chart: {
                        type: 'line',
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: 'Defectos y Porcentaje Pareto - Cliente: {{ json_encode($index) }}'
                    },
                    xAxis: {
                        categories: {!! json_encode($data['defectos']->pluck('defecto_unico')->toArray()) !!},
                        title: {
                            text: 'Defecto Único'
                        }
                    },
                    yAxis: [{
                        title: {
                            text: 'Cantidad'
                        }
                    }, {
                        title: {
                            text: 'Porcentaje Acumulado (%)'
                        },
                        opposite: true // Alinea el porcentaje acumulado al lado derecho
                    }],
                    series: [{
                        type: 'column',  // Muestra la serie de "Conteo" como barras
                        name: 'Conteo',
                        data: {!! json_encode($data['defectos']->pluck('conteo')->toArray()) !!},
                        color: '#4aa5d6' // Azul
                    }, {
                        type: 'line',  // Mantiene "Porcentaje Acumulado" como línea
                        name: 'Porcentaje Acumulado (%)',
                        data: {!! json_encode($data['defectos']->pluck('porcentaje_acumulado')->toArray()) !!},
                        color: '#8B0000', // Rojo oscuro
                        yAxis: 1 // Ubica el porcentaje acumulado en el segundo eje Y
                    }]
                });
            @endforeach
        });
    </script>    
@endpush