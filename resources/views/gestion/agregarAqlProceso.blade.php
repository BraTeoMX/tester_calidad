@extends('layouts.app', ['pageSlug' => 'GestionBusqueda', 'titlePage' => __('GestionBusqueda')])

@section('content')

    <div class="row">
        <div class="col-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Sección: AQL</h3>
                </div>
                <div class="card-body">
                    <!-- Formulario de búsqueda -->
                    <form id="search-form">
                        <div class="form-group">
                            <label for="search-input">Buscar por OP:</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Escribe un ID" value="OP00" maxlength="9">
                        </div>
                        <button type="button" id="search-button" class="btn btn-primary">Buscar</button>
                    </form>
                    <hr>
                    <!-- Tabla para mostrar resultados -->
                    <div id="search-results" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Bulto</th>
                                    <th>OP</th>
                                    <th>Estilo</th>
                                    <th>Fecha Corte</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los resultados se inyectarán aquí -->
                            </tbody>
                        </table>
                        <br>
                        <button type="button" id="save-button" class="btn btn-success">Guardar Registros</button>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Sección: PROCESO</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="select-estilo">Seleccionar Estilo:</label>
                        <select id="select-estilo" class="form-control">
                            <option value="">-- Seleccionar Estilo --</option>
                            @foreach ($estilos as $estilo)
                                <option value="{{ $estilo->itemid }}" data-customer="{{ $estilo->customername }}">
                                    {{ $estilo->itemid }} - {{ $estilo->customername }}
                                </option>
                            @endforeach
                        </select>
                    </div>
        
                    <!-- Tabla dinámica -->
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered" id="selected-items-table">
                            <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Estilo</th>
                                    <th>Cliente</th>
                                    <th>Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas dinámicas generadas por JavaScript -->
                            </tbody>
                        </table>
                    </div>
        
                    <!-- Botón para enviar los datos -->
                    <button type="button" id="save-items" class="btn btn-success mt-3">Guardar Registros</button>
                </div>
            </div>
        </div>        
    </div>

    <style>
        thead tr {
            background-color: #343a40; /* Gris oscuro */
            color: white; /* Texto en blanco para contraste */
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Definición de elementos ---
            const searchInput = document.getElementById('search-input');
            const searchButton = document.getElementById('search-button');
            const saveButton = document.getElementById('save-button');
            const searchResultsBody = document.querySelector('#search-results tbody');

            // --- Lógica de Búsqueda ---
            const realizarBusqueda = () => {
                const searchTerm = searchInput.value.trim();

                // ✅ 1. MEJORA: Validación con SweetAlert en lugar de alert()
                if (searchTerm.length !== 9) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Entrada Inválida',
                        text: 'El término de búsqueda debe tener exactamente 9 caracteres.',
                    });
                    return;
                }

                // ✅ 2. MEJORA: Bloqueo del botón para evitar múltiples clics
                searchButton.disabled = true;
                // Opcional: Añadir un indicador visual de carga (requiere Bootstrap CSS)
                searchButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Buscando...
                `;

                fetch(`/buscarAql?searchTerm=${searchTerm}`)
                    .then(response => {
                        // ✅ 3. MEJORA: Manejo de errores más inteligente
                        if (!response.ok) {
                            // Si el error es de validación (422), Laravel envía los detalles.
                            if (response.status === 422) {
                                return response.json().then(errorData => {
                                    // Extraemos el primer mensaje de error que envía Laravel.
                                    const firstError = Object.values(errorData.errors)[0][0];
                                    throw new Error(firstError);
                                });
                            }
                            // Para otros errores del servidor.
                            throw new Error('Error en la comunicación con el servidor.');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success') {
                            renderizarResultados(data.data);
                        } else {
                            // Para errores lógicos que el backend maneje con status 'error'.
                            throw new Error(data.message || 'Ocurrió un error inesperado.');
                        }
                    })
                    .catch(error => {
                        // Mostramos cualquier error capturado con SweetAlert.
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: error.message,
                        });
                        // Limpiamos la tabla en caso de error.
                        renderizarResultados([]);
                    })
                    .finally(() => {
                        // ✅ 4. MEJORA: Reactivación del botón SIEMPRE al finalizar.
                        // El bloque .finally() se ejecuta tanto si la petición fue exitosa como si falló.
                        searchButton.disabled = false;
                        searchButton.innerHTML = 'Buscar';
                    });
            };

            const renderizarResultados = (items) => {
                searchResultsBody.innerHTML = ''; // Limpia resultados previos

                if (items.length === 0) {
                    searchResultsBody.innerHTML = '<tr><td colspan="4">No se encontraron resultados.</td></tr>';
                    return;
                }

                items.forEach(item => {
                    const row = `
                        <tr>
                            <td>${item.prodpackticketid || 'N/A'}</td>
                            <td>${item.prodid || 'N/A'}</td>
                            <td>${item.itemid || 'N/A'}</td>
                            <td>${item.payrolldate || 'N/A'}</td>
                        </tr>
                    `;
                    searchResultsBody.insertAdjacentHTML('beforeend', row);
                });
            };

            // --- Asignación de Eventos ---
            document.getElementById('search-form').addEventListener('submit', (event) => {
                event.preventDefault();
                realizarBusqueda();
            });

            searchButton.addEventListener('click', realizarBusqueda);

            // --- Lógica para Guardar (también mejorada con SweetAlert) ---
            saveButton.addEventListener('click', function () {
                const ids = Array.from(searchResultsBody.querySelectorAll('tr'))
                    .map(row => row.cells[0]?.textContent)
                    .filter(id => id && id !== 'N/A'); // Filtra filas vacías o sin ID

                if (ids.length === 0) {
                    Swal.fire('Atención', 'No hay registros válidos en la tabla para guardar.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Confirmar',
                    text: `¿Estás seguro de que deseas guardar ${ids.length} registro(s)?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Lógica de guardado con Fetch...
                        // (Aquí iría tu fetch a '/guardarAql' con método POST)
                        console.log('Guardando los siguientes IDs:', ids);
                        // Ejemplo de cómo se vería la alerta de éxito:
                        Swal.fire('¡Guardado!', 'Los registros han sido guardados correctamente.', 'success');
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            const tableBody = document.querySelector('#selected-items-table tbody');
            const saveButton = document.getElementById('save-items');

            // Array para almacenar los datos seleccionados
            let selectedItems = [];

            // Inicializa Select2
            const selectEstilo = $('#select-estilo');
            selectEstilo.select2({
                placeholder: "Seleccione un estilo",
                allowClear: true,
                width: '100%'
            });

            // Evento change del select
            selectEstilo.off('change').on('change', function () {
                const itemid = $(this).val();
                const customername = $(this).find(':selected').data('customer');

                if (!itemid) {
                    return;
                }

                // Verificar si el estilo ya está en la lista
                if (selectedItems.some(item => item.itemid === itemid)) {
                    alert('Este estilo ya está en la lista.');
                    return;
                }

                // Agregar el estilo al array
                selectedItems.push({ itemid, customername, modulo: '' });

                // Agregar fila a la tabla
                const row = `
                    <tr data-itemid="${itemid}">
                        <td>
                            <input type="text" class="form-control input-modulo" placeholder="Ingresar módulo" />
                        </td>
                        <td>${itemid}</td>
                        <td>${customername}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-remove" data-itemid="${itemid}">Quitar</button>
                        </td>
                    </tr>
                `;
                tableBody.insertAdjacentHTML('beforeend', row);
            });

            // Evento para quitar un registro
            tableBody.addEventListener('click', function (event) {
                if (event.target.classList.contains('btn-remove')) {
                    const itemid = event.target.dataset.itemid;

                    // Eliminar el registro del array
                    selectedItems = selectedItems.filter(item => item.itemid !== itemid);

                    // Eliminar la fila de la tabla
                    event.target.closest('tr').remove();
                }
            });

            // Evento para capturar el valor del módulo ingresado
            tableBody.addEventListener('input', function (event) {
                if (event.target.classList.contains('input-modulo')) {
                    const row = event.target.closest('tr');
                    const itemid = row.dataset.itemid;
                    
                    // Convertir el valor a mayúsculas
                    event.target.value = event.target.value.toUpperCase();

                    const moduloValue = event.target.value;

                    // Actualizar el array con el valor del módulo
                    const item = selectedItems.find(item => item.itemid === itemid);
                    if (item) {
                        item.modulo = moduloValue; // Almacenar siempre en mayúsculas
                    }
                }
            });

            // Botón para guardar registros
            saveButton.addEventListener('click', function () {
                if (selectedItems.length === 0) {
                    alert('No hay registros para guardar.');
                    return;
                }

                fetch('/guardarModuloEstilo', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ items: selectedItems }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message);
                        // Limpiar tabla y array
                        tableBody.innerHTML = '';
                        selectedItems = [];
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al guardar los registros.');
                });
            });
        });

    </script>
    
@endsection
