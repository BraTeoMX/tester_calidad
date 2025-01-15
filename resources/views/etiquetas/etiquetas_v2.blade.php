@extends('layouts.app', ['pageSlug' => 'Etiquetas', 'titlePage' => __('Etiquetas')])

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
        <div class="card">
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
                    <!-- Formulario para enviar los datos al controlador -->
                    <form id="guardarFormulario"  action="{{ route('guardarAuditoriaEtiqueta') }}" method="POST">
                        @csrf
                        <!-- Inputs ocultos para enviar el tipo de búsqueda y el valor de la orden -->
                        <input type="hidden" name="tipoEtiqueta" value="{{ old('tipoEtiqueta', $tipoBusqueda) }}">
                        <input type="hidden" name="valorEtiqueta" value="{{ old('valorEtiqueta', $orden) }}">

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
                                        <th style="min-width: 200px;">Defectos</th>
                                        <th style="min-width: 200px;">Acciones Correctivas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <!-- Select Estilo -->
                                        <td>
                                            <select name="estilo" id="estilosSelect" class="form-control" required>
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
                                            <select name="talla" id="tallaSelect" class="form-control" disabled>
                                                <option value="">-- Seleccionar --</option>
                                            </select>
                                        </td>
            
                                        <!-- Input Color -->
                                        <td>
                                            <input type="text" name="color" class="form-control texto-blanco" id="colorInput" readonly>
                                        </td>
            
                                        <!-- Input Cantidad -->
                                        <td>
                                            <input type="text" name="cantidad" class="form-control texto-blanco" id="cantidadInput" readonly>
                                        </td>
            
                                        <!-- Input Tamaño de Muestra -->
                                        <td>
                                            <input type="text" name="muestreo" class="form-control texto-blanco" id="tamanoMuestraInput" readonly>
                                        </td>

                                        <!-- Select Defectos (con AJAX y Select2) -->
                                        <td>
                                            <select id="defectosSelect" class="form-control">
                                                <option value="">-- Seleccionar Defectos --</option>
                                            </select>
                                            <!-- Un contenedor donde se irán agregando los defectos -->
                                            <div id="listaDefectosContainer"></div>
                                        </td>
            
                                        <!-- Select Acciones Correctivas -->
                                        <td>
                                            <select name="accion_correctiva" id="accionesSelect" class="form-control" required>
                                                <option value="">-- Seleccionar --</option>
                                                <option value="Aprobado">Aprobado</option>
                                                <option value="Aprobado con condiciones">Aprobado con condiciones</option>
                                                <option value="Rechazado">Rechazado</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Botón para enviar los datos -->
                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-primary">Guardar Auditoría</button>
                            </div>
                        </div>
                    </form>
                @else
                    <h4 class="mt-4">No se encontraron estilos.</h4>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header">
                <h2>Registros del dia</h2>
            </div>
            <div class="card-body">
                @if($registrosDelDia->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Orden</th>
                                    <th>Estilo</th>
                                    <th>Color</th>
                                    <th>Cantidad</th>
                                    <th>Muestreo</th>
                                    <th>Estatus</th>
                                    <th>Defectos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registrosDelDia as $registro)
                                    <tr>
                                        <td>{{ $registro->tipo }}</td>
                                        <td>{{ $registro->orden }}</td>
                                        <td>{{ $registro->estilo }}</td>
                                        <td>{{ $registro->color }}</td>
                                        <td>{{ $registro->cantidad }}</td>
                                        <td>{{ $registro->muestreo }}</td>
                                        <td>{{ $registro->estatus }}</td>
                                        <td>
                                            @if($registro->defectos->isNotEmpty())
                                                <ul>
                                                    @foreach($registro->defectos as $defecto)
                                                        <li>{{ $defecto->nombre }} ({{ $defecto->cantidad }})</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                Sin defectos
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No se encontraron registros para el día de hoy.</p>
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
        .texto-blanco {
            color: white !important;
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

    <script>
        $(document).ready(function() {
            // Inicializar Select2 en el select de defectos
            $('#defectosSelect').select2({
                placeholder: '-- Seleccionar Defectos --',
                allowClear: true,
            });

            // Array para almacenar la lista de defectos seleccionados
            let defectosSeleccionados = [];

            // Función para redibujar la lista de defectos en el contenedor
            function renderizarListaDefectos() {
                // Limpiar el contenedor
                $('#listaDefectosContainer').empty();

                // Limpiar inputs ocultos existentes en el formulario
                $('#guardarFormulario input[name^="defectos"]').remove();

                // Recorrer cada defecto en el arreglo
                defectosSeleccionados.forEach(function(defecto, index) {
                    // Contenedor principal
                    let $defectoItem = $('<div class="defecto-item" style="margin-bottom: 5px;">');

                    // Nombre del defecto
                    let $nombreDefecto = $('<span style="margin-right: 5px;">')
                        .text(defecto.nombre + ':');

                    // Input numérico visible
                    let $inputCantidad = $('<input type="number" min="0" step="1" style="width: 80px; margin-right: 5px;">')
                        .val(defecto.cantidad || 1)
                        .on('input', function() {
                            // Actualizamos la cantidad en el array
                            defecto.cantidad = $(this).val();
                            // También actualizamos el input oculto
                            $inputOcultoCantidad.val(defecto.cantidad);
                        });

                    // Botón para eliminar
                    let $btnEliminar = $('<button class="btn btn-sm btn-danger">').text('Eliminar');

                    $btnEliminar.on('click', function() {
                        // Remover del arreglo principal
                        defectosSeleccionados = defectosSeleccionados.filter(function(item) {
                            return item.id !== defecto.id;
                        });

                        // Devolver la opción al select
                        $('#defectosSelect').append(
                            $('<option>', {
                                value: defecto.id,
                                text: defecto.nombre
                            })
                        );

                        // Volver a dibujar
                        renderizarListaDefectos();
                    });

                    // --- Inputs ocultos para envío en el formulario --- //
                    // Nombre
                    let $inputOcultoNombre = $('<input>').attr({
                        type: 'hidden',
                        name: `defectos[${index}][nombre]`,
                        value: defecto.nombre
                    });

                    // Cantidad (se creará con el valor inicial
                    // y se actualiza en el on('input') anterior)
                    let $inputOcultoCantidad = $('<input>').attr({
                        type: 'hidden',
                        name: `defectos[${index}][cantidad]`,
                        value: defecto.cantidad || 1
                    });

                    // Agregamos todo al defecto-item
                    $defectoItem.append($nombreDefecto);
                    $defectoItem.append($inputCantidad);
                    $defectoItem.append($btnEliminar);

                    // Agregamos el item al contenedor en la vista
                    $('#listaDefectosContainer').append($defectoItem);

                    // Agregamos los inputs ocultos directamente al formulario
                    $('#guardarFormulario').append($inputOcultoNombre);
                    $('#guardarFormulario').append($inputOcultoCantidad);
                });
            }

            // Cargar defectos mediante AJAX
            $.ajax({
                url: "{{ route('obtenerDefectosEtiquetas') }}",
                method: 'GET',
                success: function(response) {
                    if (response) {
                        // Agregar la opción "OTRO"
                        $('#defectosSelect').append(
                            $('<option>', {
                                value: 'otro',
                                text: 'OTRO'
                            })
                        );

                        // Agregar defectos retornados por AJAX
                        response.forEach(function(defecto) {
                            $('#defectosSelect').append(
                                $('<option>', {
                                    value: defecto.id,
                                    text: defecto.Defectos
                                })
                            );
                        });
                    }
                },
                error: function(xhr) {
                    console.log("Error al cargar defectos:", xhr.responseText);
                }
            });

            // Detectar cuando se selecciona algo en el select de defectos
            $('#defectosSelect').on('change', function() {
                let seleccionado = $(this).val();
                let textoSeleccionado = $(this).find('option:selected').text();

                // Verificar si se selecciona la opción 'OTRO'
                if (seleccionado === 'otro') {
                    // Mostrar prompt para capturar nuevo defecto
                    let nuevoDefecto = prompt("Por favor, introduce el nuevo defecto:");

                    if (nuevoDefecto) {
                        // Petición AJAX para guardar el nuevo defecto
                        $.ajax({
                            url: "{{ route('guardarDefectoEtiqueta') }}",
                            method: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                Defectos: nuevoDefecto
                            },
                            success: function(response) {
                                if (response.success) {
                                    alert("El defecto se ha guardado correctamente.");

                                    // Agregar el nuevo defecto al select y seleccionarlo
                                    $('#defectosSelect').append(
                                        $('<option>', {
                                            value: response.id,
                                            text: nuevoDefecto
                                        })
                                    );

                                    // Seleccionar el nuevo defecto automáticamente
                                    $('#defectosSelect').val(response.id).trigger('change');
                                } else {
                                    alert("Ocurrió un error al guardar el defecto.");
                                }
                            },
                            error: function(xhr) {
                                console.log("Estado del error:", xhr.status);
                                console.log("Detalle del error:", xhr.responseText);
                                alert("Ocurrió un error. Inténtalo de nuevo.");
                            }
                        });
                    } else {
                        // Si el usuario no introduce nada, reiniciamos el select
                        $('#defectosSelect').val(null).trigger('change');
                    }
                }
                else if (seleccionado) {
                    // Si NO es "otro" y es un defecto válido
                    // Verificar si ya existe en defectosSeleccionados
                    let existe = defectosSeleccionados.some(function(def) {
                        return def.id == seleccionado;
                    });

                    if (!existe) {
                        // 1. Agregamos el defecto al arreglo
                        defectosSeleccionados.push({
                            id: seleccionado,
                            nombre: textoSeleccionado
                        });

                        // 2. Removemos la opción del select para no volver a seleccionarla
                        $(this).find('option[value="' + seleccionado + '"]').remove();

                        // 3. Limpiamos el valor del select para que quede en placeholder
                        $(this).val(null).trigger('change');

                        // 4. Renderizamos la lista
                        renderizarListaDefectos();
                    } else {
                        // Si ya existe, simplemente podemos resetear el select
                        $(this).val(null).trigger('change');
                    }
                }
            });
        });
    </script>
    
    
@endsection
