<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegimeVinculoServidor extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = "regime_vinculo_servidor";

    protected $primaryKey = 'id_regime';
}
