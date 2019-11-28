<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigaRodada extends Model
{
    protected $table 	= 'liga_rodada';
    protected $fillable = ['liga_id', 'numero', 'dataconsolidacao', 'created_by', 'updated_by'];
}
