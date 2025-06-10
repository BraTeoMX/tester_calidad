<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobAQL;
use App\Models\JobAQLTemporal;
use App\Models\AuditoriaProceso;
use App\Models\CategoriaTeamLeader;
use App\Models\CategoriaTipoProblema;
use App\Models\AuditoriaAQL;
use App\Models\CategoriaUtility;
use App\Models\TpAuditoriaAQL;
use App\Models\CategoriaSupervisor; 
use App\Models\ModuloEstiloTemporal;
use App\Models\ModuloEstilo;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AuditoriaAQLV3Controller extends Controller
{

    public function index(Request $request)
    {
        $pageSlug ='';
        $auditorDato = Auth::user()->name;

        return view('AQL.index', compact('pageSlug', 'auditorDato'));
    }

    public function initialData(Request $request)
    {
        $auditorDato = Auth::user()->name;
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $fechaFormateada = now()->format('d ') . $mesesEnEspanol[now()->format('n') - 1] . now()->format(' Y');

        return response()->json([
            'auditorDato' => $auditorDato,
            'fechaFormateada' => $fechaFormateada,
        ]);
    }

    // Método para obtener la lista de módulos
    public function listaModulos(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        $cacheKey = 'listaModulos_' . $datoPlanta;
        $minutes = 5;

        // Intentar obtener de la caché
        $listaModulos = Cache::get($cacheKey);

        if (is_null($listaModulos)) { // Si no está en caché o es null
            $datosCategoriaSupervisor = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
                ->whereBetween('moduleid', ['100A', '299A'])
                ->get(['moduleid']);

            $datosModuloEstiloTemporal = ModuloEstiloTemporal::where('prodpoolid', $datoPlanta)
                ->whereBetween('moduleid', ['100A', '299A'])
                ->distinct('moduleid')
                ->get(['moduleid']);

            $calculatedList = $datosCategoriaSupervisor->concat($datosModuloEstiloTemporal)
                ->unique('moduleid')
                ->sortBy('moduleid')
                ->values();

            if ($calculatedList->isNotEmpty()) {
                Cache::put($cacheKey, $calculatedList, $minutes); // Guardar en caché solo si no está vacía
                $listaModulos = $calculatedList;
            } else {
                // No guardar en caché si está vacía.
                // $listaModulos permanecerá null o puedes asignarle un array vacío para la respuesta.
                $listaModulos = collect([]); // O $listaModulos = [];
            }
        }

        return response()->json($listaModulos);
    }

    // Método para obtener los gerentes de producción
    public function gerentesProduccion()
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";
        $cacheKey = 'gerentes_produccion_planta_' . $datoPlanta;

        $duracionCacheEnSegundos = 120; // 2 minutos * 60 segundos/minuto
        $gerentes = Cache::remember($cacheKey, $duracionCacheEnSegundos, function () use ($datoPlanta) {
            return CategoriaTeamLeader::where('planta', $datoPlanta)
                ->where('estatus', 1)           // Filtra por planta y estatus
                ->where('jefe_produccion', 1)   // Filtra solo los que son jefes de producción
                ->orderBy('nombre', 'asc')      // Ordena los resultados por el nombre del gerente
                ->get(['nombre']);              // Obtiene solo la columna 'nombre'
        });

        return response()->json($gerentes);
    }

    public function cargarOrdenesOP(Request $request)
    {
        $moduloSeleccionado = $request->input('modulo');

        // Obtener datos únicos de JobAQL
        $ordenesJobAQL = JobAQL::select('prodid')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$moduloSeleccionado])
            ->distinct();

        // Obtener datos únicos de JobAQLTemporal
        $ordenesJobAQLTemporal = JobAQLTemporal::select('prodid')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$moduloSeleccionado])
            ->distinct();

        // Unir ambas consultas y obtener valores únicos
        $ordenesOPFiltradas = $ordenesJobAQL
            ->union($ordenesJobAQLTemporal) // Unir ambas consultas
            ->orderBy('prioridad') // Priorizar las OPs relacionadas con el módulo seleccionado
            ->orderBy('prodid') // Ordenar el resto por prodid
            ->get();

        // Convertir los datos a formato JSON y retornar
        return response()->json($ordenesOPFiltradas);
    }

    public function obtenerSupervisor(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        if($auditorPlanta == "Planta1"){
            $datoPlanta = "Intimark1";
        }else{
            $datoPlanta = "Intimark2";
        }
        
        $moduleid = $request->input('moduleid');

        // Supervisor relacionado con el moduleid (puedes ajustar esto según la lógica que determines como "relacionada")
        $supervisorRelacionado = CategoriaSupervisor::where('moduleid', $moduleid)
            ->where('prodpoolid', $datoPlanta)
            ->first();

        // Todos los supervisores que pertenecen a la misma planta
        $supervisores = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
            ->whereNotNull('name')          // Filtra los valores nulos de la columna 'name'
            ->where(function($query) {
                $query->where('moduleid', 'like', '1%')   // Filtra los que inician con "1"
                      ->orWhere('moduleid', 'like', '2%') // Filtra los que inician con "2"
                      ->orWhereIn('moduleid', ['830A', '831A']); // Incluye específicamente "830A" y "831A"
            })
            ->where('moduleid', '!=', '198A') // Excluye el valor específico "198A"
            ->select('name')                // Selecciona solo el campo 'name' o los que desees
            ->distinct()                    // Aplica el filtro para datos únicos
            ->get();

        return response()->json([
            'supervisorRelacionado' => $supervisorRelacionado,
            'supervisores' => $supervisores
        ]);
    }

    private function filtrarProcesosUnicos($procesos)
    {
        $valoresMostrados = [];
        $resultadoFiltrado = [];
        foreach ($procesos as $proceso) {
            // Usamos 'default_area' si $proceso->area es null para evitar errores con la clave del array
            $areaKey = $proceso->area ?? 'default_area_key'; 
            $moduloKey = $proceso->modulo ?? 'default_modulo_key';
            $opKey = $proceso->op ?? 'default_op_key';

            if (!isset($valoresMostrados[$areaKey][$moduloKey][$opKey])) {
                $resultadoFiltrado[] = $proceso;
                $valoresMostrados[$areaKey][$moduloKey][$opKey] = true;
            }
        }
        return $resultadoFiltrado;
    }

    public function getAuditoriaAQLData()
    {
        $fechaActual = Carbon::now()->toDateString();
        $auditorDato = Auth::user()->name;
        $auditorPlanta = Auth::user()->Planta;
        $tipoUsuario = Auth::user()->puesto;

        if ($auditorPlanta == "Planta1") {
            $datoPlanta = "Intimark1";
        } else {
            $datoPlanta = "Intimark2";
        }

        // Consulta unificada para obtener datos en proceso (estatus IS NULL) y finalizados (estatus = 1)
        $query = AuditoriaAQL::query()
            ->where(function ($q) {
                $q->whereNull('estatus')
                  ->orWhere('estatus', 1);
            })
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select( // Asegúrate de que todos los campos necesarios estén aquí
                'estatus', 'modulo', 'op', 'team_leader', 
                'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion'
            )
            ->orderBy('modulo', 'asc'); // Ordenar para consistencia en el filtro PHP

        // Aplicar filtro por auditor si no es Administrador o Gerente de Calidad
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $query->where('auditor', $auditorDato);
        }

        $todosLosProcesos = $query->get();

        // Separar los procesos en "actuales" (en proceso) y "finalizados"
        $procesosActualesRaw = [];
        $procesosFinalizadosRaw = [];

        foreach ($todosLosProcesos as $proceso) {
            if (is_null($proceso->estatus)) {
                $procesosActualesRaw[] = $proceso;
            } elseif ($proceso->estatus == 1) {
                $procesosFinalizadosRaw[] = $proceso;
            }
        }

        // Aplicar el filtrado para evitar duplicados en la vista (basado en area, modulo, op)
        $procesosActualesFiltrados = $this->filtrarProcesosUnicos($procesosActualesRaw);
        $procesosFinalizadosFiltrados = $this->filtrarProcesosUnicos($procesosFinalizadosRaw);
        
        return response()->json([
            'actuales' => $procesosActualesFiltrados,
            'finalizados' => $procesosFinalizadosFiltrados,
        ]);
    }

    public function formAltaAQLV3(Request $request)
    {
        $pageSlug = '';

        // Optimización: Seleccionar solo la columna 'customername'
        $datoUnicoOP = JobAQL::where('prodid', $request->op)
            ->select('customername') // Aquí se especifica la columna deseada
            ->first();

        if (!$datoUnicoOP) {
            return redirect()->back()->with('error', 'La OP proporcionada no fue encontrada.');
            // O si prefieres que 'cliente' sea nulo o un string vacío:
            // $customerName = null; // o $customerName = '';
        } else {
            // $customerName = $datoUnicoOP->customername; // No es necesario si se maneja en el array $data
        }

        $data = [
            'modulo' => $request->modulo,
            'estilo' => $request->estilo,
            'op' => $request->op,
            'cliente' => $datoUnicoOP ? $datoUnicoOP->customername : null, // O un valor por defecto si prefieres
            'auditor' => $request->auditor,
            'turno' => $request->turno,
            'team_leader' => $request->team_leader,
            'gerente_produccion' => $request->gerente_produccion,
        ];

        return redirect()->route('AQLV3.registro', $data)
                         ->with('cambio-estatus', 'Iniciando en modulo: ' . $data['modulo'])
                         ->with('pageSlug', $pageSlug);
    }

    public function registro(Request $request)
    {

        $pageSlug ='';
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $fechaActual = Carbon::now()->toDateString();
        $auditorDato = Auth::user()->name;
        $auditorPlanta = Auth::user()->Planta;
        
        if($auditorPlanta == 'Planta1'){
            $detectarPlanta = "Intimark1";
        }elseif($auditorPlanta == 'Planta2'){
            $detectarPlanta = "Intimark2";
        }

        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        // 1. Consulta general
        $registros = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('cantidad_rechazada', '>', 0)
            ->where('modulo', $data['modulo'])
            ->orderBy('created_at', 'asc')
            ->get();
        //dd($registros->count(), $registros->pluck('id')); // Ejemplo para ver cuántos y cuáles IDs

        // 2. Dividir en dos subconjuntos
        $registrosSinTE = $registros->filter(function ($r) {
            return is_null($r->tiempo_extra);
        })->values();

        $registrosConTE = $registros->filter(function ($r) {
            return $r->tiempo_extra == 1;
        })->values();
        function evaluarSubconjunto($registros)
        {
            // Si hay menos de 2, retorna false
            if ($registros->count() < 2) {
                return false;
            }
    
            $total = $registros->count();
            // Hallar el último par
            $ultimoPar = floor($total / 2) * 2;  // 3 => 2; 4 =>4; 5 =>4; 6 =>6, etc.
            $indice = $ultimoPar - 1;           // Para indexar en base 0
    
            // Registro a evaluar
            $registroEvaluar = $registros[$indice];
    
            // true si fin_paro_modular es null, false de lo contrario
            return is_null($registroEvaluar->fin_paro_modular);
        }
        // 3. Evaluar cada subconjunto
        $resultadoFinalSinTE = evaluarSubconjunto($registrosSinTE); 
        $resultadoFinalConTE = evaluarSubconjunto($registrosConTE); 

        // 4. El resultado final es TRUE si al menos uno de los 2 casos da true
        $resultadoFinal = $resultadoFinalSinTE || $resultadoFinalConTE;
        

        return view('aql.registro', compact('mesesEnEspanol', 'pageSlug',
            'data', 'resultadoFinal'));
    }

    public function obtenerAQLenProceso(Request $request)
    {
        // Datos de entrada
        $fechaActual = now()->toDateString();
        $usuarioAutenticado = Auth::user();
        $tipoUsuario = $usuarioAutenticado->puesto;
        $auditorDato = $usuarioAutenticado->name;

        // Construir una clave única para el caché
        $cacheKey = 'aql_en_proceso_groupby_' . $fechaActual; // Modificada para reflejar la nueva lógica
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $cacheKey .= '_' . str_replace(' ', '_', strtolower($auditorDato));
        }

        // Duración del caché en segundos
        $cacheDuration = 30; // 30 segundos

        // Columnas finales a seleccionar de la tabla principal
        $columnsToSelect = [
            'auditoria_aql.id as registro_id', // Opcional: para ver el ID del registro seleccionado
            'auditoria_aql.modulo',
            'auditoria_aql.op',
            'auditoria_aql.team_leader',
            'auditoria_aql.turno',
            'auditoria_aql.auditor',
            'auditoria_aql.estilo',
            'auditoria_aql.cliente',
            'auditoria_aql.gerente_produccion',
            // No incluyas 'estatus' aquí si siempre va a ser nulo por la condición de la subconsulta
            // No incluyas 'created_at' si siempre va a ser $fechaActual por la condición
        ];

        $procesos = Cache::remember($cacheKey, $cacheDuration, function () use ($fechaActual, $tipoUsuario, $auditorDato, $columnsToSelect) {
            // Subconsulta para encontrar el ID máximo (registro más próximo)
            // para cada combinación de modulo y op que cumpla las condiciones.
            $subQuery = AuditoriaAQL::select('modulo', 'op') // Columnas por las que se agrupa
                ->selectRaw('MAX(id) as max_id') // Selecciona el ID máximo para cada grupo
                ->whereNull('estatus')
                ->whereDate('created_at', $fechaActual);

            // Aplicar filtro de auditor DENTRO de la subconsulta
            // para que el MAX(id) se determine sobre los registros correctos.
            if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
                $subQuery->where('auditor', $auditorDato);
            }

            $subQuery->groupBy('modulo', 'op');

            // Consulta principal que se une con la subconsulta
            // para obtener todas las columnas del registro con el ID máximo.
            $query = AuditoriaAQL::query() // Iniciar con query() para claridad
                ->select($columnsToSelect)
                // Unir con la subconsulta (latest_aql_records es un alias para la subconsulta)
                ->joinSub($subQuery, 'latest_aql_records', function ($join) {
                    $join->on('auditoria_aql.id', '=', 'latest_aql_records.max_id');
                })
                ->orderBy('auditoria_aql.modulo', 'asc'); // Ordenar el resultado final

            return $query->get();
        });

        return response()->json($procesos);
    }

    public function obtenerOpcionesOP(Request $request)
    {
        $cacheKey = 'opciones_op_todas_lista';
        $duracionCacheEnSegundos = 5 * 60; // 5 minutos

        // Intentar obtener de la caché primero
        if (Cache::has($cacheKey)) {
            $datosOP = Cache::get($cacheKey);
            return response()->json($datosOP);
        }

        // Verificar si JobAQL tiene algún registro. Esto es para la condición de cacheo.
        $jobAQLExiste = JobAQL::exists();

        // Obtener todos los prodid de ambas tablas, unirlos, eliminar duplicados y ordenar.
        // No se usa el input 'search' aquí porque cargaremos toda la lista.
        $datosOP = JobAQL::select('prodid')
            ->union(
                JobAQLTemporal::select('prodid')
            )
            ->distinct()
            ->orderBy('prodid')
            ->get(); // Devuelve una colección de objetos, cada uno con una propiedad 'prodid'

        // Aplicar el cacheo solo si JobAQL tiene datos.
        if ($jobAQLExiste) {
            Cache::put($cacheKey, $datosOP, $duracionCacheEnSegundos);
        }

        return response()->json($datosOP);
    }


    public function obtenerOpcionesBulto(Request $request)
    {
        $opSeleccionada = $request->input('op');

        if (!$opSeleccionada) {
            return response()->json([]); // Importante: si no hay OP, no hay bultos
        }

        // La clave de caché ahora incluye la OP específica
        $cacheKey = 'bultos_para_op_' . $opSeleccionada;
        $duracionCacheEnSegundos = 60; // 1 minuto

        // Intentar obtener de la caché
        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        // Condición para cachear: ¿Existen registros en JobAQL para esta OP?
        $jobAQLExisteParaEstaOP = JobAQL::where('prodid', $opSeleccionada)->exists();

        // Columnas que necesitas para la lógica del frontend (incluyendo las de 'extra')
        $selectColumns = [
            'prodid',
            'prodpackticketid',
            'qty',
            'itemid',
            'colorname',
            'customername',
            'inventcolorid',
            'inventsizeid'
        ];

        // Consulta para obtener TODOS los bultos de la OP, sin el filtro 'search' de bulto
        $query = JobAQL::where('prodid', $opSeleccionada)
            ->select($selectColumns)
            ->union(
                JobAQLTemporal::where('prodid', $opSeleccionada)
                    ->select($selectColumns)
            )
            ->distinct() // Aplicado al resultado de la unión
            ->orderBy('prodpackticketid'); // Ordenar los bultos

        $datosBulto = $query->get();

        // Cachear solo si la condición se cumple
        if ($jobAQLExisteParaEstaOP) {
            Cache::put($cacheKey, $datosBulto, $duracionCacheEnSegundos);
        }

        return response()->json($datosBulto);
    }

    public function obtenerDefectosAQL(Request $request)
    {
        // La búsqueda por término ('search') ya no es necesaria aquí
        // si todas las opciones se cargan para búsqueda local en Select2.
        // Si aún necesitas una carga inicial filtrada por algún motivo, puedes mantenerla,
        // pero la búsqueda de Select2 operará sobre el conjunto de datos ya cargado.

        $defectos = Cache::remember('defectos_aql_todos', 30, function () {
            // Obtenemos todos los defectos para el área 'aql', 'proceso', 'playera'
            // Si solo necesitas 'aql' para este select en específico, ajusta el whereIn.
            return CategoriaTipoProblema::whereIn('area', ['proceso', 'playera', 'aql'])
                                        ->orderBy('nombre', 'asc') // Opcional: ordenar los resultados
                                        ->get(['id', 'nombre']); // Solo seleccionamos los campos necesarios
        });

        // Si no se encuentran resultados, devolver arreglo vacío
        if ($defectos->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($defectos);
    }

    public function crearDefectoAQL(Request $request)
    {
        try {
            $nombre = $request->input('nombre');

            if (!$nombre) {
                return response()->json(['error' => 'El nombre es obligatorio'], 400);
            }

            // Convertir el nombre a mayúsculas para la búsqueda y creación
            $nombre = strtoupper($nombre);

            // Verificar si el defecto ya existe
            $defectoExistente = CategoriaTipoProblema::where('nombre', $nombre)
                ->where('area', 'aql') // Asumimos que los nuevos defectos son siempre para 'aql'
                ->first();

            if ($defectoExistente) {
                // Si ya existe, devolver el registro existente
                return response()->json($defectoExistente);
            }

            // Crear un nuevo defecto si no existe
            $nuevoDefecto = CategoriaTipoProblema::create([
                'nombre' => $nombre,
                'area' => 'aql', // Asignar el área directamente
            ]);

            // Invalidar el caché para que la próxima carga incluya el nuevo defecto
            Cache::forget('defectos_aql_todos');

            return response()->json($nuevoDefecto);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerNombresProceso(Request $request)
    {
        try {
            $modulo = $request->input('modulo');

            // Es crucial que $modulo tenga un valor si tu orderByRaw depende de él.
            // Si $modulo puede no venir, debes decidir cómo manejar el orderByRaw o la clave de caché.
            if (!$modulo) {
                // Podrías asignar un valor por defecto o manejar el error si es indispensable.
                // Log::warning('Se accedió a obtenerNombresProceso sin un parámetro de módulo.');
                // Para este ejemplo, asumiremos que el query maneja un $modulo nulo o vacío si es posible,
                // o que siempre se envía. Si $modulo es estrictamente necesario:
                // return response()->json(['error' => 'El parámetro módulo es requerido.'], 400);
            }

            // La clave de caché debe considerar el $modulo ya que el ordenamiento depende de él.
            // Si $modulo es null o una cadena vacía, asegúrate que esto genere una clave válida y consistente.
            $cacheKey = "lista_completa_nombres_proceso_mod_priorizado_" . strval($modulo);
            $cacheDuration = 180; // 3 minutos en segundos (3 * 60)

            $nombres = Cache::remember($cacheKey, $cacheDuration, function () use ($modulo) {

                $query = AuditoriaProceso::query()
                    ->select('name', 'personnelnumber')
                    ->distinct();

                // Aplicamos el ordenamiento que prioriza el módulo actual.
                // Asegúrate que 'moduleid' sea el nombre correcto de la columna en tu tabla.
                // Si $modulo es null, la comparación `moduleid = NULL` en SQL es especial (debería ser `moduleid IS NULL`).
                // Si $modulo siempre va a tener un valor, esto está bien.
                // Si $modulo puede ser nulo y quieres manejarlo, podrías hacer:
                if (!empty($modulo)) {
                    $query->orderByRaw("CASE WHEN moduleid = ? THEN 0 ELSE 1 END", [$modulo])
                          ->orderBy('name'); // Orden secundario para consistencia dentro de los grupos
                } else {
                    // Si no hay módulo, un ordenamiento general
                    $query->orderBy('name');
                }
                
                // IMPORTANTE: No se incluye la lógica `if ($search)` aquí.
                // Esta función ahora devuelve el conjunto completo de datos para la búsqueda del lado del cliente.

                return $query->get();
            });

            return response()->json($nombres);

        } catch (\Exception $e) {
            // Loguear el error detallado en el servidor
            Log::error('Error en obtenerNombresProceso: ' . $e->getMessage(), [
                'modulo' => $request->input('modulo'),
                'exception_trace' => $e->getTraceAsString() // Para depuración más profunda si es necesario
            ]);
            // Devolver un mensaje de error genérico al cliente
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud.'], 500);
        }
    }


    public function guardarRegistrosAql(Request $request)
    {
        try {
            // Registrar los datos recibidos en el archivo de log
            //Log::info('Aqui va bien, donde rompe?:');
            //Log::info('Datos recibidos en guardarRegistrosAql:', $request->selectedNombre);
            $fechaHoraActual= now();

            // Verificar el día de la semana
            $diaSemana = $fechaHoraActual ->dayOfWeek;
            $fechaActual = Carbon::now()->toDateString();
            $conteoParos = AuditoriaAQL::whereDate('created_at', $fechaActual)
                //->where('area', $request->area)
                ->where('modulo', $request->modulo)
                ->where('op', $request->op)
                ->where('team_leader', $request->team_leader)
                ->where('cantidad_rechazada', '>', 0)
                ->count();
            
            // Buscar la planta asociada al módulo
            $primerCaracter = substr($request->modulo, 0, 1);

            $plantaBusqueda = match ($primerCaracter) {
                '1' => 'Intimark1',
                '2' => 'Intimark2',
                default => null, // O el valor por defecto que necesites
            };

            $nombreFinal = $request->selectedNombre;
            $nombreFinalValidado = null;
            $numeroEmpleado = null;
            //Log::info('Antes del if');
            if ($nombreFinal && is_array($nombreFinal)) {
                //Log::info('Inicia procesamiento de nombres:', $nombreFinal);
                $nombresValidados = [];
                $numerosEmpleados = [];
    
                foreach ($nombreFinal as $nombre) {
                    //Log::info('Procesando nombre individual:', ['nombre' => $nombre]);
                    $nombreValidado = trim($nombre);
                    $nombresValidados[] = $nombreValidado;
    
                    // Intentamos buscar primero en el modelo AuditoriaProceso
                    $numeroEmpleado = AuditoriaProceso::where('name', $nombreValidado)->pluck('personnelnumber')->first();
    
                    // Si no lo encontramos en AuditoriaProceso, intentamos buscar en CategoriaUtility
                    if (!$numeroEmpleado) {
                        $numeroEmpleado = CategoriaUtility::where('nombre', $nombreValidado)->pluck('numero_empleado')->first();
                    }
    
                    // Si tampoco se encuentra en CategoriaUtility, devolvemos un valor de 0 para que tenga almacenado algo y no marque error
                    $numerosEmpleados[] = $numeroEmpleado ? $numeroEmpleado : "0000000";
                }
    
                // Concatenamos los nombres y números de empleados con comas
                $nombreFinalValidado = implode(', ', $nombresValidados);
                $numeroEmpleado = implode(', ', $numerosEmpleados);
            }
            //Log::info('despues del if y comeinza el new de nuevos registros');
    
            //Log::info('Nombres validados:', $nombresValidados);
            //Log::info('Números de empleados:', $numerosEmpleados);

            $nuevoRegistro = new AuditoriaAQL();
            $nuevoRegistro->numero_empleado = $numeroEmpleado;
            $nuevoRegistro->nombre = $nombreFinalValidado;
            $nuevoRegistro->modulo = $request->modulo;
            $nuevoRegistro->op = $request->op_seleccion;
            $nuevoRegistro->cliente = $request->customername;
            $nuevoRegistro->team_leader = $request->team_leader;
            $nuevoRegistro->gerente_produccion = $request->gerente_produccion;
            $nuevoRegistro->auditor = $request->auditor;
            $nuevoRegistro->turno = $request->turno;
            $nuevoRegistro->planta = $plantaBusqueda;

            $nuevoRegistro->bulto = $request->bulto_seleccion;
            $nuevoRegistro->pieza = $request->pieza;
            $nuevoRegistro->estilo = $request->estilo;
            $nuevoRegistro->color = $request->color;
            $nuevoRegistro->talla = $request->talla;
            $nuevoRegistro->cantidad_auditada = $request->cantidad_auditada;
            $nuevoRegistro->cantidad_rechazada = $request->cantidad_rechazada;
            //Log::info('antes del if del registro');
            if($request->cantidad_rechazada > 0){
                $nuevoRegistro->inicio_paro = Carbon::now();
            }

            // Verificar la hora para determinar el valor de "tiempo_extra"
            if ($diaSemana >= 1 && $diaSemana <= 4) { // De lunes a jueves
                if ($fechaHoraActual->hour >= 19) { // Después de las 7:00 pm
                    $nuevoRegistro->tiempo_extra = 1;
                } else {
                    $nuevoRegistro->tiempo_extra = null;
                }
            } elseif ($diaSemana == 5) { // Viernes
                if ($fechaHoraActual->hour >= 14) { // Después de las 2:00 pm
                    $nuevoRegistro->tiempo_extra = 1;
                } else {
                    $nuevoRegistro->tiempo_extra = null;
                }
            } else { // Sábado y domingo
                $nuevoRegistro->tiempo_extra = 1;
            }

            if ((($conteoParos == 1) && ($request->cantidad_rechazada > 0)) || (($conteoParos == 3) && ($request->cantidad_rechazada > 0))) {
                $nuevoRegistro->paro_modular = 1;
            }
            $nuevoRegistro->ac = $request->accion_correctiva;
            $nuevoRegistro->save();

            // Obtener el ID del nuevo registro
            $nuevoRegistroId = $nuevoRegistro->id;

            // Almacenar los valores de tp en la tabla tp_auditoria_aql

            // Asegúrate de que $request->tp sea un arreglo y contenga "NINGUNO" si está vacío o es null
            $tp = $request->input('selectedAQL', ['NINGUNO']);

            // Itera sobre el arreglo $tp y guarda cada valor
            foreach ($tp as $valorTp) {
                $nuevoTp = new TpAuditoriaAQL();
                $nuevoTp->auditoria_aql_id = $nuevoRegistroId; // Asegúrate de que $nuevoRegistroId esté definido
                $nuevoTp->tp = $valorTp;
                $nuevoTp->save();
            }

            return response()->json(['message' => 'Datos guardados correctamente.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }
    
    public function mostrarRegistrosAqlUnificado(Request $request)
    {
        try {
            // Validar que el módulo venga en la petición (opcional pero recomendado)
            $request->validate([
                'modulo' => 'required|string',
                // 'fechaActual' podría ser opcional si siempre usas Carbon::now()
                // Si la envías desde el frontend, también la puedes validar:
                // 'fechaActual' => 'sometimes|date_format:Y-m-d',
            ]);

            // Usar la fecha de la petición si se envía, sino la fecha actual del servidor
            $fecha = Carbon::now()->toDateString(); 
            $modulo = $request->input('modulo');

            // 1. Realizar UNA SOLA consulta para obtener todos los registros del día y módulo
            $todosLosRegistros = AuditoriaAQL::whereDate('created_at', $fecha)
                ->where('modulo', $modulo)
                ->with('tpAuditoriaAQL') // Cargar la relación con los tipos de problema/defecto
                ->orderBy('created_at', 'asc') // Opcional: ordenar por hora de creación
                ->get();

            // 2. Separar los registros usando el método `partition` de las colecciones de Laravel
            // `partition` divide la colección en dos: una donde la condición es true, y otra donde es false.
            // Asumimos que `tiempo_extra == 1` significa tiempo extra, y cualquier otra cosa (null, 0) es turno normal.
            list($registrosTiempoExtra, $registrosTurnoNormal) = $todosLosRegistros->partition(function ($registro) {
                return $registro->tiempo_extra == 1; // O === true si es booleano en DB
            });

            // 3. Devolver la respuesta JSON con ambos conjuntos de datos
            return response()->json([
                'turno_normal' => $registrosTurnoNormal->values(), // ->values() para reindexar el array
                'tiempo_extra' => $registrosTiempoExtra->values(), // ->values() para reindexar el array
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en mostrarRegistrosAqlUnificado: ' . $e->getMessage(), $e->errors());
            return response()->json(['error' => 'Datos de entrada inválidos.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Loguear el error para depuración
            Log::error('Error en mostrarRegistrosAqlUnificado: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud.'], 500);
        }
    }


    public function eliminarRegistroAql(Request $request)
    {
        $id = $request->input('id');
        $registro = AuditoriaAQL::find($id);

        if (!$registro) {
            return response()->json(['success' => false, 'message' => 'Registro no encontrado.'], 404);
        }

        // Validar si el estatus es 1 (Auditoría finalizada)
        if ($registro->estatus == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Auditoria finalizada, no se puede borrar el registro'
            ]);
        }

        // Si no está finalizada, proceder a eliminar
        $registro->tpAuditoriaAQL()->delete();
        $registro->delete();

        return response()->json(['success' => true, 'message' => 'Registro eliminado exitosamente.']);
    }


    public function buscarUltimoRegistro(Request $request)
    {
        // Obtener el módulo enviado desde el formulario
        $modulo = $request->input('modulo');
        $fechaActual = now()->toDateString();

        // Buscar el último registro que coincida con las condiciones
        $registro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('cantidad_rechazada', '>', 0)
            ->where('modulo', $modulo)
            ->latest('created_at') // Trae el último registro por fecha
            ->first();

        // Si se encuentra un registro, actualizar las columnas
        if ($registro) {
            // Obtener la hora actual para fin_paro_modular
            $horaActual = now();

            // Actualizar la columna "fin_paro_modular" con la hora actual
            $registro->fin_paro_modular = $horaActual;

            // Calcular la diferencia en minutos entre "inicio_paro" y "fin_paro_modular"
            $inicioParo = Carbon::parse($registro->inicio_paro);
            $diferenciaEnMinutos = $inicioParo->diffInMinutes($horaActual);

            // Actualizar la columna "minutos_paro_modular" con la diferencia
            $registro->minutos_paro_modular = $diferenciaEnMinutos;

            // Guardar los cambios en la base de datos
            $registro->save();

            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Paro modular finalizado correctamente. Tiempo acumulado: ' . $diferenciaEnMinutos . ' minutos.');
        }

        // Si no se encuentra ningún registro
        return redirect()->back()->with('error', 'No se encontró ningún registro para finalizar el paro modular.');
    }

    public function finalizarParoAQL(Request $request)
    {
        try {
            $registro = AuditoriaAQL::findOrFail($request->id);
            $registro->fin_paro = Carbon::now();

            $inicioParo = Carbon::parse($registro->inicio_paro);
            $finParo = Carbon::parse($registro->fin_paro);
            $registro->minutos_paro = $inicioParo->diffInMinutes($finParo);
            $registro->reparacion_rechazo = $request->piezasReparadas;
            $registro->save();

            return response()->json([
                'success' => true,
                'message' => 'Paro finalizado y piezas reparadas almacenadas correctamente.',
                'minutos_paro' => $registro->minutos_paro,
                'reparacion_rechazo' => $registro->reparacion_rechazo
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al finalizar el paro: ' . $e->getMessage()
            ], 500);
        }
    }

    public function finalizarAuditoriaModuloUnificado(Request $request)
    {

        $modulo = $request->input('modulo');
        $observaciones = $request->input('observaciones');
        $tipoTurno = $request->input('tipo_turno');
        $estatus = 1; // Estatus de finalizado
        $fechaActual = Carbon::now()->toDateString();

        // --- Verificación de Paros Pendientes ---
        $queryParosPendientes = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')
            ->whereNull('fin_paro');

        if ($tipoTurno === 'tiempo_extra') {
            $queryParosPendientes->where('tiempo_extra', 1);
            $mensajeErrorParo = 'Tiene paros pendientes en tiempo extra, finalícelos e intente de nuevo.';
        } else { // 'normal'
            // Para turno normal, asumimos que 'tiempo_extra' es null o 0. Ajusta si es diferente.
            $queryParosPendientes->where(function ($query) {
                $query->where('tiempo_extra', 0)
                      ->orWhereNull('tiempo_extra');
            });
            $mensajeErrorParo = 'Tiene paros pendientes en turno normal, finalícelos e intente de nuevo.';
        }

        $parosPendientes = $queryParosPendientes->exists();

        if ($parosPendientes) {
            return response()->json([
                'success' => false,
                'message' => $mensajeErrorParo
            ]);
        }

        // --- Proceder con la Actualización/Finalización ---
        $queryActualizacion = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo);

        $datosActualizar = [
            'observacion' => $observaciones, // Guardar las observaciones generales del turno
            'estatus' => $estatus
            // Considera si necesitas un campo específico para la observación de finalización
            // y si 'observacion' se usa para otras cosas.
            // Por ejemplo, podrías tener 'observacion_finalizacion' => $observaciones
        ];

        if ($tipoTurno === 'tiempo_extra') {
            $queryActualizacion->where('tiempo_extra', 1);
            $mensajeExito = 'Finalización de tiempo extra aplicada correctamente.';
        } else { // 'normal'
             // Para turno normal, actualiza los registros donde tiempo_extra es null o 0
            $queryActualizacion->where(function ($query) {
                $query->where('tiempo_extra', 0)
                      ->orWhereNull('tiempo_extra');
            });
            $mensajeExito = 'Finalización de turno normal aplicada correctamente.';
        }

        // Ejecutar la actualización
        // Es importante verificar si se actualizó algún registro para dar un mensaje más preciso.
        $registrosActualizados = $queryActualizacion->update($datosActualizar);

        if ($registrosActualizados > 0) {
            return response()->json([
                'success'   => true,
                'message'   => $mensajeExito,
                'observacion_guardada' => $observaciones // Devuelve la observación guardada para confirmación si es necesario
            ]);
        } else {
            // Esto podría suceder si no hay registros que coincidan con los criterios para ese día/módulo/turno
            // o si ya estaban finalizados con los mismos datos.
            return response()->json([
                'success'   => false,
                'message'   => 'No se encontraron registros para finalizar o ya estaban actualizados para el turno de ' . $tipoTurno . '.'
            ]);
        }
    }


    public function verificarFinalizacion(Request $request)
    {
        try {
            $request->validate([
                'modulo' => 'required|string',
            ]);

            $modulo = $request->input('modulo');
            $fechaActual = Carbon::now()->toDateString();

            // Verificar finalización para Turno Normal
            // Buscamos el primer registro que cumpla las condiciones para considerarlo "finalizado"
            $registroNormal = AuditoriaAQL::whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->whereNull('tiempo_extra') // Turno Normal
                ->where('estatus', 1)       // Asumiendo estatus=1 significa finalizado
                ->orderBy('updated_at', 'desc') // Opcional: tomar el más reciente si hay varios
                ->first();

            // Verificar finalización para Tiempo Extra
            $registroTE = AuditoriaAQL::whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->where('tiempo_extra', 1)  // Tiempo Extra
                ->where('estatus', 1)       // Asumiendo estatus=1 significa finalizado
                ->orderBy('updated_at', 'desc') // Opcional
                ->first();

            return response()->json([
                'normal' => [
                    'finalizado'  => $registroNormal ? true : false,
                    'observacion' => $registroNormal ? $registroNormal->observacion : '',
                ],
                'tiempo_extra' => [
                    'finalizado'  => $registroTE ? true : false,
                    'observacion' => $registroTE ? $registroTE->observacion : '',
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Error de validación en verificarEstadoFinalizacionUnificado: ' . $e->getMessage(), $e->errors());
            return response()->json(['error' => 'Datos de entrada inválidos.', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error en verificarEstadoFinalizacionUnificado: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al verificar el estado de finalización.'], 500);
        }
    }



    public function bultosNoFinalizados(Request $request)
    {
        $modulo = $request->input('modulo');

        // Calcular las fechas para los últimos 7 días (sin contar hoy)
        $fechaInicio = Carbon::now()->subDays(7)->startOfDay(); // 7 días atrás a las 00:00:00
        $fechaFin = Carbon::yesterday()->endOfDay(); // Ayer hasta las 23:59:59

        // Consulta de registros en el rango de los últimos 7 días
        $bultos = AuditoriaAQL::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')
            ->whereNull('fin_paro')
            ->get();

        return response()->json($bultos);
    }


    public function finalizarParoAQLdespues(Request $request)
    {
        try {
            $registro = AuditoriaAQL::findOrFail($request->id);
            $registro->fin_paro = Carbon::now(); // Almacenamos la fecha actual como "fin_paro"

            // Usamos created_at como punto de inicio del cálculo
            $inicio = Carbon::parse($registro->created_at);
            $fin = Carbon::now();

            // Calcular minutos considerando horarios laborales
            $minutosParo = $this->calcularMinutosParoDesdeCreatedAt($inicio, $fin);

            $registro->minutos_paro = $minutosParo;
            $registro->reparacion_rechazo = $request->piezasReparadas;
            $registro->save();

            return response()->json([
                'success' => true,
                'message' => 'Paro finalizado y piezas reparadas almacenadas correctamente.',
                'minutos_paro' => $registro->minutos_paro,
                'reparacion_rechazo' => $registro->reparacion_rechazo
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
     * Respeta los horarios laborales establecidos:
     * - Lunes a jueves: 08:00 - 19:00
     * - Viernes: 08:00 - 14:00
     * - Fines de semana: no se cuentan.
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

            // Determinar horarios del día actual
            $inicioJornada = $actual->copy()->setTime(8, 0, 0);
            if ($actual->dayOfWeek == Carbon::FRIDAY) {
                $finJornada = $actual->copy()->setTime(14, 0, 0);
            } else {
                $finJornada = $actual->copy()->setTime(19, 0, 0);
            }

            // Calcular minutos dentro del horario laboral
            if ($actual->lessThanOrEqualTo($finJornada) && $fin->greaterThanOrEqualTo($inicioJornada)) {
                $inicioEfectivo = $actual->greaterThan($inicioJornada) ? $actual : $inicioJornada;
                $finEfectivo = $fin->lessThan($finJornada) ? $fin : $finJornada;

                if ($inicioEfectivo->lessThan($finEfectivo)) {
                    $minutosHoy = $inicioEfectivo->diffInMinutes($finEfectivo);
                    $totalMinutos += max($minutosHoy, 0);
                }
            }

            // Avanzar al siguiente día
            $actual->addDay()->startOfDay();
        }

        return $totalMinutos;
    }
}
