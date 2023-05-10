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
}
