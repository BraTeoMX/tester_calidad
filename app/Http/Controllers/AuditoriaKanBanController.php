<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobAQL;
use App\Models\AuditoriaAQL;
use App\Models\JobAQLHistorial;
use App\Models\CatalogoComentarioKanban;
use App\Models\ReporteKanban;
use App\Models\ReporteKanbanComentario;
use App\Models\TicketCorte;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AuditoriaKanBanController extends Controller
{

    public function index(Request $request)
    {
        $pageSlug = '';
        $fechaActual = Carbon::now()->toDateString();
        $auditorDato = Auth::user()->name;
        $auditorPlanta = Auth::user()->Planta;
        $tipoUsuario = Auth::user()->puesto;
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

        if ($auditorPlanta == "Planta1") {
            $datoPlanta = "1";
        } else {
            $datoPlanta = "2";
        }

        return view('kanban.index', compact('mesesEnEspanol', 'pageSlug'));
    }

    public function reporte(Request $request)
    {
        $mesesEnEspanol = [/* ... */];

        if ($request->ajax()) {
            $query = ReporteKanban::query();

            if ($request->filled('desde') && $request->filled('hasta')) {
                $query->whereBetween('fecha_corte', [
                    $request->desde . ' 00:00:00',
                    $request->hasta . ' 23:59:59'
                ]);
            }
            if ($request->filled('op'))      $query->where('op', $request->op);
            if ($request->filled('planta'))  $query->where('planta', $request->planta);
            if ($request->filled('estatus')) $query->where('estatus', $request->estatus);

            $registros = $query->get();

            $produccion = $registros->whereNotNull('fecha_online')->count();

            // KPIs después de procesar
            $kpis = [
                'total_op'     => $registros->count(),
                'total_piezas' => $registros->sum('piezas'),
                'aceptados'    => $registros->where('estatus', 1)->count(),
                'parciales'    => $registros->where('estatus', 2)->count(),
                'rechazados'   => $registros->where('estatus', 3)->count(),
            ];

            return response()->json([
                'kpis'      => $kpis,
                'registros' => $registros, // Aquí ya se incluye fecha_online
                'produccion' => $produccion,
            ]);
        }

        return view('kanban.reporte', compact('mesesEnEspanol'));
    }

    public function getOpciones(Request $request)
    {
        $search = $request->input('term');

        if (strlen($search) < 4) {
            return response()->json([]);
        }

        $cacheKey = 'opciones_kanban_' . md5($search); // Clave específica por término
        $backupKey = $cacheKey . '_backup';

        // Cache con duración de 1 minuto
        $datos = Cache::remember($cacheKey, 60, function () use ($search) {
            $resultados = TicketCorte::select([
                'op',
                DB::raw('SUM(piezas) as piezas_total'),
                DB::raw('MIN(fecha) as fecha'),
                DB::raw('MIN(cliente) as cliente'),
                DB::raw('MIN(estilo) as estilo'),
            ])
                ->where('op', 'like', '%' . $search . '%')
                ->groupBy('op')
                ->get();

            // No guardar en cache si no hay resultados
            if ($resultados->isEmpty()) {
                // Devolver null para evitar cache de vacío
                return null;
            }

            // Si hay resultados, guardar también una copia como backup
            Cache::put('opciones_kanban_' . md5($search) . '_backup', $resultados, now()->addMinutes(10));
            return $resultados;
        });

        // Si remember devolvió null (por ser vacío y no cachear), buscamos backup
        if (is_null($datos)) {
            $datos = Cache::get($backupKey, collect()); // Si no hay backup, devolver colección vacía
        }

        return response()->json($datos);
    }

    public function obtenerComentarios(Request $request)
    {
        $query = $request->input('q');

        $comentarios = CatalogoComentarioKanban::where('estatus', 1)
            ->when($query, function ($qBuilder) use ($query) {
                $qBuilder->where('nombre', 'like', '%' . $query . '%');
            })
            ->orderBy('nombre')
            ->limit(100) // Puedes ajustar este límite si lo ves necesario
            ->get(['nombre']);

        return response()->json($comentarios);
    }

    public function crearComentario(Request $request)
    {
        //Log::info($request->all());
        $request->validate([
            'nombre' => 'required|string|max:255'
        ]);

        $comentario = CatalogoComentarioKanban::create([
            'nombre' => strtoupper($request->input('nombre')), // se guarda en mayúsculas, si se requiere
            'estatus' => 1
        ]);

        return response()->json([
            'mensaje' => 'Comentario creado correctamente',
            'comentario' => $comentario
        ]);
    }

    public function guardar(Request $request)
    {
        //Log::info('Datos recibidos: ' . json_encode($request->all()));
        $auditorDato = Auth::user()->name;
        // Crear instancia de ReporteKanban
        $kanban = new ReporteKanban();
        $kanban->auditor = $auditorDato;
        $kanban->fecha_corte = $request->input('fecha');
        $kanban->fecha_almacen = now();
        $kanban->op = $request->input('op');
        $kanban->cliente = $request->input('cliente');
        $kanban->estilo = $request->input('estilo');
        $kanban->piezas = $request->input('piezas_total');
        $kanban->planta = $request->input('accion');

        $kanban->save(); // Guarda el registro principal

        return response()->json(['mensaje' => 'Datos guardados correctamente']);
    }

    public function actualizar(Request $request)
    {
        //Log::info('Datos recibidos: ' . json_encode($request->all()));

        $kanban = ReporteKanban::find($request->input('id'));

        if (!$kanban) {
            return response()->json(['mensaje' => 'Registro no encontrado.'], 404);
        }

        // Actualizar campos del registro principal
        $kanban->estatus = $request->input('accion');
        $kanban->fecha_liberacion = null;
        $kanban->fecha_parcial   = null;
        $kanban->fecha_rechazo   = null;

        if ($kanban->estatus == '1') {
            $kanban->fecha_liberacion = now();
        } elseif ($kanban->estatus == '2') {
            $kanban->fecha_parcial = now();
        } elseif ($kanban->estatus == '3') {
            $kanban->fecha_rechazo = now();
        }

        $kanban->save();

        // Manejo de comentarios de forma flexible
        // Se espera que $comentariosNuevos sea un array enviado desde el AJAX (por ejemplo, ["coment1", "coment2"])
        $comentariosNuevos = $request->input('comentarios', []);

        // Obtener los comentarios existentes en la base para este registro (sólo los nombres)
        $comentariosExistentes = ReporteKanbanComentario::where('reporte_kanban_id', $kanban->id)
            ->pluck('nombre')
            ->toArray();

        // Determinar cuáles comentarios se deben eliminar:
        // Es decir, aquellos que existen en la base y NO están en la lista enviada.
        $paraEliminar = array_diff($comentariosExistentes, $comentariosNuevos);
        if (!empty($paraEliminar)) {
            ReporteKanbanComentario::where('reporte_kanban_id', $kanban->id)
                ->whereIn('nombre', $paraEliminar)
                ->delete();
        }

        // Determinar cuáles comentarios se deben agregar:
        // Aquellos que están en la lista enviada y NO existen en la base.
        $paraAgregar = array_diff($comentariosNuevos, $comentariosExistentes);
        foreach ($paraAgregar as $comentario) {
            $comentarioKanban = new ReporteKanbanComentario();
            $comentarioKanban->reporte_kanban_id = $kanban->id;
            $comentarioKanban->nombre = $comentario;
            $comentarioKanban->save();
        }

        return response()->json(['mensaje' => 'Registro actualizado correctamente']);
    }


    public function obtenerParciales(Request $request)
    {
        $parciales = ReporteKanban::where('estatus', 2)
            ->whereNull('fecha_liberacion')
            ->get(['id', 'op', 'cliente', 'estilo', 'piezas']);

        return response()->json($parciales);
    }

    public function liberarParcial(Request $request)
    {
        $id = $request->input('id');

        $registro = ReporteKanban::find($id);

        if (!$registro) {
            return response()->json(['error' => 'Registro no encontrado.'], 404);
        }

        // Actualiza la fecha de liberación
        $registro->fecha_liberacion = Carbon::now();
        $registro->save();

        return response()->json(['mensaje' => 'Registro liberado correctamente.']);
    }

    public function obtenerRegistrosHoy(Request $request)
    {
        $hoy = Carbon::today();

        $registros = ReporteKanban::with('comentarios')
            ->whereDate('created_at', $hoy)
            ->get();

        $data = $registros->map(function ($registro) {
            return [
                'fecha_corte'    => $registro->fecha_corte
                    ? Carbon::parse($registro->fecha_corte)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_almacen'    => $registro->fecha_almacen
                    ? Carbon::parse($registro->fecha_almacen)->format('Y-m-d H:i')
                    : 'N/A',
                'op'               => $registro->op ?? 'N/A',
                'cliente'          => $registro->cliente ?? 'N/A',
                'estilo'           => $registro->estilo ?? 'N/A',
                // Retornamos el valor real de estatus (1,2,3) o vacío para que el select muestre "Selecciona"
                'estatus'          => $registro->estatus ?? '',
                // Retornamos los comentarios como cadena separada por coma  
                'comentarios'      => $registro->comentarios->pluck('nombre')->implode(','),
                'fecha_parcial'    => $registro->fecha_parcial
                    ? Carbon::parse($registro->fecha_parcial)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_liberacion' => $registro->fecha_liberacion
                    ? Carbon::parse($registro->fecha_liberacion)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_rechazo' => $registro->fecha_rechazo
                    ? Carbon::parse($registro->fecha_rechazo)->format('Y-m-d H:i')
                    : 'N/A',
                'id'               => $registro->id,
            ];
        });

        return response()->json($data);
    }

    public function eliminar(Request $request)
    {
        $id = $request->input('id');

        $registro = ReporteKanban::find($id);

        if (!$registro) {
            return response()->json(['mensaje' => 'Registro no encontrado.'], 404);
        }

        $registro->delete();

        return response()->json(['mensaje' => 'Registro eliminado correctamente.']);
    }

    public function checkActualizacionOnline()
    {
        // Clave única para que no se repita dentro de 5 horas
        $cacheKey = 'actualizacion_online_kanban';

        if (!Cache::has($cacheKey)) {
            // Tiempo hasta el próximo intento: 5 horas
            Cache::put($cacheKey, now(), now()->addHours(5));

            // Solo registros sin fecha_online
            $registros = ReporteKanban::whereNull('fecha_online')->get();

            // Solo si hay registros
            if ($registros->count() > 0 && JobAQL::exists()) {
                foreach ($registros as $registro) {
                    $job = JobAQL::where('prodid', $registro->op)
                                ->where('oprname', 'ON LINE')
                                ->orderBy('payrolldate', 'asc')
                                ->first();

                    if ($job && $job->payrolldate) {
                        $registro->fecha_online = $job->payrolldate;
                        $registro->save();
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

}
