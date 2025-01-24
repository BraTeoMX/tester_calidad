@extends('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')])

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos todos los elementos de alerta
            const alerts = document.querySelectorAll('.alert');
    
            // Iteramos por cada alerta para aplicar el desvanecido
            alerts.forEach(alert => {
                // Esperamos 6 segundos antes de iniciar el desvanecido
                setTimeout(() => {
                    // Cambiamos la opacidad para el efecto de desvanecido
                    alert.style.transition = 'opacity 1s ease';
                    alert.style.opacity = '0';
    
                    // Eliminamos el elemento del DOM después de 1 segundo (duración del desvanecido)
                    setTimeout(() => alert.remove(), 1000);
                }, 10000); // Tiempo de espera antes de desvanecer (6 segundos)
            });
        });
    </script>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card bg-dark text-white">
                    <div class="card-header bg-info text-white">
                        <h2 style="color: aliceblue; font-weight: bold;">Usuarios</h2>
                        <p style="color: aliceblue">Apartado para gestionar a los usuarios</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-right">
                                <button class="btn btn-sm btn-primary" id="openModalButton" >
                                    Agregar nuevo personal <i class="tim-icons icon-single-02"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <table class="table table-hover table-dark" id="tablaDinamico">
                                <thead class="text-primary">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>No. Empleado</th>
                                        <th>Correo </th>
                                        <th>Auditor</th>
                                        <th>Puesto</th>
                                        <th>Fecha de Creacion</th>
                                        <th>Estatus</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->no_empleado }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->tipo_auditor }}</td>
                                            <td>{{ $user->puesto }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            @if($user->Estatus == "Baja")
                                                <td style="color: #ff4d4d !important;">{{ $user->Estatus }}</td>
                                            @else
                                                <td>{{ $user->Estatus }}</td>
                                            @endif
                                            <td class="td-actions text-right">
                                                <div class="btn-group" role="group" aria-label="Acciones">
                                                    <button 
                                                        class="btn btn-info btn-link editUserBtn" 
                                                        data-id="{{ $user->no_empleado }}" 
                                                        data-name="{{ $user->name }}" 
                                                        id="openEditModalButton">
                                                        <i class="tim-icons icon-pencil"></i>
                                                    </button>
                                                    <form method="POST" action="{{ route('blockUser', ['noEmpleado' => $user->no_empleado]) }}">
                                                        @method('PUT')
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-link">
                                                            @if ($user->Estatus == 'Baja')
                                                                <i class="tim-icons icon-alert-circle-exc"></i>
                                                            @else
                                                                <i class="tim-icons icon-single-02"></i>
                                                            @endif
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Modal personalizado -->
                            <div id="customModal" class="custom-modal">
                                <div class="custom-modal-content">
                                    <div class="custom-modal-header">
                                        <h3>Agregar Usuario</h3>
                                        <button id="closeModalButton" class="btn btn-danger">CERRAR</button>
                                    </div>
                                    <div class="custom-modal-body">
                                        <form id="addUserForm" action="{{ route('user.AddUser') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="name" id="name" 
                                                    placeholder="Ingrese el nombre" oninput="this.value = this.value.toUpperCase();" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Correo</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="email" class="form-control" name="email" id="email" placeholder="Ingrese el correo" required>
                                                    <div class="form-check ms-3">
                                                        <input type="checkbox" class="form-check-input" id="disableEmailCheckbox" onclick="toggleEmailInput()">
                                                        <label class="form-check-label" for="disableEmailCheckbox">Deshabilitar</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="no_empleado" class="form-label">No. Empleado</label>
                                                <input type="number" class="form-control" name="no_empleado" id="no_empleado" placeholder="Ingrese el número de empleado" maxlength="10" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese la contraseña" required>
                                                    <button class="btn btn-warning" type="button" onclick="togglePasswordVisibility('password')">Ver</button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPuesto" class="form-label">Puesto</label>
                                                <select class="form-control" id="editPuesto" name="editPuesto" required>
                                                    <option value="" disabled selected hidden>Seleccione el puesto</option>
                                                    @foreach ($puestoDatos as $puesto)
                                                        <option value="{{ $puesto->Puesto }}">{{ $puesto->Puesto }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tipo_auditoria" class="form-label">Tipo Auditoria</label>
                                                <select class="form-control" id="tipo_auditoria" name="tipo_auditoria" required>
                                                    <option value="" disabled selected hidden>Seleccione el tipo de auditoria</option>
                                                    @foreach ($tipoAuditoriaDatos as $tipo)
                                                        <option value="{{ $tipo->Tipo_auditoria }}">{{ $tipo->Tipo_auditoria }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPlanta" class="form-label">Planta</label>
                                                <select class="form-control" id="editPlanta" name="editPlanta" required>
                                                    <option value="" disabled selected hidden>Seleccione la planta</option>
                                                    <option value="Planta1">Ixtlahuaca</option>
                                                    <option value="Planta2">San Bartolo</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Edit User Personalizado -->
                            <div id="editCustomModal" class="custom-modal">
                                <div class="custom-modal-content">
                                    <div class="custom-modal-header">
                                        <h3>Editar Usuario</h3>
                                        <button id="closeEditModalButton" class="btn btn-danger">CERRAR</button>
                                    </div>
                                    <div class="custom-modal-body">
                                        <form id="editUserForm" action="{{ route('users.editUser') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="editId" class="form-label">ID</label>
                                                <input type="text" class="form-control disabled-input" name="editId" id="editId" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editName" class="form-label">Nombre</label>
                                                <input type="text" class="form-control" name="editName" id="editName" placeholder="Nombre del usuario">
                                            </div>
                                            <div class="mb-3">
                                                <label for="editTipoAuditoria" class="form-label">Tipo Auditoria</label>
                                                <select class="form-control" id="editTipoAuditoria" name="editTipoAuditoria"></select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editPuestos" class="form-label">Puesto</label>
                                                <select class="form-control" id="editPuestos" name="editPuestos"></select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password_update" class="form-label">Contraseña</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="password_update" id="password_update" placeholder="Cambiar Contraseña">
                                                    <button class="btn btn-warning" type="button" onclick="togglePasswordVisibility('password_update')">Ver</button>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <style>
        /* Fondo del modal */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            overflow-y: auto; /* Permite el desplazamiento vertical del modal completo */
        }

        /* Contenido del modal */
        .custom-modal-content {
            position: relative; /* Cambiado de 'fixed' a 'relative' */
            margin: 2rem auto; /* Espaciado superior e inferior */
            background-color: #1a1a1a;
            color: #ffffff;
            border-radius: 10px;
            width: 500px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Encabezado */
        .custom-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ffffff;
            margin-bottom: 20px;
        }

        /* Botón cerrar */
        .custom-close-button {
            background: none;
            border: none;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }

        .custom-close-button:hover {
            color: #ff4d4d;
        }

        /* Botones del formulario */
        .btn-primary {
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        // Función para abrir un modal específico
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        // Función para cerrar un modal específico
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Evento para abrir el primer modal
        document.getElementById('openModalButton').addEventListener('click', function () {
            openModal('customModal');
        });

        // Evento para cerrar el primer modal
        document.getElementById('closeModalButton').addEventListener('click', function () {
            closeModal('customModal');
        });

        // Evento para manejar múltiples botones de apertura para el segundo modal
        document.querySelectorAll('.editUserBtn').forEach(function (button) {
            button.addEventListener('click', function () {
                // Obtener los datos del botón
                const userId = this.getAttribute('data-id');
                const userName = this.getAttribute('data-name');

                // Prellenar el formulario del modal de edición
                document.getElementById('editId').value = userId;
                document.getElementById('editName').value = userName;

                // Abrir el modal de edición
                openModal('editCustomModal');
            });
        });

        // Evento para cerrar el segundo modal
        document.getElementById('closeEditModalButton').addEventListener('click', function () {
            closeModal('editCustomModal');
        });

        // Cerrar cualquier modal al presionar la tecla "ESC"
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closeModal('customModal');
                closeModal('editCustomModal');
            }
        });

        // Cerrar cualquier modal al hacer clic fuera del contenido
        ['customModal', 'editCustomModal'].forEach(function (modalId) {
            const modalElement = document.getElementById(modalId);
            modalElement.addEventListener('click', function (event) {
                if (event.target === modalElement) {
                    closeModal(modalId);
                }
            });
        });
    </script>

    <script>
        function toggleEmailInput() {
            const emailInput = document.getElementById('email');
            const checkbox = document.getElementById('disableEmailCheckbox');
            
            // Si el checkbox está marcado, deshabilita el input y elimina el atributo 'required'
            if (checkbox.checked) {
                emailInput.disabled = true;
                emailInput.removeAttribute('required');
            } else {
                // Si el checkbox está desmarcado, habilita el input y agrega el atributo 'required'
                emailInput.disabled = false;
                emailInput.setAttribute('required', 'required');
            }
        }
    </script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


    <script>
        $(document).ready(function() {
            // Verifica si la tabla ya está inicializada antes de inicializarla nuevamente

            if (!$.fn.dataTable.isDataTable('#tablaDinamico')) {
                $('#tablaDinamico').DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: true,
                    pageLength: 10,
                    autoWidth: false,
                    responsive: true,
                    columnDefs: [
                        {
                            targets: 7, // Índice de la columna a excluir (0-indexed, es decir, la septima columna es índice 8)
                            searchable: false, // Excluir de la búsqueda
                            orderable: false, // Excluir del ordenamiento
                        },
                    ],
                });
            }
        });
    </script>
    
@endpush
