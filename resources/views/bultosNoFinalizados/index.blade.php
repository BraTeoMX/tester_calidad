@extends('layouts.app', ['pageSlug' => 'busquedaOP', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Bultos no finalizados</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-body">
                <h3>Bultos No Finalizados (Últimos 7 días)</h3>
                <div id="bultos-container-general">
                    <p class="text-muted">Cargando datos de los últimos 7 días...</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body">
                <h3>Proceso</h3>
            </div>
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
            // Al cargar la vista, ejecutamos directamente la llamada
            $.ajax({
                url: '/bnf/bultos-no-finalizados-general',
                method: 'GET',
                beforeSend: function () {
                    $('#bultos-container-general').html(`
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Cargando datos...</p>
                        </div>
                    `);
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
                                            <th>Módulo</th>
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
                                    <td>${item.modulo}</td>
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
                        $('#bultos-container-general').html(contenido);
                    } else {
                        $('#bultos-container-general').html('<p class="text-warning text-center">No se encontraron bultos no finalizados en los últimos 7 días.</p>');
                    }
                },
                error: function () {
                    $('#bultos-container-general').html('<p class="text-danger text-center">Error al cargar los datos.</p>');
                }
            });
        
            // Reutilizamos el mismo evento para finalizar paros
            $(document).on('click', '.finalizar-paro', function () {
                let id = $(this).data('id');
                let piezasReparadas = prompt("Ingresa el número de piezas reparadas:", "0");
                if (piezasReparadas === null) return;
        
                if (confirm("¿Estás seguro de que deseas finalizar este paro?")) {
                    const spinnerHtml = `
                        <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                            Procesando solicitud...
                        </div>`;
                    $('body').append(spinnerHtml);
        
                    $.ajax({
                        url: '/bnf/finalizar-paro-aql-despues',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            id: id,
                            piezasReparadas: piezasReparadas
                        },
                        success: function (response) {
                            $('#processing-spinner').remove();
        
                            if (response.success) {
                                alert(`✅ Paro finalizado correctamente.\nMinutos Paro: ${response.minutos_paro}\nPiezas Reparadas: ${response.reparacion_rechazo}`);
                                location.reload(); // Para recargar la lista actualizada
                            } else {
                                alert(`❌ Error: ${response.message}`);
                            }
                        },
                        error: function () {
                            $('#processing-spinner').remove();
                            alert("⚠️ Ocurrió un error al intentar finalizar el paro.");
                        }
                    });
                }
            });
        });
    </script>
    
@endsection
