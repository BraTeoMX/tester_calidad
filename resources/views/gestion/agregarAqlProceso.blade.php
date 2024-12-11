@extends('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')])

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
                    <div id="search-results">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Bulto</th>
                                    <th>OP</th>
                                    <th>Estilo</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Los resultados se inyectarán aquí -->
                            </tbody>
                        </table>
                        <button type="button" id="save-button" class="btn btn-success">Guardar Registros</button>
                    </div>                    
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Seccion: PROCESO</h3>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>

    
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');
            const searchResults = document.querySelector('#search-results tbody');

            searchButton.addEventListener('click', function (event) {
                event.preventDefault(); // Evita el comportamiento predeterminado del formulario
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
                                    <td>${item.qty}</td>
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
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const saveButton = document.getElementById('save-button');
            const searchResults = document.querySelector('#search-results tbody');

            // Asegúrate de que el evento no se registre múltiples veces
            saveButton.removeEventListener('click', guardarRegistros);
            saveButton.addEventListener('click', guardarRegistros);

            function guardarRegistros() {
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
            }
        });

    </script>
    
@endpush
