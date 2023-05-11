<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verba extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = 'verbas_avb_csg';

    protected $primaryKey = 'id_verba';
}
