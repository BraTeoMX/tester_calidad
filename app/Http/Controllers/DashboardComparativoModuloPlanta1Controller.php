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
use App\Models\ComparativoSemanalCliente;


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
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() : Carbon::now()->subWeeks(1)->startOfWeek();

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
                // Insertar o actualizar en la tabla solo si al menos uno de los porcentajes es válido
                if ($porcentajeProceso !== 'N/A' || $porcentajeAQL !== 'N/A') {
                    ComparativoSemanalCliente::updateOrCreate(
                        [
                            'semana' => $semana['inicio']->week,
                            'anio' => $semana['inicio']->year,
                            'cliente' => $cliente,
                            'estilo' => null,
                            'modulo' => $modulo,
                            'planta' => str_starts_with($modulo, '1') ? 1 : (str_starts_with($modulo, '2') ? 2 : 0),
                        ],
                        [
                            'cantidad_auditada_proceso' => $cantidadAuditada ?: null,
                            'cantidad_rechazada_proceso' => $cantidadRechazada ?: null,
                            'porcentaje_proceso' => ($porcentajeProceso === 'N/A') ? null : $porcentajeProceso,
                            'cantidad_auditada_aql' => $cantidadAuditadaAQL ?: null,
                            'cantidad_rechazada_aql' => $cantidadRechazadaAQL ?: null,
                            'porcentaje_aql' => ($porcentajeAQL === 'N/A') ? null : $porcentajeAQL,
                        ]
                    );
                }
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

                // Insertar o actualizar en la tabla solo si al menos uno de los porcentajes es válido
                if ($porcentajeProceso !== 'N/A' || $porcentajeAQL !== 'N/A') {
                    ComparativoSemanalCliente::updateOrCreate(
                        [
                            'semana' => $semana['inicio']->week,
                            'anio' => $semana['inicio']->year,
                            'cliente' => $cliente,
                            'estilo' => $estilo,
                            'modulo' => $modulo,
                            'planta' => str_starts_with($modulo, '1') ? 1 : (str_starts_with($modulo, '2') ? 2 : 0),
                        ],
                        [
                            'cantidad_auditada_proceso' => $cantidadAuditada ?: null,
                            'cantidad_rechazada_proceso' => $cantidadRechazada ?: null,
                            'porcentaje_proceso' => ($porcentajeProceso === 'N/A') ? null : $porcentajeProceso,
                            'cantidad_auditada_aql' => $cantidadAuditadaAQL ?: null,
                            'cantidad_rechazada_aql' => $cantidadRechazadaAQL ?: null,
                            'porcentaje_aql' => ($porcentajeAQL === 'N/A') ? null : $porcentajeAQL,
                        ]
                    );
                }
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



    public function semanaComparativaGeneral(Request $request)
    {
        // Solo devolver la vista básica con el formulario y las tabs vacías (o estructura base)
        return view('dashboarComparativaModulo.semanaComparativaGeneral');
    }

    private function procesarRegistros($registros, $semanas, $clientesPorcentajes)
    {
        return $registros
            ->groupBy('cliente')
            ->map(function ($itemsPorCliente, $cliente) use ($semanas, $clientesPorcentajes) {
                $datosClienteProcentaje = $clientesPorcentajes->get($cliente);

                return $itemsPorCliente
                    ->groupBy(function($item) {
                        return $item->estilo ?? 'General';
                    })
                    ->map(function($modulosEstilo) use ($semanas, $datosClienteProcentaje) {
                        $consolidado = [];

                        // Arreglos para acumular totales AQL y PROCESO por semana
                        $rechazadaAqlSemana = array_fill(0, count($semanas), 0);
                        $auditadaAqlSemana = array_fill(0, count($semanas), 0);

                        // Arreglos para acumular totales PROCESO por semana
                        $rechazadaProcesoSemana = array_fill(0, count($semanas), 0);
                        $auditadaProcesoSemana = array_fill(0, count($semanas), 0);

                        foreach ($modulosEstilo as $modulo) {
                            if (!isset($consolidado[$modulo->modulo])) {
                                $consolidado[$modulo->modulo] = [
                                    'modulo' => $modulo->modulo,
                                    'semanalPorcentajes' => array_fill(0, count($semanas), [
                                        'aql' => "N/A",
                                        'proceso' => "N/A",
                                        'aql_color' => false,
                                        'proceso_color' => false
                                    ])
                                ];
                            }

                            foreach ($semanas as $index => $semana) {
                                if ($modulo->semana == $semana['semana'] && $modulo->anio == $semana['anio']) {
                                    // Comparación de porcentajes con ClienteProcentaje
                                    $aqlColor = $datosClienteProcentaje && $modulo->porcentaje_aql !== null && $modulo->porcentaje_aql >= $datosClienteProcentaje->aql;
                                    $procesoColor = $datosClienteProcentaje && $modulo->porcentaje_proceso !== null && $modulo->porcentaje_proceso >= $datosClienteProcentaje->proceso;

                                    // Asignar valores de porcentajes y colores
                                    $consolidado[$modulo->modulo]['semanalPorcentajes'][$index] = [
                                        'aql' => $modulo->porcentaje_aql ?? "N/A",
                                        'proceso' => $modulo->porcentaje_proceso ?? "N/A",
                                        'aql_color' => $aqlColor,
                                        'proceso_color' => $procesoColor
                                    ];

                                    // Acumular datos para el total AQL y PROCESO
                                    $rechazadaAqlSemana[$index] += $modulo->cantidad_rechazada_aql ?? 0;
                                    $auditadaAqlSemana[$index] += $modulo->cantidad_auditada_aql ?? 0;

                                    // Acumular datos para el total PROCESO
                                    $rechazadaProcesoSemana[$index] += $modulo->cantidad_rechazada_proceso ?? 0;
                                    $auditadaProcesoSemana[$index] += $modulo->cantidad_auditada_proceso ?? 0;
                                }
                            }
                        }

                        // Calcular totales AQL por semana
                        $totalesAql = [];
                        $totalesAqlColores = [];
                        foreach ($semanas as $i => $semana) {
                            if ($auditadaAqlSemana[$i] > 0) {
                                $total = round(($rechazadaAqlSemana[$i] / $auditadaAqlSemana[$i]) * 100, 3);
                                $totalesAql[] = $total;

                                // Comparar con ClienteProcentaje para determinar el color
                                $totalesAqlColores[] = $datosClienteProcentaje && $total >= $datosClienteProcentaje->aql;
                            } else {
                                $totalesAql[] = "N/A";
                                $totalesAqlColores[] = false; // No aplica color para N/A
                            }
                        }

                        // Calcular totales PROCESO por semana
                        $totalesProceso = [];
                        $totalesProcesoColores = [];
                        foreach ($semanas as $i => $semana) {
                            if ($auditadaProcesoSemana[$i] > 0) {
                                $total = round(($rechazadaProcesoSemana[$i] / $auditadaProcesoSemana[$i]) * 100, 3);
                                $totalesProceso[] = $total;

                                // Comparar con ClienteProcentaje para determinar el color
                                $totalesProcesoColores[] = $datosClienteProcentaje && $total >= $datosClienteProcentaje->proceso;
                            } else {
                                $totalesProceso[] = "N/A";
                                $totalesProcesoColores[] = false; // No aplica color para N/A
                            }
                        }

                        // Al final, retornas el mismo array que ya devuelves actualmente:
                        return [
                            'modulos' => array_values($consolidado),
                            'totales_aql' => $totalesAql,
                            'totales_proceso' => $totalesProceso,
                            'totales_aql_colores' => $totalesAqlColores,
                            'totales_proceso_colores' => $totalesProcesoColores
                        ];
                    });
            });
    }

    public function getSemanaComparativaGeneralData(Request $request)
    {
        \Carbon\Carbon::setLocale('es');

        // Obtenemos rangos de fecha
        $fecha_inicio_str = $request->input('fecha_inicio');
        $fecha_fin_str = $request->input('fecha_fin');

        if ($fecha_inicio_str) {
            // $fecha_inicio_str = "YYYY-Wxx"
            list($anio_inicio, $semana_inicio) = explode('-W', $fecha_inicio_str);
            $anio_inicio = (int)$anio_inicio;
            $semana_inicio = (int)$semana_inicio;
            // Establecer la fecha a la primera semana ISO del año y sumarle las semanas necesarias
            $fechaInicio = Carbon::now()->setISODate($anio_inicio, $semana_inicio)->startOfWeek();
        } else {
            $fechaInicio = Carbon::now()->subWeeks(1)->startOfWeek();
        }

        if ($fecha_fin_str) {
            list($anio_fin, $semana_fin) = explode('-W', $fecha_fin_str);
            $anio_fin = (int)$anio_fin;
            $semana_fin = (int)$semana_fin;
            $fechaFin = Carbon::now()->setISODate($anio_fin, $semana_fin)->endOfWeek();
        } else {
            $fechaFin = Carbon::now()->endOfWeek();
        }

        // Obtener semanas y años
        $semanas = [];
        $currentDate = $fechaInicio->copy();
        while ($currentDate <= $fechaFin) {
            $semanas[] = [
                'semana' => $currentDate->weekOfYear,
                'anio' => $currentDate->year
            ];
            $currentDate->addWeek();
        }

        $listaSemanasAnios = array_map(function($rango) {
            return $rango['anio'] . '-' . $rango['semana'];
        }, $semanas);

        $registros = ComparativoSemanalCliente::whereIn(
            DB::raw("CONCAT(anio, '-', semana)"), $listaSemanasAnios
        )->get();

        $clientesPorcentajes = ClienteProcentaje::all()->keyBy('nombre');

        $registrosGeneral = $registros;
        $registrosPlanta1 = $registros->where('planta', 1);
        $registrosPlanta2 = $registros->where('planta', 2);

        $modulosPorClienteYEstiloGeneral = $this->procesarRegistros($registrosGeneral, $semanas, $clientesPorcentajes);
        $modulosPorClienteYEstiloPlanta1 = $this->procesarRegistros($registrosPlanta1, $semanas, $clientesPorcentajes);
        $modulosPorClienteYEstiloPlanta2 = $this->procesarRegistros($registrosPlanta2, $semanas, $clientesPorcentajes);

        // Estructura JSON con toda la información necesaria
        return response()->json([
            'fechaInicio' => $fechaInicio->format('Y-m-d'),
            'fechaFin' => $fechaFin->format('Y-m-d'),
            'semanas' => $semanas,
            'modulosPorClienteYEstilo' => $modulosPorClienteYEstiloGeneral,
            'modulosPorClienteYEstiloPlanta1' => $modulosPorClienteYEstiloPlanta1,
            'modulosPorClienteYEstiloPlanta2' => $modulosPorClienteYEstiloPlanta2
        ]);
    }


}
