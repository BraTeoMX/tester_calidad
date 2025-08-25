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

    public function buscarDatosCompletos(Request $request)
    {
        $tipoBusqueda = $request->input('tipoEtiqueta');
        $orden = $request->input('valorEtiqueta');

        // 1. Obtener todos los datos de la fuente externa (usando caché)
        $datosExternos = $this->obtenerDatosCompletosPorOrden($tipoBusqueda, $orden);

        if ($datosExternos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros para la orden especificada.',
            ]);
        }

        // 2. Filtrar para excluir registros que ya existen en nuestra BD local
        // (Esta lógica no se cachea para reflejar siempre el estado actual)
        $registrosExistentes = ReporteAuditoriaEtiqueta::where('orden', $orden)
            ->get(['estilo', 'talla', 'color'])
            ->map(fn($item) => $item->estilo . '-' . $item->talla . '-' . $item->color)
            ->flip();

        $datosDisponibles = $datosExternos->filter(function ($item) use ($registrosExistentes) {
            $combinacion = $item->estilo . '-' . $item->talla . '-' . $item->color;
            return !isset($registrosExistentes[$combinacion]);
        });

        if ($datosDisponibles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Todos los registros para esta orden ya han sido auditados.',
            ]);
        }

        // 3. Calcular el tamaño de muestra para cada registro disponible
        $datosFinales = $datosDisponibles->map(function ($item) {
            $item->muestreo = $this->calcularTamanoMuestra($item->cantidad);
            return $item;
        })->values(); // ->values() para re-indexar el array

        return response()->json([
            'success' => true,
            'data' => $datosFinales,
        ]);
    }

    /**
     * Helper que realiza la consulta unificada a la BD externa, con caché.
     */
    private function obtenerDatosCompletosPorOrden($tipoBusqueda, $orden)
    {
        $cacheKey = 'datos_completos.' . $tipoBusqueda . '.' . $orden;

        return Cache::remember($cacheKey, 10, function () use ($tipoBusqueda, $orden) {
            $query = null;

            if ($tipoBusqueda === 'OC') {
                $conexion1 = DB::connection('sqlsrv_ax')->table('EtiquetasOC_View')
                    ->where('ordenCompra', $orden)
                    ->select('Estilos as estilo', 'Talla as talla', 'Color as color', 'Cantidad as cantidad');

                $query = DB::connection('sqlsrv_ax')->table('EtiquetasOC2_View')
                    ->where('OrdenCompra', $orden)
                    ->select('Estilos as estilo', 'Talla as talla', 'Color as color', 'Cantidad as cantidad')
                    ->union($conexion1);
            } elseif (in_array($tipoBusqueda, ['OP', 'PO', 'OV'])) {
                $campoBusqueda = ['OP' => 'OP', 'PO' => 'CPO', 'OV' => 'SALESID'][$tipoBusqueda];

                $conexion1 = DB::connection('sqlsrv')->table('MaterializedBacklogTable_View')
                    ->where($campoBusqueda, $orden)
                    ->select('Estilos as estilo', 'sizename as talla', 'inventcolorid as color', 'qty as cantidad');

                if ($tipoBusqueda === 'PO') { // El tipo 'PO' también puede estar en la tabla 2
                    $conexion2 = DB::connection('sqlsrv')->table('MaterializedBacklogTable2_View')
                        ->where($campoBusqueda, $orden)
                        ->select('Estilos as estilo', 'sizename as talla', 'inventcolorid as color', 'qty as cantidad')
                        ->union($conexion1);
                    $query = $conexion2;
                } else {
                    $query = $conexion1;
                }
            }

            // Si no se encontró un tipo de búsqueda válido, retorna una colección vacía
            if (!$query) {
                return collect();
            }

            return $query->distinct()->get();
        });
    }

    /**
     * Función helper para calcular el tamaño de muestra (sin cambios).
     */
    private function calcularTamanoMuestra($cantidad)
    {
        // ... (la misma lógica de if/elseif que ya tenías)
        if ($cantidad >= 2 && $cantidad <= 8) return '2';
        if ($cantidad >= 9 && $cantidad <= 15) return '3';
        if ($cantidad >= 16 && $cantidad <= 25) return '5';
        if ($cantidad >= 26 && $cantidad <= 50) return '8';
        if ($cantidad >= 51 && $cantidad <= 90) return '13';
        if ($cantidad >= 91 && $cantidad <= 150) return '20';
        if ($cantidad >= 151 && $cantidad <= 280) return '32';
        if ($cantidad >= 281 && $cantidad <= 500) return '50';
        if ($cantidad >= 501 && $cantidad <= 1200) return '80';
        if ($cantidad >= 1201 && $cantidad <= 3200) return '125';
        if ($cantidad >= 3201 && $cantidad <= 10000) return '200';
        if ($cantidad >= 10001 && $cantidad <= 35000) return '315';
        if ($cantidad >= 35001 && $cantidad <= 150000) return '500';
        if ($cantidad >= 150001 && $cantidad <= 5000000) return '800';
        if ($cantidad > 5000000) return '2000';
        return '';
    }

    public function guardarAuditoria(Request $request)
    {
        // Validación más específica y robusta
        $request->validate([
            'tipoEtiqueta'      => 'required|string',
            'valorEtiqueta'     => 'required|string',
            'estilo'            => 'required|string',
            'talla'             => 'required|string',
            'accion_correctiva' => 'required|string',
            'comentarios'       => 'required_if:accion_correctiva,Aprobado con condicion|nullable|string',
            // Reglas para el arreglo de defectos
            'defectos'          => 'required_if:accion_correctiva,Rechazado|array|min:1',
            // Reglas para CADA ITEM dentro del arreglo de defectos
            'defectos.*.id'     => 'required|integer|exists:catalogo_defectosetiqueas,id', // El ID debe existir en tu tabla de catálogo
            'defectos.*.nombre' => 'required|string',
            'defectos.*.cantidad' => 'required|integer|min:1', // La cantidad debe ser un entero y al menos 1
        ]);

        $reporte = new ReporteAuditoriaEtiqueta();
        // ... Asignación de campos ...
        $reporte->nombre_auditor = Auth::user()->name;
        $reporte->tipo = $request->tipoEtiqueta;
        $reporte->orden = $request->valorEtiqueta;
        $reporte->estilo = $request->estilo;
        $reporte->color = $request->color;
        $reporte->talla = $request->talla;
        $reporte->cantidad = $request->cantidad;
        $reporte->muestreo = $request->muestreo;
        $reporte->estatus = $request->accion_correctiva;
        $reporte->comentario = $request->comentarios;
        $reporte->registro_manual = $request->input('registro_manual', 0);
        $reporte->rechazo = ($request->accion_correctiva === 'Rechazado') ? 1 : null;
        $reporte->save();

        // Ahora sí, guardamos los defectos si la acción fue 'Rechazado'
        if ($request->accion_correctiva === 'Rechazado' && $request->has('defectos')) {
            foreach ($request->defectos as $defecto) {
                TpReporteAuditoriaEtiqueta::create([ // Asegúrate que el namespace sea correcto
                    'id_reporte_auditoria_etiquetas' => $reporte->id,
                    'nombre'   => $defecto['nombre'],
                    'cantidad' => $defecto['cantidad'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Auditoría guardada correctamente.',
        ]);
    }

    public function obtenerDefectos()
    {
        // Usamos tu lógica original, es perfecta.
        $tiposDefectos = Cat_DefEtiquetas::where('estatus', 1)->get(); // Asegúrate que el namespace del modelo sea correcto
        return response()->json($tiposDefectos);
    }

    public function guardarNuevoDefecto(Request $request)
    {
        $request->validate(['Defectos' => 'required|string|unique:cat_def_etiquetas,Defectos']);

        $nuevoDefecto = Cat_DefEtiquetas::create([
            'Defectos' => $request->Defectos,
            'estatus' => 1
        ]);

        return response()->json(['success' => true, 'defecto' => $nuevoDefecto]);
    }
}
