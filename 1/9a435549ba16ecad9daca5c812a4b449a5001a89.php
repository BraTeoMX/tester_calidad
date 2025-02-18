

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title"><?php echo e(__('Editar Perfil')); ?></h5>
                </div>
                <form method="post" action="<?php echo e(route('profile.update')); ?>" autocomplete="off">
                    <div class="card-body">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('put'); ?>

                            <?php echo $__env->make('alerts.success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <div class="form-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                                <label><?php echo e(__('Nombre')); ?></label>
                                <input type="text" name="name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Nombre')); ?>" value="<?php echo e(old('name', auth()->user()->name)); ?>">
                                <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>

                            <div class="form-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                                <label><?php echo e(__('Correo Electronico')); ?></label>
                                <input type="email" name="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Correo Electronico')); ?>" value="<?php echo e(old('email', auth()->user()->email)); ?>">
                                <?php echo $__env->make('alerts.feedback', ['field' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary"><?php echo e(__('Guardar')); ?></button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="title"><?php echo e(__('Cambio de contraseña')); ?></h5>
                </div>
                <form method="post" action="<?php echo e(route('profile.password')); ?>" autocomplete="off">
                    <div class="card-body">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('put'); ?>

                        <?php echo $__env->make('alerts.success', ['key' => 'password_status'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="form-group<?php echo e($errors->has('old_password') ? ' has-danger' : ''); ?>">
                            <label><?php echo e(__('Contraseña Anterior')); ?></label>
                            <input type="password" name="old_password" class="form-control<?php echo e($errors->has('old_password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Contraseña Anterior')); ?>" value="" required>
                            <?php echo $__env->make('alerts.feedback', ['field' => 'old_password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="form-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
                            <label><?php echo e(__('Escriba la nueva contraseña')); ?></label>
                            <input type="password" name="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Nueva contraseña')); ?>" value="" required>
                            <?php echo $__env->make('alerts.feedback', ['field' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="form-group">
                            <label><?php echo e(__('Confirmar nueva contraseña')); ?></label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="<?php echo e(__('Confirmar nueva contraseña')); ?>" value="" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary"><?php echo e(__('Change password')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\profile\edit.blade.php ENDPATH**/ ?>