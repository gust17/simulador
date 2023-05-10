<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

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

        $conveniosIds = $user->servidor->consignante->convenios->pluck('consignataria.cd_consignataria');
        $taxas = \App\Models\Taxas::whereIn('consignataria_cd_consignataria', $conveniosIds)
            ->where('prazo', $request->prazo)
            ->orderBy('prazo')
            ->orderBy('taxa', 'asc')
            ->get();

        $hoje = Carbon::now();

        $taxas = $taxas->reject(function ($taxa) use ($hoje) {
            $regra = $taxa->regra;
            if (isNull($regra->fim)) {
                $fim = $hoje;
            } else {
                $fim = Carbon::parse($regra->fim);
            }
            $inicio = Carbon::parse($regra->inicio);

            return !$hoje->between($inicio, $fim);
        });
        if ($taxas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Não foram encontradas taxas de juros para o prazo especificado',
                'bancos' => [],
            ]);
        }

        $dataProximoMes = \Carbon\Carbon::now()->addMonth()->day(7);

        $consulta = $taxas->map(function ($taxa) use ($dataProximoMes) {
            return [
                'banco' => $taxa->consignataria->nm_fantasia,
                'codigo_do_banco' => $taxa->consignataria->codigo_do_banco,
                'primeiro_desconto' => $dataProximoMes->format('d/m/Y'),
                'taxa_de_juros' => $taxa->taxa,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Taxas de juros encontradas com sucesso',
            'bancos' => $consulta,
        ]);
    }

}
