<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditoriaProceso;
use App\Models\AseguramientoCalidad;
use App\Models\CategoriaTeamLeader;
use App\Models\CategoriaTipoProblema;
use App\Models\CategoriaAccionCorrectiva;
use App\Models\JobOperacion;
use App\Models\TpAseguramientoCalidad;
use App\Models\CategoriaSupervisor;
use App\Mail\NotificacionParo;
use App\Models\ModuloEstilo;
use App\Models\ModuloEstiloTemporal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

use App\Models\EvaluacionCorte;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon

class AuditoriaProcesoV3Controller extends Controller
{

    public function altaProcesoV3(Request $request)
    {
        $pageSlug = 'proceso'; // O el que corresponda para tu layout
        $auditorDato = Auth::user()->name;
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
        // Formateamos la fecha aquí para evitar lógica en la vista
        $fechaActualCarbon = Carbon::now();
        $fechaActualParaVista = $fechaActualCarbon->format('d ') . $mesesEnEspanol[$fechaActualCarbon->format('n') - 1] . $fechaActualCarbon->format(' Y');

        // 1. Mapeamos el string del usuario al ID numérico
        $plantaUsuarioId = match (Auth::user()->Planta) {
            'Planta1' => 1,
            'Planta2' => 2,
            default   => null, // O maneja un valor por defecto si es necesario
        };

        // 2. Ejecutamos la consulta usando whereIn
        // Esto dice: "Trae donde estatus sea 1 Y la planta sea (ID del usuario O 0)"
        $turnos = \App\Models\Turno::where('estatus', 1)
            ->whereIn('planta', [$plantaUsuarioId, 0])
            ->get();

        return view('proceso.index', compact(
            'pageSlug',
            'auditorDato', // Necesario para el campo readonly en el formulario principal
            'tipoUsuario', // Podría ser necesario para lógica condicional en la vista
            'fechaActualParaVista',
            'turnos'
            // Los siguientes datos se cargarán por AJAX:
            // 'gerenteProduccion',
            // 'procesoActual',
            // 'procesoFinal'
        ));
    }



    public function obtenerModulos()
    {
        $usuario = Auth::user();

        // Es una buena práctica verificar si Auth::user() devolvió un usuario,
        // aunque las rutas suelen estar protegidas por el middleware de autenticación.
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado.'], 401);
        }

        $auditorPlanta = $usuario->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // Clave de caché única para la planta especificada.
        // He añadido _v2 por si existiera una caché antigua con una clave similar.
        $claveCache = "modulos_planta_{$datoPlanta}_v2";

        // Tiempo de caché en segundos (60 segundos = 1 minuto)
        $tiempoCache = 30;
        // Alternativamente, usando Carbon para una definición más explícita del tiempo:
        // $tiempoCache = Carbon::now()->addMinutes(1);

        $listaModulos = Cache::remember($claveCache, $tiempoCache, function () use ($datoPlanta) {
            // Consulta para CategoriaSupervisor
            // Seleccionamos solo 'moduleid' para la operación UNION
            $queryCategoriaSupervisor = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
                ->whereBetween('moduleid', ['100A', '299A'])
                ->select('moduleid');

            // Consulta para ModuloEstiloTemporal
            // Seleccionamos solo 'moduleid' para la operación UNION
            $queryModuloEstiloTemporal = ModuloEstiloTemporal::where('prodpoolid', $datoPlanta)
                ->whereBetween('moduleid', ['100A', '999A'])
                ->select('moduleid');

            // Usamos UNION para combinar los resultados.
            // UNION inherentemente devuelve filas distintas (basado en las columnas seleccionadas).
            // Ordenamos la lista combinada final por 'moduleid'.
            // get() devolverá una colección de objetos, cada uno con la propiedad 'moduleid'.
            $modulos = $queryCategoriaSupervisor
                ->union($queryModuloEstiloTemporal)
                ->orderBy('moduleid', 'asc') // Ordenar en la base de datos
                ->get();

            // El resultado de get() es una Colección de objetos, por ejemplo:
            // Illuminate\Support\Collection {#123
            //   all: [
            //     {#456 ▼ +"moduleid": "100A"},
            //     {#789 ▼ +"moduleid": "101A"},
            //   ]
            // }
            // Esto coincide con la estructura que generaba tu secuencia original de operaciones
            // y producirá un JSON como: [{"moduleid":"100A"}, {"moduleid":"101A"}, ...]
            return $modulos;
        });

