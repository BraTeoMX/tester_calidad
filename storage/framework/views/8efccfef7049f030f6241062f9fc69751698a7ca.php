

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
                            <h3 class="card-title"><?php echo e($data['area']); ?></h3>
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
                    <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formRegistroAuditoriaProceso')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" class="form-control" name="area" id="area"
                            value="<?php echo e($data['area']); ?>">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>MODULO</th>
                                        <th>ESTILO</th>
                                        <th>TEAM LEADER</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                        <th>CLIENTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" name="modulo" id="modulo"
                                                value="<?php echo e($data['modulo']); ?>" readonly></td>
                                        <td><input type="text" class="form-control" name="estilo" id="estilo"
                                                value="<?php echo e($data['estilo']); ?>" readonly></td>
                                        <td><input type="text" class="form-control" name="team_leader" id="team_leader"
                                                value="<?php echo e($data['team_leader']); ?>" readonly></td>
                                        <td><input type="text" class="form-control" name="auditor" id="auditor"
                                                value="<?php echo e($data['auditor']); ?>" readonly></td>
                                        <td><input type="text" class="form-control" name="turno" id="turno"
                                                value="<?php echo e($data['turno']); ?>" readonly></td>
                                        <td><input type="text" class="form-control" name="cliente" id="cliente"
                                                value="<?php echo e($data['cliente']); ?>" readonly></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <?php if($estatusFinalizar): ?>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table flex-container">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>OPERACION</th>
                                            <th>PIEZAS AUDITADAS</th>
                                            <th>PIEZAS RECHAZADOS</th>
                                            <th>TIPO DE PROBLEMA</th>
                                            <th>ACCION CORRECTIVA</th>
                                            <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                            <?php else: ?>
                                                <th>P x P</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <input type="hidden" name="nombre_hidden" id="nombre_hidden" value="">
                                            <td>
                                                <button class="btn btn-secondary" type="button" onclick="resetForm()">Restablecer</button>
                                                <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción" onchange="showOtherOptions()">
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="OTRO">OTRO</option>
                                                    <option value="UTILITY">UTILITY</option>
                                                    <?php if($auditorPlanta == 'Planta1'): ?>
                                                        <?php $__currentLoopData = $nombresPlanta1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->name); ?>"><?php echo e($nombre->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php elseif($auditorPlanta == 'Planta2'): ?>
                                                        <?php $__currentLoopData = $nombresPlanta2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->name); ?>"><?php echo e($nombre->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                                
                                            
                                                <div id="otroOptions" style="display: none;">
                                                    <select name="modulo_adicional" id="module" class="form-control" onchange="loadNames()">
                                                        <option value="">Selecciona un módulo</option>
                                                    </select>
                                                    <select name="nombre" id="name" class="form-control">
                                                        <option value="">Selecciona un nombre</option>
                                                    </select>
                                                </div>

                                                <div id="utilityOptions" style="display: none;">
                                                    <select name="utility" id="utility" class="form-control">
                                                        <option value="">Selecciona un Utility</option>
                                                    </select>
                                                </div>
                                                
                                            </td>
                                            
                                            <script>
                                                function showOtherOptions() {
                                                    var select = document.getElementById("nombre");
                                                    var otroOptions = document.getElementById("otroOptions");
                                                    var utilityOptions = document.getElementById("utilityOptions");
                                                    var nombreHidden = document.getElementById("nombre_hidden");

                                                    if (select.value !== "OTRO" && select.value !== "UTILITY") {
                                                        select.style.display = "block";
                                                        select.disabled = false;
                                                        otroOptions.style.display = "none";
                                                        utilityOptions.style.display = "none";
                                                        nombreHidden.value = select.value; // Actualiza el campo oculto con el valor seleccionado del primer select
                                                    } else if (select.value === "UTILITY") {
                                                        select.style.display = "none";
                                                        select.disabled = true;
                                                        otroOptions.style.display = "none";
                                                        utilityOptions.style.display = "block";
                                                        loadUtilities(); // Cargar los utilities disponibles
                                                    } else {
                                                        select.style.display = "none";
                                                        select.disabled = true;
                                                        otroOptions.style.display = "block";
                                                        utilityOptions.style.display = "none";
                                                        loadModules(); // Cargar los módulos disponibles
                                                    }
                                                }
                                            
                                                function loadModules() {
                                                    fetch("<?php echo e(route('modules.getModules')); ?>")
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            var select = document.getElementById("module");
                                                            select.innerHTML = "";
                                                            data.forEach(module => {
                                                                var option = document.createElement("option");
                                                                option.text = module.moduleid;
                                                                option.value = module.moduleid;
                                                                select.appendChild(option);
                                                            });
                                                        });
                                                }
                                            
                                                function loadNames() {
                                                    var moduleid = document.getElementById("module").value;
                                                    fetch("<?php echo e(route('modules.getNamesByModule')); ?>?moduleid=" + moduleid)
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            var select = document.getElementById("name");
                                                            select.innerHTML = "";
                                                            data.forEach(name => {
                                                                var option = document.createElement("option");
                                                                option.text = name.name;
                                                                option.value = name.name;
                                                                select.appendChild(option);
                                                            });
                                                        });
                                                }
                                            
                                                // Cargar los módulos iniciales
                                                loadModules();
                                                function loadUtilities() {
                                                    fetch("<?php echo e(route('utilities.getUtilities')); ?>")
                                                        .then(response => response.json())
                                                        .then(data => {
                                                            var select = document.getElementById("utility");
                                                            select.innerHTML = "";
                                                            data.forEach(utility => {
                                                                var option = document.createElement("option");
                                                                option.text = utility.nombre; // Usa 'nombre' en lugar de 'name'
                                                                option.value = utility.nombre; // Usa 'nombre' en lugar de 'name'
                                                                select.appendChild(option);
                                                            });
                                                        });
                                                }
                                                
                                            </script>
                                            <script>
                                                function resetForm() {
                                                    var select = document.getElementById("nombre");
                                                    var otroOptions = document.getElementById("otroOptions");
                                                    var utilityOptions = document.getElementById("utilityOptions");
                                                    var nombreHidden = document.getElementById("nombre_hidden");

                                                    select.style.display = "block";
                                                    select.disabled = false;
                                                    otroOptions.style.display = "none";
                                                    utilityOptions.style.display = "none";
                                                    nombreHidden.value = ""; // Restablecer el valor del campo oculto

                                                    // Limpiar select de módulos y nombres si fuera necesario
                                                    var moduleSelect = document.getElementById("module");
                                                    moduleSelect.innerHTML = "<option value=''>Selecciona un módulo</option>";

                                                    var nameSelect = document.getElementById("name");
                                                    nameSelect.innerHTML = "<option value=''>Selecciona un nombre</option>";

                                                    // Cargar los módulos iniciales
                                                    loadModules();
                                                }

                                            </script>
                                            
                                            <td><input type="text" class="form-control" name="operacion" id="operacion"
                                                    required></td>
                                            <td><input type="text" class="form-control" name="cantidad_auditada"
                                                    id="cantidad_auditada" required></td>
                                            <td><input type="text" class="form-control" name="cantidad_rechazada"
                                                    id="cantidad_rechazada" required></td>
                                            <td>
                                                <select name="tp[]" id="tp" class="form-control" required multiple title="Por favor, selecciona una opción"> 
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="NINGUNO">NINGUNO</option>
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
                                            </td>
                                                    
                                            <td>
                                                <select name="ac" id="ac" class="form-control" required
                                                    title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="NINGUNO">NINGUNO</option>
                                                    <?php if($data['area'] == 'AUDITORIA EN PROCESO'): ?>
                                                        <?php $__currentLoopData = $categoriaACProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($proceso->accion_correctiva); ?>">
                                                                <?php echo e($proceso->accion_correctiva); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php elseif($data['area'] == 'AUDITORIA EN PROCESO PLAYERA'): ?>
                                                        <?php $__currentLoopData = $categoriaACPlayera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($playera->accion_correctiva); ?>">
                                                                <?php echo e($playera->accion_correctiva); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php elseif($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                        <?php $__currentLoopData = $categoriaACEmpaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empque): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($empque->accion_correctiva); ?>">
                                                                <?php echo e($empque->accion_correctiva); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                <?php else: ?>
                                                    <input type="text" class="form-control" name="pxp" id="pxp">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success">GUARDAR</button>
                        <?php endif; ?>
                    </form>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <?php if($mostrarRegistro): ?>
                        <?php if($estatusFinalizar): ?>
                            <h2>Registro</h2>
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Operacion </th>
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
                                                    <input type="text" class="form-control" name="nombre"
                                                        value="<?php echo e($registro->nombre); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="operacion"
                                                        value="<?php echo e($registro->operacion); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_auditada"
                                                        value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_rechazada"
                                                        value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="tp"
                                                        value="<?php echo e($registro->tp); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="ac"
                                                        value="<?php echo e($registro->ac); ?>" readonly>
                                                </td>
                                                <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                <?php else: ?>
                                                    <td>
                                                        <input type="text" class="form-control" name="pxp"
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
                                        <textarea class="form-control" name="observacion" id="observacion" rows="3" readonly><?php echo e($registro->observacion); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <h2>Registro</h2>

                                <table class="table">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Paro</th>
                                            <th>Nombre</th>
                                            <th>Operacion </th>
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
                                                    <input type="text" class="form-control" name="nombre"
                                                        value="<?php echo e($registro->nombre); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="operacion"
                                                        value="<?php echo e($registro->operacion); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_auditada"
                                                        value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_rechazada"
                                                        value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                                </td>
                                                <form action="<?php echo e(route('aseguramientoCalidad.formUpdateDeleteProceso')); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                                    <td>
                                                        <input type="text" class="form-control" readonly
                                                               value="<?php echo e(implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray())); ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="ac"
                                                        value="<?php echo e($registro->ac); ?>" readonly>
                                                    </td>
                                                    <?php if($data['area'] == 'AUDITORIA EN EMPAQUE'): ?>
                                                    <?php else: ?>
                                                        <td>
                                                            <input type="text" class="form-control" name="pxp_text"
                                                                id="pxp_text_<?php echo e($registro->id); ?>"
                                                                value="<?php echo e($registro->pxp); ?>" readonly>
                                                            <input type="hidden" name="pxp"
                                                                id="pxp_hidden_<?php echo e($registro->id); ?>"
                                                                value="<?php echo e($registro->pxp); ?>">
                                                        </td>
                                                        <script>
                                                            document.getElementById('pxp_text_<?php echo e($registro->id); ?>').addEventListener('input', function() {
                                                                document.getElementById('pxp_hidden_<?php echo e($registro->id); ?>').value = this.value;
                                                            });
                                                        </script>
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
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="observacion"
                                                class="col-sm-6 col-form-label">Observaciones:</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="observacion" id="observacion" rows="3" placeholder="comentarios"
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
                                        <td><input type="text" class="form-control" value="<?php echo e($registro->nombre); ?>"
                                                readonly></td>
                                        <td><input type="text" class="form-control" 
                                            value="<?php echo e($registro->cantidad_registros); ?>" readonly></td> 
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_auditada); ?>" readonly></td>
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_rechazada); ?>" readonly></td>
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_rechazada != 0 ? round(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0); ?>"
                                                readonly></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <hr>
                    <div class="table-responsive">
                        <h2>Total General </h2>
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
                                    <td><input type="text" class="form-control" name="total_auditada"
                                            id="total_auditada" value="<?php echo e($total_auditada); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_rechazada"
                                            id="total_rechazada" value="<?php echo e($total_rechazada); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_porcentaje"
                                            id="total_porcentaje" value="<?php echo e(number_format($total_porcentaje, 2)); ?>"
                                            readonly></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
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

        .table th:nth-child(1) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table th:nth-child(5) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table th:nth-child(6) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table th:nth-child(7) {
            min-width: 70px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table th:nth-child(3) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }
    </style>

    <script>
        $('#tp').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
    </script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/aseguramientoCalidad/auditoriaProceso.blade.php ENDPATH**/ ?>