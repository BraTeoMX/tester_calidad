<?php

namespace App\Http\Controllers;
use App\Models\CategoriaTipoProblema;

class AltaYBajaController extends Controller
{
    public function altaYbaja()
    {
        $pageSlug ='';
        $categoriaTipoProblemaProceso = CategoriaTipoProblema::where('area', 'proceso')->get();
        $categoriaTipoProblemaPlayera = CategoriaTipoProblema::where('area', 'playera')->get();
        return view('altaYbaja', compact('pageSlug', 'categoriaTipoProblemaProceso', 'categoriaTipoProblemaPlayera'));
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

}
