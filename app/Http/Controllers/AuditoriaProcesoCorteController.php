<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CategoriaAuditor;
use App\Models\CategoriaTecnico;
use App\Models\CategoriaCliente;
use App\Models\CategoriaColor;
use App\Models\CategoriaEstilo;
use App\Models\CategoriaNoRecibo;
use App\Models\CategoriaTallaCantidad;
use App\Models\CategoriaTamañoMuestra;
use App\Models\CategoriaDefecto;
use App\Models\CategoriaTipoDefecto;
use App\Models\CategoriaMaterialRelajado;
use App\Models\CategoriaDefectoCorte;
use App\Models\EncabezadoAuditoriaCorte;
use App\Models\AuditoriaMarcada;
use App\Models\CategoriaParteCorte;
use App\Models\CategoriaAccionCorrectiva;
use App\Models\AuditoriaProcesoCorte;
use App\Models\AuditoriaAQL;


use App\Exports\DatosExport;
use App\Models\DatoAX;
use App\Models\EvaluacionCorte;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon

class AuditoriaProcesoCorteController extends Controller
{

    // Método privado para cargar las categorías
    private function cargarCategorias()
    {
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
            'DatoAX' => DatoAX::where(function ($query) {
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
            'CategoriaDefectoCorteTendido' => CategoriaDefectoCorte::where('estado', 1)->where('area', "tendido")->get(),
            'CategoriaDefectoCorteLectraSellado' => CategoriaDefectoCorte::where('estado', 1)
                ->where(function ($query) {
                    $query->where('area', 'corte lectra')
                        ->orWhere('area', 'sellado');
                })
                ->get(),
            'CategoriaAccionCorrectiva' => CategoriaAccionCorrectiva::where('estado', 1)->get(),

        ];
    }


    public function inicioAuditoriaProcesoCorte()
    {
        $pageSlug = '';
        $categorias = $this->cargarCategorias();


        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];

        return view('auditoriaProcesoCorte.inicioAuditoriaProcesoCorte', array_merge($categorias, ['mesesEnEspanol' => $mesesEnEspanol, 'pageSlug' => $pageSlug]));
    }

    public function altaProcesoCorte(Request $request)
    {
        $pageSlug = '';
        $categorias = $this->cargarCategorias();
        $auditorDato = Auth::user()->name;
        //dd($userName);

        //dd($registroEvaluacionCorte->all()); 
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];


