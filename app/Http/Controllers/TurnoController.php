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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'horarios' => 'required|array', // Validar array de horarios Lunes-Domingo
        ]);

        $turno = new \App\Models\Turno();
        $turno->nombre = $request->nombre;

        // Convertir array de horarios a JSON para guardarlo
        // Estructura esperada del request:
        // horarios[1][inicio], horarios[1][fin], etc. (1=Lunes, 7=Domingo)
        $turno->horario_semanal = $request->horarios;

        $turno->save();

        return redirect()->route('turnos.index')->with('success', 'Turno creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        return view('turnos.form', compact('turno'));
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'horarios' => 'required|array',
        ]);

        $turno = \App\Models\Turno::findOrFail($id);
        $turno->nombre = $request->nombre;
        $turno->horario_semanal = $request->horarios; // Laravel cast to object handling? No, model has no casts. Eloquent generic JSON cast?
        // Revisar si el modelo tiene $casts. Si no, hay que hacer json_encode.
        // Asumiendo que el modelo tiene protected $casts = ['horario_semanal' => 'array'];
        // Si no lo tiene, agregarlo es buena práctica.

        $turno->save();

        return redirect()->route('turnos.index')->with('success', 'Turno actualizado correctamente.');
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
        return redirect()->route('turnos.index')->with('success', 'Turno eliminado correctamente.');
    }
}
