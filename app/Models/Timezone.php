<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table 	= 'timezone';
    protected $fillable = ['gmt', 'timezone', 'gmt_offset'];
}
