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
        $categoriaTPProceso = CategoriaTipoProblema::whereIn('area', ['proceso', 'playera'])->get();
        
        if($auditorPlanta == 'Planta1'){
            $detectarPlanta = "Intimark1";
        }elseif($auditorPlanta == 'Planta2'){
            $detectarPlanta = "Intimark2";
        }

        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        $datoBultos = JobAQL::whereIn('prodid', (array) $data['op'])
            ->where('moduleid', $data['modulo'])
            ->select('prodpackticketid', 'qty', 'itemid', 'colorname', 'inventsizeid')
            ->distinct()
            ->get();

        $nombreCliente = $data['cliente'];
        //dd($nombreCliente);

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
        
        $procesoActualAQL =AuditoriaAQL::where('estatus', NULL)
            ->where('auditor', $auditorDato)
            ->where('planta', $detectarPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','op', 'team_leader', 'turno', 'auditor', 'estilo', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc')
            ->get();

        return view('auditoriaAQL.auditoriaAQL_v2', compact('mesesEnEspanol', 'pageSlug', 'datoBultos', 'nombreCliente', 'categoriaTPProceso',
            'data', 'total_auditada','total_rechazada','total_porcentaje','registrosIndividual','total_auditadaIndividual',
            'total_rechazadaIndividual', 'total_porcentajeIndividual','estatusFinalizar','registrosIndividualPieza', 'conteoBultos',
            'conteoPiezaConRechazo','porcentajeBulto','mostrarRegistro', 'conteoParos', 'finParoModular1','finParoModular2','nombreProceso',
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
            ->select('prodid', 'prodpackticketid')
            ->union(
                JobAQLTemporal::where('prodid', $opSeleccionada)
                    ->select('prodid', 'prodpackticketid')
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



}