        return view('auditoriaProcesoCorte.altaProcesoCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'auditorDato' => $auditorDato
        ]));
    }

    public function auditoriaProcesoCorte(Request $request)
    {
        $pageSlug = '';
        $mesesEnEspanol = [
            'Enero',
            'Febrero',
            'Marzo',
            'Abril',
            'Mayo',
            'Junio',
            'Julio',
            'Agosto',
            'Septiembre',
            'Octubre',
            'Noviembre',
            'Diciembre'
        ];
        $categorias = $this->cargarCategorias();
        $auditorDato = Auth::user()->name;
        // Obtener los datos de la solicitud
        $data = $request->all();
        // Asegurarse de que la variable $data esté definida
        $data = $data ?? [];

        $fechaActual = Carbon::now()->toDateString();

        $mostrarRegistro = AuditoriaProcesoCorte::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->get();

        $registros = AuditoriaProcesoCorte::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->selectRaw('COALESCE(SUM(cantidad_auditada), 0) as total_auditada, COALESCE(SUM(cantidad_rechazada), 0) as total_rechazada')
            ->first();
        $total_auditada = $registros->total_auditada ?? 0;
        $total_rechazada = $registros->total_rechazada ?? 0;
        $total_porcentaje = $total_auditada != 0 ? ($total_rechazada / $total_auditada) * 100 : 0;


        $registrosIndividual = AuditoriaProcesoCorte::whereDate('created_at', $fechaActual)
            ->where('area', $data['area'])
            ->selectRaw('nombre_1, nombre_2, SUM(cantidad_auditada) as total_auditada, SUM(cantidad_rechazada) as total_rechazada, orden_id, estilo_id')
            ->groupBy('nombre_1', 'nombre_2', 'orden_id', 'estilo_id')
            ->get();

        // Inicializa las variables para evitar errores
        $total_auditadaIndividual = 0;
        $total_rechazadaIndividual = 0;

        // Calcula la suma total solo si hay registros individuales
        if ($registrosIndividual->isNotEmpty()) {
            $total_auditadaIndividual = $registrosIndividual->sum('total_auditada');
            $total_rechazadaIndividual = $registrosIndividual->sum('total_rechazada');
        }
        //dd($registros, $fechaActual);
        // Calcula el porcentaje total
        $total_porcentajeIndividual = $total_auditadaIndividual != 0 ? ($total_rechazadaIndividual / $total_auditadaIndividual) * 100 : 0;




        return view('auditoriaProcesoCorte.auditoriaProcesoCorte', array_merge($categorias, [
            'mesesEnEspanol' => $mesesEnEspanol,
            'pageSlug' => $pageSlug,
            'data' => $data,
            'total_auditada' => $total_auditada,
            'total_rechazada' => $total_rechazada,
            'total_porcentaje' => $total_porcentaje,
            'registrosIndividual' => $registrosIndividual,
            'total_auditadaIndividual' => $total_auditadaIndividual,
            'total_rechazadaIndividual' => $total_rechazadaIndividual,
            'total_porcentajeIndividual' => $total_porcentajeIndividual,
            'mostrarRegistro' => $mostrarRegistro,
            'auditorDato' => $auditorDato
        ]));
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


    public function formAltaProcesoCorte(Request $request)
    {
        $pageSlug = '';

        $data = [
            'area' => $request->area,
            'estilo' => $request->estilo,
            'supervisor' => $request->supervisor,
            'auditor' => $request->auditor,
            'turno' => $request->turno,
        ];
        //dd($data);
        return redirect()->route('auditoriaProcesoCorte.auditoriaProcesoCorte', $data)->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formRegistroAuditoriaProcesoCorte(Request $request)
    {
        $pageSlug = '';
        // Obtener el ID seleccionado desde el formulario
        //dd($request->all());
        $procesoCorte = new AuditoriaProcesoCorte();
        $procesoCorte->area = $request->area;
        $procesoCorte->estilo = $request->estilo;
        $procesoCorte->orden_id = $request->orden_id;
        $procesoCorte->estilo_id = $request->estilo_id;
        $procesoCorte->cliente = $request->cliente_id;
        $procesoCorte->supervisor_corte = $request->supervisor_corte;
        $procesoCorte->auditor = $request->auditor;
        $procesoCorte->turno = $request->turno;
        $procesoCorte->nombre_1 = $request->nombre_1;
        $procesoCorte->nombre_2 = $request->nombre_2;
        $procesoCorte->operacion = $request->operacion;
        $procesoCorte->mesa = $request->mesa;
        $procesoCorte->cantidad_auditada = $request->cantidad_auditada;
        $procesoCorte->cantidad_rechazada = $request->cantidad_rechazada;
        $procesoCorte->tp = $request->tp;
        $procesoCorte->ac = $request->ac;

        $procesoCorte->save();

        return back()->with('success', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formActualizacionEliminacionEvaluacionCorte($id, Request $request)
    {
        $pageSlug = '';
        $action = $request->input('action');
        //$id = $request->input('id');
        //dd($request->all());
        if ($action == 'update') {
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
        } elseif ($action == 'delete') {
            // Lógica para eliminar el registro
            EvaluacionCorte::where('id', $id)->delete();
            return back()->with('error', 'Registro eliminado.')->with('pageSlug', $pageSlug);
        }

        //dd($request->all(), $request->input('descripcion_parte1'), $id);
        return back()->with('cambio-estatus', 'Datos guardados correctamente.')->with('pageSlug', $pageSlug);
    }

    public function formFinalizarEventoCorte(Request $request)
    {
        $pageSlug = '';
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
