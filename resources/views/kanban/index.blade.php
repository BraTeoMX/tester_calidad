@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

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
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA KANBAN</h3>
                        </div>
                        <div class="col-auto">
                            <h4>Fecha:
                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>OP</th>
                                    <th>ACCION</th>
                                    <th>COMENTARIO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>123456</td> <!-- ejemplo OP -->
                                    <td>Ejecutar acción</td> <!-- ejemplo ACCION -->
                                    <td>
                                        <select class="form-control select-comentario" name="comentario[]"></select>
                                    </td>
                                </tr>
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3>Registros por dia - ordenes liberadas en AMP </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table56">
                            <thead class="thead-primary">
                                <tr>
                                    <th>FECHA DE ALMACEN</th>
                                    <th>OP</th>
                                    <th>CLIENTE</th>
                                    <th>ESTILO</th>
                                    <th>ACEPTADO</th>
                                    <th>PARCIAL</th>
                                    <th>RECHAZADO</th>
                                    <th>COMENTARIOS</th>
                                    <th>FECHA DE LIBERACION</th>
                                    <th>Eliminar </th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
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

        .texto-blanco {
            color: white !important;
        }

    </style>

    <script>
        $(document).ready(function () {
            $('.select-comentario').select2({
                placeholder: 'Selecciona un comentario',
                ajax: {
                    url: '{{ route('kanban.comentarios') }}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.map(function (comentario) {
                                return {
                                    id: comentario.nombre,
                                    text: comentario.nombre
                                };
                            })
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });
        });
    </script>
    
@endsection
