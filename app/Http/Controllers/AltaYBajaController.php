<?php

namespace App\Http\Controllers;
use App\Models\CategoriaTipoProblema;
use App\Models\CategoriaUtility;
use App\Models\CategoriaTeamLeader;
use App\Models\CategoriaTecnico;
use Illuminate\Http\Request; 

class AltaYBajaController extends Controller
{
    public function altaYbaja()
    {
        $pageSlug ='';
        $categoriaTipoProblemaProceso = CategoriaTipoProblema::where('area', 'proceso')->get();
        $categoriaTipoProblemaPlayera = CategoriaTipoProblema::where('area', 'playera')->get();
        $categoriaTipoProblemaEmpaque = CategoriaTipoProblema::where('area', 'empaque')->get();
        $categoriaUtility = CategoriaUtility::all();
        $categoriaResponsable = CategoriaTeamLeader::all();
        $categoriaTecnico = CategoriaTecnico::all();
        return view('altaYbaja', compact('pageSlug', 'categoriaTipoProblemaProceso', 'categoriaTipoProblemaPlayera', 'categoriaTipoProblemaEmpaque',
                'categoriaUtility', 'categoriaResponsable', 'categoriaTecnico'));
    }

    public function crearDefectoProceso(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);

        // Validar si el registro ya existe
        $registroExistente = CategoriaTipoProblema::where('nombre', $nombre)
            ->where('area', 'proceso')
            ->first();

        if ($registroExistente) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'El defecto de proceso ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $categoriaTipoProblema = new CategoriaTipoProblema();
        $categoriaTipoProblema->nombre = $nombre;
        $categoriaTipoProblema->area = 'proceso';
        $categoriaTipoProblema->estado = 1;
        $categoriaTipoProblema->save();

