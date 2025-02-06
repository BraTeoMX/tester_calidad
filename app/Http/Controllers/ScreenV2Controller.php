<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccionCorrectScreen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\OpcionesDefectosScreen;
use App\Models\Tecnicos;
use App\Models\Tipo_Fibra;
use App\Models\Tipo_Tecnica;
use App\Models\JobAQLHistorial;
use App\Models\inspeccion_horno;

class ScreenV2Controller extends Controller
{
    public function inspeccionEstampadoHorno(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.inspeccionEstampadoHorno', compact('mesesEnEspanol'));
    }
    
    /**
     * Busca las OP (prodid) únicas según el término ingresado.
     * Retorna un JSON con formato para Select2.
     */
    public function searchOpsScreen(Request $request)
    {
        $term = $request->get('q', '');

        // Buscamos los prodid distintos que coincidan con el término
        $results = JobAQLHistorial::select('prodid')
                    ->where('prodid', 'LIKE', "%{$term}%")
                    ->distinct()
                    ->limit(100)
                    ->get();

        // Formateamos la respuesta para Select2
        $formattedResults = $results->map(function ($result) {
            return [
                'id'   => $result->prodid,  // El valor que usaremos para filtrar bultos
                'text' => $result->prodid,  // Lo que se mostrará en el dropdown
            ];
        });

        return response()->json($formattedResults);
    }

    /**
     * Busca los bultos (prodpackticketid) que correspondan a la OP recibida,
     * filtrando además por el término ingresado en el select2.
     */
    public function searchBultosByOpScreen(Request $request)
    {
        $op   = $request->get('op', '');
        $term = $request->get('q', ''); // para buscar por bulto

        // Buscamos los bultos relacionados a esa OP, filtrando por term en prodpackticketid
        $results = JobAQLHistorial::where('prodid', $op)
                    ->where('prodpackticketid', 'LIKE', "%{$term}%")
                    ->select('id', 'prodpackticketid')
                    ->distinct()
                    ->limit(100)
                    ->get();

        // Formateamos la respuesta para Select2
        $formattedResults = $results->map(function ($result) {
            return [
                'id'   => $result->id, // Este será el ID específico para luego obtener detalles
                'text' => $result->prodpackticketid,
            ];
        });

        return response()->json($formattedResults);
    }

    /**
     * Obtiene detalles de un bulto en base a su id (columna 'id').
     */
    public function getBultoDetailsScreen($id)
    {
        $bulto = JobAQLHistorial::find($id);

        if (!$bulto) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        return response()->json([
            'bulto'    => $bulto->prodpackticketid,
            'op'       => $bulto->prodid,
            'cliente'  => $bulto->customername,
            'estilo'   => $bulto->itemid,
            'color'    => $bulto->inventcolorid,
            'cantidad' => $bulto->qty,
        ]);
    }

}
