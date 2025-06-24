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
                    <h4 class="card-title">Responsables SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
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
                    <h4 class="card-title">Maquina SCREEN <i class="tim-icons icon-palette text-success"></i> y Proceso Plancha <i class="tim-icons icon-volume-98 text-primary"></i></h4>
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
                                    <span class="d-none d-sm-block">SCREEN</span>
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
                            <label class="btn btn-sm btn-primary btn-simple active" id="top3-SCREEN">
                                <input type="radio" name="top3Options" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">SCREEN</span>
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
        // Usamos un solo listener para todo el código. Esto asegura que todo el HTML está listo.
        document.addEventListener('DOMContentLoaded', function () {

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
                    chart: { type: 'column', backgroundColor: 'transparent', style: { fontFamily: 'inherit', color: '#ffffff' } },
                    title: { text: title, align: 'center', style: { color: '#ffffff', fontWeight: 'bold' } },
                    xAxis: { categories: categories, crosshair: true, lineColor: '#ffffff', tickColor: '#ffffff', labels: { style: { color: '#ffffff' } } },
                    yAxis: { min: 0, title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } }, labels: { style: { color: '#ffffff' } }, gridLineColor: 'rgba(255, 255, 255, 0.2)' },
                    tooltip: { shared: true, backgroundColor: 'rgba(0, 0, 0, 0.85)', style: { color: '#ffffff' }, formatter: function () { let tooltip = `<b>${this.key}</b><br/>`; this.points.forEach(point => { tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`; }); return tooltip; } },
                    plotOptions: { column: { borderWidth: 0, pointPadding: 0.2 }, series: { dataLabels: { enabled: true, rotation: -90, color: '#FFFFFF', align: 'right', format: '{point.y:.2f}%', y: 10, style: { fontSize: '10px', fontWeight: 'normal', textOutline: 'none' } } } },
                    legend: { itemStyle: { color: '#ffffff' }, itemHoverStyle: { color: '#cccccc' } },
                    credits: { enabled: false },
                    series: seriesData
                });
            };

            // --- NUEVA FUNCIÓN DE AYUDA PARA GRÁFICOS DE ÁREA ---
            const createAreaSplineChart = (containerId, title, categories, seriesData) => {
                const nombreMes = new Date().toLocaleString('es-ES', { month: 'long' });
                Highcharts.chart(containerId, {
                    chart: { type: 'areaspline', height: 500, backgroundColor: 'transparent', style: { fontFamily: 'inherit', color: '#ffffff' } },
                    title: { text: title, align: 'center', style: { color: '#ffffff', fontWeight: 'bold', fontSize: '20px' } },
                    xAxis: {
                        categories: categories,
                        crosshair: true,
                        title: { text: `Días del Mes - ${nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1)}`, style: { color: '#ffffff' } },
                        lineColor: '#ffffff', tickColor: '#ffffff', labels: { style: { color: '#ffffff' } }
                    },
                    yAxis: { title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } }, min: 0, labels: { style: { color: '#ffffff' } }, gridLineColor: 'rgba(255, 255, 255, 0.2)' },
                    tooltip: { shared: true, backgroundColor: 'rgba(0, 0, 0, 0.85)', style: { color: '#ffffff' }, formatter: function () { let tooltip = `<b>Día ${this.x}</b><br/>`; this.points.forEach(point => { tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`; }); return tooltip; } },
                    plotOptions: { areaspline: { fillOpacity: 0.5, lineWidth: 2, marker: { enabled: false } } },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { enabled: false },
                    series: seriesData
                });
            };

            /**
             * Función que observa un contenedor y llama a una función de renderizado
             * solo cuando el contenedor se vuelve visible en la pantalla (Lazy Loading).
             */
            const observeChart = (containerId, renderCallback) => {
                const container = document.getElementById(containerId);
                if (!container) { console.error(`Error: No se encontró el contenedor del gráfico con id #${containerId}`); return; }
                const observer = new IntersectionObserver((entries, obs) => { if (entries[0].isIntersecting) { renderCallback(); obs.unobserve(container); } }, { threshold: 0.1 });
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
                    document.getElementById('generalProcesoPlancha').textContent = data.porcentajePlancha + ' %';
                })
                .catch(err => console.error('Error en stats generales diarios:', err));

            // Fetch DIARIO para Clientes
            fetch("{{ route('screen.dashboard.client-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaClientes tbody');
                    tablaBody.innerHTML = ''; 
                    if (data.clientes && data.clientes.length > 0) {
                        data.clientes.forEach(cliente => { tablaBody.innerHTML += `<tr><td>${cliente.cliente}</td><td>${cliente.porcentajeScreen} %</td><td>${cliente.porcentajePlancha} %</td></tr>`; });
                        
                        const renderClientChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.clientes.map(c => c.cliente); 
                            const series = [{ name: '% SCREEN', data: data.clientes.map(c => c.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.clientes.map(c => c.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaClientePorDia', 'Comparativo Cliente (Día)', categories, series); 
                        };
                        observeChart('graficaClientePorDia', renderClientChartDaily);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de clientes.</td></tr>'; }
                })
                .catch(err => console.error('Error en stats diarios de clientes:', err));

            // Fetch DIARIO para Responsables
            fetch("{{ route('screen.dashboard.responsible-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaResponsables tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => { tablaBody.innerHTML += `<tr><td>${item.responsable}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`; });
                        
                        const renderResponsibleChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.responsable); 
                            const series = [{ name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaResponsablePorDia', 'Comparativo Responsable (Día)', categories, series); 
                        };
                        observeChart('graficaResponsablePorDia', renderResponsibleChartDaily);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de responsables.</td></tr>'; }
                })
                .catch(err => console.error('Error en stats diarios de responsables:', err));

            // Fetch DIARIO para Máquinas
            fetch("{{ route('screen.dashboard.machine-stats') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaMaquinas tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => { tablaBody.innerHTML += `<tr><td>${item.maquina}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`; });
                        
                        const renderMachineChartDaily = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.maquina); 
                            const series = [{ name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaMaquinaPorDia', 'Comparativo Máquina (Día)', categories, series); 
                        };
                        observeChart('graficaMaquinaPorDia', renderMachineChartDaily);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos diarios de máquinas.</td></tr>'; }
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
                        data.clientes.forEach(cliente => { tablaBody.innerHTML += `<tr><td>${cliente.cliente}</td><td>${cliente.porcentajeScreen} %</td><td>${cliente.porcentajePlancha} %</td></tr>`; });
                        
                        const renderClientChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.clientes.map(c => c.cliente); 
                            const series = [{ name: '% SCREEN', data: data.clientes.map(c => c.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.clientes.map(c => c.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaClientePorSemana', 'Comparativo Cliente (Semana)', categories, series); 
                        };
                        observeChart('graficaClientePorSemana', renderClientChartWeekly);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de clientes.</td></tr>'; }
                })
                .catch(err => console.error('Error en stats semanales de clientes:', err));

            // Fetch SEMANAL para Responsables
            fetch("{{ route('screen.dashboard.responsible-stats-weekly') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaResponsablesSemanal tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => { tablaBody.innerHTML += `<tr><td>${item.responsable}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`; });
                        
                        const renderResponsibleChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.responsable); 
                            const series = [{ name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaResponsablePorSemana', 'Comparativo Responsable (Semana)', categories, series); 
                        };
                        observeChart('graficaResponsablePorSemana', renderResponsibleChartWeekly);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de responsables.</td></tr>'; }
                })
                .catch(err => console.error('Error en stats semanales de responsables:', err));

            // Fetch SEMANAL para Máquinas
            fetch("{{ route('screen.dashboard.machine-stats-weekly') }}")
                .then(res => res.ok ? res.json() : Promise.reject(res))
                .then(data => {
                    const tablaBody = document.querySelector('#tablaMaquinasSemanal tbody');
                    tablaBody.innerHTML = '';
                    if (data && data.length > 0) {
                        data.forEach(item => { tablaBody.innerHTML += `<tr><td>${item.maquina}</td><td>${item.porcentajeScreen} %</td><td>${item.porcentajePlancha} %</td></tr>`; });
                        
                        const renderMachineChartWeekly = () => { // <--- NOMBRE ÚNICO
                            const categories = data.map(item => item.maquina); 
                            const series = [{ name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6' }, { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca' }]; 
                            createBarChart('graficaMaquinaPorSemana', 'Comparativo Máquina (Semana)', categories, series); 
                        };
                        observeChart('graficaMaquinaPorSemana', renderMachineChartWeekly);
                    } else { tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos semanales de máquinas.</td></tr>'; }
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
                            const series = [
                                { name: '% SCREEN', data: data.map(item => item.porcentajeScreen), color: '#2bffc6', zIndex: 1 },
                                { name: '% Proceso Plancha', data: data.map(item => item.porcentajePlancha), color: '#e14eca', zIndex: 0 }
                            ];
                            createAreaSplineChart('graficaMensualGeneral', 'Tendencia Mensual: SCREEN vs. Proceso Plancha', dias, series);
                        };
                        observeChart('graficaMensualGeneral', renderMonthlyChart);
                    } else {
                        // Opcional: Mostrar un mensaje si no hay datos en el mes
                        document.getElementById('graficaMensualGeneral').innerHTML = '<div class="loading-container"><div class="loading-text">No hay datos para el mes actual.</div></div>';
                    }
                })
                .catch(err => {
                    console.error('Error en stats mensuales:', err);
                    document.getElementById('graficaMensualGeneral').innerHTML = '<div class="loading-container"><div class="loading-text">Error al cargar el gráfico.</div></div>';
                });


        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- FUNCIÓN DE AYUDA PARA CREAR GRÁFICOS DE LÍNEAS MÚLTIPLES ---
            function createMultiLineChart(containerId, title, categories, seriesData) {
                return Highcharts.chart(containerId, {
                    chart: { type: 'spline', height: 500, backgroundColor: 'transparent' },
                    title: { text: title, style: { color: '#ffffff', fontWeight: 'bold' } },
                    xAxis: {
                        categories: categories,
                        title: { text: 'Días del Mes', style: { color: '#ffffff' } },
                        labels: { style: { color: '#ffffff' } },
                        lineColor: '#ffffff', tickColor: '#ffffff'
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)', style: { color: '#ffffff' } },
                        labels: { style: { color: '#ffffff' } },
                        gridLineColor: 'rgba(255, 255, 255, 0.2)',
                        min: 0
                    },
                    tooltip: { shared: true, backgroundColor: 'rgba(0,0,0,0.8)', style: { color: '#ffffff' } },
                    legend: { itemStyle: { color: '#ffffff' } },
                    credits: { enabled: false },
                    series: seriesData
                });
            }

            // --- LÓGICA PRINCIPAL ---
            const loadingContainer = document.getElementById('loadingContainerCliente');
            const containerScreen = document.getElementById('clienteChartSCREEN');
            const containerPlancha = document.getElementById('clienteChartProcesoPlancha');
            const btnScreen = document.getElementById('btnClienteScreen');
            const btnPlancha = document.getElementById('btnClientePlancha');

            // Función para obtener y procesar los datos
            function fetchDataAndRenderCharts() {
                fetch("{{ route('screen.dashboard.client-stats-month') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        // Ocultar el loader
                        loadingContainer.style.display = 'none';

                        if (Object.keys(data).length === 0) {
                            containerScreen.innerHTML = '<div class="loading-text">No hay datos de clientes para mostrar.</div>';
                            containerScreen.style.display = 'block';
                            return;
                        }

                        const clientes = Object.keys(data);
                        const diasDelMes = data[clientes[0]].map(d => d.dia);

                        // Preparamos los datos para cada gráfico
                        const seriesScreen = clientes.map(cliente => ({
                            name: cliente,
                            data: data[cliente].map(d => d.porcentajeScreen)
                        }));
                        
                        const seriesPlancha = clientes.map(cliente => ({
                            name: cliente,
                            data: data[cliente].map(d => d.porcentajePlancha)
                        }));

                        // Renderizamos ambos gráficos pero mantenemos uno oculto
                        const chartScreen = createMultiLineChart('clienteChartSCREEN', 'Indicador Mensual por Cliente - SCREEN', diasDelMes, seriesScreen);
                        const chartPlancha = createMultiLineChart('clienteChartProcesoPlancha', 'Indicador Mensual por Cliente - PROCESO PLANCHA', diasDelMes, seriesPlancha);
                        
                        // Mostramos el gráfico inicial
                        containerScreen.style.display = 'block';

                        // Lógica de los botones para cambiar de vista
                        btnScreen.addEventListener('click', () => {
                            containerPlancha.style.display = 'none';
                            containerScreen.style.display = 'block';
                            chartScreen.reflow(); // Reajusta el tamaño del gráfico
                        });

                        btnPlancha.addEventListener('click', () => {
                            containerScreen.style.display = 'none';
                            containerPlancha.style.display = 'block';
                            chartPlancha.reflow(); // Reajusta el tamaño del gráfico
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los datos por cliente:', error);
                        loadingContainer.innerHTML = '<div class="loading-text">Error al cargar los datos.</div>';
                    });
            }

            // Usamos IntersectionObserver para cargar los datos solo cuando el contenedor sea visible
            const observer = new IntersectionObserver((entries, obs) => {
                if (entries[0].isIntersecting) {
                    fetchDataAndRenderCharts();
                    obs.unobserve(entries[0].target);
                }
            }, { threshold: 0.1 });

            observer.observe(loadingContainer);
        });
    </script>

@endsection

