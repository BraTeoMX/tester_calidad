<?php

namespace App\Http\Controllers;

use App\Models\DatosAuditoriaEtiquetas as ModelsDatosAuditoriaEtiquetas;
use App\Models\Cat_DefEtiquetas;
use App\Models\ReporteAuditoriaEtiqueta;
use Illuminate\Http\Request;
use App\Models\DatosAXOV;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\Input;

class DatosAuditoriaEtiquetas extends Controller
{
    public function auditoriaEtiquetas()
    {
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        return view('formulariosCalidad.auditoriaEtiquetas', compact('mesesEnEspanol'));
    }
    public function buscarEstilos(Request $request)
    {
        $orden = $request->input('orden');
        $tipoBusqueda = $request->input('tipoBusqueda');
        //Log::info('Datos ingresados: ' . $orden . ',' . $tipoBusqueda);

        $conexion = null;
        $campoBusqueda = null;

        // Definir la conexión y el campo de búsqueda según el tipo
        if ($tipoBusqueda === 'OC') {
            $campoBusqueda = 'ordenCompra';
            $conexion = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
        } else if ($tipoBusqueda === 'OP') {
            $campoBusqueda = 'OP';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'PO') {
            $campoBusqueda = 'CPO';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'OV') {
            $campoBusqueda = 'SALESID';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        }

        // Ejecutar la primera consulta
        $estilos = $conexion
            ->where($campoBusqueda, $orden)
            ->select('Estilos')
            ->distinct()
            ->get();

        // Si la búsqueda principal no encuentra registros y es tipo OC, hacer una búsqueda secundaria
        if ($tipoBusqueda === 'OC' && $estilos->isEmpty()) {
            Log::info('No se encontraron resultados en EtiquetasOC_View, buscando en EtiquetasOC2_View...');
            $campoBusqueda2 = 'OrdenCompra';
            $conexion2 = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');

            $estilos = $conexion2
                ->where($campoBusqueda2, $orden)
                ->select('Estilos')
                ->distinct()
                ->get();
        }

        Log::info('Datos del select buscar estilos: ' . $estilos);
        $status = [];

        // Iterar sobre los estilos y obtener el estado de la auditoría
        foreach ($estilos as $key => $estilo) {
            $auditoriaEstado = $this->obtenerEstadoAuditoria($orden, $tipoBusqueda, $estilo->Estilos);
            $status[$key] = $auditoriaEstado;
        }

        return response()->json(['estilos' => $estilos, 'status' => $status]);
    }


