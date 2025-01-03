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

        $listaModulos = CategoriaSupervisor::where('prodpoolid', $datoPlanta)
            ->whereBetween('moduleid', ['100A', '299A'])
            ->get();
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
            'area' => $request->area,
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

        $fechaActual = Carbon::now()->toDateString();

        $mostrarRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->get();
        $estatusFinalizar = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('estatus', 1)
            ->exists();

        $registros = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->selectRaw('COALESCE(SUM(cantidad_auditada), 0) as total_auditada, COALESCE(SUM(cantidad_rechazada), 0) as total_rechazada')
            ->first();
        $total_auditada = $registros->total_auditada ?? 0;
        $total_rechazada = $registros->total_rechazada ?? 0;
        $total_porcentaje = $total_auditada != 0 ? ($total_rechazada / $total_auditada) * 100 : 0;


        $registrosIndividual = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', null)
            ->selectRaw('SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada')
            ->get();

        //apartado para suma de piezas por cada bulto
        $registrosIndividualPieza = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', null)
            ->selectRaw('SUM(pieza) as total_pieza, SUM(cantidad_rechazada) as total_rechazada')
            ->get();
        // Inicializa las variables para evitar errores
        $total_auditadaIndividual = 0;
        $total_rechazadaIndividual = 0;

        // Calcula la suma total solo si hay registros individuales
        if ($registrosIndividual->isNotEmpty()) {
            $total_auditadaIndividual = $registrosIndividual->sum('total_auditada');
            $total_rechazadaIndividual = $registrosIndividual->sum('total_rechazada');
        }
        //dd($registros, $fechaActual);
         //conteo de registros del dia respecto a la cantidad de bultos, que es lo mismo a los bultos
        $conteoBultos = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', null)
            ->count();
        //conteo de registros del dia respecto a los rechazos
        $conteoPiezaConRechazo = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', null)
            ->count('pieza');
        $porcentajeBulto = $conteoBultos != 0 ? ($conteoPiezaConRechazo / $conteoBultos) * 100: 0;
        // Calcula el porcentaje total
        $total_porcentajeIndividual = $total_auditadaIndividual != 0 ? ($total_rechazadaIndividual / $total_auditadaIndividual) * 100 : 0;

        //apartado para mostrar Tiempo Extra
        $registrosIndividualTE = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->selectRaw('SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada')
            ->get();

        //apartado para suma de piezas por cada bulto
        $registrosIndividualPiezaTE = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->selectRaw('SUM(pieza) as total_pieza, SUM(cantidad_rechazada) as total_rechazada')
            ->get();
        // Inicializa las variables para evitar errores
        $total_auditadaIndividualTE = 0;
        $total_rechazadaIndividualTE = 0;

        // Calcula la suma total solo si hay registros individuales
        if ($registrosIndividualTE->isNotEmpty()) {
            $total_auditadaIndividualTE = $registrosIndividualTE->sum('total_auditada');
            $total_rechazadaIndividualTE = $registrosIndividualTE->sum('total_rechazada');
        }
         //conteo de registros del dia respecto a la cantidad de bultos, que es lo mismo a los bultos
        $conteoBultosTE = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->count();
        //conteo de registros del dia respecto a los rechazos
        $conteoPiezaConRechazoTE = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', 1)
            ->count('pieza');
        $porcentajeBultoTE = $conteoBultosTE != 0 ? ($conteoPiezaConRechazoTE / $conteoBultosTE) * 100: 0;
        // Calcula el porcentaje total
        $total_porcentajeIndividualTE = $total_auditadaIndividualTE != 0 ? ($total_rechazadaIndividualTE / $total_auditadaIndividualTE) * 100 : 0;

        $registrosOriginales = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->orderBy('created_at', 'asc') // Ordenar por created_at ascendente
            ->get();

        // Aplicar filtro adicional para registros 2 y 4
        $registro2 = $registrosOriginales->get(1); // Obtener el segundo registro
        $registro4 = $registrosOriginales->get(3); // Obtener el cuarto registro

        // Verificar si los registros 2 y 4 cumplen con el criterio adicional
        $evaluacionRegistro2 = $registro2 && is_null($registro2->fin_paro_modular); // Usar is_null() o el operador ??
        $evaluacionRegistro4 = $registro4 && is_null($registro4->fin_paro_modular); // Usar is_null() o el operador ??

        // Almacenar los resultados en variables
        $finParoModular1 = $evaluacionRegistro2;
        $finParoModular2 = $evaluacionRegistro4;


        $conteoParos = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', null)
            ->count();

        //dd($conteoParos, $registrosOriginales, $registro2, $registro4, $evaluacionRegistro2, $evaluacionRegistro4, $finParoModular1, $finParoModular2);
        $customerName = JobAQL::where('prodid', $data['op'])
            ->pluck('customername')
            ->first();

        // Nueva consulta para obtener los nombres únicos agrupados por módulo
        $nombrePorModulo = AuditoriaProceso::select('moduleid', 'name')
            ->where('prodpoolid', $detectarPlanta)
            ->distinct()
            ->orderBy('moduleid')
            ->get()
            ->filter(function($item) {
                // Verifica si el valor de 'name' comienza con "1" o "2"
                return !in_array(substr($item->name, 0, 1), ['1', '2']);
            })
            ->groupBy('moduleid')
            ->toArray();
        
        $procesoActualAQL =AuditoriaAQL::where('estatus', NULL)
            ->where('auditor', $auditorDato)
            ->where('planta', $detectarPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc')
            ->get();

        return view('auditoriaAQL.auditoriaAQL_v2', compact('mesesEnEspanol', 'pageSlug',
            'data', 'total_auditada','total_rechazada','total_porcentaje','registrosIndividual','total_auditadaIndividual',
            'total_rechazadaIndividual', 'total_porcentajeIndividual','estatusFinalizar','registrosIndividualPieza', 'conteoBultos',
            'conteoPiezaConRechazo','porcentajeBulto','mostrarRegistro', 'conteoParos', 'finParoModular1','finParoModular2',
            'registrosIndividualTE','registrosIndividualPiezaTE','conteoBultosTE','conteoPiezaConRechazoTE','porcentajeBultoTE',
            'nombrePorModulo','procesoActualAQL'));
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
            Log::info('Aqui va bien, donde rompe?:');
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
            Log::info('Antes del if');
            if ($nombreFinal && is_array($nombreFinal)) {
                Log::info('Inicia procesamiento de nombres:', $nombreFinal);
                $nombresValidados = [];
                $numerosEmpleados = [];
    
                foreach ($nombreFinal as $nombre) {
                    Log::info('Procesando nombre individual:', ['nombre' => $nombre]);
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
            Log::info('despues del if y comeinza el new de nuevos registros');
    
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
            Log::info('antes del if del registro');
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
            Log::info('fin del new registro');
            // Registrar confirmación de éxito
            //Log::info('Registro guardado correctamente:', $validatedData);

            return response()->json(['message' => 'Datos guardados correctamente.'], 200);
        } catch (\Exception $e) {

            return response()->json(['error' => 'Error al guardar los datos: ' . $e->getMessage()], 500);
        }
    }
    
    public function mostrarRegistrosAqlDia(Request $request)
    {
        $fechaActual = $request->input('fechaActual'); // Recibir la fecha actual desde la petición AJAX
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

        // Encuentra el registro principal
        $registro = AuditoriaAQL::find($id);

        if ($registro) {
            // Eliminar los registros relacionados
            $registro->tpAuditoriaAQL()->delete();

            // Eliminar el registro principal
            $registro->delete();

            return response()->json(['success' => true, 'message' => 'Registro eliminado exitosamente.']);
        }

        return response()->json(['success' => false, 'message' => 'Registro no encontrado.'], 404);
    }


    public function mostrarRegistrosAqlDiaTE(Request $request)
    {
        $fechaActual = $request->input('fechaActual'); // Recibir la fecha actual desde la petición AJAX
        $modulo = $request->input('modulo'); // Recibir el módulo desde la petición AJAX

        // Cargar registros junto con los datos relacionados
        $mostrarRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $modulo)
            ->where('tiempo_extra', 1) 
            ->with('tpAuditoriaAQL') // Cargar relación
            ->get();

        return response()->json($mostrarRegistro);
    }

}
