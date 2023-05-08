<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioAcesso extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'usuario_acesso';


    protected $primaryKey = 'id_usuario_acesso';


    public function servidor()
    {
        return $this->belongsTo(Servidor::class, 'cd_servidor', 'cd_servidor');
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'cd_pessoa', 'cd_pessoa');
    }

    public function usersistema()
    {
        return $this->belongsTo(UserSistema::class, 'cd_usuario', 'cd_usuario');
    }

}
