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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class DashboardBusquedaOPController extends Controller
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

    public function index()
    {
        $title = "";

        return view('dashboard.busquedaOP', compact('title' ));
    }

    public function buscarOP(Request $request)
    {
        $op = $request->input('op');
    
        if (!$op) {
            return response()->json(['error' => 'No se proporcionó una OP'], 400);
        }
    
        $bultos = AuditoriaAQL::where('op', $op)
                    ->pluck('bulto')
                    ->unique()
                    ->values();
    
        return response()->json(['bultos' => $bultos]);
    }
    
}
