<?php

namespace App\Models;

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

    public function UsuarioAcesso()
    {
        return $this->hasOne(UsuarioAcesso::class, 'cd_servidor', 'cd_servidor');
    }

    public function solicitacaos()
    {
        return $this->hasMany(SolicitacaoConsignacao::class, 'cd_servidor', 'cd_servidor');
    }

    public function recuperaVerba($item)
    {
        //
        //dd($item);

        $solicitacoes = $this->solicitacaos()->whereHas('movimentacaos', function ($query) use ($item) {
            $query->where('folha_referencia', $item->ano . $item->mes);
        })->get();



        return $solicitacoes->filter(function ($solicitacoe) use ($item) {
            return $solicitacoe->getVerbaReal() == $item->cod_verba;
        });
    }
}
