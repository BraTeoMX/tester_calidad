@extends('layouts.app', ['pageSlug' => 'Corte', 'titlePage' => __('Corte')])

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
    @if (session('sobre-escribir'))
        <div class="alert sobre-escribir">
            {{ session('sobre-escribir') }}
        </div>
    @endif
    @if (session('status'))
        {{-- A menudo utilizado para mensajes de estado genéricos --}}
        <div class="alert alert-secondary">
            {{ session('status') }}
        </div>
    @endif
    @if (session('cambio-estatus'))
        <div class="alert cambio-estatus">
            {{ session('cambio-estatus') }}
        </div>
    @endif
    <style>
        .alerta-exito {
            background-color: #32CD32;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .sobre-escribir {
            background-color: #FF8C00;
            /* Color de fondo verde */
            color: white;
            /* Color de texto blanco */
            padding: 20px;
            border-radius: 15px;
            font-size: 20px;
        }

        .cambio-estatus {
            background-color: #800080;
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
                    <h3 class="card-title">CONTROL DE CALIDAD EN CORTE</h3>
                    {{--<h3 id="estatusValue2">Estatus: {{ $datoAX->estatus }}</h3>--}}
                </div>
                <hr> 
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Orden: {{ $datoAX->op }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Estlo: {{ $datoAX->estilo }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Planta: {{ $datoAX->planta }}</h4>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <h4>Temporada: {{ $datoAX->temporada }}</h4>
                    </div>
                </div>
                <form method="POST" action="{{ route('auditoriaCorte.formEncabezadoAuditoriaCorte') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $datoAX->id }}">
                    <input type="hidden" name="orden" value="{{ $datoAX->op }}">
                    <!-- Desde aquí inicia la edición del código para mostrar el contenido -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="cliente" class="col-sm-6 col-form-label">Cliente</label>
                            <div class="col-sm-12 d-flex align-items-center">
                                <select name="cliente" id="cliente" class="form-control"
                                    title="Por favor, selecciona una opción" required>
                                    <option value="">Selecciona una opción</option>
                                    @foreach ($CategoriaCliente as $cliente)
                                        <option value="{{ $cliente->nombre }}">{{ $cliente->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="color" class="col-sm-6 col-form-label">Color</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="color" id="color"
                                    placeholder="codigo del color" required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="material" class="col-sm-6 col-form-label">Material</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="material" id="material"
                                    placeholder="nombre del material" required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="evento" class="col-sm-9 col-form-label">CANTIDAD EVENTOS</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="evento" id="evento" required>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        {{-- 
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="trazo" class="col-sm-6 col-form-label">TRAZO</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="trazo" id="trazo"
                                    placeholder="..." required/>
                            </div>
                        </div>
                        --}}
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="pieza" class="col-sm-6 col-form-label">PIEZAS</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="pieza" id="pieza"
                                    placeholder="..." required/>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
                            <label for="lienzo" class="col-sm-6 col-form-label">LIENZOS</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="lienzo" id="lienzo"
                                    placeholder="..." required/>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="text"]');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        });
    });

</script>


    @endsection
