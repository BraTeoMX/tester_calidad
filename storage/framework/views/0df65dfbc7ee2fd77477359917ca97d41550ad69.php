

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
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                </div>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="accordion" id="accordionExample1">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                ESTATUS: NO INICIADO
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <input type="text" id="searchInput" class="form-control"
                                                placeholder="Buscar por Orden">
                                            <br>
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <div class="table-responsive" data-filter="false">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>iniciar</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBody">
                                                        <?php $__currentLoopData = $DatoAXNoIniciado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inicio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                                                            <tr>
                                                                <td><a href="<?php echo e(route('auditoriaCorte.altaAuditoriaCorte', ['orden' => $inicio->op])); ?>"
                                                                        class="btn btn-primary">Acceder</a></td>
                                                                <td><?php echo e($inicio->op); ?></td>
                                                                <td><?php echo e($inicio->estilo); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del acordeón -->
                        </div>
                        <div class="col-md-6">
                            
                            <div class="accordion" id="accordionExample2">
                                <div class="card">
                                    <div class="card-header" id="headingOne2">
                                        <h2 class="mb-0">
                                            <button class="btn estado-proceso btn-block" type="button"
                                                data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true"
                                                aria-controls="collapseOne2">
                                                ESTATUS: EN PROCESO
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne2"
                                        data-parent="#accordionExample2">
                                        <div class="card-body">
                                            <input type="text" id="searchInputAcordeon" class="form-control"
                                                placeholder="Buscar por Proceso">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <div class="accordion" id="accordionExample">
                                                <?php if($EncabezadoAuditoriaCorte->isNotEmpty()): ?>
                                                    <?php $__currentLoopData = $EncabezadoAuditoriaCorte->unique('orden_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $encabezadoCorte): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="card proceso-card" data-proceso="<?php echo e($encabezadoCorte->orden_id); ?>">
                                                            <div class="card-header" id="heading<?php echo e($encabezadoCorte->orden_id); ?>">
                                                                <h2 class="mb-0">
                                                                    <button class="btn estado-proceso btn-block" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapse<?php echo e($encabezadoCorte->orden_id); ?>"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapse<?php echo e($encabezadoCorte->orden_id); ?>">
                                                                        <?php echo e($encabezadoCorte->orden_id); ?>

                                                                    </button>
                                                                </h2>
                                                            </div>
                                                
                                                            <div id="collapse<?php echo e($encabezadoCorte->orden_id); ?>" class="collapse"
                                                                aria-labelledby="heading<?php echo e($encabezadoCorte->orden_id); ?>"
                                                                data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div>
                                                                        <form method="POST" action="<?php echo e(route('auditoriaCorte.agregarEventoCorte')); ?>">
                                                                            <?php echo csrf_field(); ?>
                                                                            <input type="hidden" name="orden_id" value="<?php echo e($encabezadoCorte->orden_id); ?>">
                                                                            <input type="hidden" name="estilo_id" value="<?php echo e($encabezadoCorte->estilo_id); ?>">
                                                                            <input type="hidden" name="planta_id" value="<?php echo e($encabezadoCorte->planta_id); ?>">
                                                                            <input type="hidden" name="temporada_id" value="<?php echo e($encabezadoCorte->temporada_id); ?>">
                                                                            <input type="hidden" name="cliente_id" value="<?php echo e($encabezadoCorte->cliente_id); ?>">
                                                                            <input type="hidden" name="color_id" value="<?php echo e($encabezadoCorte->color_id); ?>">
                                                                            <input type="hidden" name="estatus_evaluacion_corte" value="<?php echo e($encabezadoCorte->estatus_evaluacion_corte); ?>">
                                                                            <input type="hidden" name="material" value="<?php echo e($encabezadoCorte->material); ?>">
                                                                            <input type="hidden" name="pieza" value="<?php echo e($encabezadoCorte->pieza); ?>">
                                                                            <input type="hidden" name="trazo" value="<?php echo e($encabezadoCorte->trazo); ?>">
                                                                            <input type="hidden" name="lienzo" value="<?php echo e($encabezadoCorte->lienzo); ?>">
                                                                            <button type="submit" class="btn btn-info">Agregar 1 evento</button>
                                                                        </form>
                                                                    </div>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Acceso</th>
                                                                                <th>Evento</th>
                                                                                <th>Estilo</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php $__currentLoopData = $EncabezadoAuditoriaCorte->where('orden_id', $encabezadoCorte->orden_id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $encabezado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <tr>
                                                                                    <td><a href="<?php echo e(route('auditoriaCorte.auditoriaCorte', ['id' => $encabezado->id, 'orden' => $encabezado->orden_id])); ?>"
                                                                                        class="btn btn-primary">Acceder</a>
                                                                                    </td>
                                                                                    <td><?php echo e($encabezado->evento); ?></td>
                                                                                    <td><?php echo e($encabezado->estilo_id); ?></td>
                                                                                </tr>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const searchInput = document.getElementById('searchInputAcordeon');
                                                const procesoCards = document.querySelectorAll('.proceso-card');

                                                searchInput.addEventListener('input', function() {
                                                    const busqueda = this.value.trim().toLowerCase();
                                                    procesoCards.forEach(card => {
                                                        const proceso = card.getAttribute('data-proceso').toLowerCase();
                                                        if (proceso.includes(busqueda)) {
                                                            card.style.display = 'block'; // Mostrar el acordeón
                                                        } else {
                                                            card.style.display = 'none'; // Ocultar el acordeón
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <!-- Fin del acordeón -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            
                            <div class="accordion" id="accordionExample3">
                                <div class="card">
                                    <div class="card-header" id="headingOne3">
                                        <h2 class="mb-0">
                                            <button class="btn btn-success  btn-block" type="button" data-toggle="collapse"
                                                data-target="#collapseOne3" aria-expanded="true"
                                                aria-controls="collapseOne3">
                                                ESTATUS: FINAL
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne3" class="collapse show" aria-labelledby="headingOne3"
                                        data-parent="#accordionExample3">
                                        <div class="card-body">
                                            <input type="text" id="searchInputFin" class="form-control"
                                                placeholder="Buscar por Proceso Final">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>iniciar</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                            <th>Planta</th>
                                                            <th>Temporada</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBodyFin">
                                                        <?php $__currentLoopData = $DatoAXFin; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><a href="<?php echo e(route('auditoriaCorte.auditoriaCorte', ['id' => $fin->id, 'orden' => $fin->op])); ?>"
                                                                        class="btn btn-primary">Acceder</a></td>
                                                                <td><?php echo e($fin->op); ?> </td>
                                                                <td><?php echo e($fin->estilo); ?></td>
                                                                <td><?php echo e($fin->planta); ?></td>
                                                                <td><?php echo e($fin->temporada); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del acordeón -->
                        <div class="col-md-6">
                            
                            <div class="accordion" id="accordionExample4">
                                <div class="card">
                                    <div class="card-header" id="headingOne4">
                                        <h2 class="mb-0">
                                            <button class="btn btn-rechazado btn-block" type="button"
                                                data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true"
                                                aria-controls="collapseOne4">
                                                ESTATUS: RECHAZADO
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne4" class="collapse show" aria-labelledby="headingOne4"
                                        data-parent="#accordionExample4">
                                        <div class="card-body">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <input type="text" id="searchInputRechazo" class="form-control" placeholder="Buscar por Orden Rechazada">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Accion</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                            <th>Planta</th>
                                                            <th>Temporada</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBodyRechazo">
                                                        <?php $__currentLoopData = $DatoAXRechazado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rechazado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td>
                                                                    <form method="POST" action="<?php echo e(route('auditoriaCorte.formAprobarCorte', ['id' => $rechazado->id])); ?>">
                                                                        <?php echo csrf_field(); ?>
                                                                        <button type="submit" class="btn btn-primary">Aprobarlo</button>
                                                                    </form>
                                                                </td>
                                                                <td><?php echo e($rechazado->op); ?> </td>
                                                                <td><?php echo e($rechazado->estilo); ?></td>
                                                                <td><?php echo e($rechazado->planta); ?></td>
                                                                <td><?php echo e($rechazado->temporada); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del acordeón -->
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* Estilo personalizado para el botón */
            .estado-proceso {
                background-color: #2196F3;
                /* Color azul intenso */
                color: #fff;
                /* Color de texto blanco */
                border-color: #2196F3;
                /* Color del borde igual al color de fondo */
                transition: background-color 0.3s, color 0.3s;
                /* Transición suave para el color de fondo y texto */
            }

            /* Estilo para el efecto hover */
            .estado-proceso:hover {
                background-color: #1976D2;
                /* Color azul más oscuro al pasar el mouse */
                border-color: #1976D2;
                /* Color del borde igual al color de fondo */
            }

            .btn-rechazado {
                background-color: #FF5733;
                /* Color de fondo naranja */
                color: white;
                /* Color de texto blanco */
                border-color: #FF5733;
                /* Color del borde igual al de fondo */
            }

            .btn-rechazado:hover {
                background-color: #FF8C00;
                /* Cambiar color de fondo al pasar el cursor */
                border-color: #FF8C00;
                /* Cambiar color del borde al pasar el cursor */
            }
        </style>
        <script>
            const searchInput = document.getElementById('searchInput');
            const tablaBody = document.getElementById('tablaBody');
            const filas = tablaBody.getElementsByTagName('tr');

            searchInput.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                for (const fila of filas) {
                    const orden = fila.getElementsByTagName('td')[1].innerText.toLowerCase();
                    if (orden.includes(busqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        </script>

        <script>
            const searchInputFin = document.getElementById('searchInputFin');
            const tablaBodyFin = document.getElementById('tablaBodyFin');
            const filasFin = tablaBodyFin.getElementsByTagName('tr');

            searchInputFin.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                for (const fila of filasFin) {
                    const orden = fila.getElementsByTagName('td')[1].innerText.toLowerCase();
                    if (orden.includes(busqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        </script>
        <script>
            const searchInputRechazo = document.getElementById('searchInputRechazo');
            const tablaBodyRechazo = document.getElementById('tablaBodyRechazo');
            const filasRechazo = tablaBodyRechazo.getElementsByTagName('tr');
        
            searchInputRechazo.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                for (const fila of filasRechazo) {
                    const orden = fila.getElementsByTagName('td')[1].innerText.toLowerCase();
                    if (orden.includes(busqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        </script>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/auditoriaCorte/inicioAuditoriaCorte.blade.php ENDPATH**/ ?>