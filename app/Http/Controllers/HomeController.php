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
        // Usamos Carbon para determinar el inicio y fin de la semana actual
        // Es importante calcular esto fuera del closure de Cache::remember
        // para que la clave de caché sea consistente durante toda la semana.
        $startOfWeek = Carbon::now()->startOfWeek(); // Lunes 00:00:00
        $endOfWeek = Carbon::now()->endOfWeek();     // Domingo 23:59:59

        // Generamos una clave de caché basada en el inicio de la semana
        $cacheKey = 'datosSemana_' . $startOfWeek->toDateString();
        // Tiempo de caché: 6 horas (21600 segundos), como en el original.
        $cacheTime = 21600;

        // Obtenemos los datos (desde caché o calculándolos)
        $datosSemana = Cache::remember($cacheKey, $cacheTime, function () use ($startOfWeek, $endOfWeek) {
            // Llama a la nueva función optimizada, pasando el rango de fechas
            return $this->calcularPorcentajesSemanaActualOptimizado($startOfWeek, $endOfWeek);
        });

        // Retorna los datos (cacheados o recién calculados)
        return response()->json($datosSemana);
    }

    /**
     * Calcula los porcentajes de AQL y PROCESO para el rango de fechas especificado (semana),
     * agrupados por cliente, supervisor y módulo, usando solo dos consultas DB.
     *
     * @param \Carbon\Carbon $fechaInicio Inicio del rango (ej. Lunes 00:00:00)
     * @param \Carbon\Carbon $fechaFin    Fin del rango (ej. Domingo 23:59:59)
     * @return array Estructura con los datos para clientes, supervisores y módulos.
     */
    private function calcularPorcentajesSemanaActualOptimizado(Carbon $fechaInicio, Carbon $fechaFin)
    {
        // --- 1. Consulta Optimizada para AQL (Rango Semanal) ---
        $aqlData = AuditoriaAQL::select(
                'cliente',
                'team_leader',
                'modulo',
                DB::raw('SUM(cantidad_auditada) as total_auditada'),
                DB::raw('SUM(cantidad_rechazada) as total_rechazada')
            )
            // Usamos whereBetween con los objetos Carbon completos para el rango semanal
            ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
            ->groupBy('cliente', 'team_leader', 'modulo')
            ->get();

        // --- 2. Consulta Optimizada para PROCESO (Rango Semanal) ---
        $procesoData = AseguramientoCalidad::select(
                'cliente',
                'team_leader',
                'modulo',
                DB::raw('SUM(cantidad_auditada) as total_auditada'),
                DB::raw('SUM(cantidad_rechazada) as total_rechazada')
            )
             // Usamos whereBetween con los objetos Carbon completos para el rango semanal
            ->whereBetween('created_at', [$fechaInicio->toDateTimeString(), $fechaFin->toDateTimeString()])
            ->groupBy('cliente', 'team_leader', 'modulo')
            ->get();

        // --- 3. Procesamiento en PHP (Acumulación de Sumas) ---
        // Idéntico al procesamiento diario, solo cambian los datos de entrada (semanales)
        $sumsByClient = [];
        $sumsBySupervisor = [];
        $sumsByModulo = [];
        $uniqueKeys = ['clientes' => [], 'supervisores' => [], 'modulos' => []];

        // Procesamos los datos de AQL
        foreach ($aqlData as $item) {
            $cliente = $item->cliente ?? 'N/A';
            $supervisor = $item->team_leader ?? 'N/A';
            $modulo = $item->modulo ?? 'N/A';

            $sumsByClient[$cliente]['aql_auditada'] = ($sumsByClient[$cliente]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsByClient[$cliente]['aql_rechazada'] = ($sumsByClient[$cliente]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['clientes'][$cliente] = true;

            $sumsBySupervisor[$supervisor]['aql_auditada'] = ($sumsBySupervisor[$supervisor]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsBySupervisor[$supervisor]['aql_rechazada'] = ($sumsBySupervisor[$supervisor]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['supervisores'][$supervisor] = true;

            $sumsByModulo[$modulo]['aql_auditada'] = ($sumsByModulo[$modulo]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsByModulo[$modulo]['aql_rechazada'] = ($sumsByModulo[$modulo]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['modulos'][$modulo] = true;
        }

        // Procesamos los datos de PROCESO
        foreach ($procesoData as $item) {
            $cliente = $item->cliente ?? 'N/A';
            $supervisor = $item->team_leader ?? 'N/A';
            $modulo = $item->modulo ?? 'N/A';

            $sumsByClient[$cliente]['proceso_auditada'] = ($sumsByClient[$cliente]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsByClient[$cliente]['proceso_rechazada'] = ($sumsByClient[$cliente]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['clientes'][$cliente] = true;

            $sumsBySupervisor[$supervisor]['proceso_auditada'] = ($sumsBySupervisor[$supervisor]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsBySupervisor[$supervisor]['proceso_rechazada'] = ($sumsBySupervisor[$supervisor]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['supervisores'][$supervisor] = true;

            $sumsByModulo[$modulo]['proceso_auditada'] = ($sumsByModulo[$modulo]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsByModulo[$modulo]['proceso_rechazada'] = ($sumsByModulo[$modulo]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['modulos'][$modulo] = true;
        }

        // --- 4. Calcular Porcentajes Finales ---
        // Idéntico al cálculo diario
        $clientesResult = [];
        foreach (array_keys($uniqueKeys['clientes']) as $cliente) {
            $auditadaAQL = $sumsByClient[$cliente]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsByClient[$cliente]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsByClient[$cliente]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsByClient[$cliente]['proceso_rechazada'] ?? 0;

            // Diferencia clave: La estructura de retorno original usaba un array de objetos/arrays, no un array asociativo.
            $clientesResult[] = [
                'cliente' => $cliente, // Añadimos la clave como un campo
                '% AQL' => $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0,
                '% PROCESO' => $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0,
            ];
        }

        $supervisoresResult = [];
        foreach (array_keys($uniqueKeys['supervisores']) as $supervisor) {
            $auditadaAQL = $sumsBySupervisor[$supervisor]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsBySupervisor[$supervisor]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsBySupervisor[$supervisor]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsBySupervisor[$supervisor]['proceso_rechazada'] ?? 0;

            $supervisoresResult[] = [
                'team_leader' => $supervisor, // Añadimos la clave como un campo
                '% AQL' => $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0,
                '% PROCESO' => $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0,
            ];
        }

        $modulosResult = [];
        foreach (array_keys($uniqueKeys['modulos']) as $modulo) {
            $auditadaAQL = $sumsByModulo[$modulo]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsByModulo[$modulo]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsByModulo[$modulo]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsByModulo[$modulo]['proceso_rechazada'] ?? 0;

            $modulosResult[] = [
                'modulo' => $modulo, // Añadimos la clave como un campo
                '% AQL' => $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0,
                '% PROCESO' => $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0,
            ];
        }

        // --- 5. Retornar Estructura Final ---
        // Mantenemos la estructura original que espera la vista
        return [
            'clientes' => $clientesResult,
            'supervisores' => $supervisoresResult,
            'modulos' => $modulosResult,
        ];
    }

    public function getDashboardDataDiaV2()
    {
        $fechaActual = Carbon::now()->toDateString();

        // Cacheamos los datos del día actual.
        // Nota: El tiempo original era 3600 (1 hora), no 5 minutos. Lo mantengo en 3600.
        // Ajusta el tiempo (tercer argumento de Cache::remember) si necesitas 5 minutos (300).
        $cacheKey = 'datosDia_' . $fechaActual;
        $cacheTime = 3600; // 1 hora en segundos

        $datosDia = Cache::remember($cacheKey, $cacheTime, function () use ($fechaActual) {
            // Llama a la nueva función optimizada
            return $this->calcularPorcentajesDiaOptimizado($fechaActual);
        });

        // Retorna los datos (cacheados o recién calculados)
        return response()->json($datosDia);
    }

    /**
     * Calcula los porcentajes de AQL y PROCESO para el día especificado,
     * agrupados por cliente, supervisor y módulo, usando solo dos consultas DB.
     *
     * @param string $fechaActual Fecha en formato YYYY-MM-DD
     * @return array Estructura con los datos para clientes, supervisores y módulos.
     */
    private function calcularPorcentajesDiaOptimizado($fechaActual)
    {
        // --- 1. Consulta Optimizada para AQL ---
        // Obtenemos sumas agrupadas por todas las combinaciones relevantes en una sola consulta.
        $aqlData = AuditoriaAQL::select(
                'cliente',
                'team_leader',
                'modulo',
                DB::raw('SUM(cantidad_auditada) as total_auditada'),
                DB::raw('SUM(cantidad_rechazada) as total_rechazada')
            )
            ->whereDate('created_at', $fechaActual)
            // Agrupamos por las tres columnas para obtener todas las combinaciones
            ->groupBy('cliente', 'team_leader', 'modulo')
            ->get();

        // --- 2. Consulta Optimizada para PROCESO ---
        // Hacemos lo mismo para la tabla de aseguramientos_calidad
        $procesoData = AseguramientoCalidad::select(
                'cliente',
                'team_leader',
                'modulo',
                DB::raw('SUM(cantidad_auditada) as total_auditada'),
                DB::raw('SUM(cantidad_rechazada) as total_rechazada')
            )
            ->whereDate('created_at', $fechaActual)
            // Agrupamos por las tres columnas
            ->groupBy('cliente', 'team_leader', 'modulo')
            ->get();

        // --- 3. Procesamiento en PHP ---
        // Inicializamos arrays para acumular los totales por cada categoría
        $sumsByClient = [];
        $sumsBySupervisor = [];
        $sumsByModulo = [];
        $uniqueKeys = ['clientes' => [], 'supervisores' => [], 'modulos' => []];

        // Procesamos los datos de AQL
        foreach ($aqlData as $item) {
            $cliente = $item->cliente ?? 'N/A'; // Manejar nulos si es posible
            $supervisor = $item->team_leader ?? 'N/A';
            $modulo = $item->modulo ?? 'N/A';

            // Acumulamos por cliente
            $sumsByClient[$cliente]['aql_auditada'] = ($sumsByClient[$cliente]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsByClient[$cliente]['aql_rechazada'] = ($sumsByClient[$cliente]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['clientes'][$cliente] = true; // Registrar cliente único

            // Acumulamos por supervisor
            $sumsBySupervisor[$supervisor]['aql_auditada'] = ($sumsBySupervisor[$supervisor]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsBySupervisor[$supervisor]['aql_rechazada'] = ($sumsBySupervisor[$supervisor]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['supervisores'][$supervisor] = true; // Registrar supervisor único

            // Acumulamos por modulo
            $sumsByModulo[$modulo]['aql_auditada'] = ($sumsByModulo[$modulo]['aql_auditada'] ?? 0) + $item->total_auditada;
            $sumsByModulo[$modulo]['aql_rechazada'] = ($sumsByModulo[$modulo]['aql_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['modulos'][$modulo] = true; // Registrar modulo único
        }

        // Procesamos los datos de PROCESO
        foreach ($procesoData as $item) {
            $cliente = $item->cliente ?? 'N/A';
            $supervisor = $item->team_leader ?? 'N/A';
            $modulo = $item->modulo ?? 'N/A';

            // Acumulamos por cliente
            $sumsByClient[$cliente]['proceso_auditada'] = ($sumsByClient[$cliente]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsByClient[$cliente]['proceso_rechazada'] = ($sumsByClient[$cliente]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['clientes'][$cliente] = true;

            // Acumulamos por supervisor
            $sumsBySupervisor[$supervisor]['proceso_auditada'] = ($sumsBySupervisor[$supervisor]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsBySupervisor[$supervisor]['proceso_rechazada'] = ($sumsBySupervisor[$supervisor]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['supervisores'][$supervisor] = true;

            // Acumulamos por modulo
            $sumsByModulo[$modulo]['proceso_auditada'] = ($sumsByModulo[$modulo]['proceso_auditada'] ?? 0) + $item->total_auditada;
            $sumsByModulo[$modulo]['proceso_rechazada'] = ($sumsByModulo[$modulo]['proceso_rechazada'] ?? 0) + $item->total_rechazada;
            $uniqueKeys['modulos'][$modulo] = true;
        }

        // --- 4. Calcular Porcentajes Finales ---
        $clientesResult = [];
        foreach (array_keys($uniqueKeys['clientes']) as $cliente) {
            $auditadaAQL = $sumsByClient[$cliente]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsByClient[$cliente]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsByClient[$cliente]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsByClient[$cliente]['proceso_rechazada'] ?? 0;

            $clientesResult[$cliente]['% AQL'] = $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0;
            $clientesResult[$cliente]['% PROCESO'] = $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0;
        }

        $supervisoresResult = [];
        foreach (array_keys($uniqueKeys['supervisores']) as $supervisor) {
            $auditadaAQL = $sumsBySupervisor[$supervisor]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsBySupervisor[$supervisor]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsBySupervisor[$supervisor]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsBySupervisor[$supervisor]['proceso_rechazada'] ?? 0;

            $supervisoresResult[$supervisor]['% AQL'] = $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0;
            $supervisoresResult[$supervisor]['% PROCESO'] = $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0;
        }

        $modulosResult = [];
        foreach (array_keys($uniqueKeys['modulos']) as $modulo) {
            $auditadaAQL = $sumsByModulo[$modulo]['aql_auditada'] ?? 0;
            $rechazadaAQL = $sumsByModulo[$modulo]['aql_rechazada'] ?? 0;
            $auditadaProceso = $sumsByModulo[$modulo]['proceso_auditada'] ?? 0;
            $rechazadaProceso = $sumsByModulo[$modulo]['proceso_rechazada'] ?? 0;

            $modulosResult[$modulo]['% AQL'] = $auditadaAQL > 0 ? round(($rechazadaAQL / $auditadaAQL) * 100, 2) : 0;
            $modulosResult[$modulo]['% PROCESO'] = $auditadaProceso > 0 ? round(($rechazadaProceso / $auditadaProceso) * 100, 2) : 0;
        }

        // --- 5. Retornar Estructura Final ---
        // Mantenemos la estructura original que espera la vista
        return [
            'clientes' => $clientesResult,
            'supervisores' => $supervisoresResult,
            'modulos' => $modulosResult,
        ];
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
