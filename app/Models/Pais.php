<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table 	= 'pais';
    protected $fillable = ['nomePT', 'nomeEN', 'nomeES', 'codigo', 'iso', 'iso3'];
}
