<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rodada extends Model
{
    protected $table 	= 'liga_rodada';
    protected $fillable = ['liga_id', 'numero', 'consolidado_at', 'created_by', 'updated_by'];
}
