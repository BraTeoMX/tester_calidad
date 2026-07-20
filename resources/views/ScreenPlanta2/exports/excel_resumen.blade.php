<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #28a745; color: #ffffff;">
                Resumen General del Periodo ({{ $rangoFechasTexto }})
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #e9ecef;">Máquina</th>
            <th style="font-weight: bold; background-color: #e9ecef; text-align: right;">Cantidad Auditada</th>
            <th style="font-weight: bold; background-color: #e9ecef; text-align: right;">Defectos Screen</th>
            <th style="font-weight: bold; background-color: #e9ecef; text-align: right;">Defectos Plancha</th>
            <th style="font-weight: bold; background-color: #e9ecef; text-align: right;">Total Defectos</th>
            <th style="font-weight: bold; background-color: #e9ecef; text-align: right;">Porcentaje Def. (%)</th>
        </tr>
    </thead>
    <tbody>
        @if(!empty($resumenGeneral['detallePorMaquina']))
            @foreach($resumenGeneral['detallePorMaquina'] as $detalle)
                <tr>
                    <td>{{ $detalle['nombreMaquina'] }}</td>
                    <td style="text-align: right;">{{ $detalle['cantidadAuditada'] }}</td>
                    <td style="text-align: right;">{{ $detalle['cantidadScreenDefectos'] }}</td>
                    <td style="text-align: right;">{{ $detalle['cantidadPlanchaDefectos'] }}</td>
                    <td style="text-align: right;">{{ $detalle['cantidadDefectosCombinados'] }}</td>
                    <td style="text-align: right;">{{ $detalle['porcentajeDefectos'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <td style="font-weight: bold; background-color: #f8f9fa;">TOTAL GENERAL:</td>
            <td style="font-weight: bold; background-color: #f8f9fa; text-align: right;">{{ $resumenGeneral['totalCantidadAuditadaGlobal'] ?? 0 }}</td>
            <td style="font-weight: bold; background-color: #f8f9fa; text-align: right;">{{ $resumenGeneral['totalScreenDefectosGlobal'] ?? 0 }}</td>
            <td style="font-weight: bold; background-color: #f8f9fa; text-align: right;">{{ $resumenGeneral['totalPlanchaDefectosGlobal'] ?? 0 }}</td>
            <td style="font-weight: bold; background-color: #f8f9fa; text-align: right;">{{ $resumenGeneral['totalDefectosCombinadosGlobal'] ?? 0 }}</td>
            <td style="font-weight: bold; background-color: #f8f9fa; text-align: right;">{{ $resumenGeneral['porcentajeDefectosGlobal'] ?? 0 }}</td>
        </tr>
    </tfoot>
</table>
