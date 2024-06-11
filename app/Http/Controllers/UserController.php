<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\tipo_auditoria;
use App\Models\puestos;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        $tipoAuditoriaDatos =  tipo_auditoria::all();
        $puestoDatos =  puestos::all();
        return view('users.index', [
            'users' => $model->all(),
            'tipoAuditoriaDatos' => $tipoAuditoriaDatos,
            'puestoDatos' => $puestoDatos,
        ]);
    }

}
