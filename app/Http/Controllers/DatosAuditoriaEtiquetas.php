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
        $Proveedor = ModelsDatosAuditoriaEtiquetas::select('Proveedor')
            ->distinct()
            ->get();
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
        Log::info('Datos del select: ' . $estilos);
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
        // Realizar una unión (JOIN) entre los modelos
        $registros = ModelsDatosAuditoriaEtiquetas::select('auditoria_etiquetas.status') // Seleccionar solo la columna 'status'
            ->leftJoin('datos_auditoriasov', function ($join) use ($orden) {
                $join->on('auditoria_etiquetas.Estilos', '=', 'datos_auditoriasov.Estilos') // Unir por la columna 'Estilos'
                    ->where(function ($query) use ($orden) {
                        $query->where('datos_auditoriasov.op', $orden)
                            ->orWhere('datos_auditoriasov.cpo', $orden)
                            ->orWhere('datos_auditoriasov.salesid', $orden);
                    });
            })
            ->where('auditoria_etiquetas.OrdenCompra', $orden) // Filtrar por la orden en el modelo principal
            ->orWhere(function ($query) use ($orden) { // Filtrar por la orden en el modelo secundario
                $query->where('datos_auditoriasov.op', $orden)
                    ->orWhere('datos_auditoriasov.cpo', $orden)
                    ->orWhere('datos_auditoriasov.salesid', $orden);
            })
            ->where('auditoria_etiquetas.Estilos', $estilo) // Filtrar por el estilo
            ->get();

        // Si no se encontraron registros, la auditoría no ha sido iniciada
        if ($registros->isEmpty()) {
            return 'No iniciada';
        }

        // Verificar los diferentes estados de auditoría (lógica similar a la original)
        $todosNulos = true;
        $todosIniciados = true;
        $alMenosUnoEnProceso = false;
        $todosFinalizados = true;

        foreach ($registros as $registro) {
            if ($registro->status !== null) {
                $todosNulos = false;
            }

            if ($registro->status !== 'Iniciado' && $registro->status !== null) {
                $todosIniciados = false;
            }

            if ($registro->status === 'Guardado' || $registro->status === 'Update' || $registro->status === 'Iniciado') {
                $alMenosUnoEnProceso = true;
            }

            if ($registro->status !== 'Aprobado' && $registro->status !== 'Aprobado Condicionalmente' && $registro->status !== 'Rechazado') {
                $todosFinalizados = false;
            }
        }

        // Determinar el estado de la auditoría
        if ($todosNulos) {
            return 'No iniciada';
        } elseif ($todosIniciados) {
            return 'Auditoría Iniciada';
        } elseif ($alMenosUnoEnProceso) {
            return 'En Proceso de auditoría';
        } elseif ($todosFinalizados) {
            return 'Auditoría Finalizada';
        }
    }
    public function buscarDatosAuditoriaPorEstilo(Request $request)
    {
        $estilo = $request->input('estilo');
        $orden = $request->input('orden');
        $tipoBusqueda = $request->input('tipoBusqueda');

        // Definir el campo de búsqueda y el modelo según el tipo
        if ($tipoBusqueda === 'OC') {
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

        // Buscar datos relacionados con el estilo especificado y la orden de compra
        $datos = $modelo::where('Estilos', $estilo)
            ->where($campoBusqueda, $orden)
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereIn('status', ['Iniciado', 'Guardado']);
            })
            ->select($selectCampos)
            ->get();

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
    public function guardarInformacion(Request $request)
    {
        $datos = $request->input('datos');

        try {
            // Validar datos
            if (!is_array($datos)) {
                throw new \Exception('Datos inválidos, se esperaba un array.');
            }

            // Iterar sobre los datos recibidos
            foreach ($datos as $dato) {
                // Verificar si existe el índice 'tipoDefecto' antes de acceder a él
                if (isset($dato['tipoDefecto'])) {
                    $tipoDefecto = is_array($dato['tipoDefecto'])
                        ? implode(', ', $dato['tipoDefecto'])
                        : $dato['tipoDefecto'];
                } else {
                    $tipoDefecto = 'N/A'; // Valor por defecto si no existe
                }

                // Buscar si existe un registro con el mismo ID en ReporteAuditoriaEtiqueta
                $registroExistente = ReporteAuditoriaEtiqueta::find($dato['id']);
                if ($registroExistente) {
                    // Si existe un registro en ReporteAuditoriaEtiqueta, actualizar sus atributos
                    $registroExistente->Status = 'Update';
                    $registroExistente->Defectos = $dato['defectos'];
                    $registroExistente->Tipo_Defectos = $tipoDefecto;
                    $registroExistente->save();
                } else {
                    // Si no existe, crear un nuevo registro en ReporteAuditoriaEtiqueta
                    $reporte = new ReporteAuditoriaEtiqueta();
                    $reporte->id = $dato['id'];
                    $reporte->Orden = $dato['orden'];
                    $reporte->Estilos = $dato['estilo'];
                    $reporte->Cantidad = $dato['cantidad'];
                    $reporte->Muestreo = $dato['muestreo'];
                    $reporte->Defectos = $dato['defectos'];
                    $reporte->Tipo_Defectos = $tipoDefecto;
                    $reporte->Talla = $dato['talla'];
                    $reporte->Color = $dato['color'];
                    $reporte->Status = 'Guardado';
                    $reporte->save();
                }

                // Obtener el tipo de búsqueda del registro actual
                $tipoBusqueda = $dato['tipoBusqueda'];

                // Determinar el modelo y el campo de búsqueda según el tipo
                if ($tipoBusqueda === 'OC') {
                    $modelo = ModelsDatosAuditoriaEtiquetas::class;
                    $campoBusqueda = 'OrdenCompra';
                } else {
                    $campoBusqueda = $tipoBusqueda; // OP, PO, OV
                    $modelo = DatosAXOV::class;
                }

                try {
                    // Buscar todos los registros en el modelo correspondiente que coincidan con el tipo de búsqueda y el estilo
                    $registrosExistentesModel = $modelo::where($campoBusqueda, $dato['orden'])
                        ->where('Estilo', $dato['estilo'])
                        ->get();

                    if ($registrosExistentesModel->isNotEmpty()) {
                        // Si existen registros, actualizar solo su atributo 'status'
                        foreach ($registrosExistentesModel as $registroExistenteModel) {
                            $registroExistenteModel->status = 'Iniciado';
                            $registroExistenteModel->save();
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error al buscar o actualizar registros en el modelo ' . $modelo . ': ' . $e->getMessage());
                    throw $e; // Volver a lanzar la excepción para que sea atrapada por el catch exterior
                }
            }

            // Retornar una respuesta JSON indicando el éxito
            return response()->json(['mensaje' => 'Los datos han sido actualizados correctamente'], 200);
        } catch (\Exception $e) {
            Log::error('Error al guardar los datos: ' . $e->getMessage() . ' en la línea ' . $e->getLine());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }
    public function actualizarStatus(Request $request)
{
    try {
        // Obtener los datos enviados desde el frontend
        $datos = $request->input('datos');
        // Obtener el status del dropdown
        $status = $request->input('status');
        $rowId = $request->input('rowId');

        $contador = count($datos);

        // Iterar sobre los datos recibidos
        for ($i = 0; $i < $contador; $i++) {
            // Buscar si existe un registro con el mismo ID en ReporteAuditoriaEtiqueta
            $registroExistente = ReporteAuditoriaEtiqueta::find($rowId);
            if ($registroExistente) {
                if ($datos[$i]['id'] == $rowId) {
                    // Actualizar el registro existente
                    $registroExistente->update([
                        'Status' => $status,
                        'Defectos' => $datos[$i]['defectos'] ?? 'N/A',
                        'Tipo_Defectos' => $datos[$i]['tipoDefecto'] ?? 'N/A'
                    ]);
                }
            } else {
                // Si no existe, crear un nuevo registro en ReporteAuditoriaEtiqueta
                $registroExistente = new ReporteAuditoriaEtiqueta();
                $registroExistente->id = $rowId;
                $registroExistente->Orden = $datos[$i]['orden'] ?? 'N/A';
                $registroExistente->Estilos = $datos[$i]['estilo'] ?? 'N/A';
                $registroExistente->Cantidad = $datos[$i]['cantidad'] ?? 'N/A';
                $registroExistente->Muestreo = $datos[$i]['muestreo'] ?? 'N/A';
                $registroExistente->Defectos = $datos[$i]['defectos'] ?? 'N/A';
                $registroExistente->Tipo_Defectos = $datos[$i]['tipoDefecto'] ?? 'N/A';
                $registroExistente->Talla = $datos[$i]['talla'] ?? 'N/A';
                $registroExistente->Color = $datos[$i]['color'] ?? 'N/A';
                $registroExistente->Status = $status;
                $registroExistente->save();
            }

            // Obtener el tipo de búsqueda del registro actual
            $tipoBusqueda = $datos[$i]['tipoBusqueda'];

            // Determinar el modelo y el campo de búsqueda según el tipo
            if ($tipoBusqueda === 'OC') {
                $modelo = ModelsDatosAuditoriaEtiquetas::class;
                $campoBusqueda = 'OrdenCompra';
            } else {
                $modelo = DatosAXOV::class;
                $campoBusqueda = $tipoBusqueda; // OP, PO, OV
            }

            // Buscar si existe un registro en el modelo correspondiente
            $registroExistenteModel = $modelo::where($campoBusqueda, $datos[$i]['id'])->first();

            if ($registroExistenteModel) {
                // Actualizar solo el atributo 'status'
                $registroExistenteModel->update(['status' => $status]);
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
