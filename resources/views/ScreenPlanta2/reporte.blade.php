@extends('layouts.app', ['pageSlug' => 'HornoReporte', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="card-title" style="text-align: center; font-weight: bold;">Reporte Screen</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 d-flex align-items-center"> {{-- Ajustado para dar más espacio al input y menos al botón --}}
                <div class="form-group w-100">
                    <label for="fecha_reporte" class="form-label">Seleccionar Fecha del Reporte</label>
                    <input type="date" class="form-control" id="fecha_reporte" name="fecha_reporte" value="{{ now()->format('Y-m-d') }}" required> {{-- Valor por defecto: hoy --}}
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-end"> {{-- Alineado al final para el botón --}}
                <div class="form-group">
                    <label class="d-block">&nbsp;</label> <button type="button" class="btn btn-secondary w-100" id="btnMostrarDatos">Mostrar Datos</button>
                </div>
            </div>
        </div>

        <div class="card card-body">
            {{-- Tabla para mostrar los registros --}}
            <div class="table-responsive">
                <h3>Registros del Día Seleccionado</h3>
                <table class="table table-striped" id="tabla-reporte-screen">
                    <thead class="thead-primary">
                        <tr>
                            <th>Auditor</th>
                            <th>Bulto</th>
                            <th>OP</th>
                            <th>Cliente</th>
                            <th>Estilo</th>
                            <th>Color</th>
                            <th>Cantidad</th>
                            <th>Panel</th>
                            <th>Maquina</th>
                            <th>Grafica</th>
                            <th>Técnicas</th>
                            <th>Fibras</th>
                            <th>Tecnico Screen</th>
                            <th>Defectos Screen</th>
                            <th>Tecnico Plancha</th>
                            <th>Defectos Plancha</th>
                            <th>Hora Registro</th> {{-- Cambiado de Fecha a Hora Registro --}}
                            {{-- Se elimina la columna de Acción --}}
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Aquí se insertarán los registros vía AJAX --}}
                        <tr>
                            <td colspan="16" class="text-center">Seleccione una fecha y presione "Mostrar Datos".</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333;                 /* Color del texto */
        }
        .texto-blanco {
            color: white !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Función para cargar los registros del día seleccionado vía AJAX
            function cargarReportePorDia(fechaSeleccionada) {
                if (!fechaSeleccionada) {
                    alert("Por favor, seleccione una fecha.");
                    $("#tabla-reporte-screen tbody").html('<tr><td colspan="16" class="text-center">Error: Fecha no proporcionada.</td></tr>');
                    return;
                }
    
                // Muestra un indicador de carga
                $("#tabla-reporte-screen tbody").html('<tr><td colspan="16" class="text-center">Cargando datos... <i class="fas fa-spinner fa-spin"></i></td></tr>');
    
    
                $.ajax({
                    url: '{{ route("reportesScreen.datosPorDia") }}', // Ruta actualizada
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaSeleccionada, // Enviar la fecha seleccionada
                        _token: '{{ csrf_token() }}' // CSRF token para peticiones GET no es estrictamente necesario, pero no hace daño
                    },
                    dataType: 'json',
                    success: function(response) {
                        var tbody = $("#tabla-reporte-screen tbody");
                        tbody.empty(); // Limpiar la tabla
    
                        if (response.data && response.data.length > 0) {
                            // Iterar sobre cada registro y construir la fila
                            $.each(response.data, function(index, registro) {
                                var row = "<tr>";
                                row += "<td>" + registro.auditor + "</td>";
                                row += "<td>" + registro.bulto + "</td>";
                                row += "<td>" + registro.op + "</td>";
                                row += "<td>" + registro.cliente + "</td>";
                                row += "<td>" + registro.estilo + "</td>";
                                row += "<td>" + registro.color + "</td>";
                                row += "<td>" + registro.cantidad + "</td>";
                                row += "<td>" + registro.panel + "</td>";
                                row += "<td>" + registro.maquina + "</td>";
                                row += "<td>" + registro.grafica + "</td>";
                                row += "<td>" + registro.tecnicas + "</td>";
                                row += "<td>" + registro.fibras + "</td>";
                                row += "<td>" + registro.tecnico_screen + "</td>";
                                row += "<td>" + registro.screenDefectos + "</td>";
                                row += "<td>" + registro.tecnico_plancha + "</td>";
                                row += "<td>" + registro.planchaDefectos + "</td>";
                                row += "<td>" + registro.fecha + "</td>";
                                row += "</tr>";
                                tbody.append(row);
                            });
                        } else {
                            tbody.html('<tr><td colspan="16" class="text-center">No se encontraron registros para la fecha seleccionada.</td></tr>');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var tbody = $("#tabla-reporte-screen tbody");
                        tbody.html('<tr><td colspan="16" class="text-center">Error al cargar los registros. Verifique la consola para más detalles.</td></tr>');
                        console.error("Error AJAX:", textStatus, errorThrown, jqXHR.responseText);
                        alert("Error al cargar los registros del día. " + (jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : errorThrown) );
                    }
                });
            }
    
            // Evento click para el botón "Mostrar Datos"
            $("#btnMostrarDatos").click(function() {
                var fechaSeleccionada = $("#fecha_reporte").val();
                cargarReportePorDia(fechaSeleccionada);
            });
        });
    </script>
@endsection