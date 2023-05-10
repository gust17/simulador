<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    public function taxas(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'valor' => 'required',
            'prazo' => 'required',

        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $user = \App\Services\UsuarioServiceAuth::authenticateUser($request);

        if (!$user) {
            return response()->json(['error' => 'Usuário não encontrado'], 401);
        }


        $resultadoConvenio = [];

        foreach ($user->servidor->consignante->convenios as $convenio) {
            $resultadoConvenio[] = ['nome' => $convenio->consignataria->nm_fantasia, 'id' => $convenio->consignataria->cd_consignataria];

        }

        $convenios = $user->servidor->consignante->convenios;

        $resultadoConvenio = $convenios->map(function ($convenio) {
            return [
                'id' => $convenio->consignataria->cd_consignataria
            ];
        });


        $taxas = \App\Models\Taxas::whereIn('consignataria_cd_consignataria', $resultadoConvenio->pluck('id'))->where('prazo', '=', $request->prazo)
            ->orderBy('prazo')
            ->orderBy('taxa', 'asc')
            ->get();

        $dataAtual = \Carbon\Carbon::now();


        $dataProximoMes = $dataAtual->addMonth();


        $dataDiaSete = $dataProximoMes->day(7);


        $consulta = [];
        foreach ($taxas as $taxa) {
            $consulta[] = [
                'banco' => $taxa->consignataria->nm_fantasia,
                'codigo_do_banco' => $taxa->consignataria->codigo_do_banco,
                'primeiro_desconto' => $dataDiaSete->format('d/m/Y'),
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
    }
}
