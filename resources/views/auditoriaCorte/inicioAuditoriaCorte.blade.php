@extends('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')])

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
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #28a745;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                </div>
                <div class="card-body">
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Inicio de Acordeón --}}
                            <div class="accordion" id="accordionExample1">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-danger btn-block" type="button" data-toggle="collapse"
                                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                ESTATUS: NO INICIADO
                                            </button>
                                        </h2>
                                    </div>
                        
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample1">
                                        <div class="card-body">
                                            <div class="form-inline">
                                                <input type="text" id="searchInput00" class="form-control mr-2" placeholder="Buscar por Orden">
                                                <button id="searchButton" class="btn btn-primary">Buscar</button>
                                            </div>
                                            <br>
                                            <div class="table-responsive" data-filter="false">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Acción</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                            <th>Color</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBody">
                                                        <!-- Los resultados se cargarán aquí mediante AJAX -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin del acordeón -->
                        </div>                        
                        <div class="col-md-6">
                            {{-- Acordeón EN PROCESO --}}
                            <div class="accordion" id="accordionExample2">
                                <div class="card">
                                    <div class="card-header" id="headingOne2">
                                        <h2 class="mb-0">
                                            <button class="btn estado-proceso btn-block" type="button"
                                                data-toggle="collapse" data-target="#collapseOne2" aria-expanded="true"
                                                aria-controls="collapseOne2">
                                                ESTATUS: EN PROCESO
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne2" data-parent="#accordionExample2">
                                        <div class="card-body">
                                            <!-- Campo de búsqueda y botón -->
                                            <div class="form-inline">
                                                <input type="text" id="searchInputAcordeon" class="form-control mr-2" placeholder="Buscar por Proceso">
                                                <button id="btnBuscar" class="btn btn-primary">Buscar</button>
                                            </div>
                                            
                                            <!-- Contenedor para mostrar los registros (ya sean del día o de la búsqueda) -->
                                            <div class="accordion" id="accordionExample">
                                                <div id="contentEnProceso">
                                                    <p>Cargando datos...</p>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                // Carga inicial: se muestran los registros del día
                                                buscarEnProceso('');
                                                
                                                // Al hacer clic en el botón de búsqueda se realiza la consulta
                                                document.getElementById('btnBuscar').addEventListener('click', function() {
                                                    const busqueda = document.getElementById('searchInputAcordeon').value.trim();
                                                    buscarEnProceso(busqueda);
                                                });
                                            });
                                            
                                            function buscarEnProceso(busqueda) {
                                                $.ajax({
                                                    url: '{{ route("auditoriaCorte.searchEnProceso") }}',
                                                    method: 'GET',
                                                    data: { search: busqueda },
                                                    success: function(response) {
                                                        $('#contentEnProceso').html(response.html);
                                                    },
                                                    error: function() {
                                                        console.error('Error en la búsqueda de EN PROCESO');
                                                    }
                                                });
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>
                            {{-- Fin del acordeón EN PROCESO --}}
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Acordeón FINAL --}}
                            <div class="accordion" id="accordionExampleFinal">
                                <div class="card">
                                    <div class="card-header" id="headingFinalOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-info btn-block" type="button"
                                                data-toggle="collapse" data-target="#collapseFinalOne" aria-expanded="true"
                                                aria-controls="collapseFinalOne">
                                                ESTATUS: FINAL
                                            </button>
                                        </h2>
                                    </div>
                        
                                    <div id="collapseFinalOne" class="collapse show" aria-labelledby="headingFinalOne"
                                        data-parent="#accordionExampleFinal">
                                        <div class="card-body">
                                            <!-- Campo de búsqueda y botón -->
                                            <div class="form-inline">
                                                <input type="text" id="searchInputAcordeonFinal" class="form-control mr-2"
                                                    placeholder="Buscar por Orden finalizada">
                                                <button id="btnBuscarFinal" class="btn btn-primary">Buscar</button>
                                            </div>
                                            
                        
                                            <!-- Contenedor que se actualizará vía AJAX -->
                                            <div class="accordion" id="accordionExampleFinalSub">
                                                <div id="contentFinal">
                                                    <p>Cargando datos...</p>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                // Carga inicial para FINAL: sin búsqueda se muestran los registros del día
                                                buscarFinal('');
                                                
                                                // Al hacer clic en el botón se ejecuta la búsqueda
                                                document.getElementById('btnBuscarFinal').addEventListener('click', function() {
                                                    const busqueda = document.getElementById('searchInputAcordeonFinal').value.trim();
                                                    buscarFinal(busqueda);
                                                });
                                            });
                        
                                            function buscarFinal(busqueda) {
                                                $.ajax({
                                                    url: '{{ route("auditoriaCorte.searchFinal") }}',
                                                    method: 'GET',
                                                    data: { search: busqueda },
                                                    success: function(response) {
                                                        $('#contentFinal').html(response.html);
                                                    },
                                                    error: function() {
                                                        console.error('Error en la búsqueda de FINAL');
                                                    }
                                                });
                                            }
                                        </script>
                                    </div>
                                </div>
                                <!-- Fin del acordeón -->
                            </div>
                        </div>                        
                        <!-- Fin del acordeón -->
                        <div class="col-md-6">
                            {{-- Inicio de Acordeon --}}
                            <div class="accordion" id="accordionExample4">
                                <div class="card">
                                    <div class="card-header" id="headingOne4">
                                        <h2 class="mb-0">
                                            <button class="btn-rechazado btn-block" type="button"
                                                data-toggle="collapse" data-target="#collapseOne4" aria-expanded="true"
                                                aria-controls="collapseOne4">
                                                ESTATUS: RECHAZADO
                                            </button>
                                        </h2>
                                    </div>

                                    <div id="collapseOne4" class="collapse show" aria-labelledby="headingOne4"
                                        data-parent="#accordionExample4">
                                        <div class="card-body">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <input type="text" id="searchInputRechazo" class="form-control" placeholder="Buscar por Orden Rechazada">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Accion</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                            <th>Planta</th>
                                                            <th>Temporada</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBodyRechazo">
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fin del acordeón -->
                    </div>
                </div>
            </div>
        </div>
        <style>
            /* Estilo personalizado para el botón */
            .estado-proceso {
                background-color: #2196F3;
                /* Color azul intenso */
                color: #fff;
                /* Color de texto blanco */
                border-color: #2196F3;
                /* Color del borde igual al color de fondo */
                transition: background-color 0.3s, color 0.3s;
                /* Transición suave para el color de fondo y texto */
            }

            /* Estilo para el efecto hover */
            .estado-proceso:hover {
                background-color: #1976D2;
                /* Color azul más oscuro al pasar el mouse */
                border-color: #1976D2;
                /* Color del borde igual al color de fondo */
            }

            .btn-rechazado {
                color: #fff !important;
                background-color: #FF5733 !important;
                border-color: #FF5733 !important;
                box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
                padding: 0.5rem 2rem;
                /* Aumenta el tamaño del botón */
                font-size: 1rem;
                /* Aumenta el tamaño de la fuente */
                font-weight: bold;
                /* Texto en negritas */
                border-radius: 10px;
                /* Ajusta las esquinas redondeadas */
                transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
                cursor: pointer;
                /* Cambia el cursor a una mano */
            }

            .btn-rechazado:hover {
                color: #fff !important;
                background-color: #FF8C00 !important;
                border-color: #FF8C00 !important;
            }

            .btn-rechazado:focus,
            .btn-rechazado.focus {
                box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 0.2rem rgba(255, 87, 51, 0.5) !important;
            }

            .btn-rechazado:disabled,
            .btn-rechazado.disabled {
                color: #fff !important;
                background-color: #FF5733 !important;
                border-color: #FF5733 !important;
            }

            .btn-rechazado:not(:disabled):not(.disabled).active,
            .btn-rechazado:not(:disabled):not(.disabled):active,
            .show>.btn-rechazado.dropdown-toggle {
                color: #fff !important;
                background-color: #E6501C !important;
                border-color: #CC4717 !important;
            }

            .btn-rechazado:not(:disabled):not(.disabled).active:focus,
            .btn-rechazado:not(:disabled):not(.disabled).active:focus,
            .show>.btn-rechazado.dropdown-toggle:focus {
                box-shadow: none, 0 0 0 0.2rem rgba(255, 87, 51, 0.5) !important;
            }
        </style>

        <script>
            const searchInputFin = document.getElementById('searchInputFin');
            const tablaBodyFin = document.getElementById('tablaBodyFin');
            const filasFin = tablaBodyFin.getElementsByTagName('tr');

            searchInputFin.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                for (const fila of filasFin) {
                    const orden = fila.getElementsByTagName('td')[1].innerText.toLowerCase();
                    if (orden.includes(busqueda)) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                }
            });
        </script>
    </div>

    <script>
        $(document).ready(function(){
            $('#searchButton').on('click', function(){
                var search = $('#searchInput00').val(); // Asegúrate de usar el mismo id que en el input
    
                $.ajax({
                    url: "{{ route('ordenes-corte.buscar') }}",
                    type: "GET",
                    data: { search: search },
                    beforeSend: function(){
                        $('#searchButton').prop('disabled', true);
                    },
                    success: function(data){
                        var html = '';
    
                        if(data.length > 0) {
                            $.each(data, function(index, item) {
                                // Si ya existe el registro, no se muestra el botón sino un mensaje.
                                var accionHtml = '';
                                if(item.yaIniciada) {
                                    accionHtml = '<span class="text-muted">Orden ya iniciada</span>';
                                } else {
                                    accionHtml = '<a href="/altaAuditoriaCorte/' + item.op + '/' + item.inventcolorid + '" class="btn btn-primary">Acceder</a>';
                                }
    
                                html += '<tr>' +
                                            '<td>' + accionHtml + '</td>' +
                                            '<td>' + item.op + '</td>' +
                                            '<td>' + (item.estilo ? item.estilo : 'N/D') + '</td>' +
                                            '<td>' + (item.inventcolorid ? item.inventcolorid : 'N/D') + '</td>' +
                                        '</tr>';
                            });
                        } else {
                            html = '<tr><td colspan="4">No se encontraron resultados.</td></tr>';
                        }
                        $('#tablaBody').html(html);
                    },
                    error: function(xhr, status, error){
                        console.error("Ocurrió un error:", error);
                    },
                    complete: function(){
                        $('#searchButton').prop('disabled', false);
                    }
                });
            });
        });
    </script>    


@endsection
