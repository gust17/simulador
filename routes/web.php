<?php

use App\Models\Consignante;
use App\Models\Consignataria;
use App\Models\Regra;
use Carbon\Carbon;
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

Route::get('consultabusca', function () {
    $arquivos = \App\Models\Arquivo::limit(1000)->get();

    //dd($arquivos->pluck('cod_verba')->toArray());
    $verbas = \App\Models\Verba::where('cd_averbador', 80)->get();


    return view('teste', compact('arquivos', 'verbas'));


});

Route::get('testebusca', function () {


    //$user = \App\Models\Pessoa::where("nr_cpf",'36021466004')->first();
    //dd($user->usuarioAcesso->userSistema);
    $user = \App\Models\Servidor::where('nr_matricula', '66102022')->first();
    dd($user->pessoa->UsuarioAcesso->usersistema, $user, $user->pessoa, $user->consignante);

    $consignatarias_ids = Consignataria::pluck('cd_consignataria')->toArray();

    //dd($consignatarias_ids);
    $regras = [];

    for ($i = 0; $i < 2000; $i++) {
        $consignataria_id = $consignatarias_ids[array_rand($consignatarias_ids)];

        $regras[] = [
            'consignataria_cd_consignataria' => $consignataria_id,
            'name' => 'Regra ' . ($i + 1),
            'inicio' => Carbon::now(),
            'fim' => Carbon::now()->addDays(30),
        ];
    }

    DB::table('regras')->insert($regras);

    $consignatarias = Consignataria::pluck('cd_consignataria')->toArray();

    $consignantes = Consignante::where('cd_consignante', 40)->pluck('cd_consignante')->toArray();
    $regras = Regra::pluck('id')->toArray();

    //dd($regras);

    $taxas = [];

    for ($i = 0; $i < 1000; $i++) {
        $consignataria_id = $consignatarias[array_rand($consignatarias)];
        $consignante_id = 40;
        $regra_id = $regras[array_rand($regras)];
        $prazo = rand(1, 40);
        $taxa = rand(1, 1000) / 100;

        $taxas[] = [
            'consignataria_cd_consignataria' => $consignataria_id,
            'consignante_cd_consignante' => $consignante_id,
            'regra_id' => $regra_id,
            'prazo' => $prazo,
            'taxa' => $taxa,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    DB::table('taxas')->insert($taxas);


    $busca = \App\Models\Taxas::where('consignataria_cd_consignataria', 40)->get();

    dd($busca);
});


Route::get('enviarteste', function () {
    return view('enviar');
});
Route::post('enviarteste', function (\Illuminate\Http\Request $request) {
    /*  if ($request->hasFile('arquivo')) {
          $file = $request->file('arquivo');


          $path = $file->store('infoconsig', 'minio');
          // Ou, se você quiser definir um nome personalizado para o arquivo:
          // $path = $file->storeAs('folder_name', 'custom_filename.jpg', 'minio');

          // Exibindo o caminho do arquivo
          dd($path);
      } */

    $bucket = 'infoconsig';
    $directory = '';

    $files = Storage::disk('minio')->files($bucket . '/' . $directory);

    //dd($files);
    foreach ($files as $file) {
        echo $file . PHP_EOL;
    }

    /*
    $filePath = 'wgeW3o15iDqm3FDESUxdw3WHE6sPMCr0jF6xyoi6.pdf';

    $url = Storage::disk('minio')->url($filePath);

    echo $url;
*/

});

Route::get('consignante/{id}', function ($id) {
    $regras = \App\Models\Regra::where('consignataria_cd_consignataria', $id)->get()->toArray();

    //dd($regras);


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


//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('buscapessoa/{inicio}/{fim}', function ($inicio,$fim) {
    $pessoa = \App\Models\Pessoa::whereBetween('cd_pessoa', [$inicio, $fim])->get();
    dd($pessoa->toArray());
    //dd($pessoa->toArray());
});
