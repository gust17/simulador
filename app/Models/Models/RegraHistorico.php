<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegraHistorico extends Model
{
    use HasFactory;

    protected $table = 'regra_historico';

    protected $fillable = [
        'regra_id',
        'name',
        'inicio',
        'fim',
        'consignataria_cd_consignataria',
        'evento',
        'usuario',
    ];

}
