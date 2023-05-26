<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regra extends Model
{
    use HasFactory;


    protected $fillable = [
        'consignataria_cd_consignataria',
        'name',
        'inicio',
        'fim'
    ];
}
