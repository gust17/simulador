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
        return $this->belongsTo(Pessoa::class,'cpf','nr_cpf');
    }
}
