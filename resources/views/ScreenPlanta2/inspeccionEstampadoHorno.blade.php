@extends('layouts.app', ['pageSlug' => 'InspeccionEstampado', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Inspección Estampado Después del Horno</h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
            <div class="card-body">
                {{-- Tabla con los Select2 en sus columnas correspondientes --}}
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead-primary">
                            <tr>
                                <th>OP</th>
                                <th>Bulto</th>
                                <th>Cliente</th>
                                <th>Estilo</th>
                                <th>Color</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Columna OP con Select2 --}}
                                <td>
                                    <select id="op-select" class="form-control"></select>
                                </td>

                                {{-- Columna Bulto con Select2 --}}
                                <td>
                                    <select id="bulto-select" class="form-control" disabled></select>
                                </td>

                                {{-- Columnas para mostrar los datos del bulto seleccionado --}}
                                <td id="cliente-cell"></td>
                                <td id="estilo-cell"></td>
                                <td id="color-cell"></td>
                                <td id="cantidad-cell"></td>
                            </tr>
                        </tbody>
                    </table>
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

    </style>

    <script>
        $(document).ready(function() {

            /**
             * 1) Inicializa el select2 para la OP (prodid)
             */
            $('#op-select').select2({
                placeholder: 'Busca una OP...',
                minimumInputLength: 4,
                width: '100%', // IMPORTANTE: Hace que se ajuste a la celda
                dropdownAutoWidth: true,
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
                    url: '{{ route("search.ops.screen") }}',
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

            /**
             * 2) Cuando seleccionamos una OP, habilitamos y cargamos el select2 de bultos
             */
            $('#op-select').on('select2:select', function(e) {
                var selectedOp = e.params.data.id;

                // Limpiar y deshabilitar el select de bultos antes de volverlo a llenar
                $('#bulto-select').html('').prop('disabled', true).val(null).trigger('change');

                // Limpiar celdas de la tabla
                $('#cliente-cell').text('');
                $('#estilo-cell').text('');
                $('#color-cell').text('');
                $('#cantidad-cell').text('');

                // Inicializa de nuevo el select2 para bultos, ahora con la OP seleccionada
                $('#bulto-select').select2({
                    placeholder: 'Selecciona un bulto...',
                    minimumInputLength: 4,
                    width: '100%', // IMPORTANTE: Hace que se ajuste a la celda
                    dropdownAutoWidth: true,
                    language: {
                        inputTooShort: function(args) {
                            var remainingChars = args.minimum - args.input.length;
                            return `Por favor, ingresa al menos ${remainingChars} caracter(es) más.`;
                        },
                        noResults: function() {
                            return 'No se encontraron bultos para la OP seleccionada';
                        },
                        searching: function() {
                            return 'Buscando...';
                        }
                    },
                    ajax: {
                        url: '{{ route("search.bultos.op.screen") }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                op: selectedOp,  // Le enviamos la OP seleccionada
                                q: params.term   // Para poder filtrar por bulto
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

                // Habilitamos el select de bultos
                $('#bulto-select').prop('disabled', false).focus();
            });

            /**
             * 3) Cuando seleccionamos un bulto, consultamos sus datos y los mostramos
             */
            $('#bulto-select').on('select2:select', function(e) {
                var bultoId = e.params.data.id; // Este es el ID de la tabla JobAQLHistorial

                $.ajax({
                    url: '/get-bulto-details-screen/' + bultoId,
                    type: 'GET',
                    success: function(response) {
                        // Llenamos las celdas con los datos del bulto
                        $('#cliente-cell').text(response.cliente);
                        $('#estilo-cell').text(response.estilo);
                        $('#color-cell').text(response.color);
                        $('#cantidad-cell').text(response.cantidad);
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