<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AccionCorrectScreen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DatosAX;
use App\Models\Horno_Banda;
use App\Models\OpcionesDefectosScreen;
use App\Models\ScreenPrint;
use App\Models\Tecnicos;
use App\Models\Tipo_Fibra;
use App\Models\Tipo_Tecnica;

class CalidadScreenPrintController extends Controller
{
    public function ScreenPrint(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.ScreenPrint', compact('mesesEnEspanol'));
    }
    public function Ordenes()
    {
        // Verificar si el usuario logeado pertenece a Planta2
        $userPlanta = Auth::user()->Planta; // Ajusta esto según la estructura de tu tabla de usuarios y la columna correspondiente

        // Realizar la consulta filtrando por planta si el usuario pertenece a Planta2
        $ordenes = DatosAX::when($userPlanta === 'Planta2', function ($query) {
            return $query->where('planta', 'INTIMARK2');
        })
            ->select('op')
            ->distinct()
            ->get();

        return response()->json($ordenes);
    }
    public function Clientes($ordenes)
    {
        $clientes = DatosAX::select('custorname')
            ->where('op', $ordenes)
            ->distinct()
            ->get();

        return response()->json($clientes);
    }

    public function Estilo($ordenes)
    {
        $estilos = DatosAX::select('estilo')
            ->where('op', $ordenes)
            ->distinct()
            ->get();

        return response()->json($estilos);
    }
    public function Tecnicos()
    {
        $tecnicos = Tecnicos::all();
        return response()->json($tecnicos);
    }
    public function TipoTecnica()
    {
        $tipo_tecnica = Tipo_Tecnica::all();

        return response()->json($tipo_tecnica);
    }
    public function TipoFibra()
    {
        $tipo_fibra = Tipo_Fibra::all();

        return response()->json($tipo_fibra);
    }
    public function AgregarTecnica(Request $request)
    {


        $nuevaTecnica = $request->input('nuevaTecnica');

        // Crear una nueva instancia de Tipo_Tecnica
        $addTecnica = new Tipo_Tecnica;

        $addTecnica->Tipo_tecnica = $nuevaTecnica;

        // Guardar la nueva técnica en la base de datos
        $addTecnica->save();

        return response()->json($addTecnica);
    }

    public function AgregarFibra(Request $request)
    {

        $nuevaFibra = $request->input('nuevafibra');

        // Crear una nueva instancia de Tipo_Tecnica
        $addFibra = new Tipo_Fibra;
        $addFibra->Tipo_Fibra = $nuevaFibra;

        // Guardar la nueva técnica en la base de datos
        $addFibra->save();

        return response()->json($addFibra);
    }
    public function OpcionesACCorrectiva()
    {
        $data = AccionCorrectScreen::pluck('AccionCorrectiva');

        return response()->json($data);
    }

    public function OpcionesTipoProblema()
    {
        $data = OpcionesDefectosScreen::pluck('Defecto');

        return response()->json($data);
    }
    public function viewTabl()
    {
        // Obtener la fecha actual
        $today = Carbon::today();

        // Filtrar registros con la fecha actual
        $screen = ScreenPrint::whereDate('created_at', $today)->get();

        // Crear un array asociativo con los datos a retornar
        $responseData = [];

        foreach ($screen as $item) {
            // Verificar y asignar el valor de Tipo_Problema si está definido, de lo contrario, establecer como nulo
            $tipoProblema = isset($item->Tipo_Problema) ? $item->Tipo_Problema : null;

            // Verificar y asignar el valor de Ac_Correctiva si está definido, de lo contrario, establecer como nulo
            $acCorrectiva = isset($item->Ac_Correctiva) ? $item->Ac_Correctiva : null;

            $responseData[] = [
                'id' => $item->id,
                'Auditor' => $item->Auditor,
                'Cliente' => $item->Cliente,
                'Estilo' => $item->Estilo,
                'OP_Defec' => $item->OP_Defec,
                'Tecnico' => $item->Tecnico,
                'Color' => $item->Color,
                'Num_Grafico' => $item->Num_Grafico,
                'Tecnica' => $item->Tecnica,
                'Fibras' => $item->Fibras,
                'Porcen_Fibra' => $item->Porcen_Fibra,
                'Piezas_Auditar'=> $item->Piezas_Auditar,
                'Tipo_Problema' => $tipoProblema,
                'Num_Problemas' => $item->Num_Problemas,
                'Ac_Correctiva' => $acCorrectiva,
                'Status' => $item->Status,
            ];
        }

        // Agregar logs para verificar los valores
        Log::info('Datos de /viewTable:', $responseData);

        return response()->json($responseData);
    }

