@extends('layouts.app', ['pageSlug' => 'dashboardPorDia', 'titlePage' => __('Dashboard')])

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h2 class="card-title" style="text-align: center; font-weight: bold;">Dashboard Consulta por dia Planta 1 - Ixtlahuaca </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('dashboar.dashboardPanta1PorDia') }}" method="GET" id="filterForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha_inicio">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="{{ $fechaActual->format('Y-m-d') }}" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-secondary">Mostrar datos</button>
            </form>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Auditoria AQL por día</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td>{{ $generalAQL }}%</td> 
                                </tr>
                                <tr>
                                    <td>Planta I :</td>
                                    <td>{{ $generalAQLPlanta1 }}%</td> 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header card-header-success card-header-icon">
                    <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Auditoria de Procesos</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter">
                            <tbody>
                                <tr>
                                    <td>Porcentaje General :</td>
                                    <td>{{ $generalProceso }}%</td>
                                </tr>
                                <tr>
                                    <td>Planta I :</td>
                                    <td>{{ $generalProcesoPlanta1 }}%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">  
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Turno Normal</h3> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaAQLGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (AQL)</th>
                                    <th>Estilo</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Total piezas por Bulto</th> 
                                    <th>Total Bulto</th> 
                                    <th>Total Bulto Rechazados</th> 
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error AQL</th>
                                    <th>Defectos</th>
                                    <th>Accion Correctiva</th>
                                    <th>Operario Responsable</th>
                                    <th>Reparacion Piezas</th>
                                    <th>Piezas de Bulto Rechazado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloAQLGeneral as $item)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalAQL{{ $item['modulo'] }}">
                                                {{ $item['modulo'] }}
                                            </button>
                                        </td>
                                        <td>{{ $item['estilosUnicos'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td>
                                        <td>{{ $item['conteParoModular'] }}</td>
                                        <td>{{ $item['sumaPiezasBulto'] }}</td> 
                                        <td>{{ $item['cantidadBultosEncontrados'] }}</td> 
                                        <td>{{ $item['cantidadBultosRechazados'] }}</td> 
                                        <td>{{ $item['sumaAuditadaAQL'] }}</td> 
                                        <td>{{ $item['sumaRechazadaAQL'] }}</td> 
                                        <td>{{ number_format($item['porcentaje_error_aql'], 2) }}%</td>
                                        <td>{{ $item['defectosUnicos'] }}</td>
                                        <td>{{ $item['accionesCorrectivasUnicos'] }}</td>
                                        <td>{{ $item['operariosUnicos'] }}</td>
                                        <td>{{ $item['sumaReparacionRechazo'] }}</td>
                                        <td>{{ $item['piezasRechazadasUnicas'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Turno Normal</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaProcesoGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (Proceso)</th>
                                    <th>Estilo</th>
                                    <th>Recorridos</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error Proceso</th>
                                    <th>DEFECTOS</th>
                                    <th>ACCION CORRECTIVA</th>
                                    <th>Operarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloProcesoGeneral as $item)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalProceso{{ $item['modulo'] }}">
                                                {{ $item['modulo'] }}
                                            </button>
                                        </td>
                                        <td>{{ $item['estilosUnicos'] }}</td>
                                        <td>{{ $item['cantidadRecorridos'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoUtility'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td> 
                                        <td>{{ $item['sumaAuditadaProceso'] }}</td> 
                                        <td>{{ $item['sumaRechazadaProceso'] }}</td> 
                                        <td>{{ number_format($item['porcentaje_error_proceso'], 2) }}%</td>
                                        <td>{{ $item['defectosUnicos'] }}</td>
                                        <td>{{ $item['accionesCorrectivasUnicos'] }}</td>
                                        <td>{{ $item['operariosUnicos'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  

    <!-- Modales para AQL -->
    @foreach ($dataModuloAQLGeneral as $item)
    <div class="modal fade" id="modalAQL{{ $item['modulo'] }}" tabindex="-1" role="dialog" aria-labelledby="modalAQLLabel{{ $item['modulo'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalAQLLabel{{ $item['modulo'] }}">Detalles AQL para Módulo {{ $item['modulo'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="tablaAQLDetalle{{ $item['modulo'] }}">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th># BULTO</th>
                                <th>PIEZAS</th>
                                <th>TALLA</th>
                                <th>COLOR</th>
                                <th>ESTILO</th>
                                <th>PIEZAS INSPECCIONADAS</th>
                                <th>PIEZAS RECHAZADAS</th>
                                <th>TIPO DE DEFECTO</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['detalles'] as $registro)
                                <tr>
                                    <td>{{ $registro->minutos_paro }}</td>
                                    <td>{{ $registro->cliente }}</td>
                                    <td>{{ $registro->bulto }}</td>
                                    <td>{{ $registro->pieza }}</td>
                                    <td>{{ $registro->talla }}</td>
                                    <td>{{ $registro->color }}</td>
                                    <td>{{ $registro->estilo }}</td>
                                    <td>{{ $registro->cantidad_auditada }}</td>
                                    <td>{{ $registro->cantidad_rechazada }}</td>
                                    <td>{{ implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray()) }}</td>
                                    <td>{{ $registro->created_at->format('H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modales para Proceso -->
    @foreach ($dataModuloProcesoGeneral as $item)
    <div class="modal fade" id="modalProceso{{ $item['modulo'] }}" tabindex="-1" role="dialog" aria-labelledby="modalProcesoLabel{{ $item['modulo'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalProcesoLabel{{ $item['modulo'] }}">Detalles de Proceso para Módulo {{ $item['modulo'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="tablaProcesoDetalle{{ $item['modulo'] }}">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th>Nombre</th>
                                <th>Operacion</th>
                                <th>Piezas Auditadas</th>
                                <th>Piezas Rechazadas</th>
                                <th>Tipo de Problema</th>
                                <th>Accion Correctiva</th>
                                <th>pxp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['detalles'] as $registro)
                                <tr>
                                    <td>{{ $registro->minutos_paro }}</td>
                                    <td>{{ $registro->cliente }}</td>
                                    <td>{{ $registro->nombre }}</td>
                                    <td>{{ $registro->operacion }}</td>
                                    <td>{{ $registro->cantidad_auditada }}</td>
                                    <td>{{ $registro->cantidad_rechazada }}</td>
                                    <td>{{ implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray()) }}</td>
                                    <td>{{ $registro->ac }}</td>
                                    <td>{{ $registro->pxp }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!--
    <div class="row">  
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                     <h3 class="card-title"><i class="tim-icons icon-app text-success"></i> Modulo AQL general - Tiempo Extra</h3> 
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaAQLGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (AQL)</th>
                                    <th>Estilo</th>
                                    <th>Numero de Operarios</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Paro Modular</th>
                                    <th>Total piezas por Bulto</th> 
                                    <th>Total Bulto</th> 
                                    <th>Total Bulto Rechazados</th> 
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error AQL</th>
                                    <th>Defectos</th>
                                    <th>Accion Correctiva</th>
                                    <th>Operario Responsable</th>
                                    <th>Reparacion Piezas</th>
                                    <th>Piezas de Bulto Rechazado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloAQLGeneralTE as $item)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalAQLTE{{ $item['modulo'] }}">
                                                {{ $item['modulo'] }}
                                            </button>
                                        </td>
                                        <td>{{ $item['estilosUnicos'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td>
                                        <td>{{ $item['conteParoModular'] }}</td>
                                        <td>{{ $item['sumaPiezasBulto'] }}</td> 
                                        <td>{{ $item['cantidadBultosEncontrados'] }}</td> 
                                        <td>{{ $item['cantidadBultosRechazados'] }}</td> 
                                        <td>{{ $item['sumaAuditadaAQL'] }}</td> 
                                        <td>{{ $item['sumaRechazadaAQL'] }}</td> 
                                        <td>{{ number_format($item['porcentaje_error_aql'], 2) }}%</td>
                                        <td>{{ $item['defectosUnicos'] }}</td>
                                        <td>{{ $item['accionesCorrectivasUnicos'] }}</td>
                                        <td>{{ $item['operariosUnicos'] }}</td>
                                        <td>{{ $item['sumaReparacionRechazo'] }}</td>
                                        <td>{{ $item['piezasRechazadasUnicas'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                <h3 class="card-title"><i class="tim-icons icon-vector text-primary"></i> Modulo Proceso general - Tiempo Extra</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table tablesorter" id="tablaProcesoGeneral">
                            <thead class=" text-primary">
                                <tr>
                                    <th>Modulo (Proceso)</th>
                                    <th>Estilo</th>
                                    <th>Recorridos</th>
                                    <th>Numero de Operarios</th>
                                    <th>Numero de Utility</th>
                                    <th>Cantidad Paro</th>
                                    <th>Minutos Paro</th>
                                    <th>Promedio Minutos Paro</th>
                                    <th>Cantidad Auditados</th>
                                    <th>Cantidad Defectos</th>
                                    <th>% Error Proceso</th>
                                    <th>DEFECTOS</th>
                                    <th>ACCION CORRECTIVA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataModuloProcesoGeneralTE as $item)
                                    <tr>
                                        <td>
                                            <button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalProcesoTE{{ $item['modulo'] }}">
                                                {{ $item['modulo'] }}
                                            </button>
                                        </td>
                                        <td>{{ $item['estilosUnicos'] }}</td>
                                        <td>{{ $item['cantidadRecorridos'] }}</td>
                                        <td>{{ $item['conteoOperario'] }}</td>
                                        <td>{{ $item['conteoUtility'] }}</td>
                                        <td>{{ $item['conteoMinutos'] }}</td>
                                        <td>{{ $item['sumaMinutos'] }}</td>
                                        <td>{{ $item['promedioMinutosEntero'] }}</td> 
                                        <td>{{ $item['sumaAuditadaProceso'] }}</td> 
                                        <td>{{ $item['sumaRechazadaProceso'] }}</td> 
                                        <td>{{ number_format($item['porcentaje_error_proceso'], 2) }}%</td>
                                        <td>{{ $item['defectosUnicos'] }}</td>
                                        <td>{{ $item['accionesCorrectivasUnicos'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>  -->
    <!-- Modales para AQL -->
    <!--
    @foreach ($dataModuloAQLGeneralTE as $item)
    <div class="modal fade" id="modalAQLTE{{ $item['modulo'] }}" tabindex="-1" role="dialog" aria-labelledby="modalAQLLabel{{ $item['modulo'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalAQLLabel{{ $item['modulo'] }}">Detalles AQL para Módulo {{ $item['modulo'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="tablaAQLDetalle{{ $item['modulo'] }}">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th># BULTO</th>
                                <th>PIEZAS</th>
                                <th>TALLA</th>
                                <th>COLOR</th>
                                <th>ESTILO</th>
                                <th>PIEZAS INSPECCIONADAS</th>
                                <th>PIEZAS RECHAZADAS</th>
                                <th>TIPO DE DEFECTO</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['detalles'] as $registro)
                                <tr>
                                    <td>{{ $registro->minutos_paro }}</td>
                                    <td>{{ $registro->cliente }}</td>
                                    <td>{{ $registro->bulto }}</td>
                                    <td>{{ $registro->pieza }}</td>
                                    <td>{{ $registro->talla }}</td>
                                    <td>{{ $registro->color }}</td>
                                    <td>{{ $registro->estilo }}</td>
                                    <td>{{ $registro->cantidad_auditada }}</td>
                                    <td>{{ $registro->cantidad_rechazada }}</td>
                                    <td>{{ implode(', ', $registro->tpAuditoriaAQL->pluck('tp')->toArray()) }}</td>
                                    <td>{{ $registro->created_at->format('H:i:s') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    -->
    <!-- Modales para Proceso -->
    <!-- 
    @foreach ($dataModuloProcesoGeneralTE as $item)
    <div class="modal fade" id="modalProcesoTE{{ $item['modulo'] }}" tabindex="-1" role="dialog" aria-labelledby="modalProcesoLabel{{ $item['modulo'] }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title text-white" id="modalProcesoLabel{{ $item['modulo'] }}">Detalles de Proceso para Módulo {{ $item['modulo'] }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-responsive" id="tablaProcesoDetalle{{ $item['modulo'] }}">
                        <thead>
                            <tr>
                                <th>PARO</th>
                                <th>CLIENTE</th>
                                <th>Nombre</th>
                                <th>Operacion</th>
                                <th>Piezas Auditadas</th>
                                <th>Piezas Rechazadas</th>
                                <th>Tipo de Problema</th>
                                <th>Accion Correctiva</th>
                                <th>pxp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item['detalles'] as $registro)
                                <tr>
                                    <td>{{ $registro->minutos_paro }}</td>
                                    <td>{{ $registro->cliente }}</td>
                                    <td>{{ $registro->nombre }}</td>
                                    <td>{{ $registro->operacion }}</td>
                                    <td>{{ $registro->cantidad_auditada }}</td>
                                    <td>{{ $registro->cantidad_rechazada }}</td>
                                    <td>{{ implode(', ', $registro->tpAseguramientoCalidad->pluck('tp')->toArray()) }}</td>
                                    <td>{{ $registro->ac }}</td>
                                    <td>{{ $registro->pxp }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    -->
@endsection


@push('js')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

    <!-- DataTables JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- DataTables Buttons JavaScript -->
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>


    <script>
        $(document).ready(function() {
    // Inicializa DataTables en las tablas que ya están en el DOM
    const initializeDataTables = () => {
        const tableIds = [
            '#tablaAQLGeneral', '#tablaProcesoGeneral'
        ];

        tableIds.forEach(tableId => {
            if (!$.fn.dataTable.isDataTable(tableId)) {
                $(tableId).DataTable({
                    lengthChange: false,
                    searching: true,
                    paging: false,
                    autoWidth: false,
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Exportar a Excel',
                            className: 'btn btn-success'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'Exportar a PDF',
                            className: 'btn btn-danger'
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            className: 'btn btn-primary'
                        }
                    ],
                    columnDefs: [
                        {
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
    };

    // Inicializa DataTables para las tablas visibles al cargar la página
    initializeDataTables();

    // Inicializa DataTables cuando se abre un modal específico
    $('body').on('shown.bs.modal', function (e) {
        const modal = $(e.target);
        const tableIds = [
            '#tablaAQLGeneral', '#tablaProcesoGeneral'
        ];

        tableIds.forEach(tableId => {
            if ($(modal).find(tableId).length) {
                if (!$.fn.dataTable.isDataTable(tableId)) {
                    $(tableId).DataTable({
                        lengthChange: false,
                        searching: true,
                        paging: false,
                        autoWidth: false,
                        responsive: true,
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                extend: 'excelHtml5',
                                text: 'Exportar a Excel',
                                className: 'btn btn-success'
                            },
                            {
                                extend: 'pdfHtml5',
                                text: 'Exportar a PDF',
                                className: 'btn btn-danger'
                            },
                            {
                                extend: 'print',
                                text: 'Imprimir',
                                className: 'btn btn-primary'
                            }
                        ],
                        columnDefs: [
                            {
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
            }
        });
    });
});
    </script>
@endpush