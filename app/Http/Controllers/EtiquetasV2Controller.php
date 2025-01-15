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

class EtiquetasV2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Función para mostrar la vista principal
    public function etiquetas_v2()
    {
        return view('etiquetas.etiquetas_v2', [
            'title' => '',
            'estilos' => session('estilos', collect([])),
            'tipoBusqueda' => session('tipoBusqueda', null),
            'orden' => session('orden', null),
        ]);
    }

    /**
     * Procesa el formulario: busca los estilos y retorna la vista con el primer select lleno.
     */
    public function procesarFormularioEtiqueta(Request $request)
    {
        $tipoBusqueda = $request->input('tipoEtiqueta');
        $orden = $request->input('valorEtiqueta');

        $conexion = null;
        $campoBusqueda = null;

        // Definir la conexión y el campo de búsqueda según el tipo de búsqueda
        if ($tipoBusqueda === 'OC') {
            $campoBusqueda = 'ordenCompra';
            $conexion = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View');
        } elseif ($tipoBusqueda === 'OP') {
            $campoBusqueda = 'OP';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'PO') {
            $campoBusqueda = 'CPO';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        } elseif ($tipoBusqueda === 'OV') {
            $campoBusqueda = 'SALESID';
            $conexion = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
        }

        // Ejecutar la consulta
        $estilos = $conexion
            ->where($campoBusqueda, $orden)
            ->select('Estilos')
            ->distinct()
            ->get();

        // Búsqueda secundaria (si es OC y no hay resultados)
        if ($tipoBusqueda === 'OC' && $estilos->isEmpty()) {
            $campoBusqueda2 = 'OrdenCompra';
            $conexion2 = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View');

            $estilos = $conexion2
                ->where($campoBusqueda2, $orden)
                ->select('Estilos')
                ->distinct()
                ->get();
        }

        // Redirigir a la vista principal con datos de sesión flash
        return redirect()->route('etiquetas_v2')->with([
            'estilos' => $estilos,
            'tipoBusqueda' => $tipoBusqueda,
            'orden' => $orden,
        ]);
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

        } else {
            // Para OP, PO, OV
            $campoBusqueda2 = [
                'OP' => 'OP',
                'PO' => 'CPO',
                'OV' => 'SALESID',
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

            $modelo        = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View');
            $selectCampos  = [$campoBusqueda2, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
            $campoCantidad = 'qty';

            $datos = $modelo->where('Estilos', $estilo)
                            ->where($campoBusqueda2, $orden)
                            ->where('sizename', $talla)
                            ->select($selectCampos)
                            ->get();
        }

        // Si tuvieras que filtrar para que no repita lo que ya existe en ReporteAuditoriaEtiqueta:
        $registrosExistentes = ReporteAuditoriaEtiqueta::all();
        $registrosExistentesArray = $registrosExistentes->map(function ($item) {
            return [
                'Orden'   => $item->Orden,
                'Estilos' => $item->Estilos,
                'Color'   => $item->Color,
                'Talla'   => $item->Talla,
            ];
        })->toArray();

        // Filtrar duplicados
        $datosFiltrados = $datos->filter(function ($dato) use ($registrosExistentesArray, $tipoBusqueda, $campoBusqueda2) {
            $color     = ($tipoBusqueda == 'OC') ? $dato->Color : $dato->inventcolorid;
            $tallaReal = ($tipoBusqueda == 'OC') ? $dato->Talla : $dato->sizename;
            $ordenValor = $dato->$campoBusqueda2;

            $combinacion = [
                'Orden'   => $ordenValor,
                'Estilos' => $dato->Estilos,
                'Color'   => $color,
                'Talla'   => $tallaReal,
            ];

            return !in_array($combinacion, $registrosExistentesArray);
        });

        // Calculamos el tamaño de muestra para cada registro
        foreach ($datosFiltrados as $dato) {
            $cantidad = $dato->$campoCantidad;
            $tamaño_muestra = $this->calcularTamanoMuestra($cantidad);
            $dato->tamaño_muestra = $tamaño_muestra;
        }

        // Si hubiera varios registros y quisieras solo el primero, lo tomas así:
        $respuesta = null;
        if ($datosFiltrados->count() > 0) {
            $primer = $datosFiltrados->first();

            // Definimos el campo de color según el tipo de búsqueda
            $colorCol = ($tipoBusqueda === 'OC') ? 'Color' : 'inventcolorid';

            // Usamos el operador ?? para poner "N/A" si no viene color
            $respuesta = [
                'cantidad'       => $primer->$campoCantidad,
                'tamaño_muestra' => $primer->tamaño_muestra,
                'color'          => $primer->$colorCol ?? 'N/A',
            ];
        } else {
            // Si no hay nada filtrado
            $respuesta = [
                'cantidad'       => 0,
                'tamaño_muestra' => '',
                'color'          => 'N/A'
            ];
        }

        return response()->json([
            'success' => true,
            'data'    => $respuesta
        ]);
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
        // Guardar el reporte principal
        $reporte = ReporteAuditoriaEtiqueta::create([
            'tipo' => $request->tipoEtiqueta,
            'orden' => $request->valorEtiqueta,
            'estilo' => $request->estilo,
            'color' => $request->color,
            'talla' => $request->talla,
            'cantidad' => $request->cantidad,
            'muestreo' => $request->muestreo,
            'estatus' => $request->accion_correctiva,
        ]);

        //dd($request->has('defectos'));
        // Guardar los defectos asociados, solo si existen
        if ($request->has('defectos')) {
            foreach ($request->defectos as $defecto) {
                TpReporteAuditoriaEtiqueta::create([
                    'id_reporte_auditoria_etiquetas' => $reporte->id,
                    'nombre' => $defecto['nombre'],
                    'cantidad' => $defecto['cantidad'],
                ]);
            }
        }
        

        return redirect()->back()->with('success', 'Auditoría guardada correctamente.');
    }



}