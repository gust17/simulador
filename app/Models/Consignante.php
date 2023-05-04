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
}
