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
                    <form action="{{ route('buscarUltimoRegistro') }}" method="POST">
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
                url: "{{ route('obtener.opciones.op') }}", // Ruta para obtener todas las OPs
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
                        // Aquí podrías añadir lógica para preseleccionar un bulto si viene por URL,
                        // similar a como se haría con la OP.
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
        
            // Configuración de Select2
            nombreSelect.select2({
                placeholder: 'Selecciona una opción',
                allowClear: true,
                ajax: {
                    url: "{{ route('obtener.nombres.proceso') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            modulo: $('#modulo').val(),
                            search: params.term // Envía el término de búsqueda
                        };
                    },
                    processResults: function (data) {
                        const options = data.map(item => ({
                            id: item.name, // El valor será solo el nombre
                            text: `${item.personnelnumber} - ${item.name}` // Se muestra número y nombre para facilitar la búsqueda
                        }));
                        return { results: options };
                    },
                    cache: true,
                },
                language: {
                    noResults: function () {
                        return "No se encontraron resultados";
                    },
                },
            });
        
            // Evento al seleccionar una opción
            nombreSelect.on('select2:select', function (e) {
                const selected = e.params.data;
                if (selectedIds.has(selected.id)) {
                    alert('Esta opción ya ha sido seleccionada.');
                    nombreSelect.val(null).trigger('change');
                    return;
                }
                // Aquí se almacena y muestra solo el nombre (selected.id)
                addOptionToContainer(selected.id, selected.id);
                nombreSelect.val(null).trigger('change');
            });
        
            // Función para agregar la opción seleccionada al contenedor
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

                // Obtenemos el valor actual de cantidad_rechazada para saber si validamos ciertos campos
                const valorCantidadRechazada = $('#cantidad_rechazada').val();

                // Validar inputs y selects visibles (excepto los excluidos)
                $(`${tablasObjetivo.join(', ')} input:visible, ${tablasObjetivo.join(', ')} select:visible`).not('#tpSelectAQL, #nombre_select').each(function () {
                    const name = $(this).attr('name'); 
                    const value = $(this).val();

                    if ($(this).prop('required') && !value) {
                        esValido = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }

                    if (name) {
                        formData[name] = value;
                    }
                });

                // Si hay algún campo requerido vacío, mostrar alerta genérica
                if (!esValido) {
                    alert('Por favor, completa todos los campos requeridos.');
                    return; 
                }

                // Validaciones adicionales si cantidad_rechazada > 0
                if (valorCantidadRechazada > 0) {
                    if ($('#selectedOptionsContainerAQL').children().length === 0) {
                        alert('Por favor, selecciona al menos una opción en "Tipo de Defecto".');
                        return;
                    }

                    if ($('#selectedOptionsContainerNombre').children().length === 0) {
                        alert('Por favor, selecciona al menos una opción en "Nombre".');
                        return;
                    }

                    const defectCount = $('#selectedOptionsContainerAQL .selected-option').length;
                    const cantRechazadaNum = parseInt(valorCantidadRechazada, 10);
                    if (defectCount !== cantRechazadaNum) {
                        alert(`La cantidad de defectos seleccionados (${defectCount}) debe coincidir con las piezas rechazadas (${cantRechazadaNum}).`);
                        return;
                    }
                }

                // Serializar las opciones seleccionadas en caso de que cantidad_rechazada > 0
                if (valorCantidadRechazada > 0) {
                    // Procesar `selectedAQL` eliminando el texto de los botones (por ejemplo, "Eliminar")
                    const selectedAQL = [];
                    $('#selectedOptionsContainerAQL .selected-option').each(function () {
                        let text = $(this).text().trim();
                        // Remover palabras específicas como "Eliminar"
                        text = text.replace(/^\+/, '').replace(/\bEliminar\b/g, '').trim();
                        selectedAQL.push(text);
                    });
                    formData['selectedAQL'] = selectedAQL;

                    // Procesar `selectedNombre` eliminando el texto de los botones (por ejemplo, "Eliminar")
                    const selectedNombre = [];
                    $('#selectedOptionsContainerNombre .selected-option').each(function () {
                        let text = $(this).text().trim();
                        // Remover palabras específicas como "Eliminar"
                        text = text.replace(/\bEliminar\b/g, '').trim();
                        selectedNombre.push(text);
                    });
                    formData['selectedNombre'] = selectedNombre;
                } else {
                    // Si es 0, no agregamos estos datos
                    formData['selectedAQL'] = [];
                    formData['selectedNombre'] = [];
                }

                // ** Ajuste adicional **
                // Reasignamos siempre los valores de la primera tabla para asegurarnos 
                // de que se incluyan sin importar el valor de cantidad_rechazada.
                $('#tabla-datos-principales input, #tabla-datos-principales select').each(function () {
                    const name = $(this).attr('name'); 
                    const value = $(this).val();
                    if (name && typeof formData[name] === 'undefined') {
                        formData[name] = value;
                    }
                });

                // Enviar datos mediante AJAX
                $.ajax({
                    url: "{{ route('guardar.registro.aql') }}", // Reemplaza con la ruta correcta
                    type: 'POST',
                    data: {
                        ...formData,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (response) {
                        alert('✅ Datos guardados correctamente.');

                        

                        // Si cantidad_rechazada es mayor a 0, recargar la página
                        if ($('#cantidad_rechazada').val() > 0) {
                            location.reload(); // Recargar la página
                        } else {
                            // Limpiar los campos de la segunda tabla
                            $('#bulto_seleccion').val('').trigger('change');
                            $('#pieza-seleccion').val('');
                            $('#estilo-seleccion').val('');
                            $('#color-seleccion').val('');
                            $('#talla-seleccion').val('');
                            $('#cantidad_auditada').val('');
                            $('#cantidad_rechazada').val('');
                            $('#selectedOptionsContainerAQL').empty();
                            $('#accion_correctiva').val('');
                            $('#selectedOptionsContainerNombre').empty();

                            // Disparar evento personalizado
                            const event = new Event('registroGuardado');
                            window.dispatchEvent(event);
                        }
                    },
                    error: function (xhr) {
                        alert('❌ Hubo un error al guardar los datos. Por favor, intenta nuevamente.');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Asignar el listener para registroGuardado UNA sola vez
            window.addEventListener('registroGuardado', function () {
                cargarRegistros();
            });

            function cargarRegistros() {
                const fechaActual = new Date().toISOString().slice(0, 10);
                const modulo = document.getElementById('modulo').value;

                if (!modulo) {
                    console.error("El módulo no está definido.");
                    return;
                }

                $.ajax({
                    url: "{{ route('mostrar.registros.aql.dia') }}",
                    type: "GET",
                    data: {
                        fechaActual: fechaActual,
                        modulo: modulo
                    },
                    success: function (response) {
                        // Tabla principal
                        const tbody = document.querySelector("#tabla_registros_dia tbody");
                        tbody.innerHTML = ""; // Limpiar el contenido actual

                        let totalPiezasAuditadas = 0;
                        let totalPiezasRechazadas = 0;

                        // Para la tabla "Total por Bultos"
                        let totalBultosAuditados = 0;
                        let totalBultosRechazados = 0;

                        // NUEVA VARIABLE para la tabla de "Total de piezas en bultos Auditados"
                        let totalPiezasEnBultos = 0;

                        response.forEach(function (registro) {
                            // Construir la fila de la tabla principal
                            const fila = `
                                <tr class="${registro.tiempo_extra ? 'tiempo-extra' : ''}">
                                    <td>
                                        ${
                                            registro.inicio_paro === null 
                                            ? '-' 
                                            : registro.fin_paro 
                                                ? registro.minutos_paro 
                                                : `<button class="btn btn-primary btn-finalizar-paro" data-id="${registro.id}">Fin Paro AQL</button>`
                                        }
                                    </td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.bulto}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.pieza}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.talla}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.color}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.estilo}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_auditada}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_rechazada}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" readonly value="${
                                        registro.tp_auditoria_a_q_l 
                                            ? registro.tp_auditoria_a_q_l.map(tp => tp.tp).join(', ') 
                                            : ''}">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-eliminar" data-id="${registro.id}">
                                            Eliminar
                                        </button>
                                    </td>
                                    <td>${registro.created_at ? new Date(registro.created_at).toLocaleTimeString() : ''}</td>
                                    <td>
                                        ${
                                        registro.reparacion_rechazo !== null && registro.reparacion_rechazo !== '' 
                                        ? `<input type="text" class="form-control texto-blanco" value="${registro.reparacion_rechazo}" readonly>` 
                                        : `<input type="text" class="form-control texto-blanco" value="-" readonly>`
                                        }
                                    </td>
                                </tr>
                            `;
                            tbody.insertAdjacentHTML('beforeend', fila);

                            // Acumular valores para las tablas secundarias de piezas
                            totalPiezasAuditadas += registro.cantidad_auditada || 0;
                            totalPiezasRechazadas += registro.cantidad_rechazada || 0;

                            // Acumular valores para "Total por Bultos"
                            totalBultosAuditados += 1;
                            if ((registro.cantidad_rechazada || 0) > 0) {
                                totalBultosRechazados += 1;
                            }

                            // ACUMULAR valor para "Total de piezas en bultos Auditados"
                            // Asumiendo que `registro.pieza` es numérico:
                            totalPiezasEnBultos += parseInt(registro.pieza) || 0;
                        });

                        // Actualizar la tabla de "Piezas auditadas por día"
                        actualizarTablasSecundarias(totalPiezasAuditadas, totalPiezasRechazadas);

                        // Actualizar la tabla de "Total por Bultos"
                        actualizarBultosTotales(totalBultosAuditados, totalBultosRechazados);

                        // NUEVA llamada: Actualizar la tabla de "Total de piezas en bultos Auditados"
                        actualizarTablaPiezasEnBultos(totalPiezasEnBultos);

                        // Vuelve a asignar eventos a los nuevos botones
                        asignarEventosEliminar();
                        asignarEventosFinalizarParo();

                        // Al finalizar la creación de filas, inicia la monitorización
                        setInterval(verificarTiemposParo, 60000); // Comprobar cada minuto
                        verificarTiemposParo(); // Comprobar inmediatamente


                    },
                    error: function (error) {
                        console.error("Error al cargar los registros:", error);
                    }
                });
            }

            function verificarTiemposParo() {
                const ahora = new Date();

                // Seleccionamos todas las filas
                document.querySelectorAll('#tabla_registros_dia tbody tr').forEach(fila => {
                    const botonParo = fila.querySelector('.btn-finalizar-paro');
                    const celdaHora = fila.querySelector('td:nth-last-child(2)'); // Penúltima columna

                    // Si no hay botón o no hay hora, limpiar colores
                    if (!botonParo || !celdaHora) {
                        fila.style.backgroundColor = "";
                        return;
                    }

                    // Obtener la hora del registro
                    const horaRegistroTexto = celdaHora.textContent.trim();
                    if (!horaRegistroTexto) return;

                    const [hora, minuto, segundo] = horaRegistroTexto.split(':').map(Number);
                    const horaRegistro = new Date();
                    horaRegistro.setHours(hora, minuto, segundo || 0);

                    // Calcular la diferencia en minutos
                    const diferenciaMinutos = Math.floor((ahora - horaRegistro) / 60000);

                    // Limpiar colores previos
                    fila.style.backgroundColor = "";

                    // Aplicar color según el tiempo
                    if (diferenciaMinutos >= 10 && diferenciaMinutos < 15) {
                        fila.style.backgroundColor = "#996515"; // Amarillo Oscuro
                        fila.style.color = "#fff";
                    } else if (diferenciaMinutos >= 15) {
                        fila.style.backgroundColor = "#8B0000"; // Rojo Oscuro
                        fila.style.color = "#fff";
                    }
                });
            }


            function actualizarTablasSecundarias(totalAuditadas, totalRechazadas) {
                const porcentajeAQL = totalAuditadas > 0 
                    ? ((totalRechazadas / totalAuditadas) * 100).toFixed(2) 
                    : 0;

                // Encuentra las filas donde actualizar los valores
                const tabla = document.getElementById("tabla-piezas-dia");
                const filas = tabla.querySelectorAll("tbody tr");

                // Asegurarse de que exista al menos una fila para editar (o agregarla si no existe)
                if (filas.length === 0) {
                    const nuevaFila = `
                        <tr>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                        </tr>
                    `;
                    tabla.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFila);
                }

                // Actualiza los inputs con los valores calculados
                const inputs = tabla.querySelectorAll("tbody tr:first-child input");
                inputs[0].value = totalAuditadas || 0;
                inputs[1].value = totalRechazadas || 0;
                inputs[2].value = `${porcentajeAQL}%`;
            }

            function actualizarBultosTotales(totalBultosAuditados, totalBultosRechazados) {
                // Calcular el porcentaje
                const porcentajeTotal = totalBultosAuditados > 0 
                    ? ((totalBultosRechazados / totalBultosAuditados) * 100).toFixed(2) 
                    : 0;

                const tablaBultos = document.getElementById("tabla-bultos-totales");
                const filasBultos = tablaBultos.querySelectorAll("tbody tr");

                // Asegurar que la tabla tenga al menos una fila
                if (filasBultos.length === 0) {
                    const nuevaFilaBultos = `
                        <tr>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                        </tr>
                    `;
                    tablaBultos.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFilaBultos);
                }

                const inputsBultos = tablaBultos.querySelectorAll("tbody tr:first-child input");
                inputsBultos[0].value = totalBultosAuditados || 0;
                inputsBultos[1].value = totalBultosRechazados || 0;
                inputsBultos[2].value = `${porcentajeTotal}%`;
            }

            // NUEVA FUNCIÓN para actualizar "Total de piezas en bultos Auditados"
            function actualizarTablaPiezasEnBultos(totalPiezasEnBultos) {
                // Seleccionamos la tabla (usa el ID o clase que le asignaste)
                const tablaPiezasBultos = document.getElementById("tabla-piezas-bultos");
                const filas = tablaPiezasBultos.querySelectorAll("tbody tr");

                // Si no hay filas, creamos una fila con un solo campo
                if (filas.length === 0) {
                    const nuevaFila = `
                        <tr>
                            <td>
                                <input type="text" class="form-control texto-blanco" readonly>
                            </td>
                        </tr>
                    `;
                    tablaPiezasBultos.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFila);
                }

                // Asignamos el valor (o 0 si no hay nada)
                const input = tablaPiezasBultos.querySelector("tbody tr:first-child input");
                input.value = totalPiezasEnBultos || 0;
            }

            function asignarEventosEliminar() {
                const botonesEliminar = document.querySelectorAll('.btn-eliminar');
                botonesEliminar.forEach(boton => {
                    boton.removeEventListener('click', manejarEliminar);
                    boton.addEventListener('click', manejarEliminar);
                });
            }

            function manejarEliminar() {
                const id = this.getAttribute('data-id');

                if (!confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                    return; // Si cancela, no hace nada
                }

                eliminarRegistro(id);
            }

            function eliminarRegistro(id) {
                if (!confirm('¿Estás seguro de eliminar este registro?')) return;
                
                $.ajax({
                    url: "{{ route('eliminar.registro.aql') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("✅ Registro eliminado exitosamente.");
                            cargarRegistros();
                        } else {
                            // Manejar específicamente el error de auditoría finalizada
                            if (response.message.includes('finalizada')) {
                                alert('⚠ Advertencia: ' + response.message);
                            } else {
                                alert("❌ Error: " + response.message);
                            }
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("❌ Error al eliminar el registro:", xhr, status, error);
                        alert("❌ Hubo un error al intentar eliminar el registro.");
                    }
                });
            }
            function asignarEventosFinalizarParo() {
                const botonesFinalizarParo = document.querySelectorAll('.btn-finalizar-paro');
                botonesFinalizarParo.forEach(boton => {
                    // Primero removemos cualquier listener previo para evitar duplicados
                    boton.removeEventListener('click', manejarFinalizarParo);
                    boton.addEventListener('click', manejarFinalizarParo);
                });
            }

            function manejarFinalizarParo() {
                const id = this.getAttribute('data-id');

                // Pedimos la cantidad de piezas reparadas
                const piezasReparadas = prompt("Ingrese la cantidad de piezas reparadas:");
                
                // Si el usuario cancela o no ingresa nada, no hacemos nada
                if (piezasReparadas === null || piezasReparadas === "") {
                    return;
                }

                // Hacemos la llamada AJAX para finalizar el paro
                $.ajax({
                    url: "{{ route('finalizar.paro.aql') }}",
                    type: "POST",
                    data: {
                        id: id,
                        piezasReparadas: piezasReparadas,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("✅ Paro finalizado correctamente.\nMinutos de paro: " + response.minutos_paro + "\nPiezas reparadas: " + response.reparacion_rechazo);
                            // Recargar la tabla y así desaparece el botón
                            cargarRegistros();
                        } else {
                            console.error("❌ Error en la respuesta del servidor:", response);
                            alert("❌ No se pudo finalizar el paro. Intente nuevamente.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al finalizar el paro:", xhr, status, error);
                        alert("❌ Hubo un error al intentar finalizar el paro.");
                    }
                });
            }

            // Inicialización
            cargarRegistros();
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.addEventListener('registroGuardado', function () {
                cargarRegistros();
            });
            function cargarRegistros() {
                const fechaActual = new Date().toISOString().slice(0, 10);
                const modulo = document.getElementById('modulo').value;
        
                if (!modulo) {
                    console.error("El módulo no está definido.");
                    return;
                }

                $.ajax({
                    url: "{{ route('mostrar.registros.aql.dia.TE') }}",
                    type: "GET",
                    data: {
                        fechaActual: fechaActual,
                        modulo: modulo
                    },
                    success: function (response) {
                        // Limpia la tabla principal de tiempo extra
                        const tbody = document.querySelector("#tabla_registros_tiempo_extra tbody");
                        tbody.innerHTML = "";

                        // Definimos contadores
                        let totalPiezasAuditadasTE = 0;
                        let totalPiezasRechazadasTE = 0;
                        let totalBultosAuditadosTE = 0;
                        let totalBultosRechazadosTE = 0;
                        let totalPiezasEnBultosTE = 0;

                        // Recorremos los registros y construimos las filas
                        response.forEach(function (registro) {
                            const fila = `
                                <tr class="${registro.tiempo_extra ? 'tiempo-extra' : ''}">
                                    <td>
                                        ${
                                            registro.inicio_paro === null 
                                                ? '-' 
                                                : registro.fin_paro 
                                                    ? registro.minutos_paro 
                                                    : `<button class="btn btn-primary btn-finalizar-paro" data-id="${registro.id}">Fin Paro AQL</button>`
                                        }
                                    </td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.bulto}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.pieza}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.talla}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.color}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.estilo}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_auditada}" readonly></td>
                                    <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_rechazada}" readonly></td>
                                    <td>
                                        <input type="text" class="form-control texto-blanco" 
                                            readonly 
                                            value="${
                                                registro.tp_auditoria_a_q_l 
                                                    ? registro.tp_auditoria_a_q_l.map(tp => tp.tp).join(', ') 
                                                    : ''
                                            }">
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-eliminar-te" data-id="${registro.id}">
                                            Eliminar
                                        </button>
                                    </td>
                                    <td>${registro.created_at ? new Date(registro.created_at).toLocaleTimeString() : ''}</td>
                                    <td>
                                        ${
                                            registro.reparacion_rechazo !== null && registro.reparacion_rechazo !== '' 
                                            ? `<input type="text" class="form-control texto-blanco" value="${registro.reparacion_rechazo}" readonly>` 
                                            : `<input type="text" class="form-control texto-blanco" value="-" readonly>`
                                        }
                                    </td>
                                </tr>
                            `;
                            tbody.insertAdjacentHTML('beforeend', fila);

                            // Acumulamos valores para las tablas secundarias
                            totalPiezasAuditadasTE += registro.cantidad_auditada || 0;
                            totalPiezasRechazadasTE += registro.cantidad_rechazada || 0;

                            totalBultosAuditadosTE += 1;
                            if ((registro.cantidad_rechazada || 0) > 0) {
                                totalBultosRechazadosTE += 1;
                            }

                            // Asumiendo que 'registro.pieza' es numérico
                            totalPiezasEnBultosTE += parseInt(registro.pieza) || 0;
                        });

                        // Actualizamos tablas secundarias
                        actualizarTablasSecundariasTE(totalPiezasAuditadasTE, totalPiezasRechazadasTE);
                        actualizarBultosTotalesTE(totalBultosAuditadosTE, totalBultosRechazadosTE);
                        actualizarTablaPiezasEnBultosTE(totalPiezasEnBultosTE);

                        // Asignamos los eventos a los nuevos botones
                        asignarEventosEliminarTE();
                        asignarEventosFinalizarParoTE();
                    },
                    error: function (error) {
                        console.error("Error al cargar los registros TE:", error);
                    }
                });
            }

            function actualizarTablasSecundariasTE(totalAuditadasTE, totalRechazadasTE) {
                const porcentajeAQLTE = totalAuditadasTE > 0 
                    ? ((totalRechazadasTE / totalAuditadasTE) * 100).toFixed(2)
                    : 0;
                
                const tablaTE = document.getElementById("tabla-piezas-dia-TE");
                const filasTE = tablaTE.querySelectorAll("tbody tr");

                // Si no hay fila, creamos una nueva
                if (filasTE.length === 0) {
                    const nuevaFilaTE = `
                        <tr>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                        </tr>
                    `;
                    tablaTE.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFilaTE);
                }

                const inputsTE = tablaTE.querySelectorAll("tbody tr:first-child input");
                inputsTE[0].value = totalAuditadasTE || 0;
                inputsTE[1].value = totalRechazadasTE || 0;
                inputsTE[2].value = `${porcentajeAQLTE}%`;
            }

            function actualizarBultosTotalesTE(totalBultosAuditadosTE, totalBultosRechazadosTE) {
                const porcentajeTotalTE = totalBultosAuditadosTE > 0
                    ? ((totalBultosRechazadosTE / totalBultosAuditadosTE) * 100).toFixed(2)
                    : 0;

                const tablaBultosTE = document.getElementById("tabla-bultos-totales-TE");
                const filasBultosTE = tablaBultosTE.querySelectorAll("tbody tr");

                if (filasBultosTE.length === 0) {
                    const nuevaFila = `
                        <tr>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                        </tr>
                    `;
                    tablaBultosTE.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFila);
                }

                const inputs = tablaBultosTE.querySelectorAll("tbody tr:first-child input");
                inputs[0].value = totalBultosAuditadosTE || 0;
                inputs[1].value = totalBultosRechazadosTE || 0;
                inputs[2].value = `${porcentajeTotalTE}%`;
            }

            function actualizarTablaPiezasEnBultosTE(totalPiezasEnBultosTE) {
                const tablaPiezasBultosTE = document.getElementById("tabla-piezas-bultos-TE");
                const filasTE = tablaPiezasBultosTE.querySelectorAll("tbody tr");

                if (filasTE.length === 0) {
                    const nuevaFila = `
                        <tr>
                            <td><input type="text" class="form-control texto-blanco" readonly></td>
                        </tr>
                    `;
                    tablaPiezasBultosTE.querySelector("tbody").insertAdjacentHTML("beforeend", nuevaFila);
                }

                const inputTE = tablaPiezasBultosTE.querySelector("tbody tr:first-child input");
                inputTE.value = totalPiezasEnBultosTE || 0;
            }

            // -------------------------------
            //   EVENTOS PARA ELIMINAR
            // -------------------------------
            function asignarEventosEliminarTE() {
                const tablaTE = document.getElementById('tabla_registros_tiempo_extra');
                const botonesEliminarTE = tablaTE.querySelectorAll('.btn-eliminar-te');

                // Importante: remover antes de asignar para evitar duplicados
                botonesEliminarTE.forEach((boton) => {
                    boton.removeEventListener('click', manejarEliminarTE);
                    boton.addEventListener('click', manejarEliminarTE);
                });
            }

            function manejarEliminarTE() {
                const id = this.getAttribute('data-id');
                
                if (!confirm("¿Estás seguro de que deseas eliminar este registro?")) {
                    return; // Si cancela, no hace nada
                }

                eliminarRegistroTE(id);
            }

            function eliminarRegistroTE(id) {
                $.ajax({
                    url: "{{ route('eliminar.registro.aql') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert("✅ Registro eliminado exitosamente.");
                            cargarRegistros(); // Recarga para actualizar la tabla
                        } else {
                            console.error("❌ Error en la respuesta del servidor:", response);
                            alert("No se pudo eliminar el registro. Intente nuevamente.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("❌ Error al eliminar el registro TE:", xhr, status, error);
                        alert("❌ Hubo un error al eliminar el registro.");
                    }
                });
            }

            // -------------------------------
            //   EVENTOS PARA FINALIZAR PARO
            // -------------------------------
            function asignarEventosFinalizarParoTE() {
                const tablaTE = document.getElementById('tabla_registros_tiempo_extra');
                const botonesFinalizarParo = tablaTE.querySelectorAll('.btn-finalizar-paro');

                // Removemos listener previo antes de asignar
                botonesFinalizarParo.forEach(boton => {
                    boton.removeEventListener('click', manejarFinalizarParoTE);
                    boton.addEventListener('click', manejarFinalizarParoTE);
                });
            }

            function manejarFinalizarParoTE() {
                const id = this.getAttribute('data-id');

                // Pedimos la cantidad de piezas reparadas
                const piezasReparadas = prompt("Ingrese la cantidad de piezas reparadas:");

                // Si el usuario cancela o no ingresa nada, no hacemos nada
                if (piezasReparadas === null || piezasReparadas === "") {
                    return;
                }

                // Llamada AJAX para finalizar el paro
                $.ajax({
                    url: "{{ route('finalizar.paro.aql') }}",
                    type: "POST",
                    data: {
                        id: id,
                        piezasReparadas: piezasReparadas,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        if (response.success) {
                            alert(
                                "✅ Paro finalizado correctamente.\n" +
                                "Minutos de paro: " + response.minutos_paro + "\n" +
                                "Piezas reparadas: " + response.reparacion_rechazo
                            );
                            // Recargar la tabla para que desaparezca el botón
                            cargarRegistros();
                        } else {
                            console.error("Error en la respuesta del servidor:", response);
                            alert("❌ No se pudo finalizar el paro. Intente nuevamente.");
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error al finalizar el paro TE:", xhr, status, error);
                        alert("❌ Hubo un error al intentar finalizar el paro.");
                    }
                });
            }

            // Inicialización
            cargarRegistros();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const openModalBtn = document.getElementById('openModalAQL');
            const closeModalBtn = document.getElementById('closeModalAQL');
            const modal = document.getElementById('customModalAQL');
            const tbody = document.getElementById('tablaProcesosAQL');

            // Abrir el modal y cargar los datos con AJAX
            openModalBtn.addEventListener('click', function () {
                modal.style.display = 'block';

                // Hacer la petición AJAX
                fetch('{{ route('auditoriaAQL.obtenerAQLenProceso') }}', {
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
                                        <form method="POST" action="{{ route('auditoriaAQL.formAltaProcesoAQL_v2') }}">
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
            closeModalBtn.addEventListener('click', function () {
                modal.style.display = 'none';
            });

            // Cerrar el modal con la tecla "ESC"
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    modal.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('observacion-container');
            const modulo = container.getAttribute('data-modulo');

            const btnFinalizar = document.getElementById('btn-finalizar');
            const textarea = document.getElementById('observacion');

            // Cargar el estado de finalización de forma asíncrona
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

            // Llamar a la función al cargar la página
            cargarEstado();

            // Evento de finalizar
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
                            cargarEstado(); // Volver a cargar el estado para actualizar UI
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
                        url: '/api/bultos-no-finalizados',
                        method: 'GET',
                        data: { modulo: modulo }, 
                        beforeSend: function () {
                            $('#bultos-container').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando datos...</p></div>');
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
                                $('#bultos-container').html('<p class="text-warning text-center">No se encontraron bultos no finalizados.</p>');
                            }
                            datosCargados = true;
                        },
                        error: function () {
                            $('#bultos-container').html('<p class="text-danger text-center">Error al cargar los datos.</p>');
                        }
                    });
                }
            });

             // Delegamos el evento click para los botones "Finalizar Paro Pendiente"
            $(document).on('click', '.finalizar-paro', function () {
                let id = $(this).data('id');

                // Preguntamos primero por las piezas reparadas
                let piezasReparadas = prompt("Ingresa el número de piezas reparadas:", "0");

                // Si el usuario cancela o deja vacío, no continuamos
                if (piezasReparadas === null) return;

                // Confirmación de la acción
                if (confirm("¿Estás seguro de que deseas finalizar este paro?")) {

                    // Agregamos un spinner temporal en la parte superior
                    const spinnerHtml = `
                        <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                            Procesando solicitud...
                        </div>`;
                    $('body').append(spinnerHtml);

                    $.ajax({
                        url: '/api/finalizar-paro-aql-despues',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            id: id,
                            piezasReparadas: piezasReparadas
                        },
                        success: function (response) {
                            // Eliminamos el spinner al finalizar la operación
                            $('#processing-spinner').remove();

                            if (response.success) {
                                alert(`✅ Paro finalizado correctamente.\nMinutos Paro: ${response.minutos_paro}\nPiezas Reparadas: ${response.reparacion_rechazo}`); 
                                $('#collapseBultos').collapse('hide');
                                datosCargados = false;
                            } else {
                                alert(`❌ Error: ${response.message}`);
                            }
                        },
                        error: function (xhr, status, error) {
                            $('#processing-spinner').remove();
                            alert("⚠️ Ocurrió un error al intentar finalizar el paro.");
                        }
                    });
                }
            });
        });
    </script>
        
        
    
@endsection
