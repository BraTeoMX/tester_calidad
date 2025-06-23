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
            
            // --- TODA TU LÓGICA DE CÁLCULO VA AQUÍ DENTRO ---
            // Este bloque solo se ejecutará si han pasado más de 60 segundos
            // desde la última vez que se guardó el caché.

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

        // 3. Devolvemos la respuesta JSON.
        // La variable $stats contendrá los datos del caché o los recién calculados.
        return response()->json($stats);
    }
}
