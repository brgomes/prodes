<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Palpite extends Model
{
    protected $table        = 'usuario_palpite';
    protected $fillable     = ['usuario_id', 'partida_id', 'palpite', 'pontos'];

    public function usuario()
    {
    	return $this->belongsTo(Usuario::class);
    }

    public function partida()
    {
    	return $this->belongsTo(Partida::class);
    }
}
