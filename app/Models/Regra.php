<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regra extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'consignataria_cd_consignataria',
        'name',
        'inicio',
        'fim',
        'usuario'
    ];


    public function nomeUsuario()
    {
        return $this->belongsTo(UserSistema::class,'usuario','cd_usuario');
    }
}
