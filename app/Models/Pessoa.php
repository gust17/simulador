<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $connection = 'oracle';


    protected $table = 'pessoa';

    protected $primaryKey = 'cd_pessoa';


    public function servidores()
    {
        return $this->hasMany(Servidor::class,'cd_pessoa','cd_pessoa');
    }

    public function UsuarioAcesso()
    {
        return $this->hasOne(UsuarioAcesso::class,'cd_pessoa','cd_pessoa');
    }


}
