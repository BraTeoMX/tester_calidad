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
                            <input type="text" id="search-input" class="form-control" placeholder="Escribe un ID">
                        </div>
                        <button type="button" id="search-button" class="btn btn-primary">Buscar</button>
                    </form>
                    <hr>
                    <!-- Tabla para mostrar resultados -->
                    <div id="search-results" class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const searchResults = document.querySelector('#search-results tbody');

            searchForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Evita que el formulario recargue la página
                realizarBusqueda();
            });

            document.getElementById('search-button').addEventListener('click', function () {
                realizarBusqueda();
            });

            function realizarBusqueda() {
                const searchTerm = searchInput.value.trim(); // Elimina espacios en blanco

                if (!searchTerm) {
                    alert('Por favor, escribe un término de búsqueda.');
                    return;
                }

                // Realiza la petición AJAX
                fetch(`/buscarAql?searchTerm=${searchTerm}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la solicitud al servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Limpia los resultados previos
                        searchResults.innerHTML = '';

                        if (data.data.length === 0) {
                            // Si no hay resultados
                            searchResults.innerHTML = '<tr><td colspan="5">No se encontraron resultados.</td></tr>';
                            return;
                        }

                        // Genera el HTML para los nuevos resultados
                        data.data.forEach(item => {
                            const row = `
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.prodpackticketid}</td>
                                    <td>${item.prodid}</td>
                                    <td>${item.itemid}</td>
                                    <td>${item.payrolldate}</td>
                                </tr>
                            `;
                            searchResults.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        alert(data.message); // Muestra el mensaje de error desde el backend
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un problema al realizar la búsqueda. Verifica la consola para más detalles.');
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const saveButton = document.getElementById('save-button');
            const searchResults = document.querySelector('#search-results tbody');

            // Limpia cualquier registro previo del evento click para evitar duplicados
            saveButton.replaceWith(saveButton.cloneNode(true));
            const newSaveButton = document.getElementById('save-button');

            newSaveButton.addEventListener('click', function () {
                // Obtiene todos los IDs de los registros mostrados en la tabla
                const ids = [];
                searchResults.querySelectorAll('tr').forEach(row => {
                    const id = row.cells[0].textContent; // Toma el ID de la primera celda
                    ids.push(id);
                });

                if (ids.length === 0) {
                    alert('No hay registros para guardar.');
                    return;
                }

                // Realiza la petición AJAX para guardar los registros
                fetch('/guardarAql', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ids }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message); // Muestra el mensaje recibido del servidor
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
    
@endpush
