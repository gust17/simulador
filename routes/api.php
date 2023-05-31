<?php


use App\Models\Taxas;
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

    $query = limpa_corrige_cpf($query);

    $data = \App\Models\Pessoa::where('nr_cpf', 'LIKE', "%$query%")->get();

    return response()->json($data);
});

Route::get('/pesquisamatricula/{id}', function ($id) {


    $matriculas = \App\Models\Servidor::where('cd_pessoa', $id)->where('id_ativo', '!=', 0)->get();


    //dd($matriculas);
    $retorno = [];
    foreach ($matriculas as $matricula) {

        $retorno[] = [

            'matricula' => intval($matricula->nr_matricula),
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

        'matricula' => intval($matricula->nr_matricula),
        'id' => $matricula->cd_servidor,
        'consignante' => $matricula->consignante->nm_consignante,
        'averbador' => $matricula->averbador->nm_averbador,
        'nome' => $matricula->pessoa->nm_pessoa,
        'data' => valida_data($matricula->dt_admissao)->format('d/m/Y'),
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

        'valor_utilizado' => format_currency($valor_utilizado),
        'valor_disponivel' => format_currency($valor_disponivel),
        'livreporc' => $livreporc,
        'utilizadaporc' => $utilizadaporc

    ];

    return response()->json($retorno);
});


Route::get('/minhamargem/{id}/solicitacaos/{consignataria}', function ($id, $consignataria) {


    $consignataria = \App\Models\Consignataria::find($consignataria);


    $matricula = \App\Models\Servidor::find($id);
    $results = DB::connection('oracle')->table('v_resumo_utilizacao_margens')->where('cd_servidor', $matricula->cd_servidor)->where('cd_tipo_consignacao', $consignataria->tipo_consignacao)->first();
    $valor_utilizado = $results->vl_mu_exclusiva + $results->vl_mu_compartilhada;

    $valor_disponivel = $results->vl_mr_geral_calculada - $valor_utilizado;

    //dd($consignataria);
    $solicitacaos = $matricula->solicitacaos->where('cd_consignataria', $consignataria->cd_consignataria)->toArray();
    // dd($solicitacaos);

    $retorno = [];
    foreach ($solicitacaos as $solicitacao) {

        $retorno[] = ['data' => valida_data($solicitacao['dt_solicitacao'])->format('d/m/y'), 'solicitacao' => $solicitacao['cd_solicitacao'], 'vl_margem' => format_currency($solicitacao['vl_parcela']), 'total_max' => format_currency($valor_disponivel)];
    }


    // dd($solicitacaos);
    return response()->json($retorno);
});


Route::get('/buscaconsgnataria/{id}', function ($id) {
    $consignataria = \App\Models\Consignataria::find($id);
    return response()->json($consignataria);
});

Route::post('dadostaxas', function (Request $request) {


    $dados = $request->all();

    // return $dados;

    //$consignataria = json_decode($dados['consignataria'], false);

    $resultado = [
        'consignataria_cd_consignataria' => intval($request['consignataria']),
        'name' => $request['nomeTabela'],
        'inicio' => $request['dataInicial'],
        'fim' => $request['dataFinal']
    ];
    $regra = \App\Models\Regra::create($resultado);
    //return $regra;
    foreach ($request->prefeituras as $prefeitura) {
        foreach ($request->taxas as $taxa) {
            $grava = [
                'taxa' => floatval($taxa['valor']),
                'prazo' => intval($taxa['taxas']),
                'consignataria_cd_consignataria' => intval($request['consignataria']),
                'consignante_cd_consignante' => intval($prefeitura['id']),
                'regra_id' => $regra->id,
            ];

            \App\Models\Taxas::create($grava);
        }
    }

    return response()->json('success');


});


Route::get('consultabuscataxas/{consignataria}', function ($consignantaria) {

    // return 'oi';

    \Illuminate\Support\Facades\DB::statement("SET sql_mode = ''");

    $consignantes = \App\Models\Consignante::all()->pluck('cd_consignante')->toArray();

    $taxas = \App\Models\Taxas::whereIn('consignante_cd_consignante', $consignantes)
        ->where('consignataria_cd_consignataria', $consignantaria)
        ->groupBy('regra_id', 'consignante_cd_consignante')
        ->distinct()
        ->get();

    // dd($taxasUnicas);


    $retorno = [];
    foreach ($taxas as $taxa) {
        $retorno[] = [
            'id' => $taxa->regra_id,
            'prazo' => $taxa->prazo,
            'taxa' => $taxa->taxa,
            'data_criacao' => $taxa->created_at->format('d/m/Y H:i:s'),
            'data_inicio' => \Carbon\Carbon::createFromFormat('Y-m-d', $taxa->regra->inicio)->format('d/m/Y'),
            'data_fim' => \Carbon\Carbon::createFromFormat('Y-m-d', $taxa->regra->fim)->format('d/m/Y'),
            'nome_tabela' => $taxa->regra->name,
            'consignante_master' => $taxa->consignante->consignanteMaster->nm_consignante_master,
            'consignante' => $taxa->consignante->nm_consignante,
            'consignante_id' => $taxa->consignante->cd_consignante
        ];
    }
    //  dd($retorno[0]);
    return response()->json($retorno);
});
Route::get('todasTabelas/{id}', function ($id) {
    $regras = \App\Models\Regra::where('consignataria_cd_consignataria', $id)->get()->toArray();

    if ($regras) {
        $data = [];


        $response = [
            'success' => true,
            'message' => 'Tabelas encontradas',
            'data' => $regras
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Tabelas nao encontradas',
            'data'
        ];
    }

    return response()->json($response);
});

Route::get('tabela-consignantes/{tabela}/{consignataria}', function ($tabela, $consignataria) {


    $consignantes = \App\Models\Taxas::where('regra_id', $tabela)
        ->where('consignataria_cd_consignataria', $consignataria)
        ->groupBy('consignante_cd_consignante')
        ->get(['consignante_cd_consignante'])->toArray();


    $consignantes = \App\Models\Consignante::whereIn('cd_consignante', $consignantes)->get()->toArray();

    // dd($consignantes);

    if ($consignantes) {
        $data = [];


        $response = [
            'success' => true,
            'message' => 'Consignantes encontradas',
            'data' => $consignantes
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Consignantes     nao encontradas',
            'data'
        ];
    }

    return response()->json($response);
});
Route::get('consulta-tabela-consignante/{tabela}/{consignante}', function ($tabela, $consignante) {

    $regra = \App\Models\Regra::find($tabela);
    $consignantename = \App\Models\Consignante::find($consignante);


    $taxas = \App\Models\Taxas::where('regra_id', $regra->id)->where('consignante_cd_consignante', $consignante)->get()->toArray();

    if ($taxas) {
        $data = [];


        $response = [
            'success' => true,
            'message' => 'Consignantes encontradas',
            'data' => ['taxas' => $taxas, 'nomeconsignante' => $consignantename->nm_fantasia]
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Consignantes nao encontradas',
            'data'
        ];
    }

    return response()->json($response);


});
Route::post('salvaralteracaotaxas', function (Request $request) {
    $dadosJson = $request->getContent(); // Obtém o JSON do corpo da solicitação
    $dados = json_decode($dadosJson, true); // Decodifica o JSON para um array associativo

    //dd($dados);
    // Acesso aos dados
    $datas = $dados['data'];


    foreach ($datas as $data) {
        //return $data;
        $taxa = \App\Models\Taxas::find($data['id']);
        $taxa->fill(['taxa' => floatval($data['taxa'])]);
        $taxa->save();
    }

    if (isset($dados['itemexclusaoTabela'])) {
        $deletados = $dados['itemexclusaoTabela'];

        // Verificar se $deletados é uma string e convertê-la em um array
        if (is_string($deletados)) {
            $deletados = explode(',', $deletados);
            $deletados = array_map('trim', $deletados); // Remover espaços em branco extras
        }

        $deletados = array_values($deletados); // Reindexar o array

        // Executar a lógica para excluir o registro com base no ID
        \App\Models\Taxas::destroy($deletados);
    }

    return response()->json(['message' => 'Cadastro com sucesso'], 200);
});
Route::get('deletaprazo/{id}', function ($id) {
    $taxa = \App\Models\Taxas::destroy($id);

    return response()->json(['message' => 'Deletado com sucesso'], 200);
});
