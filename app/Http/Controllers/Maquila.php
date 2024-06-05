<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccionCorrectScreen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DatosAX;
use App\Models\Maquilas;
use App\Models\OpcionesDefectosScreen;
use App\Models\Tecnicos;
use Illuminate\Support\Facades\Auth;

class Maquila extends Controller
{
    public function Maquilas(Request $request)
    {

        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('ScreenPlanta2.Maquila', compact('mesesEnEspanol'));
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
    public function viewTableMaquila()
{
    // Obtener la fecha actual
    $today = Carbon::today();

    // Filtrar registros con la fecha actual
    $screen = Maquilas::whereDate('created_at', $today)->get();

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
            'Descripcion'  => $item->Descripcion,
            'Cliente' => $item->Cliente,
            'Estilo' => $item->Estilo,
            'OP_Defec' => $item->OP_Defec,
            'Maquina'  => $item->Maquina,
            'Tecnico' => $item->Tecnico,
            'Corte'  => $item->Corte,
            'Color' => $item->Color,
            'Talla' => $item->Talla,
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

public function SendMaquila(Request $request)
{
    try {
        // Obtener la marca addRowClicked del formulario
        $addRowClicked = $request->input('addRowClicked');

        // Obtener los datos del formulario
        $auditor = $request->input('Auditor');
        $descripcion = $request->input('Descripcion');
        $cliente = $request->input('Cliente');
        $estilo = $request->input('Estilo');
        $opDefec = $request->input('OP_Defec');
        $maquina = $request->input('Maquina');
        $tecnico = $request->input('Tecnico');
        $corte  = $request->input('Corte');
        $color = $request->input('Color');
        $talla = $request->input('Talla');
        $pizasAuditar = $request->input('Piezas_Auditar');
        $tipoProblema = $request->input('Tipo_Problema');
        $numProblemas =  $request->input('Num_Problemas');
        $acCorrectiva = $request->input('Ac_Correctiva');

        // Crear un nuevo registro con 'Nuevo' como valor para la columna 'Status' si ambos botones fueron presionados
        if ($addRowClicked) {
            if (is_null($numProblemas)) {
                $numProblemas = array_fill(0, count($tipoProblema), 0); // Array de ceros del mismo tamaño que $tipoProblema
            }
            $tipoProblemaString = implode(', ', $tipoProblema);
            $numProblemasString = implode(', ', $numProblemas);
            $acCorrectivaString = implode(', ', $acCorrectiva);
            $screenPrint = Maquilas::create([
                'Auditor' => $auditor,
                'Descripcion' => $descripcion,
                'Cliente' => $cliente,
                'Estilo' => $estilo,
                'OP_Defec' => $opDefec,
                'Maquina' => $maquina,
                'Tecnico' => $tecnico,
                'Corte'  => $corte,
                'Color' => $color,
                'Talla' => $talla,
                'Piezas_Auditar' => $pizasAuditar,
                'Tipo_Problema' => $tipoProblemaString, // Guardar como string separado por comas
                'Num_Problemas' => $numProblemasString, // Guardar como string separado por comas
                'Ac_Correctiva' => $acCorrectivaString, // Guardar como string separado por comas
                'Status' => 'Nuevo', // Cambiado de 'Guardado' a 'Nuevo'
            ]);

            // Puedes realizar acciones adicionales si es necesario después de crear el nuevo registro

            return response()->json(['mensaje' => 'Datos guardados exitosamente', 'screenPrint' => $screenPrint]);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


   public function UpdateMaquila(Request $request, $id)
   {
       // Verificar si el registro existe
       $screenPrint = Maquilas::find($id);

       if (!$screenPrint) {
           return response()->json(['mensaje' => 'Registro no encontrado'], 404);
       }

       // Obtener los valores actuales de 'Tipo_Problema' y 'Ac_Correctiva'
       $tipoProblemaActual = $screenPrint->Tipo_Problema;
       $acCorrectivaActual = $screenPrint->Ac_Correctiva;

       // Obtener los nuevos valores del request
       $tipoProblemaNuevo = $request->input('Tipo_Problema');
       $acCorrectivaNuevo = $request->input('Ac_Correctiva');

       // Validar que los nuevos valores no sean indefinidos o vacíos, y no sean 'Seleccione Tipo de Problema' o 'Seleccione Acción Correctiva'
       if ($tipoProblemaNuevo !== null && $tipoProblemaNuevo !== '' && $tipoProblemaNuevo !== 'Seleccione Tipo de Problema') {
           $tipoProblema = $tipoProblemaNuevo;
       } else {
           $tipoProblema = $tipoProblemaActual;
       }

       if ($acCorrectivaNuevo !== null && $acCorrectivaNuevo !== '' && $acCorrectivaNuevo !== 'Seleccione Acción Correctiva') {
           $acCorrectiva = $acCorrectivaNuevo;
       } else {
           $acCorrectiva = $acCorrectivaActual;
       }

       // Actualizar los campos necesarios (ajusta según tu lógica)
       $screenPrint->update([
           'Descripcion'  => $request->input('Descripcion', $screenPrint->Descripcion),
           'Cliente' => $request->input('Cliente', $screenPrint->Cliente),
           'Estilo' => $request->input('Estilo', $screenPrint->Estilo),
           'OP_Defec' => $request->input('OP_Defec', $screenPrint->OP_Defec),
           'Maquina' => $request->input('Maquina', $screenPrint->Maquina),
           'Tecnico' => $request->input('Tecnico', $screenPrint->Tecnico),
           'Corte' => $request->input('Corte', $screenPrint->Corte),
           'Color' => $request->input('Color', $screenPrint->Color),
           'Talla' => $request->input('Talla', $screenPrint->Talla),
           'Piezas_Auditar' => $request->input('Piezas_Auditar', $screenPrint->Piezas_Auditar),
           'Tipo_Problema' => $tipoProblema,
           'Num_Problemas' => $request->input('Num_Problemas', $screenPrint->Num_Problemas),
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
   public function actualizarEstado($id, Request $request)
   {
       // Buscar el registro por id
       $screenPrint = Maquilas::find($id);

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
public function PorcenTotalDefecMaquila()
{
    try {
        // Obtener la fecha actual
        $today = Carbon::today();

        // Obtener la suma de piezas auditadas para el día actual
        $totalRegistros = Maquilas::whereDate('created_at', $today)
            ->sum('Piezas_Auditar');

        // Obtener todos los registros con Tipo_Problema diferente de 'N/A' para el día actual
        $registrosConDefectos = Maquilas::whereDate('created_at', $today)
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
