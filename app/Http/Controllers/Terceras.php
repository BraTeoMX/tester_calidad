<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Terceras extends Controller
{
    public function Terceras()
    {
        try {
            return view('Segundas.Terceras');

        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener Terceras: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
    public function ObtenerTerceras()
    {
        try {
            // Obtener Segundas y Terceras Generales
            $ObtenerTerceras = ObtenerTerceras();
            return response()->json([
                'ObtenerTerceras' => $ObtenerTerceras,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            // Manejar la excepción, por ejemplo, loguear el error
            Log::error('Error al obtener Terceras: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
}
