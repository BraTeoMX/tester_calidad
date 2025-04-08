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

    public function buscar(Request $request)
    {
        $tipo = $request->input('tipo');
        $termino = $request->input('termino');

        if (!$termino) {
            return response()->json(['error' => 'Ingrese un término de búsqueda'], 400);
        }

        $query = AuditoriaAQL::query();

        switch ($tipo) {
            case 'op':
                $query->where('op', 'LIKE', "%{$termino}%");
                break;

            case 'estilo':
                $ops = AuditoriaAQL::where('estilo', 'LIKE', "%{$termino}%")
                        ->distinct()
                        ->pluck('op');

                return response()->json(['ops' => $ops]);
                break;

            case 'color':
                $ops = AuditoriaAQL::where('color', 'LIKE', "%{$termino}%")
                        ->distinct()
                        ->pluck('op');

                return response()->json(['ops' => $ops]);
                break;

            default:
                return response()->json(['error' => 'Tipo de búsqueda no válido'], 400);
        }

        $resultados = $query->get([
            'op', 'bulto', 'auditor', 'modulo', 'cliente',
            'estilo', 'color', 'planta', 'cantidad_auditada', 'cantidad_rechazada', 'created_at'
        ]);

        // Dar formato a la fecha:
        $resultados->transform(function($item){
            $item->fecha_creacion = Carbon::parse($item->created_at)->format('d-m-Y H:i:s');
            return $item;
        });

        return response()->json(['resultados' => $resultados]);
    }

    
}
