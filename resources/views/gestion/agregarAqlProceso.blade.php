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
        background-color: #343a40;
        /* Gris oscuro */
        color: white;
        /* Texto en blanco para contraste */
    }
</style>

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

                // Mostrar SweetAlert de carga
                Swal.fire({
                    title: 'Buscando...',
                    text: 'Realizando búsqueda en la base de datos local.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

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
                    // Cerrar SweetAlert de carga
                    Swal.close();

                    if (data.status === 'success') {
                        // Mostrar mensaje informativo sobre la fuente de datos
                        if (data.source === 'SQLServer_View_OpBusqueda_3_View') {
                            Swal.fire({
                                icon: 'info',
                                title: 'Búsqueda Global',
                                text: data.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                        } else {
                            // Mostrar mensaje de éxito para búsqueda local
                            Swal.fire({
                                icon: 'success',
                                title: 'Búsqueda Exitosa',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }

                        // Limpia los resultados previos
                        searchResults.innerHTML = '';

                        if (data.data.length === 0) {
                            // Si no hay resultados
                            searchResults.innerHTML = '<tr><td colspan="5">No se encontraron resultados.</td></tr>';
                            window.currentSearchResults = [];
                            return;
                        }

                        // Guardar los datos completos para usarlos al guardar
                        window.currentSearchResults = data.data;

                        // Genera el HTML para los nuevos resultados
                        data.data.forEach((item, index) => {
                            const row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.prodpackticketid}</td>
                                    <td>${item.prodid}</td>
                                    <td>${item.itemid}</td>
                                    <td>${item.payrolldate}</td>
                                </tr>
                            `;
                            searchResults.insertAdjacentHTML('beforeend', row);
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.close(); // Cerrar cualquier SweetAlert abierto
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Conexión',
                        text: 'Hubo un problema al realizar la búsqueda. Verifica la consola para más detalles.'
                    });
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
                // Usar los datos originales de la búsqueda que ya contienen toda la información necesaria
                if (!window.currentSearchResults || window.currentSearchResults.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin registros',
                        text: 'No hay registros para guardar.'
                    });
                    return;
                }

                // Mostrar SweetAlert de confirmación
                Swal.fire({
                    title: '¿Guardar registros?',
                    text: `¿Estás seguro de que deseas guardar ${window.currentSearchResults.length} registro(s)?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar SweetAlert de carga
                        Swal.fire({
                            title: 'Guardando...',
                            text: 'Procesando los registros.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Realiza la petición AJAX para guardar los registros
                        fetch('/guardarAql', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({ records: window.currentSearchResults }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Cerrar SweetAlert de carga
                            Swal.close();

                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.close(); // Cerrar cualquier SweetAlert abierto
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'Hubo un problema al guardar los registros.'
                            });
                        });
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

                    // Convertir el valor a mayúsculas automáticamente
                    event.target.value = event.target.value.toUpperCase();

                    const moduloValue = event.target.value;

                    // Actualizar el array con el valor del módulo
                    const item = selectedItems.find(item => item.itemid === itemid);
                    if (item) {
                        item.modulo = moduloValue; // Almacenar siempre en mayúsculas
                    }
                }
            });

            // Función para validar el formato del módulo
            function validarModulo(modulo) {
                if (!modulo || modulo.trim() === '') {
                    return { valido: false, mensaje: 'El campo módulo no puede estar vacío.' };
                }

                // Verificar que contenga al menos un número y una letra mayúscula
                const tieneNumero = /\d/.test(modulo);
                const tieneLetra = /[A-Z]/.test(modulo);

                if (!tieneNumero) {
                    return { valido: false, mensaje: 'El módulo debe contener al menos un número.' };
                }

                if (!tieneLetra) {
                    return { valido: false, mensaje: 'El módulo debe contener al menos una letra mayúscula.' };
                }

                // Verificar que no contenga caracteres inválidos (solo números y letras mayúsculas)
                if (!/^[A-Z0-9]+$/.test(modulo)) {
                    return { valido: false, mensaje: 'El módulo solo puede contener números y letras mayúsculas.' };
                }

                return { valido: true };
            }

            // Botón para guardar registros
            saveButton.addEventListener('click', function () {
                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sin registros',
                        text: 'No hay registros para guardar.'
                    });
                    return;
                }

                // Validar todos los módulos antes de proceder
                let erroresValidacion = [];
                selectedItems.forEach((item, index) => {
                    if (!item.modulo || item.modulo.trim() === '') {
                        erroresValidacion.push(`Fila ${index + 1}: El campo módulo está vacío.`);
                    } else {
                        const validacion = validarModulo(item.modulo);
                        if (!validacion.valido) {
                            erroresValidacion.push(`Fila ${index + 1}: ${validacion.mensaje}`);
                        }
                    }
                });

                // Si hay errores de validación, mostrarlos
                if (erroresValidacion.length > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errores de validación',
                        html: erroresValidacion.join('<br>'),
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }

                // Mostrar SweetAlert de confirmación
                Swal.fire({
                    title: '¿Guardar registros?',
                    text: `¿Estás seguro de que deseas guardar ${selectedItems.length} registro(s) de módulo y estilo?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar SweetAlert de carga
                        Swal.fire({
                            title: 'Guardando...',
                            text: 'Procesando los registros de módulo y estilo.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            willOpen: () => {
                                Swal.showLoading();
                            }
                        });

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
                            // Cerrar SweetAlert de carga
                            Swal.close();

                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Limpiar tabla y array después de que el usuario cierre el modal
                                    tableBody.innerHTML = '';
                                    selectedItems = [];
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.close(); // Cerrar cualquier SweetAlert abierto
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'Hubo un problema al guardar los registros.'
                            });
                        });
                    }
                });
            });
        });


</script>

@endsection