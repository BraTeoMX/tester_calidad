<?php

namespace App\Http\Controllers;


class viewlistaFormularios extends Controller
{
    public function listaFormularios()
    {
        $pageSlug ='';
        return view('listaFormularios', compact('pageSlug'));
    }


}
