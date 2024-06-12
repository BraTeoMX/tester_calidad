<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_empleado';
        
        // Verificar el estatus del usuario
        $user = \App\Models\User::where($fieldType, $input['email'])->first();
        //dd($user);
        if ($user && $user->Estatus == 'Baja') {
            return redirect()->route('login')
                ->with('error', 'Usuario dado de Baja, consulta con el gerente de calidad.');
        }

        if (auth()->attempt([$fieldType => $input['email'], 'password' => $input['password']])) {
            return redirect()->route('home');
        } else {
            return redirect()->route('login')
                ->with('error', 'Estas credenciales no coinciden con nuestros registros.');
        }
    }
}