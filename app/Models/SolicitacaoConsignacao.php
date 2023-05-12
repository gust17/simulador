<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitacaoConsignacao extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'solicitacao_consignacao';

    protected $primaryKey = 'cd_solicitacao';


    public function servidor()
    {
        return $this->belongsTo(Servidor::class, 'cd_servidor', 'cd_servidor');
    }

    public function verba()
    {

        return $this->hasOne(Verba::class, 'cd_consignante', 'cd_consignante')
            ->where('cd_consignataria', $this->cd_consignataria)
            ->where('cd_averbador', $this->cd_averbador)
            ->where('cd_verba', $this->cd_verba);
    }


    public function getVerbaReal()
    {
        $verba = Verba::
        where('cd_consignataria', $this->attributes['cd_consignataria'])
            ->where('cd_averbador', $this->attributes['cd_averbador'])
            ->where('cd_verba', $this->attributes['cd_verba'])
            ->where('cd_consignante', $this->attributes['cd_consignante'])
            ->first();


        return $verba;


    }

    public function getVerbaReal2($ds_verba)
    {
        $verba = Verba::where('ds_verba', $this->attributes['ds_verba'])->first();


        if (empty($verba)) {
            dd($this->attributes['cd_verba']);
        } else {
            return $verba->ds_verba;
        }
    }


    public function movimentacaos()
    {
        return $this->hasMany(MovimentacaoParcela::class, 'cd_solicitacao', 'cd_solicitacao');
    }

}
