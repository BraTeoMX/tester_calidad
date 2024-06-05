<?php if(session($key ?? 'status')): ?>
    <div class="alert alert-success" role="alert">
        <?php echo e(session($key ?? 'status')); ?>

    </div>
<?php endif; ?>
<?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\alerts\success.blade.php ENDPATH**/ ?>