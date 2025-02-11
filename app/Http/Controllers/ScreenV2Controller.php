<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\CatalogoDefectosScreen;
use App\Models\CategoriaAccionCorrectScreen;
use App\Models\JobAQLHistorial;
use App\Models\InspeccionHorno;
use App\Models\InspeccionHornoScreen;
use App\Models\InspeccionHornoPlancha;
use App\Models\InspeccionHornoTecnica;
use App\Models\InspeccionHornoFibra;
use App\Models\InspeccionHornoScreenDefecto;
use App\Models\InspeccionHornoPlanchaDefecto;
use App\Models\CategoriaTipoPanel;
use App\Models\CategoriaTipoMaquina;
use App\Models\Tecnicos;
use App\Models\Tipo_Fibra;
use App\Models\Tipo_Tecnica;

class ScreenV2Controller extends Controller
{
    public function inspeccionEstampadoHorno(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.inspeccionEstampadoHorno', compact('mesesEnEspanol'));
    }
    
    /**
     * Busca las OP (prodid) únicas según el término ingresado.
     * Retorna un JSON con formato para Select2.
     */
    public function searchOpsScreen(Request $request)
    {
        $term = $request->get('q', '');

        // Buscamos los prodid distintos que coincidan con el término
        $results = JobAQLHistorial::select('prodid')
                    ->where('prodid', 'LIKE', "%{$term}%")
                    ->distinct()
                    ->limit(100)
                    ->get();

        // Formateamos la respuesta para Select2
        $formattedResults = $results->map(function ($result) {
            return [
                'id'   => $result->prodid,  // El valor que usaremos para filtrar bultos
                'text' => $result->prodid,  // Lo que se mostrará en el dropdown
            ];
        });

        return response()->json($formattedResults);
    }

    /**
     * Busca los bultos (prodpackticketid) que correspondan a la OP recibida,
     * filtrando además por el término ingresado en el select2.
     */
    public function searchBultosByOpScreen(Request $request)
    {
        $op   = $request->get('op', '');
        $term = $request->get('q', ''); // para buscar por bulto

        // Buscamos los bultos relacionados a esa OP, filtrando por term en prodpackticketid
        $results = JobAQLHistorial::where('prodid', $op)
                    ->where('prodpackticketid', 'LIKE', "%{$term}%")
                    ->select('id', 'prodpackticketid')
                    ->distinct()
                    ->limit(100)
                    ->get();

        // Formateamos la respuesta para Select2
        $formattedResults = $results->map(function ($result) {
            return [
                'id'   => $result->id, // Este será el ID específico para luego obtener detalles
                'text' => $result->prodpackticketid,
            ];
        });

        return response()->json($formattedResults);
    }

    /**
     * Obtiene detalles de un bulto en base a su id (columna 'id').
     */
    public function getBultoDetailsScreen($id)
    {
        $bulto = JobAQLHistorial::find($id);

        if (!$bulto) {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }

        return response()->json([
            'bulto'    => $bulto->prodpackticketid,
            'op'       => $bulto->prodid,
            'cliente'  => $bulto->customername,
            'estilo'   => $bulto->itemid,
            'color'    => $bulto->inventcolorid,
            'cantidad' => $bulto->qty,
        ]);
    }

