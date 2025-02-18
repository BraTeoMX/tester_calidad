

<?php $__env->startSection('content'); ?>

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
                                    <td><?php echo e($generalAQL); ?>%</td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo e(route('dashboar.dashboardPlanta1')); ?>">Planta I :</a></td>
                                    <td><?php echo e($generalAQLPlanta1); ?>%</td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo e(route('dashboar.dashboardPlanta2')); ?>">Planta II :</a></td>
                                    <td><?php echo e($generalAQLPlanta2); ?>%</td>
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
                                    <td><?php echo e($generalProceso); ?>%</td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo e(route('dashboar.dashboardPlanta1')); ?>">Planta I :</a></td>
                                    <td><?php echo e($generalProcesoPlanta1); ?>%</td>
                                </tr>
                                <tr>
                                    <td><a href="<?php echo e(route('dashboar.dashboardPlanta2')); ?>">Planta II :</a></td>
                                    <td><?php echo e($generalProcesoPlanta2); ?>%</td>
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
                                <td><?php echo e(number_format($generalAQL, 2)); ?>%</td>
                                <td><?php echo e(number_format($generalProceso, 2)); ?>%</td>
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
                <div id="graficaClientesSemanal" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaSupervisoresSemanal" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-body">
                <div id="graficaModulosSemanal" style="width:100%; height:400px;"></div>
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
                <div id="graficaMensualGeneral" style="width:100%; height:500px;"></div>
            </div>
        </div>
    </div>

    <!-- Graficas -->
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
                    <div class="chart-area" style="height: 500px;">
                        <div id="clienteChartAQL"></div>
                        <div id="clienteChartProcesos" style="display: none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Gráficas de Módulo -->
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
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> Top 3 (Defectos)</h3>
                    <div class="col-sm-15">
                        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                            <label class="btn btn-sm btn-primary btn-simple active" id="top3-1" onclick="mostrarGrafica('AQL')">
                                <input type="radio" name="clienteOptions" checked>
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-single-02"></i>
                                </span>
                            </label>
                            <label class="btn btn-sm btn-primary btn-simple" id="top3-2" onclick="mostrarGrafica('Procesos')">
                                <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Proceso</span>
                                <span class="d-block d-sm-none">
                                    <i class="tim-icons icon-gift-2"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 400px;">
                    <div class="chart-area">
                        <div id="chartContainer"></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-8">
            <div class="card card-chart">
                <div class="card-body">
                    <div id="SegundasTercerasChart"></div>
                    <div id="spinner" class="spinner"></div>
                </div>
            </div>
        </div>





    </div>

    <style>
  /* Estilo para el spinner */
