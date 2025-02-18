

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard: COMPARATIVO CLIENTES </h2>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form action="<?php echo e(route('dashboarComparativaModulo.planta1PorSemana')); ?>" method="GET" id="filterForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_inicio">Semana inicio</label>
                                            <input type="week" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo e($fechaInicio->format('Y-\WW')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fecha_fin">Semana fin</label>
                                            <input type="week" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php echo e($fechaFin->format('Y-\WW')); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-secondary">Mostrar datos</button> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: <?php echo e($cliente); ?></h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="tablaClienteModulo<?php echo e($loop->index); ?>" class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">Módulo</th>
                                                <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th colspan="2" class="text-center">
                                                        Semana <?php echo e($semana['inicio']->format('W')); ?> <br> (<?php echo e($semana['inicio']->format('Y')); ?>)
                                                    </th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                            <tr>
                                                <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th>% Proceso</th>
                                                    <th>% AQL</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($modulo['modulo']); ?></td>
                                                    <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td><?php echo e($porcentajes['aql']); ?></td>
                                                        <td><?php echo e($porcentajes['proceso']); ?></td>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                <?php $__currentLoopData = $totalesPorCliente[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <td><?php echo e($totales['aql']); ?></td>
                                                    <td><?php echo e($totales['proceso']); ?></td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-4">
                            <div class="row">
                                <!-- Contenedor para la tabla reducida -->
                                <div class="col-lg-3">
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5>Resumen por Semana</h5>
                                        </div>
                                        <div class="card-body table-responsive">
                                            <table id="tablaResumenCliente<?php echo e($loop->index); ?>" class="table tablesorter">
                                                <thead>
                                                    <tr>
                                                        <th>Semana</th>
                                                        <th>% AQL</th>
                                                        <th>% Proceso</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td>Semana <?php echo e($semana['inicio']->format('W')); ?> <br> (<?php echo e($semana['inicio']->format('Y')); ?>)</td>
                                                            <td><?php echo e($totalesPorCliente[$cliente][$key]['aql']); ?></td>
                                                            <td><?php echo e($totalesPorCliente[$cliente][$key]['proceso']); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>                                
                                <!-- Contenedor para la gráfica -->
                                <div class="col-lg-9">
                                    <div class="card">
                                        <div id="graficoCliente_<?php echo e($loop->index); ?>" style="width:100%; height:500px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>                
            </div>
        </div>
    </div>

    <style>
        .costo-rojo {
            color: red;
        }
        .amarillo-indicador {
            background-color: #887404 !important; /* Color amarillo oscuro */
        }
    </style>
    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?> 
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- Inicialización de DataTables -->
    <script>
        $(document).ready(function () {

            // Inicializa DataTables en cada tabla de defectos por cliente-Modulo
            <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#tablaClienteModulo<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10          // Número de registros por página
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>
    <script>
        $(document).ready(function () {
            // Inicializa DataTables en cada tabla resumen por cliente
            <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#tablaResumenCliente<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>

    <!-- Highcharts JavaScript -->
    <script src="<?php echo e(asset('js/highcharts/highcharts.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/highcharts-3d.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/exporting.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/dark-unica.js')); ?>"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                // Crear datos para las series
                const semanas_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        "Semana <?php echo e($semana['inicio']->format('W')); ?> - <?php echo e($semana['inicio']->format('Y')); ?>",
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const aql_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorCliente[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['aql'] === 'N/A' ? 'null' : $totales['aql']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const proceso_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorCliente[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['proceso'] === 'N/A' ? 'null' : $totales['proceso']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                // Calcular rango dinámico para el eje Y
                const allData_<?php echo e($loop->index); ?> = aql_<?php echo e($loop->index); ?>.concat(proceso_<?php echo e($loop->index); ?>).filter(v => v !== null);
                const maxY_<?php echo e($loop->index); ?> = Math.ceil(Math.max(...allData_<?php echo e($loop->index); ?>)) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoCliente_<?php echo e($loop->index); ?>", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: <?php echo e($cliente); ?>"
                    },
                    xAxis: {
                        categories: semanas_<?php echo e($loop->index); ?>,
                        title: {
                            text: "Semanas"
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)"
                        },
                        min: 0,
                        max: maxY_<?php echo e($loop->index); ?>, // Máximo dinámico
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_<?php echo e($loop->index); ?>,
                            color: "#007bff", // Color azul
                            zIndex: 2, // Mayor zIndex para sobreponerse a las barras
                            marker: {
                                enabled: true, // Mostrar puntos en la línea
                                radius: 4
                            }
                        },
                        {
                            name: "% Proceso",
                            type: 'column', // Barras para Proceso
                            data: proceso_<?php echo e($loop->index); ?>,
                            color: "#28a745", // Color verde
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%"
                    },
                    credits: {
                        enabled: false
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboardComparativoCliente', 'titlePage' => __('Dashboard Comparativo clientes')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboarComparativaModulo\reemplzado.blade.php ENDPATH**/ ?>