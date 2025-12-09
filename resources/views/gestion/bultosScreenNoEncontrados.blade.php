@extends('layouts.app', ['pageSlug' => 'GestionBusquedaScreen', 'titlePage' => __('GestionBusquedaScreen')])

@section('content')

<div class="row">
    <div class="col-12"> <!-- Changed to col-12 since we removed the other column -->
        <div class="card card-chart">
            <div class="card-header">
                <h3>Sección: Bultos Screen No Encontrados</h3>
            </div>
            <div class="card-body">
                <!-- Formulario de búsqueda -->
                <form id="search-form">
                    <div class="form-group">
                        <label for="search-input">Buscar por OP (escribe el OP completo):</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Escribe el OP completo">
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
                                <th>Fecha</th>
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('search-form');
        const searchInput = document.getElementById('search-input');
        const searchResults = document.querySelector('#search-results tbody');

        searchForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita que el formulario recargue la página
            realizarBusqueda();
        });

        document.getElementById('search-button').addEventListener('click', function() {
            realizarBusqueda();
        });

        // Convertir a mayúsculas automáticamente
        searchInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        function realizarBusqueda() {
            const searchTerm = searchInput.value.trim(); // Elimina espacios en blanco

            if (!searchTerm) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo Vacío',
                    text: 'Por favor, escribe un ID de OP.'
                });
                return;
            }

            // Validación de formato: Debe empezar con "OP" y tener 9 caracteres en total
            const opRegex = /^OP\d{7}$/;

            if (searchTerm.length !== 9 || !searchTerm.startsWith('OP')) {
                Swal.fire({
                    icon: 'info',
                    title: 'Formato Incorrecto',
                    text: 'Para buscar, debes escribir el OP completo. Ejemplo: "OP0012345" (9 caracteres, iniciando con OP).',
                    footer: 'No omitas el prefijo "OP".'
                });
                return;
            }

            if (!opRegex.test(searchTerm)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Formato Inválido',
                    text: 'El OP debe comenzar con "OP" seguido de 7 números.'
                });
                return;
            }

            // Mostrar SweetAlert de carga
            Swal.fire({
                title: 'Buscando...',
                text: 'Consultando directamente en SQL Server...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Realiza la petición AJAX
            fetch(`/consultaOP?searchTerm=${searchTerm}`, {
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
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Búsqueda Exitosa',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });

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

    document.addEventListener('DOMContentLoaded', function() {
        const saveButton = document.getElementById('save-button');
        const searchResults = document.querySelector('#search-results tbody');

        // Limpia cualquier registro previo del evento click para evitar duplicados
        saveButton.replaceWith(saveButton.cloneNode(true));
        const newSaveButton = document.getElementById('save-button');

        newSaveButton.addEventListener('click', function() {
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
                text: `¿Estás seguro de que deseas guardar ${window.currentSearchResults.length} registro(s) en JobAQLHistorial?`,
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
                    fetch('/guardarBultosScreen', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                records: window.currentSearchResults
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Cerrar SweetAlert de carga
                            Swal.close();

                            // Determinar el tipo de mensaje según result_type
                            let icon = 'success';
                            let title = '¡Éxito!';
                            let timer = 2000;

                            switch (data.result_type) {
                                case 'all_new':
                                    icon = 'success';
                                    title = '¡Éxito!';
                                    break;
                                case 'all_existing':
                                    icon = 'warning';
                                    title = 'Advertencia';
                                    timer = 3000;
                                    break;
                                case 'mixed':
                                    icon = 'info';
                                    title = 'Información';
                                    timer = 3000;
                                    break;
                                case 'error':
                                    icon = 'error';
                                    title = 'Error';
                                    timer = 0; // No auto-cerrar en errores
                                    break;
                                default:
                                    icon = 'success';
                                    title = '¡Éxito!';
                            }

                            // Mostrar estadísticas detalladas si hay datos disponibles
                            let text = data.message;
                            if (data.data) {
                                const stats = data.data;
                                text += `\n\nEstadísticas:\n`;
                                text += `• Total de registros: ${stats.total_registros}\n`;
                                if (stats.registros_insertados > 0) {
                                    text += `• Nuevos: ${stats.registros_insertados}\n`;
                                }
                                if (stats.registros_actualizados > 0) {
                                    text += `• Actualizados: ${stats.registros_actualizados}\n`;
                                }
                                if (stats.errores > 0) {
                                    text += `• Errores: ${stats.errores}`;
                                }
                            }

                            Swal.fire({
                                icon: icon,
                                title: title,
                                text: text,
                                timer: timer,
                                showConfirmButton: timer === 0 // Solo mostrar botón si no hay timer
                            });
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