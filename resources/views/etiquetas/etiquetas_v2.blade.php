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
    <script>
        setTimeout(function(){
            $('.alert').fadeOut('slow', function(){
                $(this).remove();
            });
        }, 5000);
    </script>
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h2>Auditoria Etiquetas</h2>
            </div>
            <div class="card-body">
                <div id="formBuscarEstilos">
                    <div class="form-row d-flex align-items-end">
                        <div class="form-group col-md-4">
                            <label for="tipoEtiqueta">Tipo de búsqueda:</label>
                            <select id="tipoEtiqueta" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="OC">OC</option>
                                <option value="OV">OV</option>
                                <option value="OP">OP</option>
                                <option value="PO">PO</option>
                            </select>
                        </div>
                
                        <div class="form-group col-md-4">
                            <label for="valorEtiqueta">Escribe la orden:</label>
                            <input type="text" id="valorEtiqueta" class="form-control" placeholder="Escribe un valor" required>
                        </div>
                
                        <div class="form-group col-md-4">
                            <button id="btnBuscar" class="btn btn-success">Buscar</button>
                        </div>
                    </div>
                </div>
                
                <!-- Resultado -->
                <div id="resultadoBusqueda" class="mt-4"></div>                

                <!-- Modal Personalizado -->
                <div id="customModal" class="modal-custom">
                    <div class="modal-content-custom">
                        <div class="modal-header-custom">
                            <h5 class="modal-title-custom">Agregar Auditoría Completa</h5>
                            <button id="closeModalBtn" class="close-custom">&times;</button>
                        </div>
                        <div class="modal-body-custom">
                            <!-- Formulario dentro del modal -->
                            <form id="guardarFormularioModal">
                                <input type="hidden" name="registro_manual" value="1">
                                
                                <div class="table-responsive">
                                    <table class="table align-items-center table-flush">
                                        <thead class="thead-primary">
                                            <tr>
                                                <th style="min-width: 150px;">Estilo</th>
                                                <th style="min-width: 150px;">Talla</th>
                                                <th style="min-width: 150px;">Color</th>
                                                <th style="min-width: 150px;">Cantidad</th>
                                                <th style="min-width: 180px;">Tamaño de Muestra</th>
                                                <th style="min-width: 250px;">Defectos</th>
                                                <th style="min-width: 200px;">Acciones Correctivas</th>
                                                <th style="min-width: 250px;" id="comentariosHeaderModal" class="d-none">Comentarios</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <!-- Select Estilo: mantiene la conexión con la lógica original -->
                                                <td>
                                                    <select name="estilo" id="estilosSelectModal" class="form-control" required>
                                                        <option value="">-- Seleccionar --</option>
                                                    </select>
                                                </td>
                                                <!-- Para el modal, los siguientes campos se muestran como input de tipo text -->
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="talla" id="tallaInputModal" class="form-control" placeholder="Escribir talla" required>
                                                        <div class="input-group-append">
                                                            <input type="checkbox" id="tallaCheckbox" class="checkbox-custom">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <input type="text" name="color" class="form-control" id="colorInputModal" placeholder="Escribir color" required>
                                                        <div class="input-group-append">
                                                            <input type="checkbox" id="colorCheckbox" class="checkbox-custom">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><input type="number" name="cantidad" class="form-control" id="cantidadInputModal" placeholder="Escribir cantidad" required min="0" step="1"></td>
                                                <td><input type="number" name="muestreo" class="form-control" id="tamanoMuestraInputModal" placeholder="Escribir tamaño de muestra" required min="0" step="1"></td>
                                                <td>
                                                    <select id="defectosSelectModal" class="form-control">
                                                        <option value="">-- Seleccionar Defectos --</option>
                                                    </select>
                                                    <div id="listaDefectosContainerModal"></div>
                                                    </td>
                                                <td>
                                                <select name="accion_correctiva" id="accionesSelectModal" class="form-control" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <option value="Aprobado">Aprobado</option>
                                                    <option value="Aprobado con condicion">Aprobado con condicion</option>
                                                    <option value="Rechazado">Rechazado</option>
                                                </select>
                                                </td>
                                                <td id="comentariosCellModal" class="d-none"><input type="text" name="comentarios" id="comentariosInputModal" class="form-control" placeholder="Escribe un comentario"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="modal-footer-custom">
                                    <button type="submit" class="btn-custom">Guardar Auditoría</button>
                                    <button type="button" id="closeModalBtnFooter" class="btn-secondary-custom">Cerrar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header">
                <h2>Registros del día</h2>
            </div>
            <div class="card-body">
                <div id="tablaRegistrosDelDia">Cargando registros...</div>
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

        .table-danger1 {
            background-color: #580202  !important; /* Rojo oscuro */
            color: #ffffff; /* Texto blanco para contraste */
        }

    </style>
    <!-- Estilos para el Modal Personalizado -->
    <style>
        /* Estilos para el fondo del modal */
        .modal-custom {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
        }

        /* Contenedor del modal */
        .modal-content-custom {
            background-color: #222;
            color: #fff;
            width: 95%; /* Aumentar el ancho al 95% del viewport */
            max-width: none; /* Eliminar el límite de ancho */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
        }

        /* Ajuste para que el modal no tenga padding lateral en pantallas pequeñas */
        @media (max-width: 768px) {
            .modal-content-custom {
                width: 100%;
                border-radius: 0; /* Eliminar bordes redondeados en pantallas pequeñas */
            }
        }

        /* Encabezado del modal */
        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #444;
            padding-bottom: 10px;
        }

        .close-custom {
            background: none;
            border: none;
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        /* Estilos para el formulario dentro del modal */
        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-custom th, .table-custom td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }

        .input-custom {
            background-color: #333;
            color: #fff;
            border: 1px solid #555;
            padding: 5px;
            width: 100%;
            border-radius: 5px;
        }

        .modal-footer-custom {
            display: flex;
            justify-content: space-between;
            padding-top: 10px;
        }

        .btn-custom {
            background-color: #28a745;
            color: #fff;
            font-size: 18px;  /* Aumentar tamaño de fuente */
            padding: 12px 20px;  /* Más espacio alrededor del texto */
            border: none;
            cursor: pointer;
            border-radius: 8px;  /* Bordes más suaves */
            min-width: 160px; /* Asegurar un tamaño mínimo */
            text-align: center;  /* Centrar el texto */
        }

        .btn-secondary-custom {
            background-color: #6c757d;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-custom:hover, .btn-secondary-custom:hover {
            opacity: 0.8;
        }

        .checkbox-custom {
            margin-left: 10px;
            margin-right: 10px;
            cursor: pointer;
        }

    </style>

    <!-- Script general para inicializar la lógica de defectos -->
    <script>
        function initDefectos(selectSelector, containerSelector, formSelector) {
        // Array para almacenar la lista de defectos seleccionados
        let defectosSeleccionados = [];
    
        // Inicializar Select2 en el select de defectos
        $(selectSelector).select2({
            placeholder: '-- Seleccionar Defectos --',
            allowClear: true,
        });
    
        // Función para redibujar la lista de defectos en el contenedor
        function renderizarListaDefectos() {
            // Limpiar el contenedor
            $(containerSelector).empty();
    
            // Limpiar inputs ocultos existentes en el formulario
            $(formSelector + ' input[name^="defectos"]').remove();
    
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
                defecto.cantidad = $(this).val();
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
                $(selectSelector).append(
                $('<option>', {
                    value: defecto.id,
                    text: defecto.nombre
                })
                );
    
                // Volver a dibujar
                renderizarListaDefectos();
            });
    
            // --- Inputs ocultos para envío en el formulario --- //
            let $inputOcultoNombre = $('<input>').attr({
                type: 'hidden',
                name: `defectos[${index}][nombre]`,
                value: defecto.nombre
            });
    
            let $inputOcultoCantidad = $('<input>').attr({
                type: 'hidden',
                name: `defectos[${index}][cantidad]`,
                value: defecto.cantidad || 1
            });
    
            // Agregar elementos al contenedor del item
            $defectoItem.append($nombreDefecto, $inputCantidad, $btnEliminar);
    
            // Agregar el item al contenedor en la vista
            $(containerSelector).append($defectoItem);
    
            // Agregar los inputs ocultos al formulario
            $(formSelector).append($inputOcultoNombre, $inputOcultoCantidad);
            });
        }
    
        // Cargar defectos mediante AJAX
        $.ajax({
            url: "{{ route('obtenerDefectosEtiquetas') }}",
            method: 'GET',
            success: function(response) {
            if (response) {
                // Agregar la opción "OTRO"
                $(selectSelector).append(
                $('<option>', {
                    value: 'otro',
                    text: 'OTRO'
                })
                );
    
                // Agregar defectos retornados por AJAX
                response.forEach(function(defecto) {
                $(selectSelector).append(
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
    
        // Manejar el cambio en el select de defectos
        $(selectSelector).on('change', function() {
            let seleccionado = $(this).val();
            let textoSeleccionado = $(this).find('option:selected').text();
    
            if (seleccionado === 'otro') {
            // Mostrar prompt para capturar nuevo defecto
            let nuevoDefecto = prompt("Por favor, introduce el nuevo defecto:");
    
            if (nuevoDefecto) {
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
                    $(selectSelector).append(
                        $('<option>', {
                        value: response.id,
                        text: nuevoDefecto
                        })
                    );
                    $(selectSelector).val(response.id).trigger('change');
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
                $(selectSelector).val(null).trigger('change');
            }
            } else if (seleccionado) {
            // Verificar si ya existe en defectosSeleccionados
            let existe = defectosSeleccionados.some(function(def) {
                return def.id == seleccionado;
            });
    
            if (!existe) {
                defectosSeleccionados.push({
                id: seleccionado,
                nombre: textoSeleccionado
                });
                // Remover la opción seleccionada
                $(this).find('option[value="' + seleccionado + '"]').remove();
                // Reiniciar el select
                $(this).val(null).trigger('change');
                renderizarListaDefectos();
            } else {
                $(this).val(null).trigger('change');
            }
            }
        });
        }
    
        // Inicializamos la lógica para el formulario principal
        initDefectos('#defectosSelect', '#listaDefectosContainer', '#guardarFormulario');
    
        // Inicializamos la lógica para el formulario del modal
        // usando los selectores correspondientes del modal
        initDefectos('#defectosSelectModal', '#listaDefectosContainerModal', '#guardarFormularioModal');
    </script>  
    <script>
        $(document).ready(function() {
            // No uses .hide() al cargar la página; basta con la clase d-none que ya tienes en el HTML
            
            // Detecta cambios en el select de Acciones Correctivas
            $('#accionesSelect').on('change', function() {
                let selectedValue = $(this).val();
                
                if (selectedValue === 'Aprobado con condicion') {
                    // Quita la clase d-none para mostrar la columna "Comentarios"
                    $('#comentariosHeader, #comentariosCell').removeClass('d-none');
                    // Hace obligatorio el campo
                    $('#comentariosInput').attr('required', true);
                } else {
                    // Vuelve a poner la clase d-none para ocultar la columna "Comentarios"
                    $('#comentariosHeader, #comentariosCell').addClass('d-none');
                    // Quita la obligatoriedad
                    $('#comentariosInput').removeAttr('required');
                }
            });
        });
        $(document).ready(function() {
            // No uses .hide() al cargar la página; basta con la clase d-none que ya tienes en el HTML
            
            // Detecta cambios en el select de Acciones Correctivas
            $('#accionesSelectModal').on('change', function() {
                let selectedValue = $(this).val();
                
                if (selectedValue === 'Aprobado con condicion') {
                    // Quita la clase d-none para mostrar la columna "Comentarios"
                    $('#comentariosHeaderModal, #comentariosCellModal').removeClass('d-none');
                    // Hace obligatorio el campo
                    $('#comentariosInputModal').attr('required', true);
                } else {
                    // Vuelve a poner la clase d-none para ocultar la columna "Comentarios"
                    $('#comentariosHeaderModal, #comentariosCellModal').addClass('d-none');
                    // Quita la obligatoriedad
                    $('#comentariosInputModal').removeAttr('required');
                }
            });
        });
    </script>    
    
    <!-- Script para abrir y cerrar el Modal -->
    <script>
        function initModalManual() {
            let modal = document.getElementById("customModal");
            let openModalBtn = document.getElementById("openModalBtn");
            let closeModalBtn = document.getElementById("closeModalBtn");
            let closeModalBtnFooter = document.getElementById("closeModalBtnFooter");
        
            function cerrarModal() {
                modal.style.display = "none";
            }
        
            if (openModalBtn) {
                openModalBtn.addEventListener("click", function () {
                    modal.style.display = "flex";
                });
            }
        
            if (closeModalBtn) {
                closeModalBtn.addEventListener("click", cerrarModal);
            }
        
            if (closeModalBtnFooter) {
                closeModalBtnFooter.addEventListener("click", cerrarModal);
            }
        
            window.addEventListener("click", function (event) {
                if (event.target === modal) {
                    cerrarModal();
                }
            });
        
            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    cerrarModal();
                }
            });
        }
    </script>        
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let tallaInput = document.getElementById("tallaInputModal");
            let colorInput = document.getElementById("colorInputModal");
            let tallaCheckbox = document.getElementById("tallaCheckbox");
            let colorCheckbox = document.getElementById("colorCheckbox");

            function toggleInputState(input, checkbox) {
                if (checkbox.checked) {
                    input.disabled = true;   // Bloquear input
                    input.removeAttribute("required"); // Quitar validación obligatoria
                    input.value = ""; // Limpiar el campo al bloquearlo
                } else {
                    input.disabled = false;  // Habilitar input
                    input.setAttribute("required", "required"); // Hacerlo obligatorio
                }
            }

            tallaCheckbox.addEventListener("change", function() {
                toggleInputState(tallaInput, tallaCheckbox);
            });

            colorCheckbox.addEventListener("change", function() {
                toggleInputState(colorInput, colorCheckbox);
            });
        });

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnBuscar = document.getElementById('btnBuscar');
            const resultadoDiv = document.getElementById('resultadoBusqueda');
        
            btnBuscar.addEventListener('click', function () {
                const tipo = document.getElementById('tipoEtiqueta').value;
                const orden = document.getElementById('valorEtiqueta').value;
        
                if (!tipo || !orden) {
                    resultadoDiv.innerHTML = '<p class="text-danger">Completa todos los campos.</p>';
                    return;
                }
        
                resultadoDiv.innerHTML = '<p>Cargando resultados...</p>';
        
                fetch("{{ route('etiquetas_v2.procesarAjax') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        tipoEtiqueta: tipo,
                        valorEtiqueta: orden
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        resultadoDiv.innerHTML = `<p class="text-warning">${data.message}</p>`;
                        return;
                    }
        
                    const selectEstilos = data.estilos.map(estilo => `
                        <option value="${estilo.Estilos}">${estilo.Estilos}</option>
                    `).join('');
        
                    // Renderiza el formulario completo
                    resultadoDiv.innerHTML = `
                        <h4 class="mt-4">Estilos encontrados:</h4>
                        <form id="guardarFormulario" action="{{ route('guardarAuditoriaEtiqueta') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tipoEtiqueta" value="${data.tipoBusqueda}">
                            <input type="hidden" name="valorEtiqueta" value="${data.orden}">
        
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Estilo</th>
                                            <th>Talla</th>
                                            <th>Color</th>
                                            <th>Cantidad</th>
                                            <th>Muestreo</th>
                                            <th>Defectos</th>
                                            <th>Acciones Correctivas</th>
                                            <th id="comentariosHeader" class="d-none">Comentarios</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="estilo" id="estilosSelect" class="form-control" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    ${selectEstilos}
                                                </select>
                                            </td>
                                            <td>
                                                <select name="talla" id="tallaSelect" class="form-control" disabled required>
                                                    <option value="">-- Seleccionar --</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="color" class="form-control texto-blanco" id="colorInput" readonly></td>
                                            <td><input type="text" name="cantidad" class="form-control texto-blanco" id="cantidadInput" readonly></td>
                                            <td><input type="text" name="muestreo" class="form-control texto-blanco" id="tamanoMuestraInput" readonly></td>
                                            <td>
                                                <select id="defectosSelect" class="form-control">
                                                    <option value="">-- Seleccionar Defectos --</option>
                                                </select>
                                                <div id="listaDefectosContainer"></div>
                                            </td>
                                            <td>
                                                <select name="accion_correctiva" id="accionesSelect" class="form-control" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <option value="Aprobado">Aprobado</option>
                                                    <option value="Aprobado con condicion">Aprobado con condicion</option>
                                                    <option value="Rechazado">Rechazado</option>
                                                </select>
                                            </td>
                                            <td id="comentariosCell" class="d-none">
                                                <input type="text" name="comentarios" id="comentariosInput" class="form-control" placeholder="Escribe un comentario">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    <button type="submit" class="btn-custom">Guardar Auditoría</button>
                                </div>
                            </div>
                        </form>
                        <!-- Botón para abrir el modal -->
                        <div class="mt-3">
                            <button id="openModalBtn" class="btn btn-secondary">Ingresar datos no encontrados</button>
                        </div>

                    `;
                    // Activar Select2 si lo usas
                    $('#estilosSelect').select2();
                    $('#tallaSelect').select2();

                    // Cargar los estilos también en el modal
                    const estilosModal = document.getElementById('estilosSelectModal');
                    if (estilosModal) {
                        estilosModal.innerHTML = `
                            <option value="">-- Seleccionar --</option>
                            ${selectEstilos}
                        `;
                        $('#estilosSelectModal').select2();
                    }

                    // ✅ Inicializar lógica de defectos dinámicamente
                    initDefectos('#defectosSelect', '#listaDefectosContainer', '#guardarFormulario');
                    // Inicializar apertura/cierre del modal
                    initModalManual();
        
                }).then(() => {
                    // Aquí viene tu AJAX preexistente pero usando delegación
                    const tipoBusqueda = $('#tipoEtiqueta').val();
                    const orden = $('#valorEtiqueta').val();
        
                    // Delegación para cambio de Estilo
                    $(document).off('change', '#estilosSelect').on('change', '#estilosSelect', function () {
                        const estiloSeleccionado = $(this).val();
        
                        $('#tallaSelect').html('<option value="">-- Seleccionar --</option>').prop('disabled', true).trigger('change');
                        $('#colorInput').val('');
                        $('#cantidadInput').val('');
                        $('#tamanoMuestraInput').val('');
        
                        if (!estiloSeleccionado) return;
        
                        $.ajax({
                            url: "{{ route('ajaxGetTallas') }}",
                            method: 'GET',
                            data: {
                                tipoBusqueda: tipoBusqueda,
                                orden: orden,
                                estilo: estiloSeleccionado
                            },
                            success: function (response) {
                                if (response.success) {
                                    response.tallas.forEach(function (t) {
                                        $('#tallaSelect').append(
                                            $('<option>', { value: t, text: t })
                                        );
                                    });
                                    $('#tallaSelect').prop('disabled', false).trigger('change');
                                }
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    });
        
                    // Delegación para cambio de Talla
                    $(document).off('change', '#tallaSelect').on('change', '#tallaSelect', function () {
                        const tallaSeleccionada = $(this).val();
                        const estiloSeleccionado = $('#estilosSelect').val();
        
                        $('#colorInput').val('');
                        $('#cantidadInput').val('');
                        $('#tamanoMuestraInput').val('');
        
                        if (!tallaSeleccionada || !estiloSeleccionado) return;
        
                        $.ajax({
                            url: "{{ route('ajaxGetData') }}",
                            method: 'GET',
                            data: {
                                tipoBusqueda: tipoBusqueda,
                                orden: orden,
                                estilo: estiloSeleccionado,
                                talla: tallaSeleccionada
                            },
                            success: function (response) {
                                if (response.success && response.data) {
                                    $('#colorInput').val(response.data.color);
                                    $('#cantidadInput').val(response.data.cantidad);
                                    $('#tamanoMuestraInput').val(response.data.tamaño_muestra);
                                } else {
                                    $('#colorInput').val('N/A');
                                    $('#cantidadInput').val('0');
                                    $('#tamanoMuestraInput').val('');
                                }
                            },
                            error: function (xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    });
                })

                .catch(err => {
                    resultadoDiv.innerHTML = `<p class="text-danger">Error al procesar la solicitud.</p>`;
                    console.error(err);
                });
            });
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const contenedor = document.getElementById("tablaRegistrosDelDia");
    
            fetch("{{ route('registros.del.dia.ajax.etiqueta') }}")
                .then(res => res.json())
                .then(data => {
                    if (!data.success || data.registros.length === 0) {
                        contenedor.innerHTML = `<p>No se encontraron registros para el día de hoy.</p>`;
                        return;
                    }
    
                    const rows = data.registros.map(registro => `
                        <tr class="${registro.isRechazado ? 'table-danger1' : ''}">
                            <td>${registro.tipo}</td>
                            <td>${registro.orden}</td>
                            <td>${registro.estilo}</td>
                            <td>${registro.color}</td>
                            <td>${registro.cantidad}</td>
                            <td>${registro.muestreo}</td>
                            <td>
                                ${registro.estatus === 'Rechazado'
                                    ? `<select class="form-control select-estatus" data-id="${registro.id}">
                                        <option value="Rechazado" selected>Rechazado</option>
                                        <option value="Aprobado">Aprobado</option>
                                       </select>`
                                    : registro.estatus}
                            </td>
                            <td><ul>${registro.defectos.map(d => `<li>${d}</li>`).join('')}</ul></td>
                            <td>${registro.comentario}</td>
                        </tr>
                    `).join('');
    
                    contenedor.innerHTML = `
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
                                        <th>Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `;
                    // ✅ Reactivar el comportamiento del <select>
                    activarCambioDeEstatus();
                })

                .catch(err => {
                    console.error(err);
                    contenedor.innerHTML = `<p class="text-danger">Ocurrió un error al cargar los registros.</p>`;
                });

                function activarCambioDeEstatus() {
                    const selects = document.querySelectorAll('.select-estatus');

                    selects.forEach(select => {
                        select.addEventListener('change', function (event) {
                            const confirmar = confirm('¿Deseas cambiar el estatus?');
                            if (!confirmar) {
                                event.target.value = 'Rechazado';
                                return;
                            }

                            const registroId = event.target.getAttribute('data-id');
                            const nuevoEstatus = event.target.value;

                            fetch(`/reporte-etiquetas/${registroId}/update-status`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ estatus: nuevoEstatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Estatus actualizado correctamente.');
                                    location.reload(); // Puedes quitar esto si prefieres recargar solo los registros con fetch
                                } else {
                                    alert('Ocurrió un error al actualizar el estatus.');
                                    event.target.value = 'Rechazado';
                                }
                            })
                            .catch(error => {
                                console.error(error);
                                alert('Error en la petición.');
                                event.target.value = 'Rechazado';
                            });
                        });
                    });
                }
        });
    </script>
    
    
@endsection
