@extends('layouts.app', ['pageSlug' => 'dashboard', 'titlePage' => __('dashboard')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">Dashboard para sistema de Calidad</h3>
                        </div>
                        <div class="col-auto">
                            <!-- Buscador -->
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar...">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <div class="row">
                        <!-- Columna para Auditoria AQL -->
                        <div class="col-md-6" id="aqlResults">
                            <h4>Auditoria AQL</h4>
                            <div id="aqlSearchResults"></div>
                        </div>
                        <!-- Columna para Auditoria Proceso -->
                        <div class="col-md-6" id="procesoResults">
                            <h4>Auditoria Proceso</h4>
                            <div id="procesoSearchResults"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            let query = this.value;
            if (query.length > 2) {
                fetchResults(query);
            } else {
                document.getElementById('aqlSearchResults').innerHTML = '';
                document.getElementById('procesoSearchResults').innerHTML = '';
            }
        });

        function fetchResults(query) {
            fetch(`/buscadorDinamico/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let aqlResultsContainer = document.getElementById('aqlSearchResults');
                    let procesoResultsContainer = document.getElementById('procesoSearchResults');
                    aqlResultsContainer.innerHTML = '';
                    procesoResultsContainer.innerHTML = '';

                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.classList.add('search-result-item');
                        div.innerHTML = `
                            <strong>Columnas:</strong> ${item.columns.join(', ')}
                        `;
                        if (item.model === 'AuditoriaAQL') {
                            aqlResultsContainer.appendChild(div);
                        } else if (item.model === 'AseguramientoCalidad') {
                            procesoResultsContainer.appendChild(div);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        }
    </script>
@endsection
