

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
                            <h3 class="card-title">Dashboard Auditoria Proceso Playera</h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <table class="table  table-bordered table1">
                        <thead class="thead-custom1 text-center">
                            <tr>
                                <th>Cliente</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $porcentajesError; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente => $porcentajeError): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e(($porcentajeError > 9 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '')); ?>">
                                    <td><?php echo e($cliente); ?></td>
                                    <td><?php echo e(number_format($porcentajeError, 2)); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-bordered ">
                        <thead class="thead-custom2 text-center">
                            <tr>
                                <th>Operario de Maquina</th>
                                <th>Modulo</th>
                                <th>Operacion</th>
                                <th>Team Leader</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $porcentajesErrorNombre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre => $porcentajeErrorNombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e(($porcentajeErrorNombre > 9 && $porcentajeErrorNombre <= 15) ? 'error-bajo' : ($porcentajeErrorNombre > 15 ? 'error-alto' : '')); ?>">
                                    <td><?php echo e($nombre); ?></td>
                                    <td><?php echo e($moduloPorNombre[$nombre]); ?></td>
                                    <td><?php echo e($operacionesPorNombre[$nombre]); ?></td>
                                    <td><?php echo e($teamLeaderPorNombre[$nombre]); ?></td>
                                    <td><?php echo e(number_format($porcentajeErrorNombre, 2)); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                    <hr>
                    <table class="table table-bordered table1">
                        <thead class="thead-custom3 text-center">
                            <tr>
                                <th>Team Leader</th>
                                <th>% Error</th>
                                <!-- Aquí puedes agregar más encabezados si es necesario -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $porcentajesErrorTeamLeader; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamLeader => $porcentajeError): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e(($porcentajeError > 10 && $porcentajeError <= 15) ? 'error-bajo' : ($porcentajeError > 15 ? 'error-alto' : '')); ?>">
                                    <td><?php echo e($teamLeader); ?></td>
                                    <td><?php echo e(number_format($porcentajeError, 2)); ?>%</td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
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

        /* Personalizar estilo del thead */
        .thead-custom2 {
            background-color: #0891ec; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }

        /* Personalizar estilo del thead */
        .thead-custom3 {
            background-color: #f77b07; /* Ajusta el color hexadecimal a tu gusto */
            color: #fff; /* Ajusta el color del texto si es necesario */
            border: 1px solid #ddd; /* Ajusta el borde si es necesario */
            padding: 10px; /* Ajusta el relleno si es necesario */
        }


        .error-bajo {
            background-color: #f8d7da; /* Rojo claro */
            color: #721c24; /* Texto oscuro */
        }

        .error-alto {
            background-color: #dc3545; /* Rojo */
            color: #ffffff; /* Texto blanco */
        }
    </style>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\dashboar\dashboarAProcesoPlayera.blade.php ENDPATH**/ ?>