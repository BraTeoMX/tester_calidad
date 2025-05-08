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
            {{-- Contenedor donde se insertarán las tablas dinámicamente --}}
            <div id="contenedor-tablas-maquinas">
                <div class="card card-body">
                    <p class="text-center">Seleccione una fecha y presione "Mostrar Datos" para ver los reportes por máquina.</p>
                </div>
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
            const COLUMNAS_TABLA = 15; // Auditor, Bulto, OP, ..., Hora Registro (sin Maquina)

            function cargarReportePorDia(fechaSeleccionada) {
                if (!fechaSeleccionada) {
                    alert("Por favor, seleccione una fecha.");
                    $("#contenedor-tablas-maquinas").html(`
                        <div class="card card-body">
                            <p class="text-center text-danger">Error: Fecha no proporcionada.</p>
                        </div>
                    `);
                    return;
                }

                // Muestra un indicador de carga general
                $("#contenedor-tablas-maquinas").html(`
                    <div class="card card-body">
                        <p class="text-center">Cargando datos... <i class="fas fa-spinner fa-spin"></i></p>
                    </div>
                `);

                $.ajax({
                    url: '{{ route("reportesScreen.datosPorDia") }}',
                    method: 'GET',
                    data: {
                        fecha_inicio: fechaSeleccionada,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        var contenedor = $("#contenedor-tablas-maquinas");
                        contenedor.empty(); // Limpiar el contenedor

                        if (response.dataGroupedByMachine && Object.keys(response.dataGroupedByMachine).length > 0) {
                            // Iterar sobre cada máquina en la respuesta
                            $.each(response.dataGroupedByMachine, function(nombreMaquina, registrosMaquina) {
                                var cardHtml = `
                                    <div class="card mt-3">
                                        <div class="card-header card-header-info">
                                            <h4 class="card-title mb-0">Máquina: ${$('<div>').text(nombreMaquina).html()}</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
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
                                                            <th>Gráfica</th>
                                                            <th>Técnicas</th>
                                                            <th>Fibras</th>
                                                            <th>Técnico Screen</th>
                                                            <th>Defectos Screen</th>
                                                            <th>Técnico Plancha</th>
                                                            <th>Defectos Plancha</th>
                                                            <th>Hora Registro</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>`;

                                if (registrosMaquina && registrosMaquina.length > 0) {
                                    $.each(registrosMaquina, function(index, registro) {
                                        cardHtml += `<tr>
                                            <td>${$('<div>').text(registro.auditor).html()}</td>
                                            <td>${$('<div>').text(registro.bulto).html()}</td>
                                            <td>${$('<div>').text(registro.op).html()}</td>
                                            <td>${$('<div>').text(registro.cliente).html()}</td>
                                            <td>${$('<div>').text(registro.estilo).html()}</td>
                                            <td>${$('<div>').text(registro.color).html()}</td>
                                            <td>${$('<div>').text(registro.cantidad).html()}</td>
                                            <td>${$('<div>').text(registro.panel).html()}</td>
                                            <td>${$('<div>').text(registro.grafica).html()}</td>
                                            <td>${registro.tecnicas}</td> <td>${registro.fibras}</td>   <td>${$('<div>').text(registro.tecnico_screen).html()}</td>
                                            <td>${registro.screenDefectos}</td> <td>${$('<div>').text(registro.tecnico_plancha).html()}</td>
                                            <td>${registro.planchaDefectos}</td> <td>${$('<div>').text(registro.fecha).html()}</td>
                                        </tr>`;
                                    });
                                } else {
                                    cardHtml += `<tr><td colspan="${COLUMNAS_TABLA}" class="text-center">No hay registros para esta máquina en la fecha seleccionada.</td></tr>`;
                                }

                                cardHtml += `</tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>`;
                                contenedor.append(cardHtml);
                            });
                        } else {
                            contenedor.html(`
                                <div class="card card-body">
                                    <p class="text-center">No se encontraron registros para la fecha seleccionada.</p>
                                </div>
                            `);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        var contenedor = $("#contenedor-tablas-maquinas");
                        contenedor.html(`
                            <div class="card card-body">
                                <p class="text-center text-danger">Error al cargar los registros. Verifique la consola para más detalles.</p>
                            </div>
                        `);
                        console.error("Error AJAX:", textStatus, errorThrown, jqXHR.responseText);
                        alert("Error al cargar los registros del día. " + (jqXHR.responseJSON && jqXHR.responseJSON.message ? jqXHR.responseJSON.message : errorThrown));
                    }
                });
            }

            $("#btnMostrarDatos").click(function() {
                var fechaSeleccionada = $("#fecha_reporte").val();
                cargarReportePorDia(fechaSeleccionada);
            });

            // Opcional: Cargar datos para la fecha por defecto (hoy) al cargar la página
            // var fechaInicial = $("#fecha_reporte").val();
            // if(fechaInicial) {
            //     cargarReportePorDia(fechaInicial);
            // }
        });
    </script>
@endsection