<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public function auth(Request $request)
    {
        $request->validate([
            'cpf' => 'required',
            'password' => 'required',
        ]);

        $cpf = $request->input('cpf');
        $password = $request->input('password');

        $user = \App\Models\User::where('cpf', $cpf)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            $token = $user->createToken(env('JWT_SECRET'))->plainTextToken;
            return response()->json(['token' => $token], 200);
        } else {
            $usuario = \App\Models\UserSistema::whereHas('pessoa', function ($query) use ($cpf) {
                $query->where('nr_cpf', $cpf);
            })->first();

            if ($usuario && $usuario->ds_senha === md5($password)) {
                $pessoa = $usuario->pessoa;

                $grava = [
                    'name' => $pessoa->nm_pessoa,
                    'email' => $pessoa->ds_email,
                    'password' => bcrypt($password),
                    'cpf' => $pessoa->nr_cpf,
                    'pessoa_cd_pessoa' => intval($usuario->cd_usuario_insert)
                ];

                $user = \App\Models\User::create($grava);
                $token = $user->createToken(env('JWT_SECRET'))->plainTextToken;

                return response()->json(['token' => $token], 200);
            }
        }

        return response()->json(['error' => 'Credenciais inválidas.'], 401);
    }


}
