

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
            background-color: #28a745;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }
    </style>
    
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3>CLIENTE <?php echo e($clienteSeleccionado); ?> </h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <?php if($rangoInicial == $rangoFinal): ?>
                        <h3 class="card-title">Seleccionado por el dia <?php echo e($rangoInicial); ?></h3>
                    <?php else: ?>
                        <h3 class="card-title">Seleccionado de <?php echo e($rangoInicial); ?> al <?php echo e($rangoFinal); ?> </h3>
                    <?php endif; ?>
                    <hr>
                    <div class="row">
                        <h3>AQL</h3>
                        <div class="col-md-12">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Modulo</th>
                                        <th>Auditor</th>
                                        <th>Estilo</th>
                                        <th>Responsable</th>
                                        <th>Motivo de defecto</th>
                                        <th>Accion correctiva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosAQLPlanta1TurnoNormal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->modulo); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->auditor); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->estilo); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->team_leader); ?>" readonly>
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $registro->tpAuditoriaAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e($tp->tp); ?>,&nbsp;
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->ac); ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <div class="row">
                        <h3>PROCESO</h3>
                        <div class="col-md-12">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Operador</th>
                                        <th>Modulo</th>
                                        <th>Auditor</th>
                                        <th>Estilo</th>
                                        <th>Responsable</th>
                                        <th>Numero de paros </th>
                                        <th>Motivo de defecto</th>
                                        <th>Accion correctiva</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $datosProcesoPlanta1TurnoNormal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->nombre); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->modulo); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->auditor); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->estilo); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->team_leader); ?>" readonly>
                                            </td>
                                            <td>
                                                
                                                (<?php echo e($registro->cantidad_rechazada > 0 ? $conteoRechazos : 0); ?>) <!-- Mostrar el conteo si es mayor a 0 -->
                                            </td>
                                            <td>
                                                <?php $__currentLoopData = $registro->tpAseguramientoCalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo e($tp->tp); ?>,&nbsp;
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->ac); ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table1 {
            max-width: 400px; /* Ajusta el valor según tus necesidades */
        }

        /* Personalizar estilo del thead */
        .thead-custom1 {
            background-color: #0c6666; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }
    </style>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboar\detallePorCliente.blade.php ENDPATH**/ ?>