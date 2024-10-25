<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class redireccionar extends Controller
{
    public function redireccionar(Request $request)
       {
           $ruta = $request->input('ruta');
           return response()->json(['ruta' => $ruta]);
       }
}
