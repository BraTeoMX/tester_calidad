@extends('layouts.app', ['pageSlug' => 'dashboardScreen', 'titlePage' => __('dashboardScreen')])

@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-palette text-success"></i> Auditoria Screen por día</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalScreen">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-volume-98 text-primary"></i> Auditoria de Proceso
                        Plancha por dia</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalProcesoPlancha">Cargando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaClientePorDia" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaResponsablePorDia" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaMaquinaPorDia" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> <i class="tim-icons icon-shape-star text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Dia actual</p>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaClientes" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Cliente</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Responsables SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso
                        Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaResponsables">
                            <thead class="text-primary">
                                <tr>
                                    <th>Responsable</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Maquina SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso
                        Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaMaquinas">
                            <thead class="text-primary">
                                <tr>
                                    <th>Maquina</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaClientePorSemana" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaResponsablePorSemana" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaMaquinaPorSemana" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title"> <i class="tim-icons icon-shape-star text-primary"></i> Clientes</h4>
                    <p class="card-category d-inline"> Semana actual</p>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaClientesSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Cliente</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Responsables SCREEN <i class="tim-icons icon-palette text-success"></i> y
                        Proceso Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaResponsablesSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Supervisor</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Maquina SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso
                        Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaMaquinasSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Máquina</th>
                                    <th>% SCREEN</th>
                                    <th>% Proceso Plancha</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card card-body">
                <div id="graficaMensualGeneral" style="width:100%; height:500px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafica mensual por cliente -->
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador Mensual por Cliente</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="btnClienteScreen">
                                    <input type="radio" name="clienteOptions" checked>
                                    <span class="d-none d-sm-block">SCREEN</span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="btnClientePlancha">
                                    <input type="radio" name="clienteOptions">
                                    <span class="d-none d-sm-block">PROCESO PLANCHA</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="width:100%; height: 500px; position: relative;">
                        <div id="loadingContainerCliente" class="loading-container" style="display: flex;">
                            <div class="loading-text">Cargando...</div>
                        </div>
                        <div id="clienteChartSCREEN" style="display: none;"></div>
                        <div id="clienteChartProcesoPlancha" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas mensual por Maquina -->
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador Mensual por Maquina</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="btnMaquinaScreen">
                                    <input type="radio" name="maquinaOptions" checked>
                                    <span class="d-none d-sm-block">SCREEN</span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="btnMaquinaProcesoPlancha">
                                    <input type="radio" name="maquinaOptions">
                                    <span class="d-none d-sm-block">PROCESO PLANCHA</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px; position: relative;">
                        <div id="loadingContainerMaquina" class="loading-container" style="display: flex;">
                            <div class="loading-text">Cargando...</div>
                        </div>
                        <div id="maquinaChartSCREEN" style="display: none;"></div>
                        <div id="maquinaChartProcesoPlancha" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-chart-bar-32 text-primary"></i> Top 3 - SCREEN</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 400px; position: relative;">
                        <div id="chartTopDefectosScreen" style="height: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-chart-bar-32 text-primary"></i> Top 3 - PROCESO
                        PLANCHA</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 400px; position: relative;">
                        <div id="chartTopDefectosPlancha" style="height: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingContainerTopDefects" class="loading-container"
        style="display: flex; width: 100%; justify-content: center;">
        <div class="loading-text">Cargando Datos de Defectos...</div>
    </div>

    <style>
        /* Contenedor para centrar el texto */
        .loading-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Texto animado */
        .loading-text {
            font-size: 18px;
            font-weight: bold;
            color: #d1d1d1;
            /* Color para tema oscuro */

            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Centrar exactamente */

            animation: fadeInOut 1.5s infinite;
        }

        /* Animación de parpadeo */
        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 1;
            }
        }
    </style>

    <script src="{{ asset('js/highcharts/12/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/offline-exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/no-data-to-display.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/accessibility.js') }}"></script>

    <script>
        // Usamos un solo listener para todo el código. Esto asegura que todo el HTML está listo.
        document.addEventListener('DOMContentLoaded', function() {

            /************************************************************************
             *
             * SECCIÓN 1: FUNCIONES REUTILIZABLES (Declaradas una sola vez)
             *
             ************************************************************************/

            /**
             * Función genérica para crear un gráfico de barras/columnas con tu estilo.
             */
            const createBarChart = (containerId, title, categories, seriesData) => {
                // Al ejecutar Highcharts.chart, el contenido del div (incluido tu "Cargando...")
                // se limpia automáticamente antes de dibujar el gráfico.
                Highcharts.chart(containerId, {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'inherit',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: title,
                        align: 'center',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)'
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        style: {
                            color: '#ffffff'
                        },
                        formatter: function() {
                            let tooltip = `<b>${this.key}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip +=
                                    `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            borderWidth: 0,
                            pointPadding: 0.2
                        },
                        series: {
                            dataLabels: {
                                enabled: true,
                                rotation: -90,
                                color: '#FFFFFF',
                                align: 'right',
                                format: '{point.y:.2f}%',
                                y: 10,
                                style: {
                                    fontSize: '10px',
                                    fontWeight: 'normal',
                                    textOutline: 'none'
                                }
                            }
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#ffffff'
                        },
                        itemHoverStyle: {
                            color: '#cccccc'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: seriesData
                });
            };

            // --- NUEVA FUNCIÓN DE AYUDA PARA GRÁFICOS DE ÁREA ---
            const createAreaSplineChart = (containerId, title, categories, seriesData) => {
                const nombreMes = new Date().toLocaleString('es-ES', {
                    month: 'long'
                });
                Highcharts.chart(containerId, {
                    chart: {
                        type: 'areaspline',
                        height: 500,
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'inherit',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: title,
                        align: 'center',
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                        title: {
                            text: `Días del Mes - ${nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1)}`,
                            style: {
                                color: '#ffffff'
                            }
                        },
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Porcentaje (%)',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        min: 0,
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)'
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        style: {
                            color: '#ffffff'
                        },
                        formatter: function() {
                            let tooltip = `<b>Día ${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip +=
                                    `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 0.5,
                            lineWidth: 2,
                            marker: {
                                enabled: false
                            }
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#ffffff'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: seriesData
                });
            };

            /**
             * Función que observa un contenedor y llama a una función de renderizado
             * solo cuando el contenedor se vuelve visible en la pantalla (Lazy Loading).
             */
            const observeChart = (containerId, renderCallback) => {
                const container = document.getElementById(containerId);
                if (!container) {
                    console.error(`Error: No se encontró el contenedor del gráfico con id #${containerId}`);
                    return;
                }
                const observer = new IntersectionObserver((entries, obs) => {
                    if (entries[0].isIntersecting) {
                        renderCallback();
                        obs.unobserve(container);
                    }
                }, {
                    threshold: 0.1
                });
                observer.observe(container);
            };


            /************************************************************************
             *
             * SECCIÓN 2: LLAMADAS AJAX Y RENDERIZADO DE CONTENIDO
             *
             ************************************************************************/

            // --- DATOS DIARIOS ---

            // Fetch DIARIO para Totales Generales (en el pie de la tabla de clientes)
            fetch("{{ route('screen.dashboard.stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    document.getElementById('generalScreen').textContent = data.porcentajeScreen + ' %';
                    document.getElementById('generalProcesoPlancha').textContent = data.porcentajePlancha +
                    ' %';
                })
                .catch(err => console.error('Error en stats generales diarios:', err));

            // Fetch DIARIO para Clientes
            fetch("{{ route('screen.dashboard.client-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaClientes tbody');
                    tablaBody.innerHTML = '';
                    if (data.clientes && data.clientes.length > 0) {
                        data.clientes.forEach(cliente => {
                            tablaBody.innerHTML +=
                                `<tr><td>${cliente.cliente}</td><td>${cliente.porcentajeScreen} %</td><td>${cliente.porcentajePlancha} %</td></tr>`;
                        });

                        const renderClientChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.clientes.map(c => c.cliente);
                            const series = [{
                                name: '% SCREEN',
                                data: data.clientes.map(c => c.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.clientes.map(c => c.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaClientePorDia', 'Comparativo Cliente (Día)', categories,
                                series);
                        };
                        observeChart('graficaClientePorDia', renderClientChartDaily);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de clientes.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats diarios de clientes:', err));

            // Fetch DIARIO para Responsables
            fetch("{{ route('screen.dashboard.responsible-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaResponsables tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML +=
                                `<tr><td>${item.responsable}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        const renderResponsibleChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.responsable);
                            const series = [{
                                name: '% SCREEN',
                                data: data.map(item => item.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.map(item => item.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaResponsablePorDia', 'Comparativo Responsable (Día)',
                                categories, series);
                        };
                        observeChart('graficaResponsablePorDia', renderResponsibleChartDaily);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de responsables.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats diarios de responsables:', err));

            // Fetch DIARIO para Máquinas
            fetch("{{ route('screen.dashboard.machine-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaMaquinas tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML +=
                                `<tr><td>${item.maquina}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        const renderMachineChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.maquina);
                            const series = [{
                                name: '% SCREEN',
                                data: data.map(item => item.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.map(item => item.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaMaquinaPorDia', 'Comparativo Máquina (Día)', categories,
                                series);
                        };
                        observeChart('graficaMaquinaPorDia', renderMachineChartDaily);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de máquinas.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats diarios de máquinas:', err));


            // --- DATOS SEMANALES ---

            // Fetch SEMANAL para Clientes
            fetch("{{ route('screen.dashboard.client-stats-weekly') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaClientesSemanal tbody');
                    tablaBody.innerHTML = '';
                    if (data.clientes && data.clientes.length > 0) {
                        data.clientes.forEach(cliente => {
                            tablaBody.innerHTML +=
                                `<tr><td>${cliente.cliente}</td><td>${cliente.porcentajeScreen} %</td><td>${cliente.porcentajePlancha} %</td></tr>`;
                        });

                        const renderClientChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.clientes.map(c => c.cliente);
                            const series = [{
                                name: '% SCREEN',
                                data: data.clientes.map(c => c.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.clientes.map(c => c.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaClientePorSemana', 'Comparativo Cliente (Semana)',
                                categories, series);
                        };
                        observeChart('graficaClientePorSemana', renderClientChartWeekly);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de clientes.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats semanales de clientes:', err));

            // Fetch SEMANAL para Responsables
            fetch("{{ route('screen.dashboard.responsible-stats-weekly') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaResponsablesSemanal tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML +=
                                `<tr><td>${item.responsable}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        const renderResponsibleChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.responsable);
                            const series = [{
                                name: '% SCREEN',
                                data: data.map(item => item.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.map(item => item.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaResponsablePorSemana',
                                'Comparativo Responsable (Semana)', categories, series);
                        };
                        observeChart('graficaResponsablePorSemana', renderResponsibleChartWeekly);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de responsables.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats semanales de responsables:', err));

            // Fetch SEMANAL para Máquinas
            fetch("{{ route('screen.dashboard.machine-stats-weekly') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaMaquinasSemanal tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML +=
                                `<tr><td>${item.maquina}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        const renderMachineChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.maquina);
                            const series = [{
                                name: '% SCREEN',
                                data: data.map(item => item.porcentajeScreen),
                                color: '#2bffc6'
                            }, {
                                name: '% Proceso Plancha',
                                data: data.map(item => item.porcentajePlancha),
                                color: '#e14eca'
                            }];
                            createBarChart('graficaMaquinaPorSemana', 'Comparativo Máquina (Semana)',
                                categories, series);
                        };
                        observeChart('graficaMaquinaPorSemana', renderMachineChartWeekly);
                    } else {
                        tablaBody.innerHTML =
                            '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de máquinas.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats semanales de máquinas:', err));

            // --- DATOS MENSUALES ---
            // --- NUEVO FETCH PARA GRÁFICO DE TENDENCIA MENSUAL ---
            fetch("{{ route('screen.dashboard.stats-month') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    if (data && data.length > 0) {
                        const renderMonthlyChart = () => {
                            const dias = data.map(item => item.dia);
                            const series = [{
                                    name: '% SCREEN',
                                    data: data.map(item => item.porcentajeScreen),
                                    color: '#2bffc6',
                                    zIndex: 1
                                },
                                {
                                    name: '% Proceso Plancha',
                                    data: data.map(item => item.porcentajePlancha),
                                    color: '#e14eca',
                                    zIndex: 0
                                }
                            ];
                            createAreaSplineChart('graficaMensualGeneral',
                                'Tendencia Mensual: SCREEN vs. Proceso Plancha', dias, series);
                        };
                        observeChart('graficaMensualGeneral', renderMonthlyChart);
                    } else {
                        // Opcional: Mostrar un mensaje si no hay datos en el mes
                        document.getElementById('graficaMensualGeneral').innerHTML =
                            '<div class="loading-container"><div class="loading-text">No hay datos para el mes actual.</div></div>';
                    }
                })
                .catch(err => {
                    console.error('Error en stats mensuales:', err);
                    document.getElementById('graficaMensualGeneral').innerHTML =
                        '<div class="loading-container"><div class="loading-text">Error al cargar el gráfico.</div></div>';
                });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- FUNCIÓN DE AYUDA PARA CREAR GRÁFICOS DE LÍNEAS MÚLTIPLES ---
            function createMultiLineChart(containerId, title, categories, seriesData) {
                return Highcharts.chart(containerId, {
                    chart: {
                        type: 'spline',
                        height: 500,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: title,
                        style: {
                            color: '#ffffff',
                            fontWeight: 'bold'
                        }
                    },
                    xAxis: {
                        categories: categories,
                        title: {
                            text: 'Días del Mes',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        lineColor: '#ffffff',
                        tickColor: '#ffffff'
                    },
                    yAxis: {
                        title: {
                            text: 'Porcentaje (%)',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)',
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    legend: {
                        itemStyle: {
                            color: '#ffffff'
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: seriesData
                });
            }

            function observeChart(containerId, fetchFunction) {
                const container = document.getElementById(containerId);
                if (!container) {
                    console.error(`Error: No se encontró el contenedor del gráfico con id #${containerId}`);
                    return;
                }

                const observer = new IntersectionObserver((entries, obs) => {
                    if (entries[0].isIntersecting) {
                        fetchFunction(); // Llama a la función que busca los datos
                        obs.unobserve(container);
                    }
                }, {
                    threshold: 0.1
                });

                observer.observe(container);
            }
            // --- LÓGICA PARA GRÁFICO MENSUAL POR CLIENTE ---
            function fetchDataAndRenderClientCharts() {
                const loadingContainer = document.getElementById('loadingContainerCliente');
                const containerScreen = document.getElementById('clienteChartSCREEN');
                const containerPlancha = document.getElementById('clienteChartProcesoPlancha');
                const btnScreen = document.getElementById('btnClienteScreen');
                const btnPlancha = document.getElementById('btnClientePlancha');

                fetch("{{ route('screen.dashboard.client-stats-month') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        loadingContainer.style.display = 'none';

                        if (Object.keys(data).length === 0) {
                            containerScreen.innerHTML =
                                '<div class="loading-text">No hay datos de clientes para mostrar.</div>';
                            containerScreen.style.display = 'block';
                            return;
                        }

                        const clientes = Object.keys(data);
                        const diasDelMes = data[clientes[0]].map(d => d.dia);

                        const seriesScreen = clientes.map(cliente => ({
                            name: cliente,
                            data: data[cliente].map(d => d.porcentajeScreen)
                        }));
                        const seriesPlancha = clientes.map(cliente => ({
                            name: cliente,
                            data: data[cliente].map(d => d.porcentajePlancha)
                        }));

                        const chartScreen = createMultiLineChart('clienteChartSCREEN',
                            'Indicador Mensual por Cliente - SCREEN', diasDelMes, seriesScreen);
                        const chartPlancha = createMultiLineChart('clienteChartProcesoPlancha',
                            'Indicador Mensual por Cliente - PROCESO PLANCHA', diasDelMes, seriesPlancha);

                        containerScreen.style.display = 'block';

                        btnScreen.addEventListener('click', () => {
                            containerPlancha.style.display = 'none';
                            containerScreen.style.display = 'block';
                            chartScreen.reflow();
                        });

                        btnPlancha.addEventListener('click', () => {
                            containerScreen.style.display = 'none';
                            containerPlancha.style.display = 'block';
                            chartPlancha.reflow();
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los datos por cliente:', error);
                        loadingContainer.innerHTML =
                            '<div class="loading-text">Error al cargar los datos.</div>';
                    });
            }

            // --- LÓGICA PARA GRÁFICO MENSUAL POR MÁQUINA ---
            function fetchDataAndRenderMachineCharts() {
                const loadingContainerMaquina = document.getElementById('loadingContainerMaquina');
                const containerMaquinaScreen = document.getElementById('maquinaChartSCREEN');
                const containerMaquinaPlancha = document.getElementById('maquinaChartProcesoPlancha');
                const btnMaquinaScreen = document.getElementById('btnMaquinaScreen');
                const btnMaquinaPlancha = document.getElementById('btnMaquinaProcesoPlancha');

                fetch("{{ route('screen.dashboard.machine-stats-month') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        loadingContainerMaquina.style.display = 'none';

                        if (Object.keys(data).length === 0) {
                            containerMaquinaScreen.innerHTML =
                                '<div class="loading-text">No hay datos de máquinas para mostrar.</div>';
                            containerMaquinaScreen.style.display = 'block';
                            return;
                        }

                        const maquinas = Object.keys(data);
                        const diasDelMes = data[maquinas[0]].map(d => d.dia);

                        const seriesScreen = maquinas.map(maquina => ({
                            name: maquina,
                            data: data[maquina].map(d => d.porcentajeScreen)
                        }));
                        const seriesPlancha = maquinas.map(maquina => ({
                            name: maquina,
                            data: data[maquina].map(d => d.porcentajePlancha)
                        }));

                        const chartScreen = createMultiLineChart('maquinaChartSCREEN',
                            'Indicador Mensual por Máquina - SCREEN', diasDelMes, seriesScreen);
                        const chartPlancha = createMultiLineChart('maquinaChartProcesoPlancha',
                            'Indicador Mensual por Máquina - PROCESO PLANCHA', diasDelMes, seriesPlancha);

                        containerMaquinaScreen.style.display = 'block';

                        btnMaquinaScreen.addEventListener('click', () => {
                            containerMaquinaPlancha.style.display = 'none';
                            containerMaquinaScreen.style.display = 'block';
                            chartScreen.reflow();
                        });

                        btnMaquinaPlancha.addEventListener('click', () => {
                            containerMaquinaScreen.style.display = 'none';
                            containerMaquinaPlancha.style.display = 'block';
                            chartPlancha.reflow();
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los datos por máquina:', error);
                        loadingContainerMaquina.innerHTML =
                            '<div class="loading-text">Error al cargar los datos.</div>';
                    });
            }

            function createTopDefectsChart(containerId, title, data) {
                // 1. Obtenemos los nombres para las categorías del eje X.
                // Resultado: ["mancha", "mancha 3", "mancha 2"]
                const categories = data.map(d => d.defecto);

                // 2. Obtenemos los valores para la ÚNICA serie de datos.
                // Resultado: [220, 70, 60]
                const totals = data.map(d => parseFloat(d.total));

                // Paleta de colores que se aplicará a cada barra.
                const colores = ['#f44336', '#ff9800', '#ffc107', '#4caf50', '#00bcd4'];

                return Highcharts.chart(containerId, {
                    chart: {
                        type: 'column',
                        height: 400,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: title,
                        style: {
                            color: '#ffffff'
                        }
                    },

                    // EJE X MEJORADO: Ahora muestra el nombre de cada defecto.
                    xAxis: {
                        categories: categories,
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        lineColor: '#ffffff'
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Cantidad de Defectos',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                color: '#ffffff'
                            }
                        },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)'
                    },
                    // La leyenda ya no es necesaria, porque los nombres están en el eje X.
                    legend: {
                        enabled: false
                    },

                    // TOOLTIP MEJORADO: Ahora puede acceder a TODOS los datos.
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        style: {
                            color: '#ffffff'
                        },
                        formatter: function() {
                            // 'this.point.index' nos permite encontrar el objeto de datos original.
                            const pointData = data[this.point.index];
                            return `<b>Defecto: ${pointData.defecto}</b><br/>` +
                                `Suma total: <b>${pointData.total}</b><br/>`;
                        }
                    },
                    plotOptions: {
                        series: {
                            // Le decimos a Highcharts que coloree cada barra de forma distinta.
                            colorByPoint: true,
                            colors: colores,
                            borderWidth: 0,
                            dataLabels: {
                                enabled: true,
                                color: '#FFFFFF',
                                style: {
                                    textOutline: 'none'
                                },
                                // La etiqueta sobre la barra muestra el valor total (el eje Y).
                                format: '{point.y}'
                            }
                        }
                    },
                    credits: {
                        enabled: false
                    },

                    // ESTRUCTURA DE SERIES MEJORADA: Solo una serie con múltiples puntos.
                    series: [{
                        name: 'Total de Defectos', // Nombre general de la serie
                        data: totals // Los datos con las alturas de las barras
                    }]
                });
            }

            function fetchDataAndRenderDefectsCharts() {
                // Obtenemos los contenedores necesarios
                const loadingContainer = document.getElementById('loadingContainerTopDefects');
                const containerScreen = document.getElementById('chartTopDefectosScreen');
                const containerPlancha = document.getElementById('chartTopDefectosPlancha');

                // Ocultamos los contenedores de los gráficos mientras se cargan los datos
                containerScreen.style.display = 'none';
                containerPlancha.style.display = 'none';

                fetch("{{ route('screen.dashboard.defecto-stats-month') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        // Ocultamos el mensaje "Cargando..."
                        if (loadingContainer) loadingContainer.style.display = 'none';

                        // Hacemos visibles los contenedores de los gráficos
                        containerScreen.style.display = 'block';
                        containerPlancha.style.display = 'block';

                        // Renderizamos ambos gráficos, cada uno en su contenedor
                        const chartScreen = createTopDefectsChart('chartTopDefectosScreen',
                            'Top 3 Defectos Mensuales - SCREEN', data.topDefectosScreen);
                        const chartPlancha = createTopDefectsChart('chartTopDefectosPlancha',
                            'Top 3 Defectos Mensuales - PROCESO PLANCHA', data.topDefectosPlancha);

                        // Forzamos un 'reflow' por si los contenedores estaban ocultos o cambiaron de tamaño.
                        // Esto asegura que los gráficos se dibujen correctamente.
                        chartScreen.reflow();
                        chartPlancha.reflow();
                    })
                    .catch(error => {
                        console.error('Error al cargar top defectos:', error);
                        if (loadingContainer) loadingContainer.innerHTML =
                            '<div class="loading-text">Error al cargar datos.</div>';
                    });
            }


            /************************************************************************
             * SECCIÓN 3: INICIALIZACIÓN DE LOS OBSERVADORES
             ************************************************************************/

            // Se le dice al navegador que observe ambos contenedores de carga.
            // Cuando uno sea visible, se ejecutará su función de carga de datos correspondiente.
            observeChart('loadingContainerCliente', fetchDataAndRenderClientCharts);
            observeChart('loadingContainerMaquina', fetchDataAndRenderMachineCharts);
            observeChart('loadingContainerTopDefects', fetchDataAndRenderDefectsCharts);
        });
    </script>
@endsection
