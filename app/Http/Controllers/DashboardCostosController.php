<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use App\Models\AuditoriaAQL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB


class DashboardCostosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */

    public function dashboardCostosNoCalidad(Request $request)
    {
        // Establecer localización en español para Carbon
        \Carbon\Carbon::setLocale('es');
        // Obtener las fechas de inicio y fin del request o establecer por defecto las últimas 6 semanas
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfDay() : Carbon::now()->endOfDay();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() : Carbon::now()->subWeeks(6)->startOfDay();

        // Definir el valor del costo por minuto
        $costoUSD = 0.21;

        // Consultar datos agrupados por semana, contando los paros y sumando los minutos
        $costoPorSemana = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->whereNotNull('minutos_paro') // Solo considerar registros donde minutos_paro no es null
            ->selectRaw("WEEK(created_at, 1) as semana, COUNT(*) as paros_proceso, CAST(SUM(minutos_paro) AS UNSIGNED) as min_paro_proc")
            ->groupBy('semana')
            ->get()
            ->map(function ($item) use ($costoUSD) {
                // Calcular el costo en USD
                $item->costo_usd = $item->min_paro_proc * $costoUSD;
                return $item;
            });

        // Obtener totales de la suma de minutos de paro y costo para semanas
        $totalParoSemana = $costoPorSemana->sum('paros_proceso');
        $totalMinParoSemana = $costoPorSemana->sum('min_paro_proc');
        $totalCostoSemana = $costoPorSemana->sum('costo_usd');

        // Obtener el mes y año de la fecha de fin para excluir el mes actual
        $mesActual = $fechaFin->month;
        $anioActual = $fechaFin->year;

        // Consulta de costos por mes, excluyendo el mes actual
        $costoPorMes = AseguramientoCalidad::whereYear('created_at', $anioActual)
            ->whereMonth('created_at', '<', $mesActual)
            ->whereNotNull('minutos_paro')
            ->selectRaw("MONTH(created_at) as mes, COUNT(*) as paros_proceso, CAST(SUM(minutos_paro) AS UNSIGNED) as min_paro_proc")
            ->groupBy('mes')
            ->get()
            ->map(function ($item) use ($costoUSD) {
                $item->costo_usd = $item->min_paro_proc * $costoUSD;
                $item->mes_nombre = ucfirst(\Carbon\Carbon::create()->month($item->mes)->translatedFormat('F')); 
                return $item;
            });
        //
        // Obtener totales de la suma de minutos de paro y costo para meses
        $totalParoMes = $costoPorMes->sum('paros_proceso');
        $totalMinParoMes = $costoPorMes->sum('min_paro_proc');
        $totalCostoMes = $costoPorMes->sum('costo_usd');

        // Obtener clientes únicos
        $clientesUnicos = AseguramientoCalidad::select('cliente')
            ->distinct()
            ->get()
            ->pluck('cliente');

        // Inicializar el arreglo para almacenar el costo por semana por cliente y defecto
        $costoPorSemanaClientes = [];

        foreach ($clientesUnicos as $cliente) {
            // Obtener los defectos únicos y su conteo para cada cliente
            $defectosPorCliente = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->with(['tpAseguramientoCalidad' => function ($query) {
                    $query->where('tp', '!=', 'NINGUNO');
                }])
                ->get()
                ->flatMap(function ($aseguramiento) {
                    return $aseguramiento->tpAseguramientoCalidad;
                })
                ->groupBy('tp')
                ->map(function ($defectos, $tp) {
                    return [
                        'defecto_unico' => $tp,
                        'conteo' => $defectos->count(),
                    ];
                })
                ->sortByDesc('conteo'); // Ordenar de mayor a menor por 'conteo'
        
            // Calcular el total de conteo para este cliente
            $totalConteo = $defectosPorCliente->sum('conteo');
        
            // Agregar el porcentaje y porcentaje acumulado a cada defecto
            $porcentajeAcumulado = 0;
            $defectosPorCliente = $defectosPorCliente->map(function ($defecto) use ($totalConteo, &$porcentajeAcumulado) {
                $defecto['porcentaje'] = $totalConteo > 0 ? ($defecto['conteo'] / $totalConteo) * 100 : 0;
                $porcentajeAcumulado += $defecto['porcentaje'];
                $defecto['porcentaje_acumulado'] = round($porcentajeAcumulado, 3);
                return $defecto;
            });
        
            // Solo agregar al array si existen defectos para el cliente
            if ($defectosPorCliente->isNotEmpty()) {
                $costoPorSemanaClientes[$cliente] = [
                    'defectos' => $defectosPorCliente->values(), // Convertir a valores numéricos
                    'total_conteo' => $totalConteo,
                ];
            }
        }
        
        

        return view('dashboar.dashboardCostosNoCalidad', compact('fechaInicio', 'fechaFin', 'costoPorSemana', 'costoPorMes', 
                'totalParoSemana','totalMinParoSemana', 'totalCostoSemana', 'totalParoMes', 'totalMinParoMes', 'totalCostoMes',
                'costoPorSemanaClientes'));

    }


}
