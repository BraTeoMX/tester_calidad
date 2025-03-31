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

class EtiquetasV2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Función para mostrar la vista principal
    public function etiquetas_v2()
    {

        // Retorna la vista con los datos
        return view('etiquetas.etiquetas_v2', [
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
        // Definir la conexión y el campo de búsqueda según el tipo de búsqueda
        if ($tipoBusqueda === 'OC') {
            $campoBusqueda1 = 'ordenCompra';
            $campoBusqueda2 = 'OrdenCompra';

            $conexion1 = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
            $conexion2 = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');

            // Unificar los resultados de ambas vistas usando union
            $estilos = $conexion1
                ->where($campoBusqueda1, $orden)
                ->select('Estilos')
                ->union(
                    $conexion2
                        ->where($campoBusqueda2, $orden)
                        ->select('Estilos')
                )
                ->distinct()
                ->get();
        } elseif ($tipoBusqueda === 'OP') {
            $campoBusqueda = 'OP';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $estilos = $conexion
                ->where($campoBusqueda, $orden)
                ->select('Estilos')
                ->distinct()
                ->get();
        } elseif ($tipoBusqueda === 'PO') {
            $campoBusqueda1 = 'CPO';
            $campoBusqueda2 = 'CPO'; // Suponiendo que el campo se llama igual en ambas vistas
        
            $conexion1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $conexion2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View');
        
            // Unificar los resultados de ambas vistas usando union
            $estilos = $conexion1
                ->where($campoBusqueda1, $orden)
                ->select('Estilos')
                ->union(
                    $conexion2
                        ->where($campoBusqueda2, $orden)
                        ->select('Estilos')
                )
                ->distinct()
                ->get();
        } elseif ($tipoBusqueda === 'OV') {
            $campoBusqueda = 'SALESID';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $estilos = $conexion
                ->where($campoBusqueda, $orden)
                ->select('Estilos')
                ->distinct()
                ->get();
        }

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

        // Según tipo de búsqueda, definimos el modelo y campos
        if ($tipoBusqueda === 'OC') { 
            $campoBusqueda2 = 'OrdenCompra';
            $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
            $selectCampos = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];

            // Buscar datos para ese estilo+orden
            $datos = $modelo->where('Estilos', $estilo)
                ->where($campoBusqueda2, $orden)
                ->select($selectCampos)
                ->get();

            // Búsqueda secundaria en EtiquetasOC2_View si no hay resultados
            if ($datos->isEmpty()) {
                $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                $datos = $modelo->where('Estilos', $estilo)
                    ->where($campoBusqueda2, $orden)
                    ->select($selectCampos)
                    ->get();
            }

            // Extraemos la lista única de tallas
            $tallas = $datos->pluck('Talla')->unique()->values();

        } elseif (in_array($tipoBusqueda, ['PO', 'OV'])) { 
            $campoBusqueda2 = [
                'PO' => 'CPO',
                'OV' => 'SALESID',
            ][$tipoBusqueda];
        
            $modelo1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $modelo2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View'); // Segunda tabla solo para 'PO'
            $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
        
            // Log de entrada
            /* Log::info('Inicio búsqueda para tipoBusqueda', [
                'TipoBusqueda' => $tipoBusqueda,
                'Estilo' => $estilo,
                'Orden' => $orden,
                'CampoBusqueda' => $campoBusqueda2,
            ]); */
        
            // Buscar en la tabla principal
            $datosTabla1 = $modelo1
                ->where('Estilos', $estilo)
                ->where($campoBusqueda2, $orden)
                ->select($selectCampos)
                ->get();
        
            // Log resultados de la primera búsqueda
            /* Log::info('Resultados de la búsqueda en MaterializedBacklogTable_View', [
                'Resultados' => $datosTabla1->toArray(),
            ]); */
        
            // Si el tipo es 'PO' y no hay resultados, buscar en la segunda tabla
            $datosTabla2 = collect();
            if ($tipoBusqueda === 'PO' && $datosTabla1->isEmpty()) {
                //Log::info('No se encontraron resultados en MaterializedBacklogTable_View. Buscando en MaterializedBacklogTable2_View');
        
                $datosTabla2 = $modelo2
                    ->where('Estilos', $estilo)
                    ->where($campoBusqueda2, $orden)
                    ->select($selectCampos)
                    ->get();
        
                // Log resultados de la segunda búsqueda
                /* Log::info('Resultados de la búsqueda en MaterializedBacklogTable2_View', [
                    'Resultados' => $datosTabla2->toArray(),
                ]); */
            }
        
            // Combina los datos de ambas tablas (si es necesario)
            $datos = $datosTabla1->merge($datosTabla2);
        
            // Extraemos las tallas únicas como cadenas
            $tallas = $datos->pluck('sizename')
                ->map(fn($size) => (string) $size) // Convierte a cadena
                ->filter(fn($size) => !is_null($size) && trim($size) !== '') // Filtra valores nulos o vacíos
                ->groupBy(fn($size) => $size) // Agrupa por cadena exacta
                ->keys(); // Obtiene las claves únicas exactas como tallas
        
            // Log de las tallas obtenidas
            /*Log::info('Lista de tallas obtenidas', [
                'Tallas' => $tallas->toArray(),
            ]); */
        
            // Retornar las tallas como JSON
            return response()->json([
                'success' => true,
                'tallas'  => $tallas
            ]);
        } else {
            // Para OP, PO, OV
            $campoBusqueda2 = [
                'OP' => 'OP',
            ][$tipoBusqueda];

            $modelo = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];

            $datos = $modelo->where('Estilos', $estilo)
                ->where($campoBusqueda2, $orden)
                ->select($selectCampos)
                ->get();

            // Extraemos la lista única de tallas (en este caso, sizename)
            $tallas = $datos->pluck('sizename')->unique()->values();
        }

        // Retornamos la lista de tallas como JSON para que el front-end llene el segundo select
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
        $talla = $request->input('talla');  // o sizename, dependiendo del tipo

        if ($tipoBusqueda === 'OC') {
            $campoBusqueda2 = 'OrdenCompra';
            $modelo         = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
            $selectCampos   = ['OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];
            $campoCantidad  = 'Cantidad';

            // Buscar datos
            $datos = $modelo->where('Estilos', $estilo)
                            ->where($campoBusqueda2, $orden)
                            ->where('Talla', $talla)
                            ->select($selectCampos)
                            ->get();

            // Búsqueda secundaria si no encuentra en EtiquetasOC_View
            if ($datos->isEmpty()) {
                $modelo = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');
                $datos  = $modelo->where('Estilos', $estilo)
                                 ->where($campoBusqueda2, $orden)
                                 ->where('Talla', $talla)
                                 ->select($selectCampos)
                                 ->get();
            }
        } else {
            // OP, PO, OV
            $campoBusqueda2 = [
                'OP' => 'OP',
                'PO' => 'CPO',
                'OV' => 'SALESID',
            ][$tipoBusqueda];
        
            $modelo1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $modelo2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View');
            $selectCampos = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
            $campoCantidad = 'qty';
        
            // Buscar en la primera tabla
            $datosTabla1 = $modelo1
                ->where('Estilos', $estilo)
                ->where($campoBusqueda2, $orden)
                ->where('sizename', $talla)
                ->select($selectCampos)
                ->get();
        
            // Log resultados de la primera búsqueda
            /* Log::info('Resultados de la búsqueda en MaterializedBacklogTable_View', [
                'Resultados' => $datosTabla1->toArray(),
            ]); */
        
            // Buscar en la segunda tabla para los casos 'PO' y 'OV'
            $datosTabla2 = collect();
            if (in_array($tipoBusqueda, ['PO', 'OV'])) {
                $datosTabla2 = $modelo2
                    ->where('Estilos', $estilo)
                    ->where($campoBusqueda2, $orden)
                    ->where('sizename', $talla)
                    ->select($selectCampos)
                    ->get();
        
                // Log resultados de la segunda búsqueda
                /* Log::info('Resultados de la búsqueda en MaterializedBacklogTable2_View', [
                    'Resultados' => $datosTabla2->toArray(),
                ]); */
            }
        
            // Combina los resultados de ambas tablas
            $datos = $datosTabla1->merge($datosTabla2);
        
            // Log resultados combinados
            /* Log::info('Resultados combinados de ambas tablas', [
                'Resultados' => $datos->toArray(),
            ]); */
        
            // Filtrar duplicados comparando con registros existentes
            $registrosExistentes = ReporteAuditoriaEtiqueta::all();
            $registrosExistentesArray = $registrosExistentes->map(function ($item) {
                return [
                    'Orden'   => $item->Orden,
                    'Estilos' => $item->Estilos,
                    'Color'   => $item->Color,
                    'Talla'   => $item->Talla,
                ];
            })->toArray();
        
            $datosFiltrados = $datos->filter(function ($dato) use ($registrosExistentesArray, $tipoBusqueda, $campoBusqueda2) {
                $color = $dato->inventcolorid; // Usar inventcolorid para casos no 'OC'
                $tallaReal = $dato->sizename; // Usar sizename para casos no 'OC'
                $ordenValor = $dato->$campoBusqueda2;
        
                $combinacion = [
                    'Orden'   => $ordenValor,
                    'Estilos' => $dato->Estilos,
                    'Color'   => $color,
                    'Talla'   => $tallaReal,
                ];
        
                return !in_array($combinacion, $registrosExistentesArray);
            });
        
            // Calcular el tamaño de muestra para cada registro filtrado
            foreach ($datosFiltrados as $dato) {
                $cantidad = $dato->$campoCantidad;
                $tamaño_muestra = $this->calcularTamanoMuestra($cantidad);
                $dato->tamaño_muestra = $tamaño_muestra;
            }
        
            // Si hubiera varios registros y quisieras solo el primero
            $respuesta = null;
            if ($datosFiltrados->count() > 0) {
                $primer = $datosFiltrados->first();
        
                // Usamos el operador ?? para poner "N/A" si no viene color
                $respuesta = [
                    'cantidad'       => $primer->$campoCantidad,
                    'tamaño_muestra' => $primer->tamaño_muestra,
                    'color'          => $primer->inventcolorid ?? 'N/A',
                ];
            } else {
                // Si no hay nada filtrado
                $respuesta = [
                    'cantidad'       => 0,
                    'tamaño_muestra' => '',
                    'color'          => 'N/A',
                ];
            }
        
            return response()->json([
                'success' => true,
                'data'    => $respuesta,
            ]);
        }       
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
                    'cantidad'=> $defecto['cantidad'],
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