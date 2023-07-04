<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSistema;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

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

    public function username()
    {
        return 'cpf';
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

        $cpf = $request->input('cpf');
        $password = $request->input('password');
        $credentials = $request->only('cpf', 'password');

        // Check if the user is already authenticated
        if (Auth::attempt($credentials)) {
            // Autenticação bem-sucedida, redirecionar para a página desejada
            return $this->sendLoginResponse($request);
        }

        // Verificar se o usuário já existe na base
        $existingUser = User::where('cpf', $cpf)->first();

        if ($existingUser) {
            // Usuário já existe, executar função de login normal
            //dd('Usuário existente:', $existingUser);
            return $this->sendLoginResponse($request);
        }

        // Usuário não existe, prosseguir com o cadastro
        $usuario = UserSistema::whereHas('pessoa', function ($query) use ($cpf) {
            $query->where('nr_cpf', $cpf);
        })->first();

        if ($usuario && $usuario->ds_senha === md5($password)) {
            $pessoa = $usuario->pessoa;

            // Create a new user instance
            $user = new User();
            $user->name = $pessoa->nm_pessoa;
            $user->email = $pessoa->ds_email ? $pessoa->ds_email : intval($usuario->cd_usuario_insert) . "@infoconsig.com.br";
            $user->password = bcrypt($password);
            $user->cpf = $pessoa->nr_cpf;
            $user->pessoa_cd_pessoa = intval($usuario->cd_usuario_insert);
            $user->save();

            Auth::login($user);

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }



}
