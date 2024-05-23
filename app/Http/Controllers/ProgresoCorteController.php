<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProgresoCorteController extends Controller
{
    public function ProgresoCorte()
    {
        return view('formulariosCalidad.ProgresoCorte');
    }
}
