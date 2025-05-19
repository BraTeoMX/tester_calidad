@extends('layouts.app', ['pageSlug' => 'proceso', 'titlePage' => __('proceso')])

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
            /* Alinea título a la izquierda y botón a la derecha */
            background-color: #2e2e2e;
            padding: 15px;
            align-items: center;
        }

        .custom-modal-body {
            padding: 15px;
        }

        /* Estilo para el botón "CERRAR" en la esquina superior derecha */
        .custom-modal-footer {
            margin-right: 10px;
            /* Ajusta el margen derecho si deseas */
        }

        #closeModal {
            font-size: 14px;
            padding: 8px 16px;
        }

        .special-option {
            font-weight: bold;
            /* Negrita */
            font-style: italic;
            /* Cursiva */
            transform: skew(-10deg);
            /* Inclinación */
        }
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA EN PROCESO</h3>
                        </div>
                        <div class="col-auto">
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-link" id="openModal">
                                <h4>Fecha:
                                    {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                </h4>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Modal personalizado -->
                <div id="customModal" class="custom-modal">
                    <div class="custom-modal-content">
                        <div class="custom-modal-header">
                            <h5 class="modal-title texto-blanco">Detalles del Proceso</h5>
                            <!-- Botón "CERRAR" en la esquina superior derecha -->
                            <button id="closeModal" class="btn btn-danger">CERRAR</button>
                        </div>
                        <div class="custom-modal-body">
                            <!-- Aquí va el contenido de la tabla -->
                            <div class="table-responsive">
                                <input type="text" id="searchInput1" class="form-control mb-3" placeholder="Buscar Módulo o Estilo">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Acción</th>
                                            <th>Módulo</th>
                                            <th>Estilo</th>
                                            <th>Supervisor</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tablaProcesos1">
                                        <!-- Aquí se insertarán los datos dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    @if($resultadoFinal == true)
                    <div class="card-body">
                        <!-- Aquí ya NO necesitamos la tabla, pero sí necesitamos mantener los valores -->
                        <input type="hidden" name="modulo" id="modulo" value="{{ $data['modulo'] }}">
                        <!-- Formulario que envía la solicitud al controlador -->
                        <form action="{{ route('buscarUltimoRegistroProceso') }}" method="POST">
                            @csrf
                            <input type="hidden" name="modulo" value="{{ $data['modulo'] }}">
                            <button type="submit" class="btn btn-primary">Fin Paro Modular</button>
                        </form>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="table-200" class="table table-200">
                            <thead class="thead-primary">
                                <tr>
                                    <th>MODULO</th>
                                    <th>ESTILO</th>
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
                                        <input type="text" class="form-control texto-blanco" name="moduleid" id="modulo" value="{{ $data['modulo'] }}" readonly>
                                    </td>
                                    <td>
                                        <select class="form-control select2 texto-blanco" name="estilo" id="estilo_proceso">
                                            <option value="">Seleccione un estilo</option>
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
                                        <input type="text" class="form-control texto-blanco" name="cliente" id="cliente" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table id="auditoriaTabla" class="table flex-container table932">
                            <thead class="thead-primary">
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>OPERACION</th>
                                    <th>PIEZAS AUDITADAS</th>
                                    <th>PIEZAS RECHAZADAS</th>
                                    <th >TIPO DE PROBLEMA</th>
                                    <th >ACCION CORRECTIVA</th>
                                    <th>P x P</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="nombre_final" id="lista_nombre" class="form-control select2" required>
                                            <option value="">Selecciona una opción</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="operacion-select-container">
                                            <select name="operacion" class="form-control operacion-select" required>
                                                <option value="">Selecciona una opción</option>
                                                <option value="otra">[OTRA OPERACIÓN]</option>
                                            </select>
                                        </div>
                                        <input type="text" name="operacion" class="form-control otra-operacion-input mt-2" 
                                               placeholder="Ingresa la operación" style="display: none;" required>
                                    </td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_auditada"  required></td>
                                    <td><input type="number" class="form-control texto-blanco" name="cantidad_rechazada"  required></td>
                                    <td>
                                        <select id="tpSelect" class="form-control w-100 select2" title="Por favor, selecciona una opción">
                                            <option value="" selected disabled>Selecciona una opción</option> <!-- Opción inicial vacía -->
                                        </select>
                                        <div id="selectedOptionsContainer" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
                                    </td>
                                    <td>
                                        <select name="ac" id="ac" class="form-control" title="Por favor, selecciona una opción">
                                            <option value="">Selecciona una opción</option>
                                        </select>
                                    </td>                                    
                                    <td><input type="text" class="form-control" name="pxp" id="pxp"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn-verde-xd">GUARDAR</button> 
                    @endif
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
            </div>

            <div class="card card-body">
                <div class="accordion" id="accordionParos">
                    <div class="card">
                        <div class="card-header p-0" id="headingParos">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-light text-decoration-none w-100 text-left" type="button" data-toggle="collapse" data-target="#collapseParos" aria-expanded="false" aria-controls="collapseParos">
                                    Mostrar Paros No Finalizados
                                </button>
                            </h2>
                        </div>
                        <div id="collapseParos" class="collapse" aria-labelledby="headingParos" data-parent="#accordionParos">
                            <div class="card-body" id="paros-container" data-modulo="{{ $data['modulo'] }}">
                                <p class="text-muted">Abre el acordeón para cargar los datos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h2>Registro - Turno normal</h2>
                        <table id="registros-turno-normal" class="table table1">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Paro</th>
                                    <th>Nombre</th>
                                    <th>Operacion </th>
                                    <th>Piezas Auditadas</th>
                                    <th>Piezas Rechazadas</th>
                                    <th>Tipo de Problema </th>
                                    <th>Accion Correctiva </th>
                                    <th>PxP </th>
                                    <th>Eliminar </th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- Panel de comentario -->
                    <div class="row" id="comentarioPanel">
                        <div class="col-lg-6">
                            <p>Observaciones</p>
                            <textarea id="comentarioInput" class="form-control texto-blanco" rows="3" placeholder="Escribe tu comentario">{{ $observacion ?? '' }}</textarea>
                            <button id="guardarComentario" class="btn btn-danger mt-2">Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <h2>Registro - Turno Extra</h2>
                        <table id="registros-turno-extra" class="table table2">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Paro</th>
                                    <th>Nombre</th>
                                    <th>Operacion </th>
                                    <th>Piezas Auditadas</th>
                                    <th>Piezas Rechazadas</th>
                                    <th>Tipo de Problema </th>
                                    <th>Accion Correctiva </th>
                                    <th>PxP </th>
                                    <th>Eliminar </th>
                                    <th>Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <!-- Panel de comentario -->
                    <div class="row" id="comentarioPanelTE">
                        <div class="col-lg-6">
                            <p>Observaciones</p>
                            <textarea id="comentarioInputTE" class="form-control texto-blanco" rows="3" placeholder="Escribe tu comentario">{{ $observacionTE ?? '' }}</textarea>
                            <button id="guardarComentarioTE" class="btn btn-danger mt-2">Finalizar</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total Individual - Turno normal</h2>
                    <table class="table table-total-individual">
                        <thead class="thead-primary">
                            <tr>
                                <th>Nombre</th>
                                <th>No. Recorridos</th>
                                <th>Total Piezas Auditadas</th>
                                <th>Total Piezas Rechazadas</th>
                                <th>Porcentaje Rechazado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">No hay datos disponibles</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total Individual - Tiempo Extra</h2>
                    <table class="table table-total-individual-tiempo-extra">
                        <thead class="thead-primary">
                            <tr>
                                <th>Nombre</th>
                                <th>No. Recorridos</th>
                                <th>Total Piezas Auditadas</th>
                                <th>Total Piezas Rechazadas</th>
                                <th>Porcentaje Rechazado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5" class="text-center">No hay datos disponibles</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total General - Turno Normal</h2>
                    <table class="table table-total-general">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de Piezas Auditadas</th>
                                <th>Total de Piezas Rechazadas</th>
                                <th>Porcentaje Rechazo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" id="total_auditada_general" value="0" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" id="total_rechazada_general" value="0" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" id="total_porcentaje_general" value="0.00" readonly></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card card-body">
                <div class="table-responsive">
                    <h2>Total General - Tiempo Extra</h2>
                    <table class="table table-total-general-tiempo-extra">
                        <thead class="thead-primary">
                            <tr>
                                <th>Total de Piezas Auditadas</th>
                                <th>Total de Piezas Rechazadas</th>
                                <th>Porcentaje Rechazo Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" id="total_auditada_general-tiempo-extra" value="0" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" id="total_rechazada_general-tiempo-extra" value="0" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" id="total_porcentaje_general-tiempo-extra" value="0.00" readonly></td>
                            </tr>
                        </tbody>
                    </table>
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

        .table1 th:nth-child(2) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(3) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(6) {
            min-width: 250px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(7) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table1 th:nth-child(8) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        @media (max-width: 768px) {
            .table1 th:nth-child(2) {
                min-width: 100px;
                /* Ajusta el ancho mínimo para móviles */
            }
        }

        .table932 th:nth-child(1) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(2) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(3) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(4) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(5) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(6) {
            min-width: 200px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table932 th:nth-child(7) {
            min-width: 80px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }


        .texto-blanco {
            color: white !important;
        }

        .table-200 th:nth-child(1) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(2) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(3) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(5) {
            min-width: 50px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .table-200 th:nth-child(6) {
            min-width: 180px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }

        .tp-column {
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-selection--multiple {
            width: 100% !important;
        }
    </style>

    <!-- Si ya existe un comentario, deshabilitamos el textarea y el botón -->
    @if(isset($observacion) && !empty($observacion))
    <script>
        $(document).ready(function(){
            $('#comentarioInput').prop('disabled', true);
            $('#guardarComentario').prop('disabled', true);
        });
    </script>
    @endif
    @if(isset($observacionTE) && !empty($observacionTE))
    <script>
        $(document).ready(function(){
            $('#comentarioInputTE').prop('disabled', true);
            $('#guardarComentarioTE').prop('disabled', true);
        });
    </script>
    @endif

    <script>
        $(document).ready(function () {
            // Función para abrir el modal
            function abrirModal() {
                $('#customModal').fadeIn(); // Mostrar con efecto de desvanecimiento
            }

            // Función para cerrar el modal
            function cerrarModal() {
                $('#customModal').fadeOut(); // Ocultar con efecto de desvanecimiento
            }

            // Evento para abrir el modal al hacer clic en el botón
            $('#openModal').on('click', function () {
                abrirModal();

                // Petición AJAX para cargar los datos en la tabla del modal
                $.ajax({
                    url: "{{ route('procesoV3.registro.lista') }}",
                    type: 'GET',
                    success: function (response) {
                        var tabla = $('#tablaProcesos1');
                        tabla.empty(); // Limpiar tabla antes de insertar nuevos datos

                        if (response.procesos.length === 0) {
                            tabla.append('<tr><td colspan="4" class="text-center">No hay datos disponibles</td></tr>');
                        } else {
                            $.each(response.procesos, function (index, proceso) {
                                var row = `
                                    <tr>
                                        <td>
                                            <form method="POST" action="{{ route('formAltaProcesoV2') }}">
                                                @csrf
                                                <input type="hidden" name="modulo" value="${proceso.modulo}">
                                                <input type="hidden" name="estilo" value="${proceso.estilo}">
                                                <input type="hidden" name="team_leader" value="${proceso.team_leader}">
                                                <input type="hidden" name="gerente_produccion" value="${proceso.gerente_produccion}">
                                                <input type="hidden" name="auditor" value="${proceso.auditor}">
                                                <input type="hidden" name="turno" value="${proceso.turno}">
                                                <button type="submit" class="btn btn-primary">Acceder</button>
                                            </form>
                                        </td>
                                        <td>${proceso.modulo}</td>
                                        <td>${proceso.estilo}</td>
                                        <td>${proceso.team_leader}</td>
                                    </tr>`;
                                tabla.append(row);
                            });
                        }
                    },
                    error: function () {
                        alert('Error al obtener los datos');
                    }
                });
            });

            // Evento para cerrar el modal al hacer clic en el botón de cerrar
            $('#closeModal').on('click', function () {
                cerrarModal();
            });

            // Evento para cerrar el modal al hacer clic fuera del contenido del modal
            $(window).on('click', function (event) {
                if (event.target === document.getElementById('customModal')) {
                    cerrarModal();
                }
            });

            // Evento para cerrar el modal al presionar la tecla "ESC"
            $(document).on('keydown', function (event) {
                if (event.key === "Escape") {
                    cerrarModal();
                }
            });

            // Filtro de búsqueda en la tabla
            $('#searchInput1').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('#tablaProcesos1 tr').filter(function () {
                    var modulo = $(this).find('td:eq(1)').text().toLowerCase();
                    var estilo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(modulo.indexOf(value) > -1 || estilo.indexOf(value) > -1);
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Inicializar select2
            $('#estilo_proceso').select2({
                placeholder: 'Seleccione un estilo',
                allowClear: true
            });

            function cargarEstilos() {
                var moduleid = $('#modulo').val(); // Asegurar que se está obteniendo el moduleid correctamente

                $.ajax({
                    url: "{{ route('obtenerEstilosV2') }}",
                    type: 'GET',
                    data: { moduleid: moduleid },
                    success: function (response) {
                        var selectEstilo = $('#estilo_proceso');
                        selectEstilo.empty();
                        selectEstilo.append('<option value="">Seleccione un estilo</option>');

                        $.each(response.estilos, function (index, estilo) {
                            var selected = (estilo.itemid == "{{ $data['estilo'] }}") ? "selected" : "";
                            selectEstilo.append('<option value="' + estilo.itemid + '" data-cliente="' + estilo.custname + '" ' + selected + '>' + estilo.itemid + '</option>');
                        });

                        // Disparar el evento de cambio manualmente para actualizar el cliente y la URL
                        selectEstilo.trigger('change');
                    }
                });
            }

            // Cargar estilos al iniciar la página
            cargarEstilos();

            // Cuando se seleccione un estilo, actualizar el cliente automáticamente y cambiar la URL
            $('#estilo_proceso').on('change', function () {
                var cliente = $(this).find(':selected').data('cliente');
                $('#cliente').val(cliente || '');

                var nuevoEstilo = $(this).val(); // Obtener el nuevo estilo seleccionado
                actualizarURL('estilo', nuevoEstilo);
            });

            function actualizarURL(parametro, valor) {
                var url = new URL(window.location.href);
                url.searchParams.set(parametro, valor); // Cambia el valor del parámetro en la URL
                window.history.pushState({}, '', url); // Actualiza la URL sin recargar la página
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            $('#lista_nombre').select2({
                placeholder: 'Selecciona una opción',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: "{{ route('procesoV3.registro.obtenerNombresGenerales') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term || '', 
                            modulo: $('#modulo').val()
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data.nombres, function (item) {
                                return {
                                    id: item.name, // El valor del select será el 'name'
                                    text: item.personnelnumber + " - " + item.name, // Lo que se muestra en el select
                                    data: {
                                        personnelnumber: item.personnelnumber // Guardamos el número de empleado en "data"
                                    }
                                };
                            })
                        };
                    },
                    cache: true
                }
            });

            // Capturar el número de empleado al seleccionar una opción
            $('#lista_nombre').on('select2:select', function (e) {
                let selectedData = e.params.data; // Captura los datos de la opción seleccionada
                $(this).attr("data-personnelnumber", selectedData.data.personnelnumber); // Guardamos el número de empleado en un atributo
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            function cargarOperaciones() {
                $(".operacion-select").select2({
                    placeholder: 'Selecciona una opción',
                    allowClear: true,
                    minimumInputLength: 0, // Muestra la lista completa sin escribir
                    ajax: {
                        url: "{{ route('procesoV3.registro.obtenerOperaciones') }}", // Ruta en Laravel
                        type: 'GET',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term || '', // Si no hay búsqueda, devuelve toda la lista
                                modulo: $('#modulo').val() // Enviar el módulo actual para ordenar los resultados
                            };
                        },
                        processResults: function (data) {
                            let opciones = [
                                { id: '', text: 'Selecciona una opción' },
                                { id: 'otra', text: '[OTRA OPERACIÓN]' }
                            ];

                            $.each(data.operaciones, function (index, item) {
                                opciones.push({
                                    id: item.oprname, // Se envía 'oprname' como valor
                                    text: item.oprname // Se muestra 'oprname'
                                });
                            });

                            return { results: opciones };
                        },
                        cache: true
                    }
                });
            }

            // Cargar operaciones al iniciar
            cargarOperaciones();

            // Manejar selección de "OTRA OPERACIÓN"
            $(document).on('change', '.operacion-select', function () {
                let select = $(this);
                let inputOtraOperacion = select.closest("td").find(".otra-operacion-input");

                if (select.val() === 'otra') {
                    select.closest("td").find(".operacion-select-container").hide(); // Oculta el contenedor del select
                    inputOtraOperacion.show().val('').focus(); // Muestra el input de texto
                }
            });

            // Transformar a mayúsculas en el input de "OTRA OPERACIÓN"
            $(document).on('input', '.otra-operacion-input', function () {
                $(this).val($(this).val().toUpperCase());
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Realizar la consulta AJAX al cargar la página
            $.ajax({
                url: "{{ route('procesoV3.registro.accionCorrectivaProceso') }}", // Ruta en Laravel
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var select = $('#ac');
                    select.empty();
                    select.append('<option value="">Selecciona una opción</option>'); // Opción por defecto

                    $.each(response.acciones, function (index, proceso) {
                        select.append(new Option(proceso.accion_correctiva, proceso.accion_correctiva, false, false));
                    });

                    // Inicializar Select2 después de cargar las opciones
                    select.select2({
                        placeholder: "Selecciona una opción",
                        allowClear: true,
                        minimumResultsForSearch: 10
                    });
                },
                error: function () {
                    alert('Error al obtener las acciones correctivas');
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            const tpSelect = $('#tpSelect');
            const selectedOptionsContainer = $('#selectedOptionsContainer');

            // Inicializar Select2 con AJAX
            tpSelect.select2({
                placeholder: 'Selecciona una opción',
                width: '100%',
                ajax: {
                    url: "{{ route('procesoV3.registro.defectos') }}",
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { search: params.term || '' };
                    },
                    processResults: function (data) {
                        const options = data.defectos.map(item => ({
                            id: item.nombre,
                            text: item.nombre,
                        }));
                        // Aseguramos que "CREAR DEFECTO" siempre esté como opción
                        options.unshift({ id: 'OTRO', text: 'CREAR DEFECTO', action: true });
                        return { results: options };
                    },
                    cache: true,
                },
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
                },
            });

            // Forzar que no haya preselección inicial
            tpSelect.val(null).trigger('change');

            // Evento al seleccionar una opción
            tpSelect.on('select2:select', function (e) {
                const selected = e.params.data;

                if (selected.id === 'OTRO') {
                    $('#nuevoConceptoModal').modal('show');
                    tpSelect.val(null).trigger('change'); // Resetear el select para evitar que quede seleccionado
                    return;
                }

                // Agregar la selección al contenedor solo si no es "CREAR DEFECTO"
                addOptionToContainer(selected.id, selected.text);
            });

            // Función para agregar la opción seleccionada al contenedor
            function addOptionToContainer(id, text) {
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1">
                        <button class="btn btn-primary btn-sm duplicate-option">+</button>
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option">Eliminar</button>
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

            // Evento para guardar un nuevo defecto desde el modal
            $('#guardarNuevoConcepto').on('click', function () {
                const nuevoDefecto = $('#nuevoConceptoInput').val().trim();

                if (!nuevoDefecto) {
                    alert('Por favor, ingresa un defecto válido.');
                    return;
                }

                $.ajax({
                    url: "{{ route('procesoV3.registro.crearDefecto') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        nombre: nuevoDefecto,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function (data) {
                        const newOption = new Option(data.nombre, data.nombre, true, true);
                        tpSelect.append(newOption).trigger('change');
                        addOptionToContainer(data.nombre, data.nombre);
                        $('#nuevoConceptoModal').modal('hide');
                        $('#nuevoConceptoInput').val('');
                    },
                    error: function (xhr) {
                        alert('Ocurrió un error al guardar el defecto: ' + xhr.responseJSON.error);
                    },
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Lógica de mostrar/ocultar columnas (ya existente)
            let tabla = $('#auditoriaTabla'); // Referencia específica a la tabla
            let piezasRechazadasInput = tabla.find('input[name="cantidad_rechazada"]');
            let selectedOptionsContainer = tabla.find('#selectedOptionsContainer');
            let acSelect = tabla.find('#ac');

            // Ocultar columnas al inicio
            tabla.find('th:nth-child(5), th:nth-child(6), td:nth-child(5), td:nth-child(6)').hide();
            selectedOptionsContainer.hide();
            acSelect.closest('td').hide();

            // Detectar cambios en "Piezas Rechazadas" (para mostrar/ocultar columnas)
            piezasRechazadasInput.on('input', function () {
                let cantidadRechazada = parseInt($(this).val()) || 0; // Si está vacío, se toma 0

                if (cantidadRechazada > 0) {
                    // Mostrar columnas y activar campos obligatorios
                    tabla.find('th:nth-child(5), th:nth-child(6), td:nth-child(5), td:nth-child(6)').fadeIn();
                    selectedOptionsContainer.fadeIn().attr('data-required', 'true');
                    acSelect.closest('td').fadeIn();
                    acSelect.attr('required', true);
                } else {
                    // Ocultar columnas y eliminar obligatoriedad
                    tabla.find('th:nth-child(5), th:nth-child(6), td:nth-child(5), td:nth-child(6)').fadeOut();
                    selectedOptionsContainer.fadeOut().removeAttr('data-required');
                    acSelect.closest('td').fadeOut();
                    acSelect.removeAttr('required');

                    // Limpiar valores cuando se ocultan
                    selectedOptionsContainer.empty(); // Elimina todas las selecciones
                    acSelect.val('').trigger('change'); // Reinicia el select
                }
            });

            // Validación y envío AJAX al pulsar "GUARDAR"
            $(".btn-verde-xd").on("click", function (e) {
                e.preventDefault(); // Evita recargar la página

                // Mapa de nombres amigables
                let friendlyNames = {
                    "nombre_final": "nombre de operario",
                    "operacion": "operación",
                    "cantidad_auditada": "cantidad auditada",
                    "cantidad_rechazada": "cantidad rechazada",
                    "ac": "acción correctiva"
                };
                // 1. Validar que todos los campos visibles obligatorios estén llenos.
                // Se recorren todos los inputs y selects visibles que tengan "required",
                // excepto el select de defectos con id "tpSelect"
                let camposVacios = [];
                $(".card-body :input:visible[required]").not("#tpSelect").each(function () {
                    if (!$(this).val() || $(this).val().trim() === "") {
                        // Se intenta obtener el friendlyName a partir del atributo "name"
                        let fieldName = $(this).attr('name') || $(this).attr('id');
                        let friendlyName = friendlyNames[fieldName] || fieldName;
                        camposVacios.push(friendlyName);
                    }
                });
                if (camposVacios.length > 0) {
                    alert("Los siguientes campos obligatorios están vacíos: " + camposVacios.join(", "));
                    return; // Detener el envío si hay campos vacíos
                }

                // 2. Validar la relación entre cantidad_rechazada y defectos seleccionados.
                let cantidadRechazada = parseInt(piezasRechazadasInput.val()) || 0;
                let defectCount = selectedOptionsContainer.is(":visible") 
                                    ? selectedOptionsContainer.children().length 
                                    : 0;
                if (cantidadRechazada > 0 && defectCount !== cantidadRechazada) {
                    alert("La cantidad de defectos seleccionados (" + defectCount + 
                        ") debe ser igual a la cantidad de piezas rechazadas (" + cantidadRechazada + ").");
                    return;
                }

                // Si la validación pasa, se arma el objeto formData
                let formData = {
                    modulo: $("#table-200 #modulo").val(),
                    estilo: $("#table-200 #estilo_proceso").val(),
                    team_leader: $("#table-200 #team_leader").val(),
                    gerente_produccion: $("#table-200 input[name='gerente_produccion']").val(),
                    auditor: $("#table-200 #auditor").val(),
                    turno: $("#table-200 #turno").val(),
                    cliente: $("#table-200 #cliente").val(),
                    auditoria: []
                };

                // Recorremos las filas de la tabla de auditoría para extraer los datos.
                $("#auditoriaTabla tbody tr").each(function () {
                    let selectedOptions = [];
                    $(this).find("#selectedOptionsContainer .option-text").each(function () {
                        selectedOptions.push($(this).text().trim()); // Guardamos cada defecto seleccionado
                    });

                    let operacionSeleccionada = $(this).find("select[name='operacion']").val();
                    let operacionEscrita = $(this).find("input[name='operacion']").val();

                    // Si se seleccionó "otra", entonces usa el valor del input
                    let operacionFinal = (operacionSeleccionada === "otra" || operacionSeleccionada === null) ? operacionEscrita : operacionSeleccionada;

                    let row = {
                        nombre_final: $(this).find("select[name='nombre_final']").val(),
                        numero_empleado: $(this).find("select[name='nombre_final']").attr("data-personnelnumber"),
                        operacion: operacionFinal, // Asigna el valor correcto
                        cantidad_auditada: $(this).find("input[name='cantidad_auditada']").val(),
                        cantidad_rechazada: $(this).find("input[name='cantidad_rechazada']").val(),
                        tipo_problema: selectedOptions, // Ahora se obtiene de selectedOptionsContainer
                        accion_correctiva: $(this).find("select[name='ac']").val(),
                        pxp: $(this).find("input[name='pxp']").val()
                    };
                    formData.auditoria.push(row);
                });

                // Enviar datos vía AJAX
                $.ajax({
                    url: "{{ route('procesoV3.registro.formRegistro') }}",
                    type: "POST",
                    data: JSON.stringify(formData),
                    contentType: "application/json",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        Swal.fire(
                            '¡Guardado!',
                            'El registro se guardó correctamente.',
                            'success'
                        ).then((result) => { // Inicio del bloque .then()

                            console.log("SweetAlert cerrado, evaluando si recargar o actualizar...");

                            // --- 1. CALCULAR LAS CONDICIONES PRIMERO ---
                            let cantidadRechazadaMayorACero = false;
                            let operacionEscritaEncontrada = false;

                            // ¡¡REVISA BIEN ESTE SELECTOR!! ¿Dónde buscas estos valores POST-GUARDADO?
                            // ¿Es realmente "#auditoriaTabla tbody tr"? ¿O debería ser el ID del formulario?
                            // Esta lógica puede ser frágil. Idealmente, la 'response' debería indicar esto.
                            $("#auditoriaTabla tbody tr").each(function () {
                                let cantidadRechazada = parseInt($(this).find("input[name='cantidad_rechazada']").val()) || 0;
                                if (cantidadRechazada > 0) {
                                    cantidadRechazadaMayorACero = true;
                                }
                                let opInput = $(this).find("input[name='operacion']");
                                if (opInput.is(":visible") && opInput.val().trim() !== "") {
                                    operacionEscritaEncontrada = true;
                                }
                                // Podríamos optimizar saliendo del bucle si ambas ya son true
                                if (cantidadRechazadaMayorACero && operacionEscritaEncontrada) {
                                    return false; // Sale del $.each
                                }
                            });
                            console.log(`Condiciones evaluadas: rechazo=${cantidadRechazadaMayorACero}, otraOp=${operacionEscritaEncontrada}`);

                            // --- 2. DECIDIR LA ACCIÓN ---
                            if (cantidadRechazadaMayorACero || operacionEscritaEncontrada) {
                                // CASO 1: Se necesita recargar la página.
                                // NO llamamos a cargarTablasRegistros()
                                console.log("Recargando la página completa...");
                                location.reload();
                                // La ejecución se detiene aquí al recargar.

                            } else {
                                // CASO 2: NO se necesita recargar. Actualizar tablas vía AJAX y limpiar form.
                                console.log("Actualizando tablas vía AJAX y limpiando formulario...");

                                // Primero, actualiza las tablas para mostrar el nuevo registro sin recargar
                                window.cargarTablasRegistros();

                                // Luego, limpia el formulario de alta usando TU lógica manual adaptada:
                                console.log("Limpiando formulario de alta manualmente...");

                                // Selecciona la(s) fila(s) DENTRO del tbody de tu TABLA DE FORMULARIO (ID: auditoriaTabla)
                                $("#auditoriaTabla tbody tr").each(function () {
                                    const formRow = $(this); // Usar una variable para claridad (es la fila actual del form)

                                    // 1. Limpiar Inputs (Texto, Número, etc.)
                                    // Usamos tipos específicos para ser más seguros que solo 'input'
                                    formRow.find("input[type='text'], input[type='number'], input[type='email'], textarea").val("");
                                    // Si tienes otros tipos de input (date, time) agrégalos aquí.

                                    // 2. Reiniciar Selects (Importante el trigger('change') para Select2 u otros)
                                    formRow.find("select").val("").trigger('change'); // Resetea a la opción vacía y notifica a los plugins

                                    // 3. Vaciar contenedores específicos (como el de defectos seleccionados)
                                    // Asegúrate que este ID/clase exista DENTRO de la fila del formulario
                                    formRow.find("#selectedOptionsContainer").empty();

                                    // 4. Restaurar el select de operación si se usó la opción "otra"
                                    let selectContainer = formRow.find(".operacion-select-container"); // El div que contiene el select
                                    let inputOtraOperacion = formRow.find(".otra-operacion-input"); // El input para escribir 'otra'
                                    let operacionSelect = formRow.find("select[name='operacion']"); // El select original

                                    // Verifica si el input de 'otra' estaba visible (significa que se usó 'otra')
                                    if (inputOtraOperacion.is(":visible")) {
                                        console.log("Restaurando select de operación...");
                                        inputOtraOperacion.hide().val(""); // Oculta y limpia el input de 'otra'
                                        selectContainer.show(); // Muestra el contenedor del select original
                                        operacionSelect.val("").trigger('change'); // Asegura que el select mostrado esté reseteado

                                        // ** OJO: La parte de recrear el HTML del select NO debería ser necesaria **
                                        // a menos que tu código realmente elimine el select cuando se elige 'otra'.
                                        // Si solo lo ocultas/muestras, la lógica anterior (hide/show/reset) es suficiente.
                                        // Si SÍ lo eliminas y necesitas recrearlo, descomenta y adapta esto:
                                        /*
                                        selectContainer.html(`
                                            <select name="operacion" class="form-control operacion-select" required>
                                                <option value="">Selecciona una opción</option>
                                                <option value="otra">[OTRA OPERACIÓN]</option>
                                                // Posiblemente necesites volver a cargar las opciones aquí si venían de AJAX
                                            </select>
                                        `);
                                        // Y reinicializar Select2 si lo usas en este select recreado:
                                        selectContainer.find(".operacion-select").select2({ // tus opciones de select2 });
                                        */
                                    } else {
                                        // Si no se usó 'otra', solo asegúrate que el select esté visible y el input oculto
                                        selectContainer.show();
                                        inputOtraOperacion.hide();
                                    }
                                }); // Fin del .each para las filas del formulario

                                console.log("Limpieza manual del formulario completada.");

                            }
                            // --- Fin de la lógica condicional ---
                        }); // Fin del bloque .then()
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        alert("❌ Hubo un error al guardar los datos. Por favor, intenta nuevamente.");
                    }
                });
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // --- FUNCIÓN ÚNICA PARA CARGAR AMBAS TABLAS ---
            window.cargarTablasRegistros = function() {
                let modulo = $("#modulo").val();

                if (!modulo) {
                    console.warn("Módulo no seleccionado.");
                    $("#registros-turno-normal tbody").html('<tr><td colspan="10" class="text-center">Seleccione un módulo</td></tr>');
                    $("#registros-turno-extra tbody").html('<tr><td colspan="10" class="text-center">Seleccione un módulo</td></tr>');
                    actualizarTablaIndividualNormal({});
                    actualizarTotalGeneralNormal(0, 0);
                    actualizarTablaIndividualExtra({});
                    actualizarTotalGeneralExtra(0, 0);
                    return;
                }

                // $("#loadingIndicator").show(); // Opcional: indicador de carga

                $.ajax({
                    url: "{{ route('procesoV3.registro.obtenerRegistroDia') }}", // Ruta de Laravel para obtener datos
                    type: "GET",
                    data: { modulo: modulo },
                    dataType: "json",
                    success: function (response) {
                        popularTabla(
                            response.registrosNormales || [],
                            $("#registros-turno-normal tbody"),
                            actualizarTablaIndividualNormal,
                            actualizarTotalGeneralNormal,
                            'normal'
                        );
                        popularTabla(
                            response.registrosExtras || [],
                            $("#registros-turno-extra tbody"),
                            actualizarTablaIndividualExtra,
                            actualizarTotalGeneralExtra,
                            'extra'
                        );
                    },
                    error: function (xhr) {
                        console.error("Error AJAX al cargar registros:", xhr.status, xhr.responseText);
                        $("#registros-turno-normal tbody").html('<tr><td colspan="10" class="text-center">Error al cargar datos</td></tr>');
                        $("#registros-turno-extra tbody").html('<tr><td colspan="10" class="text-center">Error al cargar datos</td></tr>');
                        Swal.fire('Error', 'Error al cargar los registros. Intente de nuevo.', 'error');
                    },
                    complete: function() {
                        // $("#loadingIndicator").hide(); // Opcional
                    }
                });
            }

            // --- FUNCIÓN HELPER REUTILIZABLE PARA POBLAR UNA TABLA Y CALCULAR STATS ---
            function popularTabla(registros, tbodyElement, fnActualizarIndividual, fnActualizarGeneral, tipo) {
                tbodyElement.empty();

                let registrosAgrupados = {};
                let totalAuditadaGeneral = 0;
                let totalRechazadaGeneral = 0;

                if (!registros || registros.length === 0) {
                    tbodyElement.append(`<tr><td colspan="10" class="text-center">No hay registros disponibles para el tipo '${tipo}'</td></tr>`);
                } else {
                    $.each(registros, function (index, registro) {
                        let paroHtml = "-";
                        if (registro.inicio_paro) {
                            if (registro.fin_paro) {
                                paroHtml = registro.minutos_paro !== null ? registro.minutos_paro : 'Calculando...';
                            } else {
                                let urlFinalizarParo = `/auditoriaProcesoV3/registro/finalizar-paro/${registro.id}`;
                                paroHtml = `<button class="btn btn-primary btn-sm fin-paro-btn" data-id="${registro.id}" data-url="${urlFinalizarParo}" data-tipo="${tipo}">
                                                Fin Paro Proceso
                                            </button>`;
                            }
                        }
                        // *** CORRECCIÓN AQUÍ: URL para eliminar ***
                        // La ruta en Laravel es /auditoriaProcesoV3/registro/{id} con método DELETE
                        let urlEliminar = `/auditoriaProcesoV3/registro/eliminar/${registro.id}`;

                        let fila = `
                            <tr>
                                <td>${paroHtml}</td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.nombre || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.operacion || ''}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_auditada || '0'}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.cantidad_rechazada || '0'}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.defectos || 'N/A'}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.ac || 'N/A'}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${registro.pxp || 'N/A'}" readonly></td>
                                <td>
                                    <button class="btn btn-danger btn-sm eliminar-registro" data-id="${registro.id}" data-url="${urlEliminar}" data-tipo="${tipo}">
                                        Eliminar
                                    </button>
                                </td>
                                <td>${registro.created_at ? new Date(registro.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'N/A'}</td>
                            </tr>`;
                        tbodyElement.append(fila);

                        totalAuditadaGeneral += parseInt(registro.cantidad_auditada) || 0;
                        totalRechazadaGeneral += parseInt(registro.cantidad_rechazada) || 0;

                        if (registro.nombre) {
                            if (!registrosAgrupados[registro.nombre]) {
                                registrosAgrupados[registro.nombre] = { cantidad_registros: 0, total_auditada: 0, total_rechazada: 0 };
                            }
                            registrosAgrupados[registro.nombre].cantidad_registros++;
                            registrosAgrupados[registro.nombre].total_auditada += parseInt(registro.cantidad_auditada) || 0;
                            registrosAgrupados[registro.nombre].total_rechazada += parseInt(registro.cantidad_rechazada) || 0;
                        }
                    });
                }
                fnActualizarIndividual(registrosAgrupados);
                fnActualizarGeneral(totalAuditadaGeneral, totalRechazadaGeneral);
            }

            // --- Tus funciones para actualizar estadísticas (sin cambios, asumiendo que funcionan) ---
            function actualizarTotalGeneralNormal(totalAuditada, totalRechazada) {
                let porcentajeRechazo = totalAuditada > 0 ? ((totalRechazada / totalAuditada) * 100).toFixed(2) : "0.00";
                $("#total_auditada_general").val(totalAuditada);
                $("#total_rechazada_general").val(totalRechazada);
                $("#total_porcentaje_general").val(porcentajeRechazo);
            }
            function actualizarTablaIndividualNormal(registrosAgrupados) {
                let tbody = $(".table-total-individual tbody"); // Asegúrate que este selector sea único para la tabla normal
                tbody.empty();
                if (Object.keys(registrosAgrupados).length === 0) {
                    tbody.append(`<tr><td colspan="5" class="text-center">No hay datos disponibles</td></tr>`);
                } else {
                    $.each(registrosAgrupados, function (nombre, data) {
                        let porcentajeRechazado = data.total_auditada > 0 ? ((data.total_rechazada / data.total_auditada) * 100).toFixed(2) : "0.00";
                        let fila = `
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" value="${nombre}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.cantidad_registros}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.total_auditada}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.total_rechazada}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${porcentajeRechazado}" readonly></td>
                            </tr>`;
                        tbody.append(fila);
                    });
                }
            }
            function actualizarTotalGeneralExtra(totalAuditada, totalRechazada) {
                let porcentajeRechazo = totalAuditada > 0 ? ((totalRechazada / totalAuditada) * 100).toFixed(2) : "0.00";
                $("#total_auditada_general-tiempo-extra").val(totalAuditada);
                $("#total_rechazada_general-tiempo-extra").val(totalRechazada);
                $("#total_porcentaje_general-tiempo-extra").val(porcentajeRechazo);
            }
            function actualizarTablaIndividualExtra(registrosAgrupados) {
                let tbody = $(".table-total-individual-tiempo-extra tbody"); // Asegúrate que este selector sea único para la tabla extra
                tbody.empty();
                if (Object.keys(registrosAgrupados).length === 0) {
                    tbody.append(`<tr><td colspan="5" class="text-center">No hay datos disponibles</td></tr>`);
                } else {
                    $.each(registrosAgrupados, function (nombre, data) {
                        let porcentajeRechazado = data.total_auditada > 0 ? ((data.total_rechazada / data.total_auditada) * 100).toFixed(2) : "0.00";
                        let fila = `
                            <tr>
                                <td><input type="text" class="form-control texto-blanco" value="${nombre}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.cantidad_registros}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.total_auditada}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${data.total_rechazada}" readonly></td>
                                <td><input type="text" class="form-control texto-blanco" value="${porcentajeRechazado}" readonly></td>
                            </tr>`;
                        tbody.append(fila);
                    });
                }
            }

            // --- MANEJADORES DE EVENTOS DELEGADOS ---
            $(document.body).on("click", ".fin-paro-btn", function(e) {
                e.preventDefault();
                let boton = $(this);
                let url = boton.data("url");
                let tipoRegistro = boton.data("tipo");
                boton.prop('disabled', true).text('...');
                $.ajax({
                    url: url,
                    type: "POST", // O PUT/PATCH según tu ruta de finalizar paro
                    // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // Ya está en $.ajaxSetup
                    success: function(response) {
                        if (response.minutos_paro !== undefined) {
                            boton.closest("td").text(response.minutos_paro);
                            Swal.fire('¡Éxito!', `Paro finalizado (${tipoRegistro}). Duración: ${response.minutos_paro} minutos.`, 'success');
                        } else if (response.warning) {
                            Swal.fire('Advertencia', response.warning, 'warning');
                            boton.prop('disabled', false).text('Fin Paro Proceso');
                        } else {
                            Swal.fire('Respuesta inesperada', 'El servidor no devolvió la información esperada.', 'question');
                            boton.prop('disabled', false).text('Fin Paro Proceso');
                        }
                    },
                    error: function(xhr) {
                        console.error(`Error finalizar paro (${tipoRegistro}):`, xhr.responseText);
                        let errorMsg = `Error al finalizar el paro (${tipoRegistro}).`;
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg = xhr.responseJSON.error;
                        }
                        Swal.fire('Error', errorMsg, 'error');
                        boton.prop('disabled', false).text('Fin Paro Proceso');
                    }
                });
            });

            // Eliminar Registro (Manejador único)
            $(document.body).on("click", ".eliminar-registro", function (e) {
                e.preventDefault();
                let boton = $(this);
                let url = boton.data("url"); // URL ya corregida desde popularTabla
                let tipoRegistro = boton.data("tipo");
                let registroId = boton.data("id"); // Para el mensaje de confirmación

                Swal.fire({
                    title: `¿Eliminar Registro (${tipoRegistro})?`,
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning', // Esto debería funcionar con SweetAlert2 moderno
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        boton.prop('disabled', true).text('Eliminando...'); // Deshabilitar botón
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            // headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }, // Ya está en $.ajaxSetup
                            dataType: "json", // Esperamos JSON del servidor
                            success: function(response) {
                                if (response.message) {
                                    Swal.fire('¡Eliminado!', `Registro ${tipoRegistro} eliminado: ${response.message}`, 'success');
                                    boton.closest("tr").fadeOut(400, function() {
                                        $(this).remove();
                                        // ** IMPORTANTE: Recalcular estadísticas o recargar tablas **
                                        // La forma más simple pero menos ideal es:
                                        // cargarTablasRegistros();
                                        // O, si quieres ser más granular, tendrías que obtener los datos de la fila eliminada
                                        // y restarlos de tus variables de estadísticas globales, luego actualizar el DOM.
                                        console.warn(`Registro ${tipoRegistro} (ID: ${registroId}) eliminado. Stats en cliente pueden estar desactualizados hasta próximo refresh completo o recálculo manual.`);
                                    });
                                } else if (response.warning) {
                                    // Esto es para manejar el caso donde el controlador devuelve un warning con status 200
                                    Swal.fire('Advertencia', response.warning, 'warning');
                                    boton.prop('disabled', false).text('Eliminar'); // Habilitar botón de nuevo
                                } else {
                                    Swal.fire('Respuesta inesperada', 'El servidor no devolvió el mensaje esperado.', 'question');
                                    boton.prop('disabled', false).text('Eliminar');
                                }
                            },
                            error: function(xhr) {
                                console.error(`Error eliminar registro (${tipoRegistro} - ID: ${registroId}):`, xhr.status, xhr.responseText);
                                let errorMsg = `Error al eliminar el registro (${tipoRegistro} - ID: ${registroId}).`;
                                if (xhr.responseJSON && xhr.responseJSON.error) {
                                    errorMsg = `Error: ${xhr.responseJSON.error}`;
                                } else if (xhr.responseJSON && xhr.responseJSON.message) { // Algunos errores de Laravel vienen en 'message'
                                    errorMsg = `Error: ${xhr.responseJSON.message}`;
                                } else if (xhr.status === 0) {
                                    errorMsg = "No se pudo conectar con el servidor. Verifique su conexión de red.";
                                } else if (xhr.status === 404) {
                                    errorMsg = "Recurso no encontrado en el servidor (Error 404). Verifique la URL.";
                                } else if (xhr.status === 500) {
                                    errorMsg = "Error interno del servidor (Error 500).";
                                }
                                Swal.fire('Error', errorMsg, 'error');
                                boton.prop('disabled', false).text('Eliminar'); // Habilitar botón de nuevo
                            }
                        });
                    }
                });
            });

            // --- MANEJADORES PARA LOS COMENTARIOS (mantener como estaban si funcionan) ---
            // $('#guardarComentario').on('click', function () { /* ... tu lógica ... */ });
            // $('#guardarComentarioTE').on('click', function () { /* ... tu lógica ... */ });

            // --- CARGA INICIAL AL ENTRAR A LA PÁGINA ---
            cargarTablasRegistros();

            // --- OPCIONAL: RECARGAR SI CAMBIA EL MÓDULO ---
            $('#modulo').on('change', function() {
                cargarTablasRegistros();
            });
        }); // Fin de $(document).ready
    </script>

    <script>
        $(document).ready(function () {
            let datosCargados = false;
        
            // Cuando se abra el acordeón para cargar paros no finalizados
            $('#collapseParos').on('show.bs.collapse', function () {
                if (!datosCargados) {
                    const modulo = $('#paros-container').data('modulo');
        
                    $.ajax({
                        url: '/api/paros-no-finalizados',  // Nuevo endpoint para paros no finalizados
                        method: 'GET',
                        data: { modulo: modulo }, 
                        beforeSend: function () {
                            $('#paros-container').html('<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando datos...</p></div>');
                        },
                        success: function (response) {
                            if (response.length > 0) {
                                let contenido = `
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover">
                                            <thead class="thead-primary">
                                                <tr>
                                                    <th>Paro</th>
                                                    <th>Nombre</th>
                                                    <th>Inicio Paro</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                `;
                                response.forEach(item => {
                                    // Para cada registro, si fin_paro es nulo, se muestra botón para finalizar
                                    let paroHtml = "";
                                    if (item.inicio_paro === null) {
                                        paroHtml = "-";
                                    } else if (item.fin_paro !== null) {
                                        paroHtml = item.minutos_paro;
                                    } else {
                                        paroHtml = `<button class="btn btn-primary btn-sm finalizar-paro" data-id="${item.id}">
                                                        Finalizar Paro Pendiente
                                                    </button>`;
                                    }
        
                                    contenido += `
                                        <tr>
                                            <td>${paroHtml}</td>
                                            <td>${item.nombre}</td>
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
                                $('#paros-container').html(contenido);
                            } else {
                                $('#paros-container').html('<p class="text-warning text-center">No se encontraron paros no finalizados.</p>');
                            }
                            datosCargados = true;
                        },
                        error: function () {
                            $('#paros-container').html('<p class="text-danger text-center">Error al cargar los datos.</p>');
                        }
                    });
                }
            });
        
            // Delegar evento para finalizar un paro
            $(document).on('click', '.finalizar-paro', function () {
                let id = $(this).data('id');
        
                // Opcional: Confirmar la acción
                if (!confirm("¿Estás seguro de que deseas finalizar este paro?")) return;
        
                // Agregar un spinner temporal para indicar procesamiento
                const spinnerHtml = `
                    <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                        <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        Procesando solicitud...
                    </div>`;
                $('body').append(spinnerHtml);
        
                $.ajax({
                    url: '/api/finalizar-paro-proceso-despues', // Nuevo endpoint (ver sección de Controller)
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { id: id }, // Ya no se envían piezas reparadas
                    success: function (response) {
                        $('#processing-spinner').remove();
                        if (response.success) {
                            alert(`✅ Paro finalizado correctamente.\nMinutos Paro: ${response.minutos_paro}`);
                            // Cierra el acordeón para forzar nueva carga al reabrirlo
                            $('#collapseParos').collapse('hide');
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
            });
        });
    </script>
    
@endsection
