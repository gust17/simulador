<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servidor extends Model
{
    use HasFactory;


    protected $connection = 'oracle';


    protected $table = 'servidor';

    protected $primaryKey = 'cd_servidor';


    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'cd_pessoa', 'cd_pessoa');
    }

    public function consignante()
    {
        return $this->belongsTo(Consignante::class, 'cd_consignante', 'cd_consignante');
    }

    public function UsuarioAcesso()
    {
        return $this->hasOne(UsuarioAcesso::class, 'cd_servidor', 'cd_servidor');
    }
}
