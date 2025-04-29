@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')])

@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Auditoria AQL por día</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalAQL">Cargando...</td>
                                </tr>
                                <tr>
                                    <td>Planta I :</a></td>
                                    <td id="generalAQLPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td>Planta II :</a></td>
                                    <td id="generalAQLPlanta2">Cargando...</td>
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
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Proceso por dia</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td id="generalProceso">Cargando...</td>
                                </tr>
                                <tr>
                                    <td>Planta I :</a></td>
                                    <td id="generalProcesoPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td>Planta II :</a></td>
                                    <td id="generalProcesoPlanta2">Cargando...</td>
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
                <div id="graficaSupervisorPorDia" style="width:100%; height:400px;">
                    <div class="loading-container">
                        <div class="loading-text">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModuloPorDia" style="width:100%; height:400px;">
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
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr style="background: #1d1c1c;">
                                <td>GENERAL</td>
                                <td id="tablaGeneralAQL">Cargando... </td>
                                <td id="tablaGeneralProceso">Cargando...</td>
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
                    <h4 class="card-title">Responsables AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaResponsables">
                            <thead class="text-primary">
                                <tr>
                                    <th>Supervisor</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
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
                    <h4 class="card-title">Modulos AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Dia actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaModulos">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
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
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
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
                    <h4 class="card-title">Supervisores AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaResponsablesSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Supervisor</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
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
                    <h4 class="card-title">Modulos AQL <i class="tim-icons icon-app text-success"></i> y PROCESO <i class="tim-icons icon-vector text-primary"></i></h4>
                    <p class="card-category d-inline"> Semana actual</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tablaModulosSemanal" class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Módulo</th>
                                    <th>% AQL</th>
                                    <th>% Proceso</th>
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
        $(document).ready(function () {
            // Variable para almacenar la solicitud AJAX, de modo que se pueda abortar si es necesario
            let dataRequest = null;

            // Función para obtener los datos vía AJAX
            function fetchDataDia() {
                // Si hay una solicitud pendiente, se cancela
                if (dataRequest) {
                    dataRequest.abort();
                }
                dataRequest = $.ajax({
                    url: "{{ route('dashboard.dataDiaV2') }}", // Ajusta la ruta a tu controlador
                    type: "GET",
                    success: function (data) {
                        // Renderizar tablas (se mantiene sin cambios)
                        renderTablaClientes(data.clientes);
                        renderTablaSupervisores(data.supervisores);
                        renderTablaModulos(data.modulos);

                        // En lugar de llamar directamente a renderGraficaXXX, usamos el Intersection Observer para carga diferida
                        observeChart('graficaClientePorDia', renderGraficaClientes, data.clientes);
                        observeChart('graficaSupervisorPorDia', renderGraficaSupervisores, data.supervisores);
                        observeChart('graficaModuloPorDia', renderGraficaModulos, data.modulos);
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos del día.');
                        }
                    }
                });
            }

            // Función que usa Intersection Observer para cargar la gráfica cuando su contenedor es visible
            function observeChart(containerId, renderFunction, datos) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // Se llama a la función de renderizado para la gráfica
                            renderFunction(datos);
                            // Se deja de observar una vez que se ha renderizado
                            obs.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1 // Se considera visible cuando al menos 10% del contenedor está en vista
                });

                observer.observe(contenedor);
            }

            // Llamamos a la función para obtener datos vía AJAX
            fetchDataDia();

            /**
             * 1) GRÁFICA DE CLIENTES
             */
             function renderGraficaClientes(clientes) {
                const categorias = Object.keys(clientes);
                const dataAQL = categorias.map(c => clientes[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => clientes[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaClientePorDia', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent', // El fondo es el del contenedor (azul oscuro)
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - CLIENTES (DÍA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: {
                            style: { color: '#ffffff' }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: {
                            style: { color: '#ffffff' }
                        },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: {
                        itemStyle: { color: '#ffffff' }
                    },
                    credits: {
                        style: { color: '#ffffff' }
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            /**
             * 2) GRÁFICA DE SUPERVISORES
             */
            function renderGraficaSupervisores(supervisores) {
                const categorias = Object.keys(supervisores);
                const dataAQL = categorias.map(c => supervisores[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => supervisores[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaSupervisorPorDia', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - SUPERVISORES (DÍA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px' 
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: {
                            style: { color: '#ffffff' }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: {
                            style: { color: '#ffffff' }
                        },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: {
                        itemStyle: { color: '#ffffff' }
                    },
                    credits: {
                        style: { color: '#ffffff' }
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> 
                                    ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            /**
             * 3) GRÁFICA DE MÓDULOS
             */
            function renderGraficaModulos(modulos) {
                const categorias = Object.keys(modulos);
                const dataAQL = categorias.map(c => modulos[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => modulos[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaModuloPorDia', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - MÓDULOS (DÍA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px' 
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: {
                            style: { color: '#ffffff' }
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: {
                            style: { color: '#ffffff' }
                        },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: {
                        itemStyle: { color: '#ffffff' }
                    },
                    credits: {
                        style: { color: '#ffffff' }
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> 
                                    ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Funciones para llenar las tablas (Ya las tienes implementadas)
            function renderTablaClientes(clientes) {
                const tableId = '#tablaClientes';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy(); // Destruir la instancia previa
                }

                let html = '';
                $.each(clientes, function (cliente, valores) {
                    html += `
                        <tr>
                            <td>${cliente}</td>
                            <td>${valores['% AQL'] ? valores['% AQL'].toFixed(2) + '%' : '0%'}</td>
                            <td>${valores['% PROCESO'] ? valores['% PROCESO'].toFixed(2) + '%' : '0%'}</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html); // Reemplazar el contenido de la tabla

                // Re-inicializar DataTable
                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            function renderTablaSupervisores(supervisores) {
                const tableId = '#tablaResponsables';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                let html = '';
                $.each(supervisores, function (supervisor, valores) {
                    html += `
                        <tr>
                            <td>${supervisor}</td>
                            <td>${valores['% AQL'] ? valores['% AQL'].toFixed(2) + '%' : '0%'}</td>
                            <td>${valores['% PROCESO'] ? valores['% PROCESO'].toFixed(2) + '%' : '0%'}</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            function renderTablaModulos(modulos) {
                const tableId = '#tablaModulos';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                let html = '';
                $.each(modulos, function (modulo, valores) {
                    html += `
                        <tr>
                            <td>${modulo}</td>
                            <td>${valores['% AQL'] ? valores['% AQL'].toFixed(2) + '%' : '0%'}</td>
                            <td>${valores['% PROCESO'] ? valores['% PROCESO'].toFixed(2) + '%' : '0%'}</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }
        });
    </script>

    <script> 
        $(document).ready(function () { 
            // Variable para almacenar la solicitud AJAX, de modo que se pueda abortar si es necesario 
            let dataRequestSemana = null; 
 
            // Función para obtener los datos vía AJAX 
            function fetchDataSemana() { 
                // Si hay una solicitud pendiente, se cancela 
                if (dataRequestSemana) { 
                    dataRequestSemana.abort(); 
                } 
                dataRequestSemana = $.ajax({ 
                    url: "{{ route('dashboard.dataSemanaV2') }}", // Ruta del controlador
                    type: "GET",
                    success: function (data) {
                        // Renderizar tablas
                        renderTablaClientesSemanal(data.clientes);
                        renderTablaResponsablesSemanal(data.supervisores);
                        renderTablaModulosSemanal(data.modulos);

                        // Cargar gráficas solo cuando el usuario las visualiza
                        observeChart('graficaClientesSemanal', renderGraficaClientesSemanal, data.clientes);
                        observeChart('graficaSupervisoresSemanal', renderGraficaSupervisoresSemanal, data.supervisores);
                        observeChart('graficaModulosSemanal', renderGraficaModulosSemanal, data.modulos);
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos de la semana.');
                        }
                    }
                });
            }

            // Función que usa Intersection Observer para cargar la gráfica cuando su contenedor es visible
            function observeChart(containerId, renderFunction, datos) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            renderFunction(datos);
                            obs.unobserve(entry.target); // Deja de observar después de renderizar
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(contenedor);
            }

            // Llamamos a la función para obtener datos vía AJAX
            fetchDataSemana();

            /**
             * 1) GRÁFICA DE CLIENTES (SEMANA)
             */
            function renderGraficaClientesSemanal(clientes) {
                const categorias = clientes.map(c => c.cliente);
                const dataAQL = clientes.map(c => c['% AQL'] || 0);
                const dataProceso = clientes.map(c => c['% PROCESO'] || 0);

                Highcharts.chart('graficaClientesSemanal', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - CLIENTES (SEMANA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { style: { color: '#ffffff' } },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: { enabled: true, brightness: 0.1 }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            /**
             * 2) GRÁFICA DE SUPERVISORES (SEMANA)
             */
            function renderGraficaSupervisoresSemanal(supervisores) {
                const categorias = supervisores.map(s => s.team_leader);
                const dataAQL = supervisores.map(s => s['% AQL'] || 0);
                const dataProceso = supervisores.map(s => s['% PROCESO'] || 0);

                Highcharts.chart('graficaSupervisoresSemanal', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - SUPERVISORES (SEMANA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { style: { color: '#ffffff' } },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: { enabled: true, brightness: 0.1 }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            /**
             * 3) GRÁFICA DE MÓDULOS (SEMANA)
             */
            function renderGraficaModulosSemanal(modulos) {
                const categorias = modulos.map(m => m.modulo);
                const dataAQL = modulos.map(m => m['% AQL'] || 0);
                const dataProceso = modulos.map(m => m['% PROCESO'] || 0);

                Highcharts.chart('graficaModulosSemanal', {
                    chart: {
                        type: 'column',
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'COMPARATIVO AQL Y PROCESO - MÓDULOS (SEMANA ACTUAL)',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: categorias,
                        crosshair: true,
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { style: { color: '#ffffff' } },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        },
                        series: {
                            cursor: 'pointer',
                            states: {
                                hover: { enabled: true, brightness: 0.1 }
                            }
                        }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Funciones para llenar las tablas
            function renderTablaClientesSemanal(clientes) {
                const tableId = '#tablaClientesSemanal';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                let html = '';
                clientes.forEach(cliente => {
                    html += `
                        <tr>
                            <td>${cliente.cliente}</td>
                            <td>${cliente['% AQL'].toFixed(2)}%</td>
                            <td>${cliente['% PROCESO'].toFixed(2)}%</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            function renderTablaResponsablesSemanal(supervisores) {
                const tableId = '#tablaResponsablesSemanal';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                let html = '';
                supervisores.forEach(supervisor => {
                    html += `
                        <tr>
                            <td>${supervisor.team_leader}</td>
                            <td>${supervisor['% AQL'].toFixed(2)}%</td>
                            <td>${supervisor['% PROCESO'].toFixed(2)}%</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            function renderTablaModulosSemanal(modulos) {
                const tableId = '#tablaModulosSemanal';
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                let html = '';
                modulos.forEach(modulo => {
                    html += `
                        <tr>
                            <td>${modulo.modulo}</td>
                            <td>${modulo['% AQL'].toFixed(2)}%</td>
                            <td>${modulo['% PROCESO'].toFixed(2)}%</td>
                        </tr>`;
                });
                $(tableId + ' tbody').html(html);

                $(tableId).DataTable({ 
                    lengthChange: false, 
                    searching: true, 
                    paging: true, 
                    pageLength: 5, 
                    autoWidth: false, 
                    responsive: true 
                }); 
            } 
        }); 
    </script> 

    <script>
        $(document).ready(function () {
            // Variable para almacenar la solicitud AJAX
            let dataRequestMensual = null;

            // Función para observar si la gráfica es visible antes de cargar los datos
            function observeChart(containerId, fetchFunction) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            fetchFunction(); // Cargar datos solo cuando sea visible
                            obs.unobserve(entry.target); // Dejar de observar después de cargar
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(contenedor);
            }

            // Función para obtener los datos vía AJAX
            function fetchMensualGeneral() {
                // Si hay una solicitud pendiente, se cancela
                if (dataRequestMensual) {
                    dataRequestMensual.abort();
                }

                dataRequestMensual = $.ajax({
                    url: "{{ route('dashboard.mensualGeneralV2') }}",
                    type: "GET",
                    success: function (data) {
                        renderGraficaMensualGeneral(data);
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos mensuales generales.');
                        }
                    }
                });
            }

            // Función para renderizar la gráfica mensual
            function renderGraficaMensualGeneral(data) {
                const dias = data.map(item => item.dia);
                const dataAQL = data.map(item => item.AQL || 0);
                const dataProceso = data.map(item => item.PROCESO || 0);

                // Obtener el nombre del mes actual
                const fechaHoy = new Date();
                const nombreMes = fechaHoy.toLocaleString('es-ES', { month: 'long' });

                Highcharts.chart('graficaMensualGeneral', {
                    chart: {
                        type: 'areaspline',
                        height: 500,
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        }
                    },
                    title: {
                        text: 'Indicador Mensual General - AQL y PROCESO',
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: dias,
                        crosshair: true,
                        title: { 
                            text: `Días del Mes - ${nombreMes}`,
                            style: { color: '#ffffff' }
                        },
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        title: {
                            text: 'Porcentaje (%)',
                            style: { color: '#ffffff' }
                        },
                        min: 0,
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { style: { color: '#ffffff' } },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            // Solución: Sumar 1 a this.x
                            let tooltip = `<b>Día ${this.x + 1}</b><br/>`; 
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 0.7,
                            lineWidth: 2,
                            marker: {
                                enabled: false
                            },
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    series: [
                        { 
                            name: '% AQL',
                            data: dataAQL,
                            color: '#00f0c1',
                            zIndex: 1 
                        },
                        { 
                            name: '% PROCESO',
                            data: dataProceso,
                            color: '#dd4dc7',
                            zIndex: 0 
                        }
                    ]
                });
            }

            // Se activa la carga diferida de la gráfica cuando sea visible
            observeChart('graficaMensualGeneral', fetchMensualGeneral);
        });
    </script>

    <script>
        $(document).ready(function () {
            // Función para observar si las gráficas son visibles antes de cargarlas
            function observeChart(containerId, fetchFunction) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            fetchFunction(); // Cargar datos solo cuando sea visible
                            obs.unobserve(entry.target); // Dejar de observar después de cargar
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(contenedor);
            }

            // Función para obtener los datos vía AJAX sin variables globales
            function fetchMensualPorCliente() {
                let dataRequest = $.ajax({
                    url: "{{ route('dashboard.mensualPorClienteV2') }}",
                    type: "GET",
                    success: function (data) {
                        let chartAQL = renderGraficaPorCliente(data, 'AQL', 'clienteChartAQL');
                        let chartProceso = renderGraficaPorCliente(data, 'PROCESO', 'clienteChartProcesos');

                        // Inicialización de la vista
                        $('#clienteChartAQL').show();
                        $('#clienteChartProcesos').hide();

                        // Botones dinámicos para cambiar de gráfico
                        $('#cliente0').off('click').on('click', function () {
                            $('#clienteChartAQL').show();
                            $('#clienteChartProcesos').hide();
                            chartAQL.reflow();
                        });

                        $('#cliente1').off('click').on('click', function () {
                            $('#clienteChartAQL').hide();
                            $('#clienteChartProcesos').show();
                            chartProceso.reflow();
                        });
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos mensuales por cliente.');
                        }
                    }
                });

                // Cancelar la petición AJAX si se cambia de vista antes de completarse
                $(window).on('beforeunload', function () {
                    if (dataRequest) {
                        dataRequest.abort();
                    }
                });
            }

            // Función para renderizar las gráficas mensuales por cliente
            function renderGraficaPorCliente(data, tipo, containerId) {
                const series = [];

                // Configuración de las series con datos, asignando 0 si el dato no existe
                Object.keys(data).forEach(cliente => {
                    const valores = data[cliente].map(item => {
                        return (item[tipo] !== undefined && item[tipo] !== null) ? item[tipo] : 0;
                    });
                    series.push({
                        name: cliente,
                        data: valores,
                        type: 'spline', // Gráfica curva
                        marker: { enabled: false }
                    });
                });

                // Generar la gráfica
                return Highcharts.chart(containerId, {
                    chart: {
                        type: 'spline',
                        height: 500,
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        },
                        // Dentro de la configuración del chart en renderGraficaPorCliente:
                        events: {
                            load: function () {
                                const chart = this;
                                // Crear botón usando el sistema de botones de Highcharts (más integrado)
                                chart.addButton({
                                    text: 'Mostrar/Ocultar Todo',
                                    theme: {
                                        fill: '#007BFF',   // Color de fondo
                                        stroke: '#0056B3', // Borde
                                        'stroke-width': 1,
                                        r: 5,             // Bordes redondeados
                                        style: {
                                            color: '#FFFFFF' // Color del texto
                                        }
                                    },
                                    onclick: function () {
                                        const allVisible = chart.series.every(s => s.visible);
                                        chart.series.forEach(series => {
                                            series.setVisible(!allVisible, false);
                                        });
                                        chart.redraw();
                                    },
                                    x: 10,  // Posición X
                                    y: 10   // Posición Y
                                });
                            }
                        }
                    },
                    title: {
                        text: `Indicador Mensual por Cliente - ${tipo}`,
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: Array.from({ length: data[Object.keys(data)[0]].length }, (_, i) => i + 1),
                        title: { text: 'Días del Mes', style: { color: '#ffffff' } },
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } },
                        min: 0,
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>Día ${this.x + 1}</b><br/>`;
                            this.points.forEach(point => {
                                // Mostrar solo puntos con valor mayor a 0
                                if (point.y > 0) {
                                    tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                                }
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { enabled: false },
                    series: series
                });
            }

            // Se activa la carga diferida de la gráfica cuando sea visible
            observeChart('clienteChartAQL', fetchMensualPorCliente);
        });
    </script>

    <script>
        $(document).ready(function () {
            // Función para observar si las gráficas son visibles antes de cargarlas
            function observeChart(containerId, fetchFunction) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            fetchFunction(); // Cargar datos solo cuando sea visible
                            obs.unobserve(entry.target); // Dejar de observar después de cargar
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(contenedor);
            }

            // Función para obtener los datos vía AJAX sin variables globales
            function fetchMensualPorModulo() {
                let dataRequest = $.ajax({
                    url: "{{ route('dashboard.mensualPorModuloV2') }}",
                    type: "GET",
                    success: function (data) {
                        let chartAQLModulo = renderGraficaPorModulo(data, 'AQL', 'moduloChartAQL');
                        let chartProcesoModulo = renderGraficaPorModulo(data, 'PROCESO', 'moduloChartProcesos');

                        // Inicialización de la vista
                        $('#moduloChartAQL').show();
                        $('#moduloChartProcesos').hide();

                        // Botones dinámicos para cambiar de gráfico
                        $('#modulo0').off('click').on('click', function () {
                            $('#moduloChartAQL').show();
                            $('#moduloChartProcesos').hide();
                            chartAQLModulo.reflow();
                        });

                        $('#modulo1').off('click').on('click', function () {
                            $('#moduloChartAQL').hide();
                            $('#moduloChartProcesos').show();
                            chartProcesoModulo.reflow();
                        });
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos mensuales por módulo.');
                        }
                    }
                });

                // Cancelar la petición AJAX si se cambia de vista antes de completarse
                $(window).on('beforeunload', function () {
                    if (dataRequest) {
                        dataRequest.abort();
                    }
                });
            }

            // Función para renderizar las gráficas mensuales por módulo
            function renderGraficaPorModulo(data, tipo, containerId) {
                const series = [];

                // Configuración de las series con datos, asignando 0 si el dato no existe
                Object.keys(data).forEach(modulo => {
                    const valores = data[modulo].map(item => {
                        return (item[tipo] !== undefined && item[tipo] !== null) ? item[tipo] : 0;
                    });
                    series.push({
                        name: modulo,
                        data: valores,
                        type: 'spline', // Gráfica curva
                        marker: { enabled: false }
                    });
                });

                // Generar la gráfica
                return Highcharts.chart(containerId, {
                    chart: {
                        type: 'spline',
                        height: 500, // Se mantiene en 500px
                        backgroundColor: 'transparent',
                        style: {
                            fontFamily: 'Arial, sans-serif',
                            color: '#ffffff'
                        },
                        events: {
                            load: function () {
                                const chart = this;
                                // Crear botón usando el sistema de botones de Highcharts (más integrado)
                                chart.addButton({
                                    text: 'Mostrar/Ocultar Todo',
                                    theme: {
                                        fill: '#007BFF',   // Color de fondo
                                        stroke: '#0056B3', // Borde
                                        'stroke-width': 1,
                                        r: 5,             // Bordes redondeados
                                        style: {
                                            color: '#FFFFFF' // Color del texto
                                        }
                                    },
                                    onclick: function () {
                                        const allVisible = chart.series.every(s => s.visible);
                                        chart.series.forEach(series => {
                                            series.setVisible(!allVisible, false);
                                        });
                                        chart.redraw();
                                    },
                                    x: 10,  // Posición X
                                    y: 10   // Posición Y
                                });
                            }
                        }
                    },
                    title: {
                        text: `Indicador Mensual por Módulo - ${tipo}`,
                        align: 'center',
                        style: { 
                            color: '#ffffff',
                            fontWeight: 'bold',
                            fontSize: '20px'
                        }
                    },
                    xAxis: {
                        categories: Array.from({ length: data[Object.keys(data)[0]].length }, (_, i) => i + 1),
                        title: { text: 'Días del Mes', style: { color: '#ffffff' } },
                        lineColor: '#ffffff',
                        tickColor: '#ffffff',
                        labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } },
                        min: 0,
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: '#4a4a4a'
                    },
                    tooltip: {
                        shared: true,
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        formatter: function () {
                            let tooltip = `<b>Día ${this.x + 1}</b><br/>`;
                            this.points.forEach(point => {
                                // Mostrar solo puntos con valor mayor a 0
                                if (point.y > 0) {
                                    tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                                }
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 2,
                            states: {
                                hover: {
                                    enabled: true,
                                    brightness: 0.1
                                }
                            }
                        }
                    },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { enabled: false },
                    series: series
                });
            }

            // Se activa la carga diferida de la gráfica cuando sea visible
            observeChart('moduloChartAQL', fetchMensualPorModulo);
        });
    </script>

    <script>
        $(document).ready(function () {
            let chartAQL, chartProceso; // Variables para almacenar las gráficas
            let dataRequest = null; // Variable para almacenar la petición AJAX

            // Función para observar si la gráfica es visible antes de cargarse
            function observeChart(containerId, fetchFunction) {
                const contenedor = document.getElementById(containerId);
                if (!contenedor) return;

                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            fetchFunction(); // Cargar datos solo cuando sea visible
                            obs.unobserve(entry.target); // Dejar de observar después de cargar
                        }
                    });
                }, { threshold: 0.1 });

                observer.observe(contenedor);
            }

            // Función para obtener los datos de defectos vía AJAX
            function fetchDefectoMensual() {
                if (dataRequest) {
                    dataRequest.abort(); // Cancelar cualquier petición en curso antes de hacer una nueva
                }

                dataRequest = $.ajax({
                    url: "{{ route('dashboard.defectoMensualV2') }}",
                    type: "GET",
                    success: function (data) {
                        // Cargar las gráficas con los datos obtenidos
                        chartAQL = crearGrafica(data.topDefectosAQL, 'Top 3 Defectos AQL', 'chartAQL');
                        chartProceso = crearGrafica(data.topDefectosProceso, 'Top 3 Defectos Proceso', 'chartProceso');
                    },
                    error: function (xhr, status) {
                        if (status !== 'abort') {
                            alert('Error al cargar los datos de defectos.');
                        }
                    }
                });
            }

            // Cancelar la petición AJAX si el usuario cambia de vista antes de completarse
            $(window).on('beforeunload', function () {
                if (dataRequest) {
                    dataRequest.abort();
                }
            });

            // Cancelar AJAX si el usuario oculta la sección
            const observerMutacion = new MutationObserver((mutations) => {
                mutations.forEach(mutation => {
                    if (mutation.target.style.display === "none" && dataRequest) {
                        dataRequest.abort();
                    }
                });
            });

            observerMutacion.observe(document.getElementById('chartAQL'), { attributes: true, attributeFilter: ['style'] });
            observerMutacion.observe(document.getElementById('chartProceso'), { attributes: true, attributeFilter: ['style'] });

            // Lista de colores predefinidos
            const colores = ['#F03C3C', '#F0E23C', '#3C8EF0', '#36A2EB', '#FFCE56'];

            // Preparar datos para la gráfica
            function prepararDatos(datos) {
                const tp = datos.map(d => d.tp);
                const total = datos.map(d => d.total);

                return { tp, total };
            }

            // Función para crear la gráfica
            function crearGrafica(datos, titulo, containerId) {
                const { tp, total } = prepararDatos(datos);

                while (tp.length < 3) {
                    tp.push("N/A");
                    total.push(0);
                }

                return Highcharts.chart(containerId, {
                    chart: {
                        type: 'column',
                        height: 400,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: titulo,
                        style: { color: '#FFFFFF' }
                    },
                    xAxis: {
                        categories: tp,
                        title: { text: 'Defectos', style: { color: '#FFFFFF' } },
                        labels: { 
                            enabled: false // Oculta los nombres de los defectos en el eje X
                        }
                    },
                    yAxis: {
                        title: { text: 'Número de defectos', style: { color: '#FFFFFF' } },
                        labels: { style: { color: '#FFFFFF' } },
                        min: 0
                    },
                    legend: {
                        itemStyle: { color: '#FFFFFF' }
                    },
                    tooltip: {
                        backgroundColor: '#000000',
                        style: { color: '#ffffff' },
                        useHTML: true,
                        formatter: function () {
                            return `<span style="color:${this.point.color}">\u25CF</span> 
                                    <b>${this.series.name}</b>: ${this.y}`;
                        }
                    },
                    series: total.map((value, index) => ({
                        name: tp[index],
                        data: [value],
                        color: colores[index % colores.length]
                    })),
                    plotOptions: {
                        column: {
                            colorByPoint: false,
                            borderColor: '#27293D'
                        }
                    },
                    credits: { enabled: false }
                });
            }

            // Evento para cambiar entre AQL y Proceso
            $('#top3-AQL').off('click').on('click', function () {
                $('#chartAQL').show();
                $('#chartProceso').hide();
                chartAQL.reflow();
            });

            $('#top3-Proceso').off('click').on('click', function () {
                $('#chartAQL').hide();
                $('#chartProceso').show();
                chartProceso.reflow();
            });

            // Se activa la carga diferida de la gráfica cuando sea visible
            observeChart('chartAQL', fetchDefectoMensual);
        });
    </script> 

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const spinner = document.getElementById("spinner");
        const chartContainer = document.getElementById("SegundasTercerasChart");
        let fetchController; // Para controlar y abortar la petición
        let chartCargada = false; // Bandera para evitar cargas múltiples
    
        if (!chartContainer) return; // Evitar errores si el contenedor no existe
    
        // Función que carga la gráfica mediante AJAX
        async function loadChart() {
            try {
            spinner.style.display = "block";
            // Creamos un nuevo AbortController
            fetchController = new AbortController();
    
            const response = await fetch("/SegundasTerceras", {
                method: "GET",
                headers: { "Content-Type": "application/json" },
                signal: fetchController.signal
            });
    
            if (!response.ok) {
                throw new Error("Error en la respuesta de la red");
            }
    
            const data = await response.json();
            generarGraficaSegundasTerceras(data.data);
            chartCargada = true;
            } catch (error) {
            if (error.name === "AbortError") {
                console.log("La carga de datos fue abortada.");
            } else {
                console.error("Error al cargar los datos:", error);
            }
            } finally {
            spinner.style.display = "none";
            }
        }
    
        // Utilizamos IntersectionObserver para cargar la gráfica solo cuando el contenedor es visible
        const observer = new IntersectionObserver((entries, observerInstance) => {
            entries.forEach(entry => {
            if (entry.isIntersecting && !chartCargada) {
                loadChart();
                // Una vez cargada, dejamos de observar
                observerInstance.unobserve(entry.target);
            }
            });
        }, { threshold: 0.1 });
    
        observer.observe(chartContainer);
    
        // Si el usuario cambia de vista (por ejemplo, la pestaña se oculta), abortamos la petición
        document.addEventListener("visibilitychange", () => {
            if (document.hidden && fetchController) {
            fetchController.abort();
            }
        });
    
        function generarGraficaSegundasTerceras(datos) {
            let segundas = 0, terceras = 0, totalQty = 0;
    
            datos.forEach(item => {
            let qty = parseFloat(item.Total_QTY) || 0; // Aseguramos que sea numérico
            totalQty += qty;
            if (item.QUALITY === "1") segundas += qty;
            if (item.QUALITY === "2") terceras += qty;
            });
    
            let porcentajeSegundas = totalQty ? ((segundas * 100) / totalQty).toFixed(2) : 0;
            let porcentajeTerceras = totalQty ? ((terceras * 100) / totalQty).toFixed(2) : 0;
    
            Highcharts.chart("SegundasTercerasChart", {
            chart: {
                type: "column",
                backgroundColor: "transparent"
            },
            title: {
                text: "Segundas y Terceras",
                style: { color: "#FFFFFF" }
            },
            xAxis: {
                categories: ["Segundas", "Terceras"],
                labels: { style: { color: "#FFFFFF" } }
            },
            yAxis: {
                min: 0,
                title: {
                text: "Cantidad",
                style: { color: "#FFFFFF" }
                },
                labels: { style: { color: "#FFFFFF" } }
            },
            tooltip: {
                shared: false, // Tooltip no compartido
                backgroundColor: "#000000",
                style: { color: "#FFFFFF" },
                formatter: function () {
                if (this.series.name === "Segundas") {
                    return `<b>Segundas</b><br>
                            <b>Cantidad:</b> ${this.y}<br>
                            <b>Porcentaje:</b> ${porcentajeSegundas}%`;
                } else if (this.series.name === "Terceras") {
                    return `<b>Terceras</b><br>
                            <b>Cantidad:</b> ${this.y}<br>
                            <b>Porcentaje:</b> ${porcentajeTerceras}%`;
                }
                }
            },
            series: [
                {
                name: "Segundas",
                id: "segundas",
                data: [segundas],
                color: "#7cb5ec",
                dataLabels: {
                    enabled: true,
                    style: { color: "#FFFFFF" }
                },
                events: {
                    click: function () {
                    window.location.href = "/Segundas";
                    }
                }
                },
                {
                name: "Terceras",
                id: "terceras",
                data: [terceras],
                color: "#434348",
                dataLabels: {
                    enabled: true,
                    style: { color: "#FFFFFF" }
                }
                }
            ],
            legend: {
                enabled: true,
                itemStyle: { color: "#FFFFFF" }
            }
            });
        }
        });
    </script>
  
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('api.porcentajesPorDiaV2') }}",
                type: "GET",
                success: function(data) {
                    $("#generalAQL").text(data.generalAQL + "%");
                    $("#generalAQLPlanta1").text(data.generalAQLPlanta1 + "%");
                    $("#generalAQLPlanta2").text(data.generalAQLPlanta2 + "%");
                    $("#generalProceso").text(data.generalProceso + "%");
                    $("#generalProcesoPlanta1").text(data.generalProcesoPlanta1 + "%");
                    $("#generalProcesoPlanta2").text(data.generalProcesoPlanta2 + "%");
                    // Actualizar los elementos en la tabla de clientes
                    $("#tablaGeneralAQL").text(data.generalAQL + "%");
                    $("#tablaGeneralProceso").text(data.generalProceso + "%");
                }
            });
        });
    </script>
@endpush
