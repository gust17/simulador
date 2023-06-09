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

    public function consignanteMaster()
    {
        return $this->belongsTo(ConsignanteMaster::class, 'cd_consignante_master', 'cd_consignante_master');
    }

    public function prmconsignante()
    {
        return $this->hasOne(PrmConsignante::class, 'cd_consignante', 'cd_consignante');
    }

    public function taxas()
    {
        return $this->hasMany(Taxas::class);
    }
}
