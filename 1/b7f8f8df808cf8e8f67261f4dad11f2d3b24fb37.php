

<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title"><?php echo e(__('Progreso Corte.')); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-left">
                            <a href="auditoriaCortes" class="btn btn-sm btn-secundary" id="NewCorteBtn">
                                <?php echo e(__('Nueva Auditoria Corte.')); ?>

                                <label for="name" class="material-icons" style="font-size: 29px;">edit_note</label>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-8 text-rigth">
                        <h3>Estatus Permisos</h3>

                    </div>


                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\formulariosCalidad\ProgresoCorte.blade.php ENDPATH**/ ?>