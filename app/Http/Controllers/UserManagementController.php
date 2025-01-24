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
                'nullable', // Hacemos que el correo sea opcional
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
        ], $messages);

        // Si la validación falla, retorna un error con un mensaje personalizado
        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Número de empleado o correo ya existente, intente con otro diferente.')->withInput();
        }

        // Crear un correo ficticio si el campo de correo no está presente en el request
        $email = $request->input('email');
        if (is_null($email) || $email === '') {
            // Obtener el último ID para generar un correo único
            $lastId = User::max('id') ?? 0; // Si no hay usuarios, usamos 0 como base
            $email = ($lastId + 1) . '@auditorx.com';
        }

        // Crear un nuevo usuario
        $user = new User([
            'name' => $request->input('name'),
            'no_empleado' => $request->input('no_empleado'),
            'email' => $email, // Usar el correo proporcionado o generado automáticamente
            'password' => Hash::make($request->input('password')),
            'puesto' => $request->input('editPuesto'),
            'tipo_auditor' => $request->input('tipo_auditoria'),
            'Planta' => $request->input('editPlanta'),
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
        $user = User::find($userId);

        // Verificar si se encontró el usuario
        if (!$user) {
            return back()->with('error', 'Usuario no encontrado.');
        }

        // Validar si hubo cambios en "name", "no_empleado" o "email"
        $changes = [];
        if ($user->name !== $request->input('editName')) {
            $changes['name'] = $request->input('editName');
        }
        if ($user->no_empleado !== $request->input('editNumeroEmpleado')) {
            $changes['no_empleado'] = $request->input('editNumeroEmpleado');
        }
        if ($user->email !== $request->input('editEmail')) {
            $changes['email'] = $request->input('editEmail');
        }

        // Si hubo cambios, validar duplicados en otros registros
        if (!empty($changes)) {
            $duplicates = [];

            if (isset($changes['name'])) {
                $nameExists = User::where('name', $changes['name'])
                    ->where('id', '!=', $userId)
                    ->exists();
                if ($nameExists) {
                    $duplicates[] = "El nombre '{$changes['name']}' ya está asociado a otro usuario.";
                }
            }

            if (isset($changes['no_empleado'])) {
                $noEmpleadoExists = User::where('no_empleado', $changes['no_empleado'])
                    ->where('id', '!=', $userId)
                    ->exists();
                if ($noEmpleadoExists) {
                    $duplicates[] = "El número de empleado '{$changes['no_empleado']}' ya está asociado a otro usuario.";
                }
            }

            if (isset($changes['email'])) {
                $emailExists = User::where('email', $changes['email'])
                    ->where('id', '!=', $userId)
                    ->exists();
                if ($emailExists) {
                    $duplicates[] = "El correo '{$changes['email']}' ya está asociado a otro usuario.";
                }
            }

            // Si hay duplicados, retornar con los mensajes específicos
            if (!empty($duplicates)) {
                return back()->with('warning', implode('<br>', $duplicates));
            }
        }

        // Actualizar los datos del usuario
        $user->name = $request->input('editName');
        $user->no_empleado = $request->input('editNumeroEmpleado');
        $user->email = $request->input('editEmail');
        $user->planta = $request->input('editPlanta');
        $user->puesto = $request->input('editPuestos');
        $user->tipo_auditor = $request->input('editTipoAuditoria');

        // Verificar si se proporcionó una nueva contraseña
        $newPassword = $request->input('password_update');
        if ($newPassword !== null) {
            $user->password = Hash::make($newPassword);
        }

        // Guardar los cambios
        $user->save();

        return back()->with('success', "Datos guardados correctamente para '{$user->name}'.");
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
