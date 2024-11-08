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
            $terceras = ObtenerTerceras();

            Log::info('Datos obtenidos:', ['data' => $terceras]);

            return response()->json([
                'data' => $terceras,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener Terceras: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
    public function ObtenerPlantas()
    {
        try {
            // Obtener plantas
            $ObtenerPlantas = ObtenerPlantas(); // Asegúrate que esta función esté definida y devuelva resultados
            Log::info('Datos ObtenerPlantas: ' . json_encode($ObtenerPlantas));
            return response()->json([
                'ObtenerPlantas' => $ObtenerPlantas,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener ObtenerPlantas: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
    public function ObtenerModulos()
    {
        try {
            Log::info('Ejecutando función ObtenerModulos desde el controlador'); // Verificación
            $modulos = ObtenerModulos(); // Llama a la función del helper

            return response()->json([
                'ObtenerModulos' => $modulos,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener ObtenerModulos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
    public function ObtenerClientes()
    {
        try {
            Log::info('Ejecutando función ObtenerModulos desde el controlador'); // Verificación
            $Clientes = ObtenerClientes(); // Llama a la función del helper

            return response()->json([
                'ObtenerClientes' => $Clientes,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener ObtenerModulos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
}
