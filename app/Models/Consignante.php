<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignante extends Model
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $table = 'consignante';

    protected $primaryKey = 'cd_consignante';

    public function convenios()
    {
        return $this->hasMany(Convenio::class, 'cd_consignante', 'cd_consignante');
    }

    public function parametrostipoativo()
    {
        return $this->belongsTo(ParametrosTipoConsignante::class, 'cd_consignante', 'cd_consignante');
    }

    public function prmconsignante()
    {
        return $this->hasOne(PrmConsignante::class,'cd_consignante', 'cd_consignante');
    }
}
