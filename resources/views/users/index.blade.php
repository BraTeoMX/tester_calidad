<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema de Calidad') }}</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('black') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('black') }}/img/favicon.png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Icons -->
    <link href="{{ asset('black') }}/css/nucleo-icons.css" rel="stylesheet" />
    <!-- CSS -->
    <link href="{{ asset('black') }}/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
    <link href="{{ asset('black') }}/css/theme.css" rel="stylesheet" />
       <!-- Select2 -->
       <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

       <!-- jQuery -->
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
       <script src="{{ asset('material') }}/js/plugins/jquery-jvectormap.js"></script>
       <script>
        jQuery.event.special.touchstart = {
          setup: function (_, ns, handle) {
            this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
          }
        };

        jQuery.event.special.touchmove = {
          setup: function (_, ns, handle) {
            this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
          }
        };

        jQuery.event.special.wheel = {
          setup: function (_, ns, handle) {
            this.addEventListener("wheel", handle, { passive: true });
          }
        };

        jQuery.event.special.mousewheel = {
          setup: function (_, ns, handle) {
            this.addEventListener("mousewheel", handle, { passive: true });
          }
        };
    </script>
    <script src="{{ asset('material') }}/js/plugins/arrive.min.js"></script>
    <!-- Core Scripts -->
    <script src="{{ asset('material') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('material') }}/js/core/bootstrap-material-design.min.js"></script>

    <!-- Select2 and Datepicker Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <!-- flatpickr CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Other Plugins -->
    <script src="{{ asset('material') }}/js/plugins/bootstrap-selectpicker.js"></script>
    <script src="{{ asset('material') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="{{ asset('material') }}/js/settings.js"></script>
    <script src="{{ asset('material') }}/js/material-dashboard.js?v=2.1.1" type="text/javascript"></script>


