<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
          <!--  <a href="#" class="simple-text logo-mini">{{ __('BD') }}</a>-->
            <a href="#" class="simple-text logo-normal">{{ __('Intimark') }}</a>
        </div>
        <ul class="nav">
            @if (auth()->check() &&
                    (auth()->user()->hasRole('Administrador') ||
                        auth()->user()->hasRole('Gerente de Calidad')))
                <li class="nav-item{{ $pageSlug == 'dashboard' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="tim-icons icon-chart-pie-36"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>
                <li class="nav-item{{ $pageSlug == 'reporteriaInterna' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('reporteriaInterna.reporteriaInterna') }}">
                        <i class="tim-icons icon-notes"></i>
                        <p>{{ __('Reporteria Interna') }}</p>
                    </a>
                </li>
                <li class="nav-item {{ $pageSlug == 'profile' || $pageSlug == 'user-management' ? ' active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="true">
                        <i class="fab fa-laravel" ></i>
                        <p>{{ __('Admin cuentas') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse" id="laravelExample">
                        <ul class="nav">
                            <li class="nav-item{{ $pageSlug == 'profile' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('profile.edit') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal">{{ __('User profile') }} </span>
                                </a>
                            </li>
                            <li class="nav-item{{ $pageSlug == 'user-management' ? ' active' : '' }}">
                                <a class="nav-link" href="{{ route('user.index') }}">
                                    <i class="tim-icons icon-single-02"></i>
                                    <span class="sidebar-normal"> {{ __('User Management') }} </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Formularios') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('Screen Print') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Inspección Horno') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Plancha') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Maquila') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Procesos') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('AQL') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#laravel-examples1" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Administración') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="laravel-examples1">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('User Profile') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="{{ route('user.index')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('User Management') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
