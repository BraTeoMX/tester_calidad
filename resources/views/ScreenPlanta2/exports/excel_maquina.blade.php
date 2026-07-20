<table>
    <thead>
        <tr>
            <th colspan="16" style="font-weight: bold; font-size: 14px; text-align: center; background-color: #17a2b8; color: #ffffff;">
                Reporte Máquina: {{ $nombreMaquina }} - {{ $rangoFechasTexto }}
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Auditor</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Bulto</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">OP</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Cliente</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Estilo</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Color</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff; text-align: right;">Cantidad</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Panel</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Gráfica</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Técnicas</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Fibras</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Técnico Screen</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Defectos Screen</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Técnico Plancha</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Defectos Plancha</th>
            <th style="font-weight: bold; background-color: #59666e; color: #ffffff;">Hora Registro</th>
        </tr>
    </thead>
    <tbody>
        @foreach($registros as $registro)
            <tr>
                <td>{{ $registro['auditor'] }}</td>
                <td>{{ $registro['bulto'] }}</td>
                <td>{{ $registro['op'] }}</td>
                <td>{{ $registro['cliente'] }}</td>
                <td>{{ $registro['estilo'] }}</td>
                <td>{{ $registro['color'] }}</td>
                <td style="text-align: right;">{{ $registro['cantidad'] }}</td>
                <td>{{ $registro['panel'] }}</td>
                <td>{{ $registro['grafica'] }}</td>
                <td>{{ $registro['tecnicasTexto'] ?? 'N/A' }}</td>
                <td>{{ $registro['fibrasTexto'] ?? 'N/A' }}</td>
                <td>{{ $registro['tecnico_screen'] }}</td>
                <td>{{ $registro['screenDefectosTexto'] ?? 'N/A' }}</td>
                <td>{{ $registro['tecnico_plancha'] }}</td>
                <td>{{ $registro['planchaDefectosTexto'] ?? 'N/A' }}</td>
                <td>{{ $registro['fecha'] }}</td>
            </tr>
        @endforeach
    </tbody>
    @if($resumen)
        <tfoot>
            <tr>
                <td colspan="6" style="font-weight: bold; text-align: right; background-color: #f8f9fa;">TOTALES MÁQUINA:</td>
                <td style="font-weight: bold; text-align: right; background-color: #f8f9fa;">{{ $resumen['totalCantidadAuditada'] }}</td>
                <td colspan="4" style="background-color: #f8f9fa;"></td>
                <td colspan="2" style="font-weight: bold; text-align: right; background-color: #f8f9fa;">Def. Screen: {{ $resumen['totalScreenDefectos'] }}</td>
                <td colspan="2" style="font-weight: bold; text-align: right; background-color: #f8f9fa;">Def. Plancha: {{ $resumen['totalPlanchaDefectos'] }}</td>
                <td style="font-weight: bold; text-align: right; background-color: #f8f9fa;">
                    Total Def: {{ $resumen['totalDefectosCombinados'] }} ({{ $resumen['porcentajeDefectos'] }}%)
                </td>
            </tr>
        </tfoot>
    @endif
</table>
