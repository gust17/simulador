<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxaHistorico extends Model
{
    use HasFactory;
    protected $table = 'taxa_historico';


    protected $fillable = [
        'prazo',
        'taxa',
        'taxa_id',
        'consignataria_cd_consignataria',
        'consignante_cd_consignante',
        'regra_id',
        'evento',
        'usuario'
    ];
}
