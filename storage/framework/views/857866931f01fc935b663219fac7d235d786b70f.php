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
                                    <td>Planta I :</td>
                                    <td><?php echo e($generalAQLPlanta1); ?>%</td>
                                </tr>
                                <tr>
                                    <td>Planta II :</td>
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
                                    <td>Planta I :</td>
                                    <td><?php echo e($generalProcesoPlanta1); ?>%</td>
                                </tr>
                                <tr>
                                    <td>Planta II :</td>
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
                            <h2 class="card-title"><a href="<?php echo e(route('dashboar.dashboarAProcesoAQL')); ?>">Intimark Mensual General</a></h2>
                        </div>
                        <div class="col-sm-6">
                            <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
                                <label class="btn btn-sm btn-primary btn-simple active" id="0">
                                    <input type="radio" name="options" checked>
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"><i class="tim-icons icon-app text-success"></i> AQL</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-single-02"></i>
                                    </span>
                                </label>
                                <label class="btn btn-sm btn-primary btn-simple" id="1">
                                    <input type="radio" class="d-none d-sm-none" name="options">
                                    <span class="d-none d-sm-block d-md-block d-lg-block d-xl-block"> <i class="tim-icons icon-vector text-primary"></i> Procesos</span>
                                    <span class="d-block d-sm-none">
                                        <i class="tim-icons icon-gift-2"></i>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartAQL"></canvas>
                        <canvas id="chartProcesos" style="display: none;"></canvas>
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
                            <h2 class="card-title">Errores Mensuales por Cliente</h2>
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 500px;"> <!-- Ajusta esta altura según tus necesidades -->
                        <canvas id="clienteChartAQL"></canvas>
                        <canvas id="clienteChartProcesos" style="display: none;"></canvas>
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
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>

                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLinePurple"></canvas>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> Segundas / Terceras</h3>
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="CountryChart"></canvas>
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
    <style>
        .chart-area {
          height: 500px; /* Ajusta esta altura según tus necesidades */
        }
      </style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('black')); ?>/js/plugins/chartjs.min.js"></script>
<script>
    $(document).ready(function() {
        // Inicializa las gráficas
        var ctxAQL = document.getElementById('chartAQL').getContext('2d');
        var chartAQL = new Chart(ctxAQL, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($fechas); ?>,
                datasets: [{
                    label: 'AQL',
                    data: <?php echo json_encode($porcentajesAQL); ?>,
                    borderColor: '#f96332',
                    backgroundColor: 'rgba(249, 99, 50, 0.4)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false // Ocultar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'DD-MM-YYYY'
                            }
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 45
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value + '%'; // Añadir el símbolo de porcentaje
                            }
                        }
                    }]
                }
            }
        });

        var ctxProcesos = document.getElementById('chartProcesos').getContext('2d');
        var chartProcesos = new Chart(ctxProcesos, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($fechas); ?>,
                datasets: [{
                    label: 'Procesos',
                    data: <?php echo json_encode($porcentajesProceso); ?>,
                    borderColor: '#1f8ef1',
                    backgroundColor: 'rgba(31, 142, 241, 0.4)',
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false // Ocultar la leyenda
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            unit: 'day',
                            tooltipFormat: 'll',
                            displayFormats: {
                                day: 'DD-MM-YYYY'
                            }
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 90,
                            minRotation: 45
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value, index, values) {
                                return value + '%'; // Añadir el símbolo de porcentaje
                            }
                        }
                    }]
                }
            }
        });

        // Manejar el cambio de gráficos
        $('#0').on('click', function() {
            $('#chartAQL').show();
            $('#chartProcesos').hide();
        });

        $('#1').on('click', function() {
            $('#chartAQL').hide();
            $('#chartProcesos').show();
        });
    });
</script>

<script>
    $(document).ready(function() {
      // Lista de colores
      var colores = [
        'rgba(75, 192, 192, 1)',   
        'rgba(153, 102, 255, 1)', 
        'rgba(255, 99, 132, 1)',  
        'rgba(54, 162, 235, 1)',  
        'rgba(255, 206, 86, 1)',  
        'rgba(255, 159, 64, 1)',  
        'rgba(199, 199, 199, 1)', 
        'rgba(255, 99, 255, 1)',  
        'rgba(99, 255, 132, 1)',  
        'rgba(99, 132, 255, 1)',  
        'rgba(132, 99, 255, 1)',  
        'rgba(192, 75, 192, 1)',  
        'rgba(235, 162, 54, 1)',  
        'rgba(86, 255, 206, 1)',  
        'rgba(64, 159, 255, 1)'   
      ];

      // Inicializa las gráficas
      var ctxClienteAQL = document.getElementById('clienteChartAQL').getContext('2d');
      var datasetsAQL = <?php echo json_encode($datasetsAQL, 15, 512) ?>.map((dataset, index) => {
        return {
          ...dataset,
          borderColor: colores[index % colores.length],
          backgroundColor: colores[index % colores.length]
        };
      });
      var chartClienteAQL = new Chart(ctxClienteAQL, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($fechasGrafica, 15, 512) ?>,
          datasets: datasetsAQL
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: true // Mostrar la leyenda
          },
          scales: {
            xAxes: [{
              type: 'time',
              time: {
                unit: 'day',
                tooltipFormat: 'll',
                displayFormats: {
                  day: 'YYYY-MM-DD'
                }
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                  return value + '%'; // Añadir el símbolo de porcentaje
                }
              }
            }]
          }
        }
      });

      var ctxClienteProcesos = document.getElementById('clienteChartProcesos').getContext('2d');
      var datasetsProceso = <?php echo json_encode($datasetsProceso, 15, 512) ?>.map((dataset, index) => {
        return {
          ...dataset,
          borderColor: colores[index % colores.length],
          backgroundColor: colores[index % colores.length]
        };
      });
      var chartClienteProcesos = new Chart(ctxClienteProcesos, {
        type: 'line',
        data: {
          labels: <?php echo json_encode($fechasGrafica, 15, 512) ?>,
          datasets: datasetsProceso
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: {
            display: true // Mostrar la leyenda
          },
          scales: {
            xAxes: [{
              type: 'time',
              time: {
                unit: 'day',
                tooltipFormat: 'll',
                displayFormats: {
                  day: 'YYYY-MM-DD'
                }
              }
            }],
            yAxes: [{
              ticks: {
                beginAtZero: true,
                callback: function(value, index, values) {
                  return value + '%'; // Añadir el símbolo de porcentaje
                }
              }
            }]
          }
        }
      });

      $('#cliente0').on('click', function() {
        $('#clienteChartAQL').show();
        $('#clienteChartProcesos').hide();
        chartClienteAQL.update(); // Asegurarse de que la gráfica se actualice
      });

      $('#cliente1').on('click', function() {
        $('#clienteChartAQL').hide();
        $('#clienteChartProcesos').show();
        chartClienteProcesos.update(); // Asegurarse de que la gráfica se actualice
      });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mac/proyectos-laravel/calidad_testeoxD/tester_calidad/resources/views/dashboard.blade.php ENDPATH**/ ?>