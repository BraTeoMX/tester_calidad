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
        // --- 1. Definir Rango de Fechas ---
        // El original usaba toDateString(), pero para whereBetween es más seguro usar objetos Carbon o DateTimeString
        $fechaFinObj = Carbon::now()->endOfDay(); // Fin del día actual
        $fechaInicioObj = Carbon::now()->startOfMonth()->startOfDay(); // Inicio del mes actual

        // --- 2. Configuración de Caché ---
        // Clave única para el caché basada en el rango de fechas
        // Usamos toDateString aquí para la clave, como en el original
        $cacheKey = "mensual_general_{$fechaInicioObj->toDateString()}_{$fechaFinObj->toDateString()}";
        // Duración del caché: 12 horas (43200 segundos), como en el original.
        $cacheTiempo = 43200;

        // --- 3. Obtener Datos (Desde Caché o Calculando) ---
        // Usamos $datos directamente como solicitaste
        $datos = Cache::remember($cacheKey, $cacheTiempo, function () use ($fechaInicioObj, $fechaFinObj) {

            // --- 3a. Obtener TODOS los datos AQL del mes en UNA consulta, agrupados por fecha ---
            $aqlData = DB::table('auditoria_aql')
                ->selectRaw("
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                // Usamos los objetos Carbon completos para whereBetween
                ->whereBetween('created_at', [$fechaInicioObj->toDateTimeString(), $fechaFinObj->toDateTimeString()])
                // Agrupamos solo por fecha
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get()
                // Convertimos a formato de búsqueda rápida: [fecha] => datos
                ->keyBy('fecha'); // La clave es directamente la fecha (YYYY-MM-DD)

            // --- 3b. Obtener TODOS los datos de PROCESO del mes en UNA consulta, agrupados por fecha ---
            $procesoData = DB::table('aseguramientos_calidad')
                ->selectRaw("
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                // Usamos los objetos Carbon completos para whereBetween
                ->whereBetween('created_at', [$fechaInicioObj->toDateTimeString(), $fechaFinObj->toDateTimeString()])
                 // Agrupamos solo por fecha
                ->groupBy('fecha')
                ->orderBy('fecha')
                ->get()
                // Convertimos a formato de búsqueda rápida: [fecha] => datos
                ->keyBy('fecha'); // La clave es directamente la fecha

            // --- 3c. Generar el Rango Completo de Fechas del Mes (hasta hoy) ---
            // Usamos los objetos Carbon para crear el período
            $periodoFechas = CarbonPeriod::create($fechaInicioObj, $fechaFinObj);

            // --- 3d. Procesar y Combinar los Datos en PHP ---
            $resultadoFinal = []; // Usamos una variable interna para construir el resultado

            // Iteramos por cada día del mes generado por CarbonPeriod
            foreach ($periodoFechas as $fecha) {
                $fechaActualStr = $fecha->toDateString(); // Formato YYYY-MM-DD

                // Buscamos los datos AQL para este día
                $aqlDia = $aqlData->get($fechaActualStr);
                $auditadaAQL = $aqlDia->total_auditada ?? 0;
                $rechazadaAQL = $aqlDia->total_rechazada ?? 0;

                // Buscamos los datos PROCESO para este día
                $procesoDia = $procesoData->get($fechaActualStr);
                $auditadaProceso = $procesoDia->total_auditada ?? 0;
                $rechazadaProceso = $procesoDia->total_rechazada ?? 0;

                // Calculamos los porcentajes (evitando división por cero)
                $porcentajeAQL = $auditadaAQL > 0
                    ? round(($rechazadaAQL / $auditadaAQL) * 100, 2)
                    : 0;

                $porcentajeProceso = $auditadaProceso > 0
                    ? round(($rechazadaProceso / $auditadaProceso) * 100, 2)
                    : 0;

                // Agregamos la entrada para este día al resultado final
                // La estructura es un array simple de objetos/arrays
                $resultadoFinal[] = [
                    'dia' => $fecha->format('j'), // Día del mes (1, 2, ..., 31)
                    'AQL' => $porcentajeAQL,
                    'PROCESO' => $porcentajeProceso
                ];
            }

            // Devolvemos el array procesado para que se guarde en caché
            return $resultadoFinal;

        }); // Fin de Cache::remember

        // --- 4. Devolver Respuesta JSON ---
        // Devolvemos la variable $datos que contiene el resultado (cacheado o calculado)
        return response()->json($datos);
    }



    public function getMensualPorClienteV2()
    {
        // --- 1. Definir Rango de Fechas ---
        $fechaInicio = Carbon::now()->startOfMonth()->startOfDay(); // Inicio del mes (YYYY-MM-01 00:00:00)
        $fechaFin = Carbon::now()->endOfMonth()->endOfDay();       // Fin del mes   (YYYY-MM-DD 23:59:59)

        // --- 2. Configuración de Caché ---
        // Clave única para el caché basada en el rango de fechas y el tipo de agrupación
        $cacheKey = "mensual_por_cliente_{$fechaInicio->toDateString()}_{$fechaFin->toDateString()}";
        // Duración del caché: 15 horas (en segundos).
        $cacheTiempo = 15 * 60 * 60; // 15 horas

        // --- 3. Obtener Datos (Desde Caché o Calculando) ---
        // Usamos $datos directamente como solicitaste
        $datos = Cache::remember($cacheKey, $cacheTiempo, function () use ($fechaInicio, $fechaFin) {

            // --- 3a. Obtener TODOS los datos AQL del mes en UNA consulta, agrupados por CLIENTE y fecha ---
            $aqlData = DB::table('auditoria_aql')
                ->selectRaw("
                    cliente,
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
                // Agrupamos por cliente Y por fecha
                ->groupBy('cliente', 'fecha')
                ->orderBy('fecha')
                ->get()
                // Convertimos a formato de búsqueda rápida: [cliente][fecha] => datos
                ->keyBy(function ($item) {
                    // Clave combinada para fácil acceso
                    return $item->cliente . '|' . $item->fecha;
                });

            // --- 3b. Obtener TODOS los datos de PROCESO del mes en UNA consulta, agrupados por CLIENTE y fecha ---
            $procesoData = DB::table('aseguramientos_calidad')
                ->selectRaw("
                    cliente,
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
                // Agrupamos por cliente Y por fecha
                ->groupBy('cliente', 'fecha')
                ->orderBy('fecha')
                ->get()
                // Convertimos a formato de búsqueda rápida: [cliente][fecha] => datos
                ->keyBy(function ($item) {
                    // Clave combinada
                    return $item->cliente . '|' . $item->fecha;
                });

            // --- 3c. Obtener la lista de clientes únicos que tuvieron actividad ---
            // Combinamos los clientes de ambas fuentes y obtenemos los únicos
            $clientesAQL = $aqlData->pluck('cliente')->unique();
            $clientesProceso = $procesoData->pluck('cliente')->unique();
            $todosLosClientes = $clientesAQL->merge($clientesProceso)->unique()->filter()->sort()->values(); // ->filter() para quitar nulos o vacíos si existen

            // --- 3d. Generar el Rango Completo de Fechas del Mes ---
            $periodoFechas = CarbonPeriod::create($fechaInicio, $fechaFin);

            // --- 3e. Procesar y Combinar los Datos en PHP ---
            $resultadoFinal = []; // Usamos una variable interna para construir el resultado

            // Iteramos por cada cliente encontrado
            foreach ($todosLosClientes as $cliente) {
                $datosPorCliente = []; // Array para guardar los datos diarios de este cliente

                // Iteramos por cada día del mes generado por CarbonPeriod
                foreach ($periodoFechas as $fecha) {
                    $fechaActualStr = $fecha->toDateString(); // Formato YYYY-MM-DD
                    $claveBusqueda = $cliente . '|' . $fechaActualStr;

                    // Buscamos los datos AQL para este cliente y día
                    $aqlDia = $aqlData->get($claveBusqueda);
                    $auditadaAQL = $aqlDia->total_auditada ?? 0;
                    $rechazadaAQL = $aqlDia->total_rechazada ?? 0;

                    // Buscamos los datos PROCESO para este cliente y día
                    $procesoDia = $procesoData->get($claveBusqueda);
                    $auditadaProceso = $procesoDia->total_auditada ?? 0;
                    $rechazadaProceso = $procesoDia->total_rechazada ?? 0;

                    // Calculamos los porcentajes (evitando división por cero)
                    $porcentajeAQL = $auditadaAQL > 0
                        ? round(($rechazadaAQL / $auditadaAQL) * 100, 2)
                        : 0;

                    $porcentajeProceso = $auditadaProceso > 0
                        ? round(($rechazadaProceso / $auditadaProceso) * 100, 2)
                        : 0;

                    // Agregamos la entrada para este día al resultado del cliente
                    $datosPorCliente[] = [
                        'dia' => $fecha->format('j'), // Día del mes (1, 2, ..., 31)
                        'AQL' => $porcentajeAQL,
                        'PROCESO' => $porcentajeProceso
                    ];
                }
                // Asignamos el array completo de días al cliente correspondiente en el resultado final
                $resultadoFinal[$cliente] = $datosPorCliente;
            }

            // Devolvemos el array procesado para que se guarde en caché
            // Esta variable interna $resultadoFinal no afecta el nombre de la variable $datos externa
            return $resultadoFinal;

        }); // Fin de Cache::remember

        // --- 4. Devolver Respuesta JSON ---
        // Devolvemos la variable $datos que contiene el resultado (cacheado o calculado)
        return response()->json($datos);
    }

    public function getMensualPorModuloV2()
    {
        // --- 1. Definir Rango de Fechas ---
        // Usamos Carbon para obtener el primer y último momento del mes actual
        $fechaInicio = Carbon::now()->startOfMonth()->startOfDay(); // Inicio del mes (YYYY-MM-01 00:00:00)
        $fechaFin = Carbon::now()->endOfMonth()->endOfDay();       // Fin del mes   (YYYY-MM-DD 23:59:59)

        // --- 2. Configuración de Caché ---
        // Clave única para el caché basada en el rango de fechas
        $cacheKey = "mensual_por_modulo_{$fechaInicio->toDateString()}_{$fechaFin->toDateString()}";
        // Duración del caché: 15 horas (en segundos). Ajusta si es necesario.
        // Considera una duración más corta si los datos cambian muy frecuentemente durante el día.
        $cacheTiempo = 15 * 60 * 60; // 15 horas

        // --- 3. Obtener Datos (Desde Caché o Calculando) ---
        $datos = Cache::remember($cacheKey, $cacheTiempo, function () use ($fechaInicio, $fechaFin) {

            // --- 3a. Obtener TODOS los datos AQL del mes en UNA consulta ---
            $aqlData = DB::table('auditoria_aql')
                ->selectRaw("
                    modulo,
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
                // Agrupamos por módulo Y por fecha para obtener totales diarios
                ->groupBy('modulo', 'fecha')
                ->orderBy('fecha') // Ordenar por fecha es útil para el procesamiento posterior
                ->get()
                // Convertimos a un formato más útil para búsqueda rápida: [modulo][fecha] => datos
                ->keyBy(function ($item) {
                    // Clave combinada para fácil acceso
                    return $item->modulo . '|' . $item->fecha;
                });

            // --- 3b. Obtener TODOS los datos de PROCESO del mes en UNA consulta ---
            $procesoData = DB::table('aseguramientos_calidad')
                ->selectRaw("
                    modulo,
                    DATE(created_at) as fecha,
                    SUM(cantidad_auditada) as total_auditada,
                    SUM(cantidad_rechazada) as total_rechazada
                ")
                ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
                // Agrupamos por módulo Y por fecha
                ->groupBy('modulo', 'fecha')
                ->orderBy('fecha')
                ->get()
                // Convertimos a formato de búsqueda rápida: [modulo][fecha] => datos
                ->keyBy(function ($item) {
                    // Clave combinada
                    return $item->modulo . '|' . $item->fecha;
                });

            // --- 3c. Obtener la lista de módulos únicos que tuvieron actividad ---
            // Combinamos los módulos de ambas fuentes y obtenemos los únicos
            $modulosAQL = $aqlData->pluck('modulo')->unique();
            $modulosProceso = $procesoData->pluck('modulo')->unique();
            $todosLosModulos = $modulosAQL->merge($modulosProceso)->unique()->sort()->values();

            // --- 3d. Generar el Rango Completo de Fechas del Mes ---
            // Usamos CarbonPeriod para tener todos los días, incluso si no hay datos en ellos
            $periodoFechas = CarbonPeriod::create($fechaInicio, $fechaFin);

            // --- 3e. Procesar y Combinar los Datos en PHP ---
            $resultadoFinal = [];

            // Iteramos por cada módulo encontrado
            foreach ($todosLosModulos as $modulo) {
                $datosPorModulo = [];

                // Iteramos por cada día del mes generado por CarbonPeriod
                foreach ($periodoFechas as $fecha) {
                    $fechaActualStr = $fecha->toDateString(); // Formato YYYY-MM-DD
                    $claveBusqueda = $modulo . '|' . $fechaActualStr;

                    // Buscamos los datos AQL para este módulo y día
                    // Si no existen (->get()), usamos valores por defecto (0)
                    $aqlDia = $aqlData->get($claveBusqueda);
                    $auditadaAQL = $aqlDia->total_auditada ?? 0;
                    $rechazadaAQL = $aqlDia->total_rechazada ?? 0;

                    // Buscamos los datos PROCESO para este módulo y día
                    $procesoDia = $procesoData->get($claveBusqueda);
                    $auditadaProceso = $procesoDia->total_auditada ?? 0;
                    $rechazadaProceso = $procesoDia->total_rechazada ?? 0;

                    // Calculamos los porcentajes (evitando división por cero)
                    $porcentajeAQL = $auditadaAQL > 0
                        ? round(($rechazadaAQL / $auditadaAQL) * 100, 2)
                        : 0;

                    $porcentajeProceso = $auditadaProceso > 0
                        ? round(($rechazadaProceso / $auditadaProceso) * 100, 2)
                        : 0;

                    // Agregamos la entrada para este día al resultado del módulo
                    $datosPorModulo[] = [
                        'dia' => $fecha->format('j'), // Día del mes sin ceros iniciales (1, 2, ..., 31)
                        'AQL' => $porcentajeAQL,
                        'PROCESO' => $porcentajeProceso
                    ];
                }
                // Asignamos el array completo de días al módulo correspondiente
                $resultadoFinal[$modulo] = $datosPorModulo;
            }

            // Devolvemos el array procesado para que se guarde en caché
            return $resultadoFinal;
        });

        // --- 4. Devolver Respuesta JSON ---
        // Devolvemos los datos (obtenidos de caché o recién calculados)
        return response()->json($datos);
    }


    public function getDefectoMensualV2()
    {
        $fechaFin = Carbon::now()->toDateString();
        $fechaInicio = Carbon::parse($fechaFin)->startOfMonth()->toDateString();
        
        // Establecemos 15 horas (54000 segundos) como duración del caché
        $cacheTiempo = 15 * 60 * 60; // 15 horas en segundos

        // Consulta para obtener los 3 defectos más repetidos de 'tp' excluyendo 'NINGUNO'
        $topDefectosAQL = Cache::remember("topDefectosAQL_{$fechaInicio}_{$fechaFin}", $cacheTiempo, function () use ($fechaInicio, $fechaFin) {
            return TpAuditoriaAQL::select('tp', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
        });

        $topDefectosProceso = Cache::remember("topDefectosProceso_{$fechaInicio}_{$fechaFin}", $cacheTiempo, function () use ($fechaInicio, $fechaFin) {
            return TpAseguramientoCalidad::select('tp', DB::raw('count(*) as total'))
                ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                ->where('tp', '!=', 'NINGUNO')
                ->groupBy('tp')
                ->orderBy('total', 'desc')
                ->limit(3)
                ->get();
        });

        return response()->json([
            'topDefectosAQL' => $topDefectosAQL,
            'topDefectosProceso' => $topDefectosProceso
        ]);
    }





}
