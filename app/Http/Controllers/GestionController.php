<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\JobAQLTemporal;
use App\Models\JobAQLHistorial;
use App\Models\ModuloEstiloTemporal;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Validation\ValidationException;

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
        try {
            $validatedData = $request->validate([
                'searchTerm' => 'required|string|size:9'
            ]);
            $searchTerm = $validatedData['searchTerm'];

            // 🚀 Lógica de búsqueda en cascada
            
            // 💡 Paso 1: Intentar la búsqueda en la fuente principal (Modelo Eloquent)
            Log::info("Paso 1: Buscando '$searchTerm' en el modelo principal 'JobAQLHistorial'.");
            $results = JobAQLHistorial::where('prodid', $searchTerm)->get();

            // 💡 Paso 2: Verificar si se encontraron resultados en la fuente principal
            if ($results->isNotEmpty()) { // isNotEmpty() es lo opuesto a isEmpty()
                Log::info("¡Éxito! Se encontraron " . $results->count() . " registros en 'JobAQLHistorial'. Devolviendo resultados.");
                
                // Si encontramos resultados, los devolvemos y la función termina aquí.
                return response()->json([
                    'status' => 'success',
                    'source' => 'JobAQLHistorial', // Opcional: para saber de dónde vinieron los datos
                    'data' => $results,
                ]);
            }

            // 💡 Paso 3: Si no hubo resultados, proceder con la fuente secundaria (Fallback)
            Log::info("Paso 2 (Fallback): No se encontró en el modelo. Buscando '$searchTerm' en la vista 'OpBusqueda_View2'.");
            $results = DB::connection('sqlsrv')
                        ->table('OpBusqueda_View2')
                        ->where('prodid', '=', $searchTerm) // Usando '=' por claridad
                        ->get();
            
            if ($results->isNotEmpty()) {
                Log::info("¡Éxito! Se encontraron " . $results->count() . " registros en 'OpBusqueda_View2'.");
            } else {
                Log::info("Búsqueda finalizada. No se encontraron registros en ninguna fuente para '$searchTerm'.");
            }

            // 💡 Paso 4: Devolver el resultado de la segunda búsqueda (que puede tener datos o estar vacío)
            return response()->json([
                'status' => 'success',
                'source' => 'OpBusqueda_View2', // Opcional
                'data' => $results,
            ]);

        } catch (ValidationException $e) {
            Log::warning('Intento de búsqueda con datos inválidos: ' . json_encode($e->errors()));
            throw $e;

        } catch (\Exception $e) {
            Log::error('Error crítico en la búsqueda de AQL: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado en el servidor.',
            ], 500);
        }
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
