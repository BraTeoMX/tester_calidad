<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
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
}
