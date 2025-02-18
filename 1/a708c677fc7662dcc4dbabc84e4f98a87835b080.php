

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
    <?php if(session('sobre-escribir')): ?>
        <div class="alert sobre-escribir">
            <?php echo e(session('sobre-escribir')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('status')): ?>
        
        <div class="alert alert-secondary">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('cambio-estatus')): ?>
        <div class="alert cambio-estatus">
            <?php echo e(session('cambio-estatus')); ?>

        </div>
    <?php endif; ?>
    <style>
        .alerta-exito {
            background-color: #32CD32;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .sobre-escribir {
            background-color: #FF8C00;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .cambio-estatus {
            background-color: #800080;
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
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                    
                </div>
                <hr> 
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Orden: <?php echo e($datoAX->op); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Estlo: <?php echo e($datoAX->estilo); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Planta: <?php echo e($datoAX->planta); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Temporada: <?php echo e($datoAX->temporada); ?></h4>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('auditoriaCorte.formEncabezadoAuditoriaCorte')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                    <input type="hidden" name="orden" value="<?php echo e($datoAX->op); ?>">
                    <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="cliente" class="col-sm-6 col-form-label">Cliente</label>
                            <div class="col-sm-12 d-flex align-items-center">
                                <select name="cliente" id="cliente" class="form-control"
                                    title="Por favor, selecciona una opción" required>
                                    <option value="">Selecciona una opción</option>
                                    <?php $__currentLoopData = $CategoriaCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cliente->nombre); ?>"><?php echo e($cliente->nombre); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="color" class="col-sm-6 col-form-label">Color</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="color" id="color"
                                    placeholder="codigo del color" required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="material" class="col-sm-6 col-form-label">Material</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="material" id="material"
                                    placeholder="nombre del material" required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="evento" class="col-sm-9 col-form-label">CANTIDAD EVENTOS</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="evento" id="evento" required>
                                    <?php for($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="pieza" class="col-sm-6 col-form-label">PIEZAS</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="pieza" id="pieza"
                                    placeholder="..." required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="lienzo" class="col-sm-6 col-form-label">LIENZOS</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="lienzo" id="lienzo"
                                    placeholder="..." required/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="text"]');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    });

</script>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Corte', 'titlePage' => __('Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\evaluacionCorte\altaEvaluacionCorte.blade.php ENDPATH**/ ?>