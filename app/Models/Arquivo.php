<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arquivo extends Model
{
    use HasFactory;

    protected $fillable = [

        'matricula',
        'cpf',
        'cod_verba',
        'valor_solicitado',
        'valor_realizado',
        'motivo',
        'mes',
        'ano'

    ];


    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'cpf', 'nr_cpf');
    }

    public function servidor()
    {

        return $this->belongsTo(Servidor::class, 'matricula', 'nr_matricula')
            ->where('cd_pessoa', $this->pessoa->cd_pessoa);
    }


    public function solicitacao()
    {
        $solicitas = SolicitacaoConsignacao::where('cd_servidor', $this->servidor->cd_servidor)->get();

        if ($solicitas->count() > 0) {
            foreach ($solicitas as $solicita) {
                if (!empty($this->attributes['cod_verba'])) {

                    if (isset($solicita->verba)) {
                        if ($solicita->verba->ds_verba == $this->attributes['cod_verba']) {
                            $busca = $solicita;
                        }
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            }


            return $busca;
        } else {
            return 0;
        }
        return;

    }
}
