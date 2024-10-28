<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Models\AseguramientoCalidad;
use App\Models\TpAseguramientoCalidad;
use App\Models\TpAuditoriaAQL;
use App\Models\AuditoriaAQL;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB


class DashboardCostosController extends Controller
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

    public function dashboardCostosNoCalidad(Request $request)
    {
        $title = "";

        // Obtener las fechas de inicio y fin del request o establecer por defecto las últimas 6 semanas
        $fechaFin = $request->input('fecha_fin') ? Carbon::parse($request->input('fecha_fin'))->endOfDay() : Carbon::now()->endOfDay();
        $fechaInicio = $request->input('fecha_inicio') ? Carbon::parse($request->input('fecha_inicio'))->startOfDay() : Carbon::now()->subWeeks(6)->startOfDay();
        $datosCostos = 1;
        //dd($fechaInicio, $fechaFin);

        return view('dashboar.dashboardCostosNoCalidad', compact('title', 'datosCostos', 'fechaInicio', 'fechaFin'));
    }


}
