

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
    <div class="row">

        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: PROCESO</h3>
                </div>
                <div class="card-body">
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
                    <h3 class="card-title"><i class="tim-icons icon-send text-info"></i> Incidencias</h3>
                    <h5 class="card-title">AQL :      45 % </h5>
                    <h5 class="card-title">PROCESOS : 45 % </h5>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
    <script src="<?php echo e(asset('black')); ?>/js/plugins/chartjs.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Verifica si la tabla ya está inicializada antes de inicializarla nuevamente
            if (!$.fn.dataTable.isDataTable('#tablaDefectoProceso')) {
                $('#tablaDefectoProceso').DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                    columnDefs: [
                        {
                            targets: 2, // Índice de la columna a excluir (0-indexed, es decir, la tercera columna es índice 2)
                            searchable: false, // Excluir de la búsqueda
                            orderable: false, // Excluir del ordenamiento
                        },
                    ],
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDefectoPlayera')) {
                $('#tablaDefectoPlayera').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                    columnDefs: [
                        {
                            targets: 2, // Índice de la columna a excluir (0-indexed, es decir, la tercera columna es índice 2)
                            searchable: false, // Excluir de la búsqueda
                            orderable: false, // Excluir del ordenamiento
                        },
                    ],
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico2')) {
                $('#tablaDinamico2').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico3')) {
                $('#tablaDinamico3').DataTable({
                    lengthChange: false,
                    searching: false,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        
            if (!$.fn.dataTable.isDataTable('#tablaDinamico4')) {
                $('#tablaDinamico4').DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 5,
                    autoWidth: false,
                    responsive: true,
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'profile'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/altaYbaja.blade.php ENDPATH**/ ?>