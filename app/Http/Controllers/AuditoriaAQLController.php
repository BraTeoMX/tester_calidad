<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\JobAQL;
use App\Models\JobAQLTemporal;
use App\Models\AuditoriaProceso;
use App\Models\AseguramientoCalidad;
use App\Models\CategoriaTeamLeader;
use App\Models\CategoriaTipoProblema;
use App\Models\CategoriaAccionCorrectiva;
use App\Models\AuditoriaAQL;
use App\Models\CategoriaUtility;
use App\Models\TpAuditoriaAQL;
use App\Models\CategoriaSupervisor; 
use App\Models\ModuloEstilo;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use App\Models\ModuloEstiloTemporal;


class AuditoriaAQLController extends Controller
{

    // Método privado para cargar las categorías
    private function cargarCategorias() {
        $fechaActual = Carbon::now()->toDateString();
        return [
            'auditorDato' => Auth::user()->name,
            'auditorPlanta' => Auth::user()->Planta,
            'AuditoriaProceso' => AuditoriaProceso::all(),
            'categoriaTPProceso' => CategoriaTipoProblema::whereIn('area', ['proceso', 'playera'])->get(),
            'categoriaTPPlayera' => CategoriaTipoProblema::where('area', 'playera')->get(),
            'categoriaTPEmpaque' => CategoriaTipoProblema::where('area', 'empaque')->get(),
            'categoriaACProceso' => CategoriaAccionCorrectiva::where('area', 'proceso')->get(),
            'categoriaACPlayera' => CategoriaAccionCorrectiva::where('area', 'playera')->get(),
            'categoriaACEmpaque' => CategoriaAccionCorrectiva::where('area', 'empaque')->get(),
            'teamLeaderPlanta1' => CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
                ->orderBy('jefe_produccion')
                ->where('planta', 'Intimark1')
                ->where('estatus', 1)
                ->get(),
            'teamLeaderPlanta2' => CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
                ->orderBy('jefe_produccion')
                ->where('planta', 'Intimark2')
                ->where('estatus', 1)
                ->get(),
            'auditoriaProcesoIntimark1' =>  JobAQL::whereBetween('moduleid', ['100A', '199A'])
                ->select('moduleid')
                ->distinct()
                ->orderBy('moduleid', 'asc')
                ->get(),
            'auditoriaProcesoIntimark2' => JobAQL::whereBetween('moduleid', ['200A', '299A'])
                ->select('moduleid')
                ->distinct()
                ->orderBy('moduleid', 'asc')
                ->get(),
            'playeraActualAQL' => AuditoriaAQL::where('estatus', NULL)
                ->where('area', 'AUDITORIA AQL PLAYERA')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente')
                ->distinct()
                ->get(),
            'playeraFinalAQL' => AuditoriaAQL::where('estatus', 1)
                ->where('area', 'AUDITORIA AQL PLAYERA')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente')
                ->distinct()
                ->get(),
            'ordenOPs' => JobAQL::select('prodid')
                ->distinct()
                ->get(),

        ];
    }

    public function metodoNombre(Request $request)
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

