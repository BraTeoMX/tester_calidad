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

        // Obtener los módulos únicos para cada cliente
        $modulosPorCliente = [];

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

            // Obtener los módulos únicos asociados a cada cliente dentro del rango de fechas
            $modulosCliente = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->select('modulo')
                ->distinct()
                ->get()
                ->map(function ($registro) use ($cliente, $fechaInicio, $fechaFin) {
                    // Sumar los minutos de paro por cada módulo
                    $minutosParo = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $registro->modulo)
                        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                        ->sum('minutos_paro');
        
                    // Obtener los estilos únicos asociados a este módulo
                    $estilos = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $registro->modulo)
                        ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                        ->distinct()
                        ->pluck('estilo')
                        ->unique()
                        ->implode(', '); // Concatenar estilos únicos separados por coma
        
                    return [
                        'modulo' => $registro->modulo,
                        'minutos_paro_proceso' => $minutosParo ?: 0, // Asignar 0 si no hay datos
                        'estilos' => $estilos, // Estilos concatenados
                    ];
                });
        
            // Solo agregar si existen módulos para el cliente
            if ($modulosCliente->isNotEmpty()) {
                $totalMinutosParo = $modulosCliente->sum('minutos_paro_proceso');
        
                // Calcular el porcentaje para cada módulo
                $modulosCliente = $modulosCliente->map(function ($modulo) use ($totalMinutosParo) {
                    $modulo['porcentaje'] = $totalMinutosParo > 0 
                        ? round(($modulo['minutos_paro_proceso'] / $totalMinutosParo) * 100, 2) 
                        : 0; // Asigna 0% si el total es 0
                    return $modulo;
                });
        
                $modulosPorCliente[$cliente] = [
                    'modulos' => $modulosCliente->values(), // Obtener solo los valores
                    'total_modulos' => $modulosCliente->count(),
                    'total_minutos_paro' => $totalMinutosParo, // Suma total de minutos de paro
                ];
            }
        }
        
        // Calcular el gran total de minutos de paro proceso para todos los clientes
        $granTotalMinutosParo = collect($modulosPorCliente)->sum('total_minutos_paro');
        
        // Añadir el porcentaje de cada cliente respecto al gran total
        $modulosPorCliente = collect($modulosPorCliente)->map(function ($data) use ($granTotalMinutosParo) {
            $data['porcentaje_entre_gran_total_cliente'] = $granTotalMinutosParo > 0
                ? round(($data['total_minutos_paro'] / $granTotalMinutosParo) * 100, 2)
                : 0; // Asigna 0% si el gran total es 0
            return $data;
        })->toArray();
        
        

        return view('dashboar.dashboardCostosNoCalidad', compact('fechaInicio', 'fechaFin', 'costoPorSemana', 'costoPorMes', 
                'totalParoSemana','totalMinParoSemana', 'totalCostoSemana', 'totalParoMes', 'totalMinParoMes', 'totalCostoMes',
                'costoPorSemanaClientes', 'modulosPorCliente', 'granTotalMinutosParo'));

    }


}
