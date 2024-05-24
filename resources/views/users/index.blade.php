<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Extra details for Live View on GitHub Pages -->
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://www.creative-tim.com/product/black-dashboard-laravel" />
    <!--  Social tags      -->
    <meta name="keywords"
        content="creative tim, html dashboard, html css dashboard, web dashboard, bootstrap 4 dashboard, bootstrap 4, css3 dashboard, bootstrap 4 admin, Black dashboard Laravel bootstrap 4 dashboard, frontend, responsive bootstrap 4 dashboard, free dashboard, free admin dashboard, free bootstrap 4 admin dashboard">
    <meta name="description"
        content="Black Dashboard Laravel is a beautiful Bootstrap 4 admin dashboard with a large number of components, designed to look beautiful and organized. If you are looking for a tool to manage and visualize data about your business, this dashboard is the thing for you.">
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="Black Dashboard Laravel by Creative Tim">
    <meta itemprop="description"
        content="Black Dashboard Laravel is a beautiful Bootstrap 4 admin dashboard with a large number of components, designed to look beautiful and organized. If you are looking for a tool to manage and visualize data about your business, this dashboard is the thing for you.">
    <meta itemprop="image"
        content="https://s3.amazonaws.com/creativetim_bucket/products/164/original/opt_blk_laravel_thumbnail.jpg?1561102244">
    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@creativetim">
    <meta name="twitter:title" content="Black Dashboard Laravel by Creative Tim">
    <meta name="twitter:description"
        content="Black Dashboard Laravel is a beautiful Bootstrap 4 admin dashboard with a large number of components, designed to look beautiful and organized. If you are looking for a tool to manage and visualize data about your business, this dashboard is the thing for you.">
    <meta name="twitter:creator" content="@creativetim">
    <meta name="twitter:image"
        content="https://s3.amazonaws.com/creativetim_bucket/products/164/original/opt_blk_laravel_thumbnail.jpg?1561102244">
    <!-- Open Graph data -->
    <meta property="fb:app_id" content="655968634437471">
    <meta property="og:title" content="Black Dashboard Laravel by Creative Tim" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="https://black-dashboard-laravel.creative-tim.com/" />
    <meta property="og:image"
        content="https://s3.amazonaws.com/creativetim_bucket/products/164/original/opt_blk_laravel_thumbnail.jpg?1561102244" />
    <meta property="og:description"
        content="Black Dashboard Laravel is a beautiful Bootstrap 4 admin dashboard with a large number of components, designed to look beautiful and organized. If you are looking for a tool to manage and visualize data about your business, this dashboard is the thing for you." />
    <meta property="og:site_name" content="Creative Tim" />
    <title>{{ config('app.name', 'Black Dashboard Laravel - Free Laravel Preset') }}</title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('black') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('black') }}/img/favicon.png">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Icons -->
    <link href="{{ asset('black') }}/css/nucleo-icons.css" rel="stylesheet" />
    <!-- CSS -->
    <link href="{{ asset('black') }}/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
    <link href="{{ asset('black') }}/css/theme.css" rel="stylesheet" />
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NKDMSK6');
    </script>
    <!-- End Google Tag Manager -->
</head>

