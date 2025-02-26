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
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td id="generalAQLPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
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
                                    <td><a href="{{ route('dashboar.dashboardPlanta1') }}">Planta I :</a></td>
                                    <td id="generalProcesoPlanta1">Cargando...</td>
                                </tr>
                                <tr>
                                    <td><a href="{{ route('dashboar.dashboardPlanta2') }}">Planta II :</a></td>
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
                <div id="graficaClientePorDia" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaSupervisorPorDia" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModuloPorDia" style="width:100%; height:400px;"></div>
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
                    url: "{{ route('dashboard.dataDia') }}", // Ajusta la ruta a tu controlador
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
