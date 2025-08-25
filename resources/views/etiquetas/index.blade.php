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
        
        // Selects e inputs del formulario de resultados
        const estilosSelect = document.getElementById('estilosSelect');
        const tallaSelect = document.getElementById('tallaSelect');
        const colorInput = document.getElementById('colorInput');
        const cantidadInput = document.getElementById('cantidadInput');
        const tamanoMuestraInput = document.getElementById('tamanoMuestraInput');

        // Variable para almacenar todos los datos de la búsqueda actual
        let auditoriaData = [];

        // --- MANEJO DE EVENTOS ---

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

        // Aquí iría la lógica para enviar el formulario #guardarFormulario,
        // para el modal #openModalBtn, y para cargar los registros del día.

    });
</script>
@endsection