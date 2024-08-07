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
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: PROCESO</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="{{ route('crearDefectoProceso') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoProceso" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaTipoProblemaProceso as $tp)
                              <tr>
                                <td>{{ $tp->nombre }}</td>
                                <td>{{ $tp->estado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoDefectoProceso', $tp->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if ($tp->estado == 1)
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        @endif
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
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: PLAYERA</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="{{ route('crearDefectoPlayera') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoPlayera" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaTipoProblemaPlayera as $tp)
                              <tr>
                                <td>{{ $tp->nombre }}</td>
                                <td>{{ $tp->estado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoDefectoProceso', $tp->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if ($tp->estado == 1)
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        @endif
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
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Defectos: EMPAQUE</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo defecto</label>
                    <form action="{{ route('crearDefectoEmpaque') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre del defecto" required>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaDefectoEmpaque" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre del defecto</th>
                                    <th>Estatus</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaTipoProblemaEmpaque as $tp)
                              <tr>
                                <td>{{ $tp->nombre }}</td>
                                <td>{{ $tp->estado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoDefectoEmpaque', $tp->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        @if ($tp->estado == 1)
                                            <button type="submit" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" class="btn btn-success">Alta</button>
                                        @endif
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
    <hr>
    <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Utility</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Utility</label>
                    <form action="{{ route('crearUtility') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del utility" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaUtility" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaUtility as $dato)
                              <tr>
                                <td>{{ $dato->nombre }}</td>
                                <td>{{ $dato->numero_empleado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoUtility', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->planta == 'Intimark1')
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('actualizarEstadoUtility', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->estado == 1)
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        @endif
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
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Responsable</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Responsable</label>
                    <form action="{{ route('crearResponsable') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del Responsable" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaResponsable" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaResponsable as $dato)
                              <tr>
                                <td>{{ $dato->nombre }}</td>
                                <td>{{ $dato->numero_empleado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoResponsable', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->planta == 'Intimark1')
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('actualizarEstadoResponsable', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->estatus == 1)
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        @endif
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
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h3>Categoria Tecnicos Corte</h3>
                </div>
                <div class="card-body">
                    <label for="nombre">Alta de nuevo Tecnico Corte</label>
                    <form action="{{ route('crearTecnico') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group mb-0 mr-2">
                            <input type="text" class="form-control mr-2" id="nombre" name="nombre" placeholder="Nombre del Responsable" required>
                            <input type="number" class="form-control" id="numero_empleado" name="numero_empleado" placeholder="Numero de empleado"  step="1">
                        </div>
                        <div class="input-group mb-0 mr-2">
                            <select name="planta" id="planta" class="form-control mr-2" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Intimark1">Planta 1</option>
                                <option value="Intimark2">Planta 2</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <div class="table-responsive">
                        <table id="tablaTecnico" class="table tablesorter">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Nombre</th>
                                    <th>No. Empleado</th>
                                    <th>Planta</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($categoriaTecnico as $dato)
                              <tr>
                                <td>{{ $dato->nombre }}</td>
                                <td>{{ $dato->numero_empleado }}</td>
                                <td>
                                    <form action="{{ route('actualizarEstadoTecnico', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->planta == 'Intimark1')
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 1</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarPlanta" class="btn btn-secondary">Planta 2</button>
                                        @endif
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('actualizarEstadoTecnico', $dato->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('PATCH')
                                        @if ($dato->estado == 1)
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-danger">Baja</button>
                                        @else
                                            <button type="submit" name="action" value="cambiarEstado" class="btn btn-success">Alta</button>
                                        @endif
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

    
@endsection

@push('js')
    <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            const tableIds = ['#tablaDefectoProceso', '#tablaDefectoPlayera', '#tablaDefectoEmpaque', '#tablaUtility', '#tablaResponsable', '#tablaTecnico'];
            
            tableIds.forEach(tableId => {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: true,
                        pageLength: 5,
                        autoWidth: false,
                        responsive: true,
                        columnDefs: [
                            {
                                targets: -1,
                                searchable: false,
                                orderable: false,
                            },
                        ],
                        language: {
                            "sProcessing":     "Procesando...",
                            "sLengthMenu":     "Mostrar _MENU_ registros",
                            "sZeroRecords":    "No se encontraron resultados",
                            "sEmptyTable":     "Ningún dato disponible en esta tabla",
                            "sInfo":           "Registros _START_ - _END_ de _TOTAL_ mostrados",
                            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                            "sInfoPostFix":    "",
                            "sSearch":         "Buscar:",
                            "sUrl":            "",
                            "sInfoThousands":  ",",
                            "sLoadingRecords": "Cargando...",
                            "oPaginate": {
                                "sFirst":    "Primero",
                                "sLast":     "Último",
                                "sNext":     "Siguiente",
                                "sPrevious": "Anterior"
                            },
                            "oAria": {
                                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                            }
                        },
                        initComplete: function(settings, json) {
                            if ($('body').hasClass('dark-mode')) {
                                $(tableId + '_wrapper').addClass('dark-mode');
                            }
                        }
                    });
                }
            });
        });
    </script>
    <script>
        document.getElementById('numero_empleado').addEventListener('input', function (e) {
            let value = e.target.value;
            if (value.includes('.')) {
                e.target.value = value.replace('.', '');
            }
        });
    </script>
@endpush
