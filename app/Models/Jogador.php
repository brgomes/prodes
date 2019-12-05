<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jogador extends Model
{
    public $timestamps = false;

    protected $table 	= 'jogador';
    protected $fillable = ['liga_id', 'usuario_id', 'admin', 'rodadasjogadas', 'rodadasvencidas',
        'pontosdisputados', 'pontosganhos', 'posicao', 'aproveitamento', 'created_at'];

    public function liga()
    {
        return $this->belongsTo(Liga::class)->with(['rodadas', 'jogadores']);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function getAproveitamentofAttribute()
    {
        return number_format($this->aproveitamento, 1);
    }
}
