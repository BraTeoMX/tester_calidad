@extends('layouts.app', ['pageSlug' => 'busquedaOP', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard busqueda OP</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-body">
        <div class="row mb-4 align-items-end">
            <div class="col-md-4">
                <input type="text" id="inputBusqueda" class="form-control" placeholder="Ingrese búsqueda...">
            </div>
    
            <div class="col-md-4">
                <select id="tipoBusqueda" class="form-control">
                    <option value="op" selected>Por OP</option>
                    <option value="estilo">Por Estilo</option>
                    <option value="color">Por Color</option>
                </select>
            </div>
    
            <div class="col-md-2">
                <button class="btn btn-success" id="btnBuscar">Buscar</button>
            </div>
        </div>
    
        <div class="table-responsive" id="tablaResultados" style="display: none;">
            <table class="table custom-table" id="tablaDatos">
                <thead>
                    <tr>
                        <th>OP</th>
                        <th>Bulto</th>
                        <th>Auditor</th>
                        <th>Módulo</th>
                        <th>Cliente</th>
                        <th>Estilo</th>
                        <th>Color</th>
                        <th>Planta</th>
                        <th>piezas</th>
                        <th>Cantidad Auditada</th>
                        <th>Cantidad Rechazada</th>
                        <th>Defectos</th>
                        <th>Operario</th>
                        <th>% AQL</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <style>
        /* Contenedor para centrar el texto */
        .loading-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Texto animado */
        .loading-text {
            font-size: 18px;
            font-weight: bold;
            color: #d1d1d1; /* Color para tema oscuro */
            
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centrar exactamente */
            
            animation: fadeInOut 1.5s infinite;
        }

        /* Animación de parpadeo */
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

    </style>
    <style>
        .custom-body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .custom-card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .custom-card-header {
            background-color: #2e7d32;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .custom-card-body {
            padding: 15px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #333;
        }

        .custom-table th {
            background-color: #2e2e2e;
        }

        .custom-btn {
            background-color: transparent;
            border: none;
            color: #4caf50;
            cursor: pointer;
            text-decoration: underline;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999; /* Asegura que está por encima de todo */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding-top: 60px; /* Espacio superior */
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
            pointer-events: auto; /* Muy importante */
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }

        .custom-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: fixed;
            right: 25px;
            top: 15px;
        }

        .custom-close:hover,
        .custom-close:focus {
            color: #fff;
        }

        .custom-modal-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #2e2e2e;
            padding: 15px;
            z-index: 1001;
        }

        .custom-modal-body {
            margin-top: 70px;
            /* Ajusta este valor según la altura de tu encabezado */
            padding: 15px;
        }
    </style>

    <!-- JavaScript -->
    <!-- DataTables CSS desde carpeta local -->
    <link rel="stylesheet" href="{{ asset('dataTable/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dataTable/css/buttons.bootstrap5.min.css') }}">

    <!-- jQuery y DataTables desde local -->
    <script src="{{ asset('dataTable/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Botones para exportar -->
    <script src="{{ asset('dataTable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('dataTable/js/buttons.html5.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#btnBuscar').on('click', function () {
                let termino = $('#inputBusqueda').val();
                let tipo = $('#tipoBusqueda').val();

                if (!termino.trim()) {
                    alert('Ingrese un término de búsqueda.');
                    return;
                }

                $.ajax({
                    url: "{{ route('busqueda_OP.buscarGeneral') }}",
                    method: 'POST',
                    data: {
                        tipo: tipo,
                        termino: termino,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        $('#tablaResultados').hide();
                        $('#tablaDatos tbody').html('<tr><td colspan="11" class="text-center loading-text">Buscando...</td></tr>');
                    },
                    success: function (response) {
                        if ($.fn.DataTable.isDataTable('#tablaDatos')) {
                            $('#tablaDatos').DataTable().clear().destroy();
                        }

                        if (response.ops) {
                            // Caso estilo o color: mostrar lista de OPs
                            let filas = '';
                            response.ops.forEach(function (op) {
                                filas += `<tr><td colspan="11"><a href="#" class="op-link">${op}</a></td></tr>`;
                            });

                            $('#tablaDatos tbody').html(filas);
                            $('#tablaResultados').show();

                            // Al dar clic sobre una OP encontrada, buscarla como OP
                            $('.op-link').on('click', function(e){
                                e.preventDefault();
                                $('#inputBusqueda').val($(this).text());
                                $('#tipoBusqueda').val('op');
                                $('#btnBuscar').click();
                            });

                        } else if (response.resultados.length === 0) {
                            $('#tablaDatos tbody').html('<tr><td colspan="11">No se encontraron resultados.</td></tr>');
                        } else {
                            let filas = '';
                            response.resultados.forEach(function (dato) {
                                filas += `<tr>
                                    <td>${dato.op}</td>
                                    <td>${dato.bulto}</td>
                                    <td>${dato.auditor}</td>
                                    <td>${dato.modulo}</td>
                                    <td>${dato.cliente}</td>
                                    <td>${dato.estilo}</td>
                                    <td>${dato.color}</td>
                                    <td>${dato.planta}</td>
                                    <td>${dato.pieza}</td>
                                    <td>${dato.cantidad_auditada}</td>
                                    <td>${dato.cantidad_rechazada}</td>
                                    <td>${dato.defectos_html}</td>
                                    <td>${dato.operario}</td>
                                    <td>${dato.porcentaje_aql}%</td>
                                    <td>${dato.fecha_creacion}</td>
                                </tr>`;
                            });
                            $('#tablaDatos tbody').html(filas);

                            // Inicializa DataTable
                            $('#tablaDatos').DataTable({
                                dom: 'Bfrtip',
                                buttons: [{extend: 'excelHtml5', text: 'Exportar a Excel', className: 'btn btn-success'}],
                                paging: true,
                                searching: true,
                                info: true,
                                language: {
                                    emptyTable: "No hay datos disponibles",
                                    paginate: {previous: "Anterior", next: "Siguiente"}
                                }
                            });

                            $('#tablaResultados').show();
                        }
                    },
                    error: function (xhr) {
                        alert('Error al buscar.');
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    
    
@endsection
