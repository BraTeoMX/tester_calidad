<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobAQLTemporal;
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
        $pageSlug ='';
        $fechaActual = Carbon::now()->toDateString();
        $auditorDato = Auth::user()->name;
        $auditorPlanta = Auth::user()->Planta;
        $tipoUsuario = Auth::user()->puesto;
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        if($auditorPlanta == "Planta1"){
            $datoPlanta = "1";
        }else{
            $datoPlanta = "2";
        }

        return view('kanban.index', compact('mesesEnEspanol', 'pageSlug'));
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

        // Crear instancia de ReporteKanban
        $kanban = new ReporteKanban();
        $kanban->op = $request->input('op');
        $kanban->cliente = $request->input('cliente');
        $kanban->estilo = $request->input('estilo');
        $kanban->fecha_corte = $request->input('fecha');
        $kanban->piezas = $request->input('piezas_total');
        $kanban->planta = $request->input('accion');

        $kanban->save(); // Guarda el registro principal

        return response()->json(['mensaje' => 'Datos guardados correctamente']);
    }

    public function actualizar(Request $request)
    {
        Log::info('Datos recibidos: ' . json_encode($request->all()));

        $kanban = ReporteKanban::find($request->input('id'));

        if (!$kanban) {
            return response()->json(['mensaje' => 'Registro no encontrado.'], 404);
        }

        // Actualizar campos
        $kanban->estatus = $request->input('accion');
        $kanban->fecha_liberacion = null;
        $kanban->fecha_parcial = null;
        $kanban->fecha_rechazo = null;

        if ($kanban->estatus == '1') {
            $kanban->fecha_liberacion = now();
        } elseif ($kanban->estatus == '2') {
            $kanban->fecha_parcial = now();
        } elseif ($kanban->estatus == '3') {
            $kanban->fecha_rechazo = now();
        }

        $kanban->save();

        $comentarios = $request->input('comentarios');

        if (is_array($comentarios) && count($comentarios)) {
            foreach ($comentarios as $comentario) {
                $comentarioKanban = new ReporteKanbanComentario();
                $comentarioKanban->reporte_kanban_id = $kanban->id;
                $comentarioKanban->nombre = $comentario;
                $comentarioKanban->save();
            }
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
                'fecha_almacen' => $registro->fecha_almacen ? Carbon::parse($registro->fecha_almacen)->format('Y-m-d H:i') : 'N/A',
                'op' => $registro->op ?? 'N/A',
                'cliente' => $registro->cliente ?? 'N/A',
                'estilo' => $registro->estilo ?? 'N/A',
                'estatus' => $this->obtenerNombreEstatus($registro->estatus),
                'comentarios' => $registro->comentarios->isNotEmpty()
                    ? '<ul class="mb-0 pl-3">' . $registro->comentarios->pluck('nombre')->map(fn($c) => "<li>$c</li>")->implode('') . '</ul>'
                    : 'N/A',
                'fecha_parcial' => $registro->fecha_parcial  
                    ? Carbon::parse($registro->fecha_parcial)->format('Y-m-d H:i') 
                    : 'N/A',
                'fecha_liberacion' => $registro->fecha_liberacion 
                    ? Carbon::parse($registro->fecha_liberacion)->format('Y-m-d H:i') 
                    : 'N/A',
                'id' => $registro->id
            ];
        });

        return response()->json($data);
    }

    private function obtenerNombreEstatus($codigo)
    {
        return match($codigo) {
            1 => 'Aceptado',
            2 => 'Parcial',
            3 => 'Rechazado',
            default => 'N/A',
        };
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
}
