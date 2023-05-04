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
        return $this->belongsTo(Consignataria::class,'cd_consignataria','cd_consignataria');
    }
}
