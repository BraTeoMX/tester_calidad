

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h2 class="card-title text-center font-weight-bold">Dashboard: COMPARATIVO CLIENTES</h2>
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

            <div class="card">
                <div class="card-header card-header-primary">
                    <!-- Tabs para los clientes -->
                    <ul class="nav nav-tabs" id="clienteTabs" role="tablist">
                        <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>" id="tab-<?php echo e($loop->index); ?>" data-toggle="tab" href="#cliente-<?php echo e($loop->index); ?>" role="tab" aria-controls="cliente-<?php echo e($loop->index); ?>" aria-selected="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                                <?php echo e($cliente); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="clienteTabContent">
                <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>" id="cliente-<?php echo e($loop->index); ?>" role="tabpanel" aria-labelledby="tab-<?php echo e($loop->index); ?>">
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4>Cliente: <?php echo e($cliente); ?></h4>
                        </div>
                
                        <!-- Subpestañas para las secciones General, Planta 1 y Planta 2 -->
                        <ul class="nav nav-pills mb-3" id="pills-tab-<?php echo e($loop->index); ?>" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="general-tab-<?php echo e($loop->index); ?>" data-toggle="pill" href="#general-<?php echo e($loop->index); ?>" role="tab" aria-controls="general-<?php echo e($loop->index); ?>" aria-selected="true">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="planta1-tab-<?php echo e($loop->index); ?>" data-toggle="pill" href="#planta1-<?php echo e($loop->index); ?>" role="tab" aria-controls="planta1-<?php echo e($loop->index); ?>" aria-selected="false">Planta 1 - Ixtlahuaca</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="planta2-tab-<?php echo e($loop->index); ?>" data-toggle="pill" href="#planta2-<?php echo e($loop->index); ?>" role="tab" aria-controls="planta2-<?php echo e($loop->index); ?>" aria-selected="false">Planta 2 - San Bartolo</a>
                            </li>
                        </ul>
                
                        <div class="tab-content" id="pills-tabContent-<?php echo e($loop->index); ?>">
                            <!-- Sección General -->
                            <div class="tab-pane fade show active" id="general-<?php echo e($loop->index); ?>" role="tabpanel" aria-labelledby="general-tab-<?php echo e($loop->index); ?>">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana General -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
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
                                                            <td class="<?php echo e($totalesPorCliente[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorCliente[$cliente][$key]['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totalesPorCliente[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorCliente[$cliente][$key]['proceso']); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoCliente_<?php echo e($loop->index); ?>" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla General -->
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $modulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($modulo['modulo']); ?></td>
                                                <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                <?php $__currentLoopData = $totalesPorCliente[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos -->
                                <?php if(isset($modulosPorClienteYEstilo[$cliente])): ?>
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    <?php $__currentLoopData = $modulosPorClienteYEstilo[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo => $modulosEstilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: <?php echo e($estilo); ?></h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $modulosEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($modulo['modulo']); ?></td>
                                                            <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            <?php $__currentLoopData = $totalesPorClienteYEstilo[$cliente][$estilo]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                
                            <!-- Sección Planta 1 -->
                            <div class="tab-pane fade" id="planta1-<?php echo e($loop->index); ?>" role="tabpanel" aria-labelledby="planta1-tab-<?php echo e($loop->index); ?>">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana Planta 1 -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table id="tablaResumenClientePlanta1<?php echo e($loop->index); ?>" class="table tablesorter">
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
                                                            <td class="<?php echo e($totalesPorClientePlanta1[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorClientePlanta1[$cliente][$key]['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totalesPorClientePlanta1[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorClientePlanta1[$cliente][$key]['proceso']); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoClientePlanta1_<?php echo e($loop->index); ?>" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla Planta 1 -->
                                    <table id="tablaClienteModuloPlanta1<?php echo e($loop->index); ?>" class="table tablesorter">
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $modulosPorClientePlanta1[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($modulo['modulo']); ?></td>
                                                <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                <?php $__currentLoopData = $totalesPorClientePlanta1[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos Planta 1 -->
                                <?php if(isset($modulosPorClienteYEstiloPlanta1[$cliente])): ?>
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    <?php $__currentLoopData = $modulosPorClienteYEstiloPlanta1[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo => $modulosEstilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: <?php echo e($estilo); ?></h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $modulosEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($modulo['modulo']); ?></td>
                                                            <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            <?php $__currentLoopData = $totalesPorClienteYEstiloPlanta1[$cliente][$estilo]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>                                                
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                
                            <!-- Sección Planta 2 -->
                            <div class="tab-pane fade" id="planta2-<?php echo e($loop->index); ?>" role="tabpanel" aria-labelledby="planta2-tab-<?php echo e($loop->index); ?>">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <!-- Resumen por Semana Planta 2 -->
                                        <div class="card mt-4">
                                            <div class="card-header">
                                                <h5>Resumen por Semana</h5>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table id="tablaResumenClientePlanta2<?php echo e($loop->index); ?>" class="table tablesorter">
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
                                                            <td class="<?php echo e($totalesPorClientePlanta2[$cliente][$key]['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorClientePlanta2[$cliente][$key]['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totalesPorClientePlanta2[$cliente][$key]['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totalesPorClientePlanta2[$cliente][$key]['proceso']); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-9">
                                        <div class="card">
                                            <div id="graficoClientePlanta2_<?php echo e($loop->index); ?>" style="width:100%; height:500px;"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                    <!-- Tabla Planta 2 -->
                                    <table id="tablaClienteModuloPlanta2<?php echo e($loop->index); ?>" class="table tablesorter">
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
                                                <th>% AQL</th>
                                                <th>% Proceso</th>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $modulosPorClientePlanta2[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($modulo['modulo']); ?></td>
                                                <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($porcentajes['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td><strong>Totales</strong></td>
                                                <?php $__currentLoopData = $totalesPorClientePlanta2[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['aql']); ?>

                                                </td>
                                                <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                    <?php echo e($totales['proceso']); ?>

                                                </td>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <!-- Datos por Estilos Planta 1 -->
                                <?php if(isset($modulosPorClienteYEstiloPlanta2[$cliente])): ?>
                                <div class="mt-4">
                                    <h5>Desglose por Estilos</h5>
                                    <?php $__currentLoopData = $modulosPorClienteYEstiloPlanta2[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo => $modulosEstilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="card mt-3">
                                            <div class="card-header">
                                                <h6>Estilo: <?php echo e($estilo); ?></h6>
                                            </div>
                                            <div class="card-body table-responsive" style="background-color: #2c2c2c; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2); padding: 15px; border-radius: 8px;">
                                                <table class="table tablesorter">
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
                                                            <th>% AQL</th>
                                                            <th>% Proceso</th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $modulosEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($modulo['modulo']); ?></td>
                                                            <?php $__currentLoopData = $modulo['semanalPorcentajes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $porcentajes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($porcentajes['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($porcentajes['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($porcentajes['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td><strong>Totales</strong></td>
                                                            <?php $__currentLoopData = $totalesPorClienteYEstiloPlanta2[$cliente][$estilo]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td class="<?php echo e($totales['aql_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['aql']); ?>

                                                            </td>
                                                            <td class="<?php echo e($totales['proceso_color'] ? 'bg-rojo-oscuro' : ''); ?>">
                                                                <?php echo e($totales['proceso']); ?>

                                                            </td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>                                                
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>                
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>            
        </div>
    </div>
    <style>
        .bg-rojo-oscuro {
            background-color: #8B0000; /* Rojo oscuro */
            color: white; /* Texto blanco para contraste */
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

            $.fn.dataTable.ext.type.order['custom-num-pre'] = function(a) {
                // Si es "N/A", devolver un valor que lo coloque al final
                if (a === "N/A") return -Infinity;
                
                // Convertir a número flotante
                var x = parseFloat(a);
                
                // Si no es un número válido, devolver -Infinity
                return isNaN(x) ? -Infinity : x;
            };

            $.fn.dataTable.ext.type.order['custom-num-desc'] = function(a, b) {
                return b - a;
            };

            // Inicializa DataTables en cada tabla de defectos por cliente-Modulo
            <?php $__currentLoopData = $modulosPorCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                $('#tablaClienteModulo<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaClienteModuloPlanta1<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaClienteModuloPlanta2<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    responsive: true, // Habilita la respuesta
                    paging: true,
                    searching: true,
                    ordering: true,
                    lengthChange: false,    // Fija la cantidad de elementos a 10 por página
                    pageLength: 10,         // Número de registros por página
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
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
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaResumenClientePlanta1<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
                });

                $('#tablaResumenClientePlanta2<?php echo e($loop->index); ?>').DataTable({
                    destroy: true,          // Evita el error de reinitialización
                    paging: true,          // Sin paginación (si es necesario, cámbialo a true)
                    searching: false,       // Sin búsqueda (opcional)
                    ordering: true,         // Habilita ordenamiento
                    lengthChange: false,    // Fija la cantidad de elementos visibles
                    pageLength: 5,
                    // Configurar columnas específicas para el ordenamiento
                    columnDefs: [
                        {
                            targets: 0,      // La primera columna (índice 0)
                            type: "string"   // Tratarla como texto (caracteres)
                        },
                        {
                            targets: "_all", // Todas las demás columnas numéricas
                            type: "custom-num",  // Usar tipo personalizado
                            render: function(data, type, row) {
                                // Esto ayuda a manejar la presentación de "N/A"
                                return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                            }
                        }
                    ]
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
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: <?php echo e($cliente); ?>",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_<?php echo e($loop->index); ?>,
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_<?php echo e($loop->index); ?>, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_<?php echo e($loop->index); ?>,
                            color: "#28a745", // Color verde
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
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script> 
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php $__currentLoopData = $modulosPorClientePlanta1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                // Crear datos para las series
                const semanas_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        "Semana <?php echo e($semana['inicio']->format('W')); ?> - <?php echo e($semana['inicio']->format('Y')); ?>",
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const aql_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorClientePlanta1[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['aql'] === 'N/A' ? 'null' : $totales['aql']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const proceso_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorClientePlanta1[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['proceso'] === 'N/A' ? 'null' : $totales['proceso']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                // Calcular rango dinámico para el eje Y
                const allData_<?php echo e($loop->index); ?> = aql_<?php echo e($loop->index); ?>.concat(proceso_<?php echo e($loop->index); ?>).filter(v => v !== null);
                const maxY_<?php echo e($loop->index); ?> = Math.ceil(Math.max(...allData_<?php echo e($loop->index); ?>)) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoClientePlanta1_<?php echo e($loop->index); ?>", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: <?php echo e($cliente); ?>",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_<?php echo e($loop->index); ?>,
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_<?php echo e($loop->index); ?>, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_<?php echo e($loop->index); ?>,
                            color: "#28a745", // Color verde
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
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script> 
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            <?php $__currentLoopData = $modulosPorClientePlanta2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $modulos): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                // Crear datos para las series
                const semanas_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $semanas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semana): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        "Semana <?php echo e($semana['inicio']->format('W')); ?> - <?php echo e($semana['inicio']->format('Y')); ?>",
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const aql_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorClientePlanta2[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['aql'] === 'N/A' ? 'null' : $totales['aql']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                const proceso_<?php echo e($loop->index); ?> = [
                    <?php $__currentLoopData = $totalesPorClientePlanta2[$cliente]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $totales): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($totales['proceso'] === 'N/A' ? 'null' : $totales['proceso']); ?>,
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ];
    
                // Calcular rango dinámico para el eje Y
                const allData_<?php echo e($loop->index); ?> = aql_<?php echo e($loop->index); ?>.concat(proceso_<?php echo e($loop->index); ?>).filter(v => v !== null);
                const maxY_<?php echo e($loop->index); ?> = Math.ceil(Math.max(...allData_<?php echo e($loop->index); ?>)) + 5; // Máximo dinámico con un margen de +5
    
                // Inicializar gráfica para cada cliente
                Highcharts.chart("graficoClientePlanta2_<?php echo e($loop->index); ?>", {
                    chart: {
                        type: 'line', // Tipo general para la gráfica
                        backgroundColor: 'transparent', // Fondo transparente
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial
                        }
                    },
                    title: {
                        text: "Porcentajes Semanales - Cliente: <?php echo e($cliente); ?>",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el título
                        }
                    },
                    xAxis: {
                        categories: semanas_<?php echo e($loop->index); ?>,
                        title: {
                            text: "Semanas",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje X
                            }
                        },
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje X
                            }
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Porcentaje (%)",
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para el eje Y 
                            }
                        },
                        min: 0,
                        max: maxY_<?php echo e($loop->index); ?>, // Máximo dinámico
                        labels: {
                            style: {
                                fontFamily: 'Arial' // Tipografía Arial para las etiquetas del eje Y
                            }
                        }
                    },
                    series: [
                        {
                            name: "% AQL",
                            type: 'line', // Línea para AQL
                            data: aql_<?php echo e($loop->index); ?>,
                            color: "#28a745", // Color verde
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
                            color: "#007bff", // Color azul
                            zIndex: 1 // Menor zIndex para estar detrás de la línea
                        }
                    ],
                    tooltip: {
                        shared: true,
                        valueSuffix: "%",
                        style: {
                            fontFamily: 'Arial' // Tipografía Arial para el tooltip
                        }
                    },
                    credits: {
                        enabled: false
                    }
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>  

<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboardComparativoClientes', 'titlePage' => __('Dashboard Comparativo Clientes')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboarComparativaModulo\planta1PorSemanaComparativa.blade.php ENDPATH**/ ?>