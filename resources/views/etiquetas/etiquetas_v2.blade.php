@extends('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')])

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="row">
        <div class="card card-chart">
            <div class="card-header">
                <h2>Auditoria Etiquetas</h2>
            </div>
            <div class="card-body">
                <!-- Formulario -->
                <form action="{{ route('procesarFormularioEtiqueta') }}" method="POST">
                    @csrf
                    <div class="form-row d-flex align-items-end">
                        <!-- Select: Tipo de búsqueda -->
                        <div class="form-group col-md-4">
                            <label for="tipoEtiqueta">Tipo de búsqueda:</label>
                            <select name="tipoEtiqueta" id="tipoEtiqueta" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="OC" {{ old('tipoEtiqueta', $tipoBusqueda) === 'OC' ? 'selected' : '' }}>OC</option>
                                <option value="OV" {{ old('tipoEtiqueta', $tipoBusqueda) === 'OV' ? 'selected' : '' }}>OV</option>
                                <option value="OP" {{ old('tipoEtiqueta', $tipoBusqueda) === 'OP' ? 'selected' : '' }}>OP</option>
                                <option value="PO" {{ old('tipoEtiqueta', $tipoBusqueda) === 'PO' ? 'selected' : '' }}>PO</option>
                            </select>
                        </div>
            
                        <!-- Input: La orden -->
                        <div class="form-group col-md-4">
                            <label for="valorEtiqueta">Escribe la orden:</label>
                            <input type="text" name="valorEtiqueta" id="valorEtiqueta" 
                                   class="form-control" 
                                   value="{{ old('valorEtiqueta', $orden) }}"
                                   placeholder="Escribe un valor" required>
                        </div>
            
                        <!-- Botón -->
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-success mt-2">Buscar</button>
                        </div>
                    </div>
                </form>

                <!-- Resultados de la búsqueda: si se encontraron Estilos -->
                @if(isset($estilos) && $estilos->count() > 0)
                    <h4 class="mt-4">Estilos encontrados:</h4>
                    
                    <!-- Tabla responsiva con un solo row de selects/inputs -->
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-primary">
                                <tr>
                                    <th style="min-width: 150px;">Estilo</th>
                                    <th style="min-width: 150px;">Talla</th>
                                    <th style="min-width: 150px;">Color</th>
                                    <th style="min-width: 150px;">Cantidad</th>
                                    <th style="min-width: 180px;">Tamaño de Muestra</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- Select Estilo -->
                                    <td>
                                        <select id="estilosSelect" class="form-control">
                                            <option value="">-- Seleccionar --</option>
                                            @foreach($estilos as $estiloObj)
                                                <option value="{{ $estiloObj->Estilos }}">
                                                    {{ $estiloObj->Estilos }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <!-- Select Talla (se cargará con AJAX) -->
                                    <td>
                                        <select id="tallaSelect" class="form-control" disabled>
                                            <option value="">-- Seleccionar --</option>
                                        </select>
                                    </td>

                                    <!-- Input Color -->
                                    <td>
                                        <input type="text" class="form-control" id="colorInput" readonly>
                                    </td>

                                    <!-- Input Cantidad -->
                                    <td>
                                        <input type="text" class="form-control" id="cantidadInput" readonly>
                                    </td>

                                    <!-- Input Tamaño de Muestra -->
                                    <td>
                                        <input type="text" class="form-control" id="tamanoMuestraInput" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <h4 class="mt-4">No se encontraron estilos.</h4>
                @endif
            </div>            
        </div>
    </div>

    <!-- Estilos opcionales para el thead -->
    <style>
        thead.thead-primary {
            background-color: #59666e54;
            color: #333; /* Color del texto */
        }
    </style>

    <script>
    $(document).ready(function(){
        // Inicia select2 (opcional si usas select2)
        $('#estilosSelect').select2();
        $('#tallaSelect').select2();

        // Cuando cambia el Estilo
        $('#estilosSelect').on('change', function() {
            var estiloSeleccionado = $(this).val();
            var tipoBusqueda       = $('#tipoEtiqueta').val(); // asumiendo que tienes este input
            var orden             = $('#valorEtiqueta').val(); // asumiendo que tienes este input

            // Limpiar el segundo select y los inputs
            $('#tallaSelect').html('<option value="">-- Seleccionar --</option>');
            $('#tallaSelect').prop('disabled', true).trigger('change'); 
            $('#colorInput').val('');       // limpiamos color
            $('#cantidadInput').val('');
            $('#tamanoMuestraInput').val('');

            if(!estiloSeleccionado) return;

            // Petición AJAX para obtener Tallas
            $.ajax({
                url: "{{ route('ajaxGetTallas') }}", // Ajusta tu ruta
                method: 'GET',
                data: {
                    tipoBusqueda: tipoBusqueda,
                    orden:       orden,
                    estilo:      estiloSeleccionado
                },
                success: function(response) {
                    if(response.success) {
                        // Llenamos el select de Tallas
                        var tallas = response.tallas;
                        tallas.forEach(function(t) {
                            $('#tallaSelect').append(
                                $('<option>', { value: t, text: t })
                            );
                        });
                        $('#tallaSelect').prop('disabled', false).trigger('change');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Cuando cambia la Talla
        $('#tallaSelect').on('change', function() {
            var tallaSeleccionada  = $(this).val();
            var estiloSeleccionado = $('#estilosSelect').val();
            var tipoBusqueda       = $('#tipoEtiqueta').val();
            var orden             = $('#valorEtiqueta').val();

            // Limpiar inputs
            $('#colorInput').val('');
            $('#cantidadInput').val('');
            $('#tamanoMuestraInput').val('');

            if(!tallaSeleccionada || !estiloSeleccionado) return;

            // Petición AJAX para obtener Cantidad, Tamaño de muestra y Color
            $.ajax({
                url: "{{ route('ajaxGetData') }}", // Ajusta tu ruta
                method: 'GET',
                data: {
                    tipoBusqueda: tipoBusqueda,
                    orden:       orden,
                    estilo:      estiloSeleccionado,
                    talla:       tallaSeleccionada
                },
                success: function(response) {
                    if(response.success && response.data) {
                        $('#colorInput').val(response.data.color); // Asignar color
                        $('#cantidadInput').val(response.data.cantidad);
                        $('#tamanoMuestraInput').val(response.data.tamaño_muestra);
                    } else {
                        // No encontró data
                        $('#colorInput').val('N/A');
                        $('#cantidadInput').val('0');
                        $('#tamanoMuestraInput').val('');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
    </script>
@endsection
