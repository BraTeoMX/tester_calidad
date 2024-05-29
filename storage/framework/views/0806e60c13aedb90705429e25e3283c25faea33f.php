

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
                    <?php if((($conteoParos == 2) && ($finParoModular1 == true)) || (($conteoParos == 4) && ($finParoModular2 == true))): ?>
                        <div class="row">
                            <form method="POST" action="<?php echo e(route('auditoriaAQL.cambiarEstadoInicioParoAQL')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="finalizar_paro_modular" value="1">
                                <input type="hidden" class="form-control" name="modulo" id="modulo" value="<?php echo e($data['modulo']); ?>">
                                <input type="hidden" class="form-control" name="op" id="op" value="<?php echo e($data['op']); ?>">
                                <input type="hidden" class="form-control" name="area" id="area" value="<?php echo e($data['area']); ?>">
                                <input type="hidden" class="form-control" name="team_leader" id="team_leader" value="<?php echo e($data['team_leader']); ?>">


                                <button type="submit" class="btn btn-primary">Fin Paro Modular</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <form method="POST" action="<?php echo e(route('auditoriaAQL.formRegistroAuditoriaProcesoAQL')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" class="form-control" name="area" id="area"
                                value="<?php echo e($data['area']); ?>">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>MODULO</th>
                                            <th>OP</th>
                                            <th>CLIENTE</th>
                                            <th>TEAM LEADER</th>
                                            <th>AUDITOR</th>
                                            <th>TURNO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" class="form-control" name="modulo" id="modulo"
                                                    value="<?php echo e($data['modulo']); ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="op" id="op"
                                                    value="<?php echo e($data['op']); ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="cliente" id="cliente"
                                                    value="<?php echo e($datoUnicoOP->customername); ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="team_leader" id="team_leader"
                                                    value="<?php echo e($data['team_leader']); ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="auditor" id="auditor"
                                                    value="<?php echo e($data['auditor']); ?>" readonly></td>
                                            <td><input type="text" class="form-control" name="turno" id="turno"
                                                    value="<?php echo e($data['turno']); ?>" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                            <?php if($estatusFinalizar): ?>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table32"> 
                                        <thead class="thead-primary">
                                            <tr>
                                                <th>NOMBRE</th>
                                                <th># BULTO</th>
                                                <th>PIEZAS</th>
                                                <th>ESTILO</th>
                                                <th>COLOR</th>
                                                <th>TALLA</th>
                                                <th>PIEZAS INSPECCIONADAS</th>
                                                <th>PIEZAS RECHAZADAS</th>
                                                <th>TIPO DE DEFECTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="nombre" id="nombre" class="form-control" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php if($auditorPlanta == 'Planta1'): ?>
                                                            <?php $__currentLoopData = $nombreProcesoToAQLPlanta1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($opcion['nombre'] ?? $opcion['name']); ?>"><?php echo e($opcion['nombre'] ?? $opcion['name']); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($auditorPlanta == 'Planta2'): ?>
                                                            <?php $__currentLoopData = $nombreProcesoToAQLPlanta2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opcion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($opcion['nombre'] ?? $opcion['name']); ?>"><?php echo e($opcion['nombre'] ?? $opcion['name']); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="bulto" id="bulto" class="form-control" required title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $datoBultos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bulto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($bulto->prodpackticketid); ?>" data-estilo="<?php echo e($bulto->itemid); ?>" data-color="<?php echo e($bulto->colorname); ?>" data-talla="<?php echo e($bulto->inventsizeid); ?>" data-pieza="<?php echo e($bulto->qty); ?>">
                                                                <?php echo e($bulto->prodpackticketid); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="pieza" id="pieza" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="estilo" id="estilo" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="color" id="color" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="talla" id="talla" readonly>
                                                </td>
                                                
                                                <script>
                                                    $(document).ready(function() {
                                                        $('#bulto').change(function() {
                                                            var selectedOption = $(this).find(':selected');
                                                            $('#pieza').val(selectedOption.data('pieza'));
                                                            $('#estilo').val(selectedOption.data('estilo'));
                                                            $('#color').val(selectedOption.data('color'));
                                                            $('#talla').val(selectedOption.data('talla'));
                                                        });
                                                
                                                        // Actualizar valores al cargar la página si una opción está seleccionada por defecto
                                                        var selectedOption = $('#bulto').find(':selected');
                                                        $('#pieza').val(selectedOption.data('pieza'));
                                                        $('#estilo').val(selectedOption.data('estilo'));
                                                        $('#color').val(selectedOption.data('color'));
                                                        $('#talla').val(selectedOption.data('talla'));
                                                    });
                                                </script>                                            
                                                
                                                <td><input type="text" class="form-control" name="cantidad_auditada"
                                                        id="cantidad_auditada" required></td>
                                                <td><input type="text" class="form-control" name="cantidad_rechazada"
                                                        id="cantidad_rechazada" required></td>
                                                <td>
                                                    <select name="tp[]" id="tp" class="form-control" required multiple 
                                                        title="Por favor, selecciona una opción">
                                                        <option value="NINGUNO">NINGUNO</option>
                                                        <?php if($data['area'] == 'AUDITORIA AQL'): ?>
                                                            <?php $__currentLoopData = $categoriaTPProceso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proceso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($proceso->nombre); ?>"><?php echo e($proceso->nombre); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php elseif($data['area'] == 'AUDITORIA AQL PLAYERA'): ?>
                                                            <?php $__currentLoopData = $categoriaTPPlayera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $playera): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($playera->nombre); ?>"><?php echo e($playera->nombre); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="submit" class="btn btn-verde">Guardar</button>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <?php if($mostrarRegistro): ?>
                        <?php if($estatusFinalizar): ?>
                            <h2>Registro</h2>
                            <table class="table table55"> 
                                <thead class="thead-primary">
                                    <tr>
                                        <th>PARO</th>
                                        <th># BULTO</th>
                                        <th>PIEZAS</th>
                                        <th>TALLA</th>
                                        <th>COLOR</th>
                                        <th>ESTILO</th>
                                        <th>PIEZAS INSPECCIONADAS</th>
                                        <th>PIEZAS RECHAZADAS</th>
                                        <th>TIPO DE DEFECTO</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $mostrarRegistro; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" name="bulto"
                                                value="<?php echo e($registro->bulto); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pieza"
                                                value="<?php echo e($registro->pieza); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="talla"
                                                value="<?php echo e($registro->talla); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="color" id="color"
                                                value="<?php echo e($registro->color); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="estilo" id="estilo"
                                                value="<?php echo e($registro->estilo); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="cantidad_auditada" id="cantidad_auditada"
                                                value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="cantidad_rechazada" id="cantidad_rechazada"
                                                value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                            </td>
                                            
                                            <form action="<?php echo e(route('auditoriaAQL.formUpdateDeleteProceso')); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                                <td>
                                                    <input type="text" class="form-control" readonly
                                                           value="<?php echo e(implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray())); ?>">
                                                </td>
                                                <td>
                                                    <?php echo e($registro->created_at->format('H:i:s')); ?>

                                                </td>
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

                                <table class="table table55">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>PARO</th>
                                            <th># BULTO</th>
                                            <th>PIEZAS</th>
                                            <th>TALLA</th>
                                            <th>COLOR</th>
                                            <th>ESTILO</th>
                                            <th>PIEZAS INSPECCIONADAS</th>
                                            <th>PIEZAS RECHAZADAS</th>
                                            <th>TIPO DE DEFECTO</th>
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
                                                        <form method="POST" action="<?php echo e(route('auditoriaAQL.cambiarEstadoInicioParoAQL')); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="idCambio" value="<?php echo e($registro->id); ?>">
                                                            <button type="submit" class="btn btn-primary">Fin Paro AQL</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="bulto"
                                                    value="<?php echo e($registro->bulto); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="pieza"
                                                    value="<?php echo e($registro->pieza); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="talla"
                                                    value="<?php echo e($registro->talla); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="color" id="color"
                                                    value="<?php echo e($registro->color); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="estilo" id="estilo"
                                                    value="<?php echo e($registro->estilo); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_auditada" id="cantidad_auditada"
                                                    value="<?php echo e($registro->cantidad_auditada); ?>" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="cantidad_rechazada" id="cantidad_rechazada"
                                                    value="<?php echo e($registro->cantidad_rechazada); ?>" readonly>
                                                </td>
                                                
                                                <form action="<?php echo e(route('auditoriaAQL.formUpdateDeleteProceso')); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="id" value="<?php echo e($registro->id); ?>">
                                                    <td>
                                                        <input type="text" class="form-control" readonly
                                                               value="<?php echo e(implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray())); ?>">
                                                    </td>
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
                                <form action="<?php echo e(route('auditoriaAQL.formFinalizarProceso')); ?>" method="POST">
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
                        <h2>Piezas auditadas por dia</h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Total de piezas Muestra Auditadas </th>
                                    <th>Total de piezas Muestra Rechazadas</th>
                                    <th>Porcentaje AQL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $registrosIndividual; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_auditada); ?>" readonly></td>
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_rechazada); ?>" readonly></td>
                                        <td><input type="text" class="form-control"
                                                value="<?php echo e($registro->total_rechazada != 0 ? number_format(($registro->total_rechazada / $registro->total_auditada) * 100, 2) : 0); ?>"
                                                readonly></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <table class="table contenedor-tabla">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de piezas en bultos Auditados</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $registrosIndividualPieza; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $registro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><input type="text" class="form-control"
                                        value="<?php echo e($registro->total_pieza); ?>" readonly></td>
                                        
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>

                    <hr>
                    <div class="table-responsive">
                        <h2>Total por Bultos </h2>
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>total de Bultos Auditados</th>
                                    <th>total de Bultos Rechazados</th>
                                    <th>Porcentaje Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" class="form-control" name="conteo_bulto"
                                            id="conteo_bulto" value="<?php echo e($conteoBultos); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_rechazada"
                                            id="total_rechazada" value="<?php echo e($conteoPiezaConRechazo); ?>" readonly></td>
                                    <td><input type="text" class="form-control" name="total_porcentaje"
                                            id="total_porcentaje" value="<?php echo e(number_format($porcentajeBulto, 2)); ?>"
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

        .table32 th:nth-child(2) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table32 th:nth-child(4) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .table55 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        

        /* Estilo general para el contenedor de la tabla */
        .contenedor-tabla {
            width: 30%; /* Ajusta el ancho según tus necesidades */
            
        }


        @media (max-width: 768px) {
            .table23 th:nth-child(3) {
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
        $('#bulto').select2({
            placeholder: 'Seleccione una o varias opciones',
            allowClear: true,
        });
    </script>

    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['activePage' => 'AQL', 'titlePage' => __('AQL')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\calidad2\resources\views/auditoriaAQL/auditoriaAQL.blade.php ENDPATH**/ ?>