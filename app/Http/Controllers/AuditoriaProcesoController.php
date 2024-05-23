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
use App\Models\TpAseguramientoCalidad; 
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificacionParo;

use App\Models\EvaluacionCorte;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon

class AuditoriaProcesoController extends Controller
{

    // Método privado para cargar las categorías
    private function cargarCategorias() {
        $fechaActual = Carbon::now()->toDateString();
        return [
            'fechaActual' => $fechaActual,
            'auditorDato' => Auth::user()->name,
            'auditorPlanta' => Auth::user()->Planta,
            'AuditoriaProceso' => AuditoriaProceso::all(),
            'categoriaTPProceso' => CategoriaTipoProblema::where('area', 'proceso')->get(),
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
            'auditoriaProcesoIntimark1' =>  AuditoriaProceso::where('prodpoolid', 'Intimark1')
                ->select('moduleid', 'itemid')
                ->distinct()
                ->get(),
            'auditoriaProcesoIntimark2' => AuditoriaProceso::where('prodpoolid', 'Intimark2')
                ->select('moduleid', 'itemid')
                ->distinct()
                ->get(), 
            'procesoActual' => AseguramientoCalidad::where('estatus', NULL)
                ->where('area', 'AUDITORIA EN PROCESO')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),
            'procesoFinal' => AseguramientoCalidad::where('estatus', 1)
                ->where('area', 'AUDITORIA EN PROCESO')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),
            'playeraActual' => AseguramientoCalidad::where('estatus', NULL)
                ->where('area', 'AUDITORIA EN PROCESO PLAYERA')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),
            'playeraFinal' => AseguramientoCalidad::where('estatus', 1)
                ->where('area', 'AUDITORIA EN PROCESO PLAYERA')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),
            'empaqueActual' => AseguramientoCalidad::where('estatus', NULL)
                ->where('area', 'AUDITORIA EN EMPAQUE')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),
            'empaqueFinal' => AseguramientoCalidad::where('estatus', 1)
                ->where('area', 'AUDITORIA EN EMPAQUE')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente')
                ->distinct()
                ->get(),

        ];
    }



    public function altaProceso(Request $request)
    {
        $pageSlug ='';
        $categorias = $this->cargarCategorias();


        //dd($registroEvaluacionCorte->all()); 
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        
        return view('aseguramientoCalidad.altaProceso', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol, 
            'pageSlug' => $pageSlug]));
    }

    public function obtenerItemId(Request $request) 
    {
        $moduleid = $request->input('moduleid');
        $auditoriaProceso = AuditoriaProceso::where('moduleid', $moduleid)->get();
        $itemid = $auditoriaProceso->pluck('itemid', 'id')->unique()->toArray();
        
        return response()->json([
            'itemid' => $itemid,
            'selectedItemid' => $itemid[0] ?? null // Asigna el primer itemid como selectedItemid, o null si no hay elementos
        ]);
    } 

    public function obtenerCliente1(Request $request)
{
    $moduleid = $request->input('moduleid');
    $auditoriaProceso = AuditoriaProceso::where('moduleid', $moduleid)->first();

    return response()->json([
        'cliente' => $auditoriaProceso->customername ?? ''
    ]);
}



    public function auditoriaProceso(Request $request)
    {
        
        $pageSlug ='';
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        $categorias = $this->cargarCategorias();
        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        //dd($request->all(), $data); 
        $nombresPlanta1 = AuditoriaProceso::where('prodpoolid', 'Intimark1') 
            ->where('moduleid', $data['modulo'])
            ->select('name')
            ->distinct()
            ->get();

        $nombresPlanta2 = AuditoriaProceso::where('prodpoolid', 'Intimark2')
            ->where('moduleid', $data['modulo'])
            ->select('name')
            ->distinct()
            ->get();


        $utilityPlanta1 = CategoriaUtility::where('planta', 'Intimark1') 
            ->where('estado', 1)
            ->get();

        $utilityPlanta2 = CategoriaUtility::where('planta', 'Intimark2')
            ->where('estado', 1)    
            ->get();
        //dd($utilityPlanta1->all(), $utilityPlanta2);
        $fechaActual = Carbon::now()->toDateString();

        $mostrarRegistro = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->get();
        $estatusFinalizar = AseguramientoCalidad::whereDate('created_at', $fechaActual)
        ->where('modulo', $data['modulo'])
        ->where('area', $data['area'])
        ->where('estatus', 1)
        ->exists();

        $registros = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->selectRaw('COALESCE(SUM(cantidad_auditada), 0) as total_auditada, COALESCE(SUM(cantidad_rechazada), 0) as total_rechazada')
            ->first();
        $total_auditada = $registros->total_auditada ?? 0;
        $total_rechazada = $registros->total_rechazada ?? 0;
        $total_porcentaje = $total_auditada != 0 ? ($total_rechazada / $total_auditada) * 100 : 0;


        $registrosIndividual = AseguramientoCalidad::whereDate('created_at', $fechaActual)
            ->where('modulo', $data['modulo'])
            ->where('area', $data['area'])
            ->selectRaw('nombre, COUNT(*) as cantidad_registros, SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada') 
            ->groupBy('nombre')
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
        // Calcula el porcentaje total
        
        $total_porcentajeIndividual = $total_auditadaIndividual != 0 ? ($total_rechazadaIndividual / $total_auditadaIndividual) * 100 : 0;
        
        

        
        return view('aseguramientoCalidad.auditoriaProceso', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol, 
            'pageSlug' => $pageSlug,
            'data' => $data, 
            'nombresPlanta1' => $nombresPlanta1, 
            'nombresPlanta2' => $nombresPlanta2, 
            'utilityPlanta1' => $utilityPlanta1, 
            'utilityPlanta2' => $utilityPlanta2, 
            'total_auditada' => $total_auditada, 
            'total_rechazada' => $total_rechazada,
            'total_porcentaje' => $total_porcentaje,
            'registrosIndividual' => $registrosIndividual,
            'total_auditadaIndividual' => $total_auditadaIndividual, 
            'total_rechazadaIndividual' => $total_rechazadaIndividual,
            'total_porcentajeIndividual' => $total_porcentajeIndividual,
            'estatusFinalizar' => $estatusFinalizar,
            'mostrarRegistro' => $mostrarRegistro]));
    }



    public function getModules()
    {
        $auditorPlanta = Auth::user()->Planta;
    $modules = AuditoriaProceso::select('moduleid')
        ->distinct();

    if ($auditorPlanta == 'Planta1') {
        $modules->where('prodpoolid', 'Intimark1');
    } elseif ($auditorPlanta == 'Planta2') {
        $modules->where('prodpoolid', 'Intimark2');
    }

    $modules = $modules->get();


        return response()->json($modules);
    }

    public function getNamesByModule(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        $moduleName = $request->input('moduleid');
        $names = AuditoriaProceso::where('moduleid', $moduleName);

        if ($auditorPlanta == 'Planta1') {
            $names->where('prodpoolid', 'Intimark1');
        } elseif ($auditorPlanta == 'Planta2') {
            $names->where('prodpoolid', 'Intimark2');
        }

        $names = $names->select('name')
            ->distinct()
            ->get();

        return response()->json($names);
    }

    public function getUtilities()
    {
        $auditorPlanta = Auth::user()->Planta;
        $utilities = CategoriaUtility::where('estado', 1);

        if ($auditorPlanta == 'Planta1') {
            $utilities->where('planta', 'Intimark1');
        } elseif ($auditorPlanta == 'Planta2') {
            $utilities->where('planta', 'Intimark2');
        }

        $utilities = $utilities->get();

        return response()->json($utilities);
    }

    public function formAltaProceso(Request $request) 
    {
        $pageSlug ='';

        $data = [
            'area' => $request->area,
            'modulo' => $request->modulo,
            'estilo' => $request->estilo,
            'team_leader' => $request->team_leader,
            'auditor' => $request->auditor,
            'turno' => $request->turno,
            'cliente' => $request->cliente,
        ];
        //dd($data, $request->all());
        return redirect()->route('aseguramientoCalidad.auditoriaProceso', $data)->with('cambio-estatus', 'Iniciando en modulo: '. $data['modulo'])->with('pageSlug', $pageSlug);
    }

    public function formRegistroAuditoriaProceso(Request $request)
    {
        $pageSlug ='';

        $fechaHoraActual = now();
        
        // Verificar el día de la semana
        $diaSemana = $fechaHoraActual->dayOfWeek;


        $plantaBusqueda = AuditoriaProceso::where('moduleid', $request->modulo)
            ->pluck('prodpoolid')
            ->first();
        //dd($plantaBusqueda);
        $jefeProduccionBusqueda = CategoriaTeamLeader::where('nombre', $request->team_leader)
            ->where('jefe_produccion', 1)
            ->first();

        //$diferenciaModulo = $request->modulo == $request->modulo_adicional;
        //dd($diferenciaModulo, $request->all());
        $modulo = $request->modulo;
        // Extraer la parte numérica del módulo
        $modulo_num = intval(substr($modulo, 0, 3));
        $nuevoRegistro = new AseguramientoCalidad();
        $nuevoRegistro->area = $request->area;
        if($modulo_num >= 100 && $modulo_num < 200){
            if(($request->modulo == "101A") && ($request->modulo_adicional == "101A")){
                $nuevoRegistro->modulo_adicional = NULL;
            }elseif($request->modulo_adicional != "101A"){
                $nuevoRegistro->modulo_adicional = $request->modulo_adicional;
            }
        }elseif($modulo_num >= 200 && $modulo_num < 300){
            if(($request->modulo == "201A") && ($request->modulo_adicional == "201A")){
                $nuevoRegistro->modulo_adicional = NULL;
            }elseif($request->modulo_adicional != "201A"){
                $nuevoRegistro->modulo_adicional = $request->modulo_adicional;
            }
        }
        $nuevoRegistro->modulo = $request->modulo;
        $nuevoRegistro->planta = $plantaBusqueda;
        //$nuevoRegistro->modulo_adicional = ($request->modulo == $request->modulo_adicional) ? NULL : $request->modulo_adicional;
        $nuevoRegistro->estilo = $request->estilo;
        $nuevoRegistro->cliente = $request->cliente;
        $nuevoRegistro->team_leader = $request->team_leader;
        if($jefeProduccionBusqueda){
            $nuevoRegistro->jefe_produccion = 1;
        }else{$nuevoRegistro->jefe_produccion = NULL; }
        $nuevoRegistro->auditor = $request->auditor;
        $nuevoRegistro->turno = $request->turno;
        if($request->utility){
            $nuevoRegistro->nombre = $request->utility;
            $nuevoRegistro->utility = 1;
        }else{ 
            if(!$request->input('nombre')){
                $nuevoRegistro->nombre = $request->input('nombre_hidden');
            }else{
                $nuevoRegistro->nombre = $request->nombre;
            }
        }
        $nuevoRegistro->operacion = $request->operacion;
        $nuevoRegistro->cantidad_auditada = $request->cantidad_auditada;
        $nuevoRegistro->cantidad_rechazada = $request->cantidad_rechazada;
        if($request->cantidad_rechazada > 0){
            $nuevoRegistro->inicio_paro = Carbon::now(); 

            // Aquí envías el correo
            //Mail::to('bteofilo@intimark.com.mx')
            //    ->send(new NotificacionParo($nuevoRegistro));
        }
        $nuevoRegistro->ac = $request->ac;
        $nuevoRegistro->pxp = $request->pxp;


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

        $nuevoRegistro->save();

        // Obtener el ID del nuevo registro
        $nuevoRegistroId = $nuevoRegistro->id;

        // Almacenar los valores de tp en la tabla tp_aseguramiento_calidad
        foreach ($request->tp as $tp) {
            $nuevoTp = new TpAseguramientoCalidad();
            $nuevoTp->aseguramiento_calidad_id = $nuevoRegistroId;
            $nuevoTp->tp = $tp;
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
            $actualizarRegistro = AseguramientoCalidad::where('id', $id)->first();
            $actualizarRegistro->operacion = $request->operacion;
            $actualizarRegistro->cantidad_auditada = $request->cantidad_auditada;
            $actualizarRegistro->cantidad_rechazada = $request->cantidad_rechazada;
            $actualizarRegistro->pxp = $request->pxp;
            $actualizarRegistro->save();

            //dd($request->all(), $actualizarRegistro, $id);
            return back()->with('sobre-escribir', 'Registro actualizado correctamente.')->with('pageSlug', $pageSlug);

            // Lógica para actualizar el registro
        } elseif ($action == 'delete'){
            // Lógica para eliminar el registro
            AseguramientoCalidad::where('id', $id)->delete();
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
        AseguramientoCalidad::whereDate('created_at', $fechaActual)
        ->where('modulo', $modulo)
        ->where('area', $area)
        ->update(['observacion' => $observacion, 'estatus' => $estatus]);
        

        return back()->with('success', 'Finalizacion aplicada correctamente.')->with('pageSlug', $pageSlug);
    }

    public function cambiarEstadoInicioParo(Request $request)
    {
        $pageSlug ='';
        $id = $request->idCambio;
        //dd($id);
        $registro = AseguramientoCalidad::find($id);
        $registro->fin_paro = Carbon::now();
        
        // Calcular la duración del paro en minutos
        $inicioParo = Carbon::parse($registro->inicio_paro);
        $finParo = Carbon::parse($registro->fin_paro);
        $minutosParo = $inicioParo->diffInMinutes($finParo);
        
        // Almacenar la duración en minutos
        $registro->minutos_paro = $minutosParo;

        $registro->save();

        return back()->with('success', 'Fin de Paro Aplicado.')->with('pageSlug', $pageSlug);
    }

}
