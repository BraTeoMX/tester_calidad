<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CategoriaAuditor;
use App\Models\CategoriaTecnico;
use App\Models\AuditoriaAQL;
use App\Models\CategoriaColor;
use App\Models\CategoriaEstilo;
use App\Models\CategoriaNoRecibo;
use App\Models\CategoriaTallaCantidad;
use App\Models\CategoriaTamañoMuestra;
use App\Models\CategoriaMaterialRelajado;
use App\Models\CategoriaDefectoCorte;
use App\Models\EncabezadoAuditoriaCorte;
use App\Models\AuditoriaMarcada;
use App\Models\CategoriaParteCorte;

use App\Models\DatoAX;
use App\Models\EvaluacionCorte;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon

class EvaluacionCorteController extends Controller
{

    // Método privado para cargar las categorías
    private function cargarCategorias() {
        $fechaActual = Carbon::now()->toDateString();
        return [ 
            'CategoriaColor' => CategoriaColor::where('estado', 1)->get(),
            'CategoriaEstilo' => CategoriaEstilo::where('estado', 1)->get(),
            'CategoriaNoRecibo' => CategoriaNoRecibo::where('estado', 1)->get(),
            'CategoriaTallaCantidad' => CategoriaTallaCantidad::where('estado', 1)->get(),
            'CategoriaTamañoMuestra' => CategoriaTamañoMuestra::where('estado', 1)->get(),
            'CategoriaMaterialRelajado' => CategoriaMaterialRelajado::where('estado', 1)->get(),
            'CategoriaAuditor' => CategoriaAuditor::where('estado', 1)->get(),
            'CategoriaTecnico' => CategoriaTecnico::where('estado', 1)->get(),
            'CategoriaDefectoCorte' => CategoriaDefectoCorte::where('estado', 1)->get(),
            'CategoriaParteCorte' => CategoriaParteCorte::where('estado', 1)->get(),
            'DatoAX' => DatoAX::where(function($query) {
                $query->whereNull('estatus')
                      ->orWhere('estatus', '');
            })->get(),
            'DatoAXNoIniciado' => DatoAX::whereNotIn('estatus', ['fin'])
                           ->where(function ($query) {
                               $query->whereNull('estatus')
                                     ->orWhere('estatus', '');
                           })
                           ->get(),
            'DatoAXProceso' => DatoAX::whereNotIn('estatus', ['fin'])
                           ->whereNotNull('estatus')
                           ->whereNotIn('estatus', [''])
                           ->with('auditoriasMarcadas')
                           ->get(),
            'DatoAXFin' => DatoAX::where('estatus', 'fin')->get(),
            'EncabezadoAuditoriaCorte' => EncabezadoAuditoriaCorte::all(),
            'auditoriasMarcadas' => AuditoriaMarcada::all(),
            'procesoActualAQL' => AuditoriaAQL::where('estatus', NULL)
                ->where('area', 'AUDITORIA EN PROCESO')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor')
                ->distinct()
                ->get(),
            'procesoFinalAQL' => AuditoriaAQL::where('estatus', 1)
                ->where('area', 'AUDITORIA EN PROCESO')
                ->whereDate('created_at', $fechaActual)
                ->select('area','modulo','estilo', 'team_leader', 'turno', 'auditor')
                ->distinct()
                ->get(),
        ];
    }

    public function obtenerEstilo(Request $request) 
    {
        $orden = $request->input('orden_id');
        $encabezado = EncabezadoAuditoriaCorte::where('orden_id', $orden)->first();

        if (!$encabezado) {
            return response()->json(['error' => 'No se encontró el encabezado para la orden especificada']);
        }

        $datos = [
            'estilo' => $encabezado->estilo_id,
            'evento' => $encabezado->evento,
            'cliente' => $encabezado->cliente_id // Agregar el dato del cliente
        ];

        return response()->json($datos);
    } 

    public function inicioEvaluacionCorte()
    {
        $pageSlug ='';
        $categorias = $this->cargarCategorias();


        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        return view('evaluacionCorte.inicioEvaluacionCorte', array_merge($categorias, ['mesesEnEspanol' => $mesesEnEspanol, 'pageSlug' => $pageSlug]));
    }

