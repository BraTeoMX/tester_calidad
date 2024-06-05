

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
                            <h3 class="card-title">Modulo <?php echo e($nombreModulo); ?></h3>
                        </div>
                        <div class="col-auto">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <?php if($rangoInicial == $rangoFinal): ?>
                        <h3 class="card-title">Detalle por modulo seleccionado por el dia <?php echo e($rangoInicial); ?></h3>
                    <?php else: ?>
                        <h3 class="card-title">Detalle por modulo seleccionado de <?php echo e($rangoInicial); ?> al <?php echo e($rangoFinal); ?> </h3>
                    <?php endif; ?>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <h3 style="font-weight: bold;">Piezas auditadas</h3>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Total de piezas Muestra Auditadas </th>
                                            <th>Total de piezas Muestra Rechazadas</th>
                                            <th>Porcentaje AQL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $registrosIndividual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><input type="text" class="form-control" value="<?php echo e($registro->total_auditada); ?>" readonly></td>
                                                <td><input type="text" class="form-control" value="<?php echo e($registro->total_rechazada); ?>" readonly></td>
                                                <td><input type="text" class="form-control" value="<?php echo e($registro->total_rechazada != 0 ? number_format(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0); ?>" readonly></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <table class="table contenedor-tabla">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Total de piezas en bultos Auditados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $registrosIndividualPieza; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><input type="text" class="form-control" value="<?php echo e($registro->total_pieza); ?>" readonly></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h3 style="font-weight: bold;">Total por Bultos </h3>
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>total de Bultos Auditados</th>
                                    <th>total de Bultos Rechazados</th>
                                    <th>Porcentaje Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="conteo_bulto"
                                            id="conteo_bulto" value="<?php echo e($conteoBultos); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_rechazada"
                                            id="total_rechazada" value="<?php echo e($conteoPiezaConRechazo); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_porcentaje"
                                            id="total_porcentaje" value="<?php echo e(number_format($porcentajeBulto, 2)); ?>"
                                            readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <table class="table table55"> 
                        <thead class="thead-primary">
                            <tr>
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
                            <?php $__currentLoopData = $mostrarRegistro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <input type="text" class="form-control" name="bulto"
                                        value="<?php echo e($registro->bulto); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="pieza"
                                        value="<?php echo e($registro->pieza); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="talla"
                                        value="<?php echo e($registro->talla); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="color" id="color"
                                        value="<?php echo e($registro->color); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="estilo" id="estilo"
                                        value="<?php echo e($registro->estilo); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cantidad_auditada" id="cantidad_auditada"
                                        value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="cantidad_rechazada" id="cantidad_rechazada"
                                        value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                    </td>
                                    
                                    <form action="<?php echo e(route('auditoriaAQL.formUpdateDeleteProceso')); ?>"
                                        method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                        <td>
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo e(implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray())); ?>">
                                        </td>
                                        <td>
                                            <?php echo e($registro->created_at->format('H:i:s')); ?>

                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table> 
                    <hr>


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

<?php echo $__env->make('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\dashboar\detalleXModuloAQL.blade.php ENDPATH**/ ?>