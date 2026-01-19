@extends('layouts.app', ['pageSlug' => 'turnos'])

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Gestión de Turnos</h4>
                        </div>
                        <div class="col-4 text-right">
                            <a href="{{ route('turnos.create') }}" class="btn btn-sm btn-primary">Nuevo Turno</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('alerts.success')
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <thead class="text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th class="text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($turnos as $turno)
                                <tr>
                                    <td>{{ $turno->id }}</td>
                                    <td>{{ $turno->nombre }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('turnos.edit', $turno->id) }}" class="btn btn-icon btn-sm btn-primary">
                                            <i class="tim-icons icon-pencil"></i>
                                        </a>
                                        <form action="{{ route('turnos.destroy', $turno->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-icon btn-sm btn-danger" onclick="confirmDelete(this)">
                                                <i class="tim-icons icon-trash-simple"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(button) {
        if (confirm('¿Estás seguro de eliminar este turno?')) {
            button.closest('form').submit();
        }
    }
</script>
@endsection