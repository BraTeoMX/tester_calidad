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
use App\Models\TicketOffline;
use App\Models\TicketApproved;
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

    public function actualizarMasivo(Request $request)
    {
        $registrosInput = $request->input('registros', []);
        $errores = [];
        $registrosActualizados = 0;
        $registrosOmitidos = 0; // Nuevo contador para registros que no necesitaron actualización

        if (empty($registrosInput)) {
            return response()->json(['mensaje' => 'No se proporcionaron registros para actualizar.'], 400);
        }

        // Recomendado: Usar una transacción para asegurar la atomicidad de las operaciones
        DB::beginTransaction();
        try {
            foreach ($registrosInput as $registroData) {
                if (empty($registroData['id'])) {
                    $errores[] = "Se recibió un registro sin ID.";
                    continue;
                }

                $kanban = ReporteKanban::find($registroData['id']);

                if (!$kanban) {
                    $errores[] = "Registro con ID {$registroData['id']} no encontrado.";
                    continue;
                }

                $nuevoEstatus = (string) ($registroData['accion'] ?? ''); // Aseguramos que sea string, default a vacío

                // ---- INICIO DE LA LÓGICA DE OMISIÓN ----
                $omitirEsteRegistro = false;

                // Cambiar de === a == para la comparación principal entre el nuevo estado (string) y el actual (int/DB type)
                if ($nuevoEstatus == $kanban->estatus) { 
                    // Si entramos aquí, significa que '1' == 1 (true), o '2' == 2 (true), o '' == 0 (true, si así se guarda '' en DB) etc.
                    // Ahora, la lógica interna sigue usando $nuevoEstatus (string) contra strings literales, lo cual está bien.
                    if ($nuevoEstatus === '1' && $kanban->fecha_liberacion !== null) {
                        $omitirEsteRegistro = true;
                    } elseif ($nuevoEstatus === '2' && $kanban->fecha_parcial !== null) {
                        $omitirEsteRegistro = true;
                    } elseif ($nuevoEstatus === '3' && $kanban->fecha_rechazo !== null) {
                        $omitirEsteRegistro = true;
                    } elseif ($nuevoEstatus === '' && // Si el nuevo estado es "sin seleccionar" (string vacío)
                            $kanban->fecha_liberacion === null && 
                            $kanban->fecha_parcial === null && 
                            $kanban->fecha_rechazo === null) {
                        // Y si kanban->estatus también representa un estado "vacío" (ej. 0, que es == a '')
                        // y no hay fechas establecidas.
                        $omitirEsteRegistro = true;
                    }
                }

                if ($omitirEsteRegistro) {
                    $registrosOmitidos++;
                    continue; 
                }
                // ---- FIN DE LA LÓGICA DE OMISIÓN ----

                // Si no se omite, se procede con la actualización
                $kanban->estatus = $nuevoEstatus;

                // Reiniciar fechas antes de asignar la nueva basada en el estatus
                // Esta parte es crucial: si el estatus cambia, la fecha anterior se borra
                // y se establece la nueva. Si el estatus es el mismo pero la fecha estaba null, se establece.
                $kanban->fecha_liberacion = null;
                $kanban->fecha_parcial    = null;
                $kanban->fecha_rechazo    = null;

                if ($nuevoEstatus === '1') { // Aceptado
                    $kanban->fecha_liberacion = Carbon::now();
                } elseif ($nuevoEstatus === '2') { // Parcial
                    $kanban->fecha_parcial = Carbon::now();
                } elseif ($nuevoEstatus === '3') { // Rechazado
                    $kanban->fecha_rechazo = Carbon::now();
                }
                // Si $nuevoEstatus es '', todas las fechas quedan null, lo cual es correcto.

                $kanban->save(); // Guardar cambios en el registro principal

                // Manejo de comentarios (solo si el registro principal fue procesado)
                // Convertimos $registroData['comentarios'] a array si es string separado por comas,
                // o lo tomamos como array si ya lo es. Ajustar según cómo llegue del frontend.
                $comentariosNuevosInput = $registroData['comentarios'] ?? ''; // Puede ser un string o array
                
                // Si los comentarios vienen como un string separado por comas y pueden tener espacios
                if (is_string($comentariosNuevosInput)) {
                    $comentariosNuevos = !empty($comentariosNuevosInput) ? array_map('trim', explode(',', $comentariosNuevosInput)) : [];
                } elseif (is_array($comentariosNuevosInput)) {
                    $comentariosNuevos = $comentariosNuevosInput;
                } else {
                    $comentariosNuevos = [];
                }
                // Filtrar elementos vacíos que puedan resultar del explode si hay comas consecutivas o al final
                $comentariosNuevos = array_filter($comentariosNuevos, function($value) { return !empty($value); });


                $comentariosExistentes = ReporteKanbanComentario::where('reporte_kanban_id', $kanban->id)
                    ->pluck('nombre')
                    ->toArray();

                // Comentarios para eliminar
                $paraEliminar = array_diff($comentariosExistentes, $comentariosNuevos);
                if (!empty($paraEliminar)) {
                    ReporteKanbanComentario::where('reporte_kanban_id', $kanban->id)
                        ->whereIn('nombre', $paraEliminar)
                        ->delete();
                }

                // Comentarios para agregar
                $paraAgregar = array_diff($comentariosNuevos, $comentariosExistentes);
                foreach ($paraAgregar as $comentarioNombre) {
                    // Asegurarse de no guardar comentarios vacíos (ya filtrado arriba, pero doble check no daña)
                    if (!empty($comentarioNombre)) {
                        ReporteKanbanComentario::create([
                            'reporte_kanban_id' => $kanban->id,
                            'nombre' => $comentarioNombre,
                        ]);
                    }
                }
                $registrosActualizados++;
            }

            DB::commit(); // Confirmar todos los cambios si no hubo excepciones

            $mensaje = "Actualización masiva completada. {$registrosActualizados} registros actualizados.";
            if ($registrosOmitidos > 0) {
                $mensaje .= " {$registrosOmitidos} registros fueron omitidos por no requerir cambios.";
            }
            if (!empty($errores)) {
                $mensaje .= " Se encontraron algunos problemas.";
            }

            return response()->json([
                'mensaje' => $mensaje,
                'errores' => $errores // Enviar lista de errores si los hubo
            ], empty($errores) ? 200 : 207); // 200 OK o 207 Multi-Status si hubo errores parciales

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir cambios en caso de error
            // Agregar el error a la lista de errores para el usuario, si es apropiado
            $errores[] = "Error interno del servidor durante la actualización masiva. {$e->getMessage()}"; 
            return response()->json([
                'mensaje' => 'Ocurrió un error crítico durante la actualización masiva. No se procesaron todos los registros.',
                'errores' => $errores // Incluir el error de la excepción
            ], 500);
        }
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

    public function obtenerRegistrosHoy() // Mantengo el nombre original por ahora
    {
        // Fecha de inicio del rango: Hace 4 días a las 00:00:00
        $inicioRango = Carbon::today()->subDays(4)->startOfDay(); 

        // Fecha de fin del rango: Hoy a las 23:59:59
        $finRango = Carbon::today()->endOfDay();

        $registros = ReporteKanban::with('comentarios')
            // Filtra los registros donde 'created_at' está entre $inicioRango y $finRango
            ->whereBetween('created_at', [$inicioRango, $finRango])
            ->orderBy('created_at', 'asc') // Ordena los registros por fecha de creación
            ->get();

        $data = $registros->map(function ($registro) {
            return [
                'fecha_corte'    => $registro->fecha_corte
                    ? Carbon::parse($registro->fecha_corte)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_almacen'  => $registro->fecha_almacen
                    ? Carbon::parse($registro->fecha_almacen)->format('Y-m-d H:i')
                    : 'N/A',
                'op'             => $registro->op ?? 'N/A',
                'cliente'        => $registro->cliente ?? 'N/A',
                'estilo'         => $registro->estilo ?? 'N/A',
                'estatus'        => $registro->estatus ?? '',
                'comentarios'    => $registro->comentarios->pluck('nombre')->implode(','),
                'fecha_parcial'  => $registro->fecha_parcial
                    ? Carbon::parse($registro->fecha_parcial)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_liberacion' => $registro->fecha_liberacion
                    ? Carbon::parse($registro->fecha_liberacion)->format('Y-m-d H:i')
                    : 'N/A',
                'fecha_rechazo' => $registro->fecha_rechazo
                    ? Carbon::parse($registro->fecha_rechazo)->format('Y-m-d H:i')
                    : 'N/A',
                'id'             => $registro->id,
                'created_at_debug' => $registro->created_at->format('Y-m-d H:i:s') // Para depuración
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

    public function checkActualizacionesKanban()
    {
        $respuestaGlobal = ['status' => 'ok', 'message' => '', 'updates_performed' => []];
        $cacheKeyGlobal = 'actualizacion_kanban_global'; // Única clave de caché

        if (!Cache::has($cacheKeyGlobal)) {
            // Si la caché no está activa, procedemos con todas las actualizaciones
            // y luego establecemos la caché.
            Log::info("Iniciando todos los procesos de actualización de Kanban (caché no activa).");
            $respuestaGlobal['message'] = 'Procesos de actualización iniciados.';

            // --- 1. Proceso para fecha_online ---
            $registrosOnline = ReporteKanban::whereNull('fecha_online')->get();
            if ($registrosOnline->count() > 0 && class_exists(JobAQL::class)) {
                Log::info("Procesando 'fecha_online' para " . $registrosOnline->count() . " registros.");
                $updatesOnlineCount = 0;
                foreach ($registrosOnline as $registro) {
                    $jobOnline = JobAQL::where('prodid', $registro->op)
                                      ->where('oprname', 'ON LINE')
                                      ->orderBy('payrolldate', 'asc')
                                      ->first();

                    if ($jobOnline && $jobOnline->payrolldate) {
                        $registro->fecha_online = $jobOnline->payrolldate;
                        $registro->save();
                        $updatesOnlineCount++;
                        Log::info("ReporteKanban ID {$registro->id} actualizado con fecha_online: {$jobOnline->payrolldate}");
                    }
                }
                $respuestaGlobal['updates_performed']['online'] = "{$updatesOnlineCount} registros actualizados.";
            } else {
                $respuestaGlobal['updates_performed']['online'] = 'No se encontraron registros para actualizar o JobAQL no disponible.';
                Log::info("'fecha_online': No hay registros para actualizar o JobAQL no está disponible.");
            }

            // --- 2. Proceso para fecha_offline ---
            $registrosOffline = ReporteKanban::whereNull('fecha_offline')->get();
            if ($registrosOffline->count() > 0 && class_exists(TicketOffline::class)) {
                Log::info("Procesando 'fecha_offline' para " . $registrosOffline->count() . " registros.");
                $updatesOfflineCount = 0;
                foreach ($registrosOffline as $registro) {
                    $ticketOffline = TicketOffline::where('op', $registro->op)
                                                // ->orderBy('fecha', 'desc') // Ajusta si necesitas un orden específico
                                                ->first();

                    // CAMBIA 'fecha_generacion' por el nombre real de tu campo de fecha en TicketOffline
                    if ($ticketOffline && $ticketOffline->fecha) {
                        $registro->fecha_offline = $ticketOffline->fecha;
                        $registro->save();
                        $updatesOfflineCount++;
                        Log::info("ReporteKanban ID {$registro->id} actualizado con fecha_offline: {$ticketOffline->fecha}");
                    }
                }
                $respuestaGlobal['updates_performed']['offline'] = "{$updatesOfflineCount} registros actualizados.";
            } else {
                $respuestaGlobal['updates_performed']['offline'] = 'No se encontraron registros para actualizar o TicketOffline no disponible.';
                Log::info("'fecha_offline': No hay registros para actualizar o TicketOffline no está disponible.");
            }

            // --- 3. Proceso para fecha_approved ---
            $registrosApproved = ReporteKanban::whereNull('fecha_approved')->get();
            if ($registrosApproved->count() > 0 && class_exists(TicketApproved::class)) {
                Log::info("Procesando 'fecha_approved' para " . $registrosApproved->count() . " registros.");
                $updatesApprovedCount = 0;
                foreach ($registrosApproved as $registro) {
                    $ticketApproved = TicketApproved::where('op', $registro->op)
                                                  // ->orderBy('fecha', 'desc') // Ajusta si necesitas un orden específico
                                                  ->first();

                    // CAMBIA 'fecha_aprobacion' por el nombre real de tu campo de fecha en TicketApproved
                    if ($ticketApproved && $ticketApproved->fecha) {
                        $registro->fecha_approved = $ticketApproved->fecha;
                        $registro->piezas = $ticketApproved->cantidad;
                        $registro->save();
                        $updatesApprovedCount++;
                        Log::info("ReporteKanban ID {$registro->id} actualizado con fecha_approved: {$ticketApproved->fecha}");
                    }
                }
                $respuestaGlobal['updates_performed']['approved'] = "{$updatesApprovedCount} registros actualizados.";
            } else {
                $respuestaGlobal['updates_performed']['approved'] = 'No se encontraron registros para actualizar o TicketApproved no disponible.';
                Log::info("'fecha_approved': No hay registros para actualizar o TicketApproved no está disponible.");
            }

            // Establecer la caché global DESPUÉS de que todos los procesos hayan intentado ejecutarse.
            // Tiempo hasta el próximo intento: 3 horas (o lo que definas)
            Cache::put($cacheKeyGlobal, now(), now()->addHours(3));
            Log::info("Todos los procesos de actualización de Kanban completados. Caché global establecida.");

        } else {
            // La caché global está activa, no hacemos nada.
            $respuestaGlobal['message'] = 'Procesos de actualización en espera (caché global activa).';
            Log::info("Procesos de actualización de Kanban omitidos debido a caché global activa.");
        }

        return response()->json($respuestaGlobal);
    }

    public function buscarPorOP(Request $request)
    {
        $op = $request->get('op');
        $registro = DB::table('reporte_kanban')
            ->where('op', $op)
            ->first();

        if ($registro) {
            return response()->json($registro);
        } else {
            return response()->json(['error' => 'Registro no encontrado'], 404);
        }
    }

}