    public function altaAQL(Request $request) 
    {
        $pageSlug ='';
        $categorias = $this->cargarCategorias();
        $tipoUsuario = Auth::user()->puesto;
        //dd($tipoUsuario);


        //dd($registroEvaluacionCorte->all());
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $fechaActual = Carbon::now()->toDateString();
        $auditorPlanta = Auth::user()->Planta;
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
            ->where('area', 'AUDITORIA AQL')
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('area', 'modulo', 'op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');

        // Aplicar el filtro del auditor solo si el tipo de usuario no es "Administrador" o "Gerente de Calidad"
        if (!in_array($tipoUsuario, ['Administrador', 'Gerente de Calidad'])) {
            $procesoActualAQL->where('auditor', $categorias['auditorDato']);
        }

        // Ejecutar la consulta
        $procesoActualAQL = $procesoActualAQL->get();

        $procesoFinalAQL = AuditoriaAQL::where('estatus', 1)
            ->where('area', 'AUDITORIA AQL')
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('area','modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->get();
        $gerenteProduccion = CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
            ->orderBy('jefe_produccion')
            ->where('planta', $datoPlanta)
            ->where('estatus', 1)
            ->where('jefe_produccion', 1)
            ->get();
        return view('auditoriaAQL.altaAQL', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'listaModulos' => $listaModulos,
            'procesoActualAQL' => $procesoActualAQL,
            'procesoFinalAQL' => $procesoFinalAQL,
            'gerenteProduccion' => $gerenteProduccion]));
    }

    public function obtenerItemId(Request $request)
    {
        $moduleid = $request->input('moduleid');
        $auditoriaProceso = AuditoriaProceso::where('moduleid', $moduleid)->first();
        $itemid = $auditoriaProceso ? $auditoriaProceso->itemid : '';

        return response()->json(['itemid' => $itemid]);
    }

    public function auditoriaAQL(Request $request)
    {

        $pageSlug ='';
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $categorias = $this->cargarCategorias();
        $detectarPlanta = Auth::user()->Planta; 
        if($detectarPlanta == 'Planta1'){
            $detectarPlanta = "Intimark1";
        }elseif($detectarPlanta == 'Planta2'){
            $detectarPlanta = "Intimark2";
        }

        //dd($detectarPlanta);
        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        //dd($request->all(), $data);

        $datoBultos = JobAQL::whereIn('prodid', (array) $data['op'])
            ->where('moduleid', $data['modulo'])
            ->select('prodpackticketid', 'qty', 'itemid', 'colorname', 'inventsizeid')
            ->distinct()
            ->get();
        //$datoUnicoOP = JobAQL::where('prodid', $data['op'])
        //    ->first();
        //$nombreCliente = $datoUnicoOP ? $datoUnicoOP->customername : ''; // En caso de no encontrar, deja vacío
        $nombreCliente = $data['cliente'];
        //dd($nombreCliente);

        $selectPivoteOP = JobAQL::select('prodid')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$data['modulo']])
            ->distinct();

        $selectPivoteOPTemporal = JobAQLTemporal::select('prodid')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$data['modulo']])
            ->distinct();

        // Combinar ambas consultas y asignar a $selectPivoteOP
        $selectPivoteOP = $selectPivoteOP
            ->union($selectPivoteOPTemporal) // Unir las consultas
            ->orderBy('prioridad') // Priorizar los relacionados con el módulo primero
            ->orderBy('prodid') // Ordenar el resto por prodid
            ->get();

        //dd($data['modulo'], $selectPivoteOP);

        $fechaActual = Carbon::now()->toDateString();

        $mostrarRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->get();
        $estatusFinalizar = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->where('estatus', 1)
            ->exists();

        $registros = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->selectRaw('COALESCE(SUM(cantidad_auditada), 0) as total_auditada, COALESCE(SUM(cantidad_rechazada), 0) as total_rechazada')
            ->first();
        $total_auditada = $registros->total_auditada ?? 0;
        $total_rechazada = $registros->total_rechazada ?? 0;
        $total_porcentaje = $total_auditada != 0 ? ($total_rechazada / $total_auditada) * 100 : 0;


        $registrosIndividual = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', null)
            ->selectRaw('SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada')
            ->get();

        //apartado para suma de piezas por cada bulto
        $registrosIndividualPieza = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('area', $data['area'])
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
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', null)
            ->count();
        //conteo de registros del dia respecto a los rechazos
        $conteoPiezaConRechazo = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', null)
            ->count('pieza');
        $porcentajeBulto = $conteoBultos != 0 ? ($conteoPiezaConRechazo / $conteoBultos) * 100: 0;
        // Calcula el porcentaje total
        $total_porcentajeIndividual = $total_auditadaIndividual != 0 ? ($total_rechazadaIndividual / $total_auditadaIndividual) * 100 : 0;

        //apartado para mostrar Tiempo Extra
        $registrosIndividualTE = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->selectRaw('SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada')
            ->get();

        //apartado para suma de piezas por cada bulto
        $registrosIndividualPiezaTE = AuditoriaAQL::whereDate('created_at', $fechaActual) 
            ->where('area', $data['area'])
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
        //dd($registros, $fechaActual);
         //conteo de registros del dia respecto a la cantidad de bultos, que es lo mismo a los bultos
        $conteoBultosTE = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('tiempo_extra', 1)
            ->count();
        //conteo de registros del dia respecto a los rechazos
        $conteoPiezaConRechazoTE = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', 1)
            ->count('pieza');
        $porcentajeBultoTE = $conteoBultosTE != 0 ? ($conteoPiezaConRechazoTE / $conteoBultosTE) * 100: 0;
        // Calcula el porcentaje total
        $total_porcentajeIndividualTE = $total_auditadaIndividualTE != 0 ? ($total_rechazadaIndividualTE / $total_auditadaIndividualTE) * 100 : 0;

        //dd($finParoModular);
        $registrosOriginales = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            //->where('op', $data['op'])
            ->where('team_leader', $data['team_leader'])
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
            ->where('area', $data['area'])
            ->where('modulo', $data['modulo'])
            //->where('op', $data['op'])
            ->where('team_leader', $data['team_leader'])
            ->where('cantidad_rechazada', '>', 0)
            ->where('tiempo_extra', null)
            ->count();

        //dd($conteoParos, $registrosOriginales, $registro2, $registro4, $evaluacionRegistro2, $evaluacionRegistro4, $finParoModular1, $finParoModular2);
        $customerName = JobAQL::where('prodid', $data['op'])
            ->pluck('customername')
            ->first();

        $utility = CategoriaUtility::where('estado', 1)
            ->where('planta', $detectarPlanta)
            ->select('nombre')
            ->get()
            ->toArray();
        //dd($utility, $detectarPlanta);
        $nombreProceso = AuditoriaProceso::where('moduleid', $data['modulo'])
            ->select('name')
            ->distinct()
            ->get()
            ->toArray();
        //dd($nombreProcesoToAQL, $data['modulo']);
        // Filtrar para omitir datos que comiencen con "1" o "2"
        $nombreProceso = array_filter($nombreProceso, function($item) {
            // Verifica si el valor de 'name' comienza con "1" o "2"
            return !in_array(substr($item['name'], 0, 1), ['1', '2']);
        });

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
        //dd($nombreProcesoToAQL, $utilityPlanta2, $utilityPlanta1, $nombreProcesoToAQLPlanta1, $nombreProcesoToAQLPlanta2);
        $procesoActualAQL =AuditoriaAQL::where('estatus', NULL)
            ->where('auditor', $categorias['auditorDato'])
            ->where('area', 'AUDITORIA AQL')
            ->where('planta', $detectarPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('area','modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc')
            ->get();
        return view('auditoriaAQL.auditoriaAQL', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'datoBultos' => $datoBultos,
            'nombreCliente' => $nombreCliente,
            'selectPivoteOP' => $selectPivoteOP,
            'data' => $data,
            'total_auditada' => $total_auditada,
            'total_rechazada' => $total_rechazada,
            'total_porcentaje' => $total_porcentaje,
            'registrosIndividual' => $registrosIndividual,
            'total_auditadaIndividual' => $total_auditadaIndividual,
            'total_rechazadaIndividual' => $total_rechazadaIndividual,
            'total_porcentajeIndividual' => $total_porcentajeIndividual,
            'estatusFinalizar' => $estatusFinalizar,
            'registrosIndividualPieza' => $registrosIndividualPieza,
            'conteoBultos' => $conteoBultos,
            'conteoPiezaConRechazo' => $conteoPiezaConRechazo,
            'porcentajeBulto' => $porcentajeBulto,
            'mostrarRegistro' => $mostrarRegistro,
            'conteoParos' => $conteoParos,
            'finParoModular1' => $finParoModular1,
            'finParoModular2' => $finParoModular2,
            'nombreProceso' => $nombreProceso,
            'utility' => $utility,
            'registrosIndividualTE' => $registrosIndividualTE,
            'registrosIndividualPiezaTE' => $registrosIndividualPiezaTE,
            'conteoBultosTE' => $conteoBultosTE,
            'conteoPiezaConRechazoTE' => $conteoPiezaConRechazoTE,
            'porcentajeBultoTE' => $porcentajeBultoTE,
            'nombrePorModulo' => $nombrePorModulo,
            'procesoActualAQL' => $procesoActualAQL
            ]));
    }

    public function getBultosByOp(Request $request)
    {
        $op = $request->input('op');

        // Consulta de JobAQL
        $queryJobAQL = JobAQL::where('prodid', $op)
            ->select('prodpackticketid', 'qty', 'itemid', 'colorname', 'inventsizeid')
            ->distinct();

        // Consulta de JobAQLTemporal
        $queryJobAQLTemporal = JobAQLTemporal::where('prodid', $op)
            ->select('prodpackticketid', 'qty', 'itemid', 'colorname', 'inventsizeid')
            ->distinct();

        // Combinar ambas consultas y ejecutar
        $datoBultos = $queryJobAQL
            ->union($queryJobAQLTemporal) // Unir los resultados
            ->get();

        // Retornar los datos en formato JSON
        return response()->json($datoBultos);
    }


    public function formAltaProcesoAQL(Request $request) 
    {
        $pageSlug ='';

        $datoUnicoOP = JobAQL::where('prodid', $request->op)->first();

        if (!$datoUnicoOP) {
            // Si no se encuentra en JobAQL, buscar en JobAQLTemporal
            $datoUnicoOP = JobAQLTemporal::where('prodid', $request->op)->first();
        }
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
        return redirect()->route('auditoriaAQL.auditoriaAQL', $data)->with('cambio-estatus', 'Iniciando en modulo: '. $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function formRegistroAuditoriaProcesoAQL(Request $request)
    {
        $pageSlug ='';

        $fechaHoraActual= now();

        // Verificar el día de la semana
        $diaSemana = $fechaHoraActual ->dayOfWeek;

        // Obtener el ID seleccionado desde el formulario
        $plantaBusqueda = CategoriaSupervisor::where('moduleid', $request->modulo)
            ->pluck('prodpoolid')
            ->first(); 
        //dd($plantaBusqueda);
        $jefeProduccionBusqueda = CategoriaTeamLeader::where('nombre', $request->team_leader)
            ->where('jefe_produccion', 1)
            ->first();

        $fechaActual = Carbon::now()->toDateString();

        $conteoParos = AuditoriaAQL::whereDate('created_at', $fechaActual)
            ->where('area', $request->area)
            ->where('modulo', $request->modulo)
            ->where('op', $request->op)
            ->where('team_leader', $request->team_leader)
            ->where('cantidad_rechazada', '>', 0)
            ->count();
        //
        $nombreFinal = $request->nombre;
        $nombreFinalValidado = null;
        $numeroEmpleado = null;
        if ($nombreFinal) {
            // Convertimos los nombres en un array, eliminando espacios adicionales
            $nombres = array_map('trim', explode(',', $nombreFinal));
            
            $nombresValidados = [];
            $numerosEmpleados = [];
            
            foreach ($nombres as $nombre) {
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
        $buscarCliente = JobAQL::where('prodid', $request->op)
            ->first(['customername']);
        $buscarClienteResultado = $buscarCliente->customername ?? $request->cliente;

        //dd($nombreFinalValidado, $numeroEmpleado, $request->all());
        $nuevoRegistro = new AuditoriaAQL();
        $nuevoRegistro->area = $request->area;
        $nuevoRegistro->numero_empleado = $numeroEmpleado;
        $nuevoRegistro->nombre = $nombreFinalValidado;
        $nuevoRegistro->modulo = $request->modulo;
        $nuevoRegistro->op = $request->op;
        $nuevoRegistro->cliente = $buscarClienteResultado;
        $nuevoRegistro->team_leader = $request->team_leader;
        $nuevoRegistro->gerente_produccion = $request->gerente_produccion;
        $nuevoRegistro->auditor = $request->auditor;
        $nuevoRegistro->turno = $request->turno;
        $nuevoRegistro->planta = $plantaBusqueda;

        $nuevoRegistro->bulto = $request->bulto;
        $nuevoRegistro->pieza = $request->pieza;
        $nuevoRegistro->estilo = $request->estilo;
        $nuevoRegistro->color = $request->color;
        $nuevoRegistro->talla = $request->talla;
        $nuevoRegistro->cantidad_auditada = $request->cantidad_auditada;
        $nuevoRegistro->cantidad_rechazada = $request->cantidad_rechazada;
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
        $nuevoRegistro->ac = $request->ac;
        $nuevoRegistro->save();

         // Obtener el ID del nuevo registro
        $nuevoRegistroId = $nuevoRegistro->id;

        // Almacenar los valores de tp en la tabla tp_auditoria_aql

        // Asegúrate de que $request->tp sea un arreglo y contenga "NINGUNO" si está vacío o es null
        $tp = $request->input('tp', ['NINGUNO']);

        // Itera sobre el arreglo $tp y guarda cada valor
        foreach ($tp as $valorTp) {
            $nuevoTp = new TpAuditoriaAQL();
            $nuevoTp->auditoria_aql_id = $nuevoRegistroId; // Asegúrate de que $nuevoRegistroId esté definido
            $nuevoTp->tp = $valorTp;
            $nuevoTp->save();
        }


        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }


    public function formUpdateDeleteProceso(Request $request){
        $pageSlug ='';
        $action = $request->input('action');

        $id = $request->input('id');
        //dd($request->all());
        if($action == 'update'){
            $actualizarRegistro = AuditoriaAQL::where('id', $id)->first();
            $actualizarRegistro->cantidad_auditada = $request->cantidad_auditada;
            $actualizarRegistro->cantidad_rechazada = $request->cantidad_rechazada;
            $actualizarRegistro->tp = $request->tp;
            $actualizarRegistro->save();

            //dd($request->all(), $actualizarRegistro, $id);
            return back()->with('sobre-escribir', 'Registro actualizado correctamente.')->with('pageSlug', $pageSlug);

            // Lógica para actualizar el registro
        } elseif ($action == 'delete'){
            // Lógica para eliminar el registro
            AuditoriaAQL::where('id', $id)->delete();
            return back()->with('error', 'Registro eliminado.')->with('pageSlug', $pageSlug);
        }

        //dd($request->all(), $request->input('descripcion_parte1'), $id);
        return back()->with('cambio-estatus', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formFinalizarProceso(Request $request)
    {
        $pageSlug ='';
        // Obtener el ID seleccionado desde el formulario
        $area = $request->input('area');
        $modulo = $request->input('modulo');
        $observacion = $request->input('observacion');
        $estatus=1;
        //dd($request->all(), $area);
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];
        $fechaActual = Carbon::now()->toDateString();

        // Actualizar todos los registros que cumplan con las condiciones
        AuditoriaAQL::whereDate('created_at', $fechaActual)
        ->where('modulo', $modulo)
        ->where('area', $area)
        ->update(['observacion' => $observacion, 'estatus' => $estatus]);


        return back()->with('success', 'Finalizacion aplicada correctamente.')->with('pageSlug', $pageSlug);
    }

    public function cambiarEstadoInicioParoAQL(Request $request)
    {
        $pageSlug ='';
        $id = $request->idCambio;
        $reparacionRechazo = $request->reparacion_rechazo;

        $registro = AuditoriaAQL::find($id);

        if($request->finalizar_paro_modular == 1){
            // Obtener la fecha actual
            $fechaActual = Carbon::now()->toDateString();

            // Obtener la hora actual
            $horaActual = Carbon::now()->toTimeString();

            //dd($request->all());

             // Obtener el segundo y cuarto registro
            $segundoRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
                ->where('modulo', $request->modulo)
                //->where('op', $request->op)
                //->where('team_leader', $request->team_leader)
                ->where('area', $request->area)
                ->where('cantidad_rechazada', '>', 0)
                ->orderBy('created_at', 'asc')
                ->skip(1) // Saltar el primer registro
                ->first();

            $cuartoRegistro = AuditoriaAQL::whereDate('created_at', $fechaActual)
                ->where('modulo', $request->modulo)
                //->where('op', $request->op)
                //->where('team_leader', $request->team_leader)
                ->where('area', $request->area)
                ->where('cantidad_rechazada', '>', 0)
                ->orderBy('created_at', 'asc')
                ->skip(3) // Saltar los primeros tres registros
                ->first();

            // Evaluar el segundo registro
            if ($segundoRegistro && is_null($segundoRegistro->minutos_paro_modular)) {
                // Actualizar la columna "fin_paro_modular" con la hora actual
                $segundoRegistro->fin_paro_modular = $horaActual;

                // Calcular la diferencia en minutos entre "inicio_paro" y "fin_paro_modular"
                $inicioParo = Carbon::parse($segundoRegistro->inicio_paro);
                $finParoModular = Carbon::parse($horaActual);
                $diferenciaEnMinutos = $inicioParo->diffInMinutes($finParoModular);

                // Actualizar la columna "minutos_paro_modular" con la diferencia en minutos
                $segundoRegistro->minutos_paro_modular = $diferenciaEnMinutos;

                // Guardar los cambios
                $segundoRegistro->save();
            }

            // Evaluar el cuarto registro si el segundo ya tiene "minutos_paro_modular"
            if ($segundoRegistro && !is_null($segundoRegistro->minutos_paro_modular) && $cuartoRegistro && is_null($cuartoRegistro->minutos_paro_modular)) {
                // Actualizar la columna "fin_paro_modular" con la hora actual
                $cuartoRegistro->fin_paro_modular = $horaActual;

                // Calcular la diferencia en minutos entre "inicio_paro" y "fin_paro_modular"
                $inicioParo = Carbon::parse($cuartoRegistro->inicio_paro);
                $finParoModular = Carbon::parse($horaActual);
                $diferenciaEnMinutos = $inicioParo->diffInMinutes($finParoModular);

                // Actualizar la columna "minutos_paro_modular" con la diferencia en minutos
                $cuartoRegistro->minutos_paro_modular = $diferenciaEnMinutos;

                // Guardar los cambios
                $cuartoRegistro->save();
            }

            //dd($request->all(), $registro);

        }else{
            $registro->fin_paro = Carbon::now();

            // Calcular la duración del paro en minutos
            $inicioParo = Carbon::parse($registro->inicio_paro);
            $finParo = Carbon::parse($registro->fin_paro);
            $minutosParo = $inicioParo->diffInMinutes($finParo);

            // Almacenar la duración en minutos
            $registro->minutos_paro = $minutosParo;
            $registro->reparacion_rechazo = $reparacionRechazo;

            $registro->save();
        }

        return back()->with('success', 'Fin de Paro Aplicado.')->with('pageSlug', $pageSlug);
    }
    //Ya no recuerdo
    public function storeCategoriaTipoProblemaAQL(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'area' => 'required|string|max:255',
        ]);

        $categoriaTipoProblema = new CategoriaTipoProblema();
        $categoriaTipoProblema->nombre = strtoupper($request->nombre);
        $categoriaTipoProblema->area = $request->area;
        $categoriaTipoProblema->estado = 1;
        $categoriaTipoProblema->save();

        return response()->json(['success' => true]);
    }

}
