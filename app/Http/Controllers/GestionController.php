<?php

namespace App\Http\Controllers;

use App\Models\JobAQLTeporal;
use App\Models\JobAQLHistorial;
use Illuminate\Http\Request; 

class GestionController extends Controller
{
    public function agregarAqlProceso()
    {
        $pageSlug ='';
        

        return view('gestion.agregarAqlProceso', compact('pageSlug'));
    }

}
