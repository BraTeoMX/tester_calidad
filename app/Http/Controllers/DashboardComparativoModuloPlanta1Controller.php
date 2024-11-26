<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\AuditoriaAQL;
use App\Models\TpAuditoriaAQL;
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
    //
    public function planta1PorSemana(Request $request)
    {
        // Establecer localización en español para Carbon
        \Carbon\Carbon::setLocale('es');
        // Obtener las fechas de inicio y fin del request o establecer por defecto las últimas 6 semanas
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfWeek() : Carbon::now()->endOfWeek();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() : Carbon::now()->subWeeks(6)->startOfWeek();

        // Obtener todas las semanas dentro del rango seleccionado
        $semanas = [];
        $fechaIterativa = $fechaInicio->copy();
        while ($fechaIterativa->lte($fechaFin)) {
            $semanas[] = [
                'inicio' => $fechaIterativa->copy(),
                'fin' => $fechaIterativa->copy()->endOfWeek(),
            ];
            $fechaIterativa->addWeek();
        }

        // Obtener clientes únicos
        $clientesUnicos = AseguramientoCalidad::select('cliente')
            ->distinct()
            ->get()
            ->pluck('cliente');

        // Inicializar el arreglo para almacenar los módulos únicos y porcentajes por cliente
        $modulosPorCliente = [];

        foreach ($clientesUnicos as $cliente) {
            // Obtener los módulos únicos para el cliente
            $modulosCliente = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo'); // Solo obtener los nombres de los módulos

            $modulosPorCliente[$cliente] = [];

            // Para cada módulo, calcular los porcentajes por semana
            foreach ($modulosCliente as $modulo) {
                $semanalPorcentajes = [];

                foreach ($semanas as $semana) {
                    // Calcular la suma de "cantidad_auditada" y "cantidad_rechazada" para la semana actual
                    $cantidadAuditada = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_auditada');

                    $cantidadRechazada = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_rechazada');

                    // Calcular el porcentaje
                    if ($cantidadAuditada > 0) {
                        $porcentaje = round(($cantidadRechazada / $cantidadAuditada) * 100, 3);
                    } elseif ($cantidadAuditada === 0 && $cantidadRechazada === 0) {
                        $porcentaje = 'N/A'; // No hay datos en esta semana
                    } else {
                        $porcentaje = 0; // Si hay datos, pero auditada es 0, mostrar 0
                    }

                    $semanalPorcentajes[] = $porcentaje;
                }

                // Agregar los datos del módulo con sus porcentajes semanales
                $modulosPorCliente[$cliente][] = [
                    'modulo' => $modulo,
                    'porcentajes' => $semanalPorcentajes,
                ];
            }
        }

        // Retornar la vista con los datos necesarios
        return view('dashboarComparativaModulo.planta1PorSemana', compact('fechaInicio', 'fechaFin', 'modulosPorCliente', 'semanas'));
    }


}
