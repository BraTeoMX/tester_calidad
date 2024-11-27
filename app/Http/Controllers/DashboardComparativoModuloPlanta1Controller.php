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
        \Carbon\Carbon::setLocale('es');

        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfWeek() : Carbon::now()->endOfWeek();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() : Carbon::now()->subWeeks(6)->startOfWeek();

        $semanas = [];
        $fechaIterativa = $fechaInicio->copy();
        while ($fechaIterativa->lte($fechaFin)) {
            $semanas[] = [
                'inicio' => $fechaIterativa->copy(),
                'fin' => $fechaIterativa->copy()->endOfWeek(),
            ];
            $fechaIterativa->addWeek();
        }

        $clientesUnicos = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->select('cliente')
            ->distinct()
            ->get()
            ->pluck('cliente');

        $modulosPorCliente = [];
        $totalesPorCliente = []; // Guardar totales por cliente

        foreach ($clientesUnicos as $cliente) {
            $modulosCliente = AseguramientoCalidad::where('cliente', $cliente)
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->select('modulo')
                ->distinct()
                ->get()
                ->pluck('modulo');

            $modulosPorCliente[$cliente] = [];
            $totalesSemanasCliente = array_fill(0, count($semanas), [
                'proceso' => 0,
                'aql' => 0,
                'auditadas_proceso' => 0,
                'rechazadas_proceso' => 0,
                'auditadas_aql' => 0,
                'rechazadas_aql' => 0,
            ]);

            foreach ($modulosCliente as $modulo) {
                $semanalPorcentajes = [];

                foreach ($semanas as $key => $semana) {
                    $cantidadAuditada = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_auditada');

                    $cantidadRechazada = AseguramientoCalidad::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_rechazada');

                    $cantidadAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_auditada');

                    $cantidadRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                        ->where('modulo', $modulo)
                        ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                        ->sum('cantidad_rechazada');

                    $porcentajeProceso = ($cantidadAuditada > 0) ? round(($cantidadRechazada / $cantidadAuditada) * 100, 3) : 'N/A';
                    $porcentajeAQL = ($cantidadAuditadaAQL > 0) ? round(($cantidadRechazadaAQL / $cantidadAuditadaAQL) * 100, 3) : 'N/A';

                    $semanalPorcentajes[] = [
                        'proceso' => $porcentajeProceso,
                        'aql' => $porcentajeAQL,
                    ];

                    // Acumular valores para totales por cliente
                    $totalesSemanasCliente[$key]['auditadas_proceso'] += $cantidadAuditada;
                    $totalesSemanasCliente[$key]['rechazadas_proceso'] += $cantidadRechazada;
                    $totalesSemanasCliente[$key]['auditadas_aql'] += $cantidadAuditadaAQL;
                    $totalesSemanasCliente[$key]['rechazadas_aql'] += $cantidadRechazadaAQL;
                }

                $modulosPorCliente[$cliente][] = [
                    'modulo' => $modulo,
                    'semanalPorcentajes' => $semanalPorcentajes,
                ];
            }

            // Calcular porcentajes finales para totales por cliente
            foreach ($totalesSemanasCliente as $key => $totales) {
                $totalesSemanasCliente[$key]['proceso'] = ($totales['auditadas_proceso'] > 0)
                    ? round(($totales['rechazadas_proceso'] / $totales['auditadas_proceso']) * 100, 3)
                    : 'N/A';

                $totalesSemanasCliente[$key]['aql'] = ($totales['auditadas_aql'] > 0)
                    ? round(($totales['rechazadas_aql'] / $totales['auditadas_aql']) * 100, 3)
                    : 'N/A';
            }

            // Guardar los totales del cliente
            $totalesPorCliente[$cliente] = $totalesSemanasCliente;
        }

        return view('dashboarComparativaModulo.planta1PorSemana', compact('fechaInicio', 'fechaFin', 'modulosPorCliente', 'semanas', 'totalesPorCliente'));
    }


}
