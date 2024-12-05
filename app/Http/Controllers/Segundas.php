<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
            // Verificar si los datos están en caché
            $cacheKey = 'ObtenerSegundasCache';
            if (Cache::has($cacheKey)) {
                Log::info('Datos obtenidos desde el caché del controlador.');
                $Segundas = Cache::get($cacheKey);
            } else {
                // Si no están en caché, consultamos los datos y los almacenamos
                $Segundas = ObtenerSegundas();

                // Almacenar los datos en caché por 30 minutos
                Cache::put($cacheKey, $Segundas, 1800);

                Log::info('Datos obtenidos desde el caché global o consulta SQL.');
            }

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
            Log::info('Ejecutando función ObtenerClientes desde el controlador');
            $Clientes = ObtenerClientes();

            return response()->json([
                'ObtenerClientes' => $Clientes,
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener clientes: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al obtener los datos.',
                'status' => 'error'
            ], 500);
        }
    }
}
