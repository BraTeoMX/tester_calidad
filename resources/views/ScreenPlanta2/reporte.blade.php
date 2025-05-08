@extends('layouts.app', ['pageSlug' => 'HornoReporte', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')

    <div class="content">
        <div class="card">
            <div class="card-header card-header-primary">
                <div class="row">
                    <div class="col-md-9">
                        <h3 class="card-title">Reporte Screen</h3>
                    </div>
                    <div class="col-md-3 text-right">
                        Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 d-flex align-items-center">
                <div class="form-group w-100">
                    <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div class="form-group">
                    <label class="d-block">&nbsp;</label> <!-- Espacio para alinear con el input -->
                    <button type="button" class="btn btn-secondary" id="btnMostrar">Mostrar datos</button>
                </div>
            </div>
        </div>

        <div class="card card-body">
        </div>
    </div>


@endsection