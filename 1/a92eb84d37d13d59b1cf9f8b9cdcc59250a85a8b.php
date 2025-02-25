

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

        .texto-blanco {
            color: white !important;
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
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Orden</th>
                                <th>Estilo</th> 
                                <th>Planta</th>
                                <th>Temporada</th>
                                <th>Cliente</th>
                                <th>Piezas Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo e($datoAX->op); ?></td>
                                <td><?php echo e($datoAX->estilo); ?></td>
                                <td><?php echo e($datoAX->planta); ?></td>
                                <td><?php echo e($datoAX->temporada); ?></td>
                                <td><?php echo e($datoAX->custorname); ?></td>
                                <td><?php echo e(intval($datoAX->qtysched)); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form method="POST" action="<?php echo e(route('auditoriaCorte.formEncabezadoAuditoriaCorteV2')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="orden" value="<?php echo e($datoAX->op); ?>">
                    <input type="hidden" name="estilo" value="<?php echo e($datoAX->estilo); ?>">
                    <input type="hidden" name="planta" value="<?php echo e($datoAX->planta); ?>">
                    <input type="hidden" name="temporada" value="<?php echo e($datoAX->temporada); ?>">
                    <input type="hidden" name="cliente" value="<?php echo e($datoAX->custorname); ?>">
                    <input type="hidden" name="qtysched_id" value="<?php echo e($datoAX->qtysched); ?>">
                    <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary table-01">
                                <tr>
                                    <th>Color</th>
                                    <th>Material</th>
                                    <th>Piezas</th>
                                    <th>Lienzos</th>
                                    <th>Cantidad Eventos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if($datoAX->inventcolorid): ?>
                                            <input type="text" class="form-control texto-blanco" name="color_id" id="color_id" value="<?php echo e($datoAX->inventcolorid); ?>" readonly/>
                                        <?php else: ?>
                                            <input type="text" class="form-control" name="color_id" id="color_id" placeholder="..." required/>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="material" id="material" placeholder="Nombre del material" required/>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="pieza" id="pieza" placeholder="..." required/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="lienzo" id="lienzo" placeholder="..." required/>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <select class="form-control" name="evento" id="evento" required>
                                                <?php for($i = 1; $i <= 10; $i++): ?>
                                                    <option value="<?php echo e($i); ?>">&nbsp; <?php echo e($i); ?> &nbsp;</option>
                                                <?php endfor; ?>
                                            </select>
                                            &nbsp;/&nbsp;
                                            <select class="form-control" name="total_evento" id="total_evento" required>
                                                <?php for($i = 1; $i <= 10; $i++): ?>
                                                    <option value="<?php echo e($i); ?>"> &nbsp;<?php echo e($i); ?> &nbsp;</option>
                                                <?php endfor; ?>
                                            </select>
                                            <div id="warning" style="display: none; color: red;">El primer número debe ser menor o igual al segundo número</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                <form method="POST" action="<?php echo e(route('auditoriaCorte.formRechazoCorte')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                    <button type="submit" class="btn btn-danger" name="action" value="rechazo">Rechazo</button>
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

    <script>
        document.getElementById('total_evento').addEventListener('change', function() {
            var evento = document.getElementById('evento').value;
            var totalEvento = this.value;
            var warning = document.getElementById('warning');
            
            if (parseInt(evento) > parseInt(totalEvento)) {
                warning.style.display = 'block';
                this.value = evento;
            } else {
                warning.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obtener el formulario
            const form = document.querySelector('form[action="<?php echo e(route('auditoriaCorte.formEncabezadoAuditoriaCorteV2')); ?>"]');

            // Validar antes de enviar el formulario
            form.addEventListener('submit', function(event) {
                var evento = parseInt(document.getElementById('evento').value);
                var totalEvento = parseInt(document.getElementById('total_evento').value);
                var warning = document.getElementById('warning');

                // Verificar si el valor de 'evento' es mayor que 'total_evento'
                if (evento > totalEvento) {
                    // Mostrar el mensaje de advertencia
                    warning.style.display = 'block';
                    
                    // Evitar el envío del formulario
                    event.preventDefault();
                    alert("Por favor, selecciona un rango válido: el primer numero debe ser menor o igual a segundo numero");
                } else {
                    // Ocultar el mensaje si la validación es correcta
                    warning.style.display = 'none';
                }
            });
        });
    </script>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333; /* Color del texto */
        }

        .table-01 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(2) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(3) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>

    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\auditoriaCorte\altaAuditoriaCorte.blade.php ENDPATH**/ ?>