    public function SendScreenPrint(Request $request)
    {
        // Obtener la marca addRowClicked del formulario
        $addRowClicked = $request->input('addRowClicked');

        // Obtener los datos del formulario
        $auditor = $request->input('Auditor');
        $cliente = $request->input('Cliente');
        $estilo = $request->input('Estilo');
        $opDefec = $request->input('OP_Defec');
        $tecnico = $request->input('Tecnico');
        $color = $request->input('Color');
        $numGrafico = $request->input('Num_Grafico');
        $tecnica = $request->input('Tecnica');
        $fibras = $request->input('Fibras');
        $porcentajeFibra = $request->input('Porcen_Fibra');
        $pizasAuditar = $request->input('Piezas_Auditar');
        $tipoProblema = $request->input('Tipo_Problema');
        $numProblemas =  $request->input('Num_Problemas');
        $acCorrectiva = $request->input('Ac_Correctiva');
        Log::info('Datos recibidos en SendScreenPrint:', [
            'addRowClicked' => $addRowClicked,
            'Auditor' => $auditor,
            'Cliente' => $cliente,
            'Estilo' => $estilo,
            'OP_Defec' => $opDefec,
            'Tecnico' => $tecnico,
            'Color' => $color,
            'Num_Grafico' => $numGrafico,
            'Tecnica' => $tecnica,
            'Fibras' => $fibras,
            'Porcen_Fibra' => $porcentajeFibra,
            'Piezas_Auditar' => $pizasAuditar,
            'Tipo_Problema' => $tipoProblema,
            'Num_Problemas' => $numProblemas,
            'Ac_Correctiva' => $acCorrectiva
        ]);
        // Crear un nuevo registro con 'Nuevo' como valor para la columna 'Status' si ambos botones fueron presionados
        if ($addRowClicked) {
            if (is_null($numProblemas)) {
                $numProblemas = array_fill(0, count($tipoProblema), 0); // Array de ceros del mismo tamaño que $tipoProblema
            }
            // Combinar los arrays en un solo string separado por comas
            $tipoProblemaString = implode(', ', $tipoProblema);
            $numProblemasString = implode(', ', $numProblemas);
            $acCorrectivaString = implode(', ', $acCorrectiva);

            $screenPrint = ScreenPrint::create([
                'Auditor' => $auditor,
                'Cliente' => $cliente,
                'Estilo' => $estilo,
                'OP_Defec' => $opDefec,
                'Tecnico' => $tecnico,
                'Color' => $color,
                'Num_Grafico' => $numGrafico,
                'Tecnica' => $tecnica,
                'Fibras' => $fibras,
                'Porcen_Fibra' => $porcentajeFibra,
                'Piezas_Auditar' => $pizasAuditar,
                'Tipo_Problema' => $tipoProblemaString, // Guardar como string separado por comas
                'Num_Problemas' => $numProblemasString, // Guardar como string separado por comas
                'Ac_Correctiva' => $acCorrectivaString, // Guardar como string separado por comas
                'Status' => 'Nuevo',
            ]);
            // Puedes realizar acciones adicionales si es necesario después de crear el nuevo registro

            return response()->json(['mensaje' => 'Datos guardados exitosamente', 'screenPrint' => $screenPrint]);
        }
    }

    public function UpdateScreenPrint(Request $request, $id)
    {
        // Verificar si el registro existe
        $screenPrint = ScreenPrint::find($id);

        if (!$screenPrint) {
            return response()->json(['mensaje' => 'Registro no encontrado'], 404);
        }

        // Obtener los valores actuales de 'Tipo_Problema' y 'Ac_Correctiva'
        $tipoProblemaActual = $screenPrint->Tipo_Problema;
        $acCorrectivaActual = $screenPrint->Ac_Correctiva;

        // Verificar si los valores son los específicos que se deben excluir
        $excluirTipoProblema = $request->input('Tipo_Problema') === 'Seleccione Tipo de Problema';
        $excluirAcCorrectiva = $request->input('Ac_Correctiva') === 'Seleccione Acción Correctiva';

        // Actualizar solo si no se deben excluir los valores
        if (!$excluirTipoProblema) {
            $tipoProblema = $request->input('Tipo_Problema');
        } else {
            $tipoProblema = $tipoProblemaActual;
        }

        if (!$excluirAcCorrectiva) {
            $acCorrectiva = $request->input('Ac_Correctiva');
        } else {
            $acCorrectiva = $acCorrectivaActual;
        }

        // Actualizar los campos necesarios (ajusta según tu lógica)
        $screenPrint->update([
            'Cliente' => $request->input('Cliente'),
            'Estilo' => $request->input('Estilo'),
            'OP_Defec' => $request->input('OP_Defec'),
            'Tecnico' => $request->input('Tecnico'),
            'Color' => $request->input('Color'),
            'Num_Grafico' => $request->input('Num_Grafico'),
            'Tipo_Maquina' => $request->input('Tipo_Maquina'),
            'LeyendaSprint' => $request->input('LeyendaSprint'),
            'Tecnica' => $request->input('Tecnica'),
            'Fibras' => $request->input('Fibras'),
            'Porcen_Fibra' => $request->input('Porcen_Fibra'),
            'Piezas_Auditar' => $request->input('Piezas_Auditar'),
            'Tipo_Problema' => $tipoProblema,
            'Num_Problemas' => $request->input('Num_Problemas'),
            'Ac_Correctiva' => $acCorrectiva,
            'Status' => 'Update', // Puedes ajustar este campo según tus necesidades
        ]);

        // Puedes realizar acciones adicionales si es necesario después de actualizar el registro

        return response()->json(['mensaje' => 'Datos actualizados exitosamente']);
    }
    public function obtenerOpcionesACCorrectiva()
    {
        $data = AccionCorrectScreen::pluck('AccionCorrectiva');

        return response()->json($data);
    }

