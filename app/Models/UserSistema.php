<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSistema extends Model
{
    use HasFactory;


    protected $connection = 'oracle';


    protected $table = 'usuario';

    protected $primaryKey = 'cd_usuario';

    public function consignataria()
    {
        return $this->belongsTo(Consignataria::class, 'cd_consignataria', 'cd_consignataria');
    }


    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'cd_pessoa', 'cd_pessoa');

    }

    public function UsuarioAcesso()
    {
        return $this->hasOne(UsuarioAcesso::class,'cd_usuario','cd_usuario');
    }
}
