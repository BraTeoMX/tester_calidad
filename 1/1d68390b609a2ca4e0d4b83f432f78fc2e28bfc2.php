

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta por dia Planta 1 - Ixtlahuaca </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="<?php echo e(route('dashboar.dashboardPanta1PorDia')); ?>" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?php echo e($fechaActual->format('Y-m-d')); ?>" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </form>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Auditoria AQL</h3>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Proceso</h3>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Botones de selección -->
    <div class="card">
        <div class="btn-group btn-group-toggle float-right" data-toggle="buttons">
            <label class="btn btn-sm btn-primary btn-simple active" id="showAQL">
                <input type="radio" name="options" checked>
                <h5><i class="tim-icons icon-app text-success"></i>&nbsp; AQL</h5>
            </label>
            <label class="btn btn-sm btn-primary btn-simple" id="showProceso">
                <input type="radio" name="options">
                <h5><i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos</h5>
            </label>
        </div>
        <!-- Tabla de AQL -->
        <div id="tablaAQL" class="table-container" style="display: block;">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Turno Normal</h3> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($datosModuloEstiloAQL): ?>
                            <table class="table tablesorter" id="tablaAQLGeneralNuevo">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Auditor</th>
                                        <th>Modulo (AQL)</th>
                                        <th>Estilo</th>
                                        <th>Numero de Operarios</th>
                                        <th>Cantidad Paro</th>
                                        <th>Minutos Paro</th>
                                        <th>Promedio Minutos Paro</th>
                                        <th>Cantidad Paro Modular</th>
                                        <th>Minutos Paro Modular</th> 
                                        <th>Total piezas por Bulto</th> 
                                        <th>Total Bulto</th> 
                                        <th>Total Bulto Rechazados</th> 
                                        <th>Cantidad Auditados</th>
                                        <th>Cantidad Defectos</th>
                                        <th>% Error AQL</th>
                                        <th>Defectos</th>
                                        <th>Accion Correctiva</th>
                                        <th>Operario Responsable</th>
                                        <th>Reparacion Piezas</th>
                                        <th>Piezas de Bulto Rechazado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosModuloEstiloAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['auditoresUnicos']); ?></td>
                                            <td>
                                                <button type="button" class="custom-btn" 
                                                    onclick="openCustomModal('customModalAQL<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">
                                                    <?php echo e($item['modulo']); ?>

                                                </button>
                                            </td>
                                            <td><?php echo e($item['estilosUnicos']); ?></td>
                                            <td><?php echo e($item['conteoOperario']); ?></td>
                                            <td><?php echo e($item['conteoMinutos']); ?></td>
                                            <td><?php echo e($item['sumaMinutos']); ?></td>
                                            <td><?php echo e($item['promedioMinutosEntero']); ?></td>
                                            <td><?php echo e($item['conteParoModular']); ?></td>
                                            <td><?php echo e($item['sumaParoModular']); ?></td>
                                            <td><?php echo e($item['sumaPiezasBulto']); ?></td> 
                                            <td><?php echo e($item['cantidadBultosEncontrados']); ?></td> 
                                            <td><?php echo e($item['cantidadBultosRechazados']); ?></td> 
                                            <td><?php echo e($item['sumaAuditadaAQL']); ?></td> 
                                            <td><?php echo e($item['sumaRechazadaAQL']); ?></td> 
                                            <td><?php echo e(number_format($item['porcentajeErrorAQL'], 2)); ?>%</td>
                                            <td><?php echo e($item['defectosUnicos']); ?></td>
                                            <td><?php echo e($item['accionesCorrectivasUnicos']); ?></td>
                                            <td><?php echo e($item['operariosUnicos']); ?></td>
                                            <td><?php echo e($item['sumaReparacionRechazo']); ?></td>
                                            <td><?php echo e($item['piezasRechazadasUnicas']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No hay datos disponibles para el proceso general de AQL.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla de Proceso -->
        <div id="tablaProceso" class="table-container" style="display: none;">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Turno Normal</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($datosModuloEstiloProceso): ?>
                            <table class="table tablesorter" id="tablaProcesoGeneralNuevo">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Auditor</th>
                                        <th>Modulo</th>
                                        <th>Estilo</th>
                                        <th>Recorridos</th>
                                        <th>Numero de Operarios</th>
                                        <th>Numero de Utility</th>
                                        <th>Cantidad Paro</th>
                                        <th>Minutos Paro</th>
                                        <th>Promedio Minutos Paro</th>
                                        <th>Cantidad Paro Modular</th>
                                        <th>Minutos Paro Modular</th>
                                        <th>Cantidad Auditados</th>
                                        <th>Cantidad Defectos</th>
                                        <th>% Error Proceso</th>
                                        <th>DEFECTOS</th>
                                        <th>ACCION CORRECTIVA</th>
                                        <th>Operarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosModuloEstiloProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['auditoresUnicos']); ?></td>
                                            <td>
                                                <button type="button" class="custom-btn" 
                                                    onclick="openCustomModal('customModalProceso<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">
                                                    <?php echo e($item['modulo']); ?>

                                                </button>
                                            </td>
                                            <td><?php echo e($item['estilo']); ?></td>
                                            <td><?php echo e($item['cantidadRecorridos']); ?></td>
                                            <td><?php echo e($item['conteoOperario']); ?></td>
                                            <td><?php echo e($item['conteoUtility']); ?></td>
                                            <td><?php echo e($item['conteoMinutos']); ?></td>
                                            <td><?php echo e($item['sumaMinutos']); ?></td>
                                            <td><?php echo e($item['promedioMinutosEntero']); ?></td> 
                                            <td><?php echo e($item['conteParoModular']); ?></td>
                                            <td><?php echo e($item['sumaParoModular']); ?></td>
                                            <td><?php echo e($item['sumaAuditadaProceso']); ?></td> 
                                            <td><?php echo e($item['sumaRechazadaProceso']); ?></td> 
                                            <td><?php echo e(number_format($item['porcentajeErrorProceso'], 2)); ?>%</td>
                                            <td><?php echo e($item['defectosUnicos']); ?></td>
                                            <td><?php echo e($item['accionesCorrectivasUnicos']); ?></td>
                                            <td><?php echo e($item['operariosUnicos']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No hay datos disponibles para el proceso general.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="card">
        <!-- Botones para Tiempo Extra -->
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-sm btn-primary btn-simple active" id="showAQLTE">
                <input type="radio" name="optionsExtra" checked>
                <h5><i class="tim-icons icon-app text-success"></i>&nbsp; AQL (Tiempo Extra)</h5>
            </label>
            <label class="btn btn-sm btn-primary btn-simple" id="showProcesoTE">
                <input type="radio" name="optionsExtra">
                <h5><i class="tim-icons icon-vector text-primary"></i>&nbsp; Procesos (Tiempo Extra)</h5>
            </label>
        </div>
        <!-- Tabla de AQL (Tiempo Extra) -->
        <div id="tablaAQLTE" class="table-container" style="display: block;">
            <!-- Contenido de la tabla AQL para Tiempo Extra -->
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Turno Tiempo Extra</h3> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($datosModuloEstiloAQLTE): ?>
                            <table class="table tablesorter" id="tablaAQLGeneralTENuevo">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Auditor</th>
                                        <th>Modulo (AQL)</th>
                                        <th>Estilo</th>
                                        <th>Numero de Operarios</th>
                                        <th>Cantidad Paro</th>
                                        <th>Minutos Paro</th>
                                        <th>Promedio Minutos Paro</th>
                                        <th>Cantidad Paro Modular</th>
                                        <th>Minutos Paro Modular</th>
                                        <th>Total piezas por Bulto</th>
                                        <th>Total Bulto</th>
                                        <th>Total Bulto Rechazados</th>
                                        <th>Cantidad Auditados</th>
                                        <th>Cantidad Defectos</th>
                                        <th>% Error AQL</th>
                                        <th>Defectos</th>
                                        <th>Accion Correctiva</th>
                                        <th>Operario Responsable</th>
                                        <th>Reparacion Piezas</th>
                                        <th>Piezas de Bulto Rechazado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosModuloEstiloAQLTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['auditoresUnicos']); ?></td>
                                            <td>
                                                <button type="button" class="custom-btn" onclick="openCustomModal('customModalAQLTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">
                                                    <?php echo e($item['modulo']); ?>

                                                </button>
                                            </td>
                                            <td><?php echo e($item['estilosUnicos']); ?></td>
                                            <td><?php echo e($item['conteoOperario']); ?></td>
                                            <td><?php echo e($item['conteoMinutos']); ?></td>
                                            <td><?php echo e($item['sumaMinutos']); ?></td>
                                            <td><?php echo e($item['promedioMinutosEntero']); ?></td>
                                            <td><?php echo e($item['conteParoModular']); ?></td>
                                            <td><?php echo e($item['sumaParoModular']); ?></td>
                                            <td><?php echo e($item['sumaPiezasBulto']); ?></td>
                                            <td><?php echo e($item['cantidadBultosEncontrados']); ?></td>
                                            <td><?php echo e($item['cantidadBultosRechazados']); ?></td>
                                            <td><?php echo e($item['sumaAuditadaAQL']); ?></td>
                                            <td><?php echo e($item['sumaRechazadaAQL']); ?></td>
                                            <td><?php echo e(number_format($item['porcentajeErrorAQL'], 2)); ?>%</td>
                                            <td><?php echo e($item['defectosUnicos']); ?></td>
                                            <td><?php echo e($item['accionesCorrectivasUnicos']); ?></td>
                                            <td><?php echo e($item['operariosUnicos']); ?></td>
                                            <td><?php echo e($item['sumaReparacionRechazo']); ?></td>
                                            <td><?php echo e($item['piezasRechazadasUnicas']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No hay datos disponibles para el proceso con tiempo extra de AQL.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div> 
        </div>
        <!-- Tabla de Procesos (Tiempo Extra) -->
        <div id="tablaProcesoTE" class="table-container" style="display: none;">
            <!-- Contenido de la tabla Proceso para Tiempo Extra -->
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Turno Tiempo Extra</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?php if($datosModuloEstiloProcesoTE): ?>
                            <table class="table tablesorter" id="tablaProcesoGeneralTENuevo">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Auditor</th>
                                        <th>Modulo</th>
                                        <th>Estilo</th>
                                        <th>Recorridos</th>
                                        <th>Numero de Operarios</th>
                                        <th>Numero de Utility</th>
                                        <th>Cantidad Paro</th>
                                        <th>Minutos Paro</th>
                                        <th>Promedio Minutos Paro</th>
                                        <th>Cantidad Paro Modular</th>
                                        <th>Minutos Paro Modular</th>
                                        <th>Cantidad Auditados</th>
                                        <th>Cantidad Defectos</th>
                                        <th>% Error Proceso</th>
                                        <th>DEFECTOS</th>
                                        <th>ACCION CORRECTIVA</th>
                                        <th>Operarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosModuloEstiloProcesoTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['auditoresUnicos']); ?></td>
                                            <td>
                                                <button type="button" class="custom-btn" onclick="openCustomModal('customModalProcesoTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">
                                                    <?php echo e($item['modulo']); ?>

                                                </button>
                                            </td>
                                            <td><?php echo e($item['estilo']); ?></td>
                                            <td><?php echo e($item['cantidadRecorridos']); ?></td>
                                            <td><?php echo e($item['conteoOperario']); ?></td>
                                            <td><?php echo e($item['conteoUtility']); ?></td>
                                            <td><?php echo e($item['conteoMinutos']); ?></td>
                                            <td><?php echo e($item['sumaMinutos']); ?></td>
                                            <td><?php echo e($item['promedioMinutosEntero']); ?></td> 
                                            <td><?php echo e($item['conteParoModular']); ?></td>
                                            <td><?php echo e($item['sumaParoModular']); ?></td>
                                            <td><?php echo e($item['sumaAuditadaProceso']); ?></td> 
                                            <td><?php echo e($item['sumaRechazadaProceso']); ?></td> 
                                            <td><?php echo e(number_format($item['porcentajeErrorProceso'], 2)); ?>%</td>
                                            <td><?php echo e($item['defectosUnicos']); ?></td>
                                            <td><?php echo e($item['accionesCorrectivasUnicos']); ?></td>
                                            <td><?php echo e($item['operariosUnicos']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No hay datos disponibles para el proceso con tiempo extra.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(!empty($datosModuloEstiloAQL) && is_iterable($datosModuloEstiloAQL)): ?>
        <?php $__currentLoopData = $datosModuloEstiloAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <!-- Modal personalizado para los detalles del módulo y estilo -->
            <div id="customModalAQL<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>" class="custom-modal">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <span class="custom-close" 
                            onclick="closeCustomModal('customModalAQL<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">&times;</span>
                        <h3>Detalles de AQL para Módulo <?php echo e($item['modulo']); ?>, Estilo: <?php echo e($item['estilo']); ?></h3>
                    </div>
                    <div class="custom-modal-body table-responsive">
                        <table class="table" id="tablaAQLDetalle<?php echo e($item['modulo']); ?>">
                            <thead>
                                <tr>
                                    <th>PARO</th>
                                    <th>CLIENTE</th>
                                    <th># BULTO</th>
                                    <th>PIEZAS</th>
                                    <th>TALLA</th>
                                    <th>COLOR</th>
                                    <th>ESTILO</th>
                                    <th>PIEZAS INSPECCIONADAS</th>
                                    <th>PIEZAS RECHAZADAS</th>
                                    <th>TIPO DE DEFECTO</th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $item['detalles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($registro->minutos_paro ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->cliente ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->bulto ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->pieza ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->talla ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->color ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->estilo ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->cantidad_auditada ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->cantidad_rechazada ?? 'N/A'); ?></td>
                                        <td><?php echo e($registro->tpAuditoriaAQL->pluck('tp')->isEmpty() ? 'N/A' : implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray())); ?></td>
                                        <td><?php echo e($registro->created_at ? $registro->created_at->format('H:i:s') : 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Fin del Modal -->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <p>.</p>
    <?php endif; ?>
    
    <?php if(!empty($datosModuloEstiloAQLTE) && is_iterable($datosModuloEstiloAQLTE)): ?>
        <?php $__currentLoopData = $datosModuloEstiloAQLTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="customModalAQLTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>" class="custom-modal">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <span class="custom-close" onclick="closeCustomModal('customModalAQLTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">&times;</span>
                    <h3>Detalles de AQL para Módulo <?php echo e($item['modulo']); ?>, Estilo: <?php echo e($item['estilo']); ?></h3>
                </div>
                <div class="custom-modal-body table-responsive">
                    <table class="table " id="tablaAQLDetalleTE<?php echo e($item['modulo']); ?>">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th># BULTO</th>
                                <th>PIEZAS</th>
                                <th>TALLA</th>
                                <th>COLOR</th>
                                <th>ESTILO</th>
                                <th>PIEZAS INSPECCIONADAS</th>
                                <th>PIEZAS RECHAZADAS</th>
                                <th>TIPO DE DEFECTO</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $item['detalles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($registro->minutos_paro ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cliente ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->bulto ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->pieza ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->talla ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->color ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->estilo ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_auditada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_rechazada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->tpAuditoriaAQL->pluck('tp')->isEmpty() ? 'N/A' : implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray())); ?></td>
                                    <td><?php echo e($registro->created_at ? $registro->created_at->format('H:i:s') : 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <p>.</p>
    <?php endif; ?>
    
    <?php if(!empty($datosModuloEstiloProceso) && is_iterable($datosModuloEstiloProceso)): ?>
    <?php $__currentLoopData = $datosModuloEstiloProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="customModalProceso<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>" class="custom-modal">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <span class="custom-close" onclick="closeCustomModal('customModalProceso<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">&times;</span>
                    <h3>Detalles de Proceso para Módulo <?php echo e($item['modulo']); ?>, Estilo: <?php echo e($item['estilo']); ?></h3>
                </div>
                <div class="custom-modal-body table-responsive">
                    <table class="table" id="tablaProcesoDetalle<?php echo e($item['modulo']); ?>">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th>Nombre</th>
                                <th>Operacion</th>
                                <th>Piezas Auditadas</th>
                                <th>Piezas Rechazadas</th>
                                <th>Tipo de Problema</th>
                                <th>Accion Correctiva</th>
                                <th>pxp</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $item['detalles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($registro->minutos_paro ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cliente ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->nombre ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->operacion ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_auditada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_rechazada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->tpAseguramientoCalidad->pluck('tp')->isEmpty() ? 'N/A' : implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray())); ?></td>
                                    <td><?php echo e($registro->ac ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->pxp ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->created_at ? $registro->created_at->format('H:i:s') : 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <p>.</p>
    <?php endif; ?>

    <?php if(!empty($datosModuloEstiloProcesoTE) && is_iterable($datosModuloEstiloProcesoTE)): ?>
        <?php $__currentLoopData = $datosModuloEstiloProcesoTE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div id="customModalProcesoTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>" class="custom-modal">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <span class="custom-close" onclick="closeCustomModal('customModalProcesoTE<?php echo e($item['modulo']); ?>_<?php echo e($item['estilo']); ?>')">&times;</span>
                    <h3>Detalles de Proceso para Módulo <?php echo e($item['modulo']); ?>, Estilo: <?php echo e($item['estilo']); ?></h3>
                </div>
                <div class="custom-modal-body table-responsive">
                    <table class="table" id="tablaProcesoDetalleTE<?php echo e($item['modulo']); ?>">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th>Nombre</th>
                                <th>Operacion</th>
                                <th>Piezas Auditadas</th>
                                <th>Piezas Rechazadas</th>
                                <th>Tipo de Problema</th>
                                <th>Accion Correctiva</th>
                                <th>pxp</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $item['detalles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($registro->minutos_paro ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cliente ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->nombre ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->operacion ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_auditada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->cantidad_rechazada ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->tpAseguramientoCalidad->pluck('tp')->isEmpty() ? 'N/A' : implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray())); ?></td>
                                    <td><?php echo e($registro->ac ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->pxp ?? 'N/A'); ?></td>
                                    <td><?php echo e($registro->created_at ? $registro->created_at->format('H:i:s') : 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <p>.</p>
    <?php endif; ?>

    <style>
        .custom-body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        .custom-card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .custom-card-header {
            background-color: #2e7d32;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .custom-card-body {
            padding: 15px;
        }
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        .custom-table th, .custom-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #333;
        }
        .custom-table th {
            background-color: #2e2e2e;
        }
        .custom-btn {
            background-color: transparent;
            border: none;
            color: #4caf50;
            cursor: pointer;
            text-decoration: underline;
        }
        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            overflow-y: auto;
        }
        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }
        .custom-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: fixed;
            right: 25px;
            top: 15px;
        }
        .custom-close:hover,
        .custom-close:focus {
            color: #fff;
        }
        .custom-modal-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #2e2e2e;
            padding: 15px;
            z-index: 1001;
        }
        .custom-modal-body {
            margin-top: 70px; /* Ajusta este valor según la altura de tu encabezado */
            padding: 15px;
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


    <script>
        $(document).ready(function() {
            // Definir un tipo personalizado de ordenamiento para manejar "N/A"
            $.fn.dataTable.ext.type.order['custom-num'] = function(a, b) {
                // Manejar "N/A" como el valor más bajo
                if (a === "N/A" || a === "") return -Infinity;
                if (b === "N/A" || b === "") return -Infinity;
                return parseFloat(a) - parseFloat(b); // Comparación numérica normal
            };

            // Función para obtener el título desde el elemento <h3> anterior a cada tabla
            function obtenerTituloTabla(tableId) {
                return $(tableId).closest('.card').find('.card-title').text().trim();
            }

            // Obtener el valor de la fecha de inicio del input en formato YYYY-MM-DD
            const fechaInicioInput = document.getElementById('fecha_inicio').value;
            const fechaInicio = fechaInicioInput.split('-').reverse().join('-'); // Transforma a DD-MM-YYYY

            // IDs de las tablas
            const tableIds = [
                '#tablaAQLGeneral', '#tablaProcesoGeneral', '#tablaAQLGeneralTE', '#tablaProcesoGeneralTE',
                '#tablaAQLGeneralNuevo', '#tablaAQLGeneralTENuevo'
            ];

            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    const tituloTabla = obtenerTituloTabla(tableId); // Obtiene el título para cada tabla

                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: false,
                        autoWidth: false,
                        responsive: true,
                        dom: 'Bfrtip',
                        order: [[1, 'asc']],
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                className: 'btn btn-success',
                                title: tituloTabla, // Título de la tabla como título del archivo Excel
                                messageTop: `Fecha: ${fechaInicio}`, // Segunda fila con la fecha de inicio
                                exportOptions: {
                                    format: {
                                        header: function(data, columnIndex) {
                                            return data; // Mantiene los nombres de columnas como están
                                        }
                                    }
                                }
                            }
                        ],
                        language: {
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sSearch": "Buscar:",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        },
                        columnDefs: [
                            {
                                targets: [0, 1, 2, 15, 16, 17], // Columnas específicas como numéricas
                                type: "string",
                                render: function (data) {
                                    return typeof data === "string" ? data.trim() : data;
                                }
                            },
                            {
                                targets: "_all", // Resto de columnas con valores personalizados
                                type: "custom-num",
                                render: function(data, type, row) {
                                    // Manejar "N/A" en la presentación y ordenamiento
                                    return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                }
                            }
                        ]
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Definir un tipo personalizado de ordenamiento para manejar "N/A"
            $.fn.dataTable.ext.type.order['custom-num'] = function(a, b) {
                // Manejar "N/A" como el valor más bajo
                if (a === "N/A" || a === "") return -Infinity;
                if (b === "N/A" || b === "") return -Infinity;
                return parseFloat(a) - parseFloat(b); // Comparación numérica normal
            };

            // Función para obtener el título desde el elemento <h3> anterior a cada tabla
            function obtenerTituloTabla(tableId) {
                return $(tableId).closest('.card').find('.card-title').text().trim();
            }

            // Obtener el valor de la fecha de inicio del input en formato YYYY-MM-DD
            const fechaInicioInput = document.getElementById('fecha_inicio').value;
            const fechaInicio = fechaInicioInput.split('-').reverse().join('-'); // Transforma a DD-MM-YYYY

            // IDs de las tablas
            const tableIds = [
                '#tablaProcesoGeneralNuevo', '#tablaProcesoGeneralTENuevo'
            ];

            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    const tituloTabla = obtenerTituloTabla(tableId); // Obtiene el título para cada tabla

                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: false,
                        autoWidth: false,
                        responsive: true,
                        dom: 'Bfrtip',
                        order: [[1, 'asc']],
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                className: 'btn btn-success',
                                title: tituloTabla, // Título de la tabla como título del archivo Excel
                                messageTop: `Fecha: ${fechaInicio}`, // Segunda fila con la fecha de inicio
                                exportOptions: {
                                    format: {
                                        header: function(data, columnIndex) {
                                            return data; // Mantiene los nombres de columnas como están
                                        }
                                    }
                                }
                            }
                        ],
                        language: {
                            "sProcessing": "Procesando...",
                            "sLengthMenu": "Mostrar _MENU_ registros",
                            "sZeroRecords": "No se encontraron resultados",
                            "sEmptyTable": "Ningún dato disponible en esta tabla",
                            "sInfo": "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                            "sSearch": "Buscar:",
                            "oPaginate": {
                                "sFirst": "Primero",
                                "sLast": "Último",
                                "sNext": "Siguiente",
                                "sPrevious": "Anterior"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        },
                        columnDefs: [
                            {
                                targets: [0, 1, 2, 14, 15, 16], // Columnas específicas como numéricas
                                type: "string",
                                render: function (data) {
                                    return typeof data === "string" ? data.trim() : data;
                                }
                            },
                            {
                                targets: "_all", // Resto de columnas con valores personalizados
                                type: "custom-num",
                                render: function(data, type, row) {
                                    // Manejar "N/A" en la presentación y ordenamiento
                                    return type === 'sort' ? (data === 'N/A' ? -Infinity : parseFloat(data)) : data;
                                }
                            }
                        ]
                    });
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Función para inicializar DataTable en tablas AQL
            function initializeAQLDataTable(tableId) {
                if (!$.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 15,
                        autoWidth: false,
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                className: 'btn btn-success'
                            }
                        ],
                        language: {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            "sInfoEmpty":      "Mostrando 0 a 0 de 0 registros",
                            "sInfoFiltered":   "(filtrado de _MAX_ registros totales)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        }
                    });
                }
            }

            // Inicializar DataTables para todas las tablas AQL
            $('table[id^="tablaAQLDetalle"], table[id^="tablaProcesoDetalle"],table[id^="tablaAQLDetalleTE"], table[id^="tablaProcesoDetalleTE"]').each(function() {
                initializeAQLDataTable('#' + $(this).attr('id'));
            });

            // Función para inicializar nuevas tablas AQL (por si se añaden dinámicamente)
            function initializeNewAQLTables() {
                $('table[id^="tablaAQLDetalle"], table[id^="tablaProcesoDetalle"],table[id^="tablaAQLDetalleTE"], table[id^="tablaProcesoDetalleTE"]').each(function() {
                    if (!$.fn.DataTable.isDataTable('#' + $(this).attr('id'))) {
                        initializeAQLDataTable('#' + $(this).attr('id'));
                    }
                });
            }

            // Si estás usando algún evento para abrir modales, puedes llamar a initializeNewAQLTables() después de abrir el modal
            // Por ejemplo:
            // $(document).on('shown.bs.modal', '.modal', initializeNewAQLTables);
        });
    </script>

    <script>
        let activeModalId = null;

        function openCustomModal(modalId) {
            document.getElementById(modalId).style.display = "block";
            document.body.style.overflow = "hidden"; // Previene el scroll en el body
            activeModalId = modalId; // Guarda el ID del modal activo
        }

        function closeCustomModal(modalId) {
            document.getElementById(modalId).style.display = "none";
            document.body.style.overflow = "auto"; // Restaura el scroll en el body
            activeModalId = null; // Limpia el ID del modal activo
        }

        // Cerrar el modal si se hace clic fuera del contenido
        window.onclick = function(event) {
            if (event.target.classList.contains('custom-modal')) {
                closeCustomModal(event.target.id);
            }
        }

        // Nuevo: Evento para cerrar el modal con la tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape" && activeModalId) {
                closeCustomModal(activeModalId);
            }
        });
    </script>

    <!-- Script para alternar entre las tablas -->
    <script>
        document.getElementById('showAQL').addEventListener('click', function() {
            document.getElementById('tablaAQL').style.display = 'block';
            document.getElementById('tablaProceso').style.display = 'none';
        });

        document.getElementById('showProceso').addEventListener('click', function() {
            document.getElementById('tablaAQL').style.display = 'none';
            document.getElementById('tablaProceso').style.display = 'block';
        });

        document.getElementById('showAQLTE').addEventListener('click', function() {
            document.getElementById('tablaAQLTE').style.display = 'block';
            document.getElementById('tablaProcesoTE').style.display = 'none';
        });

        document.getElementById('showProcesoTE').addEventListener('click', function() {
            document.getElementById('tablaAQLTE').style.display = 'none';
            document.getElementById('tablaProcesoTE').style.display = 'block';
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboardPorDia', 'titlePage' => __('Dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboar\dashboardPanta1PorDia.blade.php ENDPATH**/ ?>