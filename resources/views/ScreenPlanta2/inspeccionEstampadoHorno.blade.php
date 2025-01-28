@extends('layouts.app', ['pageSlug' => 'InspeccionEstampado', 'titlePage' => __('Inspeccion Estampado Despues del Horno')])

@section('content')
    <div class="content">
            <div class="card">
                <div class="card-header card-header-primary">
                    <div class="row">
                        <div class="col-md-9">
                            <h3 class="card-title">Inspeccion Estampado Despues del Horno.</h3>
                        </div>
                        <div class="col-md-3 text-right">
                            Fecha: {{ now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y') }}
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="thead-primary">
                                    <tr>
                                        <th>OP</th>
                                        <th>Cliente</th>
                                        <th>Estilo</th>
                                        <th>Color</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
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

        .texto-blanco {
            color: white !important;
        }
    </style>
@endsection
