@extends('layouts.app', ['pageSlug' => 'Plancha', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Plancha </h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Selector de Fecha --}}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5 d-flex align-items-center">
                        <div class="form-group w-100">
                            <label for="fecha_busqueda" class="form-label">Seleccionar Fecha:</label>
                            <input type="date" class="form-control" id="fecha_busqueda" name="fecha_busqueda">
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="form-group">
                            <button type="button" class="btn btn-secondary" id="btnMostrarDatos">Mostrar datos</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tabla-screen-strart">
                        <thead class="thead-primary">
                            <tr>
                                <th>Gran total revisado</th>
                                <th>Gran total de defectos</th>
                                <th>Porcentaje de defectos</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tabla-screen">
                        <thead class="thead-primary">
                            <tr>
                                <th>OP</th>
                                <th>Panel</th>
                                <th>Máquina</th>
                                <th>Técnicas</th> <!-- Nueva columna -->
                                <th>Fibras</th> <!-- Nueva columna -->
                                <th>Gráfica</th>
                                <th>Cliente</th>
                                <th>Estilo</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                                <th>Técnico Screen</th>
                                <th>Defectos</th>
                                <th>Acción Correctiva</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se insertarán los datos dinámicos con AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 10px;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
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
            color: #ffffff !important; 
            background-color: #4bce67 !important; /* Verde más claro */
            border-color: #4bce67 !important;
            cursor: not-allowed !important; /* Cursor de "prohibido" */
            opacity: 0.6; /* Reduce opacidad */
            box-shadow: none !important; /* Elimina sombra */
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
        
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333;                 /* Color del texto */
        }
        .texto-blanco {
            color: white !important;
        }
        /* Ajusta Select2 dentro de las celdas de la tabla */
        td .select2-container {
            width: 100% !important;
        }

        /* Corrige el padding para que el Select2 no sobresalga */
        td .select2-selection {
            height: 100% !important;
            padding: 4px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Asegurar que las celdas no se agranden demasiado */
        .table td {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Si usas un tema oscuro, cambia los colores del Select2 */
        .select2-container--default .select2-selection--single {
            background-color: #1e1e1e; /* Color de fondo oscuro */
            color: #ffffff; /* Texto blanco */
            border: 1px solid #444; /* Borde más discreto */
        }

        /* Estilos base para el contenedor del checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
            cursor: pointer;
        }

        /* Ocultar el checkbox original */
        .form-check input[type="checkbox"] {
            display: none;
        }

        /* Crear el checkbox personalizado */
        .form-check label {
            position: relative;
            padding-left: 30px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-check label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border: 2px solid #333;
            border-radius: 4px;
            background-color: #fff;
            transition: all 0.3s;
        }

        /* Icono de la palomita cuando está marcado */
        .form-check input[type="checkbox"]:checked + label::before {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .form-check input[type="checkbox"]:checked + label::after {
            content: "✔";
            position: absolute;
            left: 5px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: white;
            font-weight: bold;
        }
    </style>

    <script>
        $(document).ready(function() {
            // 1. Establecer la fecha actual por defecto en el input
            var hoy = new Date();
            var dia = ("0" + hoy.getDate()).slice(-2);
            var mes = ("0" + (hoy.getMonth() + 1)).slice(-2); // getMonth() es 0-indexed
            var fechaActual = hoy.getFullYear() + "-" + mes + "-" + dia;
            $('#fecha_busqueda').val(fechaActual);

            // 2. Cargar datos con la fecha actual al iniciar la página
            cargarDatosPorFechaSeleccionada();

            // 3. Evento para el botón "Mostrar datos"
            $('#btnMostrarDatos').on('click', function() {
                cargarDatosPorFechaSeleccionada();
            });
        });

        // Función unificada para cargar todos los datos según la fecha
        function cargarDatosPorFechaSeleccionada() {
            var fechaSeleccionada = $('#fecha_busqueda').val();

            // Validar que haya una fecha seleccionada (aunque por defecto siempre la habrá)
            if (!fechaSeleccionada) {
                // Si por alguna razón no hay fecha (no debería pasar con la inicialización),
                // puedes optar por no hacer nada, mostrar un error, o usar la fecha actual nuevamente.
                // Aquí, por robustez, re-establecemos la fecha actual si está vacía.
                var hoy = new Date();
                var dia = ("0" + hoy.getDate()).slice(-2);
                var mes = ("0" + (hoy.getMonth() + 1)).slice(-2);
                fechaSeleccionada = hoy.getFullYear() + "-" + mes + "-" + dia;
                $('#fecha_busqueda').val(fechaSeleccionada);
            }
            
            // Mostrar mensaje de carga en las tablas
            $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Cargando datos...</td></tr>');
            $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Cargando datos...</td></tr>');

            // Llamar a las funciones que cargan los datos, pasando la fecha
            cargarRegistros(fechaSeleccionada);
            cargarDatosEstadisticos(fechaSeleccionada);
        }

        // Modificada para aceptar y enviar la fecha
        function cargarRegistros(fecha) {
            $.ajax({
                url: "{{ route('planchaV2.data') }}",
                method: "GET",
                data: { fecha: fecha }, // Enviar la fecha seleccionada al backend
                dataType: "json",
                success: function(data) {
                    let tabla = "";
                    if (data && data.length > 0) {
                        data.forEach(registro => {
                            tabla += `<tr>
                                <td>${registro.op !== null && registro.op !== undefined ? registro.op : 'N/A'}</td>
                                <td>${registro.panel !== null && registro.panel !== undefined ? registro.panel : 'N/A'}</td>
                                <td>${registro.maquina !== null && registro.maquina !== undefined ? registro.maquina : 'N/A'}</td>
                                <td>${registro.tecnicas !== null && registro.tecnicas !== undefined ? registro.tecnicas : 'N/A'}</td>
                                <td>${registro.fibras !== null && registro.fibras !== undefined ? registro.fibras : 'N/A'}</td>
                                <td>${registro.grafica !== null && registro.grafica !== undefined ? registro.grafica : 'N/A'}</td>
                                <td>${registro.cliente !== null && registro.cliente !== undefined ? registro.cliente : 'N/A'}</td>
                                <td>${registro.estilo !== null && registro.estilo !== undefined ? registro.estilo : 'N/A'}</td>
                                <td>${registro.color !== null && registro.color !== undefined ? registro.color : 'N/A'}</td>
                                <td>${registro.cantidad !== null && registro.cantidad !== undefined ? registro.cantidad : '0'}</td>
                                <td>${registro.tecnico_screen !== null && registro.tecnico_screen !== undefined ? registro.tecnico_screen : 'N/A'}</td>
                                <td>${registro.defectos !== null && registro.defectos !== undefined ? registro.defectos : 'Sin defectos'}</td>
                                <td>${registro.accion_correctiva !== null && registro.accion_correctiva !== undefined ? registro.accion_correctiva : 'N/A'}</td>
                            </tr>`;
                        });
                    } else {
                        tabla = '<tr><td colspan="13" class="text-center">No se encontraron registros para la fecha seleccionada.</td></tr>';
                    }
                    $("#tabla-screen tbody").html(tabla);
                },
                error: function(error) {
                    console.error("Error al obtener datos de registros:", error);
                    $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Error al cargar los datos. Revise la consola.</td></tr>');
                }
            });
        }

        // Modificada para aceptar y enviar la fecha
        function cargarDatosEstadisticos(fecha) {
            $.ajax({
                url: "{{ route('planchaV2.strart') }}", // Revisar si 'strart' es correcto o debería ser 'stats'
                method: "GET",
                data: { fecha: fecha }, // Enviar la fecha seleccionada al backend
                dataType: "json",
                success: function (data) {
                    let fila = "";
                    if (data) { // Asumiendo que el backend siempre devuelve un objeto
                        fila = `
                            <tr>
                                <td>${data.cantidad_total_revisada}</td>
                                <td>${data.cantidad_defectos}</td>
                                <td>${data.porcentaje_defectos} %</td>
                            </tr>
                        `;
                    } else { // Este caso es menos probable si el backend siempre responde con la estructura JSON
                        fila = '<tr><td colspan="3" class="text-center">No se pudieron cargar los datos estadísticos.</td></tr>';
                    }
                    $("#tabla-screen-strart tbody").html(fila);
                },
                error: function (error) {
                    console.error("Error al obtener datos estadísticos:", error);
                    $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Error al cargar datos estadísticos. Revise la consola.</td></tr>');
                }
            });
        }
    </script>

@endsection