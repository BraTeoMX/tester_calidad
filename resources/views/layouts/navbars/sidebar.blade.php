<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal">{{ _('INTIMARK') }}</a>
        </div>
        <ul class="nav">
            @if (auth()->check() && (auth()->user()->hasRole('kanban')))
                <li class="nav-item{{ $pageSlug == 'kanban' ? ' active' : '' }}">
                    @if(auth()->user()->no_empleado == '4')
                        <a class="nav-link" href="{{ route('kanban.indexCalidad') }}">
                    @else
                        <a class="nav-link" href="{{ route('kanban.index') }}">
                    @endif
                        <i class="material-icons">edit_document</i>
                        <p>{{ __('AUDITORIA KANBAN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'reporte_kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.reporte') }}">
                        <i class="tim-icons icon-molecule-40"></i>
                        <p>{{ __('REPORTE KANBAN') }}</p>
                    </a>
                </li>
            @endif
            @if (auth()->check() &&  auth()->user()->hasRole('Gerente'))
                <li class="nav-item{{ $pageSlug == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorDiaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Día') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorDiaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta1' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorSemanaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Semana') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorSemanaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta1V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta2V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item{{ $pageSlug == 'HornoReporte' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('reportesScreen.index') }}">
                        <i class="tim-icons icon-basket-simple"></i>
                        <p>{{ __('CONSULTA SCREEN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'dashboardComparativoClientes' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboarComparativaModulo.semanaComparativaGeneral') }}">
                        <i class="tim-icons icon-bag-16"></i>
                        <p>{{ __('Comparativo Clientes') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'busquedaOP' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('busqueda_OP.index') }}">
                        <i class="material-symbols-outlined">action_key</i>
                        <p>{{ __('Buscar Por OP') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'reporte_kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.reporte') }}">
                        <i class="tim-icons icon-molecule-40"></i>
                        <p>{{ __('REPORTE KANBAN') }}</p>
                    </a>
                </li>
            @endif

            @if (auth()->check() && (auth()->user()->hasRole('Administrador')))
                <li class="nav-item{{ $pageSlug == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorDiaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Día') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorDiaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta1' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorSemanaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Semana') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorSemanaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta1V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta2V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item{{ $pageSlug == 'HornoReporte' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('reportesScreen.index') }}">
                        <i class="tim-icons icon-basket-simple"></i>
                        <p>{{ __('CONSULTA SCREEN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'dashboardComparativoClientes' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboarComparativaModulo.semanaComparativaGeneral') }}">
                        <i class="tim-icons icon-bag-16"></i>
                        <p>{{ __('Comparativo Clientes') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'busquedaOP' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('busqueda_OP.index') }}">
                        <i class="material-symbols-outlined">action_key</i>
                        <p>{{ __('Buscar Por OP') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.index') }}">
                        <i class="material-icons">edit_document</i>
                        <p>{{ __('AUDITORIA KANBAN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'reporte_kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.reporte') }}">
                        <i class="tim-icons icon-molecule-40"></i>
                        <p>{{ __('REPORTE KANBAN') }}</p>
                    </a>
                </li>
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
                            <li class="nav-item{{ $pageSlug == 'GestionBusqueda' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('gestion.agregarAqlProceso') }}">
                                    <i class="tim-icons icon-zoom-split"></i>
                                    <p>{{ __('Busqueda Bulto/Estilo') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'bnf' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('bnf.index') }}">
                                    <i class="material-symbols-outlined">delete_history</i>
                                    <p>{{ __('Paros No Finalizados') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('gestionUsuario') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"> {{ __('Administrador de Usuarios') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (auth()->user()->hasRole('Gerente de Calidad'))
                <li class="nav-item{{ $pageSlug == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorDiaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Día') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorDiaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta1' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardPorDiaPlanta2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#consultaPorSemanaMenu" aria-expanded="false">
                        <i class="tim-icons icon-tap-02"></i>
                        <p>{{ __('Consulta por Semana') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="consultaPorSemanaMenu">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta1V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta1V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 1 - Ixtlahuaca</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'dashboardSemanaPlanta2V2' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('dashboardSemanaPlanta2V2') }}">
                                    <i class="tim-icons icon-pin"></i>
                                    <p>Planta 2 - San Bartolo</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item{{ $pageSlug == 'HornoReporte' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('reportesScreen.index') }}">
                        <i class="tim-icons icon-basket-simple"></i>
                        <p>{{ __('CONSULTA SCREEN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'dashboardComparativoClientes' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('dashboarComparativaModulo.semanaComparativaGeneral') }}">
                        <i class="tim-icons icon-bag-16"></i>
                        <p>{{ __('Comparativo Clientes') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'busquedaOP' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('busqueda_OP.index') }}">
                        <i class="material-symbols-outlined">action_key</i>
                        <p>{{ __('Buscar Por OP') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.index') }}">
                        <i class="material-icons">edit_document</i>
                        <p>{{ __('AUDITORIA KANBAN') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'reporte_kanban' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('kanban.reporte') }}">
                        <i class="tim-icons icon-molecule-40"></i>
                        <p>{{ __('REPORTE KANBAN') }}</p>
                    </a>
                </li>
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
                            <li class="nav-item{{ $pageSlug == 'GestionBusqueda' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('gestion.agregarAqlProceso') }}">
                                    <i class="tim-icons icon-zoom-split"></i>
                                    <p>{{ __('Busqueda Bulto/Estilo') }}</p>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'bnf' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('bnf.index') }}">
                                    <i class="material-symbols-outlined">delete_history</i>
                                    <p>{{ __('Paros No Finalizados') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('gestionUsuario') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"> {{ __('Administrador de Usuarios') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (auth()->check() &&
                (auth()->user()->hasRole('Auditor') ||
                auth()->user()->hasRole('Administrador') ||
                auth()->user()->hasRole('Gerente de Calidad')))
                <li class="nav-item {{ $pageSlug == 'profile' || $pageSlug == 'user-management' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                        <i class="material-icons">note_alt</i>
                        <p>{{ __('Formularios Calidad') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="laravelExamples">
                        <ul class="nav">
                            @if (auth()->user()->Planta == 'Planta1')
                                <li class="nav-item{{ $pageSlug == 'Etiquetas' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('etiquetas_v2') }}">
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
                                <li class="nav-item{{ $pageSlug == 'proceso' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('procesoV3.inicio') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-001') }}</p>
                                        <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('AQLV3.inicio') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-009-B') }}</p>
                                        <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'consulta_corte_final' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('auditoriaCorte.index') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('Consulta Corte Final') }}</p>
                                    </a>
                                </li>
                            @endif
                            @if (auth()->user()->Planta == 'Planta2')
                                <li class="nav-item{{ $pageSlug == 'Horno' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('inspeccionEstampadoHorno') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('Inspección Después De Horno') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'Screen' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('screenV2') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('Screen Print') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'Plancha' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('planchaV2') }}">
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
                                    <a class="nav-link" href="{{ route('procesoV3.inicio') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-001') }}</p>
                                        <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('AQLV3.inicio') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('FCC-009-B') }}</p>
                                        <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item{{ $pageSlug == 'consulta_corte_final' ? ' active' : '' }}">
                                    <a class="nav-link" href="{{ route('auditoriaCorte.index') }}">
                                        <i class="material-icons">edit_document</i>
                                        <p>{{ __('Consulta Corte Final') }}</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            <li class="nav-item{{ $pageSlug == 'nulo' ? ' active' : '' }}">
                <h1></h1>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
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
