<?php $__currentLoopData = $DatoAXNoIniciado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr>
    <td><a href="<?php echo e(route('auditoriaCorte.altaAuditoriaCorte', ['orden' => $inicio->op])); ?>" class="btn btn-primary">Acceder</a></td>
    <td><?php echo e($inicio->op); ?></td>
    <td><?php echo e($inicio->estilo); ?></td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\auditoriaCorte\partials\_table.blade.php ENDPATH**/ ?>