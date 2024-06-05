

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

<div class="content">
    <div class="container-fluid">
      <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <h3 class="card-title">AUDITORIA FINAL A.Q.L</h3>
                  </div>

                        <?php echo csrf_field(); ?>
                        <hr>
                        <div class="card-body">
                            <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="col-sm-3 col-form-label">Fecha</label>
                                    <div class="col-sm-12 d-flex justify-content-between align-items-center">
                                        <p><?php echo e(now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y')); ?></p>
                                        <p class="ml-auto">Dia: <?php echo e($nombreDia); ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cliente" class="col-sm-6 col-form-label">CLIENTE / CUSTOMER</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="cliente" id="cliente" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaCliente; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($cliente->id); ?>"><?php echo e($cliente->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="talla" class="col-sm-3 col-form-label">TALLA / SIZE</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" name="talla" id="talla" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="estilo" class="col-sm-3 col-form-label">ESTILO</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="estilo" id="estilo" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $estilo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($estilo->id); ?>"><?php echo e($estilo->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="color" class="col-sm-3 col-form-label">COLOR / COLOR</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="color" id="color" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaEstilo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($color->id); ?>"><?php echo e($color->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="descripcion" class="col-sm-6 col-form-label">DESCRIPCION</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" name="descripcion" id="descripcion" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cantidad_auditada" class="col-sm-6 col-form-label">CANTIDAD AUDITADA / AUDIT QTY</label>
                                    <div class="col-sm-12">
                                        <input type="number" class="form-control" name="cantidad_auditada" id="cantidad_auditada" placeholder="..." required />
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mpo" class="col-sm-3 col-form-label">M.P.O </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="mpo" id="mpo" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaTamañoMuestra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mpo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($mpo->id); ?>"><?php echo e($mpo->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tamaño_muestra" class="col-sm-3 col-form-label">M.P.O </label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="tamaño_muestra" id="tamaño_muestra" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaTamañoMuestra; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tamaño_muestra): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($tamaño_muestra->id); ?>"><?php echo e($tamaño_muestra->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div style="background: #32d2d8a2">
                                <h4 style="text-align: center"> - - - - </h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="operario" class="col-sm-3 col-form-label">TABLA</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#miModal">
                                            2.5 A.Q.L
                                        </button>
                                        <div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">2.5 A.Q.L</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Agrega desbordamiento vertical -->
                                                        <div class="table-responsive"> <!-- Hace la tabla responsiva -->
                                                            <input type="number" id="searchInput" class="form-control" placeholder="Buscar Tamaño de Lote">
                                                            <table class="table table-bordered  table-hover">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>TAMAÑO DE LOTE O CANTIDAD A AUDITAR / AUDIT QTY</th>
                                                                        <th>AUDITAR / AUDIT</th>
                                                                        <th>ACEPTA / APPROVED</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tableBody">
                                                                    <tr class="table-row">
                                                                        <td>2 a 8</td>
                                                                        <td class="muestra-size">2</td>
                                                                        <td>0</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>9 a 15</td>
                                                                        <td class="muestra-size">3</td>
                                                                        <td>0</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>16 a 25</td>
                                                                        <td class="muestra-size">5</td>
                                                                        <td>0</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>26 a 50</td>
                                                                        <td class="muestra-size">8</td>
                                                                        <td>0</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>51 a 90</td>
                                                                        <td class="muestra-size">13</td>
                                                                        <td>0</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>91 a 150</td>
                                                                        <td class="muestra-size">20</td>
                                                                        <td>1</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>151 a 280</td>
                                                                        <td class="muestra-size">32</td>
                                                                        <td>2</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>281 a 500</td>
                                                                        <td class="muestra-size">50</td>
                                                                        <td>3</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>501 a 1200</td>
                                                                        <td class="muestra-size">80</td>
                                                                        <td>5</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>1201 a 3200</td>
                                                                        <td class="muestra-size">125</td>
                                                                        <td>7</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>3201 a 10000</td>
                                                                        <td class="muestra-size">200</td>
                                                                        <td>10</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>10000 a 35000</td>
                                                                        <td class="muestra-size">315</td>
                                                                        <td>14</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>35000 a 150000</td>
                                                                        <td class="muestra-size">500</td>
                                                                        <td>15</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>150000 a 5000000</td>
                                                                        <td class="muestra-size">800</td>
                                                                        <td>21</td>
                                                                    </tr>
                                                                    <tr class="table-row">
                                                                        <td>5000000 o más</td>
                                                                        <td class="muestra-size">2000</td>
                                                                        <td>25</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="muestra" class="col-sm-3 col-form-label">CANTIDAD / QTY</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control" name="muestra" id="muestra" placeholder="..." required title="..."/>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_defecto" class="col-sm-3 col-form-label">TIPO DE DEFECTO / TYPE OD DEFECT</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <input type="number" class="form-control" name="tipo_defecto" id="tipo_defecto" placeholder="..." required title="..."/>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="menor" class="col-sm-3 col-form-label">MENOR / MINOR</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="menor" id="menor" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaDefecto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($menor->id); ?>"><?php echo e($menor->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="mayor" class="col-sm-3 col-form-label">MAYOR / MAJOR</label>
                                    <div class="col-sm-12 d-flex align-items-center">
                                        <select name="mayor" id="mayor" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                            <?php $__currentLoopData = $CategoriaDefecto; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mayor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($mayor->id); ?>"><?php echo e($mayor->nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <!--Fin de la edicion del codigo para mostrar el contenido-->
                        </div>
                    <form>
                </div>
            </div>
        </div>
<style>
    /* Estilos personalizados para los elementos de tipo "radio" */
    input[type="radio"] {
        width: 20px; /* Ancho personalizado */
        height: 20px; /* Altura personalizada */
        /* Otros estilos personalizados según tus necesidades */
    }
    .col-form-label-radio{
        font-size: 16px; /* Tamaño de fuente personalizado */
        color: #142b4b; /* Color de texto personalizado */
        margin-left: 50px; /* Espacio entre el radio y el texto (ajusta según tus necesidades) */
        font-weight: bold; /* Texto en negritas (bold) */
        /* Otros estilos personalizados según tus necesidades */
    }


    .col-form-label{
        font-size: 16px; /* Tamaño de fuente personalizado */
        color: #142b4b; /* Color de texto personalizado */
        /* Otros estilos personalizados según tus necesidades */
    }
</style>
<style>
/* Anula la propiedad position: fixed en .modal-backdrop */
.modal-backdrop {
    position: static !important; /* Cambia a 'static' para permitir la interacción */
}
</style>
    <style>
        .selected-row {
            background-color: #11d885ef; /* Cambia este color al que prefieras */
        }
    </style>
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            var filterValue = this.value.toLowerCase();
            var rows = document.getElementById('tableBody').getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var tamañoLoteCell = rows[i].getElementsByTagName('td')[0].textContent.toLowerCase();
                // Comprueba si la celda contiene "o más"
                if (tamañoLoteCell.includes('o más')) {
                    // Extrae el número antes de " o más"
                    var numberBeforeText = parseInt(tamañoLoteCell.split(' ')[0], 10);
                    var filterNumber = parseInt(filterValue, 10);
                    if (!isNaN(filterNumber) && filterNumber > numberBeforeText) {
                        rows[i].style.display = '';
                        continue;
                    }
                }
                // Si no contiene "o más", maneja como un rango
                else {
                    var rangeParts = tamañoLoteCell.split(' a ');
                    if (rangeParts.length === 2) {
                        var rangeStart = parseInt(rangeParts[0], 10);
                        var rangeEnd = parseInt(rangeParts[1], 10);
                        var filterNumber = parseInt(filterValue, 10);
                        if (!isNaN(filterNumber) && filterNumber >= rangeStart && filterNumber <= rangeEnd) {
                            rows[i].style.display = '';
                            continue;
                        }
                    }
                }
                rows[i].style.display = 'none';
            }
        });


        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('#tableBody .table-row').forEach(row => {
                row.addEventListener('click', function() {
                    // Quita la clase 'selected-row' de todas las filas
                    document.querySelectorAll('.selected-row').forEach(selectedRow => {
                        selectedRow.classList.remove('selected-row');
                    });

                    // Agrega la clase 'selected-row' a la fila clickeada
                    this.classList.add('selected-row');

                    // Obtén el texto del tamaño de muestra de la celda correspondiente
                    let tamañoMuestra = this.querySelector('.muestra-size').textContent;
                    // Asigna ese texto al input del tamaño de muestra
                    document.getElementById('muestra').value = tamañoMuestra;
                    // Cierra el modal utilizando la API de modal de Bootstrap 5
                    let modalElement = document.getElementById('miModal');
                    if(modalElement) {
                        let modalInstance = bootstrap.Modal.getInstance(modalElement);
                        if(modalInstance) {
                            modalInstance.hide();
                        }
                    }
                });
            });
        });

        </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['pageSlug' => 'Auditoria AQL', 'titlePage' => __('Auditoria AQL')], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp1\htdocs\tester_calidad\resources\views\formulariosCalidad\auditoriaFinalAQL.blade.php ENDPATH**/ ?>