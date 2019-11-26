<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifyUser extends Model
{
	protected $table 	= 'verify_users';
    protected $guarded 	= [];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
