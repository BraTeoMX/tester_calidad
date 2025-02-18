<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
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
    <title><?php echo e(config('app.name', 'Black Dashboard Laravel - Free Laravel Preset')); ?></title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo e(asset('black')); ?>/img/apple-icon.png">
    <link rel="icon" type="image/png" href="<?php echo e(asset('black')); ?>/img/favicon.ico">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,600,700,800" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- Icons -->
    <link href="<?php echo e(asset('black')); ?>/css/nucleo-icons.css" rel="stylesheet" />
    <!-- CSS -->
    <link href="<?php echo e(asset('black')); ?>/css/black-dashboard.css?v=1.0.0" rel="stylesheet" />
    <link href="<?php echo e(asset('black')); ?>/css/theme.css" rel="stylesheet" />
</head>

<body class="">
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text logo-normal"><?php echo e(_('INTIMARK')); ?></a>
                </div>
                <ul class="nav">
                    <?php if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad'))): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('home')); ?>">
                                <i class="tim-icons icon-chart-pie-36"></i>
                                <p><?php echo e(__('Dashboard')); ?></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('dashboar.dashboardPanta1PorDia')); ?>">
                                <i class="tim-icons icon-tap-02"></i>
                                <p><?php echo e(__('Consulta por Dia')); ?></p>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad'))): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                                <i class="material-icons">admin_panel_settings</i>
                                <p><?php echo e(__('Administrador')); ?>

                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="collapse" id="laravelExample">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('altaYbaja')); ?>">
                                            <i class="tim-icons icon-support-17"></i>
                                            <p><?php echo e(__('Gestión de Categorías')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('profile.edit')); ?>">
                                            <i class="tim-icons icon-single-02"></i>
                                            <span class="sidebar-normal"><?php echo e(__('Perfil de usuario')); ?> </span>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="<?php echo e(route('user.index')); ?>">
                                            <i class="tim-icons icon-single-02"></i>
                                            <span class="sidebar-normal"> <?php echo e(__('Administrador de Usuarios')); ?> </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                            <i class="material-icons">note_alt</i>
                            <p><?php echo e(__('Formularios Calidad')); ?>

                                <b class="caret"></b>
                            </p>
                        </a>
                        <div class="collapse" id="laravelExamples">
                            <ul class="nav">
                                <?php if(auth()->check() &&
                                        (auth()->user()->hasRole('Auditor') ||
                                            auth()->user()->hasRole('Administrador') ||
                                            auth()->user()->hasRole('Gerente de Calidad')) &&
                                        auth()->user()->Planta == 'Planta1'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('formulariosCalidad.auditoriaEtiquetas')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-014')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA ETIQUETAS')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('auditoriaCorte.inicioAuditoriaCorte')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-010')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA CORTE')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('evaluacionCorte.inicioEvaluacionCorte')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('F-4')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('EVALUACION DE CORTE')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('auditoriaProcesoCorte.altaProcesoCorte')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-04')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA PROCESO DE CORTE')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('aseguramientoCalidad.altaProceso')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-001')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA DE PROCESOS')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('auditoriaAQL.altaAQL')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-009-B')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA FINAL A.Q.L')); ?></p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if(auth()->check() &&
                                        (auth()->user()->hasRole('Auditor') ||
                                            auth()->user()->hasRole('Administrador') ||
                                            auth()->user()->hasRole('Gerente de Calidad')) &&
                                        auth()->user()->Planta == 'Planta2'): ?>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.ScreenPrint')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('Screen Print')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.InsEstamHorno')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('Inspección Después De Horno')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.CalidadProcesoPlancha')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('Proceso Plancha')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('ScreenPlanta2.Maquila')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('Maquila')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('aseguramientoCalidad.altaProceso')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-001')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA DE PROCESOS')); ?></p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="<?php echo e(route('auditoriaAQL.altaAQL')); ?>">
                                            <i class="material-icons">edit_document</i>
                                            <p><?php echo e(__('FCC-009-B')); ?></p>
                                            <p style="text-align: center;"><?php echo e(__('AUDITORIA FINAL A.Q.L')); ?></p>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-item">
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                    <p>&nbsp;</p>
                                </li>
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
                        <a class="navbar-brand" href="#"><?php echo e($page ?? __('Dashboard')); ?></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
                        aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                        <span class="navbar-toggler-bar navbar-kebab"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav ml-auto">
                            <li class="dropdown nav-item">
                                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                                    <div class="photo">
                                        <img src="<?php echo e(asset('black')); ?>/img/anime3.png"
                                            alt="<?php echo e(__('Profile Photo')); ?>">
                                    </div>
                                    <b class="caret d-none d-lg-block d-xl-block"></b>
                                    <p class="d-lg-none"><?php echo e(__('Log out')); ?></p>
                                </a>
                                <ul class="dropdown-menu dropdown-navbar">
                                    <li class="nav-link">
                                        <a href="<?php echo e(route('profile.edit')); ?>"
                                            class="nav-item dropdown-item"><?php echo e(__('Profile')); ?></a>
                                    </li>
                                    <li class="dropdown-divider"></li>
                                    <li class="nav-link">
                                        <a href="<?php echo e(route('logout')); ?>" class="nav-item dropdown-item" onclick="event.preventDefault();  document.getElementById('logout-form').submit();"><?php echo e(__('Cerrar sesion')); ?></a>
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
                                placeholder="<?php echo e(__('SEARCH')); ?>">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-label="<?php echo e(__('Close')); ?>">
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
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>
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
                                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addUserModal">
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
                                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($user->name); ?></td>
                                                        <td><?php echo e($user->no_empleado); ?></td>
                                                        <td><?php echo e($user->email); ?></td>
                                                        <td><?php echo e($user->tipo_auditor); ?></td>
                                                        <td><?php echo e($user->puesto); ?></td>
                                                        <td><?php echo e($user->created_at); ?></td>
                                                        <?php if($user->Estatus == "Baja"): ?>
                                                            <td style="color: #ff4d4d !important;"><?php echo e($user->Estatus); ?></td>
                                                        <?php else: ?>
                                                            <td><?php echo e($user->Estatus); ?></td>
                                                        <?php endif; ?>
                                                        <td class="td-actions text-right">
                                                            <div class="btn-group" role="group" aria-label="Acciones">
                                                                <button class="btn btn-info btn-link editUserBtn" data-id="<?php echo e($user->no_empleado); ?>" data-name="<?php echo e($user->name); ?>" data-toggle="modal" data-target="#editModal">
                                                                    <i class="tim-icons icon-pencil"></i>
                                                                </button>
                                                                <form method="POST" action="<?php echo e(route('blockUser', ['noEmpleado' => $user->no_empleado])); ?>">
                                                                    <?php echo method_field('PUT'); ?>
                                                                    <?php echo csrf_field(); ?>
                                                                    <button type="submit" class="btn btn-danger btn-link">
                                                                        <?php if($user->Estatus == 'Baja'): ?>
                                                                            <i class="tim-icons icon-alert-circle-exc"></i>
                                                                        <?php else: ?>
                                                                            <i class="tim-icons icon-single-02"></i>
                                                                        <?php endif; ?>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
            
                                        <!-- Modal Add User -->
                                        <form id="addUserForm" action="<?php echo e(route('user.AddUser')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content bg-dark text-white">
                                                        <div class="modal-header">
                                                            <h3 class="modal-title" style="color: aliceblue" id="addUserModalLabel">Add User</h3>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="name" class="form-label">Name</label>
                                                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="no_empleado" class="form-label">No. Empleado</label>
                                                                <input type="number" class="form-control" name="no_empleado" id="no_empleado" placeholder="Enter no. empleado" maxlength="10" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="password" class="form-label">Password</label>
                                                                <div class="input-group">
                                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                                                    <button class="btn btn-warning" type="button" onclick="togglePasswordVisibility('password')">Ver
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tipo_auditoria" class="form-label">Tipo Auditoria</label>
                                                                <select class="form-control" id="tipo_auditoria1" name="tipo_auditoria" required>
                                                                    <option value="" disabled selected hidden>Seleccione el tipo de auditoria</option>
                                                                    <?php $__currentLoopData = $tipoAuditoriaDatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($tipo->Tipo_auditoria); ?>"><?php echo e($tipo->Tipo_auditoria); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editPuesto" class="form-label">Puesto</label>
                                                                <select class="form-control" id="editPuesto1" name="editPuesto" required>
                                                                    <option value="" disabled selected hidden>Seleccione el puesto</option>
                                                                    <?php $__currentLoopData = $puestoDatos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $puesto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <option value="<?php echo e($puesto->Puesto); ?>"><?php echo e($puesto->Puesto); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                                            <button type="submit" class="btn btn-primary">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form> 

                                        <!-- Modal Edit User -->
                                        <form id="editUser" action="<?php echo e(route('users.editUser')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content bg-dark text-white">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="editId" class="form-label">ID</label>
                                                                <input type="text" class="form-control disabled-input" name="editId" id="editId" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editName" class="form-label">Name</label>
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
                                                                <label for="password_update" class="form-label">Password</label>
                                                                <div class="input-group">
                                                                    <input type="password" class="form-control" name="password_update" id="password_update" placeholder="Cambiar Contraseña">
                                                                    <button class="btn btn-warning" type="button" onclick="togglePasswordVisibility('password_update')">Ver
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
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
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>
    <script src="<?php echo e(asset('black')); ?>/js/core/jquery.min.js"></script>
    <script src="<?php echo e(asset('black')); ?>/js/core/popper.min.js"></script>
    <script src="<?php echo e(asset('black')); ?>/js/core/bootstrap.min.js"></script>
    <script src="<?php echo e(asset('black')); ?>/js/plugins/perfect-scrollbar.jquery.min.js"></script>

    <script src="<?php echo e(asset('black')); ?>/js/plugins/bootstrap-notify.js"></script>

    <script src="<?php echo e(asset('black')); ?>/js/black-dashboard.min.js?v=1.0.0"></script>
    <script src="<?php echo e(asset('black')); ?>/js/theme.js"></script>

    <?php echo $__env->yieldPushContent('js'); ?>

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
    <?php echo $__env->yieldPushContent('js'); ?>
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
            // Deshabilitar visualmente los campos editId y editName
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

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>


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

</body>

</html>
<?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\users\index.blade.php ENDPATH**/ ?>