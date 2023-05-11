<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    return view('simulador');
    $users = \App\Models\UserSistema::all();
    $user = \App\Models\UserSistema::find(458);

    dd($user->pessoa->servidors);
    $convenios = \App\Models\Convenio::where('cd_consignataria', 1)->get();
    // dd($convenios[0]->consignataria);
    dd($user->consignataria->convenios[0]->consignante->nm_consignante);

});

Route::post('consulta', function (\Illuminate\Http\Request $request) {
    //dd($request->all());
    $request['prazo'] = 25;
    $taxas = \App\Models\Taxas::where('prazo', '>=', $request->prazo)
        ->orderBy('prazo')
        ->orderBy('taxa', 'asc')
        ->get();

    $consulta = [];
    foreach ($taxas as $taxa) {
        dd($taxa->consignataria);
        $consulta[] = ['banco' => $taxa->consignataria->nm_fantasia, 'codigo_do_banco', 'taxa_de_juros' => $taxa->taxa];
    }
    dd($consulta);
});

Route::get('gravapadrao', function () {

    $grava = [
        'prazo' => 36,
        'taxa' => 0.78,
        'consignataria_cd_consignataria' => 14,
        'consignante_cd_consignante' => 9,
    ];


    \App\Models\Taxas::create($grava);

});
Route::get('testesolicita', function () {

    $pessoa = \App\Models\Pessoa::where('nr_cpf', 63030578291)->first();

    // dd($pessoa->servidores->with('solicitacaos'));
//
    /*
    foreach ($pessoa->servidores as $servidor) {
        dd($servidor->solicitacaos->where('cd_consignataria', 35)->whereIn('id_situacao_solicitacao', [505, 510]));
    }  */
    $pessoa = \App\Models\Pessoa::where('nr_cpf', 63030578291)->with(['servidores.solicitacaos' => function ($query) {
        $query->where('cd_consignataria', 35)->whereIn('id_situacao_solicitacao', [505, 510]);
    }])->first();

    dd($pessoa->servidores->flatMap(function ($servidor) {
        return $servidor->solicitacaos;
    }));


    $solicitacaos = \App\Models\SolicitacaoConsignacao::whereHas('servidor')->with('servidor')->where('cd_consignataria', 35)
        ->where('cd_consignante', 32)
        ->get();

    $temporaria = DB::connection('oracle')->table('tb_analise_descontos')->get();

    dd($temporaria);
    dd($solicitacaos->toArray());
});


Route::get('consultabusca', function () {
    /*
    $fileContents = file_get_contents('arquivo.txt');

    $lines = explode("\n", $fileContents);

    $consulta = [];

    foreach ($lines as $line) {

        $grava = array(
            'matricula' => (trim(substr($line, 0, 20))),
            'cpf' => (trim(substr($line, 20, 11))),
            'cod_verba' => (trim(substr($line, 31, 10))),
            'valor_solicitado' => floatval(str_insert(trim(substr($line, 41, 15)), ".", -2)),
            'valor_realizado' => floatval(str_insert(trim(substr($line, 56, 15)), ".", -2)),
            'motivo' => (trim(substr($line, 71, 50))),
            'mes' => (trim(substr($line, 121, 2))),
            'ano' => (trim(substr($line, 123, 4)))
        );

        \App\Models\Arquivo::create($grava);


    }

    */


    /*$consolida = collect($consulta)->flatMap(function ($item) {
        $pessoa = \App\Models\Pessoa::where('nr_cpf', $item['cpf'])->first();
        if (!$pessoa) {
            return [];
        }
        $servidor = $pessoa->servidores->where('nr_matricula', $item['matricula'])->first();
        if (!$servidor) {
            return [];
        }

        $solicitacoes = $servidor->solicitacaos()->whereHas('movimentacaos', function ($query) use ($item) {
            $query->where('folha_referencia', $item['ano'] . $item['mes']);
        })->get();

        return $solicitacoes->filter(function ($solicitacoe) use ($item) {
            return $solicitacoe->getVerbaReal() == $item['cod_verba'];
        });
    })->flatten();

    dd($consolida);*/


    /*
        $consolida = [];

        foreach ($consulta as $item) {


            $pessoa = \App\Models\Pessoa::where('nr_cpf', $item['cpf'])->first();

            $servidor = $pessoa->servidores->where('nr_matricula', $item['matricula'])->first();

            //  $solitacaos = $servidor->solicitacaos;

            $mesano = ($item['ano'] . $item['mes']);


            $solicitacoes = \App\Models\SolicitacaoConsignacao::whereHas('servidor', function ($query) use ($item) {

                $query->where('nr_matricula', $item['matricula'])
                    ->whereHas('pessoa', function ($query) use ($item) {
                        $query->where('nr_cpf', $item['cpf']);
                    });
            })
                ->whereHas('movimentacaos', function ($query) use ($item) {
                    $query->where('folha_referencia', $item['ano'] . $item['mes']);
                })
                ->get();

            foreach ($solicitacoes as $solicitacoe) {
                if ($solicitacoe->getVerbaReal() == $item['cod_verba']) {
                    $consolida[] = $solicitacoe;
                }


            }


        }
        dd($consolida);
    */

    $verbas = \App\Models\Verba::where('cd_averbador', 80)->get();

    $arquivos = \App\Models\Arquivo::limit(3)->get();
    return view('teste', compact('arquivos','verbas'));

});
