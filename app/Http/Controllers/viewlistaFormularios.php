<?php

namespace App\Http\Controllers;


class viewlistaFormularios extends Controller
{
    public function listaFormularios()
    {
        $activePage ='';
        return view('listaFormularios', compact('activePage'));
    }


}
