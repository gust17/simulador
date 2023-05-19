<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Averbador extends Model
{
    use HasFactory;

    protected $connection = 'oracle';

    protected $table = 'averbador';

    protected $primaryKey = 'cd_averbador';
}
