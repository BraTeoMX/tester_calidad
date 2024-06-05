

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
            font-size: 1rem;
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
    </style>
    
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                    
                    <?php if(isset($encabezadoAuditoriaCorte->estatus)): ?>
                        <h3 id="estatusValue">Estatus: <?php echo e($encabezadoAuditoriaCorte->estatus); ?></h3>
                    <?php endif; ?>
                    <?php if(isset($encabezadoAuditoriaCorte->evento)): ?>
                        <h4>Evento: <?php echo e($encabezadoAuditoriaCorte->evento); ?> / <?php echo e($encabezadoAuditoriaCorte->total_evento); ?> </h4>
                    <?php endif; ?>
                </div>
                <hr> 
                <?php if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == "proceso"): ?>
                <form method="POST" action="<?php echo e(route('auditoriaCorte.formEncabezadoAuditoriaCorte')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="orden" value="<?php echo e($datoAX->op); ?>">
                    <input type="hidden" name="idEncabezadoAuditoriaCorte" value="<?php echo e($encabezadoAuditoriaCorte->id); ?>">
                    <input type="hidden" name="qtysched_id" value="<?php echo e($datoAX->qtysched); ?>">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Orden: <?php echo e($encabezadoAuditoriaCorte->orden_id); ?></h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Estilo: <?php echo e($encabezadoAuditoriaCorte->estilo_id); ?></h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Cliente: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->cliente_id : ''); ?></h4>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <h4>Material: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->material : ''); ?></h4>
                        </div>
                        <?php if($encabezadoAuditoriaCorte->temporada_id): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <h4>Color: <?php echo e($encabezadoAuditoriaCorte->temporada_id); ?></h4>
                            </div>
                        <?php else: ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <label for="temporada_id" class="col-sm-6 col-form-label">Temporada</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="temporada_id" id="temporada_id" placeholder="..." required/>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($encabezadoAuditoriaCorte->color_id): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                <h4>Color: <?php echo e($encabezadoAuditoriaCorte->color_id); ?></h4>
                            </div>
                        <?php else: ?>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                                <label for="color_id" class="col-sm-6 col-form-label">Color</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="color_id" id="color_id" placeholder="..." required/>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="pieza" class="col-sm-6 col-form-label">PIEZAS</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="pieza" id="pieza"
                                    placeholder="..." required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="lienzo" class="col-sm-6 col-form-label">LIENZOS</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="lienzo" id="lienzo"
                                    placeholder="..." required/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn-verde-xd">Guardar</button>
                    </div>
                </form>
                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Orden: <?php echo e($encabezadoAuditoriaCorte->orden_id); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Estilo: <?php echo e($encabezadoAuditoriaCorte->estilo_id); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Temporada: <?php echo e($encabezadoAuditoriaCorte->temporada_id); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Cliente: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->cliente_id : ''); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Material: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->material : ''); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Color: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->color_id : ''); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Lienzo: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->lienzo : ''); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Piezas: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->pieza : ''); ?></h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Piezas Total: <?php echo e(isset($encabezadoAuditoriaCorte) ? $encabezadoAuditoriaCorte->qtysched_id : ''); ?></h4>
                    </div>
                </div>
                <?php endif; ?>
                <div id="accordion">
                    <!--Inicio acordeon 1 -->
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button id="btnOne" class="btn btn-info btn-block" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    - - AUDITORIA DE MARCADA - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                
                                <?php if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'proceso'): ?>
                                    <p> - </p>
                                <?php elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada'): ?> 
                                <form method="POST"
                                    action="<?php echo e(route('auditoriaCorte.formAuditoriaMarcada', ['id' => $datoAX->id])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                                    <input type="hidden" name="idAuditoriaMarcada" value="<?php echo e($auditoriaMarcada->id); ?>">
                                    <input type="hidden" name="orden" value="<?php echo e($datoAX->orden); ?>">
                                    
                                    <input type="hidden" name="accion" value="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_orden" class="col-sm-6 col-form-label">Yardas en la
                                                orden</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_orden" id="yarda_orden" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->yarda_orden : ''); ?>"
                                                        required />
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="yarda_orden_estatus"
                                                        id="yarda_orden_estatus1" value="1"
                                                        <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->yarda_orden_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="yarda_orden_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="yarda_orden_estatus"
                                                        id="yarda_orden_estatus2" value="0"
                                                        <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->yarda_orden_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="yarda_orden_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                        <div class="table-responsive">
                                            <p>CANTIDADES ABSOLUTAS</p>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Tallas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <select name="talla<?php echo e($i); ?>" class="form-control">
                                                                <option value="">Selecciona una talla</option>
                                                                <?php $__currentLoopData = $auditoriaMarcadaTalla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sizename): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($sizename); ?>" <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->{'talla'.$i} == $sizename ? 'selected' : ''); ?>>
                                                                        <?php echo e($sizename); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="bulto<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'bulto'.$i} : ''); ?>" />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="total_pieza<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'total_pieza'.$i} : ''); ?>" />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <p>CANTIDADES PARCIALES</p>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Tallas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <select name="talla_parcial<?php echo e($i); ?>" class="form-control">
                                                                <option value="">Selecciona una talla</option>
                                                                <?php $__currentLoopData = $auditoriaMarcadaTalla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sizename): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <option value="<?php echo e($sizename); ?>" <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->{'talla_parcial'.$i} == $sizename ? 'selected' : ''); ?>>
                                                                        <?php echo e($sizename); ?>

                                                                    </option>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </select>
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="bulto_parcial<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'bulto_parcial'.$i} : ''); ?>" />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number"  class="form-control" name="total_pieza_parcial<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'total_pieza_parcial'.$i} : ''); ?>" />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="largo_trazo" class="col-sm-3 col-form-label">Largo Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="largo_trazo" id="largo_trazo" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->largo_trazo : ''); ?>"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_trazo" class="col-sm-3 col-form-label">Ancho Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="ancho_trazo" id="ancho_trazo" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->ancho_trazo : ''); ?>"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div>
                                        <button type="submit" name="accion" class="btn-verde-xd">Guardar</button>
                                        <?php if($mostrarFinalizarMarcada): ?>
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                                
                                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>  
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_orden" class="col-sm-6 col-form-label">Yardas en la orden</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_orden" id="yarda_orden" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->yarda_orden : ''); ?>"
                                                        <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?>

                                                        required />
                                                </div>
                                                <?php if(isset($auditoriaMarcada)): ?>
                                                    <div class="form-check form-check-inline">
                                                        <?php if($auditoriaMarcada->yarda_orden_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <br>
                                        <hr>
                                        <div class="table-responsive">
                                            <p>CANTIDADES ABSOLUTAS</p>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Tallas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <?php if(isset($auditoriaMarcada)): ?>
                                                                <input type="text" class="form-control" value="<?php echo e($auditoriaMarcada->{'talla'.$i}); ?>" readonly />
                                                            <?php else: ?>
                                                                <select name="talla<?php echo e($i); ?>" class="form-control">
                                                                    <option value="">Selecciona una talla</option>
                                                                    <?php $__currentLoopData = $auditoriaMarcadaTalla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sizename): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($sizename); ?>" <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->{'talla'.$i} == $sizename ? 'selected' : ''); ?>>
                                                                            <?php echo e($sizename); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            <?php endif; ?>
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="bulto<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'bulto'.$i} : ''); ?>"
                                                                <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?> />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="total_pieza<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'total_pieza'.$i} : ''); ?>"
                                                                <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?> />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="table-responsive">
                                            <p>CANTIDADES PARCIALES</p>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <td>Tallas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <?php if(isset($auditoriaMarcada)): ?>
                                                                <input type="text" class="form-control" value="<?php echo e($auditoriaMarcada->{'talla_parcial'.$i}); ?>" readonly />
                                                            <?php else: ?>
                                                                <select name="talla_parcial<?php echo e($i); ?>" class="form-control">
                                                                    <option value="">Selecciona una talla</option>
                                                                    <?php $__currentLoopData = $auditoriaMarcadaTalla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sizename): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($sizename); ?>" <?php echo e(isset($auditoriaMarcada) && $auditoriaMarcada->{'talla_parcial'.$i} == $sizename ? 'selected' : ''); ?>>
                                                                            <?php echo e($sizename); ?>

                                                                        </option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            <?php endif; ?>
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td># Bultos</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number" class="form-control" name="bulto_parcial<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'bulto_parcial'.$i} : ''); ?>"
                                                                <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?> />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Piezas</td>
                                                        <?php for($i = 1; $i <= 6; $i++): ?>
                                                        <td>
                                                            <input type="number"  class="form-control" name="total_pieza_parcial<?php echo e($i); ?>"
                                                                value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->{'total_pieza_parcial'.$i} : ''); ?>"
                                                                <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?> />
                                                        </td>
                                                        <?php endfor; ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="largo_trazo" class="col-sm-3 col-form-label">Largo Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="largo_trazo" id="largo_trazo" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->largo_trazo : ''); ?>"
                                                    <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?>

                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_trazo" class="col-sm-3 col-form-label">Ancho Trazo </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="number" step="0.0001" class="form-control me-2"
                                                    name="ancho_trazo" id="ancho_trazo" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaMarcada) ? $auditoriaMarcada->ancho_trazo : ''); ?>"
                                                    <?php echo e(isset($auditoriaMarcada) ? 'readonly' : ''); ?>

                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 1 -->
                    <!--Inicio acordeon 2 -->
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button id="btnTwo" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    - - AUDITORIA DE TENDIDO - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                
                                <?php if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada'): ?>
                                    <p>-</p>
                                <?php elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido'): ?>
                                <form method="POST"
                                    action="<?php echo e(route('auditoriaCorte.formAuditoriaTendido', ['id' => $datoAX->id])); ?>"> 
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                                    <input type="hidden" name="idAuditoriaTendido" value="<?php echo e($auditoriaTendido->id); ?>">
                                    <input type="hidden" name="orden" value="<?php echo e($datoAX->orden); ?>">
                                    
                                    <input type="hidden" name="accion" value=""> 
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="row"> 
                                                <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) TENDEDOR(RES)</label>
                                                <div class="col-sm-6">
                                                    <select class="form-control" name="nombre[]" id="nombre" multiple>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $CategoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->nombre); ?>" <?php echo e(isset($auditoriaTendido) && in_array(trim($nombre->nombre), explode(',', trim($auditoriaTendido->nombre))) ? 'selected' : ''); ?>>
                                                                <?php echo e($nombre->nombre); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción" required>
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="1 : Mesa" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '1 : Mesa' ? 'selected' : ''); ?>>1 : Manual</option>
                                                    <option value="2 : Brio" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '2 : Brio' ? 'selected' : ''); ?>>2 : Brio</option>
                                                    <option value="3 : Brio" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '3 : Brio' ? 'selected' : ''); ?>>3 : Brio</option>
                                                    <option value="4 : Brio" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '4 : Brio' ? 'selected' : ''); ?>>4 : Brio</option>
                                                    <option value="5 : Brio" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '5 : Brio' ? 'selected' : ''); ?>>5 : Brio</option>
                                                    <option value="6 : Brio" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->mesa == '6 : Brio' ? 'selected' : ''); ?>>6 : Brio</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                    value="<?php echo e($auditorDato); ?>" readonly required />
                                                <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_material" class="col-sm-6 col-form-label">1. Codigo de
                                                material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="codigo_material_estatus" id="codigo_material_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->codigo_material_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="codigo_material_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="codigo_material_estatus" id="codigo_material_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->codigo_material_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="codigo_material_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2"
                                                        name="codigo_material" id="codigo_material" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->codigo_material : ''); ?>"
                                                        required />
                                                </div>                                              
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_color" class="col-sm-6 col-form-label">2. Codigo de
                                                color</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center"
                                                    style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_color_estatus" id="codigo_color_estatus1"
                                                            value="1"
                                                            <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->codigo_color_estatus == 1 ? 'checked' : ''); ?>

                                                            required />
                                                        <label class="label-paloma" for="codigo_color_estatus1">✔ </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_color_estatus" id="codigo_color_estatus2"
                                                            value="0"
                                                            <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->codigo_color_estatus == 0 ? 'checked' : ''); ?>

                                                            required />
                                                        <label class="label-tache" for="codigo_color_estatus2">✖ </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="text" class="form-control me-2" name="codigo_color" id="codigo_color"
                                                               placeholder="..." value="<?php echo e($encabezadoAuditoriaCorte->color_id); ?>" readonly required />
                                                        <input type="hidden" name="codigo_color" value="<?php echo e($encabezadoAuditoriaCorte->color_id); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="informacion_trazo" class="col-sm-6 col-form-label">3. Informacion
                                                de trazo</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="informacion_trazo_estatus" id="informacion_trazo_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="informacion_trazo_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="informacion_trazo_estatus" id="informacion_trazo_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="informacion_trazo_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <select name="informacion_trazo" id="informacion_trazo" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Si" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo == 'Si' ? 'selected' : ''); ?>>Si</option>
                                                        <option value="No" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->informacion_trazo == 'No' ? 'selected' : ''); ?>>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_lienzo" class="col-sm-6 col-form-label">4. Cantidad de
                                                lienzos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->cantidad_lienzo_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="cantidad_lienzo_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->cantidad_lienzo_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="cantidad_lienzo_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="cantidad_lienzo" id="cantidad_lienzo"
                                                           value="<?php echo e($encabezadoAuditoriaCorte->lienzo); ?>" readonly required />
                                                    <input type="hidden" name="cantidad_lienzo" value="<?php echo e($encabezadoAuditoriaCorte->lienzo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="longitud_tendido" class="col-sm-6 col-form-label">5. Longitud de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="longitud_tendido_estatus" id="longitud_tendido_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->longitud_tendido_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="longitud_tendido_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="longitud_tendido_estatus" id="longitud_tendido_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->longitud_tendido_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="longitud_tendido_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="longitud_tendido" id="longitud_tendido"
                                                           value="<?php echo e($auditoriaMarcada->largo_trazo); ?>" readonly required />
                                                    <input type="hidden" name="longitud_tendido" value="<?php echo e($auditoriaMarcada->largo_trazo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_tendido" class="col-sm-6 col-form-label">6. Ancho de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ancho_tendido_estatus" id="ancho_tendido_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->ancho_tendido_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="ancho_tendido_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ancho_tendido_estatus" id="ancho_tendido_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->ancho_tendido_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="ancho_tendido_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="ancho_tendido" id="ancho_tendido"
                                                           value="<?php echo e($auditoriaMarcada->ancho_trazo); ?>" readonly required />
                                                    <input type="hidden" name="ancho_tendido" value="<?php echo e($auditoriaMarcada->ancho_trazo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="material_relajado" class="col-sm-6 col-form-label">7. Material relajado</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="material_relajado_estatus" id="material_relajado_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->material_relajado_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="material_relajado_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="material_relajado_estatus" id="material_relajado_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->material_relajado_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="material_relajado_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <select name="material_relajado" id="material_relajado" class="form-control"
                                                        title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $CategoriaMaterialRelajado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materialRelajado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                            <option value="<?php echo e($materialRelajado->nombre); ?>"
                                                                <?php echo e(isset($auditoriaTendido) && trim($auditoriaTendido->material_relajado) == trim($materialRelajado->nombre) ? 'selected' : ''); ?>>
                                                                <?php echo e($materialRelajado->nombre); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="empalme" class="col-sm-6 col-form-label">8. Empalmes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                        id="empalme_estatus1" value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->empalme_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="empalme_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                        id="empalme_estatus2" value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->empalme_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="empalme_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cara_material" class="col-sm-6 col-form-label">9. Cara de
                                                material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cara_material_estatus" id="cara_material_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->cara_material_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="cara_material_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="cara_material_estatus" id="cara_material_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->cara_material_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="cara_material_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tono" class="col-sm-6 col-form-label">10. Tonos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                        id="tono_estatus1" value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->tono_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="tono_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                        id="tono_estatus2" value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->tono_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="tono_estatus2">✖ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2"
                                                        name="tono" id="tono" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->tono : ''); ?>"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="alineacion_tendido" class="col-sm-6 col-form-label">11. Alineacion de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="alineacion_tendido" id="alineacion_tendido" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Regular" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->alineacion_tendido == 'Regular' ? 'selected' : ''); ?>>Regular </option>
                                                        <option value="Mal" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->alineacion_tendido == 'Mal' ? 'selected' : ''); ?>>Mal </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="arruga_tendido" class="col-sm-6 col-form-label">12. Arrugas de
                                                tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="arruga_tendido" id="arruga_tendido" class="form-control" title="Por favor, selecciona una opción"> 
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="Algunas" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->arruga_tendido == 'Algunas' ? 'selected' : ''); ?>>Algunas </option>
                                                        <option value="Pocas" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->arruga_tendido == 'Pocas' ? 'selected' : ''); ?>>Pocas </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto_material" class="col-sm-6 col-form-label">13. defecto de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <select name="defecto_material[]" id="defecto_material" class="form-control" multiple>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $CategoriaDefectoCorte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $defectoMaterial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($defectoMaterial->nombre); ?>"
                                                                <?php echo e(isset($auditoriaTendido) && in_array(trim($defectoMaterial->nombre), explode(',', trim($auditoriaTendido->defecto_material))) ? 'selected' : ''); ?>>
                                                                <?php echo e($defectoMaterial->nombre); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_marcada" class="col-sm-6 col-form-label">14. Yardas en la
                                                marcada</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_marcada" id="yarda_marcada" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->yarda_marcada : ''); ?>"
                                                        required />
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="yarda_marcada_estatus" id="yarda_marcada_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->yarda_marcada_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="yarda_marcada_estatus1">✔ </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="yarda_marcada_estatus" id="yarda_marcada_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->yarda_marcada_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="yarda_marcada_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bio_tension" class="col-sm-6 col-form-label">
                                                15. Parametro de Brio Tension:
                                            </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select class="form-control me-2" name="bio_tension" id="bio_tension" required>
                                                    <option value="">Selecciona una opcion</option>
                                                    <option value="automatico" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->bio_tension === 'automatico' ? 'selected' : ''); ?>>Automático</option>
                                                    <option value="manual" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->bio_tension === 'manual' ? 'selected' : ''); ?>>Manual</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="velocidad" class="col-sm-6 col-form-label">
                                                16. Velocidad:
                                            </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2"
                                                    name="velocidad" id="velocidad" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->velocidad : ''); ?>"
                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="accion_correctiva" class="col-sm-6 col-form-label">Accion
                                                correctiva </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="accion_correctiva" id="accion_correctiva" class="form-control me-2" required>
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="NINGUNO">NINGUNO</option>
                                                    <?php $__currentLoopData = $CategoriaAccionCorrectiva; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($categoria->accion_correctiva); ?>" <?php echo e(isset($auditoriaTendido) && $auditoriaTendido->accion_correctiva == $categoria->accion_correctiva ? 'selected' : ''); ?>>
                                                            <?php echo e($categoria->accion_correctiva); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        <?php if($mostrarFinalizarTendido): ?>
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="row"> 
                                                <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) TENDEDOR(RES)</label>
                                                <div class="col-sm-6">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php $__currentLoopData = explode(',', trim($auditoriaTendido->nombre)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <input type="text" class="form-control mb-2" value="<?php echo e($nombre); ?>" readonly />
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>
                                                        <select class="form-control" name="nombre[]" id="nombre" multiple>
                                                            <option value="">Selecciona una opción</option>
                                                            <?php $__currentLoopData = $CategoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($nombre->nombre); ?>"><?php echo e($nombre->nombre); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($auditoriaTendido)): ?>
                                                    <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->mesa); ?>" readonly />
                                                <?php else: ?>
                                                    <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="1 : Mesa">1 : Manual</option>
                                                        <option value="2 : Brio">2 : Brio</option>
                                                        <option value="3 : Brio">3 : Brio</option>
                                                        <option value="4 : Brio">4 : Brio</option>
                                                        <option value="5 : Brio">5 : Brio</option>
                                                        <option value="6 : Brio">6 : Brio</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                    value="<?php echo e($auditorDato); ?>" readonly required />
                                                <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_material" class="col-sm-6 col-form-label">1. Codigo de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->codigo_material_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_material_estatus" id="codigo_material_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="codigo_material_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="codigo_material_estatus" id="codigo_material_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="codigo_material_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2"
                                                        name="codigo_material" id="codigo_material" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->codigo_material : ''); ?>"
                                                        <?php echo e(isset($auditoriaTendido) ? 'readonly' : ''); ?>

                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="codigo_color" class="col-sm-6 col-form-label">2. Codigo de color</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <?php if(isset($auditoriaTendido)): ?>
                                                            <?php if($auditoriaTendido->codigo_color_estatus == 1): ?>
                                                                <label class="label-paloma">✔</label>
                                                            <?php else: ?>
                                                                <label class="label-tache">✖</label>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <input class="quitar-espacio" type="radio"
                                                                name="codigo_color_estatus" id="codigo_color_estatus1"
                                                                value="1" required />
                                                            <label class="label-paloma" for="codigo_color_estatus1">✔</label>
                                                            <input class="quitar-espacio" type="radio"
                                                                name="codigo_color_estatus" id="codigo_color_estatus2"
                                                                value="0" required />
                                                            <label class="label-tache" for="codigo_color_estatus2">✖</label>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="text" class="form-control me-2" name="codigo_color" id="codigo_color"
                                                            placeholder="..." value="<?php echo e($encabezadoAuditoriaCorte->color_id); ?>" readonly required />
                                                        <input type="hidden" name="codigo_color" value="<?php echo e($encabezadoAuditoriaCorte->color_id); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="informacion_trazo" class="col-sm-6 col-form-label">3. Informacion de trazo</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->informacion_trazo_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="informacion_trazo_estatus" id="informacion_trazo_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="informacion_trazo_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="informacion_trazo_estatus" id="informacion_trazo_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="informacion_trazo_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->informacion_trazo); ?>" readonly />
                                                    <?php else: ?>
                                                        <select name="informacion_trazo" id="informacion_trazo" class="form-control" title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Si">Si</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_lienzo" class="col-sm-6 col-form-label">4. Cantidad de lienzos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->cantidad_lienzo_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="cantidad_lienzo_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cantidad_lienzo_estatus" id="cantidad_lienzo_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="cantidad_lienzo_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="cantidad_lienzo" id="cantidad_lienzo"
                                                        value="<?php echo e($encabezadoAuditoriaCorte->lienzo); ?>" readonly required />
                                                    <input type="hidden" name="cantidad_lienzo" value="<?php echo e($encabezadoAuditoriaCorte->lienzo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="longitud_tendido" class="col-sm-6 col-form-label">5. Longitud de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->longitud_tendido_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="longitud_tendido_estatus" id="longitud_tendido_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="longitud_tendido_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="longitud_tendido_estatus" id="longitud_tendido_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="longitud_tendido_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="longitud_tendido" id="longitud_tendido"
                                                        value="<?php echo e($auditoriaMarcada->largo_trazo); ?>" readonly required />
                                                    <input type="hidden" name="longitud_tendido" value="<?php echo e($auditoriaMarcada->largo_trazo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ancho_tendido" class="col-sm-6 col-form-label">6. Ancho de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->ancho_tendido_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="ancho_tendido_estatus" id="ancho_tendido_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="ancho_tendido_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="ancho_tendido_estatus" id="ancho_tendido_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="ancho_tendido_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="ancho_tendido" id="ancho_tendido"
                                                        value="<?php echo e($auditoriaMarcada->ancho_trazo); ?>" readonly required />
                                                    <input type="hidden" name="ancho_tendido" value="<?php echo e($auditoriaMarcada->ancho_trazo); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="material_relajado" class="col-sm-6 col-form-label">7. Material relajado</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->material_relajado_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="material_relajado_estatus" id="material_relajado_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="material_relajado_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="material_relajado_estatus" id="material_relajado_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="material_relajado_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->material_relajado); ?>" readonly />
                                                    <?php else: ?>
                                                        <select name="material_relajado" id="material_relajado" class="form-control"
                                                            title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            <?php $__currentLoopData = $CategoriaMaterialRelajado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materialRelajado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                                <option value="<?php echo e($materialRelajado->nombre); ?>"><?php echo e($materialRelajado->nombre); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="empalme" class="col-sm-6 col-form-label">8. Empalmes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->empalme_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                            id="empalme_estatus1" value="1" required />
                                                        <label class="label-paloma" for="empalme_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio" name="empalme_estatus"
                                                            id="empalme_estatus2" value="0" required />
                                                        <label class="label-tache" for="empalme_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cara_material" class="col-sm-6 col-form-label">9. Cara de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->cara_material_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cara_material_estatus" id="cara_material_estatus1"
                                                            value="1" required />
                                                        <label class="label-paloma" for="cara_material_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio"
                                                            name="cara_material_estatus" id="cara_material_estatus2"
                                                            value="0" required />
                                                        <label class="label-tache" for="cara_material_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="tono" class="col-sm-6 col-form-label">10. Tonos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <?php if($auditoriaTendido->tono_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                            id="tono_estatus1" value="1" required />
                                                        <label class="label-paloma" for="tono_estatus1">✔</label>
                                                        <input class="quitar-espacio" type="radio" name="tono_estatus"
                                                            id="tono_estatus2" value="0" required />
                                                        <label class="label-tache" for="tono_estatus2">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2"
                                                        name="tono" id="tono" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->tono : ''); ?>"
                                                        <?php echo e(isset($auditoriaTendido) ? 'readonly' : ''); ?>

                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="alineacion_tendido" class="col-sm-6 col-form-label">11. Alineacion de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->alineacion_tendido); ?>" readonly />
                                                    <?php else: ?>
                                                        <select name="alineacion_tendido" id="alineacion_tendido" class="form-control" title="Por favor, selecciona una opción">
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Regular">Regular</option>
                                                            <option value="Mal">Mal</option>
                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="arruga_tendido" class="col-sm-6 col-form-label">12. Arrugas de tendido</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaTendido)): ?>
                                                        <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->arruga_tendido); ?>" readonly />
                                                    <?php else: ?>
                                                        <select name="arruga_tendido" id="arruga_tendido" class="form-control" title="Por favor, selecciona una opción"> 
                                                            <option value="">Selecciona una opción</option>
                                                            <option value="Algunas">Algunas</option>
                                                            <option value="Pocas">Pocas</option>
                                                        </select>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto_material" class="col-sm-6 col-form-label">13. Defecto de material</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <?php if(isset($auditoriaTendido)): ?>
                                                    <?php $__currentLoopData = explode(',', trim($auditoriaTendido->defecto_material)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $defecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="text" class="form-control mb-2" value="<?php echo e($defecto); ?>" readonly />
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <select name="defecto_material[]" id="defecto_material" class="form-control" multiple>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $CategoriaDefectoCorte; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $defectoMaterial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($defectoMaterial->nombre); ?>"><?php echo e($defectoMaterial->nombre); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="yarda_marcada" class="col-sm-6 col-form-label">14. Yardas en la marcada</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="form-check form-check-inline">
                                                    <input type="number" step="0.0001" class="form-control me-2"
                                                        name="yarda_marcada" id="yarda_marcada" placeholder="..."
                                                        value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->yarda_marcada : ''); ?>"
                                                        <?php echo e(isset($auditoriaTendido) ? 'readonly' : ''); ?>

                                                        required />
                                                </div>
                                                <?php if(isset($auditoriaTendido)): ?>
                                                    <div class="form-check form-check-inline">
                                                        <?php if($auditoriaTendido->yarda_marcada_estatus == 1): ?>
                                                            <label class="label-paloma">✔</label>
                                                        <?php else: ?>
                                                            <label class="label-tache">✖</label>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bio_tension" class="col-sm-6 col-form-label">15. Parametro de Brio Tension:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($auditoriaTendido)): ?>
                                                    <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->bio_tension); ?>" readonly />
                                                <?php else: ?>
                                                    <select class="form-control me-2" name="bio_tension" id="bio_tension" required>
                                                        <option value="">Selecciona una opcion</option>
                                                        <option value="automatico">Automático</option>
                                                        <option value="manual">Manual</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="velocidad" class="col-sm-6 col-form-label">16. Velocidad:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2"
                                                    name="velocidad" id="velocidad" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaTendido) ? $auditoriaTendido->velocidad : ''); ?>"
                                                    <?php echo e(isset($auditoriaTendido) ? 'readonly' : ''); ?>

                                                    required />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="accion_correctiva" class="col-sm-6 col-form-label">Accion correctiva</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($auditoriaTendido)): ?>
                                                    <input type="text" class="form-control" value="<?php echo e($auditoriaTendido->accion_correctiva); ?>" readonly />
                                                <?php else: ?>
                                                    <select name="accion_correctiva" id="accion_correctiva" class="form-control me-2" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="NINGUNO">NINGUNO</option>
                                                        <?php $__currentLoopData = $CategoriaAccionCorrectiva; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($categoria->accion_correctiva); ?>"><?php echo e($categoria->accion_correctiva); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 2 -->
                    <!--Inicio acordeon 3 -->
                    <div class="card">
                        <div class="card-header" id="headingThree">
                            <h5 class="mb-0">
                                <button id="btnThree" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    - - LECTRA - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                            data-parent="#accordion">
                            <div class="card-body">
                                
                                <?php if($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido')): ?>
                                    <p>-</p>
                                <?php elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusLectra'): ?> 
                                <form method="POST"
                                    action="<?php echo e(route('auditoriaCorte.formLectra', ['id' => $datoAX->id])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                                    <input type="hidden" name="idLectra" value="<?php echo e($Lectra->id); ?>">
                                    <input type="hidden" name="orden" value="<?php echo e($datoAX->orden); ?>">
                                    
                                    <input type="hidden" name="accion" value="">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) CORTADOR(ES)</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="nombre[]" id="nombrel" class="form-control" multiple required>
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $CategoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($nombre->nombre); ?>"
                                                            <?php echo e(isset($Lectra) && in_array(trim($nombre->nombre), explode(',', trim($Lectra->nombre))) ? 'selected' : ''); ?>>
                                                            <?php echo e($nombre->nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">Maquina Lectra: </label>
                                            <div class="col-sm-12 d-flex align-items-center"> 
                                                <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción"> 
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="IH8" <?php echo e(isset($Lectra) && $Lectra->mesa == 'IH8' ? 'selected' : ''); ?>>IH8</option>
                                                    <option value="IX6" <?php echo e(isset($Lectra) && $Lectra->mesa == 'IX6' ? 'selected' : ''); ?>>IX6</option>
                                                </select> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                    value="<?php echo e($auditorDato); ?>" readonly required />
                                                <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <?php
                                            $options = ['-1', '-15/16', '-7/8', '-13/16', '-3/4', '-11/16', '-5/8', '-9/16', '-1/2', '-7/16', '-3/8', '-5/16', '-1/4', '-3/16', '-1/8', '-1/16', 
                                            '0', '+1/16', '+1/8', '+3/16', '+1/4', '+5/16', '+3/8', '+7/16', '+1/2', '+9/16', '+5/8', '+11/16', '+3/4', '+13/16', '+7/8', '+15/16', '+1'];
                                            $paneles = ['DELANTERO', 'TRACERO', 'PARCHE', 'ADICIONAL'];
                                        ?>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Panel</th>
                                                        <th scope="col">Simetria de piezas</th>
                                                        <th scope="col" colspan="2">X° ANCHO</th>
                                                        <th scope="col" colspan="2">Y° LARGO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for($panel = 1; $panel <= 4; $panel++): ?>
                                                        <tr>
                                                            <th scope="row">Panel <?php echo e($panel); ?></th>
                                                            <td>
                                                                <input type="text" class="form-control" name="simetria_pieza<?php echo e($panel); ?>" id="simetria_pieza<?php echo e($panel); ?>" placeholder="panel <?php echo e($panel); ?>" value="<?php echo e(isset($Lectra) ? $Lectra->{'simetria_pieza'.$panel} : ''); ?>" />
                                                            </td>
                                                            <!-- Panel X -->
                                                            <td>
                                                                <select name="panel<?php echo e($panel); ?>_x1" id="panel<?php echo e($panel); ?>_x1" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial </option>
                                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($option); ?>" <?php echo e(isset($Lectra) && $Lectra->{'panel'.$panel.'_x1'} == $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel<?php echo e($panel); ?>_x2" id="panel<?php echo e($panel); ?>_x2" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final </option>
                                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($option); ?>" <?php echo e(isset($Lectra) && $Lectra->{'panel'.$panel.'_x2'} == $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                            <!-- Panel Y -->
                                                            <td>
                                                                <select name="panel<?php echo e($panel); ?>_y1" id="panel<?php echo e($panel); ?>_y1" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Inicial </option>
                                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($option); ?>" <?php echo e(isset($Lectra) && $Lectra->{'panel'.$panel.'_y1'} == $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select name="panel<?php echo e($panel); ?>_y2" id="panel<?php echo e($panel); ?>_y2" class="form-control" title="Por favor, selecciona una opción">
                                                                    <option value="">Rango Final </option>
                                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($option); ?>" <?php echo e(isset($Lectra) && $Lectra->{'panel'.$panel.'_y2'} == $option ? 'selected' : ''); ?>><?php echo e($option); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3"> 
                                            <label for="pieza_contrapatron" class="col-sm-6 col-form-label">1. Piezas contra patron</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="pieza_contrapatron_estatus" id="pieza_contrapatron_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($Lectra) && $Lectra->pieza_contrapatron_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="pieza_contrapatron_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="pieza_contrapatron_estatus" id="pieza_contrapatron_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($Lectra) && $Lectra->pieza_contrapatron_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="pieza_contrapatron_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_inspeccionada" class="col-sm-6 col-form-label">Piezas inspeccionadas</label> 
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if($encabezadoAuditoriaCorte->pieza >= 2 && $encabezadoAuditoriaCorte->pieza <= 8): ?>
                                                    <input type="text" class="form-control" readonly value="2" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 9 && $encabezadoAuditoriaCorte->pieza <= 15): ?>
                                                    <input type="text" class="form-control" readonly value="3" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 16 && $encabezadoAuditoriaCorte->pieza <= 25): ?>
                                                    <input type="text" class="form-control" readonly value="5" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 26 && $encabezadoAuditoriaCorte->pieza <= 50): ?>
                                                    <input type="text" class="form-control" readonly value="8" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 51 && $encabezadoAuditoriaCorte->pieza <= 90): ?>
                                                    <input type="text" class="form-control" readonly value="13" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 91 && $encabezadoAuditoriaCorte->pieza <= 150): ?>
                                                    <input type="text" class="form-control" readonly value="20" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 151 && $encabezadoAuditoriaCorte->pieza <= 280): ?>
                                                    <input type="text" class="form-control" readonly value="32" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 281 && $encabezadoAuditoriaCorte->pieza <= 500): ?>
                                                    <input type="text" class="form-control" readonly value="50" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 501 && $encabezadoAuditoriaCorte->pieza <= 1200): ?>
                                                    <input type="text" class="form-control" readonly value="80" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 1201 && $encabezadoAuditoriaCorte->pieza <= 3200): ?>
                                                    <input type="text" class="form-control" readonly value="125" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 3201 && $encabezadoAuditoriaCorte->pieza <= 10000): ?>
                                                    <input type="text" class="form-control" readonly value="200" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 10001 && $encabezadoAuditoriaCorte->pieza <= 35000): ?>
                                                    <input type="text" class="form-control" readonly value="315" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php endif; ?>
                                            </div> 
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nivel_aql" class="col-sm-6 col-form-label">Nivel AQL</label>
                                            <div class="col-sm-12">
                                                <select class="form-control" name="nivel_aql" id="nivel_aql">
                                                    <option value="1.0" <?php echo e(isset($Lectra) && $Lectra->nivel_aql == '1.0' ? 'selected' : ''); ?>>1.0</option>
                                                    <option value="1.5" <?php echo e(isset($Lectra) && $Lectra->nivel_aql == '1.5' ? 'selected' : ''); ?>>1.5</option>
                                                    <option value="2.5" <?php echo e(isset($Lectra) && $Lectra->nivel_aql == '2.5' ? 'selected' : ''); ?>>2.5</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="cantidad_defecto"
                                                       id="cantidad_defecto" placeholder="..."
                                                       value="<?php echo e(isset($Lectra) ? $Lectra->cantidad_defecto : ''); ?>"
                                                       required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="porcentaje" id="porcentaje" placeholder="..."
                                                    value="<?php echo e(isset($calculoPorcentaje) ? $calculoPorcentaje : ''); ?>" readonly step="0.01"/>
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12">
                                                <!-- Campo oculto para enviar el mensaje de validación al formulario -->
                                                <input type="hidden" name="estado_validacion" id="estado_validacion" />
                                                <input type="text" class="form-control" name="mensaje_validacion" readonly id="mensaje_validacion" 
                                                value="<?php echo e(isset($Lectra) ? $Lectra->estado_validacion : ''); ?>" readonly />
                                            </div>
                                        </div>
                                        
                                        <script>
                                            // Obtener referencia al input de cantidad de defectos y al input de porcentaje
                                            const cantidadDefectosInput = document.getElementById('cantidad_defecto');
                                            const porcentajeInput = document.getElementById('porcentaje');
                                            const piezasInspeccionadasInput = document.getElementById('pieza_inspeccionada_input');
                                        
                                            // Función para calcular el porcentaje y actualizar el input de porcentaje
                                            function actualizarPorcentaje() {
                                                const cantidadDefectos = parseFloat(cantidadDefectosInput.value);
                                                const piezasInspeccionadas = parseFloat(piezasInspeccionadasInput.value);
                                        
                                                // Si piezasInspeccionadas es 0, asignarle el valor del select inicial
                                                const piezasIniciales = {
                                                    2: 2,
                                                    3: 3,
                                                    5: 5,
                                                    8: 8,
                                                    13: 13,
                                                    20: 20,
                                                    32: 32,
                                                    50: 50,
                                                    80: 80,
                                                    125: 125,
                                                    200: 200,
                                                    315: 315
                                                };
                                        
                                                const valorInicial = piezasIniciales[piezasInspeccionadas] || 0;
                                                const porcentaje = piezasInspeccionadas !== 0 ? (cantidadDefectos / valorInicial) * 100 : 0; // Evitar división por cero
                                                porcentajeInput.value = porcentaje.toFixed(2);
                                            }
                                        
                                            // Escuchar el evento input en el input de cantidad de defectos para actualizar el porcentaje
                                            cantidadDefectosInput.addEventListener('input', actualizarPorcentaje);
                                            // Escuchar el evento change en el select de piezas inspeccionadas para actualizar el porcentaje
                                            piezasInspeccionadasInput.addEventListener('change', actualizarPorcentaje);
                                        
                                            // Calcular el porcentaje inicial al cargar la página
                                            actualizarPorcentaje();

                                            const selectOpcion = document.getElementById('nivel_aql');
                                            const mensajeValidacionInput = document.getElementById('mensaje_validacion');

                                            function validarCantidadDefectos() {
                                                const piezasInspeccionadas = parseFloat(piezasInspeccionadasInput.value);
                                                const cantidadDefectos = parseFloat(cantidadDefectosInput.value);
                                                const opcion = selectOpcion.value;

                                                let minCantidadDefectos;
                                                let maxCantidadDefectos;

                                                switch (opcion) {
                                                    case '1.0':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 800:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 1250:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    case '1.5':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 800:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    case '2.5':
                                                        switch (piezasInspeccionadas) {
                                                            case 3:
                                                            case 5:
                                                            case 8:
                                                            case 13:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                            case 20:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 1;
                                                                break;
                                                            case 32:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 2;
                                                                break;
                                                            case 50:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 3;
                                                                break;
                                                            case 80:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 5;
                                                                break;
                                                            case 125:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 7;
                                                                break;
                                                            case 200:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 10;
                                                                break;
                                                            case 315:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 14;
                                                                break;
                                                            case 500:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 21;
                                                                break;
                                                            default:
                                                                minCantidadDefectos = 0;
                                                                maxCantidadDefectos = 0;
                                                                break;
                                                        }
                                                        break;
                                                    default:
                                                        minCantidadDefectos = 0;
                                                        maxCantidadDefectos = 0;
                                                        break;
                                                }

                                                if (cantidadDefectos >= minCantidadDefectos && cantidadDefectos <= maxCantidadDefectos) {
                                                    mensajeValidacionInput.value = 'Aceptable';
                                                    document.getElementById('estado_validacion').value = 'Aceptable';
                                                } else {
                                                    mensajeValidacionInput.value = 'Rechazado';
                                                    document.getElementById('estado_validacion').value = 'Rechazado';
                                                }
                                            }

                                            selectOpcion.addEventListener('change', validarCantidadDefectos);
                                            cantidadDefectosInput.addEventListener('input', validarCantidadDefectos);

                                        </script>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="defecto[]" id="defecto" class="form-control" multiple title="Por favor, selecciona una opción" required>
                                                    <option value="">Selecciona una opción</option>
                                                    <option value="ninguno">Ninguno</option>
                                                    <?php $__currentLoopData = $CategoriaDefectoCorteTendido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corteTendido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($corteTendido->nombre); ?>"
                                                            <?php echo e(isset($Lectra) && in_array(trim($corteTendido->nombre), explode(',', trim($Lectra->defecto))) ? 'selected' : ''); ?>>
                                                            <?php echo e($corteTendido->nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        <?php if($mostrarFinalizarLectra): ?>
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE(S) CORTADOR(ES)</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($Lectra)): ?>
                                                    <?php $__currentLoopData = explode(',', trim($Lectra->nombre)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="text" class="form-control mb-2" value="<?php echo e($nombre); ?>" readonly />
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <select name="nombre[]" id="nombrel" class="form-control" multiple required>
                                                        <option value="">Selecciona una opción</option>
                                                        <?php $__currentLoopData = $CategoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($nombre->nombre); ?>"><?php echo e($nombre->nombre); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="mesa" class="col-sm-6 col-form-label">Maquina Lectra:</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($Lectra)): ?>
                                                    <input type="text" class="form-control" value="<?php echo e($Lectra->mesa); ?>" readonly />
                                                <?php else: ?>
                                                    <select name="mesa" id="mesa" class="form-control" title="Por favor, selecciona una opción">
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="IH8">IH8</option>
                                                        <option value="IX6">IX6</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="auditor" id="auditor" value="<?php echo e($auditorDato); ?>" readonly required />
                                                <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Panel</th>
                                                        <th scope="col">Simetria de piezas</th>
                                                        <th scope="col" colspan="2">X° ANCHO</th>
                                                        <th scope="col" colspan="2">Y° LARGO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php for($panel = 1; $panel <= 4; $panel++): ?>
                                                        <tr>
                                                            <th scope="row">Panel <?php echo e($panel); ?></th>
                                                            <td>
                                                                <input type="text" class="form-control" name="simetria_pieza<?php echo e($panel); ?>" id="simetria_pieza<?php echo e($panel); ?>" placeholder="panel <?php echo e($panel); ?>" value="<?php echo e(isset($Lectra) ? $Lectra->{'simetria_pieza'.$panel} : ''); ?>" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e(isset($Lectra) ? $Lectra->{'panel'.$panel.'_x1'} : ''); ?>" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e(isset($Lectra) ? $Lectra->{'panel'.$panel.'_x2'} : ''); ?>" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e(isset($Lectra) ? $Lectra->{'panel'.$panel.'_y1'} : ''); ?>" readonly />
                                                            </td>
                                                            <td>
                                                                <input type="text" class="form-control" value="<?php echo e(isset($Lectra) ? $Lectra->{'panel'.$panel.'_y2'} : ''); ?>" readonly />
                                                            </td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    
                                        <div class="col-md-6 mb-3"> 
                                            <label for="pieza_contrapatron" class="col-sm-6 col-form-label">1. Piezas contra patron</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <?php if(isset($Lectra)): ?>
                                                    <?php if($Lectra->pieza_contrapatron_estatus == 1): ?>
                                                        <label class="label-paloma">✔</label>
                                                    <?php else: ?>
                                                        <label class="label-tache">✖</label>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div> 
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_inspeccionada" class="col-sm-6 col-form-label">Piezas inspeccionadas</label> 
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if($encabezadoAuditoriaCorte->pieza >= 2 && $encabezadoAuditoriaCorte->pieza <= 8): ?>
                                                    <input type="text" class="form-control" readonly value="2" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 9 && $encabezadoAuditoriaCorte->pieza <= 15): ?>
                                                    <input type="text" class="form-control" readonly value="3" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 16 && $encabezadoAuditoriaCorte->pieza <= 25): ?>
                                                    <input type="text" class="form-control" readonly value="5" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 26 && $encabezadoAuditoriaCorte->pieza <= 50): ?>
                                                    <input type="text" class="form-control" readonly value="8" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 51 && $encabezadoAuditoriaCorte->pieza <= 90): ?>
                                                    <input type="text" class="form-control" readonly value="13" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 91 && $encabezadoAuditoriaCorte->pieza <= 150): ?>
                                                    <input type="text" class="form-control" readonly value="20" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 151 && $encabezadoAuditoriaCorte->pieza <= 280): ?>
                                                    <input type="text" class="form-control" readonly value="32" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 281 && $encabezadoAuditoriaCorte->pieza <= 500): ?>
                                                    <input type="text" class="form-control" readonly value="50" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 501 && $encabezadoAuditoriaCorte->pieza <= 1200): ?>
                                                    <input type="text" class="form-control" readonly value="80" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 1201 && $encabezadoAuditoriaCorte->pieza <= 3200): ?>
                                                    <input type="text" class="form-control" readonly value="125" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 3201 && $encabezadoAuditoriaCorte->pieza <= 10000): ?>
                                                    <input type="text" class="form-control" readonly value="200" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php elseif($encabezadoAuditoriaCorte->pieza >= 10001 && $encabezadoAuditoriaCorte->pieza <= 35000): ?>
                                                    <input type="text" class="form-control" readonly value="315" name="pieza_inspeccionada" id="pieza_inspeccionada_input">
                                                <?php endif; ?>
                                            </div> 
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nivel_aql" class="col-sm-6 col-form-label">Nivel AQL</label>
                                            <div class="col-sm-12">
                                                <?php if(isset($Lectra)): ?>
                                                    <input type="text" class="form-control" value="<?php echo e($Lectra->nivel_aql); ?>" readonly />
                                                <?php else: ?>
                                                    <select class="form-control" name="nivel_aql" id="nivel_aql">
                                                        <option value="1.0">1.0</option>
                                                        <option value="1.5">1.5</option>
                                                        <option value="2.5">2.5</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="cantidad_defecto"
                                                    id="cantidad_defecto" placeholder="..."
                                                    value="<?php echo e(isset($Lectra) ? $Lectra->cantidad_defecto : ''); ?>"
                                                    readonly required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="porcentaje" id="porcentaje" placeholder="..."
                                                    value="<?php echo e(isset($Lectra) ? $Lectra->porcentaje : ''); ?>" readonly step="0.01"/>
                                                <span>%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12">
                                                <!-- Campo oculto para enviar el mensaje de validación al formulario -->
                                                <input type="hidden" name="estado_validacion" id="estado_validacion" />
                                                <input type="text" class="form-control" name="mensaje_validacion" readonly id="mensaje_validacion" 
                                                value="<?php echo e(isset($Lectra) ? $Lectra->estado_validacion : ''); ?>" readonly />
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($Lectra)): ?>
                                                    <?php $__currentLoopData = explode(',', trim($Lectra->defecto)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $defecto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="text" class="form-control mb-2" value="<?php echo e($defecto); ?>" readonly />
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <select name="defecto[]" id="defecto" class="form-control" multiple title="Por favor, selecciona una opción" required>
                                                        <option value="">Selecciona una opción</option>
                                                        <option value="ninguno">Ninguno</option>
                                                        <?php $__currentLoopData = $CategoriaDefectoCorteTendido; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $corteTendido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($corteTendido->nombre); ?>"><?php echo e($corteTendido->nombre); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 3 -->
                    <!--Inicio acordeon 4 -->
                    <div class="card">
                        <div class="card-header" id="headingFour">
                            <h5 class="mb-0">
                                <button id="btnFour" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    - - AUDITORIA EN BULTOS - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">
                                
                                <?php if($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra')): ?>
                                    <p>-</p>
                                <?php elseif($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto'): ?>
                                <form method="POST"
                                    action="<?php echo e(route('auditoriaCorte.formAuditoriaBulto', ['id' => $datoAX->id])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                                    <input type="hidden" name="idBulto" value="<?php echo e($auditoriaBulto->id); ?>"> 
                                    <input type="hidden" name="orden" value="<?php echo e($datoAX->orden); ?>">
                                    
                                    <input type="hidden" name="accion" value="">
                                    <div class="row"> 
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE DEL SELLADOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <select name="nombre[]" id="nombreb" class="form-control" multiple required>
                                                    <option value="">Selecciona una opción</option>
                                                    <?php $__currentLoopData = $CategoriaTecnico; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($nombre->nombre); ?>"
                                                            <?php echo e(isset($auditoriaBulto) && in_array(trim($nombre->nombre), explode(',', trim($auditoriaBulto->nombre))) ? 'selected' : ''); ?>>
                                                            <?php echo e($nombre->nombre); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <?php
                                                $nombreMesa = "SELLADO";
                                            ?>
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="mesa" id="mesa" placeholder="..."
                                                    value="<?php echo e(isset($nombreMesa) ? $nombreMesa : ''); ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                        value="<?php echo e($auditorDato); ?>" readonly required />
                                                    <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_paquete" class="col-sm-6 col-form-label">1. Piezas por paquete</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input type="number" class="form-control me-2"
                                                               name="pieza_paquete" id="pieza_paquete" placeholder="..."
                                                               value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->pieza_paquete : ''); ?>"
                                                               required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_bulto" class="col-sm-6 col-form-label">2. Cantidad de Bultos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="cantidad_bulto" id="cantidad_bulto" placeholder="..."
                                                           value="<?php echo e(isset($calculoPorcentajeBulto) ? $calculoPorcentajeBulto : ''); ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            // Obtener referencia al input de piezas por paquete y al input de cantidad de bultos
                                            const piezasPorPaqueteInput = document.getElementById('pieza_paquete');
                                            const cantidadBultoInput = document.getElementById('cantidad_bulto');
                                        
                                            // Función para calcular la cantidad de bultos y actualizar el input de cantidad de bultos
                                            function actualizarCantidadBulto() {
                                                const piezasPorPaquete = parseFloat(piezasPorPaqueteInput.value);
                                                const cantidadBulto = piezasPorPaquete !== 0 ? Math.ceil(<?php echo e($encabezadoAuditoriaCorte->pieza); ?> / piezasPorPaquete) : 0; // Evitar división por cero
                                                cantidadBultoInput.value = cantidadBulto;
                                            }
                                        
                                            // Escuchar el evento input en el input de piezas por paquete para actualizar la cantidad de bultos
                                            piezasPorPaqueteInput.addEventListener('input', actualizarCantidadBulto);
                                        
                                            // Calcular la cantidad de bultos inicial al cargar la página
                                            actualizarCantidadBulto();
                                        </script>
                                        <div class="col-md-6 mb-3">
                                            <label for="ingreso_ticket" class="col-sm-6 col-form-label">3. Ingreso de Tickets</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ingreso_ticket_estatus" id="ingreso_ticket_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="ingreso_ticket_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="ingreso_ticket_estatus" id="ingreso_ticket_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="ingreso_ticket_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sellado_paquete" class="col-sm-6 col-form-label">4. Sellado de Paquetes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="sellado_paquete_estatus" id="sellado_paquete_estatus1"
                                                        value="1"
                                                        <?php echo e(isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 1 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-paloma" for="sellado_paquete_estatus1">✔
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="quitar-espacio" type="radio"
                                                        name="sellado_paquete_estatus" id="sellado_paquete_estatus2"
                                                        value="0"
                                                        <?php echo e(isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 0 ? 'checked' : ''); ?>

                                                        required />
                                                    <label class="label-tache" for="sellado_paquete_estatus2">✖ </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="cantidad_defecto"
                                                    id="cantidad_defecto_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->cantidad_defecto : ''); ?>"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="defecto"
                                                    id="defecto_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->defecto : ''); ?>"
                                                    required />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="porcentaje"
                                                    id="porcentaje_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->porcentaje : ''); ?>"
                                                    readonly />
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Obtener referencias a los elementos del DOM
                                                const piezaPaqueteInput = document.getElementById('pieza_paquete');
                                                const cantidadDefectoInput = document.getElementById('cantidad_defecto_bulto');
                                                const porcentajeInput = document.getElementById('porcentaje_bulto');
                                        
                                                // Función para actualizar el porcentaje
                                                function actualizarPorcentaje() {
                                                    const piezaPaquete = parseFloat(piezaPaqueteInput.value) || 0;
                                                    const cantidadDefecto = parseFloat(cantidadDefectoInput.value) || 0;
                                                    console.log('pieza_paquete:', piezaPaquete);
                                                    console.log('cantidad_defecto:', cantidadDefecto);
                                        
                                                    if (piezaPaquete !== 0) {
                                                        const porcentaje = (cantidadDefecto / piezaPaquete) * 100;
                                                        console.log('porcentaje:', porcentaje);
                                                        if (!isNaN(porcentaje)) {
                                                            porcentajeInput.value = porcentaje.toFixed(2);
                                                            console.log('porcentajeInput.value:', porcentajeInput.value);
                                                        } else {
                                                            porcentajeInput.value = '';
                                                        }
                                                    } else {
                                                        porcentajeInput.value = '';
                                                    }
                                                }
                                        
                                                // Agregar eventos de escucha a los inputs
                                                piezaPaqueteInput.addEventListener('input', actualizarPorcentaje);
                                                cantidadDefectoInput.addEventListener('input', actualizarPorcentaje);
                                        
                                                // Calcular el porcentaje inicial al cargar la página
                                                actualizarPorcentaje();
                                            });
                                        </script>
                                    </div>
                                    <div>
                                        <button type="submit" class="btn-verde-xd">Guardar</button>
                                        <?php if($mostrarFinalizarBulto): ?>
                                            <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>
                                    <<div class="row"> 
                                        <div class="col-md-6 mb-3">
                                            <label for="nombre" class="col-sm-6 col-form-label">NOMBRE DEL SELLADOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php if(isset($auditoriaBulto)): ?>
                                                    <?php $__currentLoopData = explode(',', $auditoriaBulto->nombre); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nombre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <input type="text" class="form-control me-2 mb-2" value="<?php echo e(trim($nombre)); ?>" readonly />
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <input type="text" class="form-control me-2" value="No hay datos" readonly />
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha" class="col-sm-6 col-form-label">Fecha</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <?php
                                                $nombreMesa = "SELLADO";
                                            ?>
                                            <label for="mesa" class="col-sm-6 col-form-label">MESA</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="mesa" id="mesa" placeholder="..."
                                                    value="<?php echo e(isset($nombreMesa) ? $nombreMesa : ''); ?>" readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="auditor" class="col-sm-6 col-form-label">AUDITOR</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2" name="auditor" id="auditor"
                                                        value="<?php echo e($auditorDato); ?>" readonly required />
                                                    <input type="hidden" name="auditor" value="<?php echo e($auditorDato); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="pieza_paquete" class="col-sm-6 col-form-label">1. Piezas por paquete</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input type="number" class="form-control me-2"
                                                               name="pieza_paquete" id="pieza_paquete" placeholder="..."
                                                               value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->pieza_paquete : ''); ?>"
                                                               readonly />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_bulto" class="col-sm-6 col-form-label">2. Cantidad de Bultos</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <input type="text" class="form-control me-2" name="cantidad_bulto" id="cantidad_bulto" placeholder="..."
                                                           value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->cantidad_bulto : ''); ?>" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="ingreso_ticket" class="col-sm-6 col-form-label">3. Ingreso de Tickets</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaBulto) && $auditoriaBulto->ingreso_ticket_estatus == 1): ?>
                                                        <label class="label-paloma">✔</label>
                                                    <?php else: ?>
                                                        <label class="label-tache">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="sellado_paquete" class="col-sm-6 col-form-label">4. Sellado de Paquetes</label>
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <div class="form-check form-check-inline">
                                                    <?php if(isset($auditoriaBulto) && $auditoriaBulto->sellado_paquete_estatus == 1): ?>
                                                        <label class="label-paloma">✔</label>
                                                    <?php else: ?>
                                                        <label class="label-tache">✖</label>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cantidad_defecto" class="col-sm-6 col-form-label">Cantidad de Defectos</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="cantidad_defecto"
                                                    id="cantidad_defecto_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->cantidad_defecto : ''); ?>"
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="defecto" class="col-sm-6 col-form-label">Defectos </label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="defecto"
                                                    id="defecto_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->defecto : ''); ?>"
                                                    readonly />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="porcentaje" class="col-sm-6 col-form-label">Porcentaje</label>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="porcentaje"
                                                    id="porcentaje_bulto" placeholder="..."
                                                    value="<?php echo e(isset($auditoriaBulto) ? $auditoriaBulto->porcentaje : ''); ?>"
                                                    readonly />
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 4 -->
                    <!--Inicio acordeon 5 -->
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                                <button id="btnFive" class="btn btn-info btn-block collapsed" data-toggle="collapse"
                                    data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    - - AUDITORIA FINAL - -
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                                
                                <?php if($encabezadoAuditoriaCorte && $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal'): ?>
                                    <form method="POST"
                                        action="<?php echo e(route('auditoriaCorte.formAuditoriaFinal', ['id' => $datoAX->id])); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($datoAX->id); ?>">
                                        <input type="hidden" name="idAuditoriaFinal" value="<?php echo e($auditoriaFinal->id); ?>">
                                        <input type="hidden" name="orden" value="<?php echo e($datoAX->orden); ?>">
                                        
                                        <input type="hidden" name="accion" value="">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="supervisor_corte" class="col-sm-6 col-form-label">Supervisor de Corte</label>
                                                <?php
                                                    $supervisorCorteFinal = "DAVID"
                                                ?>
                                                <div class="col-sm-12 d-flex align-items-center">
                                                    <input type="text" class="form-control me-2" name="supervisor_corte" id="supervisor_corte"
                                                        value="<?php echo e($supervisorCorteFinal); ?>" readonly />
                                                    <input type="hidden" name="supervisor_corte" value="<?php echo e($supervisorCorteFinal); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="aceptado_condicion" class="col-sm-6 col-form-label">Aceptado con condiciones :</label>
                                                <div class="col-sm-12">
                                                    <textarea class="form-control" name="aceptado_condicion" id="aceptado_condicion" rows="3"
                                                        placeholder="comentarios" required><?php echo e(isset($auditoriaFinal) ? $auditoriaFinal->aceptado_condicion : ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Aceptado - Rechazado</label>
                                                <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="aceptado_rechazado" id="estatus1"
                                                            value="1"
                                                            <?php echo e(isset($auditoriaFinal) && $auditoriaFinal->aceptado_rechazado == 1 ? 'checked' : ''); ?>

                                                            required />
                                                        <label class="label-paloma" for="estatus1">✔
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="quitar-espacio" type="radio"
                                                            name="aceptado_rechazado" id="estatus2"
                                                            value="0"
                                                            <?php echo e(isset($auditoriaFinal) && $auditoriaFinal->aceptado_rechazado == 0 ? 'checked' : ''); ?>

                                                            required />
                                                        <label class="label-tache" for="estatus2">✖ </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn-verde-xd">Guardar</button>
                                            <?php if($mostrarFinalizarFinal): ?>
                                                <button type="submit" class="btn btn-danger" value="finalizar" name="accion" >Finalizar</button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-danger" disabled>Finalizar</button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                <?php elseif($encabezadoAuditoriaCorte && ($encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaMarcada' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaTendido' || $encabezadoAuditoriaCorte->estatus == 'estatusLectra' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaBulto' || $encabezadoAuditoriaCorte->estatus == 'estatusAuditoriaFinal' || $encabezadoAuditoriaCorte->estatus == 'fin')): ?>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="supervisor_corte" class="col-sm-6 col-form-label">Supervisor de Corte</label>
                                            <?php
                                                $supervisorCorteFinal = "DAVID";
                                            ?>
                                            <div class="col-sm-12 d-flex align-items-center">
                                                <input type="text" class="form-control me-2" name="supervisor_corte" id="supervisor_corte"
                                                    value="<?php echo e($supervisorCorteFinal); ?>" readonly />
                                                <input type="hidden" name="supervisor_corte" value="<?php echo e($supervisorCorteFinal); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="aceptado_condicion" class="col-sm-6 col-form-label">Aceptado con condiciones :</label>
                                            <div class="col-sm-12">
                                                <textarea class="form-control" name="aceptado_condicion" id="aceptado_condicion" rows="3" placeholder="comentarios" readonly><?php echo e(isset($auditoriaFinal) ? $auditoriaFinal->aceptado_condicion : ''); ?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                                <?php if(isset($auditoriaFinal)): ?>
                                                    <?php if($auditoriaFinal->aceptado_rechazado == 1): ?>
                                                        <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Aceptado</label>
                                                        <label class="label-paloma">✔</label>
                                                    <?php else: ?>
                                                        <label for="aceptado_rechazado" class="col-sm-6 col-form-label">Rechazado</label>
                                                        <label class="label-tache">✖</label>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <label class="label-tache">✖</label>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>
                    </div>
                    <!--Fin acordeon 5 -->
                </div>
                <!--Fin div de acordeon -->
            </div>
        </div>
        <style>
            /* Estilos personalizados para los elementos de tipo "radio" */
            input[type="radio"] {
                width: 20px;
                /* Ancho personalizado */
                height: 20px;
                /* Altura personalizada */
                /* Otros estilos personalizados según tus necesidades */
            }

            .label-paloma {
                font-size: 20px;
                /* Tamaño de fuente personalizado */
                color: #33a533;
                /* Color de texto personalizado */
                font-weight: bold;
                /* Texto en negritas (bold) */
                /* Otros estilos personalizados según tus necesidades */
            }

            .label-tache {
                font-size: 20px;
                /* Tamaño de fuente personalizado */
                color: #b61711;
                /* Color de texto personalizado */
                font-weight: bold;
                /* Texto en negritas (bold) */
                /* Otros estilos personalizados según tus necesidades */
            }

            .form-check-inline {
                margin-right: 25px;
            }

            .form-control.me-2 {
                margin-right: 25px;
                /* Ajusta la cantidad de margen según tus necesidades */
            }

            .quitar-espacio {
                margin-right: 10px;
            }

            
        </style>
        <!-- Script para abrir el acordeón correspondiente -->
        <script>
            // Obtenemos el valor del estatus desde el HTML generado por PHP en Laravel
            var estatus = <?php echo json_encode(optional($encabezadoAuditoriaCorte)->estatus, 15, 512) ?>;
            const estatusTextos = {
                'estatusAuditoriaMarcada': 'Auditoria de Marcada',
                'estatusAuditoriaTendido': 'Auditoria de Tendido',
                'estatusLectra': 'Lectra',
                'estatusAuditoriaBulto': 'Auditoria en Bultos',
                'estatusAuditoriaFinal': 'Auditoria Final',
                'fin': 'Terminado'
                // Agrega otros valores para los demás estados
            };
            const estatusTexto = estatusTextos[estatus];

            // Verificamos si el valor de estatus se estableció correctamente
            if (estatus) {
                // Mostramos el valor en la página
                document.getElementById("estatusValue").innerText = "Estatus: " + estatusTexto;

                // Dependiendo del valor de estatus, abrimos el acordeón correspondiente
                switch (estatus) {
                    case "estatusAuditoriaMarcada":
                        // Abre el acordeón 1
                        document.getElementById("collapseOne").classList.add("show");
                        document.getElementById("btnOne").classList.remove("btn-info");
                        document.getElementById("btnOne").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaTendido":
                        // Abre el acordeón 2
                        document.getElementById("collapseTwo").classList.add("show");
                        document.getElementById("btnTwo").classList.remove("btn-info");
                        document.getElementById("btnTwo").classList.add("btn-primary");
                        break;
                    case "estatusLectra":
                        // Abre el acordeón 3
                        document.getElementById("collapseThree").classList.add("show");
                        document.getElementById("btnThree").classList.remove("btn-info");
                        document.getElementById("btnThree").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaBulto":
                        // Abre el acordeón 4
                        document.getElementById("collapseFour").classList.add("show");
                        document.getElementById("btnFour").classList.remove("btn-info");
                        document.getElementById("btnFour").classList.add("btn-primary");
                        break;
                    case "estatusAuditoriaFinal":
                        // Abre el acordeón 5
                        document.getElementById("collapseFive").classList.add("show");
                        document.getElementById("btnFive").classList.remove("btn-info");
                        document.getElementById("btnFive").classList.add("btn-primary");
                        break;
                    default:
                        console.log("El valor de estatus no coincide con ninguna opción válida para abrir un acordeón.");
                }
            } else {
                console.log("ERROR: No se pudo obtener el valor de estatus.");
            }
        </script>
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
        $('#defecto_material').select2({
                placeholder: 'Seleccione una o varias opciones',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombre').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombrel').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#nombreb').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
        $('#defecto').select2({
                placeholder: 'Seleccione una o varios nombres',
                allowClear: true,
                multiple: true // Esta opción permite la selección múltiple
            });
    </script>


    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\auditoriaCorte\auditoriaCorte.blade.php ENDPATH**/ ?>