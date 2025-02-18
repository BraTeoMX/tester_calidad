

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
                            <table class="table table10">
                                <thead class="thead-primary"> 
                                    <tr>
                                        <th>AREA</th>
                                        <th>MODULO</th>
                                        <th>ESTILO</th> 
                                        <th>CLIENTE</th>
                                        <th>SUPERVISOR</th>
                                        <th>GERENTE DE PRODUCCION</th>
                                        <th>AUDITOR</th>
                                        <th>TURNO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="area" id="area"
                                            value="AUDITORIA EN PROCESO" readonly  />
                                        </td>
                                        <td>
                                            <select name="modulo" id="modulo" class="form-control" required title="Por favor, selecciona una opción" onchange="obtenerSupervisor()">
                                                <option value="" selected>Selecciona una opción</option> 
                                                <?php $__currentLoopData = $listaModulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modulo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($modulo->moduleid); ?>" data-itemid="<?php echo e($modulo->itemid); ?>">
                                                        <?php echo e($modulo->moduleid); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control texto-blanco" name="estilo" id="estilo" required>
                                                <option value="">Selecciona una opción</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control texto-blanco" name="cliente" id="cliente" readonly required />
                                        </td>
                                        
                                        <td>
                                            <select name="team_leader" id="team_leader" class="form-control" required>
                                                <!-- Las opciones se llenarán dinámicamente con la llamada AJAX -->
                                            </select>                                            
                                        </td>
                                        <td>
                                            <select name="gerente_produccion" class="form-control" required title="Por favor, selecciona una opción">
                                               <!-- <option value="" selected>Selecciona una opción</option> -->
                                                <?php $__currentLoopData = $gerenteProduccion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gerente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($gerente->nombre); ?>">
                                                        <?php echo e($gerente->nombre); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="auditor" id="auditor"
                                            value="<?php echo e($auditorDato); ?>" readonly  /></td>
                                        <td><input type="text" class="form-control me-2 texto-blanco" name="turno" id="turno"
                                                    value="1" readonly  /></td>                             
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
                                                                <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
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
                                                                <input type="text" id="searchInput2" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos2">
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
                                                                                        <input type="hidden" name="gerente_produccion" value="<?php echo e($proceso->gerente_produccion); ?>">
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
                                                                <input type="text" id="searchInput3" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos3">
                                                                        <?php $__currentLoopData = $empaqueActual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <tr>
                                                                                <form method="POST" action="<?php echo e(route('aseguramientoCalidad.formAltaProceso')); ?>">
                                                                                    <?php echo csrf_field(); ?>
                                                                                    <input type="hidden" name="area" value="<?php echo e($proceso->area); ?>">
                                                                                    <input type="hidden" name="modulo" value="<?php echo e($proceso->modulo); ?>">
                                                                                    <input type="hidden" name="cliente" value="<?php echo e($proceso->cliente); ?>">
                                                                                    <input type="hidden" name="estilo" value="<?php echo e($proceso->estilo); ?>">
                                                                                    <input type="hidden" name="team_leader" value="<?php echo e($proceso->team_leader); ?>">
                                                                                    <input type="hidden" name="auditor" value="<?php echo e($proceso->auditor); ?>">
                                                                                    <input type="hidden" name="turno" value="<?php echo e($proceso->turno); ?>">
                                                                                    <td><button class="btn btn-primary">Acceder</button></td>
                                                                                </form>
                                                                                
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
                                                                <input type="text" id="searchInput4" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Accion</th>
                                                                            <th>Módulo</th>
                                                                            <th>Estilo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="tablaProcesos4">
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

    <style>
        #searchInput1::placeholder, #searchInput2::placeholder, #searchInput3::placeholder, #searchInput4::placeholder {
            color: rgba(255, 255, 255, 0.85);
            font-weight: bold;
        }
      
        #searchInput1, #searchInput2, #searchInput3, #searchInput4 {
            background-color: #1a1f30;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#modulo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
            $('#estilo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });

    </script>

    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }

        .texto-blanco {
            color: white !important;
        }

        .table10 th:nth-child(1) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(2) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(4) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table10 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(6) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table10 th:nth-child(7) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>
    <script>
        function obtenerSupervisor() {
            var moduleid = $('#modulo').val();  // Obtén el valor del select "modulo"
            
            if (moduleid) {
                $.ajax({
                    url: '/obtener-supervisor',  // Ruta para obtener el supervisor
                    type: 'GET',
                    data: { moduleid: moduleid },
                    success: function(response) {
                        var $teamLeader = $('#team_leader');
                        $teamLeader.empty();  // Limpia el select antes de llenarlo

                        // Si hay un supervisor relacionado, lo agrega como seleccionado
                        if (response.supervisorRelacionado) {
                            $teamLeader.append('<option value="' + response.supervisorRelacionado.name + '" selected>' + response.supervisorRelacionado.name + '</option>');
                        }

                        // Agrega las otras opciones de supervisores
                        $.each(response.supervisores, function(index, supervisor) {
                            if (response.supervisorRelacionado && supervisor.name === response.supervisorRelacionado.name) {
                                // Ya se agregó el supervisor relacionado como opción seleccionada, por lo que se omite
                                return;
                            }
                            // Agrega el resto de los supervisores
                            $teamLeader.append('<option value="' + supervisor.name + '">' + supervisor.name + '</option>');
                        });

                        // Si no hay supervisores disponibles, muestra un mensaje opcional
                        if (!response.supervisores.length) {
                            $teamLeader.append('<option value="">No hay supervisores disponibles</option>');
                        }
                    },
                    error: function() {
                        alert('Error al obtener los supervisores');
                    }
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('#modulo').change(function() {
                var moduleid = $(this).val();
                $('#estilo').html(''); // Limpiar el select anterior
    
                if (moduleid === '830A' || moduleid === '831A') {
                    // Realizar solicitud AJAX para obtener todos los estilos únicos 
                    $.ajax({
                        url: '<?php echo e(route("obtenerTodosLosEstilosUnicos")); ?>',
                        type: 'POST',
                        data: {
                            '_token': '<?php echo e(csrf_token()); ?>'
                        },
                        success: function(response) {
                            console.log(response); // Verificar los datos recibidos
                            if (response.itemids && response.itemids.length > 0) {
                                var options = '<option value="">Selecciona una opción</option>';
                                $.each(response.itemids, function(index, itemid) {
                                    options += '<option value="' + itemid + '">' + itemid + '</option>';
                                });
                                $('#estilo').html(options);
                            } else {
                                $('#estilo').html('<option value="">No se encontraron estilos</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                } else {
                    // Realizar solicitud AJAX para obtener los estilos según el módulo seleccionado
                    $.ajax({
                        url: '<?php echo e(route("obtenerItemId")); ?>',
                        type: 'POST',
                        data: {
                            '_token': '<?php echo e(csrf_token()); ?>',
                            'moduleid': moduleid
                        },
                        success: function(response) {
                            console.log(response); // Verificar los datos recibidos
                            if (response.itemids && response.itemids.length > 0) {
                                var options = '<option value="">Selecciona una opción</option>';
                                $.each(response.itemids, function(index, itemid) {
                                    options += '<option value="' + itemid + '">' + itemid + '</option>';
                                });
                                $('#estilo').html(options);
                            } else {
                                $('#estilo').html('<option value="">No se encontraron estilos</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });
    
            // Manejar la selección del estilo para actualizar el input de cliente
            $('#estilo').change(function() {
                var itemid = $(this).val();
    
                // Realizar solicitud AJAX para obtener el cliente correspondiente al estilo seleccionado
                $.ajax({ 
                    type: 'POST',
                    url: '<?php echo e(route("obtenerCliente1")); ?>',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        itemid: itemid
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


    <script>
        $(document).ready(function() {
            $('#searchInput1').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos1 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput2').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos2 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput3').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos3 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });

            $('#searchInput4').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos4 tr').filter(function() {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });
    </script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\aseguramientoCalidad\altaProceso.blade.php ENDPATH**/ ?>