    public function getCategoriaTecnicoScreen()
    {
        $data = Tecnicos::where('estatus', 1)->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getCategoriaTipoPanel()
    {
        $data = CategoriaTipoPanel::where('estatus', 1)->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getCategoriaTipoMaquina()
    {
        $data = CategoriaTipoMaquina::where('estatus', 1)->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getTipoTecnicaScreen() // Cambio de nombre aquí
    {
        $data = Tipo_Tecnica::where('estatus', 1)->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getTipoFibraScreen() // Cambio de nombre aquí
    {
        $data = Tipo_Fibra::where('estatus', 1)->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function guardarNuevoValor(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'modelo' => 'required|string',
            'estatus' => 'required|integer'
        ]);

        // Determinar el modelo a usar dinámicamente
        $modeloClass = '\\App\\Models\\' . $validatedData['modelo'];

        if (!class_exists($modeloClass)) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado.'], 400);
        }

        // Crear la nueva instancia y guardarla en la base de datos
        $nuevoRegistro = new $modeloClass();
        $nuevoRegistro->nombre = $validatedData['nombre'];
        $nuevoRegistro->estatus = $validatedData['estatus'];
        $nuevoRegistro->save();

        return response()->json(['success' => true, 'id' => $nuevoRegistro->id]);
    }

    public function getDefectoScreen()
    {
        $data = CatalogoDefectosScreen::where('estatus', 1)->where('area', 'screen')->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getAccionCorrectivaScreen()
    {
        $data = CategoriaAccionCorrectScreen::where('estatus', 1)->where('area', 'screen')->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getDefectoPlancha()
    {
        $data = CatalogoDefectosScreen::where('estatus', 1)->where('area', 'plancha')->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function getAccionCorrectivaPlancha()
    {
        $data = CategoriaAccionCorrectScreen::where('estatus', 1)->where('area', 'plancha')->select('id', 'nombre')->get();
        return response()->json($data);
    }

    public function guardarNuevoValorDA(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'modelo' => 'required|string',
            'estatus' => 'required|integer',
            'area' => 'required|string|in:screen,plancha' // Validamos que el área sea válida
        ]);

        // Determinar el modelo dinámicamente
        $modeloClass = '\\App\\Models\\' . $validatedData['modelo'];

        if (!class_exists($modeloClass)) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado.'], 400);
        }

        // Crear y guardar el nuevo registro
        $nuevoRegistro = new $modeloClass();
        $nuevoRegistro->nombre = $validatedData['nombre'];
        $nuevoRegistro->estatus = $validatedData['estatus'];
        $nuevoRegistro->area = $validatedData['area']; // Guardamos el área
        $nuevoRegistro->save();

        return response()->json(['success' => true, 'id' => $nuevoRegistro->id]);
    }


    public function store(Request $request)
    {
        // Para ver el contenido del request, puedes usar dd($request->all());
        // dd($request->all());
        
        DB::beginTransaction(); // Iniciar la transacción

        try {
            // 1. Crear el registro principal en InspeccionHorno
            $inspeccion = new InspeccionHorno();
            $inspeccion->panel              = $request->input('tipo_panel');
            $inspeccion->maquina            = $request->input('tipo_maquina');
            $inspeccion->grafica            = $request->input('valor_grafica');
            $inspeccion->op                 = $request->input('op_select');
            $inspeccion->bulto              = $request->input('bulto_select');
            $inspeccion->cliente            = $request->input('cliente_seleccionado');
            $inspeccion->estilo             = $request->input('estilo_seleccionado');
            $inspeccion->color              = $request->input('color_seleccionado');
            $inspeccion->cantidad           = $request->input('cantidad_seleccionado');
            $inspeccion->save();

            // 2. Guardar Técnicas (uno a muchos) en InspeccionHornoTecnica
            if ($request->has('tipo_tecnica_screen')) {
                foreach ($request->input('tipo_tecnica_screen') as $tecnica) {
                    $nuevaTecnica = new InspeccionHornoTecnica();
                    $nuevaTecnica->inspeccion_horno_id = $inspeccion->id;
                    $nuevaTecnica->nombre              = $tecnica;
                    $nuevaTecnica->save();
                }
            }

            // 3. Guardar Fibras (uno a muchos) en InspeccionHornoFibra
            if ($request->has('tipo_fibra_screen')) {
                foreach ($request->input('tipo_fibra_screen') as $key => $fibraData) {
                    // Cada $fibraData es un arreglo con 'nombre' y 'cantidad'
                    $nuevaFibra = new InspeccionHornoFibra();
                    $nuevaFibra->inspeccion_horno_id = $inspeccion->id;
                    $nuevaFibra->nombre              = $fibraData['nombre'] ?? null;
                    $nuevaFibra->cantidad            = $fibraData['cantidad'] ?? 0;
                    $nuevaFibra->save();
                }
            }

            // 4. Guardar datos de Screen (uno a uno + uno a muchos para defectos)
            if ($request->has('nombre_tecnico_screen')) {
                $screen = new InspeccionHornoScreen();
                $screen->inspeccion_horno_id = $inspeccion->id;
                $screen->nombre_tecnico      = $request->input('nombre_tecnico_screen');
                $screen->accion_correctiva   = $request->input('accion_correctiva_screen');
                $screen->save();

                // Guardar defectos de Screen (uno a muchos) en InspeccionHornoScreenDefecto
                if ($request->has('defecto_screen')) {
                    foreach ($request->input('defecto_screen') as $key => $defectoData) {
                        $nuevoDefectoScreen = new InspeccionHornoScreenDefecto();
                        $nuevoDefectoScreen->inspeccion_horno_screen_id = $screen->id;
                        $nuevoDefectoScreen->nombre  = $defectoData['nombre'] ?? null;
                        $nuevoDefectoScreen->cantidad = $defectoData['cantidad'] ?? 0;
                        $nuevoDefectoScreen->save();
                    }
                }
            }

            // 5. Guardar datos de Plancha (uno a uno + uno a muchos para defectos)
            if ($request->has('nombre_tecnico_plancha')) {
                $plancha = new InspeccionHornoPlancha();
                $plancha->inspeccion_horno_id = $inspeccion->id;
                $plancha->nombre_tecnico      = $request->input('nombre_tecnico_plancha');
                $plancha->piezas_auditadas    = $request->input('piezas_auditadas');
                $plancha->accion_correctiva   = $request->input('accion_correctiva_plancha');
                $plancha->save();

                // Guardar defectos de Plancha (uno a muchos) en InspeccionHornoPlanchaDefecto
                if ($request->has('defecto_plancha')) {
                    foreach ($request->input('defecto_plancha') as $key => $defectoData) {
                        $nuevoDefectoPlancha = new InspeccionHornoPlanchaDefecto();
                        $nuevoDefectoPlancha->inspeccion_horno_plancha_id = $plancha->id;
                        $nuevoDefectoPlancha->nombre  = $defectoData['nombre'] ?? null;
                        $nuevoDefectoPlancha->cantidad = $defectoData['cantidad'] ?? 0;
                        $nuevoDefectoPlancha->save();
                    }
                }
            }

            DB::commit(); // Confirmar la transacción

            // Redirigir a la misma vista con mensaje de éxito
            return redirect()->back()->with('success', 'Inspección registrada con éxito')->withInput();
        } catch (\Exception $e) {
            DB::rollBack(); // Revertir la transacción en caso de error
            return redirect()->back()->with('error', 'Error al guardar la inspección. Inténtalo de nuevo.');
        }
    }

    public function screenV2(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.screenV2', compact('mesesEnEspanol'));
    }

    public function getScreenData()
    {
        // Obtener todos los registros con sus relaciones
        $inspecciones = InspeccionHorno::with(['screen.defectos', 'tecnicas', 'fibras'])
                            ->whereHas('screen')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Agrupar los registros por la columna "op"
        $grouped = $inspecciones->groupBy('op');

        // Preparar los datos finales
        $result = $grouped->map(function ($group) {
            // Tomamos el primer registro del grupo para campos comunes
            $first = $group->first();

            // Sumar la cantidad total de los registros del mismo "op"
            $totalCantidad = $group->sum('cantidad');

            // 🔹 Agrupar valores únicos en listas (sin cantidad)
            $panelesTexto = '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", 
                $group->pluck('panel')->unique()->toArray())) . '</ul>';

            $maquinasTexto = '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", 
                $group->pluck('maquina')->unique()->toArray())) . '</ul>';

            $graficasTexto = '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", 
                $group->pluck('grafica')->unique()->toArray())) . '</ul>';

            $clientesTexto = '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", 
                $group->pluck('cliente')->unique()->toArray())) . '</ul>';

            $tecnicosTexto = '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", 
                $group->pluck('screen.nombre_tecnico')->unique()->toArray())) . '</ul>';

            // 🔹 Agrupar acciones correctivas y evitar listas vacías
            $accionesCorrectivasTexto = $group->pluck('screen.accion_correctiva')->unique()->filter()->toArray();
            $accionesCorrectivasTexto = count($accionesCorrectivasTexto) 
                ? '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", $accionesCorrectivasTexto)) . '</ul>' 
                : 'N/A';

            // 🔹 Agrupar y contar defectos
            $defectosAggregados = [];
            foreach ($group as $registro) {
                if ($registro->screen && $registro->screen->defectos) {
                    foreach ($registro->screen->defectos as $defecto) {
                        $nombre = $defecto->nombre;
                        if (isset($defectosAggregados[$nombre])) {
                            $defectosAggregados[$nombre]++;
                        } else {
                            $defectosAggregados[$nombre] = 1;
                        }
                    }
                }
            }
            $defectosTexto = count($defectosAggregados) 
                ? '<ul>' . implode('', array_map(fn($nombre, $cantidad) => "<li>{$nombre} ({$cantidad})</li>", 
                array_keys($defectosAggregados), array_values($defectosAggregados))) . '</ul>' 
                : 'Sin defectos';

            //
            // 🔹 Agrupar y contar técnicas
            $tecnicasAggregadas = [];
            foreach ($group as $registro) {
                if ($registro->tecnicas) {
                    foreach ($registro->tecnicas as $tecnica) {
                        $nombre = $tecnica->nombre;
                        if (isset($tecnicasAggregadas[$nombre])) {
                            $tecnicasAggregadas[$nombre]++;
                        } else {
                            $tecnicasAggregadas[$nombre] = 1;
                        }
                    }
                }
            }
            $tecnicasTexto = count($tecnicasAggregadas) 
                ? '<ul>' . implode('', array_map(fn($nombre, $cantidad) => "<li>{$nombre} ({$cantidad})</li>", 
                array_keys($tecnicasAggregadas), array_values($tecnicasAggregadas))) . '</ul>'
                : 'Sin técnicas';

            // 🔹 Agrupar y contar fibras
            $fibrasAggregadas = [];
            foreach ($group as $registro) {
                if ($registro->fibras) {
                    foreach ($registro->fibras as $fibra) {
                        $nombre = $fibra->nombre;
                        if (isset($fibrasAggregadas[$nombre])) {
                            $fibrasAggregadas[$nombre]++;
                        } else {
                            $fibrasAggregadas[$nombre] = 1;
                        }
                    }
                }
            }
            $fibrasTexto = count($fibrasAggregadas) 
                ? '<ul>' . implode('', array_map(fn($nombre, $cantidad) => "<li>{$nombre} ({$cantidad})</li>", 
                array_keys($fibrasAggregadas), array_values($fibrasAggregadas))) . '</ul>'
                : 'Sin fibras';

            return [
                'op'                => $first->op,
                'panel'             => $panelesTexto,
                'maquina'           => $maquinasTexto,
                'tecnicas'          => $tecnicasTexto,
                'fibras'            => $fibrasTexto,
                'grafica'           => $graficasTexto,
                'cliente'           => $clientesTexto,
                'estilo'            => $first->estilo,
                'color'             => $first->color,
                'tecnico_screen'    => $tecnicosTexto,
                'cantidad'          => $totalCantidad,
                'defectos'          => $defectosTexto,
                'accion_correctiva' => $accionesCorrectivasTexto
            ];
        })->values(); // values() para reindexar el array

        return response()->json($result);
    }


}
