

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
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA CONTROL DE CALIDAD</h3>
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
                    <form method="POST" action="<?php echo e(route('auditoriaAQL.formAltaProcesoAQL')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>OP</th>
                                        <th>TEAM LEADER</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="area" id="area" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                <option value="AUDITORIA AQL">AUDITORIA AQL</option>
                                                <option value="AUDITORIA AQL PLAYERA">AUDITORIA AQL PLAYERA</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required
                                                title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                <!-- Agrega el atributo selected aquí -->
                                                <?php if($auditorPlanta == 'Planta1'): ?>
                                                    <?php $__currentLoopData = $auditoriaProcesoIntimark1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduloP1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                        <option value="<?php echo e($moduloP1->moduleid); ?>"
                                                            data-itemid="<?php echo e($moduloP1->itemid); ?>">
                                                            <?php echo e($moduloP1->moduleid); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php elseif($auditorPlanta == 'Planta2'): ?>
                                                    <?php $__currentLoopData = $auditoriaProcesoIntimark2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduloP2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                        <option value="<?php echo e($moduloP2->moduleid); ?>"
                                                            data-itemid="<?php echo e($moduloP2->itemid); ?>">
                                                            <?php echo e($moduloP2->moduleid); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="op" id="op" class="form-control" required 
                                            title="Por favor, selecciona una opción">
                                            <option value="" selected>Selecciona una opción</option>
                                            <!-- Agrega el atributo selected aquí -->
                                            <?php $__currentLoopData = $ordenOPs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orden): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($orden->prodid); ?>">
                                                    <?php echo e($orden->prodid); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        </td>
                                        <td>
                                            <select name="team_leader" id="team_leader" class="form-control" required
                                                title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                <!-- Agrega el atributo selected aquí -->
                                                <?php if($auditorPlanta == 'Planta1'): ?>
                                                    <?php $__currentLoopData = $teamLeaderPlanta1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamLeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($teamLeader->nombre); ?>">
                                                            <?php echo e($teamLeader->nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php elseif($auditorPlanta == 'Planta2'): ?>
                                                    <?php $__currentLoopData = $teamLeaderPlanta2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamLeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($teamLeader->nombre); ?>">
                                                            <?php echo e($teamLeader->nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                value="<?php echo e($auditorDato); ?>" readonly required /></td>
                                        <td><input type="text" class="form-control me-2" name="turno" id="turno"
                                                value="1" readonly required /></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">Iniciar</button>
                    </form>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->

                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>

        <div class="card">
            <!--Aqui se edita el encabezado que es el que se muestra -->
            <div class="card-header card-header-primary">
                <div class="row align-items-center justify-content-between">
                    <div class="col">
                        <h5 class="card-title">ESTATUS</h5>
                    </div>
                    <div class="col-auto">

                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body">
                <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                    data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    AUDITORIA AQL
                                </button>
                            </h2>
                        </div>
                
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                        <div class="accordion" id="accordionExample5">
                                            <div class="card">
                                                <div class="card-header" id="headingOne5">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                            data-target="#collapseOne5" aria-expanded="true" aria-controls="collapseOne5">
                                                            En Proceso
                                                        </button>
                                                    </h2>
                                                </div>
                                
                                                <div id="collapseOne5" class="collapse show" aria-labelledby="headingOne5"
                                                    data-parent="#accordionExample5">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $valoresMostrados = [];
                                                                    ?>
                                                                    <?php $__currentLoopData = $procesoActualAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op])): ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('auditoriaAQL.formAltaProcesoAQL')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="op" value="<?php echo e($proceso->op); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->op); ?></td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            <?php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            ?>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin del acordeón 1 -->
                                    <div class="col-md-6">
                                        
                                        <div class="accordion" id="accordionExample6">
                                            <div class="card">
                                                <div class="card-header" id="headingOne6">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                            data-target="#collapseOne6" aria-expanded="true" aria-controls="collapseOne6">
                                                            Finalizado
                                                        </button>
                                                    </h2>
                                                </div>
                                
                                                <div id="collapseOne6" class="collapse show" aria-labelledby="headingOne6"
                                                    data-parent="#accordionExample6">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $valoresMostrados = [];
                                                                    ?>
                                                                    <?php $__currentLoopData = $procesoFinalAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op])): ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('auditoriaAQL.formAltaProcesoAQL')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="op" value="<?php echo e($proceso->op); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->op); ?></td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            <?php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            ?>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin del acordeón 2 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-primary btn-block" type="button" data-toggle="collapse"
                                    data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                    AUDITORIA AQL PLAYERA
                                </button>
                            </h2>
                        </div>
                
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingOne"
                            data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        
                                        <div class="accordion" id="accordionExample5">
                                            <div class="card">
                                                <div class="card-header" id="headingOne5">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                            data-target="#collapseTwo5" aria-expanded="true" aria-controls="collapseTwo5">
                                                            En Proceso
                                                        </button>
                                                    </h2>
                                                </div>
                                
                                                <div id="collapseTwo5" class="collapse show" aria-labelledby="headingOne5"
                                                    data-parent="#accordionExample5">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $valoresMostrados = [];
                                                                    ?>
                                                                    <?php $__currentLoopData = $playeraActualAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op])): ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('auditoriaAQL.formAltaProcesoAQL')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="op" value="<?php echo e($proceso->op); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->op); ?></td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            <?php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            ?>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin del acordeón 1 -->
                                    <div class="col-md-6">
                                        
                                        <div class="accordion" id="accordionExample6">
                                            <div class="card">
                                                <div class="card-header" id="headingOne6">
                                                    <h2 class="mb-0">
                                                        <button class="btn btn-success btn-block" type="button" data-toggle="collapse"
                                                            data-target="#collapseTwo6" aria-expanded="true" aria-controls="collapseTwo6">
                                                            Finalizado
                                                        </button>
                                                    </h2>
                                                </div>
                                
                                                <div id="collapseTwo6" class="collapse show" aria-labelledby="headingOne6"
                                                    data-parent="#accordionExample6">
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Accion</th>
                                                                        <th>Módulo</th>
                                                                        <th>OP</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $valoresMostrados = [];
                                                                    ?>
                                                                    <?php $__currentLoopData = $playeraFinalAQL; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php if(!isset($valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op])): ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('auditoriaAQL.formAltaProcesoAQL')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="op" value="<?php echo e($proceso->op); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->op); ?></td>
                                                                                <!-- Agrega aquí el resto de las columnas que deseas mostrar -->
                                                                            </tr>
                                                                            <?php
                                                                                $valoresMostrados[$proceso->area][$proceso->modulo][$proceso->op] = true;
                                                                            ?>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </tbody>                                                                
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin del acordeón 2 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <!--Fin de la edicion del codigo para mostrar el contenido-->
            </div>
        </div>

    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#modulo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            $('#modulo').on('select2:select', function(e) {
                var itemid = e.params.data.element.dataset.itemid;
                $('#estilo').val(itemid);
            });
        });

        $(document).ready(function() {
            $('#op').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#modulo').change(function() {
                var itemid = $(this).find(':selected').data('itemid');
                $('#estilo').val(itemid);
            });
        });
    </script>





<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/auditoriaAQL/altaAQL.blade.php ENDPATH**/ ?>