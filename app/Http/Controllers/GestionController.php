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
            // Validar que ambos campos, `itemid` y `moduleid`, no estén duplicados
            $exists = ModuloEstiloTemporal::where('itemid', $item['itemid'])
                ->where('moduleid', $item['modulo']) // Asegúrate de usar el nombre correcto de la columna en la base de datos
                ->exists();

            if (!$exists) {
                // Evaluar el valor inicial de `modulo`
                $prodpoolid = null;
                if (strpos($item['modulo'], '1') === 0) {
                    $prodpoolid = 'Intimark1';
                } elseif (strpos($item['modulo'], '2') === 0) {
                    $prodpoolid = 'Intimark2';
                }

                // Crear el registro con el nuevo campo
                ModuloEstiloTemporal::create([
                    'itemid' => $item['itemid'],
                    'custname' => $item['customername'],
                    'moduleid' => $item['modulo'], // Almacenar el valor del módulo
                    'prodpoolid' => $prodpoolid,   // Asignar el valor correspondiente
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Registros guardados correctamente.',
        ]);
    }

}
