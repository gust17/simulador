<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametrosTipoConsignante extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = 'PRM_TIPO_CONSIG_AUTORIZADA';


    protected $primaryKey = 'cd_tipo_consignacao';
}
