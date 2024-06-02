<div style="background-image: url('<?php echo e(asset('black')); ?>/img/backlog.jpg'); background-size: cover; background-position: top center;align-items: center;" data-color="purple"" >

<?php $__env->startSection('content'); ?>
    <div class="header py-7 py-lg-8">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-7 col-md-8">
                         
                        <h1 class="text-white"><font size=+4><strong><?php echo e(__('Sistema de Calidad')); ?></strong></font></h1>
                       <!-- <p class="text-lead text-light">
                            <?php echo e(__('Use Black Dashboard theme to create a great project.')); ?>

                        </p>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/mac/proyectos-laravel/calidad_testeoxD/tester_calidad/resources/views/welcome.blade.php ENDPATH**/ ?>