<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal">{{ _('INTIMARK') }}</a>
        </div>
        <ul class="nav">
            @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                <li class="nav-item{{ $pageSlug == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li> 
                <li class="nav-item{{ $pageSlug == 'dashboardPorDia' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboar.dashboardPanta1PorDia') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Consulta por Dia') }}</p>
                    </a>
                </li>
            @endif

            @if (auth()->check() && (auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')))
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                        <i class="material-icons">admin_panel_settings</i>
                        <p>{{ __('Administrador') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="laravelExample">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'Gestion' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('altaYbaja') }}">
                                    <i class="tim-icons icon-support-17"></i>
                                    <p>{{ __('Gestión de Categorías') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal">{{ __('Perfil de usuario') }} </span>
                                </a>
                            </li>
                            <li class="nav-item active">
                                <a class="nav-link" href="{{ route('user.index') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"> {{ __('Administrador de Usuarios') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            <li class="nav-item {{ $pageSlug == 'profile' || $pageSlug == 'user-management' ? ' active' : '' }}">
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
                            <li class="nav-item{{ $pageSlug == 'Etiquetas' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('formulariosCalidad.auditoriaEtiquetas') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-014') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA ETIQUETAS') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'Progreso Corte' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('auditoriaCorte.inicioAuditoriaCorte') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-010') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA CORTE') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'Evaluacion Corte' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('evaluacionCorte.inicioEvaluacionCorte') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('F-4') }}</p>
                                    <p style="text-align: center;">{{ __('EVALUACION DE CORTE') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'Proceso Corte' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('auditoriaProcesoCorte.altaProcesoCorte') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-04') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA PROCESO DE CORTE') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'proceso' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-001') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                                    <i class="material-icons">edit_document</i>
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
                            <li class="nav-item{{ $pageSlug == 'ScreenPrint' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('ScreenPlanta2.ScreenPrint') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('Screen Print') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'InspeccionEstampado' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('ScreenPlanta2.InsEstamHorno') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('Inspección Después De Horno') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'CalidadProcesoPlancha' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('ScreenPlanta2.CalidadProcesoPlancha') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('Proceso Plancha') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'Maquila' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('ScreenPlanta2.Maquila') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('Maquila') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'proceso' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-001') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                                    <i class="material-icons">edit_document</i>
                                    <p>{{ __('FCC-009-B') }}</p>
                                    <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                                </a>
                            </li>
                        @endif
                        <li class="nav-item{{ $pageSlug == 'nulo' ? ' active' : '' }}">
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

<style>
    .sidebar {
        height: 100vh; /* Asegura que el contenedor ocupe toda la altura disponible */
        overflow: hidden; /* Desactiva el desbordamiento en el contenedor principal */
    }

    .sidebar-wrapper {
        height: 100%; /* Asegura que el contenedor ocupe toda la altura disponible */
        overflow-y: auto; /* Habilita el desplazamiento vertical */
        scrollbar-color: #6c757d #2a2c36; /* Colores del pulgar y del track */
        scrollbar-width: thin; /* Ancho de la barra de desplazamiento */
    }

    /* Personalizar las barras de desplazamiento para WebKit (Chrome, Safari) */
    .sidebar-wrapper::-webkit-scrollbar {
        width: 12px;
        height: 12px;
    }

    /* Color del track (fondo de la barra) */
    .sidebar-wrapper::-webkit-scrollbar-track {
        background: #2a2c36;
    }

    /* Color del pulgar (scroll thumb) */
    .sidebar-wrapper::-webkit-scrollbar-thumb {
        background-color: #6c757d;
        border-radius: 10px;
        border: 2px solid #2a2c36;
    }

    .sidebar-wrapper::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }
</style>
