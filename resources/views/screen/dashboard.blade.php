@extends('layouts.app', ['pageSlug' => 'dashboardScreen', 'titlePage' => __('dashboardScreen')])

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
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
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-volume-98 text-primary"></i> Auditoria de Proceso Plancha por dia</h3>
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
                            <tfoot>
                                <tr style="background: #1d1c1c;">
                                <td>GENERAL</td>
                                <td id="tablaGeneralScreen">Cargando... </td>
                                <td id="tablaGeneralProcesoPlancha">Cargando...</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card ">
                <div class="card-header">
                    <h4 class="card-title">Responsables SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
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
                    <h4 class="card-title">Maquina SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaModulos">
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
                <div id="graficaClientesSemanal" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaSupervisoresSemanal" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModulosSemanal" style="width:100%; height:400px;">
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
                    <h4 class="card-title">Supervisores AQL <i class="tim-icons icon-palette text-success"></i> y PROCESO <i class="tim-icons icon-volume-98 text-primary"></i></h4>
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
                    <h4 class="card-title">Modulos AQL <i class="tim-icons icon-palette text-success"></i> y PROCESO <i class="tim-icons icon-volume-98 text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaModulosSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Módulo</th>
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
                                <label class="btn btn-sm btn-primary btn-simple active" id="cliente0">
                                    <input type="radio" name="clienteOptions" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="cliente1">
                                    <input type="radio" name="clienteOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div style="width:100%; height: 500px;">
                        <div id="clienteChartAQL"></div>
                        <div id="clienteChartProcesos" style="display: none;"></div>
                        <div class="loading-container">
                            <div class="loading-text">Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficas mensual por Módulo -->
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Indicador Mensual por Módulo</h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="modulo0">
                                    <input type="radio" name="moduloOptions" checked>
                                    <span class="d-none d-sm-block">AQL</span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="modulo1">
                                    <input type="radio" name="moduloOptions">
                                    <span class="d-none d-sm-block">Proceso</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;">
                        <div id="moduloChartAQL"></div>
                        <div id="moduloChartProcesos" style="display: none;"></div>
                        <div class="loading-container">
                            <div class="loading-text">Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="tim-icons icon-bell-55 text-primary"></i> Top 3 Defectos mensuales
                    </h3>
                    <div class="col-sm-15">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="top3-AQL">
                                <input type="radio" name="top3Options" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="top3-Proceso">
                                <input type="radio" name="top3Options">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 400px;">
                    <div class="chart-area">
                        <div id="chartAQL"></div>
                        <div id="chartProceso" style="display: none;"></div>
                        <div class="loading-container">
                            <div class="loading-text">Cargando...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>             
        <div class="col-12 col-md-6">
            <div class="card card-chart">
                <div class="card-body" style="height: 500px;">
                    <div id="SegundasTercerasChart"></div>
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                        <div id="spinner" class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>
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
            color: #d1d1d1; /* Color para tema oscuro */
            
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centrar exactamente */
            
            animation: fadeInOut 1.5s infinite;
        }

        /* Animación de parpadeo */
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

    </style>

    <script src="{{ asset('js/highcharts/12/highcharts.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/offline-exporting.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/no-data-to-display.js') }}"></script>
    <script src="{{ asset('js/highcharts/12/modules/accessibility.js') }}"></script>
  
    <script>
    // Usamos un solo listener para todo el código, es más eficiente.
        document.addEventListener('DOMContentLoaded', function () {

            /************************************************************************
             * *
             * SECCIÓN 1: FUNCIONES REUTILIZABLES                  *
             * *
             ************************************************************************/

            /**
             * Función genérica para crear un gráfico de barras/columnas con tu estilo.
             * @param {string} containerId - El ID del div donde se renderizará el gráfico.
             * @param {string} title - El título del gráfico.
             * @param {string[]} categories - Un array con los nombres para el eje X.
             * @param {object[]} seriesData - Un array de objetos, cada uno representando una serie de datos.
             */
            const createBarChart = (containerId, title, categories, seriesData) => {
                Highcharts.chart(containerId, {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: { fontFamily: 'inherit', color: '#ffffff' }
                    },
                    title: {
                        text: title,
                        align: 'center',
                        style: { color: '#ffffff', fontWeight: 'bold' }
                    },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } },
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)'
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            // ----- INICIO DE LA CORRECCIÓN -----
                            // En lugar de 'this.x' (que es el índice), usamos 'this.key' para obtener el nombre de la categoría.
                            let tooltip = `<b>${this.key}</b><br/>`;
                            // ----- FIN DE LA CORRECCIÓN -----

                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: { borderWidth: 0, pointPadding: 0.2 },
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
                        itemStyle: { color: '#ffffff' },
                        itemHoverStyle: { color: '#cccccc' }
                    },
                    credits: { enabled: false },
                    series: seriesData
                });
            };

            /**
             * Función que observa un contenedor y llama a una función de renderizado
             * solo cuando el contenedor se vuelve visible en la pantalla (Lazy Loading).
             * @param {string} containerId - El ID del div del gráfico a observar.
             * @param {function} renderCallback - La función que se ejecutará para dibujar el gráfico.
             */
            const observeChart = (containerId, renderCallback) => {
                const container = document.getElementById(containerId);
                if (!container) {
                    console.error(`Error: No se encontró el contenedor del gráfico con id #${containerId}`);
                    return;
                }

                const observer = new IntersectionObserver((entries, obs) => {
                    if (entries[0].isIntersecting) {
                        renderCallback(); // Llama a la función que crea el gráfico
                        obs.unobserve(container); // Deja de observar, ya no es necesario
                    }
                }, { threshold: 0.1 }); // Se activa cuando el 10% del div es visible

                observer.observe(container);
            };


            /************************************************************************
             * *
             * SECCIÓN 2: LLAMADAS AJAX Y RENDERIZADO DE CONTENIDO         *
             * *
             ************************************************************************/

            // --- 1. Fetch para las tarjetas de totales generales ---
            // (Esta sección no la tenías, la agrego para que todo esté completo y funcional)
            const generalScreenTd = document.getElementById('generalScreen');
            const generalProcesoPlanchaTd = document.getElementById('generalProcesoPlancha');
            if (generalScreenTd && generalProcesoPlanchaTd) {
                fetch("{{ route('screen.dashboard.stats') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        generalScreenTd.textContent = data.porcentajeScreen + ' %';
                        generalProcesoPlanchaTd.textContent = data.porcentajePlancha + ' %';
                    })
                    .catch(err => console.error('Error en stats generales:', err));
            }


            // --- 2. Fetch para Tabla y Gráfico de CLIENTES ---
            fetch("{{ route('screen.dashboard.client-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    // A. Llenar la tabla de clientes
                    const tablaBody = document.querySelector('#tablaClientes tbody');
                    const footerScreen = document.getElementById('tablaGeneralScreen');
                    const footerPlancha = document.getElementById('tablaGeneralProcesoPlancha');
                    tablaBody.innerHTML = ''; 

                    if (data.clientes && data.clientes.length > 0) {
                        data.clientes.forEach(cliente => {
                            tablaBody.innerHTML += `<tr><td>${cliente.cliente}</td><td>${cliente.porcentajeScreen} %</td><td>${cliente.porcentajePlancha} %</td></tr>`;
                        });
                        footerScreen.textContent = data.generales.porcentajeScreen + ' %';
                        footerPlancha.textContent = data.generales.porcentajePlancha + ' %';

                        // B. Preparar la función que renderizará el gráfico (sin ejecutarla aún)
                        const renderClientChart = () => {
                            const categories = data.clientes.map(c => c.cliente);
                            const series = [
                                { name: '% SCREEN', data: data.clientes.map(c => c.porcentajeScreen), color: '#2bffc6' },
                                { name: '% Proceso Plancha', data: data.clientes.map(c => c.porcentajePlancha), color: '#e14eca' }
                            ];
                            createBarChart('graficaClientePorDia', 'Defectos por Cliente', categories, series);
                        };
                        
                        // C. Poner el gráfico en espera hasta que sea visible
                        observeChart('graficaClientePorDia', renderClientChart);

                    } else {
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos de clientes para mostrar.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats de clientes:', err));


            // --- 3. Fetch para Tabla y Gráfico de RESPONSABLES ---
            fetch("{{ route('screen.dashboard.responsible-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    // A. Llenar la tabla de responsables
                    const tablaBody = document.querySelector('#tablaResponsables tbody');
                    tablaBody.innerHTML = '';

                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML += `<tr><td>${item.responsable}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        // B. Preparar la función que renderizará el gráfico
                        const renderResponsibleChart = () => {
                            const categories = data.map(item => item.responsable);
                            const series = [
                                { name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' },
                                { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }
                            ];
                            createBarChart('graficaResponsablePorDia', 'Defectos por Responsable', categories, series);
                        };
                        
                        // C. Poner el gráfico en espera
                        observeChart('graficaResponsablePorDia', renderResponsibleChart);

                    } else {
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos de responsables para mostrar.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats de responsables:', err));


            // --- 4. Fetch para Tabla y Gráfico de MÁQUINAS ---
            fetch("{{ route('screen.dashboard.machine-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    // A. Llenar la tabla de máquinas
                    const tablaBody = document.querySelector('#tablaModulos tbody');
                    tablaBody.innerHTML = '';
                    
                    if (data && data.length > 0) {
                        data.forEach(item => {
                            tablaBody.innerHTML += `<tr><td>${item.maquina}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`;
                        });

                        // B. Preparar la función que renderizará el gráfico
                        const renderMachineChart = () => {
                            const categories = data.map(item => item.maquina);
                            const series = [
                                { name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' },
                                { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }
                            ];
                            createBarChart('graficaMaquinaPorDia', 'Defectos por Máquina', categories, series);
                        };

                        // C. Poner el gráfico en espera
                        observeChart('graficaMaquinaPorDia', renderMachineChart);
                    } else {
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos de máquinas para mostrar.</td></tr>';
                    }
                })
                .catch(err => console.error('Error en stats de máquinas:', err));

        });
    </script>
@endsection

