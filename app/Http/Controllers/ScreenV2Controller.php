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
use Illuminate\Support\Collection;

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

    public function getScreenData(Request $request)
    {
        $auditorDato = Auth::user()->name;
        $auditorPuesto = Auth::user()->puesto;

        $fechaInput = $request->input('fecha');
        $fechaSeleccionada = $fechaInput ? Carbon::parse($fechaInput)->toDateString() : Carbon::today()->toDateString();

        $query = InspeccionHorno::with(['screen.defectos', 'tecnicas', 'fibras'])
            ->whereHas('screen') // Solo inspecciones que tengan una relación 'screen'
            ->orderBy('created_at', 'desc')
            ->whereDate('created_at', $fechaSeleccionada);

        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            $query->where('auditor', $auditorDato);
        }

        $inspecciones = $query->get();

        if ($inspecciones->isEmpty()) {
            return response()->json([]); // Devolver array vacío si no hay inspecciones
        }

        $grouped = $inspecciones->groupBy('op');

        $result = $grouped->map(function ($group) {
            $first = $group->first(); // $group no estará vacío aquí

            // Helper para generar listas HTML o devolver un texto por defecto
            $generateHtmlList = function ($itemsCollection, $defaultText = 'N/A') {
                $filteredItems = $itemsCollection->unique()->filter(function ($value) {
                    return !is_null($value) && $value !== ''; // Filtrar nulos y strings vacíos
                })->values();

                if ($filteredItems->isEmpty()) {
                    return $defaultText;
                }
                return '<ul>' . $filteredItems->map(fn($item) => "<li>" . htmlspecialchars($item, ENT_QUOTES, 'UTF-8') . "</li>")->implode('') . '</ul>';
            };
            
            // Helper para generar listas HTML agregadas (con conteo/suma) - Lo mantenemos por si se usa en 'defectos'
            $generateAggregatedHtmlList = function ($collection, $relationAccessor, $nameProperty, $valueProperty = null, $defaultText = 'N/A') {
                // ... (definición original de $generateAggregatedHtmlList sin cambios)
                $aggregated = [];
                foreach ($collection as $item) {
                    $relatedItems = null;
                    // Acceder a la relación, incluso si está anidada (ej. 'screen.defectos')
                    $relations = explode('.', $relationAccessor);
                    $tempItem = $item;
                    foreach ($relations as $relationName) {
                        if (isset($tempItem->{$relationName})) {
                            $tempItem = $tempItem->{$relationName};
                        } else {
                            $tempItem = null;
                            break;
                        }
                    }
                    $relatedItems = $tempItem;

                    if ($relatedItems && is_iterable($relatedItems)) {
                        foreach ($relatedItems as $relatedItem) {
                            $name = isset($relatedItem->{$nameProperty}) ? trim($relatedItem->{$nameProperty}) : null;
                            if ($name) {
                                if ($valueProperty && isset($relatedItem->{$valueProperty}) && is_numeric($relatedItem->{$valueProperty})) {
                                    $aggregated[$name] = ($aggregated[$name] ?? 0) + $relatedItem->{$valueProperty};
                                } elseif (!$valueProperty) { // Si no hay valueProperty, contamos ocurrencias
                                    $aggregated[$name] = ($aggregated[$name] ?? 0) + 1;
                                }
                            }
                        }
                    } elseif ($relatedItems && is_object($relatedItems) && !$valueProperty) { // Caso para relación a objeto único (no colección)
                        $name = isset($relatedItems->{$nameProperty}) ? trim($relatedItems->{$nameProperty}) : null;
                        if ($name) {
                            $aggregated[$name] = ($aggregated[$name] ?? 0) + 1;
                        }
                    }
                }

                if (empty($aggregated)) {
                    return $defaultText;
                }

                $listContent = '';
                foreach ($aggregated as $name => $countOrSum) {
                    $displayValue = ($valueProperty || $countOrSum > 1) ? " ({$countOrSum})" : ""; // Mostrar valor solo si es suma o conteo > 1
                    $listContent .= "<li>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . $displayValue . "</li>";
                }
                return '<ul>' . $listContent . '</ul>';
            };


            $panelesTexto = $generateHtmlList($group->pluck('panel'));
            $maquinasTexto = $generateHtmlList($group->pluck('maquina'));
            $graficasTexto = $generateHtmlList($group->pluck('grafica'));
            $clientesTexto = $generateHtmlList($group->pluck('cliente'));
            
            // Para técnico_screen y acción_correctiva, que vienen de la relación 'screen'
            $tecnicosTexto = $generateHtmlList($group->pluck('screen.nombre_tecnico'));
            $accionesCorrectivasTexto = $generateHtmlList($group->pluck('screen.accion_correctiva'), 'N/A');
            
            // Para defectos (agregados con suma de cantidad)
            $defectosTexto = $generateAggregatedHtmlList($group, 'screen.defectos', 'nombre', 'cantidad', 'N/A');
            
            // MODIFICACIÓN AQUÍ: Cambiar a $generateHtmlList para técnicas y fibras
            // Se asume que 'tecnicas' y 'fibras' son relaciones que devuelven una colección de objetos,
            // y cada objeto tiene una propiedad 'nombre'.
            $tecnicasTexto = $generateHtmlList(
                $group->pluck('tecnicas')->flatten()->pluck('nombre'), // Obtiene todos los nombres de todas las técnicas del grupo
                'Sin técnicas'
            );
            $fibrasTexto = $generateHtmlList(
                $group->pluck('fibras')->flatten()->pluck('nombre'), // Obtiene todos los nombres de todas las fibras del grupo
                'Sin fibras'
            );

            // Campos directos, con fallback y sanitización
            $op = htmlspecialchars($first->op ?? 'N/A', ENT_QUOTES, 'UTF-8');
            $estilo = htmlspecialchars($first->estilo ?? 'N/A', ENT_QUOTES, 'UTF-8');
            $color = htmlspecialchars($first->color ?? 'N/A', ENT_QUOTES, 'UTF-8');

            // Suma de cantidad, asegurando que solo se sumen números
            $totalCantidad = $group->sum(function($item) {
                return isset($item->cantidad) && is_numeric($item->cantidad) ? $item->cantidad : 0;
            });

            return [
                'op'                => $op,
                'panel'             => $panelesTexto,
                'maquina'           => $maquinasTexto,
                'tecnicas'          => $tecnicasTexto, // Mostrará lista de nombres únicos
                'fibras'            => $fibrasTexto,   // Mostrará lista de nombres únicos
                'grafica'           => $graficasTexto,
                'cliente'           => $clientesTexto,
                'estilo'            => $estilo,
                'color'             => $color,
                'tecnico_screen'    => $tecnicosTexto,
                'cantidad'          => $totalCantidad, 
                'defectos'          => $defectosTexto,
                'accion_correctiva' => $accionesCorrectivasTexto,
            ];
        })->values(); 

        return response()->json($result);
    }

    public function getScreenStats(Request $request)
    {
        $auditorDato = Auth::user()->name;
        $auditorPuesto = Auth::user()->puesto;

        $fechaInput = $request->input('fecha');
        $fechaSeleccionada = $fechaInput ? Carbon::parse($fechaInput)->toDateString() : Carbon::today()->toDateString();

        $query = InspeccionHorno::with(['screen.defectos']) // Eager load para eficiencia
            ->whereHas('screen')
            ->whereDate('created_at', $fechaSeleccionada);

        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            $query->where('auditor', $auditorDato);
        }

        $inspecciones = $query->get();

        // 1. Calcular la Cantidad total revisada
        // El método sum() de Eloquent en una colección ya devuelve 0 si la colección está vacía
        // o si todos los valores son null para el campo sumado.
        // Casteamos a float para asegurar que sea un número de punto flotante (ej. 0.0).
        $cantidad_total_revisada = (float) $inspecciones->sum('cantidad');

        // 2. Calcular la Cantidad total de defectos
        $cantidad_defectos = 0.0; // Inicializar como float para consistencia

        foreach ($inspecciones as $inspeccion) {
            // Verificar que las relaciones existan antes de intentar acceder a ellas
            if ($inspeccion->screen && $inspeccion->screen->defectos) {
                foreach ($inspeccion->screen->defectos as $defecto) {
                    // Asegurarse de que $defecto->cantidad existe, es numérico y no es null antes de sumar
                    // Si $defecto->cantidad es null, isset() será false.
                    // is_numeric() verifica si es un número o un string numérico.
                    if (isset($defecto->cantidad) && is_numeric($defecto->cantidad)) {
                        $cantidad_defectos += (float) $defecto->cantidad;
                    }
                    // Si no cumple la condición, no se suma nada (efectivamente se suma 0 para ese defecto)
                }
            }
        }

        // 3. Calcular el porcentaje de defectos
        $porcentaje_defectos = 0.0; // Inicializar como float

        // Asegurarse de que $cantidad_total_revisada sea mayor que 0 para evitar división por cero.
        // Las variables $cantidad_defectos y $cantidad_total_revisada ya son floats.
        if ($cantidad_total_revisada > 0) {
            $porcentaje_defectos = ($cantidad_defectos / $cantidad_total_revisada) * 100;
        }

        // Redondear el porcentaje a 2 decimales. round() devuelve un float.
        // Este paso ya estaba en tu código y es correcto.
        $porcentaje_defectos_redondeado = round($porcentaje_defectos, 2);

        // Retornar los datos estadísticos en formato JSON.
        // Todos los valores ya deberían ser floats (0.0 en caso de no datos o cálculos no válidos).
        return response()->json([
            'cantidad_total_revisada' => $cantidad_total_revisada,
            'cantidad_defectos'       => $cantidad_defectos,
            'porcentaje_defectos'     => $porcentaje_defectos_redondeado
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
        // No se añade el chequeo de $inspecciones->isEmpty() para mantener la lógica original.
        // Si $inspecciones está vacía, $grouped->map resultará en una colección vacía,
        // y la respuesta JSON será [] como es usual.

        $grouped = $inspecciones->groupBy('op');

        // Preparar los datos finales
        $result = $grouped->map(function (Collection $group) {
            // Tomamos el primer registro del grupo para campos comunes
            $first = $group->first();

            // Sumar la cantidad total de los registros del mismo "op"
            $totalCantidad = $group->sum('plancha.piezas_auditadas'); // Se mantiene como suma numérica

            // Función auxiliar para generar listas HTML o 'N/A' (VERSIÓN ORIGINAL DEL USUARIO)
            $generateHtmlListOrNA = function (Collection $items, $pluckPath) {
                $filteredItems = $items->pluck($pluckPath)
                                    ->map(fn($item) => is_scalar($item) ? trim((string)$item) : null)
                                    ->filter(fn($item) => $item !== null && $item !== '')
                                    ->unique()
                                    ->values() // Re-indexar para asegurar un array limpio
                                    ->all();
                if (empty($filteredItems)) {
                    return 'N/A';
                }
                return '<ul>' . implode('', array_map(fn($item) => "<li>{$item}</li>", $filteredItems)) . '</ul>';
            };
            
            $panelesTexto = $generateHtmlListOrNA($group, 'panel');
            $maquinasTexto = $generateHtmlListOrNA($group, 'maquina');
            $graficasTexto = $generateHtmlListOrNA($group, 'grafica');
            $clientesTexto = $generateHtmlListOrNA($group, 'cliente');
            $tecnicosTexto = $generateHtmlListOrNA($group, 'plancha.nombre_tecnico');
            $accionesCorrectivasTexto = $generateHtmlListOrNA($group, 'plancha.accion_correctiva');


            // Agrupar y sumar la cantidad de defectos por nombre (LÓGICA ORIGINAL DEL USUARIO)
            $defectosAggregados = [];
            foreach ($group as $registro) {
                if ($registro->plancha && $registro->plancha->defectos) {
                    foreach ($registro->plancha->defectos as $defecto) {
                        $nombre = trim($defecto->nombre ?? '');
                        if (!empty($nombre)) { // Solo procesar si el nombre del defecto no está vacío
                            $cantidadDefecto = $defecto->cantidad ?? 0; // Asumir 0 si la cantidad es null
                            if (isset($defectosAggregados[$nombre])) {
                                $defectosAggregados[$nombre] += $cantidadDefecto;
                            } else {
                                $defectosAggregados[$nombre] = $cantidadDefecto;
                            }
                        }
                    }
                }
            }
            $defectosTexto = !empty($defectosAggregados)
                ? '<ul>' . implode('', array_map(fn($nombre, $cantidad) => "<li>{$nombre} ({$cantidad})</li>", 
                array_keys($defectosAggregados), array_values($defectosAggregados))) . '</ul>'
                : 'N/A';

            // ---- INICIO DE CAMBIOS EXCLUSIVOS PARA TÉCNICAS Y FIBRAS ----

            // Nuevo helper para generar listas HTML de ítems únicos a partir de una colección aplanada
            // (sin conteo y sin htmlspecialchars en los ítems para ser consistente con $generateHtmlListOrNA original)
            $generateUniqueItemListHtml = function (Collection $flatNameCollection, $defaultText = 'N/A') {
                $uniqueNames = $flatNameCollection
                    ->map(fn($name) => is_scalar($name) ? trim((string)$name) : null)
                    ->filter(fn($name) => $name !== null && $name !== '')
                    ->unique()
                    ->values(); // Devuelve una colección para poder usar ->map()->implode()

                if ($uniqueNames->isEmpty()) {
                    return $defaultText;
                }
                // Genera la lista sin htmlspecialchars para ser consistente con el $generateHtmlListOrNA original
                return '<ul>' . $uniqueNames->map(fn($name) => "<li>{$name}</li>")->implode('') . '</ul>';
            };

            // Procesamiento para "tecnicas" para obtener una lista de nombres únicos
            $nombresTecnicas = $group->pluck('tecnicas') // Colección de colecciones de objetos Tecnica
                                    ->flatten()          // Colección de objetos Tecnica
                                    ->pluck('nombre');    // Colección de nombres de técnicas (strings)
            $tecnicasTexto = $generateUniqueItemListHtml($nombresTecnicas, 'N/A'); // Puedes usar 'Sin técnicas' como default si prefieres

            // Procesamiento para "fibras" para obtener una lista de nombres únicos
            $nombresFibras = $group->pluck('fibras')   // Colección de colecciones de objetos Fibra
                                ->flatten()        // Colección de objetos Fibra
                                ->pluck('nombre');  // Colección de nombres de fibras (strings)
            $fibrasTexto = $generateUniqueItemListHtml($nombresFibras, 'N/A');   // Puedes usar 'Sin fibras' como default si prefieres

            // ---- FIN DE CAMBIOS EXCLUSIVOS PARA TÉCNICAS Y FIBRAS ----

            return [
                'op'                => !empty(trim((string)($first->op ?? ''))) ? $first->op : 'N/A',
                'panel'             => $panelesTexto,
                'maquina'           => $maquinasTexto,
                'tecnicas'          => $tecnicasTexto, // MODIFICADO: Mostrará lista de nombres únicos sin conteo
                'fibras'            => $fibrasTexto,   // MODIFICADO: Mostrará lista de nombres únicos sin conteo
                'grafica'           => $graficasTexto,
                'cliente'           => $clientesTexto,
                'estilo'            => !empty(trim((string)($first->estilo ?? ''))) ? $first->estilo : 'N/A',
                'color'             => !empty(trim((string)($first->color ?? ''))) ? $first->color : 'N/A',
                'tecnico_screen'    => $tecnicosTexto, // Mantenido el nombre de clave original
                'cantidad'          => $totalCantidad, // Se mantiene como número
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

        $query = InspeccionHorno::with(['plancha.defectos']) // Eager load para eficiencia
            ->whereHas('plancha') // Asegura que la inspección tiene una 'plancha' asociada
            ->whereDate('created_at', $fechaSeleccionada);

        if ($auditorPuesto !== 'Administrador' && $auditorPuesto !== 'Gerente de Calidad') {
            $query->where('auditor', $auditorDato);
        }
        
        $inspecciones = $query->get();

        // 1. Calcular la Cantidad total revisada
        // Sumaremos 'piezas_auditadas' de la relación 'plancha'.
        // Usamos un callback en sum() para manejar de forma segura el acceso a la propiedad de la relación.
        $cantidad_total_revisada = $inspecciones->sum(function ($inspeccion) {
            // Se asume que 'plancha' es una relación (ej. uno a uno) y ya está cargada.
            // 'whereHas' asegura que $inspeccion->plancha existe.
            if ($inspeccion->plancha && isset($inspeccion->plancha->piezas_auditadas) && is_numeric($inspeccion->plancha->piezas_auditadas)) {
                return (float) $inspeccion->plancha->piezas_auditadas;
            }
            return 0.0; // Si no hay plancha, o piezas_auditadas no es numérico/no existe, sumar 0 para esta inspección.
        });
        // $cantidad_total_revisada ya será un float (ej. 0.0) debido al retorno de 0.0 o (float)valor en el callback.

        // 2. Calcular la Cantidad total de defectos
        $cantidad_defectos = 0.0; // Inicializar como float para consistencia

        foreach ($inspecciones as $inspeccion) {
            // Verificar que las relaciones 'plancha' y 'plancha.defectos' existan
            if ($inspeccion->plancha && $inspeccion->plancha->defectos) {
                foreach ($inspeccion->plancha->defectos as $defecto) {
                    // Asegurarse de que $defecto->cantidad existe, es numérico y no es null antes de sumar
                    if (isset($defecto->cantidad) && is_numeric($defecto->cantidad)) {
                        $cantidad_defectos += (float) $defecto->cantidad;
                    }
                    // Si no cumple la condición, no se suma nada (efectivamente se suma 0 para ese defecto)
                }
            }
        }

        // 3. Calcular el porcentaje de defectos
        $porcentaje_defectos = 0.0; // Inicializar como float

        // Asegurarse de que $cantidad_total_revisada sea mayor que 0 para evitar división por cero.
        if ($cantidad_total_revisada > 0) {
            $porcentaje_defectos = ($cantidad_defectos / $cantidad_total_revisada) * 100;
        }

        // Redondear el porcentaje a 2 decimales. round() devuelve un float.
        // Este paso ya estaba en tu código y es correcto.
        $porcentaje_defectos_redondeado = round($porcentaje_defectos, 2);

        // Retornar los datos estadísticos en formato JSON.
        // Todos los valores ya deberían ser floats.
        return response()->json([
            'cantidad_total_revisada' => $cantidad_total_revisada,
            'cantidad_defectos'       => $cantidad_defectos,
            'porcentaje_defectos'     => $porcentaje_defectos_redondeado
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
