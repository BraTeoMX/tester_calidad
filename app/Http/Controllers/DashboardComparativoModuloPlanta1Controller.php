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
use App\Models\ClienteProcentaje;
use Illuminate\Support\Facades\Log;


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
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() : Carbon::now()->subWeeks(3)->startOfWeek();

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
        //
        // Traer datos de ClienteProcentaje solo para los clientes encontrados
        $clientesProcentaje = ClienteProcentaje::whereIn('nombre', $clientesUnicos)->get()->keyBy('nombre');

        // Formatear clientes para log
        //$clientesLog = $clientesProcentaje->map(function ($cliente) {
        //    return "Nombre: {$cliente->nombre}, Proceso: {$cliente->proceso}, AQL: {$cliente->aql}";
        //})->join("\n");

        // Registrar en el log
        //Log::info("Lista de clientes obtenidos de ClienteProcentaje:\n" . $clientesLog);

        $modulosPorCliente = [];
        $totalesPorCliente = [];
        $modulosPorClienteYEstilo = [];
        $totalesPorClienteYEstilo = [];
        $modulosPorClientePlanta1 = [];
        $totalesPorClientePlanta1 = [];
        $modulosPorClienteYEstiloPlanta1 = [];
        $totalesPorClienteYEstiloPlanta1 = [];
        $modulosPorClientePlanta2 = [];
        $totalesPorClientePlanta2 = [];
        $modulosPorClienteYEstiloPlanta2 = [];
        $totalesPorClienteYEstiloPlanta2 = [];

        // Obtener datos para cada cliente
        foreach ($clientesUnicos as $cliente) {
            // Obtener los datos específicos del cliente desde ClienteProcentaje
            $datosClienteProcentaje = $clientesProcentaje->get($cliente);
            // Registrar el cliente y sus datos en el log
            //Log::info("Cliente: $cliente, Datos ClienteProcentaje: " . json_encode($datosClienteProcentaje));

            // Datos generales (sin estilo)
            [$modulosPorCliente[$cliente], $totalesPorCliente[$cliente]] = $this->getDatosPorCliente(
                $cliente,
                $fechaInicio,
                $fechaFin,
                $semanas,
                null,
                $datosClienteProcentaje // Pasar datos del cliente
            );

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
                    $this->getDatosPorClienteYEstilo(
                        $cliente,
                        $estilo,
                        $fechaInicio,
                        $fechaFin,
                        $semanas,
                        null,
                        $datosClienteProcentaje // Pasar datos del cliente
                    );

                // Planta 1 - Ixtlahuaca
                [$modulosPorClienteYEstiloPlanta1[$cliente][$estilo], $totalesPorClienteYEstiloPlanta1[$cliente][$estilo]] =
                    $this->getDatosPorClienteYEstilo(
                        $cliente,
                        $estilo,
                        $fechaInicio,
                        $fechaFin,
                        $semanas,
                        'Intimark1',
                        $datosClienteProcentaje // Pasar datos del cliente
                    );

                // Planta 2 - San Bartolo
                [$modulosPorClienteYEstiloPlanta2[$cliente][$estilo], $totalesPorClienteYEstiloPlanta2[$cliente][$estilo]] =
                    $this->getDatosPorClienteYEstilo(
                        $cliente,
                        $estilo,
                        $fechaInicio,
                        $fechaFin,
                        $semanas,
                        'Intimark2',
                        $datosClienteProcentaje // Pasar datos del cliente
                    );
            }

            // Datos Planta 1 - Ixtlahuaca
            [$modulosPorClientePlanta1[$cliente], $totalesPorClientePlanta1[$cliente]] = $this->getDatosPorCliente(
                $cliente,
                $fechaInicio,
                $fechaFin,
                $semanas,
                'Intimark1',
                $datosClienteProcentaje
            );

            // Datos Planta 2 - San Bartolo
            [$modulosPorClientePlanta2[$cliente], $totalesPorClientePlanta2[$cliente]] = $this->getDatosPorCliente(
                $cliente,
                $fechaInicio,
                $fechaFin,
                $semanas,
                'Intimark2',
                $datosClienteProcentaje
            );
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
            'modulosPorClienteYEstiloPlanta1',
            'totalesPorClienteYEstiloPlanta1',
            'modulosPorClientePlanta2',
            'totalesPorClientePlanta2',
            'modulosPorClienteYEstiloPlanta2',
            'totalesPorClienteYEstiloPlanta2',
            'semanas'
        ));
    }

    /**
     * Función privada para obtener datos por cliente y planta (si aplica).
     */
    private function getDatosPorCliente($cliente, $fechaInicio, $fechaFin, $semanas, $planta = null, $datosClienteProcentaje = null)
    {

        //Log::info("Funcion Privada Cliente: $cliente, Datos ClienteProcentaje: " . json_encode($datosClienteProcentaje));
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
        //Log::info("Cliente: $cliente, Módulos Cliente: " . json_encode($modulosCliente));

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

                // Comparar con ClienteProcentaje
                $procesoColor = $datosClienteProcentaje && $porcentajeProceso !== 'N/A' && $porcentajeProceso >= $datosClienteProcentaje->proceso;
                $aqlColor = $datosClienteProcentaje && $porcentajeAQL !== 'N/A' && $porcentajeAQL >= $datosClienteProcentaje->aql;

                // Registrar los valores en el log
                //Log::info("Cliente: $cliente, Módulo: $modulo, Semana: {$semana['inicio']->format('W')}");
                //Log::info("Porcentaje Proceso: $porcentajeProceso, Cliente Proceso: " . ($datosClienteProcentaje->proceso ?? 'N/A') . ", Indicador Proceso Color: " . ($procesoColor ? 'true' : 'false'));
                //Log::info("Porcentaje AQL: $porcentajeAQL, Cliente AQL: " . ($datosClienteProcentaje->aql ?? 'N/A') . ", Indicador AQL Color: " . ($aqlColor ? 'true' : 'false'));
                
                // Agregar porcentajes e indicadores al arreglo
                $semanalPorcentajes[] = [
                    'proceso' => $porcentajeProceso,
                    'proceso_color' => $procesoColor, // Indicador para colorear
                    'aql' => $porcentajeAQL,
                    'aql_color' => $aqlColor, // Indicador para colorear
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

        // Totales comparativos
        foreach ($totalesSemanas as $key => $totales) {
            $totalesSemanas[$key]['proceso'] = ($totales['auditadas_proceso'] > 0)
                ? round(($totales['rechazadas_proceso'] / $totales['auditadas_proceso']) * 100, 3)
                : 'N/A';

            $totalesSemanas[$key]['aql'] = ($totales['auditadas_aql'] > 0)
                ? round(($totales['rechazadas_aql'] / $totales['auditadas_aql']) * 100, 3)
                : 'N/A';

            $totalesSemanas[$key]['proceso_color'] = $datosClienteProcentaje
                && $totalesSemanas[$key]['proceso'] !== 'N/A'
                && $totalesSemanas[$key]['proceso'] >= $datosClienteProcentaje->proceso;

            $totalesSemanas[$key]['aql_color'] = $datosClienteProcentaje
                && $totalesSemanas[$key]['aql'] !== 'N/A'
                && $totalesSemanas[$key]['aql'] >= $datosClienteProcentaje->aql;

            //
            // Registrar los valores en el log
           // Log::info("Totales Semana: $key");
           // Log::info("Total Proceso: " . $totalesSemanas[$key]['proceso'] . ", Indicador Proceso Color: " . ($totalesSemanas[$key]['proceso_color'] ? 'true' : 'false'));
            //Log::info("Total AQL: " . $totalesSemanas[$key]['aql'] . ", Indicador AQL Color: " . ($totalesSemanas[$key]['aql_color'] ? 'true' : 'false'));
        }

        return [$modulos, $totalesSemanas];
    }

    /**
     * Función privada para obtener datos por cliente y estilo.
    */
    private function getDatosPorClienteYEstilo($cliente, $estilo, $fechaInicio, $fechaFin, $semanas, $planta = null, $datosClienteProcentaje = null)
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
                // Crear consultas independientes para evitar problemas de acumulación
                $cantidadAuditada = AseguramientoCalidad::where('cliente', $cliente)
                    ->where('estilo', $estilo)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_auditada');

                $cantidadRechazada = AseguramientoCalidad::where('cliente', $cliente)
                    ->where('estilo', $estilo)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_rechazada');

                // Auditoría AQL
                $cantidadAuditadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->where('estilo', $estilo)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_auditada');

                $cantidadRechazadaAQL = AuditoriaAQL::where('cliente', $cliente)
                    ->where('estilo', $estilo)
                    ->where('modulo', $modulo)
                    ->whereBetween('created_at', [$semana['inicio'], $semana['fin']])
                    ->sum('cantidad_rechazada');

                // Cálculo de porcentajes
                $porcentajeProceso = ($cantidadAuditada > 0) ? round(($cantidadRechazada / $cantidadAuditada) * 100, 3) : 'N/A';
                $porcentajeAQL = ($cantidadAuditadaAQL > 0) ? round(($cantidadRechazadaAQL / $cantidadAuditadaAQL) * 100, 3) : 'N/A';

                // Comparar con ClienteProcentaje
                $procesoColor = $datosClienteProcentaje && $porcentajeProceso !== 'N/A' && $porcentajeProceso >= $datosClienteProcentaje->proceso;
                $aqlColor = $datosClienteProcentaje && $porcentajeAQL !== 'N/A' && $porcentajeAQL >= $datosClienteProcentaje->aql;

                // Agregar porcentajes e indicadores al arreglo
                $semanalPorcentajes[] = [
                    'proceso' => $porcentajeProceso,
                    'proceso_color' => $procesoColor, // Indicador para colorear
                    'aql' => $porcentajeAQL,
                    'aql_color' => $aqlColor, // Indicador para colorear
                ];

                // Acumular totales por semana
                $totalesSemanas[$key]['auditadas_proceso'] += $cantidadAuditada;
                $totalesSemanas[$key]['rechazadas_proceso'] += $cantidadRechazada;
                $totalesSemanas[$key]['auditadas_aql'] += $cantidadAuditadaAQL;
                $totalesSemanas[$key]['rechazadas_aql'] += $cantidadRechazadaAQL;
            }

            // Añadir el módulo con sus porcentajes semanales
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

            $totalesSemanas[$key]['proceso_color'] = $datosClienteProcentaje
                && $totalesSemanas[$key]['proceso'] !== 'N/A'
                && $totalesSemanas[$key]['proceso'] >= $datosClienteProcentaje->proceso;

            $totalesSemanas[$key]['aql_color'] = $datosClienteProcentaje
                && $totalesSemanas[$key]['aql'] !== 'N/A'
                && $totalesSemanas[$key]['aql'] >= $datosClienteProcentaje->aql;
        }

        return [$modulos, $totalesSemanas];
    }




}
