

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
            <form action="<?php echo e(route('dashboar.dashboarAProcesoAQL')); ?>" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_fin">Fecha de fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </form>
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Obtener los parámetros de la URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const fechaInicio = urlParams.get('fecha_inicio');
                    const fechaFin = urlParams.get('fecha_fin');

                    // Establecer los valores de los campos de fecha
                    document.getElementById("fecha_inicio").value = fechaInicio || '';
                    document.getElementById("fecha_fin").value = fechaFin || '';

                    // Manejar el evento de envío del formulario
                    document.getElementById("filterForm").addEventListener("submit", function(event) {
                        // Agregar los valores de los campos de fecha a la URL del formulario
                        const fechaInicioValue = document.getElementById("fecha_inicio").value || '';
                        const fechaFinValue = document.getElementById("fecha_fin").value || '';
                        this.action = "<?php echo e(route('dashboar.dashboarAProcesoAQL')); ?>?fecha_inicio=" + fechaInicioValue + "&fecha_fin=" + fechaFinValue;
                    });
                });

            </script>
            <hr>     
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h2 class="card-title">Seleccion por rango General: </h2>
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
                            <h2 class="card-title">Errores por Cliente en seleccion de rango: </h2>
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
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)'
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

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/dashboar/dashboarAProcesoAQL.blade.php ENDPATH**/ ?>