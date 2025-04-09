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
use Illuminate\Support\Facades\App;


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
        App::setLocale('es'); // Establecer el idioma en español para esta solicitud
        Carbon::setLocale('es'); // Para Carbon en específico
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

        $resultados = $query->with('tpAuditoriaAQL')->get([
            'id',
            'op', 'bulto', 'auditor', 'modulo', 'cliente',
            'estilo', 'color', 'planta', 'pieza',
            'cantidad_auditada', 'cantidad_rechazada', 'created_at'
        ]);
        
        $resultados->transform(function ($item) {
            $item->fecha_creacion = \Carbon\Carbon::parse($item->created_at)->translatedFormat('d \d\e F \d\e Y - H:i:s');
        
            // Calcular porcentaje AQL
            $pieza = $item->pieza ?? 0;
            $rechazada = $item->cantidad_rechazada ?? 0;
            $item->porcentaje_aql = $pieza > 0 ? round(($rechazada / $pieza) * 100, 2) : 0;
        
            // Obtener defectos
            $defectos = $item->tpAuditoriaAQL->pluck('tp')->filter();
        
            // Detectar si alguno dice "NINGUNO"
            $contieneNinguno = $defectos->contains(function($valor) {
                return strtolower(trim($valor)) === 'ninguno';
            });
        
            if ($contieneNinguno || $defectos->isEmpty()) {
                $htmlDefectos = '<span>N/A</span>';
            } else {
                $conteoDefectos = $defectos->countBy();
                $htmlDefectos = '<ul class="mb-0">';
                
                foreach ($conteoDefectos as $nombre => $cantidad) {
                    if ($cantidad > 1) {
                        $htmlDefectos .= "<li>{$nombre} ({$cantidad})</li>";
                    } else {
                        $htmlDefectos .= "<li>{$nombre}</li>";
                    }
                }
        
                $htmlDefectos .= '</ul>';
            }
        
            $item->defectos_html = $htmlDefectos;
        
            // Campos vacíos como "N/A"
            $campos = [
                'op', 'bulto', 'auditor', 'modulo', 'cliente',
                'estilo', 'color', 'planta', 'pieza',
                'cantidad_auditada', 'cantidad_rechazada'
            ];
        
            foreach ($campos as $campo) {
                if (is_null($item->$campo) || $item->$campo === '') {
                    $item->$campo = 'N/A';
                }
            }
        
            return $item;
        });        

        return response()->json(['resultados' => $resultados]);
    }

    
}
