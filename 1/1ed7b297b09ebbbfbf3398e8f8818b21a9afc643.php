

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
    background-color: #28a745; /* Color de fondo verde */
    color: white; /* Color de texto blanco */
    padding: 20px;
    border-radius: 15px;
    font-size: 20px;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="content">
    <div class="container-fluid">
      <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                  </div>
                    <form method="POST" action="<?php echo e(route('formulariosCalidad.formAuditoriaCortes')); ?>">
                        <?php echo csrf_field(); ?>
                        <hr>
                        <div class="card-body">
                            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label for="orden" class="col-form-label">ORDEN</label>
                                    <input type="number" class="form-control" name="orden" id="orden" placeholder="..." required />
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="estilo" class="col-form-label">ESTILO</label>
                                    <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($estilo->id); ?>"><?php echo e($estilo->nombre); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="pieza" class="col-form-label">PIEZAS</label>
                                    <input type="number" class="form-control" name="pieza" id="pieza" placeholder="..." required />
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="trazo" class="col-form-label">TRAZO</label>
                                    <input type="number" class="form-control" name="trazo" id="trazo" placeholder="..." required />
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="color" class="col-form-label">COLOR</label>
                                    <input type="number" class="form-control" name="color" id="color" placeholder="..." required />
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="material" class="col-form-label">MATERIAL</label>
                                    <input type="number" class="form-control" name="material" id="material" placeholder="..." required />
                                </div>

                                <div class="col-md-1 mb-3">
                                    <label for="lienzo" class="col-form-label">LIENZOS</label>
                                    <input type="text" class="form-control" name="lienzo" id="lienzo" placeholder="..." required />
                                </div>
                            </div>




                            <hr>
                            <div style="background: #db8036a2">
                                <h4 style="text-align: center">AUDITORIA DE MARCADA</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="yOrden" class="col-sm-6 col-form-label">Yardas en la orden</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input type="number" class="form-control me-2" name="yOrden" id="yOrden" placeholder="..." required />
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1">✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado2">✖ </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="yMarcada" class="col-sm-6 col-form-label">Yardas en la marcada</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input type="number" class="form-control me-2" name="yOrden" id="yOrden" placeholder="..." required />
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1">✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado2">✖ </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="yTendido" class="col-sm-6 col-form-label">Yardas en el tendido</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input type="number" class="form-control me-2" name="yOrden" id="yOrden" placeholder="..." required />
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1">✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado2">✖ </label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-6 mb-3">
                                    <label for="pieza_bulto" class="col-sm-3 col-form-label">Piezas X Bulto </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="pieza_bulto" id="pieza_bulto" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="pieza_total" class="col-sm-3 col-form-label">Piezas Totales</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="pieza_total" id="pieza_total" placeholder="..." required />
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <label for="talla" class="col-sm-3 col-form-label">Tallas</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="talla" id="talla" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="bulto" class="col-sm-3 col-form-label"># Bultos</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="bulto" id="bulto" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="bulto" id="bulto" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="bulto" id="bulto" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="bulto" id="bulto" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="bulto" id="bulto" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="total_pieza" class="col-sm-3 col-form-label">Total piezas</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="total_pieza" id="total_pieza" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="total_pieza" id="total_pieza" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="total_pieza" id="total_pieza" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="total_pieza" id="total_pieza" placeholder="..." required />
                                        <input type="number" class="form-control me-2" name="total_pieza" id="total_pieza" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="largo_trazo" class="col-sm-3 col-form-label">Largo Trazo </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="largo_trazo" id="largo_trazo" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ancho_trazo" class="col-sm-3 col-form-label">Ancho Trazo </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control me-2" name="ancho_trazo" id="ancho_trazo" placeholder="..." required />
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div style="background: #32d2d8a2">
                                <h4 style="text-align: center">AUDITORIA DE TENDIDO</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="col-sm-3 col-form-label">Fecha</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mesa" class="col-sm-3 col-form-label">MESA</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="mesa" id="mesa" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <label for="codigo_material" class="col-sm-6 col-form-label">1.  Codigo de material</label>
                                    <div class="col-sm-12 d-flex align-items-center" style="margin-right: -5px;">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline" style="margin-right: -5px;">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="codigo_color" class="col-sm-6 col-form-label">2.  Codigo de color</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="codigo_color" id="codigo_color" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="info_trazo" class="col-sm-6 col-form-label">3.  Informacion de trazo</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="info_trazo" id="info_trazo" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cantidad_lienzo" class="col-sm-6 col-form-label">4.  Cantidad de lienzos</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="cantidad_lienzo" id="cantidad_lienzo" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="longitud_tendido" class="col-sm-6 col-form-label">5.  Longitud de tendido</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="longitud_tendido" id="longitud_tendido" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="ancho_tendido" class="col-sm-6 col-form-label">6.  Ancho de tendido</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="ancho_tendido" id="ancho_tendido" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="material_relajado" class="col-sm-6 col-form-label">7.  Material relajado</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="material_relajado" id="material_relajado" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="material_relajado" class="col-sm-6 col-form-label">Accion correctiva </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="text" class="form-control me-2" name="material_relajado" id="material_relajado" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 mb-3">
                                    <label for="emplame" class="col-sm-6 col-form-label">8.  Empalmes</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="emplame" id="emplame" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cara_material" class="col-sm-6 col-form-label">9.  Cara de material</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="cara_material" id="cara_material" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tono" class="col-sm-6 col-form-label">10.  Tonos</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="tono" id="tono" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="alineacion_tendido" class="col-sm-6 col-form-label">11.  Alineacion de tendido</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="alineacion_tendido" id="alineacion_tendido" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="arruga_tendido" class="col-sm-6 col-form-label">12.  Arrugas de tendido</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="arruga_tendido" id="arruga_tendido" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="defecto_material" class="col-sm-6 col-form-label">13.  defecto de material</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                            <label class="label-paloma" for="estado1"> ✔ </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                            <label class="label-tache" for="estado1"> ✖ </label>
                                        </div>
                                        <input type="text" class="form-control me-2" name="defecto_material" id="defecto_material" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="material_relajado" class="col-sm-6 col-form-label">¿Se libera el tendido?</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="text" class="form-control me-2" name="material_relajado" id="material_relajado" placeholder=" COMENTARIOS" required />
                                    </div>
                                </div>
                                <hr>
                            </div>
                                <div style="background: #b41873a2">
                                    <h4 style="text-align: center">LECTRA</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">Fecha</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mesa" class="col-sm-3 col-form-label">MESA</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="mesa" id="mesa" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-6 mb-3">
                                        <label for="simetria_pieza" class="col-sm-6 col-form-label">1.  Simetria de piezas</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="simetria_pieza_pcs" id="simetria_pieza_pcs" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="simetria_pieza" id="simetria_pieza" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">2.  piezas completas</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">3.  Piezas contra patron</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">4.  Sellado de paquetes</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Piezas inspeccionadas</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Defectos</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Porcentaje</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Firma de Aprobado</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div style="background: #18b420a2">
                                    <h4 style="text-align: center">AUDITORIA EN BULTOS</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">Fecha</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?>

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mesa" class="col-sm-3 col-form-label">MESA</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="mesa" id="mesa" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">NOMBRE</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-6 mb-3">
                                        <label for="simetria_pieza" class="col-sm-6 col-form-label">1.  Cantidad de bultos</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="simetria_pieza_pcs" id="simetria_pieza_pcs" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="simetria_pieza" id="simetria_pieza" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">2.  Piezas por paquete</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">3.  Encojimiento</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">4. Ingreso de tikets</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>
                                                <label class="label-paloma" for="estado1"> ✔ </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>
                                                <label class="label-tache" for="estado1"> ✖ </label>
                                            </div>
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder="Pcs." required />
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Piezas inspeccionadas</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Defectos</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Porcentaje</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="codigo_material" class="col-sm-6 col-form-label">Firma de Aprobado</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <input type="text" class="form-control me-2" name="codigo_material" id="codigo_material" placeholder=" COMENTARIOS" required />
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div style="background: #3518b4a2">
                                    <h4 style="text-align: center">AUDITORIA EN BULTOS</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label">AUDTORIA FINAL</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-3 col-form-label"> </label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <div class="form-check">
                                                <label class="form-check-label" for="estado1">APROBADO </label>
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado1" value="1" required>

                                            </div>
                                            <div class="form-check ">
                                                <label class="form-check-label" for="estado2"> RECHAZADO </label>
                                                <input class="quitar-espacio" type="radio" name="estado" id="estado2" value="0" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-6 col-form-label">SUPERVUSOR DE CORTE</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="col-sm-6 col-form-label">SUPERVUSOR DE LINEA</label>
                                        <div class="col-sm-12 d-flex align-items-center">
                                            <select name="nombre" id="nombre" class="form-control" required title="Por favor, selecciona una opción">
                                                <option value="">Selecciona una opción</option>
                                                <?php $__currentLoopData = $CategoriaAuditor; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auditor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($auditor->id); ?>"><?php echo e($auditor->nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <hr>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <!--Fin de la edicion del codigo para mostrar el contenido-->
                        </div>
                    <form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<style>
    /* Estilos personalizados para los elementos de tipo "radio" */
    input[type="radio"] {
        width: 20px; /* Ancho personalizado */
        height: 20px; /* Altura personalizada */
        /* Otros estilos personalizados según tus necesidades */
    }
    .label-paloma{
        font-size: 20px; /* Tamaño de fuente personalizado */
        color: #33a533; /* Color de texto personalizado */
        font-weight: bold; /* Texto en negritas (bold) */
        /* Otros estilos personalizados según tus necesidades */
    }
    .label-tache{
        font-size: 20px; /* Tamaño de fuente personalizado */
        color: #b61711; /* Color de texto personalizado */
        font-weight: bold; /* Texto en negritas (bold) */
        /* Otros estilos personalizados según tus necesidades */
    }
    .form-check-inline{
        margin-right: 25px;
    }
    .form-control.me-2 {
        margin-right: 25px; /* Ajusta la cantidad de margen según tus necesidades */
    }
    .quitar-espacio{
        margin-right: 10px;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Corte', 'titlePage' => __('Corte')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\formulariosCalidad\auditoriaCortes.blade.php ENDPATH**/ ?>