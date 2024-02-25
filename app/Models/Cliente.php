<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
        // campos específicos de cliente, si los hay
    ];

    public function auths()
    {
        return $this->belongsTo('App\Models\Auths', 'auth_id');
    }
}

