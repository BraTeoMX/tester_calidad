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
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        // Formateamos la fecha aquí para evitar lógica en la vista
        $fechaActualCarbon = Carbon::now();
        $fechaActualParaVista = $fechaActualCarbon->format('d ') . $mesesEnEspanol[$fechaActualCarbon->format('n') - 1] . $fechaActualCarbon->format(' Y');

        return view('proceso.index', compact(
            'pageSlug',
            'auditorDato', // Necesario para el campo readonly en el formulario principal
            'tipoUsuario', // Podría ser necesario para lógica condicional en la vista
            'fechaActualParaVista'
            // Los siguientes datos se cargarán por AJAX:
            // 'gerenteProduccion',
            // 'procesoActual',
            // 'procesoFinal'
        ));
    }

    

    public function obtenerModulos()
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";
        // Obtener datos de las dos tablas
        $datosCategoriaSupervisor = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
            ->whereBetween('moduleid', ['100A', '299A'])
            ->get(['moduleid']);

        $datosModuloEstiloTemporal = ModuloEstiloTemporal::where('prodpoolid', $datoPlanta)
            ->whereBetween('moduleid', ['100A', '299A'])
            ->distinct('moduleid')
            ->get(['moduleid']);

        // Combinar y eliminar duplicados
        $listaModulos = $datosCategoriaSupervisor->concat($datosModuloEstiloTemporal)
            ->unique('moduleid')
            ->sortBy('moduleid')
            ->values();

        return response()->json($listaModulos);
    }

    // Nuevo método para obtener Gerentes de Producción vía AJAX
    public function obtenerGerentesProduccionAjax(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        // Clave única para el caché por planta
        $cacheKey = "gerentes_produccion_{$datoPlanta}";
        $tiempoCache = 60 ; // Cachear por 1 minuto (en segundos)

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

        // Clave de caché dinámica
        $cacheKeyParts = [
            'procesos',
            $tipoProceso,
            $datoPlanta,
            $fechaActual
        ];
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $cacheKeyParts[] = "auditor_{$auditorDato}";
        } else {
            $cacheKeyParts[] = "todos";
        }
        $cacheKey = implode('_', $cacheKeyParts);
        $tiempoCache = 60; // Cachear por 1 minutos (ajusta según la frecuencia de actualización)

        $procesos = Cache::remember($cacheKey, $tiempoCache, function () use ($tipoProceso, $datoPlanta, $fechaActual, $tipoUsuario, $auditorDato) {
            $query = AseguramientoCalidad::where('planta', $datoPlanta)
                ->whereDate('created_at', $fechaActual)
                ->select('modulo', 'estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
                ->distinct()
                ->orderBy('modulo', 'asc');

            if ($tipoProceso === 'actual') {
                $query->whereNull('estatus');
            } else { // 'final'
                $query->where('estatus', 1);
            }

            if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
                $query->where('auditor', $auditorDato);
            }

            return $query->get();
        });

        return response()->json($procesos);
    }

    public function obtenerEstilos(Request $request)
    {
        $moduleid = $request->input('moduleid');

        // Obtener los estilos con su respectivo cliente desde ModuloEstilo
        $itemidsModuloEstilo = ModuloEstilo::select('itemid', 'custname')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$moduleid])
            ->distinct('itemid', 'custname')
            ->orderBy('prioridad') // Priorizar los relacionados al módulo
            ->orderBy('itemid') // Ordenar por itemid después
            ->get();

        // Obtener los estilos desde ModuloEstiloTemporal
        $itemidsModuloEstiloTemporal = ModuloEstiloTemporal::select('itemid', 'custname')
            ->distinct('itemid', 'custname')
            ->orderBy('itemid') // Ordenar por itemid
            ->get();

        // Combinar ambos resultados y eliminar duplicados
        $estilosCombinados = $itemidsModuloEstilo->concat($itemidsModuloEstiloTemporal)
            ->unique('itemid');

        return response()->json([
            'estilos' => $estilosCombinados->values(),
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
            ->where(function($query) {
                $query->where('moduleid', 'like', '1%')
                    ->orWhere('moduleid', 'like', '2%')
                    ->orWhereIn('moduleid', ['830A', '831A']);
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
        $pageSlug ='';

        $data = [
            'modulo' => $request->modulo,
            'estilo' => $request->estilo,
            'team_leader' => $request->team_leader,
            'auditor' => $request->auditor,
            'turno' => $request->turno,
            'gerente_produccion' => $request->gerente_produccion,
        ];
        //dd($data);

        return redirect()->route('procesoV3.registro', 
            array_merge($data))->with('cambio-estatus', 'Iniciando en modulo: '. $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function auditoriaProceso(Request $request)
    {
        $pageSlug ='';
        $fechaActual = Carbon::now()->toDateString();
        //$fechaActual = Carbon::now()->subDay()->toDateString();
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
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
        $fechaActual = now()->toDateString();
        $auditorPlanta = Auth::user()->Planta;
        $auditorDato = Auth::user()->name;
        $tipoUsuario = Auth::user()->puesto;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        $procesoActual = AseguramientoCalidad::whereNull('estatus')
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo', 'estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');
        // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoActual->where('auditor', $auditorDato);
        }
        $procesoActual = $procesoActual->get();

        return response()->json([
            'procesos' => $procesoActual,
        ]);
    }

    public function obtenerNombresGenerales(Request $request)
    {
        $modulo = $request->input('modulo'); // Obtener el módulo desde AJAX
        $search = $request->input('search'); // Obtener el término de búsqueda
        $auditorPlanta = auth()->user()->Planta ?? 'Planta1'; // Ajustar según sea necesario
        $detectarPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";
        //Log::info('Planta detectada:', ['planta' => $detectarPlanta]);

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

        // Si el usuario está buscando, filtrar los resultados
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('personnelnumber', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%");
            });
        }

        // Obtener todos los datos y ordenarlos
        $nombresGenerales = $query
            ->select('personnelnumber', 'name', 'moduleid')
            ->distinct()
            ->orderByRaw("CASE WHEN moduleid = ? THEN 0 ELSE 1 END, name ASC", [$modulo])
            ->get();

        return response()->json([
            'nombres' => $nombresGenerales
        ]);
    }


    public function obtenerOperaciones(Request $request)
    {
        $modulo = $request->input('modulo');
        $search = $request->input('search');

        $excluidos = [
            "APP SCREEN:    /    /", 
            "APPROVED     /    /", 
            "APPROVED / /",
            "APPROVED //",
            "OFF LINE", 
            "ON CUT", 
            "ON LINE", 
            "OUT CUT"
        ];

        $query = JobOperacion::whereNotIn('oprname', $excluidos);

        // Filtrar por módulo si existe
        if (!empty($modulo)) {
            $query->where('moduleid', $modulo);
        }

        // Aplicar búsqueda si el usuario está escribiendo
        if (!empty($search)) {
            $query->where('oprname', 'like', "%$search%");
        }

        $operaciones = $query->select('oprname')->distinct()->orderBy('oprname', 'asc')->get();

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
        $search = $request->input('search');

        $query = CategoriaTipoProblema::whereIn('area', ['proceso', 'playera']);

        // Aplicar filtro si el usuario escribe algo
        if (!empty($search)) {
            $query->where('nombre', 'like', "%$search%");
        }

        $defectos = $query->select('nombre')->distinct()->orderBy('nombre', 'asc')->get();

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
            Log::info("Datos recibidos en el controlador:", $datosFormulario);

            // Si no hay piezas rechazadas, limpiar 'ac' y 'tp'
            if ($datosFormulario['auditoria'][0]['cantidad_rechazada'] == 0) {
                $datosFormulario['auditoria'][0]['accion_correctiva'] = null;
                // Eliminar 'tipo_problema' del array para evitar que se registre
                unset($datosFormulario['auditoria'][0]['tipo_problema']);
            }

            $fechaHoraActual = now();
            $diaSemana = $fechaHoraActual->dayOfWeek;

            // Buscar la planta asociada al módulo
            $primerCaracter = substr($datosFormulario['modulo'], 0, 1); // Obtiene el primer carácter

            // Definir el valor de $plantaBusqueda según el primer carácter
            if ($primerCaracter === '1') {
                $plantaBusqueda = "Intimark1";
            } elseif ($primerCaracter === '2') {
                $plantaBusqueda = "Intimark2";
            } else {
                $plantaBusqueda = null; // O algún valor por defecto si no coincide con 1 o 2
            }


            // Obtener el cliente desde la base de datos
            $obtenerEstilo = $datosFormulario['estilo']; 
            $obtenerCliente = $datosFormulario['cliente']; 
            $obtenerCliente = $obtenerCliente ?: ModuloEstiloTemporal::where('itemid', $datosFormulario['estilo'])->value('custname');


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

            // Determinar si aplica tiempo extra según la hora y el día
            if ($diaSemana >= 1 && $diaSemana <= 4) { // Lunes a Jueves
                $nuevoRegistro->tiempo_extra = ($fechaHoraActual->hour >= 19) ? 1 : null;
            } elseif ($diaSemana == 5) { // Viernes
                $nuevoRegistro->tiempo_extra = ($fechaHoraActual->hour >= 14) ? 1 : null;
            } else { // Sábado y domingo
                $nuevoRegistro->tiempo_extra = 1;
            }

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
                 Log::warning('Intento de obtenerListaProcesos sin especificar módulo.');
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
    
    public function cambiarEstadoInicioParoTurnoNormal(Request $request)
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

    public function eliminarRegistroTurnoNormal(Request $request)
    {
        try {
            $id = $request->id;
            $registro = AseguramientoCalidad::find($id);

            if (!$registro) {
                return response()->json(['error' => 'Registro no encontrado'], 404);
            }

            // Verificar si el registro tiene estatus (por ejemplo, si no es nulo o es igual a 1)
            if (!is_null($registro->estatus)) {
                return response()->json([
                    'warning' => 'No se puede eliminar porque ya se finalizó la auditoría.'
                ], 200); // ⚠ Se devuelve un código 200 en lugar de 400 para no disparar "error" en AJAX
            }

            $registro->delete(); // Eliminar el registro

            return response()->json(['message' => 'Registro eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el registro: ' . $e->getMessage()], 500);
        }
    }


    public function buscarUltimoRegistroProceso(Request $request)
    {
        // Obtener el módulo enviado desde el formulario
        $modulo = $request->input('modulo');
        $fechaActual = now()->toDateString();
        //dd($request->all());

        // Buscar el último registro que coincida con las condiciones
        $registro = AseguramientoCalidad::whereDate('created_at', $fechaActual)
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

            //dd($registro);

            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', 'Paro modular finalizado correctamente. Tiempo acumulado: ' . $diferenciaEnMinutos . ' minutos.');
        }

        // Si no se encuentra ningún registro
        return redirect()->back()->with('error', 'No se encontró ningún registro para finalizar el paro modular.');
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


}
