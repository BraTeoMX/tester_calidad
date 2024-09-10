<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Segundas extends Controller
{
    public function  Segundas(){
              //Ontener Segundas y Teceras Generales
              $SegundasTerceras = obtenerSegundasTerceras();
              return view('Segundas.Segundas', compact('SegundasTerceras'));
}
}
