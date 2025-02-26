<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\AseguramientoCalidad;
use App\Models\AuditoriaAQL;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
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
    /**
     * @method bool hasRole(string $role, string $planta = null)
     */
    public function index()
    {
        $title = "";
        $fechaActual = Carbon::now()->toDateString();
        //$fechaInicio = Carbon::now()->subMonth()->toDateString(); // Cambia el rango de fechas según necesites
        $fechaFin = Carbon::now()->toDateString();
        $fechaInicio = Carbon::parse($fechaFin)->startOfMonth()->toDateString();
      
        // Verifica si el usuario tiene los roles 'Administrador' o 'Gerente de Calidad'
        /**
         * @var User $user
        */
        $user = Auth::user();
        if ($user->hasRole('Administrador') || $user->hasRole('Gerente de Calidad') || $user->hasRole('Gerente')) {

            return view('dashboardv2', compact('title'));
        } else {
            // Si el usuario no tiene esos roles, redirige a listaFormularios
            return redirect()->route('viewlistaFormularios');
        }
    }

    public function porcentajesPorDiaV2()
    {
        $fechaActual = Carbon::now()->toDateString();

        $resultados = Cache::remember('resultados_consolidados_' . $fechaActual, 3600, function () use ($fechaActual) {
            $generalAseguramiento = DB::table('aseguramientos_calidad')
                ->selectRaw("
                    'Proceso' as tipo,
                    'General' as planta,
                    SUM(cantidad_auditada) as cantidad_auditada,
                    SUM(cantidad_rechazada) as cantidad_rechazada
                ")
                ->whereDate('created_at', $fechaActual);

            $generalAQL = DB::table('auditoria_aql')
                ->selectRaw("
                    'AQL' as tipo,
                    'General' as planta,
                    SUM(cantidad_auditada) as cantidad_auditada,
                    SUM(cantidad_rechazada) as cantidad_rechazada
                ")
                ->whereDate('created_at', $fechaActual);

            $aseguramientoPorPlanta = DB::table('aseguramientos_calidad')
                ->selectRaw("
                    'Proceso' as tipo,
                    COALESCE(planta, 'General') as planta,
                    SUM(cantidad_auditada) as cantidad_auditada,
                    SUM(cantidad_rechazada) as cantidad_rechazada
                ")
                ->whereDate('created_at', $fechaActual)
                ->groupBy('planta');

            $aqlPorPlanta = DB::table('auditoria_aql')
                ->selectRaw("
                    'AQL' as tipo,
                    COALESCE(planta, 'General') as planta,
                    SUM(cantidad_auditada) as cantidad_auditada,
                    SUM(cantidad_rechazada) as cantidad_rechazada
                ")
                ->whereDate('created_at', $fechaActual)
                ->groupBy('planta');

            return $generalAseguramiento
                ->unionAll($generalAQL)
                ->unionAll($aseguramientoPorPlanta)
                ->unionAll($aqlPorPlanta)
                ->get();
        });

        // Inicializamos variables
        $dashboardData = [
            'generalProceso' => "0.00",
            'generalAQL' => "0.00",
            'generalAQLPlanta1' => "0.00",
            'generalAQLPlanta2' => "0.00",
            'generalProcesoPlanta1' => "0.00",
            'generalProcesoPlanta2' => "0.00",
        ];

        // Recorrer resultados y asignar valores
        foreach ($resultados as $item) {
            if ($item->tipo == 'Proceso') { // AseguramientoCalidad
                if ($item->planta == 'General') {
                    $dashboardData['generalProceso'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                } elseif ($item->planta == 'Intimark1') {
                    $dashboardData['generalProcesoPlanta1'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                } elseif ($item->planta == 'Intimark2') {
                    $dashboardData['generalProcesoPlanta2'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                }
            } elseif ($item->tipo == 'AQL') { // AuditoriaAQL
                if ($item->planta == 'General') {
                    $dashboardData['generalAQL'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                } elseif ($item->planta == 'Intimark1') {
                    $dashboardData['generalAQLPlanta1'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                } elseif ($item->planta == 'Intimark2') {
                    $dashboardData['generalAQLPlanta2'] = number_format($item->cantidad_auditada != 0 
                        ? ($item->cantidad_rechazada / $item->cantidad_auditada) * 100 
                        : 0, 2);
                }
            }
        }

        return response()->json($dashboardData);
    }


    public function SegundasTerceras()
    {
        try {
            // Obtener Segundas y Terceras Generales
            $SegundasTerceras = obtenerSegundasTerceras();
        //Log::info('SegundasTerceras'. $SegundasTerceras);
            return response()->json([
                'data' => $SegundasTerceras,
                'status' => 'success'
            ], 200);

        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            //Log::error('Error al obtener SegundasTerceras: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }

    public function getDashboardDataSemanaV2()
    {
        $fechaActual = Carbon::now()->toDateString();

        // Cacheamos los datos semanales por 6 horas (21600 segundos)
        $datosSemana = Cache::remember('datosSemana_' . $fechaActual, 21600, function () {
            return $this->calcularPorcentajesSemanaActual();
        });

        return response()->json($datosSemana);
    }

    private function calcularPorcentajesSemanaActual()
    {
        // Obtener la fecha de inicio y fin de la semana actual
        $fechaInicioSemana = Carbon::now()->startOfWeek()->toDateString();
        $fechaFinSemana = Carbon::now()->endOfWeek()->toDateString();

        // Consultas para cada caso y modelo
        $clientesAQL = AuditoriaAQL::select('cliente',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('cliente')
            ->get();

        $clientesProceso = AseguramientoCalidad::select('cliente',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('cliente')
            ->get();

        $supervisoresAQL = AuditoriaAQL::select('team_leader',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('team_leader')
            ->get();

        $supervisoresProceso = AseguramientoCalidad::select('team_leader',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('team_leader')
            ->get();

        $modulosAQL = AuditoriaAQL::select('modulo',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('modulo')
            ->get();

        $modulosProceso = AseguramientoCalidad::select('modulo',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereBetween('created_at', [$fechaInicioSemana, $fechaFinSemana])
            ->groupBy('modulo')
            ->get();

        // Formatear los resultados
        $clientes = $this->formatearResultados($clientesAQL, $clientesProceso, 'cliente');
        $supervisores = $this->formatearResultados($supervisoresAQL, $supervisoresProceso, 'team_leader');
        $modulos = $this->formatearResultados($modulosAQL, $modulosProceso, 'modulo');

        // Retornar los resultados
        return [
            'clientes' => $clientes,
            'supervisores' => $supervisores,
            'modulos' => $modulos,
        ];
    }

    private function formatearResultados($aqlData, $procesoData, $columna)
    {
        $resultados = [];

        // Unificar las claves de ambos datasets
        $claves = collect($aqlData->pluck($columna))->merge($procesoData->pluck($columna))->unique();

        foreach ($claves as $clave) {
            $aql = $aqlData->firstWhere($columna, $clave);
            $proceso = $procesoData->firstWhere($columna, $clave);

            $porcentajeAQL = $aql && $aql->total_auditada > 0
                ? ($aql->total_rechazada / $aql->total_auditada) * 100
                : 0;

            $porcentajeProceso = $proceso && $proceso->total_auditada > 0
                ? ($proceso->total_rechazada / $proceso->total_auditada) * 100
                : 0;

            $resultados[] = [
                $columna => $clave,
                '% AQL' => round($porcentajeAQL, 2),
                '% PROCESO' => round($porcentajeProceso, 2),
            ];
        }

        return $resultados;
    }

    public function getDashboardDataDiaV2()
    {
        $fechaActual = Carbon::now()->toDateString();

        // Cacheamos los datos del día actual por 5 minutos
        $datosDia = Cache::remember('datosDia_' . $fechaActual, 3600, function () use ($fechaActual) {
            return $this->calcularPorcentajesDia($fechaActual);
        });

        return response()->json($datosDia);
    }

    private function calcularPorcentajesDia($fechaActual)
    {
        // Consultas para Clientes, Supervisores y Módulos
        $clientesAQL = AuditoriaAQL::select('cliente',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('cliente')
            ->get();

        $clientesProceso = AseguramientoCalidad::select('cliente',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('cliente')
            ->get();

        $supervisoresAQL = AuditoriaAQL::select('team_leader',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('team_leader')
            ->get();

        $supervisoresProceso = AseguramientoCalidad::select('team_leader',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('team_leader')
            ->get();

        $modulosAQL = AuditoriaAQL::select('modulo',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('modulo')
            ->get();

        $modulosProceso = AseguramientoCalidad::select('modulo',
                DB::raw('SUM(cantidad_rechazada) as total_rechazada'),
                DB::raw('SUM(cantidad_auditada) as total_auditada'))
            ->whereDate('created_at', $fechaActual)
            ->groupBy('modulo')
            ->get();

        // Formatear resultados
        $clientes = $this->formatearResultadosDia($clientesAQL, $clientesProceso, 'cliente');
        $supervisores = $this->formatearResultadosDia($supervisoresAQL, $supervisoresProceso, 'team_leader');
        $modulos = $this->formatearResultadosDia($modulosAQL, $modulosProceso, 'modulo');

        return [
            'clientes' => $clientes,
            'supervisores' => $supervisores,
            'modulos' => $modulos,
        ];
    }

    private function formatearResultadosDia($datosAQL, $datosProceso, $campo)
    {
        $resultados = [];

        foreach ($datosAQL as $itemAQL) {
            $resultados[$itemAQL->$campo]['% AQL'] = $itemAQL->total_auditada != 0
                ? ($itemAQL->total_rechazada / $itemAQL->total_auditada) * 100
                : 0;
        }

        foreach ($datosProceso as $itemProceso) {
            $resultados[$itemProceso->$campo]['% PROCESO'] = $itemProceso->total_auditada != 0
                ? ($itemProceso->total_rechazada / $itemProceso->total_auditada) * 100
                : 0;
        }

        return $resultados;
    }

    public function getMensualGeneralV2()
    {
        $fechaFin = Carbon::now()->toDateString();
        $fechaInicio = Carbon::parse($fechaFin)->startOfMonth()->toDateString();

        // Almacenar en caché por 12 hora (43,200 segundos)
        $cacheKey = "mensual_general_{$fechaInicio}_{$fechaFin}";

        $datos = Cache::remember($cacheKey, 43200, function () use ($fechaInicio, $fechaFin) {
            $fechas = CarbonPeriod::create($fechaInicio, $fechaFin); // Rango de fechas
            $datos = [];

            foreach ($fechas as $fecha) {
                $fechaLog = $fecha->toDateString();

                // Consulta para AQL
                $aql = DB::table('auditoria_aql')
                    ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                    ->whereDate('created_at', $fechaLog)
                    ->first();

                // Consulta para Proceso
                $proceso = DB::table('aseguramientos_calidad')
                    ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                    ->whereDate('created_at', $fechaLog)
                    ->first();

                // Calcular porcentajes
                $porcentajeAQL = $aql->cantidad_auditada > 0
                    ? round(($aql->cantidad_rechazada / $aql->cantidad_auditada) * 100, 2)
                    : 0;

                $porcentajeProceso = $proceso->cantidad_auditada > 0
                    ? round(($proceso->cantidad_rechazada / $proceso->cantidad_auditada) * 100, 2)
                    : 0;

                $datos[] = [
                    'dia' => $fecha->format('j'),
                    'AQL' => $porcentajeAQL,
                    'PROCESO' => $porcentajeProceso
                ];
            }

            return $datos;
        });

        return response()->json($datos);
    }



    public function getMensualPorClienteV2()
    {
        $fechaFin = Carbon::now()->endOfDay()->toDateTimeString();
        $fechaInicio = Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString();

        // Almacenar en caché por 1 hora (3600 segundos)
        $cacheKey = "mensual_por_cliente_{$fechaInicio}_{$fechaFin}";

        $datos = Cache::remember($cacheKey, 3600, function () use ($fechaInicio, $fechaFin) {
            $fechas = CarbonPeriod::create($fechaInicio, $fechaFin);
            $clientes = DB::table('aseguramientos_calidad')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct()
                ->pluck('cliente');

            $datos = [];

            foreach ($clientes as $cliente) {
                $dataPorCliente = [];

                foreach ($fechas as $fecha) {
                    $fechaLog = $fecha->toDateString();

                    // Consulta para AQL
                    $aql = DB::table('auditoria_aql')
                        ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                        ->where('cliente', $cliente)
                        ->whereDate('created_at', $fechaLog)
                        ->first();

                    // Consulta para PROCESO
                    $proceso = DB::table('aseguramientos_calidad')
                        ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                        ->where('cliente', $cliente)
                        ->whereDate('created_at', $fechaLog)
                        ->first();

                    $porcentajeAQL = $aql->cantidad_auditada > 0
                        ? round(($aql->cantidad_rechazada / $aql->cantidad_auditada) * 100, 2)
                        : 0;

                    $porcentajeProceso = $proceso->cantidad_auditada > 0
                        ? round(($proceso->cantidad_rechazada / $proceso->cantidad_auditada) * 100, 2)
                        : 0;

                    $dataPorCliente[] = [
                        'dia' => $fecha->format('j'),
                        'AQL' => $porcentajeAQL,
                        'PROCESO' => $porcentajeProceso
                    ];
                }

                $datos[$cliente] = $dataPorCliente;
            }

            return $datos;
        });

        return response()->json($datos);
    }

    public function getMensualPorModuloV2()
    {
        $fechaFin = Carbon::now()->endOfDay()->toDateTimeString();
        $fechaInicio = Carbon::now()->startOfMonth()->startOfDay()->toDateTimeString();

        // Almacenar en caché por 1 hora (3600 segundos)
        $cacheKey = "mensual_por_modulo_{$fechaInicio}_{$fechaFin}";

        $datos = Cache::remember($cacheKey, 3600, function () use ($fechaInicio, $fechaFin) {
            $fechas = CarbonPeriod::create($fechaInicio, $fechaFin);
            $modulos = DB::table('aseguramientos_calidad')
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->distinct()
                ->pluck('modulo');

            $datos = [];

            foreach ($modulos as $modulo) {
                $dataPorModulo = [];

                foreach ($fechas as $fecha) {
                    $fechaLog = $fecha->toDateString();

                    // Consulta para AQL
                    $aql = DB::table('auditoria_aql')
                        ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                        ->where('modulo', $modulo)
                        ->whereDate('created_at', $fechaLog)
                        ->first();

                    // Consulta para PROCESO
                    $proceso = DB::table('aseguramientos_calidad')
                        ->selectRaw("SUM(cantidad_auditada) as cantidad_auditada, SUM(cantidad_rechazada) as cantidad_rechazada")
                        ->where('modulo', $modulo)
                        ->whereDate('created_at', $fechaLog)
                        ->first();

                    $porcentajeAQL = $aql->cantidad_auditada > 0
                        ? round(($aql->cantidad_rechazada / $aql->cantidad_auditada) * 100, 2)
                        : 0;

                    $porcentajeProceso = $proceso->cantidad_auditada > 0
                        ? round(($proceso->cantidad_rechazada / $proceso->cantidad_auditada) * 100, 2)
                        : 0;

                    $dataPorModulo[] = [
                        'dia' => $fecha->format('j'),
                        'AQL' => $porcentajeAQL,
                        'PROCESO' => $porcentajeProceso
                    ];
                }

                $datos[$modulo] = $dataPorModulo;
            }

            return $datos;
        });

        return response()->json($datos);
    }


    public function getDefectoMensualV2()
    {
        $fechaFin = Carbon::now()->toDateString();
        $fechaInicio = Carbon::parse($fechaFin)->startOfMonth()->toDateString();

        // Consulta para obtener los 3 valores más repetidos de 'tp' excluyendo 'NINGUNO'
        $topDefectosAQL = Cache::remember("topDefectosAQL_{$fechaInicio}_{$fechaFin}", 300, function () use ($fechaInicio, $fechaFin) {
            return TpAuditoriaAQL::select('tp', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
        });

        $topDefectosProceso = Cache::remember("topDefectosProceso_{$fechaInicio}_{$fechaFin}", 300, function () use ($fechaInicio, $fechaFin) {
            return TpAseguramientoCalidad::select('tp', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
        });

        // Registrar en el log de Laravel los datos obtenidos
        Log::info("Consulta de Defectos Mensuales", [
            'Fecha Inicio' => $fechaInicio,
            'Fecha Fin' => $fechaFin,
            'Top Defectos AQL' => $topDefectosAQL,
            'Top Defectos Proceso' => $topDefectosProceso
        ]);

        return response()->json([
            'topDefectosAQL' => $topDefectosAQL,
            'topDefectosProceso' => $topDefectosProceso
        ]);
    }




}
