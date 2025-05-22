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
            background-color: rgba(0,0,0,0.9);
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
            z-index: 1000; /* Asegúrate de que sea mayor que cualquier elemento que pueda superponer */
            position: relative; /* Esto ayuda a que el z-index funcione */
            display: inline-block; /* Asegura que el área sea del tamaño del contenido */
            width: auto; /* Ajusta el tamaño al contenido */
        }

    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
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
                                <h4>Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}</h4>
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
                                    <input type="text" id="searchInputAQL" class="form-control mb-3" placeholder="Buscar Módulo u OP">
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
                @if($resultadoFinal == true)
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
                                        <input type="text" class="form-control texto-blanco" name="modulo" id="modulo" value="{{ $data['modulo'] }}" readonly>
                                    </td>
                                    <td>
                                        <select class="form-control texto-blanco" name="op_seleccion" id="op_seleccion" required title="Selecciona una OP">
                                            <option value="">Cargando opciones...</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="team_leader" id="team_leader" value="{{ $data['team_leader'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="gerente_produccion" value="{{ $data['gerente_produccion'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="auditor" id="auditor" value="{{ $data['auditor'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="turno" id="turno" value="{{ $data['turno'] }}" readonly>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" name="customername" id="customername_hidden" readonly>
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
                                        <select name="bulto_seleccion" id="bulto_seleccion" class="form-control" required title="Por favor, selecciona una opción">
                                            <option value="">Cargando bultos...</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control texto-blanco" name="pieza" id="pieza-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="estilo" id="estilo-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="color" id="color-seleccion" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" name="talla" id="talla-seleccion" readonly></td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada" id="cantidad_auditada" required></td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada" id="cantidad_rechazada" required></td>
                                    <td> 
                                        <select id="tpSelectAQL" class="form-control w-100" title="Por favor, selecciona una opción"></select>
                                        <div id="selectedOptionsContainerAQL" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
                                    </td>
                                    <td><input type="text" class="form-control" name="accion_correctiva" id="accion_correctiva" required></td>
                                    <td>
                                        <select name="nombre-none" id="nombre_select" class="form-control"></select> 
                                        <div id="selectedOptionsContainerNombre" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
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
                                <button class="btn btn-link text-light text-decoration-none w-100 text-left" type="button" data-toggle="collapse" data-target="#collapseBultos" aria-expanded="false" aria-controls="collapseBultos">
                                    <i class="fa fa-box mr-2"></i> Mostrar Bultos No Finalizados
                                </button>
                            </h2>
                        </div>
                        <div id="collapseBultos" class="collapse" aria-labelledby="headingBultos" data-parent="#accordionBultos">
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
                                <label for="observacion-TE" class="col-sm-6 col-form-label">Observaciones Tiempo Extra:</label>
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
    <div class="modal fade" id="nuevoConceptoModal" tabindex="-1" role="dialog" aria-labelledby="nuevoConceptoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 id="nuevoConceptoModalLabel">Introduce el nuevo defecto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control bg-dark text-white" id="nuevoConceptoInput" placeholder="Nuevo defecto">
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
            background-color: #1d0f2c; /* Color gris claro */
        }
        
        /* Asegúrate de que los textos permanezcan visibles */
        .tiempo-extra input, 
        .tiempo-extra .form-control, 
        .tiempo-extra button {
            color: #1d0f2c; 
        }
    </style>

    <script>
        $(document).ready(function () {
            const opSelect = $('#op_seleccion');

            function getParameterByName(name) {
                const url = new URL(window.location.href);
                return url.searchParams.get(name);
            }

            // 1. Cargar todos los datos de OP vía AJAX una sola vez
            $.ajax({
                url: "{{ route('AQLV3.obtener.op') }}", // Esta ruta llama a tu función obtenerOpcionesOP modificada
                type: 'GET',
                dataType: 'json',
                success: function (dataFromServer) {
                    // dataFromServer es un array de objetos, ej: [{prodid: 'OP001'}, {prodid: 'OP002'}]
                    
                    // Mapear los datos al formato que Select2 espera para la opción 'data':
                    // un array de objetos con 'id' y 'text'.
                    let select2Data = dataFromServer.map(item => ({
                        id: item.prodid,
                        text: item.prodid 
                    })); 

                    const selectedValueFromUrl = getParameterByName('op');

                    // Lógica para preseleccionar si viene un valor en la URL
                    if (selectedValueFromUrl) {
                        const valueExistsInLoadedData = select2Data.some(item => item.id === selectedValueFromUrl);
                        
                        if (!valueExistsInLoadedData) {
                            // Si el valor de la URL no está en los datos masivos y quieres que aparezca
                            // lo añadimos a `select2Data`. Esto asegura que pueda ser seleccionado.
                            console.warn(`El valor '${selectedValueFromUrl}' de la URL no estaba en la lista inicial. Añadiéndolo para selección.`);
                            select2Data.unshift({ id: selectedValueFromUrl, text: selectedValueFromUrl }); // Añadir al principio
                            // Opcional: re-ordenar `select2Data` si el orden es crítico después de añadir
                            // select2Data.sort((a, b) => a.text.localeCompare(b.text));
                        }
                    }

                    // Configuración de Select2 para usar datos locales
                    const select2Options = {
                        placeholder: 'Selecciona una opción',
                        allowClear: true,
                        language: {
                            noResults: function () {
                                return "No se encontraron resultados";
                            }
                        },
                        data: select2Data // Proporcionar los datos locales a Select2
                    };

                    // Limpiar el select (quitar "Cargando opciones...") e inicializar Select2
                    opSelect.empty().select2(select2Options);

                    // Intentar preseleccionar el valor después de que Select2 esté inicializado con datos
                    if (selectedValueFromUrl) {
                        opSelect.val(selectedValueFromUrl).trigger('change');
                    }

                },
                error: function (xhr, status, error) {
                    console.error("Error al cargar opciones OP:", error);
                    opSelect.empty().append('<option value="">Error al cargar opciones</option>');
                    // Opcionalmente, inicializar Select2 con un mensaje de error
                    opSelect.select2({
                        placeholder: 'Error al cargar',
                        language: {
                            noResults: function () { return "Error al cargar opciones"; }
                        }
                    });
                }
            });

            // Manejador de evento 'change' (para tus acciones posteriores)
            opSelect.on('change', function () {
                const selectedValue = $(this).val();
                console.log('Valor seleccionado:', selectedValue);
                // Aquí puedes realizar otras acciones cuando el usuario selecciona una OP
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const opSelect = $('#op_seleccion'); // Este es tu select de OPs
            const bultoSelect = $('#bulto_seleccion');

            $.ajax({
                url: "{{ route('AQLV3.obtener.op') }}", // Ruta para obtener todas las OPs 
                type: 'GET',
                dataType: 'json',
                success: function (dataOpsServer) {
                    let select2OpData = dataOpsServer.map(item => ({
                        id: item.prodid,
                        text: item.prodid
                    }));

                    // (Aquí iría la lógica de preselección de OP si viene por URL, como en el ejemplo anterior)
                    // const selectedValueOpFromUrl = getParameterByName('op');
                    // if (selectedValueOpFromUrl) { ... añadir a select2OpData si no existe ... }
                    
                    opSelect.empty().select2({
                        placeholder: 'Selecciona una OP',
                        allowClear: true,
                        language: { noResults: function () { return "No se encontraron resultados"; } },
                        data: select2OpData
                    });

                    // (Si hay una OP preseleccionada por URL, establecerla)
                    // if (selectedValueOpFromUrl) { opSelect.val(selectedValueOpFromUrl).trigger('change'); }
                    // O, si no hay preselección de URL, pero quieres cargar bultos si hay un valor inicial:
                    const initialOp = opSelect.val();
                    if (initialOp) {
                        cargarBultosParaOP(initialOp);
                    }
                },
                error: function() {
                    opSelect.empty().append('<option value="">Error al cargar OPs</option>');
                    opSelect.select2({ placeholder: 'Error al cargar OPs' });
                }
            });
            // --- FIN: Lógica para op_seleccion ---


            // Función para cargar bultos para una OP específica
            function cargarBultosParaOP(selectedOp) {
                if (!selectedOp) {
                    bultoSelect.empty().append('<option value="">Selecciona una OP primero...</option>');
                    bultoSelect.select2({ // Re-inicializar para mostrar placeholder
                        placeholder: 'Selecciona una OP primero',
                        allowClear: true,
                        data: [] // Sin datos
                    }).val(null).trigger('change');
                    limpiarCamposDependientesDeBulto(); // Limpiar campos si se deselecciona OP
                    return;
                }

                bultoSelect.empty().append('<option value="">Cargando bultos...</option>').prop('disabled', true);
                bultoSelect.select2({ placeholder: 'Cargando bultos...' }); // Actualizar placeholder visualmente

                $.ajax({
                    url: "{{ route('AQLV3.obtener.bulto') }}", // Ruta al controlador de bultos modificado
                    type: 'GET',
                    dataType: 'json',
                    data: { op: selectedOp }, // Solo enviamos la OP
                    success: function (dataBultosServer) {
                        // dataBultosServer es un array de objetos con todos los datos del bulto
                        const select2BultoData = dataBultosServer.map(item => ({
                            id: item.prodpackticketid,       // Lo que se usará como valor del select
                            text: item.prodpackticketid,     // Lo que se mostrará en el select
                            extra: item                  // Guardar el objeto completo para uso posterior
                        }));

                        bultoSelect.empty().select2({
                            placeholder: 'Selecciona un bulto',
                            allowClear: true,
                            language: { noResults: function () { return "No se encontraron resultados"; } },
                            data: select2BultoData // Usar los datos locales
                        });
                        bultoSelect.prop('disabled', false);
                        // Asegurar que se muestre el placeholder por defecto y no el primer bulto.
                        bultoSelect.val(null).trigger('change');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al cargar bultos para OP " + selectedOp + ":", error);
                        bultoSelect.empty().append('<option value="">Error al cargar bultos</option>');
                        bultoSelect.select2({ placeholder: 'Error al cargar bultos' });
                        bultoSelect.prop('disabled', false);
                    }
                });
            }
            
            function limpiarCamposDependientesDeBulto() {
                $('#pieza-seleccion').val('');
                $('#estilo-seleccion').val('');
                $('#color-seleccion').val('');
                $('#talla-seleccion').val('');
                $('#customername_hidden').val('');
                $('form input[name="inventcolorid"][type="hidden"]').remove(); // Eliminar si se añadió
            }


            // Evento cuando cambia la selección de OP
            opSelect.on('change', function () {
                const selectedOp = $(this).val();
                limpiarCamposDependientesDeBulto(); // Limpia campos antes de cargar nuevos bultos
                bultoSelect.val(null).trigger('change'); // Resetea el select de bultos visualmente y su valor
                cargarBultosParaOP(selectedOp);
            });

            // Evento para manejar la selección de un bulto (esta lógica se mantiene)
            bultoSelect.on('select2:select', function (e) {
                const data = e.params.data.extra; // Obtener los datos adicionales del bulto seleccionado

                if (data) {
                    $('#pieza-seleccion').val(data.qty || '');
                    $('#estilo-seleccion').val(data.itemid || '');
                    $('#color-seleccion').val(data.colorname || '');
                    $('#talla-seleccion').val(data.inventsizeid || '');
                    $('#customername_hidden').val(data.customername || '');
                    
                    // Manejo del input oculto 'inventcolorid'
                    // Primero, remover cualquier input oculto 'inventcolorid' existente para evitar duplicados
                    $(this).closest('form').find('input[name="inventcolorid"][type="hidden"]').remove();
                    // Luego, añadir el nuevo input oculto
                    if(data.inventcolorid) { // Solo añadir si hay valor
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'inventcolorid',
                            value: data.inventcolorid
                        }).appendTo($(this).closest('form')); // Añadir al formulario donde está bultoSelect
                    }
                } else {
                    // Si por alguna razón no hay 'data' (no debería ocurrir si el mapeo es correcto)
                    limpiarCamposDependientesDeBulto();
                }
            });

            // (Si necesitas getParameterByName para preselección por URL)
            // function getParameterByName(name) { ... }
        });
    </script>

    <script>
        $(document).ready(function () {
            const tpSelect = $('#tpSelectAQL');
            const selectedOptionsContainer = $('#selectedOptionsContainerAQL');
            let allDefectsData = []; // Variable para almacenar todos los defectos cargados
            let defectsDataLoaded = false; // Bandera para controlar si los datos ya se cargaron
            let isLoadingDefects = false; // Bandera para evitar múltiples cargas simultáneas

            // Función para procesar y preparar los datos para Select2
            function processDataForSelect2(data) {
                const options = data.map(item => ({
                    id: item.nombre, // O item.id si 'id' es el identificador único
                    text: item.nombre,
                }));
                // Añadir la opción de crear defecto al principio
                options.unshift({ id: 'CREAR_DEFECTO', text: 'CREAR DEFECTO', action: true });
                return options;
            }

            // Función para inicializar o actualizar Select2
            function initializeOrUpdateTpSelect(processedData) {
                if (tpSelect.hasClass("select2-hidden-accessible")) {
                    tpSelect.select2('destroy').empty();
                }

                tpSelect.select2({
                    placeholder: 'Selecciona una o más opciones',
                    allowClear: true,
                    data: processedData,
                    templateResult: function (data) {
                        if (data.action) {
                            return $('<span style="color: #007bff; font-weight: bold;">' + data.text + '</span>');
                        }
                        return data.text;
                    },
                    language: {
                        noResults: function () {
                            return "No se encontraron resultados";
                        },
                        searching: function () {
                            return "Buscando...";
                        }
                    },
                });
                tpSelect.val(null).trigger('change');
            }

            // Función para cargar los defectos
            function loadInitialDefects() {
                // Si ya están cargados y no forzamos recarga, no hacer nada (útil si se llama desde varios sitios)
                // Para el caso de "cargar al abrir", el 'defectsDataLoaded' ya controla esto afuera.
                // Esta función ahora se enfoca solo en cargar.
                if (isLoadingDefects) {
                    return; // Ya hay una carga en curso
                }
                isLoadingDefects = true;

                // Mostrar algún indicador de carga si se desea, p.ej., dentro del select
                // tpSelect.prop('disabled', true); // Deshabilitar mientras carga

                $.ajax({
                    url: "{{ route('AQLV3.defectos.aql') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        allDefectsData = data;
                        const processedData = processDataForSelect2(data);
                        initializeOrUpdateTpSelect(processedData);
                        defectsDataLoaded = true; // Marcar que los datos se cargaron exitosamente
                    },
                    error: function (xhr) {
                        console.error('Error al cargar defectos:', xhr);
                        alert('Error al cargar los defectos.');
                        // Dejar defectsDataLoaded como false para permitir un nuevo intento al abrir
                        defectsDataLoaded = false;
                        // Opcionalmente, reinicializar con solo la opción de crear si falla la carga
                        initializeOrUpdateTpSelect(processDataForSelect2([]));
                    },
                    complete: function() {
                        isLoadingDefects = false; // Termina la carga (exitosa o no)
                        // tpSelect.prop('disabled', false); // Habilitar de nuevo
                    }
                });
            }

            // Inicializar Select2 con la opción "CREAR DEFECTO" únicamente al cargar la página.
            // Los datos completos se cargarán al abrir el select.
            const initialMinimalData = processDataForSelect2([]); // Solo contendrá "CREAR DEFECTO"
            initializeOrUpdateTpSelect(initialMinimalData);


            // Evento para cargar los datos cuando se abre el Select2 por primera vez
            tpSelect.on('select2:open', function () {
                if (!defectsDataLoaded && !isLoadingDefects) { // Solo cargar si no se han cargado y no hay una carga en curso
                    loadInitialDefects();
                }
            });

            // Evento al seleccionar una opción
            tpSelect.on('select2:select', function (e) {
                const selected = e.params.data;

                if (selected.id === 'CREAR_DEFECTO') {
                    $('#nuevoConceptoModal').modal('show');
                    return;
                }

                addOptionToContainer(selected.id, selected.text);
                tpSelect.val(null).trigger('change');
            });

            // Agregar la opción seleccionada al contenedor
            function addOptionToContainer(id, text) {
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1" data-id="${id}">
                        <button class="btn btn-primary btn-sm duplicate-option" title="Duplicar defecto">+</button>
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option" title="Eliminar defecto">Eliminar</button>
                    </div>
                `);

                optionElement.find('.duplicate-option').on('click', function () {
                    addOptionToContainer(id, text);
                });

                optionElement.find('.remove-option').on('click', function () {
                    optionElement.remove();
                });

                selectedOptionsContainer.append(optionElement);
            }

            // Evento para guardar un nuevo defecto
            $('#guardarNuevoConcepto').on('click', function () {
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
                    success: function (newDefect) {
                        addOptionToContainer(newDefect.nombre, newDefect.nombre);
                        
                        // Forzar la recarga de defectos para incluir el nuevo.
                        // Esto invalidará la bandera `defectsDataLoaded` temporalmente si es necesario
                        // o simplemente llamará a loadInitialDefects que actualizará todo.
                        defectsDataLoaded = false; // Para asegurar que se recarguen al abrir si se quiere la lista más fresca
                                                // o podrías simplemente llamar a loadInitialDefects() directamente.
                                                // Llamar a loadInitialDefects() es más directo aquí.
                        loadInitialDefects(); // Esto recargará y actualizará la bandera 'defectsDataLoaded' a true.

                        $('#nuevoConceptoModal').modal('hide');
                        $('#nuevoConceptoInput').val('');
                    },
                    error: function (xhr) {
                        let errorMessage = 'Ocurrió un error al guardar el defecto.';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage += ' ' + xhr.responseJSON.error;
                        }
                        alert(errorMessage);
                    },
                });
            });

            // Cuando el modal de nuevo concepto se cierre
            $('#nuevoConceptoModal').on('hidden.bs.modal', function () {
                tpSelect.val(null).trigger('change');
            });
        });
    </script>

    <script>
        $(document).ready(function () {
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
                    matcher: function (params, data) {
                        if ($.trim(params.term) === '') return data;
                        if (typeof data.text === 'undefined') return null;

                        const term = params.term.toLowerCase();
                        const text = data.text.toLowerCase();

                        return text.includes(term) ? data : null;
                    },
                    language: {
                        noResults: function () {
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
                    noResults: function () {
                        return "Haz clic para cargar opciones.";
                    }
                }
            });

            nombreSelect.one('select2:open', function () {
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
                    success: function (data) {
                        localData = data.map(item => ({
                            id: item.name,
                            text: `${item.personnelnumber} - ${item.name}`
                        }));

                        dataLoaded = true;
                        initializeSelect2WithLocalData();

                        setTimeout(() => nombreSelect.select2('open'), 50);
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al cargar los datos:", error);
                        alert('Ocurrió un error al cargar las opciones.');
                    }
                });
            });

            nombreSelect.on('select2:select', function (e) {
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
                optionElement.find('.remove-option').on('click', function () {
                    optionElement.remove();
                    selectedIds.delete(id);
                });
                selectedOptionsContainerNombre.append(optionElement);
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            // Identificadores de las tablas específicas
            const tablasObjetivo = ['#tabla-datos-principales', '#tabla-datos-secundarios'];

            // Inicializa las columnas ocultas
            const columnasPosteriores = $('th:contains("TIPO DE DEFECTO"), th:contains("ACCION CORRECTIVA"), th:contains("NOMBRE")')
                .add('td:nth-child(8), td:nth-child(9), td:nth-child(10)');
            columnasPosteriores.hide(); // Ocultar al inicio

            // Detectar cambios en el campo cantidad_rechazada
            $('#cantidad_rechazada').on('input', function () {
                const valor = $(this).val();

                if (valor > 0) {
                    columnasPosteriores.show(); // Mostrar columnas
                    columnasPosteriores.find('input, select').prop('required', true); // Hacer obligatorios
                } else {
                    columnasPosteriores.hide(); // Ocultar columnas
                    columnasPosteriores.find('input, select').prop('required', false); // Quitar obligatoriedad
                }
            });

            // Evento del botón "Guardar"
            $('.btn-verde-xd').on('click', function (e) {
                e.preventDefault(); // Prevenir el envío estándar

                let esValido = true;
                let formData = {};
                let primerCampoInvalido = null; // Para hacer focus en el primer error

                const valorCantidadRechazada = parseInt($('#cantidad_rechazada').val(), 10) || 0;

                if (typeof tablasObjetivo !== 'undefined' && tablasObjetivo.length > 0) {
                    selectorValidacion = `${tablasObjetivo.join(', ')} input:visible, ${tablasObjetivo.join(', ')} select:visible`;
                }


                $(selectorValidacion).not('#tpSelectAQL, #nombre_select').each(function () {
                    const name = $(this).attr('name');
                    const value = $(this).val();

                    if ($(this).prop('required') && (!value || (Array.isArray(value) && value.length === 0))) {
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
                    $('#selectedOptionsContainerAQL .selected-option').each(function () {
                        // Extraer el texto del span, que es más confiable que .text() del div completo
                        let text = $(this).find('.option-text').text().trim();
                        selectedAQL.push(text);
                    });
                }
                formData['selectedAQL'] = selectedAQL;


                const selectedNombre = [];
                if (valorCantidadRechazada > 0) { // Asumo que esto también depende de cantidad_rechazada
                    $('#selectedOptionsContainerNombre .selected-option').each(function () {
                        // Similarmente, si tienes una estructura específica para el texto
                        let text = $(this).find('.option-text').text().trim(); // Ajusta si la clase es otra
                        if(!text) { // Fallback si no hay .option-text
                            text = $(this).text().trim().replace(/\bEliminar\b/g, '').replace(/^\+/, '').trim();
                        }
                        selectedNombre.push(text);
                    });
                }
                formData['selectedNombre'] = selectedNombre;


                // ** Ajuste adicional ** (Este bloque parece redundante si el primer loop ya captura todo)
                // Si `tablasObjetivo` o el selector general ya cubren `#tabla-datos-principales`, este bloque puede no ser necesario
                // o puede simplificarse para solo añadir campos que no se hayan capturado (inputs hidden, por ejemplo)
                // Reevalúa si este bloque es estrictamente necesario o si el primer bucle de validación ya recolecta todo.
                $('#tabla-datos-principales input, #tabla-datos-principales select').each(function () {
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
                    if (name && typeof formData[name] === 'undefined') { // Solo añadir si no está ya
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
                    url: "{{ route('guardar.registro.aql') }}",
                    type: 'POST',
                    data: {
                        ...formData, // Desestructura formData aquí
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Guardado!',
                            text: 'Datos guardados correctamente.'
                        }).then(() => {
                            if (valorCantidadRechazada > 0) {
                                location.reload(); // Recargar la página
                            } else {
                                // Limpiar los campos
                                $('#bulto_seleccion').val(null).trigger('change'); // Usa null para Select2
                                $('#pieza-seleccion').val('');
                                $('#estilo-seleccion').val('');
                                $('#color-seleccion').val('');
                                $('#talla-seleccion').val('');
                                $('#cantidad_auditada').val('');
                                $('#cantidad_rechazada').val(''); // Debería ser 0 o null
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
                    error: function (xhr) {
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
        document.addEventListener('DOMContentLoaded', function () {
            // Listener para el evento personalizado 'registroGuardado'
            window.addEventListener('registroGuardado', function () {
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
                Swal.fire({
                    title: 'Cargando Registros...',
                    text: 'Por favor, espera un momento.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: "{{ route('AQLV3.mostrar.registros') }}", // NUEVA RUTA UNIFICADA
                    type: "GET",
                    data: {
                        fechaActual: fechaActual, // El controlador tomará Carbon::now() si este no se envía
                        modulo: modulo
                    },
                    success: function (response) {
                        Swal.close(); // Cerrar el loader

                        // Procesar y mostrar registros para Turno Normal
                        procesarYMostrarRegistros(
                            response.turno_normal || [],
                            '#tabla_registros_dia',
                            'normal',
                            { // IDs de las tablas de totales para Turno Normal
                                piezasDia: 'tabla-piezas-dia',
                                bultosTotales: 'tabla-bultos-totales',
                                piezasEnBultos: 'tabla-piezas-bultos'
                            }
                        );

                        // Procesar y mostrar registros para Tiempo Extra
                        procesarYMostrarRegistros(
                            response.tiempo_extra || [],
                            '#tabla_registros_tiempo_extra',
                            'te',
                            { // IDs de las tablas de totales para Tiempo Extra
                                piezasDia: 'tabla-piezas-dia-TE', // Asegúrate que estos IDs existan en tu HTML
                                bultosTotales: 'tabla-bultos-totales-TE',
                                piezasEnBultos: 'tabla-piezas-bultos-TE'
                            }
                        );

                        // (Re)iniciar la monitorización de tiempos de paro para ambas tablas
                        iniciarOReiniciarMonitorizacionParos();
                    },
                    error: function (xhr, status, error) {
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
                    const numColumnas = $(`${tablaSelector} thead th`).length || 12; // Default a 12 si no se encuentra
                    tbody.innerHTML = `<tr><td colspan="${numColumnas}" class="text-center">No hay registros para mostrar.</td></tr>`;
                } else {
                    registros.forEach(function (registro) {
                        // Clases específicas para botones si es necesario, o usar data-attributes
                        const claseBotonEliminar = `btn-eliminar-${tipoTurno}`; // ej. btn-eliminar-normal, btn-eliminar-te
                        const claseBotonFinalizarParo = `btn-finalizar-paro-${tipoTurno}`; // ej. btn-finalizar-paro-normal

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
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.bulto || ''}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.pieza || ''}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.talla || ''}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.color || ''}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.estilo || ''}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.cantidad_auditada || 0}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" value="${registro.cantidad_rechazada || 0}" readonly></td>
                                <td><input type="text" class="form-control form-control-sm texto-blanco" readonly value="${
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
                                    <input type="text" class="form-control form-control-sm texto-blanco" value="${(registro.reparacion_rechazo !== null && registro.reparacion_rechazo !== '') ? registro.reparacion_rechazo : '-'}" readonly>
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
                        totales.piezasEnBultos += parseInt(registro.pieza) || 0; // Asegúrate que 'pieza' sea la cantidad de piezas en el bulto
                    });
                }

                // Actualizar las tablas de totales correspondientes
                actualizarTablaDeTotales(idsTablasTotales.piezasDia, [totales.piezasAuditadas, totales.piezasRechazadas], true);
                actualizarTablaDeTotales(idsTablasTotales.bultosTotales, [totales.bultosAuditados, totales.bultosRechazados], true);
                actualizarTablaDeTotales(idsTablasTotales.piezasEnBultos, [totales.piezasEnBultos], false); // Solo un valor, sin porcentaje
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
                        tdsHtml += `<td><input type="text" class="form-control form-control-sm texto-blanco" readonly></td>`;
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
                    const total = parseFloat(valores[0]) || 0;       // ej. piezas auditadas, bultos auditados
                    const parcial = parseFloat(valores[1]) || 0;     // ej. piezas rechazadas, bultos rechazados
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

                intervaloVerificarTiempos = setInterval(function () {
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

                        const horaRegistro = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate(), hora, minuto, segundo);

                        const diferenciaMinutos = Math.floor((ahora - horaRegistro) / 60000);

                        if (diferenciaMinutos >= 15) {
                            fila.style.backgroundColor = "#8B0000"; // Rojo Oscuro
                            fila.style.color = "#fff";
                        } else if (diferenciaMinutos >= 10) {
                            fila.style.backgroundColor = "#996515"; // Naranja/Amarillo Oscuro
                            fila.style.color = "#fff";
                        }
                    } catch (e) {
                        console.error("Error parseando hora para verificarTiemposParo:", horaRegistroTexto, e);
                    }
                });
            }

            // --- MANEJO DE EVENTOS CON DELEGACIÓN ---

            // Eliminar registro (para ambas tablas, diferenciado por clase)
            $(document).on('click', '.btn-eliminar-normal, .btn-eliminar-te', function () {
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
                        Swal.fire({ title: 'Eliminando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        $.ajax({
                            url: "{{ route('eliminar.registro.aql') }}", // Ruta única para eliminar
                            type: "POST",
                            data: { id: registroId, _token: "{{ csrf_token() }}" },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Eliminado!', response.message || 'El registro ha sido eliminado.', 'success');
                                    cargarRegistrosUnificado(); // Recargar ambas tablas
                                } else {
                                    Swal.fire('Error', response.message || 'No se pudo eliminar el registro.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error de Comunicación', 'Hubo un error al intentar eliminar el registro.', 'error');
                                console.error("Error al eliminar:", xhr.responseText);
                            }
                        });
                    }
                });
            });

            // Finalizar paro (para ambas tablas, diferenciado por clase)
            $(document).on('click', '.btn-finalizar-paro-normal, .btn-finalizar-paro-te', function () {
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
                                Swal.fire({ title: 'Procesando...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                                $.ajax({
                                    url: "{{ route('AQLV3.finalizar.paro') }}", // Ruta única para finalizar paro
                                    type: "POST",
                                    data: {
                                        id: registroId,
                                        piezasReparadas: piezasReparadas,
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Paro Finalizado!',
                                                html: `Minutos de paro: ${response.minutos_paro || '-'}<br>Piezas reparadas: ${response.reparacion_rechazo || '-'}`
                                            });
                                            cargarRegistrosUnificado(); // Recargar ambas tablas
                                        } else {
                                            Swal.fire('Error', response.message || 'No se pudo finalizar el paro.', 'error');
                                        }
                                    },
                                    error: function (xhr) {
                                        Swal.fire('Error de Comunicación', 'Hubo un error al intentar finalizar el paro.', 'error');
                                        console.error("Error al finalizar paro:", xhr.responseText);
                                    }
                                });
                            }
                        });
                    }
                });
            });


            // --- Eventos para los botones "Finalizar" de cada Card de Auditoría ---
            $('#btn-finalizar').on('click', function() {
                finalizarAuditoriaModulo('normal', '#observacion');
            });

            $('#btn-finalizar-TE').on('click', function() {
                finalizarAuditoriaModulo('tiempo_extra', '#observacion-TE');
            });

            function finalizarAuditoriaModulo(tipoTurno, selectorTextareaObservaciones) {
                const moduloInput = document.getElementById('modulo');
                if (!moduloInput || !moduloInput.value) {
                    Swal.fire('Error', 'No se ha definido el módulo.', 'error');
                    return;
                }
                const modulo = moduloInput.value;
                const observaciones = $(selectorTextareaObservaciones).val().trim();

                // Opcional: Validar que haya observaciones si son requeridas
                // if (!observaciones) {
                //     Swal.fire('Atención', 'Por favor, ingresa las observaciones antes de finalizar.', 'warning');
                //     return;
                // }

                Swal.fire({
                    title: `¿Finalizar auditoría de ${tipoTurno === 'normal' ? 'Turno Normal' : 'Tiempo Extra'}?`,
                    text: "Esta acción marcará la auditoría como completada para este módulo y turno. No podrás agregar más registros a este turno después de finalizar.",
                    icon: 'warning', // Usar warning para una acción importante
                    showCancelButton: true,
                    confirmButtonText: 'Sí, finalizar',
                    confirmButtonColor: '#d33', // Color rojo para acción de "finalizar"
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({ title: 'Finalizando Auditoría...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        $.ajax({
                            url: "{{ route('AQLV3.finalizar.auditoria.modulo') }}", // Ruta para finalizar auditoría de módulo
                            type: "POST",
                            data: {
                                modulo: modulo,
                                observaciones: observaciones,
                                tipo_turno: tipoTurno,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('¡Auditoría Finalizada!', response.message, 'success');
                                    // Deshabilitar el botón correspondiente y el textarea
                                    $(selectorTextareaObservaciones).prop('disabled', true);
                                    if (tipoTurno === 'normal') {
                                        $('#btn-finalizar').prop('disabled', true);
                                    } else {
                                        $('#btn-finalizar-TE').prop('disabled', true);
                                    }
                                    // Aquí podrías querer deshabilitar también el formulario de nuevos registros para ese turno.
                                } else {
                                    Swal.fire('Error', response.message || 'No se pudo finalizar la auditoría.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error de Comunicación', 'Hubo un error al conectar con el servidor.', 'error');
                                console.error("Error al finalizar auditoría de módulo:", xhr.responseText);
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
        document.addEventListener('DOMContentLoaded', function () {
            const modulo = document.getElementById('observacion-container').getAttribute('data-modulo');
            const btnFinalizar = document.getElementById('btn-finalizar');
            const textarea = document.getElementById('observacion');

            const moduloTE = document.getElementById('observacion-container-TE').getAttribute('data-modulo');
            const btnFinalizarTE = document.getElementById('btn-finalizar-TE');
            const textareaTE = document.getElementById('observacion-TE');

            // Función para cargar el estado del tiempo normal
            function cargarEstado() {
                $.ajax({
                    url: "{{ route('auditoriaAQL.verificarFinalizacion') }}",
                    type: "GET",
                    data: { modulo: modulo },
                    success: function (response) {
                        if (response.finalizado) {
                            textarea.value = response.observacion;
                            textarea.setAttribute('readonly', true);
                            btnFinalizar.disabled = true;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al verificar el estado:", error);
                    }
                });
            }

            // Función para cargar el estado del tiempo extra
            function cargarEstadoTE() {
                $.ajax({
                    url: "{{ route('auditoriaAQL.verificarFinalizacionTE') }}",
                    type: "GET",
                    data: { modulo: moduloTE },
                    success: function (response) {
                        if (response.finalizado) {
                            textareaTE.value = response.observacion;
                            textareaTE.setAttribute('readonly', true);
                            btnFinalizarTE.disabled = true;
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al verificar el estado del tiempo extra:", error);
                    }
                });
            }

            // Llamar a las funciones al cargar la página
            cargarEstado();
            cargarEstadoTE();

            // Evento para finalizar tiempo normal
            btnFinalizar.addEventListener('click', function (e) {
                e.preventDefault();

                const observacion = textarea.value.trim();
                if (observacion === '') {
                    alert("Por favor, ingrese una observación.");
                    return;
                }

                $.ajax({
                    url: "{{ route('auditoriaAQL.formFinalizarProceso_v2') }}",
                    type: "POST",
                    data: {
                        modulo: modulo,
                        observacion: observacion,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message || "✅ Finalización aplicada correctamente.");
                            cargarEstado();
                        } else {
                            alert(response.message || "❌ No se pudo aplicar la finalización.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                        alert("❌ Hubo un error al procesar la solicitud.");
                    }
                });
            });

            // Evento para finalizar tiempo extra
            btnFinalizarTE.addEventListener('click', function (e) {
                e.preventDefault();

                const observacion = textareaTE.value.trim();
                if (observacion === '') {
                    alert("Por favor, ingrese una observación.");
                    return;
                }

                $.ajax({
                    url: "{{ route('auditoriaAQL.formFinalizarProceso_v2TE') }}",
                    type: "POST",
                    data: {
                        modulo: moduloTE,
                        observacion: observacion,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(response.message || "✅ Finalización aplicada correctamente.");
                            cargarEstadoTE();
                        } else {
                            alert(response.message || "No se pudo aplicar la finalización.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error en la solicitud AJAX:", error);
                        alert("❌ Hubo un error al procesar la solicitud.");
                    }
                });
            }); 
        }); 
    </script> 
    <script>
        $(document).ready(function () {
            let datosCargados = false;

            $('#collapseBultos').on('show.bs.collapse', function () {
                if (!datosCargados) {
                    const modulo = $('#bultos-container').data('modulo');

                    $.ajax({
                        url: '/auditoriaAQLV3/registro/bultos-no-finalizados', // Asegúrate que esta ruta sea correcta
                        method: 'GET',
                        data: { modulo: modulo },
                        beforeSend: function () {
                            $('#bultos-container').html('<div class="text-center mt-3 mb-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando datos...</p></div>');
                        },
                        success: function (response) {
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
                                $('#bultos-container').html('<p class="text-warning text-center mt-3 mb-3">No se encontraron bultos no finalizados.</p>');
                            }
                            datosCargados = true;
                        },
                        error: function () {
                            $('#bultos-container').html('<p class="text-danger text-center mt-3 mb-3">Error al cargar los datos.</p>');
                            // datosCargados podría quedar en false para permitir un reintento, o true para no reintentar.
                            // Si se quiere reintentar, se deja en false.
                            datosCargados = false;
                        }
                    });
                }
            });

            // Delegamos el evento click para los botones "Finalizar Paro Pendiente"
            $(document).on('click', '.finalizar-paro', function () {
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
                                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                                    data: {
                                        id: paroId, // Nombre del parámetro que espera tu backend
                                        piezasReparadas: piezasReparadas
                                    },
                                    success: function (response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Paro Finalizado!',
                                                html: `El paro se finalizó correctamente.<br>
                                                    <b>Minutos de Paro:</b> ${response.minutos_paro || 'N/A'}<br>
                                                    <b>Piezas Reparadas Registradas:</b> ${response.reparacion_rechazo || 'N/A'}`
                                            }).then(() => {
                                                $('#collapseBultos').collapse('hide');
                                                datosCargados = false; // Para que se recarguen los datos la próxima vez
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error al Finalizar',
                                                text: response.message || 'No se pudo finalizar el paro.'
                                            });
                                        }
                                    },
                                    error: function (xhr, status, error) {
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
        
        
    
@endsection
