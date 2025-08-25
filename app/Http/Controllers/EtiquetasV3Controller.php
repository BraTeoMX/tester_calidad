<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\DB; // Importa la clase DB
use Illuminate\Http\Request;
use App\Models\Cat_DefEtiquetas;
use App\Models\ReporteAuditoriaEtiqueta;
use App\Models\TpReporteAuditoriaEtiqueta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class EtiquetasV3Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Función para mostrar la vista principal
    public function index()
    {

        // Retorna la vista con los datos
        return view('etiquetas.index', [
            'title' => ''
        ]);
    }

    public function getRegistrosDelDiaEtiqueta()
    {
        $registros = ReporteAuditoriaEtiqueta::whereDate('created_at', Carbon::today())
            ->with('defectos')
            ->get()
            ->map(function ($registro) {
                return [
                    'id' => $registro->id,
                    'tipo' => $registro->tipo,
                    'orden' => $registro->orden,
                    'estilo' => $registro->estilo,
                    'color' => $registro->color,
                    'cantidad' => $registro->cantidad,
                    'muestreo' => $registro->muestreo,
                    'estatus' => $registro->estatus,
                    'isRechazado' => $registro->estatus === 'Rechazado',
                    'comentario' => $registro->comentario ?? 'N/A',
                    'defectos' => $registro->defectos->isNotEmpty()
                        ? $registro->defectos->map(fn($d) => "{$d->nombre} ({$d->cantidad})")->toArray()
                        : ['Sin defectos']
                ];
            });

        return response()->json(['success' => true, 'registros' => $registros]);
    }

    public function eliminarRegistro($id)
    {
        $registro = ReporteAuditoriaEtiqueta::find($id);

        if (!$registro) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.'], 404);
        }

        try {
            $registro->delete();
            return response()->json(['success' => true, 'message' => 'Registro eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el registro.']);
        }
    }


    public function procesarFormularioEtiquetaAjax(Request $request)
    {
        $tipoBusqueda = $request->input('tipoEtiqueta');
        $orden = $request->input('valorEtiqueta');

        $estilos = $this->obtenerEstilos($tipoBusqueda, $orden);

        if ($estilos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron estilos para la búsqueda.',
            ]);
        }

        return response()->json([
            'success' => true,
            'estilos' => $estilos,
            'tipoBusqueda' => $tipoBusqueda,
            'orden' => $orden,
        ]);
    }


    private function obtenerEstilos($tipoBusqueda, $orden)
    {
        // 1. Creamos una clave única para el caché
        $cacheKey = 'estilos.' . $tipoBusqueda . '.' . $orden;

        // 2. Usamos Cache::remember. El '10' es el número de minutos.
        $estilos = Cache::remember($cacheKey, 10, function () use ($tipoBusqueda, $orden) {
            // Toda tu lógica de consulta original va aquí dentro.
            // Solo se ejecutará si los datos no están en el caché.
            if ($tipoBusqueda === 'OC') {
                $campoBusqueda1 = 'ordenCompra';
                $campoBusqueda2 = 'OrdenCompra';
                $conexion1 = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
                $conexion2 = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                return $conexion1
                    ->where($campoBusqueda1, $orden)->select('Estilos')
                    ->union($conexion2->where($campoBusqueda2, $orden)->select('Estilos'))
                    ->distinct()->get();
            } elseif ($tipoBusqueda === 'OP') {
                $campoBusqueda = 'OP';
                $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                return $conexion->where($campoBusqueda, $orden)->select('Estilos')->distinct()->get();
            } elseif ($tipoBusqueda === 'PO') {
                $campoBusqueda1 = 'CPO';
                $campoBusqueda2 = 'CPO';
                $conexion1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                $conexion2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View');
                return $conexion1
                    ->where($campoBusqueda1, $orden)->select('Estilos')
                    ->union($conexion2->where($campoBusqueda2, $orden)->select('Estilos'))
                    ->distinct()->get();
            } elseif ($tipoBusqueda === 'OV') {
                $campoBusqueda = 'SALESID';
                $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                return $conexion->where($campoBusqueda, $orden)->select('Estilos')->distinct()->get();
            }
            // En caso de que no entre en ningún if, devolvemos una colección vacía
            return collect();
        });

        return $estilos;
    }


    /**
     * AJAX: Retorna las tallas para un Estilo específico,
     *       en base a la lógica y el tipo de búsqueda.
     */
    public function ajaxGetTallas(Request $request)
    {
        $tipoBusqueda = $request->input('tipoBusqueda');
        $orden = $request->input('orden');
        $estilo = $request->input('estilo');

        // 1. Clave única para las tallas
        $cacheKey = 'tallas.' . $tipoBusqueda . '.' . $orden . '.' . $estilo;

        // 2. Obtenemos las tallas desde el caché o ejecutando la consulta
        $tallas = Cache::remember($cacheKey, 10, function () use ($tipoBusqueda, $orden, $estilo) {
            if ($tipoBusqueda === 'OC') {
                // ... (lógica para OC sin cambios)
                $campoBusqueda2 = 'OrdenCompra';
                $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
                $selectCampos = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];
                $datos = $modelo->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->select($selectCampos)->get();
                if ($datos->isEmpty()) {
                    $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                    $datos = $modelo->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->select($selectCampos)->get();
                }
                return $datos->pluck('Talla')->unique()->values(); // Devolvemos el dato a cachear

            } elseif (in_array($tipoBusqueda, ['PO', 'OV'])) {
                // ... (lógica para PO y OV sin cambios)
                $campoBusqueda2 = ['PO' => 'CPO', 'OV' => 'SALESID'][$tipoBusqueda];
                $modelo1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                $modelo2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View');
                $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
                $datosTabla1 = $modelo1->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->select($selectCampos)->get();
                $datosTabla2 = collect();
                if ($tipoBusqueda === 'PO' && $datosTabla1->isEmpty()) {
                    $datosTabla2 = $modelo2->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->select($selectCampos)->get();
                }
                $datos = $datosTabla1->merge($datosTabla2);
                return $datos->pluck('sizename')->map(fn($size) => (string) $size)->filter()->unique()->values(); // Devolvemos el dato a cachear

            } else { // Caso 'OP'
                $campoBusqueda2 = 'OP';
                $modelo = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
                $datos = $modelo->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->select($selectCampos)->get();
                return $datos->pluck('sizename')->unique()->values(); // Devolvemos el dato a cachear
            }
        });

        // 3. La respuesta JSON se construye siempre, usando los datos del caché o los recién consultados
        return response()->json([
            'success' => true,
            'tallas'  => $tallas
        ]);
    }

    /**
     * AJAX: Dado un Estilo + Talla específicos, retorna la Cantidad y Tamaño de muestra
     *       para ese registro (o registros).
     */
    public function ajaxGetData(Request $request)
    {
        $tipoBusqueda = $request->input('tipoBusqueda');
        $orden = $request->input('orden');
        $estilo = $request->input('estilo');
        $talla = $request->input('talla');

        // 1. Clave única para los datos crudos de la BD externa
        $cacheKey = 'data_externa.' . $tipoBusqueda . '.' . $orden . '.' . $estilo . '.' . $talla;

        // 2. Obtenemos los datos de la BD externa (desde caché o consulta real)
        $datos = Cache::remember($cacheKey, 10, function () use ($tipoBusqueda, $orden, $estilo, $talla) {
            if ($tipoBusqueda === 'OC') {
                // ... (lógica de consulta para OC)
                $campoBusqueda2 = 'OrdenCompra';
                $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
                $selectCampos = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];
                $datosQuery = $modelo->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->where('Talla', $talla)->select($selectCampos)->get();
                if ($datosQuery->isEmpty()) {
                    $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                    $datosQuery = $modelo->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->where('Talla', $talla)->select($selectCampos)->get();
                }
                return $datosQuery;
            } else {
                // ... (lógica de consulta para OP, PO, OV)
                $campoBusqueda2 = ['OP' => 'OP', 'PO' => 'CPO', 'OV' => 'SALESID'][$tipoBusqueda];
                $modelo1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
                $modelo2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View');
                $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
                $datosTabla1 = $modelo1->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->where('sizename', $talla)->select($selectCampos)->get();
                $datosTabla2 = collect();
                if (in_array($tipoBusqueda, ['PO', 'OV'])) {
                    $datosTabla2 = $modelo2->where('Estilos', $estilo)->where($campoBusqueda2, $orden)->where('sizename', $talla)->select($selectCampos)->get();
                }
                return $datosTabla1->merge($datosTabla2);
            }
        });

        // --- ESTA PARTE SE EJECUTA SIEMPRE, CON DATOS FRESCOS O DE CACHÉ ---
        // 3. Filtrar duplicados contra registros locales (esto NO se cachea)
        $registrosExistentes = ReporteAuditoriaEtiqueta::where('orden', $orden)
            ->where('estilo', $estilo)
            ->where('talla', $talla)
            ->get(['orden', 'estilo', 'color', 'talla']) // Más eficiente
            ->map(fn($item) => implode('-', [$item->orden, $item->estilo, $item->talla, $item->color]))
            ->flip(); // Usar un mapa para búsquedas O(1)

        $datosFiltrados = $datos->filter(function ($dato) use ($registrosExistentes) {
            $color = $dato->Color ?? $dato->inventcolorid ?? 'N/A';
            $tallaReal = $dato->Talla ?? $dato->sizename ?? '';
            $ordenValor = $dato->OrdenCompra ?? $dato->CPO ?? $dato->SALESID ?? $dato->OP ?? '';

            $combinacion = implode('-', [$ordenValor, $dato->Estilos, $tallaReal, $color]);
            return !isset($registrosExistentes[$combinacion]);
        });

        // 4. Procesar el primer resultado disponible
        $respuesta = null;
        if ($datosFiltrados->isNotEmpty()) {
            $primer = $datosFiltrados->first();
            $campoCantidad = isset($primer->Cantidad) ? 'Cantidad' : 'qty';
            $cantidad = $primer->$campoCantidad;
            $respuesta = [
                'cantidad'       => $cantidad,
                'tamaño_muestra' => $this->calcularTamanoMuestra($cantidad),
                'color'          => $primer->Color ?? $primer->inventcolorid ?? 'N/A',
            ];
        } else {
            $respuesta = ['cantidad' => 0, 'tamaño_muestra' => '', 'color' => 'N/A'];
        }

        return response()->json(['success' => true, 'data' => $respuesta]);
    }

    /**
     * Función helper para calcular el tamaño de muestra con base en la cantidad.
     */
    private function calcularTamanoMuestra($cantidad)
    {
        if ($cantidad >= 2 && $cantidad <= 8) {
            return '2';
        } elseif ($cantidad >= 9 && $cantidad <= 15) {
            return '3';
        } elseif ($cantidad >= 16 && $cantidad <= 25) {
            return '5';
        } elseif ($cantidad >= 26 && $cantidad <= 50) {
            return '8';
        } elseif ($cantidad >= 51 && $cantidad <= 90) {
            return '13';
        } elseif ($cantidad >= 91 && $cantidad <= 150) {
            return '20';
        } elseif ($cantidad >= 151 && $cantidad <= 280) {
            return '32';
        } elseif ($cantidad >= 281 && $cantidad <= 500) {
            return '50';
        } elseif ($cantidad >= 501 && $cantidad <= 1200) {
            return '80';
        } elseif ($cantidad >= 1201 && $cantidad <= 3200) {
            return '125';
        } elseif ($cantidad >= 3201 && $cantidad <= 10000) {
            return '200';
        } elseif ($cantidad >= 10001 && $cantidad <= 35000) {
            return '315';
        } elseif ($cantidad >= 35001 && $cantidad <= 150000) {
            return '500';
        } elseif ($cantidad >= 150001 && $cantidad <= 5000000) {
            return '800';
        } elseif ($cantidad > 5000000) {
            return '2000';
        }

        return '';
    }

    public function obtenerDefectosEtiquetas()
    {
        $tiposDefectos = Cat_DefEtiquetas::where('estatus', 1)
            ->get();

        return response()->json($tiposDefectos);
    }

    public function guardarDefectoEtiqueta(Request $request)
    {
        // Validar que el campo Defectos no esté vacío
        $request->validate([
            'Defectos' => 'required|string|max:255',
        ]);

        // Crear un nuevo defecto
        $defecto = Cat_DefEtiquetas::create([
            'Defectos' => $request->input('Defectos'),
            'estatus'  => 1 // Asumiendo que "1" es activo
        ]);

        // Devolver respuesta JSON
        return response()->json([
            'success' => true,
            'id'      => $defecto->id
        ]);
    }

    public function guardarAuditoriaEtiqueta(Request $request)
    {
        Log::debug('Datos recibidos en guardarAuditoriaEtiqueta:', $request->all());

        $nombreAuditor = Auth::user()->name;

        $reporte = new ReporteAuditoriaEtiqueta();
        $reporte->nombre_auditor    = $nombreAuditor;
        $reporte->tipo              = $request->tipoEtiqueta;
        $reporte->orden             = $request->valorEtiqueta;
        $reporte->estilo            = $request->estilo;
        $reporte->color             = $request->color;
        $reporte->talla             = $request->talla;
        $reporte->cantidad          = $request->cantidad;
        $reporte->muestreo          = $request->muestreo;
        $reporte->estatus           = $request->accion_correctiva;
        $reporte->comentario        = $request->comentarios;
        $reporte->registro_manual   = $request->registro_manual;

        // Ahora decides el valor de 'rechazo'
        if ($request->accion_correctiva === 'Rechazado') {
            $reporte->rechazo = 1;
        } else {
            $reporte->rechazo = null;
        }

        // Guardas
        $reporte->save();

        // Guardar defectos asociados
        if ($request->has('defectos')) {
            foreach ($request->defectos as $defecto) {
                TpReporteAuditoriaEtiqueta::create([
                    'id_reporte_auditoria_etiquetas' => $reporte->id,
                    'nombre'  => $defecto['nombre'],
                    'cantidad' => $defecto['cantidad'],
                ]);
            }
        }

        // 2. Volvemos a obtener la lista de estilos usando la misma lógica
        $tipoBusqueda = $request->tipoEtiqueta;
        $orden = $request->valorEtiqueta;
        $estilos = $this->obtenerEstilos($tipoBusqueda, $orden);

        // 3. Redirigimos a la misma vista y llenamos la sesión con los datos.
        return response()->json([
            'success' => true,
            'message' => 'Auditoría guardada correctamente.',
        ]);
    }


    public function updateStatus(Request $request, $id)
    {
        // Buscar registro
        $registro = ReporteAuditoriaEtiqueta::findOrFail($id);
        // Actualizar estatus
        $registro->estatus = $request->estatus;
        // Registramos la fecha/hora del cambio
        // (Suponiendo que agregaste la columna `fecha_cambio_estatus` en tu tabla)
        $registro->fecha_cambio_estatus = \Carbon\Carbon::now();

        $registro->save();

        return response()->json(['success' => true]);
    }
}
