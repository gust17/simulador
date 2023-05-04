<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignataria extends Model
{
    use HasFactory;

    protected $connection = "oracle";

    protected $table = 'consignataria';

    protected $primaryKey = 'cd_consignataria';


    public function userSistemas()
    {
        return $this->hasMany(UserSistema::class, 'cd_consignataria', 'cd_consignataria');
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'cd_consignataria', 'cd_consignataria');
    }
}
