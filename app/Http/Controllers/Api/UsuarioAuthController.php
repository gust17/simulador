<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuarioAuthController extends Controller
{
    public function auth(Request $request)
    {
        // dd($request->all());
        $validator = \Validator::make($request->all(), [
            'cpf' => 'required|string',
            'password' => 'required|string',
            'registro_unico_servidor' => 'required|string',
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $cpf = $request->cpf;
        $password = $request->password;
        $cd_servidor = $request->registro_unico_servidor;


        $servidor = \App\Models\Servidor::where('nr_matricula', $cd_servidor)
            ->whereHas('pessoa', function ($query) use ($cpf) {
                $query->where('nr_cpf', $cpf);
            })
            ->with('pessoa')
            ->firstOrFail();

        $userSistema = \App\Models\UsuarioAcesso::where("cd_pessoa", $servidor->pessoa->cd_pessoa)
            ->where("cd_servidor", $servidor->cd_servidor)
            ->first();

        if (!$userSistema || $password == $userSistema->ds_senha) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        $jwt_payload = [
            "user_id" => $userSistema->cd_usuario,
            "nome" => $userSistema->pessoa->nm_pessoa,
            "cd_servidor" => $userSistema->servidor->nr_matricula,
        ];

        $jwt_header = array(
            "alg" => "HS256",
            "typ" => "JWT",
            "kid" => "4" // valor único que identifica a chave utilizada
        );


        $jwt = \Firebase\JWT\JWT::encode($jwt_payload, env('JWT_SECRET'), 'HS256', null, $jwt_header);


        return response()->json(['token' => $jwt]);
    }
}
