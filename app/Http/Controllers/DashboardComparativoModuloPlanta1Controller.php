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

        // Fechas de inicio y fin
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfWeek() : Carbon::now()->endOfWeek();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() : Carbon::now()->subWeeks(6)->startOfWeek();

        // Generar semanas en el rango
        $semanas = [];
        $fechaIterativa = $fechaInicio->copy();
        while ($fechaIterativa->lte($fechaFin)) {
            $semanas[] = [
                'inicio' => $fechaIterativa->copy(),
                'fin' => $fechaIterativa->copy()->endOfWeek(),
            ];
            $fechaIterativa->addWeek();
        }

        // Clientes únicos
        $clientesUnicos = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->select('cliente')
            ->distinct()
            ->get()
            ->pluck('cliente')
            ->sort();

        // Inicializar arreglos para almacenar los datos
        $modulosPorCliente = [];
        $totalesPorCliente = [];
        $modulosPorClienteYEstilo = [];
        $totalesPorClienteYEstilo = [];
        $modulosPorClientePlanta1 = [];
        $totalesPorClientePlanta1 = [];
        $modulosPorClientePlanta2 = [];
        $totalesPorClientePlanta2 = [];

        // Obtener datos para cada cliente
        foreach ($clientesUnicos as $cliente) {
            // Datos generales (sin estilo)
            [$modulosPorCliente[$cliente], $totalesPorCliente[$cliente]] = $this->getDatosPorCliente($cliente, $fechaInicio, $fechaFin, $semanas);

            // Estilos únicos asociados al cliente
            $estilosUnicos = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('cliente', $cliente)
                ->select('estilo')
                ->distinct()
                ->get()
                ->pluck('estilo');

            foreach ($estilosUnicos as $estilo) {
                // Datos específicos por cliente y estilo
                [$modulosPorClienteYEstilo[$cliente][$estilo], $totalesPorClienteYEstilo[$cliente][$estilo]] =
                    $this->getDatosPorClienteYEstilo($cliente, $estilo, $fechaInicio, $fechaFin, $semanas);
            }

            // Datos Planta 1 - Ixtlahuaca
            [$modulosPorClientePlanta1[$cliente], $totalesPorClientePlanta1[$cliente]] = $this->getDatosPorCliente($cliente, $fechaInicio, $fechaFin, $semanas, 'Intimark1');

            // Datos Planta 2 - San Bartolo
            [$modulosPorClientePlanta2[$cliente], $totalesPorClientePlanta2[$cliente]] = $this->getDatosPorCliente($cliente, $fechaInicio, $fechaFin, $semanas, 'Intimark2');
        }

        return view('dashboarComparativaModulo.planta1PorSemanaComparativa', compact(
            'fechaInicio',
            'fechaFin',
            'modulosPorCliente',
            'totalesPorCliente',
            'modulosPorClienteYEstilo',
            'totalesPorClienteYEstilo',
            'modulosPorClientePlanta1',
            'totalesPorClientePlanta1',
            'modulosPorClientePlanta2',
            'totalesPorClientePlanta2',
            'semanas'
        ));
    }

    /**
     * Función privada para obtener datos por cliente y planta (si aplica).
     */
    private function getDatosPorCliente($cliente, $fechaInicio, $fechaFin, $semanas, $planta = null)
    {
        // Consulta base para AseguramientoCalidad (proceso)
        $queryCalidad = AseguramientoCalidad::where('cliente', $cliente)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        // Consulta base para AuditoriaAQL (AQL)
        $queryAQL = AuditoriaAQL::where('cliente', $cliente)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if ($planta) {
            $queryCalidad->where('planta', $planta);
            $queryAQL->where('planta', $planta);
        }

        // Obtener módulos únicos
        $modulosCliente = $queryCalidad->select('modulo')->distinct()->get()->pluck('modulo');

        $modulos = [];
        $totalesSemanas = array_fill(0, count($semanas), [
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
                // Datos para AseguramientoCalidad
                $cantidadAuditada = AseguramientoCalidad::where('cliente', $cliente)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');

                $cantidadRechazada = AseguramientoCalidad::where('cliente', $cliente)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                // Datos para AuditoriaAQL
                $cantidadAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_auditada');

                $cantidadRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->when($planta, function ($query) use ($planta) {
                        return $query->where('planta', $planta);
                    })
                    ->sum('cantidad_rechazada');

                // Cálculo de porcentajes
                $porcentajeProceso = ($cantidadAuditada > 0) ? round(($cantidadRechazada / $cantidadAuditada) * 100, 3) : 'N/A';
                $porcentajeAQL = ($cantidadAuditadaAQL > 0) ? round(($cantidadRechazadaAQL / $cantidadAuditadaAQL) * 100, 3) : 'N/A';

                $semanalPorcentajes[] = [
                    'proceso' => $porcentajeProceso,
                    'aql' => $porcentajeAQL,
                ];

                // Acumular totales para cada semana
                $totalesSemanas[$key]['auditadas_proceso'] += $cantidadAuditada;
                $totalesSemanas[$key]['rechazadas_proceso'] += $cantidadRechazada;
                $totalesSemanas[$key]['auditadas_aql'] += $cantidadAuditadaAQL;
                $totalesSemanas[$key]['rechazadas_aql'] += $cantidadRechazadaAQL;
            }

            $modulos[] = [
                'modulo' => $modulo,
                'semanalPorcentajes' => $semanalPorcentajes,
            ];
        }

        foreach ($totalesSemanas as $key => $totales) {
            $totalesSemanas[$key]['proceso'] = ($totales['auditadas_proceso'] > 0)
                ? round(($totales['rechazadas_proceso'] / $totales['auditadas_proceso']) * 100, 3)
                : 'N/A';

            $totalesSemanas[$key]['aql'] = ($totales['auditadas_aql'] > 0)
                ? round(($totales['rechazadas_aql'] / $totales['auditadas_aql']) * 100, 3)
                : 'N/A';
        }

        return [$modulos, $totalesSemanas];
    }

    /**
     * Función privada para obtener datos por cliente y estilo.
    */
    private function getDatosPorClienteYEstilo($cliente, $estilo, $fechaInicio, $fechaFin, $semanas, $planta = null)
    {
        // Consulta base para AseguramientoCalidad (proceso)
        $queryCalidadBase = AseguramientoCalidad::where('cliente', $cliente)
            ->where('estilo', $estilo)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        // Consulta base para AuditoriaAQL (AQL)
        $queryAQLBase = AuditoriaAQL::where('cliente', $cliente)
            ->where('estilo', $estilo)
            ->whereBetween('created_at', [$fechaInicio, $fechaFin]);

        if ($planta) {
            $queryCalidadBase->where('planta', $planta);
            $queryAQLBase->where('planta', $planta);
        }

        // Obtener módulos únicos
        $modulosCliente = $queryCalidadBase->select('modulo')->distinct()->get()->pluck('modulo');

        $modulos = [];
        $totalesSemanas = array_fill(0, count($semanas), [
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
                // Clonar las consultas para evitar modificar las originales
                $queryCalidad = clone $queryCalidadBase;
                $queryAQL = clone $queryAQLBase;

                // Datos para AseguramientoCalidad
                $cantidadAuditada = $queryCalidad->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_auditada');

                $cantidadRechazada = $queryCalidad->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_rechazada');

                // Datos para AuditoriaAQL
                $cantidadAuditadaAQL = $queryAQL->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_auditada');

                $cantidadRechazadaAQL = $queryAQL->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_rechazada');

                // Cálculo de porcentajes
                $porcentajeProceso = ($cantidadAuditada > 0) ? round(($cantidadRechazada / $cantidadAuditada) * 100, 3) : 'N/A';
                $porcentajeAQL = ($cantidadAuditadaAQL > 0) ? round(($cantidadRechazadaAQL / $cantidadAuditadaAQL) * 100, 3) : 'N/A';

                $semanalPorcentajes[] = [
                    'proceso' => $porcentajeProceso,
                    'aql' => $porcentajeAQL,
                ];

                // Acumular totales para cada semana
                $totalesSemanas[$key]['auditadas_proceso'] += $cantidadAuditada;
                $totalesSemanas[$key]['rechazadas_proceso'] += $cantidadRechazada;
                $totalesSemanas[$key]['auditadas_aql'] += $cantidadAuditadaAQL;
                $totalesSemanas[$key]['rechazadas_aql'] += $cantidadRechazadaAQL;
            }

            // Solo añadir módulos si tienen al menos un dato válido
            if (array_filter($semanalPorcentajes, fn($data) => $data['proceso'] !== 'N/A' || $data['aql'] !== 'N/A')) {
                $modulos[] = [
                    'modulo' => $modulo,
                    'semanalPorcentajes' => $semanalPorcentajes,
                ];
            }
        }

        foreach ($totalesSemanas as $key => $totales) {
            $totalesSemanas[$key]['proceso'] = ($totales['auditadas_proceso'] > 0)
                ? round(($totales['rechazadas_proceso'] / $totales['auditadas_proceso']) * 100, 3)
                : 'N/A';

            $totalesSemanas[$key]['aql'] = ($totales['auditadas_aql'] > 0)
                ? round(($totales['rechazadas_aql'] / $totales['auditadas_aql']) * 100, 3)
                : 'N/A';
        }

        return [$modulos, $totalesSemanas];
    }


}
