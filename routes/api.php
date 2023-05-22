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

Route::post('auth', [\App\Http\Controllers\Api\UsuarioAuthController::class, 'auth']);

Route::post('consulta/taxas', [\App\Http\Controllers\Api\ConsultasController::class, 'taxas'])->middleware('JWTMiddleware');

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


Route::get('/pesquisapessoa', function (Request $request) {


    $query = $request->get('q');

    $data = \App\Models\Pessoa::where('nr_cpf', 'LIKE', "%$query%")->get();

    return response()->json($data);
});

Route::get('/pesquisamatricula/{id}', function ($id) {


    $matriculas = \App\Models\Servidor::where('cd_pessoa', $id)->where('id_ativo', '!=', 0)->get();


    $retorno = [];
    foreach ($matriculas as $matricula) {

        $retorno[] = [

            'matricula' => $matricula->nr_matricula,
            'id' => $matricula->cd_servidor,
            'consignante' => $matricula->consignante->nm_consignante,
            'averbador' => $matricula->averbador->nm_averbador,
            'nome' => $matricula->pessoa->nm_pessoa,
            'data Admissão' => valida_data($matricula->dt_admissao)->format('d-m-Y'),
            'regime' => $matricula->regime->ds_regime_vinculo_trab,
            'categoria' => $matricula->categoria->ds_situacao_categoria,

        ];

    }


    return response()->json($retorno);
});


Route::get('/matricula/{id}', function ($id) {


    $matricula = \App\Models\Servidor::find($id);


    $retorno = [

        'matricula' => $matricula->nr_matricula,
        'id' => $matricula->cd_servidor,
        'consignante' => $matricula->consignante->nm_consignante,
        'averbador' => $matricula->averbador->nm_averbador,
        'nome' => $matricula->pessoa->nm_pessoa,
        'data' => valida_data($matricula->dt_admissao)->format('d-m-Y'),
        'regime' => $matricula->regime->ds_regime_vinculo_trab,
        'categoria' => $matricula->categoria->ds_situacao_categoria,

    ];


    return response()->json($retorno);
});


Route::get('/minhamargem/{id}/consignataria/{consignataria}', function ($id, $consignataria) {


    $consignataria = \App\Models\Consignataria::find($consignataria);


    $matricula = \App\Models\Servidor::find($id);

    $results = DB::connection('oracle')->table('v_resumo_utilizacao_margens')->where('cd_servidor', $matricula->cd_servidor)->where('cd_tipo_consignacao', $consignataria->tipo_consignacao)->first();


    $valor_utilizado = $results->vl_mu_exclusiva + $results->vl_mu_compartilhada;

    $valor_disponivel = $results->vl_mr_geral_calculada - $valor_utilizado;

    $livreporc = get_porcentagem($results->vl_mr_geral_calculada, $valor_disponivel);
    $utilizadaporc = get_porcentagem($results->vl_mr_geral_calculada, $valor_utilizado);


    $retorno = [

        'valor_utilizado' => $valor_utilizado,
        'valor_disponivel' => $valor_disponivel,
        'livreporc' => $livreporc,
        'utilizadaporc' => $utilizadaporc

    ];

    return response()->json($retorno);
});


Route::get('/minhamargem/{id}/solicitacaos/{consignataria}', function ($id, $consignataria) {


    $consignataria = \App\Models\Consignataria::find($consignataria);


    $matricula = \App\Models\Servidor::find($id);

    $solicitacaos = $matricula->solicitacaos->where('cd_consignataria',$consignataria->cd_consignataria);
   // dd($solicitacaos);



    return response()->json($solicitacaos);
});


Route::get('/buscaconsgnataria/{id}', function ($id) {
    $consignataria = \App\Models\Consignataria::find($id);
    return response()->json($consignataria);
});

