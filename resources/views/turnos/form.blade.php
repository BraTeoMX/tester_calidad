@extends('layouts.app', ['pageSlug' => 'turnos'])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ isset($turno) ? 'Editar Turno' : 'Crear Turno' }}</h5>
                    <a href="{{ route('turnos.index') }}" class="btn btn-sm btn-secondary">Regresar</a>
                </div>
                <form method="POST" action="{{ isset($turno) ? route('turnos.update', $turno->id) : route('turnos.store') }}" autocomplete="off">
                    @csrf
                    @if(isset($turno))
                    @method('PUT')
                    @endif

                    <div class="card-body">
                        <div class="form-group{{ $errors->has('nombre') ? ' has-danger' : '' }}">
                            <label>Nombre del Turno</label>
                            <input type="text" name="nombre" class="form-control{{ $errors->has('nombre') ? ' is-invalid' : '' }}" placeholder="Nombre" value="{{ old('nombre', $turno->nombre ?? '') }}" required>
                            @include('alerts.feedback', ['field' => 'nombre'])
                        </div>

                        <h4 class="mt-4">Horario Semanal</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Descanso (Sin turno)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $dias = [
                                    1 => 'Lunes',
                                    2 => 'Martes',
                                    3 => 'Miércoles',
                                    4 => 'Jueves',
                                    5 => 'Viernes',
                                    6 => 'Sábado',
                                    7 => 'Domingo'
                                    ];
                                    // Obtener horario existente, asegurando array si es null
                                    $horarioActual = isset($turno) && $turno->horario_semanal ? $turno->horario_semanal : [];
                                    @endphp

                                    @foreach($dias as $numDia => $nombreDia)
                                    @php
                                    $inicio = $horarioActual[$numDia]['inicio'] ?? '';
                                    $fin = $horarioActual[$numDia]['fin'] ?? '';
                                    $descanso = (!isset($horarioActual[$numDia]) || (empty($inicio) && empty($fin))) ? true : false;
                                    @endphp
                                    <tr data-dia="{{ $numDia }}">
                                        <td>{{ $nombreDia }}</td>
                                        <td>
                                            <input type="time" name="horarios[{{ $numDia }}][inicio]" class="form-control time-input" value="{{ $inicio }}">
                                        </td>
                                        <td>
                                            <input type="time" name="horarios[{{ $numDia }}][fin]" class="form-control time-input" value="{{ $fin }}">
                                        </td>
                                        <td>
                                            <div class="form-check">
                                                <!-- Checkbox para invalidar el día (si no se trabaja) -->
                                                <!-- Se maneja con JS para limpiar los inputs o simplemente si están vacíos no se guarda regla -->
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection