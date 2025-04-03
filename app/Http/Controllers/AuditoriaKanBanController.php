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
use App\Models\TpAuditoriaAQL;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;

class AuditoriaKanBanController extends Controller
{

    public function index(Request $request) 
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
            $datoPlanta = "1";
        }else{
            $datoPlanta = "2";
        }

        return view('kanban.index', compact('mesesEnEspanol', 'pageSlug'));
    }
}
