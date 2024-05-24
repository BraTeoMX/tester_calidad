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
            <li class="nav-item {{ $pageSlug == 'profile' || $pageSlug == 'user-management' ? ' active' : '' }}">
                <a class="nav-link" data-toggle="collapse" href="#laravelExamples" aria-expanded="true">
                    <i class="fab fa-laravel"></i>
                    <p>{{ __('Formularios Calidad') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExamples">
                    <ul class="nav">
                        @if (auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta1')
                        <li class="nav-item{{ $pageSlug == 'Etiquetas' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('formulariosCalidad.auditoriaEtiquetas') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('FCC-014') }}</p>
                                <p style="text-align: center;">{{ __('AUDITORIA ETIQUETAS') }}</p>
                            </a>
                        </li>
                        <li class="nav-item{{ $pageSlug == 'Progreso Corte' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('auditoriaCorte.inicioAuditoriaCorte') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('FCC-010') }}</p>
                                <p style="text-align: center;">{{ __('AUDITORIA CORTE') }}</p>
                            </a>
                        </li>
                        <li class="nav-item{{ $pageSlug == 'Evaluacion Corte' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('evaluacionCorte.inicioEvaluacionCorte') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('F-4') }}</p>
                                <p style="text-align: center;">{{ __('EVALUACION DE CORTE') }}</p>
                            </a>
                        </li>
                        <li class="nav-item{{ $pageSlug == 'Proceso Corte' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('auditoriaProcesoCorte.altaProcesoCorte') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('FCC-04') }}</p>
                                <p style="text-align: center;">{{ __('AUDITORIA PROCESO DE CORTE') }}</p>
                            </a>
                        </li>
                        <li class="nav-item{{ $pageSlug == 'proceso' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('FCC-001') }}</p>
                                <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                            </a>
                        </li>
                        <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('FCC-009-B') }}</p>
                                <p style="text-align: center;">{{ __('AUDITORIA FINAL A.Q.L') }}</p>
                            </a>
                        </li>
                    @endif
                    @if (auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta2')
                    <li class="nav-item{{ $pageSlug == 'ScreenPrint' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('ScreenPlanta2.ScreenPrint') }}">
                            <i class="tim-icons icon-single-02"></i>
                            <p>{{ __('Screen Print') }}</p>
                        </a>
                    </li>
                    <li class="nav-item{{ $pageSlug == 'InspeccionEstampado' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('ScreenPlanta2.InsEstamHorno') }}">
                            <i class="tim-icons icon-single-02"></i>
                            <p>{{ __('Inspección Después De Horno') }}</p>
                        </a>
                    </li>
                    <li class="nav-item{{ $pageSlug == 'CalidadProcesoPlancha' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('ScreenPlanta2.CalidadProcesoPlancha') }}">
                            <i class="tim-icons icon-single-02"></i>
                            <p>{{ __('Proceso Plancha') }}</p>
                        </a>
                    </li>
                    <li class="nav-item{{ $pageSlug == 'Maquila' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('ScreenPlanta2.Maquila') }}">
                            <i class="tim-icons icon-single-02"></i>
                            <p>{{ __('Maquila') }}</p>
                        </a>
                    </li>
                    <li class="nav-item{{ $pageSlug == 'proceso' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('aseguramientoCalidad.altaProceso') }}">
                            <i class="tim-icons icon-single-02"></i>
                            <p>{{ __('FCC-001') }}</p>
                            <p style="text-align: center;">{{ __('AUDITORIA DE PROCESOS') }}</p>
                        </a>
                    </li>
                    <li class="nav-item{{ $pageSlug == 'AQL' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('auditoriaAQL.altaAQL') }}">
                            <i class="tim-icons icon-single-02"></i>
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
