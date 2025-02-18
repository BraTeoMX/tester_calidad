<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-5 ml-auto">
            <div class="info-area info-horizontal mt-5">
                <div class="icon icon-warning">
                    <i class="tim-icons icon-wifi"></i>
                </div>
                <div class="description">
                    <h3 class="info-title"><?php echo e(__('Marketing')); ?></h3>
                    <p class="description">
                        <?php echo e(__('We\'ve created the marketing campaign of the website. It was a very interesting collaboration.')); ?>

                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-primary">
                    <i class="tim-icons icon-triangle-right-17"></i>
                </div>
                <div class="description">
                    <h3 class="info-title"><?php echo e(__('Fully Coded in HTML5')); ?></h3>
                    <p class="description">
                        <?php echo e(__('We\'ve developed the website with HTML5 and CSS3. The client has access to the code using GitHub.')); ?>

                    </p>
                </div>
            </div>
            <div class="info-area info-horizontal">
                <div class="icon icon-info">
                    <i class="tim-icons icon-trophy"></i>
                </div>
                <div class="description">
                    <h3 class="info-title"><?php echo e(__('Built Audience')); ?></h3>
                    <p class="description">
                        <?php echo e(__('There is also a Fully Customizable CMS Admin Dashboard for this product.')); ?>

                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-7 mr-auto">
            <div class="card card-register card-white">
                <div class="card-header">
                    <img class="card-img" src="<?php echo e(asset('black')); ?>/img/card-primary.png" alt="Card image">
                    <h4 class="card-title"><?php echo e(__('Register')); ?></h4>
                </div>
                <form class="form" method="post" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="card-body">
                        <div class="input-group<?php echo e($errors->has('name') ? ' has-danger' : ''); ?>">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-single-02"></i>
                                </div>
                            </div>
                            <input type="text" name="name" class="form-control<?php echo e($errors->has('name') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Name')); ?>">
                            <?php echo $__env->make('alerts.feedback', ['field' => 'name'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="input-group<?php echo e($errors->has('email') ? ' has-danger' : ''); ?>">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-email-85"></i>
                                </div>
                            </div>
                            <input type="email" name="email" class="form-control<?php echo e($errors->has('email') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Email')); ?>">
                            <?php echo $__env->make('alerts.feedback', ['field' => 'email'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="input-group<?php echo e($errors->has('password') ? ' has-danger' : ''); ?>">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password" class="form-control<?php echo e($errors->has('password') ? ' is-invalid' : ''); ?>" placeholder="<?php echo e(__('Password')); ?>">
                            <?php echo $__env->make('alerts.feedback', ['field' => 'password'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tim-icons icon-lock-circle"></i>
                                </div>
                            </div>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="<?php echo e(__('Confirm Password')); ?>">
                        </div>
                        <div class="form-check text-left">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox">
                                <span class="form-check-sign"></span>
                                <?php echo e(__('I agree to the')); ?>

                                <a href="#"><?php echo e(__('terms and conditions')); ?></a>.
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary btn-round btn-lg"><?php echo e(__('Get Started')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['class' => 'register-page', 'page' => __('Register Page'), 'contentClass' => 'register-page'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\auth\register.blade.php ENDPATH**/ ?>