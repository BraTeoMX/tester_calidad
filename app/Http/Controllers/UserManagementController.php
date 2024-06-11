<?php

namespace App\Http\Controllers;

use App\Models\tipo_auditoria;
use App\Models\puestos;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserManagementController extends Controller
{
    public function tipoAuditorias()
    {
        $options = tipo_auditoria::all();

        return response()->json($options);
    }
    public function puestos()
    {
        $options = puestos::all();

        return response()->json($options);
    }
    public function AddUser(Request $request)
{
    // Validar los datos del formulario
    $messages = [
        'no_empleado.unique' => 'El número de empleado ya ha sido tomado.',
        'email.unique' => 'El correo electrónico ya ha sido tomado.',
        // Puedes agregar más mensajes personalizados según tus necesidades
    ];

    // Validar los datos del formulario con mensajes personalizados
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'no_empleado' => [
            'required',
            'string',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('no_empleado', $request->no_empleado);
            }),
        ],
        'email' => [
            'required',
            'string',
            'email',
            Rule::unique('users')->where(function ($query) use ($request) {
                return $query->where('email', $request->email);
            }),
        ],
        'password' => 'required|string|min:8',
        'editPuesto' => 'required|string',
        'tipo_auditoria' => 'required|string',
        'editPlanta' => 'required|string',
        // Agrega las reglas de validación necesarias para los demás campos
    ], $messages);

    // Si la validación falla, retorna un error con un mensaje personalizado
    if ($validator->fails()) {
        return back()->withErrors($validator)->with('error', 'Número de empleado o correo ya existente, intente con otro diferente.')->withInput();
    }

    // Crear un nuevo usuario
    $user = new User([
        'name' => $request->input('name'),
        'no_empleado' => $request->input('no_empleado'),
        'email' => $request->input('email'),
        'password' => Hash::make($request['password']),
        'puesto' => $request->input('editPuesto'),
        'tipo_auditor' => $request->input('tipo_auditoria'),
        'Planta' => $request->input('editPlanta'),
        // Agrega asignación de otros campos
    ]);

    // Guardar el usuario en la base de datos
    $user->save();

    // Redirigir con mensajes de éxito o error
    return back()->with('success', 'Datos guardados correctamente.')->withInput();
}
public function editUser(Request $request)
{
    // Obtener el ID del usuario a través del campo editId
    $userId = $request->input('editId');

    // Buscar el usuario en la base de datos
    $user = User::where('no_empleado', $userId)->first();

    // Verificar si se encontró el usuario
    if (!$user) {
        return back()->with('error', 'Usuario no encontrado.');
    }

    // Validar y actualizar los campos solo si son diferentes de null
    $user->puesto = $request->input('editPuestos') ?? $user->puesto;
    $user->tipo_auditor = $request->input('editTipoAuditoria') ?? $user->tipo_auditor;

    // Verificar si se proporcionó una nueva contraseña
    $newPassword = $request->input('password_update');
    if ($newPassword !== null) {
        $user->password = Hash::make($newPassword);
    }

    // Guardar los cambios
    $user->save();

    return back()->with('success', 'Datos guardados correctamente.');
}

    public function blockUser($noEmpleado)
    {
        // Obtener el usuario por no_empleado
        $user = User::where('no_empleado', $noEmpleado)->first();

        // Verificar si se encontró el usuario
        if (!$user) {
            return redirect()->back()->with('error', 'Usuario no encontrado.');
        }

        // Verificar si el estado actual es 'Baja'
        if ($user->Estatus == 'Baja') {
            // Cambiar el estado a 'Alta'
            $user->Estatus = 'Alta';
            $user->save();

            return redirect()->back()->with('success', 'Usuario activado correctamente.');
        } else {
            // Cambiar el estado a 'Baja'
            $user->Estatus = 'Baja';
            $user->save();

            return redirect()->back()->with('success', 'Usuario bloqueado correctamente.');
        }
    }


}
