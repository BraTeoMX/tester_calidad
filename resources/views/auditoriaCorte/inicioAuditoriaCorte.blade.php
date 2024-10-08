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
                            {{-- Inicio de Acordeon --}}
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

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordionExample">
                                        <div class="card-body">
                                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar por Orden">
                                            <br>
                                            <div class="table-responsive" data-filter="false">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Iniciar</th>
                                                            <th>Orden</th>
                                                            <th>Estilo</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tablaBody">
                                                        @foreach ($DatoAXNoIniciado as $inicio)
                                                        <tr>
                                                            <td><a href="{{ route('auditoriaCorte.altaAuditoriaCorte', ['orden' => $inicio->op]) }}" class="btn btn-primary">Acceder</a></td>
                                                            <td>{{ $inicio->op }}</td>
                                                            <td>{{ $inicio->estilo }}</td>
                                                        </tr>
                                                        @endforeach
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
                            {{-- Inicio de Acordeon --}}
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

                                    <div id="collapseOne2" class="collapse show" aria-labelledby="headingOne2"
                                        data-parent="#accordionExample2">
                                        <div class="card-body">
                                            <input type="text" id="searchInputAcordeon" class="form-control"
                                                placeholder="Buscar por Proceso">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido --> 
                                            <div class="accordion" id="accordionExample">
                                                @if($EncabezadoAuditoriaCorte->isNotEmpty())
                                                    @foreach ($EncabezadoAuditoriaCorte->unique('orden_id') as $encabezadoCorte)
                                                        <div class="card proceso-card" data-proceso="{{ $encabezadoCorte->orden_id }}">
                                                            <div class="card-header" id="heading{{ $encabezadoCorte->orden_id }}">
                                                                <h2 class="mb-0">
                                                                    <button class="btn estado-proceso btn-block" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapse{{ $encabezadoCorte->orden_id }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapse{{ $encabezadoCorte->orden_id }}">
                                                                        {{ $encabezadoCorte->orden_id }}
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                
                                                            <div id="collapse{{ $encabezadoCorte->orden_id }}" class="collapse"
                                                                aria-labelledby="heading{{ $encabezadoCorte->orden_id }}"
                                                                data-parent="#accordionExample">
                                                                <div class="card-body">
                                                                    <div>
                                                                        <form method="POST" action="{{ route('auditoriaCorte.agregarEventoCorteV2') }}">
                                                                            @csrf
                                                                            <input type="hidden" name="orden_id" value="{{ $encabezadoCorte->orden_id }}">
                                                                            <input type="hidden" name="estilo_id" value="{{ $encabezadoCorte->estilo_id }}">
                                                                            <input type="hidden" name="planta_id" value="{{ $encabezadoCorte->planta_id }}">
                                                                            <input type="hidden" name="temporada_id" value="{{ $encabezadoCorte->temporada_id }}">
                                                                            <input type="hidden" name="cliente_id" value="{{ $encabezadoCorte->cliente_id }}">
                                                                            <input type="hidden" name="color_id" value="{{ $encabezadoCorte->color_id }}">
                                                                            <input type="hidden" name="estatus_evaluacion_corte" value="{{ $encabezadoCorte->estatus_evaluacion_corte }}">
                                                                            <input type="hidden" name="material" value="{{ $encabezadoCorte->material }}">
                                                                            <input type="hidden" name="pieza" value="{{ $encabezadoCorte->pieza }}">
                                                                            <input type="hidden" name="trazo" value="{{ $encabezadoCorte->trazo }}">
                                                                            <input type="hidden" name="lienzo" value="{{ $encabezadoCorte->lienzo }}">
                                                                            <button type="submit" class="btn btn-info">Agregar 1 evento</button>
                                                                        </form>
                                                                    </div>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Acceso</th>
                                                                                <th>Evento</th>
                                                                                <th>Estilo</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($EncabezadoAuditoriaCorteFiltro->where('orden_id', $encabezadoCorte->orden_id)->where('estatus', '!=', 'fin') as $encabezado)
                                                                                <tr>
                                                                                    <td><a href="{{ route('auditoriaCorte.auditoriaCorteV2', ['id' => $encabezado->id, 'orden' => $encabezado->orden_id]) }}"
                                                                                        class="btn btn-primary">Acceder</a>
                                                                                    </td>
                                                                                    <td>{{ $encabezado->evento }}</td>
                                                                                    <td>{{ $encabezado->estilo_id }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const searchInput = document.getElementById('searchInputAcordeon');
                                                const procesoCards = document.querySelectorAll('.proceso-card');

                                                searchInput.addEventListener('input', function() {
                                                    const busqueda = this.value.trim().toLowerCase();
                                                    procesoCards.forEach(card => {
                                                        const proceso = card.getAttribute('data-proceso').toLowerCase();
                                                        if (proceso.includes(busqueda)) {
                                                            card.style.display = 'block'; // Mostrar el acordeón
                                                        } else {
                                                            card.style.display = 'none'; // Ocultar el acordeón
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                                <!-- Fin del acordeón -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Inicio de Acordeon --}}
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
                                            <input type="text" id="searchInputAcordeonFinal" class="form-control"
                                                placeholder="Buscar por Operacion">
                                            <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                                            <div class="accordion" id="accordionExampleFinalSub">
                                                @if($EncabezadoAuditoriaCorteFinal->isNotEmpty())
                                                    @foreach ($EncabezadoAuditoriaCorteFinal->unique('orden_id') as $encabezadoCorte)
                                                        <div class="card proceso-card-final" data-proceso="{{ $encabezadoCorte->orden_id }}">
                                                            <div class="card-header" id="headingFinal{{ $encabezadoCorte->orden_id }}">
                                                                <h2 class="mb-0">
                                                                    <button class="btn btn-success btn-block" type="button"
                                                                        data-toggle="collapse"
                                                                        data-target="#collapseFinal{{ $encabezadoCorte->orden_id }}"
                                                                        aria-expanded="true"
                                                                        aria-controls="collapseFinal{{ $encabezadoCorte->orden_id }}">
                                                                        {{ $encabezadoCorte->orden_id }}
                                                                    </button>
                                                                </h2>
                                                            </div>
                                                            <div id="collapseFinal{{ $encabezadoCorte->orden_id }}" class="collapse"
                                                                aria-labelledby="headingFinal{{ $encabezadoCorte->orden_id }}"
                                                                data-parent="#accordionExampleFinalSub">
                                                                <div class="card-body">
                                                                    <div>
                                                                    </div>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Acceso</th>
                                                                                <th>Evento</th>
                                                                                <th>Estilo</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($EncabezadoAuditoriaCorteFinal->where('orden_id', $encabezadoCorte->orden_id) as $encabezado)
                                                                                <tr>
                                                                                    <td><a href="{{ route('auditoriaCorte.auditoriaCorteV2', ['id' => $encabezado->id, 'orden' => $encabezado->orden_id]) }}"
                                                                                        class="btn btn-primary">Acceder</a>
                                                                                    </td>
                                                                                    <td>{{ $encabezado->evento }}</td>
                                                                                    <td>{{ $encabezado->estilo_id }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <!--Fin del cuerpo del acordeon-->
                                        </div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const searchInputFinal = document.getElementById('searchInputAcordeonFinal');
                                                const procesoCardsFinal = document.querySelectorAll('.proceso-card-final');
                        
                                                searchInputFinal.addEventListener('input', function() {
                                                    const busqueda = this.value.trim().toLowerCase();
                                                    procesoCardsFinal.forEach(card => {
                                                        const proceso = card.getAttribute('data-proceso').toLowerCase();
                                                        if (proceso.includes(busqueda)) {
                                                            card.style.display = 'block'; // Mostrar el acordeón
                                                        } else {
                                                            card.style.display = 'none'; // Ocultar el acordeón
                                                        }
                                                    });
                                                });
                                            });
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
                                                        @foreach ($DatoAXRechazado as $rechazado)
                                                            <tr>
                                                                <td>
                                                                    <form method="POST" action="{{ route('auditoriaCorte.formAprobarCorte', ['id' => $rechazado->id]) }}">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-primary">Aprobarlo</button>
                                                                    </form>
                                                                </td>
                                                                <td>{{ $rechazado->op }} </td>
                                                                <td>{{ $rechazado->estilo }}</td>
                                                                <td>{{ $rechazado->planta }}</td>
                                                                <td>{{ $rechazado->temporada }}</td>
                                                            </tr>
                                                        @endforeach
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
            $(document).ready(function() {
                $('#searchInput').on('input', function() {
                    var query = $(this).val();
                    $.ajax({
                        url: "{{ route('auditoriaCorte.inicioAuditoriaCorte') }}",
                        type: "GET",
                        data: {
                            'search': query
                        },
                        success: function(data) {
                            $('#tablaBody').html(data);
                        }
                    });
                });
            });
        </script>

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
        <script>
            const searchInputRechazo = document.getElementById('searchInputRechazo');
            const tablaBodyRechazo = document.getElementById('tablaBodyRechazo');
            const filasRechazo = tablaBodyRechazo.getElementsByTagName('tr');
        
            searchInputRechazo.addEventListener('input', function() {
                const busqueda = this.value.toLowerCase();
                for (const fila of filasRechazo) {
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
@endsection
