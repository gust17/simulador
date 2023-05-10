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

Route::post('auth', [\App\Http\Controllers\Api\UsuarioAuthController::class,'auth']);

Route::post('consulta/taxas', [\App\Http\Controllers\Api\ConsultasController::class,'taxas'])->middleware('JWTMiddleware');

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
})->middleware('JWTMiddleware');


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

