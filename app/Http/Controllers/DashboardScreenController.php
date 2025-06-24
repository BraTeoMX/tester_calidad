<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use App\Models\CatalogoDefectosScreen;
use App\Models\CategoriaAccionCorrectScreen;
use App\Models\JobAQLHistorial;
use App\Models\InspeccionHorno;
use App\Models\InspeccionHornoScreen;
use App\Models\InspeccionHornoPlancha;
use App\Models\InspeccionHornoTecnica;
use App\Models\InspeccionHornoFibra;
use App\Models\InspeccionHornoScreenDefecto;
use App\Models\InspeccionHornoPlanchaDefecto;
use App\Models\CategoriaTipoPanel;
use App\Models\CategoriaTipoMaquina;
use App\Models\Tecnicos;
use App\Models\Tipo_Fibra;
use App\Models\Tipo_Tecnica;
use App\Models\Horno_Banda;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Cell\DataType; // Para especificar tipos de datos
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DashboardScreenController extends Controller
{
    public function dashboard()
    {
        return view('screen.dashboard');
    }

    public function getDashboardStats()
    {
        $cacheKey = 'dashboard_stats_' . Carbon::today()->toDateString();

        $stats = Cache::remember($cacheKey, 60, function () {
            $fechaActual = Carbon::today()->toDateString();

            // --- CÁLCULO PARA AUDITORIA SCREEN ---
            $queryScreen = InspeccionHorno::with(['screen.defectos'])
                ->whereHas('screen')
                ->whereDate('created_at', $fechaActual);

            $inspeccionesScreen = $queryScreen->get();

            $cantidadTotalRevisadaScreen = (float) $inspeccionesScreen->sum('cantidad');
            $cantidadDefectosScreen = $inspeccionesScreen->sum(function ($inspeccion) {
                return $inspeccion->screen ? $inspeccion->screen->defectos->sum('cantidad') : 0;
            });

            $porcentajeScreen = 0.0;
            if ($cantidadTotalRevisadaScreen > 0) {
                $porcentajeScreen = ($cantidadDefectosScreen / $cantidadTotalRevisadaScreen) * 100;
            }

            // --- CÁLCULO PARA AUDITORIA PLANCHA ---
            $queryPlancha = InspeccionHorno::with(['plancha.defectos'])
                ->whereHas('plancha')
                ->whereDate('created_at', $fechaActual);
            
            $inspeccionesPlancha = $queryPlancha->get();

            $cantidadTotalRevisadaPlancha = $inspeccionesPlancha->sum(function ($inspeccion) {
                return ($inspeccion->plancha && is_numeric($inspeccion->plancha->piezas_auditadas))
                    ? (float) $inspeccion->plancha->piezas_auditadas
                    : 0.0;
            });

            $cantidadDefectosPlancha = $inspeccionesPlancha->sum(function ($inspeccion) {
                return $inspeccion->plancha ? $inspeccion->plancha->defectos->sum('cantidad') : 0;
            });

            $porcentajePlancha = 0.0;
            if ($cantidadTotalRevisadaPlancha > 0) {
                $porcentajePlancha = ($cantidadDefectosPlancha / $cantidadTotalRevisadaPlancha) * 100;
            }

            // La función DEBE devolver el valor que se va a guardar en el caché.
            return [
                'porcentajeScreen' => round($porcentajeScreen, 2),
                'porcentajePlancha' => round($porcentajePlancha, 2)
            ];
        });

        return response()->json($stats);
    }

    public function getClientStats()
    {
        $cacheKey = 'dashboard_client_stats_' . Carbon::today()->toDateString();
        $ttl = 60; // 1 minuto de caché

        $data = Cache::remember($cacheKey, $ttl, function () {
            $fechaActual = Carbon::today()->toDateString();

            // 1. Obtenemos TODAS las inspecciones del día que tengan relación con 'screen' o 'plancha'.
            // Hacemos eager loading de las relaciones para optimizar.
            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereDate('created_at', $fechaActual)
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // 2. Agrupamos la colección completa de inspecciones por el campo 'cliente'.
            $groupedByClient = $inspecciones->groupBy('cliente');

            $clientData = [];
            $totalGeneralRevisadaScreen = 0;
            $totalGeneralDefectosScreen = 0;
            $totalGeneralRevisadaPlancha = 0;
            $totalGeneralDefectosPlancha = 0;

            // 3. Iteramos sobre cada grupo de cliente para calcular sus estadísticas.
            foreach ($groupedByClient as $cliente => $clientInspections) {
                // --- Cálculo para Screen por cliente ---
                $cantidadRevisadaScreen = (float) $clientInspections->sum('cantidad');
                $cantidadDefectosScreen = $clientInspections->sum(function ($insp) {
                    return $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0;
                });

                $porcentajeScreen = ($cantidadRevisadaScreen > 0)
                    ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100
                    : 0;
                
                // Acumulamos para el total general
                $totalGeneralRevisadaScreen += $cantidadRevisadaScreen;
                $totalGeneralDefectosScreen += $cantidadDefectosScreen;


                // --- Cálculo para Plancha por cliente ---
                $cantidadRevisadaPlancha = $clientInspections->sum(function ($insp) {
                    return ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas))
                           ? (float) $insp->plancha->piezas_auditadas : 0.0;
                });
                $cantidadDefectosPlancha = $clientInspections->sum(function ($insp) {
                    return $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0;
                });

                $porcentajePlancha = ($cantidadRevisadaPlancha > 0)
                    ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100
                    : 0;

                // Acumulamos para el total general
                $totalGeneralRevisadaPlancha += $cantidadRevisadaPlancha;
                $totalGeneralDefectosPlancha += $cantidadDefectosPlancha;
                
                // Añadimos los datos del cliente al array de resultados.
                $clientData[] = [
                    'cliente' => $cliente,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }

            // 4. Calculamos los porcentajes generales finales
            $porcentajeGeneralScreen = ($totalGeneralRevisadaScreen > 0)
                ? ($totalGeneralDefectosScreen / $totalGeneralRevisadaScreen) * 100
                : 0;

            $porcentajeGeneralPlancha = ($totalGeneralRevisadaPlancha > 0)
                ? ($totalGeneralDefectosPlancha / $totalGeneralRevisadaPlancha) * 100
                : 0;

            // 5. Devolvemos una estructura que contiene la lista de clientes y los totales.
            return [
                'clientes' => $clientData,
                'generales' => [
                    'porcentajeScreen' => round($porcentajeGeneralScreen, 2),
                    'porcentajePlancha' => round($porcentajeGeneralPlancha, 2),
                ]
            ];
        });

        return response()->json($data);
    }

    public function getResponsibleStats()
    {
        $cacheKey = 'dashboard_responsible_stats_' . Carbon::today()->toDateString();
        $ttl = 60; // 1 minuto de caché

        $data = Cache::remember($cacheKey, $ttl, function () {
            
            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // 1. Creamos un array para agrupar manualmente las inspecciones por técnico.
            $inspeccionesPorTecnico = [];

            foreach ($inspecciones as $inspeccion) {
                // Si la inspección tiene un registro de 'screen' con un técnico
                if ($inspeccion->screen && !empty($inspeccion->screen->nombre_tecnico)) {
                    $nombreTecnico = $inspeccion->screen->nombre_tecnico;
                    // Añadimos la inspección al grupo de ese técnico
                    $inspeccionesPorTecnico[$nombreTecnico][] = $inspeccion;
                }
                // Si la inspección tiene un registro de 'plancha' con un técnico
                if ($inspeccion->plancha && !empty($inspeccion->plancha->nombre_tecnico)) {
                    $nombreTecnico = $inspeccion->plancha->nombre_tecnico;
                    // Añadimos la inspección al grupo de ese técnico
                    $inspeccionesPorTecnico[$nombreTecnico][] = $inspeccion;
                }
            }

            $responsibleData = [];

            // 2. Iteramos sobre nuestro array agrupado manualmente.
            foreach ($inspeccionesPorTecnico as $tecnico => $inspections) {
                // Convertimos el array de inspecciones de vuelta a una colección para usar sus métodos.
                $coleccionDelTecnico = new Collection($inspections);

                // --- Cálculo para Screen por técnico ---
                $cantidadRevisadaScreen = (float) $coleccionDelTecnico->sum('cantidad');
                $cantidadDefectosScreen = $coleccionDelTecnico->sum(function ($insp) {
                    return $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0;
                });

                $porcentajeScreen = ($cantidadRevisadaScreen > 0)
                    ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100
                    : 0;

                // --- Cálculo para Plancha por técnico ---
                $cantidadRevisadaPlancha = $coleccionDelTecnico->sum(function ($insp) {
                    return ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas))
                           ? (float) $insp->plancha->piezas_auditadas : 0.0;
                });
                $cantidadDefectosPlancha = $coleccionDelTecnico->sum(function ($insp) {
                    return $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0;
                });

                $porcentajePlancha = ($cantidadRevisadaPlancha > 0)
                    ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100
                    : 0;

                // Añadimos los datos del responsable al array de resultados.
                $responsibleData[] = [
                    'responsable' => $tecnico,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }

            // Devolvemos el array de datos de responsables.
            return $responsibleData;
        });

        return response()->json($data);
    }

    public function getMachineStats()
    {
        $cacheKey = 'dashboard_machine_stats_' . Carbon::today()->toDateString();
        $ttl = 60; // 1 minuto de caché

        $data = Cache::remember($cacheKey, $ttl, function () {
            
            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereDate('created_at', Carbon::today()->toDateString())
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // 1. Agrupamos la colección por el campo 'maquina'. ¡Este es el único cambio clave!
            $groupedByMachine = $inspecciones->groupBy('maquina');

            $machineData = [];

            // 2. Iteramos sobre cada grupo de máquina para calcular sus estadísticas.
            foreach ($groupedByMachine as $maquina => $machineInspections) {
                
                // --- Cálculo para Screen por máquina ---
                $cantidadRevisadaScreen = (float) $machineInspections->sum('cantidad');
                $cantidadDefectosScreen = $machineInspections->sum(function ($insp) {
                    return $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0;
                });
                $porcentajeScreen = ($cantidadRevisadaScreen > 0)
                    ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100
                    : 0;

                // --- Cálculo para Plancha por máquina ---
                $cantidadRevisadaPlancha = $machineInspections->sum(function ($insp) {
                    return ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas))
                           ? (float) $insp->plancha->piezas_auditadas : 0.0;
                });
                $cantidadDefectosPlancha = $machineInspections->sum(function ($insp) {
                    return $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0;
                });
                $porcentajePlancha = ($cantidadRevisadaPlancha > 0)
                    ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100
                    : 0;

                // Añadimos los datos de la máquina al array de resultados.
                $machineData[] = [
                    'maquina' => $maquina,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }

            // Devolvemos el array de datos de máquinas.
            return $machineData;
        });

        return response()->json($data);
    }

    public function getClientStatsWeekly()
    {
        // La clave del caché debe ser única para esta función
        $cacheKey = 'dashboard_client_stats_weekly_' . Carbon::now()->startOfWeek()->toDateString();
        $ttl = 60; // 1 minuto de caché

        $data = Cache::remember($cacheKey, $ttl, function () {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            
            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // --- LÓGICA CORRECTA: Agrupar por CLIENTE ---
            $groupedByClient = $inspecciones->groupBy('cliente');

            $clientData = [];
            $totalGeneralRevisadaScreen = 0;
            $totalGeneralDefectosScreen = 0;
            $totalGeneralRevisadaPlancha = 0;
            $totalGeneralDefectosPlancha = 0;

            foreach ($groupedByClient as $cliente => $clientInspections) {
                // Cálculos para Screen
                $cantidadRevisadaScreen = (float) $clientInspections->sum('cantidad');
                $cantidadDefectosScreen = $clientInspections->sum(fn($insp) => $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0);
                $porcentajeScreen = ($cantidadRevisadaScreen > 0) ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100 : 0;
                $totalGeneralRevisadaScreen += $cantidadRevisadaScreen;
                $totalGeneralDefectosScreen += $cantidadDefectosScreen;

                // Cálculos para Plancha
                $cantidadRevisadaPlancha = $clientInspections->sum(fn($insp) => ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas)) ? (float) $insp->plancha->piezas_auditadas : 0.0);
                $cantidadDefectosPlancha = $clientInspections->sum(fn($insp) => $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0);
                $porcentajePlancha = ($cantidadRevisadaPlancha > 0) ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100 : 0;
                $totalGeneralRevisadaPlancha += $cantidadRevisadaPlancha;
                $totalGeneralDefectosPlancha += $cantidadDefectosPlancha;
                
                $clientData[] = [
                    'cliente' => $cliente,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }

            $porcentajeGeneralScreen = ($totalGeneralRevisadaScreen > 0) ? ($totalGeneralDefectosScreen / $totalGeneralRevisadaScreen) * 100 : 0;
            $porcentajeGeneralPlancha = ($totalGeneralRevisadaPlancha > 0) ? ($totalGeneralDefectosPlancha / $totalGeneralRevisadaPlancha) * 100 : 0;

            // Devolvemos la estructura que el JS espera para los clientes
            return [
                'clientes' => $clientData,
                'generales' => [
                    'porcentajeScreen' => round($porcentajeGeneralScreen, 2),
                    'porcentajePlancha' => round($porcentajeGeneralPlancha, 2),
                ]
            ];
        });

        return response()->json($data);
    }

    public function getResponsibleStatsWeekly()
    {
        // Clave de caché única
        $cacheKey = 'dashboard_responsible_stats_weekly_' . Carbon::now()->startOfWeek()->toDateString();
        $ttl = 60;

        $data = Cache::remember($cacheKey, $ttl, function () {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // --- LÓGICA CORRECTA: Agrupación manual por TÉCNICO ---
            $inspeccionesPorTecnico = [];
            foreach ($inspecciones as $inspeccion) {
                if ($inspeccion->screen && !empty($inspeccion->screen->nombre_tecnico)) {
                    $inspeccionesPorTecnico[$inspeccion->screen->nombre_tecnico][] = $inspeccion;
                }
                if ($inspeccion->plancha && !empty($inspeccion->plancha->nombre_tecnico)) {
                    $inspeccionesPorTecnico[$inspeccion->plancha->nombre_tecnico][] = $inspeccion;
                }
            }

            $responsibleData = [];
            foreach ($inspeccionesPorTecnico as $tecnico => $inspections) {
                $coleccionDelTecnico = new Collection($inspections);

                $cantidadRevisadaScreen = (float) $coleccionDelTecnico->sum('cantidad');
                $cantidadDefectosScreen = $coleccionDelTecnico->sum(fn($insp) => $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0);
                $porcentajeScreen = ($cantidadRevisadaScreen > 0) ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100 : 0;

                $cantidadRevisadaPlancha = $coleccionDelTecnico->sum(fn($insp) => ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas)) ? (float) $insp->plancha->piezas_auditadas : 0.0);
                $cantidadDefectosPlancha = $coleccionDelTecnico->sum(fn($insp) => $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0);
                $porcentajePlancha = ($cantidadRevisadaPlancha > 0) ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100 : 0;

                $responsibleData[] = [
                    'responsable' => $tecnico,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }
            
            return $responsibleData;
        });

        return response()->json($data);
    }

    public function getMachineStatsWeekly()
    {
        // Clave de caché única
        $cacheKey = 'dashboard_machine_stats_weekly_' . Carbon::now()->startOfWeek()->toDateString();
        $ttl = 60;

        $data = Cache::remember($cacheKey, $ttl, function () {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
            
            $inspecciones = InspeccionHorno::with(['screen.defectos', 'plancha.defectos'])
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->where(function ($query) {
                    $query->whereHas('screen')->orWhereHas('plancha');
                })
                ->get();

            // --- LÓGICA CORRECTA: Agrupar por MÁQUINA ---
            $groupedByMachine = $inspecciones->groupBy('maquina');

            $machineData = [];

            foreach ($groupedByMachine as $maquina => $machineInspections) {
                $cantidadRevisadaScreen = (float) $machineInspections->sum('cantidad');
                $cantidadDefectosScreen = $machineInspections->sum(fn($insp) => $insp->screen ? $insp->screen->defectos->sum('cantidad') : 0);
                $porcentajeScreen = ($cantidadRevisadaScreen > 0) ? ($cantidadDefectosScreen / $cantidadRevisadaScreen) * 100 : 0;

                $cantidadRevisadaPlancha = $machineInspections->sum(fn($insp) => ($insp->plancha && is_numeric($insp->plancha->piezas_auditadas)) ? (float) $insp->plancha->piezas_auditadas : 0.0);
                $cantidadDefectosPlancha = $machineInspections->sum(fn($insp) => $insp->plancha ? $insp->plancha->defectos->sum('cantidad') : 0);
                $porcentajePlancha = ($cantidadRevisadaPlancha > 0) ? ($cantidadDefectosPlancha / $cantidadRevisadaPlancha) * 100 : 0;

                $machineData[] = [
                    'maquina' => $maquina,
                    'porcentajeScreen' => round($porcentajeScreen, 2),
                    'porcentajePlancha' => round($porcentajePlancha, 2),
                ];
            }

            return $machineData;
        });

        return response()->json($data);
    }

    public function getDashboardStatsMonth()
    {
        // Usamos v5 en la clave para una nueva versión de caché
        $cacheKey = 'dashboard_stats_month_v5_' . Carbon::today()->format('Y-m');
        $ttl = 300; // 5 minutos de caché

        $monthlyData = Cache::remember($cacheKey, $ttl, function () {
            
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::today()->endOfDay(); // Usamos endOfDay para incluir todo el día de hoy

            // --- 1. DATOS DE SCREEN: Consulta única que agrupa y suma en la BD ---
            // Se le pide a la base de datos que haga todo el trabajo pesado.
            $screenData = DB::table('inspeccion_horno as ih')
                ->leftJoin('inspeccion_horno_screen as ihs', 'ih.id', '=', 'ihs.inspeccion_horno_id')
                ->leftJoin('inspeccion_horno_screen_defecto as ihsd', 'ihs.id', '=', 'ihsd.inspeccion_horno_screen_id')
                ->selectRaw("
                    DATE(ih.created_at) as fecha,
                    SUM(ih.cantidad) as total_auditado,
                    SUM(ihsd.cantidad) as total_defectos
                ")
                ->whereNotNull('ihs.id') // Esto asegura que solo contamos inspecciones de Screen.
                ->whereBetween('ih.created_at', [$startOfMonth, $today])
                ->groupBy('fecha')
                ->get()
                ->keyBy('fecha'); // La clave del array será la fecha ('YYYY-MM-DD') para una búsqueda instantánea.

            // --- 2. DATOS DE PLANCHA: Consulta única que agrupa y suma en la BD ---
            $planchaData = DB::table('inspeccion_horno as ih')
                ->leftJoin('inspeccion_horno_plancha as ihp', 'ih.id', '=', 'ihp.inspeccion_horno_id')
                ->leftJoin('inspeccion_horno_plancha_defecto as ihpd', 'ihp.id', '=', 'ihpd.inspeccion_horno_plancha_id')
                ->selectRaw("
                    DATE(ih.created_at) as fecha,
                    SUM(ihp.piezas_auditadas) as total_auditado,
                    SUM(ihpd.cantidad) as total_defectos
                ")
                ->whereNotNull('ihp.id') // Esto asegura que solo contamos inspecciones de Plancha.
                ->whereBetween('ih.created_at', [$startOfMonth, $today])
                ->groupBy('fecha')
                ->get()
                ->keyBy('fecha');

            // --- 3. PROCESAR Y COMBINAR EN PHP (Esto ya es muy rápido) ---
            $periodoFechas = CarbonPeriod::create($startOfMonth, $today);
            $results = [];

            foreach ($periodoFechas as $fecha) {
                $fechaActualStr = $fecha->toDateString();

                // Buscar datos de Screen para el día actual (ya pre-calculados por la BD)
                $screenDia = $screenData->get($fechaActualStr);
                $auditadaScreen = $screenDia->total_auditado ?? 0;
                $defectosScreen = $screenDia->total_defectos ?? 0;
                $porcentajeScreen = $auditadaScreen > 0 ? round(($defectosScreen / $auditadaScreen) * 100, 2) : 0;

                // Buscar datos de Plancha para el día actual (ya pre-calculados por la BD)
                $planchaDia = $planchaData->get($fechaActualStr);
                $auditadaPlancha = $planchaDia->total_auditado ?? 0;
                $defectosPlancha = $planchaDia->total_defectos ?? 0;
                $porcentajePlancha = $auditadaPlancha > 0 ? round(($defectosPlancha / $auditadaPlancha) * 100, 2) : 0;

                $results[] = [
                    'dia'               => $fecha->day,
                    'porcentajeScreen'  => $porcentajeScreen,
                    'porcentajePlancha' => $porcentajePlancha,
                ];
            }

            return $results;
        });

        return response()->json($monthlyData);
    }

    public function getClientStatsMonth()
    {
        $cacheKey = 'client_stats_month_' . Carbon::today()->format('Y-m');
        $ttl = 300; // 5 minutos de caché

        $monthlyDataByClient = Cache::remember($cacheKey, $ttl, function () {
            $startOfMonth = Carbon::now()->startOfMonth();
            $today = Carbon::today()->endOfDay();

            // --- 1. DATOS DE SCREEN: Agrupados por CLIENTE y FECHA en la BD ---
            $screenData = DB::table('inspeccion_horno as ih')
                ->leftJoin('inspeccion_horno_screen as ihs', 'ih.id', '=', 'ihs.inspeccion_horno_id')
                ->leftJoin('inspeccion_horno_screen_defecto as ihsd', 'ihs.id', '=', 'ihsd.inspeccion_horno_screen_id')
                ->selectRaw("
                    ih.cliente,
                    DATE(ih.created_at) as fecha,
                    SUM(ih.cantidad) as total_auditado,
                    SUM(ihsd.cantidad) as total_defectos
                ")
                ->whereNotNull('ihs.id')->whereNotNull('ih.cliente')
                ->whereBetween('ih.created_at', [$startOfMonth, $today])
                ->groupBy('ih.cliente', 'fecha')
                ->get()
                ->keyBy(fn($item) => $item->cliente . '|' . $item->fecha); // Clave combinada

            // --- 2. DATOS DE PLANCHA: Agrupados por CLIENTE y FECHA en la BD ---
            $planchaData = DB::table('inspeccion_horno as ih')
                ->leftJoin('inspeccion_horno_plancha as ihp', 'ih.id', '=', 'ihp.inspeccion_horno_id')
                ->leftJoin('inspeccion_horno_plancha_defecto as ihpd', 'ihp.id', '=', 'ihpd.inspeccion_horno_plancha_id')
                ->selectRaw("
                    ih.cliente,
                    DATE(ih.created_at) as fecha,
                    SUM(ihp.piezas_auditadas) as total_auditado,
                    SUM(ihpd.cantidad) as total_defectos
                ")
                ->whereNotNull('ihp.id')->whereNotNull('ih.cliente')
                ->whereBetween('ih.created_at', [$startOfMonth, $today])
                ->groupBy('ih.cliente', 'fecha')
                ->get()
                ->keyBy(fn($item) => $item->cliente . '|' . $item->fecha);

            // --- 3. PROCESAR Y COMBINAR EN PHP ---
            $todosLosClientes = $screenData->pluck('cliente')->merge($planchaData->pluck('cliente'))->unique()->sort();
            $periodoFechas = CarbonPeriod::create($startOfMonth, $today);
            $resultadoFinal = [];

            foreach ($todosLosClientes as $cliente) {
                $datosPorCliente = [];
                foreach ($periodoFechas as $fecha) {
                    $fechaStr = $fecha->toDateString();
                    $clave = $cliente . '|' . $fechaStr;

                    $screenDia = $screenData->get($clave);
                    $auditadaScreen = $screenDia->total_auditado ?? 0;
                    $defectosScreen = $screenDia->total_defectos ?? 0;
                    $porcentajeScreen = $auditadaScreen > 0 ? round(($defectosScreen / $auditadaScreen) * 100, 2) : 0;
                    
                    $planchaDia = $planchaData->get($clave);
                    $auditadaPlancha = $planchaDia->total_auditado ?? 0;
                    $defectosPlancha = $planchaDia->total_defectos ?? 0;
                    $porcentajePlancha = $auditadaPlancha > 0 ? round(($defectosPlancha / $auditadaPlancha) * 100, 2) : 0;
                    
                    $datosPorCliente[] = [
                        'dia'                 => $fecha->day,
                        'porcentajeScreen'    => $porcentajeScreen,
                        'porcentajePlancha'   => $porcentajePlancha,
                    ];
                }
                $resultadoFinal[$cliente] = $datosPorCliente;
            }

            return $resultadoFinal;
        });

        return response()->json($monthlyDataByClient);
    }

}
