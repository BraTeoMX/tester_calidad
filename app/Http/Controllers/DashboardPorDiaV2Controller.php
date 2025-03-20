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


class DashboardPorDiaV2Controller extends Controller
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

    public function dashboardPlanta1V2(Request $request)
    {
        $title = "";
        // Verifica si hay una fecha en la solicitud; si la hay, la convierte en un objeto Carbon, si no, usa la fecha actual
        $fechaActual = $request->has('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio')) 
            : Carbon::now();  // Aquí no se usa toDateString(), así que $fechaActual es un objeto Carbon
        //dd($fechaActual);
        $plantaConsulta = "Intimark1";
        $fechaInicio = Carbon::now()->subMonth()->toDateString(); // Cambia el rango de fechas según necesites
        $fechaFin = Carbon::now()->toDateString();

        return view('dashboar.dashboardPlanta1PorDiaV2', compact('title', 'fechaActual' ));
    }

}
