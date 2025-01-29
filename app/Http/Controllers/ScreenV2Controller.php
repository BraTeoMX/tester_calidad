<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccionCorrectScreen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DatosAX;
use Illuminate\Support\Facades\Auth;
use App\Models\OpcionesDefectosScreen;
use App\Models\InspeccionEstampadoDHorno;
use App\Models\Tecnicos;
use App\Models\Tipo_Fibra;
use App\Models\Tipo_Tecnica;
use App\Models\JobAQLHistorial;

class ScreenV2Controller extends Controller
{
    public function inspeccionEstampadoHorno(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.inspeccionEstampadoHorno', compact('mesesEnEspanol'));
    }
    
    public function searchBultos(Request $request)
    {
        $term = $request->get('q', '');

        $results = JobAQLHistorial::where('prodpackticketid', 'LIKE', '%' . $term)
                    ->limit(100)
                    ->get();

        $formattedResults = $results->map(function ($result) {
            return [
                'id' => $result->id,
                'text' => $result->prodpackticketid
            ];
        });

        return response()->json($formattedResults);
    }

    public function getBultoDetails($id)
    {
        $bulto = JobAQLHistorial::find($id);

        if (!$bulto) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        return response()->json([
            'bulto' => $bulto->prodpackticketid,
            'op' => $bulto->prodid,
            'cliente' => $bulto->customername,
            'estilo' => $bulto->itemid,
            'color' => $bulto->inventcolorid,
            'cantidad' => $bulto->qty,
        ]);
    }

}
