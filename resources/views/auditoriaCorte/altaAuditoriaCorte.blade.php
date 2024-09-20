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

        .texto-blanco {
            color: white !important;
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
                <hr> 
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>Orden</th>
                                <th>Estilo</th> 
                                <th>Planta</th>
                                <th>Temporada</th>
                                <th>Cliente</th>
                                <th>Piezas Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $datoAX->op }}</td>
                                <td>{{ $datoAX->estilo }}</td>
                                <td>{{ $datoAX->planta }}</td>
                                <td>{{ $datoAX->temporada }}</td>
                                <td>{{ $datoAX->custorname }}</td>
                                <td>{{ intval($datoAX->qtysched) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <form method="POST" action="{{ route('auditoriaCorte.formEncabezadoAuditoriaCorteV2') }}">
                    @csrf
                    <input type="hidden" name="orden" value="{{ $datoAX->op }}">
                    <input type="hidden" name="estilo" value="{{ $datoAX->estilo }}">
                    <input type="hidden" name="planta" value="{{ $datoAX->planta }}">
                    <input type="hidden" name="temporada" value="{{ $datoAX->temporada }}">
                    <input type="hidden" name="cliente" value="{{ $datoAX->custorname }}">
                    <input type="hidden" name="qtysched_id" value="{{ $datoAX->qtysched }}">
                    <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary table-01">
                                <tr>
                                    <th>Color</th>
                                    <th>Material</th>
                                    <th>Piezas</th>
                                    <th>Lienzos</th>
                                    <th>Cantidad Eventos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if($datoAX->inventcolorid)
                                            <input type="text" class="form-control texto-blanco" name="color_id" id="color_id" value="{{ $datoAX->inventcolorid }}" readonly/>
                                        @else
                                            <input type="text" class="form-control" name="color_id" id="color_id" placeholder="..." required/>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="material" id="material" placeholder="Nombre del material" required/>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" name="pieza" id="pieza" placeholder="..." required/>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="lienzo" id="lienzo" placeholder="..." required/>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <select class="form-control" name="evento" id="evento" required>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}">&nbsp; {{ $i }} &nbsp;</option>
                                                @endfor
                                            </select>
                                            &nbsp;/&nbsp;
                                            <select class="form-control" name="total_evento" id="total_evento" required>
                                                @for ($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}"> &nbsp;{{ $i }} &nbsp;</option>
                                                @endfor
                                            </select>
                                            <div id="warning" style="display: none; color: red;">El primer número debe ser menor o igual al segundo número</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                <form method="POST" action="{{ route('auditoriaCorte.formRechazoCorte') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $datoAX->id }}">
                    <button type="submit" class="btn btn-danger" name="action" value="rechazo">Rechazo</button>
                </form>
            </div>
        </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="text"]');
            
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            });
        });

    </script>

    <script>
        document.getElementById('total_evento').addEventListener('change', function() {
            var evento = document.getElementById('evento').value;
            var totalEvento = this.value;
            var warning = document.getElementById('warning');
            
            if (parseInt(evento) > parseInt(totalEvento)) {
                warning.style.display = 'block';
                this.value = evento;
            } else {
                warning.style.display = 'none';
            }
        });
    </script>

    <style>
        thead.thead-primary {
            background-color: #59666e54; /* Azul claro */
            color: #333; /* Color del texto */
        }

        .table-01 th:nth-child(1) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(2) {
            min-width: 220px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(3) {
            min-width: 100px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(4) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
        .table-01 th:nth-child(5) {
            min-width: 150px;
            /* Ajusta el ancho mínimo según tu necesidad */
        }
    </style>

    @endsection