.spinner {
  border: 4px solid #f3f3f3;
  border-radius: 50%;
  border-top: 4px solid #3498db;
  width: 40px;
  height: 40px;
  animation: spin 2s linear infinite;

  /* Centrar el spinner horizontal y verticalmente */
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

@keyframes  spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Ocultar el spinner inicialmente */
#spinner {
  display: none;
}
      </style>


    <style>
        .chart-area {
          height: 500px; /* Ajusta esta altura según tus necesidades */
        }

        #chartAQLContainer, #chartProcesosContainer, #clienteChartAQL, #clienteChartProcesos, #moduloChartAQL, #moduloChartProcesos{
            width: 100%;
            height: 100%;
        }
      </style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('js/highcharts/highcharts.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/highcharts-3d.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/exporting.js')); ?>"></script>
    <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="<?php echo e(asset('js/highcharts/dark-unica.js')); ?>"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            fetchDataDia();

            function fetchDataDia() {
                $.ajax({
                    url: "<?php echo e(route('dashboard.dataDia')); ?>",
                    type: "GET",
                    success: function (data) {
                        renderTablaClientes(data.clientes);
                        renderTablaSupervisores(data.supervisores);
                        renderTablaModulos(data.modulos);

                        // Llamar funciones para generar las gráficas
                        renderGraficaClientes(data.clientes);
                        renderGraficaSupervisores(data.supervisores);
                        renderGraficaModulos(data.modulos);
                    },
                    error: function () {
                        alert('Error al cargar los datos del día.');
                    }
                });
            }

            // Configuración global de Highcharts
            Highcharts.setOptions({
                chart: {
                    style: {
                        fontFamily: 'Arial, sans-serif' // Tipografía global
                    }
                },
                tooltip: {
                    shared: true, // Tooltip combinado
                    formatter: function () {
                        let tooltip = `<b>${this.x}</b><br/>`;
                        this.points.forEach(point => {
                            tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                        });
                        return tooltip;
                    },
                    backgroundColor: '#000000', // Fondo negro
                    style: { color: '#ffffff' } // Texto blanco
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
                                brightness: 0.1 // Iluminación al pasar el mouse
                            }
                        }
                    }
                }
            });

            // Función para generar gráfica de Clientes
            function renderGraficaClientes(clientes) {
                const categorias = Object.keys(clientes);
                const dataAQL = categorias.map(c => clientes[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => clientes[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaClientePorDia', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Clientes (Día Actual)' },
                    xAxis: {
                        categories: categorias,
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Porcentaje (%)' }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Función para generar gráfica de Supervisores
            function renderGraficaSupervisores(supervisores) {
                const categorias = Object.keys(supervisores);
                const dataAQL = categorias.map(c => supervisores[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => supervisores[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaSupervisorPorDia', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Supervisores (Día Actual)' },
                    xAxis: {
                        categories: categorias,
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Porcentaje (%)' }
                    },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Función para generar gráfica de Módulos
            function renderGraficaModulos(modulos) {
                const categorias = Object.keys(modulos);
                const dataAQL = categorias.map(c => modulos[c]['% AQL'] || 0);
                const dataProceso = categorias.map(c => modulos[c]['% PROCESO'] || 0);

                Highcharts.chart('graficaModuloPorDia', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Módulos (Día Actual)' },
                    xAxis: {
                        categories: categorias,
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: { text: 'Porcentaje (%)' }
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
            fetchDataSemana();

            function fetchDataSemana() {
                $.ajax({
                    url: "<?php echo e(route('dashboard.dataSemana')); ?>",
                    type: "GET",
                    success: function (data) {
                        // Actualizar tablas
                        renderTablaClientesSemanal(data.clientes);
                        renderTablaResponsablesSemanal(data.supervisores);
                        renderTablaModulosSemanal(data.modulos);

                        // Generar gráficas
                        renderGraficaClientesSemanal(data.clientes);
                        renderGraficaSupervisoresSemanal(data.supervisores);
                        renderGraficaModulosSemanal(data.modulos);
                    },
                    error: function () {
                        alert('Error al cargar los datos de la semana.');
                    }
                });
            }

            // Función para actualizar la tabla de Clientes
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
                $('#tablaClientesSemanal tbody').html(html);

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

            // Función para actualizar la tabla de Supervisores
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
                $('#tablaResponsablesSemanal tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            // Función para actualizar la tabla de Módulos
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
                $('#tablaModulosSemanal tbody').html(html);

                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true
                });
            }

            // Función para generar la gráfica de Clientes
            function renderGraficaClientesSemanal(clientes) {
                const categorias = clientes.map(c => c.cliente);
                const dataAQL = clientes.map(c => c['% AQL']);
                const dataProceso = clientes.map(c => c['% PROCESO']);

                Highcharts.chart('graficaClientesSemanal', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Clientes (Semana Actual)' },
                    xAxis: { categories: categorias, crosshair: true },
                    yAxis: { min: 0, title: { text: 'Porcentaje (%)' } },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Función para generar la gráfica de Supervisores
            function renderGraficaSupervisoresSemanal(supervisores) {
                const categorias = supervisores.map(s => s.team_leader);
                const dataAQL = supervisores.map(s => s['% AQL']);
                const dataProceso = supervisores.map(s => s['% PROCESO']);

                Highcharts.chart('graficaSupervisoresSemanal', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Supervisores (Semana Actual)' },
                    xAxis: { categories: categorias, crosshair: true },
                    yAxis: { min: 0, title: { text: 'Porcentaje (%)' } },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }

            // Función para generar la gráfica de Módulos
            function renderGraficaModulosSemanal(modulos) {
                const categorias = modulos.map(m => m.modulo);
                const dataAQL = modulos.map(m => m['% AQL']);
                const dataProceso = modulos.map(m => m['% PROCESO']);

                Highcharts.chart('graficaModulosSemanal', {
                    chart: { type: 'column', backgroundColor: null },
                    title: { text: 'Comparativo AQL y PROCESO - Módulos (Semana Actual)' },
                    xAxis: { categories: categorias, crosshair: true },
                    yAxis: { min: 0, title: { text: 'Porcentaje (%)' } },
                    series: [
                        { name: '% AQL', data: dataAQL, color: '#00f0c1' },
                        { name: '% PROCESO', data: dataProceso, color: '#dd4dc7' }
                    ]
                });
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            fetchMensualGeneral();

            function fetchMensualGeneral() {
                $.ajax({
                    url: "<?php echo e(route('dashboard.mensualGeneral')); ?>",
                    type: "GET",
                    success: function (data) {
                        renderGraficaMensualGeneral(data);
                    },
                    error: function () {
                        alert('Error al cargar los datos mensuales generales.');
                    }
                });
            }

            function renderGraficaMensualGeneral(data) {
                const dias = data.map(item => item.dia); // Eje X: Días del mes
                const dataAQL = data.map(item => item.AQL); // Eje Y: AQL
                const dataProceso = data.map(item => item.PROCESO); // Eje Y: Proceso
                // Obtener el nombre del mes actual
                const fechaHoy = new Date();
                const nombreMes = fechaHoy.toLocaleString('es-ES', { month: 'long' }); // Ejemplo: "diciembre"

                Highcharts.chart('graficaMensualGeneral', {
                    chart: {
                        type: 'areaspline', // Cambio a gráfica de área
                        backgroundColor: null // Fondo transparente
                    },
                    title: {
                        text: 'Indicador mensual general - AQL y PROCESO'
                    },
                    xAxis: {
                        categories: dias, // Eje X: Días del mes
                        crosshair: true,
                        title: { text: `Días del Mes - ${nombreMes}` } // Agrega el nombre del mes
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)' },
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        formatter: function () {
                            let tooltip = `<b>Día ${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        areaspline: {
                            fillOpacity: 0.7, // Nivel de transparencia del relleno (0.3 = 30%)
                            lineWidth: 2, // Grosor de la línea
                            marker: {
                                enabled: false // Ocultar los marcadores en los puntos
                            }
                        }
                    },
                    series: [
                        {
                            name: '% AQL',
                            data: dataAQL,
                            color: '#00f0c1', // Color de la línea y el relleno
                            zIndex: 1 // Asegura que quede al frente
                        },
                        {
                            name: '% PROCESO',
                            data: dataProceso,
                            color: '#dd4dc7', // Color de la línea y el relleno
                            zIndex: 0 // Asegura que quede detrás
                        }
                    ]
                });
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            fetchMensualPorCliente();

            let chartAQL, chartProceso;

            function fetchMensualPorCliente() {
                $.ajax({
                    url: "<?php echo e(route('dashboard.mensualPorCliente')); ?>",
                    type: "GET",
                    success: function (data) {
                        chartAQL = renderGraficaPorCliente(data, 'AQL', 'clienteChartAQL');
                        chartProceso = renderGraficaPorCliente(data, 'PROCESO', 'clienteChartProcesos');

                        // Inicialización de la vista
                        $('#clienteChartAQL').show();
                        $('#clienteChartProcesos').hide();

                        // Botones dinámicos para cambiar de gráfico
                        $('#cliente0').on('click', function () {
                            $('#clienteChartAQL').show();
                            $('#clienteChartProcesos').hide();
                            chartAQL.reflow();
                        });

                        $('#cliente1').on('click', function () {
                            $('#clienteChartAQL').hide();
                            $('#clienteChartProcesos').show();
                            chartProceso.reflow();
                        });
                    },
                    error: function () {
                        alert('Error al cargar los datos mensuales por cliente.');
                    }
                });
            }

            function renderGraficaPorCliente(data, tipo, containerId) {
                const series = [];

                // Configuración de las series con datos
                Object.keys(data).forEach(cliente => {
                    const valores = data[cliente].map(item => item[tipo]);
                    series.push({
                        name: cliente,
                        data: valores,
                        type: 'spline', // Gráfica curva
                        marker: { enabled: false }
                    });
                });

                // Generar la gráfica
                const chart = Highcharts.chart(containerId, {
                    chart: {
                        backgroundColor: null,
                        events: {
                            load: function () {
                                const chart = this;

                                // Crear botón interno en la gráfica
                                chart.renderer.button('Mostrar/Ocultar Todo', 10, 10)
                                    .attr({
                                        zIndex: 3,
                                        fill: '#007bff', // Color del botón
                                        stroke: '#0056b3',
                                        'stroke-width': 1,
                                        padding: 5,
                                        r: 5, // Bordes redondeados
                                        style: {
                                            color: '#ffffff',
                                            cursor: 'pointer'
                                        }
                                    })
                                    .on('click', function () {
                                        // Alternar visibilidad de todas las series
                                        const allVisible = chart.series.every(s => s.visible);
                                        chart.series.forEach(series => {
                                            series.setVisible(!allVisible, false);
                                        });
                                        chart.redraw();
                                    })
                                    .add();
                            }
                        }
                    },
                    title: {
                        text: `Indicador Mensual por Cliente - ${tipo}`
                    },
                    xAxis: {
                        categories: Array.from({ length: data[Object.keys(data)[0]].length }, (_, i) => i + 1),
                        title: { text: 'Días del Mes' }
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)' },
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        formatter: function () {
                            let tooltip = `<b>Día ${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        spline: {
                            lineWidth: 2
                        }
                    },
                    legend: {
                        enabled: true
                    },
                    series: series
                });

                return chart;
            }
        });

    </script>

    <script>
        $(document).ready(function () {
            fetchMensualPorModulo();

            let chartAQLModulo, chartProcesoModulo;

            function fetchMensualPorModulo() {
                $.ajax({
                    url: "<?php echo e(route('dashboard.mensualPorModulo')); ?>",
                    type: "GET",
                    success: function (data) {
                        chartAQLModulo = renderGraficaPorModulo(data, 'AQL', 'moduloChartAQL');
                        chartProcesoModulo = renderGraficaPorModulo(data, 'PROCESO', 'moduloChartProcesos');

                        // Inicialización: mostrar AQL, ocultar Proceso
                        $('#moduloChartAQL').show();
                        $('#moduloChartProcesos').hide();

                        // Botones externos para alternar entre AQL y PROCESO
                        $('#modulo0').on('click', function () {
                            $('#moduloChartAQL').show();
                            $('#moduloChartProcesos').hide();
                            chartAQLModulo.reflow();
                        });

                        $('#modulo1').on('click', function () {
                            $('#moduloChartAQL').hide();
                            $('#moduloChartProcesos').show();
                            chartProcesoModulo.reflow();
                        });
                    },
                    error: function () {
                        alert('Error al cargar los datos mensuales por módulo.');
                    }
                });
            }

            function renderGraficaPorModulo(data, tipo, containerId) {
                const series = [];

                // Preparar los datos
                Object.keys(data).forEach(modulo => {
                    const valores = data[modulo].map(item => item[tipo]);
                    series.push({
                        name: modulo,
                        data: valores,
                        type: 'spline',
                        marker: { enabled: false }
                    });
                });

                // Crear la gráfica
                const chart = Highcharts.chart(containerId, {
                    chart: {
                        backgroundColor: null,
                        events: {
                            load: function () {
                                const chart = this;

                                // Crear botón interno para mostrar/ocultar todas las series
                                chart.renderer.button('Mostrar/Ocultar Todo', 10, 10)
                                    .attr({
                                        zIndex: 3,
                                        fill: '#007bff',
                                        stroke: '#0056b3',
                                        'stroke-width': 1,
                                        padding: 5,
                                        r: 5,
                                        style: {
                                            color: '#ffffff',
                                            cursor: 'pointer'
                                        }
                                    })
                                    .on('click', function () {
                                        const allVisible = chart.series.every(s => s.visible);
                                        chart.series.forEach(series => {
                                            series.setVisible(!allVisible, false);
                                        });
                                        chart.redraw();
                                    })
                                    .add();
                            }
                        }
                    },
                    title: { text: `Indicador Mensual por Módulo - ${tipo}` },
                    xAxis: {
                        categories: Array.from({ length: data[Object.keys(data)[0]].length }, (_, i) => i + 1),
                        title: { text: 'Días del Mes' }
                    },
                    yAxis: {
                        title: { text: 'Porcentaje (%)' },
                        min: 0
                    },
                    tooltip: {
                        shared: true,
                        formatter: function () {
                            let tooltip = `<b>Día ${this.x}</b><br/>`;
                            this.points.forEach(point => {
                                tooltip += `<span style="color:${point.color}">\u25CF</span> ${point.series.name}: <b>${point.y.toFixed(2)}%</b><br/>`;
                            });
                            return tooltip;
                        }
                    },
                    plotOptions: {
                        spline: { lineWidth: 2 }
                    },
                    legend: { enabled: true },
                    series: series
                });

                return chart;
            }
        });

    </script>

    <script>
        const topDefectosAQL = <?php echo json_encode($topDefectosAQL, 15, 512) ?>;
        const topDefectosProceso = <?php echo json_encode($topDefectosProceso, 15, 512) ?>;
        // Lista de colores
        const colores = [
            '#F03C3C', '#F0E23C', '#3C8EF0', '#36A2EB', '#FFCE56',
        ];

        function prepararDatos(datos) {
            const tp = datos.map(d => d.tp);
            const total = datos.map(d => d.total);

            return {
                tp,
                total,
            };
        }

        function crearGrafica(datos, titulo) {
            const { tp, total } = prepararDatos(datos);

            Highcharts.chart('chartContainer', {
                chart: {
                    type: 'column',
                    backgroundColor: '#27293D',
                },
                title: {
                    text: titulo,
                    style: {
                        color: '#FFFFFF'
                    }
                },
                xAxis: {
                    categories: ['Defectos'],
                    title: {
                        style: {
                            color: '#FFFFFF'
                        }
                    },
                    labels: {
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Número de defectos',
                        style: {
                            color: '#FFFFFF'
                        }
                    },
                    labels: {
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF'
                    }
                },
                series: [
                    {
                        name: tp[0],
                        data: [total[0]],
                        color: colores[0],
                    },
                    {
                        name: tp[1],
                        data: [total[1]],
                        color: colores[1],
                    },
                    {
                        name: tp[2],
                        data: [total[2]],
                        color: colores[2],
                    }
                ],
                plotOptions: {
                    column: {
                        colorByPoint: false, // Cambia a false ya que estamos asignando colores manualmente
                        borderColor: '#27293D'
                    }
                }
            });
        }

        function mostrarGrafica(tipo) {
            if (tipo === 'AQL') {
                crearGrafica(topDefectosAQL, 'Top 3 Defectos AQL');
            } else {
                crearGrafica(topDefectosProceso, 'Top 3 Defectos Procesos');
            }
        }

        // Mostrar la gráfica AQL por defecto
        mostrarGrafica('AQL');
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
      // Crear una bandera global para evitar múltiples cargas
      if (window.datosCargados) return; // Detener si ya se ha cargado
      window.datosCargados = true; // Marcar como cargado
  
      // Mostrar el spinner al iniciar la petición
      document.getElementById("spinner").style.display = "block";
  
      fetch("/SegundasTerceras", {
        method: "GET",
        headers: {
          "Content-Type": "application/json"
        }
      })
        .then(response => {
          if (!response.ok) {
            throw new Error("Error en la respuesta de la red");
          }
          return response.json();
        })
        .then(data => {
          let segundas = 0;
          let terceras = 0;
          let totalQty = 0; // Variable para almacenar el total de Total_QTY
  
          // Sumamos las cantidades de Total_QTY
          data.data.forEach(item => {
            let qty = parseFloat(item.Total_QTY); // Asegúrate de que el valor es numérico
            totalQty += qty; // Sumar al total de QTY
            // Sumar para segundas y terceras
            if (item.QUALITY === "1") {
              segundas += qty; // Suma para "Segundas"
            } else if (item.QUALITY === "2") {
              terceras += qty; // Suma para "Terceras"
            }
          });
  
          // Calcular el porcentaje para Segundas y Terceras
          let porcentajeSegundas = ((segundas * 100) / totalQty).toFixed(2);
          let porcentajeTerceras = ((terceras * 100) / totalQty).toFixed(2);
  
          // Generamos la gráfica con los datos
          Highcharts.chart("SegundasTercerasChart", {
            chart: {
              type: "column",
              backgroundColor: "transparent"
            },
            title: {
              text: "Segundas y Terceras"
            },
            xAxis: {
              categories: ["Segundas", "Terceras"]
            },
            yAxis: {
              min: 0,
              title: {
                text: "Cantidad"
              }
            },
            tooltip: {
              shared: true,
              formatter: function () {
                // Tooltip personalizado
                if (this.series.name === "Segundas") {
                  return `
                    <b>Segundas</b><br>
                    <b>Cantidad:</b> ${segundas}<br>
                    <b>Porcentaje:</b> ${porcentajeSegundas}%
                     <br>
                     <br>
                    <b>Terceras</b><br>
                    <b>Cantidad:</b> ${terceras}<br>
                    <b>Porcentaje:</b> ${porcentajeTerceras}%
                  `;
                }
              },
              backgroundColor: "#000000", // Fondo negro
              style: { color: "#ffffff" } // Texto blanco
            },
            series: [
              {
                name: "Segundas",
                id: "segundas",
                data: [segundas],
                color: "#7cb5ec",
                dataLabels: {
                  enabled: true
                },
                events: {
                  click: function (event) {
                    if (this.options.id === "segundas") {
                      window.location.href = "/Segundas";
                    }
                  }
                }
              },
              {
                name: "Terceras",
                id: "terceras",
                data: [terceras],
                color: "#434348",
                dataLabels: {
                  enabled: true
                }
              }
            ],
            legend: {
              enabled: true
            }
          });
  
          // Ocultar el spinner después de que se haya generado la gráfica
          document.getElementById("spinner").style.display = "none";
        })
        .catch(error => {
          console.error("Error al cargar los datos:", error);
          // Ocultar el spinner en caso de error
          document.getElementById("spinner").style.display = "none";
        });
    });
  </script>
  


<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboard.blade.php ENDPATH**/ ?>