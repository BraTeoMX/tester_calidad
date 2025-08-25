@extends('layouts.app', ['pageSlug' => 'Etiquetas', 'titlePage' => __('Etiquetas')])

@section('content')
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
                        <input type="text" id="valorEtiqueta" class="form-control" placeholder="Escribe un valor"
                            required>
                    </div>

                    <div class="form-group col-md-4">
                        <button id="btnBuscar" class="btn btn-success">Buscar</button>
                    </div>
                </div>
            </div>

            <!-- Resultado -->
            <div id="resultadoBusqueda" class="mt-4">

                <div id="contenedorFormularioResultados" class="d-none">
                    <h4 class="mt-4">Registrar Auditoría:</h4>
                    <form id="guardarFormulario">
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>Estilo</th>
                                        <th>Talla</th>
                                        <th>Color</th>
                                        <th>Cantidad</th>
                                        <th>Muestreo</th>
                                        <th>Acciones Correctivas</th>
                                        <th id="defectosHeader" class="d-none">Defectos</th>
                                        <th id="comentariosHeader" class="d-none">Comentarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select name="estilo" id="estilosSelect" class="form-control" required>
                                                <option value="">-- Seleccionar Estilo --</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="talla" id="tallaSelect" class="form-control" required
                                                disabled>
                                                <option value="">-- Seleccionar Talla --</option>
                                            </select>
                                        </td>
                                        <td><input type="text" name="color" class="form-control texto-blanco"
                                                id="colorInput" readonly></td>
                                        <td><input type="text" name="cantidad" class="form-control texto-blanco"
                                                id="cantidadInput" readonly></td>
                                        <td><input type="text" name="muestreo" class="form-control texto-blanco"
                                                id="tamanoMuestraInput" readonly></td>
                                        <td>
                                            <select name="accion_correctiva" id="accionesSelect" class="form-control"
                                                required>
                                                <option value="">-- Seleccionar --</option>
                                                <option value="Aprobado">Aprobado</option>
                                                <option value="Aprobado con condicion">Aprobado con condicion</option>
                                                <option value="Rechazado">Rechazado</option>
                                            </select>
                                        </td>
                                        <td id="defectosCell" class="d-none">
                                            <select id="defectosSelect" class="form-control" style="width: 100%;">
                                            </select>
                                            <div id="listaDefectosContainer" class="mt-2">
                                            </div>
                                        </td>
                                        <td id="comentariosCell" class="d-none">
                                            <input type="text" name="comentarios" id="comentariosInput"
                                                class="form-control" placeholder="Escribe un comentario">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="mt-3 d-flex justify-content-between">
                                <button type="submit" class="btn-custom">Guardar Auditoría</button>
                                <button type="button" id="openModalBtn" class="btn btn-secondary">Ingresar datos no
                                    encontrados</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
                                            <th style="min-width: 200px;">Acciones Correctivas</th>
                                            <th style="min-width: 250px;" id="defectosHeaderModal" class="d-none">
                                                Defectos</th>
                                            <th style="min-width: 250px;" id="comentariosHeaderModal" class="d-none">
                                                Comentarios</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <!-- Select Estilo: mantiene la conexión con la lógica original -->
                                            <td>
                                                <select name="estilo" id="estilosSelectModal" class="form-control"
                                                    required>
                                                    <option value="">-- Seleccionar --</option>
                                                </select>
                                            </td>
                                            <!-- Para el modal, los siguientes campos se muestran como input de tipo text -->
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" name="talla" id="tallaInputModal"
                                                        class="form-control" placeholder="Escribir talla" required>
                                                    <div class="input-group-append">
                                                        <input type="checkbox" id="tallaCheckbox"
                                                            class="checkbox-custom">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" name="color" class="form-control"
                                                        id="colorInputModal" placeholder="Escribir color" required>
                                                    <div class="input-group-append">
                                                        <input type="checkbox" id="colorCheckbox"
                                                            class="checkbox-custom">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><input type="number" name="cantidad" class="form-control"
                                                    id="cantidadInputModal" placeholder="Escribir cantidad" required
                                                    min="0" step="1"></td>
                                            <td><input type="number" name="muestreo" class="form-control"
                                                    id="tamanoMuestraInputModal"
                                                    placeholder="Escribir tamaño de muestra" required min="0" step="1">
                                            </td>
                                            <td>
                                                <select name="accion_correctiva" id="accionesSelectModal"
                                                    class="form-control" required>
                                                    <option value="">-- Seleccionar --</option>
                                                    <option value="Aprobado">Aprobado</option>
                                                    <option value="Aprobado con condicion">Aprobado con condicion
                                                    </option>
                                                    <option value="Rechazado">Rechazado</option>
                                                </select>
                                            </td>
                                            <td id="defectosCellModal" class="d-none">
                                                <select id="defectosSelectModal" class="form-control">
                                                    <option value="">-- Seleccionar Defectos --</option>
                                                </select>
                                                <div id="listaDefectosContainerModal"></div>
                                            </td>

                                            <td id="comentariosCellModal" class="d-none"><input type="text"
                                                    name="comentarios" id="comentariosInputModal" class="form-control"
                                                    placeholder="Escribe un comentario"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer-custom">
                                <button type="submit" class="btn-custom">Guardar Auditoría</button>
                                <button type="button" id="closeModalBtnFooter"
                                    class="btn-secondary-custom">Cerrar</button>
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
        color: #333;
        /* Color del texto */
    }

    .texto-blanco {
        color: white !important;
    }

    .table-danger1 {
        background-color: #580202 !important;
        /* Rojo oscuro */
        color: #ffffff;
        /* Texto blanco para contraste */
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
        width: 95%;
        /* Aumentar el ancho al 95% del viewport */
        max-width: none;
        /* Eliminar el límite de ancho */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(255, 255, 255, 0.2);
    }

    /* Ajuste para que el modal no tenga padding lateral en pantallas pequeñas */
    @media (max-width: 768px) {
        .modal-content-custom {
            width: 100%;
            border-radius: 0;
            /* Eliminar bordes redondeados en pantallas pequeñas */
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

    .table-custom th,
    .table-custom td {
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
        font-size: 18px;
        /* Aumentar tamaño de fuente */
        padding: 12px 20px;
        /* Más espacio alrededor del texto */
        border: none;
        cursor: pointer;
        border-radius: 8px;
        /* Bordes más suaves */
        min-width: 160px;
        /* Asegurar un tamaño mínimo */
        text-align: center;
        /* Centrar el texto */
    }

    .btn-secondary-custom {
        background-color: #6c757d;
        color: #fff;
        padding: 10px 15px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
    }

    .btn-custom:hover,
    .btn-secondary-custom:hover {
        opacity: 0.8;
    }

    .checkbox-custom {
        margin-left: 10px;
        margin-right: 10px;
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ==========================================================
        // === REFERENCIAS A ELEMENTOS Y ESTADO - FORMULARIO PRINCIPAL
        // ==========================================================
        const btnBuscar = document.getElementById('btnBuscar');
        const formularioResultados = document.getElementById('contenedorFormularioResultados');
        const estilosSelect = document.getElementById('estilosSelect');
        const tallaSelect = document.getElementById('tallaSelect');
        const colorInput = document.getElementById('colorInput');
        const cantidadInput = document.getElementById('cantidadInput');
        const tamanoMuestraInput = document.getElementById('tamanoMuestraInput');
        const guardarForm = document.getElementById('guardarFormulario');
        const tablaRegistrosContainer = document.getElementById('tablaRegistrosDelDia');
        const accionesSelect = document.getElementById('accionesSelect');
        const comentariosCell = document.getElementById('comentariosCell');
        const comentariosHeader = document.getElementById('comentariosHeader');
        const comentariosInput = document.getElementById('comentariosInput');
        const defectosCell = document.getElementById('defectosCell');
        const defectosHeader = document.getElementById('defectosHeader');
        
        let auditoriaData = [];
        let defectosSeleccionados = []; // Estado para el formulario principal

        // ==========================================================
        // === LÓGICA DEL SISTEMA DE DEFECTOS - FORMULARIO PRINCIPAL
        // ==========================================================

        // Dibuja la lista de defectos seleccionados en el formulario principal
        function renderizarListaDefectos() {
            const container = document.getElementById('listaDefectosContainer');
            container.innerHTML = '';
            defectosSeleccionados.forEach((defecto) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'd-flex align-items-center mb-1';
                itemDiv.innerHTML = `
                    <span class="mr-2 text-white">${defecto.nombre}:</span>
                    <input type="number" class="form-control form-control-sm defecto-cantidad" style="width: 70px;" value="${defecto.cantidad}" min="1" data-id="${defecto.id}">
                    <button type="button" class="btn btn-danger btn-sm ml-2 eliminar-defecto" data-id="${defecto.id}">Eliminar</button>
                `;
                container.appendChild(itemDiv);
            });
        }

        // Inicializa el Select2 y toda la lógica de defectos para el formulario principal
        function inicializarSelect2Defectos() {
            const $defectosSelect = $('#defectosSelect');
            $defectosSelect.select2({ placeholder: 'Cargando defectos...', width: '100%' });

            fetch("{{ route('etiquetasV3.obtenerDefectos') }}")
                .then(res => res.json())
                .then(defectos => {
                    const opciones = defectos.map(defecto => ({ id: defecto.id, text: defecto.Defectos }));
                    $defectosSelect.empty().select2({
                        placeholder: '-- Seleccionar Defectos --', width: '100%',
                        data: [{ id: '', text: '' }, { id: 'crear_nuevo', text: '--- Crear Nuevo Defecto ---' }, ...opciones]
                    });
                });

            $defectosSelect.on('change', function() {
                const id = $(this).val();
                if (!id) return;
                if (id === 'crear_nuevo') {
                    handleCrearNuevoDefecto();
                } else {
                    const nombre = $(this).find('option:selected').text();
                    defectosSeleccionados.push({ id: id, nombre: nombre, cantidad: 1 });
                    $(this).find('option:selected').remove();
                    renderizarListaDefectos();
                }
                $(this).val(null).trigger('change.select2');
            });
        }

        // Maneja la creación de un nuevo defecto desde el formulario principal
        async function handleCrearNuevoDefecto() {
            const { value: nuevoDefectoNombre } = await Swal.fire({
                title: 'Crear Nuevo Defecto', input: 'text', inputLabel: 'Nombre del nuevo defecto',
                inputPlaceholder: 'Escribe el nombre aquí...', showCancelButton: true,
                inputValidator: (v) => !v && '¡Necesitas escribir algo!'
            });
            if (nuevoDefectoNombre) {
                fetch("{{ route('etiquetasV3.guardarDefecto') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ Defectos: nuevoDefectoNombre })
                }).then(res => res.json()).then(response => {
                    if (response.success) {
                        defectosSeleccionados.push({ id: response.defecto.id, nombre: response.defecto.Defectos, cantidad: 1 });
                        renderizarListaDefectos();
                        Swal.fire('Éxito', 'Defecto creado y agregado.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo guardar el defecto (posiblemente ya existe).', 'error');
                    }
                });
            }
        }

        // Eventos para actualizar cantidad y eliminar defectos del formulario principal
        $('#listaDefectosContainer').on('change', '.defecto-cantidad', function() {
            const defectoId = $(this).data('id');
            const nuevaCantidad = parseInt($(this).val());
            const defecto = defectosSeleccionados.find(d => d.id == defectoId);
            if (defecto) {
                defecto.cantidad = nuevaCantidad > 0 ? nuevaCantidad : 1;
                if(nuevaCantidad <= 0) $(this).val(1);
            }
        });

        $('#listaDefectosContainer').on('click', '.eliminar-defecto', function() {
            const defectoId = $(this).data('id');
            const index = defectosSeleccionados.findIndex(d => d.id == defectoId);
            if (index > -1) {
                const defectoEliminado = defectosSeleccionados.splice(index, 1)[0];
                $('#defectosSelect').append(new Option(defectoEliminado.nombre, defectoEliminado.id));
                renderizarListaDefectos();
            }
        });

        // Lógica de visibilidad para el formulario principal
        accionesSelect.addEventListener('change', function() {
            const accion = this.value;
            const mostrarComentarios = (accion === 'Aprobado con condicion');
            comentariosHeader.classList.toggle('d-none', !mostrarComentarios);
            comentariosCell.classList.toggle('d-none', !mostrarComentarios);
            comentariosInput.required = mostrarComentarios;
            if (!mostrarComentarios) comentariosInput.value = '';

            const mostrarDefectos = (accion === 'Rechazado');
            defectosHeader.classList.toggle('d-none', !mostrarDefectos);
            defectosCell.classList.toggle('d-none', !mostrarDefectos);
            if (!mostrarDefectos) {
                defectosSeleccionados.forEach(defecto => {
                    if ($('#defectosSelect').find(`option[value="${defecto.id}"]`).length === 0) {
                        $('#defectosSelect').append(new Option(defecto.nombre, defecto.id));
                    }
                });
                defectosSeleccionados = [];
                renderizarListaDefectos();
            }
        });

        // ==========================================================
        // === LÓGICA DE BÚSQUEDA Y FORMULARIO PRINCIPAL
        // ==========================================================

        btnBuscar.addEventListener('click', function () {
            const tipo = document.getElementById('tipoEtiqueta').value;
            const orden = document.getElementById('valorEtiqueta').value;
            if (!tipo || !orden) {
                Swal.fire('Atención', 'Por favor, completa todos los campos de búsqueda.', 'warning');
                return;
            }
            btnBuscar.disabled = true;
            btnBuscar.innerText = 'Buscando...';
            fetch("{{ route('etiquetasV3.buscar') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify({ tipoEtiqueta: tipo, valorEtiqueta: orden })
            })
            .then(res => res.json())
            .then(response => {
                if (response.success) {
                    auditoriaData = response.data;
                    procesarResultados();
                } else {
                    Swal.fire('Información', response.message, 'info');
                    formularioResultados.classList.add('d-none');
                }
            })
            .catch(err => Swal.fire('Error', 'Ocurrió un error inesperado al buscar.', 'error'))
            .finally(() => {
                btnBuscar.disabled = false;
                btnBuscar.innerText = 'Buscar';
            });
        });

        estilosSelect.addEventListener('change', function () {
            resetearCamposDesde('talla');
            if (!this.value) return;
            const tallasParaEstilo = [...new Set(auditoriaData.filter(item => item.estilo === this.value).map(item => item.talla))];
            poblarSelect(tallaSelect, tallasParaEstilo, '-- Seleccionar Talla --');
            tallaSelect.disabled = false;
        });

        tallaSelect.addEventListener('change', function () {
            resetearCamposDesde('inputs');
            if (!this.value) return;
            const registro = auditoriaData.find(item => item.estilo === estilosSelect.value && item.talla.toString() === this.value);
            if (registro) {
                colorInput.value = registro.color;
                cantidadInput.value = registro.cantidad;
                tamanoMuestraInput.value = registro.muestreo;
            }
        });

        guardarForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const accionCorrectiva = accionesSelect.value;

            if (!estilosSelect.value || !tallaSelect.value || !accionCorrectiva) {
                return Swal.fire('Atención', 'Debes seleccionar Estilo, Talla y Acción Correctiva.', 'warning');
            }
            if (accionCorrectiva === 'Aprobado con condicion' && comentariosInput.value.trim() === '') {
                return Swal.fire('Atención', 'Debes agregar un comentario.', 'warning');
            }
            if (accionCorrectiva === 'Rechazado' && defectosSeleccionados.length === 0) {
                return Swal.fire('Atención', 'Debes agregar al menos un defecto.', 'warning');
            }

            const datosFormulario = {
                estilo: estilosSelect.value, talla: tallaSelect.value, color: colorInput.value,
                cantidad: cantidadInput.value, muestreo: tamanoMuestraInput.value,
                accion_correctiva: accionCorrectiva, comentarios: comentariosInput.value,
                tipoEtiqueta: document.getElementById('tipoEtiqueta').value,
                valorEtiqueta: document.getElementById('valorEtiqueta').value,
            };
            
            if (accionCorrectiva === 'Rechazado') {
                datosFormulario.defectos = defectosSeleccionados;
            }
            
            enviarFormularioAuditoria(datosFormulario, this.querySelector('button[type="submit"]'), () => {
                auditoriaData = auditoriaData.filter(item => !(item.estilo === datosFormulario.estilo && item.talla.toString() === datosFormulario.talla));
                procesarResultados();
                cargarRegistrosDelDia();
            });
        });

        
        // ==========================================================
        // === SECCIÓN DEL MODAL PARA INGRESO MANUAL
        // ==========================================================

        // --- REFERENCIAS A ELEMENTOS Y ESTADO - MODAL ---
        const guardarFormularioModal = document.getElementById('guardarFormularioModal');
        const estilosSelectModal = document.getElementById('estilosSelectModal');
        const tallaInputModal = document.getElementById('tallaInputModal');
        const colorInputModal = document.getElementById('colorInputModal');
        const tallaCheckbox = document.getElementById('tallaCheckbox');
        const colorCheckbox = document.getElementById('colorCheckbox');
        const accionesSelectModal = document.getElementById('accionesSelectModal');
        const comentariosHeaderModal = document.getElementById('comentariosHeaderModal');
        const comentariosCellModal = document.getElementById('comentariosCellModal');
        const comentariosInputModal = document.getElementById('comentariosInputModal');
        const defectosHeaderModal = document.getElementById('defectosHeaderModal');
        const defectosCellModal = document.getElementById('defectosCellModal');
        
        let defectosSeleccionadosModal = []; // Estado INDEPENDIENTE para el modal

        // --- LÓGICA DEL SISTEMA DE DEFECTOS - MODAL ---
        
        function renderizarListaDefectosModal() {
            const container = document.getElementById('listaDefectosContainerModal');
            container.innerHTML = '';
            defectosSeleccionadosModal.forEach((defecto) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'd-flex align-items-center mb-1';
                itemDiv.innerHTML = `
                    <span class="mr-2 text-white">${defecto.nombre}:</span>
                    <input type="number" class="form-control form-control-sm defecto-cantidad-modal" style="width: 70px;" value="${defecto.cantidad}" min="1" data-id="${defecto.id}">
                    <button type="button" class="btn btn-danger btn-sm ml-2 eliminar-defecto-modal" data-id="${defecto.id}">Eliminar</button>
                `;
                container.appendChild(itemDiv);
            });
        }

        function inicializarSelect2DefectosModal() {
            const $defectosSelectModal = $('#defectosSelectModal');
            $defectosSelectModal.select2({ placeholder: 'Cargando defectos...', width: '100%' });

            fetch("{{ route('etiquetasV3.obtenerDefectos') }}")
                .then(res => res.json())
                .then(defectos => {
                    const opciones = defectos.map(defecto => ({ id: defecto.id, text: defecto.Defectos }));
                    $defectosSelectModal.empty().select2({
                        placeholder: '-- Seleccionar Defectos --', width: '100%',
                        data: [{ id: '', text: '' }, { id: 'crear_nuevo', text: '--- Crear Nuevo Defecto ---' }, ...opciones]
                    });
                });

            $defectosSelectModal.on('change', function() {
                const id = $(this).val();
                if (!id) return;
                if (id === 'crear_nuevo') {
                    handleCrearNuevoDefectoModal();
                } else {
                    const nombre = $(this).find('option:selected').text();
                    defectosSeleccionadosModal.push({ id: id, nombre: nombre, cantidad: 1 });
                    $(this).find('option:selected').remove();
                    renderizarListaDefectosModal();
                }
                $(this).val(null).trigger('change.select2');
            });
        }

        async function handleCrearNuevoDefectoModal() {
            // ... (Exactamente la misma lógica que handleCrearNuevoDefecto, pero opera sobre el estado del modal)
            const { value: nuevoDefectoNombre } = await Swal.fire({
                title: 'Crear Nuevo Defecto', input: 'text', inputLabel: 'Nombre del nuevo defecto',
                inputPlaceholder: 'Escribe el nombre aquí...', showCancelButton: true,
                inputValidator: (v) => !v && '¡Necesitas escribir algo!'
            });
            if (nuevoDefectoNombre) {
                fetch("{{ route('etiquetasV3.guardarDefecto') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ Defectos: nuevoDefectoNombre })
                }).then(res => res.json()).then(response => {
                    if (response.success) {
                        defectosSeleccionadosModal.push({ id: response.defecto.id, nombre: response.defecto.Defectos, cantidad: 1 });
                        renderizarListaDefectosModal();
                        Swal.fire('Éxito', 'Defecto creado y agregado.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo guardar el defecto.', 'error');
                    }
                });
            }
        }

        $('#listaDefectosContainerModal').on('change', '.defecto-cantidad-modal', function() {
            const defectoId = $(this).data('id');
            const nuevaCantidad = parseInt($(this).val());
            const defecto = defectosSeleccionadosModal.find(d => d.id == defectoId);
            if (defecto) {
                defecto.cantidad = nuevaCantidad > 0 ? nuevaCantidad : 1;
                if(nuevaCantidad <= 0) $(this).val(1);
            }
        });

        $('#listaDefectosContainerModal').on('click', '.eliminar-defecto-modal', function() {
            const defectoId = $(this).data('id');
            const index = defectosSeleccionadosModal.findIndex(d => d.id == defectoId);
            if (index > -1) {
                const defectoEliminado = defectosSeleccionadosModal.splice(index, 1)[0];
                $('#defectosSelectModal').append(new Option(defectoEliminado.nombre, defectoEliminado.id));
                renderizarListaDefectosModal();
            }
        });

        accionesSelectModal.addEventListener('change', function() {
            const accion = this.value;
            const mostrarComentarios = (accion === 'Aprobado con condicion');
            comentariosHeaderModal.classList.toggle('d-none', !mostrarComentarios);
            comentariosCellModal.classList.toggle('d-none', !mostrarComentarios);
            comentariosInputModal.required = mostrarComentarios;
            if (!mostrarComentarios) comentariosInputModal.value = '';

            const mostrarDefectos = (accion === 'Rechazado');
            defectosHeaderModal.classList.toggle('d-none', !mostrarDefectos);
            defectosCellModal.classList.toggle('d-none', !mostrarDefectos);
            if (!mostrarDefectos) {
                defectosSeleccionadosModal.forEach(defecto => {
                    if ($('#defectosSelectModal').find(`option[value="${defecto.id}"]`).length === 0) {
                        $('#defectosSelectModal').append(new Option(defecto.nombre, defecto.id));
                    }
                });
                defectosSeleccionadosModal = [];
                renderizarListaDefectosModal();
            }
        });

        // --- LÓGICA DE APERTURA Y ENVÍO DEL MODAL ---
        function initModalManual() {
            let modal = document.getElementById("customModal");
            let openModalBtn = document.getElementById("openModalBtn");
            let closeModalBtn = document.getElementById("closeModalBtn");
            let closeModalBtnFooter = document.getElementById("closeModalBtnFooter");
            function cerrarModal() { modal.style.display = "none"; }
            
            openModalBtn.addEventListener("click", function () {
                // ¡AQUÍ ESTÁ LA CLAVE! Usamos los datos de la búsqueda actual.
                if (auditoriaData.length === 0) {
                    Swal.fire('Atención', 'Primero debes realizar una búsqueda para obtener la lista de estilos.', 'warning');
                    return;
                }
                const estilosUnicos = [...new Set(auditoriaData.map(item => item.estilo))];
                poblarSelect(estilosSelectModal, estilosUnicos, '-- Seleccionar Estilo --');
                modal.style.display = "flex";
            });
            
            closeModalBtn.addEventListener("click", cerrarModal);
            closeModalBtnFooter.addEventListener("click", cerrarModal);
            window.addEventListener("click", (event) => { if (event.target === modal) cerrarModal(); });
            document.addEventListener("keydown", (event) => { if (event.key === "Escape") cerrarModal(); });
        }

        // --- Lógica mejorada para los checkboxes del modal ---
        tallaCheckbox.addEventListener('change', function() {
            tallaInputModal.disabled = this.checked;
            tallaInputModal.required = !this.checked;
            if (this.checked) tallaInputModal.value = '';
        });

        colorCheckbox.addEventListener('change', function() {
            colorInputModal.disabled = this.checked;
            colorInputModal.required = !this.checked;
            if (this.checked) colorInputModal.value = '';
        });

        guardarFormularioModal.addEventListener('submit', function (e) {
            e.preventDefault();
            const form = this;
            const accionCorrectiva = accionesSelectModal.value;

            // Validaciones específicas del modal
            if (!estilosSelectModal.value || !accionCorrectiva) {
                return Swal.fire('Atención', 'Debes seleccionar Estilo y Acción Correctiva.', 'warning');
            }
            if (!tallaCheckbox.checked && !tallaInputModal.value.trim()){
                return Swal.fire('Atención', 'El campo Talla es obligatorio.', 'warning');
            }
            if (!colorCheckbox.checked && !colorInputModal.value.trim()){
                return Swal.fire('Atención', 'El campo Color es obligatorio.', 'warning');
            }
            if (accionCorrectiva === 'Aprobado con condicion' && comentariosInputModal.value.trim() === '') {
                return Swal.fire('Atención', 'Debes agregar un comentario.', 'warning');
            }
            if (accionCorrectiva === 'Rechazado' && defectosSeleccionadosModal.length === 0) {
                return Swal.fire('Atención', 'Debes agregar al menos un defecto.', 'warning');
            }

            const datosFormulario = {
                tipoEtiqueta: document.getElementById('tipoEtiqueta').value,
                valorEtiqueta: document.getElementById('valorEtiqueta').value,
                estilo: form.estilo.value, talla: form.talla.value, color: form.color.value,
                cantidad: form.cantidad.value, muestreo: form.muestreo.value,
                accion_correctiva: accionCorrectiva, comentarios: form.comentarios.value,
                registro_manual: 1, // ¡Importante!
            };
            
            if (accionCorrectiva === 'Rechazado') {
                datosFormulario.defectos = defectosSeleccionadosModal;
            }

            enviarFormularioAuditoria(datosFormulario, this.querySelector('button[type="submit"]'), () => {
                // Callback de éxito para el modal
                form.reset();
                tallaInputModal.required = true;
                colorInputModal.required = true;
                // Limpiar y resetear defectos del modal
                defectosSeleccionadosModal.forEach(defecto => {
                    if ($('#defectosSelectModal').find(`option[value="${defecto.id}"]`).length === 0) {
                        $('#defectosSelectModal').append(new Option(defecto.nombre, defecto.id));
                    }
                });
                defectosSeleccionadosModal = [];
                renderizarListaDefectosModal();
                // Cerrar modal y recargar tabla
                document.getElementById('customModal').style.display = 'none';
                cargarRegistrosDelDia();
            });
        });


        // ==========================================================
        // === FUNCIONES AUXILIARES Y DE LA TABLA DE REGISTROS
        // ==========================================================

        function enviarFormularioAuditoria(datos, submitButton, onSuccessCallback) {
            submitButton.disabled = true;
            submitButton.innerText = 'Guardando...';

            fetch("{{ route('etiquetasV3.guardar') }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(async res => {
                if (!res.ok) {
                    const errorData = await res.json().catch(() => ({}));
                    const msj = errorData.errors ? Object.values(errorData.errors).flat().join('<br>') : `Error en el servidor (${res.status})`;
                    throw new Error(msj);
                }
                return res.json();
            })
            .then(response => {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    onSuccessCallback();
                }
            })
            .catch(err => Swal.fire({ icon: 'error', title: 'Error al Guardar', html: err.message }))
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerText = 'Guardar Auditoría';
            });
        }

        function procesarResultados() {
            resetearFormularioResultados();
            const estilosUnicos = [...new Set(auditoriaData.map(item => item.estilo))];
            if (estilosUnicos.length > 0) {
                poblarSelect(estilosSelect, estilosUnicos, '-- Seleccionar Estilo --');
                formularioResultados.classList.remove('d-none');
            } else {
                formularioResultados.classList.add('d-none');
                Swal.fire('Información', 'No se encontraron estilos pendientes para esta orden.', 'info');
            }
        }

        function poblarSelect(selectElement, opciones, placeholder) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            opciones.forEach(opcion => {
                const el = document.createElement('option');
                el.value = opcion;
                el.textContent = opcion;
                selectElement.appendChild(el);
            });
        }

        function resetearFormularioResultados() {
            guardarForm.reset();
            resetearCamposDesde('estilo');
            // Resetear defectos del formulario principal
            defectosSeleccionados.forEach(defecto => {
                if ($('#defectosSelect').find(`option[value="${defecto.id}"]`).length === 0) {
                $('#defectosSelect').append(new Option(defecto.nombre, defecto.id));
                }
            });
            defectosSeleccionados = [];
            renderizarListaDefectos();
        }

        function resetearCamposDesde(nivel) {
            if (['estilo', 'talla', 'inputs'].includes(nivel)) {
                colorInput.value = '';
                cantidadInput.value = '';
                tamanoMuestraInput.value = '';
            }
            if (['estilo', 'talla'].includes(nivel)) {
                tallaSelect.innerHTML = '<option value="">-- Seleccionar Talla --</option>';
                tallaSelect.disabled = true;
            }
            if (nivel === 'estilo') {
                estilosSelect.innerHTML = '<option value="">-- Seleccionar Estilo --</option>';
            }
        }

        function cargarRegistrosDelDia() {
            tablaRegistrosContainer.innerHTML = 'Cargando registros...'; // Muestra un mensaje de carga

            fetch("{{ route('etiquetasV3.registrosDelDia') }}")
                .then(res => res.json())
                .then(data => {
                    if (!data.success || data.registros.length === 0) {
                        tablaRegistrosContainer.innerHTML = `<p>No se encontraron registros para el día de hoy.</p>`;
                        return;
                    }
                    
                    const rows = data.registros.map(registro => {
                        const defectosHtml = registro.defectos.length > 0
                            ? `<ul>${registro.defectos.map(d => `<li>${d}</li>`).join('')}</ul>`
                            : 'N/A';
                        
                        // Si el estatus es 'Rechazado', creamos un <select>. De lo contrario, solo texto.
                        const estatusHtml = registro.isRechazado
                            ? `<select class="form-control select-estatus" data-id="${registro.id}">
                                   <option value="Rechazado" selected>Rechazado</option>
                                   <option value="Aprobado">Aprobar</option>
                               </select>`
                            : registro.estatus;

                        return `
                            <tr class="${registro.isRechazado ? 'table-danger1' : ''}" id="registro-${registro.id}">
                                <td>${registro.tipo}</td>
                                <td>${registro.orden}</td>
                                <td>${registro.estilo}</td>
                                <td>${registro.color}</td>
                                <td>${registro.cantidad}</td>
                                <td>${registro.muestreo}</td>
                                <td>${estatusHtml}</td>
                                <td>${defectosHtml}</td>
                                <td>${registro.comentario}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-eliminar" data-id="${registro.id}">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');

                    tablaRegistrosContainer.innerHTML = `
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="thead-primary">
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
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>${rows}</tbody>
                            </table>
                        </div>
                    `;
                })
                .catch(err => {
                    console.error(err);
                    tablaRegistrosContainer.innerHTML = `<p class="text-danger">Ocurrió un error al cargar los registros.</p>`;
                });
        }

        /**
         * Manejo de eventos para la tabla de registros (actualizar y eliminar) usando delegación.
         */
        tablaRegistrosContainer.addEventListener('click', async function (e) {
            // --- Lógica para Eliminar ---
            if (e.target.classList.contains('btn-eliminar')) {
                const registroId = e.target.dataset.id;
                
                const result = await Swal.fire({
                    title: '¿Estás seguro?',
                    text: "No podrás revertir esta acción.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                });

                if (result.isConfirmed) {
                    fetch(`/etiquetasV3/${registroId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', 'El registro ha sido eliminado.', 'success');
                            document.getElementById(`registro-${registroId}`).remove();
                        } else {
                            Swal.fire('Error', 'No se pudo eliminar el registro.', 'error');
                        }
                    });
                }
            }
        });

        tablaRegistrosContainer.addEventListener('change', async function(e) {
            // --- Lógica para Actualizar Estatus ---
            if (e.target.classList.contains('select-estatus')) {
                 const registroId = e.target.dataset.id;
                 const nuevoEstatus = e.target.value;

                 if (nuevoEstatus === 'Aprobado') {
                     const result = await Swal.fire({
                         title: 'Confirmar Cambio',
                         text: '¿Deseas cambiar el estatus a "Aprobado"? Los defectos asociados serán eliminados.',
                         icon: 'question',
                         showCancelButton: true,
                         confirmButtonText: 'Sí, cambiar',
                         cancelButtonText: 'Cancelar'
                     });

                     if (result.isConfirmed) {
                         fetch(`/etiquetasV3/${registroId}/update-status`, {
                             method: 'PUT',
                             headers: {
                                 'Content-Type': 'application/json',
                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                 'Accept': 'application/json'
                             },
                             body: JSON.stringify({ estatus: nuevoEstatus })
                         })
                         .then(res => res.json())
                         .then(data => {
                            if (data.success) {
                                Swal.fire('Actualizado', 'El estatus se cambió a Aprobado.', 'success');
                                cargarRegistrosDelDia(); // Recargamos toda la tabla para reflejar el cambio
                            } else {
                                Swal.fire('Error', 'No se pudo actualizar el estatus.', 'error');
                                e.target.value = 'Rechazado'; // Revertir el cambio en el select
                            }
                         });
                     } else {
                         e.target.value = 'Rechazado'; // Si cancelan, regresa el select a su estado original
                     }
                 }
            }
        });
        
        // ==========================================================
        // === LLAMADAS INICIALES AL CARGAR LA PÁGINA
        // ==========================================================
        inicializarSelect2Defectos(); // Para el form principal
        inicializarSelect2DefectosModal(); // Para el modal
        cargarRegistrosDelDia();
        initModalManual();
    });
</script>
@endsection