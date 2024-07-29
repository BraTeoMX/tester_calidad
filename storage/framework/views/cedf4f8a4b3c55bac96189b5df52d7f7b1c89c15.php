

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
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Procesos</h3>
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
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">
                                <a href="<?php echo e(route('dashboar.dashboarAProcesoAQL')); ?>">Intimark Mensual General</a>
                            </h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-success btn-simple active" id="btnAQL">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i> AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-info btn-simple" id="btnProcesos">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-vector text-primary"></i> Procesos</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;">
                        <div id="chartAQLContainer"></div>
                        <div id="chartProcesosContainer" style="display: none;"></div>
                    </div>
                </div>
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
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="cliente1">
                                    <input type="radio" class="d-none d-sm-none" name="clienteOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAll">
                                    <input type="checkbox" name="toggleAllOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Mostrar/Ocultar Todo</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-bullet-list-67"></i>
                                    </span>
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
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="modulo1">
                                    <input type="radio" class="d-none d-sm-none" name="moduloOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="toggleAllModulos">
                                    <input type="checkbox" name="toggleAllModulosOptions">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Mostrar/Ocultar Todo</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-bullet-list-67"></i>
                                    </span>
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
                                <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block">Procesos</span>
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


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h2 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> Segundas / Terceras</h2>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="SegundasTerceras"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-send text-info"></i> Incidencias</h3>
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
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
                                    <th>% Error Proceso</th>
                                    <th>% Error AQL</th>
                                    <!-- Aquí puedes agregar más encabezados si es necesario -->
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $dataGeneral['dataCliente']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $clienteData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr class="<?php echo e($clienteData['porcentajeErrorProceso'] > 9 && $clienteData['porcentajeErrorProceso'] <= 15 ? 'error-bajo' : ($clienteData['porcentajeErrorProceso'] > 15 ? 'error-alto' : '')); ?>">
                                <td><?php echo e($clienteData['cliente']); ?></td>
                                <td><?php echo e(number_format($clienteData['porcentajeErrorProceso'], 2)); ?>%</td>
                                <td><?php echo e(number_format($clienteData['porcentajeErrorAQL'], 2)); ?>%</td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tr style="background: #1d1c1c;">
                              <td>GENERAL</td>
                              <td><?php echo e(number_format($totalGeneral['totalPorcentajeErrorProceso'], 2)); ?>%</td>
                              <td><?php echo e(number_format($totalGeneral['totalPorcentajeErrorAQL'], 2)); ?>%</td>
                            </tr>
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
                        <table class="table tablesorter" id="">
                            <thead class="text-primary">
                                <tr>
                                    <th>Gerentes Produccion</th>
                                    <th>% Error AQL</th>
                                    <th>% Error Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataGerentesGeneral; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['team_leader']); ?></td>
                                        <td><?php echo e($item['porcentaje_error_aql'] !== null ? number_format($item['porcentaje_error_aql'], 2) . '%' : 'N/A'); ?></td>
                                        <td><?php echo e($item['porcentaje_error_proceso'] !== null ? number_format($item['porcentaje_error_proceso'], 2) . '%' : 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>Modulo</th>
                                    <th>% Error AQL</th>
                                    <th>% Error Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataModulosGeneral; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['modulo']); ?></td>
                                        <td><?php echo e($item['porcentaje_error_aql'] !== null ? number_format($item['porcentaje_error_aql'], 2) . '%' : 'N/A'); ?></td>
                                        <td><?php echo e($item['porcentaje_error_proceso'] !== null ? number_format($item['porcentaje_error_proceso'], 2) . '%' : 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (AQL)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>% Error AQL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataModuloAQLGeneral; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['modulo']); ?></td>
                                        <td><?php echo e($item['conteoOperario']); ?></td>
                                        <td><?php echo e($item['conteoMinutos']); ?></td>
                                        <td><?php echo e($item['sumaMinutos']); ?></td>
                                        <td><?php echo e($item['promedioMinutosEntero']); ?></td>
                                        <td><?php echo e($item['conteParoModular']); ?></td>
                                        <td><?php echo e(number_format($item['porcentaje_error_aql'], 2)); ?>%</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (Proceso)</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>% Error Proceso</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dataModuloProcesoGeneral; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($item['modulo']); ?></td>
                                        <td><?php echo e($item['conteoOperario']); ?></td>
                                        <td><?php echo e($item['conteoUtility']); ?></td>
                                        <td><?php echo e($item['conteoMinutos']); ?></td>
                                        <td><?php echo e($item['sumaMinutos']); ?></td>
                                        <td><?php echo e($item['promedioMinutosEntero']); ?></td>
                                        <td><?php echo e(number_format($item['porcentaje_error_proceso'], 2)); ?>%</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-zoom-split text-success"></i> Seleccion de Cliente por Modulo</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('dashboar.detalleXModulo')); ?>" method="GET">
                        <div class="form-group">
                            <label for="clienteBusqueda">Seleccione un cliente:</label>
                            <select class="form-control" name="clienteBusqueda" id="clienteBusqueda" required>
                                <option value="">Seleccione un cliente</option>
                                <?php $__currentLoopData = $clientesUnicosArrayBusqueda; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cliente); ?>"><?php echo e($cliente); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"></h3>
                </div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>


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
    <script src="<?php echo e(asset('js/highcharts/dark-unica.js')); ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechas = <?php echo json_encode($fechas, 15, 512) ?>;
            const porcentajesAQL = <?php echo json_encode($porcentajesAQL, 15, 512) ?>;
            const porcentajesProceso = <?php echo json_encode($porcentajesProceso, 15, 512) ?>;

            // Función para convertir los datos y manejar valores nulos o cero
            function prepareData(data) {
                return data.map(value => value === null ? null : parseFloat(value));
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'areaspline',
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                title: {
                    text: null
                },
                xAxis: {
                    categories: fechas,
                    tickmarkPlacement: 'on',
                    title: { enabled: false },
                    labels: { style: { color: '#ffffff' } }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5,
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartAQL = Highcharts.chart('chartAQLContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'AQL',
                    data: prepareData(porcentajesAQL),
                    color: '#00F0BA', // Color de la línea y el fondo de la línea
                    showInLegend: false // Ocultar nombre en la leyenda
                }]
            }));

            // Gráfica Procesos
            const chartProcesos = Highcharts.chart('chartProcesosContainer', Highcharts.merge(commonOptions, {
                series: [{
                    name: 'Procesos',
                    data: prepareData(porcentajesProceso),
                    color: '#E146A1', // Color de la línea y el fondo de la línea
                    showInLegend: false // Ocultar nombre en la leyenda
                }]
            }));

            // Funcionalidad de los botones
            document.getElementById('btnAQL').addEventListener('click', function() {
                document.getElementById('chartAQLContainer').style.display = 'block';
                document.getElementById('chartProcesosContainer').style.display = 'none';
                chartAQL.reflow();
            });

            document.getElementById('btnProcesos').addEventListener('click', function() {
                document.getElementById('chartAQLContainer').style.display = 'none';
                document.getElementById('chartProcesosContainer').style.display = 'block';
                chartProcesos.reflow();
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartAQL.reflow();
                chartProcesos.reflow();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechasGrafica = <?php echo json_encode($fechasGrafica, 15, 512) ?>;
            const datasetsAQL = <?php echo json_encode($datasetsAQL, 15, 512) ?>;
            const datasetsProceso = <?php echo json_encode($datasetsProceso, 15, 512) ?>;

            // Lista de colores
            const colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#40AFFF'
            ];

            // Función para preparar datasets para Highcharts
            function prepareDatasets(datasets) {
                return datasets.map((dataset, index) => {
                    return {
                        name: dataset.label,
                        data: dataset.data.map((value, i) => [new Date(fechasGrafica[i]).getTime(), parseFloat(value)]),
                        color: colores[index % colores.length],
                        //showInLegend: false // Ocultar nombre en la leyenda
                    };
                });
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'spline', // Cambiado a 'spline' para curvas suaves
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                // Eliminar el título de la gráfica
                title: {
                    text: null
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        style: { color: '#ffffff' }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    spline: { // Opciones específicas para 'spline'
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartClienteAQL = Highcharts.chart('clienteChartAQL', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsAQL)
            }));

            // Gráfica Procesos
            const chartClienteProcesos = Highcharts.chart('clienteChartProcesos', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsProceso)
            }));

            // Funcionalidad de los botones
            document.getElementById('cliente0').addEventListener('click', function() {
                document.getElementById('clienteChartAQL').style.display = 'block';
                document.getElementById('clienteChartProcesos').style.display = 'none';
                chartClienteAQL.reflow();
            });

            document.getElementById('cliente1').addEventListener('click', function() {
                document.getElementById('clienteChartAQL').style.display = 'none';
                document.getElementById('clienteChartProcesos').style.display = 'block';
                chartClienteProcesos.reflow();
            });

            document.getElementById('toggleAll').addEventListener('click', function() {
                const showAll = document.getElementById('toggleAll').querySelector('input').checked;
                const toggleVisibility = function(chart) {
                    chart.series.forEach(function(series) {
                        series.setVisible(showAll, false);
                    });
                    chart.redraw();
                };

                toggleVisibility(chartClienteAQL);
                toggleVisibility(chartClienteProcesos);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartClienteAQL.reflow();
                chartClienteProcesos.reflow();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Datos para las gráficas
            const fechasGraficaModulos = <?php echo json_encode($fechasGraficaModulos, 15, 512) ?>;
            const datasetsAQLModulos = <?php echo json_encode($datasetsAQLModulos, 15, 512) ?>;
            const datasetsProcesoModulos = <?php echo json_encode($datasetsProcesoModulos, 15, 512) ?>;

            // Lista de colores
            const colores = [
                '#4BC0C0', '#9966FF', '#FF6384', '#36A2EB', '#FFCE56',
                '#FF9F40', '#C7C7C7', '#FF63FF', '#63FF84', '#6384FF',
                '#8463FF', '#C04BC0', '#EBA236', '#56FFCE', '#40AFFF'
            ];

            // Función para preparar datasets para Highcharts
            function prepareDatasets(datasets) {
                return datasets.map((dataset, index) => {
                    return {
                        name: dataset.label,
                        data: dataset.data.map((value, i) => [new Date(fechasGraficaModulos[i]).getTime(), parseFloat(value)]),
                        color: colores[index % colores.length],
                        //showInLegend: false // Ocultar nombre en la leyenda
                    };
                });
            }

            // Configuración común para ambas gráficas
            const commonOptions = {
                chart: {
                    type: 'spline', // Cambiado a 'spline' para curvas suaves
                    backgroundColor: '#27293D',
                    events: {
                        load: function() {
                            this.reflow();
                        }
                    }
                },
                // Eliminar el título de la gráfica
                title: {
                    text: null
                },
                xAxis: {
                    type: 'datetime',
                    labels: {
                        style: { color: '#ffffff' }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Porcentaje',
                        style: { color: '#ffffff' }
                    },
                    labels: {
                        formatter: function () {
                            return this.value + '%';
                        },
                        style: { color: '#ffffff' }
                    },
                    gridLineColor: '#707073'
                },
                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>',
                    valueDecimals: 2
                },
                plotOptions: {
                    spline: { // Opciones específicas para 'spline'
                        marker: {
                            radius: 2
                        },
                        lineWidth: 1,
                        states: {
                            hover: {
                                lineWidth: 1
                            }
                        },
                        threshold: null
                    }
                },
                legend: {
                    itemStyle: { color: '#ffffff' }
                }
            };

            // Gráfica AQL
            const chartModuloAQL = Highcharts.chart('moduloChartAQL', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsAQLModulos)
            }));

            // Gráfica Procesos
            const chartModuloProcesos = Highcharts.chart('moduloChartProcesos', Highcharts.merge(commonOptions, {
                series: prepareDatasets(datasetsProcesoModulos)
            }));

            // Funcionalidad de los botones
            document.getElementById('modulo0').addEventListener('click', function() {
                document.getElementById('moduloChartAQL').style.display = 'block';
                document.getElementById('moduloChartProcesos').style.display = 'none';
                chartModuloAQL.reflow();
            });

            document.getElementById('modulo1').addEventListener('click', function() {
                document.getElementById('moduloChartAQL').style.display = 'none';
                document.getElementById('moduloChartProcesos').style.display = 'block';
                chartModuloProcesos.reflow();
            });

            document.getElementById('toggleAllModulos').addEventListener('click', function() {
                const showAll = document.getElementById('toggleAllModulos').querySelector('input').checked;
                const toggleVisibility = function(chart) {
                    chart.series.forEach(function(series) {
                        series.setVisible(showAll, false);
                    });
                    chart.redraw();
                };

                toggleVisibility(chartModuloAQL);
                toggleVisibility(chartModuloProcesos);
            });

            // Ajuste responsivo
            window.addEventListener('resize', function() {
                chartModuloAQL.reflow();
                chartModuloProcesos.reflow();
            });
        });
    </script>
    <!-- nothing-->
    <!-- nothing-->
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
$(document).ready(function() {
    $.ajax({
        url: '/segundas-terceras',
        type: 'GET',
        success: function(data) {
            // Procesar los datos para obtener los valores para el gráfico
            let categories = [];
            let segundasData = [];
            let tercerasData = [];

            data.forEach(item => {
                categories.push(item.month);
                segundasData.push(item.segundas);
                tercerasData.push(item.terceras);
            });

            // Crear el gráfico con Highcharts
            Highcharts.chart('SegundasTerceras', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Segundas y Terceras por Mes'
                },
                xAxis: {
                    categories: categories
                },
                yAxis: {
                    title: {
                        text: 'Cantidad'
                    }
                },
                series: [{
                    name: 'Segundas',
                    data: segundasData
                }, {
                    name: 'Terceras',
                    data: tercerasData
                }]
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\tester_calidad\resources\views/dashboard.blade.php ENDPATH**/ ?>