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
        // Espera a que todo el contenido del DOM esté cargado antes de ejecutar el script.
        document.addEventListener('DOMContentLoaded', function () {
            
            // Elementos del DOM donde mostraremos los resultados.
            const generalScreenTd = document.getElementById('generalScreen');
            const generalProcesoPlanchaTd = document.getElementById('generalProcesoPlancha');

            // Usamos la API Fetch para hacer la petición AJAX a nuestra nueva ruta.
            // La función route() de Laravel genera la URL correcta.
            fetch("{{ route('screen.dashboard.stats') }}")
                .then(response => {
                    // Verificamos si la respuesta del servidor es exitosa.
                    if (!response.ok) {
                        throw new Error('La respuesta del servidor no fue exitosa.');
                    }
                    return response.json(); // Convertimos la respuesta a JSON.
                })
                .then(data => {
                    // Una vez que tenemos los datos, actualizamos el HTML.
                    // Añadimos el símbolo de '%' para mayor claridad.
                    generalScreenTd.textContent = data.porcentajeScreen + ' %';
                    generalProcesoPlanchaTd.textContent = data.porcentajePlancha + ' %';
                })
                .catch(error => {
                    // Si ocurre un error en cualquier punto, lo mostramos en la consola
                    // y actualizamos el texto para informar al usuario.
                    console.error('Error al cargar las estadísticas:', error);
                    generalScreenTd.textContent = 'Error al cargar';
                    generalProcesoPlanchaTd.textContent = 'Error al cargar';
                });
        });

        document.addEventListener('DOMContentLoaded', function () {

            const tablaBody = document.querySelector('#tablaClientes tbody');
            const footerScreen = document.getElementById('tablaGeneralScreen'); // ID ajustado para claridad
            const footerPlancha = document.getElementById('tablaGeneralProcesoPlancha'); // ID ajustado para claridad

            fetch("{{ route('screen.dashboard.client-stats') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La respuesta del servidor no fue exitosa.');
                    }
                    return response.json();
                })
                .then(data => {
                    // Limpiamos el cuerpo de la tabla antes de insertar nuevos datos.
                    tablaBody.innerHTML = '';

                    if (data.clientes && data.clientes.length > 0) {
                        // Iteramos sobre la lista de clientes y creamos una fila por cada uno.
                        data.clientes.forEach(cliente => {
                            const row = `
                                <tr>
                                    <td>${cliente.cliente}</td>
                                    <td>${cliente.porcentajeScreen} %</td>
                                    <td>${cliente.porcentajePlancha} %</td>
                                </tr>
                            `;
                            tablaBody.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        // Mensaje si no se encontraron auditorías para el día.
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos para mostrar.</td></tr>';
                    }

                    // Actualizamos el pie de tabla con los totales generales.
                    footerScreen.textContent = data.generales.porcentajeScreen + ' %';
                    footerPlancha.textContent = data.generales.porcentajePlancha + ' %';
                })
                .catch(error => {
                    console.error('Error al cargar las estadísticas por cliente:', error);
                    tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Error al cargar los datos.</td></tr>';
                    footerScreen.textContent = 'Error';
                    footerPlancha.textContent = 'Error';
                });
        });

        document.addEventListener('DOMContentLoaded', function () {

            const tablaBody = document.querySelector('#tablaResponsables tbody');

            fetch("{{ route('screen.dashboard.responsible-stats') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La respuesta del servidor no fue exitosa.');
                    }
                    return response.json();
                })
                .then(data => {
                    tablaBody.innerHTML = ''; // Limpiamos la tabla.

                    if (data && data.length > 0) {
                        data.forEach(responsable => {
                            const row = `
                                <tr>
                                    <td>${responsable.responsable}</td>
                                    <td>${responsable.porcentajeScreen} %</td>
                                    <td>${responsable.porcentajePlancha} %</td>
                                </tr>
                            `;
                            tablaBody.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos para mostrar.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las estadísticas por responsable:', error);
                    tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Error al cargar los datos.</td></tr>';
                });
        });

        document.addEventListener('DOMContentLoaded', function () {

            const tablaBody = document.querySelector('#tablaModulos tbody');

            fetch("{{ route('screen.dashboard.machine-stats') }}")
                .then(response => {
                    if (!response.ok) {
                        throw new Error('La respuesta del servidor no fue exitosa.');
                    }
                    return response.json();
                })
                .then(data => {
                    tablaBody.innerHTML = ''; // Limpiamos la tabla.

                    if (data && data.length > 0) {
                        data.forEach(maquina => {
                            const row = `
                                <tr>
                                    <td>${maquina.maquina}</td>
                                    <td>${maquina.porcentajeScreen} %</td>
                                    <td>${maquina.porcentajePlancha} %</td>
                                </tr>
                            `;
                            tablaBody.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No hay datos para mostrar.</td></tr>';
                    }
                })
                .catch(error => {
                    console.error('Error al cargar las estadísticas por máquina:', error);
                    tablaBody.innerHTML = '<tr><td colspan="3" style="text-align: center;">Error al cargar los datos.</td></tr>';
                });
        });
    </script>
@endsection

