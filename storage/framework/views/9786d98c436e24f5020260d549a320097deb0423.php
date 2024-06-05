

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
                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>ESTILO</th>
                                        <th>CLIENTE</th>
                                        <th>TEAM-LEADER</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="area" id="area" class="form-control" required>
                                                <option value="">Selecciona una opción</option>
                                                <option value="AUDITORIA EN PROCESO">AUDITORIA EN PROCESO</option>
                                                <option value="AUDITORIA EN PROCESO PLAYERA">AUDITORIA EN PROCESO PLAYERA</option>
                                                <option value="AUDITORIA EN EMPAQUE">AUDITORIA EN EMPAQUE</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="" selected>Selecciona una opción</option>
                                                <?php if($auditorPlanta == 'Planta1'): ?>
                                                    <?php $__currentLoopData = $auditoriaProcesoIntimark1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduloP1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($moduloP1->moduleid); ?>" data-itemid="<?php echo e($moduloP1->itemid); ?>">
                                                            <?php echo e($moduloP1->moduleid); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php elseif($auditorPlanta == 'Planta2'): ?>
                                                    <?php $__currentLoopData = $auditoriaProcesoIntimark2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $moduloP2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($moduloP2->moduleid); ?>" data-itemid="<?php echo e($moduloP2->itemid); ?>">
                                                            <?php echo e($moduloP2->moduleid); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="estilo" id="estilo" readonly required />
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="cliente" id="cliente" readonly required />
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
                                        AUDITORIA EN PROCESO
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
                                                                            <th>Estilo</th>
                                                                            <th>Gerente de Produccion</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $procesoActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                                <td><?php echo e($proceso->team_leader); ?></td>

                                                                            </tr>
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
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $procesoFinal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                            </tr>
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
                                        AUDITORIA EN PROCESO PLAYERA
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
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $playeraActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                            </tr>
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
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $playeraFinal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                                                        <?php echo csrf_field(); ?>
                                                                                        <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                        <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                        <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                        <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                        <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                        <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                        <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                        <button type="submit" class="btn btn-primary">Acceder</button>
                                                                                    </form>
                                                                                </td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                            </tr>
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
                                        data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                        AUDITORIA EN PROCESO EMPAQUE
                                    </button>
                                </h2>
                            </div>
                    
                            <div id="collapseThree" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            
                                            <div class="accordion" id="accordionExample5">
                                                <div class="card">
                                                    <div class="card-header" id="headingOne5">
                                                        <h2 class="mb-0">
                                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                                data-target="#collapseThree5" aria-expanded="true" aria-controls="collapseThree5">
                                                                En Proceso
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree5" class="collapse show" aria-labelledby="headingOne5"
                                                        data-parent="#accordionExample5">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $empaqueActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td><button class="btn btn-primary">Acceder</button></td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                            </tr>
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
                                                                data-target="#collapseThree6" aria-expanded="true" aria-controls="collapseThree6">
                                                                Finalizado
                                                            </button>
                                                        </h2>
                                                    </div>
                                    
                                                    <div id="collapseThree6" class="collapse show" aria-labelledby="headingOne6"
                                                        data-parent="#accordionExample6">
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $empaqueFinal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <td><button class="btn btn-primary">Acceder</button></td>
                                                                                <td><?php echo e($proceso->modulo); ?></td>
                                                                                <td><?php echo e($proceso->estilo); ?></td>
                                                                            </tr>
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
    </div>
 
    <script>
        $('#modulo').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
            });

    </script>

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
            // Manejar la selección del módulo para actualizar el input de estilo
            $('#modulo').change(function() {
                var moduleid = $(this).val();
                $('#estilo').val(''); // Limpiar el input anterior
    
                $.ajax({
                    url: '<?php echo e(route("obtenerItemId")); ?>',
                    type: 'POST',
                    data: {
                        '_token': '<?php echo e(csrf_token()); ?>',
                        'moduleid': moduleid
                    },
                    success: function(response) {
                        console.log(response); // Verificar los datos recibidos
                        if (response.itemid) {
                            $('#estilo').val(response.itemid); // Establecer el itemid en el input
                        } else {
                            $('#estilo').val('No se encontró estilo'); // Mensaje si no hay itemid
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
    
            // Manejar la selección del módulo para actualizar el input de cliente
            $('#modulo').change(function() {
                var moduleid = $(this).val();
    
                // Realizar solicitud AJAX para obtener el cliente correspondiente al módulo seleccionado
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route("obtenerCliente1")); ?>',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        moduleid: moduleid
                    },
                    success: function(response) {
                        console.log(response); // Verificar los datos recibidos
                        // Actualizar el valor del campo "cliente" con el cliente obtenido de la respuesta AJAX
                        $('#cliente').val(response.cliente);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>    






<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views/aseguramientoCalidad/altaProceso.blade.php ENDPATH**/ ?>