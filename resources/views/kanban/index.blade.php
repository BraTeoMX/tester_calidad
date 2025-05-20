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
                const registrosParaActualizar = [];
                let alMenosUnCambio = false; // Para verificar si hay algo que enviar

                // Iterar sobre cada fila del tbody
                $('#tabla-registros-hoy tbody tr').each(function () {
                    const fila = $(this);
                    const id = fila.data('id'); // Obtener el ID del atributo data-id de la fila

                    // Asegurarse de que la fila tenga un ID (evitar filas de mensajes como "cargando..." o "error")
                    if (id === undefined || id === null) {
                        return; // Saltar esta iteración si no hay ID
                    }

                    const accion = fila.find('.select-accion').val();
                    const comentariosArray = comentariosSeleccionadosPorFila[id] ? Array.from(comentariosSeleccionadosPorFila[id]) : [];

                    // Solo incluir registros que tengan una acción seleccionada
                    // o si quieres enviar todos los registros independientemente de si cambió el estatus,
                    // puedes quitar esta condición `if (accion)` o ajustarla.
                    // Por ahora, solo enviaremos los que tienen un estatus definido.
                    if (accion) {
                        registrosParaActualizar.push({
                            id: id,
                            accion: accion, // 'estatus' en el backend
                            comentarios: comentariosArray
                        });
                        alMenosUnCambio = true;
                    } else {
                        // Opcional: si quieres también enviar registros sin acción pero con comentarios nuevos.
                        // if (comentariosArray.length > 0) {
                        //     registrosParaActualizar.push({
                        //         id: id,
                        //         accion: null, // O el estatus actual si lo puedes obtener
                        //         comentarios: comentariosArray
                        //     });
                        //    alMenosUnCambio = true;
                        // }
                    }
                });

                if (!alMenosUnCambio) {
                    alert('No hay cambios para actualizar. Selecciona un estatus en al menos un registro.');
                    return;
                }

                if (!confirm('¿Estás seguro de que deseas actualizar los registros seleccionados masivamente?')) {
                    return;
                }

                // Deshabilitar botón para evitar múltiples envíos
                $(this).prop('disabled', true).text('Procesando...');

                $.ajax({
                    url: '{{ route("kanban.actualizarMasivo") }}', // NUEVA RUTA que necesitarás crear en Laravel
                    method: 'POST',
                    data: {
                        registros: registrosParaActualizar // Enviamos el array de registros
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.mensaje || 'Registros actualizados correctamente.');
                        cargarRegistrosHoy(); // Recargar la tabla para ver los cambios
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        let errorMsg = 'Error al actualizar los registros.';
                        if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                            errorMsg = xhr.responseJSON.mensaje;
                        } else if(xhr.responseJSON && xhr.responseJSON.errors){
                            // Si Laravel devuelve errores de validación específicos
                            try {
                                const errors = xhr.responseJSON.errors;
                                let messages = [];
                                for(const key in errors){
                                    messages.push(errors[key].join("\n"));
                                }
                                errorMsg = messages.join("\n");
                            } catch (e) {
                                // No hacer nada, usar el mensaje genérico
                            }
                        }
                        alert(errorMsg);
                    },
                    complete: function() {
                        // Volver a habilitar el botón
                        $('#btn-actualizar-todo').prop('disabled', false).text('Guardar Cambios Masivos');
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
