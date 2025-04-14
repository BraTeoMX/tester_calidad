@extends('layouts.app', ['pageSlug' => 'busquedaOP', 'titlePage' => __('Dashboard')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Bultos no finalizados</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-body">
                <h3>Bultos No Finalizados (Últimos 20 días)</h3>
                <div id="bultos-container-general">
                    <p class="text-muted">Cargando datos de los últimos 20 días...</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-body">
                <h3>Paros No Finalizados - Proceso (Últimos 20 días)</h3>
                <div id="paros-container-general">
                    <p class="text-muted">Cargando datos de los últimos 20 días...</p>
                </div>
            </div>
        </div>
    </div>

    <div id="modalParoManual" class="modal-personalizado-overlay">
        <div class="modal-personalizado-content">
            <button class="modal-personalizado-close" onclick="cerrarModal()">cerrar</button>
            <h5>Editar Finalización de Paro</h5>
            <input type="hidden" id="paroManualId">
    
            <label for="minutosParo">⏱ Minutos del paro:</label>
            <input type="number" id="minutosParo" placeholder="Ej. 45">
    
            <label for="piezasReparadas">🔧 Piezas reparadas:</label>
            <input type="number" id="piezasReparadas" placeholder="Ej. 100">
    
            <label for="razonAjuste">📝 Razón del ajuste:</label>
            <textarea id="razonAjuste" rows="3" placeholder="Describa el motivo..."></textarea>
    
            <button id="btnGuardarAjuste">Guardar Ajuste</button>
            <button class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
        </div>
    </div>    
    
    <style>
        /* Contenedor para centrar el texto */
        .loading-container {
            position: relative;
            width: 100%;
            height: 100%;
        }

        /* Texto animado */
        .loading-text {
            font-size: 18px;
            font-weight: bold;
            color: #d1d1d1; /* Color para tema oscuro */
            
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); /* Centrar exactamente */
            
            animation: fadeInOut 1.5s infinite;
        }

        /* Animación de parpadeo */
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

    </style>
    <style>
        .custom-body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }

        .custom-card {
            background-color: #1e1e1e;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .custom-card-header {
            background-color: #2e7d32;
            color: white;
            padding: 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .custom-card-body {
            padding: 15px;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        .custom-table th,
        .custom-table td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #333;
        }

        .custom-table th {
            background-color: #2e2e2e;
        }

        .custom-btn {
            background-color: transparent;
            border: none;
            color: #4caf50;
            cursor: pointer;
            text-decoration: underline;
        }

        .custom-modal {
            display: none;
            position: fixed;
            z-index: 9999; /* Asegura que está por encima de todo */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            padding-top: 60px; /* Espacio superior */
            background-color: rgba(0, 0, 0, 0.9);
            overflow-y: auto;
            pointer-events: auto; /* Muy importante */
        }

        .custom-modal-content {
            background-color: #1e1e1e;
            margin: 0 auto;
            padding: 20px;
            width: 100%;
            min-height: 100%;
            box-sizing: border-box;
        }

        .custom-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: fixed;
            right: 25px;
            top: 15px;
        }

        .custom-close:hover,
        .custom-close:focus {
            color: #fff;
        }

        .custom-modal-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #2e2e2e;
            padding: 15px;
            z-index: 1001;
        }

        .custom-modal-body {
            margin-top: 70px;
            /* Ajusta este valor según la altura de tu encabezado */
            padding: 15px;
        }
    </style>
    <style>
        .modal-personalizado-overlay {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-personalizado-content {
            background: #1e1e1e;
            color: #f1f1f1;
            padding: 20px;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.8);
            position: relative;
            font-family: sans-serif;
        }

        .modal-personalizado-content h5 {
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 18px;
            text-align: center;
        }

        .modal-personalizado-content input,
        .modal-personalizado-content textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #2c2c2c;
            color: #f1f1f1;
        }

        .modal-personalizado-content button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .modal-personalizado-content button:hover {
            background-color: #0056b3;
        }

        .modal-personalizado-content .btn-cancelar {
            background-color: #6c757d;
            margin-top: 5px;
        }

        .modal-personalizado-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 32px;
            height: 32px;
            background-color: #dc3545 !important;
            color: #fff !important;
            border: none;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4);
        }

        .modal-personalizado-close:hover {
            background-color: #c82333;
        }
    </style>

    <!-- JavaScript -->
    
    <script>
        $(document).ready(function () {
            // Al cargar la vista, ejecutamos directamente la llamada
            $.ajax({
                url: '/bnf/bultos-no-finalizados-general',
                method: 'GET',
                beforeSend: function () {
                    $('#bultos-container-general').html(`
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Cargando datos...</p>
                        </div>
                    `);
                },
                success: function (response) {
                    if (response.length > 0) {
                        let contenido = `
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Módulo</th>
                                            <th>Bulto</th>
                                            <th>Estilo</th>
                                            <th>Inicio Paro</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        response.forEach(item => {
                            contenido += `
                                <tr>
                                    <td>${item.modulo}</td>
                                    <td>${item.bulto}</td>
                                    <td>${item.estilo}</td>
                                    <td>${item.formato_creado}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm finalizar-paro" data-id="${item.id}">
                                            Finalizar Paro Pendiente
                                        </button>
                                        <button class="btn btn-danger btn-sm editar-paro-aql" data-id="${item.id}">
                                            Editar Finalización Paro Bulto
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        contenido += '</tbody></table></div>';
                        $('#bultos-container-general').html(contenido);
                    } else {
                        $('#bultos-container-general').html('<p class="text-warning text-center">No se encontraron bultos no finalizados en los últimos 20 días.</p>');
                    }
                },
                error: function () {
                    $('#bultos-container-general').html('<p class="text-danger text-center">Error al cargar los datos.</p>');
                }
            });
        
            // Reutilizamos el mismo evento para finalizar paros
            $(document).on('click', '.finalizar-paro', function () {
                let id = $(this).data('id');
                let piezasReparadas = prompt("Ingresa el número de piezas reparadas:", "0");
                if (piezasReparadas === null) return;
        
                if (confirm("¿Estás seguro de que deseas finalizar este paro?")) {
                    const spinnerHtml = `
                        <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                            <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                            Procesando solicitud...
                        </div>`;
                    $('body').append(spinnerHtml);
        
                    $.ajax({
                        url: '/bnf/finalizar-paro-aql-despues',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: {
                            id: id,
                            piezasReparadas: piezasReparadas
                        },
                        success: function (response) {
                            $('#processing-spinner').remove();
        
                            if (response.success) {
                                alert(`✅ Paro finalizado correctamente.\nMinutos Paro: ${response.minutos_paro}\nPiezas Reparadas: ${response.reparacion_rechazo}`);
                                location.reload(); // Para recargar la lista actualizada
                            } else {
                                alert(`❌ Error: ${response.message}`);
                            }
                        },
                        error: function () {
                            $('#processing-spinner').remove();
                            alert("⚠️ Ocurrió un error al intentar finalizar el paro.");
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function abrirModal(id) {
            document.getElementById('paroManualId').value = id;
            document.getElementById('modalParoManual').style.display = 'flex';
        }
    
        function cerrarModal() {
            document.getElementById('modalParoManual').style.display = 'none';
        }
    
        // Evento ESC para cerrar el modal
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                cerrarModal();
            }
        });
    
        $(document).on('click', '.editar-paro-aql', function () {
            const id = $(this).data('id');
            abrirModal(id);
        });
    
        document.getElementById('btnGuardarAjuste').addEventListener('click', function () {
            const id = document.getElementById('paroManualId').value;
            const minutosParo = document.getElementById('minutosParo').value.trim();
            const piezasReparadas = document.getElementById('piezasReparadas').value.trim();
            const razonAjuste = document.getElementById('razonAjuste').value.trim();
    
            if (!minutosParo || !/^\d+$/.test(minutosParo)) {
                alert("⛔ Los minutos deben ser un número entero.");
                return;
            }
    
            if (!piezasReparadas || !/^\d+$/.test(piezasReparadas)) {
                alert("⛔ Las piezas reparadas deben ser un número entero.");
                return;
            }
    
            if (!razonAjuste) {
                alert("⛔ La razón del ajuste es obligatoria.");
                return;
            }
    
            const confirmar = confirm(`¿Confirmas el ajuste?\nMinutos: ${minutosParo}\nPiezas: ${piezasReparadas}\nRazón: ${razonAjuste}`);
            if (!confirmar) return;
    
            const spinnerHtml = `
                <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                    <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                    Procesando solicitud...
                </div>`;
            $('body').append(spinnerHtml);
    
            $.ajax({
                url: '/bnf/editar-paro-aql',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id: id,
                    minutosParo: minutosParo,
                    piezasReparadas: piezasReparadas,
                    razonAjuste: razonAjuste
                },
                success: function (response) {
                    $('#processing-spinner').remove();
                    cerrarModal();
                    if (response.success) {
                        alert("✅ Ajuste registrado correctamente: " + response.message);
                        location.reload();
                    } else {
                        alert("❌ Error: " + response.message);
                    }
                },
                error: function () {
                    $('#processing-spinner').remove();
                    cerrarModal();
                    alert("⚠️ Error al guardar el ajuste.");
                }
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $.ajax({
                url: '/bnf/paros-no-finalizados-general',
                method: 'GET',
                beforeSend: function () {
                    $('#paros-container-general').html(`
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Cargando datos...</p>
                        </div>
                    `);
                },
                success: function (response) {
                    if (response.length > 0) {
                        let contenido = `
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="thead-primary">
                                        <tr>
                                            <th>Módulo</th>
                                            <th>Nombre</th>
                                            <th>Operacion</th>
                                            <th>Inicio Paro</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;
                        response.forEach(item => {
                            contenido += `
                                <tr>
                                    <td>${item.modulo}</td>
                                    <td>${item.nombre}</td>
                                    <td>${item.operacion}</td>
                                    <td>${item.formato_creado}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm finalizar-paro-proceso" data-id="${item.id}">
                                            Finalizar Paro Pendiente
                                        </button>
                                        <button class="btn btn-danger btn-sm editar-paro-proceso" data-id="${item.id}">
                                            Editar Finalización Paro
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });
                        contenido += '</tbody></table></div>';
                        $('#paros-container-general').html(contenido);
                    } else {
                        $('#paros-container-general').html('<p class="text-warning text-center">No se encontraron paros no finalizados en los últimos 20 días.</p>');
                    }
                },
                error: function () {
                    $('#paros-container-general').html('<p class="text-danger text-center">Error al cargar los datos.</p>');
                }
            });
        
            // Delegar evento para finalizar paro
            $(document).on('click', '.finalizar-paro-proceso', function () {
                let id = $(this).data('id');
                if (!confirm("¿Estás seguro de que deseas finalizar este paro?")) return;
        
                const spinnerHtml = `
                    <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                        <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                        Procesando solicitud...
                    </div>`;
                $('body').append(spinnerHtml);
        
                $.ajax({
                    url: '/bnf/finalizar-paro-proceso-despues',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: { id: id },
                    success: function (response) {
                        $('#processing-spinner').remove();
                        if (response.success) {
                            alert(`✅ Paro finalizado correctamente.\nMinutos Paro: ${response.minutos_paro}`);
                            location.reload();
                        } else {
                            alert(`❌ Error: ${response.message}`);
                        }
                    },
                    error: function () {
                        $('#processing-spinner').remove();
                        alert("⚠️ Ocurrió un error al intentar finalizar el paro.");
                    }
                });
            });
        });
    </script>
    <script>
        $(document).on('click', '.editar-paro-proceso', function () {
            const id = $(this).data('id');

            const minutosParo = prompt("⏱ Ingresa los minutos del paro:");
            if (minutosParo === null || minutosParo.trim() === "") {
                alert("⚠️ Los minutos del paro son obligatorios.");
                return;
            }

            const razonAjuste = prompt("📝 Escribe la razón del ajuste:");
            if (razonAjuste === null || razonAjuste.trim() === "") {
                alert("⚠️ La razón del ajuste es obligatoria.");
                return;
            }

            const confirmar = confirm(
                `¿Confirmas guardar el ajuste?\n\nMinutos: ${minutosParo}\nRazón: ${razonAjuste}`
            );
            if (!confirmar) return;

            const spinnerHtml = `
                <div id="processing-spinner" class="position-fixed top-0 start-50 translate-middle-x mt-3 p-2 bg-dark text-white rounded shadow" style="z-index: 1050;">
                    <div class="spinner-border spinner-border-sm text-light" role="status"></div>
                    Procesando solicitud...
                </div>`;
            $('body').append(spinnerHtml);

            $.ajax({
                url: '/bnf/editar-paro-proceso',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    id: id,
                    minutosParo: minutosParo,
                    razonAjuste: razonAjuste
                },
                success: function (response) {
                    $('#processing-spinner').remove();
                    if (response.success) {
                        alert("✅ Ajuste registrado correctamente: " + response.message);
                        location.reload();
                    } else {
                        alert("❌ Error: " + response.message);
                    }
                },
                error: function () {
                    $('#processing-spinner').remove();
                    alert("⚠️ Error al guardar el ajuste.");
                }
            });
        });
    </script>
    
    
@endsection
