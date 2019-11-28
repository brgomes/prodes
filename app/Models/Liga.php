<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liga extends Model
{
    protected $table 	= 'liga';
    protected $fillable = ['nome', 'datainicio', 'datafim', 'dataconsolidacao', 'created_by', 'updated_by'];

    public function rodadas()
    {
        return $this->hasMany(Rodada::class);
    }
}
