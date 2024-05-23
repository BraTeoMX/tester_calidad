<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoriaCliente;

class ReporteEtiquetaController extends Controller
{
    public function index()
    {
        $CategoriaCliente = CategoriaCliente::all();
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];


        return view('reporte_etiqueta', compact('mesesEnEspanol', 'CategoriaCliente'));
    }
}