</head>
<body class="">
    <!-- End Google Tag Manager (noscript) -->
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text logo-mini">{{ _('BD') }}</a>
                    <a href="#" class="simple-text logo-normal">{{ _('Black Dashboard') }}</a>
                </div>
                <ul class="nav">
                    @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="material-icons">dashboard</i>
                                <p>{{ __('Dashboard') }}</p>
                            </a>
                    @endif
                    </li>
                    @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                                <i class="material-icons">admin_panel_settings</i>
                                <p>{{ __('Admin cuentas') }}
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="laravelExample">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('profile.edit') }}">
                                            <span class="sidebar-mini"> UP </span>
                                            <span class="sidebar-normal">{{ __('User profile') }} </span>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('user.index') }}">
                                            <span class="sidebar-mini"> UM </span>
                                            <span class="sidebar-normal"> {{ __('User Management') }} </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                            <i class="material-icons">note_alt</i>
                            <p>{{ __('Formularios Calidad') }}
                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="laravelExamples">
                            <ul class="nav">
                                @if (auth()->check() &&
                                        (auth()->user()->hasRole('Auditor') ||
                                            auth()->user()->hasRole('Administrador') ||
                                            auth()->user()->hasRole('Gerente de Calidad')) &&
                                        auth()->user()->Planta == 'Planta1')
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('formulariosCalidad.auditoriaEtiquetas') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('FCC-014') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA ETIQUETAS') }}</p>

                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('auditoriaCorte.inicioAuditoriaCorte') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('FCC-010') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('evaluacionCorte.inicioEvaluacionCorte') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('F-4') }}</p>
                                            <p style="text-align: center;">{{ __('EVALUACION DE CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('auditoriaProcesoCorte.altaProcesoCorte') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('FCC-04') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA PROCESO DE CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('FCC-001') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">

                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-009-B') }}</p>
                                        <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">

                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-008') }}</p>
                                        <p style="text-align: center;">{{ __('CONTROL DE CALIDAD EMPAQUE') }}</p>
                                        </a>
                                    </li>
                                @endif
                                @if (auth()->check() &&
                                        (auth()->user()->hasRole('Auditor') ||
                                            auth()->user()->hasRole('Administrador') ||
                                            auth()->user()->hasRole('Gerente de Calidad')) &&
                                        auth()->user()->Planta == 'Planta2')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('ScreenPlanta2.ScreenPrint') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('Screen Print') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('ScreenPlanta2.InsEstamHorno') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('Inspección Después De Horno') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('ScreenPlanta2.CalidadProcesoPlancha') }}">
                                            <i class="material-icons">edit_document</i>
                                            <p>{{ __('Proceso Plancha') }}</p>
                                        </a>
                                    </li>
                                @endif
                    </li>
                </ul>
            </div>
            </li>
            </ul>
        </div>
    </div>
    <div class="main-panel">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                    <a class="navbar-brand" href="#">User Management</a>
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end">
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="nav-link" href="#pablo" id="navbarDropdownProfile" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">person</i>
                                <p class="d-lg-none d-md-block">
                                    Account
                                </p>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log
                                    out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div id="messages-container" class="container mt-5">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-header card-header-primary">
                                <h4 class="card-title ">Users</h4>
                                <p class="card-category"> Here you can manage users</p>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 text-right">

                                        <a href="#" class="btn btn-sm btn-primary " id="addUserBtn">
                                            Add
                                            user
                                            <label for="name" class="material-icons">
                                                person_add</label></a>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-hover">
                                        <thead class=" text-primary">
                                            <tr>
                                                <th>
                                                    Name
                                                </th>
                                                <th>
                                                    No. Empleado
                                                </th>
                                                <th>
                                                    Email
                                                </th>
                                                <th>
                                                    Auditor
                                                </th>
                                                <th>
                                                    Puesto
                                                </th>
                                                <th>
                                                    Creation date
                                                </th>
                                                <th>
                                                    Status User
                                                </th>
                                                <th class="text-right">
                                                    Actions
                                                </th>
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
                                                    <td>{{ $user->Estatus }}</td>
                                                    <td class="td-actions text-right">
                                                        <div class="btn-group" role="group" aria-label="Acciones">
                                                            <button class="btn btn-info btn-link editUserBtn"
                                                                data-id="{{ $user->no_empleado }}"
                                                                data-name="{{ $user->name }}">
                                                                <i class="material-icons">edit</i>
                                                            </button>
                                                            <form method="POST"
                                                                action="{{ route('blockUser', ['noEmpleado' => $user->no_empleado]) }}">
                                                                @method('PUT')
                                                                @csrf
                                                                <!-- Resto de tus campos y botones aquí -->
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-link">
                                                                    @if ($user->Estatus == 'Baja')
                                                                        <i class="material-icons">block</i>
                                                                </button>
                                                            @else
                                                                <button type="submit"
                                                                    class="btn btn-success btn-link">
                                                                    <i class="material-icons">how_to_reg</i>
                                            @endif
                                            </button>
                                            </form>
                                </div>
                                </td>
                                </tr>
                                @endforeach
                                </tbody>
                                </table>
                                <!-- Modal -->
                                <form id="addUserForm" action="{{ route('user.AddUser') }}" method="POST">
                                    @csrf
                                    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog"
                                        aria-labelledby="addUserModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="card-header card-header-primary"
                                                        id="addUserModalLabel">Add User
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="name" class="material-icons">
                                                            person</label>
                                                        <input type="text" class="form-control" name="name"
                                                            id="name" placeholder="Enter name" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email" class="material-icons">mail</label>
                                                        <input type="email" class="form-control" name="email"
                                                            id="email" placeholder="Enter email" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="no_empleado"
                                                            class="material-icons">numbers</label>
                                                        <input type="number" class="form-control" name="no_empleado"
                                                            id="no_empleado" placeholder="Enter no. empleado"
                                                            oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                                            maxlength="10" required>
                                                    </div>
                                                    <div class="form-group row">
                                                        <span class="material-icons">key</span>
                                                        <label for="password" class="col-sm-2 col-form-label">
                                                        </label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control"
                                                                    name="password" id="password"
                                                                    placeholder="Enter password" required>
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text show-password-toggle"
                                                                        style="cursor: pointer;"
                                                                        onclick="togglePasswordVisibility('password')">
                                                                        <i
                                                                            class="material-icons">visibility</i>{{ __('Ver') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="tipo_auditoria"
                                                            class="material-icons">engineering</label>
                                                        <select class="form-control" id="tipo_auditoria"
                                                            name="tipo_auditoria" required>
                                                            <!-- Las opciones se cargarán dinámicamente aquí -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editPuesto" class="material-icons">work</label>
                                                        <select class="form-control" id="editPuesto"
                                                            name="editPuesto" required>
                                                            <!-- Las opciones se cargarán dinámicamente aquí -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editPlanta"
                                                            class="material-icons">apartment</label>
                                                        <select class="form-control" id="editPlanta"
                                                            name="editPlanta" required>
                                                            <option value="" disabled selected hidden>
                                                                Seleccione la planta </option>
                                                            <option value="Planta1">Ixtlahuaca </option>
                                                            <option value="Planta2">San Bartolo</option>
                                                        </select>
                                                    </div>
                                                    <!-- Otros campos del formulario según tus necesidades -->
                                                    <button type="submit" class="bookmarkBtn">
                                                        <span class="IconContainer">
                                                            <svg viewBox="0 0 384 512" height="0.9em" class="icon">
                                                                <path
                                                                    d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                        <p class="text">Save</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <form id="editUser" action="{{ route('users.editUser') }}" method="POST">
                                    @csrf
                                    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
                                        aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <!-- Contenido del modal, puedes personalizarlo según tus necesidades -->
                                                <div class="modal-header">
                                                    <h5 class="card-header card-header-primary" id="editModalLabel">
                                                        Editar Usuario</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <!-- Campos de edición -->
                                                    <div class="form-group">
                                                        <label for="editId" class="material-icons">badge</label>
                                                        <input type="text" class="form-control disabled-input"
                                                            name="editId" id="editId">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="editName" class="material-icons">person</label>
                                                        <input type="text" class="form-control" name="editName"
                                                            id="editName" placeholder="Nombre del usuario">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editTipoAuditoria"
                                                            class="material-icons">engineering</label>
                                                        <select class="form-control" id="editTipoAuditoria"
                                                            name="editTipoAuditoria">
                                                            <!-- Las opciones se cargarán dinámicamente aquí -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="editPuestos" class="material-icons">work</label>
                                                        <select class="form-control" id="editPuestos"
                                                            name="editPuestos">
                                                            <!-- Las opciones se cargarán dinámicamente aquí -->
                                                        </select>
                                                    </div>
                                                    <div class="form-group row">
                                                        <span class="material-icons">lock_reset</span>
                                                        <label for="password" class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <div class="input-group">
                                                                <input type="password" class="form-control"
                                                                    name="password_update" id="password_update"
                                                                    placeholder="Cambiar Contraseña">
                                                                <div class="input-group-append">
                                                                    <span class="input-group-text show-password-toggle"
                                                                        style="cursor: pointer;"
                                                                        onclick="togglePasswordVisibility('password_update')">
                                                                        <i
                                                                            class="material-icons">visibility</i>{{ __('Ver') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Otros campos según sea necesario -->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="bookmarkBtn">
                                                        <span class="IconContainer">
                                                            <svg viewBox="0 0 384 512" height="0.9em" class="icon">
                                                                <path
                                                                    d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                        <p class="text">Save</p>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script src="{{ asset('black') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('black') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('black') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('black') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <!-- Place this tag in your head or just before your close body tag. -->
    {{-- <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script> --}}
    <!-- Chart JS -->
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <!--  Notifications Plugin    -->
    <script src="{{ asset('black') }}/js/plugins/bootstrap-notify.js"></script>

    <script src="{{ asset('black') }}/js/black-dashboard.min.js?v=1.0.0"></script>
    <script src="{{ asset('black') }}/js/theme.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar el modal al hacer clic en el botón "Add user"
            $("#addUserBtn").click(function() {
                $("#addUserModal").modal("show");
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Deshabilitar visualmente los campos editId, editName y editTipoAuditoria
            $("#editId, #editName").addClass("disabled-input").attr("readonly", true).css("pointer-events", "none");

            // Mostrar el modal al hacer clic en cualquier botón de edición
            $(".editUserBtn").click(function() {
                // Obtener el ID del usuario desde el atributo data-id
                var userId = $(this).data('id');

                // Asignar el ID al campo editId en el modal
                $("#editId").val(userId);

                // Obtener el nombre del usuario desde el atributo data-name
                var userName = $(this).data('name');

                // Asignar el nombre al campo editName en el modal
                $("#editName").val(userName);

                // Cargar las opciones del tipo de auditoría desde la base de datos
                $.ajax({
                    url: '/tipoAuditorias', // Ajusta la URL según tu ruta
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#editTipoAuditoria').empty();
                        // Agregar la opción predeterminada
                        $('#editTipoAuditoria').append($('<option>', {
                            text: 'Enter Auditor',
                            disabled: true,
                            selected: true
                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#editTipoAuditoria').append($('<option>', {
                                text: value.Tipo_auditoria
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones: ', error);
                    }
                });

                // Cargar las opciones de los puestos desde la base de datos
                $.ajax({
                    url: '/puestos', // Ajusta la URL según tu ruta
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#editPuestos').empty();

                        // Agregar la opción predeterminada
                        $('#editPuestos').append($('<option>', {
                            text: 'Enter puesto',
                            disabled: true,
                            selected: true
                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#editPuestos').append($('<option>', {
                                text: value.Puesto
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones de puestos: ', error);
                    }
                });

                // Mostrar el modal de edición
                $("#editModal").modal("show");
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Al abrir el modal, cargar las opciones del dropdown
            $('#addUserModal').on('show.bs.modal', function() {
                $.ajax({
                    url: '/tipoAuditorias',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#tipo_auditoria').empty();
                        // Agregar la opción predeterminada
                        $('#tipo_auditoria').append($('<option>', {
                            text: 'Enter puesto',
                            disabled: true,
                            selected: true
                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#tipo_auditoria').append($('<option>', {
                                text: value.Tipo_auditoria
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones: ', error);
                    }
                });
                $.ajax({
                    url: '/puestos', // Ajusta la URL según tu ruta
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Limpiar las opciones existentes
                        $('#editPuesto').empty();

                        // Agregar la opción predeterminada
                        $('#editPuesto').append($('<option>', {
                            text: 'Enter puesto',
                            disabled: true,
                            selected: true
                        }));
                        // Agregar las nuevas opciones desde la respuesta del servidor
                        $.each(data, function(key, value) {
                            $('#editPuesto').append($('<option>', {
                                text: value.Puesto
                            }));
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar opciones de puestos: ', error);
                    }
                });
            });
        });
    </script>
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        }
    </script>
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        }
    </script>
    <script>
        $(document).ready(function() {
            // Cierra el mensaje cuando se hace clic en el botón de cerrar
            $(".alert").alert();

            // Cierra automáticamente el mensaje después de 5 segundos (puedes ajustar este tiempo)
            setTimeout(function() {
                $(".alert").alert('close');
            }, 5000);
        });
    </script>
    @stack('js')

    @stack('js')

    <script>
        $(document).ready(function() {
            $().ready(function() {
                $sidebar = $('.sidebar');
                $navbar = $('.navbar');
                $main_panel = $('.main-panel');

                $full_page = $('.full-page');

                $sidebar_responsive = $('body > .navbar-collapse');
                sidebar_mini_active = true;
                white_color = false;

                window_width = $(window).width();

                fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

                $('.fixed-plugin a').click(function(event) {
                    if ($(this).hasClass('switch-trigger')) {
                        if (event.stopPropagation) {
                            event.stopPropagation();
                        } else if (window.event) {
                            window.event.cancelBubble = true;
                        }
                    }
                });

                $('.fixed-plugin .background-color span').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');

                    var new_color = $(this).data('color');

                    if ($sidebar.length != 0) {
                        $sidebar.attr('data', new_color);
                    }

                    if ($main_panel.length != 0) {
                        $main_panel.attr('data', new_color);
                    }

                    if ($full_page.length != 0) {
                        $full_page.attr('filter-color', new_color);
                    }

                    if ($sidebar_responsive.length != 0) {
                        $sidebar_responsive.attr('data', new_color);
                    }
                });

                $('.switch-sidebar-mini input').on("switchChange.bootstrapSwitch", function() {
                    var $btn = $(this);

                    if (sidebar_mini_active == true) {
                        $('body').removeClass('sidebar-mini');
                        sidebar_mini_active = false;
                        blackDashboard.showSidebarMessage('Sidebar mini deactivated...');
                    } else {
                        $('body').addClass('sidebar-mini');
                        sidebar_mini_active = true;
                        blackDashboard.showSidebarMessage('Sidebar mini activated...');
                    }

                    // we simulate the window Resize so the charts will get updated in realtime.
                    var simulateWindowResize = setInterval(function() {
                        window.dispatchEvent(new Event('resize'));
                    }, 180);

                    // we stop the simulation of Window Resize after the animations are completed
                    setTimeout(function() {
                        clearInterval(simulateWindowResize);
                    }, 1000);
                });

                $('.switch-change-color input').on("switchChange.bootstrapSwitch", function() {
                    var $btn = $(this);

                    if (white_color == true) {
                        $('body').addClass('change-background');
                        setTimeout(function() {
                            $('body').removeClass('change-background');
                            $('body').removeClass('white-content');
                        }, 900);
                        white_color = false;
                    } else {
                        $('body').addClass('change-background');
                        setTimeout(function() {
                            $('body').removeClass('change-background');
                            $('body').addClass('white-content');
                        }, 900);

                        white_color = true;
                    }
                });

                $('.light-badge').click(function() {
                    $('body').addClass('white-content');
                });

                $('.dark-badge').click(function() {
                    $('body').removeClass('white-content');
                });
            });
        });
    </script>
    @stack('js')
</body>

</html>
