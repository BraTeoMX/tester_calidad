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
        $pageSlug = '';
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
            $source = 'JobAQLHistorial';
            Log::info("Paso 1: Buscando '$searchTerm' en el modelo principal '$source'.");
            $results = JobAQLHistorial::where('prodid', $searchTerm)->get();

            // Agregar mensaje informativo para búsqueda local
            $message = 'Búsqueda realizada en la base de datos local.';

            // 💡 Paso 2: Si no se encontraron resultados, usar la fuente secundaria optimizada
            if ($results->isEmpty()) {
                $source = 'SQLServer_View_OpBusqueda_3_View'; // Nueva fuente para claridad
                Log::info("Paso 2 (Fallback): No se encontró en el modelo. Buscando '$searchTerm' en la vista '$source'.");

                // Optimización: Usar consulta directa con WHERE aplicado en la tabla base para mejor rendimiento
                // Aplicamos el filtro en p.PRODID (la tabla base) antes de hacer JOINs y procesamientos
                $sql = "
                    SELECT TOP (1000)
                        inventdimid,
                        oprname,
                        payrolldate,
                        prodpackticketid,
                        prodticketid,
                        qty,
                        moduleid,
                        prodid,
                        itemid,
                        colorname,
                        CASE
                            WHEN customername = 'SKG Consulting Group Corp' THEN 'BELLEFIT INC'
                            WHEN customername = 'PDS' THEN 'Otro Cliente'
                            WHEN itemid = '1360' AND (customername IS NULL OR customername = '')
                                THEN 'Velrose Lingerie'
                            ELSE customername
                        END AS customername,
                        inventcolorid,
                        inventsizeid
                    FROM (
                        SELECT
                            p.INVENTDIMID,
                            p.OPRNAME,
                            DATEADD(SECOND, p.SCANNEDTIME, CAST(p.SCANNEDDATE AS DATETIME)) AS payrolldate,
                            p.PRODPACKTICKETID,
                            p.PRODTICKETID,
                            p.QTY,
                            p.MODULEID,
                            p.PRODID,
                            t.ITEMID,
                            b.COLORNAME,
                            CASE
                                WHEN b.CUSTOMERNAME = 'SKG Consulting Group Corp' THEN 'BELLEFIT INC'
                                WHEN b.CUSTOMERNAME = 'PDS' THEN 'Otro Cliente'
                                WHEN t.ITEMID = '1360'
                                     AND (b.CUSTOMERNAME IS NULL OR b.CUSTOMERNAME = '')
                                    THEN 'Velrose Lingerie'
                                ELSE b.CUSTOMERNAME
                            END AS CUSTOMERNAME,
                            b.INVENTCOLORID,
                            id.INVENTSIZEID,
                            ROW_NUMBER()
                                OVER (
                                    PARTITION BY p.PRODPACKTICKETID
                                    ORDER BY p.PAYROLLDATE DESC
                                ) AS rn
                        FROM
                            [AX_SERVER_LIVE].[INTIMARKDBAXPRODLIVE].[dbo].[PRODTICKETSTABLE_AT] AS p
                        INNER JOIN
                            [AX_SERVER_LIVE].[INTIMARKDBAXPRODLIVE].[dbo].[INVENTDIM] AS id
                            ON id.INVENTDIMID = p.INVENTDIMID
                        INNER JOIN
                            [AX_SERVER_LIVE].[INTIMARKDBAXPRODLIVE].[dbo].[PRODTABLE] AS t
                            ON t.PRODID = p.PRODID
                        INNER JOIN
                            [AX_SERVER_LIVE].[INTIMARKDBAXPRODLIVE].[dbo].[BACKLOGTABLE_AT] AS b
                            ON b.ITEMID        = t.ITEMID
                           AND b.SALESID       = t.INVENTREFID
                           AND b.INVENTCOLORID = id.INVENTCOLORID
                        WHERE
                            p.OPRID BETWEEN '200' AND '299'
                            AND p.PRODID = ?
                    ) AS subquery
                    WHERE rn = 1
                    ORDER BY PAYROLLDATE DESC;
                ";

                $queryResults = DB::connection('sqlsrv')
                    ->select($sql, [$searchTerm]);

                // DB::select devuelve un array estándar de PHP. Lo convertimos a una colección de Laravel
                // para mantener la consistencia con los resultados de Eloquent (usar isNotEmpty, count, etc.).
                // Transformamos la colección para añadir un ID secuencial a cada elemento
                $results = collect($queryResults)->map(function ($item, $key) {
                    // A cada item ($item), le añadimos una nueva propiedad 'id'.
                    // El valor será la posición del item ($key, que empieza en 0) más 1.
                    $item->id = $key + 1;

                    // Devolvemos el item ya modificado
                    return $item;
                });
            }

            // 💡 Paso 3: Registrar el resultado final y devolver la respuesta
            if ($results->isNotEmpty()) {
                Log::info("¡Éxito! Se encontraron " . $results->count() . " registros desde la fuente '$source' para '$searchTerm'.");
            } else {
                Log::info("Búsqueda finalizada. No se encontraron registros en ninguna fuente para '$searchTerm'.");
            }

            // 💡 Paso 4: Devolver el resultado de la búsqueda (que puede tener datos o estar vacío)
            $response = [
                'status' => 'success',
                'source' => $source,
                'data' => $results,
                'message' => $message,
            ];

            return response()->json($response);
        } catch (ValidationException $e) {
            Log::warning('Intento de búsqueda con datos inválidos: ' . json_encode($e->errors()));
            // Re-lanzar la excepción para que Laravel la maneje y devuelva una respuesta 422.
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error crítico en la búsqueda de AQL: ' . $e->getMessage(), ['exception' => $e]);
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
