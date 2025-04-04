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
                        <form id="formKanban">
                            <table class="table tabla-kanban">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>OP</th>
                                        <th>Fecha Corte</th>
                                        <th>Cliente</th>
                                        <th>estilo</th>
                                        <th>Piezas</th>
                                        <th>ACCION</th>
                                        <th>COMENTARIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select id="selectOP" class="form-control select-op"></select>
                                        </td> 
                                        <td>
                                            <span id="fechaText"></span>
                                            <input type="hidden" id="fechaInput" name="fecha">
                                        </td>
                                        <td>
                                            <span id="clienteText"></span>
                                            <input type="hidden" id="clienteInput" name="cliente">
                                        </td>
                                        <td>
                                            <span id="estiloText"></span>
                                            <input type="hidden" id="estiloInput" name="estilo">
                                        </td>
                                        
                                        <td>
                                            <span id="piezasText"></span>
                                            <input type="hidden" id="piezasInput" name="piezas_total">
                                        </td>
                                        <td>
                                            <select class="form-control" id="selectAccion">
                                                <option value="">selecciona una opcion</option>
                                                <option value="1">Aceptado</option>
                                                <option value="2">Parcial</option>
                                                <option value="3">Rechazado</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select id="selectComentario" class="form-control select-comentario"></select>
                                            <div id="selectedOptionsContainerComentario" class="w-100 mb-2" required title="Por favor, selecciona una opción"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="submit" class="btn-verde-xd">Guardar</button>
                        </form>
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
                    <h3>Registros por dia - ordenes liberadas en AMP </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabla-registros-hoy" class="table">
                            <thead class="thead-primary">
                                <tr>
                                    <th>FECHA DE ALMACEN</th>
                                    <th>OP</th>
                                    <th>CLIENTE</th>
                                    <th>ESTILO</th>
                                    <th>ESTATUS</th>
                                    <th>COMENTARIOS</th>
                                    <th>FECHA DE PARCIAL</th>
                                    <th>FECHA DE LIBERACION</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Se cargará vía AJAX -->
                            </tbody>
                        </table>
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
            min-width: 100px; /* Fecha Corte */
        }
    
        .tabla-kanban tbody td:nth-child(3) {
            min-width: 150px; /* Cliente */
        }
    
        .tabla-kanban tbody td:nth-child(4) {
            min-width: 100px; /* Estilo */
        }
    
        .tabla-kanban tbody td:nth-child(5) {
            min-width: 70px; /* Piezas */
            text-align: center;
        }
    
        .tabla-kanban tbody td:nth-child(6) {
            min-width: 120px; /* Acción */
        }
    
        .tabla-kanban tbody td:nth-child(7) {
            min-width: 160px; /* Comentario */
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
            const selectedIdsComentario = new Set();
            const selectedOptionsContainerComentario = $('#selectedOptionsContainerComentario');

            $('#selectComentario').select2({
                placeholder: 'Selecciona un comentario',
                templateResult: function (data) {
                    if (data.id === 'crear_comentario') {
                        return $('<span style="color: white; font-weight: bold;">' + data.text + '</span>');
                    }
                    return data.text;
                },
                ajax: {
                    url: '{{ route('kanban.comentarios') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function (data) {
                        const results = data.map(comentario => ({
                            id: comentario.nombre,
                            text: comentario.nombre
                        }));
                        results.unshift({ id: 'crear_comentario', text: 'Crear comentario' });
                        return { results };
                    },
                    cache: true
                },
                minimumInputLength: 0
            });

            $('#selectComentario').on('select2:select', function (e) {
                const data = e.params.data;

                if (data.id === 'crear_comentario') {
                    $('#selectComentario').val(null).trigger('change');

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
                        success: function (response) {
                            const comentarioCreado = response.comentario;

                            if (!selectedIdsComentario.has(comentarioCreado.nombre)) {
                                addOptionToContainer(comentarioCreado.nombre, comentarioCreado.nombre);
                                selectedIdsComentario.add(comentarioCreado.nombre);
                            }

                            alert('Comentario creado correctamente');
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                            alert('Error al crear el comentario');
                        }
                    });

                    return;
                }

                if (!selectedIdsComentario.has(data.id)) {
                    addOptionToContainer(data.id, data.text);
                    selectedIdsComentario.add(data.id);
                }

                $('#selectComentario').val(null).trigger('change');
            });

            function addOptionToContainer(id, text) {
                const optionElement = $(`
                    <div class="selected-option d-flex align-items-center justify-content-between border rounded p-2 mb-1" data-id="${id}">
                        <span class="option-text flex-grow-1 mx-2">${text}</span>
                        <button type="button" class="btn btn-danger btn-sm remove-option">Eliminar</button>
                    </div>
                `);

                optionElement.find('.remove-option').on('click', function () {
                    optionElement.remove();
                    selectedIdsComentario.delete(id);
                });

                selectedOptionsContainerComentario.append(optionElement);
            }

            $('#formKanban').on('submit', function (e) {
                e.preventDefault();

                let comentariosSeleccionados = [];
                $('#selectedOptionsContainerComentario .selected-option').each(function () {
                    let texto = $(this).find('.option-text').text();
                    comentariosSeleccionados.push(texto);
                });

                let op = $('#selectOP').val();
                let accion = $('#selectAccion').val();

                if (!accion) {
                    alert('Por favor selecciona una acción válida antes de continuar.');
                    $('#selectAccion').focus();
                    return;
                }

                let dataFormulario = {
                    comentarios: comentariosSeleccionados,
                    op: op,
                    accion: accion,
                    cliente: $('#clienteInput').val(),
                    estilo: $('#estiloInput').val(),
                    fecha: $('#fechaInput').val(),
                    piezas_total: $('#piezasInput').val()
                };

                $.ajax({
                    url: '{{ route("kanban.guardar") }}',
                    method: 'POST',
                    data: dataFormulario,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert('Guardado correctamente');
                        $('#selectAccion').val('');
                        $('#selectedOptionsContainerComentario').empty();
                        selectedIdsComentario.clear();
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('Error al guardar');
                    }
                });
            });
        });
    </script>

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
        $(document).ready(function () {
            function cargarRegistrosHoy() {
                $.ajax({
                    url: '{{ route("kanban.registrosHoy") }}',
                    method: 'GET',
                    success: function (data) {
                        let tbody = '';

                        data.forEach(function (item) {

                            tbody += `
                                <tr>
                                    <td>${item.fecha_almacen}</td>
                                    <td>${item.op}</td>
                                    <td>${item.cliente}</td>
                                    <td>${item.estilo}</td>
                                    <td>${item.estatus}</td>
                                    <td>${item.comentarios}</td>
                                    <td>${item.fecha_parcial}</td>
                                    <td>${item.fecha_liberacion}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm btn-eliminar" data-id="${item.id}">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        $('#tabla-registros-hoy tbody').html(tbody);
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        $('#tabla-registros-hoy tbody').html('<tr><td colspan="8" class="text-center text-danger">Error al cargar los registros.</td></tr>');
                    }
                });
            }

            // Llama la función al cargar la página
            cargarRegistrosHoy();

            // Evento para eliminar
            $('#tabla-registros-hoy').on('click', '.btn-eliminar', function () {
                const id = $(this).data('id');

                if (!confirm('¿Estás seguro de eliminar este registro?')) return;

                $.ajax({
                    url: '{{ route("kanban.eliminar") }}',
                    method: 'POST',
                    data: { id: id },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        alert(response.mensaje);
                        cargarRegistrosHoy(); // vuelve a cargar la tabla
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        alert('Error al eliminar el registro.');
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
        
@endsection
