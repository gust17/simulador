<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('consulta', function (Request $request) {
    $taxas = \App\Models\Taxas::where('prazo', '>=', $request->prazo)
        ->orderBy('prazo')
        ->orderBy('taxa', 'asc')
        ->get();

    $consulta = [];
    foreach ($taxas as $taxa) {
        $consulta[] = [
            'banco' => $taxa->consignataria->nm_fantasia,
            'codigo_do_banco' => $taxa->consignataria->codigo_do_banco,
            'taxa_de_juros' => $taxa->taxa
        ];
    }

    if (count($consulta) > 0) {
        $response = [
            'success' => true,
            'message' => 'Taxas de juros encontradas com sucesso',
            'bancos' => $consulta
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Não foram encontradas taxas de juros para o prazo especificado',
            'bancos' => []
        ];
    }

    return response()->json($response);
});

Route::post('valida/user', function (Request $request) {
    $cd_usuario = $request->input('cd_usuario');


    // Lógica para validar o cd_usuario
    $usuario = \App\Models\UserSistema::find($cd_usuario);
    if ($usuario) {
        $response = [
            'success' => true,
            'message' => 'Usuário validado com sucesso',
            'data' => $usuario
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Usuário inválido',
            'data'
        ];
    }

    return response()->json($response);
});

Route::post('busca/convenios', function (Request $request) {
    $cd_usuario = $request->input('cd_usuario');


    // Lógica para validar o cd_usuario
    $usuario = \App\Models\UserSistema::find($cd_usuario);

    //return $usuario->consignataria->convenios;
    if ($usuario) {
        $data = [];
        foreach ($usuario->consignataria->convenios as $convenio) {
            $data[] = ['convenio' => $convenio->consignante->nm_consignante, 'id' => $convenio->consignante->cd_consignante];
        }


        $response = [
            'success' => true,
            'message' => 'Usuário validado com sucesso',
            'data' => $data
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Usuário inválido',
            'data'
        ];
    }

    return response()->json($response);
});

Route::post('auth', function (Request $request) {
    $validator = Validator::make($request->all(), [
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

    $servidor = \App\Models\Servidor::all();

    //dd($servidor[0]);

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
        "cd_servidor" => $userSistema->cd_servidor,
    ];

    $jwt_header = array(
        "alg" => "HS256",
        "typ" => "JWT",
        "kid" => "4" // valor único que identifica a chave utilizada
    );



    $jwt = \Firebase\JWT\JWT::encode($jwt_payload, env('JWT_SECRET'), 'HS256', null, $jwt_header);


    return response()->json(['token' => $jwt]);
});

Route::post('minhasmatriculas', function (Request $request) {
    $authHeader = $request->header('Authorization');


    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return response()->json(['error' => 'Token não encontrado no cabeçalho Authorization'], 401);
    }


    $jwt = $matches[1];

    $key = new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256');

    try {
        $payload = \Firebase\JWT\JWT::decode($jwt, $key);
    } catch (Exception $e) {
        return response()->json(['error' => 'Token inválido'], 401);
    }

    $userId = $payload->user_id;
    $user = \App\Models\UsuarioAcesso::find($userId);

    if (!$user) {
        return response()->json(['error' => 'Usuário não encontrado'], 401);
    }

    $convenios = \App\Models\Convenio::all();

    return response()->json($convenios);
});


Route::post('convenios', function (Request $request) {
    $authHeader = $request->header('Authorization');


    if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
        return response()->json(['error' => 'Token não encontrado no cabeçalho Authorization'], 401);
    }


    $jwt = $matches[1];

    $key = new \Firebase\JWT\Key(env('JWT_SECRET'), 'HS256');

    try {
        $payload = \Firebase\JWT\JWT::decode($jwt, $key);
    } catch (Exception $e) {
        return response()->json(['error' => 'Token inválido'], 401);
    }

    $userId = $payload->user_id;
    $user = \App\Models\UsuarioAcesso::find($userId);

    if (!$user) {
        return response()->json(['error' => 'Usuário não encontrado'], 401);
    }

    $resultadoConvenio = [];

    foreach ($user->servidor->consignante->convenios as $convenio) {
        $resultadoConvenio[] = ['nome' => $convenio->consignataria->nm_fantasia, 'id' => $convenio->consignataria->cd_consignataria];
        //($convenio->consignataria->nm_fantasia);
    }

    $convenios = $user->servidor->consignante->convenios;

    $resultadoConvenio = $convenios->map(function ($convenio) {
        return [
            'nome' => $convenio->consignataria->nm_fantasia,
            'id' => $convenio->consignataria->cd_consignataria
        ];
    });
    return response()->json($resultadoConvenio);
});

