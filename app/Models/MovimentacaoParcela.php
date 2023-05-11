<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoParcela extends Model
{
    use HasFactory;


    protected $connection = 'oracle';

    protected $table = 'movimentacao_parcela';



}
