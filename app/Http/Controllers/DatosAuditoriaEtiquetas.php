<?php

namespace App\Http\Controllers;

use App\Models\DatosAuditoriaEtiquetas as ModelsDatosAuditoriaEtiquetas;
use App\Models\Cat_DefEtiquetas;
use App\Models\ReporteAuditoriaEtiqueta;
use Illuminate\Http\Request;
use App\Models\DatosAXOV;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatosAuditoriaEtiquetas extends Controller
{
    public function auditoriaEtiquetas()
    {
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];
        return view('formulariosCalidad.auditoriaEtiquetas', compact('mesesEnEspanol'));
    }
    public function NoOrdenes()
    {
        $ordenes = ModelsDatosAuditoriaEtiquetas::select('OrdenCompra')
            ->distinct()
            ->get();

        return response()->json($ordenes);
    }
    public function NoOP()
    {

        $ordenes = DatosAXOV::select('op')
            ->distinct()
            ->get();

        return response()->json($ordenes);
    }
    public function NoPO()
    {

        $ordenes = DatosAXOV::select('cpo')
            ->distinct()
            ->get();


        return response()->json($ordenes);
    }
    public function NoOV()
    {
        $ordenes = DatosAXOV::select('salesid')
            ->distinct()
            ->get();


        return response()->json($ordenes);
    }
    public function buscarEstilos(Request $request)
    {
        $orden = $request->input('orden');
        $tipoBusqueda = $request->input('tipoBusqueda'); // Obtener el tipo de búsqueda
        Log::info('Datosingresados: ' . $orden . ',' . $tipoBusqueda);
        // Definir el campo de búsqueda según el tipo
        $campoBusqueda = 'OrdenCompra'; // Valor predeterminado para OC
        $modelo = ModelsDatosAuditoriaEtiquetas::class;
        if ($tipoBusqueda === 'OP') {
            $campoBusqueda = 'op'; // Cambia 'op' por el nombre real de la columna
            $modelo = DatosAXOV::class;
        } elseif ($tipoBusqueda === 'PO') {
            $campoBusqueda = 'cpo'; // Cambia 'cpo' por el nombre real de la columna
            $modelo = DatosAXOV::class;
        } elseif ($tipoBusqueda === 'OV') {
            $campoBusqueda = 'salesid'; // Cambia 'salesid' por el nombre real de la columna
            $modelo = DatosAXOV::class;
        }

        $estilos = $modelo::where($campoBusqueda, $orden)
            ->select('Estilos') // Asegúrate de que 'Estilos' existe en DatosAXOV
            ->distinct()
            ->get();
        Log::info('Datos del select buscar estilos: ' . $estilos);
        $status = [];

        foreach ($estilos as $key => $estilo) {
            // Obtener el estado de la auditoría para este estilo
            $auditoriaEstado = $this->obtenerEstadoAuditoria($orden, $estilo->Estilos);
            $status[$key] = $auditoriaEstado;
        }

        return response()->json(['estilos' => $estilos, 'status' => $status]);
    }

    private function obtenerEstadoAuditoria($orden, $estilo)
    {
        try {
            // Evaluar el estado en el modelo 'ModelsDatosAuditoriaEtiquetas'
            $registrosEtiquetas = ModelsDatosAuditoriaEtiquetas::where('OrdenCompra', $orden)
                ->where('Estilos', $estilo)
                ->pluck('status');

            // Evaluar el estado en el modelo 'DatosAXOV'
            $registrosAXOV = DatosAXOV::where(function ($query) use ($orden) {
                    $query->where('op', $orden)
                          ->orWhere('cpo', $orden)
                          ->orWhere('salesid', $orden);
                })
                ->where('Estilos', $estilo)
                ->pluck('status');

            // Unir los estados de ambos modelos
            $todosEstados = $registrosEtiquetas->merge($registrosAXOV);

            // Si no se encontraron registros, la auditoría no ha sido iniciada
            if ($todosEstados->isEmpty()) {
                return 'No iniciada';
            }

            // Verificar los diferentes estados de auditoría
            $todosNulos = true;
            $todosIniciados = true;
            $alMenosUnoEnProceso = false;
            $todosFinalizados = true;

            $estadosEnProceso = ['Guardado', 'Update', 'Iniciado', 'Aprobado', 'Aprobado Condicionalmente', 'Rechazado'];
            $estadosFinalizados = ['Aprobado', 'Aprobado Condicionalmente', 'Rechazado'];

            foreach ($todosEstados as $status) {
                if ($status !== null) {
                    $todosNulos = false;
                }

                if ($status === 'Iniciado') {
                    $todosIniciados = true;
                }

                if (in_array($status, $estadosEnProceso)) {
                    $alMenosUnoEnProceso = true;
                }

                if (!in_array($status, $estadosFinalizados)) {
                    $todosFinalizados = false;
                }
            }

            // Determinar el estado de la auditoría
            if ($todosNulos) {
                return 'No iniciada';
            } elseif ($todosFinalizados) {
                return 'Auditoría Finalizada';
            } elseif ($alMenosUnoEnProceso) {
                return 'En Proceso de auditoría';
            } elseif ($todosIniciados) {
                return 'Auditoría Iniciada';
            }

        } catch (\Exception $e) {
            // Manejar excepciones y errores
            Log::error('Error en la obtención del estado de la auditoría: ' . $e->getMessage());
            return 'Error al obtener el estado de la auditoría';
        }
    }

    public function buscarDatosAuditoriaPorEstilo(Request $request)
    {
        $estilo = $request->input('estilo');
        $orden = $request->input('orden');
        $tipoBusqueda = $request->input('tipoBusqueda');

        // Definir el campo de búsqueda y el modelo según el tipo
        if ($tipoBusqueda == 'OC') {
            $campoBusqueda = 'OrdenCompra';
            $modelo = ModelsDatosAuditoriaEtiquetas::class;
            $selectCampos = ['id', 'OrdenCompra', 'Estilos', 'Cantidad', 'Talla', 'Color'];
        } else {
            // Mapeo de tipos de búsqueda a nombres de columna
            $campoBusqueda = [
                'OP' => 'op',
                'PO' => 'cpo',
                'OV' => 'salesid',
            ][$tipoBusqueda];

            $modelo = DatosAXOV::class;
            $selectCampos = ['id', $campoBusqueda, 'Estilos', 'qty', 'sizename', 'inventcolorid'];
        }
Log::info('Estilo seleccionado: '. $estilo);
        // Buscar datos relacionados con el estilo especificado y la orden de compra
        $datos = $modelo::where('Estilos', $estilo)
            ->where($campoBusqueda, $orden)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn('status', ['Iniciado', 'Guardado']);
            })
            ->select($selectCampos)
            ->get();
            Log::info('Datos del select buscar data cmpleta: ' . $datos);
        // Iterar sobre los datos y determinar el tamaño de muestra
        foreach ($datos as $dato) {
            // Determinar el campo de cantidad según el modelo
            $campoCantidad = ($modelo === DatosAXOV::class) ? 'qty' : 'Cantidad';
            $cantidad = $dato->$campoCantidad; // Obtener la cantidad del campo correcto

            $tamaño_muestra = '';

            // Determinar el rango de cantidad y asignar el tamaño de muestra correspondiente
            if ($cantidad >= 2 && $cantidad <= 8) {
                $tamaño_muestra = '2';
            } elseif ($cantidad >= 9 && $cantidad <= 15) {
                $tamaño_muestra = '3';
            } elseif ($cantidad >= 16 && $cantidad <= 25) {
                $tamaño_muestra = '5';
            } elseif ($cantidad >= 26 && $cantidad <= 50) {
                $tamaño_muestra = '8';
            } elseif ($cantidad >= 51 && $cantidad <= 90) {
                $tamaño_muestra = '13';
            } elseif ($cantidad >= 91 && $cantidad <= 150) {
                $tamaño_muestra = '20';
            } elseif ($cantidad >= 151 && $cantidad <= 280) {
                $tamaño_muestra = '32';
            } elseif ($cantidad >= 281 && $cantidad <= 500) {
                $tamaño_muestra = '50';
            } elseif ($cantidad >= 501 && $cantidad <= 1200) {
                $tamaño_muestra = '80';
            } elseif ($cantidad >= 1201 && $cantidad <= 3200) {
                $tamaño_muestra = '125';
            } elseif ($cantidad >= 3201 && $cantidad <= 10000) {
                $tamaño_muestra = '200';
            } elseif ($cantidad >= 10001 && $cantidad <= 35000) {
                $tamaño_muestra = '315';
            } elseif ($cantidad >= 35001 && $cantidad <= 150000) {
                $tamaño_muestra = '500';
            } elseif ($cantidad >= 150001 && $cantidad <= 5000000) {
                $tamaño_muestra = '800';
            } elseif ($cantidad > 5000000) {
                $tamaño_muestra = '2000';
            }

            // Asignar el tamaño de muestra al modelo
            $dato->tamaño_muestra = $tamaño_muestra;
        }
        return response()->json($datos);
    }

    public function obtenerTiposDefectos()
    {
        $tiposDefectos = Cat_DefEtiquetas::all();

        return response()->json($tiposDefectos);
    }
    public function actualizarStatus(Request $request)
{
    try {
        // Obtener los datos enviados desde el frontend
        $datos = $request->input('datos');
        $status = $request->input('status');
        $rowId = $request->input('rowId');

        // Registrar los datos iniciales para depuración
        Log::info('Datos recibidos: ' . json_encode($datos));
        Log::info('Status recibido: ' . $status);
        Log::info('Row ID recibido: ' . $rowId);

        if (!is_array($datos) || empty($datos)) {
            throw new \Exception('Datos inválidos, se esperaba un array no vacío.');
        }

        foreach ($datos as $dato) {
            // Log detallado de cada dato individual
            Log::info('Procesando dato: ' . json_encode($dato));

            // Validar la existencia de campos necesarios
            if (!isset($dato['id']) || !isset($dato['tipoBusqueda'])) {
                throw new \Exception('Datos incompletos: falta id o tipoBusqueda.');
            }

            // Convertir el array tipoDefecto en string si es necesario
            if (is_array($dato['tipoDefecto'])) {
                $dato['tipoDefecto'] = implode(', ', $dato['tipoDefecto']);
            }
            Log::info('Tipo Defecto después de conversión: ' . $dato['tipoDefecto']);

            // Convertir el array defectos en una cadena separada por comas
            if (is_array($dato['defectos'])) {
                // Suponiendo que cada elemento es un objeto con una clave "cantidad"
                $defectos = array_map(function($defecto) {
                    return $defecto['cantidad']; // Extraer solo la cantidad
                }, $dato['defectos']);

                $dato['defectos'] = implode(', ', $defectos); // Convertir array en string separado por comas
            }
            Log::info('Defectos después de conversión: ' . $dato['defectos']);

            // Log de los datos antes de intentar guardar
            Log::info('Datos a guardar o actualizar: ' . json_encode([
                'Orden' => $dato['orden'] ?? 'N/A',
                'Estilos' => $dato['estilo'] ?? 'N/A',
                'Cantidad' => $dato['cantidad'] ?? 'N/A',
                'Muestreo' => $dato['muestreo'] ?? 'N/A',
                'Defectos' => $dato['defectos'] ?? '', // Ahora es una cadena separada por comas
                'Tipo_Defectos' => $dato['tipoDefecto'] ?? 'N/A',
                'Talla' => $dato['talla'] ?? 'N/A',
                'Color' => $dato['color'] ?? 'N/A',
                'Status' => $status
            ]));

            // Buscar o crear registro en ReporteAuditoriaEtiqueta
            $reporte = ReporteAuditoriaEtiqueta::updateOrCreate(
                ['id' => $rowId],
                [
                    'Orden' => $dato['orden'] ?? 'N/A',
                    'Estilos' => $dato['estilo'] ?? 'N/A',
                    'Cantidad' => $dato['cantidad'] ?? 'N/A',
                    'Muestreo' => $dato['muestreo'] ?? 'N/A',
                    'Defectos' => $dato['defectos'] ?? '', // Guardar como cadena separada por comas
                    'Tipo_Defectos' => $dato['tipoDefecto'] ?? 'N/A',
                    'Talla' => $dato['talla'] ?? 'N/A',
                    'Color' => $dato['color'] ?? 'N/A',
                    'Status' => $status
                ]
            );
            Log::info('Reporte guardado/actualizado con ID: ' . $reporte->id);

            // Obtener el tipo de búsqueda del registro actual
            $tipoBusqueda = $dato['tipoBusqueda'];

            // Determinar el modelo y el campo de búsqueda según el tipo
            if ($tipoBusqueda === 'OC') {
                $modelo = ModelsDatosAuditoriaEtiquetas::class;
                $campoBusqueda = 'id';
            } else {
                $modelo = DatosAXOV::class;
                $campoBusqueda = 'id';
            }

            // Buscar si existe un registro en el modelo correspondiente
            $registroExistenteModel = $modelo::where($campoBusqueda, $dato['id'])->first();
            Log::info('Registro existente encontrado: ' . json_encode($registroExistenteModel));

            if ($registroExistenteModel) {
                // Actualizar solo el atributo 'status'
                $registroExistenteModel->update(['status' => $status]);
                Log::info('Status actualizado en el modelo: ' . $modelo);
            } else {
                // Registro no encontrado en el modelo específico, loguear advertencia
                Log::warning('Registro no encontrado en ' . $modelo . ' con ' . $campoBusqueda . ': ' . $dato['id']);
            }
        }

        // Retornar una respuesta JSON indicando el éxito
        return response()->json(['mensaje' => 'Los datos han sido actualizados correctamente'], 200);
    } catch (\Exception $e) {
        Log::error('Error al actualizar los datos: ' . $e->getMessage() . ' en la línea ' . $e->getLine());
        // Retornar una respuesta JSON con el mensaje de error
        return response()->json(['error' => 'Error al actualizar los datos: ' . $e->getMessage()], 500);
    }
}



}
