

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('danger')): ?>
        <div class="alert alert-danger">
            <?php echo e(session('danger')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('warning')): ?>
        <div class="alert alert-warning">
            <?php echo e(session('warning')); ?>

        </div>
    <?php endif; ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: PROCESO</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="<?php echo e(route('crearDefectoProceso')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoProceso" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaTipoProblemaProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($tp->nombre); ?></td>
                                <td><?php echo e($tp->estado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoDefectoProceso', $tp->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($tp->estado == 1): ?>
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: PLAYERA</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="<?php echo e(route('crearDefectoPlayera')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoPlayera" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaTipoProblemaPlayera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($tp->nombre); ?></td>
                                <td><?php echo e($tp->estado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoDefectoProceso', $tp->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($tp->estado == 1): ?>
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: EMPAQUE</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="<?php echo e(route('crearDefectoEmpaque')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoEmpaque" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaTipoProblemaEmpaque; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($tp->nombre); ?></td>
                                <td><?php echo e($tp->estado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoDefectoEmpaque', $tp->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($tp->estado == 1): ?>
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Utility</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Utility</label>
                    <form action="<?php echo e(route('crearUtility')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del utility" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaUtility" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaUtility; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($dato->nombre); ?></td>
                                <td><?php echo e($dato->numero_empleado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoUtility', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->planta == 'Intimark1'): ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoUtility', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->estado == 1): ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Responsable</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Responsable</label>
                    <form action="<?php echo e(route('crearResponsable')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del Responsable" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaResponsable" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaResponsable; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($dato->nombre); ?></td>
                                <td><?php echo e($dato->numero_empleado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoResponsable', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->planta == 'Intimark1'): ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoResponsable', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->estatus == 1): ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Tecnicos Corte</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Tecnico Corte</label>
                    <form action="<?php echo e(route('crearTecnico')); ?>" method="POST" class="form-inline">
                        <?php echo csrf_field(); ?>
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del Responsable" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaTecnico" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $__currentLoopData = $categoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <tr>
                                <td><?php echo e($dato->nombre); ?></td>
                                <td><?php echo e($dato->numero_empleado); ?></td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoTecnico', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->planta == 'Intimark1'): ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                                <td>
                                    <form action="<?php echo e(route('actualizarEstadoTecnico', $dato->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <?php if($dato->estado == 1): ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        <?php else: ?>
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        <?php endif; ?>
                                    </form>
                                </td>
                              </tr>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Clientes: Porcentajes AQL y Proceso</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form method="POST" action="<?php echo e(route('actualizarClientesPorcentajes')); ?>">
                            <?php echo csrf_field(); ?>
                            <table id="tablaClientesPorcentajes" class="table tablesorter">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>% AQL</th>
                                        <th>% PROCESO</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $clientesPorcentajes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dato): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($dato->nombre); ?></td>
                                        <td>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                max="99.99" 
                                                name="aql[<?php echo e($dato->id); ?>]" 
                                                value="<?php echo e(number_format($dato->aql, 2, '.', '')); ?>" 
                                                class="form-control" 
                                                oninput="validarDecimales(this)"
                                            />
                                        </td>
                                        <td>
                                            <input 
                                                type="number" 
                                                step="0.01" 
                                                min="0" 
                                                max="99.99" 
                                                name="proceso[<?php echo e($dato->id); ?>]" 
                                                value="<?php echo e(number_format($dato->proceso, 2, '.', '')); ?>" 
                                                class="form-control" 
                                                oninput="validarDecimales(this)"
                                            />
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-sm">Actualizar</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>

    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('black')); ?>/js/plugins/chartjs.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            const tableIds = ['#tablaDefectoProceso', '#tablaDefectoPlayera', '#tablaDefectoEmpaque', '#tablaUtility', '#tablaResponsable', '#tablaTecnico'];
            
            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 5,
                        autoWidth: false,
                        responsive: true,
                        columnDefs: [
                            {
                                targets: -1,
                                searchable: false,
                                orderable: false,
                            },
                        ],
                        language: {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            const tableIds = ['#tablaClientesPorcentajes'];
            
            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 10,
                        autoWidth: false,
                        responsive: true,
                        language: {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById('numero_empleado').addEventListener('input', function (e) {
            let value = e.target.value;
            if (value.includes('.')) {
                e.target.value = value.replace('.', '');
            }
        });
    </script>
    <script>
        function validarDecimales(input) {
            // Limitar a 2 decimales
            if (input.value) {
                let value = parseFloat(input.value).toFixed(2);
                input.value = value;
            }
        }
    </script>
    
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\altaYbaja.blade.php ENDPATH**/ ?>