

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
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">EVALUACION DE CORTE CONTRA PATRON</h3>
                        </div>
                        <div class="col-auto">
                            <h4>Fecha:
                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <form method="POST" action="<?php echo e(route('evaluacionCorte.formAltaEvaluacionCortes')); ?>">
                        <?php echo csrf_field(); ?>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="orden" class="col-sm-6 col-form-label">ORDEN</label>
                                <div class="col-sm-12">
                                    <select name="orden" id="orden" class="form-control select2" required
                                        title="Por favor, selecciona una opción" onchange="mostrarEstilo()">
                                        <option value="">Selecciona una opción</option>
                                        <?php $__currentLoopData = $EncabezadoAuditoriaCorte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($dato->orden_id); ?>" data-evento="<?php echo e($dato->evento); ?>"><?php echo e($dato->orden_id); ?> - Evento: <?php echo e($dato->evento); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <input type="hidden" name="evento" id="evento" value="">
                                </div>
                            </div>
                            &nbsp;
                            <div class="col-md-4 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">ESTILO</label>
                                <div class="col-sm-12">
                                    <input type="text" name="estilo" id="estilo" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <!--Este apartado debe ser modificado despues -->
                                <button type="submit" class="btn btn-primary">iniciar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
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

    <script>
        function mostrarEstilo() {
            var ordenSeleccionado = document.getElementById('orden').value;

            // Obtener el token CSRF de la etiqueta meta
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Obtener el evento seleccionado del atributo data-evento de la opción seleccionada
            var eventoSeleccionado = document.getElementById('orden').selectedOptions[0].getAttribute('data-evento');

            $.ajax({
                url: "<?php echo e(route('evaluacionCorte.obtenerEstilo')); ?>",
                type: 'POST',
                data: {
                    orden_id: ordenSeleccionado,
                    _token: csrfToken // Incluir el token CSRF en los datos de la solicitud
                },
                success: function(response) {
                    console.log(response); // Verifica la respuesta en la consola
                    document.getElementById('estilo').value = response.estilo;
                    document.getElementById('evento').value = eventoSeleccionado; // Asignar el valor del evento obtenido
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText); // Muestra el mensaje de error en la consola
                }
            });
        }
    </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Evaluacion Corte', 'titlePage' => __('Evaluacion Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\auditoriaProcesoCorte\inicioAuditoriaProcesoCorte.blade.php ENDPATH**/ ?>