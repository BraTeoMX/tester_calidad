

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
        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            /* Aumenta el tamaño del botón */
            font-size: 1.2rem;
            /* Aumenta el tamaño de la fuente */
            font-weight: bold;
            /* Texto en negritas */
            border-radius: 10px;
            /* Ajusta las esquinas redondeadas */
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
            /* Cambia el cursor a una mano */
        }

        .btn-verde-xd:hover {
            color: #fff !important;
            background-color: #218838 !important;
            border-color: #1e7e34 !important;
        }

        .btn-verde-xd:focus,
        .btn-verde-xd.focus {
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        .btn-verde-xd:disabled,
        .btn-verde-xd.disabled {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active,
        .btn-verde-xd:not(:disabled):not(.disabled):active,
        .show>.btn-verde-xd.dropdown-toggle {
            color: #fff !important;
            background-color: #1e7e34 !important;
            border-color: #1c7430 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .show>.btn-verde-xd.dropdown-toggle:focus {
            box-shadow: none, 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            box-sizing: border-box;
            position: relative;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between; /* Alinea título a la izquierda y botón a la derecha */
            background-color: #2e2e2e;
            padding: 15px;
            align-items: center;
        }

        .custom-modal-body {
            padding: 15px;
        }

        /* Estilo para el botón "CERRAR" en la esquina superior derecha */
        .custom-modal-footer {
            margin-right: 10px; /* Ajusta el margen derecho si deseas */
        }

        #closeModal {
            font-size: 14px;
            padding: 8px 16px;
        }

        .special-option {
            font-weight: bold; /* Negrita */
            font-style: italic; /* Cursiva */
            transform: skew(-10deg); /* Inclinación */
        }
    </style>
    
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra --> 
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title"><?php echo e($data['area']); ?></h3>
                        </div>
                        <div class="col-auto">
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-link" id="openModal">
                                <h4>Fecha: 
                                  <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                </h4>
                            </button>                              
                        </div>
                    </div>
                </div>
                
                <!-- Modal personalizado -->
                <div id="customModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="custom-modal-header">
                            <h5 class="modal-title texto-blanco">Detalles del Proceso</h5>
                            <!-- Botón "CERRAR" en la esquina superior derecha -->
                            <button id="closeModal" class="btn btn-danger">CERRAR</button>
                        </div>
                        <div class="custom-modal-body">
                            <!-- Aquí va el contenido de la tabla -->
                            <div class="table-responsive">
                                <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Acción</th>
                                            <th>Módulo</th>
                                            <th>Estilo</th>
                                            <th>Supervisor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaProcesos1">
                                        <?php $__currentLoopData = $procesoActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <!-- Campos ocultos -->
                                                    <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                    <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                    <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                    <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                    <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                    <input type="hidden" name="gerente_produccion" value="<?php echo e($proceso->gerente_produccion); ?>">
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
                <hr>
                <div class="card-body">
                    <?php if((($conteoParos == 3) && ($finParoModular1 == true)) || (($conteoParos == 6) && ($finParoModular2 == true))): ?> 
                        <div class="row">
                            <form method="POST" action="<?php echo e(route('aseguramientoCalidad.cambiarEstadoInicioParo')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="finalizar_paro_modular" value="1">
                                <input type="hidden" class="form-control" name="modulo" id="modulo" value="<?php echo e($data['modulo']); ?>">
                                <input type="hidden" class="form-control" name="estilo" id="estilo1" value="<?php echo e($data['estilo']); ?>"> 
                                <input type="hidden" class="form-control" name="area" id="area" value="<?php echo e($data['area']); ?>">
                                <input type="hidden" class="form-control" name="team_leader" id="team_leader" value="<?php echo e($data['team_leader']); ?>">
                                <input type="hidden" class="form-control" name="gerente_produccion" value="<?php echo e($data['gerente_produccion']); ?>">
        
        
                                <button type="submit" class="btn btn-primary">Fin Paro Modular Proceso</button> 
                            </form>
                        </div>
                    <?php else: ?>
                        <form id="miFormulario" method="POST" action="<?php echo e(route('aseguramientoCalidad.formRegistroAuditoriaProceso')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" class="form-control" name="area" id="area"
                                value="<?php echo e($data['area']); ?>">
                            <div class="table-responsive">
                                <table class="table table-200">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>MODULO</th>
                                            <th>ESTILO</th>
                                            <th>SUPERVISOR</th>
                                            <th>GERENTE PRODUCCION</th>
                                            <th>AUDITOR</th>
                                            <th>TURNO</th>
                                            <th>CLIENTE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control texto-blanco" name="modulo" id="modulo"
                                                    value="<?php echo e($data['modulo']); ?>" readonly></td>
                                            <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>
                                            <td>
                                                <select class="form-control texto-blanco" name="estilo" id="estilo" required onchange="actualizarEstilo(this.value)">
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $estilosEmpaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($estilo); ?>" <?php echo e($estilo == $data['estilo'] ? 'selected' : ''); ?>><?php echo e($estilo); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <?php else: ?>
                                            <td>
                                                <select class="form-control texto-blanco" name="estilo" id="estilo" required onchange="actualizarEstilo(this.value)">
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $estilos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($estilo); ?>" <?php echo e($estilo == $data['estilo'] ? 'selected' : ''); ?>><?php echo e($estilo); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                            <?php endif; ?>
                                            <td><input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader"
                                                    value="<?php echo e($data['team_leader']); ?>" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="gerente_produccion" 
                                                value="<?php echo e($data['gerente_produccion']); ?>" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="auditor" id="auditor"
                                                    value="<?php echo e($data['auditor']); ?>" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="turno" id="turno"
                                                    value="<?php echo e($data['turno']); ?>" readonly></td>
                                            <td><input type="text" class="form-control texto-blanco" name="cliente" id="cliente"
                                                value="<?php echo e($data['cliente']); ?>" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <?php if($estatusFinalizar): ?>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table flex-container table932">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>NOMBRE</th>
                                                <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>

                                                <?php else: ?>
                                                    <th>OPERACION</th>
                                                <?php endif; ?>
                                                <th>PIEZAS AUDITADAS</th>
                                                <th>PIEZAS RECHAZADAS</th>
                                                <th id="tp-column-header" class="d-none">TIPO DE PROBLEMA</th>
                                                <th id="ac-column-header" class="d-none">ACCION CORRECTIVA</th>
                                                <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                <?php else: ?>
                                                    <th>P x P</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td> 
                                                    <!-- Incluye Select2 en el select -->
                                                    <select name="nombre_final" id="nombre" class="form-control select2" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $nombresGenerales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->name); ?>"><?php echo e($nombre->personnelnumber); ?> - <?php echo e($nombre->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td> 
                                                <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>

                                                <?php else: ?>
                                                <td>
                                                    <select name="operacion" id="operacion" class="form-control" required title="Por favor, selecciona una opción" onchange="cambiarAInput(this)">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="otra"> [OTRA OPERACION]</option>
                                                        <?php $__currentLoopData = $operacionNombre; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->oprname); ?>"><?php echo e($nombre->oprname); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <?php endif; ?>
                                                <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                                <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                                <td class="tp-column d-none w-100">
                                                    <select id="tpSelect" class="form-control w-100" multiple title="Por favor, selecciona una opción"> 
                                                        <option value="OTRO">OTRO</option>
                                                        <?php if($data['area'] == 'AUDITORIA EN PROCESO'): ?>
                                                            <?php $__currentLoopData = $categoriaTPProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($proceso->nombre); ?>"><?php echo e($proceso->nombre); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA'): ?>
                                                            <?php $__currentLoopData = $categoriaTPPlayera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($playera->nombre); ?>"><?php echo e($playera->nombre); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                            <?php $__currentLoopData = $categoriaTPEmpaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($empque->nombre); ?>"><?php echo e($empque->nombre); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                    <div id="selectedOptionsContainer" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div> 
                                                </td>
                                                <td class="ac-column d-none">
                                                    <select name="ac" id="ac" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <?php if($data['area'] == 'AUDITORIA EN PROCESO'): ?>
                                                            <?php $__currentLoopData = $categoriaACProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($proceso->accion_correctiva); ?>"><?php echo e($proceso->accion_correctiva); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA'): ?>
                                                            <?php $__currentLoopData = $categoriaACPlayera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($playera->accion_correctiva); ?>"><?php echo e($playera->accion_correctiva); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                            <?php $__currentLoopData = $categoriaACEmpaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($empque->accion_correctiva); ?>"><?php echo e($empque->accion_correctiva); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                <?php else: ?>
                                                    <td><input type="text" class="form-control" name="pxp" id="pxp"></td>
                                                <?php endif; ?>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" class="btn-verde-xd">GUARDAR</button> 
                            <?php endif; ?>
                        </form>
                        <!-- Modal -->
                        <div class="modal fade" id="nuevoConceptoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoConceptoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content bg-dark text-white">
                                    <div class="modal-header">
                                        <h5 id="nuevoConceptoModalLabel">Introduce el nuevo concepto</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true" class="text-white">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInput" placeholder="Nuevo concepto">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                        <button type="button" class="btn btn-primary" id="guardarNuevoConcepto">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <?php if($mostrarRegistro): ?>
                        <?php if($estatusFinalizar): ?>
                            <h2>Registro</h2>
                            <table class="table table1">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>
                                        <?php else: ?>
                                        <th>Operacion </th>
                                        <?php endif; ?>
                                        <th>Piezas Auditadas</th>
                                        <th>Piezas Rechazadas</th>
                                        <th>Tipo de Problema </th>
                                        <th>Accion Correctiva </th>
                                        <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                        <?php else: ?>
                                            <th>pxp </th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $mostrarRegistro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <form action="<?php echo e(route('aseguramientoCalidad.formUpdateDeleteProceso')); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="nombre"
                                                        value="<?php echo e($registro->nombre); ?>" readonly>
                                                </td>
                                                <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>

                                                <?php else: ?>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="<?php echo e($registro->operacion); ?>" readonly>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_auditada"
                                                        value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_rechazada"
                                                        value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="tp"
                                                        value="<?php echo e($registro->tp); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="ac"
                                                        value="<?php echo e($registro->ac); ?>" readonly>
                                                </td>
                                                <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                <?php else: ?>
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" name="pxp"
                                                            value="<?php echo e($registro->pxp); ?>" readonly>

                                                    </td>
                                                <?php endif; ?>
                                            </form>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="observacion" class="col-sm-6 col-form-label">Observaciones:</label>
                                    <div class="col-sm-12">
                                        <textarea class="form-control texto-blanco" name="observacion" id="observacion" rows="3" readonly><?php echo e($registro->observacion); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <h2>Registro</h2>

                                <table class="table table1">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Paro</th>
                                            <th>Nombre</th>
                                            <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>

                                            <?php else: ?>
                                            <th>Operacion </th>
                                            <?php endif; ?>
                                            <th>Piezas Auditadas</th>
                                            <th>Piezas Rechazadas</th>
                                            <th>Tipo de Problema </th>
                                            <th>Accion Correctiva </th>
                                            <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                            <?php else: ?>
                                                <th>PxP </th>
                                            <?php endif; ?>
                                            <th>Eliminar </th>
                                            <th>Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $mostrarRegistro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <?php if($registro->inicio_paro == NULL): ?>
                                                        -
                                                    <?php elseif($registro->fin_paro != NULL): ?>
                                                        <?php echo e($registro->minutos_paro); ?>

                                                    <?php elseif($registro->fin_paro == NULL): ?>
                                                        <form method="POST" action="<?php echo e(route('aseguramientoCalidad.cambiarEstadoInicioParo')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="idCambio" value="<?php echo e($registro->id); ?>">
                                                            <button type="submit" class="btn btn-primary">Fin Paro Proceso</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="nombre"
                                                        value="<?php echo e($registro->nombre); ?>" readonly>
                                                </td>
                                                <?php if($data['modulo'] == "830A" || $data['modulo'] == "831A"): ?>

                                                <?php else: ?>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="operacion"
                                                        value="<?php echo e($registro->operacion); ?>" readonly>
                                                </td>
                                                <?php endif; ?>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_auditada"
                                                        value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control texto-blanco" name="cantidad_rechazada"
                                                        value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                                </td>
                                                <form action="<?php echo e(route('aseguramientoCalidad.formUpdateDeleteProceso')); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" readonly
                                                               value="<?php echo e(implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray())); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control texto-blanco" name="ac"
                                                        value="<?php echo e($registro->ac); ?>" readonly>
                                                    </td>
                                                    <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                    <?php else: ?>
                                                        <td> 
                                                            <input type="text" class="form-control texto-blanco" name="pxp_text"
                                                                value="<?php echo e($registro->pxp); ?>" readonly>
                                                        </td> 
                                                    <?php endif; ?>
                                                    <td>
                                                        <button type="submit" name="action" value="delete"
                                                            class="btn btn-danger">Eliminar</button>
                                                    </td>
                                                    <td>
                                                        <?php echo e($registro->created_at->format('H:i:s')); ?>

                                                    </td>
                                                </form>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                                <form action="<?php echo e(route('aseguramientoCalidad.formFinalizarProceso')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="area" value="<?php echo e($data['area']); ?>">
                                    <input type="hidden" name="modulo" value="<?php echo e($data['modulo']); ?>">
                                    <input type="hidden" name="estilo" value="<?php echo e($data['estilo']); ?>">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="observacion"
                                                class="col-sm-6 col-form-label">Observaciones:</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control texto-blanco" name="observacion" id="observacion" rows="3" placeholder="comentarios"
                                                    required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <button type="submit" name="action"
                                                class="btn btn-danger">Finalizar</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div>
                            <h2> sin registros el dia de hoy</h2>
                        </div>
                    <?php endif; ?>
                    <hr>
                </div>
            </div>
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total Individual</h2>
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Nombre </th>
                                <th>No. Recorridos </th>
                                <th>Total Piezas Auditada</th>
                                <th>Total Piezas Rechazada</th>
                                <th>Porcentaje Rechazado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $registrosIndividual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><input type="text" class="form-control texto-blanco" value="<?php echo e($registro->nombre); ?>"
                                                readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" 
                                            value="<?php echo e($registro->cantidad_registros); ?>" readonly></td> 
                                    <td><input type="text" class="form-control texto-blanco"
                                                value="<?php echo e($registro->total_auditada); ?>" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco"
                                                value="<?php echo e($registro->total_rechazada); ?>" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco"
                                                value="<?php echo e($registro->total_rechazada != 0 ? round(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0); ?>"
                                                readonly></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
                <hr>
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total General - Turno Normal</h2>
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de Piezas Auditadas</th>
                                <th>Total de Piezas Rechazados</th>
                                <th>Porcentaje Rechazo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" name="total_auditada"
                                        id="total_auditada" value="<?php echo e($total_auditada); ?>" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="total_rechazada"
                                            id="total_rechazada" value="<?php echo e($total_rechazada); ?>" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="total_porcentaje"
                                            id="total_porcentaje" value="<?php echo e(number_format($total_porcentaje, 2)); ?>"
                                            readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
                <!--Fin de la edicion del codigo para mostrar el contenido-->
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total General - Tiempo Extra </h2>
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de Piezas Auditadas</th>
                                <th>Total de Piezas Rechazados</th>
                                <th>Porcentaje Rechazo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" name="total_auditada"
                                            id="total_auditada" value="<?php echo e($total_auditadaTE); ?>" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="total_rechazada"
                                            id="total_rechazada" value="<?php echo e($total_rechazadaTE); ?>" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="total_porcentaje"
                                            id="total_porcentaje" value="<?php echo e(number_format($total_porcentajeTE, 2)); ?>"
                                            readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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

        .table1 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(6) {
            min-width: 250px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(7) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table1 th:nth-child(8) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table1 th:nth-child(2) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .table932 th:nth-child(1) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(2) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(3) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(4) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(5) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(6) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table932 th:nth-child(7) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .texto-blanco {
            color: white !important;
        }

        .table-200 th:nth-child(1) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(2) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(3) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(5) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-200 th:nth-child(6) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .tp-column {
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection--multiple {
            width: 100% !important;
        }
    </style>

    <script>
        // Abre el modal al hacer clic en el botón
        document.getElementById('openModal').addEventListener('click', function() {
            document.getElementById('customModal').style.display = 'block';
        });

        // Cierra el modal al hacer clic en el botón de cerrar
        document.getElementById('closeModal').addEventListener('click', function() {
            document.getElementById('customModal').style.display = 'none';
        });

        // Cierra el modal al hacer clic fuera del contenido
        window.addEventListener('click', function(event) {
            if (event.target === document.getElementById('customModal')) {
                document.getElementById('customModal').style.display = 'none';
            }
        });

        // Cierra el modal al presionar la tecla "ESC"
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.getElementById('customModal').style.display = 'none';
            }
        });

        $(document).ready(function() {
            $('#searchInput1').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos1 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });

    </script>
    <script>
        $(document).ready(function() {
            // Inicializar el select2
            $('#operacion').select2({
                placeholder: 'Seleccione una opcion',
                allowClear: true,
                width: 'resolve'
            });
        }); 
        $(document).ready(function() {
            // Inicializar el select2
            $('#estilo').select2({
                placeholder: 'Seleccione una opcion',
                allowClear: true,
                width: 'resolve'
            });
        }); 
        // Inicializar Select2
        $(document).ready(function() {
            $('#nombre').select2({
                placeholder: 'Selecciona una opción',
                allowClear: true
            });
        });

    </script>

    <!-- Nuevo script para manejar la visibilidad de las columnas y select2 -->
    <script>  
        $(document).ready(function() {
            // Inicializar el select2
            $('#tpSelect').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true,
                width: 'resolve'
            });
    
            // Manejador de cambio del select
            $('#tpSelect').on('change', function() {
                let selectedOptions = $(this).val();
                if (selectedOptions.length > 0) {
                    let lastSelectedOption = selectedOptions[selectedOptions.length - 1];
                    if (lastSelectedOption === 'OTRO') {
                        $('#nuevoConceptoModal').modal('show');
                    } else {
                        addSelectedOption(lastSelectedOption);
                        $(this).val(null).trigger('change'); // Reiniciar el select
                    }
                }
            });
    
            // Manejador del botón de guardar del modal
            $('#guardarNuevoConcepto').on('click', function() {
                let nuevoConcepto = $('#nuevoConceptoInput').val();
                if (nuevoConcepto) {
                    let area = '';
                    <?php if($data['area'] == 'AUDITORIA EN PROCESO'): ?>
                        area = 'proceso';
                    <?php elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA'): ?>
                        area = 'playera';
                    <?php elseif($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                        area = 'empaque';
                    <?php endif; ?>
    
                    fetch('<?php echo e(route('categoria_tipo_problema.store')); ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({
                            nombre: nuevoConcepto.toUpperCase(),
                            area: area
                        })
                    }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            addSelectedOption(nuevoConcepto.toUpperCase());
                            $('#nuevoConceptoModal').modal('hide');
                        } else {
                            alert('Error al guardar el nuevo concepto');
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        alert('Error al guardar el nuevo concepto');
                    });
                } else {
                    alert('Por favor, introduce un concepto válido');
                }
            });
    
            // Ocultar el modal y reiniciar el input del nuevo concepto
            $('#nuevoConceptoModal').on('hidden.bs.modal', function () {
                $('#nuevoConceptoInput').val('');
            });
    
            // Función para agregar una opción seleccionada a la lista de seleccionados
            function addSelectedOption(optionText) {
                let container = $('#selectedOptionsContainer');

                // Crear el div para la nueva opción
                let newOption = $('<div class="selected-option">').text(optionText);

                // Crear el input oculto
                let hiddenInput = $('<input type="hidden" name="tp[]" />').val(optionText);
                newOption.append(hiddenInput);

                // Crear botón para eliminar
                let removeButton = $('<button type="button" class="btn btn-danger btn-sm ml-2">').text('Eliminar');
                removeButton.on('click', function() {
                    newOption.remove();
                    checkContainerValidity();
                });
                newOption.append(removeButton);

                // Crear botón para duplicar
                let duplicateButton = $('<button type="button" class="btn btn-info btn-sm ml-2">').text('+');
                duplicateButton.on('click', function() {
                    // Llamar a la misma función para duplicar la opción
                    addSelectedOption(optionText);
                });
                newOption.prepend(duplicateButton);  // Prepend para que el botón "+" aparezca al inicio

                // Añadir la nueva opción al contenedor
                container.append(newOption);

                checkContainerValidity();
            }
    
            // Verifica si el contenedor tiene opciones seleccionadas y ajusta la validez
            function checkContainerValidity() {
                let container = $('#selectedOptionsContainer');
                if (container.children('.selected-option').length === 0) {
                    container.addClass('is-invalid');
                } else {
                    container.removeClass('is-invalid');
                }
            }
    
            function updateColumnsVisibility() {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                
                if (isNaN(cantidadRechazada) || cantidadRechazada === 0) {
                    // Ocultar las columnas y quitar el "required"
                    $('#tp-column-header, #ac-column-header').addClass('d-none');
                    $('.tp-column, .ac-column').addClass('d-none');
                    $('#selectedOptionsContainer, #ac').prop('required', false);
    
                    // Quitar cualquier validación pendiente del contenedor
                    $('#selectedOptionsContainer').removeClass('is-invalid');
                    $('#selectedOptionsContainer').prop('required', false); // Asegurarse de que no sea obligatorio
    
                } else {
                    // Mostrar las columnas y volver a poner el "required"
                    $('#tp-column-header, #ac-column-header').removeClass('d-none');
                    $('.tp-column, .ac-column').removeClass('d-none');
                    $('#selectedOptionsContainer, #ac').prop('required', true);
    
                    // Validar si hay opciones seleccionadas
                    checkContainerValidity();
                }
            }
    
            // Llamar a la función en cuanto se cargue la página para inicializar
            updateColumnsVisibility();
    
            // Actualizar la visibilidad de las columnas al cambiar el valor de cantidad_rechazada
            $('#cantidad_rechazada').on('input', function() {
                updateColumnsVisibility();
            });
    
            // Modificar el comportamiento del submit
            $('#miFormulario').on('submit', function(event) {
                const cantidadRechazada = parseInt($('#cantidad_rechazada').val());
                const selectedOptionsCount = $('#selectedOptionsContainer').children('.selected-option').length;
                
                if (cantidadRechazada > 0) {
                    if (selectedOptionsCount === 0) {
                        alert('Por favor, selecciona al menos un defecto antes de enviar el formulario.');
                        event.preventDefault(); // Detener el envío del formulario
                        $('#tpSelect').select2('open'); // Abrir el select2 para que el usuario vea dónde seleccionar
                    } else if (selectedOptionsCount !== cantidadRechazada) {
                        alert(`El número de defectos seleccionados debe ser exactamente ${cantidadRechazada}.`);
                        event.preventDefault(); // Detener el envío del formulario
                    }
                } else {
                    // No validar el contenedor cuando la cantidad rechazada es 0
                    $('#selectedOptionsContainer').prop('required', false);
                }
            });
        });
    </script>
    

    <script>
        function actualizarEstilo(nuevoEstilo) {
            // Obtener la URL actual
            let url = new URL(window.location.href);
            let params = new URLSearchParams(url.search);
        
            // Actualizar el parámetro 'estilo'
            params.set('estilo', nuevoEstilo);
        
            // Construir la nueva URL
            url.search = params.toString();
        
            // Realizar la solicitud AJAX para obtener el cliente correspondiente al estilo seleccionado
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route("obtenerCliente1")); ?>',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>',
                    itemid: nuevoEstilo
                },
                success: function(response) {
                    console.log(response); // Verificar los datos recibidos
                    // Actualizar el valor del campo "cliente" con el cliente obtenido de la respuesta AJAX
                    $('#cliente').val(response.cliente);
    
                    // Actualizar la URL para reflejar el cambio en el estilo y cliente
                    window.history.pushState({}, '', url.toString());
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    </script>
    
    <script>
        function cambiarAInput(selectElement) {
            // Verifica si se seleccionó "Otra operación"
            if (selectElement.value === "otra") {
                // Destruye el select2 para permitir la manipulación directa del select
                $(selectElement).select2('destroy');

                // Crear un nuevo input con los mismos atributos
                const input = document.createElement("input");
                input.type = "text";
                input.name = selectElement.name;
                input.id = selectElement.id;
                input.className = selectElement.className;
                input.required = true;
                input.placeholder = "Ingresa la operación";

                // Transformar el texto a mayúsculas mientras se escribe
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Reemplazar el select por el input
                selectElement.parentNode.replaceChild(input, selectElement);
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\aseguramientoCalidad\auditoriaProceso.blade.php ENDPATH**/ ?>