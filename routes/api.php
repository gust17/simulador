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
            'data'=> $usuario
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

