<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxas extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $connection = 'mysql';

    protected $fillable = [
        'prazo',
        'taxa',
        'consignataria_cd_consignataria',
        'consignante_cd_consignante',
        'regra_id',
        'usuario'
    ];


    public function consignataria()
    {
        return $this->belongsTo(Consignataria::class, 'consignataria_cd_consignataria', 'cd_consignataria');
    }

    public function consignante()
    {
        return $this->belongsTo(Consignante::class, 'consignante_cd_consignante', 'cd_consignante');
    }

    public function regra()
    {
        return $this->belongsTo(Regra::class);
    }

    public function nomeUsuario()
    {
        return $this->belongsTo(UserSistema::class,'usuario','cd_usuario');
    }
}
