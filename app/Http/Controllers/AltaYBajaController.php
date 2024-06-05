<?php

namespace App\Http\Controllers;
use App\Models\CategoriaTipoProblema;
use Illuminate\Http\Request; 

class AltaYBajaController extends Controller
{
    public function altaYbaja()
    {
        $pageSlug ='';
        $categoriaTipoProblemaProceso = CategoriaTipoProblema::where('area', 'proceso')->get();
        $categoriaTipoProblemaPlayera = CategoriaTipoProblema::where('area', 'playera')->get();
        $categoriaTipoProblemaEmpaque = CategoriaTipoProblema::where('area', 'empaque')->get();
        return view('altaYbaja', compact('pageSlug', 'categoriaTipoProblemaProceso', 'categoriaTipoProblemaPlayera', 'categoriaTipoProblemaEmpaque'));
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

}
