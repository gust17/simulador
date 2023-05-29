<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsignanteMaster extends Model
{
    use HasFactory;

    protected $connection = 'oracle';


    protected $table = 'consignante_master';

    protected $primaryKey = 'cd_consignante_master';


    public function consignantes()
    {
        return $this->hasMany(Consignante::class,'cd_consignante_master','cd_consignante_master');
    }

    public function taxas()
    {
        return $this->hasMany(Taxas::class);
    }
}
