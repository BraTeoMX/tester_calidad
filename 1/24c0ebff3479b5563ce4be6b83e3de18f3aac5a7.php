

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
                            <h3>GERENTE DE PRODUCCION <?php echo e($gerenteProduccion); ?> </h3>
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
                        <div class="col-md-4">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>MODULOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $mostrarRegistroModulo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->modulo); ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table> 
                        </div>
                        <div class="col-md-4">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>OPERARIOS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $mostrarRegistroOperario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->nombre); ?>" readonly>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table> 
                        </div>
                        <div class="col-md-4">
                            <table class="table"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>UTILITY</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $mostrarRegistroUtility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->nombre); ?>" readonly>
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

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboar\detallePorGerente.blade.php ENDPATH**/ ?>