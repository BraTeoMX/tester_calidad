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


class DashboardComparativoModuloPlanta1Controller extends Controller
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

    public function planta1PorSemana(Request $request)
    {
        // Establecer localización en español para Carbon
        \Carbon\Carbon::setLocale('es');
        // Obtener las fechas de inicio y fin del request o establecer por defecto las últimas 6 semanas
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfDay() : Carbon::now()->endOfDay();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() : Carbon::now()->subWeeks(6)->startOfDay();

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
        
        return view('dashboarComparativaModulo.planta1PorSemana', compact('fechaInicio', 'fechaFin', 'modulosPorCliente'));

    }

}
