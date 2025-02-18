

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard: COSTO DE LA NO CALIDAD </h2>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Formulario de selección de rango de semanas -->
                            <form action="<?php echo e(route('dashboardCostosNoCalidad')); ?>" method="GET" id="filterForm">
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
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body table-responsive">
                        <table id="tablaCostoSemana" class="table tablesorter">
                            <thead>
                                <tr>
                                    <th># Semana</th>
                                    <th>Paros Proceso</th>
                                    <th>Min Paro Proceso</th>
                                    <th>Costo (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $costoPorSemana; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>SEMANA <?php echo e($dato->semana); ?></td>
                                        <td><?php echo e($dato->paros_proceso); ?></td>
                                        <td><?php echo e($dato->min_paro_proc); ?></td>
                                        <td>$<?php echo e(number_format($dato->costo_usd, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron datos para el rango seleccionado.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><?php echo e($totalParoSemana); ?></th>
                                    <th><?php echo e($totalMinParoSemana); ?></th>
                                    <th class="costo-rojo">$<?php echo e(number_format($totalCostoSemana, 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body ">
                        <!-- Gráfica para $costoPorSemana -->
                        <div id="graficoSemana" style="width:100%; height:400px;"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body table-responsive">
                        <table id="tablaCostoMes" class="table tablesorter" >
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Paros Proceso</th>
                                    <th>Min Paro Proceso</th>
                                    <th>Costo (USD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $costoPorMes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($dato->mes_nombre); ?></td>
                                        <td><?php echo e($dato->paros_proceso); ?></td>
                                        <td><?php echo e($dato->min_paro_proc); ?></td>
                                        <td class="costo-rojo">$<?php echo e(number_format($dato->costo_usd, 2)); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No se encontraron datos para el rango mensual.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><?php echo e($totalParoMes); ?></th>
                                    <th><?php echo e($totalMinParoMes); ?></th>
                                    <th class="costo-rojo">$<?php echo e(number_format($totalCostoMes, 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card card-body ">
                        <!-- Gráfica para $costoPorMes -->
                        <div id="graficoMes" style="width:100%; height:500px;"></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Defectos Únicos por Cliente</h3>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php $__currentLoopData = $costoPorSemanaClientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: <?php echo e($cliente); ?></h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="tablaDefectosCliente_<?php echo e($loop->index); ?>" class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th>Defecto Único</th>
                                                <th>Conteo</th>
                                                <th>Porcentaje (%)</th>
                                                <th>Porcentaje Acumulado (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data['defectos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $defecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr class="<?php echo e(count($data['defectos']) > 7 && $index < 4 ? 'amarillo-indicador' : ''); ?>">
                                                    <td><?php echo e($defecto['defecto_unico']); ?></td>
                                                    <td><?php echo e($defecto['conteo']); ?></td>
                                                    <td><?php echo e(number_format($defecto['porcentaje'], 2)); ?>%</td>
                                                    <td><?php echo e(number_format($defecto['porcentaje_acumulado'], 2)); ?>%</td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total</th>
                                                <th><?php echo e($data['total_conteo']); ?></th>
                                                <th>100%</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <div class="card">
                                <!-- Contenedor para la gráfica de cada cliente -->
                                <div id="graficoCliente_<?php echo e($loop->index); ?>" style="width:100%; height:500px;"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modulos unicos por cliente</h3>
                </div>
            </div>
            <div class="card-body">
                <!-- Mostrar el gran total fuera del foreach -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5>Gran Total Minutos Paro Proceso: <?php echo e($granTotalMinutosParo); ?></h5>
                    </div>
                </div>
            
                <div class="row">
                    <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-6 col-md-12 mb-4">
                            <!-- Card individual para cada cliente -->
                            <div class="card">
                                <div class="card-header">
                                    <h4>Cliente: <?php echo e($cliente); ?></h4>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="tablaDefectosClienteModulo_<?php echo e($loop->index); ?>" class="table tablesorter">
                                        <thead>
                                            <tr>
                                                <th>Módulo Único</th>
                                                <th>Minutos Paro Proceso</th>
                                                <th>Porcentaje (%)</th>
                                                <th>Estilos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $data['modulos']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($modulo['modulo']); ?></td>
                                                    <td><?php echo e($modulo['minutos_paro_proceso']); ?></td>
                                                    <td><?php echo e($modulo['porcentaje']); ?>%</td>
                                                    <td><?php echo e($modulo['estilos']); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total Módulos</th>
                                                <th><?php echo e($data['total_modulos']); ?></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Total Minutos Paro Proceso</th>
                                                <th><?php echo e($data['total_minutos_paro']); ?></th>
                                                <th>100%</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Porcentaje respecto Gran Total</th>
                                                <th><?php echo e($data['porcentaje_entre_gran_total_cliente']); ?>%</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
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
            $('#tablaCostoSemana').DataTable({
                destroy: true,          // Evita el error de reinitialización
                paging: true,
                searching: true,
                ordering: true,
                lengthChange: false,
                pageLength: 10
            });

            $('#tablaCostoMes').DataTable({
                destroy: true,          // Evita el error de reinitialización
                paging: true,
                searching: true,
                ordering: true,
                lengthChange: false,
                pageLength: 10
            });
            // Inicializa DataTables en cada tabla de defectos por cliente
            <?php $__currentLoopData = $costoPorSemanaClientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#tablaDefectosCliente_<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,
                    paging: true,
                    searching: true,
                    ordering: true,
                    order: [[1, 'desc']],  // Ordena por defecto en la segunda columna, descendente
                    lengthChange: false,
                    pageLength: 10,
                    drawCallback: function () {
                        var totalRows = this.api().rows().count(); // Total de registros en la tabla
                        if (totalRows > 7) { // Solo aplica la lógica si hay más de 7 registros
                            // Recorre las primeras 4 filas visibles en la página actual
                            this.api().rows({ page: 'current' }).every(function (rowIdx) {
                                if (rowIdx < 4) { // Solo aplica a las primeras 4 filas
                                    $(this.node()).addClass('amarillo-indicador');
                                }
                            });
                        }
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            // Inicializa DataTables en cada tabla de defectos por cliente-Modulo
            <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#tablaDefectosClienteModulo_<?php echo e($loop->index); ?>').DataTable({
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

    <!-- Highcharts JavaScript -->
    <script src="<?php echo e(asset('js/highcharts/highcharts.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/highcharts-3d.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/exporting.js')); ?>"></script>
    <script src="<?php echo e(asset('js/highcharts/dark-unica.js')); ?>"></script>

    <!-- Configuración de Highcharts -->
    <script>
        // Configuración global de Highcharts para la fuente
        Highcharts.setOptions({
            chart: {
                style: {
                    fontFamily: 'Inter, sans-serif'
                }
            }
        });

        const datosSemana = <?php echo json_encode($costoPorSemana, 15, 512) ?>;
        const datosMes = <?php echo json_encode($costoPorMes, 15, 512) ?>;

        // Gráfico de Costo y Minutos de Paro por Semana
        const semanas = datosSemana.map(d => `SEMANA ${d.semana}`);
        const minutosParoSemana = datosSemana.map(d => d.min_paro_proc);
        const costoSemana = datosSemana.map(d => d.costo_usd);
    
        Highcharts.chart('graficoSemana', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: 'Costo y Minutos de Paro por Semana' },
            xAxis: { categories: semanas, title: { text: 'Semana' }},
            yAxis: [{
                title: { text: 'Minutos Paro Proceso (MPP)', style: { color: '#4aa5d6' }},
                labels: { format: '{value}', style: { color: '#4aa5d6' }}
            }, {
                title: { text: 'Costo (USD)', style: { color: '#8B0000' }},
                labels: { format: '${value}', style: { color: '#8B0000' }},
                opposite: true
            }],
            series: [
                { name: 'Minutos Paro Proceso (MPP)', data: minutosParoSemana, color: '#4aa5d6', lineWidth: 3, yAxis: 0 },
                { name: 'Costo (USD)', data: costoSemana, color: '#8B0000', lineWidth: 6, yAxis: 1 }
            ]
        });

        // Gráfico de Costo y Minutos de Paro por Mes
        const meses = datosMes.map(d => d.mes_nombre);
        const minutosParoMes = datosMes.map(d => d.min_paro_proc);
        const costoMes = datosMes.map(d => d.costo_usd);
    
        Highcharts.chart('graficoMes', {
            chart: { type: 'line', backgroundColor: 'transparent' },
            title: { text: 'Costo y Minutos de Paro por Mes' },
            xAxis: { categories: meses, title: { text: 'Mes' }},
            yAxis: [{
                title: { text: 'Minutos Paro Proceso (MPP)', style: { color: '#4aa5d6' }},
                labels: { format: '{value}', style: { color: '#4aa5d6' }}
            }, {
                title: { text: 'Costo (USD)', style: { color: '#8B0000' }},
                labels: { format: '${value}', style: { color: '#8B0000' }},
                opposite: true
            }],
            series: [
                { name: 'Minutos Paro Proceso (MPP)', data: minutosParoMes, color: '#4aa5d6', lineWidth: 3, yAxis: 0 },
                { name: 'Costo (USD)', data: costoMes, color: '#8B0000', lineWidth: 6, yAxis: 1 }
            ]
        });

        // Gráficos de cada cliente
        document.addEventListener("DOMContentLoaded", function() {
            <?php $__currentLoopData = $costoPorSemanaClientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                Highcharts.chart('graficoCliente_<?php echo e($loop->index); ?>', {
                    chart: { type: 'line', backgroundColor: 'transparent' },
                    title: { text: 'Defectos y Porcentaje Pareto - Cliente: <?php echo e(json_encode($index)); ?>' },
                    xAxis: {
                        categories: <?php echo json_encode($data['defectos']->pluck('defecto_unico')->toArray()); ?>,
                        title: { text: 'Defecto Único' }
                    },
                    yAxis: [{
                        title: { text: 'Cantidad' }
                    }, {
                        title: { text: 'Porcentaje Acumulado (%)' },
                        opposite: true
                    }],
                    series: [
                        { type: 'column', name: 'Conteo', data: <?php echo json_encode($data['defectos']->pluck('conteo')->toArray()); ?>, color: '#4aa5d6' },
                        { type: 'line', name: 'Porcentaje Acumulado (%)', data: <?php echo json_encode($data['defectos']->pluck('porcentaje_acumulado')->toArray()); ?>, color: '#8B0000', yAxis: 1 }
                    ]
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboardCostosNoCalidad', 'titlePage' => __('Dashboard Costos No Calidad')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboar\dashboardCostosNoCalidad.blade.php ENDPATH**/ ?>