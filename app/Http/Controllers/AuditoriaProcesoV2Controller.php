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
        return view('aseguramientoCalidad.auditoriaProcesoV2', compact('mesesEnEspanol', 'pageSlug', 'data' ));
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


}
