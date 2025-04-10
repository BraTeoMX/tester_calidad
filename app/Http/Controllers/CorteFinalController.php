<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\EncabezadoAuditoriaCorteV2;
use App\Models\AuditoriaCorteMarcada;
use App\Models\AuditoriaCorteTendido;
use App\Models\AuditoriaCorteLectra;
use App\Models\AuditoriaCorteBulto;
use App\Models\AuditoriaCorteFinal;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CorteFinalController extends Controller
{
    
    public function index(Request $request) 
    {
        $pageSlug ='';
        $fechaActual = Carbon::now()->toDateString();
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];



        return view('auditoriaCorte.index', compact('mesesEnEspanol', 'pageSlug'));
    }
}
