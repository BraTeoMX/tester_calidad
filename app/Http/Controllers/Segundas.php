<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Segundas extends Controller
{
    public function Segundas()
    {
        try {
            // Obtener Segundas y Terceras Generales
            return view('Segundas.Segundas');

        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener Segundas: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
    public function ObtenerSegundas()
    {
        try {
            // Obtener Segundas y Terceras Generales
            $ObtenerSegundas = ObtenerSegundas();

            return response()->json([
                'ObtenerSegundas' => $ObtenerSegundas,
                'status' => 'success'
            ], 200);

        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener Segundas: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
}