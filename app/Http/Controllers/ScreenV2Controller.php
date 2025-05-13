<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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
use App\Models\Horno_Banda;

class ScreenV2Controller extends Controller
{
    public function inspeccionEstampadoHornoInicio(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.inspeccionEstampadoHornoInicio', compact('mesesEnEspanol'));
    }

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
        $data = Cache::remember('operarios_tecnico_view', now()->addHours(15), function () {
            return DB::connection('sqlsrv')
                ->table('OperariosTecnico_View')
                ->orderBy('nombre', 'asc')
                ->get();
        });

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
        DB::beginTransaction();

        try {
            $auditorDato = Auth::user()->name;
            $bulto = $request->input('bulto_seleccionado');
            $fechaHoy = now()->toDateString();
            $tipoPanelNombre = $request->input('tipo_panel_nombre');

            // Buscar inspección existente del día actual para el mismo bulto y tipo de panel
            $inspeccion = InspeccionHorno::where('bulto', $bulto)
                ->whereDate('created_at', $fechaHoy)
                ->where('panel', $tipoPanelNombre)
                ->first();

            if (!$inspeccion) {
                // Crear nueva inspección si no existe
                $inspeccion = new InspeccionHorno();
                $inspeccion->auditor    = $auditorDato;
                $inspeccion->panel      = $tipoPanelNombre;
                $inspeccion->maquina    = $request->input('tipo_maquina_nombre');
                $inspeccion->grafica    = $request->input('valor_grafica');
                $inspeccion->op         = $request->input('op_select');
                $inspeccion->bulto      = $bulto;
                $inspeccion->cliente    = $request->input('cliente_seleccionado');
                $inspeccion->estilo     = $request->input('estilo_seleccionado');
                $inspeccion->color      = $request->input('color_seleccionado');
                $inspeccion->cantidad   = $request->input('cantidad_seleccionado');
                $inspeccion->save();
            }

            // Guardar técnicas
            if ($request->has('tipo_tecnica_screen')) {
                foreach ($request->input('tipo_tecnica_screen') as $tecnica) {
                    $nuevaTecnica = new InspeccionHornoTecnica();
                    $nuevaTecnica->inspeccion_horno_id = $inspeccion->id;
                    $nuevaTecnica->nombre = $tecnica;
                    $nuevaTecnica->save();
                }
            }

            // Guardar fibras
            if ($request->has('tipo_fibra_screen')) {
                foreach ($request->input('tipo_fibra_screen') as $fibraData) {
                    $nuevaFibra = new InspeccionHornoFibra();
                    $nuevaFibra->inspeccion_horno_id = $inspeccion->id;
                    $nuevaFibra->nombre = $fibraData['nombre'] ?? null;
                    $nuevaFibra->cantidad = $fibraData['cantidad'] ?? 0;
                    $nuevaFibra->save();
                }
            }

            // Registrar auditoría Screen si se envió
            if ($request->filled('nombre_tecnico_screen')) {
                $screen = new InspeccionHornoScreen();
                $screen->inspeccion_horno_id = $inspeccion->id;
                $screen->nombre_tecnico = $request->input('nombre_tecnico_screen');
                $screen->accion_correctiva = $request->input('accion_correctiva_screen');

                if (is_numeric($request->input('cantidad_screen_segundas')) && intval($request->input('cantidad_screen_segundas')) > 0) {
                    $screen->cantidad_segunda = intval($request->input('cantidad_screen_segundas'));
                }

                $screen->save();

                // Guardar defectos de Screen
                if ($request->has('defecto_screen')) {
                    foreach ($request->input('defecto_screen') as $defectoData) {
                        $nuevoDefectoScreen = new InspeccionHornoScreenDefecto();
                        $nuevoDefectoScreen->inspeccion_horno_screen_id = $screen->id;
                        $nuevoDefectoScreen->nombre = $defectoData['nombre'] ?? null;
                        $nuevoDefectoScreen->cantidad = $defectoData['cantidad'] ?? 0;
                        $nuevoDefectoScreen->save();
                    }
                }
            }

            // Registrar auditoría Plancha si se envió
            if ($request->filled('nombre_tecnico_plancha')) {
                $plancha = new InspeccionHornoPlancha();
                $plancha->inspeccion_horno_id = $inspeccion->id;
                $plancha->nombre_tecnico = $request->input('nombre_tecnico_plancha');
                $plancha->piezas_auditadas = $request->input('piezas_auditadas');
                $plancha->accion_correctiva = $request->input('accion_correctiva_plancha');

                if (is_numeric($request->input('cantidad_plancha_segundas')) && intval($request->input('cantidad_plancha_segundas')) > 0) {
                    $plancha->cantidad_segunda = intval($request->input('cantidad_plancha_segundas'));
                }

                $plancha->save();

                // Guardar defectos de Plancha
                if ($request->has('defecto_plancha')) {
                    foreach ($request->input('defecto_plancha') as $defectoData) {
                        $nuevoDefectoPlancha = new InspeccionHornoPlanchaDefecto();
                        $nuevoDefectoPlancha->inspeccion_horno_plancha_id = $plancha->id;
                        $nuevoDefectoPlancha->nombre = $defectoData['nombre'] ?? null;
                        $nuevoDefectoPlancha->cantidad = $defectoData['cantidad'] ?? 0;
                        $nuevoDefectoPlancha->save();
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Inspección registrada con éxito')->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al guardar la inspección. Inténtalo de nuevo.');
        }
    }

    public function bultosPorDia(Request $request)
    {
        // Obtener el inicio y fin del día actual
        $inicioDia = \Carbon\Carbon::now()->startOfDay();
        $finDia    = \Carbon\Carbon::now()->endOfDay();
        $auditorDato = Auth::user()->name;

        // Traer las inspecciones del día con las relaciones necesarias
        $inspecciones = InspeccionHorno::with([
            'tecnicas',             // Relación con InspeccionHornoTecnica
            'fibras',               // Relación con InspeccionHornoFibra
            'screen.defectos',      // Relación: InspeccionHornoScreen y sus defectos
            'plancha.defectos'      // Relación: InspeccionHornoPlancha y sus defectos
        ])
        ->whereBetween('created_at', [$inicioDia, $finDia])
        ->where('auditor', $auditorDato)
        ->get();

        // Procesar cada registro para formatear los datos a mostrar
        $data = $inspecciones->map(function ($inspeccion) {
            // Técnicas: Se muestran únicas y formateadas como <ul><li>...</li></ul>
            $tecnicas = '';
            if ($inspeccion->tecnicas->isNotEmpty()) {
                $tecnicaItems = $inspeccion->tecnicas
                                    ->pluck('nombre')
                                    ->unique()
                                    ->map(function ($tecnica) {
                                        return '<li>' . $tecnica . '</li>';
                                    });
                $tecnicas = '<ul>' . $tecnicaItems->implode('') . '</ul>';
            }

            // Fibras: Se muestran con cantidad en paréntesis, formateadas como lista <ul><li>...</li></ul>
            $fibras = '';
            if ($inspeccion->fibras->isNotEmpty()) {
                $fibraItems = $inspeccion->fibras
                                    ->map(function ($fibra) {
                                        return '<li>' . $fibra->nombre . ' (' . $fibra->cantidad . ')</li>';
                                    })
                                    ->unique();
                $fibras = '<ul>' . $fibraItems->implode('') . '</ul>';
            }

            // Defectos de Screen: Se muestran como <ul><li>...</li></ul>
            $screenDefectos = '';
            if ($inspeccion->screen && $inspeccion->screen->defectos->isNotEmpty()) {
                $screenDefectoItems = $inspeccion->screen->defectos
                                                ->map(function ($defecto) {
                                                    return '<li>' . $defecto->nombre . ' (' . $defecto->cantidad . ')</li>';
                                                })
                                                ->unique();
                $screenDefectos = '<ul>' . $screenDefectoItems->implode('') . '</ul>';
            }
            // Defectos de Plancha: se formatean como lista desordenada (<ul><li>...</li></ul>)
            $planchaDefectos = '';
            if ($inspeccion->plancha && $inspeccion->plancha->defectos->isNotEmpty()) {
                $planchaItems = $inspeccion->plancha->defectos
                                        ->map(function ($defecto) {
                                            return '<li>' . $defecto->nombre . ' (' . $defecto->cantidad . ')</li>';
                                        })
                                        ->unique();
                $planchaDefectos = '<ul>' . $planchaItems->implode('') . '</ul>';
            }

            // Nombre del técnico para Screen y para Plancha
            $tecnicoScreen  = $inspeccion->screen ? $inspeccion->screen->nombre_tecnico : '';
            $tecnicoPlancha = $inspeccion->plancha ? $inspeccion->plancha->nombre_tecnico : '';

            return [
                'id'               => $inspeccion->id, // SE AGREGA EL ID
                'bulto'            => $inspeccion->bulto ?? 'N/A',
                'op'               => $inspeccion->op ?? 'N/A',
                'cliente'          => $inspeccion->cliente ?? 'N/A',
                'estilo'           => $inspeccion->estilo ?? 'N/A',
                'color'            => $inspeccion->color ?? 'N/A',
                'cantidad'         => $inspeccion->cantidad ?? 'N/A',
                'panel'            => $inspeccion->panel ?? 'N/A',
                'maquina'          => $inspeccion->maquina ?? 'N/A',
                'grafica'          => $inspeccion->grafica ?? 'N/A',
                'tecnicas'         => !empty($tecnicas) ? $tecnicas : 'N/A',
                'fibras'           => !empty($fibras) ? $fibras : 'N/A',
                'screenDefectos'   => !empty($screenDefectos) ? $screenDefectos : 'N/A',
                'planchaDefectos'  => !empty($planchaDefectos) ? $planchaDefectos : 'N/A',
                'tecnico_screen'   => !empty($tecnicoScreen) ? $tecnicoScreen : 'N/A',
                'tecnico_plancha'  => !empty($tecnicoPlancha) ? $tecnicoPlancha : 'N/A',
                'fecha'            => $inspeccion->created_at ? $inspeccion->created_at->format('H:i:s') : 'N/A'
            ];            
        });

        return response()->json(['data' => $data]);
    }

    public function eliminarBulto($id)
    {
        try {
            // Buscar el registro
            $inspeccion = InspeccionHorno::findOrFail($id);

            // Eliminar el registro (y sus relaciones si aplica por "cascade")
            $inspeccion->delete();

            return response()->json(['success' => true, 'message' => 'Registro eliminado correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar el registro.']);
        }
    }

    public function screenV2(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        // Obtener el inicio y fin del día
        $inicioDia = Carbon::now()->startOfDay(); // 00:00:00
        $finDia = Carbon::now()->endOfDay(); // 23:59:59

        // Obtener registros del día y formatear la hora
        $registroHornoDia = Horno_Banda::whereBetween('created_at', [$inicioDia, $finDia])
            ->get()
            ->map(function ($registro) {
                $registro->hora = Carbon::parse($registro->created_at)->format('H:i:s'); // Formato 24h
                return $registro;
            });

        return view('ScreenPlanta2.screenV2', compact('mesesEnEspanol', 'registroHornoDia'));
    }

    public function getScreenData()
    {
        $auditorDato = Auth::user()->name;
        $auditorPuesto = Auth::user()->puesto;
        // Iniciar la consulta base
        $query = InspeccionHorno::with(['screen.defectos', 'tecnicas', 'fibras'])
                                ->whereHas('screen') // Asegura que solo se obtengan inspecciones que tengan una pantalla asociada
                                ->orderBy('created_at', 'desc')
                                ->whereDate('created_at', Carbon::today());

        // Aplicar el filtro de auditor condicionalmente
        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            // Si el puesto NO es Administrador NI Gerente de Calidad,
            // entonces filtramos por el nombre del auditor.
            $query->where('auditor', $auditorDato);
        }
        // Si el puesto ES Administrador o Gerente de Calidad, no se añade el ->where('auditor', $auditorDato),
        // por lo que se obtendrán todos los registros del día para esos puestos.

        // Ejecutar la consulta
        $inspecciones = $query->get();

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

            // Agrupar y sumar la cantidad de defectos por nombre
            $defectosAggregados = [];
            foreach ($group as $registro) {
                if ($registro->screen && $registro->screen->defectos) {
                    foreach ($registro->screen->defectos as $defecto) {
                        $nombre = trim($defecto->nombre);
                        $cantidadDefecto = $defecto->cantidad;  // Asumiendo que "cantidad" es un campo numérico
                        if (isset($defectosAggregados[$nombre])) {
                            $defectosAggregados[$nombre] += $cantidadDefecto;
                        } else {
                            $defectosAggregados[$nombre] = $cantidadDefecto;
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

    public function getScreenStats()
    {
        // Obtener las inspecciones que tengan la relación "screen" (y sus defectos)
        $inspecciones = InspeccionHorno::with(['screen.defectos'])
                            ->whereHas('screen')
                            ->whereDate('created_at', Carbon::today())
                            ->get();

        // Calcular la Cantidad total revisada (suma de la columna "cantidad" de InspeccionHorno)
        $cantidad_total_revisada = $inspecciones->sum('cantidad');

        // Inicializar la variable para la cantidad total de defectos
        $cantidad_defectos = 0;

        // Recorrer cada inspección y sumar la cantidad de defectos de la relación "screen.defectos"
        foreach ($inspecciones as $inspeccion) {
            if ($inspeccion->screen && $inspeccion->screen->defectos) {
                foreach ($inspeccion->screen->defectos as $defecto) {
                    // Se asume que $defecto->cantidad es un valor numérico
                    $cantidad_defectos += $defecto->cantidad;
                }
            }
        }

        // Calcular el porcentaje de defectos
        // Se asume que "Porcentaje de defectos" es: (Cantidad de defectos / Cantidad total revisada) * 100
        $porcentaje_defectos = 0;
        if ($cantidad_total_revisada > 0) {
            $porcentaje_defectos = ($cantidad_defectos / $cantidad_total_revisada) * 100;
        }
        // Redondear el porcentaje a 2 decimales (opcional)
        $porcentaje_defectos = round($porcentaje_defectos, 2);

        // Retornar los datos estadísticos en formato JSON
        return response()->json([
            'cantidad_total_revisada' => $cantidad_total_revisada,
            'cantidad_defectos'       => $cantidad_defectos,
            'porcentaje_defectos'     => $porcentaje_defectos
        ]);
    }

    public function planchaV2(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.planchaV2', compact('mesesEnEspanol'));
    }

    public function getPlanchaData(Request $request)
    {
        $auditorDato = Auth::user()->name;
        $auditorPuesto = Auth::user()->puesto;

        // Obtener la fecha de la solicitud. Si no se provee, usar la fecha actual.
        $fechaInput = $request->input('fecha');
        $fechaSeleccionada = $fechaInput ? Carbon::parse($fechaInput)->toDateString() : Carbon::today()->toDateString();

        $query = InspeccionHorno::with([
            'plancha.defectos',
            'tecnicas',
            'fibras'
        ])
        ->whereHas('plancha')
        ->whereDate('created_at', $fechaSeleccionada) // Filtrar por la fecha seleccionada
        ->orderBy('created_at', 'desc');

        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            $query->where('auditor', $auditorDato);
        }

        $inspecciones = $query->get();
        $grouped = $inspecciones->groupBy('op');

        // Preparar los datos finales
        $result = $grouped->map(function ($group) {
            // Tomamos el primer registro del grupo para campos comunes
            $first = $group->first();

            // Sumar la cantidad total de los registros del mismo "op"
            $totalCantidad = $group->sum('plancha.piezas_auditadas');

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
                $group->pluck('plancha.nombre_tecnico')->unique()->toArray())) . '</ul>';

            // 🔹 Agrupar acciones correctivas y evitar listas vacías
            $accionesCorrectivasTexto = $group->pluck('plancha.accion_correctiva')->unique()->filter()->toArray();
            $accionesCorrectivasTexto = count($accionesCorrectivasTexto) 
                ? '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", $accionesCorrectivasTexto)) . '</ul>' 
                : 'N/A';

            // Agrupar y sumar la cantidad de defectos por nombre
            $defectosAggregados = [];
            foreach ($group as $registro) {
                if ($registro->plancha && $registro->plancha->defectos) {
                    foreach ($registro->plancha->defectos as $defecto) {
                        $nombre = trim($defecto->nombre);
                        $cantidadDefecto = $defecto->cantidad;  // Asumiendo que "cantidad" es un campo numérico
                        if (isset($defectosAggregados[$nombre])) {
                            $defectosAggregados[$nombre] += $cantidadDefecto;
                        } else {
                            $defectosAggregados[$nombre] = $cantidadDefecto;
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

    public function getPlanchaStats(Request $request)
    {
        $auditorDato = Auth::user()->name;
        $auditorPuesto = Auth::user()->puesto;

        $fechaInput = $request->input('fecha');
        $fechaSeleccionada = $fechaInput ? Carbon::parse($fechaInput)->toDateString() : Carbon::today()->toDateString();

        $query = InspeccionHorno::with(['plancha.defectos'])
            ->whereHas('plancha')
            ->whereDate('created_at', $fechaSeleccionada);

        // Aplicar el mismo filtro de auditor que en getPlanchaData para consistencia
        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            $query->where('auditor', $auditorDato);
        }
        
        $inspecciones = $query->get();

        // Calcular la Cantidad total revisada (suma de la columna "cantidad" de InspeccionHorno)
        $cantidad_total_revisada = $inspecciones->sum('plancha.piezas_auditadas');

        // Inicializar la variable para la cantidad total de defectos
        $cantidad_defectos = 0;

        // Recorrer cada inspección y sumar la cantidad de defectos de la relación "screen.defectos"
        foreach ($inspecciones as $inspeccion) {
            if ($inspeccion->plancha && $inspeccion->plancha->defectos) {
                foreach ($inspeccion->plancha->defectos as $defecto) {
                    // Se asume que $defecto->cantidad es un valor numérico
                    $cantidad_defectos += $defecto->cantidad;
                }
            }
        }

        // Calcular el porcentaje de defectos
        // Se asume que "Porcentaje de defectos" es: (Cantidad de defectos / Cantidad total revisada) * 100
        $porcentaje_defectos = 0;
        if ($cantidad_total_revisada > 0) {
            $porcentaje_defectos = ($cantidad_defectos / $cantidad_total_revisada) * 100;
        }
        // Redondear el porcentaje a 2 decimales (opcional)
        $porcentaje_defectos = round($porcentaje_defectos, 2);

        // Retornar los datos estadísticos en formato JSON
        return response()->json([
            'cantidad_total_revisada' => $cantidad_total_revisada,
            'cantidad_defectos'       => $cantidad_defectos,
            'porcentaje_defectos'     => $porcentaje_defectos
        ]);
    }

    public function formControlHorno(Request $request) 
    {
        
        //dd($request->all());
        // Guardar el registro
        $registro = new Horno_Banda();
        $registro->temperatura_horno = $request->grados;
        $registro->velocidad_banda = "{$request->minuto}:{$request->segundo}"; // Formato mm:ss
        $registro->save();

        // Redirigir a la misma vista con un mensaje de éxito
        return redirect()->back()->with('success', 'Datos guardados correctamente.');
    }


}
