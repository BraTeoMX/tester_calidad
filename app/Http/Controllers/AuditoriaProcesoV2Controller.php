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
        if($auditorPlanta == "Planta1"){
            $datoPlanta = "Intimark1";
        }else{
            $datoPlanta = "Intimark2";
        }

        $gerenteProduccion = CategoriaTeamLeader::orderByRaw("jefe_produccion != '' DESC")
            ->orderBy('jefe_produccion')
            ->where('planta', $datoPlanta)
            ->where('estatus', 1)
            ->where('jefe_produccion', 1)
            ->get();

        $procesoActual = AseguramientoCalidad::where('estatus', NULL)  
            //->where('auditor', $categorias['auditorDato'])
            ->where('area', 'AUDITORIA EN PROCESO')
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
            ->distinct()
            ->orderBy('modulo', 'asc');
        $procesoActual = $procesoActual->get();
        $procesoFinal =  AseguramientoCalidad::where('estatus', 1) 
            ->where('area', 'AUDITORIA EN PROCESO')
            ->where('planta', $datoPlanta)
            ->whereDate('created_at', $fechaActual)
            ->select('modulo','estilo', 'team_leader', 'turno', 'auditor', 'cliente', 'gerente_produccion')
            ->distinct()
            ->get();
        return view('aseguramientoCalidad.altaProcesoV2', compact('pageSlug', 'auditorDato', 'tipoUsuario', 'mesesEnEspanol', 'gerenteProduccion', 
                    'procesoActual', 'procesoFinal'));
    }

    public function obtenerModulosV2(Request $request)
    {
        $auditorPlanta = Auth::user()->Planta;
        if($auditorPlanta == "Planta1"){
            $datoPlanta = "Intimark1";
        }else{
            $datoPlanta = "Intimark2";
        }

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

        // Obtener los estilos con prioridad para los relacionados al módulo desde ModuloEstilo
        $itemidsModuloEstilo = ModuloEstilo::select('itemid')
            ->selectRaw('CASE WHEN moduleid = ? THEN 0 ELSE 1 END AS prioridad', [$moduleid])
            ->distinct('itemid')
            ->orderBy('prioridad') // Priorizar los relacionados al módulo
            ->orderBy('itemid') // Ordenar por itemid después
            ->pluck('itemid');

        // Obtener los estilos desde ModuloEstiloTemporal
        $itemidsModuloEstiloTemporal = ModuloEstiloTemporal::select('itemid')
            ->distinct('itemid')
            ->orderBy('itemid') // Ordenar por itemid
            ->pluck('itemid');

        // Combinar ambos resultados y eliminar duplicados
        $itemidsCombinados = $itemidsModuloEstilo->merge($itemidsModuloEstiloTemporal)->unique();

        return response()->json([
            'itemids' => $itemidsCombinados->values(),
        ]);
    }


}
