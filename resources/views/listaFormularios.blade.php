@extends('layouts.app', ['pageSlug' => 'Formularios', 'titlePage' => __('Formularios')])

@section('content')
<div class="content">
    <div class="container-fluid">
      <div class="card">
        <div class="card-header card-header-primary">
          <h3 class="card-title">{{ __('Formularios.') }}</h3>
        </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div class="container mt-3">
                            <div class="row">
                                @if (auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta1')
                                <!-- Opción 1 -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">REPORTE AUDITORIA DE ETIQUETAS <br>FCC-014</h5>
                                            <a href="{{ route('formulariosCalidad.auditoriaEtiquetas') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Opción 2 -->
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA CORTE CONTROL DE CALIDAD<br>FCC-010</h5>
                                            <a href="{{ route('auditoriaCorte.inicioAuditoriaCorte') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">EVALUACION CORTE CONTRA PATRON<br>F-4</h5>
                                            <a href="{{ route('evaluacionCorte.inicioEvaluacionCorte') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA PROCESO DE CORTE <br>FCC-04</h5>
                                            <a href="{{ route('auditoriaProcesoCorte.altaProcesoCorte') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA DE PROCESOS <br>FCC-001</h5>
                                            <a href="{{ route('aseguramientoCalidad.altaProceso') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA FINAL A.Q.L <br>FCC-009-B</h5>
                                            <a href="{{ route('auditoriaAQL.altaAQL') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                              @endif
                              @if (auth()->check() && (auth()->user()->hasRole('Auditor') || auth()->user()->hasRole('Administrador') || auth()->user()->hasRole('Gerente de Calidad')) && auth()->user()->Planta == 'Planta2')
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">SCREEN PRINT</h5>
                                            <a href="{{ route('ScreenPlanta2.ScreenPrint') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">INSPECCIÓN DESPUES DE HORNO</h5>
                                            <a href="{{ route('ScreenPlanta2.InsEstamHorno') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">CALIDAD PROCESO DE PLANCHA</h5>
                                            <a href="{{ route('ScreenPlanta2.CalidadProcesoPlancha') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">MAQUILA</h5>
                                            <a href="{{ route('ScreenPlanta2.Maquila') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA DE PROCESOS <br>FCC-001</h5>
                                            <a href="{{ route('aseguramientoCalidad.altaProceso') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('material') }}/img/Intimark.png" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">AUDITORIA FINAL A.Q.L <br>FCC-009-B</h5>
                                            <a href="{{ route('auditoriaAQL.altaAQL') }}" class="btn btn-primary"  >INICIAR</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- Repite para cada opción que tengas -->

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
