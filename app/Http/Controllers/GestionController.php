<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\JobAQLTemporal;
use App\Models\JobAQLHistorial;
use App\Models\ModuloEstiloTemporal;
use Illuminate\Http\Request; 

class GestionController extends Controller
{
    public function agregarAqlProceso()
    {
        $pageSlug ='';
        // Obtener estilos y clientes únicos
        $estilos = JobAQLHistorial::select('itemid', 'customername')
            ->distinct()
            ->orderBy('itemid') // Ordenar por itemid
            ->get();

        return view('gestion.agregarAqlProceso', compact('pageSlug', 'estilos'));
    }

    public function buscarAql(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        // Valida el término de búsqueda
        if (!$searchTerm) {
            return response()->json([
                'status' => 'error',
                'message' => 'El término de búsqueda es obligatorio.',
            ], 400);
        }

        // Busca en el modelo JobAQLHistorial
        $results = JobAQLHistorial::where('prodid', 'LIKE', "%$searchTerm%")->get();

        return response()->json([
            'status' => 'success',
            'data' => $results,
        ]);
    }

    public function guardarAql(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se proporcionaron registros para guardar.',
            ], 400);
        }

        // Elimina registros con más de 15 días basándose en `created_at`
        $fechaLimite = now()->subDays(15); // Fecha límite: 15 días antes de hoy
        JobAQLTemporal::where('created_at', '<', $fechaLimite)->delete();
        //Log::info("Dato: " . json_encode($fechaLimite));
        // Obtiene los registros completos desde JobAQLHistorial
        $records = JobAQLHistorial::whereIn('id', $ids)->get();

        if ($records->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron registros con los IDs proporcionados.',
            ], 404);
        }

        // Procesa los registros y los guarda en JobAQLTemporal
        foreach ($records as $record) {
            // Verifica si el registro ya existe basado en `prodpackticketid`
            $exists = JobAQLTemporal::where('prodpackticketid', $record->prodpackticketid)->exists();

            if (!$exists) {
                JobAQLTemporal::create([
                    'payrolldate' => now(),
                    'prodpackticketid' => $record->prodpackticketid,
                    'qty' => $record->qty,
                    'moduleid' => $record->moduleid,
                    'prodid' => $record->prodid,
                    'itemid' => $record->itemid,
                    'colorname' => $record->colorname,
                    'customername' => $record->customername,
                    'inventcolorid' => $record->inventcolorid,
                    'inventsizeid' => $record->inventsizeid,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Registros guardados correctamente.',
        ]);
    }

    public function guardarModuloEstilo(Request $request)
    {
        $items = $request->input('items');
        
        // Elimina registros con más de 15 días basándose en `created_at`
        $fechaLimite = now()->subDays(15); // Fecha límite: 15 días antes de hoy
        JobAQLTemporal::where('created_at', '<', $fechaLimite)->delete();
        foreach ($items as $item) {
            // Evitar duplicados basados en itemid
            $exists = ModuloEstiloTemporal::where('itemid', $item['itemid'])->exists();

            if (!$exists) {
                ModuloEstiloTemporal::create([
                    'itemid' => $item['itemid'],
                    'custname' => $item['customername'],
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Registros guardados correctamente.',
        ]);
    }

}
