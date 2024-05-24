@extends('layouts.app', ['pageSlug' => 'Empaque', 'titlePage' => __('Empaque')])

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
                    <h3 class="card-title">INTIMARK CONTROL DE CALIDAD EMPAQUE</h3>
                </div>
                <form method="POST" action="{{ route('formulariosCalidad.formControlCalidadEmpaque') }}">
                    @csrf
                    <hr>
                    <div class="card-body">
                        <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha" class="col-sm-3 col-form-label">Fecha</label>
                                <div class="col-sm-12 d-flex justify-content-between align-items-center">
                                    <p>{{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                                    </p>
                                    <p class="ml-auto">Dia: {{ $nombreDia }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cliente" class="col-sm-6 col-form-label">No. DE CAJAS EN EMBARQUE /
                                    CARTONS</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="cliente" id="cliente" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaCliente as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ old('cliente') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="talla" class="col-sm-6 col-form-label">TOTAL DE CAJAS / TOTA OF
                                    CARTONS</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="talla" id="talla"
                                        placeholder="..." required />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">SERIE DE / FROM</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}"
                                                {{ old('estilo') == $estilo->id ? 'selected' : '' }}>{{ $estilo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="color" class="col-sm-3 col-form-label">A/ TO</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="color" id="color" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old('color') == $color->id ? 'selected' : '' }}>{{ $color->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="descripcion" class="col-sm-6 col-form-label">CAJAS AUDITADAS / CARTONS</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="descripcion" id="descripcion"
                                        placeholder="..." required />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cantidad_auditada" class="col-sm-6 col-form-label">No. DE BOLSAS X CAJA / BAGS
                                    IN CARTON</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="cantidad_auditada"
                                        id="cantidad_auditada" placeholder="..." required />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mpo" class="col-sm-6 col-form-label">No. DE PIEZAS EN BOLSA / PC PER
                                    BAG</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="mpo" id="mpo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaTamañoMuestra as $mpo)
                                            <option value="{{ $mpo->id }}"
                                                {{ old('mpo') == $mpo->id ? 'selected' : '' }}>{{ $mpo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div style="background: #833e06a2">
                            <h4 style="text-align: center"> - - - - </h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="muestra" class="col-sm-6 col-form-label">M.P.O </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <input type="number" class="form-control" name="muestra" id="muestra"
                                        placeholder="..." required title="..." />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tipo_defecto" class="col-sm-6 col-form-label">C.P.O </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <input type="number" class="form-control" name="tipo_defecto" id="tipo_defecto"
                                        placeholder="..." required title="..." />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cliente" class="col-sm-6 col-form-label">CLIENTE/CUSTOMER</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="cliente" id="cliente" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaCliente as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mayor" class="col-sm-6 col-form-label">CÓDIGO DE CLASE: CLASS </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="mayor" id="mayor" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaDefecto as $mayor)
                                            <option value="{{ $mayor->id }}">{{ $mayor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mayor" class="col-sm-6 col-form-label">CODIGO ESTILO/ STYLE CODE </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="mayor" id="mayor" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaDefecto as $mayor)
                                            <option value="{{ $mayor->id }}">{{ $mayor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">ÉSTILO/STYLE </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">CÓDIGO COLOR/ COLOR CODE </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">COLOR/COLOR DESC </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">TALLA/SIZE </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">PESO/ WEIGHT </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div style="background: #770347">
                            <h4 style="text-align: center; color:aliceblue"> C C </h4>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="muestra" class="col-sm-6 col-form-label">C.C </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <input type="text" class="form-control" name="muestra" id="muestra"
                                        placeholder="..." required title="..." />
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="cliente" class="col-sm-6 col-form-label">TURNO/SHIFT</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="cliente" id="cliente" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaCliente as $cliente)
                                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mayor" class="col-sm-6 col-form-label">No DE CAJA / No. Of Carton</label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="mayor" id="mayor" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaDefecto as $mayor)
                                            <option value="{{ $mayor->id }}">{{ $mayor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mayor" class="col-sm-6 col-form-label">Talla / size </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="mayor" id="mayor" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaDefecto as $mayor)
                                            <option value="{{ $mayor->id }}">{{ $mayor->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">No. Piezas / Pieces </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="estilo" class="col-sm-6 col-form-label">UPC / SKU LABEL </label>
                                <div class="col-sm-12 d-flex align-items-center">
                                    <select name="estilo" id="estilo" class="form-control" required
                                        title="Por favor, selecciona una opción">
                                        <option value="">Selecciona una opción</option>
                                        @foreach ($CategoriaEstilo as $estilo)
                                            <option value="{{ $estilo->id }}">{{ $estilo->nombre }}</option>
                                        @endforeach
                                    </select>
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
    <style>
        /* Estilos personalizados para los elementos de tipo "radio" */
        input[type="radio"] {
            width: 20px;
            /* Ancho personalizado */
            height: 20px;
            /* Altura personalizada */
            /* Otros estilos personalizados según tus necesidades */
        }

        .col-form-label-radio {
            font-size: 16px;
            /* Tamaño de fuente personalizado */
            color: #142b4b;
            /* Color de texto personalizado */
            margin-left: 50px;
            /* Espacio entre el radio y el texto (ajusta según tus necesidades) */
            font-weight: bold;
            /* Texto en negritas (bold) */
            /* Otros estilos personalizados según tus necesidades */
        }


        .col-form-label {
            font-size: 16px;
            /* Tamaño de fuente personalizado */
            color: #142b4b;
            /* Color de texto personalizado */
            /* Otros estilos personalizados según tus necesidades */
        }
    </style>
    <style>
        /* Anula la propiedad position: fixed en .modal-backdrop */
        .modal-backdrop {
            position: static !important;
            /* Cambia a 'static' para permitir la interacción */
        }
    </style>


@endsection