<body class="">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text logo-normal">{{ _('INTIMARK') }}</a>
                </div>
                <ul class="nav">
                    @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">
                                <i class="tim-icons icon-chart-pie-36"></i>
                                <p>{{ __('Dashboard') }}</p>
                            </a>
                        </li>
                    @endif

                    @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                                <i class="fab fa-laravel"></i>
                                <p>{{ __('Admin cuentas') }}
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="laravelExample">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('profile.edit') }}">
                                            <i class="tim-icons icon-single-02"></i>
                                            <span class="sidebar-normal">{{ __('User profile') }} </span>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="{{ route('user.index') }}">
                                            <i class="tim-icons icon-single-02"></i>
                                            <span class="sidebar-normal"> {{ __('User Management') }} </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                            <i class="fab fa-laravel"></i>
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
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-014') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA ETIQUETAS') }}</p>

                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('auditoriaCorte.inicioAuditoriaCorte') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-010') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('evaluacionCorte.inicioEvaluacionCorte') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('F-4') }}</p>
                                            <p style="text-align: center;">{{ __('EVALUACION DE CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('auditoriaProcesoCorte.altaProcesoCorte') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-04') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA PROCESO DE CORTE') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-001') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-009-B') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
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
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('Screen Print') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('ScreenPlanta2.InsEstamHorno') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('Inspección Después De Horno') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('ScreenPlanta2.CalidadProcesoPlancha') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('Proceso Plancha') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-001') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                                            <i class="tim-icons icon-bullet-list-67"></i>
                                            <p>{{ __('FCC-009-B') }}</p>
                                            <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <div class="navbar-toggle d-inline">
                            <button type="button" class="navbar-toggler">
                                <span class="navbar-toggler-bar bar1"></span>
                                <span class="navbar-toggler-bar bar2"></span>
                                <span class="navbar-toggler-bar bar3"></span>
                            </button>
                        </div>
                        <a class="navbar-brand" href="#">{{ $page ?? __('Dashboard') }}</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="search-bar input-group">
                                <button class="btn btn-link" id="search-button" data-toggle="modal"
                                    data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                                    <span class="d-lg-none d-md-block">{{ __('Search') }}</span>
                                </button>
                            </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <div class="notification d-none d-lg-block d-xl-block"></div>
                                    <i class="tim-icons icon-sound-wave"></i>
                                    <p class="d-lg-none"> {{ __('Notifications') }} </p>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                                    <li class="nav-link">
                                        <a href="#"
                                            class="nav-item dropdown-item">{{ __('Mike John responded to your email') }}</a>
                                    </li>
                                    <li class="nav-link">
                                        <a href="#"
                                            class="nav-item dropdown-item">{{ __('You have 5 more tasks') }}</a>
                                    </li>
                                    <li class="nav-link">
                                        <a href="#"
                                            class="nav-item dropdown-item">{{ __('Your friend Michael is in town') }}</a>
                                    </li>
                                    <li class="nav-link">
                                        <a href="#"
                                            class="nav-item dropdown-item">{{ __('Another notification') }}</a>
                                    </li>
                                    <li class="nav-link">
                                        <a href="#" class="nav-item dropdown-item">{{ __('Another one') }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <div class="photo">
                                        <img src="{{ asset('black') }}/img/anime3.png"
                                            alt="{{ __('Profile Photo') }}">
                                    </div>
                                    <b class="caret d-none d-lg-block d-xl-block"></b>
                                    <p class="d-lg-none">{{ __('Log out') }}</p>
                                </a>
                                <ul class="dropdown-menu dropdown-navbar">
                                    <li class="nav-link">
                                        <a href="{{ route('profile.edit') }}"
                                            class="nav-item dropdown-item">{{ __('Profile') }}</a>
                                    </li>
                                    <li class="nav-link">
                                        <a href="#" class="nav-item dropdown-item">{{ __('Settings') }}</a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li class="nav-link">
                                        <a href="{{ route('logout') }}" class="nav-item dropdown-item"
                                            onclick="event.preventDefault();  document.getElementById('logout-form').submit();">{{ __('Log out') }}</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="separator d-lg-none"></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog"
                aria-labelledby="searchModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                placeholder="{{ __('SEARCH') }}">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="{{ __('Close') }}">
                                <i class="tim-icons icon-simple-remove"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog"
                aria-labelledby="searchModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <input type="text" class="form-control" id="inlineFormInputGroup"
                                placeholder="SEARCH">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="tim-icons icon-simple-remove"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
            
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Users</h4>
                                    <p class="card-category"> Here you can manage users</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 text-right">
                                            <a href="#" class="btn btn-sm btn-primary" id="addUserBtn">
                                                Agregar nuevo personal <i class="tim-icons icon-single-02"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <table class="table table-hover">
                                            <thead class="text-primary">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>No. Empleado</th>
                                                    <th>Email</th>
                                                    <th>Auditor</th>
                                                    <th>Puesto</th>
                                                    <th>Creation date</th>
                                                    <th>Status User</th>
                                                    <th class="text-right">Actions</th>
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
                                                                <button class="btn btn-info btn-link editUserBtn" data-id="{{ $user->no_empleado }}" data-name="{{ $user->name }}">
                                                                    <i class="tim-icons icon-single-02"></i>
                                                                </button>
                                                                <form method="POST" action="{{ route('blockUser', ['noEmpleado' => $user->no_empleado]) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-link">
                                                                        @if ($user->Estatus == 'Baja')
                                                                            <i class="tim-icons icon-single-02"></i>
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
            
                                        <!-- Modal -->
                                        <form id="addUserForm" action="{{ route('user.AddUser') }}" method="POST">
                                            @csrf
                                            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="card-header card-header-primary" id="addUserModalLabel">Add User</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="name" class="material-icons">person</label>
                                                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="email" class="material-icons">mail</label>
                                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="no_empleado" class="material-icons">numbers</label>
                                                                <input type="number" class="form-control" name="no_empleado" id="no_empleado" placeholder="Enter no. empleado" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="10" required>
                                                            </div>
                                                            <div class="form-group row">
                                                                <span class="material-icons">key</span>
                                                                <label for="password" class="col-sm-2 col-form-label"></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text show-password-toggle" style="cursor: pointer;" onclick="togglePasswordVisibility('password')">
                                                                                <i class="material-icons">visibility</i>{{ __('Ver') }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tipo_auditoria" class="material-icons">engineering</label>
                                                                <select class="form-control" id="tipo_auditoria" name="tipo_auditoria" required>
                                                                    <!-- Las opciones se cargarán dinámicamente aquí -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editPuesto" class="material-icons">work</label>
                                                                <select class="form-control" id="editPuesto" name="editPuesto" required>
                                                                    <!-- Las opciones se cargarán dinámicamente aquí -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editPlanta" class="material-icons">apartment</label>
                                                                <select class="form-control" id="editPlanta" name="editPlanta" required>
                                                                    <option value="" disabled selected hidden>Seleccione la planta</option>
                                                                    <option value="Planta1">Ixtlahuaca</option>
                                                                    <option value="Planta2">San Bartolo</option>
                                                                </select>
                                                            </div>
                                                            <!-- Otros campos del formulario según tus necesidades -->
                                                            <button type="submit" class="bookmarkBtn">
                                                                <span class="IconContainer">
                                                                    <svg viewBox="0 0 384 512" height="0.9em" class="icon">
                                                                        <path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"></path>
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
                                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="card-header card-header-primary" id="editModalLabel">Editar Usuario</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="editId" class="material-icons">badge</label>
                                                                <input type="text" class="form-control disabled-input" name="editId" id="editId">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editName" class="material-icons">person</label>
                                                                <input type="text" class="form-control" name="editName" id="editName" placeholder="Nombre del usuario">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editTipoAuditoria" class="material-icons">engineering</label>
                                                                <select class="form-control" id="editTipoAuditoria" name="editTipoAuditoria">
                                                                    <!-- Las opciones se cargarán dinámicamente aquí -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="editPuestos" class="material-icons">work</label>
                                                                <select class="form-control" id="editPuestos" name="editPuestos">
                                                                    <!-- Las opciones se cargarán dinámicamente aquí -->
                                                                </select>
                                                            </div>
                                                            <div class="form-group row">
                                                                <span class="material-icons">lock_reset</span>
                                                                <label for="password" class="col-sm-2 col-form-label"></label>
                                                                <div class="col-sm-10">
                                                                    <div class="input-group">
                                                                        <input type="password" class="form-control" name="password_update" id="password_update" placeholder="Cambiar Contraseña">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text show-password-toggle" style="cursor: pointer;" onclick="togglePasswordVisibility('password_update')">
                                                                                <i class="material-icons">visibility</i>{{ __('Ver') }}
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
                                                                        <path d="M0 48V487.7C0 501.1 10.9 512 24.3 512c5 0 9.9-1.5 14-4.4L192 400 345.7 507.6c4.1 2.9 9 4.4 14 4.4c13.4 0 24.3-10.9 24.3-24.3V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48z"></path>
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

            <footer class="footer">
                <div class="container-fluid">

                    <div class="copyright">
                        © 2024 made with <i class="tim-icons icon-heart-2"></i> by and to Intimark</div>
                </div>
            </footer>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        <input type="hidden" name="_token" value="ub2DzAIrgUnghVvu3l3KAbbq0UztNO8yfkrDNm6n">
    </form>
    <script src="{{ asset('black') }}/js/core/jquery.min.js"></script>
    <script src="{{ asset('black') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('black') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('black') }}/js/plugins/perfect-scrollbar.jquery.min.js"></script>

    <script src="{{ asset('black') }}/js/plugins/bootstrap-notify.js"></script>

    <script src="{{ asset('black') }}/js/black-dashboard.min.js?v=1.0.0"></script>
    <script src="{{ asset('black') }}/js/theme.js"></script>

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
    <script>
        // Facebook Pixel Code Don't Delete
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window,
            document, 'script', '//connect.facebook.net/en_US/fbevents.js');
        try {
            fbq('init', '111649226022273');
            fbq('track', "PageView");
        } catch (err) {
            console.log('Facebook Track Error:', err);
        }
    </script>

    <script>
        $(document).ready(function() {
            // Mostrar el modal al hacer clic en el botón "Add user"
            $("#addUserBtn").click(function() {
                $('#addUserModal').modal('show'); // Bootstrap 5
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
                $(".alert").alert('close'); // Bootstrap 5
            }, 5000);
        });
    </script>

</body>

</html>
