<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SituacaoTrabalho extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = 'situacao_trabalho';


    protected $primaryKey = 'id_situacao_trabalho';
}
