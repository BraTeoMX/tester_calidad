<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $turnos = \App\Models\Turno::all();
        return view('turnos.index', compact('turnos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('turnos.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'horarios' => 'required|array',
            'planta' => 'required|integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $turno = new \App\Models\Turno();
        $turno->nombre = $request->nombre;
        $turno->planta = $request->planta;
        $turno->estatus = 1; // Default active on create
        $turno->horario_semanal = $request->horarios;
        $turno->save();

        return response()->json(['success' => true, 'message' => 'Turno creado correctamente.', 'data' => $turno]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(\App\Models\Turno::findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $turno = \App\Models\Turno::findOrFail($id);
        return response()->json($turno);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'horarios' => 'required|array',
            'planta' => 'required|integer|in:0,1,2',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $turno = \App\Models\Turno::findOrFail($id);
        $turno->nombre = $request->nombre;
        $turno->planta = $request->planta;
        $turno->horario_semanal = $request->horarios;
        $turno->save();

        return response()->json(['success' => true, 'message' => 'Turno actualizado correctamente.', 'data' => $turno]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $turno = \App\Models\Turno::findOrFail($id);
        $turno->delete();
        return response()->json(['success' => true, 'message' => 'Turno eliminado correctamente.']);
    }

    public function toggleStatus($id)
    {
        $turno = \App\Models\Turno::findOrFail($id);
        $turno->estatus = !$turno->estatus;
        $turno->save();

        return response()->json([
            'success' => true,
            'message' => 'Estatus actualizado correctamente.',
            'nuevo_estatus' => $turno->estatus,
            'label' => $turno->estatus_label,
            'badge_class' => $turno->estatus_badge_class
        ]);
    }
}
