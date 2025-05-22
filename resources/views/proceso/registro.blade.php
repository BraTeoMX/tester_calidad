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
    <style>

        tr.paro-advertencia td {
            background-color: #59470F !important; /* Amarillo mostaza oscuro */
            color: #F0F0F0 !important; /* Texto claro para contraste */
        }

        tr.paro-critico td {
            background-color: #5D1A1A !important; /* Rojo sangre oscuro */
            color: #F0F0F0 !important; /* Texto claro para contraste */
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
            let estilosDataParaSelect2 = []; // Para almacenar los datos formateados para Select2
            const valorEstiloPreseleccionadoBlade = "{{ $data['estilo'] ?? '' }}"; // Valor inicial de Blade

            // Función matcher personalizada (opcional, si la búsqueda por defecto no es suficiente)
            // Por defecto, select2 busca en el campo 'text'. Si 'text' es 'itemid', buscará por 'itemid'.
            // Si quieres buscar también en 'custname' con el mismo input:
            function estiloCustomMatcher(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                if (typeof data.text === 'undefined' || typeof data.originalData === 'undefined') {
                    return null;
                }
                const term = params.term.toLowerCase();
                const itemIdText = String(data.originalData.itemid).toLowerCase();
                const custNameText = String(data.originalData.custname || '').toLowerCase(); // custname puede ser null

                if (itemIdText.includes(term) || custNameText.includes(term)) {
                    return data;
                }
                return null;
            }

            function cargarYConfigurarEstilos() {
                var moduleid = $('#modulo').val(); // Asegurar que se está obteniendo el moduleid correctamente

                // Mostrar estado de carga y deshabilitar
                $('#estilo_proceso').prop('disabled', true);
                if ($('#estilo_proceso').data('select2')) {
                    $('#estilo_proceso').select2('destroy');
                }
                $('#estilo_proceso').html('<option value="">Cargando estilos...</option>');

                $.ajax({
                    url: "{{ route('procesoV3.obtenerEstilos') }}", // Asegúrate que esta ruta apunta a tu método `obtenerEstilos`
                    type: 'GET',
                    data: { moduleid: moduleid }, // Enviar moduleid si es necesario para la consulta
                    dataType: 'json',
                    success: function (response) {
                        estilosDataParaSelect2 = $.map(response.estilos || [], function (estilo) {
                            return {
                                id: estilo.itemid, // El valor de la opción
                                text: estilo.itemid, // El texto que se muestra (y se busca por defecto)
                                originalData: estilo // Guardamos el objeto original para data adicional
                            };
                        });

                        // Destruir select2 si ya existe (por si se recarga) y limpiar opciones
                        if ($('#estilo_proceso').data('select2')) {
                            $('#estilo_proceso').select2('destroy');
                        }
                        $('#estilo_proceso').empty(); // Limpiar opciones antiguas

                        // Inicializar select2 con los datos locales
                        $('#estilo_proceso').select2({
                            placeholder: 'Seleccione un estilo o busque',
                            allowClear: true,
                            data: estilosDataParaSelect2, // ¡Los datos cargados!
                            // Descomenta la siguiente línea si quieres la búsqueda personalizada en itemid y custname
                            // matcher: estiloCustomMatcher,
                        });

                        // Añadir la opción "Seleccione un estilo" como la primera opción si no está ya por el placeholder
                        if (estilosDataParaSelect2.length > 0) { // Solo si hay datos, para no mostrarlo solo
                            $('#estilo_proceso').prepend('<option value="" data-placeholder="true"></option>');
                        }


                        // Intentar restaurar la selección basada en el valor de Blade
                        if (valorEstiloPreseleccionadoBlade) {
                            $('#estilo_proceso').val(valorEstiloPreseleccionadoBlade).trigger('change.select2');
                        } else {
                            $('#estilo_proceso').val("").trigger('change.select2'); // Para asegurar que el placeholder se muestre
                        }

                        $('#estilo_proceso').prop('disabled', false);

                        // Si después de seleccionar el valor por defecto necesitas ejecutar la lógica de 'change'
                        // explícitamente (porque 'change.select2' podría no disparar todos los listeners 'change' genéricos):
                        // $('#estilo_proceso').trigger('change');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Error al cargar estilos:", textStatus, errorThrown);
                        if ($('#estilo_proceso').data('select2')) {
                            $('#estilo_proceso').select2('destroy');
                        }
                        $('#estilo_proceso').html('<option value="">Error al cargar estilos</option>').prop('disabled', true);
                    }
                });
            }

            // Cargar estilos al iniciar la página (o cuando el módulo esté disponible)
            if ($('#modulo').val()) { // Solo cargar si hay un módulo seleccionado inicialmente
                cargarYConfigurarEstilos();
            } else {
                // Configurar select2 vacío si no hay módulo inicial, para que se vea bien
                $('#estilo_proceso').select2({
                    placeholder: 'Seleccione primero un módulo',
                    allowClear: true
                }).prop('disabled', true);
            }


            // Cuando cambie el módulo, recargar los estilos
            $('#modulo').on('change', function () {
                cargarYConfigurarEstilos();
            });

            // Cuando se seleccione un estilo, actualizar el cliente y la URL
            $('#estilo_proceso').on('change', function () {
                var selectedData = $(this).select2('data')[0]; // Obtener el objeto de datos de la opción seleccionada
                var cliente = '';
                var nuevoEstilo = $(this).val();

                if (selectedData && selectedData.originalData) {
                    cliente = selectedData.originalData.custname;
                } else if (!nuevoEstilo && $('#cliente').length) { // Si se deselecciona (valor vacío)
                    cliente = ''; // Limpiar cliente si se deselecciona estilo
                }


                $('#cliente').val(cliente || ''); // Actualizar el campo cliente (asegúrate que existe un input#cliente)

                if (nuevoEstilo) { // Solo actualizar URL si hay un estilo válido
                    actualizarURL('estilo', nuevoEstilo);
                } else {
                    actualizarURL('estilo', ''); // Opcional: limpiar el parámetro si se deselecciona
                }
            });

            function actualizarURL(parametro, valor) {
                var url = new URL(window.location.href);
                if (valor) {
                    url.searchParams.set(parametro, valor);
                } else {
                    url.searchParams.delete(parametro); // Eliminar el parámetro si el valor es vacío/nulo
                }
                // Usar replaceState para no llenar el historial con cada cambio de filtro si no es deseado
                window.history.replaceState({}, '', url);
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            // Variable para almacenar los datos de los nombres/empleados una vez cargados
            let nombresData = [];

            // Función matcher personalizada para que select2 busque en múltiples campos
            function customMatcher(params, data) {
                // Si no hay término de búsqueda, mostrar todas las opciones
                // (select2 maneja esto internamente si retornamos 'data' cuando no hay término)
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Si la opción no tiene 'text' o no tiene 'originalData' (nuestros datos crudos), no la incluimos
                if (typeof data.text === 'undefined' || typeof data.originalData === 'undefined') {
                    return null;
                }

                const term = params.term.toLowerCase();
                const personnelNumber = String(data.originalData.personnelnumber).toLowerCase();
                const name = data.originalData.name.toLowerCase();

                // Si el término de búsqueda se encuentra en el número de personal o en el nombre
                if (personnelNumber.includes(term) || name.includes(term)) {
                    return data; // Devuelve el objeto de datos si hay coincidencia
                }

                // Devuelve null si no hay coincidencia
                return null;
            }

            // Función para cargar datos e inicializar (o re-inicializar) Select2
            function cargarEInicializarSelectNombres(moduloId) {
                if (!moduloId) {
                    // Si no hay módulo, limpiar el select y deshabilitarlo
                    $('#lista_nombre').empty().append('<option value="">Selecciona primero un módulo</option>').prop('disabled', true);
                    if ($('#lista_nombre').data('select2')) { // Destruir instancia previa si existe
                        $('#lista_nombre').select2('destroy');
                    }
                    return;
                }

                // Mostrar estado de carga
                $('#lista_nombre').prop('disabled', true).empty().append('<option value="">Cargando empleados...</option>');
                if ($('#lista_nombre').data('select2')) { // Destruir instancia previa si existe
                    $('#lista_nombre').select2('destroy');
                }
                $('#lista_nombre').empty();


                $.ajax({
                    url: "{{ route('procesoV3.registro.obtenerNombresGenerales') }}", // Tu endpoint actual
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        modulo: moduloId,
                        search: '' // Importante: search vacío para traer todos los del módulo
                    },
                    success: function (response) {
                        // Mapear los datos recibidos al formato que Select2 espera
                        // y guardar los datos originales para la búsqueda y la selección
                        nombresData = $.map(response.nombres, function (item) {
                            return {
                                id: item.name, // El valor que se enviará del select (puede ser item.personnelnumber si lo prefieres)
                                text: item.personnelnumber + " - " + item.name, // Lo que se muestra en el select
                                originalData: item // Guardamos el objeto completo para el matcher y el evento 'select'
                            };
                        });

                        // Inicializar Select2 con los datos locales
                        $('#lista_nombre').select2({
                            placeholder: 'Selecciona una opción o busca',
                            allowClear: true,
                            data: nombresData, // ¡Aquí pasamos los datos cargados!
                            matcher: customMatcher, // Nuestra función de búsqueda personalizada
                            minimumInputLength: 0 // Permite abrir y ver la lista sin escribir
                        });

                        $('#lista_nombre').prop('disabled', false);
                        // Asegurar que el placeholder se muestre si no hay valor inicial
                        if ($('#lista_nombre').find('option[value=""]').length === 0) {
                            $('#lista_nombre').prepend('<option value="" selected>Selecciona una opción</option>');
                        }
                        $('#lista_nombre').val("").trigger('change');


                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Error al cargar nombres:", textStatus, errorThrown);
                        $('#lista_nombre').empty().append('<option value="">Error al cargar datos</option>').prop('disabled', true);
                    }
                });
            }

            // Capturar el número de empleado al seleccionar una opción
            $('#lista_nombre').on('select2:select', function (e) {
                let selectedData = e.params.data; // Captura los datos de la opción seleccionada
                if (selectedData && selectedData.originalData) {
                    // Guardamos el número de empleado en un atributo data-* del select
                    $(this).attr("data-personnelnumber", selectedData.originalData.personnelnumber);
                    // También puedes asignarlo a un input hidden si es para un formulario
                    // $('#id_del_input_hidden_para_personnelnumber').val(selectedData.originalData.personnelnumber);
                } else if (selectedData && selectedData.id === "") {
                    // Si se selecciona la opción "Selecciona una opción" (placeholder)
                    $(this).removeAttr("data-personnelnumber");
                }
            });

            // Lógica para cuando el campo #modulo cambie (si aplica)
            // o para la carga inicial.
            // Asumiré que tienes un input con id="modulo"
            let moduloActual = $('#modulo').val();
            if (moduloActual) {
                cargarEInicializarSelectNombres(moduloActual);
            } else {
                $('#lista_nombre').empty().append('<option value="">Selecciona primero un módulo</option>').prop('disabled', true);
            }

            $('#modulo').on('change', function () {
                let nuevoModuloId = $(this).val();
                cargarEInicializarSelectNombres(nuevoModuloId);
            });

        });
    </script>

    <script> 
        $(document).ready(function () {
            const $selectOperaciones = $(".operacion-select"); // Considera usar un ID si es una única instancia para más precisión
            const $inputOtraOperacion = $selectOperaciones.closest("td").find(".otra-operacion-input");
            const $selectContainer = $selectOperaciones.closest(".operacion-select-container");

            // Función para gestionar el estado (visible/oculto, habilitado/deshabilitado, required)
            function gestionarEstadoCampos(esModoOtraOperacion) {
                if (esModoOtraOperacion) {
                    // Modo "OTRA OPERACIÓN" activo
                    $selectContainer.hide();
                    $selectOperaciones.prop('disabled', true).removeAttr('required'); // Deshabilitar select

                    $inputOtraOperacion.show().val('').focus(); // Mostrar, limpiar y enfocar input
                    $inputOtraOperacion.prop('disabled', false).attr('required', 'required'); // Habilitar input y hacerlo required
                } else {
                    // Modo selección de lista activo
                    $selectContainer.show();
                    $selectOperaciones.prop('disabled', false).attr('required', 'required'); // Habilitar select y hacerlo required

                    $inputOtraOperacion.hide().val(''); // Ocultar y limpiar input
                    $inputOtraOperacion.prop('disabled', true).removeAttr('required'); // Deshabilitar input
                }
            }

            function cargarYConfigurarOperaciones() {
                const moduloActual = $('#modulo').val();
                // Guardar el valor seleccionado actual del select (si existe y es relevante para preselección)
                const valorSeleccionadoPreviamente = $selectOperaciones.val();

                $selectOperaciones.prop('disabled', true); // Deshabilitar mientras carga
                if ($selectOperaciones.data('select2')) {
                    $selectOperaciones.select2('destroy');
                }
                // Mostrar un placeholder de carga. No borraremos las opciones estáticas del HTML aún.
                $selectOperaciones.html('<option value="">Cargando operaciones...</option>');

                $.ajax({
                    url: "{{ route('procesoV3.registro.obtenerOperaciones') }}",
                    type: 'GET',
                    data: { modulo: moduloActual, search: '' }, // search: '' para traer todos del módulo
                    dataType: 'json',
                    success: function (response) {
                        let opcionesDinamicas = $.map(response.operaciones || [], function (item) {
                            return { id: item.oprname, text: item.oprname };
                        });

                        // Preparamos los datos para Select2
                        // La opción "Selecciona una opción" (value="") será el placeholder de Select2
                        // La opción "[OTRA OPERACIÓN]" (value="otra") la añadimos a los datos.
                        const datosParaSelect2 = [
                            // La opción { id: '', text: 'Selecciona una opción'} se maneja con placeholder
                            { id: 'otra', text: '[OTRA OPERACIÓN]' }, // Importante: id debe ser 'otra'
                            ...opcionesDinamicas
                        ];

                        // Destruir select2 si existe (por si es una recarga) y vaciar el select HTML
                        // para que Select2 lo reconstruya solo con la opción 'data'.
                        if ($selectOperaciones.data('select2')) {
                            $selectOperaciones.select2('destroy');
                        }
                        $selectOperaciones.empty(); // Limpiar el select de cualquier <option> previa

                        $selectOperaciones.select2({
                            placeholder: 'Selecciona una opción o busca', // Esto crea la opción vacía visualmente
                            allowClear: true,
                            minimumInputLength: 0,
                            data: datosParaSelect2 // Usar los datos cargados y procesados
                        });

                        // Lógica de preselección (si aplica, ej. al editar un formulario)
                        // Aquí debes decidir qué valor preseleccionar. Si el valor original era 'otra', o una operación específica.
                        let valorASeleccionar = null;
                        if (valorSeleccionadoPreviamente === 'otra') {
                            valorASeleccionar = 'otra';
                        } else if (valorSeleccionadoPreviamente && opcionesDinamicas.some(op => op.id === valorSeleccionadoPreviamente)) {
                            valorASeleccionar = valorSeleccionadoPreviamente;
                        }
                        $selectOperaciones.val(valorASeleccionar).trigger('change.select2'); // Dispara el evento para actualizar UI y lógica

                        // El evento 'change' (que se define más abajo) llamará a gestionarEstadoCampos.
                        // Si no hay valor preseleccionado (valorASeleccionar es null),
                        // el 'change' se dispara con null, y gestionarEstadoCampos se llamará con 'false'.
                        // Es importante que el 'change' se dispare DESPUÉS de inicializar select2.

                        // Habilitar el select (gestionarEstadoCampos se encarga de esto en base al valor)
                        // $selectOperaciones.prop('disabled', false); // Se maneja en gestionarEstadoCampos

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Error al cargar operaciones:", textStatus, errorThrown);
                        if ($selectOperaciones.data('select2')) {
                            $selectOperaciones.select2('destroy');
                        }
                        // En caso de error, restaurar las opciones estáticas básicas que estaban en el HTML
                        // o al menos la de "otra" para que la lógica no se rompa completamente.
                        $selectOperaciones.html('<option value="">Error al cargar</option><option value="otra">[OTRA OPERACIÓN]</option>');
                        $selectOperaciones.select2({ placeholder: 'Error al cargar' }); // Reinicializar select2
                        gestionarEstadoCampos(false); // Asumir estado no-otra
                        $selectOperaciones.prop('disabled', true); // Deshabilitar por error
                    }
                });
            }

            // Cargar operaciones al iniciar si el módulo está disponible
            if ($('#modulo').val()) {
                cargarYConfigurarOperaciones();
            } else {
                // Si no hay módulo, inicializar Select2 con las opciones estáticas que ya están en el HTML
                // o con un set de datos mínimo si el HTML fue limpiado.
                if ($selectOperaciones.data('select2')) {
                    $selectOperaciones.select2('destroy');
                }
                $selectOperaciones.empty(); // Limpiar por si acaso
                $selectOperaciones.select2({
                    placeholder: 'Selecciona primero un módulo',
                    allowClear: true,
                    data: [ // Solo permitir "otra" si no hay módulo, o ninguna si se prefiere
                        { id: 'otra', text: '[OTRA OPERACIÓN]' }
                    ]
                }).prop('disabled', true); // Deshabilitar el select
                gestionarEstadoCampos(false); // Asegurar estado de campos (input de "otra" oculto y deshabilitado)
                $selectOperaciones.prop('disabled', true); // Redundante pero seguro.
            }

            // Manejar el cambio del módulo para recargar las operaciones
            $('#modulo').on('change', function () {
                cargarYConfigurarOperaciones();
            });

            // Manejar el cambio en el select de operaciones
            $selectOperaciones.on('change', function () {
                const esModoOtra = $(this).val() === 'otra';
                gestionarEstadoCampos(esModoOtra);
            });

            // Transformar a mayúsculas el input de "OTRA OPERACIÓN"
            $inputOtraOperacion.on('input', function () {
                $(this).val($(this).val().toUpperCase());
            });

            // Llamada inicial a gestionarEstadoCampos por si el HTML tiene "otra" preseleccionado
            // (aunque con la carga dinámica y el .val(null).trigger('change') esto debería cubrirse)
            // Se ejecuta después de la posible inicialización síncrona de select2 (si no hay módulo)
            // o se ejecutará por el trigger('change') después de la carga AJAX.
            const valorActualSelect = $selectOperaciones.val();
            gestionarEstadoCampos(valorActualSelect === 'otra');
            if (!$('#modulo').val() && valorActualSelect !== 'otra') { // Si no hay módulo y no es 'otra'
                $selectOperaciones.prop('disabled', true); // Asegurar que el select esté deshabilitado.
            }


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
            let defectosDataLoaded = false; // Bandera para controlar la carga única de datos

            // Configuración común de Select2 que se reutilizará
            const select2Options = {
                placeholder: 'Selecciona una opción',
                width: '100%',
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
                    searching: function() { // Mensaje mientras carga por primera vez
                        return "Buscando...";
                    }
                }
            };

            // Inicializar Select2 de forma mínima para que podamos detectar el evento 'open'
            // o mostrar un mensaje de carga inicial.
            tpSelect.select2(select2Options);
            tpSelect.val(null).trigger('change'); // Forzar que no haya preselección inicial

            tpSelect.on('select2:open', function () {
                if (!defectosDataLoaded) {
                    // Mostrar algún feedback de carga si es posible, Select2 lo hace con 'searching'
                    // tpSelect.prop('disabled', true); // Opcional: deshabilitar mientras carga

                    $.ajax({
                        url: "{{ route('procesoV3.registro.defectos') }}", // El backend debe devolver TODOS los defectos si search es ''
                        type: 'GET',
                        dataType: 'json',
                        data: { search: '' } // Pedimos todos los defectos relevantes
                    }).done(function (data) {
                        const options = data.defectos.map(item => ({
                            id: item.nombre,
                            text: item.nombre,
                        }));
                        // Aseguramos que "CREAR DEFECTO" siempre esté como opción al inicio
                        options.unshift({ id: 'OTRO', text: 'CREAR DEFECTO', action: true });

                        // Destruir la instancia actual de Select2 y reinicializar con los datos estáticos
                        if (tpSelect.data('select2')) {
                            tpSelect.select2('destroy');
                        }
                        tpSelect.empty(); // Limpiar opciones previas si 'destroy' no lo hizo

                        // Reconfigurar Select2 con los datos cargados
                        tpSelect.select2($.extend({}, select2Options, { data: options }));
                        
                        defectosDataLoaded = true;
                        // tpSelect.prop('disabled', false); // Habilitar si se deshabilitó
                        tpSelect.select2('open'); // Volver a abrir el dropdown ahora que tiene datos

                    }).fail(function (xhr) {
                        console.error("Error al cargar los defectos:", xhr);
                        // tpSelect.prop('disabled', false);
                        // Manejar error, quizás mostrar un mensaje o solo la opción de crear
                        if (tpSelect.data('select2')) {
                            tpSelect.select2('destroy');
                        }
                        tpSelect.empty();
                        tpSelect.select2($.extend({}, select2Options, {
                            data: [{ id: 'OTRO', text: 'CREAR DEFECTO (Error al cargar)', action: true }]
                        }));
                        defectosDataLoaded = true; // Marcar como intentado para no reintentar en cada open
                        tpSelect.select2('open');
                    });
                }
            });

            // Evento al seleccionar una opción (sin cambios)
            tpSelect.on('select2:select', function (e) {
                const selected = e.params.data;
                if (selected.id === 'OTRO') {
                    $('#nuevoConceptoModal').modal('show');
                    // Resetear el select para evitar que quede seleccionado "CREAR DEFECTO"
                    // Es importante hacerlo después de que el modal se muestre para evitar que se cierre
                    // y también para que la próxima vez que se abra, se pueda volver a seleccionar.
                    // Considera si es mejor limpiar la selección o no.
                    // Si lo limpias, y el usuario cierra el modal sin crear, el select queda vacío.
                    // Podrías resetearlo dentro del 'shown.bs.modal' o al cerrar el modal.
                    // Por ahora, lo dejamos así para que la opción "OTRO" no persista.
                    tpSelect.val(null).trigger('change');
                    return;
                }
                addOptionToContainer(selected.id, selected.text);
                // Limpiar la selección del Select2 después de añadir al contenedor
                // para que el usuario pueda seguir añadiendo más defectos sin tener que borrar el anterior.
                tpSelect.val(null).trigger('change');
            });

            // Función para agregar la opción seleccionada al contenedor (sin cambios)
            function addOptionToContainer(id, text) {
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border p-2 mb-1" data-id="${id}">
                        <button class="btn btn-primary btn-sm duplicate-option" title="Duplicar defecto">+</button>
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button class="btn btn-danger btn-sm remove-option" title="Eliminar defecto">Eliminar</button>
                    </div>
                `);

                optionElement.find('.duplicate-option').on('click', function () {
                    addOptionToContainer(id, text); // Duplica este mismo elemento
                });

                optionElement.find('.remove-option').on('click', function () {
                    optionElement.remove();
                });

                selectedOptionsContainer.append(optionElement);
            }

            // Evento para guardar un nuevo defecto desde el modal (sin cambios significativos, pero revisa el success)
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
                        // Añadir la nueva opción al Select2 y seleccionarla (opcional)
                        // O simplemente añadirla al contenedor y actualizar la lista de datos de Select2 si es necesario
                        // para que esté disponible en futuras búsquedas locales.

                        // Para que el nuevo defecto esté disponible en el Select2 para búsquedas locales:
                        if (defectosDataLoaded && tpSelect.data('select2')) {
                            const currentData = tpSelect.select2('data');
                            // Verificar si ya existe para evitar duplicados visuales si algo falló
                            const exists = currentData.some(opt => opt.id === data.nombre);
                            if (!exists) {
                                // Crear la nueva opción para Select2
                                const newSelectOption = { id: data.nombre, text: data.nombre };
                                // Insertar después de "CREAR DEFECTO"
                                currentData.splice(1, 0, newSelectOption); 
                                
                                // Actualizar Select2 (destruir y recrear)
                                tpSelect.select2('destroy').empty();
                                tpSelect.select2($.extend({}, select2Options, { data: currentData }));
                            }
                        } else {
                            // Si los datos no se habían cargado, la próxima vez que se abra se incluirá (si está en la BD)
                            // O podrías forzar una recarga o añadirlo a `allDefectosData` si lo estuvieras usando más globalmente.
                            // Por ahora, la forma más simple es que se recargue la próxima vez o se asuma que la BD lo tiene.
                            // Para una mejor UX, añadirlo dinámicamente a `allDefectosData` y re-renderizar Select2 es lo ideal.
                        }

                        addOptionToContainer(data.nombre, data.nombre); // Añade al contenedor de abajo
                        $('#nuevoConceptoModal').modal('hide');
                        $('#nuevoConceptoInput').val('');
                        
                        // Opcional: Seleccionar el nuevo defecto en el Select2 si se desea
                        // tpSelect.val(data.nombre).trigger('change'); 
                        // tpSelect.trigger({ type: 'select2:select', params: { data: {id: data.nombre, text: data.nombre} }});

                    },
                    error: function (xhr) {
                        let errorMessage = 'Ocurrió un error al guardar el defecto.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        }
                        alert(errorMessage);
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

                // Enviar datos mediante AJAX
                Swal.fire({
                    title: 'Guardando...',
                    text: 'Por favor, espera.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
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

                $.ajax({
                    url: "{{ route('procesoV3.registro.obtenerRegistroDia') }}",
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
                        // $("#loadingIndicator").hide();
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
                    const ahora = new Date();

                    $.each(registros, function (index, registro) {
                        let paroHtml = "-";
                        let claseFilaAdicional = '';
                        const paroEstaActivo = registro.inicio_paro && !registro.fin_paro;

                        if (paroEstaActivo) {
                            let urlFinalizarParo = `/auditoriaProcesoV3/registro/finalizar-paro/${registro.id}`;
                            paroHtml = `<button class="btn btn-primary btn-sm fin-paro-btn" data-id="${registro.id}" data-url="${urlFinalizarParo}" data-tipo="${tipo}">
                                            Fin Paro Proceso
                                        </button>`;
                            if (registro.created_at) {
                                const horaCreacionRegistro = new Date(registro.created_at);
                                if (!isNaN(horaCreacionRegistro.getTime())) {
                                    const diffMs = ahora.getTime() - horaCreacionRegistro.getTime();
                                    const diffMins = Math.floor(diffMs / 60000);
                                    if (diffMins >= 10 && diffMins <= 15) {
                                        claseFilaAdicional = 'paro-advertencia';
                                    } else if (diffMins > 15) {
                                        claseFilaAdicional = 'paro-critico';
                                    }
                                } else {
                                    console.warn(`Fecha created_at inválida para el registro ID: ${registro.id}`, registro.created_at);
                                }
                            }
                        } else if (registro.inicio_paro && registro.fin_paro) {
                            paroHtml = registro.minutos_paro !== null ? registro.minutos_paro : 'Calculando...';
                        }
                        
                        let urlEliminar = `/auditoriaProcesoV3/registro/eliminar/${registro.id}`; // Asegúrate que esta URL sea correcta para tu ruta DELETE

                        let fila = `
                            <tr class="${claseFilaAdicional}">
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

            // --- Funciones para actualizar estadísticas ---
            function actualizarTotalGeneralNormal(totalAuditada, totalRechazada) {
                let porcentajeRechazo = totalAuditada > 0 ? ((totalRechazada / totalAuditada) * 100).toFixed(2) : "0.00";
                $("#total_auditada_general").val(totalAuditada);
                $("#total_rechazada_general").val(totalRechazada);
                $("#total_porcentaje_general").val(porcentajeRechazo);
            }
            function actualizarTablaIndividualNormal(registrosAgrupados) {
                let tbody = $(".table-total-individual tbody");
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
                let tbody = $(".table-total-individual-tiempo-extra tbody");
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
                    type: "POST",
                    success: function(response) {
                        if (response.minutos_paro !== undefined) {
                            // MODIFICACIÓN: Quitar clases de resaltado al finalizar paro
                            boton.closest("tr").removeClass('paro-advertencia paro-critico');
                            boton.closest("td").text(response.minutos_paro);
                            Swal.fire('¡Éxito!', `Paro finalizado (${tipoRegistro}). Duración: ${response.minutos_paro} minutos.`, 'success');
                            // OPCIONAL: Si finalizar un paro afecta las estadísticas de alguna manera (ej. tiempo total de paro),
                            // podrías llamar a cargarTablasRegistros() aquí también.
                            // cargarTablasRegistros(); 
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
                let url = boton.data("url");
                let tipoRegistro = boton.data("tipo");
                let registroId = boton.data("id");

                Swal.fire({
                    title: `¿Eliminar Registro (${tipoRegistro})?`,
                    text: "Esta acción no se puede deshacer.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        boton.prop('disabled', true).text('Eliminando...');
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "json",
                            success: function(response) {
                                if (response.message) {
                                    Swal.fire('¡Eliminado!', `Registro ${tipoRegistro} eliminado: ${response.message}`, 'success');
                                    boton.closest("tr").fadeOut(400, function() {
                                        $(this).remove();
                                        // *** PUNTO CLAVE: Actualizar las tablas de estadísticas ***
                                        // Llamar a cargarTablasRegistros() recargará todos los datos y
                                        // reconstruirá las tablas, incluyendo las de estadísticas.
                                        cargarTablasRegistros();
                                    });
                                } else if (response.warning) {
                                    Swal.fire('Advertencia', response.warning, 'warning');
                                    boton.prop('disabled', false).text('Eliminar');
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
                                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMsg = `Error: ${xhr.responseJSON.message}`;
                                } else if (xhr.status === 0) {
                                    errorMsg = "No se pudo conectar con el servidor. Verifique su conexión de red.";
                                } else if (xhr.status === 404) {
                                    errorMsg = "Recurso no encontrado en el servidor (Error 404). Verifique la URL.";
                                } else if (xhr.status === 500) {
                                    errorMsg = "Error interno del servidor (Error 500).";
                                }
                                Swal.fire('Error', errorMsg, 'error');
                                boton.prop('disabled', false).text('Eliminar');
                            }
                        });
                    }
                });
            });

            // --- CARGA INICIAL AL ENTRAR A LA PÁGINA ---
            cargarTablasRegistros();

            // --- OPCIONAL: RECARGAR SI CAMBIA EL MÓDULO ---
            $('#modulo').on('change', function() {
                cargarTablasRegistros();
            });
        });

    </script>

    <script>
        $(document).ready(function () {
            let datosCargados = false;
        
            // Cuando se abra el acordeón para cargar paros no finalizados
            $('#collapseParos').on('show.bs.collapse', function () {
                if (!datosCargados) {
                    const modulo = $('#paros-container').data('modulo');
        
                    $.ajax({
                        url: '/auditoriaProcesoV3/registro/paros-no-finalizados',  // Nuevo endpoint para paros no finalizados
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
                                                    <th>Nombre</th>
                                                    <th>Operacion</th>
                                                    <th>Inicio Paro</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                `;
                                response.forEach(item => {
                                    // Para cada registro, si fin_paro es nulo, se muestra botón para finalizar
                                    let paroHtml = "";
        
                                    contenido += `
                                        <tr>
                                            <td>${item.nombre}</td>
                                            <td>${item.operacion}</td>
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
                    url: '/auditoriaProcesoV3/registro/finalizar-paro-proceso-despues', // Nuevo endpoint (ver sección de Controller)
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