        return response()->json($listaModulos);
    }

    // Nuevo método para obtener Gerentes de Producción vía AJAX
    public function obtenerGerentesProduccionAjax(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // Clave única para el caché por planta
        $cacheKey = "gerentes_produccion_{$datoPlanta}";
        $tiempoCache = 60; // Cachear por 1 minuto (en segundos)

        $gerenteProduccion = Cache::remember($cacheKey, $tiempoCache, function () use ($datoPlanta) {
            return CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
                ->orderBy('jefe_produccion') // O por 'nombre' si es más relevante para el display
                ->where('planta', $datoPlanta)
                ->where('estatus', 1)
                ->where('jefe_produccion', 1) // Asumo que esto filtra a los que SÍ son jefes de producción
                ->select('nombre') // Solo lo que necesitas para el select
                ->get();
        });

        return response()->json($gerenteProduccion);
    }

    // Nuevo método unificado para obtener Procesos (Actuales y Finales) vía AJAX
    public function obtenerProcesosAjax(Request $request)
    {
        $tipoProceso = $request->query('tipo'); // 'actual' o 'final'

        if (!in_array($tipoProceso, ['actual', 'final'])) {
            return response()->json(['error' => 'Tipo de proceso no válido'], 400);
        }

        $auditorDato = Auth::user()->name;
        $tipoUsuario = Auth::user()->puesto;
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";
        $fechaActual = Carbon::now()->toDateString();
        $tiempoCache = Carbon::now()->addMinutes(1); // Cachear por 1 minuto

        // Partes base para la clave de caché
        $baseCacheKeyParts = [
            'procesos',
            $datoPlanta,
            $fechaActual
        ];

        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $baseCacheKeyParts[] = "auditor_{$auditorDato}";
        } else {
            $baseCacheKeyParts[] = "todos";
        }

        // Claves de caché específicas para procesos actuales y finales
        $cacheKeyActual = implode('_', array_merge($baseCacheKeyParts, ['actual']));
        $cacheKeyFinal = implode('_', array_merge($baseCacheKeyParts, ['final']));

        $procesosSolicitados = null;

        // Intentar obtener los procesos solicitados desde la caché primero
        if ($tipoProceso === 'actual') {
            if (Cache::has($cacheKeyActual)) {
                $procesosSolicitados = Cache::get($cacheKeyActual);
            }
        } else { // 'final'
            if (Cache::has($cacheKeyFinal)) {
                $procesosSolicitados = Cache::get($cacheKeyFinal);
            }
        }

        if ($procesosSolicitados !== null) {
            return response()->json($procesosSolicitados);
        }

        // Si no está en caché, realizar una consulta combinada para ambos tipos
        // y luego popular la caché para ambos.

        // Columnas a seleccionar (sin 'estatus' para el resultado final, pero necesaria para la división)
        $selectColumns = ['modulo', 'estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion'];
        $queryColumns = array_merge($selectColumns, ['estatus']); // Añadir 'estatus' para la consulta

        $baseQuery = AseguramientoCalidad::where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select($queryColumns) // Seleccionar con 'estatus' para poder diferenciar
            ->distinct() // Distinct se aplicará a todas las columnas seleccionadas, incluyendo 'estatus'
            ->orderBy('modulo', 'asc');

        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $baseQuery->where('auditor', $auditorDato);
        }

        // Clonar la consulta base y añadir la condición para ambos tipos de estatus
        $combinedQuery = clone $baseQuery;
        $todosLosProcesosDb = $combinedQuery->where(function ($query) {
            $query->whereNull('estatus')      // Procesos actuales
                ->orWhere('estatus', 1); // Procesos finales
        })->get();

        $procesosActuales = [];
        $procesosFinales = [];

        foreach ($todosLosProcesosDb as $procesoDb) {
            // Preparamos el objeto/array sin la columna 'estatus' para el JSON final
            $procesoData = new \stdClass(); // O usa un array si lo prefieres
            foreach ($selectColumns as $column) {
                $procesoData->{$column} = $procesoDb->{$column};
            }

            if ($procesoDb->estatus === null) {
                $procesosActuales[] = $procesoData;
            } elseif ($procesoDb->estatus == 1) { // Usar comparación no estricta por si 'estatus' es numérico
                $procesosFinales[] = $procesoData;
            }
        }

        // Guardar ambos resultados en sus respectivas cachés
        Cache::put($cacheKeyActual, $procesosActuales, $tiempoCache);
        Cache::put($cacheKeyFinal, $procesosFinales, $tiempoCache);

        // Devolver el tipo de proceso que se solicitó originalmente
        if ($tipoProceso === 'actual') {
            return response()->json($procesosActuales);
        } else { // 'final'
            return response()->json($procesosFinales);
        }
    }

    public function obtenerEstilos(Request $request) // Renombrado de obtenerEstilosV2 si es el mismo método
    {
        $moduleid = $request->input('moduleid');

        // Clave de caché única. Considera si moduleid puede ser nulo o ausente.
        $cacheKey = "estilos_modulo_" . ($moduleid ?? 'global'); // 'global' o 'todos' si moduleid es opcional para la consulta general
        $minutesToCache = 2;

        // Intentar obtener desde el caché
        if (Cache::has($cacheKey)) {
            $estilosCombinados = Cache::get($cacheKey);
        } else {
            // Obtener los estilos con su respectivo cliente desde ModuloEstilo
            $queryModuloEstilo = ModuloEstilo::select('itemid', 'custname');
            if ($moduleid) {
                // Aplicar prioridad solo si moduleid está presente
                $queryModuloEstilo->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$moduleid])
                    ->orderBy('prioridad');
            }
            $itemidsModuloEstilo = $queryModuloEstilo->orderBy('itemid')->distinct()->get();


            // Obtener los estilos desde ModuloEstiloTemporal
            $itemidsModuloEstiloTemporal = ModuloEstiloTemporal::select('itemid', 'custname')
                ->distinct()
                ->orderBy('itemid')
                ->get();

            // Combinar ambos resultados y eliminar duplicados por 'itemid',
            // dando preferencia a los de $itemidsModuloEstilo (que ya están priorizados si había moduleid)
            $estilosCombinados = $itemidsModuloEstilo
                ->concat($itemidsModuloEstiloTemporal)
                ->unique('itemid') // unique opera sobre la colección y mantiene el primer elemento encontrado con ese 'itemid'
                ->values(); // Reindexa la colección

            // Solo guardar en caché si la consulta devolvió resultados
            if ($estilosCombinados->isNotEmpty()) {
                Cache::put($cacheKey, $estilosCombinados, now()->addMinutes($minutesToCache));
            }
        }

        return response()->json([
            'estilos' => $estilosCombinados,
        ]);
    }


    public function obtenerSupervisor(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        $moduleid = $request->input('moduleid');

        // Supervisor relacionado con el módulo seleccionado
        $supervisorRelacionado = CategoriaSupervisor::where('moduleid', $moduleid)
            ->where('prodpoolid', $datoPlanta)
            ->first();

        // Lista de supervisores de la planta con ciertas condiciones
        $supervisores = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
            ->whereNotNull('name')
            ->where(function ($query) {
                $query->where('moduleid', 'like', '1%')
                    ->orWhere('moduleid', 'like', '2%')
                    ->orWhereIn('moduleid', ['830A', '831A', '833A']);
            })
            ->where('moduleid', '!=', '198A')
            ->select('name')
            ->distinct()
            ->get();

        return response()->json([
            'supervisorRelacionado' => $supervisorRelacionado ? $supervisorRelacionado->name : null,
            'supervisores' => $supervisores
        ]);
    }


    public function formAltaProceso(Request $request)
    {
        $pageSlug = '';

        $data = [
            'modulo' => $request->modulo,
            'estilo' => $request->estilo,
            'team_leader' => $request->team_leader,
            'auditor' => $request->auditor,
            'turno' => $request->turno,
            'gerente_produccion' => $request->gerente_produccion,
        ];
        //dd($data);

        // GUARDAR ASIGNACIÓN DE TURNO PARA EL DÍA
        // Esto asegura que el sistema sepa qué reglas aplicar para este módulo hoy.
        if ($request->has('modulo') && $request->has('turno')) {
            \App\Models\ModuloTurno::updateOrCreate(
                ['modulo' => $request->modulo, 'fecha' => \Carbon\Carbon::now()->toDateString()],
                ['turno_id' => $request->turno]
            );
        }

        return redirect()->route(
            'procesoV3.registro',
            array_merge($data)
        )->with('cambio-estatus', 'Iniciando en modulo: ' . $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function auditoriaProceso(Request $request)
    {
        $pageSlug = '';
        $fechaActual = Carbon::now()->toDateString();
        //$fechaActual = Carbon::now()->subDay()->toDateString();
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
        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // 1. Consulta general para AseguramientoCalidad
        $registros = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('cantidad_rechazada', '>', 0)
            ->where('modulo', $data['modulo'])
            ->orderBy('created_at', 'asc')
            ->get();

        // 2. Dividir en dos subconjuntos: sin tiempo extra y con tiempo extra
        $registrosSinTE = $registros->filter(function ($r) {
            return is_null($r->tiempo_extra);
        })->values();

        $registrosConTE = $registros->filter(function ($r) {
            return $r->tiempo_extra == 1;
        })->values();

        // 3. Función para evaluar cada subconjunto de 3 en 3
        function evaluarSubconjunto3($registros)
        {
            // Si hay menos de 3 registros, no se puede formar un grupo completo
            if ($registros->count() < 3) {
                return false;
            }

            $total = $registros->count();
            // Hallar el número del último grupo completo de 3
            $ultimoGrupo = floor($total / 3) * 3; // Ej: si hay 5 registros => floor(5/3)=1, 1*3=3; si hay 7 => floor(7/3)=2, 2*3=6
            $indice = $ultimoGrupo - 1; // El índice del último registro del grupo (recordar que se indexa desde 0)

            // Registro a evaluar (último del grupo completo)
            $registroEvaluar = $registros[$indice];

            // Retorna true si fin_paro_modular es null, false en caso contrario
            return is_null($registroEvaluar->fin_paro_modular);
        }

        // 4. Evaluar cada subconjunto
        $resultadoFinalSinTE = evaluarSubconjunto3($registrosSinTE);
        $resultadoFinalConTE = evaluarSubconjunto3($registrosConTE);

        // 5. El resultado final es true si al menos uno de los subconjuntos arroja true
        $resultadoFinal = $resultadoFinalSinTE || $resultadoFinalConTE;
        //dd($resultadoFinal, $resultadoFinalSinTE, $resultadoFinalConTE);

        // Recuperar el comentario (observacion) para el módulo y día actual
        $observacion = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->whereNull('tiempo_extra')
            ->value('observacion'); // Devuelve null si no hay

        $observacionTE = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->value('observacion'); // Devuelve null si no hay

        return view('proceso.registro', compact('mesesEnEspanol', 'pageSlug', 'data', 'resultadoFinal', 'observacion', 'observacionTE'));
    }

    public function obtenerListaProcesos()
    {
        $usuario = Auth::user();
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado.'], 401);
        }

        $fechaActual = Carbon::now()->toDateString(); // Usar Carbon para consistencia
        $auditorPlanta = $usuario->Planta;
        $auditorDato = $usuario->name;
        $tipoUsuario = $usuario->puesto;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // --- Clave de Caché Dinámica ---
        // Construimos una clave que identifique unívocamente esta consulta
        $cacheKeyParts = [
            'lista_procesos_actuales', // Identificador base
            $datoPlanta,
            $fechaActual,
        ];

        // La clave de caché debe variar si la consulta varía (por ejemplo, si se filtra por auditor)
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $cacheKeyParts[] = "auditor_{$auditorDato}";
        } else {
            $cacheKeyParts[] = "todos_los_auditores"; // O simplemente omitir si no hay filtro de auditor
        }
        $cacheKey = implode('_', $cacheKeyParts);

        // --- Tiempo de Caché ---
        $minutesToCache = 30; // 30 minutos

        // --- Lógica de Caché y Consulta ---
        $procesos = Cache::remember($cacheKey, now()->addMinutes($minutesToCache), function () use ($datoPlanta, $fechaActual, $tipoUsuario, $auditorDato) {

            $query = AseguramientoCalidad::whereNull('estatus')
                ->where('planta', $datoPlanta)
                // Optimización para whereDate:
                // Si 'created_at' es DATETIME o TIMESTAMP, es mejor usar whereBetween para aprovechar índices
                ->whereBetween('created_at', [Carbon::parse($fechaActual)->startOfDay(), Carbon::parse($fechaActual)->endOfDay()])
                // ->whereDate('created_at', $fechaActual) // Alternativa si 'created_at' es solo DATE o prefieres esta sintaxis y tienes índices funcionales
                ->select('modulo', 'estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
                ->distinct() // distinct() puede ser costoso. Asegúrate de que los índices lo cubran.
                ->orderBy('modulo', 'asc');

            // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
            if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
                $query->where('auditor', $auditorDato);
            }

            return $query->get();
        });

        return response()->json([
            'procesos' => $procesos,
        ]);
    }

    public function obtenerNombresGenerales(Request $request)
    {
        $modulo = $request->input('modulo');
        $search = $request->input('search');
        $auditorPlanta = auth()->user()->Planta ?? 'Planta1'; // Ajustar según sea necesario
        $detectarPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // Crear una clave única para el caché basada en los parámetros que afectan la consulta
        // Es importante incluir todos los parámetros que cambian el resultado de la consulta.
        $cacheKey = "nombresGenerales_{$detectarPlanta}_modulo_{$modulo}_search_" . md5((string)$search);
        $minutesToCache = 5;

        // Intentar obtener los datos desde el caché primero
        if (Cache::has($cacheKey)) {
            $nombresGenerales = Cache::get($cacheKey);
            // Si lo que está cacheado es una marca indicando "sin resultados",
            // podríamos querer re-evaluar. Pero para el caso de "no cachear si está vacío",
            // simplemente devolvemos lo que sea que esté en caché (que no debería ser un "vacío" si se implementa correctamente).
            // O, si el caché pudiera contener un marcador explícito de "no resultados", aquí se manejaría.
            // Por simplicidad, si está en caché, se devuelve. El truco está en no ponerlo si está vacío.
        } else {
            // Base de la consulta
            $query = AuditoriaProceso::where('prodpoolid', $detectarPlanta)
                ->whereNotIn('name', [
                    '831A-EMPAQUE P2 T1',
                    '830A-EMPAQUE P1 T1',
                    'VIRTUAL P2T1 02',
                    'VIRTUAL P2T1 01'
                ])
                ->where('name', 'not like', '1%')
                ->where('name', 'not like', '2%');

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('personnelnumber', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%");
                });
            }

            $nombresGenerales = $query
                ->select('personnelnumber', 'name', 'moduleid')
                ->distinct()
                ->orderByRaw("CASE WHEN moduleid = ? THEN 0 ELSE 1 END, name ASC", [$modulo])
                ->get();

            // Solo guardar en caché si la consulta devolvió resultados
            if ($nombresGenerales->isNotEmpty()) {
                Cache::put($cacheKey, $nombresGenerales, now()->addMinutes($minutesToCache));
            }
            // Si $nombresGenerales está vacío, no se guarda nada en caché.
            // La próxima vez que se llame con los mismos parámetros, se volverá a consultar la BD.
        }

        return response()->json([
            'nombres' => $nombresGenerales
        ]);
    }


    public function obtenerOperaciones(Request $request)
    {
        $modulo = $request->input('modulo');
        // El 'search' del request ya no se usará para filtrar resultados si cargamos todo al cliente.
        // Se mantiene aquí por si el endpoint se usa de otra manera o para una carga inicial condicionada.

        // Clave de caché. Si el módulo es opcional y no tenerlo significa "todos los módulos", ajústalo.
        $cacheKey = "operaciones_modulo_" . ($modulo ?? 'todos_los_modulos');
        $minutesToCache = 5; // Puedes ajustar este valor

        if (Cache::has($cacheKey)) {
            $operaciones = Cache::get($cacheKey);
        } else {
            $excluidos = [
                "APP SCREEN:   /   /",
                "APPROVED     /    /",
                "APPROVED    /   /",
                "APPROVED / /",
                "APPROVED //",
                "OFF LINE",
                "ON CUT",
                "ON LINE",
                "OUT CUT"
            ];

            $query = JobOperacion::whereNotIn('oprname', $excluidos);

            if (!empty($modulo)) {
                $query->where('moduleid', $modulo);
            }

            // Cuando se carga todo para el cliente, no se aplica el $search del request aquí.
            // La búsqueda se hará en el cliente.
            $operacionesResult = $query->select('oprname')->distinct()->orderBy('oprname', 'asc')->get();

            // Solo guardar en caché si la consulta devolvió resultados
            if ($operacionesResult->isNotEmpty()) {
                Cache::put($cacheKey, $operacionesResult, now()->addMinutes($minutesToCache));
            }
            // Asignar siempre para tener un valor (puede ser colección vacía)
            $operaciones = $operacionesResult;
        }

        // El cliente se encargará de añadir la opción "[OTRA OPERACIÓN]"
        return response()->json([
            'operaciones' => $operaciones
        ]);
    }

    public function accionCorrectivaProceso()
    {
        $categoriaACProceso = CategoriaAccionCorrectiva::where('area', 'proceso')->get();

        return response()->json([
            'acciones' => $categoriaACProceso
        ]);
    }

    public function defectosProceso(Request $request)
    {
        $search = $request->input('search'); // Puede ser null si no se envía

        // --- Clave de Caché Dinámica ---
        // La clave debe cambiar si el término de búsqueda cambia.
        // Usamos md5 para el término de búsqueda para asegurar una clave válida y de longitud consistente.
        // Si search es null o una cadena vacía, podríamos usar un marcador como 'todos'.
        $searchTermForCache = !empty($search) ? md5($search) : 'todos';
        $cacheKey = "defectos_proceso_search_{$searchTermForCache}";

        // --- Tiempo de Caché ---
        $minutesToCache = 1; // 1 minuto

        // --- Lógica de Caché y Consulta ---
        $defectos = Cache::remember($cacheKey, $minutesToCache * 60, function () use ($search) {
            // El segundo argumento de remember es en segundos, o puedes usar now()->addMinutes()

            $query = CategoriaTipoProblema::whereIn('area', ['proceso', 'playera']);

            // Aplicar filtro de búsqueda si el usuario escribe algo
            if (!empty($search)) {
                // Esta es la parte que puede ser lenta en tablas grandes sin Full-Text Search
                $query->where('nombre', 'like', "%{$search}%");
            }

            // Seleccionar solo la columna 'nombre', obtener valores distintos, ordenar y ejecutar
            return $query->select('nombre')
                ->distinct()
                ->orderBy('nombre', 'asc')
                ->get();
            // El resultado será una colección de objetos, cada uno con una propiedad 'nombre', ej: [{"nombre":"Defecto A"}]
            // Si quisieras una lista plana de strings ["Defecto A", "Defecto B"], usarías:
            // ->pluck('nombre');
        });

        return response()->json([
            'defectos' => $defectos
        ]);
    }

    public function crearDefecto(Request $request)
    {
        try {
            $nombre = strtoupper(trim($request->input('nombre')));

            if (!$nombre) {
                return response()->json(['error' => 'El nombre es obligatorio'], 400);
            }

            // Verificar si ya existe
            $defectoExistente = CategoriaTipoProblema::where('nombre', $nombre)
                ->where('area', 'proceso')
                ->first();

            if ($defectoExistente) {
                return response()->json($defectoExistente);
            }

            // Crear el nuevo defecto
            $nuevoDefecto = CategoriaTipoProblema::create([
                'nombre' => $nombre,
                'area' => 'proceso',
            ]);

            return response()->json($nuevoDefecto);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function formRegistro(Request $request)
    {
        try {
            // Convertir JSON recibido a un array asociativo
            $datosFormulario = $request->json()->all();
            //Log::info("Datos recibidos en el controlador:", $datosFormulario);

            // Si no hay piezas rechazadas, limpiar 'ac' y 'tp'
            if ($datosFormulario['auditoria'][0]['cantidad_rechazada'] == 0) {
                $datosFormulario['auditoria'][0]['accion_correctiva'] = null;
                // Eliminar 'tipo_problema' del array para evitar que se registre
                unset($datosFormulario['auditoria'][0]['tipo_problema']);
            }

            $fechaHoraActual = now();
            $diaSemana = $fechaHoraActual->dayOfWeek;

            // Buscar la planta asociada al módulo
            $primerCaracter = substr($datosFormulario['modulo'], 0, 1);

            $plantaBusqueda = match ($primerCaracter) {
                '1' => 'Intimark1',
                '2' => 'Intimark2',
                default => 'Intimark1', // O el valor por defecto que necesites
            };


            $obtenerEstilo = $datosFormulario['estilo'];
            $obtenerClienteInicial = $datosFormulario['cliente'];

            // Definimos una clave única para el cache basada en el estilo
            $cacheKey = 'cliente_por_estilo_' . $obtenerEstilo;

            // Usamos Cache::remember para obtener o almacenar el cliente
            $obtenerCliente = Cache::remember($cacheKey, now()->addHours(15), function () use ($obtenerEstilo, $obtenerClienteInicial) {

                //Log::info("V3 Buscando cliente en DB (no en cache)", ['estilo' => $obtenerEstilo]);

                // 1. Priorizamos el cliente que ya viene en el formulario
                if (!empty($obtenerClienteInicial)) {
                    return $obtenerClienteInicial;
                }

                // 2. Si no viene, buscamos en la tabla temporal
                $cliente = ModuloEstiloTemporal::where('itemid', $obtenerEstilo)->value('custname');
                if (!empty($cliente)) {
                    return $cliente;
                }

                // 3. Si tampoco está ahí, buscamos en la tabla principal
                $cliente = ModuloEstilo::where('itemid', $obtenerEstilo)->value('custname');
                if (!empty($cliente)) {
                    return $cliente;
                }

                // Si después de todas las búsquedas no se encontró un cliente,
                // devolvemos null. Laravel Cache::remember NO almacenará en caché un valor nulo.
                return null;
            });

            //Log::info("V3 cliente final", ['cliente' => $obtenerCliente]);

            // Procesar el nombre final
            $nombreFinalValidado = $datosFormulario['auditoria'][0]['nombre_final'] ? trim($datosFormulario['auditoria'][0]['nombre_final']) : null;

            // Obtener número de empleado desde AuditoriaProceso
            $numeroEmpleado = $datosFormulario['auditoria'][0]['numero_empleado'];

            // Obtener módulo adicional
            $moduloAdicional = AuditoriaProceso::where('name', $nombreFinalValidado)
                ->pluck('moduleid')
                ->first();

            // Identificar si es Utility
            $utilityIdentificado = in_array($moduloAdicional, ['860A', '863A']) ? 1 : null;
            if ($utilityIdentificado) {
                $moduloAdicional = null;
            }

            // ✅ Crear y guardar el nuevo registro en AseguramientoCalidad
            $nuevoRegistro = new AseguramientoCalidad();
            $nuevoRegistro->modulo = $datosFormulario['modulo'];
            $nuevoRegistro->modulo_adicional = $moduloAdicional;
            $nuevoRegistro->planta = $plantaBusqueda;
            $nuevoRegistro->estilo = $obtenerEstilo;
            $nuevoRegistro->cliente = $obtenerCliente;
            $nuevoRegistro->team_leader = $datosFormulario['team_leader'];
            $nuevoRegistro->gerente_produccion = $datosFormulario['gerente_produccion'];
            $nuevoRegistro->auditor = $datosFormulario['auditor'];
            $nuevoRegistro->turno = $datosFormulario['turno'];
            $nuevoRegistro->numero_empleado = $numeroEmpleado;
            $nuevoRegistro->nombre = $nombreFinalValidado;
            $nuevoRegistro->utility = $utilityIdentificado;
            $nuevoRegistro->operacion = $datosFormulario['auditoria'][0]['operacion'];
            $nuevoRegistro->cantidad_auditada = $datosFormulario['auditoria'][0]['cantidad_auditada'];
            $nuevoRegistro->cantidad_rechazada = $datosFormulario['auditoria'][0]['cantidad_rechazada'];

            // Si hay piezas rechazadas, registrar inicio de paro
            if ($datosFormulario['auditoria'][0]['cantidad_rechazada'] > 0) {
                $nuevoRegistro->inicio_paro = now();
            }

            $nuevoRegistro->ac = $datosFormulario['auditoria'][0]['accion_correctiva'];
            $nuevoRegistro->pxp = $datosFormulario['auditoria'][0]['pxp'];

            // LÓGICA DE HORARIOS FLEXIBLES
            // 1. Buscamos si hay un turno asignado para este módulo hoy
            $asignacion = \App\Models\ModuloTurno::where('modulo', $datosFormulario['modulo'])
                ->where('fecha', now()->toDateString())
                ->with('turno')
                ->first();

            // 2. Obtenemos el horario (si hay asignación usa ese, si no, usa el default ID 1)
            $turnoReglas = $asignacion ? $asignacion->turno : \App\Models\Turno::find(1);

            $esTiempoExtra = true; // Asumimos TE por defecto (fuera de horario o fin de semana)

            if ($turnoReglas && isset($turnoReglas->horario_semanal[$diaSemana])) {
                $horarioHoy = $turnoReglas->horario_semanal[$diaSemana];

                // Si existe horario definido para hoy
                if ($horarioHoy && !empty($horarioHoy['inicio']) && !empty($horarioHoy['fin'])) {
                    // Creamos instancias Carbon para comparar horas (usa fecha de hoy)
                    $horaInicio = \Carbon\Carbon::createFromTimeString($horarioHoy['inicio']);
                    $horaFin = \Carbon\Carbon::createFromTimeString($horarioHoy['fin']);

                    // Verificar si la hora actual está DENTRO del rango (inclusive)
                    if ($fechaHoraActual->between($horaInicio, $horaFin, true)) {
                        $esTiempoExtra = false; // Está dentro del horario laboral = Turno Normal
                    }
                }
            }

            // Asignar el valor (1 para TE, null para Normal)
            $nuevoRegistro->tiempo_extra = $esTiempoExtra ? 1 : null;

            $nuevoRegistro->save();

            // ✅ Obtener el ID del nuevo registro
            $nuevoRegistroId = $nuevoRegistro->id;

            // ✅ Guardar los tipos de problema en TpAseguramientoCalidad
            $tpSeleccionados = $datosFormulario['auditoria'][0]['tipo_problema'] ?? ['NINGUNO'];

            // ✅ Solo guardar en TpAseguramientoCalidad si existe 'tipo_problema' y tiene datos
            if (isset($datosFormulario['auditoria'][0]['tipo_problema']) && !empty($datosFormulario['auditoria'][0]['tipo_problema'])) {

                $tpSeleccionados = $datosFormulario['auditoria'][0]['tipo_problema'];

                // Si tipo_problema no es un array, convertirlo en uno
                if (!is_array($tpSeleccionados)) {
                    $tpSeleccionados = [$tpSeleccionados];
                }

                foreach ($tpSeleccionados as $valorTp) {
                    $nuevoTp = new TpAseguramientoCalidad();
                    $nuevoTp->aseguramiento_calidad_id = $nuevoRegistroId; // Relación con aseguramiento_calidad
                    $nuevoTp->tp = $valorTp;
                    $nuevoTp->save();
                }
            }

            return response()->json(['message' => 'Datos guardados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error("Error al guardar los datos: " . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerRegistroDia(Request $request)
    {
        try {
            $fechaActual = Carbon::today()->toDateString();
            $modulo = $request->input('modulo');

            // Validación básica del módulo
            if (empty($modulo)) {
                // Devuelve arrays vacíos si no hay módulo, para que el JS no falle
                //Log::warning('Intento de obtenerListaProcesos sin especificar módulo.');
                return response()->json([
                    'registrosNormales' => [],
                    'registrosExtras' => [],
                ], 200); // Opcional: podrías devolver 400 Bad Request
                // return response()->json(['error' => 'El parámetro módulo es requerido.'], 400);
            }

            // 1. --- UNA SOLA CONSULTA ---
            // Obtiene TODOS los registros del día y módulo, incluyendo la relación necesaria.
            $registrosDelDia = AseguramientoCalidad::with('tpAseguramientoCalidad') // Carga ansiosa de la relación
                ->whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->orderBy('created_at', 'asc') // O el orden que prefieras
                ->get();

            // 2. --- TRANSFORMAR DATOS (UNA SOLA VEZ) ---
            // Aplicamos el formato deseado a toda la colección antes de dividirla.
            $registrosTransformados = $registrosDelDia->map(function ($registro) {
                // Formateo de la lista de defectos
                $defectosStr = $registro->tpAseguramientoCalidad->isNotEmpty()
                    ? $registro->tpAseguramientoCalidad->pluck('tp')->implode(', ')
                    : 'N/A';

                return [
                    'id' => $registro->id,
                    'inicio_paro' => $registro->inicio_paro,
                    'fin_paro' => $registro->fin_paro,
                    'minutos_paro' => $registro->minutos_paro,
                    'nombre' => $registro->nombre,
                    'operacion' => $registro->operacion,
                    'cantidad_auditada' => $registro->cantidad_auditada,
                    'cantidad_rechazada' => $registro->cantidad_rechazada,
                    'defectos' => $defectosStr,
                    'ac' => $registro->ac,
                    'pxp' => $registro->pxp,
                    // Enviar fecha en un formato estándar que JS pueda interpretar fácilmente
                    'created_at' => $registro->created_at ? $registro->created_at->toIso8601String() : null,
                    // Incluimos la columna clave para la partición
                    'tiempo_extra' => $registro->tiempo_extra,
                ];
            });

            // 3. --- SEPARAR LA COLECCIÓN TRANSFORMADA ---
            // Usamos `partition`. Los que cumplen la condición (tiempo_extra == 1) van al primer array ($registrosExtras).
            // Los que NO cumplen (tiempo_extra es null u otro valor) van al segundo array ($registrosNormales).
            list($registrosExtrasCollection, $registrosNormalesCollection) = $registrosTransformados->partition(function ($registro) {
                // La condición es que tiempo_extra sea estrictamente 1
                return $registro['tiempo_extra'] == 1;
            });

            // 4. --- RETORNAR JSON CON AMBOS GRUPOS ---
            // Usamos ->values() para asegurar que sean arrays indexados numéricamente (evita objetos si quedan pocos elementos)
            return response()->json([
                'registrosNormales' => $registrosNormalesCollection->values(),
                'registrosExtras' => $registrosExtrasCollection->values(),
            ], 200);
        } catch (\Exception $e) {
            // Registrar el error real para diagnóstico interno
            Log::error("Error en obtenerListaProcesos V3: " . $e->getMessage(), [
                'modulo' => $request->input('modulo'),
                'trace' => $e->getTraceAsString() // Opcional, puede ser muy largo
            ]);
            // Devolver un error genérico al cliente
            return response()->json(['error' => 'Ocurrió un error interno al obtener los registros.'], 500);
        }
    }

    public function finalizarParoGeneral(Request $request)
    {
        try {
            $id = $request->id;
            $registro = AseguramientoCalidad::find($id);

            if (!$registro) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            $registro->fin_paro = Carbon::now();

            // Calcular la duración del paro en minutos
            $inicioParo = Carbon::parse($registro->inicio_paro);
            $finParo = Carbon::parse($registro->fin_paro);
            $minutosParo = $inicioParo->diffInMinutes($finParo);

            $registro->minutos_paro = $minutosParo;
            $registro->save();

            return response()->json(['message' => 'Paro finalizado', 'minutos_paro' => $minutosParo], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al finalizar el paro: ' . $e->getMessage()], 500);
        }
    }

    public function eliminarRegistroGeneral($id) // MODIFICADO: Recibe $id directamente
    {
        //Log::info('Intentando eliminar registro con ID:', ['id' => $id]); // Log para verificar llegada

        try {
            // Encuentra el registro por ID. Cambia 'AseguramientoCalidad' por tu modelo real si es diferente.
            // Si tu modelo se llama AuditoriaProceso, úsalo.
            $registro = AseguramientoCalidad::find($id); // O el modelo que estés usando.

            if (!$registro) {
                Log::warning('Registro no encontrado para eliminar:', ['id' => $id]);
                return response()->json(['error' => 'Registro no encontrado.'], 404);
            }

            //Log::info('Registro encontrado:', ['registro_data' => $registro->toArray()]); // Log de datos del registro

            // Verificar si el registro tiene un estatus que impida su eliminación
            // Ajusta 'estatus' al nombre real del campo en tu modelo si es diferente.
            if (!is_null($registro->estatus) /* && $registro->estatus != VALOR_PERMITIDO_PARA_ELIMINAR */) {
                Log::info('Intento de eliminar registro con estatus finalizado:', ['id' => $id, 'estatus' => $registro->estatus]);
                return response()->json([
                    'warning' => 'No se puede eliminar el registro porque ya tiene un estatus final o procesado.'
                ], 200); // Se devuelve un código 200 para que AJAX lo maneje en 'success'
            }

            $registro->delete(); // Eliminar el registro de la base de datos
            //Log::info('Registro eliminado correctamente:', ['id' => $id]);

            return response()->json(['message' => 'Registro eliminado correctamente.'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el registro:', [
                'id' => $id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString() // Útil para debugging detallado
            ]);
            return response()->json(['error' => 'Error interno del servidor al eliminar el registro. Revise los logs.'], 500);
        }
    }


    public function buscarUltimoRegistroProceso(Request $request)
    {
        // 1. DATOS INICIALES
        $modulo = $request->input('modulo');
        $fechaActual = now()->toDateString();

        // 2. CONSULTA ÚNICA Y OPTIMIZADA
        $posiblesParos = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->where('cantidad_rechazada', '>', 0)
            ->select('id', 'created_at', 'inicio_paro', 'fin_paro_modular', 'minutos_paro_modular', 'tiempo_extra')
            ->orderBy('created_at', 'asc') // Orden cronológico es esencial
            ->get();

        // 3. SEPARACIÓN DE REGISTROS (Tiempo Normal vs. Tiempo Extra)
        list($registrosExtra, $registrosNormales) = $posiblesParos->partition(function ($registro) {
            return $registro->tiempo_extra === '1';
        });

        // 4. APLICAR LÓGICA DE BÚSQUEDA CON PRIORIDAD
        $registroAActualizar = null;

        // Escenario 1: Buscar en registros de Tiempo Normal (tiempo_extra es NULL).
        $registroAActualizar = $this->encontrarParoParaActualizarDeTres($registrosNormales);

        // Escenario 2: Si no se encontró nada, buscar en registros de Tiempo Extra.
        if (!$registroAActualizar) {
            $registroAActualizar = $this->encontrarParoParaActualizarDeTres($registrosExtra);
        }

        // 5. ACTUALIZACIÓN (SI SE ENCONTRÓ UN REGISTRO)
        if ($registroAActualizar) {
            $horaActual = now();
            $inicioParo = Carbon::parse($registroAActualizar->inicio_paro);
            $diferenciaEnMinutos = $inicioParo->diffInMinutes($horaActual);

            $registroAActualizar->update([
                'fin_paro_modular' => $horaActual,
                'minutos_paro_modular' => $diferenciaEnMinutos,
            ]);

            return redirect()->back()->with('success', 'Paro de proceso finalizado. Tiempo: ' . $diferenciaEnMinutos . ' min.');
        }

        // 6. RESPUESTA SI NO SE ENCONTRÓ NADA
        return redirect()->back()->with('error', 'No se encontró ningún paro de proceso pendiente para finalizar.');
    }

    /**
     * Itera sobre una colección para encontrar el registro correcto a actualizar.
     * La lógica es: buscar el registro más reciente en una posición divisible por 3
     * (3, 6, 9...) que todavía no haya sido finalizado.
     *
     * @param  \Illuminate\Support\Collection  $registros
     * @return \App\Models\AseguramientoCalidad|null
     */
    private function encontrarParoParaActualizarDeTres(Collection $registros)
    {
        // Se usa values() para re-indexar la colección y asegurar que las claves son 0, 1, 2...
        // Este es el paso clave para que los índices coincidan con las posiciones.
        $registrosOrdenados = $registros->values();

        // Se itera de forma inversa, desde el final hacia el principio.
        for ($i = $registrosOrdenados->count() - 1; $i >= 0; $i--) {
            $registro = $registrosOrdenados[$i];

            // La posición real es el índice + 1.
            $posicion = $i + 1;

            // Se verifica si la posición es divisible por 3.
            if ($posicion % 3 === 0) {
                // Si la posición es correcta, AHORA verificamos si el paro está pendiente.
                if (is_null($registro->minutos_paro_modular)) {
                    // ¡Encontrado! Es el paro más reciente que cumple las condiciones.
                    return $registro;
                }
            }
        }

        // Si el bucle termina, no se encontró ningún registro apto.
        return null;
    }

    public function parosNoFinalizados(Request $request)
    {
        $modulo = $request->input('modulo');

        // Calcular fechas para los últimos 7 días (sin incluir hoy)
        $fechaInicio = Carbon::now()->subDays(7)->startOfDay();
        $fechaFin = Carbon::yesterday()->endOfDay();

        // Consulta de registros en AseguramientoCalidad
        $paros = AseguramientoCalidad::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')  // Se debe haber iniciado el paro
            ->whereNull('fin_paro')         // Aún no se finalizó
            ->get();

        return response()->json($paros);
    }

    public function finalizarParoProcesodespues(Request $request)
    {
        try {
            // Buscar registro en AseguramientoCalidad
            $registro = AseguramientoCalidad::findOrFail($request->id);
            $registro->fin_paro = Carbon::now(); // Asigna el fin del paro

            // Usamos created_at como punto de inicio para el cálculo
            $inicio = Carbon::parse($registro->created_at);
            $fin = Carbon::now();

            // Calcular minutos de paro (según horarios laborales)
            $minutosParo = $this->calcularMinutosParoDesdeCreatedAt($inicio, $fin);

            $registro->minutos_paro = $minutosParo;
            // Ya no se actualiza reparacion_rechazo
            $registro->save();

            return response()->json([
                'success' => true,
                'message' => 'Paro finalizado correctamente.',
                'minutos_paro' => $registro->minutos_paro
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar el paro: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calcula los minutos de paro basándose en created_at hasta el momento actual.
     * Respeta los horarios laborales establecidos.
     */
    private function calcularMinutosParoDesdeCreatedAt(Carbon $inicio, Carbon $fin)
    {
        $totalMinutos = 0;
        $actual = $inicio->copy();

        while ($actual->lessThan($fin)) {
            // Saltar fines de semana
            if ($actual->isWeekend()) {
                $actual->addDay()->startOfDay();
                continue;
            }

            // Definir horario laboral según día
            $inicioJornada = $actual->copy()->setTime(8, 0, 0);
            $finJornada = ($actual->dayOfWeek == Carbon::FRIDAY)
                ? $actual->copy()->setTime(14, 0, 0)
                : $actual->copy()->setTime(19, 0, 0);

            if ($actual->lessThanOrEqualTo($finJornada) && $fin->greaterThanOrEqualTo($inicioJornada)) {
                $inicioEfectivo = $actual->greaterThan($inicioJornada) ? $actual : $inicioJornada;
                $finEfectivo = $fin->lessThan($finJornada) ? $fin : $finJornada;

                if ($inicioEfectivo->lessThan($finEfectivo)) {
                    $minutosHoy = $inicioEfectivo->diffInMinutes($finEfectivo);
                    $totalMinutos += max($minutosHoy, 0);
                }
            }

            $actual->addDay()->startOfDay();
        }

        return $totalMinutos;
    }

    public function guardarObservacionProceso(Request $request)
    {
        try {
            $modulo = $request->input('modulo');
            $comentario = $request->input('comentario');
            $fechaActual = Carbon::now()->toDateString();

            // Actualizar la columna 'observacion' para todos los registros del módulo y día
            AseguramientoCalidad::whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->where('tiempo_extra', null)
                ->update([
                    'observacion' => $comentario,
                    'estatus' => 1  // Establece estatus en 1
                ]);

            return response()->json([
                'success' => true,
                'comentario' => $comentario,
                'message' => 'Comentario actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function guardarObservacionProcesoTE(Request $request)
    {
        try {
            $modulo = $request->input('modulo');
            $comentario = $request->input('comentario');
            $fechaActual = Carbon::now()->toDateString();

            // Actualizar la columna 'observacion' para todos los registros del módulo y día
            AseguramientoCalidad::whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->where('tiempo_extra', 1)
                ->update([
                    'observacion' => $comentario,
                    'estatus' => 1  // Establece estatus en 1
                ]);

            return response()->json([
                'success' => true,
                'comentario' => $comentario,
                'message' => 'Comentario actualizado correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el comentario: ' . $e->getMessage()
            ], 500);
        }
    }


    public function finalizarAuditoriaModuloUnificado(Request $request)
    {

        $modulo = $request->input('modulo');
        $observaciones = $request->input('observaciones');
        $tipoTurno = $request->input('tipo_turno');
        $estatusFinalizado = 1; // Estatus de finalizado
        $fechaActual = Carbon::now()->toDateString();

        // --- Verificación de Paros Pendientes ---
        $queryParosPendientes = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')
            ->whereNull('fin_paro');

        if ($tipoTurno === 'extra') {
            $queryParosPendientes->where('tiempo_extra', 1);
            $mensajeErrorParo = 'Tiene paros pendientes en tiempo extra. Finalícelos e intente de nuevo.';
        } else { // 'normal'
            $queryParosPendientes->where(function ($query) {
                $query->where('tiempo_extra', 0)
                    ->orWhereNull('tiempo_extra');
            });
            $mensajeErrorParo = 'Tiene paros pendientes en turno normal. Finalícelos e intente de nuevo.';
        }

        $parosPendientes = $queryParosPendientes->exists();

        if ($parosPendientes) {
            return response()->json([
                'success' => false,
                'message' => $mensajeErrorParo
            ]);
        }

        // --- Proceder con la Actualización/Finalización ---
        // Primero, verifica si ya está finalizado para evitar re-finalizar innecesariamente
        // o para manejar el caso en que no hay registros que finalizar.
        $queryBaseRegistros = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo);

        if ($tipoTurno === 'extra') {
            $queryBaseRegistros->where('tiempo_extra', 1);
        } else { // 'normal'
            $queryBaseRegistros->where(function ($query) {
                $query->where('tiempo_extra', 0)
                    ->orWhereNull('tiempo_extra');
            });
        }

        // Clonar para contar y luego para actualizar
        $queryParaContar = clone $queryBaseRegistros;
        $queryParaActualizar = clone $queryBaseRegistros;

        $existenRegistrosParaTurno = $queryParaContar->exists();

        if (!$existenRegistrosParaTurno) {
            return response()->json([
                'success' => false,
                'message' => 'No hay registros para finalizar en el turno de ' . ($tipoTurno === 'extra' ? 'tiempo extra' : 'normal') . '.'
            ]);
        }

        // Actualizar los registros del turno específico.
        // Aquí es donde decides si guardas la observación
        // o en cada registro. Si es en cada registro, se replicará.
        // Para este ejemplo, asumiré un campo 'observacion' y 'estatus'
        $datosActualizar = [
            // Si tienes un campo específico para la observación general del turno, úsalo:
            'observacion' => $observaciones,
            // Si no, y la observación de finalización va en el mismo campo 'observacion' que los registros individuales:
            // 'observacion' => $observaciones, // Descomenta y ajusta si es necesario
            'estatus' => $estatusFinalizado
        ];


        $registrosActualizados = $queryParaActualizar->update($datosActualizar);

        if ($registrosActualizados > 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Finalización de turno ' . ($tipoTurno === 'extra' ? 'extra' : 'normal') . ' aplicada correctamente.',
                'observacion_guardada' => $observaciones,
                'tipo_turno_finalizado' => $tipoTurno
            ]);
        } else {
            // Esto podría pasar si los registros ya estaban finalizados con estatus 1
            // o si, por alguna razón, la query de existencia pasó pero la de actualización no afectó filas (raro si estatus no era ya 1).
            // Es bueno verificar si ya estaban finalizados.
            $yaFinalizadoQuery = clone $queryBaseRegistros; // Reusamos la query base
            $yaFinalizado = $yaFinalizadoQuery->where('estatus', $estatusFinalizado)->exists();

            if ($yaFinalizado) {
                // Si ya estaba finalizado, pero se quiere actualizar la observación
                $queryParaActualizarObs = clone $queryBaseRegistros;
                $obsActualizada = $queryParaActualizarObs->where('estatus', $estatusFinalizado)
                    ->update(['observacion_general' => $observaciones]);
                if ($obsActualizada > 0) {
                    return response()->json([
                        'success'   => true,
                        'message'   => 'Observación del turno ' . ($tipoTurno === 'extra' ? 'extra' : 'normal') . ' actualizada.',
                        'observacion_guardada' => $observaciones,
                        'tipo_turno_finalizado' => $tipoTurno
                    ]);
                }
                return response()->json([
                    'success'   => true, // O false, dependiendo de cómo quieras manejar "ya estaba hecho"
                    'message'   => 'El turno ' . ($tipoTurno === 'extra' ? 'extra' : 'normal') . ' ya estaba finalizado. No se realizaron cambios.',
                    'observacion_guardada' => $observaciones, // Podrías devolver la observación existente si prefieres
                    'tipo_turno_finalizado' => $tipoTurno
                ]);
            }

            return response()->json([
                'success'   => false,
                'message'   => 'No se actualizaron registros para el turno de ' . ($tipoTurno === 'extra' ? 'tiempo extra' : 'normal') . '. Podrían ya estar actualizados o no existir.'
            ]);
        }
    }
}