    private function obtenerEstadoAuditoria($tipoBusqueda, $orden, $estilo)
    {
        try {
            // Inicializar contadores
            $totalEtiquetas = 0;
            $totalAXOV = 0;
            $totalRevision = 0;

            // Determinar conexión y campo de búsqueda según el tipo
            if ($tipoBusqueda === 'OC') {
                // Buscar estilos de la orden en la tabla EtiquetasOC_View
                $totalEtiquetas = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View')
                    ->where('OrdenCompra', $orden)
                    ->where('Estilos', $estilo)
                    ->count();

                // Si no encuentra registros, buscar en la tabla EtiquetasOC2_View
                if ($totalEtiquetas) {
                    Log::info('detalles: No se encontraron resultados en EtiquetasOC_View, buscando en EtiquetasOC2_View...');
                    $totalEtiquetas = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View')
                        ->where('OrdenCompra', $orden)
                        ->where('Estilos', $estilo)
                        ->count();
                }
            } elseif (in_array($tipoBusqueda, ['OP', 'PO', 'OV'])) {
                // Buscar por OP, CPO o SALESID y contar registros
                $totalAXOV = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View')
                    ->where(function ($query) use ($orden) {
                        $query->where('OP', $orden)
                            ->orWhere('CPO', $orden)
                            ->orWhere('SALESID', $orden);
                    })
                    ->where('Estilos', $estilo)
                    ->count();
            }

            // Buscar en el modelo 'ReporteAuditoriaEtiqueta' por la orden y el estilo y contar registros
            $totalRevision = ReporteAuditoriaEtiqueta::where('Orden', $orden)
                ->where('Estilos', $estilo)
                ->count();

            // Calcular la cantidad de registros pendientes
            $registrosPendientes = ($totalEtiquetas + $totalAXOV) - $totalRevision;

            // Si no hay registros pendientes, la auditoría no ha sido iniciada o está finalizada
            if ($registrosPendientes >= 0) {
                // Obtener los estados de 'ReporteAuditoriaEtiqueta' para determinar si está finalizada
                $estadosRevision = ReporteAuditoriaEtiqueta::where('Orden', $orden)
                    ->where('Estilos', $estilo)
                    ->select('Status')
                    ->get();

                // Verificar si todos los estados están finalizados
                $todosFinalizados = $estadosRevision->every(function ($estado) {
                    $estadosFinalizados = ['Aprobado', 'Aprobado Condicionalmente', 'Rechazado'];
                    return in_array($estado->Status, $estadosFinalizados);
                });
                $existeRevision = ReporteAuditoriaEtiqueta::where('Orden', $orden)
                    ->where('Estilos', $estilo)
                    ->exists();
                if ($todosFinalizados) {
                    return 'Auditoría Finalizada';
                } elseif (!$existeRevision) {
                    return 'No iniciada';
                }
            } else { // Si hay registros pendientes...

                // Inicializar variables para los estados
                $todosIniciados = false;
                $alMenosUnoEnProceso = false;
                $todosFinalizados = false;

                $estadosEnProceso = ['Guardado', 'Update', 'Iniciado', 'Aprobado', 'Aprobado Condicionalmente', 'Rechazado'];
                $estadosFinalizados = ['Aprobado', 'Aprobado Condicionalmente', 'Rechazado'];

                // Obtener los estados de 'ReporteAuditoriaEtiqueta' para determinar el estado actual
                $estadosRevision = ReporteAuditoriaEtiqueta::where('Orden', $orden)
                    ->where('Estilos', $estilo)
                    ->select('Status')
                    ->get();

                // Verificar si existen registros en 'ReporteAuditoriaEtiqueta'
                $existeRevision = ReporteAuditoriaEtiqueta::where('Orden', $orden)
                    ->where('Estilos', $estilo)
                    ->exists();

                // Evaluar los estados obtenidos
                foreach ($estadosRevision as $estado) {
                    $status = $estado->Status;

                    if ($status === 'Iniciado') {
                        $todosIniciados = true;
                    }

                    if (in_array($status, $estadosEnProceso)) {
                        $alMenosUnoEnProceso = true;
                    }

                    if (!in_array($status, $estadosFinalizados)) {
                        $todosFinalizados = false;
                    }
                }

                // Determinar el estado de la auditoría (manteniendo la lógica original)
                if (!$existeRevision) {
                    return 'No iniciada';
                } elseif ($todosFinalizados) {
                    return 'Auditoría Finalizada';
                } elseif ($alMenosUnoEnProceso) {
                    return 'En Proceso de auditoría';
                } elseif ($todosIniciados) {
                    return 'Auditoría Iniciada';
                }
            }
        } catch (\Exception $e) {
            // Manejar excepciones y errores con más detalles
            Log::error('Error en la obtención del estado de la auditoría: ' . $e->getMessage() . ' en línea ' . $e->getLine());
            return 'Error al obtener el estado de la auditoría';
        }
    }


