<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxas extends Model
{
    use HasFactory;

    protected $fillable = [
        'prazo',
        'taxa',
        'consignataria_cd_consignataria',
        'consignante_cd_consignante',
        'regra_id'
    ];


    public function consignataria()
    {
        return $this->belongsTo(Consignataria::class,'consignataria_cd_consignataria','cd_consignataria');
    }
}
