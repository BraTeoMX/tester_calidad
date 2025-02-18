

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
                    <h3 class="card-title">EVALUACION DE CORTE CONTRA PATRON</h3>
                </div>
                <form method="POST" action="<?php echo e(route('formulariosCalidad.formEvaluacionCorte')); ?>">
                    <?php echo csrf_field(); ?>
                    <hr>
                    <div class="card-body">
                        <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha" class="col-sm-6 col-form-label">FECHA</label>
                                <div class="col-sm-12">
                                    <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">ESTILO</label>
                                <div class="col-sm-12">
                                    <select name="estilo" id="estilo" class="form-control select2" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($estilo->id); ?>"><?php echo e($estilo->nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <!--Este apartado debe ser modificado despues -->
                                <label for="descripcion" class="col-sm-6 col-form-label">DESCRIPCION</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control me-2" name="descripcion" id="descripcion"
                                        placeholder=" COMENTARIOS" required />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 style="text-align: center">IZQUIERDA</h5>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="izquierda_x" class="col-sm-6 col-form-label">X </label>
                                <div class="col-sm-12">
                                    <select name="izquierda_x" id="izquierda_x" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($estilo->id); ?>"><?php echo e($estilo->nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="izquierda_y" class="col-sm-3 col-form-label">Y </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="izquierda_y" id="izquierda_y"
                                        placeholder="Ingresa y " required title="Por favor, selecciona una opción"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 style="text-align: center">DERECHA</h5>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="derecha_x" class="col-sm-6 col-form-label">X </label>
                                <div class="col-sm-12">
                                    <select name="derecha_x" id="derecha_x" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($estilo->id); ?>"><?php echo e($estilo->nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="derecha_y" class="col-sm-3 col-form-label">Y </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="derecha_y" id="derecha_y"
                                        placeholder="Ingresa y " required title="Por favor, selecciona una opción"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <!--Fin de la edicion del codigo para mostrar el contenido-->
                    </div>
                <form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Evaluacion Corte', 'titlePage' => __('Evaluacion Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\formulariosCalidad\evaluacionCorte.blade.php ENDPATH**/ ?>