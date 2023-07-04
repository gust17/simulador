<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_sistema_cd_usuario',
        'documento',
        'name',
        'assunto',
        'tipo',
        'ids'
    ];
}
