@extends('layouts.app', ['pageSlug' => 'Gestion', 'titlePage' => __('Gestion')])

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('danger'))
        <div class="alert alert-danger">
            {{ session('danger') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="row">
        <div class="card card-chart">
            <div class="card-header">
                <h2>Auditoria Etiquetas</h2>
            </div>
            <div class="card-body">
                <!-- Formulario -->
                <form action="{{ route('procesarFormularioEtiqueta') }}" method="POST">
                    @csrf
                    <div class="form-row d-flex align-items-end">
                        <!-- Select -->
                        <div class="form-group col-md-4">
                            <label for="tipoEtiqueta">Tipo de busqueda:</label>
                            <select name="tipoEtiqueta" id="tipoEtiqueta" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="OC">OC</option>
                                <option value="OV">OV</option>
                                <option value="OP">OP</option>
                                <option value="PO">PO</option>
                            </select>
                        </div>
            
                        <!-- Input -->
                        <div class="form-group col-md-4">
                            <label for="valorEtiqueta">Escribe la orden:</label>
                            <input type="text" name="valorEtiqueta" id="valorEtiqueta" class="form-control" placeholder="Escribe un valor" required>
                        </div>
            
                        <!-- Botón -->
                        <div class="form-group col-md-4">
                            <button type="submit" class="btn btn-success mt-2">Enviar</button>
                        </div>
                    </div>
                </form>
                <!-- Resultados de la búsqueda -->
                @if(isset($estilos) && count($estilos) > 0)
                    <h4 class="mt-4">Estilos encontrados:</h4>
                    <ul>
                        @foreach($estilos as $estilo)
                            <li>{{ $estilo->Estilos }}</li>
                        @endforeach
                    </ul>
                @elseif(isset($estilos))
                    <h4 class="mt-4">No se encontraron estilos.</h4>
                @endif
            </div>            
        </div>
    </div>
@endsection
