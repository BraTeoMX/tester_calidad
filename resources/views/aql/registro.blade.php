@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

@section('content')
    {{-- ... dentro de tu vista ... --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alerta-exito">
            {{ session('success') }}
            @if (session('sorteo'))
                <br>{{ session('sorteo') }}
            @endif
        </div>
    @endif
    @if (session('sobre-escribir'))
        <div class="alert sobre-escribir">
            {{ session('sobre-escribir') }}
        </div>
    @endif
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    @if (session('cambio-estatus'))
        <div class="alert cambio-estatus">
            {{ session('cambio-estatus') }}
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos todos los elementos de alerta
            const alerts = document.querySelectorAll('.alert');

            // Iteramos por cada alerta para aplicar el desvanecido
            alerts.forEach(alert => {
                // Esperamos 6 segundos antes de iniciar el desvanecido
                setTimeout(() => {
                    // Cambiamos la opacidad para el efecto de desvanecido
                    alert.style.transition = 'opacity 1s ease';
                    alert.style.opacity = '0';

                    // Eliminamos el elemento del DOM después de 1 segundo (duración del desvanecido)
                    setTimeout(() => alert.remove(), 1000);
                }, 5000); // Tiempo de espera antes de desvanecer (6 segundos)
            });
        });
    </script>
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
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 50px auto;
            padding: 20px;
            width: 90%;
            max-width: 1200px;
            box-sizing: border-box;
            position: relative;
        }

        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            background-color: #2e2e2e;
            padding: 15px;
            align-items: center;
        }

        .custom-modal-body {
            padding: 15px;
        }

        #closeModalAQL {
            z-index: 1000;
            /* Asegúrate de que sea mayor que cualquier elemento que pueda superponer */
            position: relative;
            /* Esto ayuda a que el z-index funcione */
            display: inline-block;
            /* Asegura que el área sea del tamaño del contenido */
            width: auto;
            /* Ajusta el tamaño al contenido */
        }
    </style>
    <div class="card">
        <!-- Encabezado del card -->
        <div class="card-header card-header-primary">
            <div class="row align-items-center justify-content-between">
                <div class="col">
                    <h3 class="card-title">AUDITORIA AQL</h3>
                </div>
                <div class="col-auto">
                    <!-- Botón para abrir el modal -->
                    <button type="button" class="btn btn-link" id="openModalAQL">
                        <h4>Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                        </h4>
                    </button>
                </div>
            </div>
            <!-- Modal -->
            <div id="customModalAQL" class="custom-modal">
                <div class="custom-modal-content">
                    <div class="custom-modal-header">
                        <h5 class="modal-title texto-blanco">Detalles del Proceso</h5>
                        <button id="closeModalAQL" class="btn btn-danger">CERRAR</button>
                    </div>
                    <div class="custom-modal-body">
                        <!-- Contenido de la tabla -->
                        <div class="table-responsive">
                            <input type="text" id="searchInputAQL" class="form-control mb-3"
                                placeholder="Buscar Módulo u OP">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Accion</th>
                                        <th>Módulo</th>
                                        <th>OP</th>
                                    </tr>
                                </thead>
                                <tbody id="tablaProcesosAQL">
                                    <!-- Aquí se insertarán dinámicamente las filas -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!-- Contenido del card -->
        @if ($resultadoFinal == true)
            <div class="card-body">
                <!-- Aquí ya NO necesitamos la tabla, pero sí necesitamos mantener los valores -->
                <input type="hidden" name="modulo" id="modulo" value="{{ $data['modulo'] }}">
                <!-- Formulario que envía la solicitud al controlador -->
                <form action="{{ route('AQLV3.buscarUltimoRegistro') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modulo" value="{{ $data['modulo'] }}">
                    <button type="submit" class="btn btn-primary">Fin Paro Modular</button>
                </form>
            </div>
        @else
            <div class="card-body">
                <!-- Tabla responsiva -->
                <div class="table-responsive">
                    <table class="table" id="tabla-datos-principales">
                        <thead class="thead-primary table-100">
                            <tr>
                                <th>MODULO</th>
                                <th>OP</th>
                                <th>SUPERVISOR</th>
                                <th>GERENTE PRODUCCION</th>
                                <th>AUDITOR</th>
                                <th>TURNO</th>
                                <th>CLIENTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="modulo" id="modulo"
                                        value="{{ $data['modulo'] }}" readonly>
                                </td>
                                <td>
                                    <select class="form-control texto-blanco" name="op_seleccion" id="op_seleccion" required
                                        title="Selecciona una OP">
                                        <option value="">Cargando opciones...</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="team_leader"
                                        id="team_leader" value="{{ $data['team_leader'] }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="gerente_produccion"
                                        value="{{ $data['gerente_produccion'] }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="auditor" id="auditor"
                                        value="{{ $data['auditor'] }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="turno" id="turno"
                                        value="{{ $data['turno'] }}" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" name="customername"
                                        id="customername_hidden" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table32" id="tabla-datos-secundarios">
                        <thead class="thead-primary">
                            <tr>
                                <th># BULTO</th>
                                <th>PIEZAS</th>
                                <th>ESTILO</th>
                                <th>COLOR</th>
                                <th>TALLA</th>
                                <th>PIEZAS INSPECCIONADAS</th>
                                <th>PIEZAS RECHAZADAS</th>
                                <th>TIPO DE DEFECTO</th>
                                <th>ACCION CORRECTIVA</th>
                                <th>NOMBRE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="bulto_seleccion" id="bulto_seleccion" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Cargando bultos...</option>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control texto-blanco" name="pieza"
                                        id="pieza-seleccion" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="estilo"
                                        id="estilo-seleccion" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="color"
                                        id="color-seleccion" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" name="talla"
                                        id="talla-seleccion" readonly></td>
                                <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada"
                                        id="cantidad_auditada" required></td>
                                <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada"
                                        id="cantidad_rechazada" required></td>
                                <td>
                                    <select id="tpSelectAQL" class="form-control w-100"
                                        title="Por favor, selecciona una opción"></select>
                                    <div id="selectedOptionsContainerAQL" class="w-100 mb-2" required
                                        title="Por favor, selecciona una opción"></div>
                                </td>
                                <td><input type="text" class="form-control" name="accion_correctiva"
                                        id="accion_correctiva" required></td>
                                <td>
                                    <select name="nombre-none" id="nombre_select" class="form-control"></select>
                                    <div id="selectedOptionsContainerNombre" class="w-100 mb-2" required
                                        title="Por favor, selecciona una opción"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn-verde-xd">Guardar</button>
            </div>
        @endif
    </div>

    <div class="card card-body">
        <div class="accordion" id="accordionBultos">
            <div class="card">
                <div class="card-header p-0" id="headingBultos">
                    <h2 class="mb-0">
                        <button class="btn btn-link text-light text-decoration-none w-100 text-left" type="button"
                            data-toggle="collapse" data-target="#collapseBultos" aria-expanded="false"
                            aria-controls="collapseBultos">
                            <i class="fa fa-box mr-2"></i> Mostrar Bultos No Finalizados
                        </button>
                    </h2>
                </div>
                <div id="collapseBultos" class="collapse" aria-labelledby="headingBultos"
                    data-parent="#accordionBultos">
                    <div class="card-body" id="bultos-container" data-modulo="{{ $data['modulo'] }}">
                        <p class="text-muted">Abre el acordeón para cargar los datos.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-header-primary">
            <h3>Registros - Turno normal</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table56" id="tabla_registros_dia">
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
                            <th>DEFECTO(S)</th>
                            <th>Eliminar </th>
                            <th>Hora</th>
                            <th>Reparación Piezas</th> <!-- Nueva columna -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="observacion-container" data-modulo="{{ $data['modulo'] }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="observacion" class="col-sm-6 col-form-label">Observaciones:</label>
                            <div class="col-sm-12">
                                <textarea class="form-control texto-blanco" id="observacion" rows="3" placeholder="comentarios" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button id="btn-finalizar" class="btn btn-danger">Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header card-header-primary">
            <h3>Registros - Tiempo extra</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table56" id="tabla_registros_tiempo_extra">
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
                            <th>DEFECTO(S)</th>
                            <th>Eliminar </th>
                            <th>Hora</th>
                            <th>Reparación Piezas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Registros dinámicos para Tiempo Extra -->
                    </tbody>
                </table>
                <div id="observacion-container-TE" data-modulo="{{ $data['modulo'] }}">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="observacion-TE" class="col-sm-6 col-form-label">Observaciones Tiempo
                                Extra:</label>
                            <div class="col-sm-12">
                                <textarea class="form-control texto-blanco" id="observacion-TE" rows="3" placeholder="comentarios" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button id="btn-finalizar-TE" class="btn btn-danger">Finalizar Tiempo Extra</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <h2>Piezas auditadas por dia - TURNO NORMAL</h2>
                <table class="table" id="tabla-piezas-dia">
                    <thead class="thead-primary">
                        <tr>
                            <th>Total de piezas Muestra Auditadas </th>
                            <th>Total de piezas Muestra Rechazadas</th>
                            <th>Porcentaje AQL</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <hr>
            <table class="table contenedor-tabla" id="tabla-piezas-bultos">
                <thead class="thead-primary">
                    <tr>
                        <th>Total de piezas en bultos Auditados</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <hr>
            <div class="table-responsive">
                <h2>Total por Bultos </h2>
                <table class="table" id="tabla-bultos-totales">
                    <thead class="thead-primary">
                        <tr>
                            <th>total de Bultos Auditados</th>
                            <th>total de Bultos Rechazados</th>
                            <th>Porcentaje Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <!-- Apartado para mostrar turno extra"-->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <h2>Piezas auditadas por dia - TIEMPO EXTRA</h2>
                <table class="table" id="tabla-piezas-dia-TE">
                    <thead class="thead-primary">
                        <tr>
                            <th>Total de piezas Muestra Auditadas </th>
                            <th>Total de piezas Muestra Rechazadas</th>
                            <th>Porcentaje AQL</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <hr>
            <table class="table contenedor-tabla" id="tabla-piezas-bultos-TE">
                <thead class="thead-primary">
                    <tr>
                        <th>Total de piezas en bultos Auditados</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <hr>
            <div class="table-responsive">
                <h2>Total por Bultos </h2>
                <table class="table" id="tabla-bultos-totales-TE">
                    <thead class="thead-primary">
                        <tr>
                            <th>total de Bultos Auditados</th>
                            <th>total de Bultos Rechazados</th>
                            <th>Porcentaje Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <!-- Modal para crear un nuevo defecto -->
    <div class="modal fade" id="nuevoConceptoModal" tabindex="-1" role="dialog"
        aria-labelledby="nuevoConceptoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 id="nuevoConceptoModalLabel">Introduce el nuevo defecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInput"
                        placeholder="Nuevo defecto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="guardarNuevoConcepto">Guardar</button>
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

        .table-100 th:nth-child(1) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-100 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-100 th:nth-child(3) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-100 th:nth-child(4) {
            min-width: 130px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-100 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(8) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(3) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table32 th:nth-child(10) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .table55 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(1) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(2) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(6) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table56 th:nth-child(9) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        /* Estilo general para el contenedor de la tabla */
        .contenedor-tabla {
            width: 30%;
            /* Ajusta el ancho según tus necesidades */

        }


        @media (max-width: 768px) {
            .table23 th:nth-child(3) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .texto-blanco {
            color: white !important;
        }
    </style>
    <style>
        .tiempo-extra {
            background-color: #1d0f2c;
            /* Color gris claro */
        }

        /* Asegúrate de que los textos permanezcan visibles */
        .tiempo-extra input,
        .tiempo-extra .form-control,
        .tiempo-extra button {
            color: #1d0f2c;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Selectores principales
            const opSelect = $('#op_seleccion');
            const bultoSelect = $(
            '#bulto_seleccion'); // Asegúrate de tener este select en tu HTML: <select id="bulto_seleccion" ...></select>

            // --- FUNCIÓN UTILITARIA PARA OBTENER PARÁMETROS DE LA URL ---
            function getParameterByName(name) {
                const url = new URL(window.location.href);
                return url.searchParams.get(name);
            }

            // --- FUNCIÓN PARA LIMPIAR CAMPOS DEPENDIENTES DE LA SELECCIÓN DE BULTO ---
            function limpiarCamposDependientesDeBulto() {
                $('#pieza-seleccion').val('');
                $('#estilo-seleccion').val('');
                $('#color-seleccion').val('');
                $('#talla-seleccion').val('');
                $('#customername_hidden').val('');
                // Remover el input oculto 'inventcolorid' si existe dentro del formulario
                // Es más seguro buscarlo dentro del formulario al que pertenece bultoSelect
                bultoSelect.closest('form').find('input[name="inventcolorid"][type="hidden"]').remove();
            }

            // --- FUNCIÓN PARA CARGAR BULTOS PARA UNA OP ESPECÍFICA ---
            function cargarBultosParaOP(selectedOp) {
                if (!selectedOp) {
                    bultoSelect.empty().append('<option value="">Selecciona una OP primero...</option>');
                    bultoSelect.select2({
                        placeholder: 'Selecciona una OP primero',
                        allowClear: true,
                        data: [] // Asegurar que no haya datos si no hay OP
                    }).val(null).trigger('change'); // Resetear valor y disparar change para limpiar dependencias
                    bultoSelect.prop('disabled', true); // Deshabilitar si no hay OP
                    limpiarCamposDependientesDeBulto(); // Limpiar campos si se deselecciona OP o no hay OP
                    return;
                }

                bultoSelect.empty().append('<option value="">Cargando bultos...</option>').prop('disabled', true);
                bultoSelect.select2({
                    placeholder: 'Cargando bultos...'
                }); // Actualizar placeholder visualmente

                $.ajax({
                    url: "{{ route('AQLV3.obtener.bulto') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        op: selectedOp
                    },
                    success: function(dataBultosServer) {
                        const select2BultoData = dataBultosServer.map(item => ({
                            id: item.prodpackticketid,
                            text: item.prodpackticketid,
                            extra: item // Guardar el objeto completo
                        }));

                        bultoSelect.empty().select2({
                            placeholder: 'Selecciona un bulto',
                            allowClear: true,
                            language: {
                                noResults: function() {
                                    return "No se encontraron resultados";
                                }
                            },
                            data: select2BultoData
                        });
                        bultoSelect.prop('disabled', false);
                        // No preseleccionar ningún bulto, dejar que el usuario elija.
                        // Si se quisiera preseleccionar el primero (no recomendado usualmente):
                        // if (select2BultoData.length > 0) {
                        //     bultoSelect.val(select2BultoData[0].id).trigger('change');
                        // } else {
                        //     bultoSelect.val(null).trigger('change');
                        // }
                        bultoSelect.val(null).trigger(
                        'change'); // Asegurar que el placeholder se muestre
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar bultos para OP " + selectedOp + ":", error);
                        bultoSelect.empty().append('<option value="">Error al cargar bultos</option>');
                        bultoSelect.select2({
                            placeholder: 'Error al cargar bultos'
                        });
                        bultoSelect.prop('disabled',
                        false); // Habilitar para que se pueda reintentar si es necesario
                    }
                });
            }

            // --- 1. CARGAR OPCIONES DE OP Y CONFIGURAR SELECT2 PARA OP_SELECCION ---
            $.ajax({
                url: "{{ route('AQLV3.obtener.op') }}",
                type: 'GET',
                dataType: 'json',
                success: function(dataOpsServer) {
                    let select2OpData = dataOpsServer.map(item => ({
                        id: item.prodid,
                        text: item.prodid
                    }));

                    const selectedValueOpFromUrl = getParameterByName('op');

                    // Lógica para preseleccionar si viene un valor en la URL
                    if (selectedValueOpFromUrl) {
                        const valueExistsInLoadedData = select2OpData.some(item => item.id ===
                            selectedValueOpFromUrl);
                        if (!valueExistsInLoadedData) {
                            // Si el valor de la URL no está en los datos masivos, lo añadimos.
                            console.warn(
                                `El valor OP '${selectedValueOpFromUrl}' de la URL no estaba en la lista inicial. Añadiéndolo para selección.`
                                );
                            select2OpData.unshift({
                                id: selectedValueOpFromUrl,
                                text: selectedValueOpFromUrl
                            });
                            // Opcional: re-ordenar `select2OpData` si el orden es crítico
                            // select2OpData.sort((a, b) => a.text.localeCompare(b.text));
                        }
                    }

                    opSelect.empty().select2({
                        placeholder: 'Selecciona una OP',
                        allowClear: true,
                        language: {
                            noResults: function() {
                                return "No se encontraron resultados";
                            }
                        },
                        data: select2OpData // Proporcionar los datos locales
                    });

                    // Intentar preseleccionar el valor DESPUÉS de que Select2 esté inicializado con datos
                    if (selectedValueOpFromUrl) {
                        opSelect.val(selectedValueOpFromUrl).trigger(
                        'change'); // Esto disparará el evento 'change' de opSelect
                    } else {
                        // Si no hay OP en la URL, inicializar el select de bultos en su estado "Selecciona una OP primero"
                        cargarBultosParaOP(null);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al cargar opciones OP:", error);
                    opSelect.empty().append('<option value="">Error al cargar OPs</option>');
                    opSelect.select2({
                        placeholder: 'Error al cargar OPs'
                    });
                    cargarBultosParaOP(null); // También inicializar bultos en estado de error/vacío
                }
            });

            // --- 2. MANEJADOR DE EVENTO 'CHANGE' PARA OP_SELECCION ---
            opSelect.on('change', function() {
                const selectedOp = $(this).val();
                console.log('OP Seleccionada:', selectedOp);
                // Limpiar campos dependientes del bulto y resetear el select de bultos antes de cargar nuevos.
                // La función cargarBultosParaOP ya se encarga de limpiar el select de bultos y los campos dependientes si selectedOp es nulo.
                cargarBultosParaOP(selectedOp);
            });

            // --- 3. MANEJADOR DE EVENTO 'SELECT2:SELECT' PARA BULTO_SELECCION ---
            // (Cuando el usuario efectivamente selecciona un bulto de la lista)
            bultoSelect.on('select2:select', function(e) {
                const data = e.params.data.extra; // Obtener los datos adicionales del bulto seleccionado

                if (data) {
                    $('#pieza-seleccion').val(data.qty || '');
                    $('#estilo-seleccion').val(data.itemid || '');
                    $('#color-seleccion').val(data.colorname || '');
                    $('#talla-seleccion').val(data.inventsizeid || '');
                    $('#customername_hidden').val(data.customername || '');

                    // Manejo del input oculto 'inventcolorid'
                    const form = $(this).closest('form');
                    form.find('input[name="inventcolorid"][type="hidden"]').remove();
                    if (data.inventcolorid) {
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'inventcolorid',
                            value: data.inventcolorid
                        }).appendTo(form);
                    }
                } else {
                    limpiarCamposDependientesDeBulto();
                }
            });

            // --- 4. MANEJADOR DE EVENTO 'CHANGE' PARA BULTO_SELECCION ---
            // (Esto se dispara también cuando se resetea el valor a null, por ejemplo, al cambiar de OP)
            bultoSelect.on('change', function() {
                const selectedBultoId = $(this).val();
                if (!selectedBultoId) { // Si se deselecciona el bulto (o se resetea)
                    limpiarCamposDependientesDeBulto();
                }
                // La lógica de llenar campos ya está en 'select2:select',
                // aquí solo nos aseguramos de limpiar si el valor se vuelve nulo.
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            const tpSelect = $('#tpSelectAQL');
            const selectedOptionsContainer = $('#selectedOptionsContainerAQL');
            
            let cachedDefectsData = null;

            tpSelect.select2({
                placeholder: 'Selecciona una o más opciones',
                allowClear: true,
                language: { 
                    noResults: function() { return "No se encontraron resultados"; },
                    searching: function() { return "Buscando..."; }
                },
                ajax: {
                    transport: function(params, success, failure) {
                        if (cachedDefectsData) {
                            success(cachedDefectsData);
                            return;
                        }
                        return $.ajax({
                            url: "{{ route('AQLV3.defectos.aql') }}",
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                cachedDefectsData = data;
                                success(data);
                            },
                            error: function() {
                                failure();
                            }
                        });
                    },
                    // ---- INICIO DE LA MODIFICACIÓN ----
                    processResults: function(data, params) { // Añadimos 'params' como argumento
                        let filteredData = data;

                        // Filtramos los datos si hay un término de búsqueda
                        if (params.term && params.term.trim() !== '') {
                            const searchTerm = params.term.toLowerCase();
                            filteredData = data.filter(item => 
                                item.nombre.toLowerCase().includes(searchTerm)
                            );
                        }

                        // Mapeamos los datos (filtrados o todos) al formato {id, text}
                        let options = filteredData.map(item => ({
                            id: item.nombre,
                            text: item.nombre
                        }));

                        // Añadimos nuestra opción especial "CREAR DEFECTO" al inicio de la lista
                        options.unshift({
                            id: 'CREAR_DEFECTO',
                            text: 'CREAR DEFECTO',
                            action: true 
                        });

                        // Devolvemos los datos en el formato que Select2 requiere
                        return {
                            results: options
                        };
                    }
                    // ---- FIN DE LA MODIFICACIÓN ----
                },
                templateResult: function(data) {
                    if (data.action) {
                        return $('<span style="color: #007bff; font-weight: bold;">' + data.text + '</span>');
                    }
                    return data.text;
                }
            });

            // El resto de tu lógica permanece igual
            tpSelect.on('select2:select', function(e) {
                const selectedData = e.params.data;

                if (selectedData.id === 'CREAR_DEFECTO') {
                    tpSelect.val(null).trigger('change');
                    $('#nuevoConceptoModal').modal('show');
                    return;
                }

                addOptionToContainer(selectedData.id, selectedData.text);
                tpSelect.val(null).trigger('change'); 
            });

            function addOptionToContainer(id, text) {
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1" data-id="${id}">
                        <button class="btn btn-primary btn-sm duplicate-option" title="Duplicar defecto">+</button>
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option" title="Eliminar defecto">Eliminar</button>
                    </div>
                `);
                optionElement.find('.duplicate-option').on('click', function() {
                    addOptionToContainer(id, text);
                });
                optionElement.find('.remove-option').on('click', function() {
                    optionElement.remove();
                });
                selectedOptionsContainer.append(optionElement);
            }

            $('#guardarNuevoConcepto').on('click', function() {
                const nuevoDefectoNombre = $('#nuevoConceptoInput').val().trim();
                if (!nuevoDefectoNombre) {
                    alert('Por favor, ingresa un defecto válido.');
                    return;
                }
                $.ajax({
                    url: "{{ route('AQLV3.crear.defecto.aql') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        nombre: nuevoDefectoNombre,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(newDefect) {
                        addOptionToContainer(newDefect.nombre, newDefect.nombre);
                        cachedDefectsData = null; // Invalidamos caché para recargar con el nuevo defecto
                        $('#nuevoConceptoModal').modal('hide');
                        $('#nuevoConceptoInput').val('');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Ocurrió un error al guardar el defecto.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage += ' ' + xhr.responseJSON.error;
                        }
                        alert(errorMessage);
                    },
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            const nombreSelect = $('#nombre_select');
            const selectedOptionsContainerNombre = $('#selectedOptionsContainerNombre');
            const selectedIds = new Set();
            let localData = [];
            let dataLoaded = false;

            function initializeSelect2WithLocalData() {
                if (nombreSelect.data('select2')) {
                    nombreSelect.select2('destroy');
                }

                nombreSelect.empty(); // Limpia opciones previas si existen
                nombreSelect.select2({
                    placeholder: 'Selecciona una opción',
                    allowClear: true,
                    data: localData,
                    matcher: function(params, data) {
                        if ($.trim(params.term) === '') return data;
                        if (typeof data.text === 'undefined') return null;

                        const term = params.term.toLowerCase();
                        const text = data.text.toLowerCase();

                        return text.includes(term) ? data : null;
                    },
                    language: {
                        noResults: function() {
                            return "No se encontraron resultados";
                        }
                    }
                });

                nombreSelect.prop('disabled', false); // Asegura que esté habilitado
                nombreSelect.val(null).trigger('change'); // Limpia selección automática
            }

            nombreSelect.select2({
                placeholder: 'Haz clic para cargar opciones...',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "Haz clic para cargar opciones.";
                    }
                }
            });

            nombreSelect.one('select2:open', function() {
                if (dataLoaded) {
                    initializeSelect2WithLocalData();
                    return;
                }

                if (nombreSelect.data('select2')) {
                    nombreSelect.select2('destroy');
                }

                nombreSelect.select2({
                    placeholder: 'Cargando datos...',
                    disabled: true
                });

                $.ajax({
                    url: "{{ route('AQLV3.obtener.nombres') }}",
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        modulo: $('#modulo').val()
                    },
                    success: function(data) {
                        localData = data.map(item => ({
                            id: item.name,
                            text: `${item.personnelnumber} - ${item.name}`
                        }));

                        dataLoaded = true;
                        initializeSelect2WithLocalData();

                        setTimeout(() => nombreSelect.select2('open'), 50);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error al cargar los datos:", error);
                        alert('Ocurrió un error al cargar las opciones.');
                    }
                });
            });

            nombreSelect.on('select2:select', function(e) {
                const selected = e.params.data;
                if (selectedIds.has(selected.id)) {
                    alert('Esta opción ya ha sido seleccionada.');
                    nombreSelect.val(null).trigger('change');
                    return;
                }

                addOptionToContainer(selected.id, selected.id);
                nombreSelect.val(null).trigger('change');
            });

            function addOptionToContainer(id, text) {
                selectedIds.add(id);
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1" data-id="${id}">
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option">Eliminar</button>
                    </div>
                `);
                optionElement.find('.remove-option').on('click', function() {
                    optionElement.remove();
                    selectedIds.delete(id);
                });
                selectedOptionsContainerNombre.append(optionElement);
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Identificadores de las tablas específicas
            const tablasObjetivo = ['#tabla-datos-principales', '#tabla-datos-secundarios'];

            // Inicializa las columnas ocultas
            const columnasPosteriores = $(
                    'th:contains("TIPO DE DEFECTO"), th:contains("ACCION CORRECTIVA"), th:contains("NOMBRE")')
                .add('td:nth-child(8), td:nth-child(9), td:nth-child(10)');
            columnasPosteriores.hide(); // Ocultar al inicio

            // Detectar cambios en el campo cantidad_rechazada
            $('#cantidad_rechazada').on('input', function() {
                const valor = $(this).val();

                if (valor > 0) {
                    columnasPosteriores.show(); // Mostrar columnas
                    columnasPosteriores.find('input, select').prop('required', true); // Hacer obligatorios
                } else {
                    columnasPosteriores.hide(); // Ocultar columnas
                    columnasPosteriores.find('input, select').prop('required',
                    false); // Quitar obligatoriedad
                }
            });

            // Evento del botón "Guardar"
            $('.btn-verde-xd').on('click', function(e) {
                e.preventDefault(); // Prevenir el envío estándar

                let esValido = true;
                let formData = {};
                let primerCampoInvalido = null; // Para hacer focus en el primer error

                const valorCantidadRechazada = parseInt($('#cantidad_rechazada').val(), 10) || 0;

                if (typeof tablasObjetivo !== 'undefined' && tablasObjetivo.length > 0) {
                    selectorValidacion =
                        `${tablasObjetivo.join(', ')} input:visible, ${tablasObjetivo.join(', ')} select:visible`;
                }


                $(selectorValidacion).not('#tpSelectAQL, #nombre_select').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();

                    if ($(this).prop('required') && (!value || (Array.isArray(value) && value
                            .length === 0))) {
                        esValido = false;
                        $(this).addClass('is-invalid');
                        if (!primerCampoInvalido) {
                            primerCampoInvalido = $(this);
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                    }

                    if (name) {
                        formData[name] = value;
                    }
                });

                if (!esValido) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos incompletos',
                        text: 'Por favor, completa todos los campos requeridos.',
                        didOpen: () => {
                            if (primerCampoInvalido) {
                                primerCampoInvalido.focus();
                            }
                        }
                    });
                    return;
                }

                // Validaciones adicionales si cantidad_rechazada > 0
                if (valorCantidadRechazada > 0) {
                    if ($('#selectedOptionsContainerAQL').children().length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: 'Por favor, selecciona al menos una opción en "Tipo de Defecto".'
                        });
                        return;
                    }

                    if ($('#selectedOptionsContainerNombre').children().length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: 'Por favor, selecciona al menos una opción en "Nombre".'
                        });
                        return;
                    }

                    const defectCount = $('#selectedOptionsContainerAQL .selected-option').length;
                    if (defectCount !== valorCantidadRechazada) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Verificación de cantidades',
                            text: `La cantidad de defectos seleccionados (${defectCount}) debe coincidir con las piezas rechazadas (${valorCantidadRechazada}).`
                        });
                        return;
                    }
                }

                // Serializar las opciones seleccionadas
                const selectedAQL = [];
                if (valorCantidadRechazada > 0) {
                    $('#selectedOptionsContainerAQL .selected-option').each(function() {
                        // Extraer el texto del span, que es más confiable que .text() del div completo
                        let text = $(this).find('.option-text').text().trim();
                        selectedAQL.push(text);
                    });
                }
                formData['selectedAQL'] = selectedAQL;


                const selectedNombre = [];
                if (valorCantidadRechazada > 0) { // Asumo que esto también depende de cantidad_rechazada
                    $('#selectedOptionsContainerNombre .selected-option').each(function() {
                        // Similarmente, si tienes una estructura específica para el texto
                        let text = $(this).find('.option-text').text()
                    .trim(); // Ajusta si la clase es otra
                        if (!text) { // Fallback si no hay .option-text
                            text = $(this).text().trim().replace(/\bEliminar\b/g, '').replace(/^\+/,
                                '').trim();
                        }
                        selectedNombre.push(text);
                    });
                }
                formData['selectedNombre'] = selectedNombre;


                // ** Ajuste adicional ** (Este bloque parece redundante si el primer loop ya captura todo)
                // Si `tablasObjetivo` o el selector general ya cubren `#tabla-datos-principales`, este bloque puede no ser necesario
                // o puede simplificarse para solo añadir campos que no se hayan capturado (inputs hidden, por ejemplo)
                // Reevalúa si este bloque es estrictamente necesario o si el primer bucle de validación ya recolecta todo.
                $('#tabla-datos-principales input, #tabla-datos-principales select').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();
                    // Añadir solo si el nombre existe y no fue capturado previamente,
                    // o si quieres asegurar que estos valores sobreescriban (cuidado con eso)
                    if (name && typeof formData[name] === 'undefined') {
                        formData[name] = value;
                    } else if (name && formData[name] !== value) {
                        // Considera si este es el comportamiento deseado: ¿sobrescribir si ya existe y es diferente?
                        // Esto podría pasar si un campo está tanto en `tablasObjetivo` como en `#tabla-datos-principales`.
                        formData[name] = value;
                    }
                });

                // Añadir campos ocultos que podrían no ser visibles pero son necesarios
                $('form input[type="hidden"]').each(function() {
                    const name = $(this).attr('name');
                    const value = $(this).val();
                    if (name && typeof formData[name] ===
                        'undefined') { // Solo añadir si no está ya
                        formData[name] = value;
                    }
                });


                // Enviar datos mediante AJAX
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor, espera.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('AQLV3.guardar.registro') }}",
                    type: 'POST',
                    data: {
                        ...formData, // Desestructura formData aquí
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Guardado!',
                            text: 'Datos guardados correctamente.'
                        }).then(() => {
                            if (valorCantidadRechazada > 0) {
                                location.reload(); // Recargar la página
                            } else {
                                // Limpiar los campos
                                $('#bulto_seleccion').val(null).trigger(
                                'change'); // Usa null para Select2
                                $('#pieza-seleccion').val('');
                                $('#estilo-seleccion').val('');
                                $('#color-seleccion').val('');
                                $('#talla-seleccion').val('');
                                $('#cantidad_auditada').val('');
                                $('#cantidad_rechazada').val(
                                ''); // Debería ser 0 o null
                                $('#selectedOptionsContainerAQL').empty();
                                $('#accion_correctiva').val('');
                                $('#selectedOptionsContainerNombre').empty();

                                // Resetear cualquier otro campo del formulario si es necesario
                                // Ejemplo: $('form#tuFormulario')[0].reset(); (si es un <form>)
                                // O limpiar campos uno por uno.

                                // Disparar evento personalizado
                                const event = new Event('registroGuardado');
                                window.dispatchEvent(event);
                            }
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al guardar los datos. Por favor, intenta nuevamente.'
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Listener para el evento personalizado 'registroGuardado'
            window.addEventListener('registroGuardado', function() {
                cargarRegistrosUnificado();
            });

            let intervaloVerificarTiempos = null; // Para controlar el intervalo de verificación de paros

            /**
             * Función principal para cargar y mostrar todos los registros (Turno Normal y Tiempo Extra).
             */
            function cargarRegistrosUnificado() {
                const fechaActual = new Date().toISOString().slice(0, 10);
                const moduloInput = document.getElementById('modulo'); // Asume que tienes un <input id="modulo">

                if (!moduloInput || !moduloInput.value) {
                    console.error("El input del módulo no está definido o no tiene valor.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Configuración',
                        text: 'No se pudo encontrar el valor del módulo. Por favor, verifica la página.'
                    });
                    return;
                }
                const modulo = moduloInput.value;

                // Mostrar un loader general mientras se cargan los datos
                //Swal.fire({
                //    title: 'Cargando Registros...',
                //    text: 'Por favor, espera un momento.',
                //    allowOutsideClick: false,
                //    didOpen: () => {
                //        Swal.showLoading();
                //    }
                //});

                $.ajax({
                    url: "{{ route('AQLV3.mostrar.registros') }}", // NUEVA RUTA UNIFICADA
                    type: "GET",
                    data: {
                        fechaActual: fechaActual, // El controlador tomará Carbon::now() si este no se envía
                        modulo: modulo
                    },
                    success: function(response) {
                        Swal.close(); // Cerrar el loader

                        // Procesar y mostrar registros para Turno Normal
                        procesarYMostrarRegistros(
                            response.turno_normal || [],
                            '#tabla_registros_dia',
                            'normal', { // IDs de las tablas de totales para Turno Normal
                                piezasDia: 'tabla-piezas-dia',
                                bultosTotales: 'tabla-bultos-totales',
                                piezasEnBultos: 'tabla-piezas-bultos'
                            }
                        );

                        // Procesar y mostrar registros para Tiempo Extra
                        procesarYMostrarRegistros(
                            response.tiempo_extra || [],
                            '#tabla_registros_tiempo_extra',
                            'te', { // IDs de las tablas de totales para Tiempo Extra
                                piezasDia: 'tabla-piezas-dia-TE', // Asegúrate que estos IDs existan en tu HTML
                                bultosTotales: 'tabla-bultos-totales-TE',
                                piezasEnBultos: 'tabla-piezas-bultos-TE'
                            }
                        );

                        // (Re)iniciar la monitorización de tiempos de paro para ambas tablas
                        iniciarOReiniciarMonitorizacionParos();
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de Carga',
                            text: 'No se pudieron cargar los registros. Por favor, intenta recargar la página.'
                        });
                        console.error("Error al cargar registros unificados:", xhr.responseText);
                    }
                });
            }

            /**
             * Procesa un conjunto de registros y los muestra en la tabla especificada,
             * actualizando también sus tablas de totales correspondientes.
             * @param {Array} registros - Array de objetos de registro.
             * @param {string} tablaSelector - Selector CSS para la tabla principal (ej: '#tabla_registros_dia').
             * @param {string} tipoTurno - Identificador ('normal' o 'te').
             * @param {object} idsTablasTotales - Objeto con los IDs de las tablas de totales.
             */
            function procesarYMostrarRegistros(registros, tablaSelector, tipoTurno, idsTablasTotales) {
                const tbody = document.querySelector(`${tablaSelector} tbody`);
                if (!tbody) {
                    console.error(`Tbody no encontrado para ${tablaSelector}`);
                    return;
                }
                tbody.innerHTML = ""; // Limpiar contenido actual

                let totales = {
                    piezasAuditadas: 0,
                    piezasRechazadas: 0,
                    bultosAuditados: 0,
                    bultosRechazados: 0,
                    piezasEnBultos: 0
                };

                if (registros.length === 0) {
                    const numColumnas = $(`${tablaSelector} thead th`).length ||
                    12; // Default a 12 si no se encuentra
                    tbody.innerHTML =
                        `<tr><td colspan="${numColumnas}" class="text-center">No hay registros para mostrar.</td></tr>`;
                } else {
                    registros.forEach(function(registro) {
                        // Clases específicas para botones si es necesario, o usar data-attributes
                        const claseBotonEliminar =
                        `btn-eliminar-${tipoTurno}`; // ej. btn-eliminar-normal, btn-eliminar-te
                        const claseBotonFinalizarParo =
                        `btn-finalizar-paro-${tipoTurno}`; // ej. btn-finalizar-paro-normal

                        // Construir la fila de la tabla principal
                        const filaHtml = `
                            <tr class="${registro.tiempo_extra ? 'bg-light-blue' : ''}">
                                <td>
                                    ${registro.inicio_paro === null
                                        ? '-'
                                        : registro.fin_paro
                                            ? (registro.minutos_paro !== null ? registro.minutos_paro : '-')
                                            : `<button class="btn btn-primary btn-sm ${claseBotonFinalizarParo}" data-id="${registro.id}">Fin Paro AQL</button>`
                                    }
                                </td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.bulto || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.pieza || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.talla || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.color || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.estilo || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_auditada || 0}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_rechazada || 0}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" readonly value="${
                                    registro.tp_auditoria_a_q_l && registro.tp_auditoria_a_q_l.length > 0
                                        ? registro.tp_auditoria_a_q_l.map(tp => tp.tp).join(', ')
                                        : '-'
                                }"></td>
                                <td>
                                    <button class="btn btn-danger btn-sm ${claseBotonEliminar}" data-id="${registro.id}">
                                        Eliminar
                                    </button>
                                </td>
                                <td>${registro.created_at ? new Date(registro.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }) : '-'}</td>
                                <td>
                                    <input type="text" class="form-control texto-blanco" value="${(registro.reparacion_rechazo !== null && registro.reparacion_rechazo !== '') ? registro.reparacion_rechazo : '-'}" readonly>
                                </td>
                            </tr>
                        `;
                        tbody.insertAdjacentHTML('beforeend', filaHtml);

                        // Acumular totales
                        totales.piezasAuditadas += parseInt(registro.cantidad_auditada) || 0;
                        totales.piezasRechazadas += parseInt(registro.cantidad_rechazada) || 0;
                        totales.bultosAuditados += 1;
                        if ((parseInt(registro.cantidad_rechazada) || 0) > 0) {
                            totales.bultosRechazados += 1;
                        }
                        totales.piezasEnBultos += parseInt(registro.pieza) ||
                        0; // Asegúrate que 'pieza' sea la cantidad de piezas en el bulto
                    });
                }

                // Actualizar las tablas de totales correspondientes
                actualizarTablaDeTotales(idsTablasTotales.piezasDia, [totales.piezasAuditadas, totales
                    .piezasRechazadas
                ], true);
                actualizarTablaDeTotales(idsTablasTotales.bultosTotales, [totales.bultosAuditados, totales
                    .bultosRechazados
                ], true);
                actualizarTablaDeTotales(idsTablasTotales.piezasEnBultos, [totales.piezasEnBultos],
                false); // Solo un valor, sin porcentaje
            }

            /**
             * Función genérica para actualizar una tabla de totales (piezas, bultos, etc.).
             * @param {string} tablaId - El ID de la tabla de totales (ej: 'tabla-piezas-dia').
             * @param {Array<number>} valores - Array de valores a mostrar en los inputs.
             * @param {boolean} calcularPorcentaje - Si se debe calcular y mostrar un porcentaje.
             */
            function actualizarTablaDeTotales(tablaId, valores, calcularPorcentaje = false) {
                const tabla = document.getElementById(tablaId);
                if (!tabla) {
                    console.warn(`Tabla de totales con ID '${tablaId}' no encontrada.`);
                    return;
                }
                let tbody = tabla.querySelector("tbody");
                if (!tbody) { // Crear tbody si no existe
                    tbody = document.createElement('tbody');
                    tabla.appendChild(tbody);
                }

                let fila = tbody.querySelector("tr:first-child");
                if (!fila) {
                    // Crear la fila y los inputs si no existen
                    const numInputsEsperados = valores.length + (calcularPorcentaje ? 1 : 0);
                    let tdsHtml = '';
                    for (let i = 0; i < numInputsEsperados; i++) {
                        tdsHtml +=
                            `<td><input type="text" class="form-control texto-blanco" readonly></td>`;
                    }
                    fila = document.createElement('tr');
                    fila.innerHTML = tdsHtml;
                    tbody.appendChild(fila);
                }

                const inputs = fila.querySelectorAll("input");
                valores.forEach((valor, index) => {
                    if (inputs[index]) {
                        inputs[index].value = valor || 0;
                    }
                });

                if (calcularPorcentaje && inputs.length > valores.length) { // Hay un input extra para el porcentaje
                    const total = parseFloat(valores[0]) || 0; // ej. piezas auditadas, bultos auditados
                    const parcial = parseFloat(valores[1]) || 0; // ej. piezas rechazadas, bultos rechazados
                    const porcentaje = total > 0 ? ((parcial / total) * 100).toFixed(2) : "0.00";
                    inputs[valores.length].value = `${porcentaje}%`;
                }
            }


            /**
             * Inicia o reinicia el intervalo para verificar tiempos de paro en ambas tablas.
             */
            function iniciarOReiniciarMonitorizacionParos() {
                if (intervaloVerificarTiempos) {
                    clearInterval(intervaloVerificarTiempos);
                }
                // Ejecutar inmediatamente para ambas tablas y luego establecer intervalo
                verificarTiemposParoTabla('#tabla_registros_dia');
                verificarTiemposParoTabla('#tabla_registros_tiempo_extra');

                intervaloVerificarTiempos = setInterval(function() {
                    verificarTiemposParoTabla('#tabla_registros_dia');
                    verificarTiemposParoTabla('#tabla_registros_tiempo_extra');
                }, 60000); // Cada minuto
            }


            /**
             * Verifica los tiempos de paro para una tabla específica y aplica estilos.
             * @param {string} tablaSelector - Selector CSS de la tabla principal.
             */
            function verificarTiemposParoTabla(tablaSelector) {
                const ahora = new Date();
                document.querySelectorAll(`${tablaSelector} tbody tr`).forEach(fila => {
                    // Usar la clase común que definimos en el HTML, por ejemplo, btn-finalizar-paro-normal o btn-finalizar-paro-te
                    // O si es una clase genérica .btn-finalizar-paro-en-tabla
                    const tipoTurno = tablaSelector.includes('tiempo_extra') ? 'te' : 'normal';
                    const claseBotonFinalizarParo = `btn-finalizar-paro-${tipoTurno}`;

                    const botonParo = fila.querySelector(`.${claseBotonFinalizarParo}`);
                    const celdaHora = fila.cells[fila.cells.length - 2]; // Penúltima celda es la hora

                    // Restablecer estilo por defecto
                    fila.style.backgroundColor = "";
                    fila.style.color = "";

                    if (!botonParo || !celdaHora) { // Si no hay botón, no es un paro activo
                        return;
                    }

                    const horaRegistroTexto = celdaHora.textContent.trim();
                    if (!horaRegistroTexto || horaRegistroTexto === '-') return; // Si no hay hora válida

                    try {
                        // Asumimos que la hora está en formato HH:MM:SS
                        const partesHora = horaRegistroTexto.split(':');
                        if (partesHora.length < 2) return; // Formato de hora inválido

                        const hora = parseInt(partesHora[0], 10);
                        const minuto = parseInt(partesHora[1], 10);
                        const segundo = partesHora[2] ? parseInt(partesHora[2], 10) : 0;

                        if (isNaN(hora) || isNaN(minuto) || isNaN(segundo)) return; // Partes no numéricas

                        const horaRegistro = new Date(ahora.getFullYear(), ahora.getMonth(), ahora
                        .getDate(), hora, minuto, segundo);

                        const diferenciaMinutos = Math.floor((ahora - horaRegistro) / 60000);

                        if (diferenciaMinutos >= 15) {
                            fila.style.backgroundColor = "#8B0000"; // Rojo Oscuro
                            fila.style.color = "#fff";
                        } else if (diferenciaMinutos >= 10) {
                            fila.style.backgroundColor = "#996515"; // Naranja/Amarillo Oscuro
                            fila.style.color = "#fff";
                        }
                    } catch (e) {
                        console.error("Error parseando hora para verificarTiemposParo:", horaRegistroTexto,
                            e);
                    }
                });
            }

            // --- MANEJO DE EVENTOS CON DELEGACIÓN ---

            // Eliminar registro (para ambas tablas, diferenciado por clase)
            $(document).on('click', '.btn-eliminar-normal, .btn-eliminar-te', function() {
                const registroId = $(this).data('id');
                // const esTiempoExtra = $(this).hasClass('btn-eliminar-te'); // Para saber de qué tabla viene

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir la eliminación de este registro!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Eliminando...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                        $.ajax({
                            url: "{{ route('eliminar.registro.aql') }}", // Ruta única para eliminar
                            type: "POST",
                            data: {
                                id: registroId,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('¡Eliminado!', response.message ||
                                        'El registro ha sido eliminado.', 'success');
                                    cargarRegistrosUnificado(); // Recargar ambas tablas
                                } else {
                                    Swal.fire('Error', response.message ||
                                        'No se pudo eliminar el registro.', 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error de Comunicación',
                                    'Hubo un error al intentar eliminar el registro.',
                                    'error');
                                console.error("Error al eliminar:", xhr.responseText);
                            }
                        });
                    }
                });
            });

            // Finalizar paro (para ambas tablas, diferenciado por clase)
            $(document).on('click', '.btn-finalizar-paro-normal, .btn-finalizar-paro-te', function() {
                const registroId = $(this).data('id');
                // const esTiempoExtra = $(this).hasClass('btn-finalizar-paro-te');

                Swal.fire({
                    title: 'Piezas Reparadas',
                    input: 'number',
                    inputLabel: 'Ingresa el número de piezas reparadas para este paro:',
                    showCancelButton: true,
                    confirmButtonText: 'Finalizar Paro',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        const num = parseInt(value);
                        if (isNaN(num) || num < 0) {
                            return 'Por favor, ingresa un número válido (0 o mayor).';
                        }
                    }
                }).then((resultPiezas) => {
                    if (resultPiezas.isConfirmed) {
                        const piezasReparadas = resultPiezas.value;
                        // Confirmación adicional antes de enviar
                        Swal.fire({
                            title: 'Confirmar Finalización',
                            text: `Se finalizará el paro con ${piezasReparadas} piezas reparadas. ¿Continuar?`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, continuar',
                            cancelButtonText: 'No'
                        }).then((resultConfirm) => {
                            if (resultConfirm.isConfirmed) {
                                Swal.fire({
                                    title: 'Procesando...',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });
                                $.ajax({
                                    url: "{{ route('AQLV3.finalizar.paro') }}", // Ruta única para finalizar paro
                                    type: "POST",
                                    data: {
                                        id: registroId,
                                        piezasReparadas: piezasReparadas,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Paro Finalizado!',
                                                html: `Minutos de paro: ${response.minutos_paro || '-'}<br>Piezas reparadas: ${response.reparacion_rechazo || '-'}`
                                            });
                                            cargarRegistrosUnificado
                                        (); // Recargar ambas tablas
                                        } else {
                                            Swal.fire('Error', response
                                                .message ||
                                                'No se pudo finalizar el paro.',
                                                'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        Swal.fire('Error de Comunicación',
                                            'Hubo un error al intentar finalizar el paro.',
                                            'error');
                                        console.error(
                                            "Error al finalizar paro:", xhr
                                            .responseText);
                                    }
                                });
                            }
                        });
                    }
                });
            });


            // --- Eventos para los botones "Finalizar" de cada Card de Auditoría ---
            $('#btn-finalizar').on('click', function() {
                finalizarAuditoriaModulo('normal', '#observacion', this); // 'this' es el botón clickeado
            });

            $('#btn-finalizar-TE').on('click', function() {
                finalizarAuditoriaModulo('tiempo_extra', '#observacion-TE',
                this); // 'this' es el botón clickeado
            });

            /**
             * Función para finalizar la auditoría de un módulo específico (Turno Normal o Tiempo Extra).
             * @param {string} tipoTurno - 'normal' o 'tiempo_extra'.
             * @param {string} selectorTextareaObservaciones - Selector CSS para el textarea de observaciones.
             * @param {HTMLElement} botonPresionado - El botón que disparó el evento.
             */
            function finalizarAuditoriaModulo(tipoTurno, selectorTextareaObservaciones, botonPresionado) {
                // Es más robusto obtener el input del módulo cada vez, por si su valor pudiera cambiar
                // o si el elemento no está presente al inicio.
                const moduloInput = document.getElementById(
                'modulo'); // Asumes que hay un input con id="modulo" general

                if (!moduloInput || !moduloInput.value) {
                    Swal.fire('Error', 'No se ha definido el valor del módulo. Por favor, verifica la página.',
                        'error');
                    return;
                }
                const modulo = moduloInput.value;
                const observaciones = $(selectorTextareaObservaciones).val().trim();


                if (observaciones === '') {
                    Swal.fire({
                        title: 'Observaciones Requeridas',
                        text: 'Por favor, ingresa tus observaciones antes de finalizar.',
                        icon: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                Swal.fire({
                    title: `¿Finalizar auditoría de ${tipoTurno === 'normal' ? 'Turno Normal' : 'Tiempo Extra'}?`,
                    text: "Esta acción marcará la auditoría como completada para este módulo y turno. No podrás agregar más registros a este turno después de finalizar.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar',
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true // Opcional: Pone el botón de confirmar a la derecha
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Finalizando Auditoría...',
                            text: 'Por favor, espera un momento.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Asegúrate que la ruta y el token CSRF sean correctos y estén disponibles.
                        // La ruta se define en tus archivos de rutas de Laravel (web.php o api.php).
                        // El token CSRF es importante para la seguridad en las peticiones POST.
                        $.ajax({
                            url: "{{ route('AQLV3.finalizar.auditoria.modulo') }}", // Reemplaza con tu nombre de ruta real
                            type: "POST",
                            data: {
                                modulo: modulo,
                                observaciones: observaciones,
                                tipo_turno: tipoTurno, // Asegúrate que el backend espera 'tipo_turno'
                                _token: "{{ csrf_token() }}" // Token CSRF de Laravel
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Auditoría Finalizada!',
                                        text: response.message ||
                                            'La auditoría se ha finalizado correctamente.'
                                    });
                                    // Deshabilitar el botón correspondiente y el textarea
                                    $(selectorTextareaObservaciones).prop('disabled', true);
                                    $(botonPresionado).prop('disabled',
                                    true); // Deshabilita el botón que fue presionado

                                    // Aquí podrías querer deshabilitar también el formulario de nuevos registros
                                    // para ese turno específico, si tienes uno. Por ejemplo:
                                    // if (tipoTurno === 'normal') {
                                    //     $('#idDelFormularioTurnoNormal :input').prop('disabled', true);
                                    // } else {
                                    //     $('#idDelFormularioTiempoExtra :input').prop('disabled', true);
                                    // }

                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message ||
                                            'No se pudo finalizar la auditoría. Intenta de nuevo.'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error al finalizar auditoría de módulo:", xhr
                                    .responseText);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de Comunicación',
                                    text: 'Hubo un problema al conectar con el servidor. Por favor, revisa la consola para más detalles.'
                                });
                            }
                        });
                    }
                });
            }

            // Carga inicial de registros al cargar la página
            cargarRegistrosUnificado();
        });
    </script>

    <script>
        $(document).ready(function() {
            let datosCargados = false;

            $('#collapseBultos').on('show.bs.collapse', function() {
                if (!datosCargados) {
                    const modulo = $('#bultos-container').data('modulo');

                    $.ajax({
                        url: '/auditoriaAQLV3/registro/bultos-no-finalizados', // Asegúrate que esta ruta sea correcta
                        method: 'GET',
                        data: {
                            modulo: modulo
                        },
                        beforeSend: function() {
                            $('#bultos-container').html(
                                '<div class="text-center mt-3 mb-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando datos...</p></div>'
                                );
                        },
                        success: function(response) {
                            if (response.length > 0) {
                                let contenido = `
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-primary">
                                                <tr>
                                                    <th>Bulto</th>
                                                    <th>Estilo</th>
                                                    <th>Inicio Paro</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                `;
                                response.forEach(item => {
                                    contenido += `
                                        <tr>
                                            <td>${item.bulto}</td>
                                            <td>${item.estilo}</td>
                                            <td>${item.inicio_paro}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm finalizar-paro" data-id="${item.id}">
                                                    Finalizar Paro Pendiente
                                                </button>
                                            </td>
                                        </tr>
                                    `;
                                });
                                contenido += '</tbody></table></div>';
                                $('#bultos-container').html(contenido);
                            } else {
                                $('#bultos-container').html(
                                    '<p class="text-warning text-center mt-3 mb-3">No se encontraron bultos no finalizados.</p>'
                                    );
                            }
                            datosCargados = true;
                        },
                        error: function() {
                            $('#bultos-container').html(
                                '<p class="text-danger text-center mt-3 mb-3">Error al cargar los datos.</p>'
                                );
                            // datosCargados podría quedar en false para permitir un reintento, o true para no reintentar.
                            // Si se quiere reintentar, se deja en false.
                            datosCargados = false;
                        }
                    });
                }
            });

            // Delegamos el evento click para los botones "Finalizar Paro Pendiente"
            $(document).on('click', '.finalizar-paro', function() {
                let paroId = $(this).data('id'); // Renombrado de 'id' a 'paroId' para claridad

                Swal.fire({
                    title: 'Piezas Reparadas',
                    input: 'number',
                    inputLabel: 'Ingresa el número de piezas reparadas',
                    showCancelButton: true,
                    confirmButtonText: 'Siguiente <i class="fas fa-arrow-right"></i>',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        const numValue = parseInt(value);
                        if (isNaN(numValue) || numValue < 0) {
                            return 'Por favor, ingresa un número válido de piezas (0 o mayor).';
                        }
                    }
                }).then((resultPiezas) => {
                    if (resultPiezas.isConfirmed) {
                        const piezasReparadas = resultPiezas.value;

                        Swal.fire({
                            title: 'Confirmar Acción',
                            text: '¿Estás seguro de que deseas finalizar este paro?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Sí, finalizar',
                            cancelButtonText: 'No, cancelar'
                        }).then((resultConfirm) => {
                            if (resultConfirm.isConfirmed) {
                                Swal.fire({
                                    title: 'Procesando...',
                                    text: 'Finalizando el paro, por favor espera.',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading();
                                    }
                                });

                                $.ajax({
                                    url: '/api/finalizar-paro-aql-despues', // Asegúrate que esta ruta sea correcta
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    data: {
                                        id: paroId, // Nombre del parámetro que espera tu backend
                                        piezasReparadas: piezasReparadas
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Paro Finalizado!',
                                                html: `El paro se finalizó correctamente.<br>
                                                    <b>Minutos de Paro:</b> ${response.minutos_paro || 'N/A'}<br>
                                                    <b>Piezas Reparadas Registradas:</b> ${response.reparacion_rechazo || 'N/A'}`
                                            }).then(() => {
                                                $('#collapseBultos')
                                                    .collapse('hide');
                                                datosCargados =
                                                false; // Para que se recarguen los datos la próxima vez
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error al Finalizar',
                                                text: response
                                                    .message ||
                                                    'No se pudo finalizar el paro.'
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error de Comunicación',
                                            text: 'Ocurrió un error al intentar finalizar el paro. Por favor, intenta más tarde.'
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openModalAQL');
            const closeModalBtn = document.getElementById('closeModalAQL');
            const modal = document.getElementById('customModalAQL');
            const tbody = document.getElementById('tablaProcesosAQL');

            // Abrir el modal y cargar los datos con AJAX
            openModalBtn.addEventListener('click', function() {
                modal.style.display = 'block';

                // Hacer la petición AJAX
                fetch('{{ route('AQLV3.proceso_actual') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar la tabla
                        tbody.innerHTML = '';

                        // Insertar las filas dinámicamente
                        data.forEach(proceso => {
                            const row = `
                                <tr>
                                    <td>
                                        <form method="POST" action="{{ route('AQLV3.formAltaAQLV3') }}">
                                            @csrf
                                            <input type="hidden" name="modulo" value="${proceso.modulo}">
                                            <input type="hidden" name="op" value="${proceso.op}">
                                            <input type="hidden" name="estilo" value="${proceso.estilo}">
                                            <input type="hidden" name="cliente" value="${proceso.cliente}">
                                            <input type="hidden" name="team_leader" value="${proceso.team_leader}">
                                            <input type="hidden" name="gerente_produccion" value="${proceso.gerente_produccion}">
                                            <input type="hidden" name="auditor" value="${proceso.auditor}">
                                            <input type="hidden" name="turno" value="${proceso.turno}">
                                            <button type="submit" class="btn btn-primary">Acceder</button>
                                        </form>
                                    </td>
                                    <td>${proceso.modulo}</td>
                                    <td>${proceso.op}</td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    })
                    .catch(error => console.error('Error al cargar los procesos:', error));
            });

            // Cerrar el modal con el botón
            closeModalBtn.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            // Cerrar el modal con la tecla "ESC"
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

@endsection
