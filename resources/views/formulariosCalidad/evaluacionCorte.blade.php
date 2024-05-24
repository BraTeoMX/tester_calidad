@extends('layouts.app', ['pageSlug' => 'Evaluacion Corte', 'titlePage' => __('Evaluacion Corte')])

@section('content')
    {{-- ... dentro de tu vista ... --}}
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alerta-exito">
            {{ session('success') }}
            @if (session('sorteo'))
                <br>{{ session('sorteo') }}
            @endif
        </div>
    @endif
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #28a745;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }
    </style>
    {{-- ... el resto de tu vista ... --}}
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <!--Aqui se edita el encabezado que es el que se muestra -->
                <div class="card-header card-header-primary">
                    <h3 class="card-title">EVALUACION DE CORTE CONTRA PATRON</h3>
                </div>
                <form method="POST" action="{{ route('formulariosCalidad.formEvaluacionCorte') }}">
                    @csrf
                    <hr>
                    <div class="card-body">
                        <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="fecha" class="col-sm-6 col-form-label">FECHA</label>
                                <div class="col-sm-12">
                                    {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">ESTILO</label>
                                <div class="col-sm-12">
                                    <select name="estilo" id="estilo" class="form-control select2" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <!--Este apartado debe ser modificado despues -->
                                <label for="descripcion" class="col-sm-6 col-form-label">DESCRIPCION</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control me-2" name="descripcion" id="descripcion"
                                        placeholder=" COMENTARIOS" required />
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 style="text-align: center">IZQUIERDA</h5>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="izquierda_x" class="col-sm-6 col-form-label">X </label>
                                <div class="col-sm-12">
                                    <select name="izquierda_x" id="izquierda_x" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="izquierda_y" class="col-sm-3 col-form-label">Y </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="izquierda_y" id="izquierda_y"
                                        placeholder="Ingresa y " required title="Por favor, selecciona una opción"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5 style="text-align: center">DERECHA</h5>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="derecha_x" class="col-sm-6 col-form-label">X </label>
                                <div class="col-sm-12">
                                    <select name="derecha_x" id="derecha_x" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="derecha_y" class="col-sm-3 col-form-label">Y </label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" name="derecha_y" id="derecha_y"
                                        placeholder="Ingresa y " required title="Por favor, selecciona una opción"
                                        oninput="this.value = this.value.toUpperCase()">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <!--Fin de la edicion del codigo para mostrar el contenido-->
                    </div>
                <form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>
@endsection
