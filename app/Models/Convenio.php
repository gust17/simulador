<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = "vinculo_consignat_consignante";


    public function consignataria()
    {
        return $this->belongsTo(Consignataria::class, 'cd_consignataria', 'cd_consignataria');
    }

    public function consignante()
    {
        return $this->belongsTo(Consignante::class,'cd_consignante','cd_consignante');
    }
}