        return redirect()->route('altaYbaja')->with('success', 'Defecto de proceso creado correctamente');
    }

    public function actualizarEstadoDefectoProceso($id)
    {
        $categoriaTipoProblema = CategoriaTipoProblema::findOrFail($id);
        $nuevoEstado = $categoriaTipoProblema->estado == 1 ? 0 : 1;
        $categoriaTipoProblema->estado = $nuevoEstado;
        $categoriaTipoProblema->save();

        $mensaje = $nuevoEstado == 1 ? 'Defecto activado correctamente' : 'Defecto desactivado correctamente';
        $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

        return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
    }

    public function crearDefectoPlayera(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);

        // Validar si el registro ya existe
        $registroExistente = CategoriaTipoProblema::where('nombre', $nombre)
            ->where('area', 'playera')
            ->first();

        if ($registroExistente) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'El defecto de proceso ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $categoriaTipoProblema = new CategoriaTipoProblema();
        $categoriaTipoProblema->nombre = $nombre;
        $categoriaTipoProblema->area = 'playera';
        $categoriaTipoProblema->estado = 1;
        $categoriaTipoProblema->save();

        return redirect()->route('altaYbaja')->with('success', 'Defecto de proceso creado correctamente');
    }

    public function actualizarEstadoDefectoPlayera($id)
    {
        $categoriaTipoProblema = CategoriaTipoProblema::findOrFail($id);
        $nuevoEstado = $categoriaTipoProblema->estado == 1 ? 0 : 1;
        $categoriaTipoProblema->estado = $nuevoEstado;
        $categoriaTipoProblema->save();

        $mensaje = $nuevoEstado == 1 ? 'Defecto activado correctamente' : 'Defecto desactivado correctamente';
        $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

        return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
    }

    public function actualizarEstadoDefectoEmpaque($id)
    {
        $categoriaTipoProblema = CategoriaTipoProblema::findOrFail($id);
        $nuevoEstado = $categoriaTipoProblema->estado == 1 ? 0 : 1;
        $categoriaTipoProblema->estado = $nuevoEstado;
        $categoriaTipoProblema->save();

        $mensaje = $nuevoEstado == 1 ? 'Defecto activado correctamente' : 'Defecto desactivado correctamente';
        $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

        return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
    }

    public function crearDefectoEmpaque(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);

        // Validar si el registro ya existe
        $registroExistente = CategoriaTipoProblema::where('nombre', $nombre)
            ->where('area', 'empaque')
            ->first();

        if ($registroExistente) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'El defecto de proceso ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $categoriaTipoProblema = new CategoriaTipoProblema();
        $categoriaTipoProblema->nombre = $nombre;
        $categoriaTipoProblema->area = 'empaque';
        $categoriaTipoProblema->estado = 1;
        $categoriaTipoProblema->save();

        return redirect()->route('altaYbaja')->with('success', 'Defecto de proceso creado correctamente');
    }

    public function actualizarEstadoUtility(Request $request, $id)
    {
        $dato  = CategoriaUtility::findOrFail($id);
        if($request->input('action') == 'cambiarEstado') {
            $nuevoEstado = $dato ->estado == 1 ? 0 : 1;
            $dato ->estado = $nuevoEstado;
            $dato ->save();

            $mensaje = $nuevoEstado == 1 ? 'Utility activado correctamente' : 'Utility desactivado correctamente';
            $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        } elseif ($request->input('action') == 'cambiarPlanta') {
            if ($dato->planta == 'Intimark1') {
                $dato->planta = 'Intimark2';
                $mensaje = 'Cambio de Utility a Planta 2 correctamente';
            } else {
                $dato->planta = 'Intimark1';
                $mensaje = 'Cambio de Utility a Planta 1 correctamente';
            }
            $dato->save();
            $tipoMensaje = 'success';
            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        }
    }

    public function crearUtility(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);
        $numeroEmpleado = $request->numero_empleado;
        $planta = $request->planta;
        //dd($numeroEmpleado);

        // Validar si el registro ya existe
        $registroExistenteNombre = CategoriaUtility::where('nombre', $nombre)
            ->first();
        $registroExistenteNoEmpleado = CategoriaUtility::where('numero_empleado', $numeroEmpleado)
            ->first();
        if ($registroExistenteNombre || $registroExistenteNoEmpleado) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'Utility ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $categoriaUtility = new CategoriaUtility();
        $categoriaUtility->nombre = $nombre;
        $categoriaUtility->numero_empleado = $numeroEmpleado;
        $categoriaUtility->planta = $planta;
        $categoriaUtility->estado = 1;
        $categoriaUtility->save();

        return redirect()->route('altaYbaja')->with('success', 'Defecto de proceso creado correctamente');
    }

    public function actualizarEstadoResponsable(Request $request, $id)
    {
        $dato  = CategoriaTeamLeader::findOrFail($id);
        if($request->input('action') == 'cambiarEstado') {
            $nuevoEstado = $dato ->estatus == 1 ? 0 : 1;
            $dato ->estatus = $nuevoEstado;
            $dato ->save();

            $mensaje = $nuevoEstado == 1 ? 'Responsable activado correctamente' : 'Responsable desactivado correctamente';
            $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        } elseif ($request->input('action') == 'cambiarPlanta') {
            if ($dato->planta == 'Intimark1') {
                $dato->planta = 'Intimark2';
                $mensaje = 'Cambio de Utility a Planta 2 correctamente';
            } else {
                $dato->planta = 'Intimark1';
                $mensaje = 'Cambio de Utility a Planta 1 correctamente';
            }
            $dato->save();
            $tipoMensaje = 'success';
            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        }
    }

    public function crearResponsable(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);
        $numeroEmpleado = $request->numero_empleado;
        $planta = $request->planta;
        //dd($numeroEmpleado);

        // Validar si el registro ya existe
        $registroExistenteNombre = CategoriaTeamLeader::where('nombre', $nombre)
            ->first();
        $registroExistenteNoEmpleado = CategoriaTeamLeader::where('numero_empleado', $numeroEmpleado)
            ->first();
        if ($registroExistenteNombre || $registroExistenteNoEmpleado) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'Gerente de produccion ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $nuevoGerente = new CategoriaTeamLeader();
        $nuevoGerente->nombre = $nombre;
        $nuevoGerente->numero_empleado = $numeroEmpleado;
        $nuevoGerente->planta = $planta;
        $nuevoGerente->estatus = 1;
        $nuevoGerente->jefe_produccion = 1;
        $nuevoGerente->save();

        return redirect()->route('altaYbaja')->with('success', 'Gerente de Produccion creado correctamente');
    }

    public function actualizarEstadoTecnico(Request $request, $id)
    {
        $dato  = CategoriaTecnico::findOrFail($id);
        if($request->input('action') == 'cambiarEstado') {
            $nuevoEstado = $dato ->estado == 1 ? 0 : 1;
            $dato ->estado = $nuevoEstado;
            $dato ->save();

            $mensaje = $nuevoEstado == 1 ? 'Tecnico de Corte activado correctamente' : 'Tecnico de Corte desactivado correctamente';
            $tipoMensaje = $nuevoEstado == 1 ? 'success' : 'danger';

            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        } elseif ($request->input('action') == 'cambiarPlanta') {
            if ($dato->planta == 'Intimark1') {
                $dato->planta = 'Intimark2';
                $mensaje = 'Cambio de Tecnico de Corte a Planta 2 correctamente';
            } else {
                $dato->planta = 'Intimark1';
                $mensaje = 'Cambio de Tecnico de Corte a Planta 1 correctamente';
            }
            $dato->save();
            $tipoMensaje = 'success';
            return redirect()->route('altaYbaja')->with($tipoMensaje, $mensaje);
        }
    }

    public function crearTecnico(Request $request)
    {
        // Convertir el texto introducido a mayúsculas
        $nombre = strtoupper($request->nombre);
        $numeroEmpleado = $request->numero_empleado;
        $planta = $request->planta;
        //dd($numeroEmpleado);

        // Validar si el registro ya existe
        $registroExistenteNombre = CategoriaTecnico::where('nombre', $nombre)
            ->first();
        $registroExistenteNoEmpleado = CategoriaTecnico::where('numero_empleado', $numeroEmpleado)
            ->first();
        if ($registroExistenteNombre || $registroExistenteNoEmpleado) {
            // Si el registro ya existe, redirigir con un mensaje de advertencia
            return redirect()->route('altaYbaja')->with('warning', 'Tecnico de Corte ya existe.');
        }

        // Si no existe, crear el nuevo registro
        $categoriaUtility = new CategoriaTecnico();
        $categoriaUtility->nombre = $nombre;
        $categoriaUtility->numero_empleado = $numeroEmpleado;
        $categoriaUtility->planta = $planta;
        $categoriaUtility->estado = 1;
        $categoriaUtility->save();

        return redirect()->route('altaYbaja')->with('success', 'Tecnico de Corte creado correctamente');
    }

}
