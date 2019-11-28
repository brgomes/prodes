<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table 	= 'pais';
    protected $fillable = ['nomePT', 'nomeEN', 'nomeES', 'codigo', 'iso', 'iso3'];

    public function getNomeAttribute()
    {
    	if (app()->getLocale() == 'pt-BR') {
    		return $this->nomePT;
    	} elseif (app()->getLocale() == 'es') {
    		return $this->nomeES;
    	} else {
    		return $this->nomeEN;
    	}
    }
}
