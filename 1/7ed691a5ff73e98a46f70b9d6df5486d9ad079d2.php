<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
        <div class="navbar-wrapper d-none">
            <div class="navbar-toggle d-inline">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            <a class="navbar-brand" href="#"><?php echo e($page ?? __('Dashboard')); ?></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="<?php echo e(__('Toggle navigation')); ?>">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
                <!--<li class="search-bar input-group">
                    <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                        <span class="d-lg-none d-md-block"><?php echo e(__('Search')); ?></span>
                    </button>
                </li>-->
                
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="photo">
                            <img src="<?php echo e(asset('black')); ?>/img/anime3.png" alt="<?php echo e(__('Profile Photo')); ?>">
                        </div>
                        <b class="caret d-none d-lg-block d-xl-block"></b> 
                        <p class="d-lg-none"><?php echo e(__('Log out')); ?></p>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <li class="nav-link">
                            <a href="<?php echo e(route('profile.edit')); ?>" class="nav-item dropdown-item"><?php echo e(__('Perfil')); ?></a>
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
<div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="<?php echo e(__('SEARCH')); ?>">
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo e(__('Close')); ?>">
                    <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Estilo para el fondo oscuro y texto blanco */
    .dropdown.nav-item .dropdown-toggle.nav-link {
        background-color: #212431; /* Fondo oscuro */
        color: #fff; /* Texto blanco */
        font-weight: bold; /* Texto en negritas */
    }

    .dropdown.nav-item .dropdown-menu.dropdown-navbar {
        background-color: #333; /* Fondo oscuro para el menú desplegable */
    }

    .dropdown.nav-item .dropdown-menu.dropdown-navbar .dropdown-item {
        color: #fff; /* Texto blanco para cada elemento del menú */
        font-weight: bold; /* Texto en negritas */
    }

    .dropdown.nav-item .dropdown-menu.dropdown-navbar .dropdown-item:hover {
        background-color: #444; /* Color un poco más claro al pasar el ratón */
    }

    /* Para el ícono de logout en dispositivos pequeños */
    .dropdown.nav-item .dropdown-toggle.nav-link p {
        color: #fff;
        font-weight: bold;
    }

</style><?php /**PATH E:\xampp8.2\htdocs\calidad2\resources\views\layouts\navbars\navs\auth.blade.php ENDPATH**/ ?>