    public function evaluaciondeCorte($ordenId, $eventoId)
    {
        $pageSlug ='';
        $categorias = $this->cargarCategorias();
        $auditorDato = Auth::user()->name;
        //dd($userName);
        $registroEvaluacionCorte = EvaluacionCorte::where('orden_id', $ordenId)
            ->where('evento', $eventoId)
            ->orderBy('created_at', 'desc')
            ->orderBy('descripcion_parte', 'asc')
            ->get();
        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorte::where('orden_id', $ordenId)
            ->where('evento', $eventoId)
            ->first();
        //dd($registroEvaluacionCorte->all()); 
        $mesesEnEspanol = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        
        return view('evaluacionCorte.evaluaciondeCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol, 
            'pageSlug' => $pageSlug, 
            'registroEvaluacionCorte' => $registroEvaluacionCorte,
            'encabezadoAuditoriaCorte' => $encabezadoAuditoriaCorte,
            'auditorDato' => $auditorDato]));
    }

    public function crearCategoriaParteCorte(Request $request)
    {
        // Obtener el nuevo nombre enviado desde el frontend
        $nuevoNombre = $request->input('nuevaTecnica');

        // Si se seleccionó "OTRO" y se ingresó un nuevo nombre
        if ($request->has('nuevaTecnica')) {
            $nuevaCategoria = new CategoriaParteCorte();
            $nuevaCategoria->nombre = $nuevoNombre;
            $nuevaCategoria->estado = 1; // O cualquier otro valor que necesites
            $nuevaCategoria->save();

            return response()->json(['success' => 'La nueva opción se ha guardado correctamente', 'nombre' => $nuevaCategoria->nombre]);
        }

        // Si no se ingresó un nuevo nombre, devuelve un error
        return response()->json(['error' => 'No se ha ingresado un nuevo nombre'], 400);
    }

    public function formAltaEvaluacionCortes(Request $request) 
    {
        $pageSlug ='';
        // Validar los datos del formulario si es necesario
        // Obtener el ID seleccionado desde el formulario
        $ordenId = $request->input('orden');
        $eventoId = $request->input('evento');
        $estilo = $request->input('estilo');
        //dd($ordenId, $eventoId, $estilo);
        

        return redirect()->route('evaluacionCorte.evaluaciondeCorte', ['orden' => $ordenId, 'evento' => $eventoId])->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formRegistro(Request $request)
    {
        $pageSlug ='';
        // Obtener el ID seleccionado desde el formulario
        $ordenId = $request->input('orden');
        $eventoId = $request->input('evento');
        $estilo = $request->input('estilo');
        //dd($ordenId, $eventoId, $estilo, $request->all());
        
        //dd($estilo, $request->all());
        $encabezadoAuditoriaCorte = EncabezadoAuditoriaCorte::where('orden_id', $ordenId)
            ->where('evento', $eventoId)
            ->first();
        //dd($encabezadoAuditoriaCorte);

        $evaluacionCorte = new EvaluacionCorte();
        $evaluacionCorte->orden_id = $ordenId;
        $evaluacionCorte->evento = $eventoId;
        $evaluacionCorte->estilo_id = $estilo;
        $evaluacionCorte->descripcion_parte = $request->input('descripcion_parte');
        $evaluacionCorte->izquierda_x = $request->input('izquierda_x');
        $evaluacionCorte->izquierda_y = $request->input('izquierda_y');
        $evaluacionCorte->derecha_x = $request->input('derecha_x');
        $evaluacionCorte->derecha_y = $request->input('derecha_y'); 
        $evaluacionCorte->auditorDato = $request->input('auditorDato'); 
        
        $evaluacionCorte->save();

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formActualizacionEliminacionEvaluacionCorte($id, Request $request){
        $pageSlug ='';
        $action = $request->input('action');
        //$id = $request->input('id');
        //dd($request->all());
        if($action == 'update'){
            $actualizarRegistro = EvaluacionCorte::where('id', $id)->first();
            $actualizarRegistro->descripcion_parte = $request->input('descripcion_parte');
            $actualizarRegistro->izquierda_x = $request->input('izquierda_x');
            $actualizarRegistro->izquierda_y = $request->input('izquierda_y');
            $actualizarRegistro->derecha_x = $request->input('derecha_x');
            $actualizarRegistro->derecha_y = $request->input('derecha_y');
            $actualizarRegistro->save();

            //dd($request->all(), $actualizarRegistro, $id);
            return back()->with('sobre-escribir', 'Registro actualizado correctamente.')->with('pageSlug', $pageSlug);

            // Lógica para actualizar el registro
        } elseif ($action == 'delete'){
            // Lógica para eliminar el registro
            EvaluacionCorte::where('id', $id)->delete();
            return back()->with('error', 'Registro eliminado.')->with('pageSlug', $pageSlug);
        }

        //dd($request->all(), $request->input('descripcion_parte1'), $id);
        return back()->with('cambio-estatus', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formFinalizarEventoCorte(Request $request)
    {
        $pageSlug ='';
        // Obtener el ID seleccionado desde el formulario
        $ordenId = $request->input('orden');
        $eventoId = $request->input('evento');
        //dd($ordenId, $eventoId, $estilo, $request->all());
        
        //dd($estilo, $request->all());
        $evaluacionCorte = EncabezadoAuditoriaCorte::where('orden_id', $ordenId)
            ->where('evento', $eventoId)
            ->first();
        $evaluacionCorte->estatus_evaluacion_corte = 1;
        $evaluacionCorte->save();
        //dd($evaluacionCorte, $ordenId, $eventoId);

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

}
