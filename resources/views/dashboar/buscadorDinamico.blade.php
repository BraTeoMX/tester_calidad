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
                    <!-- Contenido de la búsqueda -->
                    <div id="searchResults"></div>
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
                document.getElementById('searchResults').innerHTML = '';
            }
        });

        function fetchResults(query) {
            fetch(`/buscadorDinamico/search?q=${query}`)
                .then(response => response.json())
                .then(data => {
                    let resultsContainer = document.getElementById('searchResults');
                    resultsContainer.innerHTML = '';

                    data.forEach(item => {
                        let div = document.createElement('div');
                        div.classList.add('search-result-item');
                        div.innerHTML = `
                            <strong>${item.model}:</strong> ${item.name}<br>
                            ${item.cliente ? `<strong>Cliente:</strong> ${item.cliente}<br>` : ''}
                            ${item.team_leader ? `<strong>Team Leader:</strong> ${item.team_leader}<br>` : ''}
                            ${item.auditor ? `<strong>Auditor:</strong> ${item.auditor}<br>` : ''}
                            ${item.modulo ? `<strong>Modulo:</strong> ${item.modulo}<br>` : ''}
                        `;
                        resultsContainer.appendChild(div);
                    });
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        }
    </script>
@endsection
