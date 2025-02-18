<?php if(session($key ?? 'status')): ?>
    <div class="alert alert-success" role="alert">
        <?php echo e(session($key ?? 'status')); ?>

    </div>
<?php endif; ?>
<?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\alerts\success.blade.php ENDPATH**/ ?>