@extends('layouts.app', ['pageSlug' => 'InspeccionEstampado', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Inspeccion Estampado Despues del Horno.</h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>Bulto</th>
                                    <th>OP</th>
                                    <th>Cliente</th>
                                    <th>Estilo</th>
                                    <th>Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select id="bulto-select" class="form-control"></select>
                                    </td>
                                    <td id="op-cell"></td>
                                    <td id="cliente-cell"></td>
                                    <td id="estilo-cell"></td>
                                    <td id="color-cell"></td>
                                </tr>
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
        $(document).ready(function() {
            // Inicializa Select2
            $('#bulto-select').select2({
                placeholder: 'Busca un bulto...',
                minimumInputLength: 4,
                language: {
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        return `Por favor, ingresa al menos ${remainingChars} caracter(es) más.`;
                    },
                    noResults: function() {
                        return 'No se encontraron resultados';
                    },
                    searching: function() {
                        return 'Buscando...';
                    }
                },
                ajax: {
                    url: '{{ route("search.bultos") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Al seleccionar un bulto, actualiza las celdas correspondientes
            $('#bulto-select').on('select2:select', function(e) {
                var bultoId = e.params.data.id;

                $.ajax({
                    url: '/get-bulto-details/' + bultoId,
                    type: 'GET',
                    success: function(response) {
                        // Actualiza las celdas correspondientes en la misma fila
                        $('#op-cell').text(response.op);
                        $('#cliente-cell').text(response.cliente);
                        $('#estilo-cell').text(response.estilo);
                        $('#color-cell').text(response.color);
                    },
                    error: function(xhr) {
                        alert('Error al obtener los detalles del bulto.');
                        console.error(xhr);
                    }
                });
            });
        });
    </script>
@endsection
@section('scripts')
    
@endsection