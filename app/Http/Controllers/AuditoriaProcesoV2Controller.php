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
use App\Models\CategoriaUtility;
use App\Models\JobOperacion;
use App\Models\TpAseguramientoCalidad; 
use App\Models\CategoriaSupervisor; 
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionParo;
use App\Models\JobAQL;
use App\Models\ModuloEstilo;
use App\Models\ModuloEstiloTemporal;
use Illuminate\Support\Facades\Log;


use App\Models\EvaluacionCorte;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon

class AuditoriaProcesoV2Controller extends Controller
{

    public function altaProcesoV2(Request $request)
    {
        $pageSlug ='';
        $auditorDato = Auth::user()->name;
        $tipoUsuario = Auth::user()->puesto;

        //dd($registroEvaluacionCorte->all()); 
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $fechaActual = Carbon::now()->toDateString();
        $auditorPlanta = Auth::user()->Planta;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        $gerenteProduccion = CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
            ->orderBy('jefe_produccion')
            ->where('planta', $datoPlanta)
            ->where('estatus', 1)
            ->where('jefe_produccion', 1)
            ->get();

        $procesoActual = AseguramientoCalidad::where('estatus', NULL)  
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');
        // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoActual->where('auditor', $auditorDato);
        }
        $procesoActual = $procesoActual->get();

        $procesoFinal =  AseguramientoCalidad::where('estatus', 1) 
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');
        // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoFinal->where('auditor', $auditorDato);
        }
        $procesoFinal = $procesoFinal->get();
        return view('aseguramientoCalidad.altaProcesoV2', compact('pageSlug', 'auditorDato', 'tipoUsuario', 'mesesEnEspanol', 'gerenteProduccion', 
                    'procesoActual', 'procesoFinal'));
    }

    public function obtenerModulosV2(Request $request)
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

    public function obtenerEstilosV2(Request $request)
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


    public function obtenerSupervisorV2(Request $request)
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


    public function formAltaProcesoV2(Request $request) 
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

        return redirect()->route('aseguramientoCalidad.auditoriaProcesoV2', 
            array_merge($data))->with('cambio-estatus', 'Iniciando en modulo: '. $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function auditoriaProcesoV2(Request $request)
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

        return view('aseguramientoCalidad.auditoriaProcesoV2', compact('mesesEnEspanol', 'pageSlug', 'data'));
    }

    public function obtenerListaProcesosV2()
    {
        $fechaActual = now()->toDateString();
        $auditorPlanta = Auth::user()->Planta;
        $auditorDato = Auth::user()->name;
        $tipoUsuario = Auth::user()->puesto;
        $datoPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

        $procesoActual = AseguramientoCalidad::whereNull('estatus')
            ->where('area', 'AUDITORIA EN PROCESO')
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
        $auditorPlanta = auth()->user()->planta ?? 'Planta1'; // Ajustar según sea necesario
        $detectarPlanta = ($auditorPlanta == "Planta1") ? "Intimark1" : "Intimark2";

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

    public function defectosProcesoV2(Request $request)
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

    public function crearDefectoProcesoV2(Request $request)
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

    public function formRegistroAuditoriaProcesoV2(Request $request)
    {
        try {
            // Convertir JSON recibido a un array asociativo
            $datosFormulario = $request->json()->all();
            Log::info("Datos recibidos en el controlador:", $datosFormulario);

            // Si no hay piezas rechazadas, limpiar 'ac' y 'tp'
            if ($datosFormulario['auditoria'][0]['cantidad_rechazada'] == 0) {
                $datosFormulario['auditoria'][0]['accion_correctiva'] = null;
                $datosFormulario['auditoria'][0]['tipo_problema'] = ['NINGUNO'];
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

            // Si `tipo_problema` no es un array, convertirlo en uno
            if (!is_array($tpSeleccionados)) {
                $tpSeleccionados = [$tpSeleccionados];
            }

            foreach ($tpSeleccionados as $valorTp) {
                $nuevoTp = new TpAseguramientoCalidad();
                $nuevoTp->aseguramiento_calidad_id = $nuevoRegistroId; // Relación con aseguramiento_calidad
                $nuevoTp->tp = $valorTp;
                $nuevoTp->save();
            }

            return response()->json(['message' => 'Datos guardados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error("Error al guardar los datos: " . $e->getMessage());
            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerRegistrosTurnoNormalV2(Request $request)
    {
        try {
            // Obtener fecha actual
            $fechaActual = now()->toDateString();
            
            // Filtrar por módulo recibido en el request
            $modulo = $request->input('modulo');
    
            // Consulta optimizada para obtener registros del día actual
            $mostrarRegistro = AseguramientoCalidad::whereDate('created_at', $fechaActual)
                ->where('modulo', $modulo)
                ->get();
    
            // Retornar datos en formato JSON
            return response()->json(['registros' => $mostrarRegistro], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener registros: ' . $e->getMessage()], 500);
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

}
