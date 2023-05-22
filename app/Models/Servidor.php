<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    use HasFactory;


    protected $connection = 'oracle';


    protected $table = 'servidor';

    protected $primaryKey = 'cd_servidor';


    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'cd_pessoa', 'cd_pessoa');
    }

    public function consignante()
    {
        return $this->belongsTo(Consignante::class, 'cd_consignante', 'cd_consignante');
    }

    public function averbador()
    {
        return $this->belongsTo(Averbador::class, 'cd_averbador', 'cd_averbador');
    }

    public function UsuarioAcesso()
    {
        return $this->hasOne(UsuarioAcesso::class, 'cd_servidor', 'cd_servidor');
    }

    public function solicitacaos()
    {
        return $this->hasMany(SolicitacaoConsignacao::class, 'cd_servidor', 'cd_servidor');
    }

    public function regime()
    {
        return $this->belongsTo(RegimeVinculoServidor::class, 'cd_regime_consignante', 'id_regime');
    }

    public function categoria()
    {
        return $this->belongsTo(SituacaoTrabalho::class, 'cd_categoria_consignante', 'id_situacao_trabalho');
    }

    public function margems()
    {
        return $this->hasMany(MargemServidor::class, 'cd_servidor', 'cd_servidor');
    }

    public function margemAtual()
    {
        $hoje = Carbon::now()->addDays(10);

       // dd($hoje);
        $atual = $this->margems;

        $filtrado = $atual->filter(function ($item) use ($hoje) {
            $dtInicial = Carbon::createFromFormat('YmdHi', $item->dt_inicial);
            return $hoje >= $dtInicial;
        });

        $ordenado = $filtrado->sortBy(function ($item) use ($hoje) {
            $dtInicial = Carbon::createFromFormat('YmdHi', $item->dt_inicial);
            return $dtInicial->diffInMinutes($hoje);
        });

        $itemMaisProximo = $ordenado->first();

       return $itemMaisProximo;


    }
}
