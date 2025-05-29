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
                    {{-- NUEVO BOTÓN PARA EXPORTAR --}}
                    <div class="col-md-3 d-flex align-items-end"> {{-- Ajustado a col-md-3 --}}
                        <div class="form-group">
                            <button type="button" class="btn btn-success" id="btnExportarExcel"> {{-- Color verde (success) --}}
                                <i class="fa fa-file-excel-o"></i> Exportar a Excel {{-- Ejemplo con FontAwesome --}}
                            </button>
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
                                <th>Auditor</th>
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
            $('#btnMostrarDatos').on('click', function() {
                cargarDatosPorFechaSeleccionada();
            });

            // Opcional: Mensajes iniciales en las tablas para indicar que se debe seleccionar una fecha
            $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Seleccione una fecha y presione "Mostrar datos".</td></tr>');
            $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Seleccione una fecha y presione "Mostrar datos".</td></tr>');
        });

        // === NUEVO: MANEJADOR PARA EL BOTÓN DE EXPORTAR EXCEL ===
        $('#btnExportarExcel').on('click', function() {
            var fechaSeleccionada = $('#fecha_busqueda').val();

            if (!fechaSeleccionada) {
                alert("Por favor, seleccione una fecha para exportar los datos.");
                return; // No continuar si no hay fecha
            }

            var urlExportar = "{{ route('planchaV2.exportarExcel') }}?fecha=" + fechaSeleccionada;

            // Redirigir a la URL de exportación. El backend se encargará de generar y enviar el archivo.
            window.location.href = urlExportar;
        });
        // === FIN DEL NUEVO MANEJADOR ===

        // Función unificada para cargar todos los datos según la fecha
        function cargarDatosPorFechaSeleccionada() {
            var fechaSeleccionada = $('#fecha_busqueda').val();

            // Si el campo de fecha está vacío al hacer clic, se alerta al usuario y no se procede.
            if (!fechaSeleccionada) {
                alert("Por favor, seleccione una fecha para continuar.");
                // Aseguramos que las tablas muestren el mensaje inicial si se intenta cargar sin fecha
                $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Seleccione una fecha y presione "Mostrar datos".</td></tr>');
                $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Seleccione una fecha y presione "Mostrar datos".</td></tr>');
                return; // Salir de la función si no hay fecha.
            }
            
            // Mensaje de carga para la tabla de estadísticas
            $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Cargando datos estadísticos...</td></tr>');
            // La tabla principal (#tabla-screen) mostrará su propio mensaje dentro de cargarRegistros

            // Llamar a las funciones que cargan los datos, pasando la fecha
            cargarRegistros(fechaSeleccionada);
            cargarDatosEstadisticos(fechaSeleccionada);
        }

        function cargarRegistros(fecha) {
            // Asumimos que la tabla principal sigue teniendo el ID "tabla-screen"
            $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Cargando registros para la fecha ' + fecha + '...</td></tr>');

            $.ajax({
                url: "{{ route('planchaV2.data') }}", // Ruta para los datos de plancha
                method: "GET",
                data: { fecha: fecha }, 
                dataType: "json",
                success: function(data) {
                    let tablaContenido = "";
                    if (data && data.length > 0) {
                        data.forEach(registro => {
                            tablaContenido += `<tr>
                                <td>${registro.op}</td>
                                <td>${registro.auditor}</td>
                                <td>${registro.panel}</td>
                                <td>${registro.maquina}</td>
                                <td>${registro.tecnicas}</td>
                                <td>${registro.fibras}</td>
                                <td>${registro.grafica}</td>
                                <td>${registro.cliente}</td>
                                <td>${registro.estilo}</td>
                                <td>${registro.color}</td>
                                <td>${registro.cantidad}</td> 
                                <td>${registro.tecnico_screen}</td>
                                <td>${registro.defectos}</td>
                                <td>${registro.accion_correctiva}</td>
                            </tr>`;
                        });
                    } else {
                        tablaContenido = '<tr><td colspan="13" class="text-center">No se encontraron registros para la fecha seleccionada.</td></tr>';
                    }
                    // Rellenar el tbody con el nuevo contenido
                    $("#tabla-screen tbody").html(tablaContenido);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error al obtener datos de registros (planchaV2.data):", textStatus, errorThrown, jqXHR.responseText);
                    // Ya no se destruye la instancia de DataTable en caso de error
                    // if (tablaPlanchaInstance) { ... } // Eliminado
                    $("#tabla-screen tbody").html('<tr><td colspan="13" class="text-center">Error al cargar los datos. Revise la consola.</td></tr>');
                }
            });
        }

        // Modificada para aceptar y enviar la fecha (para la tabla de estadísticas)
        // Esta función no parecía usar DataTables, así que los cambios son menores.
        function cargarDatosEstadisticos(fecha) {
            $.ajax({
                url: "{{ route('planchaV2.strart') }}", // Ruta para las estadísticas de plancha
                method: "GET",
                data: { fecha: fecha }, 
                dataType: "json",
                success: function (data) {
                    let fila = "";
                    if (data) {
                        // Aseguramos que los campos nulos o undefined se muestren como '0' o '0.00 %'
                        const cantidadTotalRevisada = data.cantidad_total_revisada !== null && data.cantidad_total_revisada !== undefined ? data.cantidad_total_revisada : '0';
                        const cantidadDefectos = data.cantidad_defectos !== null && data.cantidad_defectos !== undefined ? data.cantidad_defectos : '0';
                        // parseFloat y toFixed ya manejan bien los números, pero la comprobación inicial es buena.
                        const porcentajeDefectos = data.porcentaje_defectos !== null && data.porcentaje_defectos !== undefined ? parseFloat(data.porcentaje_defectos).toFixed(2) : '0.00';

                        fila = `
                            <tr>
                                <td>${cantidadTotalRevisada}</td>
                                <td>${cantidadDefectos}</td>
                                <td>${porcentajeDefectos} %</td>
                            </tr>
                        `;
                    } else {
                        fila = '<tr><td colspan="3" class="text-center">No se pudieron cargar los datos estadísticos.</td></tr>';
                    }
                    // Asumimos que la tabla de estadísticas sigue teniendo el ID "tabla-screen-strart"
                    $("#tabla-screen-strart tbody").html(fila);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.error("Error al obtener datos estadísticos (planchaV2.strart):", textStatus, errorThrown, jqXHR.responseText);
                    $("#tabla-screen-strart tbody").html('<tr><td colspan="3" class="text-center">Error al cargar datos estadísticos. Revise la consola.</td></tr>');
                }
            });
        }
    </script>

@endsection