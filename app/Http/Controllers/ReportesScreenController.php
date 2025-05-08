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

class ReportesScreenController extends Controller
{
    public function index(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.reporte', compact('mesesEnEspanol'));
    }


    public function bultosPorDia(Request $request)
    {
        // Obtener el inicio y fin del día actual
        $inicioDia = Carbon::now()->startOfDay();
        $finDia    = Carbon::now()->endOfDay();
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
        // Obtener todos los registros con sus relaciones
        $inspecciones = InspeccionHorno::with(['screen.defectos', 'tecnicas', 'fibras'])
                            ->whereHas('screen')
                            ->orderBy('created_at', 'desc')
                            ->where('auditor', $auditorDato)
                            ->whereDate('created_at', Carbon::today())
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

    public function getPlanchaData()
    {
        $auditorDato = Auth::user()->name;
        // Obtener todos los registros con sus relaciones
        $inspecciones = InspeccionHorno::with(['plancha.defectos', 'tecnicas', 'fibras'])
                            ->whereHas('plancha')
                            ->whereDate('created_at', Carbon::today())
                            ->orderBy('created_at', 'desc')
                            ->where('auditor', $auditorDato)
                            ->get();

        // Agrupar los registros por la columna "op"
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

    public function getPlanchaStats()
    {
        // Obtener las inspecciones que tengan la relación "plancha" (y sus defectos)
        $inspecciones = InspeccionHorno::with(['plancha.defectos'])
                            ->whereHas('plancha')
                            ->whereDate('created_at', Carbon::today())
                            ->get();

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
