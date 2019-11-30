<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Palpite extends Model
{
    protected $table 	= 'usuario_palpite';
    protected $fillable = ['partida_id', 'usuario_id', 'palpite', 'pontos', 'created_by'];
}
