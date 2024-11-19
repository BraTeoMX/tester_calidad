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
            $Segundas = ObtenerSegundas();

            Log::info('Datos obtenidos:', ['data' => $Segundas]);

            return response()->json([
                'data' => $Segundas,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener Segundas: ' . $e->getMessage());

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
