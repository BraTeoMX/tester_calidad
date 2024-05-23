@extends('layouts.app', ['pageSlug' => 'AQL', 'titlePage' => __('AQL')])

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
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h3 class="card-title">AUDITORIA CONTROL DE CALIDAD</h3>
                        </div>
                        <div class="col-auto">
                            <h4>Fecha:
                                {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                            </h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="card-body">
                    <table class="table table55">
                        <thead class="thead-primary">
                            <tr>
                                <tr>
                                    <th>MODULO</th>
                                    <th>OP</th>
                                    <th>CLIENTE</th>
                                    <th>TEAM LEADER</th>
                                    <th>AUDITOR</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{$encabezadoAQL->modulo}}</td>
                                    <td>{{$encabezadoAQL->op}}</td>
                                    <td>{{$encabezadoAQL->cliente}}</td>
                                    <td>{{$encabezadoAQL->team_leader}}</td>
                                    <td>{{$encabezadoAQL->auditor}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <hr>
                    <form method="POST" action="{{ route('auditoriaAQL.formRegistroAuditoriaProcesoAQL') }}">
                        @csrf
                            <div class="table-responsive">
                                <table class="table table32"> 
                                    <thead class="thead-primary">
                                        <tr>
                                            <th># BULTO</th>
                                            <th>PIEZAS</th>
                                            <th>ESTILO</th>
                                            <th>COLOR</th>
                                            <th>TALLA</th>
                                            <th>PIEZAS INSPECCIONADAS</th>
                                            <th>PIEZAS RECHAZADAS</th>
                                            <th>TIPO DE DEFECTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select name="bulto" id="bulto" class="form-control" required title="Por favor, selecciona una opción">
                                                    <option value="">Selecciona una opción</option>
                                                    @foreach ($datoBultos as $bulto)
                                                        <option value="{{ $bulto->prodpackticketid }}" data-estilo="{{ $bulto->itemid }}" data-color="{{ $bulto->colorname }}" data-talla="{{ $bulto->inventsizeid }}" data-pieza="{{ $bulto->qty }}">
                                                            {{ $bulto->prodpackticketid }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="pieza" id="pieza" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="estilo" id="estilo" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="color" id="color" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="talla" id="talla" readonly>
                                            </td>
                                            
                                            <script>
                                                $(document).ready(function() {
                                                    $('#bulto').change(function() {
                                                        var selectedOption = $(this).find(':selected');
                                                        $('#pieza').val(selectedOption.data('pieza'));
                                                        $('#estilo').val(selectedOption.data('estilo'));
                                                        $('#color').val(selectedOption.data('color'));
                                                        $('#talla').val(selectedOption.data('talla'));
                                                    });
                                            
                                                    // Actualizar valores al cargar la página si una opción está seleccionada por defecto
                                                    var selectedOption = $('#bulto').find(':selected');
                                                    $('#pieza').val(selectedOption.data('pieza'));
                                                    $('#estilo').val(selectedOption.data('estilo'));
                                                    $('#color').val(selectedOption.data('color'));
                                                    $('#talla').val(selectedOption.data('talla'));
                                                });
                                            </script>                                            
                                            
                                            <td><input type="text" class="form-control" name="cantidad_auditada"
                                                    id="cantidad_auditada" required></td>
                                            <td><input type="text" class="form-control" name="cantidad_rechazada"
                                                    id="cantidad_rechazada" required></td>
                                            <td>
                                                <select name="tp[]" id="tp" class="form-control" required multiple 
                                                    title="Por favor, selecciona una opción">
                                                    <option value="NINGUNO">NINGUNO</option>
                                                    @if ($data['area'] == 'AUDITORIA AQL')
                                                        @foreach ($categoriaTPProceso as $proceso)
                                                            <option value="{{ $proceso->nombre }}">{{ $proceso->nombre }}
                                                            </option>
                                                        @endforeach
                                                    @elseif($data['area'] == 'AUDITORIA AQL PLAYERA')
                                                        @foreach ($categoriaTPPlayera as $playera)
                                                            <option value="{{ $playera->nombre }}">{{ $playera->nombre }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                    <hr>
                    <!--Desde aqui inicia la edicion del codigo para mostrar el contenido-->
                    <table class="table table55">
                        <thead class="thead-primary">
                            <tr>
                                <tr>
                                    <th>MODULO</th>
                                    <th>OP</th>
                                    <th>CLIENTE</th>
                                    <th>TEAM LEADER</th>
                                    <th>AUDITOR</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td>{{$encabezadoAQL->modulo}}</td>
                                    <td>{{$encabezadoAQL->op}}</td>
                                    <td>{{$encabezadoAQL->cliente}}</td>
                                    <td>{{$encabezadoAQL->team_leader}}</td>
                                    <td>{{$encabezadoAQL->auditor}}</td>
                                </tr>
                        </tbody>
                    </table>
                    <!--Fin de la edicion del codigo para mostrar el contenido-->
                </div>
            </div>
        </div>

    </div>

    <style>
        thead.thead-primary {
            background-color: #59666e54;
            /* Azul claro */
            color: #333;
            /* Color del texto */
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#modulo').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            $('#modulo').on('select2:select', function(e) {
                var itemid = e.params.data.element.dataset.itemid;
                $('#estilo').val(itemid);
            });
        });

        $(document).ready(function() {
            $('#op').select2({
                placeholder: 'Seleccione una opción',
                allowClear: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#modulo').change(function() {
                var itemid = $(this).find(':selected').data('itemid');
                $('#estilo').val(itemid);
            });
        });
    </script>





@endsection
