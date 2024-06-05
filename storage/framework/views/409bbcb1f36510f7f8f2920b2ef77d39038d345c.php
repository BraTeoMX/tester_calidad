

    <?php $__env->startSection('content'); ?>
    
    <?php if(session('error')): ?>
    <div class="alert alert-danger">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>
    <?php if(session('success')): ?>
    <div class="alert alerta-exito">
        <?php echo e(session('success')); ?>

        <?php if(session('sorteo')): ?>
            <br><?php echo e(session('sorteo')); ?>

        <?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if(session('status')): ?> 
        <div class="alert alert-secondary">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>
    <style>
    .alerta-exito {
        background-color: #28a745; /* Color de fondo verde */
        color: white; /* Color de texto blanco */
        padding: 20px;
        border-radius: 15px;
        font-size: 20px;
    }
    </style>
    
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <!--Aqui se edita el encabezado que es el que se muestra -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2>AUDITORIA DE ETIQUETAS</h2>
                            </div>
                            <div>
                            </div>
                        </div>
                            <div class="card-body">
                                <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                                <div>
                                    <form action="<?php echo e(route('formulariosCalidad.filtrarDatosEtiquetas')); ?>" method="GET">
                                        <div class="row mb-3">
                                            <label for="cliente" class="col-sm-3 col-form-label">CLIENTE</label>
                                            <div class="col-sm-9">
                                                <select name="cliente" id="cliente" class="form-control" required title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $CategoriaCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($cliente->id); ?>" <?php if(request('cliente') == $cliente->id): ?> selected <?php endif; ?>><?php echo e($cliente->nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="estilo" class="col-sm-3 col-form-label">ESTILO</label>
                                            <div class="col-sm-9">
                                                <select name="estilo" id="estilo" class="form-control" title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($estilo->id); ?>" <?php if(request('estilo') == $estilo->id): ?> selected <?php endif; ?>><?php echo e($estilo->nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="no_recibo" class="col-sm-3 col-form-label">NO/RECIBO</label>
                                            <div class="col-sm-9">
                                                <select name="no_recibo" id="no_recibo" class="form-control" title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $CategoriaNoRecibo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $no_recibo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($no_recibo->id); ?>" <?php if(request('no_recibo') == $no_recibo->id): ?> selected <?php endif; ?>><?php echo e($no_recibo->nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="fecha" class="col-sm-3 col-form-label">Fecha</label>
                                            <div class="col-sm-9">
                                                <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo e(request('fecha')); ?>">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Filtrar Datos</button>
                                        <button type="submit" formaction="<?php echo e(route('exportar-excel')); ?>" class="btn btn-success">Exportar a Excel</button>
                                    </form>                                    
                                </div>
                                <hr>
                                <?php if($mostrarAuditoriaEtiquetas->isEmpty()): ?>
                                    <div class="alert alert-info">No hay datos para mostrar.</div>
                                <?php else: ?>
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Estilo</th>
                                            <th>NO/RECIBIDO</th>
                                            <th>TALLA/CANTIDAD</th>
                                            <th>TAMAÑO MUESTRA</th>
                                            <th>DEFECTOS</th>
                                            <th>TIPO DE DEFECTO</th>
                                            <th>ESTADO</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $mostrarAuditoriaEtiquetas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e(optional($auditoria->categoriaEstilo)->nombre ?? 'NINGUNO'); ?></td>
                                                <td><?php echo e(optional($auditoria->categoriaNoRecibo)->nombre ?? 'NINGUNO'); ?></td>
                                                <td><?php echo e($auditoria->talla_cantidad_id ?: 'NINGUNO'); ?></td>
                                                <td><?php echo e($auditoria->tamaño_muestra_id ?: 'NINGUNO'); ?></td>
                                                <td><?php echo e($auditoria->defecto_id ?: 'NINGUNO'); ?></td>
                                                <td><?php echo e(optional($auditoria->categoriaTipoDefecto)->nombre ?? 'NINGUNO'); ?></td>
                                                <td>
                                                    <?php if($auditoria->estado == 1): ?>
                                                        APROBADO
                                                    <?php elseif($auditoria->estado == 0): ?>
                                                        RECHAZADO
                                                    <?php else: ?>
                                                        NINGUNO
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <?php endif; ?>
                                
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <canvas id="graficoPorEstilo"></canvas>
                                    </div>
                                    <div class="col-md-6">
                                        <canvas id="graficoPorNoRecibo"></canvas>
                                    </div>
                                    
                                </div>
                                <!--Fin de la edicion del codigo para mostrar el contenido-->
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para el gráfico por estilo
            let datosPorEstilo = <?php echo json_encode($datosPorEstilo, 15, 512) ?>;
            let etiquetasEstilo = Object.keys(datosPorEstilo);
            let valoresEstilo = Object.values(datosPorEstilo);

            // Datos para el gráfico por tipo de No. Recibo
            let datosPorNoRecibo = <?php echo json_encode($datosPorNoRecibo, 15, 512) ?>;
            let etiquetasNoRecibo = Object.keys(datosPorNoRecibo);
            let valoresNoRecibo = Object.values(datosPorNoRecibo);

            // Crear gráfico por estilo
            new Chart(document.getElementById('graficoPorEstilo'), {
                type: 'bar', // Puedes cambiar el tipo de gráfico aquí
                data: {
                    labels: etiquetasEstilo,
                    datasets: [{
                        label: 'Auditorías por Estilo',
                        data: valoresEstilo,
                        // Configuraciones adicionales...
                    }]
                },
                options: {
                    // Opciones del gráfico...
                }
            });

            // Crear gráfico por tipo de defecto
            new Chart(document.getElementById('graficoPorNoRecibo'), {
                type: 'pie', // Puedes cambiar el tipo de gráfico aquí
                data: {
                    labels: etiquetasNoRecibo,
                    datasets: [{
                        label: 'Auditorías por Numero de Recibo',
                        data: valoresNoRecibo,
                        // Configuraciones adicionales...
                    }]
                },
                options: {
                    // Opciones del gráfico...
                }
            });

            // Puedes agregar más gráficos aquí...
        });
    </script>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\formulariosCalidad\mostrarAuditoriaEtiquetas.blade.php ENDPATH**/ ?>