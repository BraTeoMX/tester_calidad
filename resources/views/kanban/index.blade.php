@extends('layouts.app', ['pageSlug' => 'kanban', 'titlePage' => __('kanban')])

@section('content')
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
            </div>

            <div class="card card-body">
                <div class="accordion" id="accordionParcial">
                    <div class="card">
                        <div class="card-header p-0" id="headingParcial">
                            <h2 class="mb-0">
                                <button class="btn btn-link text-light text-decoration-none w-100 text-left" type="button" data-toggle="collapse" data-target="#collapseParcial" aria-expanded="false" aria-controls="collapseParcial">
                                    Mostrar Parcial No Liberado
                                </button>
                            </h2>
                        </div>
                        <div id="collapseParcial" class="collapse" aria-labelledby="headingParcial" data-parent="#accordionParcial">
                            <div class="card-body" id="parcial-container">
                                <p class="text-muted">Abre el acordeón para cargar los datos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-primary">
                    <h3>Registros por dia - ordenes liberadas en AMP</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-registros-hoy" class="table tabla-amp">
                            <thead class="thead-primary">
                                <tr>
                                    <th>FECHA DE CORTE</th>
                                    <th>FECHA DE ALMACEN</th>
                                    <th>OP</th>
                                    <th>CLIENTE</th>
                                    <th>ESTILO</th>
                                    <th>ESTATUS</th>
                                    <th>COMENTARIOS</th>
                                    <th>FECHA DE LIBERACION</th>
                                    <th>FECHA DE PARCIAL</th>
                                    <th>FECHA DE RECHAZADO</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3"> <button id="btn-actualizar-todo" class="btn btn-success">Guardar Cambios Masivos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .tabla-kanban tbody td:nth-child(1) {
            min-width: 150px; /* OP */
        }

        .tabla-kanban tbody td:nth-child(2) {
            min-width: 100px; /* planta */
        }

        .tabla-kanban tbody td:nth-child(3) {
            min-width: 190px; /* Fecha Corte */
        }
    
        .tabla-kanban tbody td:nth-child(4) {
            min-width: 150px; /* Cliente */
        }
    
        .tabla-kanban tbody td:nth-child(5) {
            min-width: 100px; /* Estilo */
        }
    
        .tabla-kanban tbody td:nth-child(6) {
            min-width: 70px; /* Piezas */
        }

    
        .tabla-amp tbody td:nth-child(6) {
            min-width: 100px; /* Cliente */
        }

        .tabla-amp tbody td:nth-child(7) {
            min-width: 200px; /* Piezas */
        }
    </style>
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

        .btn-verde-xd {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08) !important;
            padding: 0.5rem 2rem;
            /* Aumenta el tamaño del botón */
            font-size: 1.2rem;
            /* Aumenta el tamaño de la fuente */
            font-weight: bold;
            /* Texto en negritas */
            border-radius: 10px;
            /* Ajusta las esquinas redondeadas */
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            cursor: pointer;
            /* Cambia el cursor a una mano */
        }

        .btn-verde-xd:hover {
            color: #fff !important;
            background-color: #218838 !important;
            border-color: #1e7e34 !important;
        }

        .btn-verde-xd:focus,
        .btn-verde-xd.focus {
            box-shadow: 0 4px 6px rgba(50, 50, 93, .11), 0 1px 3px rgba(0, 0, 0, .08), 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }

        .btn-verde-xd:disabled,
        .btn-verde-xd.disabled {
            color: #fff !important;
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active,
        .btn-verde-xd:not(:disabled):not(.disabled):active,
        .show>.btn-verde-xd.dropdown-toggle {
            color: #fff !important;
            background-color: #1e7e34 !important;
            border-color: #1c7430 !important;
        }

        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .btn-verde-xd:not(:disabled):not(.disabled).active:focus,
        .show>.btn-verde-xd.dropdown-toggle:focus {
            box-shadow: none, 0 0 0 0.2rem rgba(40, 167, 69, 0.5) !important;
        }
    </style>

    <script>
        $(document).ready(function () {
            let cargado = false;

            function cargarParciales() {
                $.ajax({
                    url: '{{ route('kanban.parciales') }}',
                    method: 'GET',
                    success: function (data) {
                        if (data.length === 0) {
                            $('#parcial-container').html('<p class="text-muted">No hay registros parciales no liberados.</p>');
                            return;
                        }

                        let tabla = `
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>OP</th>
                                            <th>Cliente</th>
                                            <th>Estilo</th>
                                            <th>Piezas</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        data.forEach(function (item) {
                            tabla += `
                                <tr>
                                    <td>${item.op}</td>
                                    <td>${item.cliente}</td>
                                    <td>${item.estilo}</td>
                                    <td>${item.piezas}</td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-actualizar" data-id="${item.id}">
                                            Liberar
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        tabla += `
                                    </tbody>
                                </table>
                            </div>
                        `;

                        $('#parcial-container').html(tabla);
                        cargado = true;
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        $('#parcial-container').html('<p class="text-danger">Error al cargar los datos.</p>');
                    }
                });
            }

            $('#collapseParcial').on('show.bs.collapse', function () {
                if (!cargado) {
                    cargarParciales();
                }
            });

            // ⬇️ AQUI DEBES AGREGAR EL POST PARA "LIBERAR"
            $('#parcial-container').on('click', '.btn-actualizar', function () {
                const id = $(this).data('id');

                if (!confirm('¿Estás seguro que deseas liberar este registro?')) return;

                $.ajax({
                    url: '{{ route("kanban.parcial.liberar") }}',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.mensaje);
                        cargado = false; // volver a permitir recarga
                        cargarParciales(); // recargar la tabla
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('Hubo un error al intentar liberar el registro.');
                    }
                });
            });
        });
    </script>

    <script>
        const comentariosSeleccionadosPorFila = {};

        function cargarRegistrosHoy() {
            // ---- INICIO DE LA MODIFICACIÓN ----
            // Limpiar el estado de los comentarios seleccionados para todas las filas
            // ya que la tabla se va a reconstruir completamente.
            for (const key in comentariosSeleccionadosPorFila) {
                if (comentariosSeleccionadosPorFila.hasOwnProperty(key)) {
                    delete comentariosSeleccionadosPorFila[key];
                    // Si sabes que siempre es un Set, podrías hacer:
                    // comentariosSeleccionadosPorFila[key].clear();
                    // Pero delete es más seguro si la clave podría no existir o no ser un Set en algún punto.
                    // Al borrar la propiedad, la próxima vez que se acceda en `inicializarSelect2Comentarios` o `agregarComentarioFila`,
                    // se creará un nuevo Set.
                }
            }
            // ---- FIN DE LA MODIFICACIÓN ----
            $.ajax({
                url: '{{ route("kanban.registrosHoy") }}',
                method: 'GET',
                success: function (data) {
                    let tbody = '';

                    data.forEach(function (item) {
                        const id = item.id;
                        // Si existen comentarios se crea un array a partir de la cadena,
                        // de lo contrario se usa un array vacío
                        // const comentarios = item.comentarios ? item.comentarios.split(',') : []; // Ya no es necesario aquí directamente

                        tbody += `
                            <tr data-id="${id}">
                                <td>${item.fecha_corte || ''}</td>
                                <td>${item.fecha_almacen || ''}</td>
                                <td>${item.op}</td>
                                <td>${item.cliente}</td>
                                <td>${item.estilo}</td>
                                <td>
                                    <select class="form-control select-accion">
                                        <option value="">Selecciona</option>
                                        <option value="1" ${item.estatus == '1' ? 'selected' : ''}>Aceptado</option>
                                        <option value="2" ${item.estatus == '2' ? 'selected' : ''}>Parcial</option>
                                        <option value="3" ${item.estatus == '3' ? 'selected' : ''}>Rechazado</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control select-comentario" id="selectComentario-${id}"></select>
                                    <div class="selected-options-container mt-2" id="selectedContainer-${id}"></div>
                                </td>
                                <td>${item.fecha_liberacion || ''}</td>
                                <td>${item.fecha_parcial || ''}</td>
                                <td>${item.fecha_rechazo || ''}</td>
                            </tr>
                        `;
                    });

                    $('#tabla-registros-hoy tbody').html(tbody);

                    // Inicializar select2 y cargar los comentarios existentes por cada fila
                    data.forEach(item => {
                        inicializarSelect2Comentarios(item.id);

                        // Si existen comentarios, se separan y se agregan al contenedor de la fila
                        if (item.comentarios) {
                            const lista = typeof item.comentarios === 'string' ? item.comentarios.split(',') : item.comentarios; // Asegurar que sea iterable
                            if (Array.isArray(lista)) {
                                lista.forEach(comentario => {
                                    if (comentario && comentario.trim() !== '') { // Verificar que comentario no sea null o undefined
                                        agregarComentarioFila(item.id, comentario.trim());
                                    }
                                });
                            }
                        }
                    });
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    // El colspan="10" ahora es correcto porque hemos eliminado 2 columnas
                    $('#tabla-registros-hoy tbody').html('<tr><td colspan="10" class="text-center text-danger">Error al cargar los registros.</td></tr>');
                }
            });
        }

        function inicializarSelect2Comentarios(idFila) {
            const select = $(`#selectComentario-${idFila}`);
            // const container = $(`#selectedContainer-${idFila}`); // No se usa directamente aquí
            
            // Asegurar que el set se inicializa solo una vez o se limpia si es necesario
            if (!comentariosSeleccionadosPorFila[idFila]) {
                comentariosSeleccionadosPorFila[idFila] = new Set();
            } else {
                // Si se recargan los datos, es posible que quieras limpiar los comentarios antiguos 
                // si no se manejan correctamente al agregar desde item.comentarios
            }


            select.select2({
                width: '100%',
                placeholder: 'Selecciona un comentario',
                templateResult: function (data) {
                    if (data.id === 'crear_comentario') {
                        return $('<span style="color: white; font-weight: bold;">' + data.text + '</span>');
                    }
                    return data.text;
                },
                ajax: {
                    url: '{{ route("kanban.comentarios") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term || '' };
                    },
                    processResults: function (data) {
                        const results = data.map(c => ({ id: c.nombre, text: c.nombre }));
                        results.unshift({ id: 'crear_comentario', text: 'Crear comentario' });
                        return { results };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });

            select.on('select2:select', function (e) {
                const data = e.params.data;

                if (data.id === 'crear_comentario') {
                    select.val(null).trigger('change'); // Limpiar la selección del select2

                    const nuevoComentario = prompt('Escribe el nuevo comentario:');
                    if (!nuevoComentario || nuevoComentario.trim() === '') {
                        alert('Comentario no válido.');
                        return;
                    }

                    $.ajax({
                        url: '{{ route("kanban.comentario.crear") }}',
                        method: 'POST',
                        data: { nombre: nuevoComentario },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (res) {
                            const nombre = res.comentario.nombre;
                            if (!comentariosSeleccionadosPorFila[idFila].has(nombre)) {
                                agregarComentarioFila(idFila, nombre);
                            }
                            alert('Comentario creado correctamente');
                            // No es necesario recargar toda la tabla, solo actualizar este select si fuera necesario
                            // o simplemente añadirlo visualmente, lo cual ya hace agregarComentarioFila
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('Error al crear el comentario');
                        }
                    });
                    return;
                }

                // Solo agregar si no es la opción de "crear" y no existe ya
                if (data.id && data.id !== 'crear_comentario' && !comentariosSeleccionadosPorFila[idFila].has(data.id)) {
                    agregarComentarioFila(idFila, data.id);
                }
                select.val(null).trigger('change'); // Limpiar la selección del select2
            });
        }

        function agregarComentarioFila(idFila, texto) {
            // Asegurar que el set para la fila existe
            if (!comentariosSeleccionadosPorFila[idFila]) {
                comentariosSeleccionadosPorFila[idFila] = new Set();
            }
            
            // Evitar duplicados visuales y en el Set
            if (comentariosSeleccionadosPorFila[idFila].has(texto)) {
                return; // Ya existe, no hacer nada
            }

            const container = $(`#selectedContainer-${idFila}`);
            const div = $(`
                <div class="selected-option d-flex align-items-center justify-content-between border rounded p-2 mb-1" data-texto="${texto}"> <span class="option-text flex-grow-1 mx-2">${texto}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-option">Eliminar</button>
                </div>
            `);

            div.find('.remove-option').on('click', function () {
                div.remove();
                comentariosSeleccionadosPorFila[idFila].delete(texto);
            });

            container.append(div);
            comentariosSeleccionadosPorFila[idFila].add(texto);
        }

        $(document).ready(function () {
            cargarRegistrosHoy();

            // Evento para el botón de actualización masiva
            $('#btn-actualizar-todo').on('click', function () {
                const botonActualizar = $(this); // Guardar referencia al botón
                const registrosParaActualizar = [];
                
                let validacionesPasadas = true;
                const mensajesErrorValidacion = [];
                let hayRegistrosConAccion = false;

                $('#tabla-registros-hoy tbody tr').each(function () {
                    const fila = $(this);
                    const id = fila.data('id');

                    if (id === undefined || id === null) {
                        return; 
                    }

                    const accion = fila.find('.select-accion').val();
                    const comentariosArray = comentariosSeleccionadosPorFila[id] ? Array.from(comentariosSeleccionadosPorFila[id]) : [];
                    const op = fila.find('td').eq(2).text(); 

                    if (accion) {
                        hayRegistrosConAccion = true; 

                        if (accion === '1' && comentariosArray.length > 0) {
                            validacionesPasadas = false;
                            mensajesErrorValidacion.push(`- OP ${op} (ID: ${id}): Con estatus "Aceptado", los comentarios deben estar vacíos.`);
                        }

                        if (accion === '2' && comentariosArray.length === 0) {
                            validacionesPasadas = false;
                            mensajesErrorValidacion.push(`- OP ${op} (ID: ${id}): Con estatus "Parcial", se requiere al menos un comentario.`);
                        }

                        if (accion === '3' && comentariosArray.length > 0) {
                            validacionesPasadas = false;
                            mensajesErrorValidacion.push(`- OP ${op} (ID: ${id}): Con estatus "Rechazado", los comentarios deben estar vacíos.`);
                        }
                        
                        registrosParaActualizar.push({
                            id: id,
                            accion: accion,
                            comentarios: comentariosArray
                        });
                    }
                });

                if (!hayRegistrosConAccion) {
                    Swal.fire({ // Reemplazo de alert
                        icon: 'info',
                        title: 'Atención',
                        text: 'No hay cambios para actualizar. Selecciona un estatus en al menos un registro.'
                    });
                    return;
                }

                if (!validacionesPasadas) {
                    Swal.fire({ 
                        icon: 'error',
                        title: 'Errores de Validación',
                        // Usamos la propiedad 'text' y unimos los errores con saltos de línea
                        text: "Por favor, corrige los siguientes errores antes de guardar:\n\n" + 
                            mensajesErrorValidacion.join("\n"), // Cada error en una nueva línea
                        confirmButtonText: 'Entendido'
                    });
                    return; 
                }
                
                if (registrosParaActualizar.length === 0) {
                    // Este chequeo es una salvaguarda, usualmente `!validacionesPasadas` o `!hayRegistrosConAccion` lo cubrirían.
                    Swal.fire({ // Reemplazo de alert
                        icon: 'warning',
                        title: 'Sin Registros Válidos',
                        text: 'No hay registros válidos para actualizar después de las validaciones.'
                    });
                    return;
                }

                // Reemplazo de confirm()
                Swal.fire({
                    title: 'Confirmar Actualización',
                    text: '¿Estás seguro de que deseas actualizar los registros seleccionados masivamente?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Usuario confirmó
                        botonActualizar.prop('disabled', true).text('Procesando...');

                        $.ajax({
                            url: '{{ route("kanban.actualizarMasivo") }}',
                            method: 'POST',
                            data: {
                                registros: registrosParaActualizar
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                Swal.fire({ // Reemplazo de alert
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: response.mensaje || 'Registros actualizados correctamente.'
                                });
                                cargarRegistrosHoy(); 
                            },
                            error: function (xhr) {
                                console.error(xhr.responseText);
                                let errorMsg = 'Error al actualizar los registros.';
                                // ... (tu lógica existente para construir errorMsg a partir de xhr.responseJSON) ...
                                if (xhr.responseJSON) {
                                    if (xhr.responseJSON.mensaje) {
                                        errorMsg = xhr.responseJSON.mensaje;
                                    }
                                    if (xhr.responseJSON.errores && Array.isArray(xhr.responseJSON.errores) && xhr.responseJSON.errores.length > 0) {
                                        const backendErrors = xhr.responseJSON.errores.filter(e => e).join("\n"); // Filtra nulos o vacíos
                                        if (backendErrors) {
                                        errorMsg += "\n\nErrores del servidor:\n" + backendErrors;
                                        }
                                    } else if (xhr.responseJSON.errors && typeof xhr.responseJSON.errors === 'object') {
                                        try {
                                            const errors = xhr.responseJSON.errors;
                                            let messages = [];
                                            for (const key in errors) {
                                                if (errors[key] && Array.isArray(errors[key])) {
                                                    messages.push(errors[key].join("\n"));
                                                }
                                            }
                                            if (messages.length > 0) {
                                                errorMsg += "\n\nErrores del servidor:\n" + messages.join("\n");
                                            }
                                        } catch (e) { /* No hacer nada */ }
                                    }
                                }
                                Swal.fire({ // Reemplazo de alert
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMsg
                                });
                            },
                            complete: function() {
                                botonActualizar.prop('disabled', false).text('Guardar Cambios Masivos');
                            }
                        });
                    } else {
                        // Usuario canceló, el botón no se deshabilitó así que no es necesario re-habilitarlo aquí.
                    }
                });
            });
        });
    </script>
    
    <script>
        $(document).ready(function () {
            let datosOP = {}; // Guardar los datos extra por OP

            $('#selectOP').select2({
                placeholder: 'Selecciona una OP',
                minimumInputLength: 4, // ⬅️ Aquí le decimos que inicie búsqueda a partir de 4 caracteres
                ajax: {
                    url: '{{ route("kanban.opciones") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            term: params.term // Esto lo recibe el backend como $request->term
                        };
                    },
                    processResults: function (data) {
                        datosOP = {};

                        const results = data.map(function (item) {
                            datosOP[item.op] = item;
                            return {
                                id: item.op,
                                text: `${item.op}`
                            };
                        });

                        return { results: results };
                    },
                    cache: true
                }
            });

            $('#selectOP').on('change', function () {
                const selectedOp = $(this).val();
                const data = datosOP[selectedOp];

                if (data) {
                    $('#clienteText').text(data.cliente);
                    $('#clienteInput').val(data.cliente);

                    $('#estiloText').text(data.estilo);
                    $('#estiloInput').val(data.estilo);

                    $('#fechaText').text(data.fecha);
                    $('#fechaInput').val(data.fecha);

                    $('#piezasText').text(data.piezas_total);
                    $('#piezasInput').val(data.piezas_total);
                }
            });
        });
    </script>
        
    <script>
        $(document).ready(function () {
            // Lógica oculta que lanza la actualización si es necesario
            $.get("{{ route('kanban.check-actualizacion') }}");
        });
    </script>
        
@endsection
