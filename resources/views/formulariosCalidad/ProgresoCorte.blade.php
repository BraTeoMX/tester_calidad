@extends('layouts.app', ['pageSlug' => 'Progreso Corte', 'titlePage' => __('Progreso Corte')])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header card-header-primary">
                    <h3 class="card-title">{{ __('Progreso Corte.') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-left">
                            <a href="auditoriaCortes" class="btn btn-sm btn-secundary" id="NewCorteBtn">
                                {{ __('Nueva Auditoria Corte.') }}
                                <label for="name" class="material-icons" style="font-size: 29px;">edit_note</label>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-8 text-rigth">
                        <h3>Estatus Permisos</h3>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