    public function buscarDatosAuditoriaPorEstilo(Request $request)
    {
        $estilo = $request->input('estilo');
        $orden = $request->input('orden');
        $tipoBusqueda = $request->input('tipoBusqueda');

        // Definir el campo de búsqueda y el modelo según el tipo
        if ($tipoBusqueda == 'OC') {
            $campoBusqueda = 'OrdenCompra';
            $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
            $selectCampos = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];
            $campoCantidad = 'Cantidad';

            // Buscar datos relacionados con el estilo especificado y la orden de compra
            $datos = $modelo->where('Estilos', $estilo)
                ->where($campoBusqueda, $orden)
                ->select($selectCampos)
                ->get();

            // Si no encuentra resultados en la primera tabla, buscar en EtiquetasOC2_View
            if ($datos->isEmpty()) {
                Log::info('No se encontraron resultados en EtiquetasOC_View, buscando en EtiquetasOC2_View...');
                $campoBusqueda = 'OrdenCompra';
                $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                $selectCampos = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];

                $datos = $modelo->where('Estilos', $estilo)
                    ->where($campoBusqueda, $orden)
                    ->select($selectCampos)
                    ->get();
            }
        } else {
            // Mapeo de tipos de búsqueda a nombres de columna
            $campoBusqueda = [
                'OP' => 'OP',
                'PO' => 'CPO',
                'OV' => 'SALESID',
            ][$tipoBusqueda];

            $modelo = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $selectCampos = [$campoBusqueda, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
            $campoCantidad = 'qty';

            // Buscar datos relacionados con el estilo especificado y la orden de compra
            $datos = $modelo->where('Estilos', $estilo)
                ->where($campoBusqueda, $orden)
                ->select($selectCampos)
                ->get();
        }

        // Log para verificar los datos obtenidos
        Log::info('Datos obtenidos: ' . json_encode($datos));

        // Obtener TODOS los registros existentes en ReporteAuditoriaEtiqueta
        $registrosExistentes = ReporteAuditoriaEtiqueta::all();

        // Convertir los registros existentes a un array para facilitar la búsqueda
        $registrosExistentesArray = $registrosExistentes->map(function ($item) {
            return [
                'Orden' => $item->Orden,
                'Estilos' => $item->Estilos,
                'Color' => $item->Color,
                'Talla' => $item->Talla,
            ];
        })->toArray();

        // Filtrar los datos obtenidos de las conexiones SQL
        $datosFiltrados = $datos->filter(function ($dato) use ($registrosExistentesArray, $tipoBusqueda, $campoBusqueda) {
            // Ajustar los nombres de las propiedades según el tipo de búsqueda
            $color = ($tipoBusqueda == 'OC') ? $dato->Color : $dato->inventcolorid;
            $talla = ($tipoBusqueda == 'OC') ? $dato->Talla : $dato->sizename;

            // Obtener el valor del campo de búsqueda (OrdenCompra, OP, CPO o SALESID)
            $ordenValor = $dato->$campoBusqueda;

            // Verificar si la combinación ya existe en ReporteAuditoriaEtiqueta
            $combinacion = [
                'Orden' => $ordenValor,
                'Estilos' => $dato->Estilos,
                'color' => $color,
                'talla' => $talla,
            ];

            return !in_array($combinacion, $registrosExistentesArray);
        });

        // Iterar sobre los datos filtrados y determinar el tamaño de muestra
        foreach ($datosFiltrados as $dato) {
            $cantidad = $dato->$campoCantidad;

            $tamaño_muestra = '';

            // Determinar el rango de cantidad y asignar el tamaño de muestra correspondiente
            if ($cantidad >= 2 && $cantidad <= 8) {
                $tamaño_muestra = '2';
            } elseif ($cantidad >= 9 && $cantidad <= 15) {
                $tamaño_muestra = '3';
            } elseif ($cantidad >= 16 && $cantidad <= 25) {
                $tamaño_muestra = '5';
            } elseif ($cantidad >= 26 && $cantidad <= 50) {
                $tamaño_muestra = '8';
            } elseif ($cantidad >= 51 && $cantidad <= 90) {
                $tamaño_muestra = '13';
            } elseif ($cantidad >= 91 && $cantidad <= 150) {
                $tamaño_muestra = '20';
            } elseif ($cantidad >= 151 && $cantidad <= 280) {
                $tamaño_muestra = '32';
            } elseif ($cantidad >= 281 && $cantidad <= 500) {
                $tamaño_muestra = '50';
            } elseif ($cantidad >= 501 && $cantidad <= 1200) {
                $tamaño_muestra = '80';
            } elseif ($cantidad >= 1201 && $cantidad <= 3200) {
                $tamaño_muestra = '125';
            } elseif ($cantidad >= 3201 && $cantidad <= 10000) {
                $tamaño_muestra = '200';
            } elseif ($cantidad >= 10001 && $cantidad <= 35000) {
                $tamaño_muestra = '315';
            } elseif ($cantidad >= 35001 && $cantidad <= 150000) {
                $tamaño_muestra = '500';
            } elseif ($cantidad >= 150001 && $cantidad <= 5000000) {
                $tamaño_muestra = '800';
            } elseif ($cantidad > 5000000) {
                $tamaño_muestra = '2000';
            }

            // Asignar el tamaño de muestra al resultado
            $dato->tamaño_muestra = $tamaño_muestra;
        }

        return response()->json($datosFiltrados);
    }

    public function obtenerTiposDefectos()
    {
        $tiposDefectos = Cat_DefEtiquetas::all();

        return response()->json($tiposDefectos);
    }
    public function actualizarStatus(Request $request)
    {
        try {
            // Obtener los datos enviados desde el frontend
            $datos = $request->input('datos');
            $status = $request->input('status');

            // Registrar los datos iniciales para depuración
            Log::info('Datos recibidos: ' . json_encode($datos));
            Log::info('Status recibido: ' . $status);

            if (!is_array($datos) || empty($datos)) {
                throw new \Exception('Datos inválidos, se esperaba un array no vacío.');
            }

            foreach ($datos as $dato) {
                // Log detallado de cada dato individual
                Log::info('Procesando dato: ' . json_encode($dato));

                // Validar la existencia de campos necesarios
                if (!isset($dato['tipoBusqueda'])) {
                    throw new \Exception('Datos incompletos: falta tipoBusqueda.');
                }

                // Convertir el array tipoDefecto en string si es necesario
                if (is_array($dato['tipoDefecto'])) {
                    $dato['tipoDefecto'] = implode(', ', $dato['tipoDefecto']);
                }
                Log::info('Tipo Defecto después de conversión: ' . $dato['tipoDefecto']);

                // Convertir el array defectos en una cadena separada por comas
                if (is_array($dato['defectos'])) {
                    // Suponiendo que cada elemento es un objeto con una clave "cantidad"
                    $defectos = array_map(function ($defecto) {
                        return $defecto['cantidad']; // Extraer solo la cantidad
                    }, $dato['defectos']);

                    $dato['defectos'] = implode(', ', $defectos); // Convertir array en string separado por comas
                }
                Log::info('Defectos después de conversión: ' . $dato['defectos']);

                // Log de los datos antes de intentar guardar
                Log::info('Datos a guardar o actualizar: ' . json_encode([
                    'Orden' => $dato['orden'] ?? 'N/A',
                    'Estilos' => $dato['estilo'] ?? 'N/A',
                    'Cantidad' => $dato['cantidad'] ?? 'N/A',
                    'Muestreo' => $dato['muestreo'] ?? 'N/A',
                    'Defectos' => $dato['defectos'] ?? '', // Ahora es una cadena separada por comas
                    'Tipo_Defectos' => $dato['tipoDefecto'] ?? 'N/A',
                    'Talla' => $dato['talla'] ?? 'N/A',
                    'Color' => $dato['color'] ?? 'N/A',
                    'Status' => $status
                ]));

                // Buscar o crear registro en ReporteAuditoriaEtiqueta
                $reporte = ReporteAuditoriaEtiqueta::updateOrCreate(
                    [
                        'Orden' => $dato['orden'] ?? 'N/A',
                        'Estilos' => $dato['estilo'] ?? 'N/A',
                        'Cantidad' => $dato['cantidad'] ?? 'N/A',
                        'Muestreo' => $dato['muestreo'] ?? 'N/A',
                        'Defectos' => $dato['defectos'] ?? '', // Guardar como cadena separada por comas
                        'Tipo_Defectos' => $dato['tipoDefecto'] ?? 'N/A',
                        'Talla' => $dato['talla'] ?? 'N/A',
                        'Color' => $dato['color'] ?? 'N/A',
                        'Status' => $status
                    ]
                );
                Log::info('Reporte guardado/actualizado con ID: ' . $reporte->id);
            }

            // Retornar una respuesta JSON indicando el éxito
            return response()->json(['mensaje' => 'Los datos han sido actualizados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar los datos: ' . $e->getMessage() . ' en la línea ' . $e->getLine());
            // Retornar una respuesta JSON con el mensaje de error
            return response()->json(['error' => 'Error al actualizar los datos: ' . $e->getMessage()], 500);
        }
    }
    public function obtenerDatosInventario(Request $request)
    {
        // Obtén los parámetros enviados desde el frontend
        $orden = $request->input('orden');
        $estilo = $request->input('estilo');
        $NewEstilos = $request->input('nuevosEstilos');

        // Log de los datos recibidos
        Log::info("Datos extendidos - Orden: " . $orden . " - Estilo: " . $estilo . " - Nuevos Estilos: " . $NewEstilos);

        // Verifica que se reciban todos los parámetros
        if (!$orden || !$estilo || !$NewEstilos) {
            return response()->json(['error' => 'Parámetros inválidos'], 400);
        }

        // Si NewEstilos contiene múltiples valores separados por coma, conviértelos en un array
        $NewEstilosArray = explode(',', $NewEstilos);

        // Consulta a SQL Server
        $placeholders = implode(',', array_fill(0, count($NewEstilosArray), '?'));
        $query = "
        SELECT
            PRD.PRODID,
            INV.INVENTREFID,
            INV.ITEMID,
            PRO.ITEMID AS ITEMIDII,
            INV.ITEMNAME,
            CASE
                WHEN CHARINDEX('-', PRD.BOMID) > 0 THEN SUBSTRING(PRD.BOMID, 1, CHARINDEX('-', PRD.BOMID) - 1)
                ELSE PRD.BOMID
            END AS OV,
            CASE
                WHEN CHARINDEX('-', PRD.BOMID) > 0 THEN SUBSTRING(PRD.BOMID, CHARINDEX('-', PRD.BOMID) + 1, LEN(PRD.BOMID))
                ELSE ''
            END AS LT,
            BACK.INVENTSIZEID,
            BACK.INVENTCOLORID,
            BACK.REQUESTQTY,
            YEAR(INV.CREATEDDATETIME) AS YEAR
        FROM
            [INVENTQUALITYORDERTABLE] INV
        INNER JOIN [PRODBOM] PRD ON INV.ITEMID = PRD.ITEMID
        INNER JOIN [PRODTABLE] PRO ON PRD.PRODID = PRO.PRODID
        INNER JOIN [BACKLOGTABLE_AT] BACK ON
            (CASE
                WHEN CHARINDEX('-', PRD.BOMID) > 0 THEN SUBSTRING(PRD.BOMID, 1, CHARINDEX('-', PRD.BOMID) - 1)
                ELSE PRD.BOMID
            END) = BACK.SALESID
        WHERE
            INV.CREATEDDATETIME >= DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) - 1, 0)
            AND INV.CREATEDDATETIME < DATEADD(MONTH, DATEDIFF(MONTH, 0, GETDATE()) + 1, 0)
            AND INV.INVENTREFID != ''
            AND (CASE
                    WHEN CHARINDEX('-', PRD.BOMID) > 0 THEN SUBSTRING(PRD.BOMID, 1, CHARINDEX('-', PRD.BOMID) - 1)
                    ELSE PRD.BOMID
                END) != ''
            AND (CASE
                    WHEN CHARINDEX('-', PRD.BOMID) > 0 THEN SUBSTRING(PRD.BOMID, CHARINDEX('-', PRD.BOMID) + 1, LEN(PRD.BOMID))
                    ELSE ''
                END) != ''
            AND BACK.REQUESTQTY != 0.0000000000000000
            AND PRD.PRODID = ?
            AND PRO.ITEMID = ?
            AND INV.ITEMID IN ($placeholders)
        ORDER BY INV.ITEMID DESC;
        ";

        $params = array_merge([$orden, $estilo], $NewEstilosArray);

        // Ejecuta la consulta
        $resultados = DB::connection('sqlsrv')->select($query, $params);

        // Determinar el tamaño de la muestra para cada resultado
        foreach ($resultados as &$resultado) {
            $cantidad = $resultado->REQUESTQTY;
            if ($cantidad >= 2 && $cantidad <= 8) {
                $resultado->tamaño_muestra = '2';
            } elseif ($cantidad >= 9 && $cantidad <= 15) {
                $resultado->tamaño_muestra = '3';
            } elseif ($cantidad >= 16 && $cantidad <= 25) {
                $resultado->tamaño_muestra = '5';
            } elseif ($cantidad >= 26 && $cantidad <= 50) {
                $resultado->tamaño_muestra = '8';
            } elseif ($cantidad >= 51 && $cantidad <= 90) {
                $resultado->tamaño_muestra = '13';
            } elseif ($cantidad >= 91 && $cantidad <= 150) {
                $resultado->tamaño_muestra = '20';
            } elseif ($cantidad >= 151 && $cantidad <= 280) {
                $resultado->tamaño_muestra = '32';
            } elseif ($cantidad >= 281 && $cantidad <= 500) {
                $resultado->tamaño_muestra = '50';
            } elseif ($cantidad >= 501 && $cantidad <= 1200) {
                $resultado->tamaño_muestra = '80';
            } elseif ($cantidad >= 1201 && $cantidad <= 3200) {
                $resultado->tamaño_muestra = '125';
            } elseif ($cantidad >= 3201 && $cantidad <= 10000) {
                $resultado->tamaño_muestra = '200';
            } elseif ($cantidad >= 10001 && $cantidad <= 35000) {
                $resultado->tamaño_muestra = '315';
            } elseif ($cantidad >= 35001 && $cantidad <= 150000) {
                $resultado->tamaño_muestra = '500';
            } elseif ($cantidad >= 150001 && $cantidad <= 5000000) {
                $resultado->tamaño_muestra = '800';
            } elseif ($cantidad > 5000000) {
                $resultado->tamaño_muestra = '2000';
            }
        }

        // Log de los resultados de la consulta
        Log::info("Datos extendidos del select: " . print_r($resultados, true));

        // Retornamos los resultados como JSON
        return response()->json($resultados);
    }
}