    public function obtenerOpcionesTipoProblema()
    {
        $data = OpcionesDefectosScreen::pluck('Defecto');

        return response()->json($data);
    }
    public function actualizarStatScrin($id, Request $request)
    {
        // Buscar el registro por id
        $screenPrint = ScreenPrint::find($id);

        // Verificar si el registro existe
        if ($screenPrint) {
            // Actualizar el estado
            $screenPrint->Status = $request->input('status');

            // Guardar los cambios
            $screenPrint->save();

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Estado actualizado con éxito'], 200);
        } else {
            // Devolver una respuesta de error
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
    }
    public function horno_banda()
    {
        // Obtener la fecha actual
        $today = Carbon::today();

        // Filtrar registros con la fecha actual
        $data = Horno_Banda::whereDate('created_at', $today)->get();
        return response()->json($data);
    }
    public function savedatahorno_banda(Request $request)
    {
        try {
            // Validar los datos si es necesario
            $request->validate([
                'Temperatura' => 'required',
                'Velocidad' => 'required',
            ]);

            // Obtener los datos del request
            $temperatura = $request->input('Temperatura');
            $velocidad = $request->input('Velocidad');

            // Crear una nueva instancia del modelo Horno_Banda
            $hornoBanda = new Horno_Banda();

            // Asignar los valores a las columnas del modelo
            $hornoBanda->Tem_Horno = $temperatura;
            $hornoBanda->Vel_Banda = $velocidad;

            // Guardar el modelo en la base de datos
            $hornoBanda->save();

            // Log de los datos recibidos
            Log::info('Datos recibidos - Temperatura: ' . $temperatura . ', Velocidad: ' . $velocidad);

            // Puedes devolver una respuesta JSON si es necesario
            $data = [
                'success' => true,
                'message' => 'Datos guardados correctamente.',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            // Log de errores
            Log::error('Error en savedatahorno_banda: ' . $e->getMessage());

            // Manejar el error y devolver una respuesta JSON
            $data = [
                'success' => false,
                'message' => 'Error al procesar los datos.',
            ];

            return response()->json($data, 500);
        }
    }
    public function PorcenScreen()
    {
        try {
            // Obtener la fecha actual
            $today = Carbon::today();

            // Obtener la suma de piezas auditadas para el día actual
            $totalRegistros = ScreenPrint::whereDate('created_at', $today)
                ->sum('Piezas_Auditar');

            // Obtener todos los registros con Tipo_Problema diferente de 'N/A' para el día actual
            $registrosConDefectos = ScreenPrint::whereDate('created_at', $today)
                ->where('Num_Problemas', '<>', 'N/A')
                ->get();

            // Contar el número total de defectos en todos los registros
            $totalDefectos = 0;
            foreach ($registrosConDefectos as $registro) {
                $tiposProblema = explode(',', $registro->Num_Problemas);
                // Sumar los valores numéricos de los tipos de problema
                $totalDefectos += array_sum(array_map('intval', $tiposProblema));
            }


            // Calcular el porcentaje
            $porcentaje = $totalRegistros > 0 ? ($totalDefectos / $totalRegistros) * 100 : 0;

            $data = [
                'success' => true,
                'totalRegistros' => $totalRegistros,
                'totalDefectos' => $totalDefectos,
                'porcentaje' => $porcentaje,
                'message' => 'Datos calculados correctamente.',
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error en PorcenScreen: ' . $e->getMessage());

            $data = [
                'success' => false,
                'message' => 'Error al calcular los datos.',
            ];

            return response()->json($data, 500);
        }

}
}
