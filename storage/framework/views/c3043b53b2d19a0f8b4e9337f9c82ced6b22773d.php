<?php if(session($key ?? 'status')): ?>
    <div class="alert alert-success" role="alert">
        <?php echo e(session($key ?? 'status')); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\calidad2\resources\views/alerts/success.blade.php ENDPATH**/ ?>