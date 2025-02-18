<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('layouts.headers.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container mt--8 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="text-center text-muted mb-4">
                            <small><?php echo e(__('Verify Your Email Address')); ?></small>
                        </div>
                        <div>
                            <?php if(session('resent')): ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo e(__('A fresh verification link has been sent to your email address.')); ?>

                                </div>
                            <?php endif; ?>
                            
                            <?php echo e(__('Before proceeding, please check your email for a verification link.')); ?>

                            
                            <?php if(Route::has('verification.resend')): ?>
                                <?php echo e(__('If you did not receive the email')); ?>, 
                               <form class="d-inline" method="POST" action="<?php echo e(route('verification.resend')); ?>">
                                   <?php echo csrf_field(); ?>
                                   <button type="submit" class="btn btn-link p-0 m-0 align-baseline"><?php echo e(__('click here to request another')); ?></button>.
                               </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['class' => 'bg-default'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\auth\verify.blade.php ENDPATH**/ ?>