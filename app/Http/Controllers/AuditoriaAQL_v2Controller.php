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

class AuditoriaAQL_v2Controller extends Controller
{

    public function altaAQL_v2(Request $request) 
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
            $datoPlanta = "Intimark1";
        }else{
            $datoPlanta = "Intimark2";
        }

        $datosCategoriaSupervisor = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
            ->whereBetween('moduleid', ['100A', '299A'])
            ->get(['moduleid']); // Obtener solo la columna necesaria

        // Obtener datos del segundo modelo, asegurando valores únicos
        $datosModuloEstiloTemporal = ModuloEstiloTemporal::where('prodpoolid', $datoPlanta)
            ->whereBetween('moduleid', ['100A', '299A'])
            ->distinct('moduleid') // Asegurarte de que sean únicos
            ->get(['moduleid']); // Obtener solo la columna necesaria

        // Combinar ambos resultados y eliminar duplicados
        $listaModulos = $datosCategoriaSupervisor->concat($datosModuloEstiloTemporal)
            ->unique('moduleid') // Asegurar que no haya duplicados en la columna `moduleid`
            ->sortBy('moduleid') // Ordenar los resultados (opcional)
            ->values(); // Resetear los índices
        //dd($listaModulos);

        $procesoActualAQL = AuditoriaAQL::where('estatus', NULL)
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo', 'op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');

        // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoActualAQL->where('auditor', $auditorDato);
        }

        // Ejecutar la consulta
        $procesoActualAQL = $procesoActualAQL->get();

        $procesoFinalAQL = AuditoriaAQL::where('estatus', 1)
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->get();
        $gerenteProduccion = CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
            ->orderBy('jefe_produccion')
            ->where('planta', $datoPlanta)
            ->where('estatus', 1)
            ->where('jefe_produccion', 1)
            ->get();

        return view('auditoriaAQL.altaAQL_v2', compact('mesesEnEspanol', 'pageSlug', 'auditorDato',
                'listaModulos', 'procesoActualAQL', 'procesoFinalAQL', 'gerenteProduccion'));
    }

    public function formAltaProcesoAQL_v2(Request $request) 
    {
        $pageSlug ='';

        $datoUnicoOP = JobAQL::where('prodid', $request->op)
            ->first();
        //dd($datoUnicoOP);
        $data = [
            'modulo' => $request->modulo,
            'estilo' => $request->estilo,
            'op' => $request->op,
            'cliente' => $datoUnicoOP->customername,
            'auditor' => $request->auditor,
            'turno' => $request->turno,
            'team_leader' => $request->team_leader,
            'gerente_produccion' => $request->gerente_produccion,
        ];
        //dd($data);
        return redirect()->route('auditoriaAQL.auditoriaAQL_v2', $data)->with('cambio-estatus', 'Iniciando en modulo: '. $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function auditoriaAQL_v2(Request $request)
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
        

        return view('auditoriaAQL.auditoriaAQL_v2', compact('mesesEnEspanol', 'pageSlug',
            'data', 'resultadoFinal'));
    }

    public function obtenerAQLenProceso(Request $request)
    {
        // Datos de entrada
        $fechaActual = now()->toDateString(); 
        $tipoUsuario = Auth::user()->puesto; 
        $auditorDato = Auth::user()->name; 

        // Construimos la consulta
        $procesoActualAQL = AuditoriaAQL::whereNull('estatus')
            ->whereDate('created_at', $fechaActual)
            ->select('modulo', 'op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');

        // Aplicar filtro si no es Administrador o Gerente de Calidad
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoActualAQL->where('auditor', $auditorDato);
        }

        // Ejecutar la consulta
        $procesos = $procesoActualAQL->get();

        // Retornar la respuesta
        return response()->json($procesos);
    }

    public function obtenerOpcionesOP(Request $request)
    {
        $query = $request->input('search', '');

        $datosOP = JobAQL::select('prodid')
            ->where('prodid', 'like', "%{$query}%")
            ->union(
                JobAQLTemporal::select('prodid')
                    ->where('prodid', 'like', "%{$query}%")
            )
            ->distinct()
            ->orderBy('prodid')
            ->get();

        return response()->json($datosOP);
    }


    public function obtenerOpcionesBulto(Request $request)
    {
        $opSeleccionada = $request->input('op');
        $search = $request->input('search', '');

        // Si no se proporciona la OP, devuelve vacío
        if (!$opSeleccionada) {
            return response()->json([]);
        }

        // Construye la consulta base
        $query = JobAQL::where('prodid', $opSeleccionada)
            ->select('prodid', 'prodpackticketid', 'qty', 'itemid', 'colorname', 'customername', 'inventcolorid', 'inventsizeid')
            ->union(
                JobAQLTemporal::where('prodid', $opSeleccionada)
                    ->select('prodid', 'prodpackticketid', 'qty', 'itemid', 'colorname', 'customername', 'inventcolorid', 'inventsizeid')
            )
            ->distinct();

        // Aplica filtro de búsqueda si existe un término
        if ($search !== '') {
            // Ajusta el campo de búsqueda si es necesario. 
            // Aquí asumo que se filtra por 'prodpackticketid'.
            $query = $query->where('prodpackticketid', 'like', "%{$search}%");
        }

        $datosBulto = $query->orderBy('prodpackticketid')->get();

        // Si no se encuentran resultados, devolver arreglo vacío
        if ($datosBulto->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($datosBulto);
    }

    public function obtenerDefectosAQL(Request $request)
    {
        $search = $request->input('search', '');

        // Construye la consulta base
        $query = CategoriaTipoProblema::whereIn('area', ['proceso', 'playera', 'aql']);

        // Aplica filtro de búsqueda si existe un término
        if ($search !== '') {
            $query = $query->where('nombre', 'like', "%{$search}%");
        }

        $categorias = $query->get();

        // Si no se encuentran resultados, devolver arreglo vacío
        if ($categorias->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($categorias);
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
                ->where('area', 'aql') // Opcional: para buscar solo en el área "aql"
                ->first();

            if ($defectoExistente) {
                // Si ya existe, devolver el registro existente
                return response()->json($defectoExistente);
            }

            // Crear un nuevo defecto si no existe
            $nuevoDefecto = CategoriaTipoProblema::create([
                'nombre' => $nombre,
                'area' => 'aql',
            ]);

            return response()->json($nuevoDefecto);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function obtenerNombresProceso(Request $request)
    {
        try {
            // Obtén el módulo desde la solicitud
            $modulo = $request->input('modulo');

            // Consulta base para los registros asociados al módulo
            $registrosAsociados = AuditoriaProceso::query()
                ->where('moduleid', $modulo)
                ->select('name')
                ->distinct()
                ->get();

            // Consulta para los registros generales, excluyendo los asociados
            $registrosGenerales = AuditoriaProceso::query()
                ->select('name')
                ->distinct()
                ->when($registrosAsociados->isNotEmpty(), function ($query) use ($registrosAsociados) {
                    return $query->whereNotIn('name', $registrosAsociados->pluck('name'));
                })
                ->get();


            // Combina los resultados de forma manual
            $nombres = array_merge(
                $registrosAsociados->toArray(),
                $registrosGenerales->toArray()
            );


            // Devuelve los nombres en formato JSON
            return response()->json($nombres);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
                ->where('area', $request->area)
                ->where('modulo', $request->modulo)
                ->where('op', $request->op)
                ->where('team_leader', $request->team_leader)
                ->where('cantidad_rechazada', '>', 0)
                ->count();
            
            // Obtener el ID seleccionado desde el formulario
            $plantaBusqueda = CategoriaSupervisor::where('moduleid', $request->modulo)
            ->pluck('prodpoolid')
            ->first(); 
            //dd($plantaBusqueda);

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
    
    public function mostrarRegistrosAqlDia(Request $request)
    {
        $fechaActual = Carbon::now()->toDateString(); // Recibir la fecha actual desde la petición AJAX
        $modulo = $request->input('modulo'); // Recibir el módulo desde la petición AJAX

        // Cargar registros junto con los datos relacionados
        $mostrarRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->whereNull('tiempo_extra') 
            ->with('tpAuditoriaAQL') // Cargar relación
            ->get();

        return response()->json($mostrarRegistro);
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

    public function mostrarRegistrosAqlDiaTE(Request $request)
    {
        $fechaActual = Carbon::now()->toDateString(); // Recibir la fecha actual desde la petición AJAX
        $modulo = $request->input('modulo'); // Recibir el módulo desde la petición AJAX

        // Cargar registros junto con los datos relacionados
        $mostrarRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->where('tiempo_extra', 1) 
            ->with('tpAuditoriaAQL') // Cargar relación
            ->get();

        return response()->json($mostrarRegistro);
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

    public function formFinalizarProceso_v2(Request $request)
    {
        $modulo = $request->input('modulo');
        $observacion = $request->input('observacion');
        $estatus = 1;
        $fechaActual = \Carbon\Carbon::now()->toDateString();

        // Verificar si hay registros con 'inicio_paro' NO nulo y 'fin_paro' en NULL
        $parosPendientes = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')  // Tiene un inicio de paro registrado
            ->whereNull('fin_paro')        // Pero no ha finalizado el paro
            ->exists();

        if ($parosPendientes) {
            return response()->json([
                'success' => false,
                'message' => 'Tiene paros pendientes, finalicelos e intente de nuevo.'
            ]);
        }

        // Si no hay paros pendientes, proceder con la actualización
        AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->where('tiempo_extra', null)
            ->update([
                'observacion' => $observacion,
                'estatus' => $estatus
            ]);

        return response()->json([
            'success'     => true,
            'observacion' => $observacion,
            'message'     => 'Finalización aplicada correctamente.'
        ]);
    }


    public function verificarFinalizacion(Request $request)
    {
        $modulo = $request->input('modulo'); 
        $fechaActual = Carbon::now()->toDateString();

        // Buscar si ya se finalizó el módulo para la fecha actual
        $registro = AuditoriaAQL::whereDate('created_at', $fechaActual)
                    ->where('modulo', $modulo)
                    ->where('tiempo_extra', null)
                    ->where('estatus', 1)
                    ->first();

        return response()->json([
            'finalizado'  => $registro ? true : false,
            'observacion' => $registro ? $registro->observacion : '',
        ]);
    }

    public function formFinalizarProceso_v2TE(Request $request)
    {
        $modulo = $request->input('modulo');
        $observacion = $request->input('observacion');
        $estatus = 1;
        $fechaActual = \Carbon\Carbon::now()->toDateString();

        // Verificar si hay registros con 'inicio_paro' NO nulo y 'fin_paro' en NULL
        $parosPendientes = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->whereNotNull('inicio_paro')
            ->whereNull('fin_paro')
            ->where('tiempo_extra', 1) // Filtra solo tiempo extra
            ->exists();

        if ($parosPendientes) {
            return response()->json([
                'success' => false,
                'message' => 'Tiene paros pendientes en tiempo extra, finalícelos e intente de nuevo.'
            ]);
        }

        // Si no hay paros pendientes, proceder con la actualización
        AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->where('tiempo_extra', 1) // Filtra solo tiempo extra
            ->update([
                'observacion' => $observacion,
                'estatus' => $estatus
            ]);

        return response()->json([
            'success'     => true,
            'observacion' => $observacion,
            'message'     => 'Finalización de tiempo extra aplicada correctamente.'
        ]);
    }

    public function verificarFinalizacionTE(Request $request)
    {
        $modulo = $request->input('modulo'); 
        $fechaActual = Carbon::now()->toDateString();

        // Buscar si ya se finalizó el módulo para la fecha actual en tiempo extra
        $registro = AuditoriaAQL::whereDate('created_at', $fechaActual)
                    ->where('modulo', $modulo)
                    ->where('tiempo_extra', 1) // Filtra solo tiempo extra
                    ->where('estatus', 1)
                    ->first();

        return response()->json([
            'finalizado'  => $registro ? true : false,
            'observacion' => $registro ? $registro->observacion : '',
        ]);
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
