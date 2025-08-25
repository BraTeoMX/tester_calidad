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
        // --- REFERENCIAS A ELEMENTOS DEL DOM ---
        const btnBuscar = document.getElementById('btnBuscar');
        const resultadoDiv = document.getElementById('resultadoBusqueda');
        const formularioResultados = document.getElementById('contenedorFormularioResultados');
        const estilosSelect = document.getElementById('estilosSelect');
        const tallaSelect = document.getElementById('tallaSelect');
        const colorInput = document.getElementById('colorInput');
        const cantidadInput = document.getElementById('cantidadInput');
        const tamanoMuestraInput = document.getElementById('tamanoMuestraInput');
        const guardarForm = document.getElementById('guardarFormulario');
        const accionesSelect = document.getElementById('accionesSelect');
        const comentariosCell = document.getElementById('comentariosCell');
        const comentariosHeader = document.getElementById('comentariosHeader');
        const comentariosInput = document.getElementById('comentariosInput');
        const defectosCell = document.getElementById('defectosCell');
        const defectosHeader = document.getElementById('defectosHeader');

        // --- VARIABLES DE ESTADO (EXISTENTES) ---
        let auditoriaData = [];
        let defectosSeleccionados = []; 

        // --- MANEJO DE EVENTOS ---
        // Llamamos a nuestra nueva función aquí, al cargar la página.
        inicializarSelect2Defectos();

        // Evento para mostrar/ocultar secciones de Comentarios y Defectos
        accionesSelect.addEventListener('change', function() {
            const accionSeleccionada = this.value;
            const mostrarComentarios = (accionSeleccionada === 'Aprobado con condicion');
            
            comentariosHeader.classList.toggle('d-none', !mostrarComentarios);
            comentariosCell.classList.toggle('d-none', !mostrarComentarios);
            comentariosInput.required = mostrarComentarios;
            if (!mostrarComentarios) comentariosInput.value = '';

            const mostrarDefectos = (accionSeleccionada === 'Rechazado');
            defectosHeader.classList.toggle('d-none', !mostrarDefectos);
            defectosCell.classList.toggle('d-none', !mostrarDefectos);

            // Si se oculta, limpiamos los defectos para evitar enviar datos incorrectos
            if (!mostrarDefectos && defectosSeleccionados.length > 0) {
                devolverTodosLosDefectosAlSelect();
                defectosSeleccionados = [];
                renderizarListaDefectos();
            }
        });

        /**
         * Dibuja la lista de defectos en el contenedor basado en el arreglo 'defectosSeleccionados'.
         */
        function renderizarListaDefectos() {
            const container = document.getElementById('listaDefectosContainer');
            container.innerHTML = ''; // Limpia el contenido anterior

            defectosSeleccionados.forEach((defecto) => { // quitamos el 'index' de aquí
                const itemDiv = document.createElement('div');
                itemDiv.className = 'd-flex align-items-center mb-1';
                // Añadimos data-id al div principal para identificarlo
                itemDiv.innerHTML = `
                    <span class="mr-2 text-white">${defecto.nombre}:</span>
                    <input type="number" class="form-control form-control-sm defecto-cantidad" style="width: 70px;" value="${defecto.cantidad}" min="1" data-id="${defecto.id}">
                    <button type="button" class="btn btn-danger btn-sm ml-2 eliminar-defecto" data-id="${defecto.id}">Eliminar</button>
                `;
                container.appendChild(itemDiv);
            });
        }
        // NECESITAMOS USAR DELEGACIÓN DE EVENTOS CON JQUERY PARA LOS ELEMENTOS DINÁMICOS
        // Añade este bloque nuevo en tu script, donde están los otros listeners.
        $('#listaDefectosContainer').on('change', '.defecto-cantidad', function() {
            const defectoId = $(this).data('id');
            const nuevaCantidad = parseInt($(this).val());
            
            // Encuentra el defecto en el arreglo por su ID
            const defecto = defectosSeleccionados.find(d => d.id == defectoId);
            if (defecto) {
                if (nuevaCantidad > 0) {
                    defecto.cantidad = nuevaCantidad;
                } else {
                    $(this).val(1); // Resetea a 1 si el valor es inválido
                    defecto.cantidad = 1;
                }
            }
        });

        $('#listaDefectosContainer').on('click', '.eliminar-defecto', function() {
            const defectoId = $(this).data('id');
            
            // Encuentra el índice del defecto a eliminar
            const index = defectosSeleccionados.findIndex(d => d.id == defectoId);
            
            if (index > -1) {
                const defectoEliminado = defectosSeleccionados.splice(index, 1)[0];
                // Devuelve la opción al Select2 para que pueda ser seleccionada de nuevo
                $('#defectosSelect').append(new Option(defectoEliminado.nombre, defectoEliminado.id));
                renderizarListaDefectos(); // Vuelve a dibujar la lista actualizada
            }
        });

        /**
         * Devuelve todas las opciones seleccionadas al Select2. Útil para resetear.
         */
        function devolverTodosLosDefectosAlSelect() {
            defectosSeleccionados.forEach(defecto => {
                // Previene añadir duplicados si la opción ya existe en el select
                if ($('#defectosSelect').find(`option[value="${defecto.id}"]`).length === 0) {
                    $('#defectosSelect').append(new Option(defecto.nombre, defecto.id));
                }
            });
        }

        /**
         * Maneja la creación de un nuevo defecto a través de un prompt.
         */
        async function handleCrearNuevoDefecto() {
            const { value: nuevoDefectoNombre } = await Swal.fire({
                title: 'Crear Nuevo Defecto',
                input: 'text',
                inputLabel: 'Nombre del nuevo defecto',
                inputPlaceholder: 'Escribe el nombre aquí...',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return '¡Necesitas escribir algo!'
                    }
                }
            });

            if (nuevoDefectoNombre) {
                // Guardar el nuevo defecto en la BD
                fetch("{{ route('etiquetasV3.guardarDefecto') }}", {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ Defectos: nuevoDefectoNombre })
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success) {
                        const nuevoDefecto = response.defecto;
                        // Agregarlo directamente a nuestra lista y renderizar
                        defectosSeleccionados.push({ id: nuevoDefecto.id, nombre: nuevoDefecto.Defectos, cantidad: 1 });
                        renderizarListaDefectos();
                        Swal.fire('Éxito', 'Defecto creado y agregado.', 'success');
                    } else {
                        Swal.fire('Error', 'No se pudo guardar el defecto (posiblemente ya existe).', 'error');
                    }
                });
            }
        }

        /**
         * Se encarga de inicializar el Select2 y llenarlo con los datos del servidor.
         * Esta función se ejecuta una sola vez al cargar la página.
         */
        function inicializarSelect2Defectos() {
            const $defectosSelect = $('#defectosSelect');
            $defectosSelect.select2({ placeholder: 'Cargando defectos...', width: '100%' });

            fetch("{{ route('etiquetasV3.obtenerDefectos') }}")
                .then(res => res.json())
                .then(defectos => {
                    const opciones = defectos.map(defecto => ({
                        id: defecto.id,
                        text: defecto.Defectos
                    }));
                    $defectosSelect.empty().select2({
                        placeholder: '-- Seleccionar Defectos --',
                        width: '100%',
                        data: [
                            { id: '', text: '' },
                            { id: 'crear_nuevo', text: '--- Crear Nuevo Defecto ---' },
                            ...opciones
                        ]
                    });
                })
                .catch(error => {
                    console.error("Error al cargar los defectos:", error);
                    $defectosSelect.select2({ placeholder: 'Error al cargar defectos', width: '100%' });
                });
            
            // REEMPLAZA el bloque $defectosSelect.on('change', ...) con este:
            $defectosSelect.on('change', function() {
                const id = $(this).val();
                const nombre = $(this).find('option:selected').text();
                
                if (!id) return; // No hacer nada si se deselecciona

                if (id === 'crear_nuevo') {
                    handleCrearNuevoDefecto(); 
                } else {
                    // 1. Añade el defecto al arreglo de estado
                    defectosSeleccionados.push({ id: id, nombre: nombre, cantidad: 1 });
                    // 2. Elimina la opción del select para no repetirla
                    $(this).find('option:selected').remove();
                    // 3. Actualiza la vista para que muestre el nuevo item
                    renderizarListaDefectos();
                }
                // Resetea el select para que muestre el placeholder
                $(this).val(null).trigger('change.select2');
            });
        }

        // 1. AL HACER CLIC EN "BUSCAR"
        btnBuscar.addEventListener('click', function () {
            const tipo = document.getElementById('tipoEtiqueta').value;
            const orden = document.getElementById('valorEtiqueta').value;

            if (!tipo || !orden) {
                Swal.fire('Atención', 'Por favor, completa todos los campos de búsqueda.', 'warning');
                return;
            }
            
            // Muestra un indicador de carga
            btnBuscar.disabled = true;
            btnBuscar.innerText = 'Buscando...';

            fetch("{{ route('etiquetasV3.buscar') }}", {
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
            .then(response => {
                if (response.success) {
                    // Guardamos todos los datos en nuestra variable
                    auditoriaData = response.data;
                    // Procesamos los datos para llenar los selects
                    procesarResultados();
                } else {
                    Swal.fire('Información', response.message, 'info');
                    // Ocultamos el formulario si ya estaba visible
                    formularioResultados.classList.add('d-none');
                }
            })
            .catch(err => {
                console.error('Error en la petición:', err);
                Swal.fire('Error', 'Ocurrió un error inesperado al buscar.', 'error');
            })
            .finally(() => {
                // Restaura el botón de búsqueda
                btnBuscar.disabled = false;
                btnBuscar.innerText = 'Buscar';
            });
        });

        // 2. AL CAMBIAR LA SELECCIÓN DE "ESTILO"
        estilosSelect.addEventListener('change', function () {
            const estiloSeleccionado = this.value;
            resetearCamposDesde('talla'); // Limpia los campos dependientes

            if (!estiloSeleccionado) return;

            // Filtramos las tallas ÚNICAS para el estilo seleccionado (todo en memoria, ¡súper rápido!)
            const tallasParaEstilo = [...new Set(
                auditoriaData
                    .filter(item => item.estilo === estiloSeleccionado)
                    .map(item => item.talla)
            )];
            
            // Llenamos el select de tallas
            poblarSelect(tallaSelect, tallasParaEstilo, '-- Seleccionar Talla --');
            tallaSelect.disabled = false;
        });

        // 3. AL CAMBIAR LA SELECCIÓN DE "TALLA"
        tallaSelect.addEventListener('change', function () {
            const estiloSeleccionado = estilosSelect.value;
            const tallaSeleccionada = this.value;
            resetearCamposDesde('inputs'); // Limpia los inputs

            if (!tallaSeleccionada) return;

            // Buscamos el registro exacto que coincide (de nuevo, en memoria)
            const registroEncontrado = auditoriaData.find(item => 
                item.estilo === estiloSeleccionado && item.talla.toString() === tallaSeleccionada
            );

            if (registroEncontrado) {
                colorInput.value = registroEncontrado.color;
                cantidadInput.value = registroEncontrado.cantidad;
                tamanoMuestraInput.value = registroEncontrado.muestreo;
            }
        });


        // --- FUNCIONES AUXILIARES ---

        function procesarResultados() {
            resetearFormularioResultados();
            
            // Obtenemos todos los estilos ÚNICOS del set de datos
            const estilosUnicos = [...new Set(auditoriaData.map(item => item.estilo))];
            
            if (estilosUnicos.length > 0) {
                poblarSelect(estilosSelect, estilosUnicos, '-- Seleccionar Estilo --');
                // Mostramos el formulario de resultados
                formularioResultados.classList.remove('d-none');
            } else {
                formularioResultados.classList.add('d-none');
                Swal.fire('Información', 'No se encontraron estilos disponibles para auditar.', 'info');
            }
        }

        function poblarSelect(selectElement, opciones, placeholder) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`; // Limpiar y poner placeholder
            opciones.forEach(opcion => {
                const optionElement = document.createElement('option');
                optionElement.value = opcion;
                optionElement.textContent = opcion;
                selectElement.appendChild(optionElement);
            });
        }

        function resetearFormularioResultados() {
            document.getElementById('guardarFormulario').reset(); // Resetea todo el form
            resetearCamposDesde('estilo');
        }

        function resetearCamposDesde(nivel) {
            if (nivel === 'estilo') {
                estilosSelect.innerHTML = '<option value="">-- Seleccionar Estilo --</option>';
            }
            if (nivel === 'estilo' || nivel === 'talla') {
                tallaSelect.innerHTML = '<option value="">-- Seleccionar Talla --</option>';
                tallaSelect.disabled = true;
            }
            if (nivel === 'estilo' || nivel === 'talla' || nivel === 'inputs') {
                colorInput.value = '';
                cantidadInput.value = '';
                tamanoMuestraInput.value = '';
            }
        }

        // 4. AL ENVIAR EL FORMULARIO PARA GUARDAR
        guardarForm.addEventListener('submit', function (e) {
            e.preventDefault();
            
            const accionCorrectiva = accionesSelect.value;
            
            // --- Validaciones del lado del cliente ---
            if (!estilosSelect.value || !tallaSelect.value || !accionCorrectiva) {
                Swal.fire('Atención', 'Debes seleccionar Estilo, Talla y Acción Correctiva.', 'warning');
                return;
            }
            if (accionCorrectiva === 'Aprobado con condicion' && comentariosInput.value.trim() === '') {
                Swal.fire('Atención', 'Debes agregar un comentario para "Aprobado con condición".', 'warning');
                return;
            }
            if (accionCorrectiva === 'Rechazado' && defectosSeleccionados.length === 0) {
                Swal.fire('Atención', 'Debes agregar al menos un defecto para "Rechazado".', 'warning');
                return;
            }

            // --- CONSTRUCCIÓN DEL OBJETO DE DATOS (AQUÍ ESTÁ LA CORRECCIÓN) ---
            const datosFormulario = {
                estilo: estilosSelect.value,
                talla: tallaSelect.value,
                color: colorInput.value,
                cantidad: cantidadInput.value,
                muestreo: tamanoMuestraInput.value,
                accion_correctiva: accionCorrectiva,
                comentarios: comentariosInput.value,
                tipoEtiqueta: document.getElementById('tipoEtiqueta').value,
                valorEtiqueta: document.getElementById('valorEtiqueta').value,
            };
            
            // ▼▼▼ LÓGICA CONDICIONAL CLAVE ▼▼▼
            // Solo añadimos el arreglo de defectos al objeto SI la acción es 'Rechazado'
            if (accionCorrectiva === 'Rechazado') {
                datosFormulario.defectos = defectosSeleccionados;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerText = 'Guardando...';

            fetch("{{ route('etiquetasV3.guardar') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(datosFormulario)
            })
            .then(async res => {
                // Mejoramos el manejo de errores para distinguir 422 de 500
                if (!res.ok) {
                    if (res.status === 422) {
                        const errorData = await res.json();
                        const errorMessages = Object.values(errorData.errors).flat().join('<br>');
                        throw new Error(errorMessages);
                    }
                    // Para errores 500 u otros, lanzamos un error genérico
                    throw new Error('Ocurrió un error en el servidor (Error ' + res.status + ')');
                }
                return res.json();
            })
            .then(response => {
                if (response.success) {
                    Swal.fire('¡Éxito!', response.message, 'success');
                    auditoriaData = auditoriaData.filter(item => 
                        !(item.estilo === datosFormulario.estilo && 
                        item.talla.toString() === datosFormulario.talla &&
                        item.color === datosFormulario.color)
                    );
                    procesarResultados();
                    cargarRegistrosDelDia();
                }
            })
            .catch(err => {
                console.error('Error al guardar:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Guardar',
                    html: err.message
                });
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerText = 'Guardar Auditoría';
            });
        });

        // --- CÓDIGO NUEVO PARA LA TABLA DE REGISTROS DEL DÍA ---

        const tablaRegistrosContainer = document.getElementById('tablaRegistrosDelDia');

        /**
         * Carga y renderiza la tabla de registros del día.
         */
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

        // --- LLAMADA INICIAL ---
        // Carga los registros del día cuando la página esté lista.
        cargarRegistrosDelDia();
    });
</script>
@endsection