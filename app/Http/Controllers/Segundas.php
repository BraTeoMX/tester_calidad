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

    public function obtenerSegundasFiltradas(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');
        $plantas = $request->input('plantas');
        $modulos = $request->input('modulos');
        $divisiones = $request->input('divisiones');

        // Obtener los datos de la tabla usando la función helper
        $segundas = ObtenerSegundas();
        $plantasBD = ObtenerPlantas();
        // Aplicar los filtros
        if ($fechaInicio && $fechaFin) {
            $segundas = $segundas->whereBetween('TRANSDATE', [$fechaInicio, $fechaFin]);
        }
        if ($plantas) {
            $segundas = $segundas->whereIn($plantasBD, $plantas); // Usando PRODPOOLID para plantas
        }
        if ($modulos) {
            $segundas = $segundas->whereIn('OPRMODULEID_AT', $modulos);
        }
        if ($divisiones) {
            $segundas = $segundas->whereIn('DIVISIONNAME', $divisiones);
        }

        // Devolver los datos en la respuesta
        return response()->json(['status' => 'success', 'data' => $segundas]);
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
